<?php
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

if (isset($_POST["submit"])) {
    $update_wgs = mysqli_query($link, "UPDATE tblusuario SET wg_lpb = '" . $_POST['wg_lpb'] . "' , wg_ord = '" . $_POST['wg_ord'] . "' , wg_pvo = '" . $_POST['wg_pvo'] . "' , wg_par = '" . $_POST['wg_par'] . "' , wg_ecf = '" . $_POST['wg_ecf'] . "' , wg_reh = '" . $_POST['wg_reh'] . "' , wg_odi = '" . $_POST['wg_odi'] . "' , wg_pod = '" . $_POST['wg_pod'] . "' , wg_auo = '" . $_POST['wg_auo'] . "' , wg_irr = '" . $_POST['wg_irr'] . "' , wg_tco = '" . $_POST['wg_tco'] . "' , wg_nol = '" . $_POST['wg_nol'] . "' WHERE cpuser = '" . $user . "'");
    $msg_wg .= " Widgets actualizados con éxito. Revisa tu home y verás los cambios";
    $box = "primary";
}
$_SESSION['msg'] = $msg_wg;
$_SESSION['box'] = $box;
header("Location: ../main.php?panel=user_config.php");
?>