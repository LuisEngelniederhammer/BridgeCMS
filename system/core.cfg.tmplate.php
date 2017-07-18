<?php

namespace system
{
	$system_root = str_replace("/system/core.cfg.php", "", str_replace('\\', '/', __FILE__));

	define ( "SYSTEM_ROOT",  $system_root);
	define ( "SYSTEM_ROOT_HOST",  "http://" . $_SERVER['HTTP_HOST']);


	abstract class config
	{

		const db_prefix = "#prefix";

		const host = "#host";

		const database = "#database";

		const user = "#username";

		const password = "#password";

		const port = "3307";
	
	}
	abstract class database
	{

		const users = config::db_prefix . "_users";
		
		const roles = config::db_prefix . "_roles";
		
		const system = config::db_prefix . "_system";
		
		const pages = config::db_prefix . "_pages";
		
		const cfg = config::db_prefix . "_config";
		
		const bans = config::db_prefix . "_bans";
	
	}

}
?>