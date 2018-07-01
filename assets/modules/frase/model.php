<?php
/////////////////////////////////////////////////////////////////////////////// PENSAMIENTO MODEL
?>
<script>

    $("#file-simple,#file-simple2,#file-simple3").fileinput({
        showUpload: false,
        showCaption: false,
        browseClass: "btn btn-info",
        fileType: "jpg"
    });

    $(document).on("click", "#updatefrase", function (event) {
        event.preventDefault();
        $.when(
                $("#updatefrase .beforeLoad").toggle(),
                $("#updatefrase .loading_img").toggle(),
                $(this).attr("id", "updatefrase_clicked"))
                .then(function () {
                    setTimeout(function () {
                        var formData = new FormData();
                        formData.append('updatefrase', 'true');
                        formData.append('text', $('#frasetext').val());

                        $.ajax({
                            url: 'assets/modules/frase/control.php',
                            type: 'POST',
                            data: formData,
                            async: false,
                            success: function (data) {
                                location.reload();
                            },
                            error: function (error) {
                                noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                $("#updatefrase_clicked .beforeLoad").toggle();
                                $("#updatefrase_clicked .loading_img").toggle();
                                $("#updatefrase_clicked").attr("id", "updatefrase");
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }, 1000);
                });
    });
    
    $(document).on("click", "#submitnewlogo", function (event) {
        event.preventDefault();
        $.when(
                $("#submitnewlogo .beforeLoad").toggle(),
                $("#submitnewlogo .loading_img").toggle(),
                $(this).attr("id", "submitnewlogo_clicked"))
                .then(function () {
                    setTimeout(function () {
                        $("#submitnewlogo_clicked").click();
                    }, 1000);
                });
    });
    
    $(document).on("click", "#bgimagefrase", function (event) {
        event.preventDefault();
        $.when(
                $("#bgimagefrase .beforeLoad").toggle(),
                $("#bgimagefrase .loading_img").toggle(),
                $(this).attr("id", "bgimagefrase_clicked"))
                .then(function () {
                    setTimeout(function () {
                        var formData = new FormData();
                        formData.append('bgimagefrase', 'true');
                        // Attach file
                        formData.append('slide1file', $('input[type=file]')[1].files[0]);

                        $.ajax({
                            url: 'assets/modules/frase/control.php',
                            type: 'POST',
                            data: formData,
                            async: false,
                            success: function (data) {
                                location.reload();
                            },
                            error: function (error) {
                                noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                $("#bgimagefrase_clicked .beforeLoad").toggle();
                                $("#bgimagefrase_clicked .loading_img").toggle();
                                $("#bgimagefrase_clicked").attr("id", "bgimagefrase");
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }, 1000);
                });
    });
</script>