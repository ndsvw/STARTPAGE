<?php
	$anmeldung_erforderlich = true;
	$seitenaufruf_nicht_speichern = true;
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php"); 
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/check.php"); 

	$user = mysql_fetch_array(mysql_query("
		SELECT user.id
		FROM user, sessions
		WHERE sessions.user_id = user.id
		AND sessions.session_code = '" . $_COOKIE['code'] . "' 
	"));

	if($_GET['json'] == "usersuchen"){
		$result = mysql_query("
			SELECT desktop_user_suchen.id, desktop_suchen.inputtext, desktop_suchen.submittext, desktop_suchen.method, desktop_suchen.link, desktop_suchen.name 
			FROM desktop_suchen, desktop_user_suchen
			WHERE desktop_suchen.id = desktop_user_suchen.such_id
			AND desktop_user_suchen.user_id = '" . $user['id'] . "'
		");

		$json_response = array();
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$row_array['id'] = $row['id'];
			$row_array['inputtext'] = $row['inputtext'];
			$row_array['submittext'] = $row['submittext'];
			$row_array['method'] = $row['method'];
			$row_array['link'] = $row['link'];
			$row_array['name'] = $row['name'];
			array_push($json_response,$row_array);
		}
		echo json_encode($json_response); 
	} else if($_GET['json'] == "userdaten"){		 
		$result = mysql_query("
			SELECT * 
			FROM user, sessions
			WHERE sessions.user_id = user.id
			AND sessions.session_code = '" . $_COOKIE['code'] . "' 
		");

		$json_response = array();

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$row_array['boxsize'] = $row['boxsize'];
			$row_array['zeilen'] = $row['zeilen'];
			$row_array['spalten'] = $row['spalten'];
			$row_array['m_boxsize'] = $row['m_boxsize'];
			$row_array['m_zeilen'] = $row['m_zeilen'];
			$row_array['m_spalten'] = $row['m_spalten'];
			array_push($json_response,$row_array);
		}
		echo json_encode($json_response); 	
	}
?>