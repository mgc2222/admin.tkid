<?php
class CategoriesImportMap extends CategoriesMap
{
	protected static $instance;
	private $table;
	private $tableProducts;
	private $tableProductCategory;
	
	function __construct()
	{
		parent::__construct();
		$this->tableCategories = 'categories_import';
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
		$sql = "SELECT id,name,url_key FROM {$this->tableCategories} ORDER BY name";
		return $this->dbo->GetRows($sql);
	}
	
	public function GetArticleName($articleId)
	{
		return '';
	}
	
	public function GetArticlesCount()
	{
		return 0;
	}
}
?>