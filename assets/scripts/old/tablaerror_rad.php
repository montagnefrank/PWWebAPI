<?php

///////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
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

$sql = "SELECT * FROM tblerror INNER JOIN tblproductos ON tblproductos.id_item = tblerror.cpitem WHERE delivery_traking =\"1969-12-27\"";
$query11 = mysqli_query($link, $sql);
$numero_filas = mysqli_num_rows($query11);

while ($res = mysqli_fetch_array($query11)) {

    $id = $res['id_orden_detalle'];
    $Ponumber = addslashes($res['Ponumber']);
    $orddate = addslashes($res['order_date']);
    $mensaje = addslashes($res['cpmensaje']);

    //datos de la tabla tblshipto
    $shipto1 = addslashes($res['shipto1']);
    $shipto2 = addslashes($res['shipto2']);
    $direccion = addslashes($res['direccion']);
    $direccion2 = addslashes($res['direccion2']);
    $ciudad = addslashes($res['cpcuidad_shipto']);
    $estado = addslashes($res['cpestado_shipto']);
    $zip = addslashes($res['cpzip_shipto']);
    $telefono = addslashes($res['cptelefono_shipto']);
    $mail = addslashes($res['mail']);

    //datos de la tabla tblsoldto
    $soldto1 = addslashes($res['soldto1']);
    $soldto2 = addslashes($res['soldto2']);
    $stphone = addslashes($res['cpstphone_soldto']);
    $adddress = addslashes($res['address1']);
    $adddress2 = addslashes($res['address2']);
    $city = addslashes($res['city']);
    $state = addslashes($res['state']);
    $billzip = addslashes($res['postalcode']);
    $country = addslashes($res['billcountry']);

    //datos de la tabla tbldetalleorden
    $satdel = '';
    $cpcantidad = addslashes($res['cpcantidad']);
    $item = addslashes($res['cpitem']);
    $farm = '';
    $tracking = '';
    $ponumber = addslashes($res['Ponumber']);
    $ctry = addslashes($res['cppais_envio']);
    $origen = '';
    $precio = addslashes($res['unitprice']);
    $deliver = addslashes($_POST['fecha_error']);
    $cliente = addslashes($res['vendor']) . "-" . $ctry;

    $cpmoneda = 'USD';
    //$cporigen = 'EC';
    $cpUOM = 'BOX';
    $estado_orden = 'Active';
    $descargada = 'not downloaded';
    $coldroom = 'No';
    $status = 'New';
    $reenvio = 'No';
    $codigo = 0;
    $user = '';
    $eBing = 0;


    //Obtener dia de la semana para saber cuanto restar al deliver para asignarle al shipdt
    $fecha = date('l', strtotime($deliver));

    $cpitem = $res['cpitem'];

    //Obteniendo el origen para obtener el pais de origen (codigo_ciudad-pais)
    $sqlorg4 = "SELECT origen FROM tblproductos WHERE tblproductos.id_item = '" . $item . "'";
    $query4 = mysqli_query($link, $sqlorg4);
    $row4 = mysqli_fetch_array($query4);
    $cporigen = $row4["origen"];
    $cporigen_city = explode("-", $cporigen);
    $cporigen = $cporigen_city[0];

    //Obteniendo el codigo del pais
    $sqlorg5 = "SELECT codpais_origen FROM tblciudad_origen WHERE tblciudad_origen.codciudad = '" . $cporigen . "'";
    $query5 = mysqli_query($link, $sqlorg5);
    $row5 = mysqli_fetch_array($query5);
    $cporigen = $row5["codpais_origen"];

    //verifico que dia es para restarle los dias que son 
    //
    //Si el envio es de ECUADOR
    //
    if ($cporigen == "EC") {
        // Si es Martes, Jueves o Viernes le resto 3 dias
        if (strcmp($fecha, "Tuesday") == 0 || strcmp($fecha, "Thursday") == 0 || strcmp($fecha, "Friday") == 0) {
            $shipdt = strtotime('-3 day', strtotime($deliver));
            $shipdt = date('Y-m-j', $shipdt);
        } else {
            //Si es otro dia de envio osea Miercoles
            $shipdt = strtotime('-4 day', strtotime($deliver));
            $shipdt = date('Y-m-j', $shipdt);
        }
    } else {
        $shipdt = strtotime('-5 day', strtotime($deliver));
        $shipdt = date('Y-m-j', $shipdt);  //TBLDETALLE_ORDEN	  
    }


    // Formateando la fecha para shipdttrakin

    $deliver = date_create($deliver);
    $deliver = date_format($deliver, 'Y-m-j');

    $sql = "select * FROM tblerror WHERE id_orden_detalle = '" . $id . "'";

    $query = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($query);
    $id = addslashes($row['id_orden_detalle']);
    $Ponumber = addslashes($row['Ponumber']);
    $poline = addslashes($row['poline']);
    $nombre_compania = addslashes($row['nombre_compania']);
    $Custnumber = addslashes($row['Custnumber']);
    $ups = addslashes($row['ups']);
    $billmail = addslashes($row['billmail']);
    $merchantSKU = addslashes($row['merchantSKU']);
    $shippingWeight = addslashes($row['shippingWeight']);
    $weightUnit = addslashes($row['weightUnit']);
    $merchantLineNumber = addslashes($row['merchantLineNumber']);

    //Comprobar que el producto esté registrado//
    $errormsj = '';
    $query = "select * from tblproductos where id_item='" . $item . "'";
    //echo $query;
    $sql = mysqli_query($link, $query) or die(mysqli_error()); //selecciona los registros iguales aItem
    $ray = mysqli_num_rows($sql);
    if ($ray == 0) { //Si el item no esta registrado uso su detalles
        $errormsj = 'El producto asociado al item ' . $cpitem . ' no está registrado, por favor regístrelo antes de continuar';

        echo("<font color='red'>El producto asociado al item " . $cpitem . " no está registrado, por favor regístrelo antes de continuar.</font>");
        echo "<br>";
    }

    ////----- Cuando finalmente se pueda insertar a la tabla detalle_orden -----/////
    //Hacer un query a la orden de la tabla de errores para obtener los datos que no estan en este formulario
    ///---------INSERCION A LA BASE DE DATOS DEL lineItem------////

    for ($l = 0; $l < $cpcantidad; $l++) {

        //Insertar los datos de tblorden
        if ($mensaje == '') {
            $mensaje = "To-Blank Info   ::From- Blank Info   ::Blank .Info";
        } else {
            $mensaje = addslashes($mensaje);
        }

        if ($l == 0) {// es la primera orden
            $sql = "Insert INTO tblorden(nombre_compania,cpmensaje,order_date) VALUES ('" . $nombre_compania . "','" . $mensaje . "','" . $orddate . "')";
            mysqli_query($link, $sql) or die(mysqli_error()); //OK
            $modificado_orden = mysqli_query($link, $sql);

            $id_order = mysqli_insert_id($link);  //Indice automatico
            //Insertar los datos de Shipto
            $sql1 = "Insert INTO tblshipto(id_shipto,shipto1,shipto2,direccion,direccion2,cpestado_shipto,cpcuidad_shipto,cptelefono_shipto,cpzip_shipto,mail,shipcountry) VALUES ('" . $id_order . "','" . $shipto1 . "','" . $shipto2 . "','" . $direccion . "','" . $direccion2 . "','" . $estado . "','" . $ciudad . "','" . $telefono . "','" . $zip . "','" . $mail . "','" . $ctry . "')";
            mysqli_query($link, $sql1) or die(mysqli_error()); //OK
            $modificado_ship = mysqli_query($link, $sql);

            //Insertar los datos de BillTo (Soldto)
            $sql2 = "Insert INTO tblsoldto(id_soldto,soldto1,soldto2,cpstphone_soldto,address1,address2,city,state,postalcode,billcountry,billmail) VALUES ('" . $id_order . "','" . $soldto1 . "','" . $soldto2 . "','" . $stphone . "','" . $adddress . "','" . $adddress2 . "','" . $city . "','" . $state . "','" . $billzip . "','" . country . "','" . $billmail . "')";
            mysqli_query($link, $sql2) or die(mysqli_error()); //ok
            $modificado_sold = mysqli_query($link, $sql);

            //Insertar los datos de tbldirector
            $sql5 = "Insert INTO tbldirector (id_director) VALUES ('" . $id_order . "')";
            mysqli_query($link, $sql5) or die(mysqli_error()); //ok
            //Inserto los detalles del primer producto de la orden
            $cpcantidadsingle = 1;
            $sql3 = "Insert INTO tbldetalle_orden(id_detalleorden,Custnumber,Ponumber,cpitem,cpcantidad,farm,satdel,cppais_envio,cpmoneda,cporigen,cpUOM,delivery_traking,ShipDT_traking,tracking,estado_orden,descargada,user,eBing,coldroom,status,reenvio,poline,unitprice,ups,codigo,vendor,merchantSKU,shippingWeight,weightUnit,merchantLineNumber)VALUES ('" . $id_order . "','" . $Custnumber . "','" . $Ponumber . "','" . $item . "','" . $cpcantidadsingle . "','" . $farm . "','" . $satdel . "','" . $ctry . "','" . $cpmoneda . "','" . $cporigen . "','" . $cpUOM . "','" . $deliver . "','" . $shipdt . "','" . $tracking . "','" . $estado_orden . "','" . $descargada . "','" . $user . "','" . $eBing . "','" . $coldroom . "','" . $status . "','" . $reenvio . "','" . $poline . "','" . $precio . "','" . $ups . "','" . $codigo . "','" . $cliente . "','" . $merchantSKU . "','" . $shippingWeight . "','" . $weightUnit . "','" . $merchantLineNumber . "')";
            mysqli_query($link, $sql3)or die(mysqli_error());
            $modificado_detalle = mysqli_query($link, $sql);
        } else {
            //Inserto los detalles del resto de los productos de la orden
            $cpcantidadsingle = 1;
            $sql3 = "Insert INTO tbldetalle_orden(id_detalleorden,Custnumber,Ponumber,cpitem,cpcantidad,farm,satdel,cppais_envio,cpmoneda,cporigen,cpUOM,delivery_traking,ShipDT_traking,tracking,estado_orden,descargada,user,eBing,coldroom,status,reenvio,poline,unitprice,ups,codigo,vendor,merchantSKU,shippingWeight,weightUnit,merchantLineNumber)VALUES ('" . $id_order . "','" . $Custnumber . "','" . $Ponumber . "','" . $item . "','" . $cpcantidadsingle . "','" . $farm . "','" . $satdel . "','" . $ctry . "','" . $cpmoneda . "','" . $cporigen . "','" . $cpUOM . "','" . $deliver . "','" . $shipdt . "','" . $tracking . "','" . $estado_orden . "','" . $descargada . "','" . $user . "','" . $eBing . "','" . $coldroom . "','" . $status . "','" . $reenvio . "','" . $poline . "','" . $precio . "','" . $ups . "','" . $codigo . "','" . $cliente . "','" . $merchantSKU . "','" . $shippingWeight . "','" . $weightUnit . "','" . $merchantLineNumber . "')";
            mysqli_query($link, $sql3)or die(mysqli_error());
            $modificado_detalle = mysqli_query($link, $sql);
        }
    }

    if ($modificado_orden && $modificado_ship && $modificado_sold && $modificado_detalle) {

        //Eliminar la orden una vez agregada a detalle_orden
        $sql_del = "delete FROM tblerror WHERE id_orden_detalle = '" . $id . "';";
        mysqli_query($link, $sql_del) or die(mysqli_error()); //ok


        $usuarioLog = $_SESSION["login"];
        $ip = getRealIP();
        $fecha = date('Y-m-d H:i:s');
        $operacion = "Arreglar Orden: " . $Ponumber;
        $SqlHistorico = "INSERT INTO tblhistorico (usuario,operacion,fecha,ip) VALUES ('" . $usuarioLog . "','" . $operacion . "','" . $fecha . "','" . $ip . "')";
        $consultaHist = mysqli_query($link, $SqlHistorico) or die("Error actualizando la bitácora de usuarios");

        echo("Orden " . $id . " modificada correctamente");
    } else {
        echo("Orden " . $id . " tiene el error" . mysqli_error());
        echo("</br>");
    }
    echo("</br>");
}

function getRealIP() {

    if (isset($_SERVER["HTTP_CLIENT_IP"])) {
        return $_SERVER["HTTP_CLIENT_IP"];
    } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
        return $_SERVER["HTTP_X_FORWARDED"];
    } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
        return $_SERVER["HTTP_FORWARDED_FOR"];
    } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
        return $_SERVER["HTTP_FORWARDED"];
    } else {
        return $_SERVER["REMOTE_ADDR"];
    }
}

echo "<a href='javascript:history.back(1)'>Volver Atras</a>";
?>