<?php  
class Paging
{		
	var $queryList;
	var $queryKey; // name of the variable that will be used in $this->queryList
	var $queryValue;
	var $queryString;
	var $activeLinkClass; // css class for active link
	var $defaultLinkClass; // css class for default link
	var $prevLinkClass;  // css class for previous link
	var $nextLinkClass; // css class for next link
	var $separatorChar; // char that will be used as a separator
	var $separatorCharClass; // css class for separator
	var $spacesClass;
	var $allowedQueries; 
	var $allPages;
	
	// dropdown for selecting page index
	var $ddlPageSelectId;
	var $ddlPageSelectClass;
	var $ddlPageSelectAttributes;
	var $ddlPageSelectShow;
	var $ddlPageSelectDefaultText;
	var $ddlPageSelectSelectedValue;
	
	// dropdown for selecting items / page
	var $ddlItemsPageId;
	var $ddlItemsPageClass;
	var $ddlItemsPageAttributes;
	var $ddlItemsPageShow;
	var $ddlItemsPageValues;
	var $ddlItemsPageDefaultText;
	var $ddlItemsPageSelectedValue;
	
	var $limit;
	var $startLimit;
	
	var $nextLink; // text for next link
	var $prevLink; // text for prev link
	var $showPrevNext; // specifiy if display next and previous links
	
	var $pagesCount;
	var $totalItems;
	var $pageQuery;
	var $selectedPageIndex;
	var $visualPageIndex;
	var $itemsPerPage; // number of items / page
	var $pagesDisplayed; // number of pages that will be displayed 
	
	function __construct()
	{
		
	}
	
	function SetDefaultOptions($queryList)
	{
		$this->queryList = $queryList;
		$this->pagesDisplayed = 5;
		
		$this->activeLinkClass = 'paging_active_link';
		$this->defaultLinkClass = 'paging_default_link';
		$this->separatorCharClass = 'paging_separator_class';
		$this->separatorChar = '|';
		$this->spacesClass = 'paging_space';
		
		$this->allowedQueries = array('letter','search');
		$this->allPagesText = 'All';
		$this->allPagesQuery = 'all';
		
		$this->queryKey = 'page';
		
		$this->nextLink = 'next &raquo;';
		$this->prevLink = '&laquo; Prev';
		$this->showPrevNext = true;
		
		$this->ddlPageSelectId = 'ddlPageSelect';
		$this->ddlPageSelectClass = '';
		$this->ddlPageSelectAttributes = ''; // either set this with onclick, or bind it from js, using the ddlPageSelectId
		$this->ddlPageSelectShow = true;
		$this->ddlPageSelectDefaultText = 'Go to page';
		$this->ddlPageSelectGetKey = 'page';
		$this->ddlPageSelectSelectedValue = ($this->queryList && isset($this->queryList[$this->ddlPageSelectGetKey]))?(int)$this->queryList[$this->ddlPageSelectGetKey]:'';
		
		$this->ddlItemsPageId = 'ddlItemsPage';
		$this->ddlItemsPageClass = '';
		$this->ddlItemsPageAttributes = ''; // either set this with onclick, or bind it from js, using the ddlItemsPageId
		$this->ddlItemsPageShow = true;
		$this->ddlItemsPageValues = '10,25,50,100';
		$this->ddlItemsPageDefaultText = 'Rows / page';
		$this->ddlItemsPageGetKey = 'itemspage';
		$this->ddlItemsPageSelectedValue = ($this->queryList && isset($this->queryList[$this->ddlItemsPageGetKey]))?(int)$this->queryList[$this->ddlItemsPageGetKey]:'';
		
		$this->RefreshQueryValue();
	}
	
	function SetOptions(&$data)
	{
		if (isset($data->activeLinkClass)) $this->activeLinkClass = $data->activeLinkClass;
		if (isset($data->defaultLinkClass)) $this->defaultLinkClass = $data->defaultLinkClass;
		if (isset($data->separatorCharClass)) $this->separatorCharClass = $data->separatorCharClass;
		if (isset($data->separatorChar)) $this->separatorChar = $data->separatorChar;
		if (isset($data->showPrevNext)) $this->showPrevNext = $data->showPrevNext;
		if (isset($data->prevLinkClass)) $this->prevLinkClass = $data->prevLinkClass;
		if (isset($data->nextLinkClass)) $this->nextLinkClass = $data->nextLinkClass;
		if (isset($data->prevLink)) $this->prevLink = $data->prevLink;
		if (isset($data->nextLink)) $this->nextLink = $data->nextLink;
		
		if (isset($data->ddlPageSelectShow)) $this->ddlPageSelectShow = $data->ddlPageSelectShow;
		if (isset($data->ddlPageSelectClass)) $this->ddlPageSelectClass = $data->ddlPageSelectClass;
		if (isset($data->ddlPageSelectAttributes)) $this->ddlPageSelectAttributes = $data->ddlPageSelectAttributes;
		if (isset($data->ddlPageSelectId)) $this->ddlPageSelectId = $data->ddlPageSelectId;
		if (isset($data->ddlPageSelectGetKey)) $this->ddlPageSelectGetKey = $data->ddlPageSelectGetKey;
		
		$this->ddlPageSelectSelectedValue = isset($this->queryList[$this->ddlPageSelectGetKey])?(int)$this->queryList[$this->ddlPageSelectGetKey]:'';
		
		if (isset($data->ddlItemsPageShow)) $this->ddlItemsPageShow = $data->ddlItemsPageShow;
		if (isset($data->ddlItemsPageClass)) $this->ddlItemsPageClass = $data->ddlItemsPageClass;
		if (isset($data->ddlItemsPageAttributes)) $this->ddlItemsPageAttributes = $data->ddlItemsPageAttributes;
		if (isset($data->ddlItemsPageId)) $this->ddlItemsPageId = $data->ddlItemsPageId;
		if (isset($data->ddlItemsPageValues)) $this->ddlItemsPageValues = $data->ddlItemsPageValues;
		if (isset($data->ddlItemsPageGetKey)) $this->ddlItemsPageGetKey = $data->ddlItemsPageGetKey;
		
		$this->ddlItemsPageSelectedValue = isset($this->queryList[$this->ddlItemsPageGetKey])?(int)$this->queryList[$this->ddlItemsPageGetKey]:'';
		
	}
	
	function RefreshQueryValue()
	{
		$this->queryValue = $this->_getQueryValue();
		$this->queryString = ($this->queryValue)?$this->queryKey.'='.$this->queryValue:'';
	}
	
	function SetPaging($totalItems, $itemsPerPage)
	{
		$this->totalItems = $totalItems;
		$this->itemsPerPage = $itemsPerPage;
		
		$this->selectedPageIndex = ($this->queryValue)?$this->queryValue:1;
		$this->pagesCount = ceil($this->totalItems/$this->itemsPerPage);
		
		$this->_setPaging();
	}
	
	function SelectPageIndex($pageIndex)
	{
		$this->selectedPageIndex = $pageIndex;
		$this->_setPaging();
	}
	
	function _setPaging()
	{
		// if not a number
		if (!is_numeric($this->selectedPageIndex))
			$this->selectedPageIndex = 1;
			
		// if try to access a page greater that pagesCount, set the last page
		if ($this->selectedPageIndex >= $this->pagesCount)
			$this->selectedPageIndex = ($this->pagesCount > 1)?$this->pagesCount:1;
		
		$this->startLimit = ($this->selectedPageIndex - 1)* $this->itemsPerPage;
		$this->limit = $this->startLimit.','.$this->itemsPerPage;
	}
	
	function _getQueryValue()
	{
		return (isset($this->queryList[$this->queryKey]))?$this->queryList[$this->queryKey]:'';
	}
	
	function GetQueryValue()
	{
		return $this->queryValue;
	}
	
	// key and value
	function GetQuery()
	{
		return $this->queryString;
	}
	
	function _getAllowedQueries()
	{
		$queries = '';
		if (count($this->queryList) > 0)
		{
			foreach ($this->queryList as $key=>$value)
			{
				foreach ($this->allowedQueries as $validQuery)
				{
					if ($validQuery == $key)
						$queries .= ";{$key}={$value}";
				}
			}
		}
		
		return $queries;
	}
	
	function GetPagingCode($pageUrl)
	{							
		$pagingCode = '';
		
		if ( ($this->pagesCount < 2) || ($this->itemsPerPage == 0))
			return $pagingCode;

		$pagingCode .= $this->GetDdlPageSelectCode();
		$pagingCode .= $this->GetPrevNextCode('prev', $pageUrl);
		$pagingCode .= $this->GetPagesCode($pageUrl);		
		$pagingCode .= $this->GetPrevNextCode('next', $pageUrl);
		$pagingCode .= $this->GetDdlItemsPageCode();
		
		return $pagingCode;
	}
	
	function GetDdlItemsPageCode()
	{
		$code = '';
		if ($this->ddlItemsPageShow)
		{
			$arrValues = explode(',', $this->ddlItemsPageValues);
			$code = '<select id="'.$this->ddlItemsPageId.'" name="'.$this->ddlItemsPageId.'" class="'.$this->ddlItemsPageClass.'" '.$this->ddlItemsPageAttributes.'>';
			$code .= '<option value="">'.$this->ddlItemsPageDefaultText.'</option>';
			foreach ($arrValues as $val)
			{
				$attr = ($this->ddlItemsPageSelectedValue == $val)?'selected="selected"':'';
				$code .= '<option value="'.$val.'" '.$attr.'>'.$val.'</option>';
			}
			$code .= '</select>';
			
			return $code;
		}
	}
	
	function GetDdlPageSelectCode()
	{
		$code = '';
		if ($this->ddlPageSelectShow)
		{
			$code = '<select id="'.$this->ddlPageSelectId.'" name="'.$this->ddlPageSelectId.'" class="'.$this->ddlPageSelectClass.'" '.$this->ddlPageSelectAttributes.'>';
			$code .= '<option value="">'.$this->ddlPageSelectDefaultText.'</option>';
			for ($pageIndex = 1; $pageIndex <= $this->pagesCount; $pageIndex++)
			{
				$attr = ($this->ddlPageSelectSelectedValue == $pageIndex)?'selected="selected"':'';
				$code .= '<option value="'.$pageIndex.'" '.$attr.'>'.$pageIndex.'</option>';
			}
			$code .= '</select>';
		}
		
		return $code;
	}
	
	function GetPrevNextCode($type, $pageUrl)
	{
		$code = '';
		if ($this->showPrevNext)
		{
			$selectedPage = $this->selectedPageIndex;
			$queries = $this->_getAllowedQueries();
			$prefixChar = $this->GetPrefixChar($pageUrl);
			
			if ($type == 'prev' && $selectedPage > 1)
			{
				$prevPage = $selectedPage - 1;
				$code .= '<span class="'.$this->prevLinkClass.'"><a href="'.$pageUrl.$prefixChar.$this->queryKey.'='.$prevPage.$queries.'">'.$this->prevLink.'</a></span>';
			}
			else if ($type == 'next' && $selectedPage < $this->pagesCount)
			{
				$nextPage = $selectedPage + 1;
				$code .= '<span class="'.$this->nextLinkClass.'"><a href="'.$pageUrl.$prefixChar.$this->queryKey.'='.$nextPage.$queries.'">'.$this->nextLink.'</a></span>';
			}
		}
		
		return $code;
	}
	
	// paddingLeft: floor(pagesDisplayed / 2);
	// paddingRight: pagesDisplayed - $paddingLeft;
	// start page: pageSelected - paddingLeft
	// end page : pageSelected + paddingRight

	function GetPagesCode($pageUrl)
	{
		$code = '';
		$selectedPage = $this->selectedPageIndex;
		$queries = $this->_getAllowedQueries();
		$prefixChar = $this->GetPrefixChar($pageUrl);
		
		$paddingLeft = floor(($this->pagesDisplayed - 1) / 2);
		$paddingRight = $this->pagesDisplayed - $paddingLeft - 1;
				
		$startPage = ($selectedPage - $paddingLeft > 1)?$selectedPage - $paddingLeft:1;
		$endPage = ($selectedPage + $paddingRight  <= $this->pagesCount)?$selectedPage + $paddingRight:$this->pagesCount;
		
		// if total pages is less pages than total displayed pages + 2 ( 2 = first and last)
		if ($this->pagesDisplayed + 2 >= $this->pagesCount)
		{
			$startPage = 1;
			$endPage = $this->pagesCount;
		}
		else 
		{
			// if displayed range less than total pages to be displayed
			if ($endPage + 1 - $startPage < $this->pagesDisplayed)
			{
				if ($endPage + $paddingRight >= $this->pagesCount)
				{
					$paddingMissing = $selectedPage + $paddingRight - $this->pagesCount;
					$startPage = $selectedPage - $paddingLeft - $paddingMissing;
				}
				else if ($startPage <= $this->pagesDisplayed)
				{
					//$paddingMissing = $selectedPage + $paddingRight - $this->pagesCount;
					// $endPage = $selectedPage + $this->pagesDisplayed - $paddingRight;
					$endPage = $this->pagesDisplayed;
				}
			}
		}
	
		// display or not the first page
		if ($startPage - 1 >= 1)
		{
			$code .= '<span><a class="'.$this->defaultLinkClass.'" href="'.$pageUrl.$prefixChar.$this->queryKey.'=0'.$queries.'">1</a></span>';
			if ($startPage - 2 >= 1) 
				$code .= '<span class="'.$this->spacesClass.'"></span>';
		}
		
		for ($pageIndex = $startPage; $pageIndex <= $endPage; $pageIndex++)
		{
			if ($selectedPage == $pageIndex)
				$code .= '<span><a class="'.$this->activeLinkClass.'">'.$pageIndex.'</a></span>';
			else 
				$code .= '<span class="'.$this->defaultLinkClass.'"><a href="'.$pageUrl.$prefixChar.$this->queryKey.'='.$pageIndex.$queries.'">'.$pageIndex.'</a></span>';
		}
		
		// display or not the last page
		if ($endPage  < $this->pagesCount)
		{
			if ($endPage + 1 < $this->pagesCount)
				$code .= '<span class="'.$this->spacesClass.'"></span>';
			$code .= '<span class="'.$this->defaultLinkClass.'"><a href="'.$pageUrl.$prefixChar.$this->queryKey.'='.$this->pagesCount.$queries.'">'.$this->pagesCount.'</a></span>';
		}
		
		return $code;
	}
	
	function GetPrefixChar($url)
	{
		$lastChar = substr($url, -1);
		$prefixChar = ($lastChar == '/') ? ';':'/';
		return $prefixChar;
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
}	
?>
