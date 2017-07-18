<?php

namespace system
{

	$system_root = str_replace ( "/system/core.cfg.php", "", str_replace ( '\\', '/', __FILE__ ) );
	define ( "SYSTEM_ROOT", $system_root );
	define ( "SYSTEM_ROOT_HOST", "http://" . $_SERVER ['HTTP_HOST'] );
	define ( "PATH_TEMPLATES", "/templates" );
	abstract class config
	{
		const db_prefix = "cms_58fe6c657ba61";
		const host = "localhost";
		const database = "bridgecms";
		const user = "root";
		const password = "";
		const port = "3306";
	}
	
	abstract class database
	{
		const users = config::db_prefix . "_users";
		const roles = config::db_prefix . "_roles";
		const system = config::db_prefix . "_system";
		const pages = config::db_prefix . "_pages";
		const backendPages = config::db_prefix . "_backendpages";
		const cfg = config::db_prefix . "_config";
		const bans = config::db_prefix . "_bans";
		const templates = config::db_prefix . "_templates";
	}
}
?>
