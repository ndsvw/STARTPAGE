<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/seitenaufruf.php");

	$user = new User();
	$view = new Seitenaufruf();
	$view->need($view->ANMELDUNGERFORDERLICH);
	$view->check();

	if(isset($_COOKIE['code']) && isset($_GET['action']))
	{
		$userId = mysql_fetch_array(mysql_query("
			SELECT
				id
			FROM
				user,
				sessions
			WHERE
				sessions.user_id = user.id
			AND
				sessions.session_code = '" . mysql_real_escape_string($_COOKIE['code']) . "'
		"))[0];


		// -- -------------- -- //
		// -- Box hinzufügen -- //
		// -- -------------- -- //

		if(isset($_GET['boxtext']) && isset($_GET['boxlink']) && isset($_GET['boxforecolor']) && isset($_GET['boxbackcolor']) && $_GET['action'] == "box_hinzufuegen")
		{
			$query = "
				SELECT
					(userboxid + 1)
				FROM
					startpage_boxen
				WHERE
					user = '" . $userId . "'
				AND
					userboxid = (
						SELECT
							MAX(userboxid)
						FROM
							startpage_boxen
						WHERE
							user = '" . $userId . "'
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
					'" . mysql_real_escape_string(urlencode($_GET['boxtext'])) . "',
					'" . mysql_real_escape_string(urlencode($_GET['boxlink'])) . "',
					'" . mysql_real_escape_string(urlencode($_GET['boxforecolor'])) . "',
					'" . mysql_real_escape_string(urlencode($_GET['boxbackcolor'])) . "',
					'" . $userId . "',
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
					text = '" . mysql_real_escape_string(urlencode($_GET['boxtext'])) . "',
					link = '" . mysql_real_escape_string(urlencode($_GET['boxlink'])) . "',
					forecolor = '" . mysql_real_escape_string(urlencode($_GET['boxforecolor'])) . "',
					backcolor = '" . mysql_real_escape_string(urlencode($_GET['boxbackcolor'])) . "'
				WHERE
					user = '" . $userId . "'
				AND
					userboxid = '" . mysql_real_escape_string($_GET['userboxid']) . "'
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
					user = '" . $userId . "'
				AND
					userboxid = '" . mysql_real_escape_string($_GET['userboxid']) . "'
			");

			mysql_query("
				UPDATE
					startpage_boxen
				SET
					userboxid = (userboxid - 1)
				WHERE
					user = '" . $userId . "'
				AND
					userboxid > '" . mysql_real_escape_string($_GET['userboxid']) . "'
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
					userboxid = '" . mysql_real_escape_string($_GET['userboxid1']) . "'
			");

			mysql_query("
				UPDATE
					startpage_boxen
				SET
					userboxid = '" . mysql_real_escape_string($_GET['userboxid1']) .  "'
				WHERE
					userboxid = '" . mysql_real_escape_string($_GET['userboxid2']) . "'
			");

			mysql_query("
				UPDATE
					startpage_boxen
				SET
					userboxid = '" . mysql_real_escape_string($_GET['userboxid2']) .  "'
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
					boxsize = '" . mysql_real_escape_string($_GET['boxsize']) . "',
					backcolor = '" . mysql_real_escape_string(urlencode($_GET['bodybackcolor'])) . "',
					style = '" . mysql_real_escape_string($_GET['boxstyle']) . "'
				WHERE
					id = '" . $userId . "'
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
					'" . mysql_real_escape_string(urlencode($_GET['shortcut'])) . "',
					'" . mysql_real_escape_string(urlencode($_GET['placeholder'])) . "',
					'" . mysql_real_escape_string(urlencode($_GET['buttontext'])) . "',
					'" . mysql_real_escape_string(urlencode($_GET['link'])) . "',
					'" . mysql_real_escape_string(urlencode($_GET['method'])) . "',
					'" . mysql_real_escape_string(urlencode($_GET['name'])) . "',
					'" . mysql_real_escape_string(urlencode($_GET['clicklink'])) . "'
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
					'" . $userId . "'
				)
			");
		}
	}
?>
