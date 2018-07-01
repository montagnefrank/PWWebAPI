<?php
/////////////////////////////////////////////////////////////////////////////// ENCUESTA MODEL
?>
<script>

    $(document).ready(function () {
        $.post('assets/modules/enc/control.php', {getMsgs: 'true'}, function (data) {
            $('.shoMsgs_panel').html(data);
        });
    });

    $(document).on("click", ".readmessage_btn", function (event) {
        event.preventDefault();
        var self, id, name, email, phone, addr, city, edo, det, proname, tipo, time, pres, uso, zona, specs, fund, sota, est, cami, coment, datetime;
        self = this;
        id = $(self).parent().find(".idEnc_cont").html();
        datetime = $(self).parent().find(".datetimeEnc_cont").html();
        name = $(self).parent().find(".nameEnc_cont").html();
        email = $(self).parent().find(".emailEnc_cont").html();
        phone = $(self).parent().find(".phoneEnc_cont").html();
        addr = $(self).parent().find(".addrEnc_cont").html();
        city = $(self).parent().find(".cityEnc_cont").html();
        edo = $(self).parent().find(".edoEnc_cont").html();
        det = $(self).parent().find(".detEnc_cont").html();
        proname = $(self).parent().find(".pronameEnc_cont").html();
        tipo = $(self).parent().find(".tipoEnc_cont").html();
        time = $(self).parent().find(".timeEnc_cont").html();
        pres = $(self).parent().find(".presEnc_cont").html();
        uso = $(self).parent().find(".usoEnc_cont").html();
        zona = $(self).parent().find(".zonaEnc_cont").html();
        specs = $(self).parent().find(".specsEnc_cont").html();
        fund = $(self).parent().find(".fundEnc_cont").html();
        sota = $(self).parent().find(".sotaEnc_cont").html();
        est = $(self).parent().find(".estEnc_cont").html();
        cami = $(self).parent().find(".camiEnc_cont").html();
        coment = $(self).parent().find(".comentEnc_cont").html();
        
        $('.readmsgId').html(id);
        $('.readmsgTime').html(datetime);
        $('.readmsgName').html(name);
        $('.readmsgEmail').html(email);
        $('.readmsgPhone').html(phone);
        $('.readmsgAddr').html(addr);
        $('.readmsgCity').html(city);
        $('.readmsgEdo').html(edo);
        $('.readmsgDet').html(det);
        $('.readmsgProname').html(proname);
        $('.readmsgTipo').html(tipo);
        $('.readmsgTimeline').html(time);
        $('.readmsgPres').html(pres);
        $('.readmsgUso').html(uso);
        $('.readmsgZona').html(zona);
        $('.readmsgSpecs').html(specs);
        $('.readmsgFund').html(fund);
        $('.readmsgSota').html(sota);
        $('.readmsgEst').html(est);
        $('.readmsgCami').html(cami);
        $('.readmsgCom').html(coment);
        
        $.when($(".messages_panel").slideUp("slow")).then(function () {
            $(".readmessage_panel,.goback_btn").slideDown("slow");
            var formData = new FormData();
            formData.append('readingMessage', 'true');
            formData.append('idMessage', id);
            $.ajax({
                url: 'assets/modules/enc/control.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    console.log(data);
                    $.post('assets/modules/enc/control.php', {getMsgs: 'true'}, function (data) {
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
                url: 'assets/modules/enc/control.php',
                type: 'POST',
                data: formData,
                success: function (data) {
                    $(".customalert_text").html(data);
                    $(".customalert").animate({width: 'toggle'}, 600);
                    $.post('assets/modules/enc/control.php', {getMsgs: 'true'}, function (data) {
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