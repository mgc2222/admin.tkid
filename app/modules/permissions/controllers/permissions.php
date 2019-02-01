<?php
class Permissions extends AdminController
{
	private $permissionsModel;
	
	function __construct()
	{
		parent::__construct();
		$this->module = 'permissions';
		$this->Auth();
		$this->permissionsModel = $this->LoadModel('permissions');
		
		$this->pageId = 'permissions';
		$this->translationPrefix = 'permissions';
	}
	
	function GetViewData($query = '')
	{		
		$dataSearch = $this->GetQueryItems($query, array('search'));

		// page initializations
		array_push($this->webpage->ScriptsFooter, 'lib/gridsort/grid_sort.js');
		parent::SetWebpageData($this->pageId);
		$this->webpage->SearchBlock = $this->GetGeneralBlockPath('search_block');
		
		// if search
		$this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'actionSearch', '1', array('txtSearch'), array('search'));
		
		$this->IncludeClasses(array('system/lib/grid/sort_grid.php'));
		$sortGridAdmin = $this->LoadUserLibrary('sort_grid', 'sort_grid_admin');
		$dataSort = $sortGridAdmin->AddSort($this->queryList, $this->pageId, 'name', 'sc');
	
		$form = new Form();
		
		$formData = $form->data;
		$this->ProcessFormAction($formData, $dataSearch);
		$dataSearch->languageId = $this->languageId;
				
		$this->webpage->PageHeadTitle = $this->trans[$this->translationPrefix.'.page_title'];

		$data = new stdClass();
		$data->rows = $this->permissionsModel->GetRecordsList($dataSearch, $dataSort->{$this->pageId}->Sql);
		$data->dataSort = $dataSort;
		
		$this->webpage->AppendQueryParams($this->webpage->PageUrl);
				
		return $data;
	}
	
	function GetEditData($editId = 0)
	{
		// page initializations
		array_push($this->webpage->ScriptsFooter, 'lib/validator/jquery.validate.min.js', 'lib/wrappers/validator/validator.js',_JS_APPLICATION_FOLDER.$this->module.'/permission_edit.js');
		parent::SetWebpageData('permission_edit', $this->pageId, $this->translationPrefix, $this->pageId.'/edit');

		$form = new Form('Save'); // get data from post
		$formData = $form->data;
		$formData->EditId = $editId;
		
		$dataSearch = null;
		$this->ProcessFormAction($formData, $dataSearch);		
		
		$this->webpage->FormHtml .= HtmlControls::GenerateHiddenField('sys_EditId', $formData->EditId); // add the hidden edit id
		
		if (!$form->IsPostback())
			if (!$this->permissionsModel->GetFormData($formData->EditId, $formData))
				Session::SetFlashMessage($this->trans[$this->translationPrefix.'.item_not_exists'], 'warning', $this->webpage->PageReturnUrl);
			
		if ($formData->EditId == 0)
			$this->webpage->PageHeadTitle = $this->trans[$this->translationPrefix.'.new_item'];
		else
		{
			$this->webpage->PageHeadTitle = $this->trans[$this->translationPrefix.'.edit_item'];
			$this->webpage->PageUrl .= '/'.$formData->EditId;
		}
			
		$data = $formData;

		return $data;
	}
	
	function ProcessFormAction(&$formData, &$dataSearch)
	{
		switch($formData->Action)
		{
			case 'Save':
				//echo'<pre>';print_r($formData);die();
				$permissionId = $this->permissionsModel->SaveRecord($formData, false);
				if ($permissionId != 0)
				{
					Session::SetFlashMessage($this->trans[$this->translationPrefix.'.save_success'], 'success', $this->webpage->PageUrl.'/'.$permissionId);
				}
				else
					$this->webpage->SetMessage($this->trans['general.save_error'], 'error');
			break;
			case 'Delete':
				$id = (int)$formData->Params;
				$this->permissionsModel->DeleteRecord($id);
				Session::SetFlashMessage($this->trans[$this->translationPrefix.'.delete_success'], 'success', $this->webpage->PageUrl);
			break;
			case 'DeleteSelected':
				$selectedRecords = $this->GetSelectedRecords();
				if ($selectedRecords != '')
				{
					$this->permissionsModel->DeleteSelectedRecords($selectedRecords);
					Session::SetFlashMessage($this->trans[$this->translationPrefix.'.delete_selected_success'], 'success', $this->webpage->PageUrl);
				}
				else $this->webpage->SetMessage($this->trans[$this->translationPrefix.'.error_selected_elements'], 'error');
			break;
			case 'SortColumn':
				$this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'sys_Action', 'SortColumn', array('hidSortColumn_'.$this->pageId), array('sc'));
			break;
		}
	}
}
?>
