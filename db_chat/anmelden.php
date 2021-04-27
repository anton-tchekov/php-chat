<?php

require('errors.php');
require('captcha_gen.php');
require('db_connect.php');
session_start();
$_SESSION['login'] = false;
$err = 0;

function check_auth($uname, $pwd, $captcha)
{
	$sql = 'SELECT uid, password, ustate FROM benutzer WHERE username = ?';
	$db_pwd = '';

	/* check fields */
	if($uname == '' || $pwd == '')
	{
		return 1;
	}

	/* check if CAPTCHA is correct */
	if($captcha != $_SESSION['captcha'])
	{
		return 2;
	}

	/* connect to database */
	$mysqli = db_connect();
	$stmt = $mysqli->prepare($sql);

	if($stmt)
	{
		$stmt->bind_param('s', $uname);
		$stmt->execute();
		$stmt->bind_result($uid, $db_pwd, $ustate);
		$stmt->fetch();
		$stmt->close();
	}

	$mysqli->close();

	if(!password_verify($pwd, $db_pwd))
	{
		echo "PENIS";
		return 3;
	}

	if($ustate == 3)
	{
		return 4;
	}

	/* correct password */
	$_SESSION['login'] = true;
	$_SESSION['uid'] = $uid;
	$_SESSION['uname'] = $uname;
	$_SESSION['ustate'] = $ustate;
	header('Location: index.php');
	return 0;
}

if(isset($_POST['loginbtn']))
{
	$uname = htmlspecialchars($_POST['uname']);
	$pwd = htmlspecialchars($_POST['pwd']);
	$captcha = htmlspecialchars($_POST['captchatest']);

	$err = check_auth($uname, $pwd, $captcha);
}

if($err != 0 || !isset($_POST['loginbtn']))
{
	captcha_gen();
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<title>Anmelden</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="pwdshow.js"></script>
</head>
<body>
<div id="logincontainer">
	<form id="center" name="loginform" action="" method="post">
		<a href="registrieren.php">Registrieren</a>
		<h1>Anmelden</h1>
<?php

if($err)
{
	echo '<p class=\"hinweis\">';

	switch($err)
	{
		case 1:
			echo 'Benutzername oder Passwort nicht ausgefüllt';
			break;

		case 2:
			echo 'CAPTCHA falsch';
			break;

		case 3:
			echo 'Benutzername oder Passwort falsch';
			break;

		case 4:
			echo 'Benutzer nicht freigeschaltet';
			break;
	}

	echo '</p>';
}

?>
		<input type="text" name="uname" placeholder="Benutzername">
		<input type="password" name="pwd" id="pwdfield" placeholder="Passwort">

		<div id="chkcont" class="deselect">
			<input type="checkbox" name="showpwd" id="showpwd" tabindex="-1">
			<label for="showpwd" id="labelpwd">Passwort anzeigen</label>
		</div>

		<div id="captcha">
			<img src="captcha.php">
			<input type="text" name="captchatest" placeholder="Zeichen im Bild" tabindex="3" value="">
		</div>

		<input type="submit" name="loginbtn" value="Anmelden">
	</form>
</div>
</body>
</html>
