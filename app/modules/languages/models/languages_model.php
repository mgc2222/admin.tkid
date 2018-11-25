<?php 
class LanguagesModel extends AbstractModel
{	
	function __construct()
	{
		parent::__construct();
		$this->table = 'langs';
		$this->primaryKey = 'id';
		$this->verifiedTableField = 'abbreviation';
		$this->verifiedFormField = 'txtAbbreviation';
		$this->messageValueExists = 'O limba cu aceasta prescurtate exista deja. Va rugam sa alegeti alta prescurtare';
	}
	
	function SetMapping()
	{
		$this->mapping = array('abbreviation'=>'txtAbbreviation','name'=>'txtName','is_translated'=>'chkIsTranslated');
	}
	
	function GetDataForJson($editId)
	{
		if ($editId == 0)
		{
			$row = new LanguageEntity();
			$row->id = 0;
		}
		else
		{
			$row = $this->GetRecordForEdit($editId);
		}
		return $row;
	}
	
	function GetSqlCondition(&$dataSearch)
	{
		if ($dataSearch == null) return '';
		
		$cond = ' WHERE 1 ';
		if (isset($dataSearch->search) && $dataSearch->search != '')
			$cond .= " AND name LIKE '%{$dataSearch->search}%'";
		if (isset($dataSearch->abbreviation) && $dataSearch->abbreviation != '')
			$cond .= " AND abbreviation = {$dataSearch->abbreviation}";
			
		return $cond;
	}
	
	function GetRecordsList($dataSearch, $orderBy)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$sql = "SELECT id, name, abbreviation, is_translated
				FROM {$this->table}	{$cond}";
		
		if ($orderBy != null)
			$sql .= ' ORDER BY '.$orderBy;
		
		$rows = $this->dbo->GetRows($sql);
		
		$sql = "SELECT key_value FROM settings WHERE key_name = 'default_language'";
		$defaultLangId = $this->dbo->GetFieldValue($sql);
		if ($defaultLangId == null)
			$defaultLangId = 1;
		
		if ($rows != null)
		{
			foreach ($rows as &$row)
			{
				$row->is_default = ($row->id == $defaultLangId)?'Da':'Nu';
			}
		}
		
		return $rows;
	}
	
	function GetRecordsForDropdown($dataSearch, $orderBy)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$orderBy = ($orderBy != null)?' ORDER BY '.$orderBy:'';
		$sql = "SELECT id, name, abbreviation FROM {$this->table}	{$cond} {$orderBy}";
		
		return $this->dbo->GetRows($sql);
	}
		
	
	function GetRecordsListCount($dataSearch)
	{
		$cond = $this->GetSqlCondition($dataSearch);
		$sql = "SELECT COUNT(*)	FROM {$this->table} {$cond}";
			
		return $this->dbo->GetFieldValue($sql);
	}
	
	function GetLanguagesIds()
	{
		$sql = 'SELECT id FROM '.$this->table;
		return $this->dbo->GetRows($sql);
	}
		
	function BeforeSaveData(&$data, &$row)
	{

	}
	
	function GetRecordForEdit($recordId)
	{
		$sql = "SELECT id, name, is_translated, abbreviation, 
				(SELECT key_value FROM settings WHERE key_name = 'default_language') as default_language_id
			FROM {$this->table}
			WHERE {$this->primaryKey} = {$recordId}";
		return $this->dbo->GetFirstRow($sql);
	}
	
	function GetDefaultLanguage()
	{
		$sql = "SELECT key_value FROM settings WHERE key_name = 'default_language' LIMIT 1";
		$langId = $this->dbo->GetFieldValue($sql);
		
		$cond = ($langId == null)?'':' WHERE id= '.$langId;
		$sql = "SELECT id, abbreviation FROM {$this->table} {$cond} LIMIT 1";
		$row = $this->dbo->GetFirstRow($sql);

		return $row;
	}
		
	function ExtendGetFormData(&$data, &$row)
	{
		$data->chkDefaultLanguage = ($row->default_language_id == $row->id)?'checked="checked"':'';
		$data->chkIsTranslated = ($row->is_translated == 1)?'checked="checked"':'';
	}
	
	function ExtendGetFormDataEmpty(&$data)
	{
		$data->chkDefaultLanguage = '';
		$data->chkIsTranslated = '';
	}
}
?>