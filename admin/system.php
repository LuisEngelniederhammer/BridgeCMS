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
require_once SYSTEM_ROOT . '/system/client_request.class.php';

/*
 *
 */
use system\sessionHelper;
use system\access;
use system\pageManager;
use system\RoleManager;
use system\client_request;
use system\styleManager;
use system\menuManager;
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
	menuManager::init ( true )->setActive ( "System" )->displayMenu ( true );
	?>
	</div>
	<div id="body_wrapper">
		<div class="content"></div>
		<div class="content-box blue">
			<h3>Bridge CMS</h3>
			test
		</div>
		<div class="content-box blue">
			<h3>API Port</h3>
			<?php 
			$statement = databaseManager::getDB ()->prepareSQL ( "SELECT * FROM " . database::system );
			$result = $statement->fetchArray ();
			$data = array (
					"file" => $result ['api_port'],
					"key" => $result ['_key'],
					"client" => $result ['client']
			);
			var_dump($data['file']);
			?>
		</div>

		
					<?php
					$response = json_decode ( client_request::init ()->request (), true );
					$color = "red";
					if ($response ['status'] == "1")
					{
						$color = "green";
					}
					?>
			<div class="content-box <?= $color ?>">
			
			<?php
			var_dump ( $response ['status'] );
			var_dump ( $response ['response'] );
			echo "<br><hr>";
			if ($response ['status'] != "1")
			{
				?>
			Activate a key:<br>
			<form method="post" action="activateSystem.php">
				<input type="text" style="width: 100%" name="key"
					placeholder="XXXX-XXXX-XXXX-XXXX"> <input type="submit"
					value="submit">
			</form>
<?php } ?>

		</div>
		<div class="content-box purple">
		<?php
		// TODO Create an update service
		?>
			<h3>Updates</h3>
			test
		</div>
	</div>



</body>
</html>