<?php
class Ajax extends AbstractController
{
	function __construct()
	{
		parent::__construct();
		$this->dbo = DBO::global_instance();
	}
	
	function HandleRequest()
	{
		$action = isset($_POST['ajax_action'])?$_POST['ajax_action']:'';
		if (!$action) $action = isset($_GET['ajax_action'])?$_GET['ajax_action']:'';
		switch ($action)
		{
			case 'verify_user': $this->VerifyUser(); break;
			case 'verify_property_user': $this->VerifyPropertyUser(); break;
			case 'verify_role': $this->VerifyRole(); break;
			case 'verify_permission': $this->VerifyPermission(); break;
			case 'verify_property_permission': $this->VerifyPropertyPermission(); break;
			case 'verify_facilitati_category': $this->VerifyFacilitatiCategory(); break;
			case 'verify_language': $this->VerifyLanguage(); break;
			case 'get_company_by_cui': $this->GetCompanyByCui(); break;
			case 'verify_user_password': $this->VerifyUserPassword(); break;
			case 'verify_property_user_password': $this->VerifyPropertyUserPassword(); break;
			case 'verify_country': $this->VerifyCountry(); break;
		}
		die();
	}
	
	function VerifyUser()
	{
		$email = $this->dbo->GetSafeValue($_POST['email']);
		$username = $this->dbo->GetSafeValue($_POST['username']);
		$editId = (int)$_POST['editId'];
		
		$usersModel = $this->LoadModel('users');
		
		$data = new stdClass();
		$data->userExists = ($usersModel->GetRecordExists('username', $username, $editId))?1:0;
		$data->emailExists = ($usersModel->GetRecordExists('email', $email, $editId))?1:0;
		
		echo json_encode($data);
	}
	
	function VerifyPropertyUser()
	{
		$email = $this->dbo->GetSafeValue($_POST['email']);
		$username = $this->dbo->GetSafeValue($_POST['username']);
		$editId = (int)$_POST['editId'];
		
		$usersModel = $this->LoadModel('property_users');
		
		$data = new stdClass();
		$data->userExists = ($usersModel->GetRecordExists('username', $username, $editId))?1:0;
		$data->emailExists = ($usersModel->GetRecordExists('email', $email, $editId))?1:0;
		
		echo json_encode($data);
	}
	
	function VerifyRole()
	{

		$name = $this->dbo->GetSafeValue($_POST['name']);
		$editId = (int)$_POST['editId'];
		
		$rolesModel = $this->LoadModel('roles');
		
		$data = new stdClass();
		$nameExists = ($rolesModel->GetRecordExists('name', $name, $editId))?'false':'true';
		echo $nameExists;
	}
	
	function VerifyPermission()
	{

		$pageId = $this->dbo->GetSafeValue($_POST['pageId']);
		$editId = (int)$_POST['editId'];
		
		$permissionsModel = $this->LoadModel('permissions');
		
		$recordExists = ($permissionsModel->GetRecordExists('page_id', $pageId, $editId))?1:0;
		echo $recordExists?'false':'true';
	}
	
	function VerifyPropertyPermission()
	{

		$pageId = $this->dbo->GetSafeValue($_POST['pageId']);
		$editId = (int)$_POST['editId'];
		
		$permissionsModel = $this->LoadModel('property_permissions');
		
		$recordExists = ($permissionsModel->GetRecordExists('page_id', $pageId, $editId))?1:0;
		echo $recordExists?'false':'true';
	}
	
	function VerifyFacilitatiCategory()
	{

		$name = $this->dbo->GetSafeValue($_POST['name']);
		$editId = (int)$_POST['editId'];
		
		$facilitatiCategoriesModel = $this->LoadModel('facilitati_categories');
		
		$recordExists = ($facilitatiCategoriesModel->GetRecordExists('name', $name, $editId))?1:0;
		echo $recordExists?'false':'true';
	}
	
	function VerifyLanguage()
	{

		$abbreviation = $this->dbo->GetSafeValue($_POST['abbreviation']);
		$editId = (int)$_POST['editId'];
		
		$qlevelsModel = $this->LoadModel('languages');
		
		$recordExists = ($qlevelsModel->GetRecordExists('abbreviation', $abbreviation, $editId))?1:0;
		echo $recordExists?'false':'true';
	}
	
	function GetCompanyByCui()
	{

		$cui = $this->dbo->GetSafeValue($_POST['cui']);
		
		$companiesModel = $this->LoadModel('companies');
		
		$row = $companiesModel->GetCompanyByCui($cui);
		$mapping = $companiesModel->GetMapping();
		$data = new stdClass();
		$data->row = $row;
		$data->mapping = $mapping;
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	function VerifyUserPassword()
	{
		$data = new stdClass();
		$data->passwordMatch = 0;
		
		$this->auth = new Auth();
		
		if (!$this->auth->AuthenticateUser())
		{
			echo json_encode($data);
			return;
		}
		
		$password = $this->dbo->GetSafeValue($_POST['password']);
		$editId = (int)$_POST['editId'];
		
		if ($editId != $this->auth->UserId) // if not same user
		{
			echo json_encode($data);
			return;
		}

		$usersModel = $this->LoadModel('users');
		$data->passwordMatch = ($usersModel->PasswordMatch($this->auth->UserId, $password))?1:0;
		
		echo json_encode($data);
	}
	
	function VerifyPropertyUserPassword()
	{
		$data = new stdClass();
		$data->passwordMatch = 0;
		
		$this->auth = new Auth();
		
		if (!$this->auth->AuthenticateUser())
		{
			echo json_encode($data);
			return;
		}
		
		$password = $this->dbo->GetSafeValue($_POST['password']);
		$editId = (int)$_POST['editId'];
		
		if ($editId != $this->auth->UserId) // if not same user
		{
			echo json_encode($data);
			return;
		}

		$usersModel = $this->LoadModel('property_users');
		$data->passwordMatch = ($usersModel->PasswordMatch($this->auth->UserId, $password))?1:0;
		
		echo json_encode($data);
	}
	
	function VerifyCountry()
	{
		$name = $this->dbo->GetSafeValue($_POST['name']);
		$editId = (int)$_POST['editId'];
		
		$countriesModel = $this->LoadModel('countries');
		
		$data = new stdClass();
		$nameExists = ($countriesModel->GetRecordExists('name', $name, $editId))?'false':'true';
		echo $nameExists;
	}
}
?>
