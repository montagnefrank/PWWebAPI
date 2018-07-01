<?php

/////////////////////////////////////////////////////////////////////////////// EBOOKS CONTROL
/////////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require ("../../scripts/conn.php");
session_start();

if (isset($_POST['addnewPdf'])) {

    //** CARGAMOS LA IMAGEN DEL POST
    $directorio = opendir("../../../../assets/img/pdf/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/pdf/";
    $target_file = $target_dir . basename($_FILES["postimgPdf"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["postimgPdf"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Imagen del Ebook examinada con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= " El archivo no es una imagen. <br/>";
        $box = "danger";
        $uploadOk = 2;
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
    }
    // Check file size
    if ($_FILES["postimgPdf"]["size"] > 500000) {
        $msg_logo .= " La Imagen del Ebook es demasiado grande.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        $uploadOk = 2;
    }
    // Allow certain file formats
    if ($imageFileType != "jpg") {
        $msg_logo .= " Solo se permite formato JPG para la Imagen del Ebook.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        $uploadOk = 2;
    } else {
        $target_file = $target_dir . $filename . ".jpg";
        $postImage = $filename;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 2) {
        $msg_logo .= " No se logró actualizar la Imagen de Post, intente de nuevo.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["postimgPdf"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Imagen de Post cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar la Imagen del Ebook.";
            $box = "danger";
            $_SESSION['msg'] = $msg_logo;
            $_SESSION['box'] = $box;
            return;
        }
    }


    //** CARGAMOS EL ARCHIVO PDF
    $directorio = opendir("../../../../assets/files/ebooks/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/files/ebooks/";
    $target_file = $target_dir . basename($_FILES["headerimgPdf"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check file size
    if ($_FILES["headerimgPdf"]["size"] > 5000000) {
        $msg_logo .= " El archivo PDF es demasiado grande.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        $uploadOk = 2;
    }
    // Allow certain file formats
    if ($imageFileType != "pdf") {
        $msg_logo .= " Solo se permite formato PDF para el Ebook.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        $uploadOk = 2;
    } else {
        $target_file = $target_dir . $filename . ".pdf";
        $headerImage = $filename;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 2) {
        $msg_logo .= " No se logró cargar el Archivo PDF, intente de nuevo.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["headerimgPdf"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " El acrhivo PDF fue cargado exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró cargar El archivo PDF.";
            $box = "danger";
            $_SESSION['msg'] = $msg_logo;
            $_SESSION['box'] = $box;
            return;
        }
    }


    $val_select = "INSERT INTO hc_pdf(titlePdf,subtitlePdf,htmlPdf,imgPdf,pathPdf,statusPdf) "
            . "VALUES ('" . $_POST['titlePdf'] . "','" . $_POST['subtitlePdf'] . "','" . addslashes($_POST['entryPdf']) . "','" . $postImage . "','" . $headerImage . "','1')";
    $val_result = $link->query($val_select);

    if ($val_result) {
        $msg_menu = " Nuevo Ebook agregado al Sistema. ";
        $box = "primary";
        $_SESSION['msg'] = $msg_menu;
        $_SESSION['box'] = $box;
    } else {
        $msg_menu = " No pudimos cargar el Ebook a sistema. Intente de nuevo. " . $link->error;
        $box = "primary";
        $_SESSION['msg'] = $msg_menu;
        $_SESSION['box'] = $box;
    }
}

if (isset($_POST['getpdfdata'])) {

    $select = "SELECT idPdf,titlePdf,subtitlePdf,imgPdf,pathPdf,statusPdf FROM hc_pdf WHERE idPdf ='" . $_POST['idPdf'] . "'";
    $result = $link->query($select);
    $row = $result->fetch_array(MYSQLI_BOTH);

    $response = array();
    if ($row) {
        $response['msg'] = "ok";
        $response['Pdf'] = $row;
        unset($response['Pdf']['htmlPdf']);
        echo json_encode($response);
    } else {
        $response['msg'] = "notok";
        $response['error'] = $link->error;
        echo json_encode($response);
    }
}

if (isset($_POST['gethtmlpdf'])) {

    $select = "SELECT * FROM hc_pdf WHERE idPdf ='" . $_POST['idPdf'] . "'";
    $result = $link->query($select);
    $row = $result->fetch_array(MYSQLI_ASSOC);

    if ($result) {
        echo $row['htmlPdf'];
    } else {
        echo "notok";
    }
}

if (isset($_POST['editpdftitles'])) {

    $select = "UPDATE hc_pdf SET subtitlePdf ='" . $_POST['subtitlePdf'] . "', titlePdf ='" . $_POST['titlePdf'] . "' WHERE idPdf ='" . $_POST['idPdf'] . "'";
    $result = $link->query($select) or die($link->error);

    if ($result) {
        $msg_menu = " Titulos actualizado con éxito. ";
        $box = "primary";
        $_SESSION['msg'] = $msg_menu;
        $_SESSION['box'] = $box;
    } else {
        $msg_menu = " No pudimos actualizar los Titulos. Intente de nuevo ";
        $box = "danger";
        $_SESSION['msg'] = $msg_menu;
        $_SESSION['box'] = $box;
    }
}

if (isset($_POST['changestatusPdf'])) {

    $val_select = "UPDATE hc_pdf SET statusPdf = '" . $_POST['statusPdf'] . "' WHERE idPdf = '" . $_POST['idPdf'] . "'";
    $val_result = $link->query($val_select) or die($link->error);


    if ($val_result) {
        $msg_logo .= " Se ha cambiado el estatus del blog.";
        echo $msg_logo;
    } else {
        echo " No pudimos cambiar el estatus del blog. Intente de nuevo ";
    }
}

if (isset($_POST['newimgheaderPdf'])) {

    //** CARGAMOS LA IMAGEN DE BANNER
    $directorio = opendir("../../../../assets/img/pdf/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/pdf/";
    $target_file = $target_dir . basename($_FILES["imgheaderPdf"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["imgheaderPdf"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Imagen examinada con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= "El archivo no es una imagen. <br/>";
        echo $msg_logo;
        return;
    }
    // Check file size
    if ($_FILES["imgheaderPdf"]["size"] > 500000) {
        $msg_logo .= " La imagen es demasiado grande.<br/>";
        echo $msg_logo;
        return;
        $uploadOk = 2;
    }
    // Allow certain file formats
    if ($imageFileType != "jpg") {
        $msg_logo .= " Solo se permite formato jpg.<br/>";
        echo $msg_logo;
        return;
        $uploadOk = 2;
    } else {
        $target_file = $target_dir . $filename . ".jpg";
        $newheadername = $filename;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 2) {
        $msg_logo .= " No se logró actualizar la imagen, intente de nuevo.<br/>";
        echo $msg_logo;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["imgheaderPdf"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Imagen cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar la Imagen.";
            echo $msg_logo;
            return;
        }
    }

    $val_select = "UPDATE hc_pdf SET imgPdf = '" . $newheadername . "' WHERE idPdf = '" . $_POST['idPdf'] . "'";
    $val_result = $link->query($val_select) or die($link->error);


    if ($val_result) {
        $msg_logo .= " la Imagen fue ingresada en sistema.";
        echo $msg_logo;
    } else {
        echo " No pudimos cargar a sistema. Intente de nuevo ";
    }
}

if (isset($_POST['edithtmlboxes'])) {

    $val_select = "UPDATE hc_pdf SET htmlPdf = '" . addslashes($_POST['entryPdf']) . "' WHERE idPdf = '" . $_POST['idPdf'] . "'";
    $val_result = $link->query($val_select) or die($link->error);

    if ($val_result) {
        echo " Textos HTML editados exitosamente.";
    } else {
        echo " No pudimos cambiar los textos. Intente de nuevo ";
    }
}

if (isset($_POST['newPdf'])) {

    //** CARGAMOS LA IMAGEN DE BANNER
    $directorio = opendir("../../../../assets/files/ebooks/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/files/ebooks/";
    $target_file = $target_dir . basename($_FILES["filePdf"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check file size
    if ($_FILES["filePdf"]["size"] > 5000000) {
        $msg_logo .= " El Archivo es demasiado grande.<br/>";
        echo $msg_logo;
        return;
        $uploadOk = 2;
    }
    // Allow certain file formats
    if ($imageFileType != "pdf") {
        $msg_logo .= " Solo se permite formato PDF.<br/>";
        echo $msg_logo;
        return;
        $uploadOk = 2;
    } else {
        $target_file = $target_dir . $filename . ".pdf";
        $newpostname = $filename;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 2) {
        $msg_logo .= " No se logró actualizar la imagen, intente de nuevo.<br/>";
        echo $msg_logo;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["filePdf"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Archivo cargado exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar la Imagen.";
            echo $msg_logo;
            return;
        }
    }

    $val_select = "UPDATE hc_pdf SET pathPdf = '" . $newpostname . "' WHERE idPdf = '" . $_POST['idPdf'] . "'";
    $val_result = $link->query($val_select) or die($link->error);


    if ($val_result) {
        $msg_logo .= " PDF cargado en sistema.";
        echo $msg_logo;
    } else {
        echo " No pudimos cargar el pdf a sistema. Intente de nuevo ";
    }
}

if (isset($_POST['deleteBlog'])) {

    $val_select = "DELETE FROM hc_blog WHERE idBlog = '" . $_POST['deleteid'] . "'";
    $val_result = $link->query($val_select) or die($link->error);

    if ($val_result) {
        $msg_menu = " Se ha eliminado exitosamente el proyecto. ";
        $box = "primary";
        $_SESSION['msg'] = $msg_menu;
        $_SESSION['box'] = $box;
    } else {
        $msg_menu = " No se ha eliminado el proyecto, intente de nuevo. ";
        $box = "danger";
        $_SESSION['msg'] = $msg_menu;
        $_SESSION['box'] = $box;
    }
}

if (isset($_POST['newBloglistImg'])) {

    //** CARGAMOS LA IMAGEN DE BLOGLIST
    $target_dir = "../../../../assets/img/";
    $target_file = $target_dir . basename($_FILES["bloglistImg"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["bloglistImg"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Imagen examinado con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= "La imagen no es un archivo de imagen. <br/>";
        echo $msg_logo;
        return;
    }
    // Check file size
    if ($_FILES["bloglistImg"]["size"] > 500000) {
        $msg_logo .= " La imagen es demasiado grande.<br/>";
        echo $msg_logo;
        return;
        $uploadOk = 2;
    }
    // Allow certain file formats
    if ($imageFileType != "jpg") {
        $msg_logo .= " Solo se permite formato jpg.<br/>";
        echo $msg_logo;
        return;
        $uploadOk = 2;
    } else {
        $target_file = $target_dir . "blogtitle.jpg";
        $newpostname = $filename;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 2) {
        $msg_logo .= " No se logró actualizar la imagen, intente de nuevo.<br/>";
        echo $msg_logo;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["bloglistImg"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Imagen cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar la Imagen.";
            echo $msg_logo;
            return;
        }
    }

    $val_select = "UPDATE hc_misc SET blogTitleSite = '" . $_POST['titleBloglist'] . "', blogSubtitleSite = '" . $_POST['sutitleBloglist'] . "' WHERE idSite = '1'";
    $val_result = $link->query($val_select) or die($link->error);


    if ($val_result) {
        $msg_menu = " La Imagen fue ingresada en sistema y los textos fueron actualizados. ";
        $box = "primary";
        $_SESSION['msg'] = $msg_menu;
        $_SESSION['box'] = $box;
    } else {
        $msg_menu = " No pudimos cargar a sistema. Intente de nuevo . ";
        $box = "primary";
        $_SESSION['msg'] = $msg_menu;
        $_SESSION['box'] = $box;
    }
}
?>