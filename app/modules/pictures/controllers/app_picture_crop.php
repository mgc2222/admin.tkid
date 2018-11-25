<?php
class AppPictureCrop extends AdminController
{
	protected $appImagesModel;
	function __construct()
	{
		parent::__construct();
		$this->module = 'pictures';
		$this->pageId = 'picture_crop';
		$this->translationPrefix = $this->pageId;
		
		$this->Auth();
		$this->appImagesModel = $this->LoadModel('app_pictures');
	}
	
	function GetViewData($query = '')
	{
		$dataSearch = $this->GetQueryItems($query, array('id'));		
		$pictureId = (int)$dataSearch->id;
		$row = $this->GetPropertyImageData($pictureId);
		//echo'<pre>';print_r($dataSearch);echo'</pre>';die;
		array_push($this->webpage->StyleSheets, 'crop/jquery.Jcrop.css');
		array_push($this->webpage->ScriptsFooter, 'lib/jquery/jquery-ui-1.8.17.custom.min.js',
		'lib/crop/jquery.Jcrop.min.js',
		_JS_APPLICATION_FOLDER.$this->module.'/image_crop.js');
		parent::SetWebpageData($this->pageId);
		
		$this->webpage->FormAttributes = 'onsubmit="return checkCoords();"';
		$this->webpage->PageReturnUrl = _SITE_RELATIVE_URL.'images';

		$form = new Form('CropImage');
		$formData = $form->data;
		$formData->pictureId = $pictureId;
		$formData->appCategoryId = $row->app_category_id;
		//echo'<pre>';print_r($formData);echo'</pre>';die;
		$this->ProcessFormAction($formData);
		
		$data = $formData;
		$data->image = _SITE_RELATIVE_URL._APP_IMAGES_PATH.$row->app_category_id.'/'.$row->file;
		
		return $data;
	}
	
	function GetPropertyImageData($targetId)
	{
		
		$row = null;
		$row = $this->appImagesModel->GetAppImageById($targetId);
		if ($row == null) Session::SetFlashMessage('Poza nu a fost gasita', 'error', $this->webpage->PageReturnUrl);
		
		return $row;
	}
	
	function ProcessFormAction(&$formData)
	{
		switch($formData->Action)
		{
			case 'CropImage':
				$this->IncludeClasses(array('system/lib/files/file_upload.php'));
				$this->CropImage($formData->pictureId, $formData->appCategoryId, (int)$_POST['w'], (int)$_POST['h'], (int)$_POST['x'], (int)$_POST['y']);
				$this->webpage->SetMessage('Imaginea a fost modificata.', 'success');
			break;
		}
	}

	function CropImage($imageId, $appCategoryId, $width, $height, $x, $y)
	{
		$row = $this->appImagesModel->GetAppImageById($imageId);
		if ($row == null) return 0;
		
		$filePath = _APP_IMAGES_PATH.$appCategoryId.'/';
		$fileName = $row->file;

		$fileUpload = new FileUpload();
		
		$imagePath = $filePath.$fileName;
		
		$options = new stdClass();
		$options->actions = array(
			array('action'=>'crop_image',  'start_x'=>$x, 'start_y'=>$y, 'width'=>$width,'height'=>$height, 'filePath'=>$imagePath, 'quality'=>0.95)
		);
						
		$uploadOk = $fileUpload->ProcessFile($imagePath, $options);
		
		$this->appImagesModel->UpdateAppImageSize($imageId, $width, $height);
		
		return $row->id;
	}
	
}
?>
