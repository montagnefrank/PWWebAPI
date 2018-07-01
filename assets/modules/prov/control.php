<?php

/////////////////////////////////////////////////////////////////////////////// PROVEEDORES CONTROL

/////////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require ("../../scripts/conn.php");
session_start();

if (isset($_POST['newprov'])) {

    $directorio = opendir("../../../../assets/img/prov/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/prov/";
    $target_file = $target_dir . basename($_FILES["photoProv"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["photoProv"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Archivo - " . $imageFileType . " - examinado con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= " El archivo no es una imagen. <br/>";
        $box = "danger";
        $uploadOk = 0;
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
    }
    // Check file size
    if ($_FILES["photoProv"]["size"] > 500000) {
        $msg_logo .= " El archivo es demasiado grande.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        $uploadOk = 0;
    }
    // Allow certain file formats
    if ($imageFileType != "png") {
        $msg_logo .= " Solo se permite formato PNG.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        $uploadOk = 0;
    } else {
        $target_file = $target_dir . $filename . ".png";
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $msg_logo .= " No se logró actualizar su imagen del cliente.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["photoProv"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Foto de proveedor cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar su logo del proveedor.";
            $box = "danger";
            $_SESSION['msg'] = $msg_logo;
            $_SESSION['box'] = $box;
            return;
        }
    }

    $val_select = "INSERT INTO hc_prov(photoProv,nameProv,statusProv) VALUES ('" . $filename . "','" . $_POST['nameProv'] . "','" . $_POST['statusProv'] . "')";
    $val_result = $link->query($val_select) or die($link->error);


    $msg_menu = " Nuevo proveedor cargado en sistema exitosamente. ";
    $box = "primary";
    $_SESSION['msg'] = $msg_menu;
    $_SESSION['box'] = $box;
}

if (isset($_POST['editprov'])) {
    
    $loop = $_POST['idList'];
    $loop = explode(',', $loop);

    foreach ($loop as $id){
        if (isset($_POST[$id . '_check'])) {
            $val_select = "UPDATE hc_prov SET statusProv ='" . $_POST[$id . '_check'] . "' WHERE idProv = '".$id."'";
            $val_result = $link->query($val_select) or die($link->error);
        } else {
            $val_select = "UPDATE hc_prov SET statusProv ='0' WHERE idProv = '".$id."'";
            $val_result = $link->query($val_select) or die($link->error);
        }
    }
    
    $msg_menu = " Proveedores Actualizados. ";
    $box = "primary";
    $_SESSION['msg'] = $msg_menu;
    $_SESSION['box'] = $box;
    header("Location: ../../../main.php");
}

if (isset($_POST['deleteprov'])) {
    $val_select = "DELETE FROM hc_prov WHERE idProv = '" . $_POST['deleteid'] . "'";
    $val_result = $link->query($val_select) or die($link->error);

    $msg_menu = " Proveedor eliminado exitosamente. ";
    $box = "primary";
    $_SESSION['msg'] = $msg_menu;
    $_SESSION['box'] = $box;
}
