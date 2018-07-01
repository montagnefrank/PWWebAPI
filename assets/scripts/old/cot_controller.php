<?php

///////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require_once ('../php/PHPExcel.php');
include('../php/date.php');
include('../php/convertHex-Dec.php');
include('../php/consecutivo.php');
include('../php/codigounico.php');
include('../php/convertir_Excel.php');
include ('../php/PHPExcel/IOFactory.php');

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

//OBTENIENDO LA FINCA DEL USUARIO
$sql = "SELECT finca FROM tblusuario WHERE cpuser = '" . $user . "'";
$query = mysqli_query($link, $sql) or die("Error seleccionando la finca de este usuario");
$row = mysqli_fetch_array($query);
$finca = $row['finca'];

function invalidoperation($error) {
    $msg = "Error en el archivo: " . $error;
    $box = "danger";
    $_SESSION['msg'] = $msg;
    $_SESSION['box'] = $box;
    header("Location: ../main.php?panel=cot.php");
    exit;
}

# definimos la carpeta destino	
$modalcontent = "<div class=\"table-responsive\"><table class=\"table table-striped\"><tbody>";
$carpetaDestino = "../php/Archivos subidos/";
//    echo "BREAK PONIT";
# si hay algun archivo que subir
if (isset($_POST["subir_ordenes"])) {
    if ($_FILES["archivo"]["name"][0]) {
        # recorremos todos los arhivos que se han subido
        for ($i = 0; $i < count($_FILES["archivo"]["name"]); $i++) {
            #divide el nombre del fichero con un .    
            $explode_name = explode('.', $_FILES["archivo"]["name"][$i]);
            # si es un formato de excel
            if ($explode_name[1] == 'csv') {
                # si exsite la carpeta o se ha creado
                if (file_exists($carpetaDestino) || @mkdir($carpetaDestino)) {
                    $origen = $_FILES["archivo"]["tmp_name"][$i];
                    $destino = $carpetaDestino . $_FILES["archivo"]["name"][$i];


                    # movemos el archivo
                    if (@move_uploaded_file($origen, $destino)) {
                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                        $modalcontent .= "<br>" . $_FILES["archivo"]["name"][$i] . " subido correctamente <br>";
                        $modalcontent .= "</td></tr>";
                        //echo $_FILES['archivo']['name'][$i];
                        //unlink($_FILES['archivo']['name'][$i]);
                        //header('Location: index.php');
                    } else {
                        echo "<br>No se ha podido mover el archivo: " . $_FILES["archivo"]["name"][$i];
                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                        $modalcontent .= "<br>No se ha podido mover el archivo: " . $_FILES["archivo"]["name"][$i] . " <br>";
                        $modalcontent .= "</td></tr>";
                    }
                } else {
                    //                echo "<br>No se ha podido crear la carpeta: up/" . $user;
                }
            } else {
                $msg = "No se logro subir el archivo. " . $_FILES["archivo"]["name"][$i] . " - Formato no admitido";
                $box = "danger";
                goto invaliformat;
            }
        }
        $fila = 1;
        $array = array();
        $dir = "../php/Archivos subidos/";

        $orden = 2;
        $id_order = 0;
        ////////////////////////////////////////////////////////////////////////contar archivos
        $total_excel = count(glob("$dir/{*.csv}", GLOB_BRACE));  //("$dir/{*.xlsx,*.xls,*.csv}",GLOB_BRACE));
        if ($total_excel == 0) {
            $msg = "No se logro subir el archivo";
            $box = "danger";
        } else {
            //renombrarlos para cargarlos
            $a = 1;
            $excels = (glob("$dir/{*.csv}", GLOB_BRACE));
            foreach ($excels as $cvs) {
                $expr = explode("/", $cvs);
                $nombre = array_pop($expr);
                rename("$dir/$nombre", "$dir/$a.csv");
                $a++;
            }

            //Convertir csv a excel
            try {
                CSVToExcelConverter::convert("$dir/1.csv", "$dir/1.xlsx");
                unlink($dir . "1.csv");
            } catch (Exception $e) {
                echo $e->getMessage();
            }

            //Aqui leemos cada uno de los excel cargados y se guardan sus datos a la BD
            for ($i = 1; $i <= $total_excel; $i++) {
                $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                $objReader->setReadDataOnly(true);
                //cargamos el archivo que deseamos leer
                $direccion = "$dir/$i.xlsx";
                $objPHPExcel = $objReader->load($direccion);
                $objHoja = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

                foreach ($objHoja as $iIndice => $objCelda) {
                    //LEEMOS EL ARCHIVO POR ORDEN DE COLUMNAS
                    $Ponumber = $objCelda['AA'];
                    // **************** Si la fila empieza con tracking empieza un nuevo doc ****************************
                    if ($Ponumber == 'Ponumber') {
                        //Imprimo el encabezado de cada archivo
                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'>";
                        $modalcontent .= '<td><strong># Fila</strong></td>';
                        $modalcontent .= '<td><strong>Fecha de Órden</strong></td>';
                        $modalcontent .= '<td><strong>Ponumber</strong></td>';
                        $modalcontent .= '<td><strong>Custnumber</strong></td>';
                        $modalcontent .= '<td><strong>Cantidad</strong></td>';
                        $modalcontent .= '<td><strong>Producto</strong></td>';
                        $modalcontent .= '</tr>';
                    } else {//////////////////////////////////////////////////////////////////VALIDAMOS QUE NO HAYAN CAMPOS VACIOS
                        $vacios = "";
                        if ($objCelda['A'] == "") {
                            $vacios .= " A |";
                        } if ($objCelda['C'] == "") {
                            $vacios .= " C |";
                        }
                        if ($objCelda['E'] == "") {
                            $vacios .= " E |";
                        } if ($objCelda['G'] == "") {
                            $vacios .= " G |";
                        }
                        if ($objCelda['I'] == "") {
                            $vacios .= " I |";
                        } if ($objCelda['K'] == "") {
                            $vacios .= " K |";
                        }
                        if ($objCelda['L'] == "") {
                            $vacios .= " L |";
                        } if ($objCelda['N'] == "") {
                            $vacios .= " N |";
                        }
                        if ($objCelda['Q'] == "") {
                            $vacios .= " Q |";
                        } if ($objCelda['S'] == "") {
                            $vacios .= " S |";
                        }
                        if ($objCelda['U'] == "") {
                            $vacios .= " U |";
                        } if ($objCelda['X'] == "") {
                            $vacios .= " X |";
                        }
                        if ($objCelda['AA'] == "") {
                            $vacios .= " AA |";
                        } if ($objCelda['AB'] == "") {
                            $vacios .= " AB |";
                        }
                        if ($objCelda['AD'] == "") {
                            $vacios .= " AD |";
                        } if ($objCelda['AG'] == "") {
                            $vacios .= " AG |";
                        }
                        if ($objCelda['AI'] == "") {
                            $vacios .= " AI |";
                        } if ($vacios !== "") {
                            $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                            $modalcontent .= "<font color=\"red\"> A la filaa " . $orden . " le faltan datos o hay datos erroneos en el archivo, Actualice el archivo y carguelo " . $objCelda['I'] . " FILE </font><br> ";
                            $modalcontent .= "<font color=\"red\">Campos con Error: " . $vacios . " </font><br> ";
                            $modalcontent .= "</td></tr>";
                            //                        $j++;
                            $orden++;
                            continue;
                        }
                        //GENERAL
                        $vendor = strtoupper(addslashes($objCelda['A']));  //TBLDETALLE_ORDEN
                        //Modificandop el cliente para si es costo-us o costco-ca
                        if ($vendor == 'COSTCO-US') {
                            $vendor = '10000-US';
                        }
                        if ($vendor == 'COSTCO-CA') {
                            $vendor = '10001-US';
                        }

                        $Orddate = $objCelda['E'];

                        //Armar la feca de orden
                        list($anno, $mes, $dia) = explode('/', $Orddate);
                        if ($dia == '') {
                            $Orddate = $Orddate;
                        } else {
                            $Orddate = $anno . "-" . $mes . "-" . $dia;
                        }
                        $UPS = addslashes($objCelda['F']);  //TBLDETALLE_ORDEN
                        //SHIP TO	  
                        $Shipto = addslashes($objCelda['G']);  //TBLSHIPTO
                        $Shipto = addcslashes($Shipto, ";");
                        $Shipto2 = addslashes($objCelda['H']);  //TBLSHIPTO
                        $Shipto2 = addcslashes($Shipto2, ";");
                        $address = addslashes($objCelda['I']);  //TBLSHIPTO
                        $address2 = addslashes($objCelda['J']); //TBLSHIPTO
                        $city = addslashes($objCelda['K']); //TBLSHIPTO
                        $state = addslashes($objCelda['L']); //TBLSHIPTO
                        $zip = $objCelda['M'];         //TBLSHIPTO
                        $phone = $objCelda['N'];             //TBLSHIPTO
                        $mail = addslashes($objCelda['O']); //TBLSHIPTO
                        //DESTINO DE LA ORDEN (US - CA)
                        $ShipCtry = $objCelda['A'];     //TBLDETALLE_ORDEN		  
                        //saber si es US o CA
                        //$ShipCtry  = substr($ShipCtry,7,2); //Obtengo las dos ultimas letras
                        $ShipCtry = explode("-", $ShipCtry);
                        $ShipCtry = $ShipCtry[1];

                        //BILL TO
                        $soldto = addslashes($objCelda['Q']);      //TBLSOLDTO
                        $soldto = addcslashes($soldto, ";");
                        $soldto2 = addslashes($objCelda['R']);      //TBLSOLDTO
                        $soldto2 = addcslashes($soldto2, ";");
                        $solto_address1 = addslashes($objCelda['S']);  //TBLSOLDTO
                        $solto_address2 = addslashes($objCelda['T']);  //TBLSOLDTO
                        $solto_city = addslashes($objCelda['U']);      //TBLSOLDTO
                        $solto_state = addslashes($objCelda['V']);     //TBLSOLDTO
                        $solto_zip = addslashes($objCelda['W']);       //TBLSOLDTO
                        $soldto_phone = $objCelda['X'];             //TBLSOLDTO
                        $soldto_mail = $objCelda['Y'];    //TBLSOLDTO
                        $solto_country = $objCelda['Z'];     //TBLSOLDTO
                        //GENERAL  
                        $Ponumber = exp_to_dec(trim($objCelda['AA']));   //TBLDETALLE_ORDEN
                        $CUSTnbr = $objCelda['AB'];   //TBLDETALLE_ORDEN
                        //EN EL CASO ODEL SHIP PPRIMERO HAY QUE LEER EL DELIVER PARA CALCULAR EL SHIPDT
                        $deliver = $objCelda['AD'];   //TBLDETALLE_ORDEN
                        //Armar la feca de envio
                        list($anno, $mes, $dia) = explode('/', $deliver);
                        if ($dia == '') {
                            $deliver = $deliver;
                        } else {
                            $deliver = $anno . "-" . $mes . "-" . $dia;
                        }
                        /* $dia  = substr($deliver,0,2);
                          $mes  = substr($deliver,3,2);
                          $anno = substr($deliver,6,4); */

                        //ITEM
                        $Item = $objCelda['AI'];   //TBLDETALLE_ORDEN
                        //$Origin    = "EC";				//TBLDETALLE_ORDEN
                        //Obteniendo el origen para obtener el pais de origen (codigo_ciudad-pais)
                        $sqlorg = "SELECT origen FROM tblproductos WHERE tblproductos.id_item = '$Item'";

                        //echo $query;
                        $query5 = mysqli_query($link, $sqlorg);
                        $row = mysqli_fetch_array($query5);
                        $cporigen = $row["origen"];
                        $cporigen_city = explode("-", $cporigen);
                        $cporigen = $cporigen_city[0];

                        //Obteniendo el codigo del pais
                        $sqlorg = "SELECT codpais_origen FROM tblciudad_origen WHERE tblciudad_origen.codciudad = '$cporigen'";
                        //echo $query;
                        $query5 = mysqli_query($link, $sqlorg);
                        $row = mysqli_fetch_array($query5);

                        $Origin = $row["codpais_origen"];

                        //Obtener dia de la semana para saber cuanto restar al deliver para asignarle al shipdt
                        $fecha = date('l', strtotime($deliver));
                        //verifico que dia es para restarle los dias que son 
                        /*
                          Si el envio es de ECUADOR
                         */
                        if ($Origin == "EC") {
                            // Si es Maertes, Jueves o Viernes le resto 3 dias
                            if (strcmp($fecha, "Tuesday") == 0 || strcmp($fecha, "Thursday") == 0 || strcmp($fecha, "Friday") == 0) {
                                $SHIPDT = strtotime('-3 day', strtotime($deliver));
                                $SHIPDT = date('Y-m-j', $SHIPDT); //TBLDETALLE_ORDEN
                            } else {
                                //Si es otro dia de envio osea Miercoles
                                $SHIPDT = strtotime('-4 day', strtotime($deliver));
                                $SHIPDT = date('Y-m-j', $SHIPDT);  //TBLDETALLE_ORDEN
                            }
                        } else {
                            $SHIPDT = strtotime('-5 day', strtotime($deliver));
                            $SHIPDT = date('Y-m-j', $shipdt);  //TBLDETALLE_ORDEN    
                        }

                        // $SatDel    = $objCelda['AE'];			//TBLDETALLE_ORDEN
                        $POline = $objCelda['AF'];   //TBLDETALLE_ORDEN
                        $Quantity = $objCelda['AG'];   //TBLDETALLE_ORDEN
                        //GENERAL
                        $Message = addslashes($objCelda['AP']); //TBLORDEN
                        //ShipContry preguntar
                        $Currency = "USD";    //TBLDETALLE_ORDEN
                        $UOM = "BOX"; //PREGUNTAR   //TBLDETALLE_ORDEN
                        //GENERAL  
                        $Farm = '';    //TBLDETALLE_ORDEN
                        $Unitprice = $objCelda['BH']; //TBLDETALLE_ORDEN
                        //ESTADOS DE LA ORDEN
                        $estado = 'Active';    //TBLDETALLE_ORDEN	  
                        $descargada = 'not downloaded'; //TBLDETALLE_ORDEN
                        $user = '';    //TBLDETALLE_ORDEN
                        $status = 'New';           //TBLDETALLE_ORDEN
                        $coldroom = 'No';            //TBLDETALLE_ORDEN
                        $SatDel = 'N';           //TBLDETALLE_ORDEN

                        $consolidado = $objCelda['BK']; //consolidado de la orden
                        if ($consolidado == "")
                            $consolidado = "N";
                        //verifico si la orden tiene custnumber, ponumber, item, pais,
                        //echo $Ponumber." ".$Custnumber." ".$Item." ".$ShipCtry; 
                        if ($Ponumber == '' | $CUSTnbr == '' | $Item == '' | ($ShipCtry != 'US' && $ShipCtry != 'CA')) {
                            //echo "The order ".$orden." missing data, such as PONumber, custnumber, etc. Please review"."<br>";
                            $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                            $modalcontent .= "<font color=\"red\"> A la fila ' . $orden . ' le faltan datos o hay datos erroneos en el archivo, por favor revise el Ponumber, Custnumber, Item y el País de destino (Cliente-CA 0 Cliente-US)...</font><br>";
                            $modalcontent .= "</td></tr>";
                            /* $j++;
                              $orden++; */
                            break;
                        } else {
                            //Verifico que la orden no este  registrado en la bd (RESTRICION PARA SUBIR ORDENES)
                            $sql = "SELECT
                                                        tbldetalle_orden.id_orden_detalle
                                                        FROM
                                                        tbldetalle_orden
                                                        WHERE
                                                        tbldetalle_orden.Custnumber = '$CUSTnbr' AND
                                                        tbldetalle_orden.Ponumber = '$Ponumber' AND
                                                        tbldetalle_orden.cpitem = '$Item'";
                            //echo $query;
                            $query = mysqli_query($link, $sql);
                            $row = mysqli_fetch_array($query);
                            //echo $row[0]."<br>";
                            //verifico si hay datos 
                            $ray = mysqli_num_rows($query);
                            if ($ray > 0) { //Si el item esta registrado uso su detalles
                                $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                $modalcontent .= "<font color='red'>La orden " . $orden . " con Ponumber: " . $Ponumber . " y Custnumber: " . $CUSTnbr . " e Item " . $Item . " ya fue insertada." . "</font><br>";
                                $modalcontent .= "</td></tr>";
                                $j++;
                                $orden++;
                            } else {
                                for ($i = 1; $i <= $Quantity; $i++) {
                                    if ($i == 1) {
                                        //inserto una linea
                                        //Verifico que el item del producto este registrado en la bd 
                                        $query = "select * from tblproductos where id_item= '$Item'";
                                        $sql = mysqli_query($link, $query) or invalidoperation(mysqli_error($link)); //selecciona los registros iguales aItem
                                        $ray = mysqli_num_rows($sql);
                                        if ($ray == 0) { //Si el item esta registrado uso su detalles
                                            $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                            $modalcontent .= "<font color='red'>El producto associado al item " . $Item . " No esta registrado, por favor registrelo antes de continuar.</font>";
                                            $modalcontent .= "</td></tr>";
                                            break;
                                        }

                                        //verificar si el mensaje viene vacio o no.
                                        if ($Message == '') {
                                            $Message = "To-Blank Info   ::From- Blank Info   ::Blank .Info";
                                        } else {
                                            $Message = addslashes($Message);
                                        }

                                        // Conectarse a la BD y guardar los datos			
                                        //Insertar los datos de tblorden
                                        $sql = "Insert INTO tblorden(nombre_compania,cpmensaje,order_date)VALUES ('$Company','$Message','$Orddate')";
                                        mysqli_query($link, $sql) or invalidoperation(mysqli_error($link)); //OK

                                        $select_last_id = "SELECT id_orden FROM tblorden ORDER BY id_orden DESC LIMIT 1";
                                        $result_last_id = mysqli_query($link, $select_last_id);
                                        $row_last_id = mysqli_fetch_array($result_last_id);
                                        $id_order = $row_last_id[0];

                                        //Insertar los datos de Shipto
                                        $sql1 = "Insert INTO `tblshipto`(`id_shipto`,`shipto1`,`shipto2`,`direccion`,`direccion2`,`cpestado_shipto`,`cpcuidad_shipto`,`cptelefono_shipto`,`cpzip_shipto`,`mail`)VALUES ('$id_order','$Shipto','$Shipto2','$address','$address2','$state','$city','$phone','$zip','$mail')";
                                        mysqli_query($link, $sql1)or invalidoperation(mysqli_error($link)); //OK
                                        //Insertar los datos de Soldto
                                        $sql2 = "Insert INTO `tblsoldto`(`id_soldto`,`soldto1`,`soldto2`,`cpstphone_soldto`,`address1`,`address2`,`city`,`state`,`postalcode`,`billcountry`,`billmail`)VALUES ('$id_order','$soldto','$soldto2','$soldto_phone','$solto_address1','$solto_address2','$solto_city','$solto_state','$solto_zip','$solto_country','$soldto_mail')";
                                        mysqli_query($link, $sql2)or invalidoperation(mysqli_error($link)); //ok
                                        //Insertar los datos de tbldirector
                                        $sql5 = "Insert INTO `tbldirector`(`id_director`)VALUES ('$id_order')";
                                        mysqli_query($link, $sql5)or invalidoperation(mysqli_error($link)); //ok
                                        //Inserto los detalles del primer producto de la orden
                                        $sql3 = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`Custnumber`,`Ponumber`,`cpitem`,`cpcantidad`,`farm`,`satdel`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`codigo`,`vendor`,`consolidado`)VALUES ('$id_order','$CUSTnbr','$Ponumber','$Item','1','$Farm','$SatDel','$ShipCtry','$Currency','$Origin','$UOM','$deliver','$SHIPDT','$Tracking','$estado','$descargada','$user','0','No','$status','No','$POlin','$Unitprice','$UPS','0','$vendor','$consolidado')";

                                        //echo $sql3;
                                        mysqli_query($link, $sql3)or invalidoperation(mysqli_error($link));

                                        //Guardar datos de la operacion de subida de ordenes
                                        /*                                         * ***** Subir Orden ******************* */
                                        /*                                         * ***** Descargar Orden *************** */
                                        /*                                         * ***** Subir tracking  *************** */

                                        $fecha = date('Y-m-d H:i:s');
                                        $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`) VALUES ('$usuario','Subir Orden','$fecha','$ip')";
                                        //                                    
                                        //Imprimir la orden leida
                                        $modalcontent .= '<tr ALIGN=center VALIGN=center>';
                                        $modalcontent .= '<td>' . $orden . "</td>";
                                        $modalcontent .= '<td>' . $Orddate . "</td>";
                                        $modalcontent .= '<td>' . $Ponumber . "</td>";
                                        $modalcontent .= '<td>' . $CUSTnbr . "</td>";
                                        $modalcontent .= '<td>1</td>';
                                        $modalcontent .= '<td>' . $Item . "</td>";
                                        $modalcontent .= '</tr>';
                                        $orden ++;
                                    } else {

                                        //Inserto los detalles del primer producto de la orden
                                        $sql3 = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`Custnumber`,`Ponumber`,`cpitem`,`cpcantidad`,`farm`,`satdel`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`codigo`,`vendor`,`consolidado`)VALUES ('$id_order','$CUSTnbr','$Ponumber','$Item','1','$Farm','$SatDel','$ShipCtry','$Currency','$Origin','$UOM','$deliver','$SHIPDT','$Tracking','$estado','$descargada','$user','0','No','$status','No','$POlin','$Unitprice','$UPS','0','$vendor','$consolidado')";
                                        //echo $sql3;
                                        mysqli_query($link, $sql3)or invalidoperation(mysqli_error($link));

                                        //Guardar datos de la operacion de subida de ordenes
                                        /*                                         * ***** Subir Orden ******************* */
                                        /*                                         * ***** Descargar Orden *************** */
                                        /*                                         * ***** Subir tracking  *************** */

                                        $fecha = date('Y-m-d H:i:s');
                                        $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`) 
                                                                                                               VALUES ('$usuario','Subir Orden'" . $Ponumber . ",'$fecha','$ip')";
                                        //                                    
                                        //imprimo la orden leida
                                        //Imprimir la orden leida
                                        $modalcontent .= '<tr ALIGN=center VALIGN=center>';
                                        $modalcontent .= '<td>' . $orden . "</td>";
                                        $modalcontent .= '<td>' . $Orddate . "</td>";
                                        $modalcontent .= '<td>' . $Ponumber . "</td>";
                                        $modalcontent .= '<td>' . $CUSTnbr . "</td>";
                                        $modalcontent .= '<td>1</td>';
                                        $modalcontent .= '<td>' . $Item . "</td>";
                                        $modalcontent .= '</tr>';
                                        $orden++;
                                    }//for
                                }//else
                            }//else
                        }
                    }// fin foreach	
                }// fin for		
                $modalcontent .= '</tbody></table></div>';
            }

            //CErrando la conexion a mysqli_
            //			mysqli_close($conection);

            $handle = opendir($dir);
            while ($file = readdir($handle)) {
                if (is_file($dir . $file)) {
                    unlink($dir . $file);
                }
            }
        }
        $_SESSION['showmodal'] = 'yes';
        $_SESSION['modalcontent'] = $modalcontent;
    } else {
        $msg = "No hay ningun arhivo para subir";
        $box = "danger";
    }
}

if (isset($_POST["subir_trackingsfedex_fincas"])) {
    ////////////////////////////////////////////////////////////////////////////SUBIMOS EL ARCHIVO
    if ($_FILES["archivo"]["name"][0]) {
        # recorremos todos los arhivos que se han subido
        for ($i = 0; $i < count($_FILES["archivo"]["name"]); $i++) {
            #divide el nombre del fichero con un .    
            $explode_name = explode('.', $_FILES["archivo"]["name"][$i]);
            # si es un formato de excel
            if ($explode_name[1] == 'csv') {
                # si exsite la carpeta o se ha creado
                if (file_exists($carpetaDestino) || @mkdir($carpetaDestino)) {
                    $origen = $_FILES["archivo"]["tmp_name"][$i];
                    $destino = $carpetaDestino . $_FILES["archivo"]["name"][$i];


                    # movemos el archivo
                    if (@move_uploaded_file($origen, $destino)) {
                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                        $modalcontent .= "<br>" . $_FILES["archivo"]["name"][$i] . " subido correctamente <br>";
                        $modalcontent .= "</td></tr>";
                        //echo $_FILES['archivo']['name'][$i];
                        //unlink($_FILES['archivo']['name'][$i]);
                        //header('Location: index.php');
                    } else {
                        echo "<br>No se ha podido mover el archivo: " . $_FILES["archivo"]["name"][$i];
                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                        $modalcontent .= "<br>No se ha podido mover el archivo: " . $_FILES["archivo"]["name"][$i] . " <br>";
                        $modalcontent .= "</td></tr>";
                    }
                } else {
                    //                echo "<br>No se ha podido crear la carpeta: up/" . $user;
                }
            } else {
                $msg = "No se logro subir el archivo. " . $_FILES["archivo"]["name"][$i] . " - Formato no admitido";
                $box = "danger";
                goto invaliformat;
            }
        }
        ////////////////////////////////////////////////////////////////////////////PROCESAMOS EL ARCHIVO
        $orden = 0;
        $fila = 1;
        $array = array();
        $dir = $carpetaDestino;
        //contar archivos
        $total_excel = count(glob("$dir/{*.csv}", GLOB_BRACE));  //("$dir/{*.xlsx,*.xls,*.csv}",GLOB_BRACE));
        if ($total_excel == 0) {
            $msg = "No se logro subir el archivo";
            $box = "danger";
        } else {
            //renombrarlos para cargarlos
            $a = 1;
            $excels = (glob("$dir/{*.csv}", GLOB_BRACE));
            foreach ($excels as $cvs) {
                $expr = explode("/", $cvs);
                $nombre = array_pop($expr);
                rename("$dir/$nombre", "$dir/$a.csv");
                $a++;
            }
        }

        //Aqui leemos cada uno de los excel cargados y se guardan sus datos a la BD
        for ($i = 1; $i <= $total_excel; $i++) {
            $orden ++;
            if (($gestor = fopen("$dir/$i.csv", "r")) !== FALSE) {
                while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                    $numero = count($datos);
                    for ($c = 0; $c < $numero; $c++) {
                        $array [$fila][$c] = addslashes($datos[$c]);
                    }
                    $fila++;
                }
                //cierro el handle de directorio
                fclose($gestor);
                //elimino el excel leido del servidor
                unlink("$dir/$i.csv");
            }
        }

        $j = 3;
        $contador = 1;
        $fila = $fila - 1;
        $modalcontent .= "<tr ALIGN=center ><td colspan=\"6\"><strong>Cantidad de filas del archivo leído: " . $fila . "</strong></td>";

        //Imprimo el encabezado de cada archivo
        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'>";
        $modalcontent .= '<td><strong># Fila</strong></td>';
        $modalcontent .= '<td><strong>Fecha de Órden</strong></td>';
        $modalcontent .= '<td><strong>Ponumber</strong></td>';
        $modalcontent .= '<td><strong>Custnumber</strong></td>';
        $modalcontent .= '<td><strong>Cantidad</strong></td>';
        $modalcontent .= '<td><strong>Producto</strong></td>';
        $modalcontent .= '</tr>';
//        echo "helloworld";
        while ($j <= $fila) { //Aqui recorro cada una de las filas leida de las ordenes
            $po_cust = explode("_", $array[$j][2]);

            $Tracking = $array [$j]['0'];
            $Ponumber = trim($po_cust[0]);
            $CustNumber = $po_cust[1];
            $item = $array [$j]['1'];
            $Guia_madre = $array [$j]['3'];
            $Guia_hija = $array [$j]['4'];

            $vuelo = $array [$j]['5'];
            $entrega = $array [$j]['6'];

            $servicio = $array [$j]['7'];
            $aerolinea = $array [$j]['8'];

            if ($Tracking == "" || $Ponumber == "" || $CustNumber == "" || $item == "" ||
                    $Guia_madre == "" || $Guia_hija == "" || $vuelo == "" || $entrega == "" ||
                    $servicio == "" || $aerolinea == "") {
                $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                $modalcontent .= "<font color=\"red\"> Hay campos vacios, por favor revise la fila " . $j . " </font><br> ";
                $modalcontent .= "</td></tr>";
                $j++;
                continue;
            }

            if ($consolidado != "Y") {
//                Armar la feca de vuelo
                list($anno, $mes, $dia ) = explode('/', $vuelo);
                if ($dia == '') {
                    $vuelo = $vuelo;
                } else {
                    $vuelo = $anno . "-" . $mes . "-" . $dia;
                }

                //Armar la feca de entrega
                list($anno, $mes, $dia ) = explode('/', $entrega);
                if ($dia == '') {
                    $entrega = $entrega;
                } else {
                    $entrega = $anno . "-" . $mes . "-" . $dia;
                }
            }

            //Consultar la BD para identificar que id tiene la orden con el ponumber y custnumber leido
            //selecciona los registros asociados a Ponumber and Custnumber 
            if ($Ponumber == '' || $CustNumber == '' || $Guia_madre == '' || $Guia_hija == '') {
                $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                $modalcontent .= "<font color='red'>La orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item . " le faltan datos, por favor revise.</font><br>";
                $modalcontent .= "</td></tr>";
                $j++;
            } else if (!validar_guia(trim($Guia_madre), 'm') || !validar_guia(trim($Guia_hija), 'h')) {
                $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                $modalcontent .= "<font color='red'>La orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item . " tiene errores en los formatos de las guias madre e hija.</font><br>";
                $modalcontent .= "</td></tr>";
                $j++;
            } else {
                //Verifico si la orden es reshipped
                $query = "select id_orden_detalle,tracking from tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item' and reenvio= 'Forwarded'";
                $row = mysqli_query($link, $query) or die("Error verificando si la orden es un reenvio");
                $ray = mysqli_num_rows($row); //cuento las filas devueltas
                //Si tiene reshiped actualizo las ordenes con reshiped
                if ($ray != 0) {
                    //insertar nuevo tracking
                    $query = "select tracking, Ponumber, Custnumber, cpitem from tbldetalle_orden where tracking = '$Tracking'";
                    $row = mysqli_query($link, $query) or die("Error verificando si el tracking existe");
                    $ray = mysqli_num_rows($row); //cuento las filas devueltas
                    //si existe este tracking ya en el detalleorden
                    if ($ray != 0) {
                        $sql = mysqli_fetch_array($row);
                        $TRACKING = $sql['tracking'];
                        $PONUMBER = $sql['Ponumber'];
                        $CUSTNUMBER = $sql['Ponumber'];
                        $ITEM = $sql['cpitem'];

                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                        $modalcontent .= "<font color='red'>La orden con Ponumber " . $Ponumber . " , Custnumber " . $CustNumber . " ya tiene un tracking asignado que es: " . $TRACKING . " y usted intenta agregar este tracking: " . $Tracking . "<font><br>";
                        $modalcontent .= "</td></tr>";
                        $j++;
                    } else {

                        //Pregunto si el custnumber y ponumber e item existen, de ser asi asi lo actualizo
                        $query = "SELECT id_orden_detalle,tracking from tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item' and reenvio= 'Forwarded'";
                        //echo "select id_detalleorden from  tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item'";

                        $row = mysqli_query($link, $query) or die("Error verificando si la orden existe");
                        $ray = mysqli_num_rows($row); //cuento las filas devueltas

                        if ($ray == 0) {
                            //si no obtubo ninguna fila es pq esa orden no ha sido introducida
                            $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                            $modalcontent .= "<font color='red'>La orden " . $j . " no existe en el sistema. Por favor inserte la orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item . "</font><br>";
                            $modalcontent .= "</td></tr>";
                            $j++;
                        } else {
                            for ($i = 0; $i < $ray; $i++) {
                                $sql = mysqli_fetch_array($row);
                                $id_order = $sql['id_orden_detalle'];
                                $tracking = $sql['tracking'];

                                $Tracking = $array [$j]['0'];
                                $Ponumber = trim($po_cust[0]);
                                $CustNumber = $po_cust[1];
                                $item = $array [$j]['1'];
                                //echo "el tracing es: ".$tracking;
                                if ($tracking == '') {
                                    //si las ordenes subidas son consolidadas el mismo tracking subido es el codigo de la orden
                                    if ($consolidado == 'Y') {
                                        $codigo = $Tracking;
                                    } else {
                                        //Se genera el codigo unico de la caja
                                        $codigo = generarCodigoUnico();
                                        //Se inserta en la tabla de codigos
                                        $consulta = "INSERT INTO tblcodigo (`codigo`,`finca`) VALUES ('$codigo','$finca')";
                                        $ejecutar = mysqli_query($link, $consulta) or die("Error insertando el código único");
                                    }

                                    $fecha = date('Y-m-d');
                                    //Crear una entrada al cuarto frio de las fincas autonomas
                                    $sql = "INSERT INTO tblcoldrom_fincas (`codigo_unico`,`item`, `finca`,`fecha`,`guia_m`, `guia_h`,`entrega`,`servicio`,`vuelo`,`aerolinea`,`tracking_asig`) VALUES ('$codigo','$item','$finca','$fecha','$Guia_madre','$Guia_hija','$entrega','$servicio','$vuelo','$aerolinea','$Tracking')";

                                    $insertado = mysqli_query($link, $sql) or die("COLDROMMFINCAS ERROR " . mysqli_error($link));

                                    //Actualizar la orden con los datos de la finca y caja
                                    $sql11 = "Update tbldetalle_orden Set tracking='$Tracking', status = 'Shipped', farm='$finca', codigo='$codigo' where id_orden_detalle = '$id_order'"; // actualizar el eBing ='$eBing',
                                    $actualizado = mysqli_query($link, $sql11);



                                    //echo "Update tracking Set tracking='$Tracking', eBing ='$eBing' where id = '$id'";
                                    if ($actualizado && $insertado) {
                                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                        $modalcontent .= "El tracking: " . $Tracking . " fue cargado correctamente.<br>";
                                        $modalcontent .= "</td></tr>";
                                        $j++;
                                    } else {
                                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                        $modalcontent .= "<font color='red'>Error cargando el tracking.</font><br>";
                                        $modalcontent .= "</td></tr>";
                                    }
                                } else {
                                    $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                    $modalcontent .= "<font color='red'>La orden con el tracking: " . $tracking . " ya fue insertado anteriormente.</font><br>";
                                    $modalcontent .= "</td></tr>";
                                    $j++;
                                }
                            }
                        }
                    }
                } else {

                    //insertar nuevo tracking
                    $query = "select tracking, Ponumber, Custnumber, cpitem from tbldetalle_orden where tracking = '$Tracking'";
                    $row = mysqli_query($link, $query) or die("Error verificando el tracking");
                    $ray = mysqli_num_rows($row); //cuento las filas devueltas
                    //si existe este tracking
                    if ($ray != 0) {
                        $sql = mysqli_fetch_array($row);
                        $TRACKING = $sql['tracking'];
                        $PONUMBER = $sql['Ponumber'];
                        $CUSTNUMBER = $sql['Ponumber'];
                        $ITEM = $sql['cpitem'];

                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                        $modalcontent .= "<font color='red'>La orden con Ponumber " . $Ponumber . " , Custnumber " . $CustNumber . " ya tiene un tracking asignado que es: " . $TRACKING . " y usted intenta agregar este tracking: " . $Tracking . "<font><br>";
                        $modalcontent .= "</td></tr>";
                        $j++;
                    } else {
                        //Pregunto si el custnumber y ponumber e item existen, de ser asi asi lo actualizo
                        $query = "select id_orden_detalle,tracking from tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item'";
                        //echo "select id_detalleorden from  tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item'";

                        $row = mysqli_query($link, $query) or die("Error verificando si la orden existe");
                        $ray = mysqli_num_rows($row); //cuento las filas devueltas

                        if ($ray == 0) {
                            //si no obtubo ninguna fila es pq esa orden no ha sido introducida
                            $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                            $modalcontent .= "<font color='red'>La orden no existe en el sistema. Por favor inserte la orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item . "</font><br>";
                            $modalcontent .= "</td></tr>";
                            $j++;
                        } else {
                            for ($i = 0; $i < $ray; $i++) {
                                $sql = mysqli_fetch_array($row);
                                $id_order = $sql['id_orden_detalle'];
                                $tracking = $sql['tracking'];

                                $Tracking = $array [$j]['0'];
                                $Ponumber = trim($po_cust[0]);
                                $CustNumber = $po_cust[1];
                                $item = $array [$j]['1'];

                                if ($tracking == '') {
                                    //si las ordenes subidas son consolidadas el mismo tracking subido es el codigo de la orden
                                    if ($consolidado == 'Y') {
                                        $codigo = $Tracking;
                                    } else {
                                        //Se genera el codigo unico de la caja
                                        $codigo = generarCodigoUnico();
                                        //Se inserta en la tabla de codigos
                                        $consulta = "INSERT INTO tblcodigo (`codigo`,`finca`) VALUES ('$codigo','$finca')";
                                        $ejecutar = mysqli_query($link, $consulta) or die("Error insertando el código único");
                                    }

                                    $fecha = date('Y-m-d');
                                    //Crear una entrada al cuarto frio de las fincas autonomas
                                    $sql = "INSERT INTO tblcoldrom_fincas (`codigo_unico`,`item`, `finca`,`fecha`,`guia_m`, `guia_h`,`entrega`,`servicio`,`vuelo`,`aerolinea`,`tracking_asig`) VALUES ('$codigo','$item','$finca','$fecha','$Guia_madre','$Guia_hija','$entrega','$servicio','$vuelo','$aerolinea','$Tracking')";
                                    $insertado = mysqli_query($link, $sql) or die("COLDROMMFINCAS ERROR " . mysqli_error($link));

                                    //Actualizar la orden con los datos de la finca y caja
                                    $sql11 = "Update tbldetalle_orden Set tracking='$Tracking', status = 'Shipped', farm='$finca', codigo='$codigo' where id_orden_detalle = '$id_order'"; // actualizar el eBing ='$eBing',
                                    $actualizado = mysqli_query($link, $sql11);


                                    //echo "Update tracking Set tracking='$Tracking', eBing ='$eBing' where id = '$id'";
                                    if ($actualizado && $insertado) {
                                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                        $modalcontent .= "El tracking: " . $Tracking . " fue cargado correctamente.<br>";
                                        $modalcontent .= "</td></tr>";
                                        $j++;
                                    } else {
                                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                        $modalcontent .= "<font color='red'>Error cargando el tracking.</font><br>";
                                        $modalcontent .= "</td></tr>";
                                    }
                                } else {
                                    $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                    $modalcontent .= "<font color='red'><font color='red'>La orden con el tracking: " . $tracking . " ya fue insertado anteriormente.</font><br>";
                                    $modalcontent .= "</td></tr>";
                                    $j++;
                                }
                            }//fin for
                        }//else
                    }//else
                }
            }
        } //while
        /* $handle = opendir($dir); 
          while ($file = readdir($handle))  {
          if (is_file($dir.$file)) {
          unlink($dir.$file);
          }
          }
         */



        $_SESSION['showmodal'] = 'yes';
        $_SESSION['modalcontent'] = $modalcontent;
    } else {
        $msg = "No hay ningun arhivo para subir";
        $box = "danger";
    }
}

if (isset($_POST["subir_trackings_fincas"])) {
    if ($_FILES["archivo"]["name"][0]) {
        # recorremos todos los arhivos que se han subido
        for ($i = 0; $i < count($_FILES["archivo"]["name"]); $i++) {
            #divide el nombre del fichero con un .    
            $explode_name = explode('.', $_FILES["archivo"]["name"][$i]);
            # si es un formato de excel
            if ($explode_name[1] == 'csv') {
                # si exsite la carpeta o se ha creado
                if (file_exists($carpetaDestino) || @mkdir($carpetaDestino)) {
                    $origen = $_FILES["archivo"]["tmp_name"][$i];
                    $destino = $carpetaDestino . $_FILES["archivo"]["name"][$i];


                    # movemos el archivo
                    if (@move_uploaded_file($origen, $destino)) {
                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                        $modalcontent .= $_FILES["archivo"]["name"][$i] . " movido correctamente";
                        $modalcontent .= "</td></tr>";
                        $orden = 0;
                        $fila = 1;
                        $array = array();
                        $dir = $carpetaDestino;
                        //contar archivos
                        $total_excel = count(glob("$dir/{*.csv}", GLOB_BRACE));  //("$dir/{*.xlsx,*.xls,*.csv}",GLOB_BRACE));
                        if ($total_excel == 0) {
                            $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                            $modalcontent .= " No hay archivo para leer o el formato de archivo no es csv...";
                            $modalcontent .= "</td></tr>";
                        } else {
                            $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                            $modalcontent .= "Total de archivos cargados: " . $total_excel;
                            $modalcontent .= "</td></tr>";

                            //renombrarlos para cargarlos
                            $a = 1;
                            $excels = (glob("$dir/{*.csv}", GLOB_BRACE));
                            foreach ($excels as $cvs) {
                                $expr = explode("/", $cvs);
                                $nombre = array_pop($expr);
                                rename("$dir/$nombre", "$dir/$a.csv");
                                $a++;
                            }
                        }

                        //Aqui leemos cada uno de los excel cargados y se guardan sus datos a la BD
                        for ($i = 1; $i <= $total_excel; $i++) {
                            $orden ++;
                            if (($gestor = fopen("$dir/$i.csv", "r")) !== FALSE) {
                                while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                                    $numero = count($datos);
                                    for ($c = 0; $c < $numero; $c++) {
                                        $array [$fila][$c] = addslashes($datos[$c]);
                                    }
                                    $fila++;
                                }
                                //cierro el handle de directorio
                                fclose($gestor);
                                //elimino el excel leido del servidor
                                unlink("$dir/$i.csv");
                            }
                        }

                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'>";
                        $modalcontent .= '<td><strong>PONumber</strong></td>';
                        $modalcontent .= '<td><strong>Custnumber</strong></td>';
                        $modalcontent .= '<td><strong>Item</strong></td>';
                        $modalcontent .= '<td><strong>Tracking</strong></td>';
                        $modalcontent .= '<td><strong>G. Madre</strong></td>';
                        $modalcontent .= '<td><strong>G. Hija</strong></td>';
                        $modalcontent .= '</tr>';
                        $j = 2;
                        $contador = 1;
                        $fila = $fila - 1;

                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                        $modalcontent .= "Cantidad de filas del archivo leído: " . $fila;
                        $modalcontent .= "</td></tr>";
                        while ($j <= $fila) { //Aqui recorro cada una de las filas leida de las ordenes
                            $Tracking = $array [$j]['5'];
                            $Ponumber = $array [$j]['2'];
                            $CustNumber = $array [$j]['3'];
                            $item = $array [$j]['4'];
                            $Guia_madre = $array [$j]['13'];
                            $Guia_hija = $array [$j]['14'];

                            $consolidado = $array [$j]['19'];
                            $vuelo = $array [$j]['15'];
                            $entrega = $array [$j]['16'];

                            //si no es consolidado hay que formatear las fechas al formato que tienen la db
                            if ($consolidado != "Y") {
//                Armar la feca de vuelo
                                list($anno, $mes, $dia ) = explode('/', $vuelo);
                                if ($dia == '') {
                                    $vuelo = $vuelo;
                                } else {
                                    $vuelo = $anno . "-" . $mes . "-" . $dia;
                                }

                                //Armar la feca de entrega
                                list($anno, $mes, $dia ) = explode('/', $entrega);
                                if ($dia == '') {
                                    $entrega = $entrega;
                                } else {
                                    $entrega = $anno . "-" . $mes . "-" . $dia;
                                }
                            }

                            $servicio = $array [$j]['17'];
                            $aerolinea = $array [$j]['18'];

                            //Consultar la BD para identificar que id tiene la orden con el ponumber y custnumber leido
                            //selecciona los registros asociados a Ponumber and Custnumber 
                            if ($Ponumber == '' || $CustNumber == '' || $Guia_madre == '' || $Guia_hija == '') {
                                $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                $modalcontent .= "La orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item . " le faltan datos, por favor revise.";
                                $modalcontent .= "</td></tr>";
                                $j++;
                            } else if (!validar_guia(trim($Guia_madre), 'm') || !validar_guia(trim($Guia_hija), 'h')) {
                                $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                $modalcontent .= "La orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item . " tiene errores en los formatos de las guias madre e hija.";
                                $modalcontent .= "</td></tr>";
                                $j++;
                            } else {
                                //Verifico si la orden es reshipped
                                $query = "select id_orden_detalle,tracking from tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item' and reenvio= 'Forwarded'";
                                $row = mysqli_query($link, $query) or die("Error verificando si la orden es un reenvio");
                                $ray = mysqli_num_rows($row); //cuento las filas devueltas
                                //Si tiene reshiped actualizo las ordenes con reshiped
                                if ($ray != 0) {
                                    //insertar nuevo tracking
                                    $query = "select tracking, Ponumber, Custnumber, cpitem from tbldetalle_orden where tracking = '$Tracking'";
                                    $row = mysqli_query($link, $query) or die("Error verificando si el tracking existe");
                                    $ray = mysqli_num_rows($row); //cuento las filas devueltas
                                    //si existe este tracking ya en el detalleorden
                                    if ($ray != 0) {
                                        $sql = mysqli_fetch_array($row);
                                        $TRACKING = $sql['tracking'];
                                        $PONUMBER = $sql['Ponumber'];
                                        $CUSTNUMBER = $sql['Ponumber'];
                                        $ITEM = $sql['cpitem'];
                                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                        $modalcontent .= "La orden con Ponumber " . $Ponumber . " , Custnumber " . $CustNumber . " ya tiene un tracking asignado que es: " . $TRACKING . " y usted intenta agregar este tracking: " . $Tracking;
                                        $modalcontent .= "</td></tr>";
                                        $j++;
                                    } else {

                                        //Pregunto si el custnumber y ponumber e item existen, de ser asi asi lo actualizo
                                        $query = "SELECT id_orden_detalle,tracking from tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item' and reenvio= 'Forwarded'";
                                        //echo "select id_detalleorden from  tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item'";

                                        $row = mysqli_query($link, $query) or die("Error verificando si la orden existe");
                                        $ray = mysqli_num_rows($row); //cuento las filas devueltas

                                        if ($ray == 0) {
                                            //si no obtubo ninguna fila es pq esa orden no ha sido introducida
                                            $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                            $modalcontent .= "La orden no existe en el sistema. Por favor inserte la orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item;
                                            $modalcontent .= "</td></tr>";
                                            $j++;
                                        } else {
                                            for ($i = 0; $i < $ray; $i++) {
                                                $sql = mysqli_fetch_array($row);
                                                $id_order = $sql['id_orden_detalle'];
                                                $tracking = $sql['tracking'];

                                                $Tracking = $array [$j]['5'];
                                                $Ponumber = $array [$j]['2'];
                                                $CustNumber = $array [$j]['3'];
                                                $item = $array [$j]['4'];
                                                //echo "el tracing es: ".$tracking;
                                                if ($tracking == '') {
                                                    //si las ordenes subidas son consolidadas el mismo tracking subido es el codigo de la orden
                                                    if ($consolidado == 'Y') {
                                                        $codigo = $Tracking;
                                                    } else {
                                                        //Se genera el codigo unico de la caja
                                                        $codigo = generarCodigoUnico();
                                                        //Se inserta en la tabla de codigos
                                                        $consulta = "INSERT INTO tblcodigo (`codigo`,`finca`) VALUES ('$codigo','$finca')";
                                                        $ejecutar = mysqli_query($link, $consulta) or die("Error insertando el código único");
                                                    }

                                                    $fecha = date('Y-m-d');
                                                    //Crear una entrada al cuarto frio de las fincas autonomas
                                                    $sql = "INSERT INTO tblcoldrom_fincas (`codigo_unico`,`item`, `finca`,`fecha`,`guia_m`, `guia_h`,`entrega`,`servicio`,`vuelo`,`aerolinea`,`tracking_asig`) VALUES ('$codigo','$item','$finca','$fecha','$Guia_madre','$Guia_hija','$entrega','$servicio','$vuelo','$aerolinea','$Tracking')";

                                                    $insertado = mysqli_query($link, $sql) or die("COLDROMMFINCAS ERROR " . mysqli_error($link));

                                                    //Actualizar la orden con los datos de la finca y caja
                                                    $sql11 = "Update tbldetalle_orden Set tracking='$Tracking', status = 'Shipped', farm='$finca', codigo='$codigo' where id_orden_detalle = '$id_order'"; // actualizar el eBing ='$eBing',
                                                    $actualizado = mysqli_query($link, $sql11);



                                                    //echo "Update tracking Set tracking='$Tracking', eBing ='$eBing' where id = '$id'";
                                                    if ($actualizado && $insertado) {
                                                        $modalcontent .= '<tr ALIGN=center VALIGN=center>';
                                                        $modalcontent .= '<td>' . $Ponumber . "</td>";
                                                        $modalcontent .= '<td>' . $CustNumber . "</td>";
                                                        $modalcontent .= '<td>' . $item . "</td>";
                                                        $modalcontent .= '<td>' . $Tracking . "</td>";
                                                        $modalcontent .= '<td>' . $Guia_madre . '</td>';
                                                        $modalcontent .= '<td>' . $Guia_hija . "</td>";
                                                        $modalcontent .= '</tr>';
                                                        $j++;
                                                    } else {
                                                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                                        $modalcontent .= "<font color='red'>Error cargando el tracking.</font>";
                                                        $modalcontent .= "</td></tr>";
                                                    }
                                                } else {
                                                    $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                                    $modalcontent .= "<font color='red'>La orden con el tracking: " . $tracking . " ya fue insertado anteriormente.</font>";
                                                    $modalcontent .= "</td></tr>";
                                                    $j++;
                                                }
                                            }
                                        }
                                    }
                                } else {

                                    //insertar nuevo tracking
                                    $query = "select tracking, Ponumber, Custnumber, cpitem from tbldetalle_orden where tracking = '$Tracking'";
                                    $row = mysqli_query($link, $query) or die("Error verificando el tracking");
                                    $ray = mysqli_num_rows($row); //cuento las filas devueltas
                                    //si existe este tracking
                                    if ($ray != 0) {
                                        $sql = mysqli_fetch_array($row);
                                        $TRACKING = $sql['tracking'];
                                        $PONUMBER = $sql['Ponumber'];
                                        $CUSTNUMBER = $sql['Ponumber'];
                                        $ITEM = $sql['cpitem'];
                                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                        $modalcontent .= "<font color='red'>La orden con Ponumber " . $Ponumber . " , Custnumber " . $CustNumber . " ya tiene un tracking asignado que es: " . $TRACKING . " y usted intenta agregar este tracking: " . $Tracking . "<font>";
                                        $modalcontent .= "</td></tr>";
                                        $j++;
                                    } else {
                                        //Pregunto si el custnumber y ponumber e item existen, de ser asi asi lo actualizo
                                        $query = "select id_orden_detalle,tracking from tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item'";
                                        //echo "select id_detalleorden from  tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item'";

                                        $row = mysqli_query($link, $query) or die("Error verificando si la orden existe");
                                        $ray = mysqli_num_rows($row); //cuento las filas devueltas

                                        if ($ray == 0) {
                                            //si no obtubo ninguna fila es pq esa orden no ha sido introducida
                                            $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                            $modalcontent .= "<font color='red'>La orden no existe en el sistema. Por favor inserte la orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item . "</font>";
                                            $modalcontent .= "</td></tr>";
                                            $j++;
                                        } else {
                                            for ($i = 0; $i < $ray; $i++) {
                                                $sql = mysqli_fetch_array($row);
                                                $id_order = $sql['id_orden_detalle'];
                                                $tracking = $sql['tracking'];

                                                $Tracking = $array [$j]['5'];
                                                $Ponumber = $array [$j]['2'];
                                                $CustNumber = $array [$j]['3'];
                                                $item = $array [$j]['4'];

                                                if ($tracking == '') {
                                                    //si las ordenes subidas son consolidadas el mismo tracking subido es el codigo de la orden
                                                    if ($consolidado == 'Y') {
                                                        $codigo = $Tracking;
                                                    } else {
                                                        //Se genera el codigo unico de la caja
                                                        $codigo = generarCodigoUnico();
                                                        //Se inserta en la tabla de codigos
                                                        $consulta = "INSERT INTO tblcodigo (`codigo`,`finca`) VALUES ('$codigo','$finca')";
                                                        $ejecutar = mysqli_query($link, $consulta) or die("Error insertando el código único");
                                                    }

                                                    $fecha = date('Y-m-d');
                                                    //Crear una entrada al cuarto frio de las fincas autonomas
                                                    $sql = "INSERT INTO tblcoldrom_fincas (`codigo_unico`,`item`, `finca`,`fecha`,`guia_m`, `guia_h`,`entrega`,`servicio`,`vuelo`,`aerolinea`,`tracking_asig`) VALUES ('$codigo','$item','$finca','$fecha','$Guia_madre','$Guia_hija','$entrega','$servicio','$vuelo','$aerolinea','$Tracking')";
                                                    $insertado = mysqli_query($link, $sql) or die("COLDROMMFINCAS ERROR " . mysqli_error($link));

                                                    //Actualizar la orden con los datos de la finca y caja
                                                    $sql11 = "Update tbldetalle_orden Set tracking='$Tracking', status = 'Shipped', farm='$finca', codigo='$codigo' where id_orden_detalle = '$id_order'"; // actualizar el eBing ='$eBing',
                                                    $actualizado = mysqli_query($link, $sql11);


                                                    //echo "Update tracking Set tracking='$Tracking', eBing ='$eBing' where id = '$id'";
                                                    if ($actualizado && $insertado) {
                                                        $modalcontent .= '<tr ALIGN=center VALIGN=center>';
                                                        $modalcontent .= '<td>' . $Ponumber . "</td>";
                                                        $modalcontent .= '<td>' . $CustNumber . "</td>";
                                                        $modalcontent .= '<td>' . $item . "</td>";
                                                        $modalcontent .= '<td>' . $Tracking . "</td>";
                                                        $modalcontent .= '<td>' . $Guia_madre . '</td>';
                                                        $modalcontent .= '<td>' . $Guia_hija . "</td>";
                                                        $modalcontent .= '</tr>';
                                                        $j++;
                                                    } else {
                                                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                                        $modalcontent .= "<font color='red'>Error cargando el tracking.</font>";
                                                        $modalcontent .= "</td></tr>";
                                                    }
                                                } else {
                                                    $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                                    $modalcontent .= "<font color='red'>La orden con el tracking: " . $tracking . " ya fue insertado anteriormente.</font>";
                                                    $modalcontent .= "</td></tr>";
                                                    $j++;
                                                }
                                            }//fin for
                                        }//else
                                    }//else
                                }
                            }
                        }

                        $_SESSION['showmodal'] = 'yes';
                        $_SESSION['modalcontent'] = $modalcontent;
                    } else {
                        $msg = "No se ha podido mover el archivo: " . $_FILES["archivo"]["name"][$i];
                        $box = "danger";
                    }
                } else {
                    $msg = "No se ha podido crear la carpeta: up/" . $user;
                    $box = "danger";
                }
            } else {
                $msg = $_FILES["archivo"]["name"][$i] . " - Formato no admitido";
                $box = "danger";
            }
        }
    } else {
        $msg = "No hay ningun arhivo para subir";
        $box = "danger";
    }
}

if (isset($_POST["subir_trackings_fincas_SR"])) {
    if ($_FILES["archivo"]["name"][0]) {
        # recorremos todos los arhivos que se han subido
        for ($i = 0; $i < count($_FILES["archivo"]["name"]); $i++) {
            #divide el nombre del fichero con un .    
            $explode_name = explode('.', $_FILES["archivo"]["name"][$i]);
            # si es un formato de excel
            if ($explode_name[1] == 'csv') {
                # si exsite la carpeta o se ha creado
                if (file_exists($carpetaDestino) || @mkdir($carpetaDestino)) {
                    $origen = $_FILES["archivo"]["tmp_name"][$i];
                    $destino = $carpetaDestino . $_FILES["archivo"]["name"][$i];


                    # movemos el archivo
                    if (@move_uploaded_file($origen, $destino)) {
                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                        $modalcontent .= $_FILES["archivo"]["name"][$i] . " movido correctamente";
                        $modalcontent .= "</td></tr>";
                        $orden = 0;
                        $fila = 1;
                        $array = array();
                        $dir = $carpetaDestino;
                        //contar archivos
                        $total_excel = count(glob("$dir/{*.csv}", GLOB_BRACE));  //("$dir/{*.xlsx,*.xls,*.csv}",GLOB_BRACE));
                        if ($total_excel == 0) {
                            $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                            $modalcontent .= " No hay archivo para leer o el formato de archivo no es csv...";
                            $modalcontent .= "</td></tr>";
                        } else {
                            $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                            $modalcontent .= "Total de archivos cargados: " . $total_excel;
                            $modalcontent .= "</td></tr>";

                            //renombrarlos para cargarlos
                            $a = 1;
                            $excels = (glob("$dir/{*.csv}", GLOB_BRACE));
                            foreach ($excels as $cvs) {
                                $expr = explode("/", $cvs);
                                $nombre = array_pop($expr);
                                rename("$dir/$nombre", "$dir/$a.csv");
                                $a++;
                            }
                        }

                        //Aqui leemos cada uno de los excel cargados y se guardan sus datos a la BD
                        for ($i = 1; $i <= $total_excel; $i++) {
                            $orden ++;
                            if (($gestor = fopen("$dir/$i.csv", "r")) !== FALSE) {
                                while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                                    $numero = count($datos);
                                    for ($c = 0; $c < $numero; $c++) {
                                        $array [$fila][$c] = addslashes($datos[$c]);
                                    }
                                    $fila++;
                                }
                                //cierro el handle de directorio
                                fclose($gestor);
                                //elimino el excel leido del servidor
                                unlink("$dir/$i.csv");
                            }
                        }

                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'>";
                        $modalcontent .= '<td><strong>PONumber</strong></td>';
                        $modalcontent .= '<td><strong>Custnumber</strong></td>';
                        $modalcontent .= '<td><strong>Item</strong></td>';
                        $modalcontent .= '<td><strong>Tracking</strong></td>';
                        $modalcontent .= '<td><strong>G. Madre</strong></td>';
                        $modalcontent .= '<td><strong>G. Hija</strong></td>';
                        $modalcontent .= '</tr>';
                        $j = 2;
                        $contador = 1;
                        $fila = $fila - 1;

                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                        $modalcontent .= "Cantidad de filas del archivo leído: " . $fila;
                        $modalcontent .= "</td></tr>";
                        while ($j <= $fila) { //Aqui recorro cada una de las filas leida de las ordenes
                            $Tracking = $array [$j]['5'];
                            $Ponumber = $array [$j]['2'];
                            $CustNumber = $array [$j]['3'];
                            $item = $array [$j]['4'];
                            $Guia_madre = $array [$j]['13'];
                            $Guia_hija = $array [$j]['14'];

                            $consolidado = $array [$j]['19'];
                            $vuelo = $array [$j]['15'];
                            $entrega = $array [$j]['16'];

                            //si no es consolidado hay que formatear las fechas al formato que tienen la db
                            if ($consolidado != "Y") {
//                Armar la feca de vuelo
                                list($anno, $mes, $dia ) = explode('/', $vuelo);
                                if ($dia == '') {
                                    $vuelo = $vuelo;
                                } else {
                                    $vuelo = $anno . "-" . $mes . "-" . $dia;
                                }

                                //Armar la feca de entrega
                                list($anno, $mes, $dia ) = explode('/', $entrega);
                                if ($dia == '') {
                                    $entrega = $entrega;
                                } else {
                                    $entrega = $anno . "-" . $mes . "-" . $dia;
                                }
                            }

                            $servicio = $array [$j]['17'];
                            $aerolinea = $array [$j]['18'];

                            //Consultar la BD para identificar que id tiene la orden con el ponumber y custnumber leido
                            //selecciona los registros asociados a Ponumber and Custnumber 
                            if ($Ponumber == '' || $CustNumber == '' || $Guia_madre == '' || $Guia_hija == '') {
                                $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                $modalcontent .= "La orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item . " le faltan datos, por favor revise.";
                                $modalcontent .= "</td></tr>";
                                $j++;
                            } else if (!validar_guia(trim($Guia_madre), 'm') || !validar_guia(trim($Guia_hija), 'h')) {
                                $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                $modalcontent .= "La orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item . " tiene errores en los formatos de las guias madre e hija.";
                                $modalcontent .= "</td></tr>";
                                $j++;
                            } else {
                                //Verifico si la orden es reshipped
                                $query = "select id_orden_detalle,tracking from tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item' and reenvio= 'Forwarded'";
                                $row = mysqli_query($link, $query) or die("Error verificando si la orden es un reenvio");
                                $ray = mysqli_num_rows($row); //cuento las filas devueltas
                                //Si tiene reshiped actualizo las ordenes con reshiped
                                if ($ray != 0) {
                                    //insertar nuevo tracking
                                    $query = "select tracking, Ponumber, Custnumber, cpitem from tbldetalle_orden where tracking = '$Tracking'";
                                    $row = mysqli_query($link, $query) or die("Error verificando si el tracking existe");
                                    $ray = mysqli_num_rows($row); //cuento las filas devueltas
                                    //si existe este tracking ya en el detalleorden
                                    if ($ray == 9999) {
                                        $sql = mysqli_fetch_array($row);
                                        $TRACKING = $sql['tracking'];
                                        $PONUMBER = $sql['Ponumber'];
                                        $CUSTNUMBER = $sql['Ponumber'];
                                        $ITEM = $sql['cpitem'];
                                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                        $modalcontent .= "La orden con Ponumber " . $Ponumber . " , Custnumber " . $CustNumber . " ya tiene un tracking asignado que es: " . $TRACKING . " y usted intenta agregar este tracking: " . $Tracking;
                                        $modalcontent .= "</td></tr>";
                                        $j++;
                                    } else {

                                        //Pregunto si el custnumber y ponumber e item existen, de ser asi asi lo actualizo
                                        $query = "SELECT id_orden_detalle,tracking from tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item' and reenvio= 'Forwarded'";
                                        //echo "select id_detalleorden from  tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item'";

                                        $row = mysqli_query($link, $query) or die("Error verificando si la orden existe");
                                        $ray = mysqli_num_rows($row); //cuento las filas devueltas

                                        if ($ray == 0) {
                                            //si no obtubo ninguna fila es pq esa orden no ha sido introducida
                                            $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                            $modalcontent .= "La orden no existe en el sistema. Por favor inserte la orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item;
                                            $modalcontent .= "</td></tr>";
                                            $j++;
                                        } else {
                                            for ($i = 0; $i < $ray; $i++) {
                                                $sql = mysqli_fetch_array($row);
                                                $id_order = $sql['id_orden_detalle'];
                                                $tracking = $sql['tracking'];

                                                $Tracking = $array [$j]['5'];
                                                $Ponumber = $array [$j]['2'];
                                                $CustNumber = $array [$j]['3'];
                                                $item = $array [$j]['4'];
                                                //echo "el tracing es: ".$tracking;
                                                if ($tracking == '' || $tracking != '') {
                                                    //si las ordenes subidas son consolidadas el mismo tracking subido es el codigo de la orden
                                                    if ($consolidado == 'Y') {
                                                        $codigo = $Tracking;
                                                    } else {
                                                        //Se genera el codigo unico de la caja
                                                        $codigo = generarCodigoUnico();
                                                        //Se inserta en la tabla de codigos
                                                        $consulta = "INSERT INTO tblcodigo (`codigo`,`finca`) VALUES ('$codigo','$finca')";
                                                        $ejecutar = mysqli_query($link, $consulta) or die("Error insertando el código único");
                                                    }

                                                    $fecha = date('Y-m-d');
                                                    //Crear una entrada al cuarto frio de las fincas autonomas
                                                    $sql = "INSERT INTO tblcoldrom_fincas (`codigo_unico`,`item`, `finca`,`fecha`,`guia_m`, `guia_h`,`entrega`,`servicio`,`vuelo`,`aerolinea`,`tracking_asig`) VALUES ('$codigo','$item','$finca','$fecha','$Guia_madre','$Guia_hija','$entrega','$servicio','$vuelo','$aerolinea','$Tracking')";

                                                    $insertado = mysqli_query($link, $sql) or die("COLDROMMFINCAS ERROR " . mysqli_error($link));

                                                    //Actualizar la orden con los datos de la finca y caja
                                                    $sql11 = "Update tbldetalle_orden Set tracking='$Tracking', status = 'Shipped', farm='$finca', codigo='$codigo' where id_orden_detalle = '$id_order'"; // actualizar el eBing ='$eBing',
                                                    $actualizado = mysqli_query($link, $sql11);



                                                    //echo "Update tracking Set tracking='$Tracking', eBing ='$eBing' where id = '$id'";
                                                    if ($actualizado && $insertado) {
                                                        $modalcontent .= '<tr ALIGN=center VALIGN=center>';
                                                        $modalcontent .= '<td>' . $Ponumber . "</td>";
                                                        $modalcontent .= '<td>' . $CustNumber . "</td>";
                                                        $modalcontent .= '<td>' . $item . "</td>";
                                                        $modalcontent .= '<td>' . $Tracking . "</td>";
                                                        $modalcontent .= '<td>' . $Guia_madre . '</td>';
                                                        $modalcontent .= '<td>' . $Guia_hija . "</td>";
                                                        $modalcontent .= '</tr>';
                                                        $j++;
                                                    } else {
                                                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                                        $modalcontent .= "<font color='red'>Error cargando el tracking.</font>";
                                                        $modalcontent .= "</td></tr>";
                                                    }
                                                } else {
                                                    $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                                    $modalcontent .= "<font color='red'>La orden con el tracking: " . $tracking . " ya fue insertado anteriormente.</font>";
                                                    $modalcontent .= "</td></tr>";
                                                    $j++;
                                                }
                                            }
                                        }
                                    }
                                } else {

                                    //insertar nuevo tracking
                                    $query = "select tracking, Ponumber, Custnumber, cpitem from tbldetalle_orden where tracking = '$Tracking'";
                                    $row = mysqli_query($link, $query) or die("Error verificando el tracking");
                                    $ray = mysqli_num_rows($row); //cuento las filas devueltas
                                    //si existe este tracking
                                    if ($ray == 9999) {
                                        $sql = mysqli_fetch_array($row);
                                        $TRACKING = $sql['tracking'];
                                        $PONUMBER = $sql['Ponumber'];
                                        $CUSTNUMBER = $sql['Ponumber'];
                                        $ITEM = $sql['cpitem'];
                                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                        $modalcontent .= "<font color='red'>La orden con Ponumber " . $Ponumber . " , Custnumber " . $CustNumber . " ya tiene un tracking asignado que es: " . $TRACKING . " y usted intenta agregar este tracking: " . $Tracking . "<font>";
                                        $modalcontent .= "</td></tr>";
                                        $j++;
                                    } else {
                                        //Pregunto si el custnumber y ponumber e item existen, de ser asi asi lo actualizo
                                        $query = "select id_orden_detalle,tracking from tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item'";
                                        //echo "select id_detalleorden from  tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item'";

                                        $row = mysqli_query($link, $query) or die("Error verificando si la orden existe");
                                        $ray = mysqli_num_rows($row); //cuento las filas devueltas

                                        if ($ray == 0) {
                                            //si no obtubo ninguna fila es pq esa orden no ha sido introducida
                                            $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                            $modalcontent .= "<font color='red'>La orden no existe en el sistema. Por favor inserte la orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item . "</font>";
                                            $modalcontent .= "</td></tr>";
                                            $j++;
                                        } else {
                                            for ($i = 0; $i < $ray; $i++) {
                                                $sql = mysqli_fetch_array($row);
                                                $id_order = $sql['id_orden_detalle'];
                                                $tracking = $sql['tracking'];

                                                $Tracking = $array [$j]['5'];
                                                $Ponumber = $array [$j]['2'];
                                                $CustNumber = $array [$j]['3'];
                                                $item = $array [$j]['4'];

                                                if ($tracking == '' || $tracking != '') {
                                                    //si las ordenes subidas son consolidadas el mismo tracking subido es el codigo de la orden
                                                    if ($consolidado == 'Y') {
                                                        $codigo = $Tracking;
                                                    } else {
                                                        //Se genera el codigo unico de la caja
                                                        $codigo = generarCodigoUnico();
                                                        //Se inserta en la tabla de codigos
                                                        $consulta = "INSERT INTO tblcodigo (`codigo`,`finca`) VALUES ('$codigo','$finca')";
                                                        $ejecutar = mysqli_query($link, $consulta) or die("Error insertando el código único");
                                                    }

                                                    $fecha = date('Y-m-d');
                                                    //Crear una entrada al cuarto frio de las fincas autonomas
                                                    $sql = "INSERT INTO tblcoldrom_fincas (`codigo_unico`,`item`, `finca`,`fecha`,`guia_m`, `guia_h`,`entrega`,`servicio`,`vuelo`,`aerolinea`,`tracking_asig`) VALUES ('$codigo','$item','$finca','$fecha','$Guia_madre','$Guia_hija','$entrega','$servicio','$vuelo','$aerolinea','$Tracking')";
                                                    $insertado = mysqli_query($link, $sql) or die("COLDROMMFINCAS ERROR " . mysqli_error($link));

                                                    //Actualizar la orden con los datos de la finca y caja
                                                    $sql11 = "Update tbldetalle_orden Set tracking='$Tracking', status = 'Shipped', farm='$finca', codigo='$codigo' where id_orden_detalle = '$id_order'"; // actualizar el eBing ='$eBing',
                                                    $actualizado = mysqli_query($link, $sql11);


                                                    //echo "Update tracking Set tracking='$Tracking', eBing ='$eBing' where id = '$id'";
                                                    if ($actualizado && $insertado) {
                                                        $modalcontent .= '<tr ALIGN=center VALIGN=center>';
                                                        $modalcontent .= '<td>' . $Ponumber . "</td>";
                                                        $modalcontent .= '<td>' . $CustNumber . "</td>";
                                                        $modalcontent .= '<td>' . $item . "</td>";
                                                        $modalcontent .= '<td>' . $Tracking . "</td>";
                                                        $modalcontent .= '<td>' . $Guia_madre . '</td>';
                                                        $modalcontent .= '<td>' . $Guia_hija . "</td>";
                                                        $modalcontent .= '</tr>';
                                                        $j++;
                                                    } else {
                                                        $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                                        $modalcontent .= "<font color='red'>Error cargando el tracking.</font>";
                                                        $modalcontent .= "</td></tr>";
                                                    }
                                                } else {
                                                    $modalcontent .= "<tr ALIGN=center BGCOLOR='#CCCCCC'><td colspan=\"6\">";
                                                    $modalcontent .= "<font color='red'>La orden con el tracking: " . $tracking . " ya fue insertado anteriormente.</font>";
                                                    $modalcontent .= "</td></tr>";
                                                    $j++;
                                                }
                                            }//fin for
                                        }//else
                                    }//else
                                }
                            }
                        }

                        $_SESSION['showmodal'] = 'yes';
                        $_SESSION['modalcontent'] = $modalcontent;
                    } else {
                        $msg = "No se ha podido mover el archivo: " . $_FILES["archivo"]["name"][$i];
                        $box = "danger";
                    }
                } else {
                    $msg = "No se ha podido crear la carpeta: up/" . $user;
                    $box = "danger";
                }
            } else {
                $msg = $_FILES["archivo"]["name"][$i] . " - Formato no admitido";
                $box = "danger";
            }
        }
    } else {
        $msg = "No hay ningun arhivo para subir";
        $box = "danger";
    }
}

invaliformat:
invalidoperation:
$_SESSION['msg'] = $msg;
$_SESSION['box'] = $box;
header("Location: ../main.php?panel=cot.php");

////////////////////////////////////////////////////////////////////////////////FUNCIONES
function validar_guia($guia, $tipo) {
    if ($tipo == 'm') {
        if (strlen($guia) > 13) {
            return false;
        }
    }
    if ($tipo == 'h') {
        if (strlen($guia) < 8) {
            return false;
        }
    }

    if (substr($guia, 3, 1) != "-") {
        return false;
    }
    return true;
}

?>
