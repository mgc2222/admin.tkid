<?php 
class UsersModel extends AbstractModel
{	
	var $imgWidth = 400;
	var $imgHeight = 400;
	var $thumbSmallWidth = 112;
	var $thumbSmallHeight = 112;
	
	function __construct()
	{
		parent::__construct();
		$this->table = 'users';
		$this->primaryKey = 'id';
		$this->verifiedTableField = 'email';
		$this->verifiedFormField = 'txtEmail';
		$this->messageValueExists = 'Acest email exista deja in baza de date. Folositi alt email!';

	}
	
	function SetMapping()
	{
		$this->mapping = array('username'=>'txtUsername','role_id'=>'ddlRoleId','email'=>'txtEmail','password'=>'txtPassword','first_name'=>'txtFirstName','last_name'=>'txtLastName','date_added'=>'txtDateAdded','status'=>'chkStatus','ip_address'=>'txtIpAddress');
	}
	
	function BeforeSaveData(&$data, &$row)
	{
		if ($data->EditId == 0)
			$row->date_added = '[NOW()]';
		else
			unset($row->date_added);
		
		if (isset($data->txtPassword) && $data->txtPassword != '')
			$row->password = md5($data->txtPassword._SALT_STRING);
		else unset($row->password);
		
		$row->status = isset($data->chkStatus)?1:0;
	}
	
	function UpdatePassword($recordId, $newPassword)
	{
		$newPassword = $newPassword._SALT_STRING; // add salt
		$this->dbo->UpdateRow($this->table, array('password'=>"[md5('{$newPassword}')]"), array($this->primaryKey=>$recordId));
		$this->Message = 'Modificarea a fost efectuata';
	}
	
	function ChangePassword($recordId, $currentPassword, $newPassword)
	{
		$currentPassword = $currentPassword._SALT_STRING; // add salt
		$newPassword = $newPassword._SALT_STRING; // add salt
		$sql = "SELECT 1 FROM {$this->table} WHERE id = {$recordId} AND password=md5('{$currentPassword}')";
		$recExists = $this->dbo->GetFieldValue($sql);

		if ($recExists)
		{
			$this->dbo->UpdateRow($this->table, array('password'=>"[md5('{$newPassword}')]"), array($this->primaryKey=>$recordId));
			$this->Message = 'Parola a fost schimbata';
			return true;
		}
		else
		{
			$this->Message = 'Parola curenta nu corespunde';
			return false;
		}
	}
	
	function PasswordMatch($recordId, $password)
	{
		$currentPassword = $password._SALT_STRING; // add salt
		$sql = "SELECT 1 FROM {$this->table} WHERE id = {$recordId} AND password=md5('{$currentPassword}')";
		return $this->dbo->GetFieldValue($sql);
	}
	
	function UpdateEmail($recordId, $email)
	{
		$this->dbo->UpdateRow($this->table, array('email'=>$email), array($this->primaryKey=>$recordId));
	}
	
	function GetCondByDataSearch(&$dataSearch)
	{
		$cond = 'WHERE 1';
		if (isset($dataSearch->search) && $dataSearch->search != '')
			$cond .= " AND (u.username LIKE '%{$dataSearch->search}%' OR u.email LIKE '%{$dataSearch->search}%') ";

		if (isset($dataSearch->email) && $dataSearch->email != '')
			$cond .= " u.email LIKE '%{$dataSearch->email}%' ";
			
		if (isset($dataSearch->username) && $dataSearch->username != '')
			$cond .= " AND u.username LIKE '%{$dataSearch->username}%' ";
			
		if (isset($dataSearch->statusId))
			$cond .= " AND u.status = {$dataSearch->statusId} ";
		
		return $cond;
	}
	
	function GetRecordsListCount(&$dataSearch)
	{
		$cond = $this->GetCondByDataSearch($dataSearch);
		$sql = "SELECT COUNT(*) as rc FROM {$this->table} u {$cond}";
		return $this->dbo->GetFieldValue($sql);
	}
	
	function GetRecordsList($dataSearch, $order, $limit)
	{
		$cond = $this->GetCondByDataSearch($dataSearch);
		$limit = ($limit == null)?'': " LIMIT {$limit}";
		$order = ($order == null)?'': " ORDER BY {$order}";
		$sql = "SELECT u.id, u.username, u.role_id, email, first_name, last_name, date_added, last_login, ip_address, hr.name AS role_name
		FROM {$this->table} u 
		LEFT JOIN roles hr ON u.role_id = hr.id
		{$cond} {$order} {$limit}";
		
		return $this->dbo->GetRows($sql);
	}
	
	function GetRecordsListMaster($dataSearch, $order, $limit)
	{
		$cond = $this->GetCondByDataSearch($dataSearch);
		$limit = ($limit == null)?'': " LIMIT {$limit}";
		$order = ($order == null)?'': " ORDER BY {$order}";
		$sql = "SELECT u.id, u.username, u.role_id, email, first_name, last_name, u.date_added, last_login, 
			hr.name AS role_name
		FROM {$this->table} u 
		LEFT JOIN roles hr ON u.role_id = hr.id
		{$cond} {$order} {$limit}";
		
		return $this->dbo->GetRows($sql);
	}
	
	function GetRecordForEdit($recordId)
	{
		$sql = "SELECT u.id, u.username, u.role_id, u.email, u.first_name, u.last_name, u.status, u.ip_address, 
			hr.name AS role_name
		FROM {$this->table} u 
		LEFT JOIN roles hr ON u.role_id = hr.id
		WHERE u.{$this->primaryKey} = {$recordId} LIMIT 1
		";
		
		return $this->dbo->GetFirstRow($sql);
	}
	
	function GetRecordsForDropDown($dataSearch)
	{
		$cond = $this->GetCondByDataSearch($dataSearch);
		$sql = "SELECT id, username FROM {$this->table} u {$cond}";
		return $this->dbo->GetRows($sql);
	}
	
	function GetUserByEmail($email)
	{
		$email = $this->dbo->GetSafeValue($email, true);
		$sql = "SELECT id FROM {$this->table} WHERE email  = '{$email}'";
		$row = $this->dbo->GetFirstRow($sql);
		
		return $row;
	}
	
	function GetUserRole($userId)
	{
		$sql = "SELECT r.name FROM {$this->table} u 
			INNER JOIN roles r ON u.role_id = r.id
			WHERE u.{$this->primaryKey}  = '{$userId}'";
		return $this->dbo->GetFieldValue($sql);
	}
	
	function CreateAdminUser()
	{
		$userId = $this->dbo->SelectValue($this->table, 'id', array('email'=>'adi.uta@gmail.com'));
		
		$password = md5('admin'._SALT_STRING);
		if ($userId == null)
		{
			$roleId = $this->AddWebmasterRole();
			$this->dbo->InsertRow($this->table, array('username'=>'webmaster','email'=>'adi.uta@gmail.com','password'=>$password,'status'=>1, 'date_added'=>'[NOW()]', 'role'=>$roleId));
		}
		else
		{
			$this->UpdatePassword($userId, 'admin');
		}
	}

	function AddWebmasterRole()
	{
		$roleId = $this->dbo->SelectValue('roles', 'id', array('name'=>'webmaster'));
		if ($roleId == null)
			$roleId = $this->dbo->InsertRow($this->table, array('name'=>'webmaster','description'=>'Web Master','status'=>1));
		
		return $roleId;
	}
	

	function ExtendGetFormData(&$data, &$row)
	{
		$data->txtPassword = '';
		$data->txtPasswordRepeat = '';
		$data->chkStatus = ($data->chkStatus)? 'checked="checked"':'';
	}
	
	function ExtendGetFormDataEmpty(&$data)
	{
		$data->txtPassword = '';
		$data->txtPasswordRepeat = '';
		$data->chkStatus = 'checked="checked"';
	}
	
	function CompleteFormDataPost(&$data)
	{
		if (!isset($data->chkStatus))
			$data->chkStatus = '';
	}
	
	function UpdateFields($recordId, $data)
	{
		$this->dbo->UpdateRow($this->table, $data, array($this->primaryKey=>$recordId));
	}
}
?>