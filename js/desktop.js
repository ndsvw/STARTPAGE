function urlencode(value){
	var new_value = encodeURIComponent(value);
	return new_value;
}
function urldecode(value){
	var new_value = decodeURIComponent(value);
	var new_value = new_value.replace('+', ' ');
	return new_value;
}
function escapeRegExp(str) {
	return str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
}
function replaceAll(str, find, replace) {
	return str.replace(new RegExp(escapeRegExp(find), 'g'), replace);
}
showEffect = {
	effect: "blind",
	duration: 1000
}
hideEffect = {
	effect: "explode",
	duration: 1000
}
buttonAbbrechen = {
	text: "Abbrechen",
	click: function(){
		$(this).dialog("close");
	}
}
draggedBox = null;

$(document).ready(function() {
	var json = [
		["addBox", ["text", "link", "forecolor", "backcolor"], "Fügt eine Box hinzu. Farben mit # angeben."],
		["deleteBox", ["id"], "Löscht die Box mit der übergebenen ID."],
		["setBgColor", ["color"], "Ändert die Hintergrundfarbe zur übergebenen Farbe. Farbe mit # angeben."],
		["showBoxIDs", [], "Zeigt bei den Boxen die IDs an"],
		["hideBoxIDs", [], "Verbirgt die angezeigten Box_IDs"],
		["zzz", [], "beschr z"],
		["yyy", [], "beschr y"]
	];

	function cmdDropDown(inputElement){
		inputValue = $(inputElement).val();

		lft = ($(inputElement).position().left + 10);
		tp = 17 + $(inputElement).height();
		wdth = $(inputElement).width() + 14;
		hght = (39 + 4) * 5;

		$("#inputDropDown").css({"left": lft + "px", "top": tp + "px", "height": hght + "px", "width": wdth + "px"});

		x = "";

		for(i = 0; i < json.length; i++){
			console.log(":" + json[i][0].substring(0, inputValue.length - 1) + " - - - - " + inputValue);
			if(":" + json[i][0].substring(0, inputValue.length - 1) === inputValue.substring(1 + json[i][0].substring(0, inputValue.length - 1)) || inputValue.startsWith(":" + json[i][0]) || inputValue === ":"){
				str = "";
				for(j = 0; j < json[i][1].length; j++){
					str += json[i][1][j] + " ";
				}
				x += "<div class='dropDownElement' data-dropDownID='" + i + "' style='position: relative; background-color: #E8E8E8; border: 1px solid #7777BB; border-radius: 2px; padding-top: 10px; padding-left: 10px; -moz-user-select:none; cursor: pointer; width: " + (wdth - 2) + "; height: 29px; margin: 2px 0px;' title='" + json[i][2] + "'><b>" + json[i][0] + "</b> " + str + "</div>";
			}
		}

		$("#inputDropDown").append(x);
	}

	function analyseCMD(cmd){
		
	}

	$("#search-form").keyup(function(event){ //only possible if input starts with ":"
		if(event.keyCode == 13){
			if($("#main_input").val().startsWith(":")){
				analyseCMD($("#main_input").val());
			}
		}
	});
	$("#main_submit").click(function(){
		console.log("x3");
		/*if($('#main_input').startsWith(":")){
			$("#main_input").focus();
			console.log("x2");
		}*/
	});
	$('#main_input').on('input', function() {
		startpage.editableAndAddable = ($('#main_input').val() == "");
		startpage.visualize(startpage.filter($('#main_input').val()));
		$("#inputDropDown").html("");
		if($('#main_input').val().substring(0, 1) == ":"){
			$("#main_submit").attr("disabled", true);
			cmdDropDown($('#main_input'));
		} else {
			$("#main_submit").removeAttr("disabled");
		}
		$('.dropDownElement').click(function(){
			id = $(this).attr('data-dropDownID');
			$("#main_input").val(":" +  json[id][0] + " ");
			$("#main_input").focus();
		});
	});





	box_drag = {
		container: "document",
		scroll: false,
		revert: true,
		start: function(){
			draggedBox = startpage.getBoxById($(this).parent().attr("data-boxid"));
			if(startpage.editableAndAddable == true){

				$(startpage.mainElement).parent().append("<div id='edit_area'><img src='/img/ic_edit_black_48dp_2x.png'></div>");
				$(startpage.mainElement).parent().append("<div id='delete_area'><img src='/img/ic_delete_black_48dp_2x.png'></div>");

				$(".box").droppable(dropInOtherBox);

				$( "#edit_area" ).animate({
					display: "block",
					opacity: 1,
					width: "50%"
				}, 800, function(){
					$("#edit_area").droppable(dropInArea);
				});

				$( "#delete_area" ).animate({
					display: "block",
					opacity: 1,
					width: "50%"
				}, 800, function(){
					$("#delete_area").droppable(dropInArea);
				});


				$(this).css("z-index", "2");
				$(startpage.mainElement).css("height", $(startpage.mainElement).height() + $("#delete_area").height());
			}
		},
		stop: function( event, ui ) {
			$(".box_inner").css("z-index", "auto");
			$(startpage.mainElement).css("height", $(startpage.mainElement).height() - $("#delete_area").height());

			$("#edit_area").animate({
				opacity: 0,
				width: "0%"
			}, 800, function() {
				$( "#edit_area" ).css("display", "hide");
			});

			$("#delete_area").animate({
				opacity: 0,
				width: "0%"
			}, 800, function() {
				$( "#delete_area" ).css("display", "hide");
			});
		}
	}

	dropInArea = {
		hoverClass: "box_with_hover_effect",
		accept: ".box_inner",
		drop: function(){
			if($(this).attr("id") == "delete_area"){
				var lnk = new Link();
				lnk.addStamm("/commands.php");
				lnk.addValue("userboxid", draggedBox.id);
				lnk.addValue("action", "box_loeschen");
				lnk.openInBackground();
				startpage.deleteBox(draggedBox.id);
				startpage.visualize(startpage.boxen);
			}else if($(this).attr("id") == "edit_area"){
				var lnk = new Link();
				lnk.addStamm("/include/popup_box_bearbeiten.php");
				lnk.addValue("userboxid", draggedBox.id);

				$("body").append("<div id='dialog'></div>");
				$("#dialog").load(lnk.linkString);
				$("#dialog").dialog({
					title: "Box bearbeiten",
					buttons: [
						buttonAbbrechen,
						{
							text: "Box speichern",
							click: function(){
								startpage.boxen[draggedBox.id].text = $("#boxtext").val();
								startpage.boxen[draggedBox.id].link = $("#boxlink").val();
								startpage.boxen[draggedBox.id].fcolor = urlencode($("#boxforecolor").val());
								startpage.boxen[draggedBox.id].bcolor = urlencode($("#boxbackcolor").val());
								startpage.visualize(startpage.boxen);

								var lnk = new Link();
								lnk.addStamm("/commands.php");
								lnk.addValue("action", "box_speichern");
								lnk.addValue("userboxid", draggedBox.id);
								lnk.addValue("boxtext", urlencode(startpage.boxen[draggedBox.id].text));
								lnk.addValue("boxlink", urlencode(startpage.boxen[draggedBox.id].link));
								lnk.addValue("boxforecolor", startpage.boxen[draggedBox.id].fcolor);
								lnk.addValue("boxbackcolor", startpage.boxen[draggedBox.id].bcolor);
								lnk.openInBackground();
								$(this).dialog("close");
							}
						}
					],
					show: showEffect,
					hide: hideEffect
				});
			}
		}
	}

	dropInOtherBox = {
		hoverClass: "box_with_hover_effect",
		accept: ".box_inner",
		drop: function(){
			droppedBox = startpage.getBoxById($(this).attr("data-boxid"));
			if(draggedBox.id != droppedBox.id){
				startpage.swapBoxes(draggedBox.id, droppedBox.id);
				var lnk = new Link();
				lnk.addStamm("/commands.php");
				lnk.addValue("action", "boxen_tauschen");
				lnk.addValue("userboxid1", urlencode(draggedBox.id))
				lnk.addValue("userboxid2", urlencode(droppedBox.id));
				lnk.openInBackground(false);
			}
			dropped_into_edit_delete = false;
		}
	}


	$popup_settings_code = "";
	$popup_layout_bearbeiten_code = "";
	$popup_neue_box_code = "";
	$popup_suchen_code = "";

	$.get("/include/popup_settings.php", function( data ) {
		$popup_settings_code = data;
	});

	$.get("/include/popup_layout_bearbeiten.php", function( data ) {
		$popup_layout_bearbeiten_code = data;
	});

	$.get("/include/popup_neue_box.php", function( data ) {
		$popup_neue_box_code = data;
	});

	$.get("/include/popup_suchen.php", function( data ) {
		$popup_suchen_code = data;
	});

	$('body').on('click','#show_popup_settings', function() {
		$("body").append("<div id='dialog'></div>");
		$("#dialog").html($popup_settings_code);
		$("#dialog").dialog({
			title: "Einstellungen",
			show: showEffect,
			hide: hideEffect
		});
	});

	$('body').on('click','#show_popup_suchen', function() {
		$("body").append("<div id='dialog'></div>");
		$("#dialog").html($popup_suchen_code);
		$("#dialog").dialog({
			title: "Suchen bearbeiten",
			buttons: [
				buttonAbbrechen,
				{
					text: "Speichern",
					click: function(){
						var lnk = new Link();
						lnk.addStamm("/commands.php");
						lnk.addValue("action", "neue_suche_speichern");
						lnk.addValue("shortcut", urlencode($("#neue_suche_shortcut").val()));
						lnk.addValue("placeholder", urlencode($("#neue_suche_placeholder").val()));
						lnk.addValue("buttontext", urlencode($("#neue_suche_buttontext").val()));
						lnk.addValue("link", urlencode($("#neue_suche_link").val()));
						lnk.addValue("name", urlencode($("#neue_suche_name").val()));
						lnk.addValue("method", $("#neue_suche_method_get").prop('checked') == true ? "get" : "post");
						lnk.addValue("clicklink", urlencode($("#neue_suche_clicklink").val()));
						lnk.openInBackground(true);
					}
				}
			],
			show: showEffect,
			hide: hideEffect
		});
	});

	$('body').on('click','#show_popup_layout_bearbeiten', function() {
		$("body").append("<div id='dialog'></div>");
		$("#dialog").html($popup_layout_bearbeiten_code);
		$("#dialog").dialog({
			title: "Layout bearbeiten",
			buttons: [
				buttonAbbrechen,
				{
					text: "Speichern",
					click: function(){
						var lnk = new Link();
						lnk.addStamm("/commands.php");
						lnk.addValue("action", "layout_bearbeiten");
						lnk.addValue("boxsize", $("#new_boxsize").val());
						lnk.addValue("bodybackcolor", urlencode($("#bodybackcolor").val()));
						lnk.addValue("boxstyle", $("#boxstyleqr").prop('checked') == true ? "qr" : "color");
						lnk.openInBackground(true);
						startpage.visualize(startpage.boxen);
					}
				}
			],
			show: showEffect,
			hide: hideEffect
		});
	});

	$('body').on('click','.show_popup_neue_box', function() {
		$("body").append("<div id='dialog'></div>");
		$("#dialog").html($popup_neue_box_code);
		$("#dialog").dialog({
			title: "Neue Box erstellen",
			buttons: [
				buttonAbbrechen,
				{
					text: "Box erstellen",
					click: function(){
						startpage.createNewBox(urlencode($("#boxtext").val()), urlencode($("#boxlink").val()), urlencode($("#boxforecolor").val()), urlencode($("#boxbackcolor").val()));
						$(this).dialog("close");
					}
				}
			],
			show: showEffect,
			hide: hideEffect
		});
	});

	$('body').on('click','.edit_box', function() {
		var lnk = new Link();
		lnk.addStamm("include/popup_box_bearbeiten.php");
		lnk.addValue("userboxid", $(this).parent().attr("data-boxid"));

		$("body").append("<div id='dialog'></div>");
		$("#dialog").load(lnk.linkString);
		$("#dialog").dialog({
			title: "Box bearbeiten",
			buttons: [
				buttonAbbrechen,
				{
					text: "Box speichern",
					click: function(){
						startpage.editBox($("#boxid").val(), urlencode($("#boxtext").val()), urlencode($("#boxlink").val()), urlencode($("#boxforecolor").val()), urlencode($("#boxbackcolor").val()));
						$(this).dialog("close");
					}
				}
			],
			show: showEffect,
			hide: hideEffect
		});
	});

	$(function() {
		$("#dialog").dialog();
	});

});
