<?php
class RenderAppImage extends AdminController
{
	
	function __construct()
	{
		//parent::__construct();
		$this->module = 'pictures';
	}
	
	function RenderImage($query)
	{
		//echo '<pre>'; print_r($query); echo '</pre>'; die;	
		$this->renderPicture($query);
	}
	
	private function renderPicture($query)
	{
		$fileInfo = $this->extractFileInfo($query);
		//echo '<pre>'; print_r($fileInfo); echo '</pre>'; die;

		$classes = array('system/lib/files/cache_file.php');
		$this->IncludeClasses($classes);
		
		//$cacheFileServerPath = _SITE_RELATIVE_URL._APP_IMAGES_PATH.$fileInfo->app_category_id.'/'.$cacheFileName;
		$filePath = $this->GetBasePath()._APP_IMAGES_PATH.$fileInfo->appCategoryId.'/'.$fileInfo->fileName.'.'.$fileInfo->extension;
				
		$fileContent = null;
		$fileContent = CacheFile::ReadFile($filePath);
		if ($fileContent) {
			$this->outputFile($fileContent, $fileInfo->extension);
		}
		
		$fileContent = file_get_contents($filePath);
		if($fileContent){
			$this->outputFile($fileContent, $fileInfo->extension);
		}
		
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
		$pattern = '/(\d+)\/([^.]*)\.([^.]*)/';
		if (!preg_match($pattern, $query, $capture)){
			return null;
		}
		
		$data = new stdClass();
		$data->appCategoryId = (int)$capture[1];
		$data->fileName = $capture[2];
	
		$data->extension = $capture[3];		
		
		return $data;
	}
}
?>
