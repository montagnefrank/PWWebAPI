<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require ("conn.php");
require ("islogged.php");

session_start();
$user = $_SESSION["login"];
$passwd = $_SESSION["passwd"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}



if (isset($_POST['newtheme'])) {
    $update_theme = mysqli_query($link, "UPDATE tblusuario SET theme = '" . $_POST['theme'] . "' WHERE cpuser = '" . $user . "'");
    $msg_theme .= " Tema actualizado con éxito. ";
    $box = "primary";
} else {
    $msg_theme .= " No pudimos actualizar su tema, por favor intente nuevamente. ";
    $box = "danger";
}

$_SESSION['msg'] = $msg_theme;
$_SESSION['box'] = $box;
header("Location: ../main.php?panel=user_config.php");
?>