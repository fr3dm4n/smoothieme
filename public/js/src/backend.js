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
});
