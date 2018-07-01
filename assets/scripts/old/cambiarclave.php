<?php
////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require ("conn.php");

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
$select_compare = mysqli_query($link, "SELECT cppassword FROM tblusuario WHERE cpuser = '" . $user . "'");
$row_compare = mysqli_fetch_row($select_compare);
$compare = $row_compare[0];

if (isset($_POST["submit"])) {
    if ($_POST["oldpass"] == $compare) {
        $update_pass = mysqli_query($link, "UPDATE tblusuario SET cppassword = '" . $_POST['newpass'] . "' WHERE cpuser = '" . $user . "'");
        $msg_pass .= " Contraseña cambiada con éxito. ";
        $box = "primary";
    } else {
        $msg_pass .= " No pudimos validar su contraseña anterior, por favor ingresela nuevamente. ";
        $box = "danger";
    }
}
$_SESSION['msg'] = $msg_pass;
$_SESSION['box'] = $box;
header("Location: ../main.php?panel=user_config.php");
?>