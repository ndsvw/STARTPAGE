<?php
$ausgabe1 = "";
$ausgabe2 = "";
if(isset($_POST['zeichen'])){
	for($i=0; $i<strlen($_POST['zeichen']); $i++)
	{
		$zeichen = substr($_POST['zeichen'], $i, 1);
		$hammingcode = ord($zeichen);
		$hammingcode = decbin($hammingcode);
		if(strlen($hammingcode) == 7){
			$hammingcode = "0" . $hammingcode;
		}
		
		$bit1 = substr($hammingcode, 0, -7);
		$bit2 = substr($hammingcode, 1, -6);
		$bit3 = substr($hammingcode, 2, -5);
		$bit4 = substr($hammingcode, 3, -4);
		$bit5 = substr($hammingcode, 4, -3);
		$bit6 = substr($hammingcode, 5, -2);
		$bit7 = substr($hammingcode, 6, -1);
		$bit8 = substr($hammingcode, -1);
		
		$oktal1bit1 = ($bit1 + $bit2 + $bit4) % 2;
		$oktal1bit2 = ($bit1 + $bit3 + $bit4) % 2;
		$oktal1bit3 = $bit1;
		$oktal1bit4 = ($bit2 + $bit3 + $bit4) % 2;
		$oktal1bit5 = $bit2;
		$oktal1bit6 = $bit3;
		$oktal1bit7 = $bit4;
		$oktal1[$i] = $oktal1bit1 . $oktal1bit2 . $oktal1bit3 . $oktal1bit4 . $oktal1bit5 . $oktal1bit6 . $oktal1bit7;
		
		$oktal2bit1 = ($bit5 + $bit6 + $bit8) % 2;
		$oktal2bit2 = ($bit5 + $bit7 + $bit8) % 2;
		$oktal2bit3 = $bit5;
		$oktal2bit4 = ($bit6 + $bit7 + $bit8) % 2;
		$oktal2bit5 = $bit6;
		$oktal2bit6 = $bit7;
		$oktal2bit7 = $bit8;
		$oktal2[$i] = $oktal2bit1 . $oktal2bit2 . $oktal2bit3 . $oktal2bit4 . $oktal2bit5 . $oktal2bit6 . $oktal2bit7;	
		
		$ausgabe1 .= $zeichen . " -> " . $oktal1[$i] . " " . $oktal2[$i] . "<br />";
		$ausgabe2 .= $oktal1[$i] . " " . $oktal2[$i];
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>ALEX - Hamming-Code-Generator</title>
		<meta http-equiv="content-type" content="text/html" charset="utf-8">
		<link rel="stylesheet" href="../css/style.css" type="text/css" />
		<link rel="SHORTCUT ICON" href="/img/icon.png">
	</head>
	<body>
		<h1>Hamming-Code-Generator</h1>
		<form method="post" action="hammingcode.php" style="text-align: center;">
			<p><input class="modern" type="text" size="20" maxlength="20" value="<?php echo $_POST['zeichen']; ?>" name="zeichen" /></p>
			<p><input class="modern" type="submit" value="Generieren" /></p>
			<p><b style="font-size: 200%;"><?php echo $ausgabe1; ?></b></p><br />
			<p><b style="font-size: 200%; color: blue;"><?php echo $ausgabe2; ?></b></p>
		</form>
		<?php include(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/unten.php"); ?>
	</body>
</html>