<?php 
class AppPicturesModel extends AbstractModel
{	
	function __construct()
	{
		parent::__construct();
		$this->table = 'app_images_meta';
		$this->tableImages = 'app_images';
		$this->primaryKey = 'id';
		$this->verifiedTableField = 'name';
		$this->verifiedFormField = 'txtName';
		$this->messageValueExists = 'Un rol cu acest nume exista deja. Va rugam sa alegeti alt nume';
	}
	
	function SetMapping()
	{
		$this->mapping = array('image_alt'=>'txtAlt','image_title'=>'txtTitle','image_caption'=>'txtCaption','image_description'=>'txtDescription', 'image_button_link_text'=>'txtButtonText','image_button_link_href'=>'txtButtonHref','order_index'=>'txtOrder');

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
	
	function GetAppInfo($appCategoryId)
	{
		//echo'<pre>';print_r($appCategoryId);echo'</pre>';die;
		$sql = "SELECT a.name, COUNT(aim.app_image_id) as images_count
				FROM app_categories a
				LEFT JOIN {$this->table} aim ON a.id = aim.app_category_id
			WHERE a.id=".$appCategoryId;	
		return $this->dbo->GetFirstRow($sql);
	}

	function GetLastInsertedAppImageId()
	{
		$sql = "select auto_increment from information_schema.TABLES where TABLE_NAME ='{$this->tableImages}' and TABLE_SCHEMA='"._DB_DATA_BASE."'";	
		return $this->dbo->GetFieldValue($sql);
	}

	function GetAppCategoryName($appCategoryId)
	{
		//echo'<pre>';print_r($appCategoryId);echo'</pre>';die;
		$sql = "SELECT name FROM app_categories WHERE id={$appCategoryId} LIMIT 1";
		return $this->dbo->GetFieldValue($sql); 
	}

	function GetAppCategoriesForDropDown()
	{
		$sql = "SELECT * FROM app_categories WHERE true";
		return $this->dbo->GetRows($sql);
	}

	function GetAppImageById($imageId)
	{
		//echo'<pre>';print_r($imageId);echo'</pre>';die;
		$cond = 'id='.$imageId;
		$sql = "SELECT * FROM {$this->tableImages} WHERE {$cond}"; 
		$row = $this->dbo->GetFirstRow($sql);
			
		return $row;
	}

	function GetAppImages($appCategoryId)
	{
		//echo'<pre>';print_r($appCategoryId);echo'</pre>';die;
		$cond = 'app_category_id='.$appCategoryId;
		$sql = "SELECT * FROM {$this->tableImages} WHERE {$cond}"; 
		$rows = $this->dbo->GetRows($sql);
			
		return $rows;
	}

	function SaveAppImages($row)
	{
		//echo'<pre>';print_r($row);echo'</pre>';die;
		$this->dbo->InsertRow($this->tableImages, $row);
		$sql = "SELECT id FROM {$this->tableImages} ORDER BY id DESC LIMIT 1";
		return $this->dbo->GetFieldValue($sql); 
	}

	function GetAppImageMetaIdByAppImageId($imageId)
	{
		//echo'<pre>IMAGE ID:';print_r($imageId);echo'</pre>';die;
		$sql = "SELECT id FROM {$this->table} WHERE app_image_id = {$imageId} LIMIT 1";
		return $this->dbo->GetFieldValue($sql); 
	}

	function InsertAppImagesMeta($imageId, $appCategoryId)
	{
		//echo'<pre>IMAGE ID:';print_r($imageId);echo'</pre>';die;
		$id = $this->GetAppImageMetaIdByAppImageId($imageId);
		$data = array('app_image_id'=>$imageId, 'app_category_id'=>$appCategoryId);
		if (!$id) {
			
			$this->dbo->InsertRow($this->table, $data);
		}
		else {
			$this->UpdateAppImagesMeta($imageId, $appCategoryId, $data);
		}
	}

	function UpdateAppImagesMeta($imageId, $appCategoryId, $data)
	{
		//echo'<pre>IMAGE ID:';print_r($data);echo'</pre>';die;
		$this->dbo->UpdateRow($this->table, $data, array('app_image_id'=>$imageId));
		
	}

	function UpdateAppImageSize($imageId, $width, $height)
	{
		$this->dbo->UpdateRow($this->tableImages, array('img_width'=>$width, 'img_height'=>$height), array('id'=>$imageId));
	}

	function DeleteAppImage($imageId, $appCategoryId, $path)
	{
		//echo'<pre>';print_r($imageId);echo'</pre>';die;
		$this->dbo->DeleteRowsWithFiles($this->tableImages, 'file', $path, null, array('id'=>$imageId, 'app_category_id'=>$appCategoryId));
	}

	function DeleteAppImageMeta($imageId, $appCategoryId)
	{
		//echo'<pre>';print_r($imageId);echo'</pre>';die;
		$this->dbo->DeleteRows($this->table, array('app_image_id'=>$imageId, 'app_category_id'=>$appCategoryId));
	}

	function DeleteAppAllImages($appCategoryId, $path)
	{
		//echo'<pre>';print_r($path);echo'</pre>';die;
		$this->dbo->DeleteRowsWithFiles($this->tableImages, 'file', $path, null, array('app_category_id'=>$appCategoryId));
	}

	function DeleteAppAllImagesMeta($appCategoryId)
	{
		//echo'<pre>IMAGE ID:';print_r($appCategoryId);echo'</pre>';die;
		$this->dbo->DeleteRows($this->table, array('app_category_id'=>$appCategoryId));
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
		
	}
	
	function ExtendGetFormDataEmpty(&$data)
	{
		
	}
	
}
?>