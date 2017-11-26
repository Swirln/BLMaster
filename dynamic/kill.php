<?php
	function killDead()
	{
		$query = "SELECT * FROM servers;";
		$statement = $GLOBALS['database']->prepare($query);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		$elapsed = time() - $result['uptime'];
		$deadIp = $result['ip'];
		$deadPort = $result['port'];
		$deadUptime = $result['uptime'];
		if ($elapsed > timeout)
		{
			$query = "DELETE FROM servers WHERE ip = :deadIp AND port = :deadPort AND uptime = :deadUptime";
			// We shouldn't just *delete* it because auto increment hates me
			// Actually forget auto increment delete it anyway
			//$query = "UPDATE servers SET visible = 0 WHERE ip = :deadIp AND port = :deadPort AND uptime = :deadUptime AND mod_ = :deadMod;";
			$statement = $GLOBALS['database']->prepare($query);
			$statement->bindParam(":deadIp", $deadIp, PDO::PARAM_STR);
			$statement->bindParam(":deadPort", $deadPort, PDO::PARAM_INT);
			$statement->bindParam(":deadUptime", $deadUptime, PDO::PARAM_INT);
			
			$statement->execute();
		}
	}
?>