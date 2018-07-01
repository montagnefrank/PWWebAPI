<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////CONEXION A DB
$user = $_SESSION["login"];
$rol = $_SESSION["rol"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////REGISTRAMOS UNA NUEVA ORDEN
if (isset($_POST["submit_neworder"])) {

    $orddate = $_POST['orderdate'];
    $deliver = $_POST['deliverydate'];
    $satdel = $_POST['satdel'];
    $consolidado = $_POST['consolidado'];
    $idcliente = $_POST['clientid'];

    //Recoger todos los datos del cliente
    $sqlsoldto = "SELECT * from tblcliente WHERE codigo = '" . $idcliente . "' LIMIT 1";
    $querysoldto = mysqli_query($link, $sqlsoldto);
    $rowsoldto = mysqli_fetch_array($querysoldto);

    //datos de la tabla tblsoldto
    $soldto = $rowsoldto['empresa'];
    $soldto2 = $rowsoldto['empresa2'];
    $stphone = $rowsoldto['telefono'];
    $adddress = $rowsoldto['direccion'];
    $adddress2 = $rowsoldto['direccion2'];
    $city = $rowsoldto['ciudad'];
    $state = $rowsoldto['estado'];
    $billzip = $rowsoldto['zip'];
    $country = $rowsoldto['pais'];
    $billmail = $rowsoldto['mail'];

    //verificando que el ponumber no exista en otra orden
    if ($_POST['poradio'] == 'defaultpo') {
        $ponumber = trim($_POST['defaultpo']);
    } elseif ($_POST['poradio'] == 'custompo') {
        $ponumber = trim($_POST['custompo']);
    }
    $sqlPO = "SELECT tbldetalle_orden.id_orden_detalle FROM tbldetalle_orden WHERE tbldetalle_orden.Ponumber = '" . $ponumber . "' ";
    $queryPO = mysqli_query($link, $sqlPO);
    $rowPO = mysqli_fetch_array($queryPO);
    $ray = mysqli_num_rows($queryPO);
    if ($ray > 0) {
        $msg = "Ese Ponumber ya está siendo utilizado por otra orden";
        $box = "danger";
        goto poyaexiste;
    }

    ////Verificar que todos los items en el carro de venta tengan un destino
    $columnas_items = $_POST['rowcount'];
    $columnas_items++;
    $i = 1;
    while ($i < $columnas_items) {
        if ($_POST['item_' . $i . '_destino'] == '') {
            $msg = "Existen Items sin un destino asignado";
            $box = "danger";
            goto itemsindestino;
        }
        $i++;
    }

    //Recorrer los registros de tblcarro_venta//
    $i = 1;
    while ($i < $columnas_items) {

        $itemm = explode('-', $_POST['item_' . $i . '_prod_ac']);
        $itemm[0] = trim($itemm[0]);

        $cantidad = $_POST['item_' . $i . '_cantidad'];
        $item = $itemm[0];
        $precio = $_POST['item_' . $i . '_precioUnitario'];
        $mensaje = addslashes($_POST['item_' . $i . '_mensaje']);

        $iddestino = $_POST['item_' . $i . '_destino'];

        $sqldest = "SELECT * FROM tblshipto_venta WHERE iddestino = '" . $iddestino . "'";
        $querydest = mysqli_query($link, $sqldest);
        $rowdest = mysqli_fetch_array($querydest);

        $shipto = addslashes($rowdest['shipto1']);
        $shipto2 = addslashes($rowdest['shipto2']);
        $direccion = addslashes($rowdest['direccion']);
        $direccion2 = addslashes($rowdest['direccion2']);
        $ciudad = addslashes($rowdest['cpcuidad_shipto']);
        $estado = $rowdest['cpestado_shipto'];
        $zip = $rowdest['cpzip_shipto'];
        $telefono = $rowdest['cptelefono_shipto'];
        $mail = $rowdest['mail'];

        //Calcular el shipdt
        $shipdt = $_POST['shippingdate'];

        //Obteniendo el origen para obtener el pais de origen (codigo_ciudad-pais)
        $sqlorg4 = "SELECT origen FROM tblproductos WHERE tblproductos.id_item ='" . $item . "'";
        $query4 = mysqli_query($link, $sqlorg4);
        $row4 = mysqli_fetch_array($query4);
        $cporigen = $row4["origen"];
        $cporigen_city = explode("-", $cporigen);
        $cporigen = $cporigen_city[0];

        //Obteniendo el codigo del pais
        $sqlorg5 = "SELECT codpais_origen FROM tblciudad_origen WHERE tblciudad_origen.codciudad = '" . $cporigen . "'";
        $query5 = mysqli_query($link, $sqlorg5);
        $row5 = mysqli_fetch_array($query5);
        $origin = $row5["codpais_origen"];

        $farm = '';

        //El pais de envio hay que sacarlo tambien del shipto_venta
        $ctry = $rowdest['shipcountry'];

        /* VENDOR */
        $cliente = $idcliente;
        $enviaramsg = $rowdest['shipto1'];
        $clientmsg = $soldto;

        //Verifico si el mensaje esta en blanco, si es asi le pongo un valor por defecto
        if ($mensaje == '') {
            $mensaje = "To-Blank Info   ::From- Blank Info   ::Blank .Info";
        } else {
            $mensaje = "To-" . $enviaramsg . "::From-" . $clientmsg . "::" . $mensaje;
        }


        //***************** Insertando en las diferentes tablas para registrar la orden ****************************************//
        //Insertando los datos de la tabla orden  
        for ($ii = 1; $ii <= $cantidad; $ii++) {
            if ($ii == 1) {
                //Insertando los datos de la tabla orden
                $sql = "INSERT INTO tblorden (nombre_compania,cpmensaje,order_date) VALUES ('eblooms','" . $mensaje . "','" . $orddate . "')";
                $creado_orden = mysqli_query($link, $sql) or die(' INSERT TBLORDEN Error: ' . mysqli_error($link));

                $select_tblorden_codigo = "SELECT id_orden FROM tblorden ORDER BY id_orden DESC LIMIT 1";
                $result_tblorden_codigo = mysqli_query($link, $select_tblorden_codigo);
                $row_tblorden_codigo = mysqli_fetch_array($result_tblorden_codigo, MYSQLI_BOTH);
                $tblorden_codigo = $row_tblorden_codigo[0];
                $id_order = $tblorden_codigo;

                //Insertar los datos de Shipto
                $sql1 = "Insert INTO tblshipto(id_shipto,shipto1,shipto2,direccion,cpestado_shipto,cpcuidad_shipto,cptelefono_shipto,cpzip_shipto,mail,direccion2,shipcountry) VALUES ('" . $id_order . "','" . $shipto . "','" . $shipto2 . "','" . $direccion . "','" . $estado . "','" . $ciudad . "','" . $telefono . "','" . $zip . "','" . $mail . "','" . $direccion2 . "','" . $ctry . "')";
                $creado_ship = mysqli_query($link, $sql1) or die(' INSERT TBLSHIPTO Error: ' . mysqli_error($link));

                //Insertar los datos de Soldto
                $sql2 = "Insert INTO tblsoldto(id_soldto,soldto1,soldto2,cpstphone_soldto,address1,address2,city,state,postalcode,billcountry,billmail) VALUES ('" . $id_order . "','" . $soldto . "','" . $soldto2 . "','" . $stphone . "','" . $adddress . "','" . $adddress2 . "','" . $city . "','" . $state . "','" . $billzip . "','" . $country . "','" . $billmail . "')";
                $creado_sold = mysqli_query($link, $sql2) or die(' INSERT TBLSOLDTO Error: ' . mysqli_error($link));

                //Insertar los datos de tbldirector
                $sql5 = "Insert INTO tbldirector(id_director) VALUES ('" . $id_order . "')";
                $creado_director = mysqli_query($link, $sql5) or die(' INSERT TBLDIRECTOR Error: ' . mysqli_error($link));

                //Inserto los detalles del primer producto de la orden
                $sql3 = "Insert INTO tbldetalle_orden(id_detalleorden,cpcantidad,Ponumber,Custnumber,cpitem,satdel,farm,cppais_envio,cpmoneda,cporigen,cpUOM,delivery_traking,ShipDT_traking,estado_orden,descargada,user,eBing,coldroom,status,poline,unitprice,ups,tracking,vendor,consolidado) VALUES ('" . $id_order . "','1','" . $ponumber . "','" . $idcliente . "','" . $item . "','" . $satdel . "','" . $farm . "','" . $ctry . "','USD','" . $origin . "','BOX','" . $deliver . "','" . $shipdt . "','Active','not donwloaded','','0','No','New','0','" . $precio . "','','','" . $cliente . "','" . $consolidado . "')";
                $creado_detalle = mysqli_query($link, $sql3) or die(' INSERT TBLDETALLE_ORDEN1 Error: ' . mysqli_error($link));
            } else {
                //Inserto los detalles del primer producto de la orden
                $sql3 = "Insert INTO tbldetalle_orden(id_detalleorden,cpcantidad,Ponumber,Custnumber,cpitem,satdel,farm,cppais_envio,cpmoneda,cporigen,cpUOM,delivery_traking,ShipDT_traking,estado_orden,descargada,user,eBing,coldroom,status,poline,unitprice,ups,tracking,vendor,consolidado) VALUES ('" . $id_order . "','1','" . $ponumber . "','" . $idcliente . "','" . $item . "','" . $satdel . "','" . $farm . "','" . $ctry . "','USD','" . $origin . "','BOX','" . $deliver . "','" . $shipdt . "','Active','not donwloaded','','0','No','New','0','" . $precio . "','','','" . $cliente . "','" . $consolidado . "')";
                $creado_detalle = mysqli_query($link, $sql3) or die(' INSERT TBLDETALLE_ORDENNEXT Error: ' . mysqli_error($link));
            }
        }

        //Insertar en la tabla de transacciones
        $sqltrans = "INSERT INTO tbltransaccion(Ponumber,codcliente,cantidad,iddestino,id_item,idusuario) VALUES ('" . $ponumber . "','" . $idcliente . "','" . $cantidad . "','" . $iddestino . "','" . $item . "','" . $id_usuario . "')";
        $querytrans = mysqli_query($link, $sqltrans) or die(' INSERT TBLTRANSACCION Error: ' . mysqli_error($link));

        $i++;
    }//FIN DEL WHILE

    if ($creado_orden && $creado_ship && $creado_sold && $creado_detalle && $creado_director) {
        $msg = "Registro de nueva orden terminado satisfactoriamente";
        $box = "primary";
    } else {
        $msg = "El registro no fue exitoso, intente de nuevo.";
        $box = "danger";
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////REGISTRAMOS UN NUEVO CLIENTE
if (isset($_POST["submit_nuevocliente"])) {
    $empresa = addslashes($_POST['empresa']);
    $empresa2 = addslashes($_POST['empresa2']);
    $direccion = addslashes($_POST['direccion']);
    $direccion2 = addslashes($_POST['direccion2']);
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $zip = $_POST['zip'];
    $pais = $_POST['pais'];
    $telefono = $_POST['telefono'];
    $vendedor = addslashes($_POST['vendedor']);
    $mail = $_POST['mail'];

    $sqlcod = "SELECT codigo FROM tblcliente ORDER BY codigo DESC LIMIT 1";
    $querycod = mysqli_query($link, $sqlcod);
    $rowcod = mysqli_fetch_array($querycod);
    $codigo = $rowcod[0] + 1;

    $sql = "INSERT INTO tblcliente (codigo, empresa, direccion,direccion2,ciudad, estado, zip, pais, telefono, vendedor, mail, empresa2) VALUES ('" . $codigo . "','" . $empresa . "','" . $direccion . "','" . $direccion2 . "','" . $ciudad . "','" . $estado . "','" . $zip . "','" . $pais . "','" . $telefono . "','" . $vendedor . "','" . $mail . "','" . $empresa2 . "')";
    $insertado = mysqli_query($link, $sql);


    if (!empty($insertado)) {
        $msg = "Nuveo cliente ingresado con &eacute;xito";
        $box = "primary";
    } else {
        $msg = "No se pudo ingresar el nuevo cliente, intente de nuevo";
        $box = "danger";
    }
}

////////////////////////////////////////////////////////////////////////////////////////////AGREGAMOS UN NUEVO DESTINO
if (isset($_POST["submit_nuevodest"])) {

    $nombredestino = addslashes($_POST['nombredestino']);
    $shipto = addslashes($_POST['shipto']);
    $shipto2 = addslashes($_POST['shipto2']);
    $direccion = addslashes($_POST['direccion']);
    $direccion2 = addslashes($_POST['direccion2']);
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $zip = $_POST['zip'];
    $telefono = $_POST['telefono'];
    $mail = $_POST['mail'];
    $shipcountry = $_POST['pais'];
    $codcliente = $_POST['codigo_cliente'];

    //busco si el destino ya existe para no duplicarlo
    $sql = "SELECT * FROM tbldestinos INNER JOIN tblshipto_venta ON tblshipto_venta.iddestino = tbldestinos.iddestino
          WHERE tbldestinos.destino='" . $nombredestino . "' AND tbldestinos.codcliente='" . $codcliente . "' AND 
          tblshipto_venta.shipto1='" . $shipto . "' AND tblshipto_venta.direccion='" . $direccion . "' AND
          tblshipto_venta.cpestado_shipto='" . $estado . "' AND tblshipto_venta.cpcuidad_shipto='" . $ciudad . "'";
    $val = mysqli_query($link, $sql);
    if (mysqli_num_rows($val) > 0) {
        echo json_encode("error");
        return;
    }

    $sql3 = "Insert INTO tbldestinos(codcliente,destino) VALUES ('" . $codcliente . "','" . $nombredestino . "')";
    $creado_destinos = mysqli_query($link, $sql3);
    $iddestino = mysqli_insert_id($link);

    $sql1 = "Insert INTO tblshipto_venta(shipto1,shipto2,direccion,cpestado_shipto,cpcuidad_shipto,cptelefono_shipto,cpzip_shipto,mail,direccion2,shipcountry,iddestino) VALUES ('" . $shipto . "','" . $shipto2 . "','" . $direccion . "','" . $estado . "','" . $ciudad . "','" . $telefono . "','" . $zip . "','" . $mail . "','" . $direccion2 . "','" . $shipcountry . "','" . $iddestino . "')";
    $creado_ship = mysqli_query($link, $sql1);

    if (!empty($creado_destinos) && !empty($creado_ship)) {
        $select_new_destino = 'SELECT iddestino,destino FROM tbldestinos ORDER BY iddestino DESC LIMIT 1';
        $result_new_destino = mysqli_query($link, $select_new_destino);
        $row_new_destino = mysqli_fetch_array($result_new_destino);
        $responsedata = array();
        $responsedata[0] = $row_new_destino[0];
        $responsedata[1] = $row_new_destino[1];
        echo json_encode($responsedata);
        return;
    } else {
        echo json_encode("error");
        return;
    }
}

///////////////////////////////////////////////////////////////////////////////////////////SALIDA DE LOS LOOPS CUANDO SE PRESENTA UN ERROR
destinoyaexiste:
poyaexiste:
itemsindestino:
//////////////////////////////////////////////////////////////////////////////////////////REGRESAMOS AL VIEW
$_SESSION['msg'] = $msg;
$_SESSION['box'] = $box;
header("Location: ../main.php?panel=pdv.php");
?>