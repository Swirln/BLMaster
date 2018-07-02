<?php
	require_once($_SERVER['DOCUMENT_ROOT'] .'/dynamic/include.php');
	header('Content-Type: text/plain');

	$postValues = [
		'ServerName',
		'Port',
		'Players',
		'MaxPlayers',
		'Map',
		'Passworded',
		'Dedicated',
		'BrickCount',
		'blid',
		'ver'
	];
	$valuesCount = count($postValues);
	$currentValuesFound = 0;

	foreach ($postValues as $value)
	{
		if (in_array($value, array_keys($_POST)))
		{
			++$currentValuesFound;
		}
	}

	if ($currentValuesFound !== $valuesCount)
	{
		exit('FAIL Invalid POST params');
	}

	$ip = IP;
	$uptime = time();
	$username = '';
	$serverName = $_POST['ServerName'];
	$serverPort = $_POST['Port'];
	$activePlayers = $_POST['Players'];
	$maxPlayers = $_POST['MaxPlayers'];
	$gameMode = $_POST['Map'];
	$isPassworded = $_POST['Passworded'];
	$isDedicated = $_POST['Dedicated'];
	$brickCount = $_POST['BrickCount'];
	$bl_id = $_POST['blid'];
	$key_id = $_POST['blid'];
	$version = $_POST['ver'];

	$intArray = [
		$brickCount,
		$serverPort,
		$activePlayers,
		$maxPlayers,
		$isPassworded,
		$isDedicated,
		$version
	];

	if ($version == '21')
	{
		$intArray[] = $bl_id;
	}

	foreach ($intArray as $testInt)
	{
		if (is_numeric($testInt))
		{
			$testInt = (int)$testInt; // Cast as int
		}
		else
		{
			exit('FAIL One of the POST values specified is not an integer');
		}
	}

	if ($version < 21)
	{
		if (keyToID($bl_id))
		{
			$bl_id = keyToID($bl_id);
		}
		else
		{
			exit('FAIL Failed to convert BL_ID to key ID');
		}
	}
	else
	{
		$key_id = idToKey($bl_id);
	}

	if ($serverPort < 0)
	{
		$serverPort = 28000;
	}

	if (empty($ip))
	{
		$ip = '127.0.0.1';
	}

	if ($bl_id < 0)
	{
		exit('FAIL Invalid BL_ID ' . $bl_id);
	}

	if ($maxPlayers < 0)
	{
		exit('FAIL Invalid player cap');
	}

	if (empty($gameMode))
	{
		$gameMode = 'Custom';
	}

	// Get creator's name
	$check = trim(file_get_contents('http://mods.greek2me.us/statistics/user-lookup.php?blid='. $bl_id));
	if (empty($check))
	{
		exit('FAIL No user');
	}

	$entries = explode("\n", $check);
	$namesAndDates = [];
	$interval = [];
	foreach ($entries as $entry)
	{
		$entry = explode("\t", $entry);
		array_shift($entry);
		$namesAndDates[$entry[0]] = $entry[1];
	}
	foreach ($namesAndDates as $name => $date)
	{
		$interval[$name] = abs(strtotime(date('Y-m-d H:m:s')) - strtotime($date));
	}

	asort($interval);
	$username = trim(key($interval));

	if (empty($username))
	{
		exit('FAIL Failed to get username');
	}
	// Do auth
	if ($ip !== '127.0.0.1' || $ip !== '::1')
	{
		$data = [
			'NAME' => urlencode($username),
			'IP' => urlencode($ip)
		];
		$options = [
			'http' => [
				'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
				'method' => 'POST',
				'content' => http_build_query($data)
			]
		];
		$context = stream_context_create($options);
		$result = str_replace(trim(strtolower(file_get_contents('https://auth.blockland.us/authQuery.php', false, $context))), "\n", '');
		if (strpos($result, 'yes') !== false)
		{
			$parts = explode(' ', $result);
			var_dump($parts);
			if ($parts[0] != $bl_id)
			{
				exit('FAIL Authserver returned different BL_ID than one sent');
			}
			// Successfully authed! Yay
		}
		elseif (strpos($result, 'error') !== false)
		{
			exit('FAIL Authserver had error');
		}
		elseif (strpos($result, 'no') !== false)
		{
			exit('FAIL Failed to auth');
		}
	}

	// Make server name
	if (substr($username, -1) == 's')
	{
		$serverName = $username . '\'' . $serverName;
	}
	else
	{
		$serverName = $username . '\'s ' . $serverName;
	}

	if (SERVERNAME_VERSION)
	{
		$serverName = '[v' . $version . '] ' . $serverName;
	}

	// finish checks, post the server
	$updating = false;
	$query = 'SELECT * FROM servers WHERE ip = :ip AND port = :port AND version = :version AND key_id = :key_id;';
	$statement = $GLOBALS['database']->prepare($query);
	$statement->bindParam(':ip', $ip, PDO::PARAM_STR);
	$statement->bindParam(':port', $serverPort, PDO::PARAM_INT);
	$statement->bindParam(':version', $version, PDO::PARAM_INT);
	$statement->bindParam(':key_id', $key_id, PDO::PARAM_STR);

	$statement->execute();
	if ($statement->rowCount() > 0)
	{
		$updating = true;
		$query = 'UPDATE servers SET port = :newPort, passworded = :newPasswordDefinition, dedicated = :newDedicatedDefinition, serverName = :newServerName, players = :newPlayerCount, maxPlayers = :newMaxPlayers, gamemode = :newGamemode, brickCount = :newBrickCount, version = :newVersion, uptime = :newUptime, bl_id = :newBLID, key_id = :newKeyID WHERE ip = :currentIp AND port = :currentPort AND key_id = :newKeyID;';
	}
	elseif ($statement->rowCount() >= SERVER_LIMIT)
	{
		exit('FAIL You can only have ' . SERVER_LIMIT . 'servers per version online at a time.');
	}
	else
	{
		$query = 'INSERT INTO servers(ip, port, passworded, dedicated, serverName, players, maxPlayers, gamemode, brickCount, version, uptime, bl_id, key_id) VALUES(:newIp, :newPort, :newPasswordDefinition, :newDedicatedDefinition, :newServerName, :newPlayerCount, :newMaxPlayers, :newGamemode, :newBrickCount, :newVersion, :newUptime, :newBLID, :newKeyID);';
	}

	$statement = $GLOBALS['database']->prepare($query);

	$statement->bindParam(':newPort', $serverPort, PDO::PARAM_INT);
	$statement->bindParam(':newPasswordDefinition', $isPassworded, PDO::PARAM_INT);
	$statement->bindParam(':newDedicatedDefinition', $isDedicated, PDO::PARAM_INT);
	$statement->bindParam(':newServerName', $serverName, PDO::PARAM_STR);
	$statement->bindParam(':newPlayerCount', $activePlayers, PDO::PARAM_INT);
	$statement->bindParam(':newMaxPlayers', $maxPlayers, PDO::PARAM_INT);
	$statement->bindParam(':newGamemode', $gameMode, PDO::PARAM_STR);
	$statement->bindParam(':newBrickCount', $brickCount, PDO::PARAM_INT);
	$statement->bindParam(':newVersion', $version, PDO::PARAM_INT);
	$statement->bindParam(':newUptime', $uptime, PDO::PARAM_STR);
	$statement->bindParam(':newBLID', $bl_id, PDO::PARAM_INT);
	$statement->bindParam(':newKeyID', $key_id, PDO::PARAM_STR);

	if ($updating)
	{
		$statement->bindParam(':currentIp', $ip, PDO::PARAM_STR);
		$statement->bindParam(':currentPort', $serverPort, PDO::PARAM_INT);
	}
	else
	{
		$statement->bindParam(':newIp', $ip, PDO::PARAM_STR);
	}

	$statement->execute();
	exit('SUCCESS ' . ($updating ? 'Successfully updated server!' : 'Successfully posted server!'));
?>
