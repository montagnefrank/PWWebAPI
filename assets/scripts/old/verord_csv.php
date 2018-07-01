<?php

ini_set("display_errors", "On");
error_reporting(E_ALL ^ E_NOTICE);

ini_set('memory_limit', '-1');
require_once ('../php/PHPExcel.php');
include ('../php/PHPExcel/IOFactory.php');
require ("conn.php");
require ("islogged.php");
session_start();

$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
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

$directorio = opendir("xlsups/"); //ruta de archivos XML
$iii = 0;
while ($archivo = readdir($directorio)) {
    if (!is_dir($archivo)) {
        $iii++;
    }
}

$fp = fopen('xlsups/'.$iii.'.csv', 'w');

//Identificamos el PAIS
if ($pais == 'US') {

    //CREAMOS LOS ENCABEZADOS
    //SI ES ROL 3 TIENE ENCABEZADO SIN MENSAJE AL FINAL
    if ($rol == 3) {
        fputcsv($fp, array('Tracking', 'Company', 'eBinv', 'Orddate', 'Shipto', 'Shipto2', 'Address', 'Address2', 'City', 'State', 'Zip', 'Phone', 'Soldto', 'Soldto2', 'STPhone', 'Ponumber', 'CUSTnbr', 'SHIPDT', 'Deliver', 'SatDel', 'Quantity', 'Item', 'ProdDesc', 'Length', 'Width', 'Height', 'WeightKg', 'DclValue', 'Message', 'Service', 'PkgType', 'GenDesc', 'ShipCtry', 'Currency', 'Origin', 'UOM', 'TPComp', 'TPAttn', 'TPAdd1', 'TPCity', 'TPState', 'TPCtry', 'TPZip', 'TPPhone', 'TPAcct', 'Farm'));
    } else {
        fputcsv($fp, array('Tracking', 'Company', 'eBinv', 'Orddate', 'Shipto', 'Shipto2', 'Address', 'Address2', 'City', 'State', 'Zip', 'Phone', 'Soldto', 'Soldto2', 'STPhone', 'Ponumber', 'CUSTnbr', 'SHIPDT', 'Deliver', 'SatDel', 'Quantity', 'Item', 'ProdDesc', 'Length', 'Width', 'Height', 'WeightKg', 'DclValue', 'Message', 'Service', 'PkgType', 'GenDesc', 'ShipCtry', 'Currency', 'Origin', 'UOM', 'TPComp', 'TPAttn', 'TPAdd1', 'TPCity', 'TPState', 'TPCtry', 'TPZip', 'TPPhone', 'TPAcct', 'Farm', 'MSG'));
    }

    //GENERAMOS EL CSV
    while ($rowr = mysqli_fetch_assoc($query)) {

        $rowr['cpmensaje'] = preg_replace("/\r|\n/", "", $rowr['cpmensaje']);
        $rowr['cpmensaje'] = addslashes($rowr['cpmensaje']);
        //DEFINIMOS SI TIENE MENSAJE MUESTRA "Y" EN SU DEFECTO MUESTRA "N"
        if (ltrim(rtrim($rowr['mensaje2'])) == 'To-Blank Info   ::From- Blank Info   ::Blank .Info') {
            $rowr['mensaje2'] = "N";
        } else {
            $rowr['mensaje2'] = "Y";
        }

        //SI ES ROL 1 MUESTRA TODA LA INFROMACION
        if ($rol == 1 || $rol == 2) {
            unset($rowr['estado_orden']);
            fputcsv($fp, $rowr);

            //SI ES ROL DISTINTO A 1 MUESTRA SOLO LOS QUE NO TIENEN TRACKING NI LAS CANCELADAS
        } else {
            if (ltrim(rtrim($rowr['estado_orden'])) == 'Active' && $rowr['tracking'] == '') {

                //SI ES ROL 3 ELIMINA EL MENSAJE AL FINAL
                if ($rol == 3) {
                    unset($rowr['mensaje2']);
                }
                unset($rowr['estado_orden']);
                fputcsv($fp, $rowr);
            }
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
        $rowr['cpmensaje'] = addslashes($rowr['cpmensaje']);
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

            //SI ES ROL DISTINTO A 1 MUESTRA SOLO LOS QUE NO TIENEN TRACKING NI LAS CANCELADAS
        } else {
            if (ltrim(rtrim($rowr['estado_orden'])) == 'Active' && $rowr['tracking'] == '') {
                unset($rowr['estado_orden']);
                fputcsv($fp, $rowr);
            }
        }
    }
}
fclose($fp);


header("Content-Type: text/csv; charset=utf-8");
header("Content-disposition: filename=reporte.csv");
readfile("xlsups/".$iii.".csv");