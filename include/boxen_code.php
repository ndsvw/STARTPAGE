<?php
	$seitenaufruf_nicht_speichern = true;
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php"); 
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/check.php"); 
	include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/user.php"); 

	$user = new User();
	$ergebnis = mysql_query("
		SELECT * FROM startpage_boxen 
		WHERE user = '" . $user->id . "'
		AND text LIKE '%" . $_GET['search'] . "%'
		ORDER BY userboxid
	 ");
	if(mysql_num_rows($ergebnis) > 0){
		while($row = mysql_fetch_object($ergebnis))
		{
			?>Create_Box("#main", <?php echo $row->userboxid; ?>, "<?php echo $user->style; ?>", "<?php echo urldecode($row->link); ?>", "<?php echo urldecode($row->text); ?>", <?php echo $user->boxsize; ?>, "<?php echo $row->forecolor; ?>", "<?php echo $row->backcolor; ?>");<?php				
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