<?php
class ContactEmail extends AbstractController
{
	private $attachmentFiles;
	private $trans;
	private $dbo;
	
	function __construct()
	{
		parent::__construct();
		$this->module = 'email';
		// $this->dbo = DBO::global_instance();
	}
		
	// ================================================================= //
	
	// =====================	Contact Email	======================== //
	public function SendContactEmail(&$formData)
	{
		$saveData = $this->GetContactSaveData($formData);
		$emailData = $this->GetContactEmailData($saveData);
		// $this->AddContactEmail($saveData);
		return $this->SendEmail($emailData);
	}
	
	private function AddContactEmail(&$emailData)
	{
		$save = array('email'=>$emailData->email, 'message'=>$emailData->message, 'name'=>$emailData->name, 'phone'=>$emailData->phone, 'date_added'=>'[NOW()]');
		$model = $this->LoadModel('contact');
		$model->AddContact($save);
	}
	
	private function GetContactSaveData(&$formData)
	{
		$data = new stdClass();
		// $data->name = $this->dbo->GetSafeValue(trim($formData->name));
		// $data->email = $this->dbo->GetSafeValue(trim($formData->email));
		// $data->phone = $this->dbo->GetSafeValue(trim($formData->phone));		
		// $data->subject = $this->dbo->GetSafeValue(trim($formData->subject));
		// $data->message = $this->dbo->GetSafeValue(trim($formData->message));
		
		$data->name = trim($formData->name);
		$data->email = trim($formData->email);
		$data->phone = trim($formData->phone);
		$data->message = trim($formData->message);
		
		return $data;
	}
	
	private function GetContactEmailData(&$saveData)
	{
		$data = new stdClass();
		$data->subject = "Formular de Contact "._SITE_NAME;
		$data->to = _CONTACT_EMAIL;
		$data->from = _FROM_EMAIL;
		$data->fromName = _FROM_EMAIL_NAME;
		$data->body = $this->GetContactEmailBody($saveData);
		
		return $data;
	}
	
	private function GetContactEmailBody(&$data)
	{
		$content = 'Nume: '.$data->name.'<br/>';
		$content .= 'Email: '.$data->email.'<br/>';
		$content .= 'Telefon: '.$data->phone.'<br/>';
		$content .= 'Mesaj: <br />'. nl2br($data->message) .' <br /><br />';
		
		return $content;
	}
	
	// ================================================================= //
	private function SendEmail(&$emailData)
	{
		$emailSend = $this->LoadController('email_send');
		$emailSent = $emailSend->Send($emailData);
		return $emailSent->status;
	}
}