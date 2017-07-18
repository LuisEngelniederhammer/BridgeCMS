<?php
session_start ();
?>
<?php
if (isset ( $_SESSION ['VALID_CFG'] ))
{
	echo "cfg is valid, skipping<br>";
	exit();
}
else
{
	echo "Writing config<br>";
	$str = file_get_contents ( '../system/core.cfg.php' );
	// replace something in the file string - this is a VERY simple example
	$str = str_replace ( "#prefix", $_POST ['prefix'], $str );
	$str = str_replace ( "#host", $_POST ['host'], $str );
	$str = str_replace ( "#database", $_POST ['database'], $str );
	$str = str_replace ( "#username", $_POST ['username'], $str );
	$str = str_replace ( "#password", $_POST ['password'], $str );
	// write the entire string
	file_put_contents ( '../system/core.cfg.php', $str );
	echo "Writing config done<br>";
}
exit ();
?>