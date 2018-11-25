<?php 
class AttributesModel extends AbstractModel
{	
	function __construct()
	{
		parent::__construct();
		$this->table = 'attributes_values';
		$this->primaryKey = 'id';
	}
	
	function GetRecordsForDropdown($attributeId)
	{
		$sql = "SELECT id, value FROM {$this->table} WHERE attribute_id = {$attributeId}";
		return $this->dbo->GetRows($sql);
	}
}
?>