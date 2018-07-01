<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require 'conn.php';

function sanitize($string) {
    $string = str_replace(' ', '--', $string); 
    $string = str_replace('.', '-DOT-', $string); 
    $string = str_replace('@', '-AT-', $string); 
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}

$name = str_replace('--', ' ', sanitize($_POST['name']));
$email = str_replace('-DOT-', '.', str_replace('-AT-', '@', sanitize($_POST['email'])));
$phone = sanitize($_POST['phone']);
$dirr = str_replace('--', ' ', sanitize($_POST['dirr']));
$ciudad = str_replace('--', ' ', sanitize($_POST['ciudad']));
$provincia = str_replace('--', ' ', sanitize($_POST['provincia']));
$detallesproyecto = str_replace('--', ' ', sanitize($_POST['detallesproyecto']));
$nombreproyecto = str_replace('--', ' ', sanitize($_POST['nombreproyecto']));
$tipomodelo = str_replace('--', ' ', sanitize($_POST['tipomodelo']));
$lineatiempo = str_replace('--', ' ', sanitize($_POST['lineatiempo']));
$presupuesto = str_replace('--', ' ', sanitize($_POST['presupuesto']));
$usofuncional = str_replace('--', ' ', sanitize($_POST['usofuncional']));
$zonariesgo = str_replace('--', ' ', sanitize($_POST['zonariesgo']));
$hvac = str_replace('--', ' ', sanitize($_POST['hvac']));
$tipofundacion = str_replace('--', ' ', sanitize($_POST['tipofundacion']));
$sotano = str_replace('--', ' ', sanitize($_POST['sotano']));
$acabados = str_replace('--', ' ', sanitize($_POST['acabados']));
$camion = str_replace('--', ' ', sanitize($_POST['camion']));
$comentarios = str_replace('--', ' ', sanitize($_POST['comentarios']));

$response = array();
if (isset($_POST['name']) && $_POST['name'] != '') {

    $query_message = "INSERT INTO `hc_encuesta` (`nameEnc`, `emailEnc`, `phoneEnc`, `addrEnc`, `cityEnc`, `edoEnc`, `detEnc`, `pronameEnc`, `tipoEnc`, `timeEnc`, `presEnc`, `usoEnc`, `zonaEnc`, `specsEnc`, `fundEnc`, `sotaEnc`, `estEnc`, `camiEnc`, `comentEnc`, `dateEnc`, `horaEnc`, `statusEnc`)"
            . "VALUES ('".$name."','".$email."','".$phone."','".$dirr."','".$ciudad."','".$provincia."','".$detallesproyecto."','".$nombreproyecto."','".$tipomodelo."','".$lineatiempo."','".$presupuesto."','".$usofuncional."','".$zonariesgo."','".$hvac."','".$tipofundacion."','".$sotano."','".$acabados."','".$camion."','".$comentarios."','" . date('Y-m-d') . "','" . date('H:i:s') . "','New')";
    $result_message = mysqli_query($link, $query_message);
    if ($result_message) {
        $response['msg'] = "success";
        echo json_encode($response);
    } else {
        $response['msg'] = "No pudimos enviar tu mensaje";
        echo json_encode($response);
    }
}
?>