<?php 
class ProductCategoriesModel extends AbstractModel
{	
	function __construct()
	{
		parent::__construct();
		$this->table = 'product_categories';
		$this->tableCategories = 'categories';
		$this->primaryKey = 'id';
	}
	
	function GetProductCategories($productId)
	{
		$sql = "SELECT id, product_id, category_id FROM {$this->table} WHERE product_id = '{$productId}'";
		$this->dbo->GetRows($sql);
	}

	function GetProductsCategories()
	{
		$sql = "SELECT pc.product_id, pc.category_id, c.name FROM {$this->table} pc LEFT JOIN {$this->tableCategories} c ON c.id=pc.category_id";
		return $this->dbo->GetRows($sql);
	}

	function GetProductsByCategoryIds($categoryId)
	{
		$sql = "SELECT * FROM 'products' p LEFT JOIN {$this->table} c ON p.id=c.product_id AND c.category_id IN({$categoryId})";
		return $this->dbo->GetRows($sql);
	}
	
	function AddNew($productId, $categoryId)
	{
		$this->dbo->InsertRow($this->table, array('product_id'=>$productId, 'category_id'=>$categoryId));
	}
	
	function AddProductCategories(&$fields, &$rows)
	{
		$this->dbo->InsertRowsBulk($this->table, $fields, $rows);
	}
	
	function DeleteProductCategories($productIds)
	{
		$this->DeleteRecords(array('product_id'=>'('.$productIds.')'));
	}

	function DeleteCategoryProducts($categoryIds)
	{
		$this->dbo->DeleteRows($this->table, array('category_id'=>'('.$categoryIds.')'));
		
	}
}
?>