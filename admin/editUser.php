<?php
require_once '../system/usermgr.class.php';
session_start ();
require_once '../system/core.class.php';
require_once SYSTEM_ROOT . '/system/access.class.php';
require_once SYSTEM_ROOT . '/system/session.class.php';
require_once SYSTEM_ROOT . '/system/pagemgr.class.php';
require_once SYSTEM_ROOT . '/system/rolemgr.class.php';
/*
 *
 */
use system\sessionHelper;
use system\access;
use system\pageManager;
use system\RoleManager;
/*
 * Setup Access System
 * TODO too complicated access system? Security check ?
 */
$client = sessionHelper::init ()->_getClientObject ();
$allowedPageRoles = pageManager::init ()->getBackendPageAccess ( "Dashboard" ) [0];
$AccessModule = access::setup ();
$roleMgr = RoleManager::init ();
foreach ( $allowedPageRoles as $role )
{
	$AccessModule->requireRole ( $roleMgr->getRoleByID ( $role ) );
}
/*
 *
 */
if (! $AccessModule->hasAccess ( $client ))
{
	die ( "No permission to view this" );
}
require_once SYSTEM_ROOT . '/system/stylemgr.class.php';
require_once SYSTEM_ROOT . '/system/menumgr.class.php';
require_once SYSTEM_ROOT . '/system/usermgr.class.php';
use system\styleManager;
use system\menuManager;
use system\User;
$styleMgr = new styleManager ();
?>
<html>
<head>
<?php
$styleMgr->parseStyle ( true );
?>

<style type="text/css">
table {
	width: 100%;
	border: 1px;
}
</style>
</head>
<body>
	<div id="topbar"></div>
	<div id="head_wrapper">
		<div class="image"></div>
	</div>
	<div id="top_menu">
		<div class="logo">
			<a href="http://www.bridgetroll.de/" target="_blank"><span
				style="display: block"> BridgeCMS </span></a>
		</div>
	</div>
	<div id="menu_wrapper">
	<?php
	menuManager::init ( true )->setActive ( "Users" )->displayMenu ( true );
	?>
	</div>
	<div id="body_wrapper">
		<div class="content" style="margin-top: 10px;">
			<span><a href="users.php"><button>&lt; Back</button></a></span>
<?php
echo $_SERVER ['QUERY_STRING'];
// TODO Edit users with form
var_dump ( User::load ( $_SERVER ['QUERY_STRING'] ) );
?>


		</div>
	</div>

</body>
</html>