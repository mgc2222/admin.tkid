<?php
class FormFront
{
	var $isPostback;
	var $data;
	function __construct()
	{
		$this->data = $this->GetPostObject();
	}
	
	function IsPostback()
	{
		return $this->isPostback;
	}
	
	function GetPostObject()
	{
		$ret = new stdClass();
		// get post keys; do not name any post field as Action / sys_Action / sys_Params / sys_EditId
		foreach ($_POST as $key=>$val)
		{
			if (is_array($val))
			{
				$this->PrepareArrayRec($_POST[$key]);
				$ret->{$key} = $_POST[$key];
			}
			else
				$ret->{$key} = trim($val);
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