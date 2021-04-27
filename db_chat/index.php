<?php

require('errors.php');
session_start();

if(!isset($_SESSION["login"]) || !$_SESSION["login"])
{
	header("Location: anmelden.php");
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chat</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="script.js"></script>
</head>
<body>
<a href="logout.php" id="logoutbtn">Abmelden</a>
<div id="chatcontainer">
	<div id="msgc">
		<table id="messagecont"></table>
	</div>
	<div id="controlcont">
		<input type="text" id="msgtb" placeholder="Nachricht schreiben">
		<input type="submit" id="sendbtn" value="Senden">

		<fieldset>
			<input type="radio" id="dttext" name="dttype" value="1" checked>
			<label for="dttext">Text</label>
			<input type="radio" id="dtlink" name="dttype" value="2">
			<label for="dtlink">Link</label>
			<input type="radio" id="dtimage" name="dttype" value="3">
			<label for="dtimage">Bild</label>
		</fieldset>
	</div>
</div>
</body>
</html>
