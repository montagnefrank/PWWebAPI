<?php
////////////////////////////////////////////////////////////////////////////CONTACT  VIEW
?>
<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#" gotopanel='home' class="menu_btn">homecubic</a></li>
    <li><a >Website</a></li>
    <li class="active"> Mensajes </li>
</ul>
<!-- BREADCRUMB --> 
<div class="page-title">                    
    <h2><span class="fa fa-list"></span> Data de loslectores</h2>
</div> 
<div class="page-content-wrap">   
    <div class="row customalert hidethis">
        <div class="col-md-12">
            <div class="widget widget-info widget-item-icon">
                <div class="widget-item-left">
                    <span class="fa fa-exclamation"></span>
                </div>
                <div class="widget-data">
                    <div class="widget-title">Notificación</div>
                    <div class="widget-subtitle">
                        <div role="alert" class="customalert_text">
                            Mensaje de error
                        </div>
                    </div>
                </div>
                <div class="widget-controls">                                
                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                </div>                             
            </div>
        </div>
    </div>
    <?php
    if (isset($_SESSION['msg'])) {
        echo '
                <div class="row notificactionbox">
                    <div class="col-md-12">
                        <div class="widget widget-';
        echo $_SESSION['box'];
        echo ' widget-item-icon">
                            <div class="widget-item-left">
                                <span class="fa fa-exclamation"></span>
                            </div>
                            <div class="widget-data">
                                <div class="widget-title">Notificación</div>
                                <div class="widget-subtitle">
                                    <div role="alert">
                                        ' . $_SESSION['msg'] . '
                                    </div>
                                </div>
                            </div>
                            <div class="widget-controls">                                
                                <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                            </div>                             
                        </div>
                    </div>
                </div>
        ';
        unset($_SESSION['msg']);
    }
    ?>
    <div class="col-md-8">
        <div class="col-md-12 messages_panel">
            <div class="panel panel-default">
                <div class="panel-heading mail">
                    <div class="mail-item mail-primary">          
                        <div class="mail-user">Nombre</div>                                    
                        <a href="#" class="mail-text readmessage_btn">Asunto</a>                                    
                        <div class="mail-date">Fecha, Hora</div>
                    </div>
                </div>
                <div class="panel-body mail shoMsgs_panel">
                    <!--LOS MENSAJES SE MUESTRAN DE MANERA DINAMICA-->
                </div>                          
            </div>
        </div>
        <div class="col-md-12 readmessage_panel hidethis">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title"><span class="readmsgName">John Doe</span> <small class="readmsgEmail">johndoe@domain.com</small></h3>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-default deleteMsg_btn"><span class="fa fa-trash-o"></span><span class="readmsgId hidethis_force"></span></button>                                    
                    </div>
                </div>
                <div class="panel-body">
                    <h3><span class="readmsgSubject">Re: Product development</span> <small class="pull-right text-muted readmsgTime"></small></h3>
                    <p class="readmsgEbook"></p>
                    <p class="readmsgMsg"></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 goback_btn hidethis">                        
        <a href="#" class="tile tile-primary tile-valign ">
            <span>Regresar </span> <span class="fa fa-reply"></span>
            <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;width: 100%;" />
        </a>                        
    </div> 
</div>