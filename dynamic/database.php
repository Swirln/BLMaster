<?php
	//try
	//{
		try
		{
			$database = new PDO("mysql:host=". databaseHost .";port=". databasePort .";dbname=blmaster", databaseUsername, databasePassword);
			if (debugging == false)
			{
				$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
			}
			$database->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}
		catch (PDOException $e)
		{
			if ($e->getMessage() == "SQLSTATE[HY000] [1049] Unknown database 'blmaster'")
			{
				try
				{
					// Create the database
					$database = new PDO("mysql:host=". databaseHost ."", databaseUsername, databasePassword);
					$database->exec("CREATE DATABASE `blmaster`; CREATE USER '". databaseUsername."'@'" . databaseHost ."' IDENTIFIED BY '". databasePassword ."'; GRANT ALL ON `blmaster`.* TO '". databaseUsername ."'@'". databaseHost ."'; FLUSH PRIVILEGES;");
					// Close the database connection and reload
					$database = null;
					$database = new PDO("mysql:host=". databaseHost .";port=". databasePort .";dbname=blmaster", databaseUsername, databasePassword);
					if (debugging == false)
					{
						$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
					}
					else
					{
						$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					}
					$database->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
					// create the tables...
				    $query = '
							CREATE TABLE `servers` (
							  `ip` varchar(256) NOT NULL,
							  `port` int(5) NOT NULL,
							  `passworded` int(1) NOT NULL,
							  `dedicated` int(1) NOT NULL,
							  `servername` longtext NOT NULL,
							  `players` longtext NOT NULL,
							  `maxplayers` longtext NOT NULL,
							  `gamemode` longtext NOT NULL,
							  `mod_` longtext NOT NULL,
							  `allowDemoPlayers` longtext NOT NULL,
							  `brickcount` int(11) NOT NULL,
							  `csg` longtext NOT NULL,
							  `ver` longtext NOT NULL,
							  `build` longtext NOT NULL,
							  `uptime` int(11) NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=latin1;
							';

					$statement = $database->prepare($query);
					$statement->execute();
				}
				catch (PDOException $e)
				{
					if ($_SERVER['REQUEST_URI'] == "index.php")
					{
						echo "localhost\t28000\t0\t0\tThe masterserver is currently down\ttry\tagain\tthanks\tlater\r\n";
						exit();
					}
					elseif ($_SERVER['REQUEST_URI'] == "postServer.php")
					{
						echo "FAIL Masterserver is down";
						exit();
					}
					else
					{
						if (debugging == false)
						{
							echo "The masterserver is currently down. Sorry about that!";
						}
						else
						{
							echo "Masterserver is currently down<br>";
							echo "Reason: ". $e->getMessage();
							exit();
						}
					}
				}
			}
			else
			{
				if ($_SERVER['REQUEST_URI'] == "index.php")
				{
					echo "localhost\t28000\t0\t0\tThe masterserver is currently down\ttry\tagain\tthanks\tlater\r\n";
					exit();
				}
				elseif ($_SERVER['REQUEST_URI'] == "postServer.php")
				{
					echo "FAIL Masterserver is down";
					exit();
				}
				else
				{
					if (debugging == false)
					{
						echo "The masterserver is currently down. Sorry about that!";
					}
					else
					{
						echo "Masterserver is currently down<br>";
						echo "Reason: ". $e->getMessage();
						exit();
					}
				}
			}
		}
?>