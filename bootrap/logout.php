<?php
	session_name( 'admintool' );
	session_start();
	session_unset();
	session_destroy();
	header("Location: index.html");
?>

