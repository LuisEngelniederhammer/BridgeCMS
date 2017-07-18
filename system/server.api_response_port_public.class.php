<?php
require_once 'server.api_response.class.php';
if (! isset ( $_POST ['api_request'] ))
{
	echo json_encode ( array (
			"status" => "-4",
			"response" => "no api_request set",
			"data" => "null" 
	) );
	if (isset ( $_SERVER ['HTTP_REFERER'] ))
	{
		new request_logger ( "unknown", $_SERVER ['HTTP_REFERER'], "no api_request set", "unknown" );
	}else
	{
		new request_logger ( "unknown", "unknown", "no api_request set", "unknown" );
	}
	die ();
}
API_Response::init ( $_POST ['api_request'] )->checkRequest ();
?>