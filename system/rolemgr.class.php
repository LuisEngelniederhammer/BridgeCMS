<?php

namespace system
{

	require_once __DIR__ . '/core.class.php';
	require_once __DIR__ . '/permissions.struct.php';
	require_once __DIR__ . '/database.class.php';
	class Role
	{
		public $id, $roleID, $name, $permissions;
		public function __construct($id, string $roleID, string $name, $permissions)
		{
			$this->id = $id;
			$this->roleID = $roleID;
			$this->name = $name;
			if (is_string ( $permissions ))
			{
				$this->permissions = json_decode ( $permissions, true );
			}
			else
			{
				$this->permissions = $permissions;
			}
		}
		public function __get($name)
		{
			switch ($name)
			{
				case $this->id :
					return $this->id;
					break;
				case $this->roleID :
					return $this->roleID;
					break;
				case $this->name :
					return $this->name;
					break;
				case $this->permissions :
					return $this->permissions;
					break;
			}
		}
		public function __set($name, $value)
		{
			// disallow setter
		}
	}
	class RoleManager
	{
		
		/*
		 * TODO
		 *
		 * have a class that creates an instance for every role
		 * save all those objects in some container
		 * Have the rolemanager for setting/getting those roles
		 *
		 */
		public $roles;
		private function __construct()
		{
		}
		static public function init($getAllRoles = false)
		{
			$tmp = new self ();
			if ($getAllRoles)
			{
				$request = databaseManager::getDB ()->prepareSQL ( "SELECT * FROM " . database::roles );
				$tmp->roles = $request->fetchAll ();
			}
			else
			{
				$tmp->roles = null;
			}
			return $tmp;
		}
		public function getRoleByName($name)
		{
			$request = databaseManager::getDB ()->prepareSQL ( "SELECT * FROM " . database::roles . " WHERE name = '$name'" );
			$this->roles = $request->fetchArray ();
			return new Role ( $this->roles ["id"], $this->roles ["roleID"], $this->roles ["name"], $this->roles ["permissions"] );
		}
		public function getRoleByID($id)
		{
			$request = databaseManager::getDB ()->prepareSQL ( "SELECT * FROM " . database::roles . " WHERE roleID = '$id'" );
			$this->roles = $request->fetchArray ();
			if (empty ( $this->roles ))
			{
				return false;
			}
			return new Role ( $this->roles ["id"], $this->roles ["roleID"], $this->roles ["name"], $this->roles ["permissions"] );
		}
		public function getRoleIDs()
		{
			$request = databaseManager::getDB ()->prepareSQL ( "SELECT roleID FROM " . database::roles );
			return $request->fetchAll(\PDO::FETCH_COLUMN);
	
			
		}
		public function createRole($name, string $permissions)
		{
			$request = databaseManager::getDB ()->prepareSQL ( "INSERT INTO " . database::roles . " (roleID,name,permissions) VALUES ('" . uniqid ( 'role_' ) . "','$name','$permissions');" );
			$this->roles = $request->executeSQL ();
		}
		public function deleteRole()
		{
		}
		public function updateRole($roleID, $name, $permissions)
		{
			$request = databaseManager::getDB ()->prepareSQL ( "UPDATE " . database::roles . " SET name='$name', permissions='$permissions' WHERE roleID='$roleID'" );
			$this->roles = $request->executeSQL ();
		}
	}
}
?>