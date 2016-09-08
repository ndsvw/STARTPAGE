<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/user.php"); 
?>

<div id="unten">
	<ul id="bottom_list">
		<li><a href="/index.php">Home</a></li>
		<?php 
		$user = new User();
		if($user->logged_in){
			?><li><a href="#" id="show_popup_settings">Einstellungen</a></li><?php
			?><li><a href="/logout.php">Logout</a></li><?php
		}
		?>
		<li><a href="impressum/index.php">Impressum</a></li>
		<li><i><?php echo date ("d.m.Y", filemtime($_SERVER["DOCUMENT_ROOT"])); ?> um <?php echo date ("H:i:s", filemtime($_SERVER["DOCUMENT_ROOT"])); ?></i></li>
	</ul>
	<div id="edit_area"><img src="/img/ic_edit_black_48dp_2x.png" /></div>
	<div id="delete_area"><img src="/img/ic_delete_black_48dp_2x.png" /></div>
</div>