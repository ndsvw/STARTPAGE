function Startpage(mainElement, boxsize, editableAndAddable, boxenAreFromDatabase){
    this.mainElement = mainElement;
    this.boxsize = boxsize;
    this.editableAndAddable = editableAndAddable;
    this.boxenAreFromDatabase = boxenAreFromDatabase;
    this.margin = 22;
    this.boxen = [];
    this.suchen = [];

    this.addBox = function(box){
        //adds boxes to the startpage to visualize them later
        this.boxen.push(box);
    }

    this.addBoxen = function(parent, style, json){
        //get boxes by json
        for(var i = 0; i < json.length; i++){
            this.addBox(new Box(this, urldecode(json[i][0]),  "", style, json[i][1], json[i][2]));
        }
    }

    this.deleteBox = function(box){
        this.boxen.splice(box, 1);
    }

    this.editBox = function(boxid, text, link, fcolor, bcolor){
        var lnk = new Link();
        lnk.addStamm("/commands.php");
        lnk.addValue("action", "box_speichern");
        lnk.addValue("userboxid", boxid);
        lnk.addValue("boxtext", text);
        lnk.addValue("boxlink", link);
        lnk.addValue("boxforecolor", fcolor);
        lnk.addValue("boxbackcolor", bcolor);
        lnk.openInBackground(true);
        startpage.visualize(startpage.boxen);
    }

    this.createNewBox = function(text, link, fcolor, bcolor){
        //adds the given box to the startpage and saves the box in the database
        this.addBox(new Box(this, text, link, this.boxen[0].style, fcolor, bcolor));
        this.visualize(this.boxen);

        var lnk = new Link();
        lnk.addStamm("/commands.php");
        lnk.addValue("action", "box_hinzufuegen");
        lnk.addValue("boxtext", text);
        lnk.addValue("boxlink", link);
        lnk.addValue("boxforecolor", fcolor);
        lnk.addValue("boxbackcolor", bcolor);
        lnk.openInBackground();
    }

    this.outputInConsole = function(){
        for(i = 0; i < this.boxen.length; i++){
            console.log("#" + this.boxen[i].id + " " + this.boxen[i].text + " " + this.boxen[i].link + " " + this.boxen[i].style + " " + this.boxen[i].fcolor + " " + this.boxen[i].bcolor);
        }
    }

    this.swapBoxes = function(id1, id2){
        //Boxen tauschen
        var tmp = this.boxen[id1];
        this.boxen[id1].id = id2;
        this.boxen[id2].id = id1;
        this.boxen[id1] = this.boxen[id2];
        this.boxen[id2] = tmp;
        this.visualize(this.boxen);
    }

    this.getBoxById = function(id){
        for(i = 0; i < this.boxen.length; i++){
            if(this.boxen[i].id == id){
                return this.boxen[i];
            }
        }
    }

    this.visualize = function(bxs){
        //visualize the boxes
        $(mainElement).empty();
        for(i = 0; i < bxs.length; i++){
            $(mainElement).append(bxs[i].getString());
        }

        //change the size of the mainElement-Div
        parentWidth = $(mainElement).parent().width();
        boxenProReihe = Math.floor((parentWidth - (parentWidth / this.boxsize) * this.margin) / this.boxsize);
        boxenProSpalte = Math.ceil(bxs.length / boxenProReihe);

        finalWidth = boxenProReihe * (this.boxsize + this.margin);
        $(mainElement).css("width", finalWidth + "px");
        $(mainElement).css("margin", "0 auto");
        $(mainElement).css("min-height", (this.boxsize + 1 + 40) * boxenProSpalte + 10 + "px");

        //only if editableAndAddable == true
        if(startpage.editableAndAddable == true){
            //fill the rest
            max_boxen = boxenProSpalte * boxenProReihe;
            for(var i = 1; i <= (max_boxen - bxs.length); i++){
                emptyBoxString = "";
                emptyBoxString += "<div class='empty_box show_popup_neue_box' data-boxid='" + bxs.length + "' style='width: " + this.boxsize + "px; height: " + this.boxsize + "px;'>";
                emptyBoxString += "</div>";
                $(mainElement).append(emptyBoxString);
            }

            //add plus-icon
            if(bxs.length % boxenProReihe == 0){
                $(mainElement).append("<img class='add_box_icon show_popup_neue_box' src='/img/ic_add_black_48dp_2x.png' style='top: " + ($(mainElement).height() - this.boxsize / 2 - 30 - 15) + "px;'>");
            }

            //make the box draggeable
            $(".box_inner").draggable(box_drag);
        }

        $("#main_input").focus();
    }

    this.filter = function(str){
        filteredMitPrio = [];
        filtered = [];
        for(i = 0; i < this.boxen.length; i++){
            if(this.boxen[i].text.toLowerCase().substring(0, str.length) === str.toLowerCase().substring(0, str.length)){
                filteredMitPrio.push(this.boxen[i]);
            } else if(this.boxen[i].text.toLowerCase().includes(str.toLowerCase())){
                filtered.push(this.boxen[i]);
            }
        }
        return filteredMitPrio.concat(filtered); //kombiniert beide arrays
    }

    this.create_Empty_Box = function(){
    	empty_string = "";
    	empty_string += "<div class='empty_box show_popup_neue_box' style='width: " + size + "px; height: " + size + "px;'>";
    	empty_string += "</div>";
    	return empty_string;
    }

    this.addSuche = function(suche){
        this.suchen.push(suche);
    }

    this.addSuchen = function(data){
        for(var i = 0; i < data.length; i++){
            this.addSuche(new Search(data[i][0], data[i][1], data[i][2], data[i][3], data[i][4]));
        }
    }

    this.visualizeSuchen = function(element){
        for(i = this.suchen.length - 1; i >= 0 ; i--){
            $(element).prepend("<a href='" + urldecode(this.suchen[i].link) + "'><div class='suchen_div' data-suche='" + i + "' >" + this.suchen[i].shortcut + "</div></a>");
        }
        $(element).width(51 * this.suchen.length + 494 + 79 + 10);
    }

    this.makeSucheAktive = function(id){
        s = this.suchen[id];
        $("#search-form").attr("action", urldecode(s.link));
        $("#search-form").attr("method", urldecode(s.method));
        $("#search-form input[type=text]").attr("name", urldecode(s.name));
        $("#search-form input[type=text]").attr("placeholder", urldecode(s.placeholder));
        $("#search-form input[type=text]").select();
    }
}

function Link(){
    this.linkString = "";
    this.argCounter = 0;
    this.addStamm = function(value){
        this.linkString = value;
    }
    this.addValue = function(name, value){
        this.linkString += (this.argCounter++ == 0 ? "?" : "&") + name + "=" + value;
    }
    this.openInBackground = function(){
        $.get(this.linkString);
    }
}
