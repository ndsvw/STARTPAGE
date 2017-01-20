<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/user.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/seitenaufruf.php");

	$user = new User();
	$view = new Seitenaufruf();
	$view->need($view->ANMELDUNGERFORDERLICH);
	$view->check();

	if($_GET['json'] == "userdata"){
		$suchenarray = array();
		$boxenarray = array();

		//get Suchen-Daten
		$arr = array();
		$result = mysql_query("
			SELECT
				startpage_suchen.inputtext,
				startpage_suchen.method,
				startpage_suchen.shortcut,
				startpage_suchen.link,
				startpage_suchen.name
			FROM
				startpage_suchen,
				startpage_user_suchen
			WHERE
				startpage_suchen.id = startpage_user_suchen.such_id
			AND
				startpage_user_suchen.user_id = '" . $user->id . "'
		");

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$arr[0] = $row['inputtext'];
			$arr[1] = $row['method'];
			$arr[2] = $row['shortcut'];
			$arr[3] = $row['link'];
			$arr[4] = $row['name'];
			$suchenarray[count($suchenarray)] = $arr;
		}

		//get Boxen-Daten
		$arr = array();
		$result = mysql_query("
			SELECT
				text, link, forecolor, backcolor
			FROM
				startpage_boxen
			WHERE
				user = '" . $user->id . "'
			ORDER BY
				userboxid
		");

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$arr[0] = $row['text'];
			$arr[1] = $row['link'];
			$arr[2] = $row['forecolor'];
			$arr[3] = $row['backcolor'];
			$boxenarray[count($boxenarray)] = $arr;
		}

		$userdata = new Userdata();
		$userdata->suchen = $suchenarray;
		$userdata->boxen = $boxenarray;
		echo json_encode($userdata);
	}

	class Userdata{
		public $suchen;
		public $boxen;
	}
?>
