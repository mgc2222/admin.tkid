<?php 
class AppPicturesModel extends AbstractModel
{	
	function __construct()
	{
		parent::__construct();
		$this->table = 'app_images_meta';
		$this->tableImages = 'app_images';
		$this->tableCategories = 'categories';
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
	
	function GetCategoryInfo($categoryId)
	{
		//echo'<pre>';print_r($categoryId);echo'</pre>';die;
		$sql = "SELECT a.name, COUNT(aim.app_image_id) as images_count
				FROM {$this->tableCategories} a
				LEFT JOIN {$this->table} aim ON a.id = aim.app_category_id
			WHERE a.id=".$categoryId;
		return $this->dbo->GetFirstRow($sql);
	}

	function GetLastInsertedAppImageId()
	{
		$sql = "select auto_increment from information_schema.TABLES where TABLE_NAME ='{$this->tableImages}' and TABLE_SCHEMA='"._DB_DATA_BASE."'";	
		return $this->dbo->GetFieldValue($sql);
	}

	function GetCategoryName($categoryId)
	{
		//echo'<pre>';print_r($categoryId);echo'</pre>';die;
		$sql = "SELECT name FROM {$this->tableCategories} WHERE id={$categoryId} LIMIT 1";
		return $this->dbo->GetFieldValue($sql); 
	}

    function GetCategoryIdByCategoryName($categoryName)
    {
        //echo'<pre>';print_r($categoryId);echo'</pre>';die;
        $sql = "SELECT id FROM {$this->tableCategories} ac WHERE ac.name='{$categoryName}' LIMIT 1";
        return $this->dbo->GetFieldValue($sql);
    }

	function GetCategoriesForDropDown($id=true)
	{
		$sql = "SELECT * FROM {$this->tableCategories} WHERE parent_id=$id AND status=1";
		return $this->dbo->GetRows($sql);
	}

	function GetAppImageById($imageId)
	{
		//echo'<pre>';print_r($imageId);echo'</pre>';die;
		$cond = 'ti.id='.$imageId;
		$sql = "SELECT * FROM {$this->tableImages} ti LEFT JOIN {$this->table} t ON ti.id=t.app_image_id WHERE {$cond}";
		$row = $this->dbo->GetFirstRow($sql);
			
		return $row;
	}

	function GetAppImages($categoryId, $order='')
	{
		//echo'<pre>';print_r($categoryId);echo'</pre>';die;
        $order = ($order) ?: 'id';
		$cond = 'ti.app_category_id='.$categoryId;
		$sql = "SELECT * FROM {$this->tableImages} ti LEFT JOIN {$this->table} t ON ti.id=t.app_image_id WHERE {$cond} ORDER BY {$order}";
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

	function InsertAppImagesMeta($imageId, $categoryId)
	{
		//echo'<pre>IMAGE ID:';print_r($imageId);echo'</pre>';die;
		$id = $this->GetAppImageMetaIdByAppImageId($imageId);
		$data = array('app_image_id'=>$imageId, 'app_category_id'=>$categoryId);
		if (!$id) {
			
			$this->dbo->InsertRow($this->table, $data);
		}
		else {
			$this->UpdateAppImagesMeta($imageId, $categoryId, $data);
		}
	}

	function UpdateAppImagesMeta($imageId, $categoryId, $data)
	{
		//echo'<pre>IMAGE ID:';print_r($data);echo'</pre>';die;
		$this->dbo->UpdateRow($this->table, $data, array('app_image_id'=>$imageId));
		
	}

	function UpdateAppImageSize($imageId, $width, $height)
	{
		$this->dbo->UpdateRow($this->tableImages, array('img_width'=>$width, 'img_height'=>$height), array('id'=>$imageId));
	}

	function DeleteAppImage($imageId, $categoryId, $path)
	{
		//echo'<pre>';print_r($imageId);echo'</pre>';die;
		$this->dbo->DeleteRowsWithFiles($this->tableImages, 'file', $path, null, array('id'=>$imageId, 'app_category_id'=>$categoryId));
	}

	function DeleteAppImageMeta($imageId, $categoryId)
	{
		//echo'<pre>';print_r($imageId);echo'</pre>';die;
		$this->dbo->DeleteRows($this->table, array('app_image_id'=>$imageId, 'app_category_id'=>$categoryId));
	}

	function DeleteAppAllImages($categoryId, $path)
	{
		//echo'<pre>';print_r($path);echo'</pre>';die;
		$this->dbo->DeleteRowsWithFiles($this->tableImages, 'file', $path, null, array('app_category_id'=>$categoryId));
	}

	function DeleteAppAllImagesMeta($categoryId)
	{
		//echo'<pre>IMAGE ID:';print_r($categoryId);echo'</pre>';die;
		$this->dbo->DeleteRows($this->table, array('app_category_id'=>$categoryId));
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