<?php
	header('Content-Type: text/plain');

	// Key logic by Clay Hanson
	function toBase($numberInput, $fromBaseInput, $toBaseInput)
	{
		if ($fromBaseInput == $toBaseInput)
		{
			return $numberInput;
		}

		$fromBase = str_split($fromBaseInput, 1);
		$toBase = str_split($toBaseInput, 1);
		$number = str_split($numberInput, 1);

		$fromLen = strlen($fromBaseInput);
		$toLen = strlen($toBaseInput);
		$numberLen = strlen($numberInput);

		$retValue = '';

		if ($toBaseInput == '0123456789')
		{
			$retValue = 0;
			for ($i = 1; $i <= $numberLen; $i++)
			{
				$retValue = bcadd($retValue, bcmul(array_search($number[$i - 1], $fromBase), bcpow($fromLen, $numberLen - $i)));
			}
			return $retValue;
		}

		if ($fromBaseInput != '0123456789')
		{
			$base10 = toBase($numberInput, $fromBaseInput, '0123456789');
		}
		else
		{
			$base10 = $numberInput;
		}

	  if ($base10 < $toLen)
		{
			return $toBase[$base10];
		}

		while ($base10 != '0')
		{
			$retval = $toBase[bcmod($base10, $toLen)] . $retValue;
			$base10 = bcdiv($base10, $toLen, 0);
		}
		return $retValue;
	}

	function idToKey($id)
	{
		$base = 'AAAAA' . toBase($id, '0123456789', 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789');
		$base = substr($base, strlen($base) - 5, 5);
		return $base;
	}

	function keyToID($key)
	{
		if (strlen($str) != 5)
		{
			return false;
		}
	  $key = strtoupper($key);
		$alphabet = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
		$id = 0;
		$value = 0;
		for ($i = 0; $i < strlen($key); $i++)
		{
			for ($x = 0; $x < strlen($alphabet); $x++)
			{
				if (substr($key, $i, 1) == substr($alphabet, $x, 1))
				{
					$value = $x;
					break;
				}
			}
			$id += pow(32, strlen($key) - ($i + 1)) * $value;
		}
		return $id;
	}

	require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamic/configuration.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamic/database.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/dynamic/kill.php');

	if (isset($_SERVER['HTTP_CF_CONNECTING_IP']))
	{
		if ($_SERVER['REMOTE_ADDR'] != $_SERVER['HTTP_CF_CONNECTING_IP'])
		{
			define('IP', $_SERVER['HTTP_CF_CONNECTING_IP']);
		}
		else
		{
			define('IP', $_SERVER['REMOTE_ADDR']);
		}
	}
	else
	{
		define('IP', $_SERVER['REMOTE_ADDR']);
	}

	if (DEBUGGING)
	{
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}
	else
	{
		error_reporting(0);
	}

	date_default_timezone_set(TIMEZONE);
?>
