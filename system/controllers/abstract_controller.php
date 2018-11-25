<?php
class AbstractController
{
	var $arrLoadedClasses;
	var $arrIncludedClasses;
	var $relativePath;
	var $module;
	var $queryData;
	var $queryList;
	
	function __construct()
	{
		$this->arrLoadedClasses = array();
		$this->arrIncludedClasses = array();
		// $this->setDebug();
		$this->relativePath = dirname(__FILE__).'/../../';
	}
	
	function setDebug()
	{
		set_exception_handler(array("AbstractController", "handleException"));
		set_error_handler(array("AbstractController", "handleError"));
	}
	
	function handleError(Exception $e)
	{
		debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
	}
	
	function handleException(Exception $e)
	{
		debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
	}
	
	function LoadModel($fileName, $module = '')
	{
		if (!$module) {
			$module = ($this->module)?$this->module:$fileName;
		}
		
		$arrFileName = explode('_', $fileName);
		foreach ($arrFileName as &$fName) { $fName = ucwords($fName); }
		$className = implode('', $arrFileName).'Model';
		
		return $this->LoadClass($this->relativePath._APPLICATION_FOLDER.'modules/'.$module.'/models/'.$fileName.'_model.php', $className);
	}
	
	function LoadEntity($fileName, $module = '')
	{
		if (!$module)
			$module = $this->module;
		
		$arrFileName = explode('_', $fileName);
		foreach ($arrFileName as &$fName) { $fName = ucwords($fName); }
		
		return $this->IncludeClasses(array($this->relativePath._APPLICATION_FOLDER.'modules/'.$module.'/entities/'.$fileName.'_entity.php'));
	}
	
	// load a user library 
	// if className is not specified, then will compose the className from the file name, by removing the '_' and capitalizing the words)
	function LoadUserLibrary($filePath, $fileName, $className = null)
	{
		if ($className == null) {
			$arrFileName = explode('_', $fileName);
			foreach ($arrFileName as &$fName) { $fName = ucwords($fName); }
			$className = implode('', $arrFileName);
		}
		return $this->LoadClass($this->relativePath._APPLICATION_FOLDER.'lib/'.$filePath.'/'.$fileName.'.php', $className);
	}
	
	// load a system library 
	// if className is not specified, then will compose the className from the file name, by removing the '_' and capitalizing the words)
	function LoadLibrary($filePath, $fileName, $className = null)
	{
		if ($className == null) {
			$arrFileName = explode('_', $fileName);
			foreach ($arrFileName as &$fName) { $fName = ucwords($fName); }
			$className = implode('', $arrFileName);
		}
		return $this->LoadClass($this->relativePath.'system/lib/'.$filePath.'/'.$fileName.'.php', $className);
	}
		
	function LoadController($fileName, $module = '')
	{
		if (!$module)
			$module = $this->module;
		
		$arrFileName = explode('_', $fileName);
		foreach ($arrFileName as &$fName) { $fName = ucwords($fName); }
		$className = implode('', $arrFileName);
		
		return $this->LoadClass($this->relativePath._APPLICATION_FOLDER.'modules/'.$module.'/controllers/'.$fileName.'.php', $className);
	}
		
	function LoadClass($classRelativePath, $className, $dependencyClasses = null) 
	{
		$classes = array();

		array_push($classes, $classRelativePath);
		if ($dependencyClasses != null) // include first the dependencies then the class
			$classes = array_merge($dependencyClasses, $classes);
				
		$this->IncludeClasses($classes);
		
		if (isset($this->arrLoadedClasses[$className]))
			return $this->arrLoadedClasses[$className];

		if (class_exists($className))
		{
			$this->arrLoadedClasses[$className] = new $className;
			return $this->arrLoadedClasses[$className];
		}
		die('Cannot create new "'.$className.'" class - includes not found or class unavailable.');
	}
	
	function LoadEnum($fileName, $module = '')
	{
		if (!$module)
			$module = $this->module;
		
		$classPath = $this->relativePath._APPLICATION_FOLDER.'modules/'.$module.'/enums/'.$fileName.'_enums.php';
		$classes = array($classPath);
		$this->IncludeClasses($classes);
	}
	
	function IncludeClasses($classesPath)
	{
		if ($classesPath == null || count($classesPath) == 0)
			return;
		
		foreach ($classesPath as $classPath)
		{
			if (!isset($this->arrIncludedClasses[$classPath])) 
			{
				require_once($classPath);
				$this->arrIncludedClasses[$classPath] = $classPath;
			}
		}
	}
	
	function GetAjaxJson()
	{
		$inputJSON = file_get_contents('php://input');
		if (strlen($inputJSON) == 0) 
			return null;
		
		$input = json_decode($inputJSON, TRUE ); //convert JSON into array

		if ($input == null)
			return null;
		
		if (!isset($input['ajaxAction']))
			$input['ajaxAction'] = '';
		
		return $input;
	}
	
	function GetEditId()
	{
		return isset($_GET['id'])?(int)$_GET['id']:0;
	}
	
	function GetJsonEncodedJavascript(&$data)
	{
		$jsonData = json_encode($data, JSON_HEX_APOS);
		return '<script type="text/javascript">var dataJson = \''.$jsonData.'\'</script>';
	}
	
	function GetDefaultResponse($message, $statusId = 0)
	{
		$data = new stdClass();
		$data->status = ($statusId == 0)?'error':'success';
		$data->message = $message;
		return $data;
	}
	
	function WriteResponse($data)
	{
		echo json_encode($data, JSON_HEX_APOS);
	}
	
	function GetRelativePath($path)
	{
		$relativePath = _SITE_RELATIVE_URL.$path;
		return $relativePath;
	}
	
	function GetBlockPath($path)
	{
		$relativePath = $this->relativePath._APPLICATION_FOLDER.'modules/'.$this->module.'/blocks/'.$path.'.php';
		return $relativePath;
	}
	
	function GetEmailTemplatePath($path)
	{
		$relativePath = $this->relativePath._APPLICATION_FOLDER.'modules/'.$this->module.'/email_templates/'.$path.'.html';
		return $relativePath;
	}
	
	function GetEmailTemplateImagesPath()
	{
		$relativePath = _SITE_URL._APPLICATION_FOLDER.'modules/'.$this->module.'/email_templates/img';
		return $relativePath;
	}
	
	function GetModulePath($path)
	{
		$relativePath = $this->relativePath._APPLICATION_FOLDER.'modules/'.$this->module.'/'.$path.'.php';
		return $relativePath;
	}
	
	function GetModuleFilePath($path)
	{
		$relativePath = $this->relativePath._APPLICATION_FOLDER.'modules/'.$this->module.'/'.$path;
		return $relativePath;
	}
	
	function GetGeneralBlockPath($path)
	{
		$relativePath = $this->relativePath._APPLICATION_FOLDER.'/blocks/'.$path.'.php';
		return $relativePath;
	}
	
	// get the path for a file in "files" folder
	function GetFilesPath($fileName)
	{
		$relativePath = $this->relativePath.'files/'.$fileName;
		return $relativePath;
	}
	
	function ParseQuery($query)
	{
		$this->queryData = $query;
		$this->queryList = array();
		$queryListTemp = explode(';', $query);
		foreach ($queryListTemp as $queryItem) 
		{
			$keyValue = explode('=', $queryItem);
			if (count($keyValue) > 1)
			{
				$this->queryList[$keyValue[0]] = $keyValue[1];
			}
		}
		
		return $this->queryList;
	}
	
	function QueryItem($key, $defaultValue = '')
	{
		if ($this->queryList == null || count($this->queryList) == 0)
			return $defaultValue;
		if (isset($this->queryList[$key]))
			return $this->queryList[$key];
		else
			return $defaultValue;
	}
	
	function GetQueryItems($query, $arrItems) {
		$this->ParseQuery($query);
		$ret = new stdClass();
		foreach ($arrItems as $item) {
			$ret->{$item} = $this->QueryItem($item);
		}
		return $ret;
	}
	
	function GetBasePath() 
	{ 
		$basePath = dirname(__FILE__) . '/../../';
		return $basePath; 
	}
}
?>
