<?php
class ProductCategoriesMap extends CategoriesMap
{
	protected static $instance;
	private $table;
	private $tableProducts;
	private $tableProductCategory;
	
	function __construct()
	{
		parent::__construct();
		$this->tableCategories = 'categories';
		$this->tableProducts = 'products';
		$this->tableProductCategory = 'product_categories';
	}
	
	// singleton
	static function GetInstance()
	{
		if (!isset(self::$instance)) 
		{
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
	}
	
	// DB functions
	protected function GetCategories()
	{
		$sql = "SELECT id,name,parent_id,level,url_key,order_index,status,display_separate_status FROM {$this->tableCategories} ORDER BY parent_id, order_index, name";
		return $this->dbo->GetRows($sql);
	}
	
	public function GetArticleName($articleId)
	{
		$sql = "SELECT name FROM {$this->tableProducts} WHERE id = {$articleId}";
		return $this->dbo->GetFieldValue($sql);
	}
	
	public function GetArticlesCount()
	{
		$sql = 'SELECT category_id, COUNT(*) as cnt, GROUP_CONCAT(product_id) as articlesIds FROM product_categories GROUP BY category_id';
		return $this->dbo->GetRows($sql);
	}
}
?>