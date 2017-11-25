<?php
	function getIp()
	{
		if (isset($_SERVER['HTTP_CF_CONNECTING_IP']))
		{
			if ($_SERVER['REMOTE_ADDR'] != $_SERVER['HTTP_CF_CONNECTING_IP'])
			{
				return $_SERVER['HTTP_CF_CONNECTING_IP'];
			}
			else
			{
				return $_SERVER['REMOTE_ADDR'];
			}
		}
		else
		{
			return $_SERVER['REMOTE_ADDR'];
		}
	}
	// from stackoverflow
	function generateRandomString($length = 32)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

?>