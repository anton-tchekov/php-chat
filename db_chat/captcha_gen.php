<?php

function captcha_gen()
{
	$code = '';
	$str = '12345679ABCDEFGHIJKLMNPQRSTUVWXYZ';
	$len = strlen($str);

	for($i = 0; $i < 4; $i++)
	{
		$code .= $str[rand() % $len];
	}

	$_SESSION['captcha'] = $code;
}

?>
