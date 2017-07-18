<?php
session_start ();

require_once 'installmgr.class.php';

$tmp = Installer::checkInstallation();
if (json_decode($tmp,true)['code'] == "VALID_CFG")
{
	$_SESSION['VALID_CFG'] = "set";
}
?>