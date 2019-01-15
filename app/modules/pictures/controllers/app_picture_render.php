<?php
class AppPictureRender extends AdminController
{
	protected $appImagesModel;
	
	function __construct()
	{
		parent::__construct();
		$this->module = 'pictures';
		$this->pageId = $this->module;
		$this->translationPrefix = $this->module;
		
		$this->appImagesModel = $this->LoadModel('app_pictures');
	}
	
	function RenderImage($query)
	{
		$this->renderPicture($query, 'resize_image');
	}
	
	function RenderThumb($query)
	{
		$this->renderPicture($query, 'crop_ratio_and_resize_image');
	}
	
	private function renderPicture($query, $action)
	{
		$fileInfo = $this->extractFileInfo($query);

		if (!$fileInfo || $fileInfo->imageId == 0) {
			header("HTTP/1.0 404 Not Found");
			die();
		}
		$row = $this->appImagesModel->GetAppImageById($fileInfo->imageId);
		if ($row == null || $row->file == '') {
			header("HTTP/1.0 404 Not Found");
			die();
		}
		//echo'<pre>';print_r($row);echo'</pre>';die;
		$classes = array('system/lib/files/cache_file.php', 'system/lib/files/file_upload.php');
		$this->IncludeClasses($classes);
		
		$cacheWidth = ($fileInfo->width == '*') ? '' : $fileInfo->width;
		$cacheHeight = ($fileInfo->height == '*') ? '' : $fileInfo->height;
		
		$fileName = preg_replace('/-\d+\.'.$row->extension.'/', '', $row->file);
		$cacheFileName = $fileName.'-'.$row->id.'-'.$cacheWidth.'x'.$cacheHeight.'.'.$row->extension;
		
		//$cacheFileServerPath = _SITE_RELATIVE_URL._APP_IMAGES_PATH.$row->app_category_id.'/'.$cacheFileName;
		$cacheFileSavePath =  $this->GetBasePath()._APP_CACHE_IMAGES_PATH.$row->app_category_id.'/'.$cacheFileName;
        if(!file_exists($cacheFileSavePath)){
            header("HTTP/1.0 404 Not Found");
            die();
        }
		$fileContent = null;
		$fileContent = CacheFile::ReadFile($cacheFileSavePath);
		if ($fileContent) {
			$this->outputFile($fileContent, $row->extension);
		}
		
		$filePath = $this->GetBasePath()._APP_IMAGES_PATH.$row->app_category_id.'/'.$row->file;
        if(!file_exists($filePath)){
            header("HTTP/1.0 404 Not Found");
            die();
        }
		$fileUpload = new FileUpload();
		$options = new stdClass();
		$options->actions = array(
			array('action'=>$action,  'quality'=>0.85, 'mentain_aspect_ratio'=>true, 'width'=>$fileInfo->width,'height'=>$fileInfo->height, 'filePath'=>$cacheFileSavePath)
		);
						
		$processInfo = $fileUpload->ProcessFile($filePath, $options);
		if ($fileUpload->lastError) {
			// echo $fileUpload->lastError;
			header("HTTP/1.0 404 Not Found");
			die();
		}

		$fileContent = file_get_contents($cacheFileSavePath);
		$this->outputFile($fileContent, $row->extension);
	}
	
	private function outputFile($fileContent, $extension)
	{
		switch( $extension ) {
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpeg":
			case "jpg": $ctype="image/jpeg"; break;
			default:
		}

		header('Content-type: ' . $ctype);
		echo $fileContent;
		die();
	}
	
	private function extractFileInfo($query)
	{
		$pattern = '/\-(\d+)-(\d*)x(\d*)\./';
		if (!preg_match($pattern, $query, $capture)){
			return null;
		}
		
		$data = new stdClass();
		$data->imageId = (int)$capture[1];
		$data->width = (int)$capture[2];
		$data->height = (int)$capture[3];		
		
		if ($data->width == 0) {
			$data->width = '*';
		}
		if ($data->height == 0) {
			$data->height = '*';
		}
		
		return $data;
	}
}
?>
