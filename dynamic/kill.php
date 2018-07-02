<?php
	$query = 'SELECT * FROM servers;';
	$statement = $GLOBALS['database']->prepare($query);
	$statement->execute();
	$result = $statement->fetch(PDO::FETCH_ASSOC);
	$elapsed = time() - $result['uptime'];
	$deadIp = $result['ip'];
	$deadPort = $result['port'];
	$deadUptime = $result['uptime'];
	$deadKey = $result['key_id'];
	$statement = null;
	if ($elapsed > TIMEOUT)
	{
		$query = 'DELETE FROM servers WHERE ip = :deadIp AND port = :deadPort AND uptime = :deadUptime AND key_id = :deadKey;';
		// $query = 'UPDATE servers SET visible = 0 WHERE ip = :deadIp AND port = :deadPort AND uptime = :deadUptime AND mod_ = :deadMod;';
		$statement = $GLOBALS['database']->prepare($query);
		$statement->bindParam(':deadIp', $deadIp, PDO::PARAM_STR);
		$statement->bindParam(':deadPort', $deadPort, PDO::PARAM_INT);
		$statement->bindParam(':deadUptime', $deadUptime, PDO::PARAM_INT);
		$statement->bindParam(':deadKey', $deadKey, PDO::PARAM_STR);

		$statement->execute();
		$statement = null;
	}
?>
