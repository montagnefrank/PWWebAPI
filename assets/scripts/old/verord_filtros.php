<?php

require ("conn.php");
require ("islogged.php");

session_start();
$user = $_SESSION["login"];
$rol = $_SESSION["rol"];
$finca = $_SESSION["finca"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

//////////////////////////////////////////////////////////////////////SI YA SE HIZO EL QUERY, NO LO CONSTRUYAS SINO QUE LO LLAMAS DE LA SESION
if (isset($_POST["step_ford"]) || isset($_POST["step_back"])) {
    $select_vo_reporte = $_SESSION['query'];
} else {
    //////////////////////////////////////////////////////////////////// SI NO SE HA HECHO EL QUERY, LO CONSTRUYES PARA LUEGO APLICAR LOS FILTROS
    $select_vo_reporte = "
    SELECT estado_orden,status,reenvio,tracking,order_date,shipto1,direccion,cpcuidad_shipto,Ponumber,Custnumber,ShipDT_traking,delivery_traking,cpitem,prod_descripcion,cppais_envio,farm,origen,id_detalleorden
    FROM tblorden
    INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
    INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
    INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
    INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
    INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem ";
    $select_xlsups = "
    SELECT tracking,nombre_compania,eBing,order_date,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,Ponumber,Custnumber,ShipDT_traking,delivery_traking,satdel,cpcantidad,cpitem,prod_descripcion,length,width,heigth,wheigthKg,dclvalue,cpmensaje,cpservicio,cptipo_pack,gen_desc,cppais_envio,cpmoneda,cporigen,cpUOM,empresa,director,direccion_director,cuidad_director,estado_director,pais_director,tpzip_director,tpphone_director,tpacct_director,farm,cpmensaje AS mensaje2, estado_orden
    FROM tblorden
    INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
    INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
    INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
    INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
    INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem";
}

///////////////////////////////////////////////////////////////////////////AQUI COMENZAMOS A RECORRER FILTRO POR FILTRO
if (isset($_POST["verord_new_submit"])) {////////////////////////////////////////////////////////FILTRO DE NUEVAS
    $reportede = "Nuevas";
    $select_vo_reporte_filtered = $select_vo_reporte . " WHERE delivery_traking BETWEEN '" . $_POST["verord_new_from"] . "' AND '" . $_POST["verord_new_to"] . "' AND status= 'New'";
    $rep_xlsups = $select_xlsups . " WHERE delivery_traking BETWEEN '" . $_POST["verord_new_from"] . "' AND '" . $_POST["verord_new_to"] . "' AND status= 'New'";
} elseif (isset($_POST["verord_ord_submit"])) {/////////////////////////////////////////////////////////FILTRO DE FECHA DE ORDEN
    $reportede = "Por fecha de orden";
    $select_vo_reporte_filtered = $select_vo_reporte . " WHERE order_date BETWEEN '" . $_POST["verord_ord_from"] . "' AND '" . $_POST["verord_ord_to"] . "'";
    $rep_xlsups = $select_xlsups . " WHERE order_date BETWEEN '" . $_POST["verord_ord_from"] . "' AND '" . $_POST["verord_ord_to"] . "'";
} elseif (isset($_POST["verord_fli_submit"])) {///////////////////////////////////////////////////////////FILTRO DE FECHA DE VUELO
    $reportede = "Por fecha de vuelo";
    $select_vo_reporte_filtered = $select_vo_reporte . " WHERE ShipDT_traking BETWEEN '" . $_POST["verord_fli_from"] . "' AND '" . $_POST["verord_fli_to"] . "'";
    $rep_xlsups = $select_xlsups . " WHERE ShipDT_traking BETWEEN '" . $_POST["verord_fli_from"] . "' AND '" . $_POST["verord_fli_to"] . "'";
} elseif (isset($_POST["verord_del_submit"])) {///////////////////////////////////////////////////////////FILTRO DE FECHA DE ENTEGA
    $reportede = "Por fecha de entrega";
    $select_vo_reporte_filtered = $select_vo_reporte . " WHERE delivery_traking BETWEEN '" . $_POST["verord_del_from"] . "' AND '" . $_POST["verord_del_to"] . "'";
    $rep_xlsups = $select_xlsups . " WHERE delivery_traking BETWEEN '" . $_POST["verord_del_from"] . "' AND '" . $_POST["verord_del_to"] . "'";
} elseif (isset($_POST["verord_tra_submit"])) { ///////////////////////////////////////////////////////////// FILTRO POR TRACKING
    $reportede = "Por Tracking";
    $select_vo_reporte_filtered = $select_vo_reporte . " WHERE tracking = '" . $_POST["verord_tra_input"] . "'";
    $rep_xlsups = $select_xlsups . " WHERE tracking = '" . $_POST["verord_tra_input"] . "'";
    $saltaropciones = 1;
} elseif (isset($_POST["verord_pon_submit"])) {///////////////////////////////////////////////////////////// FILTRO POR PONUMBER
    $reportede = "Por Ponumber";
    $select_vo_reporte_filtered = $select_vo_reporte . " WHERE Ponumber = '" . $_POST["verord_pon_input"] . "'";
    $rep_xlsups = $select_xlsups . " WHERE Ponumber = '" . $_POST["verord_pon_input"] . "'";
    $saltaropciones = 1;
} elseif (isset($_POST["verord_cus_submit"])) {//////////////////////////////////////////////////////////////// FILTRO POR CUSTNUMBER
    $reportede = "Por numero de cliente";
    $select_vo_reporte_filtered = $select_vo_reporte . " WHERE Custnumber = '" . $_POST["verord_cus_input"] . "'";
    $rep_xlsups = $select_xlsups . " WHERE Custnumber = '" . $_POST["verord_cus_input"] . "'";
    $saltaropciones = 1;
} elseif (isset($_POST["verord_ite_submit"])) {//////////////////////////////////////////////////////////////// FILTRO POR ITEM
    $reportede = "Por numero de producto";
    $select_vo_reporte_filtered = $select_vo_reporte . " WHERE cpitem = '" . $_POST["verord_ite_input"] . "'";
    $rep_xlsups = $select_xlsups . " WHERE cpitem = '" . $_POST["verord_ite_input"] . "'";
    $saltaropciones = 1;
} elseif (isset($_POST["verord_ndr_submit"])) {/////////////////////////////////////////////// FILTRO POR NOMBRE DEL RECEPTOR
    $reportede = "Por nombre del receptor";
    $select_vo_reporte_filtered = $select_vo_reporte . " WHERE shipto1 LIKE '%" . $_POST["verord_ndr_input"] . "%' ";
    $rep_xlsups = $select_xlsups . " WHERE shipto1 LIKE '%" . $_POST["verord_ndr_input"] . "%' ";
    $saltaropciones = 1;
} elseif (isset($_POST["verord_ddr_submit"])) { ///////////////////////////////////////////////// FILTRO POR DIRECCION DEL RECEPTOR
    $reportede = "Por direcci&oacute;n de receptor";
    $select_vo_reporte_filtered = $select_vo_reporte . " WHERE direccion LIKE '%" . $_POST["verord_ddr_input"] . "%' ";
    $rep_xlsups = $select_xlsups . " WHERE direccion LIKE '%" . $_POST["verord_ddr_input"] . "%' ";
    $saltaropciones = 1;
} elseif (isset($_POST["verord_ndc_submit"])) {/////////////////////////////////////////////////// FILTRO POR NOMBRE DEL COMPRADOR
    $reportede = "Por nombre del comprador";
    $select_vo_reporte_filtered = $select_vo_reporte . " WHERE soldto1 LIKE '%" . $_POST["verord_ndc_input"] . "%' ";
    $rep_xlsups = $select_xlsups . " WHERE soldto1 LIKE '%" . $_POST["verord_ndc_input"] . "%' ";
    $saltaropciones = 1;
} elseif (isset($_POST["verord_ddc_submit"])) {/////////////////////////////////////////////////// FILTRO POR DIRECCION DEL COMPRADOR
    $reportede = "Por direcci&oacute;n del comprador";
    $select_vo_reporte_filtered = $select_vo_reporte . " WHERE address1 LIKE '%" . $_POST["verord_ddc_input"] . "%' ";
    $rep_xlsups = $select_xlsups . " WHERE address1 LIKE '%" . $_POST["verord_ddc_input"] . "%' ";
    $saltaropciones = 1;
} else {////////////////////////////////////////////////////////////////////////////////////////////// SI NO SE APLICO NINGUN FILTRO ENTONCES VIENE DEL PAGINADOR
    $select_vo_reporte_filtered = $select_vo_reporte;
    $saltaropciones = 1;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////MODIFICAMOS EL QUERY SEGUN LAS OPCIONES SELECCIONADAS
if ($saltaropciones != 1) {
    if (isset($_POST["pais_select"])) {////////////////////////////////////////////////////////////////////PAIS DE DESTINO SLEECIONADO
        $select_vo_reporte_filtered = $select_vo_reporte_filtered . " AND cppais_envio = '" . $_POST["pais_select"] . "' ";
        $rep_xlsups = $rep_xlsups . " AND cppais_envio = '" . $_POST["pais_select"] . "' ";
    }
    if (isset($_POST["UIO"]) && !isset($_POST["GYE"]) && !isset($_POST["MED"])) {////////////////////////////////////////////////////////////////////////////DEFINIMOS QUE CIUDADES DE ORIGEN SE TILDARON, 3 OPCIONES BOOLEANAS SON 7 ESCENARIOS POSIBLES
        $select_vo_reporte_filtered = $select_vo_reporte_filtered . " AND origen= 'UIO-ECUADOR'";
        $rep_xlsups = $rep_xlsups . " AND origen= 'UIO-ECUADOR'";
    } elseif (!isset($_POST["UIO"]) && isset($_POST["GYE"]) && !isset($_POST["MED"])) {
        $select_vo_reporte_filtered = $select_vo_reporte_filtered . " AND origen= 'GYE-ECUADOR'";
        $rep_xlsups = $rep_xlsups . " AND origen= 'GYE-ECUADOR'";
    } elseif (!isset($_POST["UIO"]) && !isset($_POST["GYE"]) && isset($_POST["MED"])) {
        $select_vo_reporte_filtered = $select_vo_reporte_filtered . " AND origen= 'MED-ECUADOR'";
        $rep_xlsups = $rep_xlsups . " AND origen= 'MED-ECUADOR'";
    } elseif (isset($_POST["UIO"]) && isset($_POST["GYE"]) && !isset($_POST["MED"])) {
        $select_vo_reporte_filtered = $select_vo_reporte_filtered . " AND (origen= 'UIO-ECUADOR' OR origen= 'GYE-ECUADOR')";
        $rep_xlsups = $rep_xlsups . " AND (origen= 'UIO-ECUADOR' OR origen= 'GYE-ECUADOR')";
    } elseif (isset($_POST["UIO"]) && !isset($_POST["GYE"]) && isset($_POST["MED"])) {
        $select_vo_reporte_filtered = $select_vo_reporte_filtered . " AND (origen= 'UIO-ECUADOR' OR origen= 'MED-ECUADOR')";
        $rep_xlsups = $rep_xlsups . " AND (origen= 'UIO-ECUADOR' OR origen= 'MED-ECUADOR')";
    } elseif (!isset($_POST["UIO"]) && isset($_POST["GYE"]) && isset($_POST["MED"])) {
        $select_vo_reporte_filtered = $select_vo_reporte_filtered . " AND (origen= 'GYE-ECUADOR' OR origen= 'MED-ECUADOR')";
        $rep_xlsups = $rep_xlsups . " AND (origen= 'GYE-ECUADOR' OR origen= 'MED-ECUADOR')";
    } elseif (isset($_POST["UIO"]) && isset($_POST["GYE"]) && isset($_POST["MED"])) {
        $select_vo_reporte_filtered = $select_vo_reporte_filtered . " AND (origen= 'UIO-ECUADOR' OR origen= 'GYE-ECUADOR' OR origen= 'MED-ECUADOR')";
        $rep_xlsups = $rep_xlsups . " AND (origen= 'UIO-ECUADOR' OR origen= 'GYE-ECUADOR' OR origen= 'MED-ECUADOR')";
    }
    if ($_POST["estado_select"] == 1) {//////////////////////////////////////////////////////////////////////OPCION 1 LLAMAS LAS ACTIVAS, OPCION 2 LLAMAS LAS CANCELADAS, OPCION 3 LAS LLAMA A TODAS
        $select_vo_reporte_filtered = $select_vo_reporte_filtered . " AND estado_orden = 'Active'";
        $rep_xlsups = $rep_xlsups . " AND estado_orden = 'Active'";
    } elseif ($_POST["estado_select"] == 2) {
        $select_vo_reporte_filtered = $select_vo_reporte_filtered . " AND estado_orden = 'Canceled'";
        $rep_xlsups = $rep_xlsups . " AND estado_orden = 'Canceled'";
    }
    if ($_POST["track_select"] == 1) {////////////////////////////////////////////////////////////////////////OPCION 1 LLAMA SIN TRACKING OPCION 2 LLAMA CON TRACKING, OPCION 3 LLAMA TODAS
        $select_vo_reporte_filtered = $select_vo_reporte_filtered . " AND tracking = ''";
        $rep_xlsups = $rep_xlsups . " AND tracking = ''";
    } elseif ($_POST["track_select"] == 2) {
        $select_vo_reporte_filtered = $select_vo_reporte_filtered . " AND tracking != ''";
        $rep_xlsups = $rep_xlsups . " AND tracking != ''";
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////SI ES ROL 3 FILTRAMOS SOLAMENTE POR ESA FINCA LOS RESULTADOS
if ($rol == 3) {
    $select_vo_reporte_filtered = $select_vo_reporte_filtered . " AND finca = '" . $finca . "'";
    $rep_xlsups = $rep_xlsups . " AND finca = '" . $finca . "'";
}


//////////////////////////////////////////////////////////////////////////////////////////////////////// EL PAGINADOR LLAMA EL BOTON PRESIONADO (ADELANTE - ATRAS) Y VALIDA QUE PAGINA VA A CONSULTAR
if (isset($_POST["step_back"])) {
    $pageindex = $_SESSION['pageindex'];
    $pageindex = $pageindex - 1;
} elseif (isset($_POST["step_ford"])) {
    $pageindex = $_SESSION['pageindex'];
    $pageindex = $pageindex + 1;
} else {
    $pageindex = 1;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////// INDEXAMOS Y COMENZAMOS EL PAGINADOR DE QUERYS, FUE CONFIGURADO PARA PAGINAR CADA 5000 REGISTROS
$i = 1;
$pagination = 1;
if ($pageindex == '1') {
    $leftcap = 0;
    $rightcap = 5000;
} elseif ($pageindex == '2') {
    $leftcap = 4999;
    $rightcap = 10000;
} elseif ($pageindex == '3') {
    $leftcap = 9999;
    $rightcap = 15000;
} elseif ($pageindex == '4') {
    $leftcap = 14999;
    $rightcap = 20000;
} elseif ($pageindex == '5') {
    $leftcap = 19999;
    $rightcap = 25000;
} elseif ($pageindex == '6') {
    $leftcap = 24999;
    $rightcap = 30000;
} elseif ($pageindex == '7') {
    $leftcap = 29999;
    $rightcap = 35000;
} elseif ($pageindex == '8') {
    $leftcap = 34999;
    $rightcap = 40000;
} elseif ($pageindex == '9') {
    $leftcap = 39999;
    $rightcap = 45000;
} elseif ($pageindex == '10') {
    $leftcap = 44999;
    $rightcap = 50000;
} elseif ($pageindex == '11') {
    $leftcap = 49999;
    $rightcap = 55000;
} elseif ($pageindex == '12') {
    $leftcap = 54999;
    $rightcap = 60000;
} elseif ($pageindex == '13') {
    $leftcap = 59999;
    $rightcap = 65000;
} elseif ($pageindex == '14') {
    $leftcap = 64999;
    $rightcap = 70000;
} elseif ($pageindex == '15') {
    $leftcap = 69999;
    $rightcap = 75000;
} elseif ($pageindex == '16') {
    $leftcap = 74999;
    $rightcap = 80000;
} elseif ($pageindex == '17') {
    $leftcap = 79999;
    $rightcap = 85000;
} elseif ($pageindex == '18') {
    $leftcap = 85999;
    $rightcap = 90000;
} elseif ($pageindex == '19') {
    $leftcap = 79999;
    $rightcap = 95000;
} elseif ($pageindex == '20') {
    $leftcap = 94999;
    $rightcap = 100000;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////VACIAMOS LA PAGINA Y COMENZAMOS A ALIMENTAR EL RESULTADO DEL QUERY
$page = "";
if ((isset($_POST["verord_new_submit"])) || (isset($_POST["verord_ord_submit"])) || (isset($_POST["verord_fli_submit"])) || (isset($_POST["verord_del_submit"])) || (isset($_POST["verord_tra_submit"])) || (isset($_POST["verord_pon_submit"])) || (isset($_POST["verord_cus_submit"])) || (isset($_POST["verord_ite_submit"])) || (isset($_POST["verord_ndr_submit"])) || (isset($_POST["verord_ddr_submit"])) || (isset($_POST["verord_ndc_submit"])) || (isset($_POST["verord_ddc_submit"])) || (isset($_POST["step_ford"]) && $_SESSION['paginator'] == "1") || (isset($_POST["step_back"]) && $_SESSION['paginator'] == "1")) {
    $_SESSION['paginator'] = "1";
    $result_vo_reporte = mysqli_query($link, $select_vo_reporte_filtered);
    while ($row_vo_reporte = mysqli_fetch_array($result_vo_reporte, MYSQLI_BOTH)) {
        if (($i > $leftcap) && ($i < $rightcap)) {
            $page .= "<tr>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['estado_orden'] . " </td>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['status'] . " </td>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['reenvio'] . " </td>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['tracking'] . " </td>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['order_date'] . " </td>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['shipto1'] . " </td>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['direccion'] . " </td>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['cpcuidad_shipto'] . " </td>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['Ponumber'] . " </td>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['Custnumber'] . " </td>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['ShipDT_traking'] . " </td>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['delivery_traking'] . " </td>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['cpitem'] . " </td>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['prod_descripcion'] . " </td>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['cppais_envio'] . " </td>";
            $page .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['farm'] . " </td>";
            if ($row_vo_reporte['origen'] == "UIO-ECUADOR") {
                $page .= "<td style='width:1%;white-space:nowrap;display: none;'>Quito </td>";
            } elseif ($row_vo_reporte['origen'] == "GYE-ECUADOR") {
                $page .= "<td style='width:1%;white-space:nowrap;display: none;'>Guayaquil </td>";
            } else {
                $page .= "<td style='width:1%;white-space:nowrap;display: none;'>OTRO</td>";
            }

            $page .= "</tr>";
            $pagination++;
        }
        $i++;
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////GUARDAMOS TODO LO QUE VAMOS A RETORNAR A LA PANTALLA
$_SESSION['query'] = $select_vo_reporte_filtered;
$_SESSION['xlsups'] = $rep_xlsups;
$_SESSION['page'] = $page;
$_SESSION['pagination'] = $pagination;
$_SESSION['leftcap'] = $leftcap;
$_SESSION['i'] = $i;
$_SESSION['select'] = $select_vo_reporte;
$_SESSION['reportede'] = $reportede;
$_SESSION["pais"] = $_POST["pais_select"];

//$_SESSION['msg'] = $msg_wg;
//$_SESSION['box'] = $box;
header("Location: ../main.php?panel=verorden.php&reportindex=" . $pageindex);
?>