<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php"); 
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/PHPMailer/PHPMailerAutoload.php");

	class MailSender{
		public function sendMail($to, $from, $subject, $text){
			$mail = new PHPMailer;
			$mail->isHTML(true);
			$mail->CharSet = "utf-8";
			$mail->setFrom($from, $from);
			$mail->addAddress($to, $to);
			$mail->Subject = $subject;
			$mail->Body = $text;

			if(!$mail->send()) {
			    echo 'Email konnte nicht gesendet werden!';
			    echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
			    echo 'Email wurde erfolgreich gesendet!';
			}
		}
	}
?>