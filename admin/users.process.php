<?php
session_start ();
require_once '../system/core.class.php';
require_once SYSTEM_ROOT . '/system/rolemgr.class.php';
require_once SYSTEM_ROOT . '/system/usermgr.class.php';
use system\RoleManager;
use system\UserManager;
/*
 *
 */
echo '<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>';

$data = array ();
if (isset ( $_POST ['request'] ) && $_POST ['request'] === "list")
{
	$users = userManager::getAllUsers ( array (
			$_POST ['start'],
			$_POST ['start'] + 30 
	) );
	$data [] = '<div class="btn-group" style="width: 100%">';
	if ($_POST ['start'] == 0)
	{
		$data [] = '<button style="width: 50%" onClick=\'listUsers(' . ($_POST ['start'] - 30) . ')\' disabled>&larr;</button>';
	}
	else
	{
		$data [] = '<button style="width: 50%" onClick=\'listUsers(' . ($_POST ['start'] - 30) . ')\' >&larr;</button>';
	}
	if (count ( $users ) < 30)
	{
		$data [] = '<button style="width: 50%" onClick=\'listUsers(' . ($_POST ['start'] + 30) . ')\' disabled>&rarr;</button>';
	}
	else
	{
		$data [] = '<button style="width: 50%" onClick=\'listUsers(' . ($_POST ['start'] + 30) . ')\' >&rarr;</button>';
	}
	$data [] = '</div>';
	$data [] = '<table>';
	$data [] = '<tr>';
	$data [] = '<th>uid</th>';
	$data [] = '<th>username</th>';
	$data [] = '<th>email</th>';
	$data [] = '<th>roles</th>';
	$data [] = '<th>register ip</th>';
	$data [] = '<th></th>';
	$data [] = '<th></th>';
	$data [] = '</tr>';
	
	$roleMgr = RoleManager::init ();
	foreach ( $users as $key => $value )
	{
		$rolesArray = json_decode ( $users [$key] ["roles"], true );
		$rolesString = "";
		foreach ( $rolesArray as $roles )
		{
			$tmp = $roleMgr->getRoleByID ( $roles );
			if (! $tmp)
			{
			}
			else
			{
				$rolesString = $rolesString . $roleMgr->getRoleByID ( $roles )->name . ", ";
			}
		}
		$data [] = '<tr>';
		$data [] = '<td>' . $users [$key] ['uid'] . '</td>';
		$data [] = '<td>' . $users [$key] ['username'] . '</td>';
		$data [] = '<td>' . $users [$key] ['email'] . '</td>';
		$data [] = '<td>' . $rolesString . '</td>';
		$data [] = '<td>' . $users [$key] ['register_ip'] . '</td>';
		$data [] = '<td><button onClick=\'editUser("' . $users [$key] ['uid'] . '")\'>Edit</button></td>';
		$data [] = '<td><button>Remove</button></td>';
		$data [] = '</tr>';
	}
	
	$data [] = '</table>';
}
/*
 *
 */
if (isset ( $_POST ['request'] ) && $_POST ['request'] === "update")
{
	if (empty ( $_POST ['updates'] ['password'] ))
	{
		unset ( $_POST ['updates'] ['password'] );
	}
	else
	{
		$_POST ['updates'] ['password'] = password_hash ( $_POST ['updates'] ['password'], PASSWORD_DEFAULT );
	}
	
	if (empty ( $_POST ['updates'] ['roles'] ))
	{
		$_POST ['updates'] ['roles'] = '["role_none"]';
	}
	else
	{
		$_POST ['updates'] ['roles'] = json_encode ( $_POST ['updates'] ['roles'] );
	}
	
	\system\userManager::update ( $_POST ['user'], $_POST ['updates'] );
	var_dump ( $_POST ['updates'] );
}
/*
 *
 */
if (isset ( $_POST ['request'] ) && $_POST ['request'] === "edit")
{
	$currentUser = \system\userManager::load ( $_POST ['user'] );
	
	$data [] = "<script>$('select option').on('mousedown', function (e) {
    this.selected = !this.selected;
    e.preventDefault();
});</script>";
	$data [] = '<div class="content-box gold">';
	$data [] = '<table><tr>';
	$data [] = '<td><h5>Table ID</h5>';
	$data [] = "<input type='text' value='{$currentUser->id}' disabled></td>";
	$data [] = '<td><h5>User uniqe ID</h5>';
	$data [] = "<input type='text' value='{$currentUser->uid}' disabled></td>";
	$data [] = '<td><h5>Registration IP</h5>';
	$data [] = "<input type='text' value='{$currentUser->register_ip}' disabled></td>";
	$data [] = '<td><h5>Registration Date</h5>';
	$data [] = "<input type='text' value='{$currentUser->register_date}' disabled></td>";
	$data [] = '</tr></table></div>';
	$data [] = '<div class="content-box blue">';
	$data [] = '<table><tr>';
	$data [] = '<td><h5>Username</h5>';
	$data [] = "<input type='text' id='username' value='{$currentUser->username}' ></td>";
	$data [] = '<td><h5>E-Mail</h5>';
	$data [] = "<input type='text' id='email' value='{$currentUser->email}' ></td>";
	$data [] = '<tr></table></div>';
	$data [] = '<div class="content-box blue">';
	$data [] = '<table><tr><td><h5>New Password</h5>';
	$data [] = "<input type='text' id='password' >";
	$data [] = '</tr></td></table></div>';
	$data [] = '<div class="content-box purple">';
	$data [] = '<table><tr><td><h5>Roles</h5>';
	
	$data [] = "<select name='roleSelection' id='roles' size='10' multiple> ";
	$roleMgr = RoleManager::init ( true );
	$userRoles = json_decode ( $currentUser->roles );
	
	$roleCorpses = array_diff ( $userRoles, $roleMgr->getRoleIDs () );
	if (! empty ( $roleCorpses ))
	{
		$userRoles = array_diff ( $userRoles, $roleCorpses );
		$update = array (
				'roles' => json_encode ( $userRoles ) 
		);
		
		\system\userManager::update ( $currentUser->uid, $update );
	}
	foreach ( $roleMgr->roles as $role )
	{
		
		if (in_array ( $role ['roleID'], $userRoles ))
		{
			$data [] = "<option value='{$role['roleID']}' selected>{$role['name']}</option>";
		}
		else
		{
			$data [] = "<option value='{$role['roleID']}'>{$role['name']}</option>";
		}
	}
	$data [] = "</select></td>";
	
	$data [] = '<td><h5>Restrictions</h5>';
	$data [] = "<textarea disabled> {$currentUser->restrictions} </textarea></td>";
	$data [] = '<td><h5>Banned</h5>';
	$data [] = "<textarea disabled> {$currentUser->banned} </textarea></td>";
	$data [] = '<td><h5>Warnings</h5>';
	$data [] = "<textarea disabled> {$currentUser->warnings} </textarea></td>";
	$data [] = '</tr></table></div>';
	$data [] = '<div class="btn-group" style="width: 100%">';
	$data [] = '<button style="width: 20%" onClick=\'listUsers(0)\'>Back</button>';
	$data [] = '<button style="width: 20%" onClick=\'updateUser("' . $currentUser->uid . '",{username: $("#username").val(),email: $("#email").val(),password: $("#password").val(),roles: $("#roles").val()})\'>Update</button>';
	$data [] = '<button style="width: 20%" >Warn</button>';
	$data [] = '<button style="width: 20%" >Ban</button>';
	$data [] = '<button style="width: 20%" >Delete</button>';
	$data [] = '</div>';
}
if (isset ( $_POST ['request'] ) && $_POST ['request'] === "createRole")
{
}
foreach ( $data as $response )
{
	echo $response;
}
?>
