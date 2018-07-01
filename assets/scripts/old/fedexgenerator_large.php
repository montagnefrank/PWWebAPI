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
    $ii = 1; ///////////////////////////////////////////////////////////////////CONTADOR DE MASTER
    $iiii = 1; ///////////////////////////////////////////////////////////////////CONTADOR DE MASTER
    //GENERAMOS EL CSV
    while ($rowr = mysqli_fetch_assoc($query)) {
        if ($i == 0) {
            if($_SESSION['finca'] == "MEDELLIN"){
               $shipping = "171673330";
               $billing = "502397885";
            } else {
               $shipping = "992001895";
               $billing = "502054406";
            }
            echo "0,\"20\"1,\"IPD\"4,\"" . $rowr["nombre_compania"] . "\"5,\"FARMS ECUADOR\"6,\"" . $rowr["farm"] . "\"7,\"Quito\"9,\"170109\"10,\"".$shipping."\"32,\"ALINA ALZUGARAY\"117,\"EC\"183,\"593-224-0163\"1150,\"ALINA ALZUGARAY\"11,\"FEDEX EXPRESS\"12,\"VIA FEDEX IPD\"13,\"6100 NW 36 STREET\"14,\"BUILDING 831\"15,\"MIAMI\"16,\"FL\"17,\"33115\"18,\"7862656564\"50,\"US\"20,\"".$billing."\"23,\"3\"24,\"" . date('Y') . date('m') . date('d') . "\"68,\"USD\"70,\"3\"71,\"".$billing."\"75,\"KGS\"1273,\"01\"1274,\"18\"541,\"YNNNNNNNN\"542,\"EBDM" . $ii . "\"1355,\"FEDEX\"1485,\"ALEX ALFONSO\"1486,\"EBLOOMS\"1487,\"2231 SW 82 PL\"1488,\"MIAMI FLORIDA 33155\"1489,\"MIAMI\"1490,\"FL\"1491,\"33155\"1492,\"1-855-532-5666\"1585,\"US\"1586,\"Y\"99,\"\"";
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
        if ($pesosplit[1] >= 5) {
            $decimal = 1;
        } else {
            $decimal = 0;
        }
        $pesounit = $pesosplit[0] + $decimal;
        $pesounit = $pesounit * 10;

        $widsplit = explode(".", $rowr["width"]);
        if ($widsplit[1] >= 5) {
            $decimal = 1;
        } else {
            $decimal = 0;
        }
        $widunit = $widsplit[0] + $decimal;

        $heisplit = explode(".", $rowr["heigth"]);
        if ($heisplit[1] >= 5) {
            $decimal = 1;
        } else {
            $decimal = 0;
        }
        $heiunit = $heisplit[0] + $decimal;

        $lensplit = explode(".", $rowr["length"]);
        if ($lensplit[1] >= 5) {
            $decimal = 1;
        } else {
            $decimal = 0;
        }
        $lenunit = $lensplit[0] + $decimal;

        $orderzip = substr($rowr["cpzip_shipto"], 0, 5);

        $dir_count = strlen($rowr["direccion"]);
        if ($dir_count >= 35) {
            $dir_1 = substr($rowr["direccion"], 0, 34);
            $dir_2 = substr($rowr["direccion"], 35);
        } else {
            $dir_1 = $rowr["direccion"];
            $dir_2 = "";
        }

        if($rowr["cpitem"] == "200208"){
            $pesototal = 102;
        } else {
            $pesototal = $widunit + $heiunit + $lenunit;
        }
        if ($rowr["cpitem"] == "407645" ||  $rowr["cpitem"] == "100057" ||  $rowr["cpitem"] == "100064" ||  $rowr["cpitem"] == "100046" ||  $rowr["cpitem"] == "50005" ||  $rowr["cpitem"] == "968886" ||  $rowr["cpitem"] == "1022682" ||  $rowr["cpitem"] == "100052" ||  $rowr["cpitem"] == "10050" ||  $rowr["cpitem"] == "968887" ||  $rowr["cpitem"] == "968888" ||  $rowr["cpitem"] == "1014144" ||  $rowr["cpitem"] == "968890" ||  $rowr["cpitem"] == "100047" ||  $rowr["cpitem"] == "1014139" ||  $rowr["cpitem"] == "968891" ||  $rowr["cpitem"] == "968892" ||  $rowr["cpitem"] == "1014127" ||  $rowr["cpitem"] == "968893" ||  $rowr["cpitem"] == "100075" ||  $rowr["cpitem"] == "968894" ||  $rowr["cpitem"] == "1040068" ||  $rowr["cpitem"] == "968895" ||  $rowr["cpitem"] == "968897" ||  $rowr["cpitem"] == "968904" ||  $rowr["cpitem"] == "1022503" ||  $rowr["cpitem"] == "968907" ||  $rowr["cpitem"] == "2004" ||  $rowr["cpitem"] == "1030808" ||  $rowr["cpitem"] == "2006" ||  $rowr["cpitem"] == "986522" ||  $rowr["cpitem"] == "986523" ||  $rowr["cpitem"] == "2001" ||  $rowr["cpitem"] == "986525" ||  $rowr["cpitem"] == "1030820" ||  $rowr["cpitem"] == "986526" ||  $rowr["cpitem"] == "2003" ||  $rowr["cpitem"] == "987454" ||  $rowr["cpitem"] == "2005" ||  $rowr["cpitem"] == "987455" ||  $rowr["cpitem"] == "2007" ||  $rowr["cpitem"] == "20000" ||  $rowr["cpitem"] == "987456" ||  $rowr["cpitem"] == "987457" ||  $rowr["cpitem"] == "1040053" ||  $rowr["cpitem"] == "MD00002" ||  $rowr["cpitem"] == "1030824" ||  $rowr["cpitem"] == "1040048" ||  $rowr["cpitem"] == "MD00003" ||  $rowr["cpitem"] == "407661" ||  $rowr["cpitem"] == "1014159" ||  $rowr["cpitem"] == "121972" ||  $rowr["cpitem"] == "1030830" ||  $rowr["cpitem"] == "100065" ||  $rowr["cpitem"] == "1014169" ||  $rowr["cpitem"] == "1014163" ||  $rowr["cpitem"] == "1014157" ||  $rowr["cpitem"] == "407658" ||  $rowr["cpitem"] == "1013549" ||  $rowr["cpitem"] == "1014150" ||  $rowr["cpitem"] == "1014177" ||  $rowr["cpitem"] == "1023188" ||  $rowr["cpitem"] == "1030816" ||  $rowr["cpitem"] == "1040062" ||  $rowr["cpitem"] == "1014147" ||  $rowr["cpitem"] == "118558" ||  $rowr["cpitem"] == "167426" ||  $rowr["cpitem"] == "10002" ||  $rowr["cpitem"] == "10003" ||  $rowr["cpitem"] == "10005" ||  $rowr["cpitem"] == "407662" ||  $rowr["cpitem"] == "363245901" ||  $rowr["cpitem"] == "1014130" ||  $rowr["cpitem"] == "1024633" ||  $rowr["cpitem"] == "1021341" ||  $rowr["cpitem"] == "1021346" ||  $rowr["cpitem"] == "40001" ||  $rowr["cpitem"] == "23001" ||  $rowr["cpitem"] == "60001" ||  $rowr["cpitem"] == "100040" ||  $rowr["cpitem"] == "100045" ||  $rowr["cpitem"] == "40003" ||  $rowr["cpitem"] == "100042" ||  $rowr["cpitem"] == "100077" ||  $rowr["cpitem"] == "100050" ||  $rowr["cpitem"] == "10006" ||  $rowr["cpitem"] == "50004" ||  $rowr["cpitem"] == "100066" ||  $rowr["cpitem"] == "100074" ||  $rowr["cpitem"] == "100073" ||  $rowr["cpitem"] == "40002" ||  $rowr["cpitem"] == "100058" ||  $rowr["cpitem"] == "10100" ||  $rowr["cpitem"] == "100061" ||  $rowr["cpitem"] == "100060" ||  $rowr["cpitem"] == "54623" ||  $rowr["cpitem"] == "100048" ||  $rowr["cpitem"] == "100056" ||  $rowr["cpitem"] == "100041" ||  $rowr["cpitem"] == "100062" ||  $rowr["cpitem"] == "50040" ||  $rowr["cpitem"] == "100054" ||  $rowr["cpitem"] == "100053" ||  $rowr["cpitem"] == "100049" ||  $rowr["cpitem"] == "1030837" ||  $rowr["cpitem"] == "1023191" ||  $rowr["cpitem"] == "167394" ||  $rowr["cpitem"] == "841775" ||  $rowr["cpitem"] == "841760" ||  $rowr["cpitem"] == "1023189" ||  $rowr["cpitem"] == "1014173" ||  $rowr["cpitem"] == "1023187" ||  $rowr["cpitem"] == "1039956" ||  $rowr["cpitem"] == "425321" ||  $rowr["cpitem"] == "425320" ||  $rowr["cpitem"] == "425318" ||  $rowr["cpitem"] == "438772" ||  $rowr["cpitem"] == "438773" ||  $rowr["cpitem"] == "407628" ||  $rowr["cpitem"] == "438774" ||  $rowr["cpitem"] == "438775" ||  $rowr["cpitem"] == "970895" ||  $rowr["cpitem"] == "100051" ||  $rowr["cpitem"] == "100059" ||  $rowr["cpitem"] == "100078" ||  $rowr["cpitem"] == "100079" ||  $rowr["cpitem"] == "100161" ||  $rowr["cpitem"] == "10101" ||  $rowr["cpitem"] == "1014154" ||  $rowr["cpitem"] == "1024611" ||  $rowr["cpitem"] == "1030833" ||  $rowr["cpitem"] == "118462" ||  $rowr["cpitem"] == "167412" ||  $rowr["cpitem"] == "167418" ||  $rowr["cpitem"] == "40005" ||  $rowr["cpitem"] == "438774A" ||  $rowr["cpitem"] == "TD00001" ||  $rowr["cpitem"] == "RP00001" ||  $rowr["cpitem"] == "DY00001" ||  $rowr["cpitem"] == "WN00001" ||  $rowr["cpitem"] == "100080" ||  $rowr["cpitem"] == "101010" ||  $rowr["cpitem"] == "101011" ||  $rowr["cpitem"] == "101012" ||  $rowr["cpitem"] == "101013" ||  $rowr["cpitem"] == "100083" ||  $rowr["cpitem"] == "100084" ||  $rowr["cpitem"] == "100086" ||  $rowr["cpitem"] == "8900605" ||  $rowr["cpitem"] == "8900604" ||  $rowr["cpitem"] == "100087" ||  $rowr["cpitem"] == "100088" ||  $rowr["cpitem"] == "100089" ||  $rowr["cpitem"] == "100092" ||  $rowr["cpitem"] == "1059856" ||  $rowr["cpitem"] == "100093" ||  $rowr["cpitem"] == "100094" ||  $rowr["cpitem"] == "100095" ||  $rowr["cpitem"] == "100096" ||  $rowr["cpitem"] == "100097" ||  $rowr["cpitem"] == "100098" ||  $rowr["cpitem"] == "100099" ||  $rowr["cpitem"] == "100100" ||  $rowr["cpitem"] == "100101" ||  $rowr["cpitem"] == "100102" ||  $rowr["cpitem"] == "100103" ||  $rowr["cpitem"] == "100104" ||  $rowr["cpitem"] == "60002" ||  $rowr["cpitem"] == "1059874" ||  $rowr["cpitem"] == "1059897" ||  $rowr["cpitem"] == "1053781" ||  $rowr["cpitem"] == "1054226" ||  $rowr["cpitem"] == "1054228" ||  $rowr["cpitem"] == "1054234" ||  $rowr["cpitem"] == "1054235" ||  $rowr["cpitem"] == "100105" ||  $rowr["cpitem"] == "100106" ||  $rowr["cpitem"] == "271492" ||  $rowr["cpitem"] == "271375" ||  $rowr["cpitem"] == "271457" ||  $rowr["cpitem"] == "271401" ||  $rowr["cpitem"] == "271564" ||  $rowr["cpitem"] == "271439" ||  $rowr["cpitem"] == "271410" ||  $rowr["cpitem"] == "271423" ||  $rowr["cpitem"] == "271430" ||  $rowr["cpitem"] == "271500" ||  $rowr["cpitem"] == "271472" ||  $rowr["cpitem"] == "271479" ||  $rowr["cpitem"] == "271529" ||  $rowr["cpitem"] == "308398" ||  $rowr["cpitem"] == "308391" ||  $rowr["cpitem"] == "100108" ||  $rowr["cpitem"] == "100109" ||  $rowr["cpitem"] == "100110" ||  $rowr["cpitem"] == "100112" ||  $rowr["cpitem"] == "100115" ||  $rowr["cpitem"] == "100118" ||  $rowr["cpitem"] == "100121" ||  $rowr["cpitem"] == "100124" ||  $rowr["cpitem"] == "100126" ||  $rowr["cpitem"] == "100127" ||  $rowr["cpitem"] == "100128" ||  $rowr["cpitem"] == "100130" ||  $rowr["cpitem"] == "100131" ||  $rowr["cpitem"] == "100132" ||  $rowr["cpitem"] == "100133" ||  $rowr["cpitem"] == "100134" ||  $rowr["cpitem"] == "100135" ||  $rowr["cpitem"] == "100136" ||  $rowr["cpitem"] == "1070419" ||  $rowr["cpitem"] == "1070502" ||  $rowr["cpitem"] == "1070501" ||  $rowr["cpitem"] == "1070498" ||  $rowr["cpitem"] == "1070496" ||  $rowr["cpitem"] == "1070497" ||  $rowr["cpitem"] == "1070499" ||  $rowr["cpitem"] == "100141" ||  $rowr["cpitem"] == "100143" ||  $rowr["cpitem"] == "100145" ||  $rowr["cpitem"] == "100146" ||  $rowr["cpitem"] == "100147" ||  $rowr["cpitem"] == "100148" ||  $rowr["cpitem"] == "100149" ||  $rowr["cpitem"] == "100150" ||  $rowr["cpitem"] == "100151" ||  $rowr["cpitem"] == "100152" ||  $rowr["cpitem"] == "100153" ||  $rowr["cpitem"] == "100154" ||  $rowr["cpitem"] == "100155" ||  $rowr["cpitem"] == "100156" ||  $rowr["cpitem"] == "100157" ||  $rowr["cpitem"] == "100158" ||  $rowr["cpitem"] == "100159" ||  $rowr["cpitem"] == "100160" ||  $rowr["cpitem"] == "100162" ||  $rowr["cpitem"] == "100163" ||  $rowr["cpitem"] == "100164" ||  $rowr["cpitem"] == "100165" ||  $rowr["cpitem"] == "100167" ||  $rowr["cpitem"] == "100168" ||  $rowr["cpitem"] == "100169" ||  $rowr["cpitem"] == "100170" ||  $rowr["cpitem"] == "100171" ||  $rowr["cpitem"] == "100172" ||  $rowr["cpitem"] == "100173" ||  $rowr["cpitem"] == "100174" ||  $rowr["cpitem"] == "100175" ||  $rowr["cpitem"] == "100176" ||  $rowr["cpitem"] == "1081270" ||  $rowr["cpitem"] == "1081259" ||  $rowr["cpitem"] == "1081293" ||  $rowr["cpitem"] == "1081291" ||  $rowr["cpitem"] == "100179" ||  $rowr["cpitem"] == "100180" ||  $rowr["cpitem"] == "100181" ||  $rowr["cpitem"] == "100182" ||  $rowr["cpitem"] == "100183" ||  $rowr["cpitem"] == "100184" ||  $rowr["cpitem"] == "100185" ||  $rowr["cpitem"] == "100186" ||  $rowr["cpitem"] == "100187" ||  $rowr["cpitem"] == "100188" ||  $rowr["cpitem"] == "100190" ||  $rowr["cpitem"] == "100191" ||  $rowr["cpitem"] == "100192" ||  $rowr["cpitem"] == "100193" ||  $rowr["cpitem"] == "100194" ||  $rowr["cpitem"] == "10973632" ||  $rowr["cpitem"] == "100195" ||  $rowr["cpitem"] == "100196" ||  $rowr["cpitem"] == "100197" ||  $rowr["cpitem"] == "100198" ||  $rowr["cpitem"] == "100199" ||  $rowr["cpitem"] == "100200" ||  $rowr["cpitem"] == "100201" ||  $rowr["cpitem"] == "100202" ||  $rowr["cpitem"] == "100203" ||  $rowr["cpitem"] == "100204" ||  $rowr["cpitem"] == "100205" ||  $rowr["cpitem"] == "100206" ||  $rowr["cpitem"] == "100207" ||  $rowr["cpitem"] == "100209" ||  $rowr["cpitem"] == "100210" ||  $rowr["cpitem"] == "100211" ||  $rowr["cpitem"] == "48804" ||  $rowr["cpitem"] == "100215" ||  $rowr["cpitem"] == "100216" ||  $rowr["cpitem"] == "100217" ||  $rowr["cpitem"] == "100220" ||  $rowr["cpitem"] == "100221" ||  $rowr["cpitem"] == "100222" ||  $rowr["cpitem"] == "100223" ||  $rowr["cpitem"] == "100224" ||  $rowr["cpitem"] == "100226" ||  $rowr["cpitem"] == "100228" ||  $rowr["cpitem"] == "100229" ||  $rowr["cpitem"] == "100230" ||  $rowr["cpitem"] == "100232" ||  $rowr["cpitem"] == "100233" ||  $rowr["cpitem"] == "100235" ||  $rowr["cpitem"] == "100236" ||  $rowr["cpitem"] == "794961" ||  $rowr["cpitem"] == "794980" ||  $rowr["cpitem"] == "794992" ||  $rowr["cpitem"] == "100237" ||  $rowr["cpitem"] == "100240" ||  $rowr["cpitem"] == "100241" ||  $rowr["cpitem"] == "100242" ||  $rowr["cpitem"] == "100243" ||  $rowr["cpitem"] == "100244" ||  $rowr["cpitem"] == "100251" ||  $rowr["cpitem"] == "100252" ||  $rowr["cpitem"] == "100253" ||  $rowr["cpitem"] == "100254" ||  $rowr["cpitem"] == "100256" ||  $rowr["cpitem"] == "100257" ||  $rowr["cpitem"] == "100258" ||  $rowr["cpitem"] == "100259" ||  $rowr["cpitem"] == "100260" ||  $rowr["cpitem"] == "100261" ||  $rowr["cpitem"] == "100262" ||  $rowr["cpitem"] == "100263" ||  $rowr["cpitem"] == "100264" ||  $rowr["cpitem"] == "100265" ||  $rowr["cpitem"] == "100266" ||  $rowr["cpitem"] == "100267" ||  $rowr["cpitem"] == "100268" ||  $rowr["cpitem"] == "100269" ||  $rowr["cpitem"] == "100270" ||  $rowr["cpitem"] == "100271" ||  $rowr["cpitem"] == "100272" ||  $rowr["cpitem"] == "100275" ||  $rowr["cpitem"] == "100281" ||  $rowr["cpitem"] == "100293" ||  $rowr["cpitem"] == "100294" ||  $rowr["cpitem"] == "100295" ||  $rowr["cpitem"] == "100296" ||  $rowr["cpitem"] == "100297" ||  $rowr["cpitem"] == "100304" ||  $rowr["cpitem"] == "100305" ||  $rowr["cpitem"] == "100306" ||  $rowr["cpitem"] == "100307" ||  $rowr["cpitem"] == "100309" ||  $rowr["cpitem"] == "100310" ||  $rowr["cpitem"] == "980022479" ||  $rowr["cpitem"] == "980022485" ||  $rowr["cpitem"] == "980022482" ||  $rowr["cpitem"] == "980022476" ||  $rowr["cpitem"] == "980022484" ||  $rowr["cpitem"] == "980021565" ||  $rowr["cpitem"] == "980021566" ||  $rowr["cpitem"] == "980021569" ||  $rowr["cpitem"] == "100311" ||  $rowr["cpitem"] == "100312" ||  $rowr["cpitem"] == "100314" ||  $rowr["cpitem"] == "100316" ||  $rowr["cpitem"] == "100319" ||  $rowr["cpitem"] == "100320" ||  $rowr["cpitem"] == "100321" ||  $rowr["cpitem"] == "100322" ||  $rowr["cpitem"] == "100323" ||  $rowr["cpitem"] == "100324" ||  $rowr["cpitem"] == "100325" ||  $rowr["cpitem"] == "100326" ||  $rowr["cpitem"] == "100327" ||  $rowr["cpitem"] == "100328" ||  $rowr["cpitem"] == "100330" ||  $rowr["cpitem"] == "100332" ||  $rowr["cpitem"] == "100333" ||  $rowr["cpitem"] == "100335" ||  $rowr["cpitem"] == "100337" ||  $rowr["cpitem"] == "100340" ||  $rowr["cpitem"] == "100342" ||  $rowr["cpitem"] == "100344" ||  $rowr["cpitem"] == "100345" ||  $rowr["cpitem"] == "100349" ||  $rowr["cpitem"] == "100350" ||  $rowr["cpitem"] == "100351" ||  $rowr["cpitem"] == "100353" ||  $rowr["cpitem"] == "100354" ||  $rowr["cpitem"] == "100355" ||  $rowr["cpitem"] == "100356" ||  $rowr["cpitem"] == "100357" ||  $rowr["cpitem"] == "100358" ||  $rowr["cpitem"] == "100359" ||  $rowr["cpitem"] == "100360" ||  $rowr["cpitem"] == "100361" ||  $rowr["cpitem"] == "100363" ||  $rowr["cpitem"] == "100364" ||  $rowr["cpitem"] == "100365" ||  $rowr["cpitem"] == "100367" ||  $rowr["cpitem"] == "100368" ||  $rowr["cpitem"] == "100369" ||  $rowr["cpitem"] == "100370" ||  $rowr["cpitem"] == "100372" ||  $rowr["cpitem"] == "100373" ||  $rowr["cpitem"] == "100375" ||  $rowr["cpitem"] == "100376" ||  $rowr["cpitem"] == "100377" ||  $rowr["cpitem"] == "100380" ||  $rowr["cpitem"] == "100381" ||  $rowr["cpitem"] == "100383" ||  $rowr["cpitem"] == "100379" ||  $rowr["cpitem"] == "100384" ||  $rowr["cpitem"] == "100385" ||  $rowr["cpitem"] == "100386" ||  $rowr["cpitem"] == "100388" ||  $rowr["cpitem"] == "100389" ||  $rowr["cpitem"] == "100390" ||  $rowr["cpitem"] == "100391" ||  $rowr["cpitem"] == "100392" ||  $rowr["cpitem"] == "100393" ||  $rowr["cpitem"] == "100395" ||  $rowr["cpitem"] == "100399" ||  $rowr["cpitem"] == "100400" ||  $rowr["cpitem"] == "100402" ||  $rowr["cpitem"] == "100403" ||  $rowr["cpitem"] == "100404" ||  $rowr["cpitem"] == "100406" ||  $rowr["cpitem"] == "100407" ||  $rowr["cpitem"] == "100408" ||  $rowr["cpitem"] == "100410" ||  $rowr["cpitem"] == "100411" ||  $rowr["cpitem"] == "100420" ||  $rowr["cpitem"] == "100426" ||  $rowr["cpitem"] == "100427" ||  $rowr["cpitem"] == "100429" ||  $rowr["cpitem"] == "100497" ||  $rowr["cpitem"] == "100498" ||  $rowr["cpitem"] == "100499" ||  $rowr["cpitem"] == "100501" ||  $rowr["cpitem"] == "100500" ||  $rowr["cpitem"] == "100428" ||  $rowr["cpitem"] == "100434" ||  $rowr["cpitem"] == "100436" ||  $rowr["cpitem"] == "100438" ||  $rowr["cpitem"] == "100432" ||  $rowr["cpitem"] == "100431" ||  $rowr["cpitem"] == "100439" ||  $rowr["cpitem"] == "100440" ||  $rowr["cpitem"] == "100442" ||  $rowr["cpitem"] == "100444" ||  $rowr["cpitem"] == "100446" ||  $rowr["cpitem"] == "100448" ||  $rowr["cpitem"] == "100454" ||  $rowr["cpitem"] == "100456" ||  $rowr["cpitem"] == "100458" ||  $rowr["cpitem"] == "100460" ||  $rowr["cpitem"] == "100503" ||  $rowr["cpitem"] == "100504" ||  $rowr["cpitem"] == "100415" ||  $rowr["cpitem"] == "100416" ||  $rowr["cpitem"] == "100421" ||  $rowr["cpitem"] == "100505" ||  $rowr["cpitem"] == "100506" ||  $rowr["cpitem"] == "100422" ||  $rowr["cpitem"] == "100423" ||  $rowr["cpitem"] == "100424" ||  $rowr["cpitem"] == "100425" ||  $rowr["cpitem"] == "100430" ||  $rowr["cpitem"] == "100450" ||  $rowr["cpitem"] == "100452" ||  $rowr["cpitem"] == "100462" ||  $rowr["cpitem"] == "100464" ||  $rowr["cpitem"] == "100466" ||  $rowr["cpitem"] == "100468" ||  $rowr["cpitem"] == "100470" ||  $rowr["cpitem"] == "100472" ||  $rowr["cpitem"] == "100474" ||  $rowr["cpitem"] == "100476" ||  $rowr["cpitem"] == "100478" ||  $rowr["cpitem"] == "100480" ||  $rowr["cpitem"] == "100482" ||  $rowr["cpitem"] == "100484" ||  $rowr["cpitem"] == "100507" ||  $rowr["cpitem"] == "100486" ||  $rowr["cpitem"] == "100488" ||  $rowr["cpitem"] == "100492" ||  $rowr["cpitem"] == "100494" ||  $rowr["cpitem"] == "100496" ||  $rowr["cpitem"] == "100510" ||  $rowr["cpitem"] == "100467" ||  $rowr["cpitem"] == "100515" ||  $rowr["cpitem"] == "100518" ||  $rowr["cpitem"] == "100520" ||  $rowr["cpitem"] == "100521" ||  $rowr["cpitem"] == "100522" ||  $rowr["cpitem"] == "100524" ||  $rowr["cpitem"] == "100526" ||  $rowr["cpitem"] == "100528" ||  $rowr["cpitem"] == "100529" ||  $rowr["cpitem"] == "100530" ||  $rowr["cpitem"] == "100532" ||  $rowr["cpitem"] == "100534" ||  $rowr["cpitem"] == "100536" ||  $rowr["cpitem"] == "100538" ||  $rowr["cpitem"] == "100540" ||  $rowr["cpitem"] == "100542" ||  $rowr["cpitem"] == "100544" ||  $rowr["cpitem"] == "100546" ||  $rowr["cpitem"] == "100548" ||  $rowr["cpitem"] == "100550" ||  $rowr["cpitem"] == "100552" ||  $rowr["cpitem"] == "100556" ||  $rowr["cpitem"] == "100559" ||  $rowr["cpitem"] == "100560" ||  $rowr["cpitem"] == "100564" ||  $rowr["cpitem"] == "100566" ||  $rowr["cpitem"] == "100567" ||  $rowr["cpitem"] == "100568" ||  $rowr["cpitem"] == "100572" ||  $rowr["cpitem"] == "100574" ||  $rowr["cpitem"] == "100576" ||  $rowr["cpitem"] == "100578" ||  $rowr["cpitem"] == "100580" ||  $rowr["cpitem"] == "100582" ||  $rowr["cpitem"] == "100584" ||  $rowr["cpitem"] == "100586" ||  $rowr["cpitem"] == "100588" ||  $rowr["cpitem"] == "100590" ||  $rowr["cpitem"] == "100592" ||  $rowr["cpitem"] == "100594" ||  $rowr["cpitem"] == "100595" ||  $rowr["cpitem"] == "100596" ||  $rowr["cpitem"] == "100599" ||  $rowr["cpitem"] == "100601" ||  $rowr["cpitem"] == "100603" ||  $rowr["cpitem"] == "100606" ||  $rowr["cpitem"] == "100608" ||  $rowr["cpitem"] == "100612" ||  $rowr["cpitem"] == "100613" ||  $rowr["cpitem"] == "100614" ||  $rowr["cpitem"] == "200258" || $rowr["cpitem"] == "200208" ||  $rowr["cpitem"] == "200025" ||  $rowr["cpitem"] == "200023" ||  $rowr["cpitem"] == "200211" ||  $rowr["cpitem"] == "200236" ||  $rowr["cpitem"] == "200029" ||  $rowr["cpitem"] == "200209" ||  $rowr["cpitem"] == "200210" ||  $rowr["cpitem"] == "200212" ||  $rowr["cpitem"] == "200213" ||  $rowr["cpitem"] == "200214" ||  $rowr["cpitem"] == "200215" ||  $rowr["cpitem"] == "200216" ||  $rowr["cpitem"] == "200217" ||  $rowr["cpitem"] == "200218" ||  $rowr["cpitem"] == "200219" ||  $rowr["cpitem"] == "200028" ||  $rowr["cpitem"] == "10001" ||  $rowr["cpitem"] == "100704"  ||  $rowr["cpitem"] == "100705"          ) {
            
            //SI ES ROL 1 MUESTRA TODA LA INFROMACION
            if ($rol == 1 || $rol == 2) {
                unset($rowr['estado_orden']);
                fputcsv($fp, $rowr);
                echo "0,\"20\"1,\"CRN" . $i . "\"11,\"" . $rowr["shipto2"] . "\"12,\"" . $rowr["shipto1"] . "\"13,\"" . $dir_1 . "\"14,\"" . $dir_2 . " " . $rowr["direccion2"] . "\"15,\"" . $rowr["cpcuidad_shipto"] . "\"16,\"" . $rowr["cpestado_shipto"] . "\"17,\"" . $orderzip . "\"18,\"" . $rowr["cptelefono_shipto"] . "\"21,\"" . $pesounit . "\"25,\"" . $rowr["cpitem"] . "\"38,\"" . $rowr["Ponumber"] . "_" . $rowr["Custnumber"] . "\"50,\"US\"57,\"" . $heiunit . "\"58,\"" . $widunit . "\"59,\"" . $lenunit . "\"68,\"USD\"75,\"KGS\"79,\"" . $rowr["prod_descripcion"] . "\"80,\"EC\"81,\"\"82,\"1\"541,\"NNNYNNNNN\"542,\"EBDM" . $ii . "\"1030,\"" . $unitprice . "\"1274,\"18\"99,\"\"         ";
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
                    echo "0,\"20\"1,\"CRN" . $i . "\"11,\"" . $rowr["shipto2"] . "\"12,\"" . $rowr["shipto1"] . "\"13,\"" . $dir_1 . "\"14,\"" . $dir_2 . " " . $rowr["direccion2"] . "\"15,\"" . $rowr["cpcuidad_shipto"] . "\"16,\"" . $rowr["cpestado_shipto"] . "\"17,\"" . $orderzip . "\"18,\"" . $rowr["cptelefono_shipto"] . "\"21,\"" . $pesounit . "\"25,\"" . $rowr["cpitem"] . "\"38,\"" . $rowr["Ponumber"] . "_" . $rowr["Custnumber"] . "\"50,\"US\"57,\"" . $heiunit . "\"58,\"" . $widunit . "\"59,\"" . $lenunit . "\"68,\"USD\"75,\"KGS\"79,\"" . $rowr["prod_descripcion"] . "\"80,\"EC\"81,\"\"82,\"1\"541,\"NNNYNNNNN\"542,\"EBDM" . $ii . "\"1030,\"" . $unitprice . "\"1274,\"18\"99,\"\"         ";
                    echo "\r\n";
                    //echo print_r($rowr); echo "\r\n";
                }
            }
            $iiii++;
        }
        $i++;
        if ($iiii >= 999) {
            $ii++;
            $iiii = 0; //////////////////////////////////////////////////////////CONTAMOS 1000 ORDENES Y VAMOS AL SIGUIENTE MASTER DE 1000
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

header("Content-Disposition: attachment; filename=\"" . date("Y-m-d H:i:s") . "_large.in\"");
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