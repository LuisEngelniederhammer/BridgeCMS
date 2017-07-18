<?php

namespace system
{

	require_once __DIR__ . '/core.class.php';
	use RecursiveIteratorIterator;


	class styleManager
	{

		private $path;

		public function __construct()
		{
			$this->path = __DIR__ . "/../styles";
			if (! file_exists ( $this->path . "/config.json" ))
			{
				$fHND = fopen ( $this->path . "/config.json", "w" );
				fwrite ( $fHND, json_encode ( array (
						"activeStyle" => "default" 
				) ) );
				fclose ( $fHND );
			}
		}
		// returns array with all available styles or false
		public function getAllStyles()
		{
			$checkTemplates = array_diff ( scandir ( $this->path ), array (
					'.',
					'..' 
			) );
			$checkTemplates = array_values ( $checkTemplates );
			$returnArray = array ();
			for($i = 0; $i < count ( $checkTemplates ); $i ++)
			{
				if (is_dir ( $this->path . "/" . $checkTemplates [$i] ))
				{
					$tmpStylePath = array_diff ( scandir ( $this->path . "/" . $checkTemplates [$i] ), array (
							'.',
							'..' 
					) );
					if (in_array ( "config.json", $tmpStylePath ) && in_array ( "main.css", $tmpStylePath ))
					{
						$config = json_decode ( file_get_contents ( $this->path . "/" . $checkTemplates [$i] . "/config.json" ), true );
						$returnArray [$checkTemplates [$i]] = $config;
					}
				}
			}
			return $returnArray;
		}

		public function setActiveStyle($name)
		{
			$arr = json_decode ( file_get_contents ( $this->path . "/config.json" ), true );
			$arr ['activeStyle'] = $name;
			file_put_contents ( $this->path . "/config.json", json_encode ( $arr ) );
		}

		public function isActiveStyle($name): bool
		{
			$arr = json_decode ( file_get_contents ( $this->path . "/config.json" ), true );
			if ($name == $arr ['activeStyle'])
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function parseStyle($isBackend = false)
		{
			if (! $isBackend)
			{
				$arr = json_decode ( file_get_contents ( SYSTEM_ROOT . "/styles/config.json" ), true );
				$path = SYSTEM_ROOT_HOST . "/styles/" . $arr ['activeStyle'] . "/main.css";
				echo '<link rel="stylesheet" type="text/css" href="' . $path . '">';
			}
			else
			{
				$arr = json_decode ( file_get_contents ( SYSTEM_ROOT . "/styles/config.json" ), true );
				$path = SYSTEM_ROOT_HOST . "/styles/" . $arr ['activeStyle'] . "/main_backend.css";
				echo '<link rel="stylesheet" type="text/css" href="' . $path . '">';
			}
		}
		//will use files in the data folder in the active style
		public function parseCustomStyle($filename)
		{

				$arr = json_decode ( file_get_contents ( SYSTEM_ROOT . "/styles/config.json" ), true );
				$path = SYSTEM_ROOT_HOST . "/styles/" . $arr ['activeStyle'] . "/data/$filename.css";
				echo '<link rel="stylesheet" type="text/css" href="' . $path . '">';
			
		}

		public function removeStyle($name)
		{
			$arr = json_decode ( file_get_contents ( $this->path . "/config.json" ), true );
			if ($name == $arr ['activeStyle'])
			{
				$arr ['activeStyle'] = "default";
			}
			$path = SYSTEM_ROOT . "/styles/" . $name;
			if (is_dir ( $path ) === true)
			{
				$files = new \RecursiveIteratorIterator ( new \RecursiveDirectoryIterator ( $path ), RecursiveIteratorIterator::CHILD_FIRST );
				foreach ( $files as $file )
				{
					if (in_array ( $file->getBasename (), array (
							'.',
							'..' 
					) ) !== true)
					{
						if ($file->isDir () === true)
						{
							rmdir ( $file->getPathName () );
						}
						else if (($file->isFile () === true) || ($file->isLink () === true))
						{
							unlink ( $file->getPathname () );
						}
					}
				}
				return rmdir ( $path );
			}
			else if ((is_file ( $path ) === true) || (is_link ( $path ) === true))
			{
				return unlink ( $path );
			}
			return false;
		}
	
	}

}
?>