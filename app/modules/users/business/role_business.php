<?php 
class RoleBusiness extends CoreBusiness
{	
	public function __construct()
	{
		parent::__construct();
	}
	
	protected function SetMapping()
	{
		$this->mapping = array('name'=>'txtName','description'=>'txtDescription','status'=>'chkStatus');
	}
	
	protected function GetSqlCondition(&$dataSearch)
	{
		if ($dataSearch == null) return '';
		
		$cond = '';
		if (isset($dataSearch->status))
			$cond = " WHERE status = '{$dataSearch->status}'";
		
		if (isset($dataSearch->hotel))
			$cond = " WHERE name <> 'webmaster'";
			
		return $cond;
	}
	
	function GetRecordsList($dataSearch, $orderBy)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$sql = "SELECT id, name, description, status
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
		$orderBy = ' ORDER BY id';
		$sql = "SELECT id, name	FROM {$this->table}	{$cond} {$orderBy}";
		
		return $this->dbo->GetRows($sql);
	}
		
	
	function GetRecordsListCount($dataSearch)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$sql = "SELECT COUNT(*)	FROM {$this->table} {$cond}";
			
		return $this->dbo->GetFieldValue($sql);
	}
	
	function BeforeSaveData(&$data, &$row)
	{
		$row->status = isset($data->chkStatus)?1:0;
	}
	
	function ExtendGetFormData(&$data, &$row)
	{
		$data->chkStatus = ($data->chkStatus)? 'checked="checked"':'';
	}
	
	function ExtendGetFormDataEmpty(&$data)
	{
		$data->chkStatus = 'checked="checked"';
	}
}
?>