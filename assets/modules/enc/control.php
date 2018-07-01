<?php

/////////////////////////////////////////////////////////////////////////////// ENCUESTA CONTROL
/////////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


require ("../../scripts/conn.php");
session_start();

if (isset($_POST['readingMessage'])) {

    $val_select = "UPDATE hc_encuesta SET statusEnc ='Read' WHERE idEnc = '" . $_POST['idMessage'] . "'";
    $val_result = $link->query($val_select) or die($link->error);

    if ($val_result) {
        $msg_menu = " Se ha cambiado la encuesta a LEIDO ";
        echo $msg_menu;
    } else {
        $msg_menu = " No pudimos actualziar la encuesta. ";
        echo $msg_menu;
    }
}

if (isset($_POST['getMsgs'])) {
    $mes_select = "SELECT * FROM hc_encuesta WHERE statusEnc != 'Gone'";
    $mes_result = $link->query($mes_select) or die($link->error);
    while ($mes_row = $mes_result->fetch_array(MYSQLI_BOTH)) {
        if ($mes_row['statusEnc'] == 'New') {
            $newmail = 'mail-unread';
            $bar = 'mail-info';
        } else {
            $newmail = '';
            $bar = 'mail-success';
        }
        echo '
                <div class="mail-item ' . $newmail . ' ' . $bar . '">          
                    <div class="mail-user">' . $mes_row['nameEnc'] . '</div>                                    
                    <a href="#" class="mail-text readmessage_btn">' . $mes_row['emailEnc'] . '</a>                                    
                    <div class="mail-date">' . $mes_row['dateEnc'] . ', ' . $mes_row['horaEnc'] . '</div>
                    <div class="hidethis_force idEnc_cont"> ' . $mes_row['idEnc'] . '</div>  
                    <div class="hidethis_force datetimeEnc_cont"><span class="fa fa-clock-o"></span> ' . $mes_row['dateEnc'] . ', ' . $mes_row['horaEnc'] . '</div>  
                    <div class="hidethis_force nameEnc_cont"> ' . $mes_row['nameEnc'] . '</div>  
                    <div class="hidethis_force emailEnc_cont"> ' . $mes_row['emailEnc'] . '</div>  
                    <div class="hidethis_force phoneEnc_cont"> ' . $mes_row['phoneEnc'] . '</div>  
                    <div class="hidethis_force addrEnc_cont"> ' . $mes_row['addrEnc'] . '</div>  
                    <div class="hidethis_force cityEnc_cont"> ' . $mes_row['cityEnc'] . '</div>  
                    <div class="hidethis_force edoEnc_cont"> ' . $mes_row['edoEnc'] . '</div>  
                    <div class="hidethis_force detEnc_cont"> ' . $mes_row['detEnc'] . '</div>  
                    <div class="hidethis_force pronameEnc_cont"> ' . $mes_row['pronameEnc'] . '</div>  
                    <div class="hidethis_force tipoEnc_cont"> ' . $mes_row['tipoEnc'] . '</div>  
                    <div class="hidethis_force timeEnc_cont"> ' . $mes_row['timeEnc'] . '</div>  
                    <div class="hidethis_force presEnc_cont"> ' . $mes_row['presEnc'] . '</div>  
                    <div class="hidethis_force usoEnc_cont"> ' . $mes_row['usoEnc'] . '</div>  
                    <div class="hidethis_force zonaEnc_cont"> ' . $mes_row['zonaEnc'] . '</div>  
                    <div class="hidethis_force specsEnc_cont"> ' . $mes_row['specsEnc'] . '</div>  
                    <div class="hidethis_force fundEnc_cont"> ' . $mes_row['fundEnc'] . '</div>  
                    <div class="hidethis_force sotaEnc_cont"> ' . $mes_row['sotaEnc'] . '</div>  
                    <div class="hidethis_force estEnc_cont"> ' . $mes_row['estEnc'] . '</div>  
                    <div class="hidethis_force camiEnc_cont"> ' . $mes_row['camiEnc'] . '</div>  
                    <div class="hidethis_force comentEnc_cont"> ' . $mes_row['comentEnc'] . '</div>   
                </div>
            ';
    }
}

if (isset($_POST['deleteMsg'])) {

    $val_select = "UPDATE hc_encuesta SET statusEnc = 'Gone' WHERE idEnc = '" . $_POST['idMessage'] . "'";
    $val_result = $link->query($val_select) or die($link->error);

    if ($val_result) {
        $msg_menu = " La encuesta ha sido eliminado Exitosamente ";
        echo $msg_menu;
    } else {
        $msg_menu = " No pudimos eliminar La encuesta. ";
        echo $msg_menu;
    }
}
?>