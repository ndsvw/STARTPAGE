<?php
	$anmeldung_erforderlich = true;
	$seitenaufruf_nicht_speichern = true;
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php"); 
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/check.php"); 

	$user = mysql_fetch_array(mysql_query("
		SELECT *
		FROM user, sessions
		WHERE sessions.user_id = user.id
		AND sessions.session_code = '" . $_COOKIE['code'] . "' 
	"));

	$sql = mysql_query("
		SELECT
			text,
			link,
			forecolor,
			backcolor
		FROM 
			desktop_boxen 
		WHERE 
			user = '" . $user["id"] . "' 
		AND
			userboxid = '" . $_GET['userboxid'] . "'
	");
	$sql = mysql_fetch_array($sql);
?>
<h2>Box bearbeiten</h2>
<p style="text-align: left">
	<label>Boxtext</label>
	<input class="modern" type="text" id="boxtext" maxlength="20" size="20" value="<?php echo urldecode($sql['text']); ?>" />
	<label>Boxtlink</label>
	<input class="modern" type="text" id="boxlink" maxlength="256" size="30" value="<?php echo urldecode($sql['link']); ?>" />
	<label>Farbe</label>
	<input class="modern" type="text" value="<?php echo $sql['forecolor']; ?>" id="boxforecolor" maxlength="6" size="6" />
	<label>Hintergrundfarbe</label>
	<input class="modern" type="text" value="<?php echo $sql['backcolor']; ?>" id="boxbackcolor" maxlength="6" size="6" />
	<input type="hidden" id="boxid" value="<?php echo $_GET['userboxid']; ?>" />
</p>