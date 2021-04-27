<?php

require('db_connect.php');
session_start();

if($_SESSION['login'])
{
	$uid = $_SESSION['uid'];
	$ustate = 3;
	$startAt = htmlspecialchars($_POST['startat']);
	$sqlState = 'SELECT ustate FROM benutzer WHERE uid = ?';
	$sqlRead = 'SELECT benutzer.username, chat.message, ' .
		'chat.dtsent, chat.datatype FROM benutzer INNER JOIN chat ON ' .
		'chat.uid = benutzer.uid AND chat.id > ?';

	$mysqli = db_connect();
	$stmtState = $mysqli->prepare($sqlState);
	$stmtRead = $mysqli->prepare($sqlRead);

	if($stmtState)
	{
		$stmtState->bind_param('i', $uid);
		$stmtState->execute();
		$stmtState->bind_result($ustate);
		$stmtState->fetch();
		$stmtState->close();
	}

	$_SESSION['ustate'] = $ustate;

	if($ustate < 3 && $stmtRead)
	{
		$stmtRead->bind_param('i', $startAt);
		$stmtRead->execute();
		$stmtRead->bind_result($uname, $message, $sent, $dttype);

		$i = 0;
		$chat_msg = array();

		while($stmtRead->fetch())
		{
			$chat_msg[$i++] = array(
				'uname' => $uname,
				'msg' => $message,
				'dtsent' => $sent,
				'dttype' => $dttype);
		}

		$stmtRead->close();
		echo json_encode($chat_msg);
	}

	$mysqli->close();
}

?>
