<?php

////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header('Content-type: application/javascript; charset=utf-8');
ob_start(); // Record output

require ("assets/scripts/conn.php");
$jsonp = array();
$json = array();

if ($_POST) {
    // Update User Profile
    if ($_POST['meth'] == 'updateUser') {
        $fullname = $_POST["fullname"];
        $phone = $_POST["phone"];
        $vehicle = $_POST["vehicle"];
        $plate = $_POST["plate"];
        $username = $_POST["username"];
        $password = $_POST["pass"];
        $email = $_POST["email"];

        if ($_POST['uploadimage'] == 'true') {

            $target_dir = "assets/img/users/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

            // CHECK IF IS ACTUALLY AN IMAGE
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $json['ajaxStatus'] = 'error';
                $json['errorMsg'] = 'The file uploaded was not an image';
                echo json_encode($json);
                return;
            }

            // Check file size
            if ($_FILES["image"]["size"] > 1024000) {
                $json['ajaxStatus'] = 'error';
                $json['errorMsg'] = 'The file size is too big (1MB max)';
                echo json_encode($json);
                return;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg") {
                $json['ajaxStatus'] = 'error';
                $json['errorMsg'] = 'Only .JPG format allowed';
                echo json_encode($json);
                return;
            }

            // SAVE IMAGE TO SERVER
            $target_file = $target_dir . $username . ".jpg";
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                chmod($target_file, 0666);
                $json['ajaxStatus'] = 'ok';
                $json['message'] = 'Profile image upload success!';
            } else {
                $json['ajaxStatus'] = 'error';
                $json['errorMsg'] = 'Profile image upload error';
                echo json_encode($json);
                return;
            }
        }

        $val_select = "UPDATE usuario SET phoneUsuario = '" . $phone . "', nombresUsuario = '" . $fullname . "', vehiculoUsuario = '" . $vehicle . "', placaUsuario = '" . $plate . "', emailUsuario = '" . $email . "'";
        if ($_POST["updatePass"] == 'true') {
            $val_select .= " , passwordUsuario = '" . $password . "' ";
        }
        $val_select .= " WHERE nombreUsuario = '" . $username . "' ";
        $val_result = $link->query($val_select) or die("{'ajaxStatus' : 'error','errorMsg' : 'Query Died'}");

        if ($val_result) {

            $query = "SELECT * FROM usuario WHERE nombreUsuario = '$username'";
            $result = $link->query($query);
            if (!$result)
                die("{'scriptResp' : 'userqueryFail'}");
            $rows = $result->num_rows;
            $result->data_seek(0);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $json['userIntel'] = $row;

            /////////////////////////////////////////VEMOS SI TIENE IMAGEN CARGADA
            $isavatar = "assets/img/users/" . $username . ".jpg";
            if (file_exists($isavatar)) {
                $jsonp['userIntel']['userImg'] = $isavatar;
            } else {
                $jsonp['userIntel']['userImg'] = "default";
            }

            $json['ajaxStatus'] = 'ok';
            $json['message'] .= ' User update success!';
            echo json_encode($json);
            return;
        } else {
            $json['ajaxStatus'] = 'error';
            $json['errorMsg'] = 'User could not be loaded';
            echo json_encode($json);
            return;
        }
    }

    // FETCH VEHICLE MODELS
    if ($_POST['meth'] == 'updateModels') {
        $query = "SELECT * FROM vehiclemodel WHERE statusModel = '1'";
        $result = $link->query($query);
        if (!$result)
            die("{'ajaxStatus' : 'fail'}");
        $rows = $result->num_rows;
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $json['vehmods'][] = $row;
        }
        $json['ajaxStatus'] = 'ok';
        echo json_encode($json);
        return;
    }

    // FETCH ORDER HISTORY
    if ($_POST['meth'] == 'getOrders') {
//        $query = "SELECT * FROM pedidos WHERE idUsuario = '" . . "'";
        $result = $link->query($query);
        if (!$result)
            die("{'ajaxStatus' : 'fail'}");
        $rows = $result->num_rows;
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $json['vehmods'][] = $row;
        }
        $json['ajaxStatus'] = 'ok';
        echo json_encode($json);
        return;
    }

    //ADD NEW VEHICLE
    if ($_POST['meth'] == 'newVehicle') {

        $idUsuario = $_POST["userid"];
        $idModel = $_POST["modelVeh"];
        $plateVehicle = $_POST["plateVeh"];
        $nameVehicle = $_POST["nameVeh"];

        $val_select = "INSERT INTO vehicles ("
                . "idUsuario,idModel,lastwashedVehicle,plateVehicle,nameVehicle,statusVehicle"
                . ")  VALUES  ('" .
                $idUsuario . "','" . $idModel . "','new','" . $plateVehicle . "','" . $nameVehicle . "','1')";
        $val_result = $link->query($val_select) or die("{'ajaxStatus' : 'error', 'message' : 'Query Insert Failed'}");

        if ($val_result) {
            $json['ajaxStatus'] = 'ok';
            $json['message'] = 'New Vehicle Parked in your Garage';
            echo json_encode($json);
        } else {
            $json['ajaxStatus'] = 'error';
            $json['message'] = 'Could not park your car, try again';
            echo json_encode($json);
        }
        return;
    }

    // FETCH VEHICLES
    if ($_POST['meth'] == 'getVehs') {
        $idusuario = $_POST['idUsuario'];

        $query = "SELECT * FROM vehicles veh INNER JOIN vehiclemodel vm ON veh.idModel = vm.idModel WHERE statusVehicle = '1' AND idUsuario = '" . $idusuario . "'";
        $result = $link->query($query);
        if (!$result)
            die("{'ajaxStatus' : 'fail'}");
        $rows = $result->num_rows;
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $json['vehicles'][] = $row;
        }
        $json['ajaxStatus'] = 'ok';
        echo json_encode($json);
        return;
    }

    // DELETE VEHICLE
    if ($_POST['meth'] == 'deleteVeh') {
        $idveh = $_POST['idVehicle'];

        $val_select = "DELETE FROM vehicles WHERE idVehicle = '" . $idveh . "'";
        $val_result = $link->query($val_select) or die($link->error);

        $json['ajaxStatus'] = 'ok';
        echo json_encode($json);
        return;
    }

    //ADD NEW ORDER
    if ($_POST['meth'] == 'newOrder') {

        $idUsuario = $_POST["idUser_newo"];
        $idVeh = $_POST["veh_newo"];
        $gps = $_POST["gps_newo"];
        $price = $_POST["price_newo"];
        $payment = $_POST["payment_newo"];

        $val_select = "INSERT INTO pedidos ("
                . "idUsuario,idVehicle,gpsPedido,precioPedido,pagoPedido,fechaPedido,horaPedido,statusPedido"
                . ")  VALUES  ('" .
                $idUsuario . "','" . $idVeh . "','" . $gps . "','" . $price . "','" . $payment . "','" . date("Y-m-d") . "','" . date("h-i-s") . "','new')";
        $val_result = $link->query($val_select) or die("{'ajaxStatus' : 'error', 'message' : 'Query Insert Failed'}");

        if ($val_result) {
            $json['ajaxStatus'] = 'ok';
            $json['message'] = 'New order placed';
            echo json_encode($json);
        } else {
            $json['ajaxStatus'] = 'error';
            $json['message'] = 'Could not place your order, try again';
            echo json_encode($json);
        }
        return;
    }
}

if ($_GET) {

    /////////////////////////////////////////////////LOGIN METHOD
    if ($_GET['meth'] == 'login') {

        $username = $_GET["username"];
        $password = $_GET["password"];

        $query = "SELECT * FROM usuario WHERE nombreUsuario = '$username'";
        $result = $link->query($query);
        if (!$result)
            die($_GET['callback'] . '(' . "{'scriptResp' : 'userqueryFail'}" . ')');
        $rows = $result->num_rows;
        $result->data_seek(0);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $jsonp['userIntel'] = $row;

        if (($rows != 0) && (strcmp($row["nombreUsuario"], $username) == 0) && ($row['passwordUsuario'] === $password)) {

            /////////////////////////////////////////VEMOS SI TIENE IMAGEN CARGADA
            $isavatar = "assets/img/users/" . $username . ".jpg";
            if (file_exists($isavatar)) {
                $jsonp['userIntel']['userImg'] = $isavatar;
            } else {
                $jsonp['userIntel']['userImg'] = "default";
            }

            $jsonp['scriptResp'] = "match";
            $output = ob_get_contents();
            ob_end_clean();
            $jsonp['output'] = $output;
            unset($jsonp['userIntel']['passwordUsuario']);
            echo $_GET['callback'] . '(' . json_encode($jsonp) . ')';
        } else {
            $jsonp['scriptResp'] = "noMatch";
            $output = ob_get_contents();
            ob_end_clean();
            $jsonp['output'] = $output;
            echo $_GET['callback'] . '(' . json_encode($jsonp) . ')';
        }
    }

    if ($_GET['meth'] == 'reg') {

        $fullname = $_GET["fullname"];
        $phone = $_GET["phone"];
        $vehicle = $_GET["vehicle"];
        $plate = $_GET["plate"];
        $username = $_GET["username"];
        $password = $_GET["password"];

        $select = "SELECT nombreUsuario FROM usuario WHERE nombreUsuario = '" . $username . "';";
        $result = $link->query($select) or die($_GET['callback'] . '(' . "{'scriptResp' : 'userexistqueryfail'}" . ')');
        $row_cnt = $result->num_rows;

        if ($row_cnt > 0) {
            $jsonp['scriptResp'] = "userAlreadyInDB";
            $output = ob_get_contents();
            ob_end_clean();
            $jsonp['output'] = $output;
            echo $_GET['callback'] . '(' . json_encode($jsonp) . ')';
        } else {
            $val_select = "INSERT INTO usuario("
                    . "passwordUsuario,nombreUsuario,idPerfil,phoneUsuario,fechaingresoUsuario,nombresUsuario,vehiculoUsuario,placaUsuario,statusUsuario,temaUsuario,panelUsuario,emailUsuario"
                    . ")  VALUES  ('" .
                    $password . "','" . $username . "','1','" . $phone . "','" . date("Y-m-d") . "','" . $fullname . "','" . $vehicle . "','" . $plate . "','1','dark','users','Empty')";
            $val_result = $link->query($val_select) or die($_GET['callback'] . '(' . "{'scriptResp' : 'insertuserFail'}" . ')');

            if ($val_result) {
                $jsonp['scriptResp'] = "regsuccess";
                $jsonp['user']['fullname'] = $fullname;
                $jsonp['user']['phone'] = $phone;
                $jsonp['user']['vehicle'] = $vehicle;
                $jsonp['user']['plate'] = $plate;
                $jsonp['user']['username'] = $username;

                $output = ob_get_contents();
                ob_end_clean();
                $jsonp['output'] = $output;
                echo $_GET['callback'] . '(' . json_encode($jsonp) . ')';
            } else {

                $jsonp['scriptResp'] = "failuserReg";
                $output = ob_get_contents();
                ob_end_clean();
                $jsonp['output'] = $output;
                echo $_GET['callback'] . '(' . json_encode($jsonp) . ')';
            }
        }
    }
} else {
    $jsonp['scriptResp'] = "silent";
    $output = ob_get_contents();
    ob_end_clean();
    $jsonp['output'] = $output;
    echo $_GET['callback'] . '(' . json_encode($jsonp) . ')';
}


