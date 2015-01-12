$(document).ready(function () {
    var fadeSuccessMessages = function () {
        $(".alert-success").delay(2000).slideUp(500, function () {
            this.remove();
        });
    };
    /** Globaler Message Handler **/
        //AJAX-Response==JSON[msg]?set message
    $(document).ajaxSuccess(function (e, req) {
        if (req.responseJSON && req.responseJSON.msg) {
            var messages = req.responseJSON.msg;
            var messageTypes = {
                error: {
                    typeClass: "alert-danger",
                    title: "Fehler"
                },
                warning: {
                    typeClass: "alert-warning",
                    title: "Achtung"
                },
                success: {
                    typeClass: "alert-success",
                    title: "Erfolg"
                }
            };
            for (var type in messageTypes) {
                messages[type].forEach(function (msg) {
                    var htmlMsg = $('<div class="alert ' + messageTypes[type]["typeClass"] + ' alert-dismissible" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span>' +
                    '</button>' +
                    '<strong>' + messageTypes[type]["title"] + '</strong> ' + msg +
                    '</div>').hide();
                    //Modal-Fenster?
                    var modal = $(".modal-dialog");
                    if (modal.length) {
                        modal.prepend(htmlMsg);
                    } else {
                        $("main").prepend(htmlMsg);
                    }
                    htmlMsg.slideDown(500);
                    //autofadeout vom erfolgsmeldungen
                    fadeSuccessMessages();
                });
            }
        }
    });
    /** Neue Frucht **/
    $(document).on("click", "#newFruitPhoto", function (e) {
        e.preventDefault();
        $("#fruitphoto").trigger("click");
    }).on("change", "#fruitphoto", function () {
        var data = new FormData();
        data.append($(this).attr("name"), this.files[0]);
        $(this).val(""); //IE-Bug

        var formular = $(this).parents("form");
        var targetUrl = formular.attr("action");

        $.ajax({
            url: targetUrl,
            data: data,
            cache: false,
            method: formular.attr("method"),
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {
                if (data.preview && data.token) {
                    $("#fruit_label").attr("src", "data:image/jpg;base64," + data["preview"]);
                    $("#token").val(data.token);
                }
            }
        });
    })
        //Leere Modal-Fenster nach Schließen
        .on("hidden.bs.modal", "#newFruit", function (e) {
            $(e.target).removeData('bs.modal');
        });

    //Lade Bild nicht nochmals hoch
    $('form#fruits').on("submit", function () {
        $(this).find('input[type="file"]').prop("disabled", true);
        return true;
    });

    //colorpicker
    $(function () {
        $('#fruits #color').colorpicker().on("changeColor", function (e) {
            var clr;
            if (e.color != undefined) {
                clr = e.color.toHex();
            } else {
                clr = $(this).val();

            }
            $(this).css({backgroundColor: clr});
        }).trigger("changeColor");
    });
    //confirm
    $('[data-toggle="confirmation"]').confirmation({
            title: "Sind Sie sicher?",
            singleton: true
        }
    );


    //Fadeout on load
    fadeSuccessMessages();
    /**
     * Funktionen für Wertevergleich
     * @returns {number}
     */
    Array.prototype.sum = function() {
        var count = 0;
        for(var i=0, n=this.length; i < n; i++) {
            count += this[i];
        }
        return count;
    };

    /**
     * Mixer-Darstellung/Steuerung
     */
    //wenn bild geladen => proportionen vorhanden
    $(window).load(function () {
        var img = $("#mixer");
        $('#mixerwrapper').css({
            width: img.width(),
            height: (img.height() / 2)
        });
    });

    var fruits=[]; //Sichert alle Früchte
    //Frücht hinzufügen
    /**
     * Initialisiert Slider
     */
    var initSliders=function(){
        //Slider
        $('.fruit-slider').slider({
            min:0,
            max:100,
            step:1,
            value:0,
            tooltip: "hide",
            handle: "round"
        }).on("slide", function(e) {
            var id=$(this).attr("data-prev");
            $("#"+id).text(e.value);
            $(this).val(e.value);
            adjustSum($(this));
        });
    };
    /**
     * Liefert Regler mit größtem Wert
     * @returns {*}
     */
    var getBiggestSlider=function(){
        var biggest;
        var biggestVal=0;

        var changingSlider=undefined;
        if(arguments.length>0) {
            changingSlider = arguments[0];
        }

        $(".fruit-slider").each(function(i,e){
            var newVal=$(e).data("slider").getValue();
            if(changingSlider==undefined){
                if(biggestVal<newVal){
                    biggest=$(e);
                    biggestVal=newVal;
                }
            }else{
                if(biggestVal<newVal && $(e).attr("data-prev")!=changingSlider.attr("data-prev")){
                    biggest=$(e);
                    biggestVal=newVal;
                }
            }
        });
        return biggest;
    };
    /**
     * Berechne Preis
     */
    var calcPrice=function(){
        var summeEle=$("#summe");
        var summe=0;
        var sizeSelect=$('#size').find('option[value="'+$('#size').val()+'"]').text();
        var size=parseInt(/[A-Z] \((.+)ml\)/.exec(sizeSelect)[1]);

        $(".fruit-slider").each(function(i,e){
            var amount=$(e).data("slider").getValue();
            var fruitPrice=parseFloat($(e).attr("data-price"));
            summe+=(amount*size*fruitPrice)/10000;
        });
        summeEle.text(summe.toFixed(2).replace(".",","))
    };
    /**
     * Aktualisiert den Mixer
     */
    var drawMixer=function(){
        //Entferne alles
        var wrapper=$("#mixerwrapper");
        wrapper.find("> .fruitcolor").remove();
        var amounts=[];
        var colors={};

        var fruitColorTmpl=$('<div class="fruitcolor"><div class="fruitwave"></div><div class="fruitbody"></div></div>');

        $(".fruit-slider").each(function(i,e){
            var amount=$(e).data("slider").getValue();
            if(amount>0) {
                //Kunstgriff. veringere Werte um mit identischen Werten Farben speichern zu können
                while(amounts.indexOf(amount)!=-1){
                    amount--;
                }
                amounts.push(amount);
                colors[amount] = $(e).attr("data-color");
            }
        });
        amounts.sort().reverse();

        var amountsSum=0;
        amounts.forEach(function(e){

            amountsSum+=e;
            var newEle=fruitColorTmpl.clone();
            newEle.css("height",amountsSum*0.77+"%");
            newEle.find(">*").css("backgroundColor",colors[e]);
            //entferne Farbwert um keine do
            wrapper.prepend(newEle);
        })
    };
    /**
     * Stellt sicher, dass alle Regler immer 100% ergeben!
     */
    var adjustSum=function(){
        var values=[];
        $(".fruit-slider").each(function(i,e){
            values.push($(e).data("slider").getValue());
        });
        var changingSlider=undefined;
        if(arguments.length>0) {
            changingSlider = arguments[0];
        }
        var sum=values.sum();
        if(sum>100){
            var diff=sum-100;
            var biggest=getBiggestSlider(changingSlider);
            var val=biggest.data("slider").getValue();
            biggest.data("slider").setValue(val-diff);
            biggest.val(val-diff);
            $('#'+$(biggest).attr("data-prev")).text(val-diff);
        }
        calcPrice();
        drawMixer();
    };
    /**
     * Fügt Frucht in Formular ein
     */
    var addFruit=function(id,color,name,price,pic){
        if(id==undefined){
            return
        }

        var defaultValue=$("#all-fruit-sliders>*").length<1?100:0;

        var slideTmpl=$('<div class="row fruit-slide-row" data-fruit-id="'+id+'">' +
        '<img src="'+pic+'"/>' +
        '<label><span id="fruit'+id+'" class="fruit-value">'+defaultValue+'</span>'+name+'</label><br>' +
        '<input name="fruitInSmoothie['+id+']" type="text" data-color="'+color+'" data-price="'+price+'" class="fruit-slider" data-prev="fruit'+id+'" data-slider-value="'+defaultValue+'" />' +
        '<button class="btn btn-danger remove-fruit" >' +
        '<span class="glyphicon glyphicon-remove" aria-hidden="true"></button></div>');

        //füge ein
        $("#all-fruit-sliders").append(slideTmpl);

        $(".fruit-slider").val(defaultValue);
        initSliders();

        adjustSum();
    };

    $("#btn-add-fruit").on("click",function(){
        var modalWindow=$('.insertnewFruit');
        var ID=modalWindow.find("select").val();
        var selection=modalWindow.find('option[id="'+ID+'"]');
        var color=selection.attr("data-color");
        var pic=selection.attr("data-pic");
        var name=selection.attr("data-name");
        var price=selection.attr("data-price");
        addFruit(ID,color,name,price, pic);
        //Auswahl steht nicht mehr zur Verfügung
        selection.prop("disabled","disabled");
        modalWindow.find("select").selectpicker('render');

        modalWindow.modal('hide')
    });

    /**
     * Löscht eine Frucht
     */
    $(document).on("click",".remove-fruit",function(e){
        e.preventDefault();
        var row=$(this).parents(".fruit-slide-row");
        var ID=row.attr("data-fruit-id");
        var modalWindow=$('.insertnewFruit');

        modalWindow.find('option[id="'+ID+'"]').removeAttr("disabled");
        modalWindow.find("select").selectpicker('render');
        $(this).parents(".fruit-slide-row").remove();
        adjustSum();
    });

    //Wenn sich Preis ändert..
    $("#size").on("change", calcPrice);

    $(window).load(function(){ //erst nach laden der Bilder
        initSliders();
        //ermaliges einfügen einer Frucht
        $('.insertnewFruit').modal('show')
    });

});

