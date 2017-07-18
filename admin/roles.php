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
require_once SYSTEM_ROOT . '/system/stylemgr.class.php';
require_once SYSTEM_ROOT . '/system/menumgr.class.php';
use system\styleManager;
use system\menuManager;
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

<style type="text/css">
table {
	width: 100%;
	border: 1px;
}
</style>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="data/roles.js"></script>

<script type="text/javascript">
$(document).ready(function(){ listRoles(); });
</script>
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
	menuManager::init ( true )->setActive ( "Roles" )->displayMenu ( true );
	?>
	</div>
	<div id="body_wrapper">
		<div class="content">
			<div class="content-box greyblue">
				<h3>Roles</h3>
				<div class="btn-group" style="width: 100%">
					<button style="width: 100%" onClick="createForm()">Create Role</button>
				</div>
			</div>
			<div class="content-box">
				<div id="main"></div>
			</div>
		</div>
	</div>

</body>
</html>