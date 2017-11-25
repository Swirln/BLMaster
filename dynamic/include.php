<?php
	if (debugging == true)
	{
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}
	else
	{
		error_reporting(0);
	}
	
	ob_start();
	
	include_once("configuration.php");
	include_once("database.php");
	include_once("kill.php");
	killDead();
	include_once("functions.php");
	
	date_default_timezone_set("America/Chicago");
?>