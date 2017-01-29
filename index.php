<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/user.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/seitenaufruf.php");

	$user = new User();
	$view = new Seitenaufruf();
	$view->need($view->ANMELDUNGERFORDERLICH);
	$view->check();
	$view->save_view();
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $user->titletext; ?></title>
		<meta http-equiv="content-type" content="text/html" charset="utf-8">
		<meta name="viewport" content="width=900px">
		<meta name="robots" content="noindex,nofollow">
		<link href="https://fonts.googleapis.com/css?family=Baloo+Paaji" rel="stylesheet">
		<link rel="stylesheet" href="/css/style.css" type="text/css" />
		<link rel="SHORTCUT ICON" href="/img/icon.png">
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/black-tie/jquery-ui.css" type="text/css" />
		<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
		<script type="text/javascript" src="/js/tinyColorPicker/jqColorPicker.min.js"></script>
		<script type="text/javascript" src="/js/desktop.js"></script>
		<script type="text/javascript" src="/js/search.js"></script>
		<script type="text/javascript" src="/js/startpage.js"></script>
		<script type="text/javascript" src="/js/box.js"></script>
	</head>
	<body style="background-color: <?php echo urldecode($user->backcolor); ?>">
		<div id="all">
			<div id="searchbox">
				<form id="search-form" action="https://eu4.startpage.com/do/search?nosteeraway=1&abp=1&cat=web&language=deutsch&prf=923be3e041a9875da96b0153b30c6ab7&cmd=process_search&engine0=v1all&lui=deutsch" method="post">
					<input class="modern" type="text" id="main_input" style="margin-left: 10px;" name="query" size="47" dir="auto" placeholder="Startpage-Suche (SSL)" autocomplete="off" aria-autocomplete="true" aria-expanded="false" autofocus />
					<input class="modern" type="submit" id="main_submit" value="Suchen"/>
					<div id="inputDropDown"></div>
				</form>
			</div>
			<div id="main"></div>
		</div>
		<script>
			var startpage = new Startpage("#main", <?php echo $user->boxsize; ?>, "<?php echo $user->style; ?>", true);
			startpage.setShortLinksUsed(true);
			startpage.setModifiable(true);
			$.getJSON("/json.php?json=userdata", function( data ) {
				startpage.addBoxen(data.boxen);
				if(typeof(Storage) !== "undefined") {
				    if(localStorage.getItem("data") == null || localStorage.getItem("data") === "[]" || JSON.parse(localStorage.getItem("data"))[0] === "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHwAAAB8CAYAAACrHtS+AAAAUklEQVR4nO3BAQ0AAADCoPdPbQ8HFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAfBvwvAABVXx5TQAAAABJRU5ErkJggg=="){
						console.log("Local storage is available but no data or data with errors found!");
						$.when(startpage.visualize(startpage.boxen)).then(function(){
							storeData();
						});
					} else {
						startpage.useLocalStorageBase64QR(localStorage.getItem("data"));
						startpage.visualize(startpage.boxen);
					}
				} else {
				    console.log("Local Storage is not available!");
				}

				startpage.addSuchen(data.suchen);
				startpage.visualizeSuchen("#searchbox");

				$(".suchen_div").mouseover(function() {
					startpage.makeSucheAktive($(this).attr("data-suche"));
				});
			});
			function storeData(){
				var arr = [];
				for(i = 0; i < startpage.boxen.length; i++){
					$("body").append("<img style='display: hidden; width: 114px; height: 114px;' class='hiddenIMG' id='hiddenIMG" + i + "' src='/include/getQrCode.php?color=" + startpage.boxen[i].fcolor + "&bgcolor=" + startpage.boxen[i].bcolor + "&link=" + startpage.boxen[i].link + "&text=" + startpage.boxen[i].text + "&size=" + startpage.boxsize + "' />");
					$("body").append("<canvas style='display: hidden;' class='hiddenCanvas' id='hiddenCanvas" + i + "' height='124' width='124'></canvas>");

					var c = document.getElementById("hiddenCanvas" + i);
					var ctx = c.getContext("2d");

					var img = document.getElementById("hiddenIMG" + i);
					ctx.drawImage(img, 0, 0);

					arr[i] = c.toDataURL();
				}

				$(".hiddenIMG").remove();
				$(".hiddenCanvas").remove();

				localStorage.setItem("data", JSON.stringify(arr));
			}
			$(document).ready(function() {
				$("#main_input").focus();

				/*newfoo = JSON.parse(localStorage.getItem("data"));
				for(i = 0; i < newfoo.length; i++){
					$("body").append("<div class=\"box_pic\" style=\"height: 154px; width: 154px; background-image: url('" + newfoo[i] + "')\"></div>");
				}*
					*/
			});
		</script>
		<noscript style="font-size: 2.3em;"><center style="margin-top: 21%;">Javascript erforderlich!<br />Bitte aktiviere Javascript in deinem Webbrowser!</center></noscript>
		<?php require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/unten.php"); ?>
	</body>
</html>
