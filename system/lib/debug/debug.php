<?php
class Debug
{
	var $time_start;
	
	function MicrotimeFloat()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	function StartTimer()
	{
		$this->time_start = $this->MicrotimeFloat();
	}
	
	function GetElapsedTime()
	{
		$time_end = $this->MicrotimeFloat();
		$time = $time_end - $this->time_start;
		return $time;
	}
}
?>