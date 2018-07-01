<?php
////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
error_reporting(E_ALL);
ini_set('display_errors', 1);


header('Content-type: application/javascript; charset=utf-8');
ob_start(); // Record output

require ("assets/scripts/conn.php");
$jsonp = array();

if ($_GET) {
    if ($_GET['meth'] == 'login') {

        $username = $_GET["username"];
        $password = $_GET["password"];

        $query = "SELECT * FROM usuario WHERE nombreUsuario = '$username'";
        $result = $conn->query($query);
        if (!$result)
            die($_GET['callback'] . '(' . "{'scriptResp' : 'userqueryFail'}" . ')');
        $rows = $result->num_rows;
        $result->data_seek(0);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $jsonp = $row;

        if (($rows != 0) && (strcmp($row["nombreUsuario"], $username) == 0) && ($row['passwordUsuario'] === $password)) {

            $jsonp['scriptResp'] = "match";
            $output = ob_get_contents();
            ob_end_clean();
            $jsonp['output'] = $output;
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
        $result = $conn->query($select) or die($_GET['callback'] . '(' . "{'scriptResp' : 'userexistqueryfail'}" . ')');
        $row_cnt = $result->num_rows;

        if ($row_cnt > 0) {
            $jsonp['scriptResp'] = "userAlreadyInDB";
            $output = ob_get_contents();
            ob_end_clean();
            $jsonp['output'] = $output;
            echo $_GET['callback'] . '(' . json_encode($jsonp) . ')';
        } else {
            $val_select = "INSERT INTO usuario("
                    . "passwordUsuario,nombreUsuario,idPerfil,phoneUsuario,fechaingresoUsuario,nombresUsuario,vehiculoUsuario,placaUsuario,statusUsuario,temaUsuario,panelUsuario"
                    . ")  VALUES  ('" .
                    $password . "','" . $username . "','0','" . $phone . "','" . date("Y-m-d") . "','" . $fullname . "','" . $vehicle . "','" . $plate . "','1','dark','users')";
            $val_result = $conn->query($val_select) or die($_GET['callback'] . '(' . "{'scriptResp' : 'insertuserFail'}" . ')');

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
