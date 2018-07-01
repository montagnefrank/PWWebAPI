<?php

/////////////////////////////////////////////////////////////////////////////// PENSAMIENTO CONTROL

/////////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


require ("../../scripts/conn.php");
session_start();

if (isset($_POST['updatefrase'])) {

    $val_select = "UPDATE hc_misc SET fraseSite ='" . $_POST['text'] . "' WHERE idSite = '1'";
    $val_result = $link->query($val_select) or die($link->error);

    $msg_menu = " Titulos actualizado con éxito. ";
    $box = "primary";
    $_SESSION['msg'] = $msg_menu;
    $_SESSION['box'] = $box;
}

if (isset($_POST['submitnewlogo'])) {
    unset($_POST['bgimagefrase']);
    $target_dir = "../../../../assets/img/frase/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Archivo - " . $imageFileType . " - examinado con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= " El archivo no es una imagen. <br/>";
        $uploadOk = 0;
    }
    //// Check if file already exists
    //if (file_exists($target_file)) {
    //    echo "Sorry, file already exists.";
    //    $uploadOk = 0;
    //}
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $msg_logo .= " El archivo es demasiado grande.<br/>";
        $box = "danger";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if ($imageFileType != "png") {
        $msg_logo .= " Solo se permite formato PNG.<br/>";
        $box = "danger";
        $uploadOk = 0;
    } else {
        $target_file = $target_dir . "firma.png";
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $msg_logo .= " No se logró actualizar su logo del sitio.<br/>";
        $box = "danger";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $msg_logo .= " El archivo " . basename($_FILES["fileToUpload"]["name"]) . " Ahora es tu firma de la frase.<br/>";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar su logo.";
            $box = "danger";
        }
    }
    $_SESSION['msg'] = $msg_logo;
    $_SESSION['box'] = $box;
    header("Location: ../../../main.php");
}

if (isset($_POST['bgimagefrase'])) {

    //    var_dump($_FILES);
    //    var_dump($_POST);die;
    $text_select = "UPDATE hc_homeslider SET linea1 ='".$_POST['uptext']."', linea2 ='".$_POST['centertext']."', linea3 ='".$_POST['bottomtext']."'  WHERE idSlide = '1'";
    $text_result = $link->query($text_select) or die($link->error);
    $target_dir = "../../../../assets/img/frase/";
    $target_file = $target_dir . basename($_FILES["slide1file"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["slide1file"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Archivo - " . $imageFileType . " - examinado con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= " El archivo no es una imagen. <br/>";
        $uploadOk = 0;
    }
    //// Check if file already exists
    //if (file_exists($target_file)) {
    //    echo "Sorry, file already exists.";
    //    $uploadOk = 0;
    //}
    // Check file size
    if ($_FILES["slide1file"]["size"] > 500000) {
        $msg_logo .= " El archivo es demasiado grande.<br/>";
        $box = "danger";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if ($imageFileType != "jpg") {
        $msg_logo .= " Solo se permite formato JPG.<br/>";
        $box = "danger";
        $uploadOk = 0;
    } else {
        $target_file = $target_dir . "fondo.jpg";
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $msg_logo .= " No se logró actualizar su fondo para e lslider.<br/>";
        $box = "danger";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["slide1file"]["tmp_name"], $target_file)) {
            $msg_logo .= " El archivo " . basename($_FILES["slide1file"]["name"]) . " Ahora es tu fondo de la seccion.<br/>";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar su fondo.";
            $box = "danger";
        }
    }
    $_SESSION['msg'] = $msg_logo;
    $_SESSION['box'] = $box;
}

?>