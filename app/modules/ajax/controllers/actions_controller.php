<?php
class ActionsController extends AbstractController
{
	function __construct()
	{
	}
	
	function GetDefaultData()
	{
		$data = new stdClass();	
		$data->status = 'error';
		$data->content = '';
		
		return $data;
	}
	
	function HandleRequest()
	{
		$data = $this->GetDefaultData();
		$formData = null;
		// $this->setTestData();
		if (!isset($_POST['ajaxAction']))
		{
			$formData = $this->GetAjaxJson();
			$action = $formData['ajaxAction'];
			$formData = (object)$formData;
		}
		else {
			$action = $_POST['ajaxAction'];
		}
	
		switch ($action)
		{
			case 'sendContactEmail':
				$data = $this->SendContactEmail($formData);
			break;
			default: 
				$data = $this->GetDefaultData(); 
			break;
		}
	
		echo json_encode($data);
	}
			
	function SendContactEmail(&$formData)
	{
		$data = $this->GetDefaultData();
		$ctlEmail = $this->LoadController('contact_email', 'email');
		$response = $ctlEmail->SendContactEmail($formData);
		if ($response) {
			$data->status = 'success';
			$data->message = 'Solicitarea dvs. a fost trimisa. Va multumim.';
		}
		else {
			$data->message = 'Eroare la trimitere solicitare. Va rugam sa incercati iar';
		}
		
		return $data;
	}
}

?>