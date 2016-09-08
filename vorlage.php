<?php 
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php"); 
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/seitenaufruf.php"); 

	$user = new User();
	$view = new Seitenaufruf();
	$view->need($view->ANMELDUNGERFORDERLICH); // $view->ANMELDUNGERFORDERLICH || $view->SPEZIALRECHTE || $view->ADMINRECHTE
	$view->check();
	$view->save_view(); // or not
?>
<!DOCTYPE html>
<html>
	<head>
		<title>aaaaaaaaaaaaaa</title>
		<meta http-equiv="content-type" content="text/html" charset="utf-8">
		<meta name="viewport" content="width=750px">
		<meta name="robots" content="noindex,nofollow">
		<link rel="stylesheet" href="/css/style.css" type="text/css" />
		<link rel="SHORTCUT ICON" href="/img/icon.png">
	</head>
	<body>
		<h1></h1>
		<?php include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/unten.php"); ?>
	</body>
</html>