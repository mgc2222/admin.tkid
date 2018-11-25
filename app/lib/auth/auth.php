<?php 
class Auth
{	
	var $UserId;
	var $Password;
	var $Message;
	var $User;
	var $PropertyId;
	var $isAuthenticated;
	var $UserNameKey;
	var $UserPasswordKey;
	var $userTable;
	var $userPermissions;

	function __construct()
	{
		$this->UserNameKey = 'user_username';
		$this->UserPasswordKey = 'user_password';
		$this->isAuthenticated = false;
		$this->userTable = 'users';
		$this->userPermissions = null;
	}
	
	function IsAuth() 	{ return $this->isAuthenticated; }
	
	function AuthenticateUser()
	{
		$this->ClearVariables();
		
		if ( isset($_SESSION[$this->UserNameKey]) && $_SESSION[$this->UserNameKey] != '' && 
			 isset($_SESSION[$this->UserPasswordKey]) && $_SESSION[$this->UserPasswordKey]!= '' )
		{
			$row = $this->GetUser($_SESSION[$this->UserNameKey], $_SESSION[$this->UserPasswordKey]);

			if($row != null)
			{
				$this->isAuthenticated = true;
					
				$this->User = $row;
				$this->Email = $row->email;
				$this->Password = $row->password;
				$this->Role = $row->role_name;
				$this->RoleId = $row->role_id;
				$this->UserId = $row->id;
				$this->PropertyId = 0;
			}
			else
				$this->Message = "Utilizatorul sau parola gresita.";
		}
		else
		{
			$this->Message = "Utilizatorul nu este logat.";
		}
		
		return $this->isAuthenticated;
	}
	
	function LoginUser($email, $password)
	{
		$this->ClearVariables();
		
		$row = $this->GetUser($email, md5($password._SALT_STRING));
		
		if ($row != null)
		{
			$this->isAuthenticated = true;
			
			if ($this->isAuthenticated)
			{
				$_SESSION[$this->UserNameKey] = $row->email;
				$_SESSION[$this->UserPasswordKey] = $row->password;
				$this->User = $row;
			}
			else
				$this->Message = "Utilizatorul nu are drepturi de acces.";
			
		}
		else
			$this->Message = "Utilizatorul sau parola gresita";
	}
	
	function ForceLoginUser($userId)
	{
		$this->ClearVariables();
		$row = $this->GetUserById($userId);
		// print_r($row);
		
		$this->SetUserLogin($row);

		return $row;
	}
	
	function SetUserLogin(&$row)
	{
		if ($row != null)
		{
			$this->isAuthenticated = true;
			
			$_SESSION[$this->UserNameKey] = $row->email;
			$_SESSION[$this->UserPasswordKey] = $row->password;
			$this->User = $row;
		}
		else
			$this->Message = "Utilizatorul nu a fost gasit";
	}
	
	function ReloginUser($email, $passwordMd5)
	{
		$this->ClearVariables();
		
		$row = $this->GetUser($email, $passwordMd5);
		
		if ($row != null)
		{
			$this->isAuthenticated = true;
			
			if ($this->isAuthenticated)
			{
				$_SESSION[$this->UserNameKey] = $row->email;
				$_SESSION[$this->UserPasswordKey] = $row->password;
			}
			else
				$this->Message = "Utilizatorul nu are drepturi de acces.";
			
		}
		else
			$this->Message = "Utilizatorul sau parola gresita";
	}
	
	function LogoutUser($destroy = true)
	{
		if (isset($_SESSION[$this->UserNameKey]))
			unset($_SESSION[$this->UserNameKey]);
		
		if (isset($_SESSION[$this->UserPasswordKey]))
			unset($_SESSION[$this->UserPasswordKey]);

		if ($destroy)
			session_destroy();
	}
	
	function GetUser($email, $password)
	{
		$sql = "SELECT u.*, hr.name AS role_name FROM {$this->userTable} u
			LEFT JOIN roles hr ON u.role_id = hr.id 
			WHERE `email`='{$email}' AND password='{$password}' AND u.status=1";
		$dbo = DBO::global_instance();
		return $dbo->GetFirstRow($sql);
	}
	
	function GetUserById($userId)
	{
		$sql = "SELECT * FROM {$this->userTable} WHERE user_id={$userId} AND status=1 LIMIT 1";
		$dbo = DBO::global_instance();
		return $dbo->GetFirstRow($sql);
	}
	
	function SetUserLastLogin($userId)
	{
		$dbo = DBO::global_instance();
		$dbo->UpdateRow($this->userTable, array('last_login'=>'[NOW()]'), array('id'=>$userId));		
	}
	
	function ClearVariables()
	{
		$this->User = null;
		$this->Email = '';
		$this->Password = '';
		$this->Message = '';
		$this->isAuthenticated = false;
	}
}
?>