<?php
class EmailSend extends AbstractController
{
	
	function __construct()
	{
		parent::__construct();
		$this->module = 'email';
	}
	
	public function Send(&$emailData)
	{
		$mail = new PHPMailer;
		
		$mail->isSMTP();
		$mail->Host = PHPMAILER_HOST;
		$mail->SMTPAuth = PHPMAILER_AUTH;
		$mail->Port = PHPMAILER_PORT;
		$mail->SMTPSecure = PHPMAILER_SMTP_SECURE;
		$mail->Username = PHPMAILER_USERNAME;
		$mail->Password = PHPMAILER_PASSWORD;
		
		// $mail->SMTPDebug = 4;
		
		$mail->AllowEmpty = true;
		
		$mail->setFrom($emailData->from, $emailData->fromName);
		$mail->addReplyTo($emailData->from, $emailData->fromName);
		$mail->addAddress($emailData->to);
		$mail->Subject = $emailData->subject;
		$mail->isHTML(true);
		$mail->Body = $emailData->body;
		$mail->AltBody = str_replace('<br/>', PHP_EOL, $emailData->body);
		
		if (isset($emailData->bccEmail) && $emailData->bccEmail) {
			$mail->addBCC($emailData->bccEmail, $emailData->bccName);
		}
		
		//Attach a file
		if (isset($emailData->attachments) && $emailData->attachments) {
			foreach ($emailData->attachments as &$attachment) {
				$mail->addAttachment($attachment['filePath'], $attachment['fileName']);
			}
		}
		
		//send the message, check for errors
		$mailSent = $mail->send();
		// print_r($mail->ErrorInfo);
		return (object)array('status'=>$mailSent, 'error' => $mail->ErrorInfo);
		
	}
}