<?php

////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

function trackvisitor($page) {
    require ("conn.php");
    $tracking_page_name = $page;
    if (isset($_SERVER['HTTP_REFERER'])) {
        $ref = $_SERVER['HTTP_REFERER'];
    } else {
        $ref = "DIRECTO";
    }
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    $query = "INSERT INTO pw_track(tm, ref, agent, ip, tracking_page_name, domain, ip_value)    VALUES('" . date('Y-m-d') . "','$ref','$agent','$ip','$tracking_page_name','$host_name','" . date('H:i:s') . "');";
    $track = mysqli_query($conn, $query) or die("no inserto" . $query);
}
