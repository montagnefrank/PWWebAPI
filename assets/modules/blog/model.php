<?php
/////////////////////////////////////////////////////////////////////////////// BLOG MODEL
?>
<script>
    $("#newBlogHeaderImg,#newBlogPostImg,#newheaderimgBlog,#newpostimgBlog,#blolistbgimg").fileinput({
        showUpload: false,
        showCaption: false,
        browseClass: "btn btn-info",
        fileType: "jpg"
    });

    $(document).on("click", ".addnewbtn", function (event) {
        event.preventDefault();
        $.when(
                $(".bloglist,.editblog").slideUp("slow")
                ).then(function () {
            $(".newblog,.showlistbtn").slideDown("slow");
        });
    });

    $(document).on("click", ".savenewblog", function (event) {
        event.preventDefault();
        var self = this;
        $(self).find("span, img").toggle();
        $('#newBlogTitle-error,#newBlogSubtitle-error,#newBlogCat-error').remove();
        setTimeout(function () {
            if ($('#newBlogTitle,#newBlogSubtitle,#newBlogCat').valid()) {
                if ($('#newBlogHeaderImg').get(0).files.length > 0
                        && $('#newBlogPostImg').get(0).files.length > 0) {
                    if ($('#htmlentrada').code().length > 0) {
                        var formData = new FormData();
                        formData.append('addnewBlog', 'true');
                        formData.append('titleBlog', $('#newBlogTitle').val());
                        formData.append('subtitleBlog', $('#newBlogSubtitle').val());
                        formData.append('catBlog', $('#newBlogCat').val());
                        formData.append('ebookBlog', $('#newBlogEbook').val());
                        formData.append('headerimgBlog', $('#newBlogHeaderImg').get(0).files[0]);
                        formData.append('postimgBlog', $('#newBlogPostImg').get(0).files[0]);
                        formData.append('entryBlog', $('#htmlentrada').code());
                        $.ajax({
                            url: 'assets/modules/blog/control.php',
                            type: 'POST',
                            data: formData,
                            async: false,
                            success: function (data) {
                                location.reload();
                            },
                            error: function (error) {
                                noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                $(".savenewproject span,.savenewproject img").toggle();
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    } else {
                        $(self).find("span, img").toggle();
                        $(".customalert_text").html('No dejes los campos HTML vacios');
                        $(".customalert").animate({width: 'toggle'}, 600);
                    }
                } else {
                    $(self).find("span, img").toggle();
                    $(".customalert_text").html('Recuerda cargar todas las imagenes');
                    $(".customalert").animate({width: 'toggle'}, 600);
                }
            } else {
                $(self).find("span, img").toggle();
                $(".customalert_text").html('Los campos no pueden estar vacios');
                $(".customalert").animate({width: 'toggle'}, 600);
            }
        }, 1000);
    });

    $(document).on("click", ".showlistbtn", function (event) {
        event.preventDefault();
        $.when(
                $(".editblog,.showlistbtn,.newblog").slideUp("slow"))
                .then(function () {
                    $(".bloglist").slideDown("slow");
                });
    });

    $(document).on("click", ".editblogbtn", function (event) {
        event.preventDefault();
        var idBlog, self, htmlBlog, blogdata;
        idBlog = $(this).parent().parent().find(".idBlogContainer").html();
        self = this;
        $.when(
                $(self).find(".beforeLoad,.loading_img").toggle())
                .then(function () {
                    setTimeout(function () {
                        var formData = new FormData();
                        formData.append('getblogdata', 'true');
                        formData.append('idBlog', idBlog);
                        $.ajax({
                            url: 'assets/modules/blog/control.php',
                            type: 'POST',
                            data: formData,
                            dataType: "json",
                            success: function (data) {
                                if (data.msg == 'ok') {
                                    blogdata = data.Blog;
                                    $.post('assets/modules/blog/control.php', {gethtmlblog: 'true', idBlog: idBlog}, function (data) {
                                        htmlBlog = data;
                                        if (blogdata.statusBlog == '1') {
                                            $(".editblogcheckbox").html('<input type="checkbox" class="switch" name=""  value="1" checked="checked" /><span></span>');
                                        } else {
                                            $(".editblogcheckbox").html('<input type="checkbox" class="switch" name=""  value="0" /><span></span>');
                                        }
                                        $(".editidBlogContainer").html(blogdata.idBlog);
                                        $("#editTitleBlog").val(blogdata.titleBlog);
                                        $("#editCatBlog").val(blogdata.categoryBlog);
                                        $("#editEbookBlog").val(blogdata.ebookBlog);
                                        $("#editSubtitleBlog").val(blogdata.subtitleBlog);
                                        $(".headerimage").html("<img src='../assets/img/blog/" + blogdata.headerimgBlog + ".jpg' style='width: 100%;' />");
                                        $(".postimage").html("<img src='../assets/img/blog/" + blogdata.postimgBlog + ".jpg' style='width: 100%;' />");
                                        $("#edithtmlentrada").code(htmlBlog);
                                        $.when(
                                                $(".bloglist,.newblogt").slideUp("slow"),
                                                $(self).find(".beforeLoad,.loading_img").toggle())
                                                .then(function () {
                                                    $(".editblog,.showlistbtn").slideDown("slow");
                                                });
                                    });
                                }
                                if (data.msg == 'notok') {
                                    noty({text: 'Error, No pudimos encontrar el proyecto seleccionado', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                    $(self).find(".beforeLoad,.loading_img").toggle();
                                }
                            },
                            error: function (error) {
                                noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                $(self).find(".beforeLoad,.loading_img").toggle();
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }, 1000);
                });
    });

    $(document).on("click", "#editTitlesBlogBtn", function (event) {
        event.preventDefault();
        $.when(
                $("#editTitlesBlogBtn .beforeLoad").toggle(),
                $("#editTitlesBlogBtn .loading_img").toggle(),
                $(this).attr("id", "editTitlesBlogBtn_clicked"))
                .then(function () {
                    setTimeout(function () {
                        var formData = new FormData();
                        formData.append('editblogtitles', 'true');
                        formData.append('idBlog', $('.editidBlogContainer').html());
                        formData.append('titleBlog', $('#editTitleBlog').val());
                        formData.append('categoryBlog', $('#editCatBlog').val());
                        formData.append('ebookBlog', $('#editEbookBlog').val());
                        formData.append('subtitleBlog', $('#editSubtitleBlog').val());

                        $.ajax({
                            url: 'assets/modules/blog/control.php',
                            type: 'POST',
                            data: formData,
                            success: function (data) {
                                location.reload();
                            },
                            error: function (error) {
                                noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                $("#editTitlesBlogBtn_clicked .beforeLoad,#editTitlesBlogBtn_clicked .loading_img").toggle();
                                $("#editTitlesBlogBtn_clicked").attr("id", "editTitlesBlogBtn");
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }, 1000);
                });
    });

    $(document).on("click", ".headerimage", function (event) {
        event.preventDefault();
        $.when(
                $(".headerimage,.uploadpostimage").slideUp("slow")
                ).then(function () {
            $(".uploadheader,.postimage").slideDown("slow");
        });
    });

    $(document).on("click", ".postimage", function (event) {
        event.preventDefault();
        $.when($(".postimage,.uploadheader").slideUp("slow")).then(function () {
            $(".uploadpostimage,.headerimage").slideDown("slow");
        });
    });

    $(document).on("click", ".editblogcheckbox", function (event) {
//        event.preventDefault();
        var self = this;
        var status = '';
        setTimeout(function () {
            if ($(self).find('input').is(':checked')) {
                status = '1';
            } else {
                status = '0';
            }
            var formData = new FormData();
            formData.append('changestatusBlog', 'true');
            formData.append('statusBlog', status);
            formData.append('idBlog', $('.editidBlogContainer').html());

            $.ajax({
                url: 'assets/modules/blog/control.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    $(".customalert_text").html(data);
                    $(".customalert").animate({width: 'show'}, 600);
                },
                error: function (error) {
                    noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }, 2000);
    });

    $(document).on("click", "#newheaderimgBlogbtn", function (event) {
        event.preventDefault();
        var btnself = this;
        $.when(
                $(btnself).find(".beforeLoad,.loading_img").toggle())
                .then(function () {
                    setTimeout(function () {
                        if ($('#newheaderimgBlog').get(0).files.length > 0) {
                            var formData = new FormData();
                            formData.append('newimgheaderBlog', 'true');
                            formData.append('imgheaderBlog', $('#newheaderimgBlog').get(0).files[0]);
                            formData.append('idBlog', $('.editidBlogContainer').html());

                            $.ajax({
                                url: 'assets/modules/blog/control.php',
                                type: 'POST',
                                data: formData,
                                success: function (data) {
                                    $(".customalert_text").html(data);
                                    $(".customalert").animate({width: 'toggle'}, 600);
                                    $(btnself).find(".beforeLoad,.loading_img").toggle();
                                    $.when(
                                            $(".editblog,.showlistbtn,.newblog").slideUp("slow")
                                            ).then(function () {
                                        $("#newheaderimgBlog").fileinput('reset');
                                        $(".bloglist").slideDown("slow");
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

    $(document).on("click", "#newpostimgBlogbtn", function (event) {
        event.preventDefault();
        var btnself = this;
        $.when(
                $(btnself).find(".beforeLoad,.loading_img").toggle())
                .then(function () {
                    setTimeout(function () {
                        if ($('#newpostimgBlog').get(0).files.length > 0) {
                            var formData = new FormData();
                            formData.append('newimgpostBlog', 'true');
                            formData.append('imgpostBlog', $('#newpostimgBlog').get(0).files[0]);
                            formData.append('idBlog', $('.editidBlogContainer').html());

                            $.ajax({
                                url: 'assets/modules/blog/control.php',
                                type: 'POST',
                                data: formData,
                                success: function (data) {
                                    $(".customalert_text").html(data);
                                    $(".customalert").animate({width: 'toggle'}, 600);
                                    $(btnself).find(".beforeLoad,.loading_img").toggle();
                                    $.when(
                                            $(".editblog,.showlistbtn,.newblog").slideUp("slow")
                                            ).then(function () {
                                        $("#newpostimgBlog").fileinput('reset');
                                        $(".bloglist").slideDown("slow");
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

    $(document).on("click", ".saveedithtmlboxes", function (event) {
        event.preventDefault();
        $(".saveedithtmlboxes span,.saveedithtmlboxes img").toggle();
        setTimeout(function () {
            if ($('#edithtmlentrada').code().length > 0) {
                var formData = new FormData();
                formData.append('edithtmlboxes', 'true');
                formData.append('entryBlog', $('#edithtmlentrada').code());
                formData.append('idBlog', $('.editidBlogContainer').html());
                $.ajax({
                    url: 'assets/modules/blog/control.php',
                    type: 'POST',
                    data: formData,
                    async: false,
                    success: function (data) {
                        $(".saveedithtmlboxes span,.saveedithtmlboxes img").toggle();
                        $(".customalert_text").html(data);
                        $(".customalert").animate({width: 'toggle'}, 600);
                    },
                    error: function (error) {
                        noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                        $(".saveedithtmlboxes span,.saveedithtmlboxes img").toggle();
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            } else {
                $(".saveedithtmlboxes span,.saveedithtmlboxes img").toggle();
                $(".customalert_text").html('No dejes los campos HTML vacios');
                $(".customalert").animate({width: 'toggle'}, 600);
            }
        }, 1000);
    });

    $(document).on("click", ".deleteblog", function (event) {
        event.preventDefault();
        var id = $(".editidBlogContainer").html();
        var self = this;
        noty({
            text: 'Seguro que quieres eliminar la entrada de Blog?',
            layout: 'topRight',
            buttons: [
                {addClass: 'btn btn-success btn-clean', text: 'Si', onClick: function ($noty) {
                        $(self).find("span, img").toggle();
                        setTimeout(function () {
                            var formData = new FormData();
                            formData.append('deleteBlog', 'true');
                            formData.append('deleteid', id);

                            $.ajax({
                                url: 'assets/modules/blog/control.php',
                                type: 'POST',
                                data: formData,
                                success: function (data) {
                                    location.reload();
                                },
                                cache: false,
                                contentType: false,
                                processData: false
                            });
                        }, 1000);
                    }
                },
                {addClass: 'btn btn-danger btn-clean', text: 'Cancelar', onClick: function ($noty) {
                        $noty.close();
                    }
                }
            ]
        }).setTimeout(4000);
    });

    $(document).on("click", "#blolistbgimgbtn", function (event) {
        event.preventDefault();
        var btnself = this;
        $.when(
                $(btnself).find(".beforeLoad,.loading_img").toggle())
                .then(function () {
                    setTimeout(function () {
                        if ($('#blolistbgimg').get(0).files.length > 0
                                && $('#BloglistTitle').val().length > 0
                                && $('#BloglistSubtitle').val().length > 0) {
                            var formData = new FormData();
                            formData.append('newBloglistImg', 'true');
                            formData.append('bloglistImg', $('#blolistbgimg').get(0).files[0]);
                            formData.append('titleBloglist', $('#BloglistTitle').val());
                            formData.append('sutitleBloglist', $('#BloglistSubtitle').val());

                            $.ajax({
                                url: 'assets/modules/blog/control.php',
                                type: 'POST',
                                data: formData,
                                success: function (data) {
                                    location.reload();
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
                            $(".customalert_text").html('Debes seleccionar una imagen, y rellenar los campos');
                            $(".customalert").animate({width: 'toggle'}, 600);
                        }
                    }, 1000);
                });
    });
</script>