<?php
class DboMysqli extends DboAbstract
{
	private $dblink = null;
	function __construct()
	{
		parent::__construct();
	}
	
	function Connect($host, $username, $password, $database, $defaultCharset)
	{
		try 
		{
			$this->dblink = mysqli_connect($host, $username, $password);
			mysqli_select_db($this->dblink, $database);
			if ($defaultCharset) {
				$this->SetCharset($defaultCharset);
			}
			// $this->dbh = new mysqli(_DB_HOST, _DB_USERNAME, _DB_PASSWORD, _DB_DATA_BASE);
		}
		catch (Exception $e)
		{
			die('Connection failed: ' . $e->getMessage());
		}
	}
	
	private function SetCharset($charset)
	{
		if (!function_exists('mysqli_set_charset')) {
			$this->Query("SET names {$charset}");
		}
		else {
			mysqli_set_charset($this->dblink, $charset);
		}
	}
	
	function Fetch(&$query)
	{
		$ret = null;
		if ($query)
		{
			switch ($this->fetchMethod)
			{
				case 'object': $ret = $query->fetch_object(); break;
				case 'array': $ret = $query->fetch_array(); break;
				case 'field': $ret = $query->fetch_field(); break;
				case 'assoc': $ret = $query->fetch_assoc(); break;
				case 'row': $ret = $query->fetch_row(); break;
			}
		}
		
		return $ret;
	}
	
	function Query($sql)
	{
		// $query = $this->dbh->query($sql);
		$query = mysqli_query($this->dblink, $sql);
		if (!$query)
			$this->reportError($sql, mysqli_error($this->dblink));
		return $query;
	}
	
	function MultiQuery($sql)
	{
		// $query = $this->dbh->multi_query($sql);
		$query = mysqli_multi_query($this->dblink, $sql);
		
		if (!$query)
			die('query error in: '.$this->caller.'<br/>Sql Error:'.mysqli_error($this->dblink));
		return $query;
	}
	
	function GetFieldInfo($sql, $fieldIndex=0)
	{
		$fieldValue = false;
		$query = $this->Query($sql);
	
		if ($query)
		{
			$fIndex = 0;
			$fieldFound = false;
			while ($fieldValue = $query->fetch_field())
			{
				if ($fIndex == $fieldIndex)
				{
					$fieldFound = true;
					break;
				}
				$fIndex++;
			}
			
			if (!$fieldFound) $fieldValue = false;
		}
		
		return $fieldValue;
	}
	
	// return first field value
	function GetFieldValue($sql)
	{
		$query = $this->Query($sql);
		$row = mysqli_fetch_array($query);
		if ($row == null) return null;
		return $row[0];
	}
	
	function GetFirstRow($sql)
	{
		// $query = $this->dbh->query($sql);
		$query = $this->Query($sql);
		$row = $this->Fetch($query);
		return $row;
	}
	
	function GetRows($sql)
	{
		// $query = $this->dbh->query($sql);	
		$query = $this->Query($sql);
		$rows = array();
		
		while ($row = $this->Fetch($query))
		{
			array_push($rows, $row);
		}
		
		if (count($rows) == 0) $rows = null;
		
		return $rows;
	}
	
	function GetMultipleResults($sql)
	{
		$query = $this->MultiQuery($sql);
		$datasets = array();
		$setIndex = 0;
		
		while ($result = mysqli_store_result($this->dblink))
		{
			$rows = array();
			
			while ($row = $this->Fetch($result))
			{
				array_push($rows, $row);
			}
			
			mysqli_free_result($result);
			array_push($datasets, $rows);
			
			if (!mysqli_more_results($this->dblink)) break;
			mysqli_next_result($this->dblink);
			
			$setIndex++;
			if ($setIndex > 50) break;
		}
		
		echo $setIndex;
		
		if (count($datasets) == 0) $datasets = null;
		
		return $datasets;
	}
	
	function SaveRow($row, $makeSafeValues, $stripTags)
	{
		$sql = $this->BuildSaveSql($row, $makeSafeValues, $stripTags);
		if ($sql == '') // if no sql created
			return 0;

		$recordId = 0;
				
		if (!$this->Query($sql))
			return $recordId;
			
		$recordId = ($row->_RecordId == 0)? mysqli_insert_id($this->dblink):$row->_RecordId;
		return $recordId;
	}
	
	
	function InsertRow($table, $arrFields)
	{	
		$sql = $this->BuildInsertSql($table, $arrFields);
		// echo $sql.'<br/>';
		if  (!$this->Query($sql)) return 0;
		else return mysqli_insert_id($this->dblink);
	}
	
	function EscapeString($val)
	{
		return mysqli_real_escape_string($this->dblink, $val);
	}
}
?>