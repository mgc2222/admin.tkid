<?php
class Users extends AdminController
{
	private $usersModel;
	function __construct()
	{
		parent::__construct();
		$this->module = 'users';
		$this->Auth();
		$this->usersModel = $this->LoadModel('users');
		
		$this->pageId = 'users';
		$this->translationPrefix = 'users';
	}
	
	function GetViewData($query = '')
	{		
		// definitions
		$dataSearch = $this->GetQueryItems($query, array('search', 'propertyId'));
		
		// page initializations
		array_push(
			$this->webpage->ScriptsFooter,
			//'cache/properties_qs.js',
			'lib/select2/select2.min.js',
			'lib/select2/select2-paging.js',
			'lib/gridsort/grid_sort.js',
			_JS_APPLICATION_FOLDER.$this->module.'/users.js'
		);
		array_unshift($this->webpage->StyleSheets, 'select2/select2.min.css');
		parent::SetWebpageData($this->pageId);
		//$this->webpage->SearchBlock = $this->GetBlockPath('search_users_block');
		
		// if search
		$this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'actionSearch', '1', array('txtSearch', 'ddlSideSearchHotel'), array('search', 'propertyId'));

		// set menu
		$this->menu->SelectMenu($this->webpage->PageId);
		$this->SetJsPageContent();
	
		$form = new Form();
		
		$formData = $form->data;
		$this->ProcessFormAction($formData);
		
		$this->IncludeClasses(array('system/lib/grid/sort_grid.php'));
		$sortGridAdmin = $this->LoadUserLibrary('sort_grid', 'sort_grid_admin');
		$dataSort = $sortGridAdmin->AddSort($this->queryList, $this->pageId, 'username', 'sc');
		
		$recordsCount = $this->usersModel->GetRecordsListCount($dataSearch);
		
		$data = new stdClass();
		$limit = $this->GetPagingCode($data, $recordsCount);
		$data->users = $this->usersModel->GetRecordsListMaster($dataSearch, $dataSort->{$this->pageId}->Sql, $limit);
		$data->dataSort = $dataSort;
		
		$this->webpage->PageUrl = $this->webpage->AppendQueryParams($this->webpage->PageUrl);
		
		return $data;
	}
	
	function GetPagingCode(&$data, $recordsCount)
	{
		$paging = $this->LoadLibrary('grid', 'paging');
		$paging->SetDefaultOptions($this->queryList);
		$paging->allowedQueries = array('search','sex','email','user','fname','sc');
		
		$itemsPerPage = ($paging->ddlItemsPageSelectedValue != '')?$paging->ddlItemsPageSelectedValue:_ITEMS_PER_PAGE;
		$paging->SetPaging($recordsCount, $itemsPerPage);

		$data->PagingHtml = $paging->GetPagingCode($this->webpage->PageUrl);
		
		$data->rowIndex = ($paging->selectedPageIndex - 1) * $paging->itemsPerPage;
		
		return $paging->limit;
	}

	function GetEditData($editId = 0)
	{
		// page initializations
		array_push(
			$this->webpage->ScriptsFooter,
			//'cache/properties_qs.js',
			'lib/select2/select2.js',
			'lib/select2/select2-paging.js',
			'lib/validator/jquery.validate.min.js',
			'lib/wrappers/validator/validator.js',
			_JS_APPLICATION_FOLDER.$this->module.'/user_edit.js'
		);
		// 'lib/wrappers/autocomplete/autocomplete.js'
		array_unshift($this->webpage->StyleSheets, 'select2/select2.min.css');
		parent::SetWebpageData('user_edit', $this->pageId);
		
		$this->webpage->FormAttributes = 'enctype="multipart/form-data"';

		$form = new Form('Save');
		$formData = $form->data;
		$formData->EditId = $editId;
		
		$this->ProcessFormAction($formData);
		
		$this->webpage->FormHtml .= HtmlControls::GenerateHiddenField('sys_EditId', $formData->EditId); // add the hidden edit id
		
		// $this->webpage->SetMessage('test messages', 'success');
		
		if (!$form->IsPostback())
			if (!$this->usersModel->GetFormData($formData->EditId, $formData))
				Session::SetFlashMessage('Utilizatorul editat nu exista', 'warning', $this->webpage->PageReturnUrl);
		
		$this->SetPageTitle($formData);
		
		$dataSearch = new stdClass();
		
		$data = $formData;
		
		$data->rolesList = $this->GetUserRoles($data->ddlRoleId);
		$this->GetPasswordClass($data);
		$data->masterUser = 1;
		
		return $data;
	}
	
	function SetPageTitle(&$formData)
	{
		if ($formData->EditId == 0)
			$this->webpage->PageHeadTitle = $this->trans[$this->translationPrefix.'.new_item'];
		else
		{
			$this->webpage->PageHeadTitle = $this->trans[$this->translationPrefix.'.edit_item'].': '.$formData->txtUsername;
		}
	}
	
	function GetUserRoles($roleId)
	{
		$dataSearch = new stdClass();
		$rolesModel = $this->LoadModel('roles', 'roles');
		$roles = $rolesModel->GetRecordsForDropDown($dataSearch);
		return HtmlControls::GenerateDropDownList($roles, 'id', 'name', $roleId);
	}
	
	function GetPasswordClass(&$data)
	{
		if ($data->EditId != '0' && $data->EditId != '')
		{
			$data->passwordCssClass = '';
			$data->passwordRepeatCssClass = '';
		}
		else
		{
			$data->passwordCssClass = 'required|alphaNumPlus|match[txtPasswordRepeat] validate';
			$data->passwordRepeatCssClass = 'required validate';
		}
	}
	
	function GetSettingsData()
	{
		// page initializations
		array_push($this->webpage->ScriptsFooter, 'lib/validator/jquery.validate.min.js', 'lib/wrappers/validator/validator.js', _JS_APPLICATION_FOLDER.$this->module.'/user_settings.js');
		parent::SetWebpageData('user_settings', $this->pageId);
		
		$form = new Form('SaveSettings');
		$formData = $form->data;

		$this->ProcessFormAction($formData);
		
		if ($formData->EditId != 0)
		
		$this->webpage->FormHtml .= HtmlControls::GenerateHiddenField('sys_EditId', $formData->EditId); // add the hidden edit id
		
		// $this->webpage->SetMessage('test messages', 'success');
		
		$this->usersModel->GetFormData($this->auth->UserId, $formData);

		$data = $formData;
		if ($formData->EditId != '0' && $formData->EditId != '')
		{
			$data->passwordCssClass = '';
			$data->passwordRepeatCssClass = '';
		}
		else
		{
			$data->passwordCssClass = 'required|alphaNumPlus|match[txtPasswordRepeat] validate';
			$data->passwordRepeatCssClass = 'required validate';
		}
		
		return $data;
	}
	
	function GetProfileData()
	{
		// page initializations
		parent::SetWebpageData('user_profile', $this->pageId);
		$this->webpage->FormAttributes = 'enctype="multipart/form-data"';
		

		$form = new Form('SaveProfile');
		$formData = $form->data;
		$formData->EditId = $this->auth->UserId;
		$this->ProcessFormAction($formData);
		
		
		$this->webpage->FormHtml .= HtmlControls::GenerateHiddenField('sys_EditId', $formData->EditId); // add the hidden edit id
		// $this->webpage->SetMessage('test messages', 'success');
		
		$this->usersModel->GetProfileFormData($this->auth->UserId, $formData);
		$data = $formData;
		
		return $data;
	}
	
	function ProcessFormAction(&$formData)
	{
		switch($formData->Action)
		{
			case 'Save':
				$userId = $this->usersModel->SaveRecord($formData, true);
				if ($userId != 0) {
					Session::SetFlashMessage($this->trans[$this->translationPrefix.'.save_success'], 'success', $this->webpage->PageUrl.'/'.$userId);
				}
				else {
					$this->webpage->SetMessage($this->trans['general.save_error'], 'error');
				}
			break;
			case 'SaveSettings':
				$this->SaveSettings($formData);
			break;
			case 'SaveProfile':
				$this->usersModel->UpdateProfile($formData->EditId, $formData);
				Session::SetFlashMessage($this->trans[$this->translationPrefix.'.save_success'], 'success', $this->webpage->PageUrl);
			break;
			case 'Impersonate':
				$this->ImpersonateUser((int)$formData->Params);
			break;
			case 'DeleteFile':
				$this->usersModel->DeleteFile($formData->EditId, $formData);
				$this->webpage->SetMessage($this->trans['general.image_deleted'], 'success');
			break;
			case 'Delete':
				$id = (int)$formData->Params;
				$this->usersModel->DeleteRecord($id);
				Session::SetFlashMessage($this->trans[$this->translationPrefix.'.delete_success'], 'success', $this->webpage->PageUrl);
			break;
			case 'DeleteSelected':
				$selectedRecords = $this->GetSelectedRecords();
				if ($selectedRecords != '')
				{
					$this->usersModel->DeleteSelectedRecords($selectedRecords);
					Session::SetFlashMessage($this->trans[$this->translationPrefix.'.delete_selected_success'], 'success', $this->webpage->PageUrl);
				}
				else $this->webpage->SetMessage($this->trans[$this->translationPrefix.'.error_selected_elements'], 'error');
			break;
			case 'SortColumn':
				$this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'sys_Action', 'SortColumn', array('hidSortColumn_'.$this->pageId), array('sc'), false);
			break;
		}
	}
	
	function SaveSettings(&$formData)
	{
		// TODO : save user first name and last name
		$email = $this->auth->User->email; // $formData->txtEmail;
		$userPassword = $formData->txtPassword;
		if ($formData->txtCurrentPassword == '' )
		{
			$this->webpage->SetMessage($this->trans[$this->translationPrefix.'.save_settings_success'], 'success');
			return;
		}
		if ($this->auth->Password == md5($formData->txtCurrentPassword._SALT_STRING))
		{
			$performUpdate = true;
			if ($email != $this->auth->Email)
			{
				if ($this->usersModel->GetRecordExists('Email', $email, $this->auth->UserId))
				{
					$performUpdate = false;
					$this->webpage->SetMessage($this->trans[$this->translationPrefix.'.error_email_in_use']);
				}
				else
					$this->usersModel->UpdateEmail($this->auth->UserId, $email);
			}
			
			if ($performUpdate)
			{
				$this->usersModel->UpdatePassword($this->auth->UserId, $userPassword);
				// login with the new user/ password
				$this->auth->LoginUser($email, $userPassword, 'admin');
				Session::SetFlashMessage($this->trans[$this->translationPrefix.'.save_settings_success'], 'success', $this->webpage->PageUrl);
			}
		}
		else
			$this->webpage->SetMessage('Parola actuala nu se potriveste');
	}
	
	function ImpersonateUser($id)
	{
		$this->auth->ForceLoginUser($id);
		$this->webpage->Redirect('dashboard');
	}
}
?>
