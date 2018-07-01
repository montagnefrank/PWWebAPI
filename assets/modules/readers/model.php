<?php
/////////////////////////////////////////////////////////////////////////////// CONTACT MODEL
?>
<script>

    $(document).ready(function () {
        $.post('assets/modules/readers/control.php', {getMsgs: 'true'}, function (data) {
            $('.shoMsgs_panel').html(data);
        });
    });

    $(document).on("click", ".readmessage_btn", function (event) {
        event.preventDefault();
        var self, id, message, email, name, subject, datetime;
        self = this;
        id = $(self).parent().find(".idContact_cont").html();
        message = $(self).parent().find(".messageContact_cont").html();
        email = $(self).parent().find(".emailContact_cont").html();
        name = $(self).parent().find(".nameContact_cont").html();
        subject = $(self).parent().find(".subjectContact_cont").html();
        datetime = $(self).parent().find(".datetimeContact_cont").html();
        ebook = $(self).parent().find(".ebookContact_cont").html();

        $('.readmsgName').html(name);
        $('.readmsgEmail').html(email);
        $('.readmsgId').html(id);
        $('.readmsgSubject').html(subject);
        $('.readmsgTime').html(datetime);
        $('.readmsgMsg').html(message);
        $('.readmsgEbook').html(ebook);
        $.when($(".messages_panel").slideUp("slow")).then(function () {
            $(".readmessage_panel,.goback_btn").slideDown("slow");
            var formData = new FormData();
            formData.append('readingMessage', 'true');
            formData.append('idMessage', id);
            $.ajax({
                url: 'assets/modules/readers/control.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    console.log(data);
                    $.post('assets/modules/readers/control.php', {getMsgs: 'true'}, function (data) {
                        $('.shoMsgs_panel').html(data);
                    });
                },
                error: function (error) {
                    $(".customalert_text").html('Error de conexi&oacute;n, intente de nuevo');
                    $(".customalert").animate({width: 'toggle'}, 600);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });

    $(document).on("click", ".goback_btn", function (event) {
        event.preventDefault();
        $.when($(".readmessage_panel,.goback_btn").slideUp("slow")).then(function () {
            $(".messages_panel").slideDown("slow");
        });
    });

    $(document).on("click", ".deleteMsg_btn", function (event) {
        event.preventDefault();
        var self, id;
        self = this;
        id = $(self).find(".readmsgId").html();
        var formData = new FormData();
        formData.append('deleteMsg', 'true');
        formData.append('idMessage', id);
        $.when($(".readmessage_panel,.goback_btn").slideUp("slow")).then(function () {
            $(".messages_panel").slideDown("slow");

            $.ajax({
                url: 'assets/modules/readers/control.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    $(".customalert_text").html(data);
                    $(".customalert").animate({width: 'toggle'}, 600);
                    $.post('assets/modules/readers/control.php', {getMsgs: 'true'}, function (data) {
                        $('.shoMsgs_panel').html(data);
                    });
                },
                error: function (error) {
                    $(".customalert_text").html('Error de conexi&oacute;n, intente de nuevo');
                    $(".customalert").animate({width: 'toggle'}, 600);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });
</script>