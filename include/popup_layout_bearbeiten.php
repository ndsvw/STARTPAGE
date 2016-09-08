<?php	
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php"); 
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/user.php"); 
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/seitenaufruf.php"); 

	$user = new User();
	$view = new Seitenaufruf();
	$view->need($view->ANMELDUNGERFORDERLICH);
	$view->check();
?>

<fieldset>
	<label>Boxengröße (in px): </label>
	<input type="text" id="new_boxsize" size="6" value="<?php echo $user->boxsize; ?>" class="text ui-widget-content ui-corner-all" />
	<label>Hintergrundfarbe: </label>
	<input type="text" id="bodybackcolor" size="6" maxlength="6" value="<?php echo $user->backcolor; ?>" class="text ui-widget-content ui-corner-all" />
	<label>Style:</label>
	<input type="radio" value="qr" id="boxstyleqr" name="style" <?php if($user->style == "qr"){ echo"checked='checked'"; } ?>/>QR-Code 
	<input type="radio" value="farbe" id="boxstylefarbe" name="style" <?php if($user->style == "color"){ echo"checked='checked'"; } ?>/>Farbe
</fieldset>