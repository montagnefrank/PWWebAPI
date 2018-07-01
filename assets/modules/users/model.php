<?php
/////////////////////////////////////////////////////////////////////////////// USERS MODEL
?>
<script>
    $(document).ready(function () {

        $.post('assets/modules/users/control.php', {getUsers: 'true'}, function (data) {
            $('.usersList').html(data);
        });
    });

    $(document).on("click", "#savenew_btn", function (event) {
        event.preventDefault();
        var self, pass, confirmpass, rol, est;
        self = this;
        $.when(
                pageLoadingFrame('show'))
                .then(function () {
                    setTimeout(function () {
                        if ($("#username_input,#password_input,#passwordconf_input").valid()) {
                            if ($("#usernombre_input,#userapellido_input,#usercargo_input,#userdep_input").valid()) {
                                pass = $("#password_input").val();
                                confirmpass = $("#passwordconf_input").val();
                                if (pass == confirmpass) {
                                    rol = $("#newuser_rol_select").val();
                                    if (rol !== "0") {
                                        est = $("#newuser_est_select").val();
                                        if (est !== "0") {
                                            var formData = new FormData();
                                            formData.append('addnewUser', 'true');
                                            formData.append('userUsuario', $('#username_input').val());
                                            formData.append('passUsuario', $('#password_input').val());
                                            formData.append('nombreUsuario', $('#usernombre_input').val());
                                            formData.append('apellidoUsuario', $('#userapellido_input').val());
                                            formData.append('cedulaUsuario', $('#usercargo_input').val());
                                            formData.append('estUsuario', $('#newuser_est_select').val());
                                            formData.append('rolUsuario', $('#newuser_rol_select').val());
                                            $.ajax({
                                                url: 'assets/modules/users/control.php',
                                                type: 'POST',
                                                data: formData,
                                                success: function (data) {
                                                    pageLoadingFrame('hide');
                                                    $(".customalert_text").html(data);
                                                    $(".customalert").animate({width: 'toggle'}, 600);
                                                    $.post('assets/modules/users/control.php', {getUsers: 'true'}, function (data) {
                                                        $('.usersList').html(data);
                                                    });
                                                    $.when($(".newuser_panel,.cancel_btn,.savenew_btn").slideUp("slow")).then(function () {
                                                        $(".userlist_panel,.newuser_btn").slideDown("slow");
                                                        $(".newuser_panel input").val("");
                                                    });
                                                },
                                                error: function (error) {
                                                    pageLoadingFrame('hide');
                                                    $(".customalert_text").html('Error de conexi&oacute;n, intente de nuevo');
                                                    $(".customalert").animate({width: 'toggle'}, 600);
                                                    console.log(error);
                                                },
                                                cache: false,
                                                contentType: false,
                                                processData: false
                                            });
                                        } else {
                                            pageLoadingFrame('hide');
                                            $(".customalert_text").html('Por favor seleccione el estalecimiento para el usuario');
                                            $(".customalert").animate({width: 'toggle'}, 600);
                                        }
                                    } else {
                                        pageLoadingFrame('hide');
                                        $(".customalert_text").html('Por favor seleccione el rol para el usuario');
                                        $(".customalert").animate({width: 'toggle'}, 600);
                                    }
                                } else {
                                    pageLoadingFrame('hide');
                                    $("#passwordconf_input").val('');
                                    $("#passwordconf_input").valid();
                                    $(".customalert_text").html('Las contrase&ntilde;as no coinciden');
                                    $(".customalert").animate({width: 'toggle'}, 600);
                                }
                            } else {
                                pageLoadingFrame('hide');
                            }
                        } else {
                            pageLoadingFrame('hide');
                        }
                    }, 1000);
                });
    });

    $(document).on("click", "#saveedit_btn", function (event) {
        event.preventDefault();
        var self, rol;
        self = this;
        $.when(
                pageLoadingFrame('show'))
                .then(function () {
                    setTimeout(function () {
                        if ($("#editusername_input").valid()) {
                            if ($("#editusernombre_input,#editusercargo_input,#edituserdep_input").valid()) {
                                rol = $("#edituser_rol_select").val();
                                if (rol !== "0") {
                                    var formData = new FormData();
                                    formData.append('updateUser', 'true');
                                    formData.append('idUsuario', $('.edituser_id_cont').html());
                                    formData.append('userUsuario', $('#editusername_input').val());
                                    formData.append('nombreUsuario', $('#editusernombre_input').val());
                                    formData.append('apellidoUsuario', $('#edituserapellido_input').val());
                                    formData.append('cedulaUsuario', $('#editusercargo_input').val());
                                    formData.append('estUsuario', $('#edituser_est_select').val());
                                    formData.append('rolUsuario', $('#edituser_rol_select').val());
                                    $.ajax({
                                        url: 'assets/modules/users/control.php',
                                        type: 'POST',
                                        data: formData,
                                        success: function (data) {
                                            pageLoadingFrame('hide');
                                            $(".customalert_text").html(data);
                                            $(".customalert").animate({width: 'toggle'}, 600);
                                            $.post('assets/modules/users/control.php', {getUsers: 'true'}, function (data) {
                                                $('.usersList').html(data);
                                            });
                                            $.when($(".edituser_panel,.cancel_btn,.savenew_btn,.saveedit_btn").slideUp("slow")).then(function () {
                                                $(".userlist_panel,.newuser_btn").slideDown("slow");
                                                $(".edituser_panel input").val("");
                                            });
                                        },
                                        error: function (error) {
                                            pageLoadingFrame('hide');
                                            $(".customalert_text").html('Error de conexi&oacute;n, intente de nuevo');
                                            $(".customalert").animate({width: 'toggle'}, 600);
                                            console.log(error);
                                        },
                                        cache: false,
                                        contentType: false,
                                        processData: false
                                    });
                                } else {
                                    pageLoadingFrame('hide');
                                    $(".customalert_text").html('Por favor seleccione el rol para el usuario');
                                    $(".customalert").animate({width: 'toggle'}, 600);
                                }
                            } else {
                                pageLoadingFrame('hide');
                                $(".customalert_text").html('Por favor rellene todos los campos');
                                $(".customalert").animate({width: 'toggle'}, 600);
                            }
                        } else {
                            pageLoadingFrame('hide');
                            $(".customalert_text").html('Por favor rellene todos los campos');
                            $(".customalert").animate({width: 'toggle'}, 600);
                        }
                    }, 1000);
                });
    });

    $(document).on("click", "#restartPass_btn", function (event) {
        // console.log('actuelizando contraseña');
        event.preventDefault();
        var self, rol;
        self = this;
        $.when(
                pageLoadingFrame('show'))
                .then(function () {
                    setTimeout(function () {
                        var formData = new FormData();
                        formData.append('restartPass', 'true');
                        formData.append('idUsuario', $('.edituser_id_cont').html());
                       
                        $.ajax({
                            url: 'assets/modules/users/control.php',
                            type: 'POST',
                            data: formData,
                            success: function (data) {
                                pageLoadingFrame('hide');
                                $(".customalert_text").html(data);
                                $(".customalert").animate({width: 'toggle'}, 600);
                                $.post('assets/modules/users/control.php', {getUsers: 'true'}, function (data) {
                                    $('.usersList').html(data);
                                });
                                $.when($(".edituser_panel,.cancel_btn,.savenew_btn,.saveedit_btn").slideUp("slow")).then(function () {
                                    $(".userlist_panel,.newuser_btn").slideDown("slow");
                                    $(".edituser_panel input").val("");
                                });
                            },
                            error: function (error) {
                                pageLoadingFrame('hide');
                                $(".customalert_text").html('Error de conexi&oacute;n, intente de nuevo');
                                $(".customalert").animate({width: 'toggle'}, 600);
                                console.log(error);
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }, 1000);
                });
    });

    function notyConfirm(id) {
        noty({
            text: 'Seguro que quieres eliminar?',
            layout: 'topRight',
            buttons: [
                {addClass: 'btn btn-primary btn-clean', text: 'Si', onClick: function ($noty) {
                        var formData = new FormData();
                        formData.append('deleteUser', 'true');
                        formData.append('deleteid', id);

                        $.ajax({
                            url: 'assets/modules/users/control.php',
                            type: 'POST',
                            data: formData,
                            async: false,
                            success: function (data) {
                                pageLoadingFrame('show');
                                setTimeout(function () {
                                    pageLoadingFrame('hide');
                                    $(".customalert_text").html(data);
                                    $(".customalert").animate({width: 'toggle'}, 600);
                                    $.post('assets/modules/users/control.php', {getUsers: 'true'}, function (data) {
                                        $('.usersList').html(data);
                                    });
                                }, 1000);
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                        ;
                    }
                },
                {addClass: 'btn btn-danger btn-clean push-left20', text: 'Cancelar', onClick: function ($noty) {
                        $noty.close();
                    }
                }
            ]
        }).setTimeout(4000);
    }

    $(document).on("click", ".edituser_btn", function (event) {

        event.preventDefault();

        var self, text, userid, username, usernombre, usercargo, userdep, userrol, userapellido;
        self = this;
        // console.log($(this).parent().parent().parent().find(".idUsuario_cont").html());
        userid = $(this).parent().parent().parent().find(".idUsuario_cont").html();
        username = $(this).parent().parent().parent().find(".userUsuario_cont").html();
        usernombre = $(this).parent().parent().parent().find(".nombreUsuario_cont").html();
        userapellido = $(this).parent().parent().parent().find(".apellidoUsuario_cont").html();
        usercargo = $(this).parent().parent().parent().find(".cedulaUsuario_cont").html();
        userdep = $(this).parent().parent().parent().find(".estUsuario_cont").html();
        userrol = $(this).parent().parent().parent().find(".rolUsuario_cont").html();
        userimg = $(this).parent().parent().parent().find(".imgUsuario_cont").html();
        $.when($(".userlist_panel,.newuser_btn,.newuser_panel").slideUp("slow"))
                .then(function () {
                    $("#edituser_image").html('<img src="' + userimg + '.jpg" class="img-thumbnail"/>');
                    $("#editusername_input").val(username);
                    $("#editusernombre_input").val(usernombre);
                    $("#edituserapellido_input").val(userapellido);
                    $("#editusercargo_input").val(usercargo);
                    $(".edituser_id_cont").html(userid);
                    text = $("#edituser_est_select option[value='" + userdep + "']").text();
                    $('#edituser_est_select').parent().find(' .bootstrap-select .filter-option').text(text);
                    $("#edituser_est_select").val(userdep);
                    text = $("#edituser_rol_select option[value='" + userrol + "']").text();
                    $('#edituser_rol_select').parent().find('.bootstrap-select .filter-option').text(text);
                    $("#edituser_rol_select").val(userrol);
                    $(".edituser_panel,.cancel_btn,.saveedit_btn").slideDown("slow");
                });
    });

    $(document).on("click", ".newuser_btn", function (event) {
        event.preventDefault();
        $.when($(".userlist_panel,.newuser_btn").slideUp("slow")).then(function () {
            $(".newuser_panel,.cancel_btn,.savenew_btn").slideDown("slow");
            
        });
    });

    $(document).on("click", ".cancel_btn", function (event) {
        event.preventDefault();
        $.when($(".newuser_panel,.cancel_btn,.savenew_btn,.edituser_panel,.saveedit_btn").slideUp("slow")).then(function () {
            $(".userlist_panel,.newuser_btn").slideDown("slow");
        });
    });

    $(document).on("click", ".edituserStatus", function (event) {
        var self = this;
        var status = '';
        setTimeout(function () {
            if ($(self).find('input').is(':checked')) {
                status = '1';
            } else {
                status = '0';
            }
            var formData = new FormData();
            formData.append('statusUser', 'true');
            formData.append('loginUsuario', status);
            formData.append('idUsuario', $(self).parent().parent().parent().find(".idUsuario_cont").html());
            formData.append('nombreUsuario', $(self).parent().parent().parent().find(".nombreUsuario_cont").html());
            $.ajax({
                url: 'assets/modules/users/control.php',
                type: 'POST',
                data: formData,
                 
                
                success: function (data) {
                    console.log(data);
                    $(".customalert_text").html(data);
                    $(".customalert").animate({width: 'show'}, 800);
                    $.post('assets/modules/users/control.php', {getUsers: 'true'}, function (data) {
                        $('.usersList').html(data);
                    });
                },
                error: function (error) {
                    $(".customalert_text").html('Error de red, revise su conexi&oacute;n');
                    $(".customalert").animate({width: 'show'}, 800);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }, 2000);
    });
</script>