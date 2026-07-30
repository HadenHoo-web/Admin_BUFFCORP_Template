<?php 
	session_name('admintool');
	session_start();
	if(!isset($_SESSION["adminkh"]) || !$_SESSION["adminkh"])
	$_SESSION["adminkh"]="adminkh";
	header("Location:bootrap");
?>
