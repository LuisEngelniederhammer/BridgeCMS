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
<script type="text/javascript">
 function getFile(){
   document.getElementById("upfile").click();
 }
 function sub(obj){
    var file = obj.value;
    var fileName = file.split("\\");
    document.getElementById("upload").innerHTML = fileName[fileName.length-1];
    document.myForm.submit();
    event.preventDefault();
  }
 
 function toggle_visibility(id){
     var e = document.getElementById(id);
     if(e.style.display == 'none')
         e.style.display = 'table-cell';
     else
         e.style.display = 'none';
  }
</script>
<?php
$styleMgr->parseStyle ( true );
?>
<style type="text/css">
#install {
	width: 100%;
	margin-left: auto;
	margin-right: auto;
	background-color: MediumTurquoise;
	height: 100px;
	bottom: 0px;
}

#upload {
	width: 150px;
	padding: 10px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border: 1px dashed #BBB;
	text-align: center;
	background-color: #DDD;
	cursor: pointer;
	margin-left: auto;
	margin-right: auto;
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
	menuManager::init ( true )->setActive ( "Styles" )->displayMenu ( true );
	?>
	</div>
	<div id="body_wrapper">
		<div class="content">
			<div class="content-box">
				<table>
					<tr>
						<th>Style</th>
						<th>Author</th>
						<th>Version</th>
						<th>Active</th>
						<th></th>
					</tr>
						
<?php
$AllStyles = $styleMgr->getAllStyles ();
foreach ( $AllStyles as $key => $values )
{
	$active = '<a href="setActiveStyle.php?' . $key . '"><button>Set active</button></a>';
	$remove = '<a href="removeStyle.php?' . $key . '"><button>Remove</button></a>';
	if ($styleMgr->isActiveStyle ( $key ))
	{
		$active = "Activated";
	}
	if ($key == "default")
	{
		$remove = "";
	}
	echo '<tr onclick=\'toggle_visibility("' . $key . '");\'>';
	echo '<td>' . $key . '</td>';
	echo '<td>' . $AllStyles [$key] ['author'] . '</td>';
	echo '<td>' . $AllStyles [$key] ['version'] . '</td>';
	echo '<td>' . $active . '</td>';
	echo '<td>' . $remove . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td id="' . $key . '" style="display:none; background-color: white;" colspan="5">' . $AllStyles [$key] ['info'] . '</td>';
	echo '</tr>';
}
?>

			</table>


			</div>
			<div class="content-box greyblue">
				<h4 style="text-align: center;">Install new Style</h4>
				<form action="installStyle.php" method="POST"
					enctype="multipart/form-data" name="myForm">
					<div id="upload" onclick="getFile()">click to upload a file</div>
					<!-- this is your file input tag, so i hide it!-->
					<!-- i used the onchange event to fire the form submission-->
					<div style='height: 0px; width: 0px; overflow: hidden;'>
						<input id="upfile" type="file" value="upload" name="datei"
							onchange="sub(this)" />
					</div>
					<!-- here you can have file submit button or you can write a simple script to upload the file automatically-->
					<!-- <input type="submit" value='submit' > -->
				</form>
			</div>
		</div>
	</div>
</body>
</html>

