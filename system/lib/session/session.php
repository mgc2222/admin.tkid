<?php
class Session
{
	const SK_MESSAGE = 'session_message';
	const SK_MESSAGE_STATUS = 'session_message_status';
	
	static function ClearSession()
	{
		session_destroy();	
	}
	
	static function SetKey($key, $value)
	{
		$_SESSION[$key] = $value;
	}
	
	static function GetKey($key, $defaultValue)
	{
		if (isset($_SESSION[$key]))
			return $_SESSION[$key];
		else return $defaultValue;
	}
	
	static function ClearKey($key)
	{
		if (isset($_SESSION[$key]))
			unset($_SESSION[$key]);
	}
	
	static function KeyExists($key)
	{
		return (isset($_SESSION[$key]));
	}
	
	static function KeyExistsNotEmpty($key)
	{
		$keyExists = false;
		
		if (isset($_SESSION[$key]))
			if ($_SESSION[$key] != '')
				$keyExists = true;
				
		return $keyExists;
	}
	
	static function KeyExistsNotNull($key)
	{
		$keyExists = false;
		
		if (isset($_SESSION[$key]))
			if ($_SESSION[$key] != null)
				$keyExists = true;
				
		return $keyExists;
	}
	
	static function SetFlashMessage($message, $status, $redirectUrl = null)
	{
		$_SESSION[self::SK_MESSAGE] = $message;
		$_SESSION[self::SK_MESSAGE_STATUS] = $status;
		
		if ($redirectUrl != null)
		{
			header('location:'.$redirectUrl);
			exit();
		}
	}
	
	static function GetFlashMessage()
	{
		$ret = new stdClass();
		$ret->Message = '';
		$ret->MessageStatus = '';
		if (isset($_SESSION[self::SK_MESSAGE]))
		{
			$ret->Message = $_SESSION[self::SK_MESSAGE];
			unset($_SESSION[self::SK_MESSAGE]);
			
			if (isset($_SESSION[self::SK_MESSAGE_STATUS]))
			{
				$ret->MessageStatus = $_SESSION[self::SK_MESSAGE_STATUS];
				unset($_SESSION[self::SK_MESSAGE]);
			}
		}
		
		return $ret;
	}
}
?>