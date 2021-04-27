<?php

require('errors.php');
require('captcha_gen.php');
require('db_connect.php');
session_start();
$err = 0;

if(isset($_POST['registerbtn']))
{
	$sql = 'INSERT INTO benutzer (username, password, ustate) VALUES (?, ?, 3)';
	$uname = $_POST['uname'];
	$pwd = $_POST['pwd'];
	$captcha = $_POST['captchatest'];

	$uname_len = strlen($uname);
	$pwd_len = strlen($pwd);

	if($uname_len < 2 || $uname_len > 30)
	{
		$err = 1;
	}

	if($pwd_len < 6)
	{
		$err = 2;
	}

	if(!preg_match('/^[A-Za-z0-9_-]*$/', $uname_len))
	{
		$err = 3;
	}

	if($captcha != $_SESSION['captcha'])
	{
		$err = 4;
	}

	if(!$err)
	{
		$mysqli = db_connect();
		$stmt = $mysqli->prepare($sql);
		if($stmt)
		{
			$hash = password_hash($pwd, PASSWORD_DEFAULT);
			$stmt->bind_param('ss', $uname, $hash);
			$stmt->execute();
			$stmt->close();
		}

		$mysqli->close();
		header('Location: waiting.php');
	}
}

if($err != 0 || !isset($_POST['registerbtn']))
{
	captcha_gen();
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<title>Registrieren</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="pwdshow.js"></script>
</head>
<body>
<div id="logincontainer">
	<form id="center" name="loginform" action="" method="post">
		<a href="anmelden.php">Anmelden</a>
		<h1>Registrieren</h1>
<?php

if($err)
{
	echo '<p class=\"hinweis\">';

	switch($err)
	{
		case 1:
			echo 'Der Beutzername muss 3 bis 30 Zeichen lang sein';
			break;

		case 2:
			echo 'Das Passwort muss mindestens 6 Zeichen lang sein';
			break;

		case 3:
			echo 'Der Benutzername darf nur '
			. 'Buchstaben, Zahlen sowie den '
			. 'Unterstrich und Bindestrich '
			. 'enthalten';
			break;

		case 4:
			echo 'CAPTCHA falsch';
			break;
	}

	echo '</p>';
}

?>
		<input type="text" name="uname"
		placeholder="Benutzername" tabindex="1" value="">

		<input type="password" id="pwdfield" name="pwd"
		placeholder="Passwort" tabindex="2" value="">

		<div id="chkcont" class="deselect">
			<input type="checkbox" name="showpwd" id="showpwd" tabindex="-1">
			<label for="showpwd" id="labelpwd">Passwort anzeigen</label>
		</div>

		<div id="captcha">
			<img src="captcha.php">
			<input type="text" name="captchatest" placeholder="Zeichen im Bild" tabindex="3" value="">
		</div>

		<input type="submit" name="registerbtn" value="Registrieren" tabindex="4">
	</form>
</div>
</body>
</html>
