<?php
/////////////////////////////////////////////////////////////////////////////// PORTFOLIO MODEL
?>
<script>
    $("#uploadnewcustomImg,#newPortHeaderImg,#newPortBannerImg,#newPortPostImg,#newsliderimgPort,#newplanosimgPort,#newacabadosimgPort,#newheaderimgPort,#newpostimgPort,#newbannerimgPort").fileinput({
        showUpload: false,
        showCaption: false,
        browseClass: "btn btn-info",
        fileType: "jpg"
    });

    $(document).on("click", ".editprojectbtn", function (event) {
        event.preventDefault();
        var idPort, self, detailsPort, descriptionPort, acabadostextPort, portdata;
        idPort = $(this).parent().parent().find(".idPortContainer").html();
        self = this;
        $.when(
                $(self).find(".beforeLoad").toggle(),
                $(self).find(".loading_img").toggle())
                .then(function () {
                    setTimeout(function () {
                        var formData = new FormData();
                        formData.append('getportdata', 'true');
                        formData.append('idPort', idPort);
                        $.ajax({
                            url: 'assets/modules/port/control.php',
                            type: 'POST',
                            data: formData,
                            dataType: "json",
                            success: function (data) {
                                if (data.msg == 'ok') {
                                    portdata = data.Port;
                                    $.post('assets/modules/port/control.php', {getdetailsport: 'true', idPort: idPort}, function (data) {
                                        detailsPort = data;
                                        $.post('assets/modules/port/control.php', {getdescriptionPort: 'true', idPort: idPort}, function (data) {
                                            descriptionPort = data;
                                            $.post('assets/modules/port/control.php', {getacabadostextPort: 'true', idPort: idPort}, function (data) {
                                                acabadostextPort = data;
                                                if (portdata.statusPort == '1') {
                                                    $(".editporjectcheckbox").html('<input type="checkbox" class="switch" name=""  value="1" checked="checked" /><span></span>');
                                                } else {
                                                    $(".editporjectcheckbox").html('<input type="checkbox" class="switch" name=""  value="0" /><span></span>');
                                                }
                                                $(".editidPortContainer").html(portdata.idPort);
                                                $("#editTitlePort").val(portdata.titlePort);
                                                $("#editSubtitlePort").val(portdata.subtitlePort);
                                                $(".headerimage").html("<img src='../assets/img/port/" + portdata.bannerimgPost + ".jpg' style='width: 100%;' />");
                                                $(".bannerimage").html("<img src='../assets/img/port/" + portdata.fullwidthimgPort + ".jpg' style='width: 100%;' />");
                                                $(".postimage").html("<img src='../assets/img/port/" + portdata.postimgPort + ".jpg' style='width: 100%;' />");
                                                $("#edithtmlresumen").code(descriptionPort);
                                                $("#edithtmlacabados").code(acabadostextPort);
                                                $("#edithtmldetalles").code(detailsPort);

                                                $.post('assets/modules/port/control.php', {getImgSlider: 'true', idPort: idPort}, function (data) {
                                                    $(".imgsliderContainer").html(data);
                                                });
                                                $.post('assets/modules/port/control.php', {getPlanosSlider: 'true', idPort: idPort}, function (data) {
                                                    $(".planosSliderContainer").html(data);
                                                });
                                                $.post('assets/modules/port/control.php', {getAcabadosSlider: 'true', idPort: idPort}, function (data) {
                                                    $(".acabadosSliderContainer").html(data);
                                                });
                                                $.when(
                                                        $(".projectlist,.newproject").slideUp("slow"),
                                                        $(self).find(".beforeLoad").toggle(),
                                                        $(self).find(".loading_img").toggle()
                                                        ).then(function () {
                                                    $(".editproject,.showlistbtn").slideDown("slow");
                                                });
                                            });
                                        });
                                    });
                                }
                                if (data.msg == 'notok') {
                                    noty({text: 'Error, No pudimos encontrar el proyecto seleccionado', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                    $(self).find(".beforeLoad").toggle();
                                    $(self).find(".loading_img").toggle();
                                }
                            },
                            error: function (error) {
                                noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                $(self).find(".beforeLoad").toggle();
                                $(self).find(".loading_img").toggle();
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }, 1000);
                });
    });

    $(document).on("click", ".addnewbtn", function (event) {
        event.preventDefault();
        $.when(
                $(".projectlist,.editproject").slideUp("slow")
                ).then(function () {
            $(".newproject,.showlistbtn").slideDown("slow");
        });
    });

    $(document).on("click", ".showlistbtn", function (event) {
        event.preventDefault();
        $.when(
                $(".editproject,.showlistbtn,.newproject").slideUp("slow")
                ).then(function () {
            $(".projectlist").slideDown("slow");
        });
    });

    $(document).on("click", ".headerimage", function (event) {
        event.preventDefault();
        $.when(
                $(".headerimage,.uploadpostimage,.uploadbannerimage").slideUp("slow")
                ).then(function () {
            $(".uploadheader,.bannerimage,.postimage").slideDown("slow");
        });
    });

    $(document).on("click", ".bannerimage", function (event) {
        event.preventDefault();
        $.when($(".bannerimage,.uploadheader,.uploadpostimage").slideUp("slow")).then(function () {
            $(".uploadbannerimage,.postimage,.headerimage").slideDown("slow");
        });
    });

    $(document).on("click", ".postimage", function (event) {
        event.preventDefault();
        $.when($(".postimage,.uploadbannerimage,.uploadheader").slideUp("slow")).then(function () {
            $(".uploadpostimage,.bannerimage,.headerimage").slideDown("slow");
        });
    });

    $(document).on("click", ".savenewproject", function (event) {
        event.preventDefault();
        $(".savenewproject span,.savenewproject img").toggle();
        $('#newPortTitle-error,#newPortSubtitle-error').remove();
        setTimeout(function () {
            if ($('#newPortTitle,#newPortSubtitle').valid()) {
                if ($('#newPortHeaderImg').get(0).files.length > 0
                        && $('#newPortBannerImg').get(0).files.length > 0
                        && $('#newPortPostImg').get(0).files.length > 0) {
                    if ($('#htmldetalles').code().length > 0
                            && $('#htmlacabados').code().length > 0
                            && $('#htmlresumen').code().length > 0) {
                        var formData = new FormData();
                        formData.append('addnewPort', 'true');
                        formData.append('titlePort', $('#newPortTitle').val());
                        formData.append('subtitlePort', $('#newPortSubtitle').val());
                        formData.append('postimgPort', $('#newPortPostImg').get(0).files[0]);
                        formData.append('bannerimgPost', $('#newPortHeaderImg').get(0).files[0]);
                        formData.append('details', $('#htmldetalles').code());
                        formData.append('descriptionPort', $('#htmlresumen').code());
                        formData.append('acabadostextPort', $('#htmlacabados').code());
                        formData.append('fullwidthimgPort', $('#newPortBannerImg').get(0).files[0]);
                        $.ajax({
                            url: 'assets/modules/port/control.php',
                            type: 'POST',
                            data: formData,
                            async: false,
                            success: function (data) {
                                location.reload();
//                                console.log(data);
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
                        $(".savenewproject span,.savenewproject img").toggle();
                        $(".customalert_text").html('No dejes los campos HTML vacios');
                        $(".customalert").animate({width: 'toggle'}, 600);
                    }
                } else {
                    $(".savenewproject span,.savenewproject img").toggle();
                    $(".customalert_text").html('Recuerda cargar todas las imagenes');
                    $(".customalert").animate({width: 'toggle'}, 600);
                }
            } else {
                $(".savenewproject span,.savenewproject img").toggle();
                $(".customalert_text").html('Los campos no pueden estar vacios');
                $(".customalert").animate({width: 'toggle'}, 600);
            }
        }, 1000);
    });

    $(document).on("click", "#editTitlesPortBtn", function (event) {
        event.preventDefault();
        $.when(
                $("#editTitlesPortBtn .beforeLoad").toggle(),
                $("#editTitlesPortBtn .loading_img").toggle(),
                $(this).attr("id", "editTitlesPortBtn_clicked"))
                .then(function () {
                    setTimeout(function () {
                        var formData = new FormData();
                        formData.append('editproyectitles', 'true');
                        formData.append('idPort', $('.editidPortContainer').html());
                        formData.append('titlePort', $('#editTitlePort').val());
                        formData.append('subtitlePort', $('#editSubtitlePort').val());

                        $.ajax({
                            url: 'assets/modules/port/control.php',
                            type: 'POST',
                            data: formData,
                            async: false,
                            success: function (data) {
                                location.reload();
                            },
                            error: function (error) {
                                noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                $("#editTitlesPortBtn_clicked .beforeLoad,#editTitlesPortBtn_clicked .loading_img").toggle();
                                $("#editTitlesPortBtn_clicked").attr("id", "editTitlesPortBtn");
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }, 1000);
                });
    });

    $(document).on("click", ".gallery-item-remove", function (event) {
        event.preventDefault();
        var id = $(this).find(".imageidPortContainer").html();
        var deletetype = $(this).find(".tableSliderContainer").html();
        var self = this;
        noty({
            text: 'Seguro que quieres eliminar?',
            layout: 'topRight',
            buttons: [
                {addClass: 'btn btn-success btn-clean', text: 'Si', onClick: function ($noty) {
                        var formData = new FormData();
                        formData.append('deletesliderimg', 'true');
                        formData.append('deleteid', id);
                        formData.append('deletetype', deletetype);

                        $.ajax({
                            url: 'assets/modules/port/control.php',
                            type: 'POST',
                            data: formData,
                            async: false,
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

    $(document).on("click", ".gallery-item", function (event) {
        event.preventDefault();
    });

    $(document).on("click", "#newsliderimgPortbtn", function (event) {
        event.preventDefault();
        var btnself = this;
        $.when(
                $(btnself).find(".beforeLoad,.loading_img").toggle())
                .then(function () {
                    setTimeout(function () {
                        if ($('#newsliderimgPort').get(0).files.length > 0) {
                            var formData = new FormData();
                            formData.append('newimgsliderPort', 'true');
                            formData.append('imgsliderPort', $('#newsliderimgPort').get(0).files[0]);
                            formData.append('idPort', $('.editidPortContainer').html());

                            $.ajax({
                                url: 'assets/modules/port/control.php',
                                type: 'POST',
                                data: formData,
                                success: function (data) {
                                    $(".customalert_text").html(data);
                                    $(".customalert").animate({width: 'toggle'}, 600);
                                    $(btnself).find(".beforeLoad,.loading_img").toggle();
                                    $.when(
                                            $(".editproject,.showlistbtn,.newproject").slideUp("slow")
                                            ).then(function () {
                                        $("#newsliderimgPort").fileinput('reset');
                                        $(".projectlist").slideDown("slow");
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

    $(document).on("click", "#newplanosimgPortbtn", function (event) {
        event.preventDefault();
        var btnself = this;
        $.when(
                $(btnself).find(".beforeLoad,.loading_img").toggle())
                .then(function () {
                    setTimeout(function () {
                        if ($('#newplanosimgPort').get(0).files.length > 0) {
                            var formData = new FormData();
                            formData.append('newimgplanosPort', 'true');
                            formData.append('imgplanosPort', $('#newplanosimgPort').get(0).files[0]);
                            formData.append('idPort', $('.editidPortContainer').html());

                            $.ajax({
                                url: 'assets/modules/port/control.php',
                                type: 'POST',
                                data: formData,
                                success: function (data) {
                                    $(".customalert_text").html(data);
                                    $(".customalert").animate({width: 'toggle'}, 600);
                                    $(btnself).find(".beforeLoad,.loading_img").toggle();
                                    $.when(
                                            $(".editproject,.showlistbtn,.newproject").slideUp("slow")
                                            ).then(function () {
                                        $("#newplanosimgPort").fileinput('reset');
                                        $(".projectlist").slideDown("slow");
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

    $(document).on("click", "#newacabadosimgPortbtn", function (event) {
        event.preventDefault();
        var btnself = this;
        $.when(
                $(btnself).find(".beforeLoad,.loading_img").toggle())
                .then(function () {
                    setTimeout(function () {
                        if ($('#newacabadosimgPort').get(0).files.length > 0) {
                            var formData = new FormData();
                            formData.append('newimgacabadosPort', 'true');
                            formData.append('imgacabadosPort', $('#newacabadosimgPort').get(0).files[0]);
                            formData.append('idPort', $('.editidPortContainer').html());

                            $.ajax({
                                url: 'assets/modules/port/control.php',
                                type: 'POST',
                                data: formData,
                                success: function (data) {
                                    $(".customalert_text").html(data);
                                    $(".customalert").animate({width: 'toggle'}, 600);
                                    $(btnself).find(".beforeLoad,.loading_img").toggle();
                                    $.when(
                                            $(".editproject,.showlistbtn,.newproject").slideUp("slow")
                                            ).then(function () {
                                        $("#newacabadosimgPort").fileinput('reset');
                                        $(".projectlist").slideDown("slow");
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

    $(document).on("click", "#newheaderimgPortbtn", function (event) {
        event.preventDefault();
        var btnself = this;
        $.when(
                $(btnself).find(".beforeLoad,.loading_img").toggle())
                .then(function () {
                    setTimeout(function () {
                        if ($('#newheaderimgPort').get(0).files.length > 0) {
                            var formData = new FormData();
                            formData.append('newimgheaderPort', 'true');
                            formData.append('imgheaderPort', $('#newheaderimgPort').get(0).files[0]);
                            formData.append('idPort', $('.editidPortContainer').html());

                            $.ajax({
                                url: 'assets/modules/port/control.php',
                                type: 'POST',
                                data: formData,
                                success: function (data) {
                                    $(".customalert_text").html(data);
                                    $(".customalert").animate({width: 'toggle'}, 600);
                                    $(btnself).find(".beforeLoad,.loading_img").toggle();
                                    $.when(
                                            $(".editproject,.showlistbtn,.newproject").slideUp("slow")
                                            ).then(function () {
                                        $("#newheaderimgPort").fileinput('reset');
                                        $(".projectlist").slideDown("slow");
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

    $(document).on("click", "#newpostimgPortbtn", function (event) {
        event.preventDefault();
        var btnself = this;
        $.when(
                $(btnself).find(".beforeLoad,.loading_img").toggle())
                .then(function () {
                    setTimeout(function () {
                        if ($('#newpostimgPort').get(0).files.length > 0) {
                            var formData = new FormData();
                            formData.append('newimgpostPort', 'true');
                            formData.append('imgpostPort', $('#newpostimgPort').get(0).files[0]);
                            formData.append('idPort', $('.editidPortContainer').html());

                            $.ajax({
                                url: 'assets/modules/port/control.php',
                                type: 'POST',
                                data: formData,
                                success: function (data) {
                                    $(".customalert_text").html(data);
                                    $(".customalert").animate({width: 'toggle'}, 600);
                                    $(btnself).find(".beforeLoad,.loading_img").toggle();
                                    $.when(
                                            $(".editproject,.showlistbtn,.newproject").slideUp("slow")
                                            ).then(function () {
                                        $("#newpostimgPort").fileinput('reset');
                                        $(".projectlist").slideDown("slow");
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

    $(document).on("click", "#newbannerimgPortbtn", function (event) {
        event.preventDefault();
        var btnself = this;
        $.when(
                $(btnself).find(".beforeLoad,.loading_img").toggle())
                .then(function () {
                    setTimeout(function () {
                        if ($('#newbannerimgPort').get(0).files.length > 0) {
                            var formData = new FormData();
                            formData.append('newimgbannerPort', 'true');
                            formData.append('imgbannerPort', $('#newbannerimgPort').get(0).files[0]);
                            formData.append('idPort', $('.editidPortContainer').html());

                            $.ajax({
                                url: 'assets/modules/port/control.php',
                                type: 'POST',
                                data: formData,
                                success: function (data) {
                                    $(".customalert_text").html(data);
                                    $(".customalert").animate({width: 'toggle'}, 600);
                                    $(btnself).find(".beforeLoad,.loading_img").toggle();
                                    $.when(
                                            $(".editproject,.showlistbtn,.newproject").slideUp("slow")
                                            ).then(function () {
                                        $("#newbannerimgPort").fileinput('reset');
                                        $(".projectlist").slideDown("slow");
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

    $(document).on("click", ".editporjectcheckbox", function (event) {
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
            formData.append('changestatusPort', 'true');
            formData.append('statusPort', status);
            formData.append('idPort', $('.editidPortContainer').html());

            $.ajax({
                url: 'assets/modules/port/control.php',
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

    $(document).on("click", ".saveedithtmlboxes", function (event) {
        event.preventDefault();
        $(".saveedithtmlboxes span,.saveedithtmlboxes img").toggle();
        setTimeout(function () {
            if ($('#edithtmlresumen').code().length > 0
                    && $('#edithtmlacabados').code().length > 0
                    && $('#edithtmldetalles').code().length > 0) {
                var formData = new FormData();
                formData.append('edithtmlboxes', 'true');
                formData.append('details', $('#edithtmldetalles').code());
                formData.append('descriptionPort', $('#edithtmlresumen').code());
                formData.append('acabadostextPort', $('#edithtmlacabados').code());
                formData.append('idPort', $('.editidPortContainer').html());
                $.ajax({
                    url: 'assets/modules/port/control.php',
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

    $(document).on("click", "#uploadnewcustomImgbtn", function (event) {
        event.preventDefault();
        var btnself = this;
        $.when(
                $(btnself).find(".beforeLoad,.loading_img").toggle())
                .then(function () {
                    setTimeout(function () {
                        if ($('#uploadnewcustomImg').get(0).files.length > 0) {
                            var formData = new FormData();
                            formData.append('newimgcustom', 'true');
                            formData.append('imgcustom', $('#uploadnewcustomImg').get(0).files[0]);

                            $.ajax({
                                url: 'assets/modules/port/control.php',
                                type: 'POST',
                                data: formData,
                                success: function (data) {
                                    $(".customalert_text").html(data);
                                    $(".customalert").animate({width: 'toggle'}, 600);
                                    $(btnself).find(".beforeLoad,.loading_img").toggle();
                                    $.when(
                                            $(".editproject,.showlistbtn,.newproject").slideUp("slow")
                                            ).then(function () {
                                        $("#uploadnewcustomImg").fileinput('reset');
                                        $(".projectlist").slideDown("slow");
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

    $(document).on("click", ".deleteproject", function (event) {
        event.preventDefault();
        var id = $(".editidPortContainer").html();
        var self = this;
        noty({
            text: 'Seguro que quieres eliminar el Proyecto?',
            layout: 'topRight',
            buttons: [
                {addClass: 'btn btn-success btn-clean', text: 'Si', onClick: function ($noty) {
                        $(self).find("span, img").toggle();
                        setTimeout(function () {
                            var formData = new FormData();
                            formData.append('deletePort', 'true');
                            formData.append('deleteid', id);

                            $.ajax({
                                url: 'assets/modules/port/control.php',
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
</script>