<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php"); 
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/user.php"); 
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/seitenaufruf.php"); 

	$user = new User();
	$view = new Seitenaufruf();
	$view->need($view->ANMELDUNGERFORDERLICH);
	$view->check();

	if($_GET['json'] == "usersuchen"){
		$result = mysql_query("
			SELECT 
				startpage_user_suchen.id, 
				startpage_suchen.inputtext, 
				startpage_suchen.submittext, 
				startpage_suchen.method, 
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
	}
?>