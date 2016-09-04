<?php 
$seitenaufruf_nicht_speichern = true;
include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php"); 
include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/check.php"); 

if(isset($_COOKIE['code']) && isset($_GET['action']))
{
	$user = mysql_fetch_array(mysql_query("
		SELECT * 
		FROM user, sessions
		WHERE sessions.user_id = user.id
		AND sessions.session_code = '" . $_COOKIE['code'] . "' 
	"));
	
	
	// -- -------------- -- //
	// -- Box hinzufügen -- //
	// -- -------------- -- //
	
	if(isset($_GET['boxtext']) && isset($_GET['boxlink']) && isset($_GET['boxforecolor']) && isset($_GET['boxbackcolor']) && $_GET['action'] == "box_hinzufuegen")
	{
		$query = "		
			SELECT (userboxid + 1)
			FROM startpage_boxen
			WHERE user = '" . $user["id"] . "'
			AND userboxid = (
				SELECT MAX(userboxid)
				FROM startpage_boxen
				WHERE user = '" . $user["id"] . "'
			)";
		$neue_userboxid = mysql_fetch_array(mysql_query($query))[0];
		mysql_query("
			INSERT INTO startpage_boxen (
				text, 
				link, 
				forecolor, 
				backcolor, 
				user, 
				userboxid
			)VALUES(
				'" . urlencode($_GET['boxtext']) . "', 
				'" . urlencode($_GET['boxlink']) . "', 
				'" . urlencode($_GET['boxforecolor']) . "', 
				'" . urlencode($_GET['boxbackcolor']) . "',
				'" . $user["id"] . "',
				'" . $neue_userboxid . "'
			)
		");
	}	
		
	
	
	// -- -------------- -- //
	// -- Box bearbeiten -- //
	// -- -------------- -- //
	
	if(isset($_GET['userboxid']) && isset($_GET['boxtext']) && isset($_GET['boxlink']) && isset($_GET['boxforecolor']) && isset($_GET['boxbackcolor']) && $_GET['action'] == "box_speichern")
	{
		mysql_query("
			UPDATE 
				startpage_boxen 
			SET 
				text = '" . urlencode($_GET['boxtext']) . "', 
				link = '" . urlencode($_GET['boxlink']) . "', 
				forecolor = '" . urlencode($_GET['boxforecolor']) . "', 
				backcolor = '" . urlencode($_GET['boxbackcolor']) . "' 
			WHERE 
				user = '" . $user["id"] . "' 
			AND 
				userboxid = '" . $_GET['userboxid'] . "' 
		");
	}
	
	

	// -- ----------- -- //
	// -- Box löschen -- //
	// -- ----------- -- //
	
	if(isset($_GET['userboxid']) && $_GET['action'] == "box_loeschen")
	{
		mysql_query("
			DELETE FROM 
				startpage_boxen 
			WHERE 
				user = '" . $user["id"] . "' 
			AND 
				userboxid = '" . $_GET['userboxid'] . "' 
		");
		
		mysql_query("
			UPDATE
				startpage_boxen
			SET
				userboxid = (userboxid - 1)
			WHERE
				user = '" . $user["id"] . "' 
			AND
				userboxid > '" . $_GET['userboxid'] . "' 
		");
	}
	
	
	
	// -- -------------- -- //
	// -- Boxen tauschen -- //
	// -- -------------- -- //
	
	if($_GET['action'] == "boxen_tauschen" && isset($_GET['userboxid1']) && isset($_GET['userboxid2']))
	{
		mysql_query("
			UPDATE
				startpage_boxen
			SET
				userboxid = '-1'
			WHERE
				userboxid = '" . $_GET['userboxid1'] . "' 
		");
			
		mysql_query("
			UPDATE
				startpage_boxen
			SET
				userboxid = '" . $_GET['userboxid1'] .  "'
			WHERE
				userboxid = '" . $_GET['userboxid2'] . "' 
		");
			
		mysql_query("
			UPDATE
				startpage_boxen
			SET
				userboxid = '" . $_GET['userboxid2'] .  "'
			WHERE
				userboxid = '-1' 
		");
	}
	
	
	
	// -- ----------------- -- //
	// -- Layout bearbeiten -- //
	// -- ----------------- -- //
	
	if(isset($_GET['boxsize']) && isset($_GET['bodybackcolor']) && isset($_GET['boxstyle']) && $_GET['action'] == "layout_bearbeiten")
	{
		mysql_query("
			UPDATE 
				user 
			SET 
				boxsize = '" . $_GET['boxsize'] . "', 
				backcolor = '" . urlencode($_GET['bodybackcolor']) . "', 
				style = '" . $_GET['boxstyle'] . "' 
			WHERE 
				id = '" . $user["id"] . "'
		");
	}
	
	
	
	// -- ---------------- -- //
	// -- Suche hinzufügen -- //
	// -- ---------------- -- //
	
	if(isset($_GET['shortcut']) && isset($_GET['placeholder']) && isset($_GET['buttontext']) && isset($_GET['link']) && isset($_GET['name']) && isset($_GET['method']) && isset($_GET['clicklink']) && $_GET['action'] == "neue_suche_speichern")
	{
		mysql_query("
			INSERT INTO startpage_suchen (
				shortcut, 
				inputtext, 
				submittext, 
				link, 
				method, 
				name, 
				clicklink
			) VALUES (
				'" . urlencode($_GET['shortcut']) . "', 
				'" . urlencode($_GET['placeholder']) . "', 
				'" . urlencode($_GET['buttontext']) . "', 
				'" . urlencode($_GET['link']) . "', 
				'" . urlencode($_GET['method']) . "', 
				'" . urlencode($_GET['name']) . "', 
				'" . urlencode($_GET['clicklink']) . "'
			)
		");
		$sql = mysql_query("
			SELECT 
				MAX(id)
			FROM
				startpage_suchen
		");				 
		mysql_query("
			INSERT INTO startpage_user_suchen (
				such_id, 
				user_id
			) VALUES (
				'" . mysql_fetch_array($sql)[0] . "', 
				'" . $user["id"] . "'
			)
		");
	}
}
?>