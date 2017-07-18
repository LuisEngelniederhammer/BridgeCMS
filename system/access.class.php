<?php
// TODO Implement the Access system
namespace system
{
	require_once __DIR__.'/core.class.php';
	require_once __DIR__.'/usermgr.class.php';
	require_once __DIR__.'/rolemgr.class.php';
	require_once __DIR__.'/utils.class.php';


	class access 
	{

		private $_roles;

		private $_permissions;

		private function __construct()
		{
		}

		static public function setup(array $mixed = null): access
		{
			$tmpInstance = new self ();
			if (is_array ( $mixed ))
			{
				$tmpInstance->_roles = $mixed [0];
				$tmpInstance->_permissions = $mixed [1];
			}
			else
			{
				$tmpInstance->_roles = array ();
				$tmpInstance->_permissions = null;
			}
			return $tmpInstance;
		}

		public function requireRoles(array $roles = null): access
		{
			if (! isset ( $roles ))
			{
				return false;
			}
			foreach ( $roles as $value )
			{
				$this->allowRole ( $value );
			}
			return $this;
		}

		public function requireRole(Role $allowedRole): access
		{
			array_push ( $this->_roles, $allowedRole->roleID );
			return $this;
		}

		public function requirePermissions(array $permissions): access
		{
			array_merge ( $this->_permissions, $permissions );
			return $this;
		}

		public function hasAccess($client): bool
		{
			if ( !$client instanceof User) 
			{
				return false;
			}
			$hardCodedAccess = false;
			$roleAccess = false;
			// $tmp = all roles the page and the client have in common
			if ($tmp = array_intersect ( $this->_roles, json_decode ( $client->roles, false ) ))
			{
				if (is_array ( $tmp ) && count ( $tmp ) > 0)
				{
					$roleAccess = true;
				}
			}
			if (isset ( $this->_permissions ))
			{
				//FIXME Check the permissions of the roles th client has
				if ($tmp = array_intersect_assoc ( $this->_permissions, json_decode ( $client->roles, false ) ))
				{
					if (is_array ( $tmp ) && count ( $tmp ) > 0)
					{
						$hardCodedAccess = true;
					}
					else
					{
						$hardCodedAccess = false;
					}
				}
				if ($roleAccess && $hardCodedAccess)
				{
					return true;
				}
				elseif (! $roleAccess && $hardCodedAccess)
				{
					return true;
				}
				elseif ($roleAccess && ! $hardCodedAccess)
				{
					return false;
				}
				else
				{
					return false;
				}
			}
			elseif ($roleAccess)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	
	}

}