<?php 
class ArrayUtils
{
	static function IsInObjectArray($arr, $searchKey, $searchValue)
	{
		$indexFound = -1;
		$itemIndex = 0;
		foreach ($arr as $arrItem)
		{
			if ($arrItem->{$searchKey} == $searchValue)
			{
				$indexFound = $itemIndex;
				break;
			}
			$itemIndex++;
		}
		
		return $indexFound;
	}
	
	static function JoinObjectArrayField($arr, $fieldKey, $glue = ',')
	{
		$retVal = '';
		foreach ($arr as $arrItem)
		{
			$retVal .= $arrItem->{$fieldKey}.$glue;
		}
		if ($retVal != '')
			$retVal = substr($retVal, 0, strlen($retVal) - strlen($glue));
		
		return $retVal;
	}
	
	static function ObjectArrayFieldToArray($arr, $fieldKey)
	{
		if ($arr == null) return null;
		$retVal = array();
		foreach ($arr as $arrItem)
		{
			array_push($retVal, $arrItem->{$fieldKey});
		}		
		return $retVal;
	}
	
	static function JoinIntegerArray($arr, $glue = ',')
	{
		$retVal = '';
		foreach ($arr as $arrVal)
		{
			$retVal .= (int)$arrVal.$glue;
		}
		if ($retVal != '')
			$retVal = substr($retVal, 0, strlen($retVal) - strlen($glue));
		
		return $retVal;
	}
	
	static function ConvertToIntegerArray(&$arr)
	{
		foreach ($arr as &$arrVal)
		{
			$arrVal = (int)$arrVal;
		}
	}
	
	function RemoveArrayValue(&$arrValues, $removeVal)
	{
		if ($arrValues != null && count($arrValues) > 0)
		{
			$arrCount = count($arrValues);
			for ($arrIndex = 0; $arrIndex < $arrCount; $arrIndex++)
			{
				if ($arrValues[$arrIndex] == $removeVal)
				{
					unset($arrValues[$arrIndex]);
				}
			}
		}
	}
	
	function ArraySortByKeys(&$array, $key, $sort_flags = SORT_REGULAR) 
	{
		if (is_array($array) && count($array) > 0) 
		{
			if (!empty($key)) 
			{
				$mapping = array();

				foreach ($array as $k => $v) 
				{
					$sort_key = '';
					if (!is_array($key)) 
					{
						$sort_key = $v[$key];
					} 
					else 
					{
						foreach ($key as $key_key) 
						{
							$sort_key .= $v[$key_key];
						}
						$sort_flags = SORT_STRING;
					}
					$mapping[$k] = $sort_key;
				}
				asort($mapping, $sort_flags);
				$sorted = array();
				foreach ($mapping as $k => $v) 
				{
					$sorted[] = $array[$k];
				}
				
				return $sorted;
			}
		}
		return $array;
	}
}
?>