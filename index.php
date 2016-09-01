<?php
	$anmeldung_erforderlich = true;
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php"); 
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/check.php"); 

	$user = mysql_fetch_array(mysql_query("
		SELECT *
		FROM user, sessions
		WHERE sessions.user_id = user.id
		AND sessions.session_code = '" . $_COOKIE['code'] . "' 
	"));

	$anzahl_suchboxen = mysql_fetch_array(mysql_query("
		SELECT COUNT(*)
		FROM user, desktop_user_suchen
		WHERE user.id = desktop_user_suchen.user_id
		AND desktop_user_suchen.user_id = '" . $user["id"] . "'
	"))[0];
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $user["titletext"]; ?></title>
		<meta http-equiv="content-type" content="text/html" charset="utf-8">
		<meta name="viewport" content="width=900px">
		<meta name="robots" content="noindex,nofollow">
		<link rel="stylesheet" href="/css/style.css" type="text/css" />
		<link rel="stylesheet" href="/css/jquery-ui.css" type="text/css" />
		<link rel="SHORTCUT ICON" href="/img/icon.png">
		<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw=" crossorigin="anonymous"></script>
		<script src="/js/desktop.js"></script>
		<script src="/js/boxen.js"></script>
		<script src="/js/popup.js"></script>
		<script>
			$(function() {
				$( "#dialog" ).dialog();
			});
		</script>
	</head>
	<body style="background-color: #<?php echo $user["backcolor"]; ?>">
		<!-- Hauptteil (erst Suchbox, dann QR/Farb-Boxen) -->
		<div id="all">
			<div id="searchbox" style="width: <?php echo (46 + 1 + 1 + 3) * $anzahl_suchboxen + 40 * 2; ?>px;">
				<?php
					$suchen = mysql_query("
						SELECT desktop_suchen.clicklink, desktop_user_suchen.id, desktop_suchen.shortcut
						FROM desktop_suchen, desktop_user_suchen
						WHERE desktop_suchen.id = desktop_user_suchen.such_id
						AND desktop_user_suchen.user_id = '" . $user['id'] . "'
					");
					while($row = mysql_fetch_array($suchen))
					{
						echo "<a href='" . urldecode($row[0]) . "'>";
						echo "<div class='suchen_div' data-suche='" . $row[1] . "' >" . $row[2] . "</div>";
						echo "</a>";
					}						
				?>
				<form id="search-form" action="https://eu4.startpage.com/do/search?nosteeraway=1&abp=1&cat=web&language=deutsch&prf=923be3e041a9875da96b0153b30c6ab7&cmd=process_search&engine0=v1all&lui=deutsch" method="post">
					<input class="modern" type="text" id="main_input" style="margin-left: 10px;" name="query" size="47" dir="auto" placeholder="Startpage-Suche (SSL)" autocomplete="off" aria-autocomplete="true" aria-expanded="false" autofocus /> 
					<input class="modern" type="submit" id="main_submit" value="Suchen"/>
				</form>
				
			</div>
			<div id="main"></div>
		</div>
		<script>
			Center_Parent("#main", <?php echo $user["boxsize"]; ?>);
			<?php
			$ergebnis = mysql_query("
				SELECT * FROM desktop_boxen 
				WHERE user = '" . $user["id"] . "'
				ORDER BY userboxid
			 ");
			while($row = mysql_fetch_object($ergebnis))
			{
				?>Create_Box("#main", <?php echo $row->userboxid; ?>, "<?php echo $user['style']; ?>", "<?php echo urldecode($row->link); ?>", "<?php echo urldecode($row->text); ?>", <?php echo $user["boxsize"]; ?>, "<?php echo $row->forecolor; ?>", "<?php echo $row->backcolor; ?>");<?php				
				echo "\n\t\t\t";						
			}
			echo "\n";
			?>
			Fill_The_Rest("#main", <?php echo $user["boxsize"]; ?>, "qr");
		</script>
		<?php include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/unten.php"); ?>
	</body>
</html>