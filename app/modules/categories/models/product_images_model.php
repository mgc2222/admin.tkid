<?php 
class ProductImagesModel extends AbstractModel
{	
	function __construct()
	{
		parent::__construct();
		$this->table = 'product_images';
		$this->primaryKey = 'id';
	}
	
	function GetProductImages($productId)
	{
		$sql = "SELECT id, product_id, file FROM {$this->table} WHERE product_id = '{$productId}'";
		$this->dbo->GetRows($sql);
	}
	
	function AddNew($productId, $file)
	{
		$this->dbo->InsertRow($this->table, array('product_id'=>$productId, 'file'=>$file));
	}
	
	function AddProductImages(&$fields, &$rows)
	{
		$this->dbo->InsertRowsBulk($this->table, $fields, $rows);
	}
	
	function DeleteProductImages($productId)
	{
		$this->DeleteRecords(array('product_id'=>$productId));
	}
}
?>