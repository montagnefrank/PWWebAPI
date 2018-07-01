<?php

/////////////////////////////////////////////////////////////////////////////// PORTFOLIO CONTROL
/////////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


require ("../../scripts/conn.php");
session_start();

if (isset($_POST['deletePort'])) {

    $val_select = "DELETE FROM hc_portfolio WHERE idPort = '" . $_POST['deleteid'] . "'";
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

if (isset($_POST['edithtmlboxes'])) {

    $val_select = "UPDATE hc_portfolio SET details = '" . addslashes($_POST['details']) . "', descriptionPort = '" . addslashes($_POST['descriptionPort']) . "', acabadostextPort = '" . addslashes($_POST['acabadostextPort']) . "' WHERE idPort = '" . $_POST['idPort'] . "'";
    $val_result = $link->query($val_select) or die($link->error);

    if ($val_result) {
        echo " Textos HTML editados exitosamente.";
    } else {
        echo " No pudimos cambiar los textos. Intente de nuevo ";
    }
}

if (isset($_POST['changestatusPort'])) {

    $val_select = "UPDATE hc_portfolio SET statusPort = '" . $_POST['statusPort'] . "' WHERE idPort = '" . $_POST['idPort'] . "'";
    $val_result = $link->query($val_select) or die($link->error);


    if ($val_result) {
        $msg_logo .= " Se ha cambiado el estatus del proyecto.";
        echo $msg_logo;
    } else {
        echo " No pudimos cambiar el estatus del proyecto. Intente de nuevo ";
    }
}

if (isset($_POST['newimgbannerPort'])) {

    //** CARGAMOS LA IMAGEN DE BANNER
    $directorio = opendir("../../../../assets/img/port/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/port/";
    $target_file = $target_dir . basename($_FILES["imgbannerPort"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["imgbannerPort"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Imagen examinado con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= "La imagen no es un archivo de imagen. <br/>";
        echo $msg_logo;
        return;
    }
    // Check file size
    if ($_FILES["imgbannerPort"]["size"] > 500000) {
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
        $newbannername = $filename;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 2) {
        $msg_logo .= " No se logró actualizar la imagen, intente de nuevo.<br/>";
        echo $msg_logo;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["imgbannerPort"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Imagen cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar la Imagen.";
            echo $msg_logo;
            return;
        }
    }

    $val_select = "UPDATE hc_portfolio SET fullwidthimgPort = '" . $newbannername . "' WHERE idPort = '" . $_POST['idPort'] . "'";
    $val_result = $link->query($val_select) or die($link->error);


    if ($val_result) {
        $msg_logo .= " la Imagen fue ingresada en sistema.";
        echo $msg_logo;
    } else {
        echo " No pudimos cargar a sistema. Intente de nuevo ";
    }
}

if (isset($_POST['newimgcustom'])) {

    //** CARGAMOS LA IMAGEN DE BANNER
    $target_dir = "../../../../assets/img/port/";
    $target_file = $target_dir . basename($_FILES["imgcustom"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["imgcustom"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Imagen examinada con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= "La imagen no es un archivo de imagen. <br/>";
        echo $msg_logo;
        return;
    }
    // Check file size
    if ($_FILES["imgcustom"]["size"] > 500000) {
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
        $target_file = $target_dir . "custom.jpg";
        $newbannername = $filename;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 2) {
        $msg_logo .= " No se logró actualizar la imagen, intente de nuevo.<br/>";
        echo $msg_logo;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["imgcustom"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Imagen cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar la Imagen.";
            echo $msg_logo;
            return;
        }
    }
    $msg_logo .= " la Imagen fue ingresada en sistema.";
    echo $msg_logo;
}

if (isset($_POST['newimgpostPort'])) {

    //** CARGAMOS LA IMAGEN DE BANNER
    $directorio = opendir("../../../../assets/img/port/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/port/";
    $target_file = $target_dir . basename($_FILES["imgpostPort"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["imgpostPort"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Imagen examinado con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= "La imagen no es un archivo de imagen. <br/>";
        echo $msg_logo;
        return;
    }
    // Check file size
    if ($_FILES["imgpostPort"]["size"] > 500000) {
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
        if (move_uploaded_file($_FILES["imgpostPort"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Imagen cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar la Imagen.";
            echo $msg_logo;
            return;
        }
    }

    $val_select = "UPDATE hc_portfolio SET postimgPort = '" . $newpostname . "' WHERE idPort = '" . $_POST['idPort'] . "'";
    $val_result = $link->query($val_select) or die($link->error);


    if ($val_result) {
        $msg_logo .= " la Imagen fue ingresada en sistema.";
        echo $msg_logo;
    } else {
        echo " No pudimos cargar a sistema. Intente de nuevo ";
    }
}

if (isset($_POST['newimgheaderPort'])) {

    //** CARGAMOS LA IMAGEN DE BANNER
    $directorio = opendir("../../../../assets/img/port/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/port/";
    $target_file = $target_dir . basename($_FILES["imgheaderPort"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["imgheaderPort"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Imagen examinado con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= "La imagen no es un archivo de imagen. <br/>";
        echo $msg_logo;
        return;
    }
    // Check file size
    if ($_FILES["imgheaderPort"]["size"] > 500000) {
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
        if (move_uploaded_file($_FILES["imgheaderPort"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Imagen cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar la Imagen.";
            echo $msg_logo;
            return;
        }
    }

    $val_select = "UPDATE hc_portfolio SET bannerimgPost = '" . $newheadername . "' WHERE idPort = '" . $_POST['idPort'] . "'";
    $val_result = $link->query($val_select) or die($link->error);


    if ($val_result) {
        $msg_logo .= " la Imagen fue ingresada en sistema.";
        echo $msg_logo;
    } else {
        echo " No pudimos cargar a sistema. Intente de nuevo ";
    }
}

if (isset($_POST['newimgacabadosPort'])) {

    //** CARGAMOS LA IMAGEN DE BANNER
    $directorio = opendir("../../../../assets/img/acabados/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/acabados/";
    $target_file = $target_dir . basename($_FILES["imgacabadosPort"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["imgacabadosPort"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Acabado examinado con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= "El Acabado no es un archivo de imagen. <br/>";
        echo $msg_logo;
        return;
    }
    // Check file size
    if ($_FILES["imgacabadosPort"]["size"] > 500000) {
        $msg_logo .= " El Acabado es demasiado grande.<br/>";
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
        $newacabadoname = $filename;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 2) {
        $msg_logo .= " No se logró actualizar el Acabado, intente de nuevo.<br/>";
        echo $msg_logo;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["imgacabadosPort"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Acabado cargado exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar el Acabado.";
            echo $msg_logo;
            return;
        }
    }

    $nameAcabado = $porciones = explode(".", $_FILES["imgacabadosPort"]["name"]);
    $val_select = "INSERT INTO hc_acabados(idPort,nameAcabado,imgAcabado,statusAcabado) "
            . "VALUES ('" . $_POST['idPort'] . "','" . $nameAcabado[0] . "','" . $newacabadoname . "','1')";
    $val_result = $link->query($val_select) or die($link->error);


    if ($val_result) {
        $msg_logo .= " el Acabado fue ingresado en sistema.";
        echo $msg_logo;
    } else {
        echo " No pudimos cargar a sistema. Intente de nuevo ";
    }
}

if (isset($_POST['newimgplanosPort'])) {

    //** CARGAMOS LA IMAGEN DE BANNER
    $directorio = opendir("../../../../assets/img/planos/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/planos/";
    $target_file = $target_dir . basename($_FILES["imgplanosPort"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["imgplanosPort"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Plano examinado con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= "El plano no es un archivo de imagen. <br/>";
        echo $msg_logo;
        return;
    }
    // Check file size
    if ($_FILES["imgplanosPort"]["size"] > 500000) {
        $msg_logo .= " El Plano es demasiado grande.<br/>";
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
        $newplanoname = $filename;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 2) {
        $msg_logo .= " No se logró actualizar el Plano, intente de nuevo.<br/>";
        echo $msg_logo;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["imgplanosPort"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Plano cargado exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar el Plano.";
            echo $msg_logo;
            return;
        }
    }

    $val_select = "INSERT INTO hc_planosslider(idPort,namePlano,statusPlano) "
            . "VALUES ('" . $_POST['idPort'] . "','" . $newplanoname . "','1')";
    $val_result = $link->query($val_select) or die($link->error);


    if ($val_result) {
        $msg_logo .= " el Plano fue ingresado en sistema.";
        echo $msg_logo;
    } else {
        echo " No pudimos cargar a sistema. Intente de nuevo ";
    }
}

if (isset($_POST['newimgsliderPort'])) {

    //** CARGAMOS LA IMAGEN DE BANNER
    $directorio = opendir("../../../../assets/img/portslider/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/portslider/";
    $target_file = $target_dir . basename($_FILES["imgsliderPort"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["imgsliderPort"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Imagen examinada con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= " La Imagen no es un archivo de imagen. <br/>";
        echo $msg_logo;
        return;
    }
    // Check file size
    if ($_FILES["imgsliderPort"]["size"] > 500000) {
        $msg_logo .= " La Imagen es demasiado grande.<br/>";
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
        $newimgname = $filename;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 2) {
        $msg_logo .= " No se logró actualizar la Imagen, intente de nuevo.<br/>";
        echo $msg_logo;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["imgsliderPort"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Imagen cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar la Imagen.";
            echo $msg_logo;
            return;
        }
    }

    $val_select = "INSERT INTO hc_portsliderimg(idPort,nameImage,statusImage) "
            . "VALUES ('" . $_POST['idPort'] . "','" . $newimgname . "','1')";
    $val_result = $link->query($val_select) or die($link->error);


    if ($val_result) {
        $msg_logo .= " La imagen fue ingresada en sistema.";
        echo $msg_logo;
    } else {
        echo " No pudimos eliminar de sistema. Intente de nuevo ";
    }
}

if (isset($_POST['deletesliderimg'])) {
    $table = '';
    $col = '';
    $msg = '';
    if ($_POST['deletetype'] == 'sliderimg') {
        $table = 'hc_portsliderimg';
        $col = 'idImage';
        $msg = 'Imagen eliminada con éxito.';
    }
    if ($_POST['deletetype'] == 'sliderplanos') {
        $table = 'hc_planosslider';
        $col = 'idPlano';
        $msg = 'Plano eliminado con éxito.';
    }
    if ($_POST['deletetype'] == 'slideracabados') {
        $table = 'hc_acabados';
        $col = 'idAcabado';
        $msg = 'Acabado eliminado con éxito.';
    }
    $val_select = "DELETE FROM " . $table . " WHERE " . $col . " = '" . $_POST['deleteid'] . "'";
    $val_result = $link->query($val_select) or die($link->error);

    if ($val_result) {
        echo $msg;
    } else {
        echo " No pudimos eliminar de sistema. Intente de nuevo ";
    }
}

if (isset($_POST['getAcabadosSlider'])) {

    $selectimg = "SELECT * FROM hc_acabados WHERE idPort ='" . $_POST['idPort'] . "'";
    $resultimg = $link->query($selectimg) or die($link->error);
    while ($rowimg = $resultimg->fetch_array(MYSQLI_BOTH)) {
        echo ' 
                <a class="gallery-item" href="" title="Imagen ' . $rowimg[3] . '" data-gallery>
                    <div class="image">                              
                        <img src="../assets/img/acabados/' . $rowimg[3] . '.jpg" alt="Imagen ' . $rowimg[2] . '"/>                                        
                        <ul class="gallery-item-controls">
                            <li>
                                <span class="gallery-item-remove"><i class="fa fa-times"></i>
                                    <div class="hidethis_force imageidPortContainer">' . $rowimg[0] . '</div>
                                    <div class="hidethis_force tableSliderContainer">slideracabados</div>
                                </span></li>
                        </ul>                                                                    
                    </div>  
                    <div class="meta">
                        <strong>' . $rowimg[2] . '</strong>
                    </div>                              
                </a>  
           ';
    }
}

if (isset($_POST['getPlanosSlider'])) {

    $selectimg = "SELECT * FROM hc_planosslider WHERE idPort ='" . $_POST['idPort'] . "'";
    $resultimg = $link->query($selectimg) or die($link->error);
    while ($rowimg = $resultimg->fetch_array(MYSQLI_BOTH)) {
        echo ' 
                <a class="gallery-item" href="" title="Imagen ' . $rowimg[2] . '" data-gallery>
                    <div class="image">                              
                        <img src="../assets/img/planos/' . $rowimg[2] . '.jpg" alt="Imagen ' . $rowimg[2] . '"/>                                        
                        <ul class="gallery-item-controls">
                            <li>
                                <span class="gallery-item-remove"><i class="fa fa-times"></i>
                                    <div class="hidethis_force imageidPortContainer">' . $rowimg[0] . '</div>
                                    <div class="hidethis_force tableSliderContainer">sliderplanos</div>
                                </span></li>
                        </ul>                                                                    
                    </div>                               
                </a>  
           ';
    }
}

if (isset($_POST['getImgSlider'])) {

    $selectimg = "SELECT * FROM hc_portsliderimg WHERE idPort ='" . $_POST['idPort'] . "'";
    $resultimg = $link->query($selectimg) or die($link->error);
    while ($rowimg = $resultimg->fetch_array(MYSQLI_BOTH)) {
        echo ' 
                <a class="gallery-item" href="" title="Imagen ' . $rowimg[2] . '" data-gallery>
                    <div class="image">                              
                        <img src="../assets/img/portslider/' . $rowimg[2] . '.jpg" alt="Imagen ' . $rowimg[2] . '"/>                                        
                        <ul class="gallery-item-controls">
                            <li>
                                <span class="gallery-item-remove"><i class="fa fa-times"></i>
                                    <div class="hidethis_force imageidPortContainer">' . $rowimg[0] . '</div>
                                    <div class="hidethis_force tableSliderContainer">sliderimg</div>
                                </span></li>
                        </ul>                                                                    
                    </div>                               
                </a>  
           ';
    }
}

if (isset($_POST['getacabadostextPort'])) {

    $select = "SELECT * FROM hc_portfolio WHERE idPort ='" . $_POST['idPort'] . "'";
    $result = $link->query($select);
    $row = $result->fetch_array(MYSQLI_ASSOC);

    if ($result) {
        echo $row['acabadostextPort'];
    } else {
        echo "notok";
    }
}

if (isset($_POST['getdescriptionPort'])) {

    $select = "SELECT * FROM hc_portfolio WHERE idPort ='" . $_POST['idPort'] . "'";
    $result = $link->query($select);
    $row = $result->fetch_array(MYSQLI_ASSOC);

    if ($result) {
        echo $row['descriptionPort'];
    } else {
        echo "notok";
    }
}

if (isset($_POST['getdetailsport'])) {

    $select = "SELECT * FROM hc_portfolio WHERE idPort ='" . $_POST['idPort'] . "'";
    $result = $link->query($select);
    $row = $result->fetch_array(MYSQLI_ASSOC);

    if ($result) {
        echo $row['details'];
    } else {
        echo "notok";
    }
}

if (isset($_POST['getportdata'])) {

    $response = array();
    $response2 = array();
    $select = "SELECT * FROM hc_portfolio WHERE idPort ='" . $_POST['idPort'] . "'";
    $result = $link->query($select);

    if ($result) {
        $response['msg'] = "ok";
        $response['Port'] = $result->fetch_array(MYSQLI_ASSOC);
        unset($response['Port']['acabadostextPort']);
        unset($response['Port']['descriptionPort']);
        unset($response['Port']['details']);
        echo json_encode($response);
    } else {
        $response['msg'] = "notok";
        $response['error'] = $link->error;
        echo json_encode($response);
    }
}

if (isset($_POST['editproyectitles'])) {

    $select = "UPDATE hc_portfolio SET subtitlePort ='" . $_POST['subtitlePort'] . "', titlePort ='" . $_POST['titlePort'] . "' WHERE idPort ='" . $_POST['idPort'] . "'";
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

if (isset($_POST['addnewPort'])) {

    //** CARGAMOS LA IMAGEN DEL POST
    $directorio = opendir("../../../../assets/img/port/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/port/";
    $target_file = $target_dir . basename($_FILES["postimgPort"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["postimgPort"]["tmp_name"]);
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
    if ($_FILES["postimgPort"]["size"] > 500000) {
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
        if (move_uploaded_file($_FILES["postimgPort"]["tmp_name"], $target_file)) {
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
    $directorio = opendir("../../../../assets/img/port/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/port/";
    $target_file = $target_dir . basename($_FILES["bannerimgPost"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["bannerimgPost"]["tmp_name"]);
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
    if ($_FILES["bannerimgPost"]["size"] > 500000) {
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
        if (move_uploaded_file($_FILES["bannerimgPost"]["tmp_name"], $target_file)) {
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

    //** CARGAMOS LA IMAGEN DE BANNER
    $directorio = opendir("../../../../assets/img/port/");

    $filename = 1;
    while ($archivo = readdir($directorio)) {
        if (!is_dir($archivo)) {
            $filename++;
        }
    }
    $target_dir = "../../../../assets/img/port/";
    $target_file = $target_dir . basename($_FILES["fullwidthimgPort"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fullwidthimgPort"]["tmp_name"]);
    if ($check !== false) {
        $msg_logo .= " Imagen de Banner examinada con éxito.";
        $uploadOk = 1;
    } else {
        $msg_logo .= " La Imagen de Banner no es una imagen. <br/>";
        $box = "danger";
        $uploadOk = 2;
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
    }
    // Check file size
    if ($_FILES["fullwidthimgPort"]["size"] > 500000) {
        $msg_logo .= " La Imagen de Banner es demasiado grande.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        $uploadOk = 2;
    }
    // Allow certain file formats
    if ($imageFileType != "jpg") {
        $msg_logo .= " Solo se permite formato jpg para la Imagen de Banner.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        $uploadOk = 2;
    } else {
        $target_file = $target_dir . $filename . ".jpg";
        $bannerImage = $filename;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 2) {
        $msg_logo .= " No se logró actualizar la Imagen de Banner, intente de nuevo.<br/>";
        $box = "danger";
        $_SESSION['msg'] = $msg_logo;
        $_SESSION['box'] = $box;
        return;
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fullwidthimgPort"]["tmp_name"], $target_file)) {
            chmod($target_file, 0666);
            $msg_logo .= " Imagen de Banner cargada exitosamente.";
            $box = "primary";
        } else {
            $msg_logo .= " No se logró actualizar la Imagen de Banner.";
            $box = "danger";
            $_SESSION['msg'] = $msg_logo;
            $_SESSION['box'] = $box;
            return;
        }
    }

    $val_select = "INSERT INTO hc_portfolio(titlePort,subtitlePort,postimgPort,bannerimgPost,details,descriptionPort,acabadostextPort,fullwidthimgPort,statusPort) "
            . "VALUES ('" . $_POST['titlePort'] . "','" . $_POST['subtitlePort'] . "','" . $postImage . "','" . $headerImage . "','" . addslashes($_POST['details']) . "','" . addslashes($_POST['descriptionPort']) . "','" . addslashes($_POST['acabadostextPort']) . "','" . $bannerImage . "','1')";
    $val_result = $link->query($val_select) or die($link->error);

    $msg_menu = " Nuevo Poyecto agregado al portafolio. ";
    $box = "primary";
    $_SESSION['msg'] = $msg_menu;
    $_SESSION['box'] = $box;
}
?>