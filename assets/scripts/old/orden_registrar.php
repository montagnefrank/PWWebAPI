<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

if (isset($_POST["submit"])) {
    $orddate = $_POST['orderdate'];
    $deliver = $_POST['deliverydate'];
    $satdel = $_POST['satdel'];
    $consolidado = $_POST['consolidado'];
    $idcliente = $_POST['clientid'];
    
    //Recoger todos los datos del cliente
    $sqlsoldto = "SELECT * from tblcliente WHERE codigo = " . $idcliente . " ";
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
    $ponumber = trim($_POST['ponumber']);
    $sqlPO = "SELECT tbldetalle_orden.id_orden_detalle FROM tbldetalle_orden WHERE tbldetalle_orden.Ponumber = '" . $ponumber . "' ";
    $queryPO = mysqli_query($link, $sqlPO);
    $rowPO = mysqli_fetch_array($queryPO);
    //verifico si hay datos 
    $ray = mysqli_num_rows($queryPO);
    if ($ray > 0) {
        $bos = 'danger';
        $msg = "Ese Ponumber ya está siendo utilizado por otra orden";
    }
    
    //Recorrer los registros de tblcarro_venta//
    $sqlins = "SELECT * FROM tblcarro_venta WHERE codcliente = '" . $idcliente . "' AND id_usuario = '" . $id_usuario . "' ";
    $queryins = mysqli_query($sqlins, $conexion);
    while ($rowins = mysqli_fetch_array($queryins)) {
        $cantidad = $rowins['cantidad'];
        $item = $rowins['id_item'];
        $precio = $rowins['preciounitario'];
        $mensaje = addslashes($rowins['mensaje']);
        $iddestino = $rowins['iddestino'];
        $sqldest = "SELECT * FROM tblcarro_venta INNER JOIN tblshipto_venta ON tblcarro_venta.iddestino = tblshipto_venta.iddestino WHERE tblcarro_venta.iddestino = '" . $iddestino . "'";
        $querydest = mysqli_query($sqldest, $conexion);
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
        $shipdt = $_POST['shipdt'];
        //Obteniendo el origen para obtener el pais de origen (codigo_ciudad-pais)
        $sqlorg4 = "SELECT origen FROM tblproductos WHERE tblproductos.id_item ='" . $item . "'";
        $query4 = mysqli_query($sqlorg4, $conexion);
        $row4 = mysqli_fetch_array($query4);
        $cporigen = $row4["origen"];
        $cporigen_city = explode("-", $cporigen);
        $cporigen = $cporigen_city[0];
        //Obteniendo el codigo del pais
        $sqlorg5 = "SELECT codpais_origen FROM tblciudad_origen WHERE tblciudad_origen.codciudad = '" . $cporigen . "'";
        $query5 = mysqli_query($sqlorg5, $conexion);
        $row5 = mysqli_fetch_array($query5);
        $origin = $row5["codpais_origen"];
        //Obtener dia de la semana para saber cuanto restar al deliver para asignarle al shipdt
        $fecha = date('l', strtotime($deliver));
        //verifico que dia es para restarle los dias que son 
        /*
          Si el envio es de ECUADOR
         */
        if ($origin == "EC") {
            // Si es Martes, Jueves o Viernes le resto 3 dias
            if (strcmp($fecha, "Tuesday") == 0 || strcmp($fecha, "Thursday") == 0 || strcmp($fecha, "Friday") == 0) {
                $shipdt = strtotime('-3 day', strtotime($deliver));
                $shipdt = date('Y-m-j', $shipdt);
            } else {
                //Si es otro dia de envio o sea Miercoles
                $shipdt = strtotime('-4 day', strtotime($deliver));
                $shipdt = date('Y-m-j', $shipdt);
            }
        } else {
            $shipdt = strtotime('-5 day', strtotime($deliver));
            $shipdt = date('Y-m-j', $shipdt);  //TBLDETALLE_ORDEN     
        } //Fin del if 
        $farm = $_POST['farm'];
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
        for ($i = 1; $i <= $cantidad; $i++) {
            if ($i == 1) {
                //Insertando los datos de la tabla orden
                $sql = "INSERT INTO tblorden (nombre_compania,cpmensaje,order_date) VALUES ('eblooms','" . $mensaje . "','" . $orddate . "')";
                $creado_orden = mysqli_query($sql, $conexion);
                $id_order = mysqli_insert_id();
                //Insertar los datos de Shipto
                $sql1 = "Insert INTO tblshipto(id_shipto,shipto1,shipto2,direccion,cpestado_shipto,cpcuidad_shipto,cptelefono_shipto,cpzip_shipto,mail,direccion2,shipcountry) VALUES ('" . $id_order . "','" . $shipto . "','" . $shipto2 . "','" . $direccion . "','" . $estado . "','" . $ciudad . "','" . $telefono . "','" . $zip . "','" . $mail . "','" . $direccion2 . "','" . $ctry . "')";
                $creado_ship = mysqli_query($sql1, $conexion);
                //Insertar los datos de Soldto
                $sql2 = "Insert INTO tblsoldto(id_soldto,soldto1,soldto2,cpstphone_soldto,address1,address2,city,state,postalcode,billcountry,billmail) VALUES ('" . $id_order . "','" . $soldto . "','" . $soldto2 . "','" . $stphone . "','" . $adddress . "','" . $adddress2 . "','" . $city . "','" . $state . "','" . $billzip . "','" . $country . "','" . $billmail . "')";
                $creado_sold = mysqli_query($sql2, $conexion);
                //Insertar los datos de tbldirector
                $sql5 = "Insert INTO tbldirector(id_director) VALUES ('" . $id_order . "')";
                $creado_director = mysqli_query($sql5, $conexion);
                //Inserto los detalles del primer producto de la orden
                $sql3 = "Insert INTO tbldetalle_orden(id_detalleorden,cpcantidad,Ponumber,Custnumber,cpitem,satdel,farm,cppais_envio,cpmoneda,cporigen,cpUOM,delivery_traking,ShipDT_traking,estado_orden,descargada,user,eBing,coldroom,status,poline,unitprice,ups,tracking,vendor,consolidado) VALUES ('" . $id_order . "','1','" . $ponumber . "','" . $idcliente . "','" . $item . "','" . $satdel . "','" . $farm . "','" . $ctry . "','USD','" . $origin . "','BOX','" . $deliver . "','" . $shipdt . "','Active','not donwloaded','','0','No','New','0','" . $precio . "','','','" . $cliente . "','" . $consolidado . "')";
                $creado_detalle = mysqli_query($sql3, $conexion);
            } else {
                //Inserto los detalles del primer producto de la orden
                $sql3 = "Insert INTO tbldetalle_orden(id_detalleorden,cpcantidad,Ponumber,Custnumber,cpitem,satdel,farm,cppais_envio,cpmoneda,cporigen,cpUOM,delivery_traking,ShipDT_traking,estado_orden,descargada,user,eBing,coldroom,status,poline,unitprice,ups,tracking,vendor,consolidado) VALUES ('" . $id_order . "','1','" . $ponumber . "','" . $idcliente . "','" . $item . "','" . $satdel . "','" . $farm . "','" . $ctry . "','USD','" . $origin . "','BOX','" . $deliver . "','" . $shipdt . "','Active','not donwloaded','','0','No','New','0','" . $precio . "','','','" . $cliente . "','" . $consolidado . "')";
                $creado_detalle = mysqli_query($sql3, $conexion);
            }
        }
        //Insertar en la tabla de transacciones
        $sqltrans = "INSERT INTO tbltransaccion(Ponumber,codcliente,cantidad,iddestino,id_item,idusuario) VALUES ('" . $ponumber . "','" . $idcliente . "','" . $cantidad . "','" . $iddestino . "','" . $item . "','" . $id_usuario . "')";
        $querytrans = mysqli_query($sqltrans);
    }//FIN DEL WHILE
    if ($creado_orden && $creado_ship && $creado_sold && $creado_detalle && $creado_director) {
        //Vaciar carro de compra
        $sqlvaciar = "DELETE FROM tblcarro_venta WHERE codcliente = '" . $idcliente . "' AND id_usuario = '" . $id_usuario . "'";
        $queryvaciar = mysqli_query($sqlvaciar, $conexion);
    }
}
$_SESSION['msg'] = $msg;
$_SESSION['box'] = $box;
header("Location: ../main.php?panel=pdv.php");
?>