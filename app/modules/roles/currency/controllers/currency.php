<?php
class Currency extends AbstractController
{	
	function __construct()
	{
		parent::__construct();
	}
	
	function GetCurrencyList(&$trans)
	{
		$this->IncludeClasses(array('system/lib/enum/abstract_enum.php'));
		$this->LoadEnum('currency', 'currency');
		$rows = CurrencyItems::GetDataForDropdown($trans, 'currency.');
		
		return $rows;
	}
}
?>
