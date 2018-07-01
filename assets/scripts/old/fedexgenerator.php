<?php
//////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 1800);
require_once ('../php/PHPExcel.php');
include ('../php/PHPExcel/IOFactory.php');
require ("conn.php");
require ("islogged.php");
session_start();
ob_start();
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$sql = $_SESSION["query"];
$sqlrep = $_SESSION["xlsups"];
$user = $_SESSION["login"];
$rol = $_SESSION["rol"];
$pais = $_SESSION["pais"];
$ip = $_SERVER['REMOTE_ADDR'];
$nombre = 'order';
$query = mysqli_query($link, $sqlrep);
$query2 = mysqli_query($link, $sql);
//$col = mysqli_num_fields($query);

//echo "<br /> SQL";
//echo "<br /> ";
//echo $sql;
//echo "<br /> REP";
//echo "<br /> ";
//echo $sqlrep;
//die;
$directorio = opendir("fedexfiles/"); //ruta de archivos XML
$iii = 1;
while ($archivo = readdir($directorio)) {
    if (!is_dir($archivo)) {
        $iii++;
    }
}

$fp = fopen('fedexfiles/' . $iii . '.csv', 'w');

//Identificamos el PAIS
if ($pais == 'US') {

    //CREAMOS LOS ENCABEZADOS
    //SI ES ROL 3 TIENE ENCABEZADO SIN MENSAJE AL FINAL
    if ($rol == 3) {
        fputcsv($fp, array('Tracking', 'Company', 'eBinv', 'Orddate', 'Shipto', 'Shipto2', 'Address', 'Address2', 'City', 'State', 'Zip', 'Phone', 'Soldto', 'Soldto2', 'STPhone', 'Ponumber', 'CUSTnbr', 'SHIPDT', 'Deliver', 'SatDel', 'Quantity', 'Item', 'ProdDesc', 'Length', 'Width', 'Height', 'WeightKg', 'DclValue', 'Message', 'Service', 'PkgType', 'GenDesc', 'ShipCtry', 'Currency', 'Origin', 'UOM', 'TPComp', 'TPAttn', 'TPAdd1', 'TPCity', 'TPState', 'TPCtry', 'TPZip', 'TPPhone', 'TPAcct', 'Farm'));
    } else {
        fputcsv($fp, array('Tracking', 'Company', 'eBinv', 'Orddate', 'Shipto', 'Shipto2', 'Address', 'Address2', 'City', 'State', 'Zip', 'Phone', 'Soldto', 'Soldto2', 'STPhone', 'Ponumber', 'CUSTnbr', 'SHIPDT', 'Deliver', 'SatDel', 'Quantity', 'Item', 'ProdDesc', 'Length', 'Width', 'Height', 'WeightKg', 'DclValue', 'Message', 'Service', 'PkgType', 'GenDesc', 'ShipCtry', 'Currency', 'Origin', 'UOM', 'TPComp', 'TPAttn', 'TPAdd1', 'TPCity', 'TPState', 'TPCtry', 'TPZip', 'TPPhone', 'TPAcct', 'Farm', 'MSG'));
    }
    $i = 0; ////////////////////////////////////////////////////////////////////CONTADOR DE CRN 
    $iiii = 1; ////////////////////////////////////////////////////////////////////CONTADOR DE CRN 
    $ii = 1; ///////////////////////////////////////////////////////////////////CONTADOR DE MASTER
    //GENERAMOS EL CSV
    while ($rowr = mysqli_fetch_assoc($query)) {
        if ($i == 0){
            ////////////////////////////////////////////////////////////////////VALIDAMOS SI EL USUARIO ES COLOMBIA O ECUADOR
            if($_SESSION['finca'] == "MEDELLIN"){
               $shipping = "171673330";
               $billing = "502397885";
            } else {
               $shipping = "788516088";
               $billing = "379816711";
            }
            echo "0,\"20\"1,\"IPD\"4,\"".$rowr["nombre_compania"]."\"5,\"FARMS ECUADOR\"6,\"".$rowr["farm"]."\"7,\"Quito\"9,\"170109\"10,\"".$shipping."\"32,\"ALINA ALZUGARAY\"117,\"EC\"183,\"593-224-0163\"1150,\"ALINA ALZUGARAY\"11,\"FEDEX EXPRESS\"12,\"VIA FEDEX IPD\"13,\"6100 NW 36 STREET\"14,\"BUILDING 831\"15,\"MIAMI\"16,\"FL\"17,\"33115\"18,\"7862656564\"50,\"US\"20,\"".$billing."\"23,\"3\"24,\"" . date('Y') . date('m') . date('d') . "\"68,\"USD\"70,\"3\"71,\"".$billing."\"75,\"KGS\"1273,\"01\"1274,\"18\"541,\"YNNNNNNNN\"542,\"EBDM".$ii."\"1355,\"FEDEX\"1485,\"ALEX ALFONSO\"1486,\"EBLOOMS\"1487,\"2231 SW 82 PL\"1488,\"MIAMI FLORIDA 33155\"1489,\"MIAMI\"1490,\"FL\"1491,\"33155\"1492,\"1-855-532-5666\"1585,\"US\"1586,\"Y\"99,\"\"";
            echo "\r\n";
            echo "\r\n";
        }
        
        $rowr['cpmensaje'] = preg_replace("/\r|\n/", "", $rowr['cpmensaje']);
        //DEFINIMOS SI TIENE MENSAJE MUESTRA "Y" EN SU DEFECTO MUESTRA "N"
        if (ltrim(rtrim($rowr['mensaje2'])) == 'To-Blank Info   ::From- Blank Info   ::Blank .Info') {
            $rowr['mensaje2'] = "N";
        } else {
            $rowr['mensaje2'] = "Y";
        }
        
        $pricesplit = explode(".", $rowr["dclvalue"]);
        $pricesplit[1] = $pricesplit[1] . "0000";
        $unitprice = $pricesplit[0] . $pricesplit[1];
        
        $pesosplit = explode(".", $rowr["wheigthKg"]);
        if ($pesosplit[1] >= 5){
            $decimal = 1;
        } else {
            $decimal = 0;
        }
        $pesounit = $pesosplit[0] + $decimal;
        $pesounit = $pesounit*10;
        
        $widsplit = explode(".", $rowr["width"]);
        if ($widsplit[1] >= 5){
            $decimal = 1;
        } else {
            $decimal = 0;
        }
        $widunit = $widsplit[0] + $decimal;
        
        $heisplit = explode(".", $rowr["heigth"]);
        if ($heisplit[1] >= 5){
            $decimal = 1;
        } else {
            $decimal = 0;
        }
        $heiunit = $heisplit[0] + $decimal;
        
        $lensplit = explode(".", $rowr["length"]);
        if ($lensplit[1] >= 5){
            $decimal = 1;
        } else {
            $decimal = 0;
        }
        $lenunit = $lensplit[0] + $decimal;
        
        $orderzip = substr($rowr["cpzip_shipto"], 0, 5);
        
        $dir_count = strlen($rowr["direccion"]);
        if ($dir_count >= 35){
            $dir_1 = substr($rowr["direccion"],0,34);
            $dir_2 = substr($rowr["direccion"],35);
        } else {
            $dir_1 = $rowr["direccion"];
            $dir_2 = "";
        }
        
        $pesototal = $widunit + $heiunit + $lenunit;
        if ($rowr["cpitem"] == "10000" ||  $rowr["cpitem"] == "1014167" ||  $rowr["cpitem"] == "100067" ||  $rowr["cpitem"] == "100068" ||  $rowr["cpitem"] == "100063" ||  
                $rowr["cpitem"] == "100069" ||  $rowr["cpitem"] == "100072" ||  $rowr["cpitem"] == "100071" ||  $rowr["cpitem"] == "100070" ||  $rowr["cpitem"] == "983160" || 
                $rowr["cpitem"] == "983163" ||  $rowr["cpitem"] == "983205" ||  $rowr["cpitem"] == "986480" ||  $rowr["cpitem"] == "986521" ||  $rowr["cpitem"] == "12001" || 
                $rowr["cpitem"] == "10004" ||  $rowr["cpitem"] == "1021437" ||  $rowr["cpitem"] == "1021440" ||  $rowr["cpitem"] == "1021441" ||
                $rowr["cpitem"] == "1021442" ||  $rowr["cpitem"] == "100076" ||  $rowr["cpitem"] == "100043" ||  $rowr["cpitem"] == "100044" ||  $rowr["cpitem"] == "850340" ||
                $rowr["cpitem"] == "986524" ||  $rowr["cpitem"] == "100081" ||  $rowr["cpitem"] == "100082" ||  $rowr["cpitem"] == "100085" ||  $rowr["cpitem"] == "100090" || 
                $rowr["cpitem"] == "100091" ||  $rowr["cpitem"] == "1059858" ||  $rowr["cpitem"] == "100107" ||  $rowr["cpitem"] == "100111" ||  $rowr["cpitem"] == "100013" ||
                $rowr["cpitem"] == "100114" ||  $rowr["cpitem"] == "100116" ||  $rowr["cpitem"] == "100117" ||  $rowr["cpitem"] == "100119" ||  $rowr["cpitem"] == "100120" || 
                $rowr["cpitem"] == "100122" ||  $rowr["cpitem"] == "100123" ||  $rowr["cpitem"] == "100125" ||  $rowr["cpitem"] == "100129" ||  $rowr["cpitem"] == "100138" ||
                $rowr["cpitem"] == "100139" ||  $rowr["cpitem"] == "100137" ||  $rowr["cpitem"] == "100140" ||  $rowr["cpitem"] == "100142" ||  $rowr["cpitem"] == "100144" || 
                $rowr["cpitem"] == "100166" ||  $rowr["cpitem"] == "100113" ||  $rowr["cpitem"] == "100177" ||  $rowr["cpitem"] == "100178" ||  $rowr["cpitem"] == "1099388" || 
                $rowr["cpitem"] == "100189" ||  $rowr["cpitem"] == "1097363" ||  $rowr["cpitem"] == "10973631" ||  $rowr["cpitem"] == "10973633" || 
                $rowr["cpitem"] == "10973634" ||  $rowr["cpitem"] == "10973635" ||  $rowr["cpitem"] == "10973636" ||  $rowr["cpitem"] == "10973637" || 
                $rowr["cpitem"] == "10973638" ||  $rowr["cpitem"] == "10973639" ||  $rowr["cpitem"] == "884649" ||  $rowr["cpitem"] == "1114333" || 
                $rowr["cpitem"] == "1114379" ||  $rowr["cpitem"] == "1114377" ||  $rowr["cpitem"] == "1114381" ||  $rowr["cpitem"] == "1114375" ||  
                $rowr["cpitem"] == "1114383" ||  $rowr["cpitem"] == "1099421" ||  $rowr["cpitem"] == "100208" ||  $rowr["cpitem"] == "100212" ||  $rowr["cpitem"] == "100213" ||
                $rowr["cpitem"] == "100214" ||  $rowr["cpitem"] == "1117041" ||  $rowr["cpitem"] == "100218" ||  $rowr["cpitem"] == "1116613" ||  $rowr["cpitem"] == "1116826" ||
                $rowr["cpitem"] == "1116821" ||  $rowr["cpitem"] == "100219" ||  $rowr["cpitem"] == "1117045" ||  $rowr["cpitem"] == "100225" ||  $rowr["cpitem"] == "100227" || 
                $rowr["cpitem"] == "100231" ||  $rowr["cpitem"] == "1124883" ||  $rowr["cpitem"] == "100234" ||  $rowr["cpitem"] == "100238" ||  $rowr["cpitem"] == "100239" || 
                $rowr["cpitem"] == "1129733" ||  $rowr["cpitem"] == "1129731" ||  $rowr["cpitem"] == "1130391" ||  $rowr["cpitem"] == "1130394" ||  
                $rowr["cpitem"] == "1129724" ||  $rowr["cpitem"] == "1130386" ||  $rowr["cpitem"] == "1129729" ||  $rowr["cpitem"] == "1130388" ||
                $rowr["cpitem"] == "1130389" ||  $rowr["cpitem"] == "1124841" ||  $rowr["cpitem"] == "1124387" ||  $rowr["cpitem"] == "1124837" || 
                $rowr["cpitem"] == "100245" ||  $rowr["cpitem"] == "100246" ||  $rowr["cpitem"] == "100247" ||  $rowr["cpitem"] == "100248" ||  $rowr["cpitem"] == "100249" || 
                $rowr["cpitem"] == "100250" ||  $rowr["cpitem"] == "100255" ||  $rowr["cpitem"] == "1138036" ||  $rowr["cpitem"] == "100273" ||  $rowr["cpitem"] == "100274" || 
                $rowr["cpitem"] == "100276" ||  $rowr["cpitem"] == "100277" ||  $rowr["cpitem"] == "100278" ||  $rowr["cpitem"] == "100279" ||  $rowr["cpitem"] == "100280" ||  
                $rowr["cpitem"] == "100282" ||  $rowr["cpitem"] == "100283" ||  $rowr["cpitem"] == "100284" ||  $rowr["cpitem"] == "100285" ||  $rowr["cpitem"] == "100286" ||  
                $rowr["cpitem"] == "100287" ||  $rowr["cpitem"] == "100288" ||  $rowr["cpitem"] == "100289" ||  $rowr["cpitem"] == "1124825" ||  $rowr["cpitem"] == "100290" ||
                $rowr["cpitem"] == "100291" ||  $rowr["cpitem"] == "1145939" ||  $rowr["cpitem"] == "100292" ||  $rowr["cpitem"] == "100298" ||  $rowr["cpitem"] == "1149733" ||
                $rowr["cpitem"] == "1149728" ||  $rowr["cpitem"] == "100299" ||  $rowr["cpitem"] == "100300" ||  $rowr["cpitem"] == "100301" ||  $rowr["cpitem"] == "100302" || 
                $rowr["cpitem"] == "100303" ||  $rowr["cpitem"] == "100308" ||  $rowr["cpitem"] == "980022477" ||  $rowr["cpitem"] == "980022478" || 
                $rowr["cpitem"] == "980022486" ||  $rowr["cpitem"] == "980022480" ||  $rowr["cpitem"] == "980022483" ||  $rowr["cpitem"] == "980022481" || 
                $rowr["cpitem"] == "980021564" ||  $rowr["cpitem"] == "980021567" ||  $rowr["cpitem"] == "980021568" ||  $rowr["cpitem"] == "980021846" ||  
                $rowr["cpitem"] == "980021843" ||  $rowr["cpitem"] == "980021845" ||  $rowr["cpitem"] == "980021848" ||  $rowr["cpitem"] == "980021847" ||  
                $rowr["cpitem"] == "980021844" ||  $rowr["cpitem"] == "100313" ||  $rowr["cpitem"] == "100315" ||  $rowr["cpitem"] == "100317" ||  $rowr["cpitem"] == "100318" ||
                $rowr["cpitem"] == "100329" ||  $rowr["cpitem"] == "100331" ||  $rowr["cpitem"] == "100334" ||  $rowr["cpitem"] == "100336" ||  $rowr["cpitem"] == "100338" || 
                $rowr["cpitem"] == "100339" ||  $rowr["cpitem"] == "100341" ||  $rowr["cpitem"] == "100343" ||  $rowr["cpitem"] == "100346" ||  $rowr["cpitem"] == "100347" || 
                $rowr["cpitem"] == "100348" ||  $rowr["cpitem"] == "100352" ||  $rowr["cpitem"] == "100362" ||  $rowr["cpitem"] == "100366" ||  $rowr["cpitem"] == "100371" || 
                $rowr["cpitem"] == "100374" ||  $rowr["cpitem"] == "100378" ||  $rowr["cpitem"] == "100382" ||  $rowr["cpitem"] == "100387" ||  $rowr["cpitem"] == "100394" || 
                $rowr["cpitem"] == "100396" ||  $rowr["cpitem"] == "100397" ||  $rowr["cpitem"] == "100398" ||  $rowr["cpitem"] == "100401" ||  $rowr["cpitem"] == "100405" || 
                $rowr["cpitem"] == "100409" ||  $rowr["cpitem"] == "100419" ||  $rowr["cpitem"] == "100413" ||  $rowr["cpitem"] == "100502" ||  $rowr["cpitem"] == "100414" || 
                $rowr["cpitem"] == "100433" ||  $rowr["cpitem"] == "100435" ||  $rowr["cpitem"] == "100437" ||  $rowr["cpitem"] == "100441" ||  $rowr["cpitem"] == "100443" || 
                $rowr["cpitem"] == "100445" ||  $rowr["cpitem"] == "100447" ||  $rowr["cpitem"] == "100453" ||  $rowr["cpitem"] == "100455" ||  $rowr["cpitem"] == "100457" || 
                $rowr["cpitem"] == "100459" ||  $rowr["cpitem"] == "100412" ||  $rowr["cpitem"] == "100417" ||  $rowr["cpitem"] == "100418" ||  $rowr["cpitem"] == "100451" || 
                $rowr["cpitem"] == "100461" ||  $rowr["cpitem"] == "100463" ||  $rowr["cpitem"] == "100465" ||  $rowr["cpitem"] == "100469" ||  $rowr["cpitem"] == "100471" || 
                $rowr["cpitem"] == "100473" ||  $rowr["cpitem"] == "100475" ||  $rowr["cpitem"] == "100477" ||  $rowr["cpitem"] == "100479" ||  $rowr["cpitem"] == "100481" || 
                $rowr["cpitem"] == "100483" ||  $rowr["cpitem"] == "100485" ||  $rowr["cpitem"] == "100487" ||  $rowr["cpitem"] == "100489" ||  $rowr["cpitem"] == "100491" || 
                $rowr["cpitem"] == "100493" ||  $rowr["cpitem"] == "100495" ||  $rowr["cpitem"] == "100509" ||  $rowr["cpitem"] == "100508" ||  $rowr["cpitem"] == "100511" || 
                $rowr["cpitem"] == "100512" ||  $rowr["cpitem"] == "100513" ||  $rowr["cpitem"] == "100449" ||  $rowr["cpitem"] == "100490" ||  $rowr["cpitem"] == "100514" || 
                $rowr["cpitem"] == "100516" ||  $rowr["cpitem"] == "100517" ||  $rowr["cpitem"] == "100519" ||  $rowr["cpitem"] == "100523" ||  $rowr["cpitem"] == "100525" || 
                $rowr["cpitem"] == "100527" ||  $rowr["cpitem"] == "100531" ||  $rowr["cpitem"] == "100533" ||  $rowr["cpitem"] == "100535" ||  $rowr["cpitem"] == "100537" || 
                $rowr["cpitem"] == "100539" ||  $rowr["cpitem"] == "100541" ||  $rowr["cpitem"] == "100543" ||  $rowr["cpitem"] == "100545" ||  $rowr["cpitem"] == "100547" ||  
                $rowr["cpitem"] == "100549" ||  $rowr["cpitem"] == "100551" ||  $rowr["cpitem"] == "100554" ||  $rowr["cpitem"] == "100555" ||  $rowr["cpitem"] == "100557" || 
                $rowr["cpitem"] == "100558" ||  $rowr["cpitem"] == "100553" ||  $rowr["cpitem"] == "100561" ||  $rowr["cpitem"] == "100562" ||  $rowr["cpitem"] == "100563" || 
                $rowr["cpitem"] == "100565" ||  $rowr["cpitem"] == "100569" ||  $rowr["cpitem"] == "100570" ||  $rowr["cpitem"] == "100571" ||  $rowr["cpitem"] == "100573" ||  
                $rowr["cpitem"] == "100575" ||  $rowr["cpitem"] == "100577" ||  $rowr["cpitem"] == "100579" ||  $rowr["cpitem"] == "100581" ||  $rowr["cpitem"] == "100583" || 
                $rowr["cpitem"] == "100585" ||  $rowr["cpitem"] == "100587" ||  $rowr["cpitem"] == "100589" ||  $rowr["cpitem"] == "100591" ||  $rowr["cpitem"] == "100593" || 
                $rowr["cpitem"] == "100597" ||  $rowr["cpitem"] == "100598" ||  $rowr["cpitem"] == "100600" ||  $rowr["cpitem"] == "100602" ||  $rowr["cpitem"] == "100604" || 
                $rowr["cpitem"] == "100605" ||  $rowr["cpitem"] == "100607" ||  $rowr["cpitem"] == "100609" ||  $rowr["cpitem"] == "100610" ||  $rowr["cpitem"] == "100611" || 
                $rowr["cpitem"] == "200203" ||  $rowr["cpitem"] == "200204" ||  $rowr["cpitem"] == "200206" ||  $rowr["cpitem"] == "200207" ||  
                $rowr["cpitem"] == "200018" || $rowr["cpitem"] == "200201" || $rowr["cpitem"] == "200202" || $rowr["cpitem"] == "200205" || $rowr["cpitem"] == "100621" || 
                $rowr["cpitem"] == "1006671" || $rowr["cpitem"] == "980076599" || $rowr["cpitem"] == "980076597" || $rowr["cpitem"] == "980076598" || $rowr["cpitem"] == "980076595"
                || $rowr["cpitem"] == "980076596" || $rowr["cpitem"] == "100706" || $rowr["cpitem"] == "100706" || $rowr["cpitem"] == "1508" || $rowr["cpitem"] == "100707"){ 
            if ($rowr["cpitem"] == "200208"){
                
            } else {
                //SI ES ROL 1 MUESTRA TODA LA INFROMACION
            if ($rol == 1 || $rol == 2) {
                unset($rowr['estado_orden']);
                fputcsv($fp, $rowr);
                echo "0,\"20\"1,\"CRN".$iiii."\"11,\"". $rowr["shipto2"] ."\"12,\"". $rowr["shipto1"] ."\"13,\"". $dir_1 ."\"14,\"". $dir_2 ." ". $rowr["direccion2"] ."\"15,\"". $rowr["cpcuidad_shipto"] ."\"16,\"". $rowr["cpestado_shipto"] ."\"17,\"". $orderzip ."\"18,\"". $rowr["cptelefono_shipto"] ."\"21,\"". $pesounit ."\"25,\"". $rowr["cpitem"] ."\"38,\"". $rowr["Ponumber"] . "_" . $rowr["Custnumber"] . "\"50,\"US\"57,\"". $heiunit ."\"58,\"". $widunit ."\"59,\"". $lenunit ."\"68,\"USD\"75,\"KGS\"79,\"". $rowr["prod_descripcion"] ."\"80,\"EC\"81,\"\"82,\"1\"541,\"NNNYNNNNN\"542,\"EBDM".$ii."\"1030,\"". $unitprice ."\"1274,\"18\"99,\"\"         ";
                echo "\r\n";

                //SI ES ROL DISTINTO A 1 MUESTRA SOLO LOS QUE NO TIENEN TRACKING NI LAS CANCELADAS
            } else {
                if (ltrim(rtrim($rowr['estado_orden'])) == 'Active' && $rowr['tracking'] == '') {

                    //SI ES ROL 3 ELIMINA EL MENSAJE AL FINAL
                    if ($rol == 3) {
                        unset($rowr['mensaje2']);
                    }
                    unset($rowr['estado_orden']);
                    fputcsv($fp, $rowr);
                    echo "0,\"20\"1,\"CRN".$iiii."\"11,\"". $rowr["shipto2"] ."\"12,\"". $rowr["shipto1"] ."\"13,\"". $dir_1 ."\"14,\"". $dir_2 ." ". $rowr["direccion2"] ."\"15,\"". $rowr["cpcuidad_shipto"] ."\"16,\"". $rowr["cpestado_shipto"] ."\"17,\"". $orderzip ."\"18,\"". $rowr["cptelefono_shipto"] ."\"21,\"". $pesounit ."\"25,\"". $rowr["cpitem"] ."\"38,\"". $rowr["Ponumber"] . "_" . $rowr["Custnumber"] . "\"50,\"US\"57,\"". $heiunit ."\"58,\"". $widunit ."\"59,\"". $lenunit ."\"68,\"USD\"75,\"KGS\"79,\"". $rowr["prod_descripcion"] ."\"80,\"EC\"81,\"\"82,\"1\"541,\"NNNYNNNNN\"542,\"EBDM".$ii."\"1030,\"". $unitprice ."\"1274,\"18\"99,\"\"         ";
                    echo "\r\n";
                //echo print_r($rowr); echo "\r\n";
                }
            }
            $iiii++;
            }
        }
        $i++;
        if ($iiii >= 999){
            $ii++;
            $iiii = 0;//////////////////////////////////////////////////////////CONTAMOS 1000 ORDENES Y VAMOS AL SIGUIENTE MASTER DE 1000
            echo "\r\n";
            echo "\r\n";
        }
    }
//SI EL PAIS NO ES USA HACER:
} else {
    //CREAMOS LOS ENCABEZADOS
    //SI ES ROL 3 TIENE ENCABEZADO SIN MENSAJE AL FINAL
    if ($rol == 3) {
        fputcsv($fp, array('Tracking', 'Company', 'eBinv', 'Orddate', 'Shipto', 'Shipto2', 'Address', 'Address2', 'City', 'State', 'Zip', 'Phone', 'Soldto', 'Soldto2', 'STPhone', 'Ponumber', 'CUSTnbr', 'SHIPDT', 'Deliver', 'Quantity', 'Item', 'ProdDesc', 'Length', 'Width', 'Height', 'WeightKg', 'DclValue', 'Message', 'Service', 'PkgType', 'GenDesc', 'ShipCtry', 'Currency', 'Origin', 'UOM', 'TPComp', 'TPAttn', 'TPAdd1', 'TPCity', 'TPState', 'TPCtry', 'TPZip', 'TPPhone', 'TPAcct', 'NRIComp', 'NRIAtt', 'NRIAdd1', 'NRIAdd2', 'NRIAdd3', 'NRICity', 'NRIState', 'NRIZip', 'NRIPhone', 'NRIAccount', 'NRITaxid', 'Farm'));
    } else {
        fputcsv($fp, array('Tracking', 'Company', 'eBinv', 'Orddate', 'Shipto', 'Shipto2', 'Address', 'Address2', 'City', 'State', 'Zip', 'Phone', 'Soldto', 'Soldto2', 'STPhone', 'Ponumber', 'CUSTnbr', 'SHIPDT', 'Deliver', 'Quantity', 'Item', 'ProdDesc', 'Length', 'Width', 'Height', 'WeightKg', 'DclValue', 'Message', 'Service', 'PkgType', 'GenDesc', 'ShipCtry', 'Currency', 'Origin', 'UOM', 'TPComp', 'TPAttn', 'TPAdd1', 'TPCity', 'TPState', 'TPCtry', 'TPZip', 'TPPhone', 'TPAcct', 'NRIComp', 'NRIAtt', 'NRIAdd1', 'NRIAdd2', 'NRIAdd3', 'NRICity', 'NRIState', 'NRIZip', 'NRIPhone', 'NRIAccount', 'NRITaxid', 'Farm', 'MSG'));
    }

    //GENERAMOS EL CSV
    while ($rowr = mysqli_fetch_assoc($query)) {

        $rowr['cpmensaje'] = preg_replace("/\r|\n/", "", $rowr['cpmensaje']);
        //DEFINIMOS SI TIENE MENSAJE MUESTRA "Y" EN SU DEFECTO MUESTRA "N"
        if (ltrim(rtrim($rowr['mensaje2'])) == 'To-Blank Info   ::From- Blank Info   ::Blank .Info') {
            $rowr['mensaje2'] = "N";
        } else {
            $rowr['mensaje2'] = "Y";
        }

        //GUARDAMOS FARM PARA AGREGARLO AL FINAL DE ARRAY
        $farm = $rowr['farm'];

        //SI ES DISTINTO A ROL 3 GUARDAMOS EL MENSAJE PARA AGREGARLO AL FINAL DE ARRAY
        if ($rol !== 3) {
            $mensaje2 = $rowr['mensaje2'];
        }

        //DEFINIMOS LOS ELEMENTOS ADICIONALES QUE QUEREMOS AGREGAR Y QUITAR DE ARRAY
        unset($rowr['mensaje2']);
        unset($rowr['satdel']);
        unset($rowr['farm']);
        unset($rowr[45]);
        $rowr[45] = "E-Blooms Direct Inc.";
        $rowr[48] = "ALINA ALZUGARAY";
        $rowr[49] = "2231 S.W. 82 PLACE";
        $rowr[50] = "";
        $rowr[51] = "MIAMI FL 33155";
        $rowr[52] = "WINDSOR RR2";
        $rowr[53] = "ON";
        $rowr[54] = "N8N2M1";
        $rowr[55] = "305-905-0153";
        $rowr[56] = "A173A5";
        $rowr[57] = "816170971RM0001";
        array_push($rowr, $farm);

        //SI NO ES ROL 3 AGREGAMOS EL MENSAJE "Y" O "N" AL FINAL DEL REPORTE
        if ($rol !== 3) {
            array_push($rowr, $mensaje2);
        }

        //SI ES ROL 1 MUESTRA TODA LA INFROMACION
        if ($rol == 1) {
            unset($rowr['estado_orden']);
            fputcsv($fp, $rowr);
            //echo print_r($rowr); echo "\r\n";

            //SI ES ROL DISTINTO A 1 MUESTRA SOLO LOS QUE NO TIENEN TRACKING NI LAS CANCELADAS
        } else {
            if (ltrim(rtrim($rowr['estado_orden'])) == 'Active' && $rowr['tracking'] == '') {
                unset($rowr['estado_orden']);
                fputcsv($fp, $rowr);
            //echo print_r($rowr); echo "\r\n";
            }
        }
    }
}
fclose($fp);

//VALIDAMOS SI EL USUARIO MODIFICA ESTATUS DE DESCARGA
if ($rol > 1) {
    while ($rowr = mysqli_fetch_array($query2)) {
        $sqlup .= $rowr["id_detalleorden"] . ",";
    }

    //REMOVEMOS LA ULTIMA COMA PARA NO GENERAR ERROR DE SINTAXIS
    $sqlup = substr(trim($sqlup), 0, -1);
    //ACTUALIZAMOS ESTATUS A DESCARGADO
    $sqlupdate = "UPDATE tbldetalle_orden SET descargada='Downloaded', user='" . $user . "', status='Ready to ship' where id_detalleorden in (" . $sqlup . ") AND status = 'New'";
    mysqli_query($link, $sqlupdate)or die("Error updating...");

    // ALIMENTAMOS LA BITACORA
    $fecha = date('Y-m-d H:i:s');
    $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`) VALUES ('$user','Descargar Orden','$fecha','$ip')";
    $consultaHist = mysqli_query($link, $SqlHistorico) or die("Error actualizando la bitacora de usuarios");
}

/////////////////////////////////////////////////////////////////////////////////GUARDAMOS EL ARCHIVO .IN
$salida2 = ob_get_contents();
//ob_end_clean();

$f = fopen("fedexfiles/" . $iii . ".in", "w");
fwrite($f, $salida2);
fclose($f);

header("Content-Disposition: attachment; filename=\"" . date("Y-m-d H:i:s") . "_small.in\"");
header("Content-Type: application/force-download");
header("Content-Length: " . filesize("fedexfiles/" . $iii . ".in"));
header("Connection: close");



////////////////////////////////////////////////////////////////////////////////////////////////////////ENVIAMOS AL CONVERTIDOR A EXCEL
//$_SESSION['filename'] = "xlsups/" . $iii . ".csv";
//header("Location: csvtoxls.php");

////header("Content-Type: text/csv; charset=utf-8");
////header("Content-disposition: filename=" . $nombre . ".csv");
////print $csv;
////LECTOR DE CSV PARA PREPARARLO A XLS
//$objReader = PHPExcel_IOFactory::createReader('CSV');
////CARGAMOS EL CSV DENTRO DEL XLS
//$objPHPExcel = $objReader->load('file.csv');
//
////CONVERTIMOS EL VALOR DE PONUMBER A EXPLICITO PARA EVITAR QUE EXCEL CONVIERTA EL VALOR
//$porowcount = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
//$j = "2";
//for ($j = 2; $j <= $porowcount; $j++) {
//    $pvalor = $objPHPExcel->getActiveSheet()->getCell('P' . $j)->getValue();
//    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('P' . $j, $pvalor, PHPExcel_Cell_DataType::TYPE_STRING);
//}
//
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="Order.xlsx"');
//header('Cache-Control: max-age=0');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->save('php://output');
//
// **SOPORTE** VERIFICAMOS LAS SALIDAS DE NUESTRO SCRIPT
//print_r($sqlrep);
//print_r($sql);
//print_r($pais);
//print_r($sqlup);
//print_r($sqlupdate);
//print_r($SqlHistorico);
//die;
exit;
?>