<?php
class WebPage
{
	var $DisplayMessage;
	var $Message;
	var $MessageType;
	var $MessageCss;
	
	var $JsMessage;
	var $JsMessageType;
	var $JsMessageCss;
	var $JsAction;
	var $JsPageContent;
	
	var $StyleSheets;
	var $ScriptsHeader;
	var $ScriptsFooter;
	var $PageIcon;
	
	var $PageId;
	var $PageName;
	var $PageUrl;
	var $PageReturnUrl;
	var $PageHeadTitle;
	
	var $FormAttributes;
	var $EnableUpload;
	var $FormHtml;
	// SEO
	var $NavigationPath;
	var $PageTitle;
	var $PageDescription;
	var $PageKeywords;
	
	var $SearchLabel;
	
	function __construct()
	{
		// messages
		$this->DisplayMessage = false;
		$this->Message = '';
		$this->MessageType = 'error';
		$this->MessageCss = 'error';
		$this->JsMessage = '';
		$this->JsMessageType = 'error';
		$this->JsMessageCss = 'error';
		$this->JsAction = '';
		
		// head section - syles, scripts, icon
		$this->StyleSheets = null;
		$this->ScriptsHeader  = null;
		$this->ScriptsFooter  = null;
		$this->PageIcon = null;
		
		// page
		$this->PageHeadTitle = '';
		
		$this->PageId = 0;
		$this->PageName = '';
		$this->PageUrl = '';
		$this->PageReturnUrl = '';
		
		// form
		$this->FormAttributes = '';
		$this->FormHtml = '<input type="hidden" name="sys_Action" id="sys_Action" value="" /><input type="hidden" name="sys_Params" id="sys_Params" value="" />';
		$this->EnableUpload = false;

		// seo
		$this->NavigationPath = '';	
		$this->PageTitle = '';
		$this->PageDescription = '';
		$this->PageKeywords = '';
		
		$this->SearchLabel = 'Cauta';
	}
	
	function SetMessage($message, $type = 'error', $display = true)
	{
		$this->Message = $message;
		$this->MessageType = $type;
		$this->DisplayMessage = $display;
		
		switch ($type)
		{
			case 'error': $this->MessageCss = 'error'; break;
			case 'warning': $this->MessageCss = 'warning'; break;
			case 'success': $this->MessageCss = 'success'; break;
			case 'info': $this->MessageCss = 'info'; break;
		}
	}
	
	function SetJsMessage($message, $type)
	{
		$this->JsMessage = $message;
		$this->JsMessageType = $type;
		
		switch ($type)
		{
			case 'error': $this->JsMessage = 'error'; break;
			case 'warning': $this->JsMessage = 'warning'; break;
			case 'success': $this->JsMessage = 'success'; break;
			case 'info': $this->JsMessage = 'info'; break;
		}
	}
	
	function Redirect($pageUrl)
	{
		header('location:'.$pageUrl);
		exit();
	}
	
	// triggerField: a field name or an array of fields
	// triggerFieldValues: a field name or a list of fields, separated by |   ... i.e: 'Sort|Move|Search'
	// arrPostFields: post fields to verify
	// arrGetFields: get fields to set
	// This function verify if a post action was made, and if so, will redirect the page to the equivalent page but placing the alias post keys as get query params
	function RedirectPostToGet($url, $triggerField, $triggerFieldValues, $arrPostFields, $arrGetFields, $encodePostFields = true)
	{
		$actionPerformed = false;
		if (is_array($triggerField))
		{
			foreach ($triggerField as $field)
			{
				if (isset($_POST[$field]))
				{
					$actionPerformed = true;
					$triggerField = $field; // set the trigger field
					break;
				}
			}
		}
		else
			$actionPerformed = isset($_POST[$triggerField]);
		
		if ($actionPerformed)
		{
			$arrValues = explode('|', $triggerFieldValues);
			if (!in_array($_POST[$triggerField], $arrValues)) return;
			
			$fieldIndex = 0;
			$qs = '';
			foreach ($arrPostFields as $fieldPost)
			{
				if (isset($_POST[$fieldPost]) && $_POST[$fieldPost] != '')
				{
					$postFieldValue = ($encodePostFields)?urlencode($_POST[$fieldPost]):$_POST[$fieldPost];
					$qs .= ';'.$arrGetFields[$fieldIndex].'='.$postFieldValue;
				}
				$fieldIndex++;
			}
			
			$queryList = $this->ParseQuery($_SERVER['REQUEST_URI']);
			
			// preserve existing query, if not exists in $arrGetFields or if it was set to null
			foreach ($queryList as $key=>$val)
			{
				if (!in_array($key, $arrGetFields))
					$qs .= ';'.$key.'='.$val;
			}

			if ($qs != '')
			{
				$qs = substr($qs, 1);
				if (strpos($url, '/') >0)
					$location = $url.$qs;
				else
					$location = $url.'/'.$qs;
			}
			else $location = $url;
			header('location:'.$location);
			exit();
		}
	}
	
	// $arrAddFields : optionally, an array of keys to add /update to query
	// $arrRemoveFields : optionally, an array of keys to remove from query
	function GetQueryParams($arrAddFields = null, $arrRemoveFields = null)
	{
		$qs = '';
		$arrAppendFields = array();
		$arrUpdateFields = array();
		
		$queryList = $this->ParseQuery($_SERVER['REQUEST_URI']);
		
		// sort the AddFields in 2 arrays: update and append
		if ($arrAddFields != null)
		{
			foreach ($arrAddFields as $key=>$val)
			{
				if (!array_key_exists($key, $queryList))
					$arrAppendFields[$key] = $val;
				else
					$arrUpdateFields[$key] = $val;
			}
		}
		
		if (count($arrAppendFields) == 0) $arrAppendFields = null;
		if (count($arrUpdateFields) == 0) $arrUpdateFields = null;
		
		
		
		foreach ($queryList as $key=>$val)
		{
			$fieldUpdated = false;
			if ($arrUpdateFields != null)
			{
				if (array_key_exists($key, $arrUpdateFields)) // if key found, updates the value
				{
					$qs .= ';'.$key.'='.$arrUpdateFields[$key];
					$fieldUpdated = true;
				}
			}
			
			if (!$fieldUpdated)
				if ($arrRemoveFields == null || ($arrRemoveFields != null && !in_array($key, $arrRemoveFields)))
					$qs .= ';'.$key.'='.$val;
		}
		
		if ($arrAppendFields != null)
		{
			foreach ($arrAppendFields as $key=>$val)
			{
				$qs .= ';'.$key.'='.$val;
			}
		}
		
		if ($qs != '')
			$qs = substr($qs, 1);

		return $qs;
	}
	
	// $arrAddFields : an array of fields to be added / updated to the query
	// $arrRemoveFields : an array of fields to be removed to the query
	function AppendQueryParams($url, $arrAddFields = null, $arrRemoveFields = null)
	{
		$qs = $this->GetQueryParams($arrAddFields, $arrRemoveFields);
		
		if ($qs != '')
		{
			$lastChar = substr($url, -1);
			if ($lastChar == '/') {
				$location = $url.$qs;
			}
			else if (strpos($url, '/') >0) {
				$location = $url.';'.$qs;
			}
			else {
				$location = $url.'/'.$qs;
			}
		}
		else $location = $url;
		
		return $location;
	}
	
	function ParseQuery($url)
	{
		$pos = strrpos($url, '/');
		if ($pos === false)
			return;
		
		$query = substr($url, $pos + 1);
		
		$queryList = array();
		$queryListTemp = explode(';', $query);
		foreach ($queryListTemp as $queryItem) 
		{
			$keyValue = explode('=', $queryItem);
			if (count($keyValue) > 1)
			{
				$queryList[$keyValue[0]] = $keyValue[1];
			}
		}
		
		return $queryList;
	}
}
?>