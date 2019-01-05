<?php
class Events extends AdminController
{
	var $eventsModel;
	function __construct()
	{
		parent::__construct();
		$this->module = 'events';
		$this->pageId = 'events';
		$this->translationPrefix = 'events';
        $this->verifiedTableField = 'id';
		$this->events = '';

		$this->Auth();
		$this->eventsModel = $this->LoadModel('events');

		$basePath = $this->GetBasePath();
	}

	// ================= Events - BEGIN =================== //
	
	function HandleAjaxRequest()
	{
		$data = $this->GetAjaxJson();

		if ($data == null)
			return;
        //print_r($data);die();
		$ajaxAction = $data['ajaxAction'];
		unset($data['ajaxAction']);

		$response = null;
		switch ($ajaxAction)
		{
			case 'VerifyEvents':
				$response = $this->VerifyEvents($data);
			break;
            case 'GetEvents':
                $res = $this->GetJsonData($data['ajaxDataFrom'], $data['ajaxDataTo']);
                $response = ($res) ?:$this->GetDefaultResponse($this->trans['events.no_events'], 1) ;
			break;
			case 'SaveEvent':
				$id = $this->eventsModel->SaveEvent($data['formValues']);
                $response = ($id) ? $this->GetDefaultResponse($this->trans['events.save_success'], 1) : $this->GetDefaultResponse($this->trans['events.save_error'], 0);
				//echo'<pre>';print_r($id);die();
			break;
            case 'DeleteEvents':
                //echo'<pre>';print_r($data);die();
                if(is_array($data['formValues']['id'])){
                	$ids = implode(',', $data['formValues']['id']);
                    $rowsAffected = $this->eventsModel->DeleteEventsById($ids);
				}
				else{
                    $rowsAffected  = $this->eventsModel->DeleteEventById($data['formValues']['id']);
				}
                $response = ($rowsAffected) ? $this->GetDefaultResponse($this->trans['events.delete_selected_success'], 1) : $this->GetDefaultResponse($this->trans['events.delete_selected_error'], 0);
			break;
            case 'DeleteEvent':
                //echo'<pre>';print_r($data);die();
                $rowsAffected  = $this->eventsModel->DeleteEventById($data['formValues']['id']);
                $response = ($rowsAffected) ? $this->GetDefaultResponse($this->trans['events.delete_success'], 1) : $this->GetDefaultResponse($this->trans['events.delete_error'], 0);
			break;
		}
        //print_r($response);die();
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
	
	function GetViewData($query = '')
	{
		$this->HandleAjaxRequest();
		// definitions
		$dataSearch = $this->GetQueryItems($query, array('search', 'parentId'));
		if (!$dataSearch->parentId) {
			$dataSearch->parentId = 0;
		}
				
		array_push($this->webpage->StyleSheets,
			//'jquery/jquery-ui.css',
			//'bootstrap/bootstrap3.3.7.min.css',
			'toastr/toastr.min.css',
            'bootstrapcalendar/css/calendar.css',
            'bootstrapcalendar/css/custom.css',
            'daterangepicker/daterangepicker.css',
            'select2/select2.min.css'
			);
		array_push($this->webpage->ScriptsFooter,
			//'lib/jquery/jquery-ui.min.js',
			'lib/toastr/toastr.min.js',
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

		//$data = $this->GetEvents();
		// $limit = $this->GetPagingCode($data, $recordsCount);

		//return $data;
	}

	function GetEvents($dataSearch=null, $orderby=null){
        $data = new stdClass();
        $data->rows = $this->eventsModel->GetRecordsList($dataSearch, $orderby);
        return $data;
	}

	function FormatEvents($rows){
        if ($rows == null) {
            return;
        }
        $ret = [];
        $ret['status'] = 'success';
        //print_r($events);die();
        foreach ($rows as $key => $row){
        	$ret['result'][$key]['id'] = $row->id;
        	$ret['result'][$key]['title'] = $row->title;
        	$ret['result'][$key]['class'] = $row->event_css_class;
        	$ret['result'][$key]['start'] = $row->event_start_unix_milliseconds;
        	$ret['result'][$key]['end'] = $row->event_end_unix_milliseconds;
        	$ret['result'][$key]['status'] = $row->status;
        	$ret['result'][$key]['description'] = $row->description;
        	$ret['result'][$key]['short_description'] = $row->short_description;
        	$ret['result'][$key]['event_type'] = $row->event_type;
        	$ret['result'][$key]['event_type_id'] = $row->event_type_id;
        	$ret['result'][$key]['event_external_id'] = $row->event_external_id;
		}
		return $ret;
	}

	function GetSearchData()
	{
		$objSearchFormObjects = new SearchFormObjects();
		$dataSearch = $objSearchFormObjects->SetQueryItems('txtSearch', 'search');
		$dataSearch->parentId = (int)$this->GetVar('id', 0);
		$objSearchFormObjects->SetQueryData($dataSearch);
		return $dataSearch;
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

	function ProcessFormAction(&$formData)
	{
		switch($formData->Action)
		{
			case 'Delete':
			//echo'<pre>';print_r($formData);echo'</pre>';die;
				$id = (int)$formData->Params;
				$this->eventsModel->DeleteRecord($id);
				$this->eventsModel->DeleteCategoryProducts($id);
				$this->eventsModel->DeleteDiskFile('../cache/mainmenu.tmp'); // force refresh menu
				Session::SetFlashMessage($this->trans['events.delete_success'], 'success', $this->webpage->PageUrl);
			break;
			case 'DeleteSelected':
			//echo'<pre>';print_r($formData);echo'</pre>';die;
				$selectedRecords = $this->GetSelectedRecords();
				if ($selectedRecords != '')
				{
					$this->eventsModel->DeleteSelectedRecords($selectedRecords);
					$this->eventsModel->DeleteCategoryProducts($selectedRecords);
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
    // ================= Events - END =================== //

    // ================= Calendar - BEGIN =================== //

    function GetJsonData($from, $to)
    {
		$dataSearch = new StdClass();
		$dataSearch->from = $from;
		$dataSearch->to = $to;

        $events =  $this->GetEvents($dataSearch, '');
        return $this->FormatEvents($events->rows);
        //$content = file_get_contents('bootstrap_calendar/events.json');
        //echo $content; die();

    }

    function GetMonthTemplate()
    {
        $calendarMonthData = file_get_contents('bootstrap_calendar/tmpls/month.html');
        //die($calendarMonthData);
        echo $calendarMonthData; die();
    }

    function GetDayTemplate()
    {
        $calendarDayData = file_get_contents('bootstrap_calendar/tmpls/day.html');
        //die($calendarDayData);
        echo $calendarDayData; die();
    }

    function GetModalTemplate()
    {
        $calendarModalData = file_get_contents('bootstrap_calendar/tmpls/modal.html');
        echo $calendarModalData; die();
    }
    function GetModalTitleTemplate()
    {
        $calendarModalData = file_get_contents('bootstrap_calendar/tmpls/modal-title.html');
        echo $calendarModalData; die();
    }

    function GetMonthDayTemplate()
    {
        $calendarMonthDayData = file_get_contents('bootstrap_calendar/tmpls/month-day.html');
        echo $calendarMonthDayData; die();
    }

    function GetWeekTemplate()
    {
        $calendarWeekData = file_get_contents('bootstrap_calendar/tmpls/week.html');
        echo $calendarWeekData; die();
    }

    function GetWeekDaysTemplate()
    {
        $calendarWeekDaysData = file_get_contents('bootstrap_calendar/tmpls/week-days.html');
        echo $calendarWeekDaysData; die();
    }

    function GetYearTemplate()
    {
        $calendarYearData = file_get_contents('bootstrap_calendar/tmpls/year.html');
        echo $calendarYearData; die();
    }

    function GetYearMonthTemplate()
    {
        $calendarYearMonthData = file_get_contents('bootstrap_calendar/tmpls/year-month.html');
        echo $calendarYearMonthData; die();
    }

    function GetEventsListTemplate()
    {
        $calendarEventsListData = file_get_contents('bootstrap_calendar/tmpls/events-list.html');
        echo $calendarEventsListData; die();
    }
}
?>
