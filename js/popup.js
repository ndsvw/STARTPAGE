// -- -------- -- //
// -- popup.js -- //
// -- -------- -- //

$( document ).ready(function() {
	$popup_settings_code = "";
	$popup_layout_bearbeiten_code = "";
	$popup_neue_box_code = "";
	$popup_suchen_code = "";

	$.get("include/popup_settings.php", function( data ) {
	  	$popup_settings_code = data;
	});

	$.get("include/popup_layout_bearbeiten.php", function( data ) {
	  	$popup_layout_bearbeiten_code = data;
	});

	$.get("include/popup_neue_box.php", function( data ) {
	  	$popup_neue_box_code = data;
	});

	$.get("include/popup_suchen.php", function( data ) {
	  	$popup_suchen_code = data;
	});


	function urlencode(value){
		var new_value = encodeURIComponent(value);
		return new_value;
	}
	function urldecode(value){
		var new_value = decodeURIComponent(value);
		var new_value = new_value.replace('+', ' ');
		return new_value;
	}

	var Link = {
	  	linkstring: "",
		counter: 0,
		add_Stamm : function(value){
			this.linkstring = value;
		},
	  	add_Value : function(name, value){
			if(this.counter == 0){
				this.linkstring += "?" + name + "=" + value;
			}
	    		else{
				this.linkstring += "&" + name + "=" + value;
			}
			this.counter++;
	  	},
		open_in_Background : function(refresh = false){
			$.get(this.linkstring, function( data ) {
				if(refresh == true){
		  			location.reload();
				}
			});
		}
	}

	$('body').on('click','#show_popup_settings', function() {
		$("body").append("<div id='dialog'></div>");
		$("#dialog").html($popup_settings_code);
		$("#dialog").dialog({
			title: "Einstellungen",
			show: {
				effect: "blind",
				duration: 1000
			},
			hide: {
				effect: "explode",
				duration: 1000
			}
		});
	});

	$('body').on('click','#show_popup_suchen', function() {
		$("body").append("<div id='dialog'></div>");
		$("#dialog").html($popup_suchen_code);
		$("#dialog").dialog({
			title: "Suchen bearbeiten",
			buttons: [
				{
					text: "Abbrechen",
					click: function(){
						$(this).dialog("close");
					}
				},{
					text: "Speichern",
					click: function(){
						if($("#neue_suche_method_get").prop('checked') == true){
							this.method = "get";
						} else {
							this.method = "post";
						}

						var lnk = Object.create(Link);
						lnk.add_Stamm("/commands.php");
						lnk.add_Value("action", "neue_suche_speichern");
						lnk.add_Value("shortcut", urlencode($("#neue_suche_shortcut").val()));
						lnk.add_Value("placeholder", urlencode($("#neue_suche_placeholder").val()));
						lnk.add_Value("buttontext", urlencode($("#neue_suche_buttontext").val()));
						lnk.add_Value("link", urlencode($("#neue_suche_link").val()));
						lnk.add_Value("name", urlencode($("#neue_suche_name").val()));
						lnk.add_Value("method", this.method);
						lnk.add_Value("clicklink", urlencode($("#neue_suche_clicklink").val()));
						lnk.open_in_Background(true);
					}
				}
			],
			show: {
				effect: "blind",
				duration: 1000
			},
			hide: {
				effect: "explode",
				duration: 1000
			}
		});
	});

	$('body').on('click','#show_popup_layout_bearbeiten', function() {
		$("body").append("<div id='dialog'></div>");
		$("#dialog").html($popup_layout_bearbeiten_code);
		$("#dialog").dialog({
			title: "Layout bearbeiten",
			buttons: [
				{
					text: "Abbrechen",
					click: function(){
						$(this).dialog("close");
					}
				},{
					text: "Speichern",
					click: function(){
						if($("#boxstyleqr").prop('checked') == true){
							this.boxstyle = "qr";
						}else{
							this.boxstyle = "color";
						}

						var lnk = Object.create(Link);
						lnk.add_Stamm("/commands.php");
						lnk.add_Value("action", "layout_bearbeiten");
						lnk.add_Value("boxsize", $("#new_boxsize").val());
						lnk.add_Value("bodybackcolor", urlencode($("#bodybackcolor").val()));
						lnk.add_Value("boxstyle", this.boxstyle);
						lnk.open_in_Background(true);
					}
				}
			],
			show: {
				effect: "blind",
				duration: 1000
			},
			hide: {
				effect: "explode",
				duration: 1000
			}
		});
	});

	$('body').on('click','.show_popup_neue_box', function() {
		$("body").append("<div id='dialog'></div>");
		$("#dialog").html($popup_neue_box_code);
		$("#dialog").dialog({
			title: "Neue Box erstellen",
			buttons: [
				{
					text: "Abbrechen",
					click: function(){
						$(this).dialog("close");
					}
				},{
					text: "Box erstellen",
					click: function(){
						var lnk = Object.create(Link);
						lnk.add_Stamm("/commands.php");
						lnk.add_Value("action", "box_hinzufuegen");
						lnk.add_Value("boxtext", urlencode($("#boxtext").val()));
						lnk.add_Value("boxlink", urlencode($("#boxlink").val()));
						lnk.add_Value("boxforecolor", urlencode($("#boxforecolor").val()));
						lnk.add_Value("boxbackcolor", urlencode($("#boxbackcolor").val()));
						lnk.open_in_Background(true);
					}
				}
			],
			show: {
				effect: "blind",
				duration: 1000
			},
			hide: {
				effect: "explode",
				duration: 1000
			}
		});
	});

	$('body').on('click','.edit_box', function() {
		var lnk = Object.create(Link);
		lnk.add_Stamm("include/popup_box_bearbeiten.php");
		lnk.add_Value("userboxid", $(this).parent().attr("data-boxid"));

		$("body").append("<div id='dialog'></div>");
		$("#dialog").load(lnk.linkstring);
		$("#dialog").dialog({
			title: "Box bearbeiten",
			buttons: [
				{
					text: "Abbrechen",
					click: function(){
						$(this).dialog("close");
					}
				},{
					text: "Box speichern",
					click: function(){
						var lnk = Object.create(Link);
						lnk.add_Stamm("/commands.php");
						lnk.add_Value("action", "box_speichern");
						lnk.add_Value("userboxid", $("#boxid").val());
						lnk.add_Value("boxtext", urlencode($("#boxtext").val()));
						lnk.add_Value("boxlink", urlencode($("#boxlink").val()));
						lnk.add_Value("boxforecolor", urlencode($("#boxforecolor").val()));
						lnk.add_Value("boxbackcolor", urlencode($("#boxbackcolor").val()));
						lnk.open_in_Background(true);
					}
				}
			],
			show: {
				effect: "blind",
				duration: 1000
			},
			hide: {
				effect: "explode",
				duration: 1000
			}
		});
	});
});
