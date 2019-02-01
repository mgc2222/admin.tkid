<?php
class SortGridAdmin extends SortGrid
{
	function __construct()
	{
		parent::__construct();
	}
	
	function GetSortArray($sortKey, $sortTable)
	{
		if ($sortKey == '') return null;

		$arrUsers = array('username'=>'username','oras'=>'o.name','role'=>'role_name','email'=>'email','firstname'=>'first_name','lastname'=>'last_name');
		$arrPropertyUsers = array('username'=>'username','hotel'=>'p.name','oras'=>'o.name','role'=>'role_name','email'=>'email','firstname'=>'first_name','lastname'=>'last_name');
		
		$arrRoles = array('name'=>'name', 'status'=>'status');
		$arrPermissions = array('name'=>'name', 'page_id'=>'page_id');
		$arrPropertyPermissions = array('name'=>'name', 'page_id'=>'page_id');
		$arrLanguages = array('name'=>'name', 'abbreviation'=>'abbreviation');
		$arrMessagesFolders = array('name'=>'fcl.name', 'css_class'=>'fc.css_class', 'order_index'=>'order_index');
		$arrMessages = array('date_sent'=>'m.date_sent');
		$arrProducts = array('name'=>'name');
		$arrCategories = array('name'=>'name');

		$arrCountries = array('name'=>'name','abbreviation'=>'abbreviation', 'status'=>'status');
		switch ($sortTable)
		{
			case 'users': $arr = $arrUsers; break;
			case 'roles': $arr = $arrRoles; break;
			case 'permissions': $arr = $arrPermissions; break;
			case 'languages': $arr = $arrLanguages; break;
			case 'messages_folders': $arr = $arrMessagesFolders; break;
			case 'messages': $arr = $arrMessages; break;
					
			case 'countries': $arr = $arrCountries; break;
			case 'products': $arr = $arrProducts; break;
			case 'categories': $arr = $arrCategories; break;
		}
		return $arr;
	}
	
	function GetSortColumnClass($sortKey, $sortTable)
	{
		$arr = $this->GetSortArray($sortKey, $sortTable);
		if ($arr == null) return '';
		$objSort = $this->GetSortObject($sortKey);		
		
		// foreach ($arr as $key=>$val)
		// {
			// $arr[$key] = '';
		// }
		
		if (isset($arr[$objSort->sortColumn]))
			$arr[$objSort->sortColumn] = 'selected';
		
		return $arr;
	}
	
	function GetSortColumnSql($sortKey, $sortTable)
	{
		$arr = $this->GetSortArray($sortKey, $sortTable);
		if ($arr == null) return '';
		
		$objSort = $this->GetSortObject($sortKey);
		if (isset($arr[$objSort->sortColumn]))
			$ret = sprintf($arr[$objSort->sortColumn], $objSort->sortDir).' '.$objSort->sortDir;
		else $ret = '';
		
		return $ret;
	}
}