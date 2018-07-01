<?php

/////////////////////////////////////////////////////////////////////////////// TWEETS CONTROL
/////////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


require ("../../scripts/conn.php");
session_start();

if (isset($_POST['edittitles'])) {

    $val_select = "UPDATE hc_misc SET tweetsTitleSite ='" . $_POST['tweetsTitle'] . "' WHERE idSite = '1'";
    $val_result = $link->query($val_select) or die($link->error);

    $msg_menu = " Titulo actualizado con éxito. ";
    $box = "primary";
    $_SESSION['msg'] = $msg_menu;
    $_SESSION['box'] = $box;
}

if (isset($_POST['editTweet'])) {
    
    $directorio = opendir("../../../../assets/img/tweets/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/tweets/";
    $target_file = $target_dir . basename($_FILES["photoTweet"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["photoTweet"]["tmp_name"]);
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
    if ($_FILES["photoTweet"]["size"] > 500000) {
        $msg_logo .= " El archivo es demasiado grande.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        $uploadOk = 0;
    }
    // Allow certain file formats
    if ($imageFileType != "jpg") {
        $msg_logo .= " Solo se permite formato JPG.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        $uploadOk = 0;
    } else {
        $target_file = $target_dir . $filename . ".jpg";
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $msg_logo .= " No se logró actualizar su imagen de testimonio.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["photoTweet"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Foto testimonio cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar su imagen de testimonio.";
            $box = "danger";
            $_SESSION['msg'] = $msg_logo;
            $_SESSION['box'] = $box;
            return;
        }
    }

    $val_select = "UPDATE hc_tweets SET photoTweet = '" . $filename . "', nameTweet = '" . $_POST['nameTweet'] . "', jobTweet = '" . $_POST['jobTweet'] . "', profileTweet = '" . $_POST['profileTweet'] . "', statusTweet = '" . $_POST['statusTweet'] . "' WHERE idTweet  = '" . $_POST['idTweet'] . "'";
    $val_result = $link->query($val_select) or die($link->error);


    $msg_menu = " Testimonio Editado exitosamente. ";
    $box = "primary";
    $_SESSION['msg'] = $msg_menu;
    $_SESSION['box'] = $box;
}

if (isset($_POST['deletetweet'])) {
    $val_select = "DELETE FROM hc_tweets WHERE idTweet = '" . $_POST['deleteid'] . "'";
    $val_result = $link->query($val_select) or die($link->error);

    $msg_menu = " Testimonio Eliminado exitosamente. ";
    $box = "primary";
    $_SESSION['msg'] = $msg_menu;
    $_SESSION['box'] = $box;
}

if (isset($_POST['newtweet'])) {

    $directorio = opendir("../../../../assets/img/tweets/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/tweets/";
    $target_file = $target_dir . basename($_FILES["photoTweet"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["photoTweet"]["tmp_name"]);
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
    if ($_FILES["photoTweet"]["size"] > 500000) {
        $msg_logo .= " El archivo es demasiado grande.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        $uploadOk = 0;
    }
    // Allow certain file formats
    if ($imageFileType != "jpg") {
        $msg_logo .= " Solo se permite formato JPG.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        $uploadOk = 0;
    } else {
        $target_file = $target_dir . $filename . ".jpg";
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $msg_logo .= " No se logró actualizar su imagen de testimonio.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["photoTweet"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Foto de miembro cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar su imagen de perfil.";
            $box = "danger";
            $_SESSION['msg'] = $msg_logo;
            $_SESSION['box'] = $box;
            return;
        }
    }

    $val_select = "INSERT INTO hc_tweets(photoTweet,nameTweet,jobTweet,profileTweet,statusTweet) VALUES ('" . $filename . "','" . $_POST['nameTweet'] . "','" . $_POST['jobTweet'] . "','" . $_POST['profileTweet'] . "','" . $_POST['statusTweet'] . "')";
    $val_result = $link->query($val_select) or die($link->error);


    $msg_menu = " Nuevo testimonio cargado en sistema exitosamente. ";
    $box = "primary";
    $_SESSION['msg'] = $msg_menu;
    $_SESSION['box'] = $box;
}
?>