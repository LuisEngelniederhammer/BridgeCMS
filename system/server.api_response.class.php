<?php
require_once 'licenseserver.class.php';
require_once 'server.config.php';


class request_logger
{

	public function __construct($file, $client, $status = "unknown", $userID = "unknown")
	{
		if (! file_exists ( "logs.json" ))
		{
			$fHND = fopen ( "logs.json", "w" );
			fwrite ( $fHND, json_encode ( array () ) );
		}
		$fHND = file ( "logs.json" );
		$logArray = json_decode ( $fHND [0], true );
		if (isset ( $logArray [$client] ))
		{
			$logArray [$client] ["file_diagnostics"] = $file;
			$logArray [$client] ["userID"] = $userID;
			$logArray [$client] ["last_request"] = date ( "d.m.Y - h:i:s" );
			$logArray [$client] ["count_request"] = $logArray [$client] ["count_request"] + 1;
			$logArray [$client] ["last_status"] = $status;
		}
		else
		{
			$logArray [$client] = array (
					"file_diagnostics" => $file,
					"userID" => $userID,
					"last_request" => date ( "d.m.Y - h:i:s" ),
					"count_request" => 1,
					"last_status" => $status 
			);
		}
		file_put_contents ( "logs.json", json_encode ( $logArray ) );
	}

	static public function display()
	{
		if (! file_exists ( "logs.json" ))
		{
			echo "no log file";
			return false;
		}
		else
		{
			$fHND = file ( "logs.json" );
			$logArray = json_decode ( $fHND [0], true );
			// var_dump ( $logArray );
			foreach ( $logArray as $key => $value )
			{
				echo '<tr>';
				echo '<td>' . $logArray [$key] ['file_diagnostics'] . '</td>';
				echo '<td>' . $logArray [$key] ['userID'] . '</td>';
				echo '<td>' . $key . '</td>';
				echo '<td>' . $logArray [$key] ["last_request"] . '</td>';
				echo '<td>' . $logArray [$key] ["count_request"] . '</td>';
				echo '<td>' . $logArray [$key] ["last_status"] . '</td>';
				echo '</tr>';
			}
		}
	}

}


class API_Response
{

	private $request;

	private $requiredFilename;

	private function __construct()
	{
	}

	static public function init(string $request)
	{
		$inst = new self ();
		$inst->request = json_decode ( $request, true );
		$inst->requiredFilename = "server.api_response_port_public.class.php";
		return $inst;
	}

	public function checkRequest()
	{
		if (! isset ( $this->request ['file'] ) || $this->request ['file'] != $this->requiredFilename)
		{
			echo json_encode ( array (
					"status" => "0",
					"response" => "required filename is bad",
					"data" => "null" 
			) );
			die ();
		}
		if (! isset ( $this->request ['key'] ))
		{
			echo json_encode ( array (
					"status" => "-1",
					"response" => "No license key was sent",
					"data" => "null" 
			) );
			die ();
		}
		else
		{
			$tmp = new BridgeCMS_server ( false );
			$check = $tmp->validateKey ( $this->request ['key'] );
			if ($check ['status'] == "0")
			{
				echo json_encode ( array (
						"status" => "-1",
						"response" => $check ['response'],
						"data" => "null" 
				) );
				new request_logger ( $this->request ['file'], $_SERVER ['HTTP_REFERER'], $check ['response'], "unknown" );
				die ();
			}
			elseif ($check ['status'] == "1")
			{
				if (! isset ( $this->request ['client'] ))
				{
					echo json_encode ( array (
							"status" => "-2",
							"response" => "No client URI was sent",
							"data" => "null" 
					) );
					new request_logger ( $this->request ['file'], $_SERVER ['HTTP_REFERER'], "No client URI was sent", "unknown" );
					die ();
				}
				elseif ($check ['url'] === $this->request ['client'])
				{
					echo json_encode ( array (
							"status" => "1",
							"response" => "request granted",
							"data" => password_hash ( server_config::api_key, PASSWORD_DEFAULT ) 
					) );
					new request_logger ( $this->request ['file'], $this->request ['client'], "request granted", $check ['userID'] );
					die ();
				}
				elseif ($check ['url'] != $this->request ['client'])
				{
					echo json_encode ( array (
							"status" => "-9",
							"response" => "key not registered to client",
							"data" => "null" 
					) );
					new request_logger ( $this->request ['file'], $this->request ['client'], "key not registered to client", $check ['userID'] );
					die ();
				}
				else
				{
					echo json_encode ( array (
							"status" => "-666",
							"response" => "unknown error",
							"data" => "nnnuuulll" 
					) );
					die ();
				}
			}
		}
	}

}
?>