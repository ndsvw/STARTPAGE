<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/mailsender.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/user.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/seitenaufruf.php");

	$view = new Seitenaufruf();
	$view->save_view();

	if(isset($_POST['resend'])){
		$query = mysql_query("
			SELECT
				id
			FROM
				user
			WHERE
				mail = '" . mysql_real_escape_string($_POST['veriEmail']) . "'
			AND
				verified = '0'
		");
		if(mysql_num_rows($query) == 1){
			User::sendVerificationMail(mysql_fetch_array($query)[0]);
			echo "success";
		}
		exit();
	}
	if(isset($_GET['code'])){
		$query = mysql_query("
			SELECT
				id,
				mail
			FROM
				user
			WHERE
				verificationCode = '" . mysql_real_escape_string($_GET['code']) . "'
			AND
				verified = '0'
		");
		if(mysql_num_rows($query) > 0){
			mysql_query("
				UPDATE
					user
				SET
					verified = '1',
					verificationCode = ''
				WHERE
					verificationCode = '" . mysql_real_escape_string($_GET['code']) . "'
			");

			$message = "";
			$message .= "Dein Account wurde erfolgreich aktiviert.<br />";
			$message .= "<a href='http://" . $_SERVER['SERVER_NAME'] . "'>Zur Seite</a><br /><br />";
			$mailsender = new MailSender;
			$mailsender->sendMail(mysql_fetch_array($query)[1], "noreply@" . $_SERVER['SERVER_NAME'], "Account erfolgreich aktiviert", $message);
			header("Location: /login.php?check=verified");
		} else {
			echo "Error! Code ungültig!";
		}
	} else {
		header("Location: /login.php");
	}
?>
