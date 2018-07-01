<?php
/////////////////////////////////////////////////////////////////////////////// TWEETS MODEL
?>
<script>

    $(document).on("click", "#edittitles", function (event) {
        event.preventDefault();
        $.when(
                $("#edittitles .beforeLoad").toggle(),
                $("#edittitles .loading_img").toggle(),
                $(this).attr("id", "edittitles_clicked"))
                .then(function () {
                    setTimeout(function () {
                        var title = $(".tweetsTitle").val();
                        if (title == '') {
                            noty({text: 'No se puede cargar los campos vacios', layout: 'topRight', type: 'error'}).setTimeout(2000);
                            $("#edittitles_clicked .beforeLoad").toggle();
                            $("#edittitles_clicked .loading_img").toggle();
                            $("#edittitles_clicked").attr("id", "edittitles");
                            return;
                        }

                        var formData = new FormData();
                        formData.append('edittitles', 'true');
                        formData.append('tweetsTitle', title);

                        $.ajax({
                            url: 'assets/modules/tweets/control.php',
                            type: 'POST',
                            data: formData,
                            async: false,
                            success: function (data) {
                                location.reload();
                            },
                            error: function (error) {
                                noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                $("#edittitles_clicked .beforeLoad").toggle();
                                $("#edittitles_clicked .loading_img").toggle();
                                $("#edittitles_clicked").attr("id", "edittitles");
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }, 1000);
                });
    });

    $(document).on("click", "#editTweet", function (event) {
        event.preventDefault();
        $.when(
                $("#editTweet .beforeLoad").toggle(),
                $("#editTweet .loading_img").toggle(),
                $(this).attr("id", "editTweet_clicked"))
                .then(function () {
                    setTimeout(function () {
                        var img = $('input[type=file]')[0].files[0];
                        var nombre = $('input[name=name_team]').val();
                        var cargo = $('input[name=job_team]').val();
                        var perfil = $('input[name=profile_team]').val();
                        var check = $("input[name='status_check']").val();
                        var id = $('input[name=tweetid]').val();
                        var type = $("#typeaction").attr("name");
                        if (nombre == '' || cargo == '' || perfil == '') {
                            noty({text: 'No se puede cargar los campos vacios', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                $("#editTweet_clicked .beforeLoad").toggle();
                                $("#editTweet_clicked .loading_img").toggle();
                                $("#editTweet_clicked").attr("id", "editTweet");
                            return;
                        }

                        var formData = new FormData();
                        if (type == 'newtweet') {
                            formData.append('newtweet', 'true');
                        } else {
                            formData.append('editTweet', 'true');
                        }

                        formData.append('photoTweet', img);
                        formData.append('nameTweet', nombre);
                        formData.append('jobTweet', cargo);
                        formData.append('profileTweet', perfil);
                        formData.append('statusTweet', check);
                        formData.append('idTweet', id);

                        $.ajax({
                            url: 'assets/modules/tweets/control.php',
                            type: 'POST',
                            data: formData,
                            async: false,
                            success: function (data) {
                                location.reload();
                            },
                            error: function (error) {
                                noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                $("#editTweet_clicked .beforeLoad").toggle();
                                $("#editTweet_clicked .loading_img").toggle();
                                $("#editTweet_clicked").attr("id", "editTweet");
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
                {addClass: 'btn btn-success btn-clean', text: 'Si', onClick: function ($noty) {
                        var formData = new FormData();
                        formData.append('deletetweet', 'true');
                        formData.append('deleteid', id);

                        $.ajax({
                            url: 'assets/modules/tweets/control.php',
                            type: 'POST',
                            data: formData,
                            async: false,
                            success: function (data) {
                                noty({text: 'Testimonio eliminado', layout: 'topRight', type: 'success'}).setTimeout(2000);
                                setTimeout(function () {
                                    location.reload();
                                }, 2000);
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
    }

    $("#file-simple1").fileinput({
        showUpload: false,
        showCaption: false,
        browseClass: "btn btn-info",
        fileType: "jpg"
    });

    $(document).on("click", ".switchcheck", function (event) {
        var value = $(".switchcheck").val();
        if (value == '1') {
            $(".switchcheck").val("0");
        }
        if (value == '0') {
            $(".switchcheck").val("1");
        }
    });

    $(document).on("click", ".edittweet", function (event) {
        event.preventDefault();
        var name = $(this).parent().parent().find(".profile-data-name").html();
        var job = $(this).parent().parent().find(".profile-data-title").html();
        var profile = $(this).parent().parent().parent().find(".teamprofile .help-block").html();
        var id = $(this).parent().parent().parent().find(".idteam .help-block").html();
        $(".newmember").parent().slideUp("slow");
        $.when($(".hidethis").slideUp("slow")).then(function () {
            $(".hidethis").slideDown("slow");
            $(".tweetsformpanel").find("input[name=name_team]").val(name);
            $(".tweetsformpanel").find("input[name=job_team]").val(job);
            $(".tweetsformpanel").find("input[name=profile_team]").val(profile);
            $(".tweetsformpanel").find("input[name=tweetid]").val(id);
            $("#typeaction").attr("name", "editTweet");
        });

    });

    $(document).on("click", ".newmember", function (event) {
        event.preventDefault();
        $("#modaltitle").html("Nuevo Mensaje")
        $(this).parent().slideUp("slow");
        $.when($(".hidethis").slideUp("slow")).then(function () {
            $(".hidethis").slideDown("slow");
        });

    });
</script>