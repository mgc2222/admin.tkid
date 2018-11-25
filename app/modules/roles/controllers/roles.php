<?php
class Roles extends AdminController
{
	private $rolesModel;
	private $ctlLog;
	
	function __construct()
	{
		parent::__construct();
		$this->module = 'roles';
		$this->pageId = $this->module;
		$this->translationPrefix = $this->module;
		
		$this->Auth();
		$this->rolesModel = $this->LoadModel('roles');
	}
	
	function GetViewData1()
	{		
		$dataSearch = $this->GetQueryItems($query, array('search'));
		
		// page initializations
		array_push($this->webpage->ScriptsFooter, 'lib/gridsort/grid_sort.js');
		parent::SetWebpageData($this->pageId);
		//$this->webpage->SearchBlock = $this->GetGeneralBlockPath('search_block');
		
		// if search
		$this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'actionSearch', '1', array('txtSearch'), array('search'));
		
		$this->IncludeClasses(array('system/lib/grid/sort_grid.php'));
		$sortGridAdmin = $this->LoadUserLibrary('sort_grid', 'sort_grid_admin');
		$dataSort = $sortGridAdmin->AddSort($this->queryList, $this->pageId, 'name', 'sc');
	
		$form = new Form();
		
		$formData = $form->data;
		$this->ProcessFormAction($formData);
				
		$data = new stdClass();
		$data->rows = $this->rolesModel->GetRecordsList($dataSearch, $dataSort->{$this->pageId}->Sql);
		$data->dataSort = $dataSort;
		
		$this->webpage->AppendQueryParams($this->webpage->PageUrl);
				
		return $data;
	}
	
	function GetViewData($query = '')
	{
		$dataSearch = $this->GetQueryItems($query, array('search'));
		
		// page initializations
		array_push($this->webpage->ScriptsFooter, 'lib/gridsort/grid_sort.js');
		parent::SetWebpageData($this->pageId);
		//$this->webpage->SearchBlock = $this->GetGeneralBlockPath('search_block');
		
		// if search
		$this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'actionSearch', '1', array('txtSearch'), array('search'));
		
		$this->IncludeClasses(array('system/lib/grid/sort_grid.php'));
		$sortGridAdmin = $this->LoadUserLibrary('sort_grid', 'sort_grid_admin');
		$dataSort = $sortGridAdmin->AddSort($this->queryList, $this->pageId, 'name', 'sc');
	
		$form = new Form();
		
		$formData = $form->data;
		$this->ProcessFormAction($formData);
				
		$data = new stdClass();
		$data->rows = $this->rolesModel->GetRecordsList($dataSearch, $dataSort->{$this->pageId}->Sql);
		$data->dataSort = $dataSort;
		
		$this->webpage->PageUrl = $this->webpage->AppendQueryParams($this->webpage->PageUrl);
		
		// print_r($data);
		// die();
				
		return $data;
	}

	function GetEditData($editId = 0)
	{
		// page initializations
		array_push($this->webpage->ScriptsFooter, 'lib/validator/jquery.validate.min.js', 'lib/wrappers/validator/validator.js',_JS_APPLICATION_FOLDER.$this->module.'/role_edit.js');
		parent::SetWebpageData('role_edit', $this->pageId);

		$form = new Form('Save'); // get data from post
		$formData = $form->data;
		$formData->EditId = $editId;
		
		$this->ProcessFormAction($formData);
		
		$this->webpage->FormHtml .= HtmlControls::GenerateHiddenField('sys_EditId', $formData->EditId); // add the hidden edit id
		
		// $this->webpage->SetMessage('test messages', 'success');
		
		if (!$form->IsPostback())
			if (!$this->rolesModel->GetFormData($formData->EditId, $formData))
				Session::SetFlashMessage($this->trans[$this->translationPrefix.'.item_not_exists'], 'warning', $this->webpage->PageReturnUrl);
			
		if ($formData->EditId == 0)
			$this->webpage->PageHeadTitle = $this->trans[$this->translationPrefix.'.new_item'];
		else
		{
			$this->webpage->PageHeadTitle = $this->trans[$this->translationPrefix.'.edit_item'];
		}
			
		$data = $formData;

		return $data;
	}
	
	function ProcessFormAction(&$formData)
	{
		switch($formData->Action)
		{
			case 'Save':
				$roleId = $this->rolesModel->SaveRecord($formData, true);
				if ($roleId != 0)
				{
					$action = ($formData->EditId == 0)?LogAction::$AddNew:LogAction::$Edit;
					$this->AddLog($action, $formData, $roleId);
					Session::SetFlashMessage($this->trans[$this->translationPrefix.'.save_success'], 'success', $this->webpage->PageUrl.'/'.$roleId);
				}
				else
					$this->webpage->SetMessage($this->trans['general.save_error'], 'error');
			break;
			case 'Delete':
				$id = (int)$formData->Params;
				$this->rolesModel->DeleteRecord($id);
				$action = LogAction::$Delete;
				$this->AddLog($action, $formData, $id);
				Session::SetFlashMessage($this->trans[$this->translationPrefix.'.delete_success'], 'success', $this->webpage->PageUrl);
			break;
			case 'DeleteSelected':
				$selectedRecords = $this->GetSelectedRecords();
				if ($selectedRecords != '')
				{
					$this->rolesModel->DeleteSelectedRecords($selectedRecords);
					$action = LogAction::$DeleteMultiple;
					$this->AddLog($action, $formData, $id, $selectedRecords);
					Session::SetFlashMessage($this->trans[$this->translationPrefix.'.delete_selected_success'], 'success', $this->webpage->PageUrl);
				}
				else $this->webpage->SetMessage($this->trans[$this->translationPrefix.'.error_selected_elements'], 'error');
			break;
			case 'SortColumn':
				$this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'sys_Action', 'SortColumn', array('hidSortColumn_'.$this->pageId), array('sc'));
			break;
		}
	}
	
	function AddLog($action, &$formData, $targetId, $params = '')
	{
		$formData->targetId = $targetId;
		$formData->userId = $this->auth->UserId;
		$formData->propertyId = $this->auth->PropertyId;
		$formData->params = $params;
		
		$this->ctlLog->AddRolesLog($action, $formData);
	}
}
?>
