<?php

/* Fetch parameters */
$name = htmlspecialchars(rawurldecode($_POST['name']));
$message = htmlspecialchars(rawurldecode($_POST['message']));
$color = htmlspecialchars(rawurldecode($_POST['color']));

/* Get current time */
date_default_timezone_set('Europe/Berlin');
$date = date('d.m.Y H:i:s', time());

/* Open CSV file */
$handle = fopen("messages.csv", "a");

/* Write message */
fputcsv($handle, array($color, $date, $name, $message));

/* Close CSV file */
fclose($handle);

?>
