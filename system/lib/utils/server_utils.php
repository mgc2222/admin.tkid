<?php 
class ServerUtils
{	
	static function GetUserIP()
	{
		if (isset($_SERVER['HTTP_X_FORWARD_FOR'])) 
			$userIP = $_SERVER['HTTP_X_FORWARD_FOR'];
		else if (isset($_SERVER['REMOTE_ADDR']))
			$userIP = $_SERVER['REMOTE_ADDR'];
		else
			$userIP = '';
			
		return $userIP;
	}
}
?>