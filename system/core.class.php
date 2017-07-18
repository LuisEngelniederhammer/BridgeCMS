<?php

namespace system
{

	require_once __DIR__ . '/core.cfg.php';


	class Core
	{

		public function __construct()
		{
		}

		static public function setURL($file)
		{
			echo '<meta http-equiv="refresh" content="0; URL=' . $file . '">';
		}
	
	}

}
?>