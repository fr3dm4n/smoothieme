
$(document).ready(function(){
    /** Globaler Message Handler **/
    //AJAX-Response==JSON[msg]?set message
    $(document).ajaxSuccess(function (e, req) {
        if (req.responseJSON && req.responseJSON.msg && req.responseJSON.msg.success && req.responseJSON.msg.warning && req.responseJSON.msg.error) {
            var messages=req.responseJSON.msg;
            var messageTypes={
                error:{
                    typeClass:"alert-danger",
                    title: "Fehler"
                },
                warning:{
                    typeClass:"alert-warning",
                    title: "Achtung"
                },
                success:{
                    typeClass:"alert-success",
                    title: "Erfolg"
                }
            };
            for(var type in messageTypes){
                messages[type].forEach(function (msg) {
                    var htmlMsg=$('<div class="alert '+messageTypes[type]["typeClass"]+' alert-dismissible" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span>' +
                    '</button>' +
                    '<strong>'+messageTypes[type]["title"]+'</strong> ' + msg+
                    '</div>');
                    $("main").prepend(htmlMsg);
                });
            }
        }
    });

    /** Neue Frucht **/
    $("#newFruitPhoto").on("click",function(e){
        e.preventDefault();
        $("#fruitphoto").trigger("click");
    });

    $("#fruitphoto").on("change",function(){
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
                $("#fruit_label").attr("src","data:image/jpg;base64,"+data["preview"]);
            }
        });
    });
});
