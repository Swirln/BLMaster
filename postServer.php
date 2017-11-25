<?php
	include($_SERVER["DOCUMENT_ROOT"] ."/dynamic/include.php");
	header('Content-Type: text/plain');
	
	function kill($error)
	{
		echo "FAIL ". $error;
		exit();
	}
	
	function success($message)
	{
		echo "NOTE ". $message;
		exit();
	}
	
	if ($_SERVER['HTTP_USER_AGENT'] != "Blockland-r1986")
	{
		kill("Not being called from Blockland");
	}
	
	$postValues = array(
		$_POST['ServerName'],
		$_POST['Port'],
		$_POST['Players'],
		$_POST['MaxPlayers'],
		$_POST['Mod'],
		$_POST['Map'],
		$_POST['Passworded'],
		$_POST['Dedicated'],
		$_POST['BrickCount'],
		$_POST['DemoPlayers'],
		$_POST['blid'],
		$_POST['csg'],
		$_POST['ver'],
		$_POST['build']
	);
	
	foreach ($postValues as $param)
	{
		if (isset($param))
		{
			$ip = getIp();
			$uptime = time();
			$serverName = $_POST['ServerName'];
			$serverPort = $_POST['Port'];
			$activePlayers = $_POST['Players'];
			$maxPlayers = $_POST['MaxPlayers'];
			$serverMod = $_POST['Mod'];
			$gameMode = $_POST['Map'];
			$isPassworded = $_POST['Passworded'];
			$isDedicated = $_POST['Dedicated'];
			$brickCount = $_POST['BrickCount'];
			$allowDemoPlayers = $_POST['DemoPlayers'];
			$userId = $_POST['blid'];
			$csg = $_POST['csg'];
			$ver = $_POST['ver'];
			$build = $_POST['build'];
		}
		else
		{
			kill("Invalid POST values!");
		}
	}
	
	// checks

	$intArray = array(
		$brickCount,
		$userId,
		$serverPort,
		$activePlayers,
		$maxPlayers,
		$isPassworded,
		$isDedicated
	);
	
	foreach ($intArray as $testInt)
	{
		if (is_numeric($testInt))
		{
			$testInt = (int)$testInt; // Cast as int
		}
		else
		{
			kill("One of the POST values specified is not an integer");
		}
	}
	
	if ($serverPort < 0)
	{
		$serverPort = 28000;
	}
	
	if ($ip == "")
	{
		$ip = "127.0.0.1";
	}
	
	if ($userId < 0)
	{
		kill("Invalid BL_ID " . $userId);
	}
	
	if ($maxPlayers < 0)
	{
		kill("Invalid player cap");
	}
	
	if ($gameMode == "")
	{
		$gameMode = "Custom";
	}
	
	// check if blid is valid
	$check = file_get_contents("http://mods.greek2me.us/statistics/user-lookup.php?blid=". $userId);
	$checkContent = preg_split("/[\t]/", $check);
	if ($check == "")
	{
		kill("No user");
	}
	
	// Make the new servername
	// first check if the temp folder exists
	if (file_exists("temp/") == false)
	{
		mkdir("temp");
	}
	$filePath = "temp/". generateRandomString() .".txt";
	file_put_contents($filePath, $check);
	$file = file($filePath);
	$newArray = array_slice($file, -2, true);
	$parts = preg_split("/[\t]/", $newArray[0]);
	$username = $parts[1];
	if (substr($checkContent[2], -1) == "s")
	{
		$serverName = $username ."' ". $serverName;
	}
	else
	{
		$serverName = $username ."'s ". $serverName;
	}
	
	// Dispose of the temp file once we're done
	unlink($filePath);
	
	// finish checks, post the server
	
	$query = "SELECT * FROM servers WHERE ip = :ip AND port = :port;";
	$statement = $GLOBALS['database']->prepare($query);
	$statement->bindParam(":ip", $ip, PDO::PARAM_STR);
	$statement->bindParam(":port", $serverPort, PDO::PARAM_INT);
	
	$statement->execute();
	if ($statement->rowCount() > 0)
	{
		$query = "UPDATE servers SET port = :newPort, passworded = :newPasswordDefinition, dedicated = :newDedicatedDefinition, servername = :newServerName, players = :newPlayerCount, maxplayers = :newMaxPlayers, gamemode = :newGamemode, allowDemoPlayers = :newDemoPlayersDefinition, brickcount = :newBrickCount, mod_ = :newMod, csg = :newCsg, ver = :newVer, build = :newBuild, uptime = :newUptime WHERE ip = :blahNewIp AND port = :blahNewPort;";
		$statement = $GLOBALS['database']->prepare($query);
		$statement->bindParam(":newPort", $serverPort, PDO::PARAM_INT);
		$statement->bindParam(":newPasswordDefinition", $isPassworded, PDO::PARAM_INT);
		$statement->bindParam(":newDedicatedDefinition", $isDedicated, PDO::PARAM_INT);
		$statement->bindParam(":newServerName", $serverName, PDO::PARAM_STR);
		$statement->bindParam(":newPlayerCount", $activePlayers, PDO::PARAM_INT);
		$statement->bindParam(":newMaxPlayers", $maxPlayers, PDO::PARAM_INT);
		$statement->bindParam(":newGamemode", $gameMode, PDO::PARAM_STR);
		$statement->bindParam(":newBrickCount", $brickCount, PDO::PARAM_INT);
		$statement->bindParam(":newMod", $serverMod, PDO::PARAM_STR);
		$statement->bindParam(":newDemoPlayersDefinition", $allowDemoPlayers, PDO::PARAM_STR);
		$statement->bindParam(":newCsg", $csg, PDO::PARAM_STR);
		$statement->bindParam(":newVer", $ver, PDO::PARAM_STR);
		$statement->bindParam(":newBuild", $build, PDO::PARAM_STR);
		$statement->bindParam(":newUptime", $uptime, PDO::PARAM_STR);
		
		$statement->bindParam(":blahNewIp", $ip, PDO::PARAM_STR);
		$statement->bindParam(":blahNewPort", $serverPort, PDO::PARAM_STR);
		
		$statement->execute();
		success("Successfully updated server");
	}
	else
	{
		$query = "INSERT INTO servers (ip, port, passworded, dedicated, servername, players, maxplayers, gamemode, brickcount, allowDemoPlayers, mod_, csg, ver, build, uptime) VALUES(:newIp, :newPort, :newPasswordDefinition, :newDedicatedDefinition, :newName, :newPlayerCount, :newMaxPlayers, :newGamemode, :newMod, :newDemoPlayersDefinition, :newBrickCount, :newCsg, :newVer, :newBuild, :currentUptime);";
		$statement = $GLOBALS['database']->prepare($query);
		$statement->bindParam(":newIp", $ip, PDO::PARAM_STR);
		$statement->bindParam(":newPort", $serverPort, PDO::PARAM_INT);
		$statement->bindParam(":newPasswordDefinition", $isPassworded, PDO::PARAM_INT);
		$statement->bindParam(":newDedicatedDefinition", $isDedicated, PDO::PARAM_INT);
		$statement->bindParam(":newName", $serverName, PDO::PARAM_STR);
		$statement->bindParam(":newPlayerCount", $activePlayers, PDO::PARAM_INT);
		$statement->bindParam(":newMaxPlayers", $maxPlayers, PDO::PARAM_INT);
		$statement->bindParam(":newGamemode", $gameMode, PDO::PARAM_STR);
		$statement->bindParam(":newBrickCount", $brickCount, PDO::PARAM_INT);
		$statement->bindParam(":newMod", $serverMod, PDO::PARAM_STR);
		$statement->bindParam(":newDemoPlayersDefinition", $allowDemoPlayers, PDO::PARAM_STR);
		$statement->bindParam(":newCsg", $csg, PDO::PARAM_STR);
		$statement->bindParam(":newVer", $ver, PDO::PARAM_STR);
		$statement->bindParam(":newBuild", $build, PDO::PARAM_STR);
		$statement->bindParam(":currentUptime", $uptime, PDO::PARAM_STR);
		
		$statement->execute();
		success("Successfully posted server");
	}
?>