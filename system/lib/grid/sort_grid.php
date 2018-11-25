<?php
class SortGrid
{		
	var $sortData;
	function __construct()
	{
		$this->sortData = new stdClass();
	}
	
	
	function GetSortData()
	{
		return $this->sortData;
	}

	function AddSort(&$page, $tableName, $defaultSortColumn, $sortColumnHidId = '')
	{
		if ($sortColumnHidId == '')
			$sortColumnHidId = 'hidSortColumn_'.$tableName;
		
		$sortColumn = isset($page[$sortColumnHidId])?$page[$sortColumnHidId]:'';
		if ($sortColumn == '') $sortColumn = $defaultSortColumn;
		
		$this->sortData->{$tableName} = new stdClass();
		$this->sortData->{$tableName}->Table = $tableName;
		$this->sortData->{$tableName}->Sql = $this->GetSortColumnSql($sortColumn, $tableName);
		$this->sortData->{$tableName}->CssClass = $this->GetSortColumnClass($sortColumn, $tableName);
		$this->sortData->{$tableName}->Input = $sortColumn;
		
		return $this->sortData;
	}
	
	function AddSortMultiple(&$page, $tableNames, $defaultSortColumns, $sortColumnHidIds = null)
	{
		$tableIndex = 0;
		foreach ($tableNames as $tableName)
		{
			$defaultSortColumn = $defaultSortColumns[$tableIndex];
			$sortColumnHidId = ($sortColumnHidIds == null)?'':$sortColumnHidIds[$tableIndex];
			
			$this->AddSort($page, $tableName, $defaultSortColumn, $sortColumnHidId);
			$tableIndex++;
		}
		return $this->sortData;
	}
	
	// override this function
	function GetSortColumnClass($sortKey, $sortTable) { }
	
	// override this function
	function GetSortColumnSql($sortKey, $sortTable) { }
	
	function GetSortObject($val, $separator = '|')
	{
		if (strpos($val, $separator) > 0)
		{
			$arrSort = explode($separator, $val);
			$sortColumn = $arrSort[0];
			$sortDir = $arrSort[1];
		}
		else
		{
			$sortColumn = $val;
			$sortDir = 'ASC';
		}
		
		$ret = new stdClass();
		$ret->sortColumn = $sortColumn;
		$ret->sortDir = $sortDir;
		return $ret;
	}
}	
?>
