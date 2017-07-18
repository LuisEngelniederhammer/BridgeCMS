<?php

namespace system
{

	require_once __DIR__ . '/core.class.php';
	require_once __DIR__ . '/database.class.php';
	use system\databaseManager;


	class pageManager
	{

		private function __construct()
		{
		}

		static public function init()
		{
			return new self ();
		}

		public function getPageId($name)
		{
			$statement = databaseManager::getDB ()->prepareSQL ( "SELECT uid FROM " . database::pages . " WHERE pageName = '$name'" );
			return $statement->fetchArray () ['uid'];
		}

		public function getPage($uid)
		{
			$statement = databaseManager::getDB ()->prepareSQL ( "SELECT * FROM " . database::pages . " WHERE uid = '$uid'" );
			return $statement->fetchArray ();
		}

		public function getAllPages()
		{
			$returnArray = array ();
			$statement = databaseManager::getDB ()->prepareSQL ( "SELECT * FROM " . database::pages );
			$row = $statement->fetchAll ();
			for($i = 0; $i < count ( $row ); $i ++)
			{
				if (! isset ( $row [$i] ['active'] ) || empty ( $row [$i] ['active'] ))
				{
					$row [$i] ['active'] = false;
				}
				if (! isset ( $row [$i] ['dropdown'] ) || empty ( $row [$i] ['dropdown'] ))
				{
					$row [$i] ['dropdown'] = false;
				}
				if (! isset ( $row [$i] ['restriction'] ) || empty ( $row [$i] ['restriction'] ))
				{
					$row [$i] ['restriction'] = false;
				}
				if (! isset ( $row [$i] ['condition'] ) || empty ( $row [$i] ['condition'] ))
				{
					$row [$i] ['condition'] = false;
				}
				$returnArray [$row [$i] ['pageName']] = array (
						"name" => $row [$i] ['pageName'],
						"active" => $row [$i] ['active'],
						"dropdown" => $row [$i] ['dropdown'],
						"restriction" => $row [$i] ['restriction'],
						"condition" => $row [$i] ['condition'],
						"uid" => $row [$i] ['uid'] 
				);
			}
			return $returnArray;
		}

		public function getBackendPages()
		{
			$returnArray = array ();
			$statement = databaseManager::getDB ()->prepareSQL ( "SELECT * FROM " . database::backendPages );
			$row = $statement->fetchAll ();
			foreach ( $row as $backendPage )
			{
				$returnArray [$backendPage ['pageName']] = array (
						"url" => $backendPage ['filename'],
						"name" => $backendPage ['pageName'],
						"active" => false,
						"restrictions" => $backendPage ['restriction'] 
				);
			}
			return $returnArray;
		}

		private function page_exsist($pageName)
		{
			$statement = databaseManager::getDB ()->prepareSQL ( "SELECT * FROM " . database::pages . " WHERE pageName = '$pageName'" );
			$statement->executeSQL ();
			if ($statement->getRowCount () > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function createPage($pageName, $dropdown = false, $restriction = false, $_condition = false, $active = false)
		{
			if ($this->page_exsist ( $pageName ))
			{
				return false;
			}
			$statement = databaseManager::getDB ()->prepareSQL ( "INSERT INTO " . database::pages . " (uid,pageName,dropdown,restriction,_condition,active,_default) VALUES ('" . uniqid ( "page_" ) . "','$pageName','$dropdown','$restriction','$_condition','$active','false');" );
			return $statement->executeSQL ();
		}

		public function deletePage($uid)
		{
			$statement = databaseManager::getDB ()->prepareSQL ( "DELETE FROM " . database::pages . " WHERE uid = '$uid'" );
			return $statement->executeSQL ();
		}

		public function setDefaultPage($uid)
		{
			$statement = databaseManager::getDB ()->prepareSQL ( "UPDATE " . database::pages . " SET _default='0' WHERE _default = '1'" );
			$statement->executeSQL ();
			$statement = databaseManager::getDB ()->prepareSQL ( "UPDATE " . database::pages . " SET _default='1' WHERE uid = '$uid'" );
			return $statement->executeSQL ();
		}

		public function getDefaultPageID()
		{
			$statement = databaseManager::getDB ()->prepareSQL ( "SELECT uid FROM " . database::pages . " WHERE _default = '1'" );
			return $statement->fetchArray () ['uid'];
		}

		public function updateBody($uid, $content)
		{
			$statement = databaseManager::getDB ()->prepareSQL ( "UPDATE " . database::pages . " SET body='$content' WHERE uid = '$uid'" );
			return $statement->executeSQL ();
		}

		public function updateHead($uid)
		{
			$statement = databaseManager::getDB ()->prepareSQL ( "UPDATE " . database::pages . " SET head='$content' WHERE uid = '$uid'" );
			return $statement->executeSQL ();
		}

		public function getPageAccess($uid)
		{
			// FIXME roles und permissions trennen/Array zurückgeben
			$statement = databaseManager::getDB ()->prepareSQL ( "SELECT restriction FROM " . database::pages . " WHERE uid = '$uid'" );
			return $statement->fetchArray ();
		}

		public function getBackendPageAccess($name)
		{
			// FIXME roles und permissions trennen/Array zurückgeben
			$statement = databaseManager::getDB ()->prepareSQL ( "SELECT restriction FROM " . database::backendPages . " WHERE pageName = '$name'" );
			$rolesANDpermissions = json_decode ( $statement->fetchArray () ['restriction'], true );
			return array($rolesANDpermissions['roles'],$rolesANDpermissions['permissions']);
		}

		public function getBody($uid)
		{
			$statement = databaseManager::getDB ()->prepareSQL ( "SELECT body FROM " . database::pages . " WHERE uid = '$uid'" );
			return $statement->fetchAll ();
		}

		public function getHead($uid)
		{
			$statement = databaseManager::getDB ()->prepareSQL ( "SELECT head FROM " . database::pages . " WHERE uid = '$uid'" );
			return $statement->fetchAll ();
		}
	
	}

}
?>