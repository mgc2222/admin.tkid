<?php
class Dashboard extends AdminController
{
	function __construct()
	{
		parent::__construct();
		$this->module = 'dashboard';
		$this->Auth();
	}
	
	function GetViewData()
	{
		$pageId = 'dashboard';
		parent::SetWebpageData($pageId);
		
		$this->webpage->PageLayout = _APPLICATION_FOLDER.'layouts/default_layout_form.php';

		$data = new stdClass();
		return $data;
	}		
}
?>
