<?php
class DboPDO extends DboAbstract
{
	protected $fetchArgs;
	function __construct()
	{
		parent::__construct();
	}
	
	function Connect($host, $username, $password, $database, $defaultCharset)
	{
		$dsn = 'mysql:dbname='.$database.';host='.$host;
		// for utf8 encoding, use: $dbh = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
		$optionsArray = $defaultCharset ? array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '{$defaultCharset}'") : null;
		try { $this->dbh = new PDO($dsn, $username, $password, $optionsArray); } 
		catch (PDOException $e) { die('Connection failed: ' . $e->getMessage()); }
	}
	
	function SetFetchMethod($method, $args)
	{
		$this->fetchMethod = $method;
		$this->fetchArgs = $args;
	}
	
	function FetchModePDO(&$query)
	{
		$fArgs = null;
		$fetchCompare = ($this->fetchMethod & PDO::FETCH_CLASS);
		if ($this->fetchMethod == PDO::FETCH_INTO || $this->fetchMethod == PDO::FETCH_COLUMN)
		{
			$fArgs = array($this->fetchMethod, $this->fetchArgs);
		}
		else if ($fetchCompare === PDO::FETCH_CLASS)
		{
			$args = $this->fetchArgs;
			$fName = array_shift($args); 
			$fArgs = array($this->fetchMethod, $fName, $args);
		}
			
		call_user_func_array(array($query, 'setFetchMode'), $fArgs);
		return $query->fetch();
	}
	
	function Fetch(&$query)
	{
		$ret = null;
		if ($query)
		{
			switch ($this->fetchMethod)
			{
				case 'object': $ret = $query->fetchObject(); break;
				case 'column': $ret = $query->fetchColumn(0); break;
				case 'all': $ret = $query->fetchAll(); break;
				default: $ret = $this->FetchModePDO($query); break;
			}
		}
		
		return $ret;
	}

	function Query($sql)
	{
		$query = $this->dbh->query($sql);
		if (!$query)
		{
			$this->reportError($sql, $this->dbh->errorInfo());
		}
		return $query;
	}
	
	function MultiQuery($sql)
	{
		return $this->Query($sql);
	}

	function GetFieldInfo($sql, $fieldIndex=0)
	{
		$fieldValue = false;
		$res = $this->Query($sql);
	
		if ($res)
			$fieldValue = $res->getColumnMeta($fieldIndex);
		
		return $fieldValue;
	}
	
	// return first field value
	function GetFieldValue($sql)
	{
		$query = $this->Query($sql);
		$val = $query->fetchColumn(0);
		return $val;
	}
	
	function GetFirstRow($sql)
	{
		$query = $this->Query($sql);
		$row = $this->Fetch($query);
		return $row;
	}
	
	function GetRows($sql)
	{
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
		$query = $this->Query($sql);
		$datasets = array();
		$setIndex = 0;
		
		while (true)
		{
			$rows = array();
			while ($row = $this->Fetch($query))
			{
				array_push($rows, $row);
			}
			
			array_push($datasets, $rows);
			if (!$query->nextRowset()) break;
			
			$setIndex++;
			if ($setIndex > 50) break; // safe exit, max 50 results
		}
		
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
			
		$recordId = ($row->_RecordId == 0)? $this->dbh->lastInsertId():$row->_RecordId;
		return $recordId;
	}
	
	
	function InsertRow($table, $arrFields)
	{
		$sql = $this->BuildInsertSql($table, $arrFields);
		if  (!$this->Query($sql)) return 0;
		else return $this->dbh->lastInsertId();
	}
	
	function EscapeString($val)
	{
		if ($val != '')
		{
			$val = $this->dbh->quote($val);
			$val = substr($val, 1);
			$val = substr($val, 0, strlen($val) - 1);
			return $val;
		}
		else return $val;
		// return $val;  // no quoting is needed for pdo
		// return $this->dbh->quote($val);
	}
}
?>