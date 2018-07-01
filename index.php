<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamic/include.php');
	echo "FIELDS\tIP\tPORT\tPASSWORDED\tDEDICATED\tSERVERNAME\tPLAYERS\tMAXPLAYERS\tMAPNAME\tBRICKCOUNT\r\n";
	echo "START\r\n";
	
	$query = 'SELECT * FROM servers;';
	$statement = $GLOBALS['database']->prepare($query);

	$statement->execute();
	foreach($statement as $result)
	{
		echo $result['ip'] . "\t";
		echo $result['port'] . "\t";
		echo $result['passworded'] . "\t";
		echo $result['dedicated'] . "\t";
		echo $result['serverName'] . "\t";
		echo $result['players'] . "\t";
		echo $result['maxPlayers'] . "\t";
		echo $result['gamemode'] . "\t";
		echo $result['brickCount'] . "\r\n";
	}
	echo "END\r\n";
?>
