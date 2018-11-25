<?php
class JsCache extends AbstractController
{
	function __construct()
	{
		$classes = array('system/lib/files/cache_file.php');
		$this->IncludeClasses($classes);
	}
	
	function CreatePropertiesScriptForQuickSearch($fileName)
	{
		// get cached file
		$fileContent = CacheFile::ReadFile($fileName);
		if ($fileContent != '')
			return false;
		
		$propertiesModel = $this->LoadModel('properties', 'properties');
		$rows = $propertiesModel->GetPropertiesForQuickSearch();
		
		$properties = '';
		foreach ($rows as &$row)
		{
			$aditional = ucfirst($row->city_name);
			$properties .= ',{value:"'.$row->name.' '.$aditional.'",id:"'.$row->id.'"}';
		}
		// remove last char
		$properties =  substr($properties, 1);
		$ret = 'var propertyList = ['.$properties.'];';
		
		CacheFile::WriteFile($fileName, $ret); // cache the content
		return true;
	}
}
?>
