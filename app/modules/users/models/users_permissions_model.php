<?php 
class UsersPermissionsModel extends AbstractModel
{	
	function __construct()
	{
		parent::__construct();
		$this->table = 'users_permissions';
		$this->primaryKey = 'id';
	}

	function SavePermissions($userId, $rows)
	{
		if ($userId == 0)
		{
			$this->Message = 'Selecteaza un utilizator';
			$this->MessageCss = 'error';
			return false;
		}
		
		$this->Message = 'Permisiunile au fost modificate';
		$this->MessageCss = 'success';

		// delete user permissions
		$this->dbo->DeleteRows($this->table, array('user_id'=>$userId));
		if ($rows != null)
			$this->dbo->InsertRowsBulk($this->table, array('user_id', 'permission_id'), $rows);

		return true;
	}
	
	function SavePermissionForUsers(&$page)
	{
		$this->Message = 'Settings saved for permission';
		$this->MessageCss = 'success';
		$arrSelectedValues = null;

		if (isset($page['chkPage']))
		{
			$selectedUserIds = $page['chkPage'];
			$arrSelectedValues = $page['chkPage'];
		}
		else $selectedUserIds = null;
		$pageId = $page['ddlPermissions'];
		
		$changedUserIds = $_POST['hidChangedValues'];
		
		if ($pageId != '')
		{
			$usersPermissions = $this->GetUsersPermissions($changedUserIds);
			foreach ($usersPermissions as &$userPerm) // for all changed users
			{
				$arrPermissions = explode(',', $userPerm->page_ids); // get user permission as array
				if ($selectedUserIds == null) // if no selected user 
					$addPermission = false;
				else if (!in_array($userPerm->user_id, $selectedUserIds)) // if current user not in the list of the selected users
					$addPermission = false;
				else $addPermission = true;
				
				$pos = array_search($pageId, $arrPermissions); // check if current permission is in the user permissions
				
				// the other cases except these 2 will imply that the permission stays the same
				if ($pos !== false && !$addPermission) // if permission exists and user was unchecked, remove the permission
					unset($arrPermissions[$pos]);
				else if ($pos === false && $addPermission) // if permission doesnt exists and user was checked, add the permission
					array_push($arrPermissions, $pageId);
					
				$selectedPermissions = implode(',', $arrPermissions);
				if ($userPerm->user_exists)
				{
					$this->dbo->UpdateRow('users_permissions', array('page_ids'=>$selectedPermissions), array('user_id'=>$userPerm->user_id));
				}
				else
				{
					$this->dbo->InsertRow('users_permissions', array('user_id'=>$userPerm->user_id, 'page_ids'=>$selectedPermissions));
				}
			}
		}
		else
		{
			$this->Message = 'Selecteaza un utilizator';
			$this->MessageCss = 'error';
		}
		return $arrSelectedValues;
	}
	
	function GetUserPermissions($userId, $langId)
	{
		if ($userId == 0) return null;
		
		$sql = "SELECT permission_id FROM {$this->table} WHERE user_id = {$userId}";
		$rows = $this->dbo->GetRows($sql);
		if ($rows == null) return null;
		
		$arrPermissionIds = array();
		foreach ($rows as &$row)
		{
			array_push($arrPermissionIds, $row->permission_id);
		}
		$permissionIds = implode(',', $arrPermissionIds);
		
		$sql = "SELECT name, page_id 
		FROM permissions
		WHERE id IN ({$permissionIds})";
		return $this->dbo->GetRows($sql);
	}
	
	function GetUserPermissionsIds($userId)
	{
		if ($userId == 0) return null;
		$sql = "SELECT permission_id FROM {$this->table} WHERE user_id = {$userId}";
		return $this->dbo->GetRows($sql);
	}
	
	function GetUserPermissionsForPage($userId, $pageId)
	{
		if ($userId == 0) return false;
		
		$sql = "SELECT id FROM permissions WHERE page_id = '{$pageId}' LIMIT 1";
		$row = $this->dbo->GetFirstRow($sql);
		if ($row == null) return true; // if page doesn't exists in the permissions list, allow it
				
		$sql = "SELECT 1 FROM {$this->table} WHERE user_id = {$userId} AND permission_id = {$row->id} LIMIT 1";
		$val = $this->dbo->GetFieldValue($sql);
		return ($val != null);
	}
	
	function CheckUserPermissions($userId, $pageId)
	{
		return $this->GetUserPermissionsForPage($userId, $pageId);
	}

	function GetUsersByPermission($permission)
	{
		$sql = "SELECT user_id FROM users_permissions WHERE page_ids LIKE '{$permission},%' OR page_ids LIKE '%,{$permission},%' OR page_ids LIKE '%,{$permission}' OR page_ids = '{$permission}'";
		
		$rows = $this->dbo->GetRows($sql,  $this->fileName.':GetUsersByPermission');
		
		if ($rows != null)
		{
			$userIds = ArrayUtils::JoinObjectArrayField($rows, 'user_id');
		
			$sql = 'SELECT username FROM users WHERE uid IN ('.$userIds.')';
			return $this->dbo->GetRows($sql,  $this->fileName.':GetUsersByPermission');
		}
		else
			return null;
	}
	
	function GetUsersIdsByPermission($permission)
	{
		$sql = "SELECT user_id FROM users_permissions WHERE page_ids LIKE '{$permission},%' OR page_ids LIKE '%,{$permission},%' OR page_ids LIKE '%,{$permission}' OR page_ids = '{$permission}'";
		
		$rows = $this->dbo->GetRows($sql,  $this->fileName.':GetUsersIdsByPermission');
		if ($rows != null)
		{
			$userIds = ArrayUtils::ObjectArrayFieldToArray($rows, 'user_id');
			return $userIds;
		}
		else
			return null;
	}

	function GetUsersPermissions($userIds)
	{
		$cond = ($userIds == '')?'':' WHERE user_id IN ('.$userIds.')';
		$sql = 'SELECT user_id, page_ids, 1 as user_exists FROM users_permissions '.$cond;
		$rows = $this->dbo->GetRows($sql);
		
		// if there are users which don't exists in the permissions table, add them with empty permissions
		$arrUserIds = explode(',', $userIds);
		foreach ($arrUserIds as $userId)
		{
			$userFound = false;
			foreach ($rows as &$row)
			{
				if ($row->user_id == $userId)
				{
					$userFound = true;
					break;
				}
			}
			if (!$userFound)
			{
				$userItem = new stdClass();
				$userItem->user_id = $userId;
				$userItem->page_ids = '';
				$userItem->user_exists = 0;
				array_push($rows, $userItem);
			}
		}
		return $rows;
	}
}
?>