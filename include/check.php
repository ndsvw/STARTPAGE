<?php
	if($anmeldung_erforderlich == true){
		if(isset($_COOKIE['code']))
		{
			$user_tmp = mysql_fetch_array(mysql_query("
				SELECT COUNT(user.id), user.accounttyp, user.id
				FROM user, sessions 
				WHERE sessions.user_id = user.id 
				AND sessions.session_code = '" . $_COOKIE['code'] . "' 
			"));
			if($user_tmp[0] == 0){
				$infotext = urlencode("Zugriff verweigert. Anmeldung erforderlich!");
				header("Location: /login.php?infotext=" . $infotext);
			}
			else{
				if($seitenaufruf_nicht_speichern != true){
					if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
					{
						$client_ip = $_SERVER['REMOTE_ADDR'];
					}
					else 
					{
						$client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					}

					mysql_query("INSERT INTO zugriffe (ip, browser, seite, user_id) VALUES ('" . $client_ip . "', '" . $_SERVER['HTTP_USER_AGENT'] . "', '" . $_SERVER["PHP_SELF"] . "', '" . $user_tmp[2] . "')");
				}
				if($adminrechte_erforderlich == true){
					if($user_tmp[1] != "admin"){
						$infotext = urlencode("Zugriff verweigert. Admin-Rechte erforderlich!");
						header("Location: /login.php?infotext=" . $infotext);
					}
				}
				elseif($familienrechte_erforderlich == true){
					if($user_tmp[1] != "familie" && $user_tmp[1] != "admin"){
						$infotext = urlencode("Zugriff verweigert. Familien-Rechte erforderlich!");
						header("Location: /login.php?infotext=" . $infotext);
					}					
				}
			}
		}
		else{
			if($seitenaufruf_nicht_speichern != true){
				if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
				{
					$client_ip = $_SERVER['REMOTE_ADDR'];
				}
				else 
				{
					$client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				}

				mysql_query("INSERT INTO zugriffe (ip, browser, seite) VALUES ('" . $client_ip . "', '" . $_SERVER['HTTP_USER_AGENT'] . "', '" . $_SERVER["PHP_SELF"] . "')");
			}

			$infotext = urlencode("Zugriff verweigert. Anmeldung erforderlich!");
			header("Location: /login.php?infotext=" . $infotext);
		}
	}
?>