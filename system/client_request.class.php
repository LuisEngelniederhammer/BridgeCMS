<?php

namespace system
{

	require_once __DIR__ . '/core.class.php';


	class client_request
	{

		private function __construct()
		{
		}

		static public function init()
		{
			$inst = new self ();
			return $inst;
		}

		public function request()
		{
			if (! $this->isActivated ())
			{
				return array(false,"No key yet activated");
			}
			$statement = databaseManager::getDB ()->prepareSQL ( "SELECT * FROM " . database::system );
			$result = $statement->fetchArray ();
			$data = array (
					"file" => $result ['api_port'],
					"key" => $result ['_key'],
					"client" => $result ['client'] 
			);
			$url_send = "http://www.bridgetroll.de/BridgeCMS_HQ/server.api_response_port_public.class.php";
			$post = json_encode ( $data );
			$ch = curl_init ( $url_send );
			curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt ( $ch, CURLOPT_REFERER, $_SERVER ['HTTP_REFERER'] );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, array (
					"api_request" => $post 
			) );
			curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
			$result = curl_exec ( $ch );
			curl_close ( $ch ); // Seems like good practice
			return $result;
		}

		public function activateKey($api_port, $key)
		{
			databaseManager::getDB ()->prepareSQL ( "INSERT INTO " . database::system . " (api_port, _key, client) VALUES ('$api_port','$key','" . $_SERVER ['HTTP_HOST'] . "')" );
		}

		public function isActivated(): bool
		{
			$statement = databaseManager::getDB ()->prepareSQL ( "SELECT * FROM " . database::system );
			$statement->executeSQL ();
			if ($statement->getRowCount () > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	
	}

}
?>