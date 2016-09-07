<?php
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php"); 
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/check.php");
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/mailsender.php"); 

	if(isset($_GET['email'])){
		$query = mysql_query("
			SELECT
				id
			FROM 
				user 
			WHERE 
				mail = '" . mysql_real_escape_string($_GET['email']) . "'
			AND
				verified = '0'
		");
		if(mysql_num_rows($query) == 1){
			mysql_query("
				UPDATE
					user
				SET
					verified = '1'
				WHERE
					mail = '" . mysql_real_escape_string($_GET['email']) . "'
			");
			
			$message = "";
			$message .= "Dein Account wurde erfolgreich aktiviert.<br />";
			$message .= "<a href='http://" . $_SERVER['SERVER_NAME'] . "'>Zur Seite</a><br /><br />";
			$mailsender = new MailSender;
			$mailsender->sendMail($_GET['email'], "noreply@" . $_SERVER['SERVER_NAME'], "Account erfolgreich aktiviert", $message);
			
			$infotext = urlencode("Dein Account ist nun verifiziert. Du kannst dich nun einloggen!");
			header("Location: /login.php?infotext=" . $infotext);
		} else {
			echo "Error2";
		}
	} else {
		echo "Error1";
	}
?>