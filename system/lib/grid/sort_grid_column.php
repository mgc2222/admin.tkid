<?php  
class SortGridColumn
{		
	var $sortColumn;
	var $sortDir;
	var $sortLink;
	var $sortColumnKey;
	var $sortDirKey;
	var $ajaxParams;
	
	function __construct($defaultSortColumn = '', $defaultSortDir = '', $defaultSortColumnKey = 'scol', $defaultSortDirKey = 'sdir')
	{
		$this->sortColumnKey = $defaultSortColumnKey;
		$this->sortDirKey = $defaultSortDirKey;
		
		if (isset($_GET[$this->sortColumnKey]))
			$this->sortColumn = $_GET[$this->sortColumnKey];
		else $this->sortColumn = $defaultSortColumn;
		
		if (isset($_GET[$this->sortDirKey]))
			$this->sortDir = $_GET[$this->sortDirKey];
		else $this->sortDir = $defaultSortDir;
	}
	
	function SetBaseLink($baseLink) { $this->sortLink = $baseLink; }
	function SetAjaxParams($ajaxParams) { $this->ajaxParams = $ajaxParams; }
	
	function GetSortLink($column, $label, $baseLink = null)
	{
		$sortDirReversed = ($this->sortDir == 'ASC')?'DESC':'ASC';
		
		$query = "{$this->sortColumnKey}={$column}&amp;{$this->sortDirKey}={$sortDirReversed}";
		if ($baseLink != null)
			$link = $baseLink;
		else if ($this->sortLink != null)
			$link = $this->sortLink;
		else // get request uri
		{
			$link = $_SERVER['REQUEST_URI'];
			$slashPos = strrpos($link,'/');
			if ($slashPos > 0) $link = substr($link, $slashPos + 1);
		}
		
		// check if link contains "?"
		if (strrpos($link, '?') > 0) $query = '&amp;'.$query;
			else $query = '?'.$query;
			
		$link = $link.$query;
			
		$ret = '<a href="'.$link.'">'.$label.'</a>';
		
		return $ret;
	}
	
	function GetSortLinkAjax($column, $label, $functionName = 'ajaxSort', $params = '')
	{
		// $sortDirReversed = ($this->sortDir == 'ASC')?'DESC':'ASC';
		$queryParams = "'{$column}','{$this->sortDir}'";
		if ($params != '') $queryParams .= ','.$params;
		else if ($this->ajaxParams != '') $queryParams .= ','.$this->ajaxParams;
		
		$functionCall = $functionName.'('.$queryParams.');';
		$ret = '<a href="javascript:;" onclick="'.$functionCall.'">'.$label.'</a>';
		return $ret;
	}
	
	function GetSortObject($val, $separator = '|')
	{
		if (strpos($val, $separator) > 0)
		{
			$arrSort = explode($separator, $val);
			$sortColumn = $arrSort[0];
			$sortDir = $arrSort[1];
		}
		else
		{
			$sortColumn = $val;
			$sortDir = 'ASC';
		}
		
		$ret = new stdClass();
		$ret->sortColumn = $sortColumn;
		$ret->sortDir = $sortDir;
		return $ret;
	}
}	
?>
