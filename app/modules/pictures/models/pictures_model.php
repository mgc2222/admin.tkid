<?php
class PicturesModel extends AbstractModel
{
	var $imgWidth = 600;
	var $imgHeight = 800;
	
	function __construct()
	{
		parent::__construct();		
		$this->table = 'product_images';
		$this->primaryKey = 'id';
	}
	
	function GetRecordByProductId($imageId, $productId)
	{
		$sql = "SELECT id, file, img_width, img_height FROM {$this->table} WHERE {$this->primaryKey} = {$imageId} AND product_id = {$productId} LIMIT 1";
		
		return $this->dbo->GetFirstRow($sql);
	}
	
	function DeleteRecordWithFile($recordId, $path)
	{
		$this->dbo->DeleteRowsWithFiles($this->table, 'file', $path, null, array($this->primaryKey=>$recordId), 1);
	}
	
	function DeleteProductImages($productId, $path)
	{
		$this->dbo->DeleteRowsWithFiles($this->table, 'file', $path, null, array('product_id'=>$productId));
	}
	
	function UpdateProductImagesMeta($productId, $resetCount = false)
	{
		$sql = "SELECT id FROM product_images_meta WHERE product_id = {$productId}";
		$id = $this->dbo->GetFieldValue($sql);
		
		if (!$id) {
			$data = array('product_id'=>$productId, 'images_count' => 1);
			$this->dbo->InsertRow('product_images_meta', $data);
		}
		else {
			if ($resetCount) {
				$data = array('images_count' => 0);
			}
			else {
				$data = array('images_count' => '[images_count + 1]');
			}
			
			$this->dbo->UpdateRow('product_images_meta', $data, array('product_id'=>$productId));
		}
	}
	
	function ImageExists($imageName, $productId)
	{
		$sql = "SELECT product_id FROM {$this->table} WHERE image='{$imageName}' AND product_id={$productId} LIMIT 1";
		return $this->dbo->GetFieldValue($sql);
	}
	
	function GetProductInfo($productId)
	{
		$sql = 'SELECT p.name, p.default_image_id, COALESCE(pim.images_count, 0) as images_count
				FROM products p
				LEFT JOIN product_images_meta pim ON p.id = pim.product_id
			WHERE p.id='.$productId;

		return $this->dbo->GetFirstRow($sql);
	}
	
	function UpdateImagesOrder($productId, $fields, $rows)
	{
		$this->dbo->UpdateRowsBulk($this->table, $fields, $rows, array('t.id'=>'v.id'));
	}
	
	function GetProductImages($productId)
	{
		$cond = 'product_id='.$productId; 
		return $this->GetRecords($cond, 'order_index');
	}
	
	function UpdateImageSize($imageId, $width, $height)
	{
		$this->dbo->UpdateRow($this->table, array('img_width'=>$width, 'img_height'=>$height), array('id'=>$imageId));
	}

	function UpdateProductDefaultImage($productId, $imageId, $file)
	{
		$this->dbo->UpdateRow('products', array('default_image'=>$file, 'default_image_id'=>$imageId), array('id'=>$productId));
	}
	
	function GetProductImage($productId, $imageId)
	{
		$sql = "SELECT id, file FROM {$this->table} WHERE product_id = {$productId} AND id = $imageId ORDER BY order_index LIMIT 1";
		return $this->dbo->GetFirstRow($sql);
	}
}
?>