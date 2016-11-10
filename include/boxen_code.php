<?php
	$seitenaufruf_nicht_speichern = true;
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/user.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/seitenaufruf.php");

	$user = new User();
	$view = new Seitenaufruf();
	$view->need($view->ANMELDUNGERFORDERLICH);
	$view->check();

	$ergebnis = mysql_query("
		SELECT * FROM startpage_boxen
		WHERE user = '" . $user->id . "'
		AND text LIKE '%" . $_GET['search'] . "%'
		ORDER BY userboxid
	 ");

	if(mysql_num_rows($ergebnis) > 0){
		while($row = mysql_fetch_object($ergebnis))
		{
			?>Create_Box("#main", <?php echo $row->userboxid; ?>, "<?php echo $user->style; ?>", "<?php echo $row->link; ?>", "<?php echo urldecode($row->text); ?>", <?php echo $user->boxsize; ?>, "<?php echo $row->forecolor; ?>", "<?php echo $row->backcolor; ?>", false);<?php
			echo "\n\t\t\t";
		}
		echo "\n";
	} else {
		$worte = explode(" ", $_GET['search']);
		echo count($worte);
	}

	if($_GET['search'] == ""){
		echo "Fill_The_Rest('#main', " . $user->boxsize . ", 'qr');";
	}
?>
