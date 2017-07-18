<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<?php
session_start ();
require_once '../system/core.class.php';
require_once SYSTEM_ROOT . '/system/rolemgr.class.php';
use system\RoleManager;
use system\Role;
/*
 *
 */
$data = array ();
if (isset ( $_POST ['request'] ) && $_POST ['request'] === "list")
{
	$data [] = '<table>';
	$data [] = '<tr>';
	$data [] = '<th>Role ID</th>';
	$data [] = '<th>Role Name</th><th></th><th></th>';
	$data [] = '</tr>';
	$rolesObj = RoleManager::init ( true );
	foreach ( $rolesObj->roles as $role )
	{
		$data [] = '<tr>';
		$data [] = '<td>' . $role ["roleID"] . '</td>';
		$data [] = '<td>' . $role ["name"] . '</td>';
		$data [] = '<td><button onClick=\'showRole("' . $role ["roleID"] . '")\'>Edit</button></td>';
		$data [] = '<td><button onClick=\'removeRole("' . $role ["roleID"] . '")\'>Delete</button></a></td>';
		$data [] = '</tr>';
	}
	$data [] = '</table>';
}
if (isset ( $_POST ['request'] ) && $_POST ['request'] === "edit")
{
	$role = RoleManager::init ()->getRoleByID ( $_POST ['role'] );
	$data [] = 'Name: ';
	$data [] = '<input type="text" id="roleName" value="' . $role->name . '"><br>';
	$data [] = 'Permissions: <br>';
	$permStr = "";
	foreach ( $role->permissions as $perm )
	{
		$permStr = $permStr . $perm . ";";
	}
	$data [] = '<textarea id="styled">' . $permStr . '</textarea>';
	
	$data [] = '<div class="btn-group" style="width: 100%">';
	$data [] = '<button style="width: 50%" onClick=\'listRoles()\'>Back</button>';
	$data [] = '<button style="width: 50%" onClick=\'updateRole("' . $role->roleID . '",$("#roleName").val(),$("#styled").val())\'>Update</button>';
	$data [] = '</div>';
}
if (isset ( $_POST ['request'] ) && $_POST ['request'] === "update")
{
	$perms = explode ( ";", $_POST ['perm'] );
	$perms = array_filter ( $perms );
	$perms = array_values ( $perms );
	RoleManager::init ()->updateRole ( $_POST ['role'], $_POST ['name'], json_encode ( $perms ) );
}
if (isset ( $_POST ['request'] ) && $_POST ['request'] === "createForm")
{
	$data [] = 'Name:';
	$data [] = '<input type="text" id="roleName" placeholder="Role name"><br>';
	$data [] = 'Permissions: <br>';
	$data [] = '<textarea id="styled" placeholder="List all permissions here, seperated by a ;"></textarea>';
	$data [] = '<div class="btn-group" style="width: 100%">';
	$data [] = '<button style="width: 50%" onClick=\'listRoles()\'>Back</button>';
	$data [] = '<button style="width: 50%" onClick=\'createRole($("#roleName").val(),$("#styled").val())\'>Create</button>';
	$data [] = '</div>';
}
if (isset ( $_POST ['request'] ) && $_POST ['request'] === "createRole")
{
	RoleManager::init ()->createRole ( $_POST ['name'], json_encode ( explode ( ";", $_POST ['perm'] ) ) );
}
foreach ( $data as $response )
{
	echo $response;
}
?>