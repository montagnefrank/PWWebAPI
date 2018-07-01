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
$subject = str_replace('--', ' ', sanitize($_POST['subject']));
$email = str_replace('-DOT-', '.', str_replace('-AT-', '@', sanitize($_POST['email'])));
$message = str_replace('--', ' ', sanitize($_POST['message']));

$response = array();
if (isset($_POST['name']) && $_POST['name'] != '') {

    $query_message = "INSERT INTO hc_messages (messageContact,emailContact,nameContact,subjectContact,dateContact,statusContact,timeContact) "
            . "VALUES ('".$message."','".$email."','".$name."','".$subject."','" . date('Y-m-d') . "','New','" . date('H:i:s') . "')";
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