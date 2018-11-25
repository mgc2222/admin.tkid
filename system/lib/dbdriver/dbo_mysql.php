<?php
class DboMysql extends DboAbstract
{
	function __construct()
	{
		parent::__construct();
	}
	
	function Connect($host, $username, $password, $database, $defaultCharset)
	{
		try 
		{
			mysql_connect($host, $username, $password);
			mysql_select_db($database);
			if ($defaultCharset) {
				$this->SetCharset($defaultCharset);
			}
		}
		catch (Exception $e)
		{
			die('Connection failed: ' . $e->getMessage());
		}
	}
	
	private function SetCharset($charset)
	{
		if (!function_exists('mysql_set_charset')) {
			$this->Query("SET names {$charset}");
		}
		else {
			mysql_set_charset($charset);
		}
	}
	
	function Fetch(&$query)
	{
		$ret = null;
		if ($query)
		{
			switch ($this->fetchMethod)
			{
				case 'object': $ret = mysql_fetch_object($query); break;
				case 'array': $ret = mysql_fetch_array($query); break;
				case 'field': $ret = mysql_fetch_field($query); break;
				case 'assoc': $ret = mysql_fetch_assoc($query); break;
				case 'row': $ret = mysql_fetch_row($query); break;
			}
		}
		
		return $ret;
	}
	
	function Query($sql)
	{
		$query = mysql_query($sql);
		if (!$query)
			$this->reportError($sql, mysql_error());
		return $query;
	}
		
	function MultiQuery($sql)
	{
		die('Multiquery is not supported in mysql. Use mysqli or PDO !');
	}
	
	function GetFieldInfo($sql, $fieldIndex=0)
	{
		$fieldValue = false;
		$res = $this->Query($sql);
	
		if ($res)
			$fieldValue = mysql_fetch_field($res, $fieldIndex);
		
		return $fieldValue;
	}
	
	// return first field value
	function GetFieldValue($sql)
	{
		$res = $this->Query($sql);
		return @mysql_result($res, 0);
	}
	
	function GetFirstRow($sql)
	{
		//$res = mysql_query($sql) or die ($sql."(".mysql_error().")");
		$res = $this->Query($sql);
		$objData = null;
	
		if ($res)
			$objData = $this->Fetch($res);
		
		return $objData;
	}
	
	function GetRows($sql)
	{
		$res = $this->Query($sql);
		$items = null;
			
		if ($res)
		{
			$recordsCount = mysql_num_rows($res);
			$items = array();
			
			for ($i = 0; $i < $recordsCount; $i++) 
			{
				$items[$i] = $this->Fetch($res);
			}
		}
		
		return $items;
	}
	
	function GetMultipleResults($sql)
	{
		die('Multiquery is not supported in mysql. Use mysqli or PDO !');
	}
	
	function SaveRow($row, $makeSafeValues, $stripTags)
	{
		$sql = $this->BuildSaveSql($row, $makeSafeValues, $stripTags);
		if ($sql == '') // if no sql created
			return 0;
		
		$recordId = 0;
				
		if (!$this->Query($sql))
			return $recordId;
			
		$recordId = ($row->_RecordId == 0)? mysql_insert_id():$row->_RecordId;
		return $recordId;
	}
	
	function InsertRow($table, $arrFields)
	{
		$sql = $this->BuildInsertSql($table, $arrFields);
		if  (!$this->Query($sql)) return 0;
		else return mysql_insert_id();
	}

	function EscapeString($val)
	{
		return mysql_real_escape_string($val);
	}
}
?>