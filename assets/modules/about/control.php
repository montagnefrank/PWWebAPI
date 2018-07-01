<?php

/////////////////////////////////////////////////////////////////////////////// ABOUT CONTROL
/////////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require ("../../scripts/conn.php");
session_start();

if (isset($_POST['edittitles'])) {

    $val_select = "UPDATE hc_misc SET aboutTitleSite ='" . $_POST['aboutTitle'] . "', aboutSubtitleSite ='" . $_POST['aboutSubtitle'] . "' WHERE idSite = '1'";
    $val_result = $link->query($val_select) or die($link->error);

    if ($val_result) {
        $msg_menu = " Titulos actualizado con éxito. ";
        $box = "primary";
        $_SESSION['msg'] = $msg_menu;
        $_SESSION['box'] = $box;
    } else {
        $msg_menu = " Hubo un error para actualizar los titulos. ";
        $box = "danger";
        $_SESSION['msg'] = $msg_menu;
        $_SESSION['box'] = $box;
    }
}

if (isset($_POST['editwidget'])) {

    $id = $_POST['editId'];

    $val_select = "UPDATE hc_aboutwidget SET titleWidget ='" . $_POST['editTitle'] . "', textWidget ='" . $_POST['editSubtitle'] . "', iconWidget ='" . $_POST['icon'] . "' WHERE idWidget = '" . $id . "'";
    $val_result = $link->query($val_select) or die($link->error);

    $msg_menu = " Widget editado y actualizado. ";
    $box = "primary";
    $_SESSION['msg'] = $msg_menu;
    $_SESSION['box'] = $box;
}

if (isset($_POST['newaboutimg'])) {
    $target_dir = "../../../../assets/img/quienes/";
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
            $msg_logo .= " El archivo " . basename($_FILES["slide1file"]["name"]) . " Ahora es tu imagen de la seccion.<br/>";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar su fondo.";
            $box = "danger";
        }
    }
    $_SESSION['msg'] = $msg_logo;
    $_SESSION['box'] = $box;
}