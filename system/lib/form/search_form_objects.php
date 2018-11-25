<?php  
	class SearchFormObjects
	{		
		var $queryItems;
		function __construct()
		{
			
		}
		
		function SetQueryItems($postVars, $getVars)
		{
			$data = new stdClass();
			$arrGet = explode(';', $getVars);
			$this->queryItems = $arrGet;
			$value = '';
			foreach ($arrGet as $var)
			{
				if (isset($_GET[$var]))
					$data->{$var} = trim($_GET[$var]);
				else $data->{$var} = '';
			}
			
			$rowIndex = 0;
			$arrPost = explode(';', $postVars);
			foreach ($arrPost as $var)
			{
				if (isset($_POST[$var]))
				{
					$varGet = $arrGet[$rowIndex];
					$data->{$varGet} =  trim($_POST[$var]);
				}
				$rowIndex++;
			}
			
			return $data;
		}

		function SetQueryData($data)
		{
			foreach ($this->queryItems as $var)
			{
				if (isset($data->{$var}) && $data->{$var} != '' )
					$_GET[$var] = $data->{$var};
					
				$data->{$var} = trim($data->{$var});
			}
		}
	}	
?>
