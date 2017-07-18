<?php
require_once '../system/usermgr.class.php';
session_start ();
require_once '../system/core.class.php';
require_once SYSTEM_ROOT . '/system/access.class.php';
require_once SYSTEM_ROOT . '/system/session.class.php';
require_once SYSTEM_ROOT . '/system/pagemgr.class.php';
require_once SYSTEM_ROOT . '/system/rolemgr.class.php';
require_once SYSTEM_ROOT . '/system/stylemgr.class.php';
require_once SYSTEM_ROOT . '/system/menumgr.class.php';
/*
 *
 */
use system\sessionHelper;
use system\access;
use system\pageManager;
use system\RoleManager;
use system\styleManager;
use system\menuManager;
/*
 * Setup Access System
 * TODO too complicated access system? Security check ?
 */
$client = sessionHelper::init ()->_getClientObject ();
//FIXME Shift from roles to permissions only system
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


$styleMgr = new styleManager ();
if (! $AccessModule->hasAccess ( $client ))
{
	die ( "No permission to view this" );
}
?>
<html>
<head>

<?php
$styleMgr->parseStyle ( true );
?>

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
	menuManager::init ( true )->setActive ( "Dashboard" )->displayMenu ( true );
	?>
	</div>
	<div id="body_wrapper">
		<div class="content">
			<div class="content-box blue">
				<h3>Welcome to your Admin Panel</h3>
			</div>
			<div class="content-box green" >
				<h3>News</h3>
				api open_news_port
			</div>
		</div>
	</div>
</body>
</html>

