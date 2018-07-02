<?php
	try
	{
		$database = new PDO('mysql:host='. DATABASE_HOST .';port='. DATABASE_PORT .';dbname='. DATABASE_NAME, DATABASE_USER, DATABASE_PASS);
		if (!DEBUGGING)
		{
			$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
		}
		else
		{
			$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		$database->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}
	catch (PDOException $e)
	{
		if (strpos(strtolower($e->getMessage()), 'unknown database \''. DATABASE_NAME .'\'') !== false)
		{
			try
			{
				$database = new PDO('mysql:host='. DATABASE_HOST .'', DATABASE_USER, DATABASE_PASS);
				$database->exec('CREATE DATABASE `'. DATABASE_NAME .'`; CREATE USER \''. DATABASE_USER.'\'@\'' . DATABASE_HOST .'\' IDENTIFIED BY \''. DATABASE_PASS .'\'; GRANT ALL ON `'. DATABASE_NAME .'`.* TO \''. DATABASE_USER .'\'@\''. DATABASE_HOST .'\'; FLUSH PRIVILEGES;');

				$database = null;
				$database = new PDO('mysql:host='. DATABASE_HOST .';port='. DATABASE_PORT .';dbname='. DATABASE_NAME, DATABASE_USER, DATABASE_PASS);
				if (!DEBUGGING)
				{
					$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
				}
				else
				{
					$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				}
				$database->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$query = "
						CREATE TABLE `servers` (
						 `ip` varchar(45) NOT NULL COMMENT 'The IP address of the server.',
						 `port` smallint(6) NOT NULL COMMENT 'The port of the server.',
						 `passworded` int(1) NOT NULL COMMENT 'Whether the server is passworded or not.',
						 `dedicated` int(1) NOT NULL COMMENT 'Whether the server is dedicated or not.',
						 `serverName` tinytext NOT NULL COMMENT 'The server''s name.',
						 `players` smallint(6) NOT NULL COMMENT 'The players currently on the server.',
						 `maxPlayers` smallint(6) NOT NULL COMMENT 'The maximum players allowed on the server.',
						 `gamemode` tinytext NOT NULL COMMENT 'The gamemode (or map name) of the server.',
						 `brickCount` int(11) NOT NULL COMMENT 'The bricks built on the server.',
						 `version` int(64) NOT NULL COMMENT 'The version of Blockland the server is on.',
						 `uptime` bigint(20) NOT NULL COMMENT 'A Unix timestamp of the last time the server posted to the masterserver.',
						 `bl_id` mediumint(9) NOT NULL COMMENT 'The host''s BL_ID.',
						 `key_id` tinytext NOT NULL COMMENT 'The first five characters of the host''s key.'
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;
				";

				$statement = $database->exec($query);
				$statement = null;
			}
			catch (PDOException $e)
			{
				if ($_SERVER['REQUEST_URI'] == 'postServer.php')
				{
					exit('FAIL Masterserver is down');
				}
				else
				{
					if (!DEBUGGING)
					{
						exit('localhost\t28000\t0\t0\tThe masterserver is currently down\ttry\tagain\tthanks\tlater\r\n');
					}
					else
					{
						exit('Masterserver is currently down<br>Reason: '. $e->getMessage());
					}
				}
			}
		}
		else
		{
			if ($_SERVER['REQUEST_URI'] == 'postServer.php')
			{
				exit('FAIL Masterserver is down');
			}
			else
			{
				if (!DEBUGGING)
				{
					exit('localhost\t28000\t0\t0\tThe masterserver is currently down\ttry\tagain\tthanks\tlater\r\n');
				}
				else
				{
					exit('Masterserver is currently down<br>Reason: '. $e->getMessage());
				}
			}
		}
	}
?>
