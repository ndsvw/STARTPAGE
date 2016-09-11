// -- -------- -- //
// -- boxen.js -- //
// -- -------- -- //

$max_boxen_pro_reihe = 0;
$max_boxen_pro_spalte = 0;
$max_boxen = 0;
$vorhandene_boxen = 0;

function Create_Box(parent, boxid, art, link, text, size, f_color, b_color){
	if(art == "qr"){
		qr_string = Get_Qr_String(boxid, link, text, size, f_color, b_color);
		$(parent).append(qr_string);
	}else if(art == "color"){
		color_string = Get_Color_String(boxid, link, text, size, f_color, b_color);
		$(parent).append(color_string);
	}
}

function Create_Empty_Box(parent, size){
	empty_box_string = Get_Empty_String(size);
	$(parent).append(empty_box_string);
}

function Center_Parent(parent, boxsize){
	$max_boxen_pro_reihe = $(parent).parent().width() / boxsize;
	$max_boxen_pro_reihe = ($(parent).parent().width() - $max_boxen_pro_reihe * 22) / boxsize;
	$max_boxen_pro_reihe = (Math.floor($max_boxen_pro_reihe));
	final_width = $max_boxen_pro_reihe * (boxsize + 22);
	$(parent).css("width", final_width + "px");
	$(parent).css("margin", "0 auto");
}

function Fill_The_Rest(parent, boxsize, art){
	$max_boxen_pro_reihe = $(parent).parent().width() / boxsize;
	$max_boxen_pro_reihe = ($(parent).parent().width() - $max_boxen_pro_reihe * 22) / boxsize;
	$max_boxen_pro_reihe = (Math.floor($max_boxen_pro_reihe));
	$vorhandene_boxen = $('.box').length;
	$max_boxen_pro_spalte = Math.ceil($vorhandene_boxen / $max_boxen_pro_reihe);
	$max_boxen = $max_boxen_pro_spalte * $max_boxen_pro_reihe;
	
	freier_platz = $max_boxen - $vorhandene_boxen;
	
	for(var i = 1; i <= freier_platz; i++){
		Create_Empty_Box(parent, boxsize);
	}

	if($vorhandene_boxen == 0){
		for(var i = 1; i <= $max_boxen_pro_reihe; i++){
			Create_Empty_Box(parent, boxsize);
		}
	}
	
	$(parent).css("min-height", (boxsize + 1 + 40) * $max_boxen_pro_spalte + "px");
	
}



// -- -------- -- //
// -- returner -- //
// -- -------- -- //

function Get_Qr_String(boxid, link, text, size, f_color, b_color){
	qr_string = "";
	qr_string += "<div class='box' data-boxid='" + boxid + "' data-link='" + link + "' style='width: " + size + "px; height: " + size + "px' >";
	qr_string += "<div class='box_pic' style='width: " + size + "px; height: " + size + "px; background-image: url(\"/include/getQrCode.php?color=" + f_color + "&bgcolor=" + b_color + "&link=" + link + "&text=" + text + "&size=" + size + "\");'>";
	qr_string += "</div>";
	qr_string += "<div class='box_link' title='" + text + "'>";
	qr_string += text;
	qr_string += "</div>";
	qr_string += "</div>";
	return qr_string;
}

function Get_Color_String(boxid, link, text, size, f_color, b_color){
	color_string = "";
	color_string += "<div class='box' data-boxid='" + boxid + "' data-link='" + link + "' style='width: " + size + "px; height: " + size + "px; background-color: " + b_color + "'>";
	color_string += "<div class='box_farbe_link' style='padding: 8px 0px; color: " + f_color + ";' title='" + text + "'>";
	color_string += text;
	color_string += "</div>"
	color_string += "</div>";
	return color_string;
}
	
function Get_Empty_String(size){
	empty_string = "";
	empty_string += "<div class='empty_box show_popup_neue_box' data-boxid='" + ($(".box").length + 1) + "' style='width: " + size + "px; height: " + size + "px;'>";
	empty_string += "</div>";
	return empty_string;
}