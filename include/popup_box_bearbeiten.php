<?php
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php"); 
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/user.php"); 
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/seitenaufruf.php"); 

	$user = new User();
	$view = new Seitenaufruf();
	$view->need($view->ANMELDUNGERFORDERLICH);
	$view->check();

	$sql = mysql_query("
		SELECT
			text,
			link,
			forecolor,
			backcolor
		FROM 
			startpage_boxen 
		WHERE 
			user = '" . $user->id . "' 
		AND
			userboxid = '" . mysql_real_escape_string($_GET['userboxid']) . "'
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