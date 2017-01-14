function Box(parent, text, link, style, forecolor, backcolor){
    this.id = parent.boxen.length;
    this.parent = parent;
    this.text = text;
    this.link = "/redirect.php?" + ((this.parent.boxenAreFromDatabase) ? "userboxid=" + this.id : "link=" + urldecode(link));
    this.style = style;
    this.fcolor = forecolor;
    this.bcolor = backcolor;

    this.getString = function(){
        str = "";
        if(this.style == "qr"){
            str += "<a href=" + this.link + ">";
        	str += "<div class='box' data-boxid='" + this.id + "' style='width: " + parent.boxsize + "px; height: " + parent.boxsize + "px' >";
        	str += "<div class='box_inner box_pic' style='width: " + parent.boxsize + "px; height: " + parent.boxsize + "px; background-image: url(\"/include/getQrCode.php?color=" + this.fcolor + "&bgcolor=" + this.bcolor + "&link=" + this.link + "&text=" + this.text + "&size=" + parent.boxsize + "\");'>";
        	str += "</div>";
        	str += "<div class='box_link' title='" + this.text + "'>";
        	str += this.text;
        	str += "</div>";
        	str += "</div>";
        	str += "</a>";
        } else if(this.style == "color") {
        	str += "<a href=" + this.link + ">";
        	str += "<div class='box' data-boxid='" + this.id + "' style='width: " + parent.boxsize + "px; height: " + parent.boxsize + "px; background-color: " + this.urldecode(this.bcolor) + ";'>";
        	str += "<div class='box_inner' style='height: 100%;'>";
        	str += "<div class='box_farbe_link' style='padding: 8px 0px; color: " + this.urldecode(this.fcolor) + ";' title='" + this.text + "'>";
        	str += this.text;
        	str += "</div>";
        	str += "</div>";
        	str += "</div>";
        	str += "</a>";
        }
    	return str;
    }

    this.urldecode = function(value){
    	var new_value = decodeURIComponent(value);
    	var new_value = new_value.replace('+', ' ');
    	return new_value;
    }
}
