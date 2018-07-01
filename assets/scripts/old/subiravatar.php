<?php

/////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require ("conn.php");

session_start();
$user = $_SESSION["login"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
$target_dir = "../img/users/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $msg_avatar .= " Archivo - " . $imageFileType . " - examinado con éxito.";
        $uploadOk = 1;
    } else {
        $msg_avatar .= " El archivo no es una imagen. <br/>";
        $uploadOk = 0;
    }
}
//// Check if file already exists
//if (file_exists($target_file)) {
//    echo "Sorry, file already exists.";
//    $uploadOk = 0;
//}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    $msg_avatar .= " El archivo es demasiado grande.<br/>";
    $box = "danger";
    $uploadOk = 0;
}
// Allow certain file formats
if ($imageFileType != "jpg") {
    $msg_avatar .= " Solo se permite formato JPG.<br/>";
    $box = "danger";
    $uploadOk = 0;
} else {
    $target_file = $target_dir . $user . ".jpg";
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $msg_avatar .= " No se logró actualizar su foto de perfil.<br/>";
    $box = "danger";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $msg_avatar .= " El archivo " . basename($_FILES["fileToUpload"]["name"]) . " Ahora es tu foto de Perfil.<br/>";
        $box = "primary";
    } else {
        $msg_avatar .= " No se logró actualizar su foto de perfil.";
        $box = "danger";
    }
}
$_SESSION['msg'] = $msg_avatar;
$_SESSION['box'] = $box;
header("Location: ../main.php?panel=user_config.php");
?>

