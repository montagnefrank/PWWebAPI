<?php
require ("conn.php");
require ("islogged.php");

session_start();
$user = $_SESSION["login"];
$passwd = $_SESSION["passwd"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

if (isset($_POST["editar"])) {
    $item = explode('-', $_POST['producto']);
    $producto = trim($item[0]);

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////VALIDAMOS QUE EL ITEM EXISTA EN LA BD
    $select_item_validate = "SELECT id_item FROM tblproductos WHERE id_item = '" . $producto . "' LIMIT 1";
    $result_item_validate = mysqli_query($link, $select_item_validate);
    $row_item_validate = mysqli_fetch_array($result_item_validate, MYSQLI_BOTH);
    $item_validate = $row_item_validate[0];
    if (!$item_validate) {
        $_SESSION['msg'] = "El item " . $producto . " no existe en la base de datos ";
        $_SESSION['box'] = "danger";
        goto item_validated; //////////////////////////////////////////////////////////////////////////////////////////SALIMOS DEL SCRIPT Y MANDAMOS MENSAJE
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////VALIDAMOS QUE EL DESTINO EXISTA EN LA BD
    $select_dest_validate = "SELECT codpais FROM tblpaises_destino WHERE codpais = '" . $_POST["destino"] . "' LIMIT 1";
    $result_dest_validate = mysqli_query($link, $select_dest_validate);
    $row_dest_validate = mysqli_fetch_array($result_dest_validate, MYSQLI_BOTH);
    $dest_validate = $row_dest_validate[0];
    if (!$dest_validate) {
        $_SESSION['msg'] = "El destino " . $_POST["destino"] . " no existe en la base de datos ";
        $_SESSION['box'] = "danger";
        goto dest_validated; //////////////////////////////////////////////////////////////////////////////////////////SALIMOS DEL SCRIPT Y MANDAMOS MENSAJE
    }
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////VALIDAMOS QUE LA AGENCIA EXISTA EN LA BD
    $select_age_validate = "SELECT nombre_agencia FROM tblagencia WHERE nombre_agencia = '" . $_POST["agencia"] . "' LIMIT 1";
    $result_age_validate = mysqli_query($link, $select_age_validate);
    $row_age_validate = mysqli_fetch_array($result_age_validate, MYSQLI_BOTH);
    $age_validate = $row_age_validate[0];
    if (!$age_validate) {
        $_SESSION['msg'] = "La Agencia " . $_POST["agencia"] . " no existe en la base de datos ";
        $_SESSION['box'] = "danger";
        goto age_validated; //////////////////////////////////////////////////////////////////////////////////////////SALIMOS DEL SCRIPT Y MANDAMOS MENSAJE
    }

    $update_pedido = "UPDATE tbletiquetasxfinca set finca = '" . $_POST["finca"] . "', item = '" . $producto . "', fecha = '" . $_POST["fecha_salida"] . "', fecha_tentativa='" . $_POST["fecha_vuelo"] . "', precio = '" . $_POST["precio"] . "', destino = '" . $_POST["destino"] . "', agencia = '" . $_POST["agencia"] . "' WHERE nropedido = '" . $_POST["nropedido"] . "' AND item ='" . $_POST["itemid"] . "'";
    $result_pedido = mysqli_query($link, $update_pedido);
    $_SESSION['msg'] = "El pedido ha sido editado con &eacute;xito";
    $_SESSION['box'] = "primary";
    header("Location: ../main.php?panel=hacped.php&listarfinca=" . $_POST["finca"]);
} elseif (isset($_POST["delete"])) {
    $nropedido_update = substr(trim($_POST["inputnropedido"]), 0, -1);
    $itemid_update = substr(trim($_POST["inputitemid"]), 0, -1);
    $update_archivar = "UPDATE tbletiquetasxfinca set archivada='Si' where nropedido IN (" . $nropedido_update . ") AND item IN (" . $itemid_update . ")";
    $result_archivar = mysqli_query($link, $update_archivar);
    $_SESSION['msg'] = "Los pedidos han sido eliminados con &eacute;xito";
    $_SESSION['box'] = "primary";
    header("Location: ../main.php?panel=hacped.php&listarfinca=" . $_POST["fincalistada"]);
} elseif (isset($_POST["archivar"])) {
    //REMOVEMOS LA ULTIMA COMA PARA NO GENERAR ERROR DE SINTAXIS
    $nropedido_update = substr(trim($_POST["inputnropedido"]), 0, -1);
    $itemid_update = substr(trim($_POST["inputitemid"]), 0, -1);
    $update_archivar = "UPDATE tbletiquetasxfinca set archivada='Si' where nropedido IN (" . $nropedido_update . ") AND item IN (" . $itemid_update . ")";
    $result_archivar = mysqli_query($link, $update_archivar);
    $_SESSION['msg'] = "Los pedidos han sido archivados con &eacute;xito";
    $_SESSION['box'] = "primary";
    header("Location: ../main.php?panel=hacped.php");
    die;
}
age_validated:
item_validated:
dest_validated:
//return;
//$_SESSION['msg'] = $msg_wg;
//$_SESSION['box'] = $box;
//header("Location: ../main.php?panel=pdv.php");
$select_form_inputs = "SELECT finca,COUNT(finca) cantidad,fecha,fecha_tentativa,precio,destino,agencia,item FROM tbletiquetasxfinca WHERE nropedido = '" . $_POST["nropedido"] . "' AND item ='" . $_POST["itemid"] . "' ";
$result_form_inputs = mysqli_query($link, $select_form_inputs);
$row_form_inputs = mysqli_fetch_array($result_form_inputs, MYSQLI_BOTH);
?>
<html lang="en" class="body-full-height">
    <head>        
        <title>Eblooms - Sistema de gestión de pedidos</title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" id="theme" href="../css/theme-eblooms-<?php
        $result_theme = mysqli_query($link, "SELECT theme FROM tblusuario WHERE cpuser='$user'");
        $row_theme = mysqli_fetch_array($result_theme, MYSQLI_ASSOC);
        $theme = $row_theme['theme'];
        echo $theme;
        ?>.css"/>
        <link rel="stylesheet" type="text/css" id="theme" href="../css/custom.css"/>
        <link rel="stylesheet" href="../css/videocontainer.css" type="text/css"> 
    </head>
    <body>
        <div  class="login-container">
            <video class="fullscreen-bg__video" autoplay="" muted="" loop="">
                <source type="video/mp4" src="../background/loop.mp4"/>
            </video>
            <div class="login-box animated fadeInDown" style="width: 80vw;">
                <div class="login-body">
                    <div class="login-title col-md-12"><strong>Editar pedidio <?php echo $_POST["nropedido"]; ?></strong>, por favor edite los campos</div>
                    <?php
                    if (isset($_SESSION['msg'])) {
                        echo '
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="widget widget-';
                        echo $_SESSION['box'];
                        echo ' widget-item-icon">
                                            <div class="widget-item-left">
                                                <span class="fa fa-exclamation"></span>
                                            </div>
                                            <div class="widget-data">
                                                <div class="widget-title">Notificación</div>
                                                <div class="widget-subtitle">
                                                    <div role="alert">
                                                        ' . $_SESSION['msg'] . '
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget-controls">                                
                                                <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                            </div>                             
                                        </div>
                                    </div>
                                </div>
                        ';
                        unset($_SESSION['msg']);
                    }
                    ?>
                    <div class="col-md-12">
                        <form id="pedido_editar_form" name="pedido_editar_form" class="form-horizontal" action="pedido_editar.php" method="post" enctype="multipart/form-data">
                            <table border="0" align="center" style="width: 70vw;">
                                <tr>
                                    <td><strong class="login-title">Finca:</strong></td>
                                    <td><input class="form-control" type="text" id="finca_ac" name="finca" value="<?php echo $row_form_inputs["finca"] ?>" style="margin-bottom: 10px" readonly="readonly"/></td>
                                </tr>
                                <tr>
                                    <td><strong class="login-title">Producto:</strong></td>
                                    <td><input class="form-control" type="text" id="prod_ac" name="producto" value="<?php echo $row_form_inputs["item"] ?>" style="margin-bottom: 10px"/></td>
                                </tr>
                                <tr>
                                    <td><strong class="login-title">Cantidad:</strong></td>
                                    <td><input class="form-control" type="text" id="cantidad" name="cantidad" value="<?php echo $row_form_inputs["cantidad"] ?>" style="margin-bottom: 10px" disabled="true"/></td>
                                </tr>
                                <tr>
                                    <td><strong class="login-title">Salida de Finca:</strong></td>
                                    <td><input class="form-control datepicker" type="date" id="fecha_salida" name="fecha_salida" value="<?php echo $row_form_inputs["fecha"] ?>" style="margin-bottom: 10px" /></td>
                                </tr>
                                <tr>
                                    <td><strong class="login-title">Fecha Tentativa de Vuelo :</strong></td>
                                    <td><input class="form-control datepicker" type="date" id="fecha_vuelo" name="fecha_vuelo" value="<?php echo $row_form_inputs["fecha_tentativa"] ?>" style="margin-bottom: 10px" /></td>
                                </tr>
                                <tr>
                                    <td><strong class="login-title">Precio de Compra:</strong></td>
                                    <td><input class="form-control" type="text" id="precio" name="precio" value="<?php echo $row_form_inputs["precio"] ?>" style="margin-bottom: 10px"/></td>
                                </tr>
                                <tr>
                                    <td><strong class="login-title">Destino:</strong></td>
                                    <td><input class="form-control" type="text" id="destino_ac" name="destino" value="<?php echo $row_form_inputs["destino"] ?>" style="margin-bottom: 10px" /></td>
                                </tr>
                                <tr>
                                    <td><strong class="login-title">Agencia de Carga:</strong></td>
                                    <td><input class="form-control" type="text" id="agencia_ac" name="agencia" value="<?php echo $row_form_inputs["agencia"] ?>" style="margin-bottom: 10px" /></td>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 16px;" class="btn btn-primary" name="editar" type="submit" value="Editar" /></td>
                                    <td><input class="btn btn-default submit" value="Cancelar" onClick="window.history.back();" /></td>
                                </tr>
                            </table>
                            <input type="hidden" name="nropedido" value="<?php echo $_POST["nropedido"]; ?>" />
                            <input type="hidden" name="itemid" value="<?php echo $_POST["itemid"]; ?>" />
                        </form>
                    </div>
                </div>
                <div class="login-footer">
                    <div class="pull-right">&copy; 2017 BIT - Bit <span class="glyphicon glyphicon-registration-mark"></span> 2017 versión 4.0</div>
                    <div class="pull-right">
                        <a href="http://www.bit-store.ec/contactanos">La empresa</a> |
                        <a href="http://www.bit-store.ec/contactanos">Términos y condiciones</a> |
                        <a href="http://www.bit-store.ec/contactanos">Contacto</a>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="../js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="../js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../js/plugins/bootstrap/bootstrap.min.js"></script>         
        <script type='text/javascript' src='../js/plugins/icheck/icheck.min.js'></script>        
        <script type="text/javascript" src="../js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        <script type="text/javascript" src="../js/plugins/morris/raphael-min.js"></script>
        <script type="text/javascript" src="../js/plugins/morris/morris.min.js"></script>       
        <script type="text/javascript" src="../js/plugins/rickshaw/d3.v3.js"></script>
        <script type="text/javascript" src="../js/plugins/rickshaw/rickshaw.min.js"></script>
        <script type='text/javascript' src='../js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'></script>
        <script type='text/javascript' src='../js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'></script>                
        <script type='text/javascript' src='../js/plugins/bootstrap/bootstrap-datepicker.js'></script>                
        <script type="text/javascript" src="../js/plugins/owl/owl.carousel.min.js"></script>
        <script type="text/javascript" src="../js/plugins/moment.min.js"></script>
        <script type="text/javascript" src="../js/plugins/daterangepicker/daterangepicker.js"></script>
        <script type="text/javascript" src="../js/plugins/dropzone/dropzone.min.js"></script>
        <script type="text/javascript" src="../js/plugins/bootstrap/bootstrap-file-input.js"></script>
        <script type="text/javascript" src="../js/plugins/form/jquery.form.js"></script>
        <script type="text/javascript" src="../js/plugins/cropper/cropper.min.js"></script>
        <script type='text/javascript' src='../js/plugins/jquery-validation/jquery.validate.js'></script>
        <script type="text/javascript" src="../js/plugins/smartwizard/jquery.smartWizard-2.0.min.js"></script>     
        <script type="text/javascript" src="../js/plugins/datatables/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="../js/plugins/tableexport/tableExport.js"></script>
        <script type="text/javascript" src="../js/plugins/tableexport/jquery.base64.js"></script>
        <script type="text/javascript" src="../js/plugins/tableexport/html2canvas.js"></script>
        <script type="text/javascript" src="../js/plugins/tableexport/jspdf/libs/sprintf.js"></script>
        <script type="text/javascript" src="../js/plugins/tableexport/jspdf/jspdf.js"></script>
        <script type="text/javascript" src="../js/plugins/tableexport/jspdf/libs/base64.js"></script>
        <script type="text/javascript" src="../js/plugins/bootstrap/bootstrap-timepicker.min.js"></script>
        <script type="text/javascript" src="../js/plugins/bootstrap/bootstrap-colorpicker.js"></script>
        <script type="text/javascript" src="../js/plugins/bootstrap/bootstrap-select.js"></script>
        <script type="text/javascript" src="../js/plugins/tagsinput/jquery.tagsinput.min.js"></script>
        <!--<script type="text/javascript" src="../js/settings.js"></script>-->
        <script type="text/javascript" src="../js/plugins.js"></script>        
        <script type="text/javascript" src="../js/actions.js"></script>
        <script type="text/javascript" src="../js/demo_edit_profile.js"></script>
        <script>
                                        //AUTOCOMPLETE DE AÑADIR PRODUCTO EN PDV.PHP
                                        $(function () {

                                            var data_1 = [<?php
                    $select_productos = "SELECT id_item,prod_descripcion FROM tblproductos";
                    $result_productos = mysqli_query($link, $select_productos);
                    while ($row_productos = mysqli_fetch_array($result_productos, MYSQLI_BOTH)) {
                        echo '"' . $row_productos["id_item"] . ' - ' . $row_productos["prod_descripcion"] . '",';
                    }
                    ?>];

                                            $("#prod_ac").autocomplete({
                                                source: data_1,
                                                open: function (event, ui) {

                                                    var autocomplete = $(".ui-autocomplete:visible");
                                                    var oldTop = autocomplete.offset().top;
                                                    var newTop = oldTop - $("#prod_ac").height() + 25;
                                                    autocomplete.css("top", newTop);

                                                }
                                            });
                                        });
        </script>
        <script type="text/javascript">
            //RESALTAMOS LA FILA DEL CLIENTE SELECCIONADO EN PDV.PHP
            $(window).load(function () {
                $('#pdv_client_table').on("click", "tr", function () {
                    $(this).addClass(" table_selected cliente_seleccionado").siblings().removeClass(" table_selected cliente_seleccionado");
                    var selectedclient = $(this).find(">:first-child").html();
                    window.location.href = "/main.php?panel=pdv.php&cliente=" + selectedclient;
                });
            });
        </script>
        <script type="text/javascript">
            //    RESALTAMOS LA COLUMNA DE LA TABLA AL HACER CLIC Y DESELECCIONAMOS CUALQUIER OTRA
            $(window).load(function () {
                $('#pdv_items').on("click", "tr", function () {
                    $(this).addClass(" table_selected item_a_editar").siblings().removeClass(" table_selected item_a_editar");
                });
            });
            $(window).load(function () {
                $('#hacped_items_table').on("click", "tr", function () {
                    $(this).addClass(" table_selected").siblings().removeClass(" table_selected");
                });
            });
            //    PARA CUANDO SELECCIONAS SIN DESELECCIONAR EL RESTO
            $(window).load(function () {
                $('#listado').on("click", "tr", function () {
                    $(this).addClass(" table_selected");
                    $('tr.totalgeneral').removeClass(" table_selected");
                    $('tr.totalpais').removeClass(" table_selected");
                    $(this).find('input').prop('disabled', false);
                });
            });
            $(window).load(function () {
                $('#listado').on("click", "tr.table_selected", function () {
                    $(this).removeClass(" table_selected");
                    $(this).find('input').prop('disabled', true);
                });
            });
            //    RESALTAMOS TODAS LAS COLUMNAS DE LA TABLA CON UN BOTON
            $(window).load(function () {
                $('#btn_seleccionar').click(function () {
                    $("#listado").find('tr').addClass(" table_selected");
                    $('tr.totalgeneral').removeClass(" table_selected");
                    $('tr.totalpais').removeClass(" table_selected");
                    $("#listado").find('input').prop('disabled', false);
                });
            });
            //     REMOVEMOS EL RESALTADO DE TODAS LAS COLUMNAS DE LA TABLA CON UN BOTON
            $(window).load(function () {
                $('#btn_deseleccionar').click(function () {
                    $("#listado").find('tr').removeClass(" table_selected");
                    $("#listado").find('input').prop('disabled', true);
                });
            });
        </script>
        <script type="text/javascript">
            //ELIMINAMOS AL HACER CLIC EN LA PAPELERA EN PDV.PHP
            $(window).load(function () {
                $('#pdv_items').on("click", ".pdv_eliminaritem_btn", function () {
                    $(this).parent().parent().remove();
                });
            });
            $(window).load(function () {
                $('#hacped_items_table').on("click", ".hacped_eliminaritem_btn", function () {
                    $(this).parent().parent().remove();
                });
            });
        </script>
        <script type="text/javascript">
            //AGREGAR CAMPOS A LA TABLA DE PRODUCTOS AL HACER CLIC EN INSERTAR EN PDV.PHP
            $(window).load(function () {
                $('#insertar_item').click(function () {
                    if ($("#pdv_items_insert").valid()) {
                        $('#modal_nuevo_producto').modal('hide');
                        var rowcount = $("#pdv_items").find('tr').length;
                        $("#pdv_items").find('tbody').append("<tr></tr>");
                        var fields = $("#pdv_items_insert").find('input');
                        jQuery.each(fields, function (i, field) {
                            $("#pdv_items")
                                    .find('tbody tr:last')
                                    .append("<td>" + field.value + "</td>");
                            $("#pdv_items").find('tbody tr:last')
                                    .append("<input type=\"hidden\" class=\"form-control\" " +
                                            "name=\"item_" + rowcount + "_" + fields[i].name + "\" id=\"item\" value=\"" + field.value + "\"/> ");
                        });
                        $("#pdv_items").find('tbody tr:last')
                                .append("<td><a href=\"#\" type=\"button\"" +
                                        "id=\"btn_editar_item\" data-toggle=\"modal\" data-target=\"#modal_editar_item\"" +
                                        "data-placement=\"rigth\" title=\"Editar Item\"><i class=\"fa fa-pencil-square-o fa-3x\" aria-hidden=\"true\"></i></a></td>");
                        $("#pdv_items").find('tbody tr:last')
                                .append("<td><i style=\"cursor: pointer; cursor: hand; color: red\" class=\"fa fa-trash-o fa-3x pdv_eliminaritem_btn\" aria-hidden=\"true\"></i></td>");
                        $("#pdv_items").find('tbody tr:last')
                                .append("<td>" +
                                        "<select name=\"item_" + rowcount + "_destino\" class=\"form-control select\" data-style=\"btn-primary\">" +
                                        "<?php
                    $cliente_seleccionado = $_GET['cliente'];
                    $cliente = $cliente_seleccionado;

                    $select_cliente_destino = 'SELECT iddestino,destino FROM tbldestinos WHERE codcliente = "' . $cliente . '"';
                    $result_cliente_destino = mysqli_query($link, $select_cliente_destino);
                    while ($row_cliente_destino = mysqli_fetch_array($result_cliente_destino, MYSQLI_BOTH)) {
                        echo "<option value='" . $row_cliente_destino['iddestino'] . "'>" . $row_cliente_destino['destino'] . "</option>";
                    }
                    ?>" +
                                        "</select></td>");
                    }
                });
            });
        </script>
        <script type="text/javascript">
            //EDITAR LOS CAMPOS DE LA FILA PDV_ITEMS EN PDV.PHP
            $(window).load(function () {
                $('#pdv_editaritem_btn').click(function () {
                    if ($("#pdv_editaritem_form").valid()) {
                        $('#modal_editar_item').modal('hide');
                        $(".item_a_editar").remove();
                        var rowcount = $("#pdv_items").find('tr').length;
                        $("#pdv_items").find('tbody').append("<tr class=\"table_selected item_a_editar\"></tr>");
                        var fields = $("#pdv_editaritem_form").find('input');
                        jQuery.each(fields, function (i, field) {
                            $("#pdv_items")
                                    .find('tbody tr:last')
                                    .append("<td>" + field.value + "</td>");
                            $("#pdv_items").find('tbody tr:last')
                                    .append("<input type=\"hidden\" class=\"form-control\" " +
                                            "name=\"item_" + rowcount + "_" + fields[i].name + "\" id=\"item\" value=\"" + field.value + "\"/> ");
                        });
                        $("#pdv_items").find('tbody tr:last')
                                .append("<td><a href=\"#\" type=\"button\"" +
                                        "id=\"btn_editar_item\" data-toggle=\"modal\" data-target=\"#modal_editar_item\"" +
                                        "data-placement=\"rigth\" title=\"Editar Item\"><i class=\"fa fa-pencil-square-o fa-3x\" aria-hidden=\"true\"></i></a></td>");
                        $("#pdv_items").find('tbody tr:last')
                                .append("<td><i style=\"cursor: pointer; cursor: hand; color: red\" class=\"fa fa-trash-o fa-3x pdv_eliminaritem_btn\" aria-hidden=\"true\"></i></td>");
                        $("#pdv_items").find('tbody tr:last')
                                .append("<td>" +
                                        "<select name=\"item_" + rowcount + "_destino\" class=\"form-control select\" data-style=\"btn-primary\">" +
                                        "<?php
                    $cliente_seleccionado = $_GET['cliente'];
                    $cliente = $cliente_seleccionado;

                    $select_cliente_destino = 'SELECT iddestino,destino FROM tbldestinos WHERE codcliente = "' . $cliente . '"';
                    $result_cliente_destino = mysqli_query($link, $select_cliente_destino);
                    while ($row_cliente_destino = mysqli_fetch_array($result_cliente_destino, MYSQLI_BOTH)) {
                        echo "<option value='" . $row_cliente_destino['iddestino'] . "'>" . $row_cliente_destino['destino'] . "</option>";
                    }
                    ?>" +
                                        "</select></td>");
                    }
                });
            });
        </script>
        <script type="text/javascript">
            //CAMBIAMOS LOS CAMPOS "DESTINO" CON EL SELECT MASTER EN PDV.PHP
            $(window).load(function () {
                $('#select_control option').click(function () {
                    var selected = $('#select_control option:selected');
                    var fields = $("#pdv_items").find('tbody tr td select option');
                    jQuery.each(fields, function (i, field) {
                        $('#pdv_items tr td select').val(selected.val()).trigger('change');
                    });
                });
            });
        </script>
        <script>
            //FILTRAR TABLA DE CLIENTES
            function pdv_filterclients() {
                var input, filter, table, tr, td, i;
                input = document.getElementById("filtrartabla");
                filter = input.value.toUpperCase();
                table = document.getElementById("pdv_client_table_body");
                tr = table.getElementsByTagName("tr");
                for (i = 0; i < tr.length; i++) {
                    tdcod = tr[i].getElementsByTagName("td")[0];
                    tdname = tr[i].getElementsByTagName("td")[1];
                    if (tdcod && tdname) {
                        if (tdcod.innerHTML.toUpperCase().indexOf(filter) > -1 || tdname.innerHTML.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        </script>
        <script type="text/javascript">
            //VALIDADOR GLOBAL DE FORMULARIOS
            var validatepass = $("#pedido_editar_form").validate({
                ignore: [],
                rules: {
                    finca: {
                        required: true,
                    },
                    producto: {
                        required: true,
                    },
                    cantidad: {
                        required: true,
                    },
                    fecha_salida: {
                        required: true,
                        date: true,
                    },
                    precio: {
                        required: true,
                    },
                    destino: {
                        required: true,
                    },
                    agencia: {
                        required: true,
                    },
                    fecha_vuelo: {
                        required: true,
                        date: true,
                    }
                }
            });
            var validatenewitem = $("#pdv_items_insert").validate({
                ignore: [],
                rules: {
                    prod_ac: {
                        required: true,
                    },
                    cantidad: {
                        required: true,
                    },
                    precioUnitario: {
                        required: true,
                    }
                }
            });
            var validateedititem = $("#pdv_editaritem_form").validate({
                ignore: [],
                rules: {
                    prod_ac: {
                        required: true,
                    },
                    cantidad: {
                        required: true,
                    },
                    precioUnitario: {
                        required: true,
                    }
                }
            });
            var validatepass = $("#hacped_additem").validate({
                ignore: [],
                rules: {
                    prod_ac: {
                        required: true,
                    },
                    finca_ac: {
                        required: true,
                    },
                    hacped_additem_precio: {
                        required: true,
                    },
                    hacped_additem_salidafinca: {
                        required: true,
                    },
                    hacped_additem_tentativa: {
                        required: true,
                    },
                    hacped_additem_cantidad: {
                        required: true,
                    }
                }
            });

        </script>
        <script>
            //AUTOCOMPLETE DE FINCA
            $(function () {

                var data_1 = [<?php
                    $select_fincas = "SELECT nombre FROM tblfinca";
                    $result_fincas = mysqli_query($link, $select_fincas);
                    while ($row_fincas = mysqli_fetch_array($result_fincas, MYSQLI_BOTH)) {
                        echo '"' . $row_fincas["nombre"] . '",';
                    }
                    ?>];

                $("#finca_ac").autocomplete({
                    source: data_1,
                    open: function (event, ui) {

                        var autocomplete = $(".ui-autocomplete:visible");
                        var oldTop = autocomplete.offset().top;
                        var newTop = oldTop - $("#finca_ac").height() + 25;
                        autocomplete.css("top", newTop);

                    }
                });
            });
        </script>
        <script>
            //AUTOCOMPLETE DE DESTINO
            $(function () {

                var data_1 = [<?php
                    $select_paisdestino = "SELECT codpais FROM tblpaises_destino";
                    $result_paisesdestino = mysqli_query($link, $select_paisdestino);
                    while ($row_paisesdestino = mysqli_fetch_array($result_paisesdestino, MYSQLI_BOTH)) {
                        echo '"' . $row_paisesdestino["codpais"] . '",';
                    }
                    ?>];

                $("#destino_ac").autocomplete({
                    source: data_1,
                    open: function (event, ui) {

                        var autocomplete = $(".ui-autocomplete:visible");
                        var oldTop = autocomplete.offset().top;
                        var newTop = oldTop - $("#destino_ac").height() + 25;
                        autocomplete.css("top", newTop);

                    }
                });
            });
        </script>
        <script>
            //AUTOCOMPLETE DE AGENCIA
            $(function () {

                var data_1 = [<?php
                    $select_agencia = "SELECT nombre_agencia FROM tblagencia";
                    $result_agencia = mysqli_query($link, $select_agencia);
                    while ($row_agencia = mysqli_fetch_array($result_agencia, MYSQLI_BOTH)) {
                        echo '"' . $row_agencia["nombre_agencia"] . '",';
                    }
                    ?>];

                $("#agencia_ac").autocomplete({
                    source: data_1,
                    open: function (event, ui) {

                        var autocomplete = $(".ui-autocomplete:visible");
                        var oldTop = autocomplete.offset().top;
                        var newTop = oldTop - $("#agencia_ac").height() + 25;
                        autocomplete.css("top", newTop);

                    }
                });
            });
        </script>
        <script type="text/javascript">
            //AGREGAR CAMPOS A LA TABLA DE PRODUCTOS AL HACER CLIC EN INSERTAR EN HACPED.PHP
            $(window).load(function () {
                $('#hacped_insertar_item').click(function () {
                    if ($("#hacped_additem").valid()) {
                        var rowcount = $("#hacped_items_table").find('tr').length;
                        $("#hacped_items_table").find('tbody').append("<tr></tr>");
                        var fields = $("#hacped_additem").find('.insert_item');
                        jQuery.each(fields, function (i, field) {
                            $("#hacped_items_table")
                                    .find('tbody tr:last')
                                    .append("<td>" + field.value + "</td>");
                            $("#hacped_items_table").find('tbody tr:last')
                                    .append("<input type=\"hidden\" class=\"form-control\" " +
                                            "name=\"item_" + rowcount + "_" + fields[i].name + "\" id=\"item\" value=\"" + field.value + "\"/> ");
                        });
                        $("#hacped_items_table").find('tbody tr:last')
                                .append("<td><i style=\"cursor: pointer; cursor: hand; color: red\" class=\"fa fa-trash-o fa-3x hacped_eliminaritem_btn\" aria-hidden=\"true\"></i></td>");
                        $("#hacped_items_table").find('tbody').append("<input type=\"hidden\" class=\"form-control\" " +
                                "name=\"rowcount\" id=\"rowcount\" value=\"" + rowcount + "\"/> ");
                    }
                });
            });
        </script>
        <script type="text/javascript">
            //al dar click en el boton de editar pedido
            $('#hacped_edititem_btn').on('click', function () {
                var band = -1;
                $("#listado tbody tr.table_selected").each(function (index)
                {
                    band = index;
                });

                if (band != 0)
                {
                    alert("Para editar un pedido debe seleccionar una sola fila de la tabla");
                    return;
                } else {
                    //veo si solicitado>0, entregado,rechazado,cierr=0
                    var solicitado = $("#listado tbody tr.table_selected td:eq(6)").html();
                    var rechazado = $("#listado tbody tr.table_selected td:eq(10)").html();
                    var cierre = $("#listado tbody tr.table_selected td:eq(11)").html();
                    var porentregar = $("#listado tbody tr.table_selected td:eq(13) strong").html();
                    var entregadas = parseInt(solicitado - porentregar);
                    if (solicitado > 0 && entregadas == 0 && rechazado == 0 && cierre == 0)
                    {
                        var producto = $("#listado tbody tr.table_selected td:eq(1) strong").html();
                        $("#listado tbody").append("<input type=\"hidden\" class=\"form-control\" name=\"itemid\" id=\"itemid\" value=\"" + producto + "\" />");
                        $("#hacped_edititem_form").submit();
                    } else
                    {
                        alert("Para editar un pedido tiene que tener valores de entregadas, rechazadas y cierre de dia iguales a cero.");
                        return;
                    }
                }
            });
        </script>
    </body>
</html>
<?php mysqli_close($link); ?>