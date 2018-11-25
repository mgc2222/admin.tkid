<?php 
class StringUtils
{	
	/**
	 * Create URL Title
	 *
	 * Takes a "title" string as input and creates a
	 * human-friendly URL string with either a dash
	 * or an underscore as the word separator.
	 *
	 * @access	public
	 * @param	string	the string
	 * @param	string	the separator: dash, or underscore
	 * @return	string
	 */
	static function UrlTitle($str, $separator = 'dash')
	{
		if ($separator == 'dash') { $search = '_'; $replace	= '-'; }
		else {$search = '-'; $replace	= '_'; }
			
		$trans = array($search => $replace, "\s+" => $replace, "[^a-z0-9".$replace."]" => '',
						$replace."+" => $replace, $replace."$" => '', "^".$replace => '');

		$str = strip_tags(strtolower($str));
		
		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#", $val, $str);
		}
		
		return trim(stripslashes($str));
	}
	
	// add a condition to the sql using the query key and field name
	static function AppendConditionFromQuery($initialCondition, $queryKey, $fieldName, $like = false)
	{
		$addAnd = ($initialCondition != "")?" AND ":"";
		if ($like)
			$resultCondition = (isset($_GET[$queryKey]) && $_GET[$queryKey] != "") ?"{$addAnd} {$fieldName} LIKE '%".StringUtils::getSafeGetVar($queryKey)."%'":"";
		else
			$resultCondition = (isset($_GET[$queryKey]) && $_GET[$queryKey] != "") ?"{$addAnd} {$fieldName}='".StringUtils::getSafeGetVar($queryKey)."'":"";
		return $resultCondition;
	}
	
	static function AppendCondition($initialCondition, $newCondition, $fieldName, $like = false)
	{
		$addAnd = ($initialCondition != "")?" AND ":"";
		if ($like)
			$resultCondition = (isset($newCondition) && $newCondition != "") ?"{$addAnd} {$fieldName} LIKE '%".StringUtils::getSafeVar($newCondition)."%'":"";
		else
			$resultCondition = (isset($newCondition) && $newCondition != "") ?"{$addAnd} {$fieldName}='".StringUtils::getSafeVar($newCondition)."'":"";
		return $resultCondition;
	}
		
	static function strtoupper_utf8($string)
	{
		$string=utf8_decode($string);
		$string=strtoupper($string);
		$string=utf8_encode($string);
		return $string;
	}
	
	static function BooleanString($val)
	{
		$ret = ($val)?'Da':'Nu';
		return $ret;
	}
	
	static function TruncateString($val, $maxLength, $addDots = true)
	{
		if (strlen($val) > $maxLength)
			return substr($val, 0, $maxLength).'...';
		else return $val;
	}
	
	static function TruncateHtml($val, $maxLength, $addDots = true)
	{
		$newVal = str_replace('||','<br/>', StringUtils::TruncateString(strip_tags(str_replace('<br />','||', $val)), $maxLength, $addDots));
		return $newVal;
	}
	
	static function RemoveLastChars($val, $charsCount = 1)
	{
		if ($val != null && strlen($val) > 0)
			return substr($val, 0, strlen($val) - $charsCount);
		else return $val;
	}
	
	// check for a list of given variables in the post; if any not found, return null, else return a row with those value
	static function getValidatedPostVars($arrName, $arrTypes)
	{
		$row = new stdClass();
		$varIndex = 0;
		
		foreach ($arrName as $varName)
		{
			$found = false;
			foreach ($_POST as $key=>$val)
			{
				if ($varName == $key)
				{
					$found = true;
					break;
				}
			}
			
			if ($found)
			{
				$row->{$varName} = strip_tags(trim($_POST[$varName]));
				switch ($arrTypes[$varIndex])
				{
					case 'int': $row->{$varName} = (int)$row->{$varName}; break;
					case 'float': $row->{$varName} = floatVal($row->{$varName}); break;
				}
				
				$varIndex++;
			}
			else // any not found? ... exit
			{
				// echo ' not found:' . $varName;
				$row = null;
				break;
			}
		}
		
		return $row;
	}
	
	static function ReplaceEndLineWithBR($val)
	{
		return str_replace("\r\n", '<br/>', $val);
	}

	static function prepareFileName($fileName)
	{
		$forbiddenChars = array(',','.','/','\\','?','!','@','#','$','%','^','&','(',')','[',']','{','}','|',"'",'"','*');
		$fileName = strtolower(trim($fileName));
		
		$strArr = array(); 
		$strArr = str_split($fileName);
		
		//daca filename este format din mai multe cuvinte care intre ele au mai mult de un spatiu  eliminam restul spatiilor
		// ex: videanu   adrian va fi videanu adrian care va deveni videanu-adrian la sfarsit
		$newFileName = "";
		$index = 0;
		$flag = 0;
		foreach ($strArr AS $s)
		{		
			if($s == " ")
			{
				$index++;
				if ($index == 2)
				{
					$flag = 1;
					$index = 0;
				}
				
				if ($flag == 0)
					$newFileName .= $s;
			}
			else
			{
				if (!in_array($s,$forbiddenChars))
				{		
					$newFileName .= $s;			
					if ($flag == 1 )			
						$flag = 0;
						
					$index = 0;
				}			
			}		
		}
		
		$fileName = $newFileName;
		$fileName = str_replace(' ','-',$fileName);
		return $fileName;
	}
	
	static function get_thumb_path($path, $size='m')
	{
		// in $path astept ceva de genu : ../images/strtolower($nume_oras)/strtolower($fileName)
		$arr = explode("/",$path);
		if($size == 's')
			$dir = 'small_thumbs';
		else 
			$dir = 'med_thumbs';
		$new_path = '';
		if(isset($arr[0]) && isset($arr[1]) && isset($arr[2]))
			$new_path = $arr[0]."/".$arr[1]."/".$dir."/".$arr[2];
		return $new_path;
	}
	
	static function getSanitizedText($val)
	{
		return strip_tags($val);
	}
	
	static function GetHotelOrasForLink($hotelName, $orasName)
	{
		return strtolower(str_replace(" ","-",$hotelName).'_'.str_replace(" ","-",$orasName));
	}
	
	static function GetHotelOrasForName($hotelName, $orasName)
	{
		return ucwords($hotelName).' '.ucwords($orasName);
	}
	
	static function CapitalizeString($val)
	{
		return str_replace('- ','-',ucwords(str_replace('-','- ',strtolower($val))));
	}
	
	static function IsValidFaxPhoneNumber($str, $length = 10)
	{
		$pattern = '@\d+@';
		// if (strlen($str) != $length) return false;
		if (strpos($str, '07') === 0) return false; // if mobile phone
		return preg_match($pattern, $str);
	}
	
	static function ExtractNumbersFromString($str)
	{
		$pattern = '@(\d+)@';
		preg_match_all($pattern, $str, $matches);
		return $matches[1];
	}
	
	static function PreparePhoneNumberForCall($phoneNumber)
	{
		$phoneNumber = trim($phoneNumber);
		if ($phoneNumber != '')
		{
			if (strpos($phoneNumber, '0040') === 0) $phoneNumber = substr($phoneNumber, 1); // remove first 0;
			$phoneNumber = str_replace(array('.', '/', '\\', '+4', ' '), array('','','','',''), $phoneNumber);
		}
		return $phoneNumber;
	}
	
	static function CapitalizeSentences($str)
	{
		$output = preg_replace_callback('/([.!?])\s*(\w)/', function ($matches) {
				return strtoupper($matches[1] . ' ' . $matches[2]);
			}, ucfirst(strtolower($str)));
		return $output;
	}
	
    static function ReplaceDiacritics($string)
    {
        $accented = array(
            'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ă', 'Ą',
            'Ç', 'Ć', 'Č', 'Œ',
            'Ď', 'Đ',
            'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ă', 'ą',
            'ç', 'ć', 'č', 'œ',
            'ď', 'đ',
            'È', 'É', 'Ê', 'Ë', 'Ę', 'Ě',
            'Ğ',
            'Ì', 'Í', 'Î', 'Ï', 'İ',
            'Ĺ', 'Ľ', 'Ł',
            'è', 'é', 'ê', 'ë', 'ę', 'ě',
            'ğ',
            'ì', 'í', 'î', 'ï', 'ı',
            'ĺ', 'ľ', 'ł',
            'Ñ', 'Ń', 'Ň',
            'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ő',
            'Ŕ', 'Ř',
            'Ś', 'Ş', 'Š',
            'ñ', 'ń', 'ň',
            'ò', 'ó', 'ô', 'ö', 'ø', 'ő',
            'ŕ', 'ř',
            'ś', 'ş', 'š',
            'Ţ', 'Ť',
            'Ù', 'Ú', 'Û', 'Ų', 'Ü', 'Ů', 'Ű',
            'Ý', 'ß',
            'Ź', 'Ż', 'Ž',
            'ţ', 'ť','ț',
            'ù', 'ú', 'û', 'ų', 'ü', 'ů', 'ű',
            'ý', 'ÿ',
            'ź', 'ż', 'ž',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р',
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'р',
            'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
            'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'
            );

        $replace = array(
            'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'A', 'A',
            'C', 'C', 'C', 'CE',
            'D', 'D',
            'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'a', 'a',
            'c', 'c', 'c', 'ce',
            'd', 'd',
            'E', 'E', 'E', 'E', 'E', 'E',
            'G',
            'I', 'I', 'I', 'I', 'I',
            'L', 'L', 'L',
            'e', 'e', 'e', 'e', 'e', 'e',
            'g',
            'i', 'i', 'i', 'i', 'i',
            'l', 'l', 'l',
            'N', 'N', 'N',
            'O', 'O', 'O', 'O', 'O', 'O', 'O',
            'R', 'R',
            'S', 'S', 'S',
            'n', 'n', 'n',
            'o', 'o', 'o', 'o', 'o', 'o',
            'r', 'r',
            's', 's', 's',
            'T', 'T',
            'U', 'U', 'U', 'U', 'U', 'U', 'U',
            'Y', 'Y',
            'Z', 'Z', 'Z',
            't', 't', 't',
            'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y',
            'z', 'z', 'z',
            'A', 'B', 'B', 'r', 'A', 'E', 'E', 'X', '3', 'N', 'N', 'K', 'N', 'M', 'H', 'O', 'N', 'P',
            'a', 'b', 'b', 'r', 'a', 'e', 'e', 'x', '3', 'n', 'n', 'k', 'n', 'm', 'h', 'o', 'p',
            'C', 'T', 'Y', 'O', 'X', 'U', 'u', 'W', 'W', 'b', 'b', 'b', 'E', 'O', 'R',
            'c', 't', 'y', 'o', 'x', 'u', 'u', 'w', 'w', 'b', 'b', 'b', 'e', 'o', 'r'
            );

        return str_replace($accented, $replace, $string);
    }
}
?>