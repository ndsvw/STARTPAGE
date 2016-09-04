// -- ---------- -- //
// -- desktop.js -- //
// -- ---------- -- //
$dragged_element = null;
$( document ).ready(function() {
	
	$(".box_pic").draggable({ 
		container: "document", 
		scroll: false,
		revert: true,
		start: function(){
			$dragged_element = $(this);
			$( "#edit_area" ).css("display", "block");
			$( "#edit_area" ).animate({
				opacity: 1,
			    	width: "50%",
			}, 800, function() {
			    	// Animation complete.
			});
			
			$( "#delete_area" ).css("display", "block");
			$( "#delete_area" ).animate({
				opacity: 1,
			    	width: "50%",
			}, 800, function() {
			    	// Animation complete.
			});
			
			$(this).css("z-index", "2");
			$("#main").css("height", $("#main").height() + $( "#delete_area" ).height());
		},
		stop: function( event, ui ) {
			$dragged_element.css("z-index", "auto");
			$("#main").css("height", $("#main").height() - $( "#delete_area" ).height());
			
			$("#edit_area").animate({
				opacity: 0,
				width: "0%",
			}, 800, function() {
				// Animation complete.
			});

			$("#delete_area").animate({
				opacity: 0,
				width: "0%",
			}, 800, function() {
				$( "#edit_area" ).css("display", "hide");
			});
		}
	});
	
	$(".box").droppable({ 
		hoverClass: "box_with_hover_effect",
		accept: ".box_pic",
		drop: function(){
			drag_box_id = $dragged_element.parent().attr("data-boxid");
			drop_box_id = $(this).attr("data-boxid");
			if(drag_box_id != drop_box_id){
				dragged_img = $dragged_element.css("background-image");
				dragged_text = $dragged_element.parent().find("div.box_link").html();
				dragged_link = $dragged_element.parent().parent().attr("href");
				$dragged_element.css("background-image", $(this).find("div.box_pic").css("background-image"));
				$dragged_element.parent().find("div.box_link").html($(this).find("div.box_link").html());
				$dragged_element.parent().parent().attr("href", $(this).parent().attr("href"));
				$(this).find("div.box_pic").css("background-image", dragged_img);
				$(this).find("div.box_link").html(dragged_text);
				$(this).parent().attr("href", dragged_link)
			
				var lnk = Object.create(Link);
				lnk.add_Stamm("commands.php");
				lnk.add_Value("action", "boxen_tauschen");
				lnk.add_Value("userboxid1", urlencode(drag_box_id))
				lnk.add_Value("userboxid2", urlencode(drop_box_id));
				lnk.open_in_Background(false);
			}
		}
	});
	
	$("#edit_area, #delete_area").droppable({
		hoverClass: "area_with_hover_effects",
		accept: ".box_pic",
		drop: function(){
			$id = $dragged_element.parent().attr("data-boxid")
			if($(this).attr("id") == "delete_area"){
				var lnk = Object.create(Link);
				lnk.add_Stamm("commands.php");
				lnk.add_Value("userboxid", $id)
				lnk.add_Value("action", "box_loeschen");
				lnk.open_in_Background(true);
			}else if($(this).attr("id") == "edit_area"){
				var lnk = Object.create(Link);
				lnk.add_Stamm("include/popup_box_bearbeiten.php");
				lnk.add_Value("userboxid", $id);

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
								lnk.add_Stamm("commands.php");
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
			}
		}
	});
	
	//
	
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
	
	searchbox_am_anfang = $("#searchbox").width();
	$("#searchbox").css("width", searchbox_am_anfang + $("form#search-form input[type=text]").width() + $("form#search-form input[type=submit]").width());
	
	
	$.get( "../json.php?json=usersuchen", function( data ) {
		var arr = $.parseJSON(data);
		$(".suchen_div").mouseover(function() {
			for(var i = 0; i < arr.length; i++){
				var x = $(this).attr("data-suche");
				if(x == arr[i].id){
					$("#search-form").attr("action", urldecode(arr[i].link));
					$("#search-form").attr("method", urldecode(arr[i].method));
					$("#search-form input[type=text]").attr("name", urldecode(arr[i].name));
					$("#search-form input[type=text]").attr("placeholder", urldecode(arr[i].inputtext));
					$("#search-form input[type=submit]").attr("value", urldecode(arr[i].submittext));
					$("#search-form input[type=text]").select();
				}
			}
		});	
	});
	
	
	$('body').on('click','.show_popup_boxen_bearbeiten', function() {
		$(".box").append("<div class='edit_box'></div>");
		$(".box").append("<div class='delete_box'></div>");
		$("#popup").remove();
		$(".box").parent().attr("href", "#");
	});	
	
	$('body').on('click','.delete_box', function() {
		var lnk = Object.create(Link);
		lnk.add_Stamm("commands.php");
		lnk.add_Value("action", "box_loeschen")
		lnk.add_Value("userboxid", $(this).parent().attr("data-boxid"));
		lnk.open_in_Background(true);
	});	
	
	$('#main_input').on('input', function() { 
		$("#main").html("");
	    	$.ajax({
	  		url: "include/boxen_code.php?search=" + $('#main_input').val(),
	  		dataType: "script"
		});
	});
});