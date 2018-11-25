<?php 
class PermissionsModel extends AbstractModel
{	
	function __construct()
	{
		parent::__construct();
		$this->table = 'permissions';
		$this->primaryKey = 'id';
		$this->verifiedTableField = 'name';
		$this->verifiedFormField = 'txtName';
		$this->messageValueExists = 'O permisiune cu acest nume exista deja. Va rugam sa alegeti alt nume';
	}
	
	function SetMapping()
	{
		$this->mapping = array('page_id'=>'txtPageId','parent_id'=>'ddlParentId', 'name'=>'txtName', 'description'=>'txtDescription');
	}
	
	function BeforeSaveData(&$data, &$row)
	{
		
	}
		
	function GetSqlCondition(&$dataSearch)
	{
		if ($dataSearch == null) return '';
		$cond = ' WHERE 1 ';
		if (isset($dataSearch->search) && $dataSearch->search != '')
			$cond .= " AND name LIKE '%{$dataSearch->search}%' OR page_id LIKE '%{$dataSearch->search}%' ";
					
		return $cond;
	}
	
	function GetRecordsList($dataSearch, $orderBy)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		
		$sql = "SELECT fc.id, page_id, parent_id, name, description
			FROM {$this->table} fc
			{$cond}
			ORDER BY {$orderBy}";
			
		$rows = $this->dbo->GetRows($sql);
		return $rows;
	}
	
	function GetRecordsForDropdown($dataSearch = null)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$orderBy = ' ORDER BY id';
		$sql = "SELECT fc.id, fc.name, fc.description
			FROM {$this->table} fc
			ORDER BY fc.name";
		
		return $this->dbo->GetRows($sql);
	}
		
	function GetRecordsListCount($dataSearch)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$sql = "SELECT COUNT(*)	FROM {$this->table} {$cond}";
			
		return $this->dbo->GetFieldValue($sql);
	}
	
	function ExtendGetFormData(&$data, &$row)
	{
		
	}	
}
?>