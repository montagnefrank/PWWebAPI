<?php
/////////////////////////////////////////////////////////////////////////////// GALLERY MODEL
?>
<script>
    $("#newgalleryImg").fileinput({
        showUpload: false,
        showCaption: false,
        browseClass: "btn btn-info",
        fileType: "jpg"
    });

    $(document).on("click", "#newgalleryImgbtn", function (event) {
        event.preventDefault();
        var btnself = this;
        $.when(
                $(btnself).find(".beforeLoad,.loading_img").toggle())
                .then(function () {
                    setTimeout(function () {
                        if ($('#newgalleryImg').get(0).files.length > 0) {
                            var formData = new FormData();
                            formData.append('newimgGallery', 'true');
                            formData.append('imgGallery', $('#newgalleryImg').get(0).files[0]);

                            $.ajax({
                                url: 'assets/modules/gal/control.php',
                                type: 'POST',
                                data: formData,
                                success: function (data) {
                                    $(".customalert_text").html(data);
                                    $(".customalert").animate({width: 'toggle'}, 600);
                                    $(btnself).find(".beforeLoad,.loading_img").toggle();
                                    $.when(
                                            $(".editproject,.showlistbtn,.newproject").slideUp("slow")
                                            ).then(function () {
                                        $("#newgalleryImg").fileinput('reset');
                                    });
                                },
                                error: function (error) {
                                    noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                    $(btnself).find(".beforeLoad,.loading_img").toggle();
                                },
                                cache: false,
                                contentType: false,
                                processData: false
                            });
                        } else {
                            $(btnself).find(".beforeLoad,.loading_img").toggle();
                            $(".customalert_text").html('Debes seleccionar una imagen primero');
                            $(".customalert").animate({width: 'toggle'}, 600);
                        }
                    }, 1000);
                });
    });

    $(document).on("click", ".gallery-item", function (event) {
        event.preventDefault();
        var selectedimg = $(this).find('img').attr('src');
        var selectedcode = $(this).find('.imageidPortContainer').html();
        $.when(
                $(".selectedimage,.selectedcode,.selectedurl").slideUp("slow"))
                .then(function () {
                    $(".selectedimage").html("<img src='" + selectedimg + "' style='width: 100%;' />");
                    $(".selectedcode").html('<code>&lt;img src="http://www.<?php
                        $domain = apache_request_headers();
                        echo $domain['Host'];
                        ?>/img/' + selectedcode + '" style="width: 100%;" /&gt; </code>');
                    $(".selectedurl").html("<blockquote><p>http://www.<?php echo $domain['Host']; ?>/img/" + selectedcode + "</p></blockquote>");
                    $(".selectedimage,.selectedcode,.selectedurl").slideDown("slow");
                });
    });

    $(document).on("click", ".removethisimg", function (event) {
        event.preventDefault();
        var deleteimg = $(this).find('.imageidPortContainer').html();
        var self = this;
        noty({
            text: 'Seguro que quieres eliminar?',
            layout: 'topRight',
            buttons: [
                {addClass: 'btn btn-success btn-clean', text: 'Si', onClick: function ($noty) {
                        var formData = new FormData();
                        formData.append('deleteImg', 'true');
                        formData.append('deleteid', deleteimg);

                        $.ajax({
                            url: 'assets/modules/gal/control.php',
                            type: 'POST',
                            data: formData,
                            success: function (data) {
                                setTimeout(function () {
                                    $(self).parent().parent().parent().parent().toggle("slow");
                                    $(".customalert_text").html(data);
                                    $(".customalert").animate({width: 'toggle'}, 600);
                                }, 1000);
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                        ;
                    }
                },
                {addClass: 'btn btn-danger btn-clean', text: 'Cancelar', onClick: function ($noty) {
                        $noty.close();
                    }
                }
            ]
        }).setTimeout(4000);
    });
</script>