<?php
class DboAbstract
{
	var $dbh;
	var $caller;
	var $fetchMethod;
	
	function __construct()	{ $this->fetchMethod = 'object'; }
	
	function reportError($sql, $error)
	{
		print_r($error);
		echo '<br/><br/>SQL:'.$sql.'<br/>';
		$arrDebug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		array_walk($arrDebug, array('DboAbstract', 'traceError'));
		die();
	}
	
	function traceError($a, $b)	{ echo "<br/> File : {$a['file']} | function :  {$a['function']}() | line : {$a['line']}); "; }
	
	function Connect($host, $username, $password, $database, $defaultCharset) { }
	
	function SetFetchMethod($method, $args) {	$this->fetchMethod = $method; } // method can be 'object' or 'array'
	
	function Fetch(&$query) { }
	
	function Query($sql) {	}
	
	function MultiQuery($sql) { }

	function GetFieldInfo($sql, $fieldIndex=0)	{	}
	
	// return first field value
	function GetFieldValue($sql) {	}
	
	function GetFirstRow($sql)	{	}
	
	function GetRows($sql)	{	}
	
	function GetMultipleResults($sql) {	}
	
	function SaveRow($row, $makeSafeValues, $stripTags)	{	}
	
	function BuildSaveSql($row, $makeSafeValues, $stripTags)
	{
		$arrFields = array();
		foreach ($row as $fieldName=>$fieldValue)
		{
			if ($fieldName != '_TableName' && $fieldName != '_Condition' && $fieldName != '_RecordId')
			{
				if ($fieldValue === null)
					$arrFields[$fieldName] = '[NULL]';
				else
				{
					$fieldSafeValue = $this->MakeSafeValue($fieldValue, $makeSafeValues, $stripTags);
					$arrFields[$fieldName] = $fieldSafeValue;
				}
			}
		}
		
		if (count($arrFields) == 0)
			die('dbo_template : BuildSaveSql : No fields specified for sql save');
			
		if ($row->_Condition == null)
		{
			$sql = $this->BuildInsertSql($row->_TableName, $arrFields);
		}
		else
		{
			$sql = $this->BuildUpdateSql($row->_TableName, $arrFields, $row->_Condition);
		}
		
		return $sql;
	}
	
	function MakeSafeValue($fieldValue, $makeSafeValues, $stripTags)
	{
		$fieldSafeValue = $fieldValue;
		
		if ($makeSafeValues)
			$fieldSafeValue = $this->GetSafeValue($fieldValue, $stripTags);
			
		// function exceptions: place them between brackets: [NOW()]
		// if (strlen($fieldValue) > 0 && $fieldValue[0] == '[' && $fieldValue[strlen($fieldValue) - 1] == ']')
			// $fieldSafeValue = substr($fieldValue, 1, strlen($fieldValue) - 2);
		
		return $fieldSafeValue;
	}
	
	function GetSafeValue($fieldValue, $stripTags)
	{
		$fieldSafeValue = trim($fieldValue);
				
		if (!get_magic_quotes_gpc())
		{
			$fieldSafeValue = $this->EscapeString(stripslashes($fieldSafeValue));
		}
		else
			$fieldSafeValue = $this->EscapeString(stripslashes($fieldSafeValue));
			
		if ($stripTags)
			$fieldSafeValue = strip_tags($fieldSafeValue);
			
		return $fieldSafeValue;
	}
	
	function UpdateRow($table, $arrFields, $arrWhere)
	{
		$sql = $this->BuildUpdateSql($table, $arrFields, $arrWhere);

        $pdoStatement = $this->Query($sql);

        return $pdoStatement->rowCount();

	}
	
	function InsertRow($table, $arrFields)	{	}
	
	// $table : name of the table that will be updated
	// $arrFields: array with all the fields to be updated; if primary key is autoincrement, don't include it or include it and set it's value to null in the rows array
	// $arrRows: array with the values, in the same order as the fields; 
	// example: InsertRowsBulk('table_name', array(name, description), array( array('t1', 't1 desc'), array('t2', 't2 desc')))
	function InsertRowsBulk($table, $arrFields, $arrRows, $bulkSize)
	{	
		$arrSql = $this->BuildInsertBulkSql($table, $arrFields, $arrRows, $bulkSize);
		if (count($arrSql) > 0)
		{
			foreach ($arrSql as $sql)
			{
				// echo $sql;
				$this->Query($sql);
			}
		}
	}
	
	// $table : name of the table that will be updated
	// $arrFields: array with all the fields to be updated, including the primary key(s)
	// $arrWhere : must be an array with the primary key(s) that are joined. The tables aliases are 't' and 'v'
	// example: UpdateRowsBulk('table_name', array(id, name, description), array( array(1, 't1', 't1 desc'), array(2, 't2', 't2 desc')), array('t.id'=>'v.id'))
	function UpdateRowsBulk($table, $arrFields, $arrRows, $arrWhere, $bulkSize)
	{
		$arrSql = $this->BuildUpdateBulkSql($table, $arrFields, $arrRows, $arrWhere, $bulkSize);
		if (count($arrSql) > 0)
		{
			foreach ($arrSql as $sql)
			{
				$this->Query($sql);
			}
		}
	}
	
	// in arrFields and arrWhere, for columns or keywords, use brackets; i.e.  $field=>'[field + 1]'
	function BuildUpdateSql($table, $arrFields, $arrWhere)
	{
		$sql = 'UPDATE '.$this->EncodeNameForDb($table).' SET ';
		$sqlValues = '';
		foreach ($arrFields as $key=>$val)
		{
			$sqlVal = $this->GetSqlValue($val);
			$sqlValues .= ", `{$key}` = {$sqlVal}";
		}
		$sqlValues = substr($sqlValues, 2);
		$sql .= $sqlValues.$this->GetCondition($arrWhere);
		
		return $sql;
	}
	
	function BuildInsertSql($table, $arrFields)
	{
		$sql =  'INSERT INTO '.$this->EncodeNameForDb($table).' ';
		$sqlFields = '';
		$sqlValues = '';

		foreach ($arrFields as $key=>$val)
		{
			$sqlFields .= ", `{$key}`";
			$sqlValues .= ', '.$this->GetSqlValue($val);
		}
		$sqlFields = substr($sqlFields, 2);
		$sqlValues = substr($sqlValues, 2);
		$sql .= "({$sqlFields}) VALUES ({$sqlValues})";
		
		return $sql;
	}
	
	function GetSqlValue($val)
	{
		// for columns or keywords, use brackets; i.e.  $field=>'[field + 1]'
		if (strlen($val) > 0 && $val[0] == '[' && $val[strlen($val) - 1] == ']')
		{
			$val = substr($val, 1, strlen($val) - 2);
		}
		else
			$val = "'{$val}'";
		
		return $val;
	}
	
	// for columns or keywords, use brackets; i.e.  $field=>'[field + 1]'
	function BuildInsertBulkSql($table, $arrFields, $arrRows, $bulkSize = 50)
	{
		if ($arrRows == null) return false;
		
		$arrSql = array();
		
		$sqlInsert =  'INSERT INTO '.$this->EncodeNameForDb($table).' ';
		$sqlFields = $this->GetSqlFieldsFromArray($arrFields);
		
		$sqlInsert .= '('.$sqlFields. ') VALUES ';
		
		$sqlValues = '';
		$rowIndex = 0;
		$rowsCount = count($arrRows);
		foreach ($arrRows as &$row)
		{
			$sqlValuesRow = '';
			foreach ($row as $val)
			{
				$sqlValuesRow .= ','.$this->GetSqlValue($val);
			}
						
			$sqlValuesRow = substr($sqlValuesRow, 1);
			$sqlValues .=  ',('.$sqlValuesRow.')';
						
			if (($rowIndex > 0 && ($rowIndex % $bulkSize) == 0) || $rowIndex == $rowsCount - 1)
			{
				$sqlValues = substr($sqlValues, 1);
				array_push($arrSql, $sqlInsert." {$sqlValues}");
				
				$sqlValues = '';
			}
			
			$rowIndex++;
		}

		return $arrSql;
	}
	
	function BuildUpdateBulkSql($table, $arrFields, $arrRows, $arrWhere, $bulkSize = 50)
	{
			// UPDATE my_table m
// JOIN (
// SELECT 1 as id, 10 as _col1, 20 as _col2
// UNION ALL
// SELECT 2, 5, 10
// UNION ALL
// SELECT 3, 15, 30
// ) vals ON m.id = vals.id
// SET col1 = _col1, col2 = __col2;

		if ($arrRows == null) return false;
		
		$arrSql = array();
		
		$sqlUpdate =  'UPDATE '.$this->EncodeNameForDb($table).' t JOIN (';
		$sqlFields = $this->GetSqlFieldsFromArray($arrFields);
		
		$sqlValues = '';
		$rowIndex = 0;
		$rowsCount = count($arrRows);
		foreach ($arrRows as &$row)
		{
			$sqlValues .= ' SELECT ';
			$fieldIndex = 0;
			foreach ($row as $val)
			{
				$fieldName = $arrFields[$fieldIndex];
				$alias = ($rowIndex == 0)?" as `{$fieldName}`":'';
			
				$sqlVal = $this->GetSqlValue($val);
				$sqlValues .= " {$sqlVal}{$alias}, ";

				$fieldIndex++;
			}
			
			$sqlValues = substr($sqlValues, 0, strlen($sqlValues) - 2);
			
			if (($rowIndex > 0 && ($rowIndex % $bulkSize) == 0) || $rowIndex == $rowsCount - 1)
			{
				$sqlValues .= ') v ON '.$this->GetConditionForBulkUpdate($arrWhere). ' SET ';
				foreach ($arrFields as &$field)
				{
					$sqlValues .= "t.{$field} = v.{$field},";
				}
				
				$sqlValues = substr($sqlValues, 0, strlen($sqlValues) - 1);
				$sqlValues .= ';';
				array_push($arrSql, $sqlUpdate." {$sqlValues}");
				$sqlValues = '';
			}
			else
				$sqlValues .= ' UNION ALL ';
			
			$rowIndex++;
		}
		
		return $arrSql;
	}
	
	
	function GetSqlFieldsFromArray(&$arrFields)
	{
		$sqlFields = '';
		foreach ($arrFields as $key)
		{
			$sqlFields .= " `{$key}`, ";
		}
		$sqlFields = substr($sqlFields, 0, strlen($sqlFields) - 2);
		return $sqlFields;
	}
	
	// toggle a field value ( if it is 0, it makes it 1, if is 1 it makes it 0)
	// returns the value of the field after update
	function ToggleField($tableName, $fieldName, $arrWhere)
	{
		$condition = $this->GetCondition($arrWhere);
		$sql = "UPDATE {$tableName} SET `{$fieldName}` = `{$fieldName}` XOR 1 {$condition}";
		$this->Query($sql);
		
		$sql = "SELECT {$fieldName} FROM {$tableName} {$condition}";
		return $this->GetFieldValue($sql);
	}
	
	// $retType : 0 - returns an array with rows; 1 - return first row; 2 - return single value
	function SelectData($table, $fields, $arrWhere, $order, $limit, $retType)
	{
		$sql = "SELECT {$fields} FROM ".$this->EncodeNameForDb($table);
		$sql .= $this->GetCondition($arrWhere);
		
		if ($order != null)
			$sql .= ' ORDER BY '.$order;
		
		if ($limit != null)
			$sql .= ' LIMIT '.$limit;
			
		if ($retType != 0 && $limit == null)
			$sql .= ' LIMIT 1';
		
		// echo $sql.'<br/>';
		switch ($retType)
		{
			case 0: $ret = $this->GetRows($sql); break;
			case 1: $ret = $this->GetFirstRow($sql); break;
			case 2: $ret = $this->GetFieldValue($sql); break;
			default: $ret = null; break;
		}
		return $ret;
	}
	
	function SelectRow($table, $fields, $arrWhere, $order)
	{
		return $this->SelectData($table, $fields, $arrWhere, $order, 1, 1);
	}
	
	function SelectValue($table, $fields, $arrWhere, $order)
	{
		return $this->SelectData($table, $fields, $arrWhere, $order, 1, 2);
	}
	
	function DeleteRows($table, $arrWhere, $limit)
	{
		$sql = 'DELETE FROM '.$table.' '.$this->GetCondition($arrWhere);
		if ($limit != null)
			$sql .= ' LIMIT '.$limit;
		return $this->Query($sql);
	}
	
	function DeleteRowsWithFiles($table, $dbFields, $filePath, $subPaths, $arrWhere, $limit)
	{
		$rows = $this->SelectData($table, $dbFields, $arrWhere, null, $limit, 0);
		if ($rows != null)
		{
			$arrFilesFields = explode(',', $dbFields);
			foreach ($rows as &$row)
			{
				foreach ($arrFilesFields as $fileField)
				{
					$fileFieldTrim = trim($fileField);
					if ($fileFieldTrim != '')
					{
						$this->DeleteDiskFile($filePath.$row->{$fileFieldTrim});
						if ($subPaths != null)
						{
							foreach ($subPaths as $subPath)
							{
								$this->DeleteDiskFile($filePath.$subPath.$row->{$fileFieldTrim});
							}
						}
					}	
				}
			}
			$this->DeleteRows($table, $arrWhere, $limit);
		}
	}
	
	function DeleteFile($table, $dbField, $filePath, $subPaths, $arrWhere)
	{
		$row = $this->SelectRow($table, $dbField, $arrWhere, 1);
		if ($row != null)
		{
			$this->DeleteDiskFile($filePath.$row->{$dbField});
			if ($subPaths != null)
			{
				foreach ($subPaths as $subPath)
				{
					$this->DeleteDiskFile($filePath.$subPath.$row->{$dbField});
				}
			}
			$this->UpdateRow($table, array($dbField=>''), $arrWhere);
		}
	}
	
	function TruncateTable($table)
	{
		$sql = 'TRUNCATE TABLE '.$table;
		return $this->Query($sql);
	}
	
	function DeleteDiskFile($filePath)
	{
		if ($filePath != '' && file_exists($filePath))
			unlink($filePath);
	}
	
	function GetCondition($arrWhere)
	{
		$ret = '';
		if ($arrWhere != null) 
		{
			$ret .= ' WHERE ';
			foreach ($arrWhere as $key=>$val)
			{
				if (strpos($val, '(') === 0 && strpos($val, ')') === strlen($val) - 1)  // if between round brackets, consider as IN (...)
					$ret .= " `{$key}` IN {$val} AND ";
				else
				{
					// $val = $this->MakeSafeValue($val);
					$ret .= " `{$key}` = '{$val}' AND ";
				}
			}
			$ret = substr($ret, 0, strlen($ret) - 5);
		}
		
		return $ret;
	}
	
	function GetConditionForBulkUpdate($arrWhere)
	{
		$ret = '';
		foreach ($arrWhere as $key=>$val)
		{
			$ret .= " {$key} = {$val} AND ";
		}
		$ret = substr($ret, 0, strlen($ret) - 5);
		return $ret;
	}
	
	function BuildSelectSql($tableName, $fields = '*', $condition = null, $order = null, $limit = null)
	{
		$sql = "SELECT {$fields} FROM {$tableName}";
		if ($condition != null)
			$sql .= " WHERE {$condition}";
		if ($order != null)
			$sql .= " ORDER BY {$order}";
		if ($limit != null)
			$sql .= " LIMIT {$limit}";
		
		return $sql;
	}
	
	function EscapeString($val) {  }  // override this
	
	function EncodeNameForDb($str)
	{
		if (strpos($str, '.') > 0)
		{
			$arr = explode('.', $str);
			return '`'.implode('`.`', $arr).'`';
		}
		else return '`'.$str.'`';
	}
}
?>