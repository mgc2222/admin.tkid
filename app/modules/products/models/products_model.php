<?php 
class ProductsModel extends AbstractModel
{	
	function __construct()
	{
		parent::__construct();
		$this->table = 'products';
		$this->tableProductCategories = 'product_categories';
		$this->tableProductAttributes = 'product_attributes';
		$this->primaryKey = 'id';
		$this->verifiedTableField = 'name';
		$this->verifiedFormField = 'txtName';
		$this->messageValueExists = 'Un rol cu acest nume exista deja. Va rugam sa alegeti alt nume';
	}
	
	function SetMapping()
	{
		$this->mapping = array('model'=>'txtModel','product_code'=>'txtProductCode','name'=>'txtName','url_key'=>'txtUrlKey', 'description'=>'txtDescription','price'=>'txtPrice','price_before'=>'txtPriceBefore','amount'=>'txtAmount', 'status'=>'txtStatus', 'amount_unit'=>'txtAmountUnit');

	}
	
	function GetSqlCondition(&$dataSearch)
	{
		$data = new stdClass();
		$data->cond = '';
		$data->join = '';
		
		if ($dataSearch == null) {
			return $data;
		}
		
		$cond = 'WHERE 1 ';
		$join = '';
		if (isset($dataSearch->search) && $dataSearch->search != '') {
			$cond = " AND name LIKE '%{$dataSearch->search}%'";
		}
		
		if (isset($dataSearch->status)) {
			$cond = " AND status = '{$dataSearch->status}'";
		}
		
		if (isset($dataSearch->cid) && $dataSearch->cid != '') {
			$join = " INNER JOIN {$this->tableProductCategories} pc ON pc.product_id = p.id AND pc.category_id = {$dataSearch->cid}";
		}

		$data->cond = $cond;
		$data->join = $join;
			
		return $data;
	}
	
	function GetRecordsList($dataSearch, $orderBy, $limit)
	{
		$data = $this->GetSqlCondition($dataSearch);
		$sql = "SELECT p.id, p.url_key, p.producer_id,p.model,p.product_code, p.name,p.category_ids,p.description,p.status,p.default_image,p.price,p.price_before,p.amount,p.amount_unit
				FROM {$this->table}	p
				{$data->join}
				{$data->cond}";
		
		if ($orderBy != null) {
			$sql .= ' ORDER BY '.$orderBy;
		}
		if ($limit != null) {
			$sql .= ' LIMIT '.$limit;
		}
		
		return $this->dbo->GetRows($sql);
	}
	
	function GetRecordsForDropdown($dataSearch = null)
	{
		$data = $this->GetSqlCondition($dataSearch);
		$orderBy = ' ORDER BY id';
		$sql = "SELECT id, name FROM {$this->table} p	
		{$data->join} 
		{$data->cond} 
		{$orderBy}";
		
		return $this->dbo->GetRows($sql);
	}
	
	function GetProductsByIds($productsIds)
	{
		$sql = "SELECT * FROM {$this->table} p WHERE  p.id IN {$productsIds}";
		return $this->dbo->GetRows($sql);
	}	
	
	function GetRecordsListCount($dataSearch)
	{
		$data = $this->GetSqlCondition($dataSearch);
		$sql = "SELECT COUNT(*)	FROM {$this->table} p 
		{$data->join}
		{$data->cond}
		";
			
		return $this->dbo->GetFieldValue($sql);
	}
	
	function BeforeSaveData(&$data, &$row)
	{
		if ($data->EditId == 0) {
			$row->date_added = date('Y-m-d H:i:s');
		}
		$row->date_updated = date('Y-m-d H:i:s');
	}
	
	function ExtendGetFormData(&$data, &$row)
	{
		//echo'<pre>';print_r($row);echo'</pre>';die;
		$data->chkCategoryList = $this->GetProductCategories($data->EditId);
		$data->chkAttributeList = $this->GetProductAttributes($data->EditId);
		$data->txtAmountUnit = $row->amount_unit;
	}
	
	function ExtendGetFormDataEmpty(&$data)
	{
		$data->chkCategoryList = null;
		$data->chkAttributeList = array();
		//$data->txtAmountUnit = '';
		// select all sizes except XXL
		for ($valueId = 1; $valueId <= 5; $valueId++) {
			$row = new stdClass();
			$row->attribute_id = 2;
			$row->attribute_value_id = $valueId;
			array_push($data->chkAttributeList, $row);
		}
	}
	
	function GetProductCategories($productId)
	{
		$sql = "SELECT category_id FROM {$this->tableProductCategories} WHERE product_id = {$productId}";
		return $this->dbo->GetRows($sql);
	}
	
	function GetProductAttributes($productId)
	{
		$sql = "SELECT attribute_id, attribute_value_id FROM {$this->tableProductAttributes} WHERE product_id = {$productId}";
		return $this->dbo->GetRows($sql);
	}
	
	function GetAttributesNames()
	{
		$sql = "SELECT id, name FROM attributes_names ORDER BY id";
		return $this->dbo->GetRows($sql);
	}
	
	function GetAttributesValues()
	{
		$sql = "SELECT id, attribute_id, value FROM attributes_values ORDER BY attribute_id, id";
		return $this->dbo->GetRows($sql);
	}
	
	function AddNewAttributeName($name)
	{
		return $this->dbo->InsertRow('attributes_names', array('name'=>$name));
	}
	
	function AddNewAttributeValue($attributeId, $value)
	{
		return $this->dbo->InsertRow('attributes_values', array('attribute_id' => $attributeId, 'value'=>$value));
	}
	
	function AddNewProduct(&$product)
	{
		return $this->dbo->InsertRow($this->table, $product);
	}
	
	function UpdateProduct($productId, &$product)
	{
		$this->dbo->UpdateRow($this->table, $product, array($this->primaryKey=>$productId));
	}
	
	function UpdateProductsAmount($amount) 
	{
		$this->dbo->UpdateRow($this->table, array('amount'=>$amount), null);
	}
	
	function GetProductByCode($productCode)
	{
		$sql = "SELECT * FROM {$this->table} WHERE product_code = '{$productCode}' LIMIT 1";
		return $this->dbo->GetFirstRow($sql);
	}
	
	function SaveProductCategories($productId, $fields, &$rows)
	{
		$this->dbo->DeleteRows($this->tableProductCategories, array('product_id'=>$productId));
		if ($rows) {
			$this->dbo->InsertRowsBulk($this->tableProductCategories, $fields, $rows);
		}
	}
}
?>