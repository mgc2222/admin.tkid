<?php 
class Menu
{	
	var $MenuItems;
	var $MenuContent;
	
	var $SelectedMenuItem;

	function __construct()
	{
		$this->SelectedMenuItem = '';
	}
	
	function LoadMenu($menuType = 'admin', &$trans)
	{
		switch ($menuType)
		{
			case 'admin': $this->SetAdminMenuVariables($trans); break;
			case 'tabs_property_facilities': $this->SetTabsPropertyFacilities($trans); break;
		}
	}

	function SetAdminMenuVariables(&$trans)
	{
		$this->MenuItems = array(
			array('id'=>'categories', 'name'=>$trans['menu.categories'], 'class'=>'', 'url'=>'categories','level'=>0,'icon'=>'fa-dashboard'),
			array('id'=>'products', 'name'=>$trans['menu.products'], 'class'=>'', 'url'=>'products','level'=>0,'icon'=>'fa-dashboard'),
			array('id'=>'app_images', 'name'=>$trans['menu.app_images'], 'class'=>'', 'url'=>'app_images','level'=>0,'icon'=>'fa-dashboard'),
			array('id'=>'users', 'name'=>$trans['menu.users'], 'class'=>'', 'url'=>'users','level'=>0,'icon'=>'fa-user'),
			array('id'=>'users_permissions', 'name'=>$trans['menu.users_permissions'], 'class'=>'', 'url'=>'users_permissions','level'=>0,'icon'=>'fa-folder-o'),
			array('id'=>'roles', 'name'=>$trans['menu.roles'], 'class'=>'', 'url'=>'roles','level'=>0,'icon'=>'fa-dashboard'),
			//array('id'=>'permissions', 'name'=>$trans['menu.permissions'], 'class'=>'', 'url'=>'permissions','level'=>0,'icon'=>'fa-folder-o'),
			//array('id'=>'products_images', 'name'=>$trans['menu.products_images'], 'class'=>'', 'url'=>'products_images','level'=>0,'icon'=>'fa-dashboard'),
			//array('id'=>'dashboard', 'name'=>$trans['menu.dashboard'], 'class'=>'', 'url'=>'dashboard','level'=>0,'icon'=>'fa-home'),
			//array('id'=>'app_categories', 'name'=>$trans['menu.app_categories'], 'class'=>'', 'url'=>'app_categories','level'=>0,'icon'=>'fa-folder-o'),
		);
			
		foreach ($this->MenuItems as &$row)
		{
			$row['icon'] .= ' fa fa-fw';
		}
	}
	
	
	function SetTabsPropertyFacilities(&$trans)
	{
		$this->MenuItems = array(
			array('id'=>'facilities_property', 'name'=>$trans['menu_tabs.property_facilities'], 'class'=>'', 'url'=>'property_facilities/property','level'=>0,'icon'=>''),
			array('id'=>'facilities_rooms', 'name'=>$trans['menu_tabs.rooms_facilities'], 'class'=>'', 'url'=>'property_facilities/tab=rooms','level'=>0,'icon'=>''),
		);
	}
		
	function SelectMenuByUrlKey($urlKey)
	{
		// $menuContentLower = strtolower($this->MenuContent);
		$urlKey = strtolower($urlKey);
		// replace class="..." with class="active"
		// $urlKey = str_replace('-', '\-', $urlKey);
		$this->MenuContent = preg_replace('/class="\{'.$urlKey.'\}([^"]*)"/', 'class="active $1"', $this->MenuContent);
		// replace rest of class="..." with ''
		$this->MenuContent = preg_replace('/class="\{[^}]+\}([^"]*)"/', 'class="$1"', $this->MenuContent);
	}
	
	function SelectMenu($menuId)
	{
		foreach ($this->MenuItems as &$row)
		{
			//echo'<pre>';print_r($row);echo'</pre>';
			if ($row['id'] == $menuId)
			{
				$row['class'] .= ' active';
				$this->SelectedMenuItem = $row;;
			}
		}
	}
	
	function RenderContent($userPermissions, $role = '')
	{
		$menuContent = '';
		foreach ($this->MenuItems as &$row)
		{
			if (isset($row['role']) && $row['role'] != $role) continue;
			if (!$this->CheckUserPermission($row['id'], $userPermissions)) continue;
			
			$liClass = ($row['class'] == '')?'':' class="'.$row['class'].'"';
			$linkUrl = _SITE_RELATIVE_URL.$row['url'];
			$menuContent .= '<li'.$liClass.'><a href="'.$linkUrl.'"><i class="'.$row['icon'].'"></i> '.$row['name'].'<span class="fa"></span></a></li>';
		}
		
		return $menuContent;
	}
	
	function RenderContentTabs()
	{
		$menuContent = '<ul class="nav nav-tabs">';
		foreach ($this->MenuItems as &$row)
		{
			$liClass = ($row['class'] == '')?'':' class="'.$row['class'].'"';
			$linkUrl = _SITE_RELATIVE_URL.$row['url'];
			$menuContent .= '<li'.$liClass.'><a href="'.$linkUrl.'">'.$row['name'].$row['icon'].'</a></li>';
		}
		$menuContent .= '</ul>';
		return $menuContent;
	}
	
	function CheckUserPermission($pageId, $userPermissions)
	{
		if ($userPermissions == null) return false;
		$ret = false;
		foreach ($userPermissions as &$row)
		{
			if ($pageId == $row->page_id)
			{
				$ret = true;
				break;
			}
		}
		return $ret;
	}
}
?>