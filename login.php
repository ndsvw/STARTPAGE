<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/user.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/seitenaufruf.php");

	$user = new User();
	$view = new Seitenaufruf();
	$view->save_view();

	if($user->logged_in){
		header("Location: /index.php");
	} elseif(isset($_POST['useremail']) && isset($_POST['userpassword'])){
		//login
		$user = new User();
		echo $user->login($_POST['useremail'], $_POST['userpassword']);
		exit();
	} elseif(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password2'])){
		//registrieren
		echo User::registrieren($_POST['email'], $_POST['password'], $_POST['password2']);
		exit();
	}
?>
<!DOCTYPE html>
<html style="width: 100%; height: 100%; background: url(/img/bsp.png); background-position: center;  background-repeat: no-repeat; background-size: 100% 100%;">
	<head>
		<title>Login</title>
		<meta http-equiv="content-type" content="text/html" charset="utf-8">
		<meta name="viewport" content="width=750px">
		<meta name="robots" content="noindex,nofollow">
		<link rel="stylesheet" href="/css/style.css" type="text/css" />
		<link href="https://fonts.googleapis.com/css?family=Baloo+Paaji" rel="stylesheet">
		<link rel="SHORTCUT ICON" href="/img/icon.png">
		<link rel="stylesheet" href="/js/vex/dist/css/vex.css" />
		<link rel="stylesheet" href="/js/vex/dist/css/vex-theme-os.css" />
		<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
		<script src="/js/vex/dist/js/vex.combined.min.js"></script>
	</head>
	<body style="text-align: center;">
		<div style="width: 700px; position: relative; top: 100px; border-radius: 7px; margin: 0 auto; padding: 0px 15px 15px 10px; border: 1px dotted black; background-color: rgba(70, 70, 70, 0.955);">
			<h1 style="font-family: 'Baloo Paaji', cursive; color: #FFFFFF; font-size: 4.5em; text-decoration: none; margin-top: 20px;">STARTPAGE</h1>
			<noscript><p style="color: red;">Zur Nutzung der Seite muss Javascript aktiviert sein</p></noscript>
			<p style="margin-top: 20px;">
				<button id="btn_login" class="modern">Zum Login</button>
				<button id="btn_reg" class="modern" style="margin-left: 6px;">Registrieren</button><br />
				<p id="pwForgot" style="color: #EEEEFF; text-decoration: none; cursor: pointer;">Passwort vergessen</p>
			</p>
			<p style="margin-top: 55px;">
				<a style="color: #EEEEFF; text-decoration: none; font-size: 1.5em;" href="https://github.com/ndsvw/STARTPAGE">
					<img src="/img/github.png" height="50px"/><br />
					SoruceCode auf GitHub
				</a>
			</p>
		</div>
		<script>
			$(document).ready(function() {
				var res = "<?php echo isset($_GET['check']) ? $_GET['check'] : ''; ?>";
				vex.defaultOptions.className = 'vex-theme-os';
				vex.dialog.defaultOptions.showCloseButton = true;

				$("#btn_login").click(function(){
					vex.dialog.open({
						message: 'Email-Adresse und Passwort eingeben:',
						input: [
							'<input name="useremail" type="email" placeholder="Email-Adresse" required />',
							'<input name="userpassword" type="password" placeholder="Passwort" required />'
						].join(''),
						buttons: [
							$.extend({}, vex.dialog.buttons.YES, { text: 'Login' }),
							$.extend({}, vex.dialog.buttons.NO, { text: 'Abbrechen' })
						],
						callback: function (data) {
							if (!data) {
								console.log('Cancelled');
							} else {
								console.log('Email', data.useremail, 'Password', data.userpassword);
								$.post("login.php", data, function(res){
									window.location.href = "login.php?check=" + res;
								});
							}
						}
					});
				});
				$("#btn_reg").click(function(){
					vex.dialog.open({
						message: 'Registrieren',
						input: [
							'<input name="email" type="email" placeholder="Email-Adresse" required />',
							'<input name="password" type="password" placeholder="Passwort" required />',
							'<input name="password2" type="password" placeholder="Passwort wiederholen" required />'
						].join(''),
						buttons: [
							$.extend({}, vex.dialog.buttons.YES, { text: 'Registrieren' }),
							$.extend({}, vex.dialog.buttons.NO, { text: 'Abbrechen' })
						],
						callback: function (data) {
							if (!data) {
								console.log('Cancelled');
							} else {
								console.log('Email', data.email, 'Password', data.password);
								$.post("login.php", data, function(res){
									window.location.href = "login.php?check=" + res;
								});
							}
						}
					});
				});

				$("#pwForgot").click(function(){
					vex.dialog.alert({
						message: 'Pech gehabt!'
					});
				});

				switch(res){
					case "loginWrong":
						vex.dialog.alert({
							message: 'Benutzername oder Passort ist falsch!'
						});
						break;
					case "notVerified":
						vex.dialog.open({
							message: 'Der Account ist nicht verifiziert. Nutze den zugesendeten Bestätigungslink! Falls du keine Email bekommen hast, gebe hier deine Email-Adresse ein und klicke auf Verifizieren!',
							input: [
								'<input name="veriEmail" type="email" placeholder="Email-Adresse" required />'
							].join(''),
							buttons: [
								$.extend({}, vex.dialog.buttons.YES, { text: 'Verifizieren' }),
								$.extend({}, vex.dialog.buttons.NO, { text: 'Abbrechen' })
							],
							callback: function (data) {
								if (!data) {
									console.log('Cancelled');
								} else {
									$.post("verify.php", { resend: "true", veriEmail: data.veriEmail }, function(res){
										if(res === "success"){
											vex.dialog.alert({
												message: 'Eine neue Mail mit dem Bestätigungslink wurde gesendet!'
											});
										}
									});
								}
							}
						});
						break;
					case "verified":
						vex.dialog.alert({
							message: 'Dein Account ist nun verifiziert. Du kannst dich nun einloggen!'
						});
						break;
					case "loggedOut":
						vex.dialog.alert({
							message: 'Logout erfolgreich!'
						});
						break;
					case "userExistsAlready":
						vex.dialog.alert({
							message: 'Es existiert bereits ein Account mit dieser Email-Adresse!'
						});
						break;
					case "differentPWs":
						vex.dialog.alert({
							message: 'Die eingegebenen Passwörter stimmen nicht miteinander überein!'
						});
						break;
					case "regSuccess":
						vex.dialog.alert({
							message: 'Registrierung erfolgreich! Eine Email mit dem Bestätigungslink wurde an deine Email-Adresse gesendet.'
						});
						break;
					case "loginNeeded":
						vex.dialog.alert({
							message: 'Anmeldung erforderlich.'
						});
						break;
					case "noPermission":
						vex.dialog.alert({
							message: 'Du hast nicht die nötigen Rechte, um auf diesen Bereich zugreifen zu können.'
						});
						break;
				}
			});
		 </script>
		<?php require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/unten.php"); ?>
	</body>
</html>
