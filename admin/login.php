<?php
session_start ();
require_once '../system/core.class.php';
require_once SYSTEM_ROOT . '/system/LoginHelper.class.php';
require_once SYSTEM_ROOT . '/system/session.class.php';
require_once SYSTEM_ROOT . '/system/userMgr.class.php';
use system\Core;
use system\sessionHelper;
use system\userManager;

$check = LoginHelper::init ()->checkLogin ( array (
		$_POST ['username'],
		$_POST ['password'] 
) );
if ($check)
{
	//TODO create load User by system\User class and create access with system\Access class
	sessionHelper::init()->_createLoginSession(userManager::loadByName($_POST ['username']));
	
	Core::setURL ( SYSTEM_ROOT_HOST . "/admin/dashboard.php" );
}
else
{
	Core::setURL ( SYSTEM_ROOT_HOST . "/admin/index.php?code=failure" );
}
?>