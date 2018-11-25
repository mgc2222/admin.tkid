<?php 
class AbstractModel
{	
	protected $mapping;
	protected $table;
	protected $primaryKey;
	
	protected $verifiedTableField;
	protected $verifiedFormField;
	protected $dbo;
	
	protected $error;
	protected $preventSave; // set this in BeforeSaveData, in order to stop saving and only call the AfterSaveData
	protected $editedRow; // current edited row

	// constructor function, set the class variables and instantiate the dbo 
	public function __construct()
	{
		$this->ClearVariables();
		
		// instantiate dbo class and connect to db
		$this->dbo = DBO::global_instance();
		
		$this->SetMapping();
	}
		
	protected function SetMapping()
	{
		// implement in extended model
	}
	
	public function GetMapping()
	{
		return $this->mapping;
	}
	
	// clear the class variables
	private function ClearVariables()
	{
		$this->table = '';
		$this->primaryKey = '';
		$this->verifiedTableField = '';
		$this->verifiedFormField = '';
		$this->error = false;
		$this->preventSave = false;
	}
	
	// get the specified $fields from database, for the primary key $recordId
	// $recordId : value of the primary key for which to get the record
	// $fields : the fields that will be retrieved from database, separated by ","; by default, will get all fields
	// returns the first row which has the primary key matching the $recordId
	public function GetRecordById($recordId, $fields = '*')
	{
		$sql = $this->dbo->BuildSelectSql($this->table, $fields, "{$this->primaryKey}='{$recordId}'", null, 1);
		return $this->dbo->GetFirstRow($sql);
	}
	
	// get a single record by the specified condition
	// $condition : a plain sql condition such as: "field1 = 5 AND field2 = 'sending'"
	// $fields : the fields that will be retrieved from database, separated by ",";  by default, will get all fields
	// returns the first row matching the $condition
	public function GetRecord($condition, $fields = '*')
	{
		$sql = $this->dbo->BuildSelectSql($this->table, $fields, $condition, null, 1);
		return $this->dbo->GetFirstRow($sql);
	}
	
	// gets an array of records from database matching the condition, ordered by $order and limited by $limit
	// $condition : a plain sql condition; i.e.: "field1 = 5 AND field2 = 'sending'"
	// $order : a plain sql representing the order; i.e. : " field1 ASC, field2 DESC "
	// $limit : a plain sql representing the limit; i.e:  "2" or "0,10"; 
	// $fields : the fields that will be retrieved from database, separated by ",";  by default, will get all fields
	// returns the rows matching the $condition, limited by $limit
	public function GetRecords($condition = null, $order=null, $limit = null, $fields = '*')
	{
		$sql = $this->dbo->BuildSelectSql($this->table, $fields, $condition, $order, $limit);
		return $this->dbo->GetRows($sql);
	}
	
	// gets the count of records matching the condition 
	// $condition : a plain sql condition; i.e.: "field1 = 5 AND field2 = 'sending'"
	// returns the count of records matching the condition 
	public function GetRecordsCount($condition = null)
	{
		$sql = $this->dbo->BuildSelectSql($this->table, 'Count(*) AS RecordsCount', $condition);
		return $this->dbo->GetFieldValue($sql);
	}
	
	// Check if a value already exists in database, for a primary key different than the currently edited record id.
	// $fieldName : name of the database field that will be checked
	// $fieldValue : value to search in the database
	// $editId : the id of the currently edited record
	// returns null if verified field doesn't exists, otherwise returns the primaryKey of the found record
	public function GetRecordExists($fieldName, $fieldValue, $editId)
	{
		$condition = "{$fieldName}='{$fieldValue}' AND {$this->primaryKey} <> '{$editId}'";
		$sql = $this->dbo->BuildSelectSql($this->table, $this->primaryKey, $condition, null, 1);
		$recordExists = $this->dbo->GetFieldValue($sql);
		return $recordExists;
	}
	
	// Delete the record for the specified recordId
	// $recordId : value of the primary key for which to delete 
	public function DeleteRecord($recordId)
	{
		$this->dbo->DeleteRow($this->table, array($this->primaryKey=>$recordId));
	}
	
	// Delete the records which match the specified conditions
	// $arrCondition: array with pair key=>value
	public function DeleteRecords($arrCondition = null, $limit = null)
	{
		$this->dbo->DeleteRows($this->table, $arrCondition, $limit);
	}
	
	// Delete the records which have the primary key within the specified values
	// $recordIds: string with the primary keys for which to delete, separated by ","
	public function DeleteSelectedRecords($recordIds)
	{
		$this->dbo->DeleteRows($this->table, array($this->primaryKey=>'('.$recordIds.')'));
	}
	
	// Delete the specified filePath from disk
	public function DeleteDiskFile($filePath)
	{
		if ($filePath != '' && file_exists($filePath))
			unlink($filePath);
	}
	
	// SaveRecord : saves the specified data in database
	// $data : an object with the specified values { $data->field1 = 1; $data->field2 = 'string'; $data->field3 = 5.4;  }
	// $verifyExists: if set to true, will verify if there is a record already existing for the verifiedFormField
	// returns : 0, if data wasn't saved or the saved id, if data was save
	public function SaveRecord(&$data, $verifyExists = false)
	{
		$this->recordExists = false;
		
		if ($verifyExists) {
			if ($this->VerifyRecordExists($this->verifiedTableField, $data->{$this->verifiedFormField}, $data->EditId)) {
				return 0;
			}
		}
		
		$row = $this->GetDefaultRecord($data->EditId);
		return $this->SaveRecordInDatabase($data, $row);
	}
	
	// returns an object with the data required for saving
	// $editId : value of the primary key, used for condition on update
	protected function GetDefaultRecord($editId)
	{
		$row = new stdClass();
		$row->_Condition = ($editId != 0)?array($this->primaryKey=>$editId):null;
		$row->_TableName = $this->table;
		$row->_RecordId = $editId;
		
		return $row;
	}
	
	// verify if a field value already exists in the database
	// $fieldName : name of the field from databse
	// $fieldValue: value to search in database
	// $editId : the id of the currently edited record
	public function VerifyRecordExists($fieldName, $fieldValue, $editId)
	{
		$recordExists = $this->GetRecordExists($fieldName, $fieldValue, $editId);
		if ($recordExists)
		{
			return true;
		}

		return false;
	}
	
	// saves the data in database 
	// $data : object with the data to be saved
	// $row : object for the database data
	protected function SaveRecordInDatabase(&$data, &$row)
	{
		foreach ($this->mapping as $dbKey=>$htmlKey)
		{
			$row->{$dbKey} = isset($data->{$htmlKey})? $data->{$htmlKey}:'';
		}
		
		$this->BeforeSaveData($data, $row); // makes any wanted changes before saving the data
		if ($this->error)
			return 0;
		
		// in some cases only AfterSaveData is required, therefor set $preventSave to true in BeforeSaveData, in order to stop saving
		if (!$this->preventSave)		
		{
			$recordId = $this->dbo->SaveRow($row);
		}
		else
		{
			$recordId = $row->_RecordId;
		}
		
		$this->AfterSaveData($recordId, $data);
		return $recordId;
	}
	
	// make additional changes before saving the data
	protected function BeforeSaveData(&$data, &$row)
	{
		// implement in each extended model
	}
	
	// add some more functionality after saving the data
	protected function AfterSaveData($recordId, &$data)
	{
		// implement in each extended model
	}
	
	public function SaveObjectData($row, $tableName, $primaryKey = null, $primaryKeyValue = 0)
	{
		$row->_Condition = ($primaryKey != null)?array($primaryKey=>$primaryKeyValue):null;
		$row->_TableName = $tableName;
		$row->_RecordId = $primaryKeyValue;
		
		return $this->dbo->SaveRow($row);
	}
	
	// save an array into database
	public function SaveData(&$data, $makeSafeValues = true, $stripTags = false, $verifyExists = false)
	{
		$editId = $data[$this->primaryKey];
		unset($data[$this->primaryKey]);
		
		if ($makeSafeValues)
			$this->MakeSafeData($data, $stripTags);
		
		if ($verifyExists)
			if ($this->VerifyRecordExists($this->verifiedTableField, $data[$this->verifiedTableField], $editId))
				return 0;
		
		if ($editId == 0)
		{
			return $this->dbo->InsertRow($this->table, $data);
		}
		else
		{
			$this->dbo->UpdateRow($this->table, $data, array($this->primaryKey=>$editId));
			return $editId;
		}
	}
	
	public function MakeSafeData(&$data)
	{
		foreach ($data as $key=>$val)
		{
			$data[$key] = $this->dbo->GetSafeValue($val, false);
		}
	}
	
	public function GetSafeValue($val, $stripTags = true)
	{
		return $this->dbo->GetSafeValue($val, $stripTags);
	}
	
	public function GetFormData($recordId, &$data)
	{
		if ($recordId == 0)
		{
			$this->GetFormDataEmpty($data);
			return true;
		}

		$row = $this->GetRecordForEdit($recordId);
		$this->editedRow = $row;
		
		if ($row == null) {
			return false;
		}

		foreach ($this->mapping as $dbKey=>$htmlKey)
		{
			if (isset($row->{$dbKey}))
				$data->{$htmlKey} = $row->{$dbKey};
		}
		
		$this->ExtendGetFormData($data, $row);
		
		return true;
	}
	
	public function GetEditedRow() {
		return $this->editedRow;
	}

	// implement this in the exteneded model
	protected function GetFormDataEmpty(&$data)
	{
		foreach ($this->mapping as $dbKey=>$htmlKey)
		{
			$data->{$htmlKey} = '';
		}
		$this->ExtendGetFormDataEmpty($data);
	}
	
	protected function ExtendGetFormData(&$data, &$row)
	{
		// implement this in the exteneded model
	}
	
	protected function ExtendGetFormDataEmpty(&$data)
	{
		// implement this in the exteneded model
	}
	
	// get the record for edit
	// implement a new function in the extended model, if some other data is required to be retrieved
	protected function GetRecordForEdit($recordId)
	{
		return $this->GetRecordById($recordId);
	}
}
?>