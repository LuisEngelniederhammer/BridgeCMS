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
$allowedPageRoles = pageManager::init ()->getBackendPageAccess ( "Pages" ) [0];
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

$pageMgr = pageManager::init ();
?>
<html>
<head>
<script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

<script type="text/javascript">
$( document ).ready(function() {

    CKEDITOR.replace( 'editor1',{
        height: 500
    } );

    CKEDITOR.replace( 'editor2',{
        height: 500
    } );
    
    CKEDITOR.instances.editor1.setData( <?php echo( $pageMgr->getHead($_SERVER ['QUERY_STRING']) ); ?> );
    CKEDITOR.instances.editor2.setData( <?php echo( $pageMgr->getBody($_SERVER ['QUERY_STRING']) ); ?> );
});


</script>
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
	menuManager::init ( true )->setActive ( "Pages" )->displayMenu ( true );
	?>
	</div>
	<div id="body_wrapper">
	<?php
	
	var_dump ( $_SERVER ['QUERY_STRING'] );
	// FIXME Editors dont work
	?>
		<div class="content">
			Head
			<textarea name="editor1"></textarea>

			Body
			<textarea name="editor2"></textarea>


		</div>
	</div>
</body>
</html>

