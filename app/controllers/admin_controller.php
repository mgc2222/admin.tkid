<?php
class AdminController extends AbstractController
{
	var $module;
	var $auth;
	var $webpage;
	var $menu;
	var $trans;
	var $transJson;
	var $arrLoadedClasses;
	var $language; // user selected language
	var $languageId; // user selected language id
	var $defaultLanguage; // default language
	var $propertyInfo;
	var $languageModel;
	
	function __construct()
	{
		parent::__construct();
		// create image preloading js file
		// PreloadImages::WriteContent();
		//session_unset();
        $this->auth = new Auth();
        $this->webpage = new WebPage();
        $this->languageModel = $this->LoadModel('languages', 'languages');
        $dataSearch = new StdClass();
        //$dataSearch->languageId = '1,2'; // get only ro and en
        $this->defaultLanguage = $this->GetDefaultLanguage();
        $this->SetSelectedLanguage();
        $selectedLanguage = $this->GetSelectedLanguage();

        if ($selectedLanguage == null) die('No language exists');
        $this->language = $selectedLanguage;
        $this->languageId = $selectedLanguage->id;
        $this->webpage->language = $selectedLanguage;
        $this->webpage->languagesDdl = $this->languageModel->GetRecordsForDropdown($dataSearch, 'id');
        $this->LoadLang($selectedLanguage->abbreviation_iso);
        //echo'<pre>';print_r($selectedLanguage);die();
        $this->GetSessionMessage();
        $this->SetDefaultData();
        $this->GenerateJsCache();

	}
	
	function GetWebPageObject() { return $this->webpage; }
	function GetAuthObject() { return $this->auth; }
	function GetMenuObject() { return $this->menu; }
	function GetTranslation() { return $this->trans; }
	
	function Auth()
	{
		if (!$this->auth->AuthenticateUser())
		{
			header('location: '._SITE_RELATIVE_URL);
			exit();
		}
		
		$this->VerifyUserPermissions();
		$this->menu = new Menu();
		$this->menu->LoadMenu('admin', $this->trans);
	}
		
	function SetDefaultData()
	{
		$this->webpage->HeaderTitle = 'Panou de administrare';
		$this->webpage->PageLayout = _APPLICATION_FOLDER.'layouts/default_layout_form.php';
		
		$this->webpage->StyleSheets = Array(
			//'bootstrap/bootstrap.css',
            'bootstrap/bootstrap.min.css',
			'fonts/font-awesome/css/font-awesome.min.css',
			'admin/admin.css'
			);
		$this->webpage->StyleSheetsOutsideStyleFolder = Array();
		$this->webpage->ScriptsFooter = Array(
			'lib/strings/strings.js',
			'lib/htmlcontrols/htmlcontrols.js',
			'lib/form/form.js',
			//'lib/jquery/jquery-1.7.1.min.js',
			'lib/jquery/jquery-3.3.1.min.js',
			'lib/bootstrap/bootstrap.min.js',
			'lib/lodash/lodash.min.js');
		$this->AutoLoadJavascript();
	}
	
	function GenerateJsCache()
	{
		$ctlJsCache = $this->LoadController('js_cache', 'cache');
		// $ctlJsCache->CreatePropertiesScriptForQuickSearch('js/cache/properties_qs.js');
	}
	
	// set web page properties (menu, css and javascript files etc. ) according to current page name
	// pageId : id of the page 
	// parentId : parent of the page, used for selecting the menu, in case the selected menu does not have a correspondent to the current page
	// fileName : fileName, in case that pageId is different than the file name
	function SetWebpageData($pageId, $parentId = '', $translationPrefix = '', $fileName = '')
	{
		$isEditPage = strpos($pageId, '_edit') > 0;
			
		if ($fileName == '') {
			$fileName = $isEditPage ? $parentId.'/edit' : $pageId;
		}
		
		if ($parentId == '')
			$parentId = $pageId;
		
		if ($translationPrefix == '')
			$translationPrefix = $parentId;
		
		// page initializations
		$this->webpage->PageId = $pageId;
		$this->webpage->PageUrl = _SITE_RELATIVE_URL.$fileName;
		$this->webpage->PageReturnUrl = _SITE_RELATIVE_URL.$parentId;
		$this->webpage->PageTitle = $this->trans[$translationPrefix.'.page_title'];
		$this->webpage->PageHeadTitle = $this->webpage->PageTitle;
		
		if (strpos($pageId, '_edit') > 0)
			$this->webpage->ContentInclude = Array(_APPLICATION_FOLDER.'modules/'.$this->module.'/views/'.$pageId.'.php');
		else
			$this->webpage->ContentInclude = Array(_APPLICATION_FOLDER.'modules/'.$this->module.'/views/'.$pageId.'_view.php');

		if ($this->menu) 
		{
			$this->menu->SelectMenu($parentId); // set menu
		}
		
		$this->SetJsPageContent(); // set js page content
	}
	
	function AutoLoadJavascript()
	{
		// include auto load
		if (defined('_JS_AUTO_LOAD') && _JS_AUTO_LOAD != '')
		{
			$arrAutoLoad = explode(',', _JS_AUTO_LOAD);
			foreach ($arrAutoLoad as $autoLoadFile) 
			{
				array_push($this->webpage->ScriptsFooter, _JS_APPLICATION_FOLDER.$autoLoadFile);
			}
		}
	}
	
	function GetSessionMessage()
	{
		$msg = Session::GetFlashMessage();
		$this->webpage->SetMessage($msg->Message, $msg->MessageStatus, ($msg->Message != ''));
	}

	function GetSelectedRecords()
	{
		$ret = isset($_POST['multipleIds'])?implode(',', $_POST['multipleIds']):'';
		return $ret;
	}
	
	function VerifyUserPermissions()
	{
		// verify user ip
		if (!$this->CheckUserIP($this->auth->User->ip_address))
		{
			$this->auth->LogoutUser(false); // don't destroy session, to display the message
			Session::SetFlashMessage('Ip-ul este restrictionat!','error', _SITE_RELATIVE_URL);
		}
			
		$usersPermissionsModel = $this->LoadModel('users_permissions', 'users');
		// set user permissions list
		$this->auth->userPermissions = $usersPermissionsModel->GetUserPermissions($this->auth->UserId, $this->languageId);
		$scriptName = $_SERVER["SCRIPT_NAME"];
		$currentPage = substr($scriptName,strrpos($scriptName,"/")+1, strrpos($scriptName,".php") - strrpos($scriptName,"/") - 1);
		if ($currentPage == 'home') return; // don't verify for home page
        //echo'<pre>';print_r($usersPermissionsModel);die();
		$hasAccess = $usersPermissionsModel->CheckUserPermissions($this->auth->UserId, $currentPage);
		
		if (!$hasAccess)
			Session::SetFlashMessage('Nu aveti acces la pagina','error', 'home.php');
	}
	
	function LoadLang($langId)
	{
		(file_exists(_APPLICATION_FOLDER.'langs/'.$langId.'/pages.php'))
			?
			require_once(_APPLICATION_FOLDER.'langs/'.$langId.'/pages.php')
			:
			require_once(_APPLICATION_FOLDER.'langs/'.$this->defaultLanguage->abbreviation_iso.'/pages.php');

        $this->trans = $trans;

        if(!file_exists(_APPLICATION_FOLDER.'langs/'.$langId.'/pages.json')){
            mkdir(_APPLICATION_FOLDER.'langs/'.$langId, 0777);
            fclose(fopen(_APPLICATION_FOLDER.'langs/'.$langId.'/pages.json', 'a'));
		}
        $this->transJson = json_decode(file_get_contents(_APPLICATION_FOLDER.'langs/'.$langId.'/pages.json'),true, JSON_UNESCAPED_UNICODE);
	}
	
	function GetSelectedLanguage()
	{
		$lang = null;
		if (isset($_SESSION['language_id']))
			$lang = $this->languageModel->GetRecordById($_SESSION['language_id']);
		
		if ($lang == null)
			$lang = $this->languageModel->GetDefaultLanguage();
			
		return $lang;
	}
	
	function GetDefaultLanguage()
	{
		$lang = $this->languageModel->GetDefaultLanguage();
			
		return $lang;
	}
	
	function SetSelectedLanguage()
	{
		if (isset($_GET['lang_id'])) {
            $_SESSION['language_id'] = (int)$_GET['lang_id'];
        }
        else if(isset($_POST['language'])){

            if($this->languageModel->GetRecordById((int)($_POST['language']))){
                $_SESSION['language_id'] = (int)$_POST['language'];
            }
            //echo'<pre>';print_r($_SESSION);die();
            $this->RedirectBack();
		}
	}
	
	function CheckUserIP($ip)
	{
		if ($ip == '*' || $ip == '') return true;
		
		$arrIP = explode(';', $ip);
        $valid = false;
		foreach($arrIP as $ipAddress)
		{
			if($_SERVER['REMOTE_ADDR'] == trim($ipAddress))
			{
				$valid = true;
				break;
			}
		}
		return $valid;
	}
	
	function LoadNotifications()
	{
		// $pushNotificationModel = $this->LoadModel ('push_notifications');
		// include_once('controllers/push_notifications_controller.php');
		
		// $this->ctlPushNotifications = new PushNotificationsController();
		// $this->ctlPushNotifications->GetNotifications($this->auth->UserId);
	}
		
	function GetVar($var, $defaultVal = '') { return (isset($_GET[$var]))?trim($_GET[$var]):$defaultVal; }
	function PostVar($var, $defaultVal = '') { return (isset($_POST[$var]))?trim($_POST[$var]):$defaultVal; }
	function ObjectVar(&$formData, $var, $defaultVal = '') { return (isset($formData->{$var}))?trim($formData->{$var}):$defaultVal; }
	
	function MinifyCssAndJs($fileName)
	{
		$classes = array('lib/files/cache_file.php', 'lib/minify/js_utils.php', 'lib/minify/Minify/Loader.php');
		$this->IncludeClasses($classes);
		
		$cssCachedFile = 'style/'.$fileName.'.min.css';
		$cssFiles = $this->webpage->StyleSheets;
		
		JSUtils::MinifyCssFiles($cssFiles, $cssCachedFile);		
		$this->webpage->StyleSheets = array($cssCachedFile);
		
		$jsCachedFile = 'cache/'.$fileName.'.min.js';
		foreach ($this->webpage->ScriptsFooter as $key=>$val)
		{
			if (strpos($this->webpage->ScriptsFooter[$key], 'http://') === false || strpos($this->webpage->ScriptsFooter[$key], 'https://') === false)
			{
				$this->webpage->ScriptsFooter[$key] = 'js/'.$val;
			}
		}
		JSUtils::MinifyJavascriptFiles($this->webpage->ScriptsFooter, 'js/'.$jsCachedFile);
		$this->webpage->ScriptsFooter = array($jsCachedFile);

	}
	
	function SetJsPageContent($js = '')
	{
		$endLine = "\r\n";
		$userIdJs = md5($this->auth->UserId);
		$this->webpage->JsPageContent = 
			"var phpPageId = '{$this->webpage->PageId}';".$endLine.
			'var SCRIPTS = "'.implode('|', $this->webpage->ScriptsFooter).'";'.$endLine.
			"var SITE_RELATIVE_URL = '"._SITE_RELATIVE_URL."';".$endLine.
			"var SITE_URL = '"._SITE_URL."';".$endLine.
			"var SCRIPTS_URL = '"._SITE_URL."js/';".$endLine.
			"var SCRIPTS_URL_REPLACE = '"._JS_OUTSIDE_JS_FOLDER."';".$endLine.
            "var languageAbb = '".$this->webpage->language->abbreviation."';".$endLine.
            "var languageAbbIso = '".$this->webpage->language->abbreviation_iso."';".$endLine.
			"var auth = { UserId: '{$userIdJs}' };".$endLine;
		
		$this->webpage->JsPageContent .= $js;
		
		if ($this->webpage->JsMessage != null)	
			$this->webpage->JsPageContent .= HtmlControls::JsAlert($this->webpage->JsMessage);
	}
	
	function CreateData($data, $fields)
	{
		$ret = array();
		foreach ($fields as $field)
		{
			$ret[$field] = ($data !=null && isset($data[$field]))?$data[$field]:'';
		}
		return $ret;
	}
	
	function CreateObject($data, $fields)
	{
		$ret = new stdClass();
		foreach ($fields as $field)
		{
			$ret->{$field} = ($data !=null && isset($data[$field]))?$data[$field]:'';
		}
		return $ret;
	}

    function RedirectBack(){
        $headers = getallheaders();
        //echo'<pre>';print_r($headers);die();
        $this->webpage->Redirect((isset($headers['Referer'])) ? $headers['Referer'] : '/');
    }
}
?>
