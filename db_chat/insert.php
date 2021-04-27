<?php

require("db_connect.php");

session_start();

if($_SESSION["login"])
{
	$ustate = 3;
	$uid = $_SESSION["uid"];
	$message = htmlspecialchars(rawurldecode($_POST['message']));
	$datatype = intval(htmlspecialchars($_POST['dttype']));

	$sqlState = "SELECT ustate FROM benutzer WHERE uid = ?";
	$sqlInsert = "INSERT INTO chat (uid, message, datatype) VALUES (?, ?, ?)";

	$mysqli = db_connect();
	$stmtState = $mysqli->prepare($sqlState);
	$stmtInsert = $mysqli->prepare($sqlInsert);

	if($stmtState)
	{
		$stmtState->bind_param("i", $uid);
		$stmtState->execute();
		$stmtState->bind_result($ustate);
		$stmtState->fetch();
		$stmtState->close();
	}

	$_SESSION["ustate"] = $ustate;

	if($ustate < 2 && $stmtInsert)
	{
		$stmtInsert->bind_param("isi", $uid, $message, $datatype);
		$stmtInsert->execute();
		$stmtInsert->close();
	}

	$mysqli->close();
	echo $message;
}

?>
