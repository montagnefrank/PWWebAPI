<?php

require ("conn.php"); /////////////////////////////////////////////////////////////////////////CONEXION A LA DB
require ("islogged.php"); ////////////////////////////////////////////////////////////////////VERIFICA LOGIN VALIDO

session_start();
$user = $_POST["user"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$result_validate = mysqli_query($link, "SELECT cppassword,idrol_user FROM tblusuario WHERE cpuser = '" . $user . "'");
$row_validate = mysqli_fetch_array($result_validate, MYSQLI_ASSOC);
$rol = $row_validate['idrol_user'];
if ($_POST["pass"] == $row_validate['cppassword']) {
    if ($rol != 3) {
        header("Location: ../main.php?panel=cot.php&uploadsr=true");
    } else {
        $_SESSION['msg'] = "Usuario no autorizado";
        $_SESSION['box'] = "primary";
        header("Location: ../main.php?panel=cot.php");
    }
} else {
    $_SESSION['msg'] = "Hubo un error validando sus datos, intente de nuevo";
    $_SESSION['box'] = "primary";
    header("Location: ../main.php?panel=cot.php");
}
mysqli_close($link);
?>