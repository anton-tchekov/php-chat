<?php

session_start();
header("Content-type: image/png");

$captchaCode = "0000";
$captchaCode = htmlspecialchars($_SESSION["captcha"]);
$anzahlZeichen = 4;

$image = imagecreate(290, 130);
$farben = array();

for($i = 0; $i < $anzahlZeichen; $i++)
{
	$h = rand(0, 359);

	$r = 0;
	$g = 0;
	$b = 0;

	$c = 0.5;
	$x = $c * (1 - abs(fmod(($h / 60), 2) - 1));
	$m = 0.25;

	if ($h < 60)
	{
		$r = $c;
		$g = $x;
		$b = 0;
	}
	else if ($h < 120)
	{
		$r = $x;
		$g = $c;
		$b = 0;
	}
	else if ($h < 180)
	{
		$r = 0;
		$g = $c;
		$b = $x;
	}
	else if ($h < 240)
	{
		$r = 0;
		$g = $x;
		$b = $c;
	}
	else if ($h < 300)
	{
		$r = $x;
		$g = 0;
		$b = $c;
	}
	else
	{
		$r = $c;
		$g = 0;
		$b = $x;
	}

	$r = ($r + $m) * 255;
	$g = ($g + $m) * 255;
	$b = ($b + $m) * 255;

	$farben[$i] = imagecolorallocate($image, $r, $g, $b);
}

/* set background color */
$bgc = imagecolorallocate($image, 255, 255, 255);

/* fill image with background color */
imagefill($image, 0, 0, $bgc);

for($i = 0; $i < $anzahlZeichen; $i++)
{
	ImageTTFText($image,
		rand(30, 80),
		rand(-20, 40),
		50 + ($i * 50),
		rand(80, 120),
		$farben[$i],
		"fonts/captcha.ttf",
		$captchaCode[$i]);
}

/* add lines */
for($i = 0; $i < $anzahlZeichen; $i++)
{
	imageline($image, rand(0, 10), rand(0, 150), rand(280, 290),
		rand(0, 150), $farben[$i]);
}

/* destroy objects */
imagepng($image);
imagedestroy($image);

?>
