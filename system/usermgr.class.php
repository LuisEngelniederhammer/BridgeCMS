<?php

namespace system
{

	require_once __DIR__ . '/core.class.php';
	require_once __DIR__ . '/database.class.php';
	require_once __DIR__ . '/rolemgr.class.php';


	class User
	{
		public $id;
		public $uid;
		public $username;
		public $password;
		public $email;
		public $roles;
		public $register_ip;
		// disallow c-tor
		private function __construct()
		{
		}

		static protected function init($mixed = null)
		{
			if ($mixed === null)
			{
				return false;
			}
			elseif (is_array ( $mixed ))
			{
				$tmpInstance = new self ();
				$tmpInstance->id = $mixed ['id'];
				$tmpInstance->uid = $mixed ['uid'];
				$tmpInstance->username = $mixed ['username'];
				$tmpInstance->password = $mixed ['password'];
				$tmpInstance->email = $mixed ['email'];
				$tmpInstance->roles = $mixed ['roles'];
				$tmpInstance->register_ip = $mixed ['register_ip'];
				$tmpInstance->register_date = $mixed ['register_date'];
				$tmpInstance->warnings = $mixed ['warnings'];
				$tmpInstance->banned = $mixed ['banned'];
				$tmpInstance->restrictions = $mixed ['restrictions'];
				return $tmpInstance;
			}
			else
			{
				return false;
			}
		}

		static public function load(string $uid)
		{
			$request = databaseManager::getDB ()->prepareSQL ( "SELECT * FROM " . database::users . " WHERE uid = '$uid'" );
			$user = $request->fetchArray ();
			return self::init ( $user );
		}

		static public function loadByName(string $name)
		{
			$request = databaseManager::getDB ()->prepareSQL ( "SELECT * FROM " . database::users . " WHERE username = '$name'" );
			$user = $request->fetchArray ();
			return self::init ( $user );
		}

		public function __sleep()
		{
			return array (
					"id",
					"uid",
					"username",
					"email",
					"register_ip" 
			);
		}

		public function __wakeup()
		{
			$roles = databaseManager::getDB ()->prepareSQL ( "SELECT roles FROM " . database::users . " WHERE uid = '$this->uid'" );
			$password = databaseManager::getDB ()->prepareSQL ( "SELECT password FROM " . database::users . " WHERE uid = '$this->uid'" );
			$this->roles = $roles->fetchArray () ['roles'];
			$this->password = $password->fetchArray () ['password'];
		}
	
	}


	abstract class userManager extends User
	{
		// array ( "colum" => "value")
		static private function user_exsists($userObj): bool
		{
			if (! $userObj || ! isset ( $userObj ))
			{
				return false;
			}
			$request = databaseManager::getDB ()->prepareSQL ( "SELECT * FROM " . database::users . " WHERE uid = '" . $userObj->uid . "'" );
			$request->executeSQL ();
			if ($request->getRowCount () > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		// array ( "colum" => "value")
		static private function assembleArrayCondition(array $condition): string
		{
			$finalString = "";
			$max = count ( $condition );
			( int ) $i = 0;
			foreach ( $condition as $key => $value )
			{
				$finalString = $finalString . $key . " = '" . $value . "'";
				$i = $i + 1;
				if ($i < $max)
				{
					$finalString = $finalString . " AND ";
				}
			}
			return $finalString;
		}

		static private function assembleArrayUpdate(array $condition): string
		{
			$finalString = "";
			$max = count ( $condition );
			( int ) $i = 0;
			foreach ( $condition as $key => $value )
			{
				$finalString = $finalString . $key . " = '" . $value . "'";
				$i = $i + 1;
				if ($i < $max)
				{
					$finalString = $finalString . ", ";
				}
			}
			return $finalString;
		}

		static public function getUserByArray(array $conditions): User
		{
			$conditions_str = self::assembleArrayCondition ( $conditions );
			$request = databaseManager::getDB ()->prepareSQL ( "SELECT * FROM " . database::users . " WHERE " . $conditions_str );
			$user = $request->fetchArray ();
			return User::init ( array (
					"id" => $user ['id'],
					"uid" => $user ['uid'],
					"username" => $user ['username'],
					"password" => $user ['password'],
					"email" => $user ['email'],
					"role" => $user ['role'],
					"register_ip" => $user ['register_ip'] 
			) );
		}
		// username*, email*, password*,optional: roles
		static public function createUserByArray(array $conditions): bool
		{
			// user already ex
			if (self::user_exsists ( User::loadByName ( $conditions ['username'] ) ))
			{
				throw new \Exception ( "User already exsits" );
				return false;
			}
			$sql = "INSERT INTO " . database::users . " (id, uid, username, password, email, roles, register_ip) VALUES (NULL, '" . uniqid ( "user_" ) . "', ?, ?, ?, ?, ? )";
			$tmpRole;
			if (isset ( $conditions ['roles'] ))
			{
				$tmpRole = json_encode ( $conditions ['roles'] );
			}
			else
			{
				$tmpRole = json_encode ( array (
						"0" 
				) );
			}
			$pw = password_hash ( $conditions ['password'], PASSWORD_DEFAULT );
			$ip = $_SERVER ['REMOTE_ADDR'];
			$bind = array (
					$conditions ['username'],
					$pw,
					$conditions ['email'],
					$tmpRole,
					$ip 
			);
			$request = databaseManager::getDB ()->prepareSQL ( array (
					$sql,
					$bind 
			) );
			return $request->executeSQL ();
		}

		static public function deleteUser(string $uid): bool
		{
			if (! self::user_exsists ( User::load ( $uid ) ))
			{
				return false;
			}
			$sql = "DELETE FROM " . database::users . " WHERE uid = '" . $uid . "'";
			$tmp = databaseManager::getDB ()->prepareSQL ( $sql );
			return $tmp->executeSQL ();
		}

		static public function update(string $uid, array $updates): bool
		{
			$strUpdates = self::assembleArrayUpdate ( $updates );
			$sql = "UPDATE " . database::users . " SET " . $strUpdates . " WHERE uid = '$uid'";
			$tmp = databaseManager::getDB ()->prepareSQL ( $sql );
			return $tmp->executeSQL ();
		}

		static public function getAllUsers($range = null)
		{
			$sql;
			if (! isset ( $range ))
			{
				$sql = "SELECT * FROM " . database::users . " ORDER BY username ASC";
			}
			else
			{
				$sql = "SELECT * FROM " . database::users . " ORDER BY username ASC LIMIT {$range[0]}, {$range[1]}";
			}
			$tmp = databaseManager::getDB ()->prepareSQL ( $sql );
			return $tmp->fetchAll ();
		}
	
	}

}
?>