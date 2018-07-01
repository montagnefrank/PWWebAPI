<?php

/////////////////////////////////////////////////////////////////////////////// CONTACT CONTROL
/////////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


require ("../../scripts/conn.php");
session_start();

if (isset($_POST['readingMessage'])) {

    $val_select = "UPDATE hc_messages SET statusContact ='Read' WHERE idContact = '" . $_POST['idMessage'] . "'";
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
    $mes_select = "SELECT * FROM hc_messages WHERE statusContact != 'Gone'";
    $mes_result = $link->query($mes_select) or die($link->error);
    while ($mes_row = $mes_result->fetch_array(MYSQLI_BOTH)) {
        if ($mes_row['statusContact'] == 'New') {
            $newmail = 'mail-unread';
            $bar = 'mail-info';
        } else {
            $newmail = '';
            $bar = 'mail-success';
        }
        echo '
                <div class="mail-item ' . $newmail . ' ' . $bar . '">          
                    <div class="mail-user">' . $mes_row['nameContact'] . '</div>                                    
                    <a href="#" class="mail-text readmessage_btn">' . $mes_row['subjectContact'] . '</a>                                    
                    <div class="mail-date">' . $mes_row['dateContact'] . ', ' . $mes_row['timeContact'] . '</div>
                    <div class="hidethis_force idContact_cont"> ' . $mes_row['idContact'] . '</div>  
                    <div class="hidethis_force messageContact_cont"> ' . $mes_row['messageContact'] . '</div>  
                    <div class="hidethis_force emailContact_cont"> ' . $mes_row['emailContact'] . '</div>  
                    <div class="hidethis_force nameContact_cont"> ' . $mes_row['nameContact'] . '</div>  
                    <div class="hidethis_force subjectContact_cont"> ' . $mes_row['subjectContact'] . '</div>  
                    <div class="hidethis_force datetimeContact_cont"><span class="fa fa-clock-o"></span> ' . $mes_row['dateContact'] . ', ' . $mes_row['timeContact'] . '</div>  
                </div>
            ';
    }
}

if (isset($_POST['deleteMsg'])) {

    $val_select = "UPDATE hc_messages SET statusContact = 'Gone' WHERE idContact = '" . $_POST['idMessage'] . "'";
    $val_result = $link->query($val_select) or die($link->error);

    if ($val_result) {
        $msg_menu = " El mensaje ha sido eliminado Exitosamente ";
        echo $msg_menu;
    } else {
        $msg_menu = " No pudimos eliminar el mensaje. ";
        echo $msg_menu;
    }
}
?>