<?php  
	class AlphabeticList
	{
		var $queryKey;
		var $queryValue;
		var $queryString;
		var $activeLinkClass;
		var $defaultLinkClass;
		var $separatorChar;
		var $separatorCharClass;
		var $allowedQueries;
		var $allPages;
		var $WhereString;
		
		function AlphabeticList()
		{
			$this->activeLinkClass = 'alphalist_active_link';
			$this->defaultLinkClass = 'alphalist_default_link';
			$this->separatorCharClass = 'alphalist_separator_class';
			$this->separatorChar = '|';
			
			$this->allowedQueries = array('page','search');
			$this->allPagesText = 'All';
			$this->allPagesQuery = 'all';
			
			$this->queryKey = 'letter';
			
			$this->WhereString = '';

			$this->RefreshQueryValue();
		}
		
		function RefreshQueryValue()
		{
			$this->queryValue = $this->_getQueryValue();
			$this->queryString = ($this->queryValue)?$this->queryKey.'='.$this->queryValue:'';
		}
		
		function _getQueryValue()
		{
			return (isset($_GET[$this->queryKey]))?$_GET[$this->queryKey]:'';
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
			if (count($_GET) > 0)
			{
				foreach ($_GET as $key=>$value)
				{
					foreach ($this->allowedQueries as $validQuery)
					{
						if ($validQuery == $key)
							$queries .= "&amp;{$key}={$value}";
					}
				}
			}
			
			return $queries;
		}
			
		function GetList($pageURL, $selectLetter = true, $allowedLetters = null)
		{							
			$lettersList = "";
			$selectedLetter = $this->queryValue;
			$queries = ""; 
			//$queries = $this->_getAllowedQueries();

			// add leading
			if (strtolower($selectedLetter) == $this->allPagesQuery || ($selectedLetter == "" && $selectLetter))
				$lettersList .= '<span class="'.$this->activeLinkClass.'">'.$this->allPagesText.'</span>';
			else 
				$lettersList .= '<span class="'.$this->defaultLinkClass.'"><a href="'.$pageURL.'?'.$queries.'">'.$this->allPagesText.'</a></span>';
			
			$lettersList .= '<span class="'.$this->separatorCharClass.'">'.$this->separatorChar.'</span>';
				
			
			$lettersListLength = strlen($selectedLetter);
			
			for ($letterIndex = 65; $letterIndex < 91; $letterIndex++)
			{
				// display only allowed lettters, if specified
				if ($allowedLetters != null) 
					if (!in_array(chr($letterIndex), $allowedLetters))
						continue; 
						
				if ($lettersListLength == 1 && (ord($selectedLetter) == $letterIndex || ord($selectedLetter) - 32 == $letterIndex))
					$lettersList .= '<span class="'.$this->activeLinkClass.'">'.chr($letterIndex).'</span>';
				else 
					$lettersList .= '<span class="'.$this->defaultLinkClass.'"><a href="'.$pageURL.'?'.$this->queryKey.'='.chr($letterIndex).$queries.'">'.chr($letterIndex).'</a></span>';
				
				if ($letterIndex < 90)
					$lettersList .= '<span class="'.$this->separatorCharClass.'">'.$this->separatorChar.'</span>';
			}
			
			return $lettersList;
		}
	}	
?>
