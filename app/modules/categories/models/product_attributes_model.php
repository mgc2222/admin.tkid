<?php 
class ProductAttributesModel extends AbstractModel
{	
	function __construct()
	{
		parent::__construct();
		$this->table = 'product_attributes';
		$this->tableSummary = 'product_attributes_summary';
		$this->primaryKey = 'id';
	}
	
	function GetProductAttributes($productId)
	{
		$sql = "SELECT id, product_id, color_ids, size_ids FROM {$this->table} WHERE product_id = '{$productId}'";
		$this->dbo->GetRows($sql);
	}
	
	function AddNew($productId, $colorIds, $sizeIds)
	{
		$this->dbo->InsertRow($this->table, array('product_id'=>$productId, 'color_ids'=>$colorIds, 'color_ids'=>$sizeIds));
	}
	
	function AddProductAttributes(&$data)
	{
		$this->dbo->InsertRow($this->table, $data);
	}
	
	function DeleteProductAttributes($productId)
	{
		$this->DeleteRecords(array('product_id'=>$productId));
	}
	
	function SaveProductAttributes($productId, $attributeId, $fields, &$rows)
	{
		$this->dbo->DeleteRows($this->table, array('product_id'=>$productId, 'attribute_id'=>$attributeId));
		if ($rows) {
			$this->dbo->InsertRowsBulk($this->table, $fields, $rows);
		}
	}
	
	function GetProductAttributesSummary($productId)
	{
		$sql = "SELECT id, product_id, color_ids, size_ids FROM {$this->tableSummary} WHERE product_id = {$productId} LIMIT 1";
		return $this->dbo->GetFirstRow($sql);
	}
	
	function SaveProductAttributesSummary($productId, $attributeFieldName, $value)
	{
		$row = $this->GetProductAttributesSummary($productId);
		if (!$row) {
			$this->dbo->InsertRow($this->tableSummary, array('product_id'=>$productId, $attributeFieldName => $value));
		}
		else {
			$this->dbo->UpdateRow($this->tableSummary, array($attributeFieldName => $value), array('product_id'=>$productId));
		}
	}
}
?>