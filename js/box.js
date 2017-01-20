function Box(parent, text, link, forecolor, backcolor){
    this.id = parent.boxen.length;
    this.parent = parent;
    this.text = text;
    this.link = link;
    this.fcolor = forecolor;
    this.bcolor = backcolor;
    this.storedBase64 = "";

    //getter and setter
    this.getParent = function(){
        return this.parent;
    }
    this.setParent = function(parent){
        this.parent = parent;
    }
    this.getText = function(){
        return this.text;
    }
    this.setText = function(text){
        this.text = text;
    }
    this.getLink = function(){
        return this.link;
    }
    this.setLink = function(link){
        this.link = link
    }
    this.getFcolor = function(){
        return this.fcolor;
    }
    this.setFcolor = function(fcolor){
        this.fcolor = fcolor
    }
    this.getBcolor = function(){
        return this.bcolor;
    }
    this.setBcolor = function(bcolor){
        this.bcolor = bcolor
    }
    this.getStoredBase64 = function(){
        return this.storedBase64;
    }
    this.setStoredBase64 = function(storedBase64){
        this.storedBase64 = storedBase64;
    }

    this.getString = function(){
        usedLink = ((this.parent.areShortLinksUsed()) ? "/redirect.php?userboxid=" + this.id : urldecode(link));
        str = "";
        if(this.parent.getBoxStyle() === "qr"){
            str += "<a href=" + usedLink + ">";
        	str += "<div class='box' data-boxid='" + this.id + "' style='width: " + parent.boxsize + "px; height: " + parent.boxsize + "px' >";
            if(this.storedBase64 != ""){
                str += "<div class='box_inner box_pic' style='width: " + parent.boxsize + "px; height: " + parent.boxsize + "px; background-image: url(\"" + this.storedBase64 + "\");'>";
            } else {
                str += "<div class='box_inner box_pic' style='width: " + parent.boxsize + "px; height: " + parent.boxsize + "px; background-image: url(\"/include/getQrCode.php?color=" + this.fcolor + "&bgcolor=" + this.bcolor + "&link=" + this.link + "&text=" + this.text + "&size=" + parent.boxsize + "\");'>";
            }
        	str += "</div>";
        	str += "<div class='box_link' title='" + this.text + "'>";
        	str += this.text;
        	str += "</div>";
        	str += "</div>";
        	str += "</a>";
        } else if(this.parent.getBoxStyle() === "color") {
        	str += "<a href=" + usedLink + ">";
        	str += "<div class='box' data-boxid='" + this.id + "' style='width: " + parent.boxsize + "px; height: " + parent.boxsize + "px; background-color: " + urldecode(this.bcolor) + ";'>";
        	str += "<div class='box_inner' style='height: 100%;'>";
        	str += "<div class='box_farbe_link' style='padding: 8px 0px; color: " + urldecode(this.fcolor) + ";' title='" + this.text + "'>";
        	str += this.text;
        	str += "</div>";
        	str += "</div>";
        	str += "</div>";
        	str += "</a>";
        }
    	return str;
    }

    this.outputInConsole = function(){
        console.log("#" + this.id + " " + this.text + " " + this.link + " " + this.fcolor + " " + this.bcolor);
    }
}
