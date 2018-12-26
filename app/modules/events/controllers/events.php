<?php
class Events extends AdminController
{
	var $eventsModel;
	var $productsCategoriesModel;
	function __construct()
	{
		parent::__construct();
		$this->module = 'events';
		$this->pageId = 'events';
		$this->translationPrefix = 'events';
		
		$this->Auth();
		$this->eventsModel = $this->LoadModel('events');
		$this->productsCategoriesModel = $this->LoadModel('events');
		
		$basePath = $this->GetBasePath();
		//$this->IncludeClasses(array($eventsMapPath, $productCategoriesMapPath));

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
	
	function VerifyEvents(&$data)
	{

		$name = $this->eventsModel->GetSafeValue($data['name']);
		$urlKey = $this->eventsModel->GetSafeValue($data['urlKey']);
		$editId = (int)$data['editId'];
		
		$response = new stdClass();
		$response->eventsExists = ($this->eventsModel->GetRecordExists('name', $name, $editId))?1:0;
		$response->urlKeyExists = ($this->eventsModel->GetRecordExists('url_key', $urlKey, $editId))?1:0;
		
		return $response;
	}
	
	function CheckSortableAction()
	{
		$basePath = $this->GetBasePath();
		$sortTableCategoriesPath = $basePath._APPLICATION_FOLDER.'lib/sort_table/sort_table_events.php';
		$sortTablePath = $basePath.'system/lib/grid/sort_table.php';
		$sortTable = $this->LoadClass($sortTableCategoriesPath, 'SortTableCategories', array($sortTablePath));
		
		if (!$sortTable->GetAjaxParams()) {
			return;
		}
		
		$dataSearch = null;
		$sortTable->itemsCount = $this->eventsModel->GetRecordsListCount($dataSearch);
		$rowsIds = $this->eventsModel->GetRecordsIds($dataSearch);
		$rows = $sortTable->GetRowsForOrder($rowsIds, 'id');
		$data = $sortTable->PerformSort('events','id','order_index', $rows);

		if ($data->isAjaxCall)
		{
			if ($data->status == 'error' || !$data->refresh )
			{
				echo json_encode($data);
				exit();
			}
			else if ($data->refresh)
			{
				// variables for include file
				$categoryId = isset($_GET['id'])?(int)$_GET['id']:0;
				$dataView = new stdClass();
				$webpage = new stdClass();
				
				$eventsMap = ProductCategoriesMap::GetInstance();
				$eventsMap->MapCategories();
		
				// $dataView->rows = $this->eventsModel->GetCategoriesListTree($categoryId);
				$this->webpage->PageDefaultUrl = 'events';
				$dataView->rows = $eventsMap->GetTreeList($categoryId);
				$this->FormatRows($dataView->rows);
				
				ob_start();
				
				$trans = $this->trans;
				include($this->GetBlockPath('events_block'));
				$data->content = ob_get_contents();
				ob_end_clean();
				
				$data->content = base64_encode($data->content);
				echo stripslashes(json_encode($data));
				exit();
			}
		}
	}
	
	function GetViewData($query = '')
	{
		$this->HandleAjaxRequest();
		// definitions
		$dataSearch = $this->GetQueryItems($query, array('search', 'parentId'));
		if (!$dataSearch->parentId) {
			$dataSearch->parentId = 0;
		}
				
		array_push($this->webpage->StyleSheets,
			'jquery/jquery-ui.css',
			//'bootstrap/bootstrap3.3.7.min.css',
			'toastr/toastr.min.css',
            'bootstrapcalendar/css/calendar.css',
            'bootstrapcalendar/css/custom.css',
            'daterangepicker/daterangepicker.css',
            'select2/select2.min.css'
			);
		array_push($this->webpage->ScriptsFooter,
			'lib/jquery/jquery-ui.min.js',
			//'lib/toastr/toastr.min.js',
			//'lib/base64/jquery.base64.js',
			//'lib/wrappers/sortable/sortable_init.js',
			//'lib/bootstrap/bootstrap3.3.7.min.js',
            'lib/moment/moment.min.js',
            'lib/daterangepicker/daterangepicker.min.js',
            'lib/tinymce/tinymce.min.js',
            'lib/wrappers/tinymce/tinymce.js',
            'lib/underscore/underscore-min.js',
            'lib/select2/select2.min.js',

            'lib/jstimezonedetect/jstz.min.js',
            'lib/bootstrapcalendar/language/ro-RO.js',
            'lib/bootstrapcalendar/calendar.js',
            'lib/bootstrapcalendar/app.js',
			_JS_APPLICATION_FOLDER.$this->module.'/events.js'
			);
		parent::SetWebpageData($this->pageId, 'events');
		
		
		$form = new Form();
		$formData = $form->data;
		$this->ProcessFormAction($formData);
		
		// $dataSort = $this->GetSortData();
		
		$data = new stdClass();
		// $limit = $this->GetPagingCode($data, $recordsCount);
		//$data->rows = $this->GetViewList($dataSearch, 'order_index', $data);
		$data->rows = '';
		//$data->rowsCount = count($data->rows);
		$data->categoryId = (int)$this->GetVar('id', 0);
		//$data->eventsBlock = $this->GetBlockPath('events_block');
				
		return $data;
	}
	
	/*function GetViewList(&$dataSearch, $orderBy, &$data)
	{
		$eventsMap = ProductCategoriesMap::GetInstance();
		$eventsMap->MapCategories();
		// $limit = $this->GetPagingCode($data, $recordsCount);
		// return $this->eventsModel->GetRecordsList($dataSearch, $orderBy, $limit);
		$rows = $eventsMap->GetTreeList($dataSearch->parentId);
		if ($rows != null) {
			//echo'<pre>';print_r($rows);echo'</pre>';die;
			$this->FormatRows($rows);
			//echo'<pre>';print_r($rows);echo'</pre>';die;
		}
		return $rows;
	}
	*/
	function GetSearchData()
	{
		$objSearchFormObjects = new SearchFormObjects();
		$dataSearch = $objSearchFormObjects->SetQueryItems('txtSearch', 'search');
		$dataSearch->parentId = (int)$this->GetVar('id', 0);
		$objSearchFormObjects->SetQueryData($dataSearch);
		return $dataSearch;
	}
	
	function GetSortData()
	{
		$sortGrid = $this->LoadClass('../lib/admin/sort_grid_admin.php', 'SortGridAdmin', array('../lib/grid/sort_grid.php'));
		$dataSort = $sortGrid->AddSort($_GET, 'events', 'name', 'sc');
		return $dataSort;
	}
	
	
	function GetPagingCode(&$data, $recordsCount)
	{
		$objPaging = new Paging();
		$objPaging->allowedQueries = array('search');
		
		$itemsPerPage = ($objPaging->ddlItemsPageSelectedValue != '')?$objPaging->ddlItemsPageSelectedValue:_ITEMS_PER_PAGE;
		$objPaging->SetPaging($recordsCount, $itemsPerPage);

		$data->PagingHtml = $objPaging->GetPagingCode($this->webpage->PageUrl);
		
		$data->rowIndex = ($objPaging->selectedPageIndex - 1) * $objPaging->itemsPerPage;
		
		return $objPaging->limit;
	}
	
	function FormatRows(&$rows)
	{
		$this->webpage->PageDefaultUrl = $this->webpage->PageUrl;
		foreach ($rows as &$row)
		{
			if ($row->DirectChildrenCount > 0) 
			{
				$row->DisplayName = '<a href="'.$this->webpage->PageDefaultUrl.'/parentId='.$row->id.'">'.$row->name.'</a>';
				$row->DisplayName .= ($row->DirectChildrenCount == 1) 
									? 
									'('.sprintf($this->trans['events.subcategory_count'], $row->DirectChildrenCount).')'
									:
									'('.sprintf($this->trans['events.subevents_count'], $row->DirectChildrenCount).')';
			}
			else{
				$row->DisplayName = $row->name;
				$row->DisplayName .= ($row->ArticlesCount == 1) 
								?
								' ('.sprintf($this->trans['events.item_count'], $row->ArticlesCount).')'
								: 
								' ('.sprintf($this->trans['events.items_count'], $row->ArticlesCount).')';
			}
		}
	}
	// ================= Categories Lists - END =================== //

	
	// ================= Category Edit - BEGIN =================== //
	
	function GetEditData($editId = 0)
	{
		array_push($this->webpage->StyleSheets,
			'toastr/toastr.min.css',
			'daterangepicker/daterangepicker.css'
		);
		array_push($this->webpage->ScriptsFooter,
            'lib/jquery/jquery-ui.min.js',

			'lib/validator/jquery.validate.min.js',
			'lib/wrappers/validator/validator.js',
			'lib/toastr/toastr.min.js',
            'lib/moment/moment.min.js',
			'lib/daterangepicker/daterangepicker.min.js',
			'lib/tinymce/tinymce.min.js',
			'lib/wrappers/tinymce/tinymce.js',
			_JS_APPLICATION_FOLDER.$this->module.'/events_edit.js'

			);
		
		parent::SetWebpageData('events_edit', 'events');
		
		$form = new Form('Save');
		$formData = $form->data;
		$formData->EditId = $editId;
		$this->ProcessFormAction($formData);
		
		$this->webpage->FormAttributes = 'enctype="multipart/form-data"';
		$this->webpage->FormHtml .= HtmlControls::GenerateHiddenField('sys_EditId', $editId); // add the hidden edit id
		
		if (!$this->eventsModel->GetFormData($formData->EditId, $formData)) {
			Session::SetFlashMessage($this->trans['events.item_not_exists'], 'warning', $this->webpage->PageReturnUrl);
		}
		
		//echo'<pre>';print_r($formData);echo'</pre>';die;
		$this->SetPageEditTitle($formData);
		
		$parentId = $this->GetVar('pid', 0);
		if ($parentId != 0)
			$formData->ddlParentId = $parentId;

		$data = $formData;
		//$data->eventsList = $this->GetCategoriesForDropDown($formData->EditId, $formData->ddlParentId);
		$data->txtFile = $this->GetImagePath($data->txtFile);
		
		return $data;
	}
	
	
	function SetPageEditTitle(&$formData)
	{
		if ($formData->EditId == 0)
			$this->webpage->PageHeadTitle = $this->trans['events.new_item'];
		else
		{
			$this->webpage->PageHeadTitle =  $this->trans['events.edit_item'].': '.$formData->txtName;
			$this->webpage->PageUrl .= '/id='.$formData->EditId;
			$this->webpage->PageReturnUrl .= '/id='.$formData->ddlParentId;

		}
	}
	
	function GetCategoriesForDropDown($editId, $parentId)
	{
		$eventsMap = ProductCategoriesMap::GetInstance();
		$eventsMap->MapCategories($this->trans['events.main_category']);
		
		$events = $eventsMap->GetCategoryTreeRecursive(0, $editId, true);
		if ($events != null)
		{
			foreach ($events as &$row)
			{
				if ($row->level != 0)
					$row->displayName = $row->Indent.'|--'.$row->name;
				else
					$row->displayName = $row->name;
			}
		}
		
		return HtmlControls::GenerateDropDownList($events, 'id', 'displayName', $parentId);
	}
	
	function SaveCategory(&$formData)
	{
		$urlKey = $this->eventsModel->GetSafeValue($formData->txtUrlKey);
		$entryExists = $this->eventsModel->GetRecordExists('url_key', $urlKey, $formData->EditId);
		if ($entryExists)
		{
			$this->webpage->SetMessage($this->trans['events.error_url_key_exists'], 'error');
			return;
		}
		
		$editId = $this->eventsModel->SaveRecord($formData);
		if ($editId != 0)
		{
			// $this->eventsModel->DeleteDiskFile('../cache/mainmenu.tmp'); // force refresh menu
			$fileName = StringUtils::UrlTitle(strtolower($formData->txtName));
			$fileName .= '_'.$editId;
			$uploadInfo = $this->UploadFile('fileUpload', $fileName);
			if ($uploadInfo['status'])	{
				if ($uploadInfo['update_filename']) {
					$this->eventsModel->UpdateFileName($editId, $uploadInfo['file_name']);
				}
				$flashMessage = $this->trans[$this->translationPrefix.'.save_success'];
				$flashStatus = 'success';
			}
			else {
				$flashMessage = $uploadInfo['upload_message'];
				$flashStatus = 'warning';
			}
			Session::SetFlashMessage($flashMessage, $flashStatus, $this->webpage->PageUrl.'/'.$editId);
		}
		else {
			$this->webpage->SetMessage($this->trans['general.save_error'], 'error');
		}
	}
	// ================= Category Edit - END =================== //

	function ProcessFormAction(&$formData)
	{
		switch($formData->Action)
		{
			case 'Delete':
			//echo'<pre>';print_r($formData);echo'</pre>';die;
				$id = (int)$formData->Params;
				$this->eventsModel->DeleteRecord($id);
				$this->productsCategoriesModel->DeleteCategoryProducts($id);
				$this->eventsModel->DeleteDiskFile('../cache/mainmenu.tmp'); // force refresh menu
				Session::SetFlashMessage($this->trans['events.delete_success'], 'success', $this->webpage->PageUrl);
			break;
			case 'DeleteSelected':
			//echo'<pre>';print_r($formData);echo'</pre>';die;
				$selectedRecords = $this->GetSelectedRecords();
				if ($selectedRecords != '')
				{
					$this->eventsModel->DeleteSelectedRecords($selectedRecords);
					$this->productsCategoriesModel->DeleteCategoryProducts($selectedRecords);
					$this->eventsModel->DeleteDiskFile('../cache/mainmenu.tmp'); // force refresh menu
					Session::SetFlashMessage($this->trans['events.delete_selected_success'], 'success', $this->webpage->PageUrl);
				}
				else $this->webpage->SetMessage($this->trans['events.error_selected_elements'], 'error');
			break;
			case 'Save': $this->SaveCategory($formData); break;
			case 'DeleteFile':
				$filePath = _SITE_RELATIVE_URL._CATEGORIES_PATH;
				$this->eventsModel->DeleteFile($formData->EditId, $filePath);
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
