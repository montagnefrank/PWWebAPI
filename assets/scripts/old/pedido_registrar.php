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

if (isset($_POST["submit"])) {
    $finca = $_POST['finca_ac'];

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////VALIDAMOS QUE LA FINCA EXISTA EN LA BD
    $select_finca_validate = "SELECT nombre FROM tblfinca WHERE nombre = '" . $finca . "' LIMIT 1";
    $result_finca_validate = mysqli_query($link, $select_finca_validate);
    $row_finca_validate = mysqli_fetch_array($result_finca_validate, MYSQLI_BOTH);
    $finca_validate = $row_finca_validate[0];
    if (!$finca_validate) {
        $msg = "La finca " . $finca . " no existe en la base de datos ";
        $box = "danger";
        goto finca_validated; //////////////////////////////////////////////////////////////////////////////////////////SALIMOS DEL SCRIPT Y MANDAMOS MENSAJE
    }

    $salidafinca = $_POST['hacped_additem_salidafinca'];
    $tentativa = $_POST['hacped_additem_tentativa'];
    $destino = $_POST['hacped_additem_destino'];
    $agencia = $_POST['hacped_additem_agencia'];
    $filas = $_POST['rowcount'];
    $filas++;

    $select_codigo = "SELECT codigo FROM tblcodigo ORDER BY id_codigo DESC LIMIT 1";
    $result_codigo = mysqli_query($link, $select_codigo);
    $row_codigo = mysqli_fetch_array($result_codigo, MYSQLI_BOTH);
    $codigo = $row_codigo[0];
    $codigo++;

    $select_numpedido = "SELECT nropedido FROM tbletiquetasxfinca ORDER BY `nropedido` DESC LIMIT 1";
    $result_numpedido = mysqli_query($link, $select_numpedido);
    $row_numpedido = mysqli_fetch_array($result_numpedido, MYSQLI_BOTH);
    $numpedido = $row_numpedido[0];
    $numpedido++;

    $values = '';
    $codigos = '';
    $i = 1;
    while ($i < $filas) {
        $item = explode('-', $_POST['item_' . $i . '_prod_ac']);
        $item[0] = trim($item[0]);

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////VALIDAMOS QUE EL ITEM EXISTA EN LA BD
        $select_item_validate = "SELECT id_item FROM tblproductos WHERE id_item = '" . $item[0] . "' LIMIT 1";
        $result_item_validate = mysqli_query($link, $select_item_validate);
        $row_item_validate = mysqli_fetch_array($result_item_validate, MYSQLI_BOTH);
        $item_validate = $row_item_validate[0];
        if (!$item_validate) {
            $msg = "El item " . $item[0] . " no existe en la base de datos ";
            $box = "danger";
            goto item_validated; //////////////////////////////////////////////////////////////////////////////////////////SALIMOS DEL SCRIPT Y MANDAMOS MENSAJE
        }

        $precio = $_POST['item_' . $i . '_hacped_additem_precio'];
        $ii = 0;
        while ($ii < $_POST['item_' . $i . '_hacped_additem_cantidad']) {
            $values .= "('" . $codigo . "','" . $finca . "','" . $item[0] . "','" . $salidafinca . "','1','0','0','" . $numpedido . "','" . $tentativa . "','" . $precio . "','" . $destino . "','" . $agencia . "'),";
            $codigos .= "('" . $codigo . "','" . $finca . "'),";
            $codigo++;
            $ii++;
        }
        $i++;
        $codigo++;
        $numpedido++;
    }
    //REMOVEMOS LA ULTIMA COMA PARA NO GENERAR ERROR DE SINTAXIS
    $values = substr(trim($values), 0, -1);
    $codigos = substr(trim($codigos), 0, -1);

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////HACEMOS EL INSERT DE LOS CODIGOS
    $insert_codigos = "INSERT INTO tblcodigo (`codigo`,`finca`) VALUES " . $codigos;
    $result_codigos = mysqli_query($link, $insert_codigos);

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////HACEMOS EL INSERT DE LOS PEDIDOS
    $insert_pedidos = "INSERT INTO tbletiquetasxfinca (`codigo`,`finca`,`item`,`fecha`,`solicitado`,`entregado`,`estado`,`nropedido`,`fecha_tentativa`,`precio`,`destino`,`agencia`) VALUES " . $values;
    $result_pedidos = mysqli_query($link, $insert_pedidos);

    if (!empty($result_codigos) && !empty($result_pedidos)) {
        $msg = "Registros ingresados con &eacute;xito";
        $box = "primary";
    } else {
        $msg = "No se pudo hacer el pedido, intente de nuevo";
        $box = "danger";
    }
}
item_validated:
finca_validated:
$_SESSION['msg'] = $msg;
$_SESSION['box'] = $box;
header("Location: ../main.php?panel=hacped.php");
?>