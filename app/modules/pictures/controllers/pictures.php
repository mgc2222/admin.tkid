<?php
class Pictures extends AdminController
{
	protected $imagesModel;
	protected $appImagesModel;
	
	function __construct()
	{
		parent::__construct();
		$this->module = 'pictures';
		$this->pageId = $this->module;
		$this->translationPrefix = $this->module;
		
		$this->Auth();
		$this->imagesModel = $this->LoadModel('pictures');
		$this->appImagesModel = $this->LoadModel('app_pictures');
	}
	
	function HandleAjaxRequest()
	{
		$data = $this->GetAjaxJson();
		$ajaxAction = $data['ajaxAction'];
		//echo'<pre>';print_r($data);echo'</pre>';die;
		unset($data['ajaxAction']);
		
		$response = null;
		switch ($ajaxAction)
		{
			case 'save_order': 
				$saveId = $this->UpdateImagesOrder((int)$data['productId'], explode(',', $data['img_ids']));
				//$this->UpdateProductDefaultImage((int)$data['productId']);
				$message = $this->trans[$this->translationPrefix.'.order_save_success'];
				$response = $this->GetDefaultResponse($message, $saveId);
			break;
			case 'set_default_image':
				//echo'<pre>';print_r($data);echo'</pre>';die; 
				$this->UpdateProductDefaultImage((int)$data['prod_id'], (int)$data['img_id']);
				$message = $this->trans[$this->translationPrefix.'.set_default_success'];
				$response = $this->GetDefaultResponse($message, $data['img_id']);
			break;
		}
		
		if ($response != null)
		{
			$this->WriteResponse($response);
			die();
		}
	}
	
	function GetViewData($query = '')
	{
		$this->HandleAjaxRequest();
		
		$dataSearch = $this->GetQueryItems($query, array('id'));
		array_push($this->webpage->StyleSheets, 'jquery/jquery-ui-1.8.17.custom.css', 'tooltip/jquery.tooltip.css','upload/jquery.plupload.queue.css');
		array_push($this->webpage->ScriptsFooter, 'lib/jquery/jquery-ui-1.8.17.custom.min.js', 
		'lib/upload/plupload.full.js',
		'lib/upload/jquery.plupload.queue.js',
		'lib/tooltip/jquery.tooltip.min.js', 
		_JS_APPLICATION_FOLDER.$this->pageId.'/upload_images.js?id=4',
		_JS_APPLICATION_FOLDER.$this->pageId.'/pictures.js');
		parent::SetWebpageData($this->pageId);
		
		$this->webpage->FormAttributes = 'enctype="multipart/form-data"';
				
		$form = new Form('Save');
		$formData = $form->data;
		$formData->productId = (int)$dataSearch->id;
		if ($formData->productId == 0) {

			//Session::SetFlashMessage($this->trans['products.no_elements'], _SITE_RELATIVE_URL.'products');
			$this->webpage->Redirect('products');
		}
		
		$this->ProcessFormAction($formData);
		
		$data = new stdClass();
		$data->product = $this->imagesModel->GetProductInfo($formData->productId);
		$data->rows = $this->imagesModel->GetProductImages($formData->productId);
		//echo'<pre>';print_r($data->rows);echo'</pre>';die;




		$this->FormatRows($formData->productId, $data->product->name, $data->product->default_image_id, $data->rows);
		$data->productId = $formData->productId;

		//echo'<pre>';print_r($radioData);echo'</pre>';die;
	
		$this->webpage->PageUrl = $this->webpage->AppendQueryParams($this->webpage->PageUrl);
		
		return $data;
	}

	function GetAppImagesViewData($query = '')
	{
		//echo'<pre>';print_r($this->HandleAjaxRequest());echo'</pre>';die;
		$this->HandleAjaxRequest();
		
		$dataSearch = $this->GetQueryItems($query, array('appCategoryId'));
		
		array_push($this->webpage->StyleSheets, 'jquery/jquery-ui-1.8.17.custom.css', 'tooltip/jquery.tooltip.css','upload/jquery.plupload.queue.css');
		array_push($this->webpage->ScriptsFooter, 'lib/jquery/jquery-ui-1.8.17.custom.min.js', 
		'lib/upload/plupload.full.js',
		'lib/upload/jquery.plupload.queue.js',
		'lib/tooltip/jquery.tooltip.min.js', 
		_JS_APPLICATION_FOLDER.$this->pageId.'/upload_images.js',
		_JS_APPLICATION_FOLDER.$this->pageId.'/pictures.js');

		parent::SetWebpageData('app_images');
		
		//echo'<pre>';print_r(get_class_methods(parent::SetWebpageData($this->pageId)));echo'</pre>';die;
		$this->webpage->FormAttributes = 'enctype="multipart/form-data"';
				
		$form = new Form('SaveAppImages');
		$formData = $form->data;
		$formData->appCategoryId = (int)$dataSearch->appCategoryId;
		if ($formData->appCategoryId == 0) {
			$formData->appCategoryId = 1; // set initial appCategoryId for the fist id in database
			//Session::SetFlashMessage($this->trans['app_images.no_images'], _SITE_RELATIVE_URL.'app_images');
		}
		
		$this->ProcessFormAction($formData);
		
		$data = new stdClass();
		//$data->advancedSearchBlock = $this->GetBlockPath($this->pageId.'_advanced_search_block');
		//echo'<pre>';print_r($formData);echo'</pre>';die;
		$data->appCategoryId = $formData->appCategoryId;
		//echo'<pre>';print_r($data->appCategoryId);echo'</pre>';die;
		$data->appCategoryName = $this->appImagesModel->GetAppCategoryName($data->appCategoryId);
		$this->webpage->PageHeadTitle = $this->trans['app_images.page_title'].' '.ucfirst($data->appCategoryName);
		$data->appCategoriesListContent = HtmlControls::GenerateDropDownList($this->appImagesModel->GetAppCategoriesForDropDown(), 'id', 'name', $data->appCategoryId);
		//echo'<pre>';print_r($data->appCategoriesListContent);echo'</pre>';die;
		$data->rows = $this->appImagesModel->GetAppImages($data->appCategoryId, 'order_index');
		
		$this->FormatAppImagesRows($data->appCategoryId, $data->appCategoryName, $data->rows);
		//echo'<pre>';print_r($data);echo'</pre>';die;
		//$data->pageId  = 'app_images';
		$this->webpage->PageUrl = $this->webpage->AppendQueryParams($this->webpage->PageUrl);
		
		return $data;
	}			
	
	function FormatRows($productId, $productName, $productDefaultImageId, &$rows)
	{
		if ($rows == null) {
			return;
		}
		//echo'<pre>';print_r($rows);echo'</pre>';die;
		$productName = StringUtils::UrlTitle($productName);
		$filePath = _SITE_RELATIVE_URL.'product_thumb/'.$productName.'-';
		foreach ($rows as $key=>&$row) {
			$row->thumb = $filePath.$row->id.'-120x120.'.$row->extension;
			$row->thumb_med = $filePath.$row->id.'-320x240.'.$row->extension;
			$row->thumb_med = $filePath.$row->id.'-320x240.'.$row->extension;
			$row->radioData = new stdClass();
			$row->radioData->inputs = [];
			$row->radioData->name = 'rdDefaultImage';
			$row->radioData->elementTag = 'div';
			$row->radioData->inputs[$key] = new stdClass();
			$row->radioData->inputs[$key]->id = $row->id;
			$row->radioData->inputs[$key]->value = $row->id;
			$row->radioData->inputs[$key]->label = $this->trans['pictures.choose_default_image'];
			$row->radioData->inputs[$key]->attributes = <<<EOF
			onclick="frm.PostAjaxJson(window.location.pathname,{ ajaxAction:'set_default_image', prod_id:$productId, img_id:$row->id}, function(){})"
EOF;
			$row->radioData->selectedValue = 0;
			
			if($row->id == $productDefaultImageId){
				$row->radioData->selectedValue = $row->id;
			}
		}
	}

	function FormatAppImagesRows($appCategoryId, $categoryName, &$rows)
	{
		if ($rows == null) {
			return;
		}
		$categoryName = StringUtils::UrlTitle($categoryName);
		$filePath = _SITE_RELATIVE_URL.'app_thumb/'.$categoryName.'-';
		foreach ($rows as &$row) {
			$row->thumb = $filePath.$row->id.'-120x120.'.$row->extension;
			$row->thumb_med = $filePath.$row->id.'-320x240.'.$row->extension;
		}
	}

	function GetRadioButtons($controlName, $wrapper, $wrapperAttributes, $selectedValue, $arrIds, $arrValues, $arrLabels)
	{
		$rlist = new RadioList();
		$rlist->SetAttributes($controlName, $selectedValue, $wrapper, $wrapperAttributes);
		$rlist->AddItems($arrIds, $arrValues, $arrLabels);
		//echo'<pre>';print_r($rlist);echo'</pre>';die;	
		return $rlist;
	}

	function ProcessFormAction(&$formData)
	{
		//echo'<pre>';print_r($formData);echo'</pre>';die;
		switch($formData->Action)
		{
			case 'FilterResults':
				$this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'sys_Action', 'FilterResults',
				array('appCategoryId'), array('appCategoryId'));
			break;
			case 'Save':
			//echo'<pre>';print_r($formData);echo'</pre>';die;
				$this->UploadImage('fileUpload', $formData->productId);
				$this->webpage->PageUrl = $this->webpage->AppendQueryParams($this->webpage->PageUrl);
				$this->UpdateProductDefaultImage($formData->productId);
				Session::SetFlashMessage('Imaginea a fost salvata', 'success', $this->webpage->PageUrl);
			break;
			case 'SaveAppImages':
				$this->UploadImage('fileUpload', $formData->Action);
				$this->webpage->PageUrl = $this->webpage->AppendQueryParams($this->webpage->PageUrl);
				$this->appImagesModel->SaveAppImages($formData);
				Session::SetFlashMessage('Imaginea a fost salvata', 'success', $this->webpage->PageUrl);
			break;
			case 'Delete':
				$path = $this->GetBasePath()._PRODUCT_IMAGES_PATH.$formData->productId.'/';
				$this->imagesModel->DeleteRecordWithFile((int)$formData->Params, $path);
				$this->webpage->PageUrl = $this->webpage->AppendQueryParams($this->webpage->PageUrl);
				$this->UpdateProductDefaultImage($formData->productId);
				Session::SetFlashMessage('Imaginea a fost stearsa', 'success', $this->webpage->PageUrl);
			break;
			case 'DeleteProductImages':
				$path = $this->GetBasePath()._PRODUCT_IMAGES_PATH.$formData->productId.'/';
				$this->imagesModel->DeleteProductImages($formData->productId, $path);
				$this->imagesModel->UpdateProductImagesMeta($formData->productId, true);
				$this->UpdateProductDefaultImage($formData->productId);
				$this->webpage->PageUrl = $this->webpage->AppendQueryParams($this->webpage->PageUrl);
				Session::SetFlashMessage('Imaginile au fost sterse', 'success', $this->webpage->PageUrl);
			break;
			case 'DeleteAppImage':
				$path = $this->GetBasePath()._APP_IMAGES_PATH.$formData->appCategoryId.'/';
				$this->appImagesModel->DeleteAppImage($formData->Params, $formData->appCategoryId, $path);
				$this->appImagesModel->DeleteAppImageMeta($formData->Params, $formData->appCategoryId);
				$this->webpage->PageUrl = $this->webpage->AppendQueryParams($this->webpage->PageUrl);
				Session::SetFlashMessage('Imaginile au fost sterse', 'success', $this->webpage->PageUrl);
			break;
			case 'DeleteAppAllImages':
				$path = $this->GetBasePath()._APP_IMAGES_PATH.$formData->appCategoryId.'/';
				$this->appImagesModel->DeleteAppAllImages($formData->appCategoryId, $path);
				$this->appImagesModel->DeleteAppAllImagesMeta($formData->appCategoryId);
				$this->webpage->PageUrl = $this->webpage->AppendQueryParams($this->webpage->PageUrl);
				Session::SetFlashMessage('Imaginile au fost sterse', 'success', $this->webpage->PageUrl);
			break;
		}
	}
	
	function UploadImage($query = '')
	{
		
		$this->IncludeClasses(array('system/lib/files/file_upload.php'));
		$dataSearch = $this->GetQueryItems($query, array('appCategoryId'));
		//echo'<pre>';print_r($dataSearch);echo'</pre>';die;
		if($dataSearch->appCategoryId){
			$appCategoryId = $dataSearch->appCategoryId;
			$this->UploadAppImage('file', $appCategoryId, 'UploadAppImages');
			die();
		}
		else{
			$dataSearch = $this->GetQueryItems($query, array('productId'));
			$productId = $dataSearch->productId;
			$this->UploadProductImage('file', $productId);
			die();
		}
		
	}
	
	function UploadProductImage($fileInputId, $productId)
	{
		$fileInfo = $this->GetFileInfo($fileInputId, $productId);
		//echo'<pre>';print_r($fileInfo);echo'</pre>';die;
		$fileSavedData = $this->UploadFile($fileInputId, _PRODUCT_IMAGES_PATH.$productId, $fileInfo->fileName);
		//echo'<pre>';print_r($fileSavedData);echo'</pre>';die;
		if ($fileSavedData['status'])
		{ 
			$row = array('id'=>0, 'product_id'=>$productId, 'file'=>$fileInfo->fileName, 'order_index'=>$fileInfo->imageOrder, 'img_width'=>$fileSavedData['img_width'], 'img_height'=>$fileSavedData['img_height'], 'extension'=>$fileSavedData['extension']);
			$imageId = $this->imagesModel->SaveData($row);
			$this->imagesModel->UpdateProductImagesMeta($productId);
			return $imageId;
		}
		else 
		{
			$this->Message = 'Imaginea nu a fost salvata:'.$fileInfo->filePath.'.'.$fileSavedData['upload_message'];
			return 0;
		}
	}

	function UploadAppImage($fileInputId, $appCategoryId, $action = '')
	{
		//echo'<pre>';print_r($action);echo'</pre>';die;
		$fileInfo = $this->GetFileInfo($fileInputId, $appCategoryId, $action);
		$fileId = $this->appImagesModel->GetLastInsertedAppImageId();
		//echo'<pre>';print_r($fileId);echo'</pre>';die;
		$file = preg_replace('/-\d+\.'. $fileInfo->fileExtension.'/', '', $fileInfo->fileName);
		$fileName = $file.'-'.$fileId.'.'.$fileInfo->fileExtension;
		$fileSavedData = $this->UploadFile($fileInputId, _APP_IMAGES_PATH.$appCategoryId, $fileName);
		if ($fileSavedData['status'])
		{ 
			$row = array('id'=>0, 'app_category_id'=>$appCategoryId, 'file'=>$fileName, 'img_width'=>$fileSavedData['img_width'], 'img_height'=>$fileSavedData['img_height'], 'extension'=>$fileSavedData['extension']);
			$imageId = $this->appImagesModel->SaveAppImages($row);
			$this->appImagesModel->InsertAppImagesMeta($imageId, $appCategoryId);
			return $imageId;
		}
		else 
		{
			$this->Message = 'Imaginea nu a fost salvata:'.$fileInfo->filePath.'.'.$fileSavedData['upload_message'];
			return 0;
		}
	}


	
	function GetFileInfo($fileInputId, $elementId, $action = '')
	{
		// image path is: _PRODUCT_IMAGES_PATH./{productId}/{elementName}-X.{fileExtension}  , X = image index
		
		$elementInfo = ($action=='UploadAppImages') ? $this->GetAppInfo($elementId) : $this->GetProductInfo($elementId);
		//echo'<pre>';print_r($elementInfo);echo'</pre>';die;
		$elementName = StringUtils::UrlTitle($elementInfo->name);
		$imageOrder = $elementInfo->images_count + 1;
		
		$fileNameBase = $elementName.'-';
		
		// there may be cases when user deletes some images and the max order will be less than the order in the existing images name
		// therefor, check in a loop if the images already exists, and if so, increment the imageOrder and check again
		$filePath = '';
		$extension = $this->GetUploadedFileExtension($fileInputId);
		
		$fileName = $fileNameBase;
		
		$fileName .= $imageOrder.'.'.$extension;
		$filePath = ($action=='UploadAppImages') ? _APP_IMAGES_PATH.$elementId.'/'.$fileName : _PRODUCT_IMAGES_PATH.$elementId.'/'.$fileName;			
		
		$ret = new stdClass();
		$ret->fileName = $fileName;
		$ret->fileExtension = $extension;
		$ret->filePath = $filePath;
		$ret->imageOrder = $imageOrder;
		//echo'<pre>';print_r($ret);echo'</pre>';die;
		return $ret;
	}
	
	function GetProductInfo($productId)
	{
		return $this->imagesModel->GetProductInfo($productId);
	}

	function GetAppInfo($appCategoryId)
	{
		return $this->appImagesModel->GetAppInfo($appCategoryId);
	}
	
	function GetUploadedFileExtension($fileInputId)
	{
		//echo'<pre>';print_r($_FILES);echo'</pre>';die;
		$fileName = $_FILES[$fileInputId]['name'];
		$fileParts = pathinfo($fileName);
		return $fileParts['extension'];
	}
	
	function UploadFile($fileInputId, $fileFolder, $fileName)
	{
		$fileUpload = new FileUpload();
		$this->Message = '';
		
		$options = new stdClass();
		$options->fileMaxSize = '5000000';
		$options->allowedTypes = array('image/jpeg','image/pjpeg','image/gif','image/png','application/octet-stream');
		
		$imagePath = $fileFolder.'/'.$fileName;

		// save the original image
		$options->actions = array(
			array('action'=>'save_file', 'filePath'=>$imagePath)
		);
						
		$uploadOk = $fileUpload->ProcessUploadFile($fileInputId, $fileFolder, $fileName, $options);
		$ret = array('status'=>$uploadOk, 'file_name'=>$fileName, 'upload_message'=>$fileUpload->lastError, 'img_width'=>$fileUpload->ImageWidth, 'img_height'=>$fileUpload->ImageHeight, 'extension'=>$fileUpload->FileExtension);
		
		return $ret;
	}
	
	
	function UpdateImagesOrder($productId, $imageIdList)
	{
		if (!$imageIdList || count($imageIdList) == 0) return 0;
		$fields = array('id', 'order_index');
		
		$orderIndex = 1;
		$rows = array();
		foreach ($imageIdList as $imageId)
		{
			$row = array($imageId, $orderIndex);
			array_push($rows, $row);
			$orderIndex++;
		}
		
		$this->imagesModel->UpdateImagesOrder($productId, $fields, $rows);
		return 1;
	}
	
	private function UpdateProductDefaultImage($productId, $imageId)
	{
		$row = $this->imagesModel->GetProductImage($productId, $imageId);
		if (!$row) {
			return;
		}
		$this->imagesModel->UpdateProductDefaultImage($productId, $row->id, $row->file);
	}
}
?>
