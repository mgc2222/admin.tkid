<?php 
class GeneratePhpCode
{
	var $dbo;
	
	function GetCode($tableName)
	{
		$this->dbo = DBO::global_instance();
		
		$sql = 'DESCRIBE '.$tableName;
		$res = $this->dbo->Query($sql);
		
		$rows = array();
		while (true)
		{
			$row = new stdClass();
			$this->dbo->SetFetchMethod(PDO::FETCH_INTO, $row);
			$row = $this->dbo->Fetch($res);
			if (!$row) {
				break;
			}
			array_push($rows, $row);
		}		

		if ($rows != null)
			return $this->GenerateCode($rows, $tableName);
	}
	
	function GenerateCode(&$rows, $tableName)
	{
		// $code = '<textarea rows="30" cols="300">';
		$code = '<pre>';
		
		$code .= $this->GenerateMapping($rows);
		$code .= $this->GenerateSaveRecordDb($rows);
		$code .= $this->GenerateFormData($rows);
		$code .= $this->GenerateFormDataEmpty($rows);
		$code .= $this->GenerateCompleteFormData($rows);
		
		$code .= '</br></br>';
		$code .= htmlentities($this->GenerateEditHtml($rows, $tableName));
		$code .= '</br></br>';
		
		$code .= '</br></br>';
		$code .= $this->GenerateTranslation($rows, $tableName);
		$code .= '</br></br>';
		
		$code .= $this->GenerateSelectData($rows);
		
		$code .= '</pre>';
		return $code;
	}
	
	function GenerateMapping(&$rows)
	{
		$ret = '<br/><br/>';
		$ret .= 'function SetMapping()';
		$ret .= '<br/>';
		$ret .= '{';
		$ret .= '<br/>';
		$ret .= "\t".'$this->mapping = array(';
		$bindings = '';
		
		foreach ($rows as &$row)
		{
			if ($row->Key == 'PRI') continue;
			$htmFieldName = $this->GetHtmlFieldName($row->Field, $row->Type);
			$bindings .= ",'".$row->Field."'=>'".$htmFieldName."'";
		}
		$bindings = substr($bindings, 1);
		$ret .= $bindings;
		$ret .= ');';
		$ret .= '<br/>';
		$ret .= '}';
		$ret .= '<br/><br/>';
		
		return $ret;
	}
	
	function GenerateSaveRecordDb(&$rows)
	{
		$lf = "\r\n";
		$lft = "\r\n\t";
		$lft2 = "\r\n\t\t";
		
		$code = 'function SaveRecordInDatabase(&$data, &$row)'.$lf;
		$code .= '{'.$lft;
		$code .= 'if ($data->EditId == 0)'.$lft;
		$code .= '{'.$lft2;
		$code .= $lft;
		$code .= '}'.$lf.$lft;
		
		foreach ($rows as &$row)
		{
			if ($row->Key == 'PRI') continue;
			$htmFieldName = $this->GetHtmlFieldName($row->Field, $row->Type);
			if (strpos($htmFieldName, 'chk') === 0)
				$code .= '$row->'.$row->Field.' = isset($data->'.$htmFieldName.')?1:0;'.$lft;
			else
				$code .= '$row->'.$row->Field.' = $data->'.$htmFieldName.';'.$lft;
		}
		
		$code .= $lft;
		$code .= '$this->dbo->SetCaller($this->debugFileName.\' : SaveRecordInDatabase\');'.$lft;
		$code .= '$recordId = $this->dbo->SaveRow($row);'.$lft;
		$code .= 'return $recordId;'.$lf;
		$code .= '}'.$lf.$lf;
		
		return $code;
	}
	
	function GenerateFormData(&$rows)
	{
		$lf = "\r\n";
		$lft = "\r\n\t";
		$lft2 = "\r\n\t\t";
		
		$code = 'function GetFormData($recordId, &$data)'.$lf;
		$code .= '{'.$lft;
		$code .= 'if ($recordId == 0)'.$lft;
		$code .= '{'.$lft2;
		$code .= '$this->GetFormDataEmpty($data);'.$lft2;
		$code .= 'return true;'.$lft;
		$code .= '}'.$lf.$lft;
		$code .= '$row = $this->GetRecordById($recordId);'.$lf.$lft;
		$code .= 'if ($row == null)'.$lft2;
		$code .= 'return false;'.$lf.$lft;
		
		foreach ($rows as &$row)
		{
			if ($row->Key == 'PRI') continue;
			$htmFieldName = $this->GetHtmlFieldName($row->Field, $row->Type);
			if (strpos($htmFieldName, 'chk') === 0)
				$code .= '$data->'.$htmFieldName.' = ($row->'.$row->Field.' == 1)?\'checked="checked"\':\'\';'.$lft;
			else
				$code .= '$data->'.$htmFieldName.' = $row->'.$row->Field.';'.$lft;
		}
		
		$code .= $lft;
		$code .= 'return true;'.$lf;
		$code .= '}'.$lf.$lf;
		
		return $code;
	}
	
	function GenerateFormDataEmpty(&$rows)
	{
		$lf = "\r\n";
		$lft = "\r\n\t";
		$lft2 = "\r\n\t\t";
		
		$code = 'function GetFormDataEmpty(&$data)'.$lf;
		$code .= '{'.$lft;
		
		foreach ($rows as &$row)
		{
			if ($row->Key == 'PRI') continue;
			$htmFieldName = $this->GetHtmlFieldName($row->Field, $row->Type);
			if (strpos($htmFieldName, 'chk') === 0) // if checkbox, then comment it, maybe is wanted default checked ... then can be uncommented
				$code .= '// ';
			$code .= '$data->'.$htmFieldName.' = \'\';'.$lft;
		}
		
		$code .= $lft;
		$code .= 'return true;'.$lf;
		$code .= '}'.$lf.$lf;
		
		return $code;
	}
	
	function GenerateCompleteFormData(&$rows)
	{
		$lf = "\r\n";
		$lft = "\r\n\t";
		$lft2 = "\r\n\t\t";
		
		$code = 'function CompleteFormData(&$data)'.$lf;
		$code .= '{'.$lft;
		
		foreach ($rows as &$row)
		{
			if ($row->Key == 'PRI') continue;
			$htmFieldName = $this->GetHtmlFieldName($row->Field, $row->Type);
			if (strpos($htmFieldName, 'chk') === 0) // if checkbox, then comment it, maybe is wanted default checked ... then can be uncommented
				$code .= '$data->'.$htmFieldName.' = isset($data->'.$htmFieldName.')?\'checked="checked"\':\'\';'.$lft;
		}
		
		$code .= $lf;
		$code .= '}'.$lf.$lf;
		
		return $code;
	}
	
	function GenerateSelectData(&$rows)
	{
		$lf = "\r\n";
		$lft = "\r\n\t";
		$lft2 = "\r\n\t\t";
		
		$fields = '';
		foreach ($rows as &$row)
		{
			$fields .= ",".$row->Field;
		}
		$fields = substr($fields, 1);
		
		$ret = '<br/><br/>';
		$ret .= 'function SelectData()';
		$ret .= '<br/>';
		$ret .= '{';
		$ret .= '<br/>';
		$ret .= "\t".'$sql = "SELECT '.$fields.$lft.
		'FROM {$this->table}'.$lft.
		'{$cond} {$order} {$limit}";';
		
		$ret .= '<br/>';
		$ret .= '}';
		$ret .= '<br/><br/>';
		
		return $ret;
	}
	
	function GenerateEditHtml(&$rows, $tableName)
	{
		$lf = "\r\n";
		$lft = "\r\n\t";
		$lft2 = "\r\n\t\t";
		$wrapper = array('tr','td');
		
		$code = '<div class="edit_wrapper">'.$lft;
		$code .= '<table cellpadding="0" cellspacing="0" border="0" class="edit_table">';
		$code .= $lft;
		
		foreach ($rows as &$row)
		{
			if ($row->Key == 'PRI') continue;
			$code .= $this->GetWrapperTag($wrapper);
			
			$htmlFieldName = $this->GetHtmlFieldName($row->Field, $row->Type);
			
			$fieldLower = $row->Field;
			if ($this->endsWith($fieldLower, '_id')) {
				$fieldLower = substr($fieldLower, 0, strlen($fieldLower) - 3);
			}
			$labelName = '<?php echo $trans[\''.$tableName.'.'.$fieldLower.'\']?>';
			$selectData = $fieldLower.'List';
			
			$code .= '<label for="'.$htmlFieldName.'">'.$labelName.'</'.$wrapper[1].'>'.$lft2.'<'.$wrapper[1].'>';
			
			if (strpos($htmlFieldName, 'chk') === 0)
				$code .= '<input type="checkbox" id="'.$htmlFieldName.'" name="'.$htmlFieldName.'" value="1" <?php echo $dataView->'.$htmlFieldName.'?>" />';
			else if (strpos($htmlFieldName, 'txt') === 0)
				$code .= '<input type="text" class="form-control" id="'.$htmlFieldName.'" name="'.$htmlFieldName.'" value="<?php echo $dataView->'.$htmlFieldName.'?>" />';
			else  if (strpos($htmlFieldName, 'ddl') === 0)
				$code .= '<select class="form-control" id="'.$htmlFieldName.'" name="'.$htmlFieldName.'"><?php echo $dataView->'.$selectData.';?></select>';
			
			$code .= $this->GetWrapperTag($wrapper, true);
		}
		
		$code .= $lft;
		$code .= '</table>'.$lf;
		$code .= '</div>';
		return $code;
	}
	
	function endsWith($haystack, $needle) {
		// search forward starting from end minus needle length characters
		return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
	}
	
	function GenerateTranslation(&$rows, $tableName)
	{
		$lf = "\r\n";
		$lft = "\r\n\t";
		$lft2 = "\r\n\t\t";
		$wrapper = array('tr','td');
		
		$code = '';
		
		foreach ($rows as &$row)
		{
			if ($row->Key == 'PRI') continue;
			$fieldLower = $row->Field;
			if ($this->endsWith($fieldLower, '_id')) {
				$fieldLower = substr($fieldLower, 0, strlen($fieldLower) - 3);
			}
			$code .= '$trans[\''.$tableName.'.'.$fieldLower.'\'] = \'\';'.$lf;			
		}		
		return $code;
	}
	
	function GetWrapperTag($wrapper, $closeTag = false)
	{
		$ret = '';
		$lft = "\r\n\t";
		$lft2 = "\r\n\t\t";
		$lft3 = "\r\n\t\t\t";
		
		if (is_array($wrapper))
		{
			$wrapperUsed = ($closeTag)?array_reverse($wrapper):$wrapper;
			$rowIndex = 0;
			foreach ($wrapperUsed as $item)
			{
				$lf = $lft2;
				if ($closeTag)
				{
					$lf = $lft;
				}
				else if ($rowIndex == 1)
					$lf = '';
				
				$ret .= '<';
				if ($closeTag)
					$ret .= '/';
				$ret .= $item;
				$ret .= '>';
								
				$ret .= $lf;
				
				$rowIndex++;
			}
		}
		else 
		{
			$ret .= '<';
			if ($closeTag)
				$ret .= '/';
			$ret .= $wrapper;
			$ret .= '>';
			$ret .= $lft2;
		}
		
		return $ret;
	}
	
	function GetHtmlFieldName($fieldName, $fieldType)
	{
		if (strpos($fieldType, 'tinyint') === 0)
			$prefix = 'chk';
		else if (strpos($fieldName, '_id') > 0)
			$prefix = 'ddl';
		else
			$prefix = 'txt';
			
		$val = $prefix.str_replace('_ ','',ucwords(str_replace('_','_ ',strtolower($fieldName))));
		return $val;
	}
	
	
	function GetHtmlFieldCode($fieldName, $fieldType)
	{
		if (strpos($fieldType, 'tinyint') === 0)
			$prefix = 'chk';
		else if (strpos($fieldName, '_id') > 0)
			$prefix = 'ddl';
		else
			$prefix = 'txt';
			
		$val = $prefix.str_replace('_ ','',ucwords(str_replace('_','_ ',strtolower($fieldName))));
		return $val;
	}
	
	
}
?>