<?php

/////////////////////////////////////////////////////////////////////////////// BLOG CONTROL
/////////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


require ("../../scripts/conn.php");
session_start();

if (isset($_POST['addnewBlog'])) {

    //** CARGAMOS LA IMAGEN DEL POST
    $directorio = opendir("../../../../assets/img/blog/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/blog/";
    $target_file = $target_dir . basename($_FILES["postimgBlog"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["postimgBlog"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Imagen de Post examinada con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= " La Imagen de Post no es una imagen. <br/>";
        $box = "danger";
        $uploadOk = 2;
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
    }
    // Check file size
    if ($_FILES["postimgBlog"]["size"] > 500000) {
        $msg_logo .= " La Imagen de Post es demasiado grande.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        $uploadOk = 2;
    }
    // Allow certain file formats
    if ($imageFileType != "jpg") {
        $msg_logo .= " Solo se permite formato JPG para la Imagen de Post.<br/>";
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
        if (move_uploaded_file($_FILES["postimgBlog"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Imagen de Post cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar la Imagen de Post.";
            $box = "danger";
            $_SESSION['msg'] = $msg_logo;
            $_SESSION['box'] = $box;
            return;
        }
    }


    //** CARGAMOS LA IMAGEN DEL SLIDER
    $directorio = opendir("../../../../assets/img/blog/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/port/";
    $target_file = $target_dir . basename($_FILES["headerimgBlog"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["headerimgBlog"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Imagen de Cabecera examinada con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= " La Imagen de Cabecera no es una imagen. <br/>";
        $box = "danger";
        $uploadOk = 2;
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
    }
    // Check file size
    if ($_FILES["headerimgBlog"]["size"] > 500000) {
        $msg_logo .= " La Imagen de Cabecera es demasiado grande.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        $uploadOk = 2;
    }
    // Allow certain file formats
    if ($imageFileType != "jpg") {
        $msg_logo .= " Solo se permite formato JPG para la Imagen de Cabecera.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        $uploadOk = 2;
    } else {
        $target_file = $target_dir . $filename . ".jpg";
        $headerImage = $filename;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 2) {
        $msg_logo .= " No se logró actualizar la Imagen de Cabecera, intente de nuevo.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["headerimgBlog"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Imagen de Cabecera cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar la Imagen de Banner.";
            $box = "danger";
            $_SESSION['msg'] = $msg_logo;
            $_SESSION['box'] = $box;
            return;
        }
    }


    $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $mes = array_search(date("F"), $months);
    $val_select = "INSERT INTO hc_blog(titleBlog,subtitleBlog,dateBlog,headerimgBlog,postimgBlog,htmlBlog,categoryBlog,authorBlog,statusBlog,ebookBlog) "
            . "VALUES ('" . $_POST['titleBlog'] . "','" . $_POST['subtitleBlog'] . "','" . $meses[$mes] . date(" d, Y") . "','" . $headerImage . "','" . $postImage . "','" . addslashes($_POST['entryBlog']) . "','" . $_POST['catBlog'] . "','" . $_SESSION["username"] . "','1','" . $_POST['ebookBlog'] . "')";
    $val_result = $link->query($val_select);

    if ($val_result) {
        $msg_menu = " Nueva Entrada agregada al Blog. ";
        $box = "primary";
        $_SESSION['msg'] = $msg_menu;
        $_SESSION['box'] = $box;
    } else {
        $msg_menu = " No pudimos cargar la entrada a sistema. Intente de nuevo. " . $link->error;
        $box = "primary";
        $_SESSION['msg'] = $msg_menu;
        $_SESSION['box'] = $box;
    }
}

if (isset($_POST['getblogdata'])) {

    $response = array();
    $select = "SELECT * FROM hc_blog WHERE idBlog ='" . $_POST['idBlog'] . "'";
    $result = $link->query($select);

    if ($result) {
        $response['msg'] = "ok";
        $response['Blog'] = $result->fetch_array(MYSQLI_ASSOC);
        unset($response['Blog']['htmlBlog']);
        echo json_encode($response);
    } else {
        $response['msg'] = "notok";
        $response['error'] = $link->error;
        echo json_encode($response);
    }
}

if (isset($_POST['gethtmlblog'])) {

    $select = "SELECT * FROM hc_blog WHERE idBlog ='" . $_POST['idBlog'] . "'";
    $result = $link->query($select);
    $row = $result->fetch_array(MYSQLI_ASSOC);

    if ($result) {
        echo $row['htmlBlog'];
    } else {
        echo "notok";
    }
}

if (isset($_POST['editblogtitles'])) {

    $select = "UPDATE hc_blog SET subtitleBlog ='" . $_POST['subtitleBlog'] . "', titleBlog ='" . $_POST['titleBlog'] . "', categoryBlog ='" . $_POST['categoryBlog'] . "', ebookBlog ='" . $_POST['ebookBlog'] . "' WHERE idBlog ='" . $_POST['idBlog'] . "'";
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

if (isset($_POST['changestatusBlog'])) {

    $val_select = "UPDATE hc_blog SET statusBLog = '" . $_POST['statusBlog'] . "' WHERE idBlog = '" . $_POST['idBlog'] . "'";
    $val_result = $link->query($val_select) or die($link->error);


    if ($val_result) {
        $msg_logo .= " Se ha cambiado el estatus del blog.";
        echo $msg_logo;
    } else {
        echo " No pudimos cambiar el estatus del blog. Intente de nuevo ";
    }
}

if (isset($_POST['newimgheaderBlog'])) {

    //** CARGAMOS LA IMAGEN DE BANNER
    $directorio = opendir("../../../../assets/img/blog/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/blog/";
    $target_file = $target_dir . basename($_FILES["imgheaderBlog"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["imgheaderBlog"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Imagen examinado con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= "La imagen no es un archivo de imagen. <br/>";
        echo $msg_logo;
        return;
    }
    // Check file size
    if ($_FILES["imgheaderBlog"]["size"] > 500000) {
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
        if (move_uploaded_file($_FILES["imgheaderBlog"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Imagen cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar la Imagen.";
            echo $msg_logo;
            return;
        }
    }

    $val_select = "UPDATE hc_blog SET headerimgBlog = '" . $newheadername . "' WHERE idBlog = '" . $_POST['idBlog'] . "'";
    $val_result = $link->query($val_select) or die($link->error);


    if ($val_result) {
        $msg_logo .= " la Imagen fue ingresada en sistema.";
        echo $msg_logo;
    } else {
        echo " No pudimos cargar a sistema. Intente de nuevo ";
    }
}

if (isset($_POST['edithtmlboxes'])) {

    $val_select = "UPDATE hc_blog SET htmlBlog = '" . addslashes($_POST['entryBlog']) . "' WHERE idBlog = '" . $_POST['idBlog'] . "'";
    $val_result = $link->query($val_select) or die($link->error);

    if ($val_result) {
        echo " Textos HTML editados exitosamente.";
    } else {
        echo " No pudimos cambiar los textos. Intente de nuevo ";
    }
}

if (isset($_POST['newimgpostBlog'])) {

    //** CARGAMOS LA IMAGEN DE BANNER
    $directorio = opendir("../../../../assets/img/blog/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/blog/";
    $target_file = $target_dir . basename($_FILES["imgpostBlog"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["imgpostBlog"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Imagen examinado con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= "La imagen no es un archivo de imagen. <br/>";
        echo $msg_logo;
        return;
    }
    // Check file size
    if ($_FILES["imgpostBlog"]["size"] > 500000) {
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
        $newpostname = $filename;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 2) {
        $msg_logo .= " No se logró actualizar la imagen, intente de nuevo.<br/>";
        echo $msg_logo;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["imgpostBlog"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Imagen cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar la Imagen.";
            echo $msg_logo;
            return;
        }
    }

    $val_select = "UPDATE hc_blog SET postimgBlog = '" . $newpostname . "' WHERE idBlog = '" . $_POST['idBlog'] . "'";
    $val_result = $link->query($val_select) or die($link->error);


    if ($val_result) {
        $msg_logo .= " la Imagen fue ingresada en sistema.";
        echo $msg_logo;
    } else {
        echo " No pudimos cargar a sistema. Intente de nuevo ";
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