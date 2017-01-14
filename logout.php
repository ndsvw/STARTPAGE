<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/user.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/seitenaufruf.php");

	$user = new User();
	$view = new Seitenaufruf();
	$view->need($view->ANMELDUNGERFORDERLICH);
	$view->check();
	$view->save_view();
	$user->logout();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Logout</title>
		<meta http-equiv="content-type" content="text/html" charset="utf-8">
		<meta name="robots" content="noindex,nofollow">
		<link rel="stylesheet" href="/css/style.css" type="text/css" />
		<link rel="SHORTCUT ICON" href="/img/icon.png">
	</head>
	<body>
		<h1 style="text-align: center;">Logout erfolgreich</h1>
		<p style="text-align: center;">
			<a href="login.php">weiter</a>
		</p>
		<?php require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/unten.php"); ?>
	</body>
</html>
