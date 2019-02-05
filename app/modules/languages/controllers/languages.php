<?php
class Languages extends AdminController
{
	var $languagesModel;
	function __construct()
	{
		parent::__construct();
		$this->module = 'languages';
		$this->Auth();
		$this->languagesModel = $this->LoadModel('languages');

		$this->pageId = 'languages';
		$this->translationPrefix = 'languages';
		
		$this->HandleAjaxRequest();
	}
	
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
			case 'save': 
				$saveId = $this->SaveData($data); 
				$message = ($saveId == 0)?$this->trans[$this->translationPrefix.'.item_exists_error']:$this->trans[$this->translationPrefix.'.save_success'];
				$response = $this->GetDefaultResponse($message, $saveId);
				$response->id = $saveId;
				$response->pageTitle = ($saveId == 0)?'':$this->trans[$this->translationPrefix.'.edit_item']. ': '.$data['name'];
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
		$dataSearch = $this->GetQueryItems($query, array('search'));
		
		array_push($this->webpage->StyleSheets, 'toastr/toastr.min.css');
		array_push($this->webpage->ScriptsFooter, 'lib/gridsort/grid_sort.js', 'lib/i18next/i18next.min.js');
		parent::SetWebpageData($this->pageId);
		$this->webpage->SearchBlock = $this->GetGeneralBlockPath('search_block');
		
		// $this->MinifyCssAndJs('languages');
		
		// if search
		$this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'actionSearch', '1', array('txtSearch'), array('search'));
		
		$this->IncludeClasses(array('system/lib/grid/sort_grid.php'));
		$sortGridAdmin = $this->LoadUserLibrary('sort_grid', 'sort_grid_admin');
		$dataSort = $sortGridAdmin->AddSort($this->queryList, $this->pageId, 'name', 'sc');
	
		$form = new Form();
		
		$formData = $form->data;
		$this->ProcessFormAction($formData);
		
		$data = new stdClass();
		$data->rows = $this->languagesModel->GetRecordsList($dataSearch, $dataSort->{$this->pageId}->Sql);
		$data->dataSort = $dataSort;
		
		$this->webpage->PageUrl = $this->webpage->AppendQueryParams($this->webpage->PageUrl);
				
		return $data;
	}

	function GetEditData($editId = 0)
	{
		array_push($this->webpage->StyleSheets, 'toastr/toastr.min.css');
		array_push($this->webpage->ScriptsFooter, 'lib/gridsort/grid_sort.js', 'lib/toastr/toastr.min.js', 'lib/i18next/i18next.min.js', 'lib/validator/jquery.validate.min.js', 'lib/wrappers/validator/validator.js', 'lib/wrappers/i18next/i18next.js','lib/knockout/knockout.min.js', _JS_APPLICATION_FOLDER.'models.js',_JS_APPLICATION_FOLDER.$this->module.'/language_edit_ko.js');
		$this->webpage->FormAttributes = 'enctype="multipart/form-data"';
		
		parent::SetWebpageData('language_edit', $this->pageId, $this->translationPrefix, $this->pageId.'/edit');
		// $this->MinifyCssAndJs('languages');
		$this->LoadEntity('language');
		
		$row = $this->languagesModel->GetDataForJson($editId);
		if ($row == null)
			Session::SetFlashMessage($this->trans[$this->translationPrefix.'.not_exists'], 'warning', $this->webpage->PageReturnUrl);
		
		if ($editId == 0)
		{
			$this->webpage->PageTitle = $this->trans[$this->translationPrefix.'.new_item'];
			$this->webpage->PageHeadTitle = $this->webpage->PageTitle;
		}
		else
		{
			$this->webpage->PageTitle = $this->trans[$this->translationPrefix.'.edit_item'];
			$this->webpage->PageHeadTitle = $this->webpage->PageTitle;
			$this->webpage->PageUrl .= '/'.$editId;
		}
		
		// $data = $formData;
		$data = new stdClass();
		$data->EditId = $editId;
		
		$data->modelJson = $this->GetJsonEncodedJavascript($row);

		return $data;
	}
	
	function GetEditDataNotKo()
	{
		$pageId = 'language_edit';
		array_push($this->webpage->StyleSheets, 'toastr/toastr.min.css');
		array_push($this->webpage->ScriptsFooter, 'lib/toastr/toastr.min.js', 'lib/i18next/i18next.min.js', 'lib/validator/jquery.validate.min.js', 'lib/wrappers/validator/validator.js', _JS_APPLICATION_FOLDER.'models.js',_JS_APPLICATION_FOLDER.$this->module.'/languages_ko.js');
		$this->webpage->FormAttributes = 'enctype="multipart/form-data"';
			
		// set menu
		$this->menu->SelectMenu($this->webpage->PageId);
		$this->SetJsPageContent();

		$form = new Form('Save'); // get data from post
		$formData = $form->data;
		$dataSearch = null;
		$this->ProcessFormAction($formData, $dataSearch);		
		
		$this->webpage->FormHtml .= HtmlControls::GenerateHiddenField('sys_EditId', $formData->EditId); // add the hidden edit id
		
		if (!$form->IsPostback())
			if (!$this->languagesModel->GetFormData($formData->EditId, $formData))
				Session::SetFlashMessage($this->trans[$this->translationPrefix.'.not_exists'], 'warning', $this->webpage->PageReturnUrl);
		
		if ($formData->EditId == 0)
		{
			$this->webpage->PageTitle = $this->trans[$this->translationPrefix.'.new_item'];
			$this->webpage->PageHeadTitle = $this->webpage->PageTitle;
		}
		else
		{
			$this->webpage->PageTitle = $this->trans[$this->translationPrefix.'.edit_item'];
			$this->webpage->PageHeadTitle = $this->webpage->PageTitle;
		}
			
		$data = $formData;
		print_r($data);

		return $data;
	}
	
	
	function SaveData($data)
	{
		$defaultLanguage = $data['default_language'];
		unset($data['default_language']);
		$saveId = $this->languagesModel->SaveData($data, true, false, true);
		
		if ($saveId && $defaultLanguage == 1)
		{
			$variablesModel = $this->LoadModel('variables');
			$variablesModel->SaveKey('default_language', $saveId);
		}
		return $saveId;
	}
	
	function ProcessFormAction(&$formData)
	{
		switch($formData->Action)
		{
			case 'Save':
				$itemId = $this->languagesModel->SaveRecord($formData, true);
				if ($itemId != 0)
				{
					if (isset($formData->chkDefaultLanguage))
					{
						$variablesModel = $this->LoadModel('variables');
						$variablesModel->SaveKey('default_language', $itemId);
					}
					Session::SetFlashMessage($this->trans[$this->translationPrefix.'.save_success'], 'success', $this->webpage->PageUrl.'/'.$itemId);
				}
				else
					$this->webpage->SetMessage($this->trans['general.save_error'], 'error');
			break;
			case 'Delete':
				$id = (int)$formData->Params;
				$this->languagesModel->DeleteRecord($id);
				Session::SetFlashMessage($this->trans[$this->translationPrefix.'.delete_success'], 'success', $this->webpage->PageUrl);
			break;
			case 'DeleteSelected':
				$selectedRecords = $this->GetSelectedRecords();
				if ($selectedRecords != '')
				{
					$this->languagesModel->DeleteSelectedRecords($selectedRecords);
					Session::SetFlashMessage($this->trans[$this->translationPrefix.'.delete_selected_success'], 'success', $this->webpage->PageUrl);
				}
				else $this->webpage->SetMessage('Nu ati ales nici un element', 'error');
			break;
			case 'SortColumn':
				$this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'sys_Action', 'SortColumn', array('hidSortColumn_'.$this->pageId), array('sc'));
			break;
		}
	}

    function SetLanguage()
    {
        if(isset($_POST['language'])){
            if($this->languageModel->GetRecordById((int)($_POST['language']))){
                $_SESSION['language_id'] = (int)$_POST['language'];
            }
        }
        $this->RedirectBack();
    }
}
?>
