<?php
class Content extends AdminController
{
	var $categoriesModel;
	var $pageId;
	var $translationPrefix;
	var $productsCategoriesModel;
	function __construct()
	{
		parent::__construct();
		$this->module = 'content';
		$this->pageId = 'content';
		$this->translationPrefix = 'content';
		
		$this->Auth();
		$this->categoriesModel = $this->LoadModel('categories', 'categories');
        $this->productsCategoriesModel = $this->LoadModel('product_categories', 'categories');

        $basePath = $this->GetBasePath();
        $categoriesMapPath = $basePath.'system/lib/dbutils/categories_map.php';
        $productCategoriesMapPath = $basePath._APPLICATION_FOLDER.'lib/categories_map/product_categories_map.php';
        $this->IncludeClasses(array($categoriesMapPath, $productCategoriesMapPath));

	}

	// ================= Categories Lists - BEGIN =================== //
	
	function HandleAjaxRequest()
	{
		$data = $this->GetAjaxJson();
		if ($data == null) 
			return;
		$ajaxAction = $data['ajaxAction'];
		unset($data['ajaxAction']);

		$response = null;
		switch ($ajaxAction)
		{
			case 'VerifyCategory': 
				$response = $this->VerifyCategory($data);
			break;
			case 'change_order': $this->CheckSortableAction(); break;
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
		// definitions
		$dataSearch = $this->GetQueryItems($query, array('search', 'parentId', 'categoryId'));
		if (!$dataSearch->parentId) {
			$dataSearch->parentId = 0;
		}
        //echo'<pre>';print_r($dataSearch);echo'</pre>';die;
		array_push(
			$this->webpage->StyleSheets,
            'jquery/jquery-ui-1.8.17.custom.css',
			'upload/jquery.plupload.queue.css',
			'toastr/toastr.min.css'
		);
		array_push(
			$this->webpage->ScriptsFooter,
			//'lib/jquery/jquery-ui.min.js',
            'lib/validator/jquery.validate.min.js',
            'lib/wrappers/validator/validator.js',
			'lib/toastr/toastr.min.js',
			'lib/base64/jquery.base64.js',
            'lib/tinymce/tinymce.min.js',
            'lib/wrappers/tinymce/tinymce.js',
			_JS_APPLICATION_FOLDER.$this->pageId.'/category_edit.js'
		);
		parent::SetWebpageData($this->pageId);
		
		
		$form = new Form();
		$formData = $form->data;
        $formData->categoryId = (int)$dataSearch->categoryId;
		$this->ProcessFormAction($formData);

        $data = new stdClass();
        $this->webpage->FormAttributes = 'enctype="multipart/form-data"';
        $data->categoryContentId = $this->categoriesModel->GetCategoryIdByCategoryName('content');
        $data->categoriesContentList = $this->categoriesModel->GetActiveCategoriesForDropDown($data->categoryContentId);
        if ($formData->categoryId == 0 && count($data->categoriesContentList)) {
            $data->categoryId = $data->categoriesContentList[0]->id;
        }
        else{
        	$data->categoryId = $formData->categoryId ;
        }
        $data->category = $this->categoriesModel->GetActiveCategoryById($data->categoryId); // set initial categoryId for the fist id in list
        //echo'<pre>';print_r($this->transJson);echo'</pre>';die();
        $data->categoriesContentListDropDown = HtmlControls::GenerateDropDownList($data->categoriesContentList, 'id', 'name', $data->categoryId);

		$category_content = (isset($this->transJson['category_id_'.$data->categoryId])) ? $this->transJson['category_id_'.$data->categoryId] : '';

        $data->categoryContent = new stdClass();
        $data->categoryContent->html = (isset($category_content['html'])) ? $category_content['html'] : '';

        $this->webpage->PageHeadTitle = ucfirst($data->category->name);

		return $data;
	}
	
	function SaveContent(&$formData)
	{
        $arrTemp = [];
        $arrTemp['name'] = $formData->categoryName;
        $arrTemp['html'] = $formData->html;
        $this->transJson['category_id_'.intval($formData->categoryId)] = $arrTemp;
        $f = fopen(_APPLICATION_FOLDER.'langs/'.$this->language->abbreviation_iso.'/pages.json', 'w');
		fwrite($f, json_encode($this->transJson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	}
	// ================= Category Edit - END =================== //

	function ProcessFormAction(&$formData)
	{

		switch($formData->Action)
		{
            case 'FilterResults':
                //echo'<pre>';print_r($formData);die();
                $this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'sys_Action', 'FilterResults',
                    array('categoryId'), array('categoryId'));
                break;
            /*case 'ChangeLanguage':
                //echo'<pre>';print_r($formData);die();
                $this->webpage->Redirect($this->GetRelativePath('change_language'));
                $this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'sys_Action', 'FilterResults',
                    array('categoryId'), array('categoryId'));
                break;*/
			case 'Delete':
			//echo'<pre>';print_r($formData);echo'</pre>';die;
				$id = (int)$formData->Params;
				$this->categoriesModel->DeleteRecord($id);
				$this->productsCategoriesModel->DeleteCategoryProducts($id);
				$this->categoriesModel->DeleteDiskFile('../cache/mainmenu.tmp'); // force refresh menu
				Session::SetFlashMessage($this->trans['categories.delete_success'], 'success', $this->webpage->PageUrl);
			break;
			case 'DeleteSelected':
			//echo'<pre>';print_r($formData);echo'</pre>';die;
				$selectedRecords = $this->GetSelectedRecords();
				if ($selectedRecords != '')
				{
					$this->categoriesModel->DeleteSelectedRecords($selectedRecords);
					$this->productsCategoriesModel->DeleteCategoryProducts($selectedRecords);
					$this->categoriesModel->DeleteDiskFile('../cache/mainmenu.tmp'); // force refresh menu
					Session::SetFlashMessage($this->trans['categories.delete_selected_success'], 'success', $this->webpage->PageUrl);
				}
				else $this->webpage->SetMessage($this->trans['categories.error_selected_elements'], 'error');
			break;
			case 'Save': $this->SaveContent($formData); break;
			case 'DeleteFile':
				$filePath = _SITE_RELATIVE_URL._CATEGORIES_PATH;
				$this->categoriesModel->DeleteFile($formData->EditId, $filePath);
			break;			
		}
	}
	
	function UploadFile($fileInputId, $fileName)
	{
		$basePath = $this->GetBasePath();
		$filePath = $basePath._CATEGORIES_PATH;
		$fileUpload = $this->LoadLibrary('files', 'file_upload');
		$options = new stdClass();
		$options->fileMaxSize = '5000000';
		$options->allowedTypes = array('image/jpeg','image/pjpeg','image/gif','image/png','application/octet-stream');
		$options->ignoreNoFileSelected = true;
		
		$imagePath = $filePath.$fileName;
		$thumbPath = $filePath._THUMBS_PATH.$fileName;
		
		$options->actions = array(
				// array('action'=>'resize_image', 'width'=>268,'height'=>201, 'mentain_aspect_ratio'=>true, 'filePath'=>$imagePath) ,
				array('action'=>'crop_ratio_and_resize_image', 'width'=>268,'height'=>201, 'filePath'=>$imagePath, 'quality'=>0.95),
				array('action'=>'crop_ratio_and_resize_image', 'width'=>54,'height'=>54, 'filePath'=>$thumbPath, 'quality'=>0.85),
				
		);		
		
		$uploadOk = $fileUpload->ProcessUploadFile($fileInputId, $filePath, $fileName, $options);
		$errorMessage = $fileUpload->lastError;
		if ($errorMessage != '')
		{
			$errorMessage = $this->trans['upload.'.$errorMessage];
			if ($fileUpload->lastErrorParam != '') {
				$errorMessage = sprintf($errorMessage, $fileUpload->lastErrorParam);
			}
		}
		
		$updateFileName = $errorMessage == '';
		$ret = array('status'=>$uploadOk, 'file_name'=>$fileName.'.'.$fileUpload->FileExtension, 'upload_message'=>$errorMessage, 'update_filename' => $updateFileName );
		
		return $ret;
	}
	
	function GetImagePath($fileName)
	{
		$filePath = $this->GetBasePath()._CATEGORIES_PATH;
		if ($fileName != '')
		{
			$filename = $filePath.$fileName;
			if (file_exists($filename)) {
				$fileName = _SITE_RELATIVE_URL._CATEGORIES_PATH.$fileName; 
			}
		}
		return $fileName;
	}
	
	function CompleteFormDataPost(&$formData, &$row)
	{
		$formData->txtFile = '';
	}
}
?>
