<?php

namespace system
{

	require_once __DIR__ . '/core.class.php';


	class sessionHelper
	{

		private $clientObjectString;

		private function __construct()
		{
			// notation: CMSName_datatype_name
			$this->clientObjectString = "BridgeCMS_obj_client";
		}

		public static function init()
		{
			return new self ();
		}

		public function _createLoginSession(User $client)
		{
			$_SESSION [$this->clientObjectString] = $client;
		}

		public function _getClientObject()
		{
			if (isset ( $_SESSION [$this->clientObjectString] ))
			{
				return $_SESSION [$this->clientObjectString];
			}
			else
			{
				return false;
			}
		}
	
	}

}
?>