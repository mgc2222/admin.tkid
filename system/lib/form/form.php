<?php
class Form
{
	var $isPostback;
	var $data;
	function __construct($defaultAction = '', $defaultParams = '')
	{
		$this->isPostback = (isset($_POST['sys_Action']));
		$this->data = $this->GetPostObject($defaultAction, $defaultParams);
	}
	
	function IsPostback()
	{
		return $this->isPostback;
	}
	
	function GetPostObject($defaultAction, $defaultParams)
	{
		$ret = new stdClass();
		// R comes from : Reserved
		if (isset($_POST['sys_Action']))
		{
			$ret->Params = '';
			$ret->EditId = 0;
			
			// get post keys; do not name any post field as Action / sys_Action / sys_Params / sys_EditId
			foreach ($_POST as $key=>$val)
			{
				if ($key == 'Action' || $key == 'Params' || $key == 'EditId')
					die('A post value named Action, Params or EditId was found. These values are not allowed. Please correct');
					
				if ($key == 'sys_Action')
					$ret->Action = $_POST['sys_Action'];
				else if ($key == 'sys_Params')
					$ret->Params = $_POST['sys_Params'];
				else if ($key == 'sys_EditId')
					$ret->EditId = $_POST['sys_EditId'];
				else
				{
					if (is_array($val))
					{
						$this->PrepareArrayRec($_POST[$key]);
						$ret->{$key} = $_POST[$key];
					}
					else
						$ret->{$key} = trim($val);
				}
			}
			
			if ($ret->Action == '' && $defaultAction != '') $ret->Action = $defaultAction;
			if ($ret->Params == '' && $defaultParams != '') $ret->Params = $defaultParams;
		}
		else
		{
			$ret->Action = '';
			$ret->Params = '';
			$ret->EditId = isset($_GET['id'])?(int)$_GET['id']:0;
		}
		return $ret;
	}
	
	function GetQueryObject()
	{
		$ret = new stdClass();
		foreach ($_GET as $key=>$val)
		{
			if (is_array($val))
			{
				$this->PrepareArrayRec($_GET[$key]);
				$ret->{$key} = $_GET[$key];
			}
			else
				$ret->{$key} = trim($val);
		}
		return $ret;
	}
	
	function PrepareArrayRec(&$arr)
	{
		foreach ($arr as $key=>$val)
		{
			if (is_array($val))
				$this->PrepareArrayRec($_POST[$key]);
			else
			{
				$_POST[$key] = trim($val);
			}
		}
	}
}
?>