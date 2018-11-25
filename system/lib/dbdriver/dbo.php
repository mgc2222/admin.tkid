<?php
class DBO
{
	private $dbclass = null;
	private static $singleton_instance = null;
	
	function __construct()
	{
		
	}
	
	// gets a global instance of this class
	public static function global_instance()
	{
		static $singleton_instance = null;
		if ($singleton_instance === null)
			$singleton_instance = new DBO();
         
        return $singleton_instance;
    }
	
	// connect to database troguh the specified driver
	public function Connect($driver, $host, $username, $password, $database, $defaultCharset = 'utf8')
	{
		if ($this->dbclass) return true;
		switch ($driver)
		{
			case 'mysql': $this->dbclass = new DboMysql(); break;
			case 'mysqli': 	$this->dbclass = new DboMysqli(); break;
			case 'pdo':	$this->dbclass = new DboPDO();	break;
		}
		
		$this->dbclass->Connect($host, $username, $password, $database, $defaultCharset);
		return true;
	}
	
	public function SetFetchMethod($method, $args = null) 
	{ 
		$this->dbclass->SetFetchMethod($method, $args);
	}
	
	public function Fetch(&$query)
	{
		return $this->dbclass->Fetch($query);
	}
	
	public function Query($sql)
	{
		return $this->dbclass->Query($sql);
	}
	
	public function MultiQuery($sql)
	{
		return $this->dbclass->MultiQuery($sql);
	}
	
	public function GetFieldInfo($sql, $fieldIndex=0)
	{
		return $this->dbclass->GetFieldInfo($sql, $fieldIndex);
	}
	
	// return first field value
	public function GetFieldValue($sql)
	{
		return $this->dbclass->GetFieldValue($sql);
	}
	
	public function GetFirstRow($sql)
	{
		return $this->dbclass->GetFirstRow($sql);
	}
	
	public function GetRows($sql)
	{
		return $this->dbclass->GetRows($sql);
	}
	
	public function GetMultipleResults($sql)
	{
		return $this->dbclass->GetMultipleResults($sql);
	}
	
	public function SaveRow(&$row, $makeSafeValues = true, $stripTags = false)
	{
		return $this->dbclass->SaveRow($row, $makeSafeValues, $stripTags);
	}
	
	public function UpdateRow($table, $arrFields, $arrWhere)
	{
		return $this->dbclass->UpdateRow($table, $arrFields, $arrWhere);
	}
	
	public function InsertRow($table, $arrFields)
	{
		return $this->dbclass->InsertRow($table, $arrFields);
	}
	
	public function InsertRowsBulk($table, $arrFields, $arrRows, $bulkSize = 50)
	{
		return $this->dbclass->InsertRowsBulk($table, $arrFields, $arrRows, $bulkSize);
	}
	
	public function UpdateRowsBulk($table, $arrFields, $arrRows, $arrWhere, $bulkSize = 50)
	{
		return $this->dbclass->UpdateRowsBulk($table, $arrFields, $arrRows, $arrWhere, $bulkSize);
	}
	
	public function ToggleField($table, $fieldName, $arrWhere)
	{
		return $this->dbclass->ToggleField($table, $fieldName, $arrWhere);
	}
	
	// $retType : 0 - returns an array with rows; 1 - return first row; 2 - return single value
	public function SelectData($table, $fields, $arrWhere = null, $order = null, $limit = null, $retType = 0)
	{
		return $this->dbclass->SelectData($table, $fields, $arrWhere, $order, $limit, $retType);
	}
	
	public function SelectRow($table, $fields, $arrWhere = null, $order = null)
	{
		return $this->dbclass->SelectRow($table, $fields, $arrWhere, $order);
	}
	
	public function SelectValue($table, $fields, $arrWhere = null, $order = null)
	{
		return $this->dbclass->SelectValue($table, $fields, $arrWhere, $order);
	}
	
	public function DeleteRows($table, $arrWhere = null, $limit = null)
	{
		return $this->dbclass->DeleteRows($table, $arrWhere, $limit);
	}
	
	public function DeleteRow($table, $arrWhere = null)
	{
		return $this->dbclass->DeleteRows($table, $arrWhere, 1);
	}
	
	public function DeleteRowsWithFiles($table, $dbFields, $filePath, $subPaths = null, $arrWhere = null, $limit = null)
	{
		return $this->dbclass->DeleteRowsWithFiles($table, $dbFields, $filePath, $subPaths, $arrWhere, $limit);
	}
	
	public function DeleteFile($table, $dbField, $filePath, $subPaths = null, $arrWhere = null)
	{
		return $this->dbclass->DeleteFile($table, $dbField, $filePath, $subPaths, $arrWhere);
	}
	
	public function TruncateTable($table)
	{
		return $this->dbclass->TruncateTable($table);
	}
		
	public function BuildSelectSql($table, $fields = '*', $condition = null, $order = null, $limit = null)
	{
		return $this->dbclass->BuildSelectSql($table, $fields, $condition, $order, $limit);
	}
	
	public function GetSafeValue($val, $stripTags = true) { return $this->dbclass->GetSafeValue($val, $stripTags); }
	
	protected function EscapeString($val) { return $this->dbclass->EscapeString($val); }
}
?>