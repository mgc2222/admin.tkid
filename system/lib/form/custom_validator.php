<?php 
class CustomValidator
{	
	var $Message;
	var $isValid;
	
	function __construct()
	{
		$this->isValid = true;
		$this->Message = '';
	}
	
	function ResetValidator()
	{
		$this->isValid = true;
		$this->Message = '';
	}
	
	function ValidateArray(&$arr)
	{
		foreach ($arr as &$item)
		{
			$this->Validate($item['field'], $item['rule'], $item['message']);
		}
	}
	
	function Validate($val, $rules, $errorMessage)
	{
		$arrRules = explode('|', $rules);
		if (strpos($errorMessage, '|') > 0)
			$arrErrorMessage = explode('|', $errorMessage);
		else $arrErrorMessage = null;
		$ruleIndex = 0;
		foreach ($arrRules as $rule)
		{
			$params = null;
			if (strpos($rule, '[') > 0)
			{
				$regexPattern = '@(\w+)\[([^[]+)?\]@';
				if (preg_match($regexPattern, $rule, $matches))
				{
					$rule = $matches[1];
					$params = explode('|', $matches[2]);
				}
			}
			if ($arrErrorMessage != null && count($arrErrorMessage) > $ruleIndex)
				$errorMessage = $arrErrorMessage[$ruleIndex];

			// format message
			if (strpos($errorMessage, '%s') !== false)
				$errorMessage = sprintf($errorMessage, $val);
				
			$this->CheckRule($val, $rule, $params, $errorMessage);
			
			$ruleIndex++;
		}
	}
	
	function CheckRule($val, $rule, $params, $errorMessage)
	{
		switch ($rule)
		{
			case 'required': 
				if ($val == '') { $this->isValid = false; $this->Message .= '|'.$errorMessage; }
			break;
			case 'romanian_mobile_phone':
				if (!$this->IsValidMobilePhoneNumber($val)) { $this->isValid = false; $this->Message .= '|'.$errorMessage; }
			break;
			case 'fax_phone':
				if (!$this->IsValidFaxPhoneNumber($val)) { $this->isValid = false; $this->Message .= '|'.$errorMessage; }
			break;
			case 'max_length':
				if ($params == null) { $this->isValid = false; $this->Message .= '|Invalid params count for rule:'.$rule; }
				else if (strlen($val) > (int)$params[0]) { $this->isValid = false; $this->Message .= '|'.$errorMessage; }
			break;
			case 'min_length':
				if ($params == null) { $this->isValid = false; $this->Message .= '|Invalid params count for rule:'.$rule; }
				else if (strlen($val) < (int)$params[0]) { $this->isValid = false; $this->Message .= '|'.$errorMessage; }
			break;
			case 'email': 
				if (!$this->IsEmail($val)) { $this->isValid = false; $this->Message .= '|'.$errorMessage; }
			break;
		}
	}
	
	function IsValid()
	{
		$this->Message = substr($this->Message, 1); // remove first '|'
		$this->Message = str_replace('|', '<br/>', $this->Message);
		return $this->isValid;
	}
	
	function SetIsValid($valid) { $this->isValid = $valid; }
	
	function IsEmail($val)
	{
		$regexPattern = '/^[-_.a-z0-9]+@(([-_a-z0-9]+\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i';
		
		return preg_match($regexPattern, $val);
	}
	
	function IsValidMobilePhoneNumber($str, $length = 10)
	{
		$pattern = '@\d+@';
		if (strlen($str) != $length || strpos($str, '07') !== 0) return false;
		return preg_match($pattern, $str);
	}
	
	function IsValidFaxPhoneNumber($str, $length = 10)
	{
		$pattern = '@\d+@';
		// if (strlen($str) != $length) return false;
		if (strpos($str, '07') === 0) return false; // if mobile phone
		return preg_match($pattern, $str);
	}

}
?>