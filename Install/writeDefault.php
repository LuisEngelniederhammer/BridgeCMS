<?php
session_start ();
require_once '../system/core.cfg.php';
use system\config;
use system\database;
$database = new PDO ( 'mysql:host=' . config::host . ';dbname=' . config::database . ';port=' . config::port, config::user, config::password );
//
//
//
//
$owner = "
INSERT INTO " . database::roles . "
	(roleID,name,permissions)
VALUES
	('role_owner','owner','[\"*\"]');
";
$none = "
INSERT INTO " . database::roles . "
	(roleID,name,permissions)
VALUES
	('role_none','none','[\"\"]');
";
//
//
//
//
$defaultPage = "
INSERT INTO " . database::pages . "
	(	uid,
		pageName,
		dropdown,
		restriction,
		_condition,
		active,
		_default,
		head,
		body
		)
VALUES
	(	'" . uniqid ( 'page_' ) . "',
		'Home',
		'0',
		'0',
		'0',
		'0',
		'1',
		'',	
		'<h3>Welcome on this custom page</h3><br>You can edit this content in the backend -> <a href=\"" . SYSTEM_ROOT_HOST . "/admin\">Administrator Control Panel</a>'	
		);
";
//
//
//
//
$administrator = "
INSERT INTO " . database::users . "
	(	uid,
		username,
		password,
		email,
		roles,
		register_ip,
		register_date,
		warnings,
		banned,
		restrictions
	)
VALUES
	(	'" . uniqid ( 'user_' ) . "',
		'" . $_POST ['admin_user'] . "',
		'" . password_hash ( $_POST ['admin_password'], PASSWORD_DEFAULT ) . "',
		'0',
		'[\"role_owner\"]',
		'" . $_SERVER ['REMOTE_ADDR'] . "',
		'" . date ( "d.m.Y" ) . "',
		'0',
		'0', 
		'0'
		);
		";
//
//
//
$standard_admin = json_encode ( array (
		"roles" => array (
				"role_owner" 
		),
		"permissions" => array () 
) );
//
$dashboard = "
INSERT INTO " . database::backendPages . "
	(	
		pageName,
		restriction,
		filename
	)
VALUES
	(	'Dashboard',
		'$standard_admin',
		'dashboard.php'
		);
		";
//
$mods = "INSERT INTO " . database::backendPages . "
	(	
		pageName,
		restriction,
		filename
	)
VALUES
	(	'Mods',
		'$standard_admin',
		'mods.php'
		);";
//
$pages = "INSERT INTO " . database::backendPages . "
	(	
		pageName,
		restriction,
		filename
	)
VALUES
	(	'Pages',
		'$standard_admin',
		'pages.php'
		);";
//
$roles = "INSERT INTO " . database::backendPages . "
	(	
		pageName,
		restriction,
		filename
	)
VALUES
	(	'Roles',
		'$standard_admin',
		'roles.php'
		);";
//
$styles = "INSERT INTO " . database::backendPages . "
	(	
		pageName,
		restriction,
		filename
	)
VALUES
	(	'Styles',
		'$standard_admin',
		'styles.php'
		);";
//
$system = "INSERT INTO " . database::backendPages . "
	(	
		pageName,
		restriction,
		filename
	)
VALUES
	(	'System',
		'$standard_admin',
		'system.php'
		);";
//
$users = "INSERT INTO " . database::backendPages . "
	(	
		pageName,
		restriction,
		filename
	)
VALUES
	(	'Users',
		'$standard_admin',
		'users.php'
		);";
//
//
//
$role_check = "";
$role_check .= $database->exec ( $owner );
$role_check .= $database->exec ( $none );
var_dump ( "<br>$role_check<br>" );
$page_check = "";
$page_check .= $database->exec ( $dashboard );
$page_check .= $database->exec ( $mods );
$page_check .= $database->exec ( $pages );
$page_check .= $database->exec ( $roles );
$page_check .= $database->exec ( $styles );
$page_check .= $database->exec ( $system );
$page_check .= $database->exec ( $users );
$page_check .= $database->exec ( $defaultPage );
var_dump ( "<br>$page_check<br>" );
$admin_check = $database->exec ( $administrator );
var_dump ( "<br>$admin_check<br>" );
?>