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
?>

<h2>Verwendete Suchen</h2>
<?php
$suchen = mysql_query("
	SELECT startpage_suchen.inputtext
	FROM startpage_suchen, startpage_user_suchen
	WHERE startpage_suchen.id = startpage_user_suchen.such_id
	AND startpage_user_suchen.user_id = '" . $user["id"] . "' 
");
while($row = mysql_fetch_array($suchen))
{
	echo urldecode($row[0]) . "<br />";
}
?>			
<hr />
<h2>Neue Suche hinzufügen</h2>
<fieldset>
	<label>Abkürzung</label>
	<input class="modern" type="text" id="neue_suche_shortcut" placeholder="Abkürzung" size="9" maxlength="5" />
	<label>Eingabe-Platzhalter</label>
	<input class="modern" type="text" id="neue_suche_placeholder" placeholder="Eingabe-Platzhalter" size="30" />
	<label>Button-Text</label>
	<input class="modern" type="text" id="neue_suche_buttontext" placeholder="Button-Text" size="30" />
	<label>Link</label>
	<input class="modern" type="text" id="neue_suche_link" placeholder="Link" size="30" />
	<label>Name-Attribut</label>
	<input class="modern" type="text" id="neue_suche_name" placeholder="Name-Attribut" size="30" />
	<label>Methode: Get</label>
	<input class="modern" type="radio" id="neue_suche_method_get" name="neue_suche_method" value="get" checked="checked"/>
	<label>Methode: Post</label>
	<input type="radio" id="neue_suche_method_post" name="neue_suche_method" value="post" />
	<label>Click-Link</label>
	<input class="modern" type="text" id="neue_suche_clicklink" placeholder="Click-Link" size="30" />
</fieldset>