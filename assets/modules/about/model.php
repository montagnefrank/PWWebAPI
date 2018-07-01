<?php /////////////////////////////////////////////////////////////////////////////// ABOUT MODEL                 ?>
<script>
    function addnewabouttitles() {

        $.when(
                $("#edittitles .beforeLoad").toggle(),
                $("#edittitles .loading_img").toggle(),
                $("#edittitles").attr("id", "edittitles_clicked"))
                .then(function () {
                    setTimeout(function () {
                        var title = $(".aboutTitle").val();
                        var subtitle = $('.aboutSubtitle').val();
                        if (title == '' || subtitle == '') {
                            noty({text: 'No se puede cargar los campos vacios', layout: 'topRight', type: 'error'}).setTimeout(2000);
                            $("#edittitles_clicked .beforeLoad").toggle();
                            $("#edittitles_clicked .loading_img").toggle();
                            $("#edittitles_clicked").attr("id", "edittitles");
                            return;
                        }

                        var formData = new FormData();
                        formData.append('edittitles', 'true');
                        formData.append('aboutTitle', title);
                        formData.append('aboutSubtitle', subtitle);

                        $.ajax({
                            url: 'assets/modules/about/control.php',
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
    }

    function editwidget1() {

        $.when(
                $("#editbtn1 .beforeLoad").toggle(),
                $("#editbtn1 .loading_img").toggle(),
                $("#editbtn1").attr("id", "editbtn1_clicked"))
                .then(function () {
                    setTimeout(function () {
                        var title = $(".edittitle1").val();
                        var subtitle = $('.editsubtitle1').val();
                        if (title == '' || subtitle == '') {
                            noty({text: 'No se puede cargar los campos vacios', layout: 'topRight', type: 'error'}).setTimeout(2000);
                            $("#editbtn1_clicked .beforeLoad").toggle();
                            $("#editbtn1_clicked .loading_img").toggle();
                            $("#editbtn1_clicked").attr("id", "editbtn1");
                            return;
                        }
                        var formData = new FormData();
                        formData.append('editwidget', 'true');
                        formData.append('editId', '1');
                        formData.append('editTitle', title);
                        formData.append('editSubtitle', subtitle);
                        formData.append('icon', $("#pick_icon1 input:eq(0)").val());

                        $.ajax({
                            url: 'assets/modules/about/control.php',
                            type: 'POST',
                            data: formData,
                            success: function (data) {
                                location.reload();
                            },
                            error: function (error) {
                                noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                $("#editbtn1_clicked .beforeLoad").toggle();
                                $("#editbtn1_clicked .loading_img").toggle();
                                $("#editbtn1_clicked").attr("id", "editbtn1");
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }, 1000);
                });
    }

    function editwidget2() {

        $.when(
                $("#editbtn2 .beforeLoad").toggle(),
                $("#editbtn2 .loading_img").toggle(),
                $("#editbtn2").attr("id", "editbtn2_clicked"))
                .then(function () {
                    setTimeout(function () {
                        var title = $(".edittitle2").val();
                        var subtitle = $('.editsubtitle2').val();
                        if (title == '' || subtitle == '') {
                            noty({text: 'No se puede cargar los campos vacios', layout: 'topRight', type: 'error'}).setTimeout(2000);
                            $("#editbtn2_clicked .beforeLoad").toggle();
                            $("#editbtn2_clicked .loading_img").toggle();
                            $("#editbtn2_clicked").attr("id", "editbtn2");
                            return;
                        }
                        var formData = new FormData();
                        formData.append('editwidget', 'true');
                        formData.append('editId', '2');
                        formData.append('editTitle', title);
                        formData.append('editSubtitle', subtitle);
                        formData.append('icon', $("#pick_icon2 input:eq(0)").val());

                        $.ajax({
                            url: 'assets/modules/about/control.php',
                            type: 'POST',
                            data: formData,
                            success: function (data) {
                                location.reload();
                            },
                            error: function (error) {
                                noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                $("#editbtn2_clicked .beforeLoad").toggle();
                                $("#editbtn2_clicked .loading_img").toggle();
                                $("#editbtn2_clicked").attr("id", "editbtn2");
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }, 1000);
                });
    }

    function editwidget3() {

        $.when(
                $("#editbtn3 .beforeLoad").toggle(),
                $("#editbtn3 .loading_img").toggle(),
                $("#editbtn3").attr("id", "editbtn3_clicked"))
                .then(function () {
                    setTimeout(function () {
                        var title = $(".edittitle3").val();
                        var subtitle = $('.editsubtitle3').val();
                        if (title == '' || subtitle == '') {
                            noty({text: 'No se puede cargar los campos vacios', layout: 'topRight', type: 'error'}).setTimeout(2000);
                            $("#editbtn3_clicked .beforeLoad").toggle();
                            $("#editbtn3_clicked .loading_img").toggle();
                            $("#editbtn3_clicked").attr("id", "editbtn3");
                            return;
                        }
                        var formData = new FormData();
                        formData.append('editwidget', 'true');
                        formData.append('editId', '3');
                        formData.append('editTitle', title);
                        formData.append('editSubtitle', subtitle);
                        formData.append('icon', $("#pick_icon3 input:eq(0)").val());

                        $.ajax({
                            url: 'assets/modules/about/control.php',
                            type: 'POST',
                            data: formData,
                            success: function (data) {
                                location.reload();
                            },
                            error: function (error) {
                                noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                $("#editbtn3_clicked .beforeLoad").toggle();
                                $("#editbtn3_clicked .loading_img").toggle();
                                $("#editbtn3_clicked").attr("id", "editbtn3");
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }, 1000);
                });
    }

    function editwidget4() {

        $.when(
                $("#editbtn4 .beforeLoad").toggle(),
                $("#editbtn4 .loading_img").toggle(),
                $("#editbtn4").attr("id", "editbtn4_clicked"))
                .then(function () {
                    setTimeout(function () {
                        var title = $(".edittitle4").val();
                        var subtitle = $('.editsubtitle4').val();
                        if (title == '' || subtitle == '') {
                            noty({text: 'No se puede cargar los campos vacios', layout: 'topRight', type: 'error'}).setTimeout(2000);
                            $("#editbtn4_clicked .beforeLoad").toggle();
                            $("#editbtn4_clicked .loading_img").toggle();
                            $("#editbtn4_clicked").attr("id", "editbtn4");
                            return;
                        }
                        var formData = new FormData();
                        formData.append('editwidget', 'true');
                        formData.append('editId', '4');
                        formData.append('editTitle', title);
                        formData.append('editSubtitle', subtitle);
                        formData.append('icon', $("#pick_icon4 input:eq(0)").val());

                        $.ajax({
                            url: 'assets/modules/about/control.php',
                            type: 'POST',
                            data: formData,
                            success: function (data) {
                                location.reload();
                            },
                            error: function (error) {
                                noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                $("#editbtn4_clicked .beforeLoad").toggle();
                                $("#editbtn4_clicked .loading_img").toggle();
                                $("#editbtn4_clicked").attr("id", "editbtn4");
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }, 1000);
                });
    }

    $("#file-simple,#file-simple2,#file-simple3").fileinput({
        showUpload: false,
        showCaption: false,
        browseClass: "btn btn-info",
        fileType: "jpg"
    });

    $(document).on("click", "#newaboutimg", function (event) {
        event.preventDefault();
        $.when(
                $("#newaboutimg .beforeLoad").toggle(),
                $("#newaboutimg .loading_img").toggle(),
                $(this).attr("id", "newaboutimg_clicked"))
                .then(function () {
                    setTimeout(function () {
                        var formData = new FormData();
                        formData.append('newaboutimg', 'true');
                        // Attach file
                        formData.append('slide1file', $('input[type=file]')[0].files[0]);

                        $.ajax({
                            url: 'assets/modules/about/control.php',
                            type: 'POST',
                            data: formData,
                            async: false,
                            success: function (data) {
                                location.reload();
                            },
                            error: function (error) {
                                noty({text: 'Error de red, revise su conexi&oacute;n', layout: 'topRight', type: 'error'}).setTimeout(2000);
                                $("#newaboutimg_clicked .beforeLoad").toggle();
                                $("#newaboutimg_clicked .loading_img").toggle();
                                $("#newaboutimg_clicked").attr("id", "newaboutimg");
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }, 1000);
                });
    });
</script>