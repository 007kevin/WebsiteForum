<?php
	header("Content-Type: text/event-stream");  // text/event-stream
	header("Cache-Control: no-cache");  // no-cache
	echo "retry: 1000\n";  // Every 1000 milliseconds
	$time = date("h:i:s", time()); // formatted date
	echo "data: {$time}\n\n"; // don't forget \n\n to end the message!!

	flush();
?>