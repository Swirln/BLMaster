<?php
	include_once("dynamic/include.php");
	header("Content-Type: text/plain");
	echo "FIELDS\tIP\tPORT\tPASSWORDED\tDEDICATED\tSERVERNAME\tPLAYERS\tMAXPLAYERS\tMAPNAME\tBRICKCOUNT\r\n";
	echo "START\r\n";
	
	$query = "SELECT * FROM servers;";
	$statement = $GLOBALS['database']->prepare($query);
	
	$statement->execute();
	foreach($statement as $result)
	{
		echo $result['ip']. "\t";
		echo $result['port']. "\t";
		echo $result['passworded']. "\t";
		echo $result['dedicated']. "\t";
		echo $result['servername']. "\t";
		echo $result['players']. "\t";
		echo $result['maxplayers']. "\t";
		echo $result['gamemode']. "\t";
		echo $result['brickcount'];
		echo "\r\n";
	}
	echo "END\r\n";
?>