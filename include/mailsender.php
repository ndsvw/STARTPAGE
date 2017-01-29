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

			return $mail->send();
		}
	}
?>
