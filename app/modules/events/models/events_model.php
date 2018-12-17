<?php 
class EventsModel extends AbstractModel
{	
	function __construct()
	{
		parent::__construct();
		$this->table = 'categories';
		$this->tableProducts = 'products';
		$this->primaryKey = 'id';
	}
	
	function SetMapping()
	{
		$this->mapping = array('parent_id'=>'ddlParentId','name'=>'txtName', 'url_key'=>'txtUrlKey', 'description'=>'txtDescription', 'short_description'=>'txtShortDescription', 'seo_keywords'=>'txtSeoKeywords', 'seo_description'=>'txtSeoDescription', 'seo_title'=>'txtSeoTitle', 'status'=>'chkStatus', 'order_index'=>'txtOrderIndex', 'display_separate_status' => 'chkDisplaySeparateStatus');
	}
	
	function GetSqlCondition(&$dataSearch)
	{
		if ($dataSearch == null) return '';
		
		$cond = '';
		if (isset($dataSearch->search) && $dataSearch->search != '') {
			$cond = " WHERE name LIKE '%{$dataSearch->search}%'";
		}
		
		return $cond;
	}
	
	function GetRecordsList($dataSearch, $orderBy)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$sql = "SELECT id,parent_id,name
				FROM {$this->table}	{$cond}";
		
		if ($orderBy != null)
			$sql .= ' ORDER BY '.$orderBy;
		
		return $this->dbo->GetRows($sql);
	}
	
	function GetRecordsListCount($dataSearch)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$sql = "SELECT COUNT(*) FROM {$this->table}	{$cond}";		
		return $this->dbo->GetFieldValue($sql);
	}
	
	function GetRecordsForDropdown($dataSearch = null)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$orderBy = ' ORDER BY name';
		$sql = "SELECT id, parent_id, name FROM {$this->table}	{$cond} {$orderBy}";
		
		return $this->dbo->GetRows($sql);
	}
	
	function GetRecordsIds($dataSearch = null)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$orderBy = ' ORDER BY id';
		$sql = "SELECT id FROM {$this->table} {$cond} {$orderBy}";
		
		return $this->dbo->GetRows($sql);
	}

	function GetCategoriesByIds($categoriesIds)
	{
		$sql = "SELECT * FROM {$this->table} c WHERE  c.id IN ({$categoriesIds})";
		return $this->dbo->GetRows($sql);
	}

	function GetProductsByIds($productsIds)
	{
		$sql = "SELECT * FROM {$this->tableProducts} p WHERE  p.id IN ({$productsIds})";
		return $this->dbo->GetRows($sql);
	}	
	
	function ExtendGetFormData(&$data, &$row)
	{
		$data->chkStatus = ($data->chkStatus > 0) ? 'checked="checked"':'';
		$data->chkDisplaySeparateStatus = ($data->chkDisplaySeparateStatus > 0) ? 'checked="checked"':'';
		$data->txtFile = $row->file;
	}
	
	function ExtendGetFormDataEmpty(&$data)
	{
		$data->chkStatus = 'checked="checked"';
		$data->chkDisplaySeparateStatus = '';
		$data->txtFile = '';
	}
	
	function BeforeSaveData(&$data, &$row)
	{
		//echo'<pre>';print_r($data);echo'</pre>';die; 
		$row->status = isset($data->chkStatus)? 1: 0;
		$row->display_separate_status = isset($data->chkDisplaySeparateStatus)? 1: 0;
	}
	
	function UpdateFileName($id, $fileName)
	{
		$this->dbo->UpdateRow($this->table, array('file'=>$fileName), array($this->primaryKey=>$id));
	}
	
	function DeleteFile($recordId, $filePath)
	{
		$this->dbo->DeleteFile($this->table, 'file', $filePath, array(_THUMBS_PATH), array($this->primaryKey=>$recordId));
	}
	
	function AddNew($name, $urlKey)
	{
		return $this->dbo->InsertRow($this->table, array('name'=>$name, 'url_key'=>$urlKey));
	}
}
?>