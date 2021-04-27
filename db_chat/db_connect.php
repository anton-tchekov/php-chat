<?php

require('db_def.php');

function db_connect()
{
	$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

	if($mysqli->connect_error)
	{
		echo $mysqli->connect_error;
		return NULL;
	}

	if(!$mysqli->set_charset('utf8'))
	{
		return NULL;
	}

	return $mysqli;
}

?>
