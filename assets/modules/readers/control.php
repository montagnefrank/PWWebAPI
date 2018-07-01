<?php

/////////////////////////////////////////////////////////////////////////////// CONTACT CONTROL
/////////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


require ("../../scripts/conn.php");
session_start();

if (isset($_POST['readingMessage'])) {

    $val_select = "UPDATE hc_descargados SET statusLector ='Read' WHERE idLector = '" . $_POST['idMessage'] . "'";
    $val_result = $link->query($val_select) or die($link->error);

    if ($val_result) {
        $msg_menu = " Se ha cambiado el mensaje a LEIDO ";
        echo $msg_menu;
    } else {
        $msg_menu = " No pudimos actualziar el mensaje. ";
        echo $msg_menu;
    }
}

if (isset($_POST['getMsgs'])) {
    $mes_select = "SELECT * FROM hc_descargados WHERE statusLector != 'Gone'";
    $mes_result = $link->query($mes_select) or die($link->error);
    while ($mes_row = $mes_result->fetch_array(MYSQLI_BOTH)) {
        if ($mes_row['statusLector'] == 'New') {
            $newmail = 'mail-unread';
            $bar = 'mail-info';
        } else {
            $newmail = '';
            $bar = 'mail-success';
        }
        echo '
                <div class="mail-item ' . $newmail . ' ' . $bar . '">          
                    <div class="mail-user">' . $mes_row['nameLector'] . '</div>                                    
                    <a href="#" class="mail-text readmessage_btn">' . $mes_row['paisLector'] . '</a>                                    
                    <div class="mail-date">' . $mes_row['dateLector'] . ', ' . $mes_row['timeLector'] . '</div>
                    <div class="hidethis_force idContact_cont"> ' . $mes_row['idLector'] . '</div>  
                    <div class="hidethis_force messageContact_cont"> ' . $mes_row['paisLector'] . '</div>  
                    <div class="hidethis_force emailContact_cont"> ' . $mes_row['emailLector'] . '</div>  
                    <div class="hidethis_force nameContact_cont"> ' . $mes_row['nameLector'] . '</div>  
                    <div class="hidethis_force subjectContact_cont"> ' . $mes_row['perfilLector'] . '</div>  
                    <div class="hidethis_force ebookContact_cont">Libro descargado: ' . $mes_row['ebookLector'] . '</div>  
                    <div class="hidethis_force datetimeContact_cont"><span class="fa fa-clock-o"></span> ' . $mes_row['dateLector'] . ', ' . $mes_row['timeLector'] . '</div>  
                </div>
            ';
    }
}

if (isset($_POST['deleteMsg'])) {

    $val_select = "UPDATE hc_descargados SET statusLector = 'Gone' WHERE idLector = '" . $_POST['idMessage'] . "'";
    $val_result = $link->query($val_select) or die($link->error);

    if ($val_result) {
        $msg_menu = " El mensaje ha sido eliminado Exitosamente ";
        echo $msg_menu;
    } else {
        $msg_menu = " No pudimos eliminar el mensaje. ";
        echo $msg_menu;
    }
}

if (isset($_POST['addnewReader'])) {

    function sanitize($string) {
        $string = str_replace(' ', '--', $string);
        $string = str_replace('.', '-DOT-', $string);
        $string = str_replace('@', '-AT-', $string);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }

    $name = str_replace('--', ' ', sanitize($_POST['name']));
    $subject = str_replace('--', ' ', sanitize($_POST['perfil']));
    $email = str_replace('-DOT-', '.', str_replace('-AT-', '@', sanitize($_POST['email'])));
    $message = str_replace('--', ' ', sanitize($_POST['pais']));
    $ebook = str_replace('--', ' ', sanitize($_POST['ebook']));

    $query_message = "INSERT INTO hc_descargados (paisLector,emailLector,nameLector,perfilLector,dateLector,statusLector,timeLector,ebookLector) "
            . "VALUES ('" . $message . "','" . $email . "','" . $name . "','" . $subject . "','" . date('Y-m-d') . "','New','" . date('H:i:s') . "','" . $ebook . "')";
    $result_message = mysqli_query($link, $query_message);
    if ($result_message) {
        $response['msg'] = "success";
        echo json_encode($response);
    } else {
        $response['msg'] = "No pudimos enviar tu mensaje";
        echo json_encode($response);
    }
}
?>