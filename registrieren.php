<?php 
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php"); 
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/mailsender.php"); 
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/seitenaufruf.php"); 



	if(isset($_POST['email']) && isset($_POST['password1']) && isset($_POST['password2'])){
		if($_POST['password1'] == $_POST['password2']){
			$query = mysql_fetch_array(mysql_query("
				SELECT 
					COUNT(*) 
				FROM 
					user 
				WHERE 
					mail = '" . mysql_real_escape_string($_POST['email']) . "'
			"));
			if($query[0] == 0){
				mysql_query("
					INSERT INTO user (
						mail, 
						password,
						verified
					) 
					VALUES (
						'" . mysql_real_escape_string($_POST['email']) . "', 
						'" . md5($_POST['password1']) . "',
						'0'
					)
				");
				$query = mysql_fetch_array(mysql_query("
					SELECT 
						id 
					FROM 
						user 
					WHERE 
						mail = '" . mysql_real_escape_string($_POST['email']) . "'
				"));
				for($i=1;$i<=5;$i++){
					mysql_query("
						INSERT INTO startpage_user_suchen (
							user_id, 
							such_id
						) 
						VALUES (
							'" . $query[0] . "', 
							'" . $i . "'
						)
					");				
				}
				
				$message = "";
				$message .= "Mit folgendem Link kannst du deinen Account verifizieren:<br />";
				$message .= "<a href='http://" . $_SERVER['SERVER_NAME'] . "/verify.php?email=" . $_POST['email'] . "'>Account verifizieren</a><br /><br />";
				$message .= "Falls der Link nicht funktioniert, öffnen Sie folgende URL manuell:<br />";
				$message .= "http://" . $_SERVER['SERVER_NAME'] . "/verify.php?email=" . $_POST['email'];
				$mailsender = new MailSender;
				$mailsender->sendMail($_POST['email'], "noreply@" . $_SERVER['SERVER_NAME'], "Verifiziere deinen Account", $message);
				
			}
		}
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Registrieren</title>
		<meta http-equiv="content-type" content="text/html" charset="utf-8">
		<meta name="viewport" content="width=750px">
		<meta name="robots" content="noindex,nofollow">
		<link rel="stylesheet" href="/css/style.css" type="text/css" />
		<link rel="SHORTCUT ICON" href="/img/icon.png">
	</head>
	<body style="text-align: center;">
		<h1>Registrieren</h1>
		<form method="post" action="registrieren.php">
			Email: <input class="modern" type="email" name="email" size="28" placeholder="Email-Adresse" style="display: inline;" required /><br />
			Passwort: <input class="modern" type="password" name="password1" size="20" placeholder="Passwort eingeben" style="display: inline;" />
			<input class="modern" type="password" name="password2" size="20" placeholder="Passwort wiederholen" style="display: inline;" /><br />
			<input class="modern" type="submit" name="submit" value="Registrierung abschließen" style="display: inline;" />
		</form>
		<?php include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/unten.php"); ?>
	</body>
</html>