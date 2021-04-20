<?php

/* Fetch parameters */
$startAt = $_POST['startat'];

/* Open CSV file */
$handle = fopen("messages.csv", "r");

/* Skip old messages */
for($i = 0; $i < $startAt; ++$i)
{
	fgets($handle);
}

$line = array();
$messages = array();

/* Read until end or error */
while(($line = fgetcsv($handle)) != NULL || $line != FALSE)
{
	/* Fill results array */
	$messages[] = array(
		'color' => $line[0],
		'datetime' => $line[1],
		'name' => $line[2],
		'message' => $line[3]);
}

/* Close CSV file */
fclose($handle);

/* Return results */
echo json_encode($messages);

?>
