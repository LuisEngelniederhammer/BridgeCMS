<?php
use system\databaseManager;
use system\database;
require_once __DIR__ . '/core.class.php';
require_once __DIR__ . '/database.class.php';


class LoginHelper
{

	private function __construct()
	{
	}

	public static function init()
	{
		return new self ();
	}

	public function checkLogin(array $UserAndPassword)
	{
		$statement = databaseManager::getDB ()->prepareSQL ( "SELECT password FROM " . database::users . " WHERE BINARY username = :user ", array (
				':user' => $UserAndPassword [0] 
		) );
		$result = $statement->fetchArray ();
		

		if (password_verify ( $UserAndPassword [1], $result ['password'] ))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

}
?>