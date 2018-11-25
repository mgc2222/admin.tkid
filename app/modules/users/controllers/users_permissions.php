<?php
class UsersPermissions extends AdminController
{
	var $usersPermissionsModel;
	function __construct()
	{
		parent::__construct();
		$this->module = 'users';
		$this->pageId = 'users_permissions';
		$this->translationPrefix = $this->pageId;
		
		$this->Auth();
		$this->usersPermissionsModel = $this->LoadModel('users_permissions');
	}
	
	function GetUsersPermissionsData($query = '')
	{		
		$searchForm = $this->LoadLibrary('form', 'search_form_objects');
		$dataSearch = $searchForm->SetQueryItems('txtSearch', 'search');
		$this->IncludeClasses(array('system/lib/utils/array_utils.php'));
		
		$this->ParseQuery($query);
		$selectedUser = (int)$this->QueryItem('user', 0);
		
		// page initializations
		array_push($this->webpage->ScriptsFooter, 'lib/gridsort/grid_sort.js', 'lib/wrappers/sortable/sortable_init.js', _JS_APPLICATION_FOLDER.$this->module.'/user_permissions.js');
		parent::SetWebpageData($this->pageId);
		
		$form = new Form('Save');
		
		$formData = $form->data;
		$formData->selectedUser = $selectedUser;
		$this->ProcessFormAction($formData);
				
		$this->webpage->PageHeadTitle = $this->trans[$this->translationPrefix.'.page_title'];
		
		$searchForm->SetQueryData($dataSearch);
		
		
		$data = new stdClass();
		$permissions = $this->usersPermissionsModel->GetUserPermissionsIds($selectedUser);
		if ($permissions != null) 
			$permissionIds = ArrayUtils::ObjectArrayFieldToArray($permissions, 'permission_id');
		else $permissionIds = '';
		
		// populate form
		$usersRows = $this->GetUsersRows();
		$data->usersListContent = HtmlControls::GenerateDropDownList($usersRows, 'id','username', $selectedUser);
		$permissions = $this->GetPermissionsList($selectedUser);
		
		$data->permissionsListContent = HtmlControls::GenerateCheckListFromArray($permissions, 'id', 'name', $permissionIds, 'chkPage', 'chk_page');
		$this->webpage->PageUrl = $this->webpage->AppendQueryParams($this->webpage->PageUrl);
				
		return $data;
	}
	
	function GetUsersRows()
	{
		$usersModel = $this->LoadModel('users');
		$dataSearch = new stdClass();
		$dataSearch->statusId = 1;
		return $usersModel->GetRecordsForDropdown($dataSearch);
	}
	
	function GetPermissionsList($userId)
	{
		$usersModel = $this->LoadModel('users');
		$userRole = $usersModel->GetUserRole($userId);
		
		$dataSearch = new stdClass();
		if ($this->auth->Role != 'webmaster' || $userRole != 'webmaster') // if current user is not webmaster, or if the selected user is not webmaster, get only the permissions which are not private
			$dataSearch->isPrivate = 0;
		$dataSearch->languageId = $this->languageId;
		$permissionsModel = $this->LoadModel('permissions', 'permissions');

		$permissions = $permissionsModel->GetRecordsList($dataSearch, 'name');
		return $permissions;
	}
	
	function ProcessFormAction(&$formData)
	{
		switch($formData->Action)
		{
			case 'Save': $this->SaveUserPermissions($formData->selectedUser); break;
			case 'ChangeUser':
				$this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'sys_Action', 'ChangeUser', array('ddlUser'), array('user'));
			break;
		}
	}
	
	function VerifyUserOwnership($userId, $propertyId)
	{
		$usersModel = $this->LoadModel('users');
		if (!$usersModel->VerifyUserByPropertyId($userId, $propertyId))
			Session::SetFlashMessage('Nu aveti acces la acest utilizator', 'error', $this->webpage->PageUrl);
	}
	
	function SaveUserPermissions($selectedUser)
	{
		$rows = $this->GetSaveRows($selectedUser);
		$permissionSaved = $this->usersPermissionsModel->SavePermissions($selectedUser, $rows);
		$this->webpage->PageUrl = $this->webpage->AppendQueryParams($this->webpage->PageUrl);
		if ($permissionSaved)
		{
			Session::SetFlashMessage($this->trans[$this->translationPrefix.'.save_success'], 'success', $this->webpage->PageUrl.'/'.$this->queryData);
		}
		else
			$this->webpage->SetMessage($this->trans['general.save_error'], 'error');
	}
	
	// returns an array of type : array(selectedUserId, permissionId)
	function GetSaveRows($selectedUser)
	{
		if (!isset($_POST['chkPage']))
			return null;
		
		$saveIds = $_POST['chkPage'];

		$rows = array();
		foreach ($saveIds as $permissionId)
		{
			$row = array($selectedUser, $permissionId);
			array_push($rows, $row);
		}
		return $rows;
	}
}
?>
