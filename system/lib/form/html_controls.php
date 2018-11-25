<?php
class HtmlControls
{
	static function GenerateDropDownList($records, $valueField, $textField, $selectedValue='', $selectedValues = null)
	{
		$controlData = "";
		if ($records != null)
		{
			if (count($records) > 0)
			{
				foreach ($records as $recordItem)
				{
					if ($selectedValues != null)
						$selectedAttr = (in_array($recordItem->{$valueField}, $selectedValues))?' selected="selected" ':'';
					else
						$selectedAttr = ($recordItem->{$valueField} == $selectedValue)?' selected="selected" ':'';
						
					$controlData .= '<option value="'.$recordItem->{$valueField}.'"'.$selectedAttr.'>'.$recordItem->{$textField}.'</option>';
				}
			}
		}
		
		return $controlData;
	}
	
	static function GenerateDropDownListYear($yearsMinus, $yearsPlus, $selectedValue = '')
	{
		$nStartYear = date("Y", mktime(0, 0, 0, date("m"),   date("d"),   date("Y")-$yearsMinus));
		$nEndYear = date("Y", mktime(0, 0, 0, date("m"),   date("d"),   date("Y")+$yearsPlus));
		
		$records = array();
		for ($nYear = $nStartYear; $nYear <= $nEndYear; $nYear++)
		{
			$recordItem = new DropDownListItem();
			$recordItem->value = $nYear;
			$recordItem->text = $nYear;
			array_push($records, $recordItem);
		}
		
		return HtmlControls::GenerateDropDownList($records, "value", "text", $selectedValue);
	}
	
	
	static function GenerateDropDownListMonth($selectedValue = '')
	{
		$arrMonths = array("Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie", "Iulie",
						"August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie");
		
		$records = array();
		for ($nMonth = 1; $nMonth <= 12; $nMonth++)
		{
			$recordItem = new DropDownListItem();
			$recordItem->value = $nMonth; //sprintf("%02d", $nMonth);
			$recordItem->text = $arrMonths[$nMonth-1];
			array_push($records, $recordItem);
		}
		
		return HtmlControls::GenerateDropDownList($records, "value", "text", $selectedValue);
	}
	
	static function GenerateDropDownListNumbers($startNumber, $endNumber, $formatNumber =  false, $selectedValue = '')
	{
		$records = array();
		// example of format: $formatNumber = "%02d";
		
		for ($nIndex = $startNumber; $nIndex < $endNumber; $nIndex++)
		{
			$recordItem = new DropDownListItem();
			$recordItem->value = ($formatNumber)?sprintf($formatNumber, $nIndex):$nIndex;
			$recordItem->text = ($formatNumber)?sprintf($formatNumber, $nIndex):$nIndex;
			array_push($records, $recordItem);
		}
		
		return HtmlControls::GenerateDropDownList($records, "value", "text", $selectedValue);
	}
	
	static function GenerateDropDownListHoursHalf($startNumber, $endNumber, $selectedValue = '')
	{
		$rows = array();
		$formatNumber = "%02d:%02d";
		
		for ($index = $startNumber; $index <= $endNumber; $index++)
		{
			$row = new DropDownListItem();
			$number = ($index == 24)?0:$index;
			$row->value = sprintf($formatNumber, $number, 0);
			$row->text = sprintf($formatNumber, $number, 0);
			array_push($rows, $row);
			
			if ($index != $endNumber)
			{
				$row = new DropDownListItem();
				$row->value = sprintf($formatNumber, $number, 30);
				$row->text = sprintf($formatNumber, $number, 30);
				array_push($rows, $row);
			}
		}
		
		return HtmlControls::GenerateDropDownList($rows, "value", "text", $selectedValue);
	}
	
	static function GenerateDropDownListStrings($itemsText, $itemsValue=null, $selectedValue, $selectedValues = null)
	{
		$records = array();
		$itemsCount = count($itemsText);
		for ($nIndex = 0; $nIndex < $itemsCount; $nIndex++)
		{
			// if no value passed, use the text as value
			$itemValue = ($itemsValue == null)?$itemsText[$nIndex]:$itemsValue[$nIndex];
			$recordItem = new DropDownListItem($itemsText[$nIndex], $itemValue);
			array_push($records, $recordItem);
		}
		
		return HtmlControls::GenerateDropDownList($records, "value", "text", $selectedValue, $selectedValues);
	}
	
	static function MultiplyContent($count, $data)
	{
		$ret = '';
		for ($rowIndex = 0; $rowIndex < $count; $rowIndex++)
		{
			$ret .= $data;
		}
		return $ret;
	}
	
	// $controlName - i.e.: chkPage -> will generate ids chkPage_1, chkPage_2 ....; group name will be chkPage[]
	// $cssClass - required for check all; 
	static function GenerateCheckList($arrLabels, $arrSelectedValues, $controlName, $cssClass ='', $addCheckAll=false)
	{
		$ret = '';
		$rowIndex = 1;
		if ($cssClass != '') $cssClassAdd = ' class="'.$cssClass.'"';
		foreach ($arrLabels as $label)
		{
			if ($arrSelectedValues == null) $checkedStatus = '';
			else $checkedStatus = (in_array($rowIndex, $arrSelectedValues))?'checked="checked"':'';
			$ret .= '<input type="checkbox" id="'.$controlName.'_'.$rowIndex.'" name="'.$controlName.'[]" value="'.$rowIndex.'" '.$checkedStatus.$cssClassAdd.' /><label for="'.$controlName.'_'.$rowIndex.'">'.$label.'</label><br/>';
			
			$rowIndex++;
		}
		
		if ($addCheckAll)
		{
			$ret .= '<br/><input type="checkbox" id="'.$controlName.'_ALL" name="'.$controlName.'_ALL" value="" /><label for="'.$controlName.'_ALL"><strong>Check All</strong></label><br/>';
		}
		
		return $ret;
	}
	
	static function GenerateCheckListFromArray(&$rows, $fieldValue, $fieldText, $arrSelectedValues, $controlName, $cssClass ='')
	{
		$ret = '';
		$rowIndex = 1;
		$cssClassAdd = ($cssClass != '')?' class="'.$cssClass.'"':'';
		foreach ($rows as &$row)
		{
			if ($arrSelectedValues == null) $checkedStatus = '';
			else $checkedStatus = (in_array($row->{$fieldValue}, $arrSelectedValues))?'checked="checked"':'';
			$ret .= '<span class="item-wrapper"><input type="checkbox" id="'.$controlName.'_'.$rowIndex.'" name="'.$controlName.'[]" value="'.$row->{$fieldValue}.'" '.$checkedStatus.$cssClassAdd.' /><label for="'.$controlName.'_'.$rowIndex.'">'.$row->{$fieldText}.'</label></span>';
			
			$rowIndex++;
		}
		
		return $ret;
	}
	
	static function JsScript($content, $source)
	{
		if ($source != '')
			return '<script type="text/javascript" src="'.$source.'></script>';
		else return '<script type="text/javascript">'.$content.'</script>';
	}
	
	static function JsAlert($message)
	{
		$message = addslashes($message);
		return HtmlControls::JsScript("alert('{$message}');");
	}
	
	static function GenerateImage($imgSrc, $alt='', $attr = '')
	{
		$ret = '<img src="'.$imgSrc.'" alt="'.$alt.'"'.$attr.' />';
		return $ret;
	}
	
	static function GenerateLink($href, $anchor, $attr = '')
	{
		$ret = '<a href="'.$href.'" '.$attr.' >'.$anchor.'</a>';
		return $ret;
	}
	
	static function GenerateAdminEditLink($pageUrl, $id, $title = 'Edit element')
	{
		$linkUrl =  _SITE_URL.$pageUrl.'/edit/'.$id;

		$ret = '<a title="'.$title.'"href="'.$linkUrl.'"><i class="fa fa-pencil fa-lg"></i></a>';
		return $ret;
	}
	
	static function GenerateAdminDeleteLink($id, $title = 'Delete element')
	{
		$ret = '<a title="'.$title.'" class="delete-item" data-id="'.$id.'"><i class="fa fa-trash-o fa-lg"></i></a>';
		return $ret;
	}
	
	static function GenerateAdminLink($href, $anchor, $title, $attr = '')
	{
		$ret = '<a title="'.$title.'" href="'.$href.'" '.$attr.'>';
		if ($anchor != '') 
			$ret.= $anchor;
		$ret.= '</a>';
		
		return $ret;
	}
	
	static function GenerateGridButtons($pageUrl, $saveCaption, $deleteName = '', $returnUrl = '', $returnName = '')
	{		
		$linkUrl =  _SITE_URL.$pageUrl.'/edit';
		
		$ret = '<a href="'.$linkUrl.'"><span class="btn btn-success"><i class="fa fa-fw fa-edit"></i> '.$saveCaption.'</span></a>';
		if ($returnUrl != '')
			$ret .= '&nbsp;&nbsp;<a href="javascript:;" href="'.$returnUrl.')"><span class="btn btn-default"><i class="fa fa-fw fa-list"></i> '.$returnName.'</span></a>';
		
		if ($deleteName != '')
			$ret.= '&nbsp;&nbsp;<a href="javascript:;" onclick="frm.FormDeleteSelected()"><span class="btn btn-default"><i class="fa fa-fw fa-trash-o"></i> '.$deleteName.'</span></a>';
		
		return $ret;
	}
	
	static function GenerateGridNewButtons($pageUrl, $extraParams, $saveCaption, $deleteName = '', $returnUrl = '', $returnName = '')
	{		
		$linkUrl =  _SITE_URL.$pageUrl.'/edit'.$extraParams;
		
		$ret = '<a href="'.$linkUrl.'"><span class="btn btn-success"><i class="fa fa-fw fa-edit"></i> '.$saveCaption.'</span></a>';
		if ($returnUrl != '')
			$ret .= '&nbsp;&nbsp;<a href="javascript:;" href="'.$returnUrl.')"><span class="btn btn-default"><i class="fa fa-fw fa-list"></i> '.$returnName.'</span></a>';
		
		if ($deleteName != '')
			$ret.= '&nbsp;&nbsp;<a href="javascript:;" onclick="frm.FormDeleteSelected()"><span class="btn btn-default"><i class="fa fa-fw fa-trash-o"></i> '.$deleteName.'</span></a>';
		
		return $ret;
	}
	
	static function GenerateDeleteSelected($deleteName = '')
	{		
		$ret = '&nbsp;&nbsp;<a href="javascript:;" onclick="frm.FormDeleteSelected()"><span class="btn btn-default"><i class="fa fa-fw fa-trash-o"></i> '.$deleteName.'</span></a>';
		return $ret;
	}
	
	static function GenerateFormButtons($saveCaption, $clickFunction = 'saveFormData();', $returnUrl = '', $returnLabel = '')
	{
		if ($clickFunction)
			$clickEvent = 'onclick="'.$clickFunction.'"';
		else $clickEvent = '';
		
		$ret = '<a href="javascript:;" '.$clickEvent.' class="btn-save"><span class="btn btn-success"><i class="fa fa-fw fa-hand-o-right"></i> '.$saveCaption.'</span></a>';
		if ($returnUrl != '')
			$ret .= '<a href="'.$returnUrl.'"><span class="btn btn-default"><i class="fa fa-fw fa-list"></i> '.$returnLabel.'</span></a>';
		return $ret;
	}
	
	static function GenerateFormButton($caption, $icon, $href = '', $attr = '')
	{
		if ($href == '') $href = 'javascript:;';
		$ret = '<a href="'.$href.'" '.$attr.'><span class="btn btn-default"><i class="'.$icon.'"></i> '.$caption.'</span></a>';
		return $ret;
	}
	
	static function GenerateNewItemButton($pageUrl, $saveCaption)
	{
		$linkUrl =  _SITE_URL.$pageUrl.'/edit';
		$ret = '<a href="'.$linkUrl.'"><span class="btn btn-default"><i class="fa fa-fw fa-edit"></i> '.$saveCaption.'</span></a>';
		
		return $ret;
	}
	
	static function GenerateHiddenField($fieldId, $value)
	{
		$ret = '<input type="hidden" name="'.$fieldId.'" id="'.$fieldId.'" value="'.$value.'" />';
		return $ret;
	}
	
	static function GenerateFacebookLikeButton($row)
	{
		$ret = '<iframe src="http://www.facebook.com/plugins/like.php?href='.urlencode(_SITE_URL.'/'.$row->UrlKey.'-'.$row->ArticleId.'.html').'&amp;layout=button_count&amp;show_faces=false&amp;width=80&amp;action=like&amp;colorscheme=light&amp;height=80" frameborder="0" style="border:none; overflow:hidden; width:100%; height:22px;"></iframe>';
		return $ret;
	}
	
	static function GenerateSortableColumn($objSortColumn, $columnName, $title, $label)
	{
		$ret = '<a title="'.$title.'" class="list_sort '.$objSortColumn->CssClass[$columnName].'" href="javascript:;" onclick="frm.GridSortColumn(\''.$objSortColumn->Table.'\',\''.$columnName.'\')">'.$label.'</a>';
		return $ret;
	}
	
	static function GenerateSortHidColumn($objSortColumn)
	{
		$ret = '<input type="hidden" name="hidSortColumn_'.$objSortColumn->Table.'" id="hidSortColumn_'.$objSortColumn->Table.'" value="'.$objSortColumn->Input.'" />';
		return $ret;
	}
	
	static function GenerateRadioList(&$data)
	{
		$ret = '';
		
		foreach ($data->inputs as &$input)
		{
			$checkedStatus = ($data->selectedValue == $input->value)?'checked="checked"':'';
			$ret .= '<'.$data->elementTag.'><input type="radio" id="'.$input->id.'" name="'.$data->name.'" value="'.$input->value.'" '.$checkedStatus.' '.$input->attributes.' /><label for="'.$input->id.'">'.$input->label.'</label></'.$data->elementTag.'>';
		}
		
		return $ret;
	}
	
	static function GenerateMultilanguageTabs(&$languages, &$data, $defaultLanguageId, $contentClass = '')
	{
		$ret = new stdClass();
		$ret->Tabs = '';
		$ret->Content = '';
		
		$ret->Tabs = '<div role="tabpanel">';
		$ret->Tabs .= '<ul class="nav nav-tabs" role="tablist">';
		foreach ($languages as $lang)
		{
			$id = 'lang_'.$lang->abbreviation;
			$cssClass = ($lang->id == $defaultLanguageId)?'class="active"':'';
			$ret->Tabs .= '<li role="presentation" '.$cssClass.'><a href="#'.$id.'" aria-controls="'.$id.'" role="tab" data-toggle="tab">'.$lang->abbreviation.'</a></li>';
		}
		$ret->Tabs .= '</ul>';

		// Tab panes
		$ret->Content .= '<div class="tab-content multilang '.$contentClass.'">';
		foreach ($languages as $lang)
		{
			$input = '';
			foreach ($data as &$ctl)
			{
				$controlId = $ctl->id.'_'.$lang->id;
				$inputValue = $ctl->values[$lang->id];
				
				$input .= '<div>';
				$input .= '<label for="'.$controlId.'">'.$ctl->label.'</label>';
				$input .= '<span>';
				if ($ctl->type == 'input')
					$input .= '<input type="text" class="form-control" name="'.$controlId.'" id="'.$controlId.'" value="'.$inputValue.'" />';
				else if ($ctl->type == 'textarea')
					$input .= '<textarea class="form-control" name="'.$controlId.'" id="'.$controlId.'">'.$inputValue.'</textarea>';
				$input .= '</span>';
				$input .= '</div>';
			}
			
			$id = 'lang_'.$lang->abbreviation;
			$cssClass = ($lang->id == $defaultLanguageId)?' active':'';
			$ret->Content .= '<div role="tabpanel" class="tab-pane'.$cssClass.'" id="'.$id.'">'.$input.'</div>';
		}
		$ret->Content .= '</div>';
		
		return $ret;
	}
	
	static function GeneratePhoneCallElementIconWithSmsAndCheckbox($phoneNumber, $elementIndex, $smsTarget, $propertyId, $checked = false, $displayNumber = true)
	{
		$ret = '<span class="phone_call_wrapper">';
		$attr = ($checked)?' checked="checked" ':'';
		
		$query = "/target={$smsTarget};id={$propertyId};phone=";
		if (trim($phoneNumber) == '') $ret = '';
		else
		{		
			$phoneNumberPrepared = StringUtils::PreparePhoneNumberForCall($phoneNumber);
			$arrPhoneNumbers = StringUtils::ExtractNumbersFromString($phoneNumberPrepared);
			// if more than one number in the string, or if the string contains also text besides phone number
			if (count($arrPhoneNumbers) > 1 || (count($arrPhoneNumbers) == 1 && strlen($phoneNumberPrepared) > strlen($arrPhoneNumbers[0])))
			{
				$content = str_replace(';','', $phoneNumber);
				$content = str_replace(',','<br/>', $phoneNumber);
				foreach ($arrPhoneNumbers as $number)
				{
					$replace = '<a href="javascript:;" onclick="ctlPhone.ShowPhoneWindow(\''.$number.'\',\'\', this)" title="Suna"><i class="fa fa-phone-square"></i></a><span class="sms_icon_wrapper"><a href="sms.php'.$query.$number.'" title="Trimite SMS"><i class="fa fa-comment"></i></a></span><input type="checkbox" name="chkPhoneElement_'.$elementIndex.'" id="chkPhoneElement_'.$elementIndex.'" value="'.$number.'" />';
					if ($displayNumber) $replace = $number.' '.$replace;
					$content = str_replace($number, $replace, $content);
				}
				$ret .= $content.'</span>';
			}
			else if (count($arrPhoneNumbers) == 1) // just phone number found
			{
				if ($displayNumber) $ret .= $phoneNumber.' ';
				$ret .= '<a href="javascript:;" onclick="ctlPhone.ShowPhoneWindow(\''.$phoneNumberPrepared.'\',\'\', this)" title="Suna"><i class="fa fa-phone-square"></i></a>';
				$ret .= '</span>';
				$ret .= '<span class="sms_icon_wrapper"><a href="sms.php'.$query.$phoneNumberPrepared.'" title="Trimite SMS"><i class="fa fa-comment"></i></a></span>';
				$ret .= '<input type="checkbox" name="chkPhoneElement_'.$elementIndex.'" id="chkPhoneElement_'.$elementIndex.'" '.$attr.' value="'.$phoneNumber.'" />';
			}
			else // no valid phone number found
				$ret = $phoneNumber;
		}
		return $ret;
	}
	
	static function GenerateSendFaxIcon($fax)
	{
		$ret = '';
		if ($fax != '')
		{
			$ret = $fax.' <a href="'._SITE_RELATIVE_URL.'/fax/send/fax='.$fax.'" title="Trimite fax" target="_blank"><i class="fa fa-fax"></i></a>';
		}
		return $ret;
	}
	
	static function GenerateSendEmailIcon($email)
	{
		$ret = '';
		if ($email != '')
		{
			$ret = $email.' <a href="'._SITE_RELATIVE_URL.'editable_email/target=email&email='.$email.'" title="Trimite email" target="_blank"><i class="fa fa-comment"></i></a>';
		}
		return $ret;
	}
	
	static function GenerateSendEmailIconWithCheckbox($email, $elementIndex, $checked = false)
	{
		$ret = '';
		if ($email != '')
		{
			$ret = $email.' <a href="'._SITE_RELATIVE_URL.'editable_email/target=email&email='.$email.'" title="Trimite email" target="_blank"><i class="fa fa-comment"></i></a>';
			$attr = ($checked)?' checked="checked" ':'';
			$ret .= '<input type="checkbox" name="chkEmailElement_'.$elementIndex.'" id="chkEmailElement_'.$elementIndex.'" '.$attr.' value="'.$email.'" />';
		}
		return $ret;
	}
}

class DropDownListItem
{
	var $value;
	var $text;
	
	function __construct($itemText=null, $itemValue=null)
	{
		$this->text = $itemText;
		$this->value = $itemValue;
	}
}
?>