<?php
require_once '../system/database.class.php';
use system\databaseManager;


class Installer
{

	static public function checkInstallation()
	{
		if (! file_exists ( "../system/core.cfg.php" ))
		{
			echo json_encode ( array (
					"msg" => "No configuration found, proceed with normal installation",
					"code" => "NO_CFG" 
			) );
			exit ();
		}
		elseif( empty(file("../system/core.cfg.php")) )
		{
			echo json_encode ( array (
					"msg" => "Corrupted configuration found, please restore the default cfg",
					"code" => "CORRUPT_CFG"
			) );
			exit ();
		}
		else
		{
			try
			{
				$test = databaseManager::getDB ();
			}
			catch ( PDOException $e )
			{
				echo json_encode ( array (
						"msg" => "[INFO]: Database could not be reached with present configuration or configuration is corrupt.<br>Continuing installation",
						"code" => "WRONG_CFG" 
				) );
				exit ();
			}
		}
		echo json_encode ( array (
				"msg" => "configuration seems to be intact",
				"code" => "VALID_CFG" 
		) );
		exit ();
	}

}
?>