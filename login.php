<?php
include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php"); 
include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/check.php"); 

function rand_string($lng)
{
	mt_srand(crc32(microtime()));
	$buchstaben = "abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
	$str_lng = strlen($buchstaben)-1;
	$rand= "";
	for($i=0;$i<$lng;$i++){
		$rand.= $buchstaben{mt_rand(0, $str_lng)};
	}		
	return $rand;
} 

if(isset($_POST['useremail']) && isset($_POST['userpassword']))
{
	$user = mysql_fetch_array(mysql_query("SELECT COUNT(*), id, verified FROM user WHERE mail = '" . $_POST['useremail'] . "' AND password = '" . md5($_POST['userpassword']) . "' "));
	if($user[0] > 0)
	{
		if($user[2] == '1'){
			$code = rand_string(64);
			setcookie("code", $code, time()+(60 * 60 * 24 * 365));
			mysql_query("INSERT INTO sessions (user_id, session_code) VALUES (" . $user[1] . ", '" . $code . "')");
			header("Location: /index.php");
		} else {
			echo "Dein Account ist nicht verifiziert. Bitte bestätige den Link in der bestätigungs-Email!";
		}
	} else {
		echo "Falsche Email-Adresse oder falches Passwort!";
	}
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
		<?php include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/unten.php"); ?>
	</body>
</html>