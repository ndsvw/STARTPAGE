<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php"); 
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/user.php"); 
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/seitenaufruf.php"); 

	$user = new User();
	$view = new Seitenaufruf();
	$view->save_view();

	if(isset($_POST['useremail']) && isset($_POST['userpassword']))
	{
		$user = new User();
		$user->login($_POST['useremail'], $_POST['userpassword']);
		header("Location: /index.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
		<meta http-equiv="content-type" content="text/html" charset="utf-8">
		<meta name="viewport" content="width=750px">
		<meta name="robots" content="noindex,nofollow">
		<link rel="stylesheet" href="/css/style.css" type="text/css" />
		<link rel="SHORTCUT ICON" href="/img/icon.png">
	</head>
	<body style="text-align: center;">
		<h1>Login</h1>
		<?php
			if(isset($_GET["infotext"])){
				echo "<p style='border: 1px dotted #888888; padding: 7px;'><b>" . $_GET["infotext"] . "</b></p>";
			}
		?>
		<form method="post" action="login.php">
			<input class="modern" type="email" name="useremail" size="50" placeholder="E-Mail-Adresse" autocomplete="off" aria-autocomplete="true" aria-expanded="false" autofocus />
			<input class="modern" type="password" name="userpassword" size="50" placeholder="Passwort" autocomplete="off" aria-autocomplete="true" aria-expanded="false" autofocus />
			&nbsp;<input class="modern" type="submit" value="Anmelden" />
		</form><br />
		oder: &nbsp; <a href="registrieren.php">Registrieren</a> &nbsp; <a href="#" onclick="alert('Pech gehabt!');">Passwort vergessen</a><br />
		<noscript>Zur optimalen Nutzung der Seite muss Javascript aktiviert sein</noscript>
		<?php require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/unten.php"); ?>
	</body>
</html>