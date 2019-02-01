<?php
	require_once('controls/common_includes_ajax.php');
	require_once('controllers/ajax_controller.php');
	
	$objAjaxController = new AjaxController();
	$objAjaxController->HandleRequest();
?>