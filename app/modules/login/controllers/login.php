<?php
class Login extends AdminController
{
	function __construct()
	{
		parent::__construct();
		$this->module = 'login';
	}
	
	function GetLoginData()
	{
		$pageId = 'login';
		array_push($this->webpage->ScriptsFooter,
			'lib/validator/jquery.validate.min.js',
			'lib/wrappers/validator/validator.js'
			//_JS_APPLICATION_FOLDER.$this->module.'/login.js'
		);
		parent::SetWebpageData($pageId);
		
		$this->webpage->PageLayout = _APPLICATION_FOLDER.'layouts/layout_login.php';
		$form = new Form('Login');
		if (!$form->IsPostback())
			$this->SetDefaultFormDataLogin($form->data);
		
		// if admin already logged in, redirect to first page
		if ($this->auth->AuthenticateUser())
		{
			$this->webpage->Redirect($this->GetRelativePath('app_images'));
		}
		
		$formData = $form->data;
		switch($formData->Action)
		{
			case 'Login':
				$this->auth->LoginUser($formData->txtUsername, $formData->txtPassword, 'admin');
				if ($this->auth->isAuthenticated)
				{
					if (!$this->CheckUserIP($this->auth->User->ip_address))
						$this->webpage->SetMessage($this->trans['login.ip_restricted'], 'error', false);
					else
						$this->webpage->Redirect($this->GetRelativePath('app_images'));
				}
				else 
					$this->webpage->SetMessage($this->auth->Message, 'error', false);
			break;
		}
		
		return $formData;
	}
	
	function Logout()
	{
		$this->auth->LogoutUser();
		header('location:'._SITE_RELATIVE_URL);
		exit();
	}

	
	function SetDefaultFormDataLogin(&$data)
	{
		$data->txtUsername = '';
	}
}
?>
