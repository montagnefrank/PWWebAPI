<?php

/////////////////////////////////////////////////////////////////////////////// GALLERY CONTROL
/////////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


require ("../../scripts/conn.php");
session_start();

if (isset($_POST['newimgGallery'])) {

    //** CARGAMOS LA IMAGEN DE GALERA
    $directorio = opendir("../../../../img/");

    $filename = substr($_FILES["imgGallery"]["name"], 0, 3);
    $target_dir = "../../../../img/";
    $target_file = $target_dir . basename($_FILES["imgGallery"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["imgGallery"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Imagen examinada con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= " La Imagen no es un archivo de imagen. <br/>";
        echo $msg_logo;
        return;
    }
    // Check file size
    if ($_FILES["imgGallery"]["size"] > 500000) {
        $msg_logo .= " La Imagen es demasiado grande.<br/>";
        echo $msg_logo;
        return;
        $uploadOk = 2;
    }
    // Allow certain file formats
    if ($imageFileType == "jpg" || $imageFileType == "png") {
        $target_file = $target_dir . $filename . "." . $imageFileType;
    } else {
        $msg_logo .= " Formato de imagen no es v&aacute;lido.<br/>";
        echo $msg_logo;
        return;
        $uploadOk = 2;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 2) {
        $msg_logo .= " No se logró actualizar la Imagen, intente de nuevo.<br/>";
        echo $msg_logo;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["imgGallery"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Imagen cargada exitosamente.";
            echo $msg_logo;
        } else {
            $msg_logo .= " No se logró actualizar la Imagen.";
            echo $msg_logo;
            return;
        }
    }
}

if (isset($_POST['deleteImg'])) {
    
    if (unlink("../../../../img/" . $_POST['deleteid'])) {
        echo 'Imagen eliminada exitosamente';
    } else {
        echo " No pudimos eliminar de sistema. Intente de nuevo ";
    }
}

?>