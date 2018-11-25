<?php 
class ProducersModel extends AbstractModel
{	
	function __construct()
	{
		parent::__construct();
		$this->table = 'producers';
		$this->primaryKey = 'id';
		$this->verifiedTableField = 'name';
		$this->verifiedFormField = 'txtName';
		$this->messageValueExists = 'Un rol cu acest nume exista deja. Va rugam sa alegeti alt nume';
	}
	
	function SetMapping()
	{
		$this->mapping = array('name'=>'txtName');

	}
	
	function GetSqlCondition(&$dataSearch)
	{
		if ($dataSearch == null) return '';
		
		$cond = '';
		if (isset($dataSearch->search) && $dataSearch->search != '')
			$cond = " WHERE name LIKE '%{$dataSearch->search}%'";
			
		return $cond;
	}
	
	function GetRecordsList($dataSearch, $orderBy)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$sql = "SELECT id,name
				FROM {$this->table}	{$cond}";
		
		if ($orderBy != null)
			$sql .= ' ORDER BY '.$orderBy;
		
		$rows = $this->dbo->GetRows($sql);
		if ($rows != null)
		{
			foreach ($rows as &$row)
			{
				$row->status_display = ($row->status == 1)?'Da':'Nu';
			}
		}
		
		return $rows;
	}
	
	function GetRecordsForDropdown($dataSearch = null)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$orderBy = ' ORDER BY name';
		$sql = "SELECT id, name FROM {$this->table}	{$cond} {$orderBy}";
		
		return $this->dbo->GetRows($sql);
	}
		
	
	function GetRecordsListCount($dataSearch)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$sql = "SELECT COUNT(*)	FROM {$this->table} {$cond}";
			
		return $this->dbo->GetFieldValue($sql);
	}
	
	function AddNew($name)
	{
		return $this->dbo->InsertRow($this->table, array('name'=>$name));
	}
	
	function BeforeSaveData(&$data, &$row)
	{
		
	}
	
	function ExtendGetFormData(&$data, &$row)
	{
		
	}
	
	function ExtendGetFormDataEmpty(&$data)
	{
		
	}
}
?>