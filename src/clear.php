<?php
	session_start();
	$old = session_id();

	//delete all session.
	session_destroy();
	
	$info = $_COOKIE;
	setcookie('PHPSESSID', '', time() - 3600);
	//setcookie('iowTrack', '', time() - 3600);
	
	echo "<pre>";
	print_r($info);
	echo session_id()." <- session id\n";
	echo "ok";
	echo "</pre>";
	
	exit();
?>