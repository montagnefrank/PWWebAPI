<?php
////////////////////////////////////////////////////////////////////////////PENSAMIENTO  VIEW

$titles_select = "SELECT * FROM hc_misc";
$titles_result = $link->query($titles_select) or die($link->error);
$titles_row = array();
$titles_result->data_seek(0);
$titles_row[0] = $titles_result->fetch_array(MYSQLI_ASSOC);
?>
<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#" gotopanel='home' class="menu_btn">homecubic</a></li>
    <li><a >Website</a></li>
    <li class="active"> Pensamiento </li>
</ul>
<!-- BREADCRUMB --> 
<div class="page-content-wrap">   
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
    <div class="col-md-12">   
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="fa fa-cogs"></span> Pensamiento</h3>                                
                <ul class="panel-controls">
                    <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                </ul>                                   
            </div>
            <div class="panel-body">
                <textarea id="frasetext" rows="3" class="form-control textoquisom"><?php echo $titles_row[0]['fraseSite']; ?></textarea>
            </div>
            <div class="panel-footer">
                <button class="pull-right btn btn-primary" name="updatetexts"  type="submit" id="updatefrase" title="Edtar Textos">
                    <span class="beforeLoad" ><span class="fa fa-save"></span> Guardar cambios </span>
                    <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;">
                </button>
            </div>  
        </div>    
    </div> 
    <div class="col-md-6">
        <div class="col-md-12">
            <div class="panel panel-default">                            
                <div class="panel-body panel-body-image">
                    <img src="../assets/img/frase/firma.png" alt="Logo"/>
                    <a href="#" class="panel-body-inform">
                        <span class="fa fa-heart-o"></span>
                    </a>
                </div>
                <div class="panel-footer text-muted">
                    <span class="fa fa-comment-o"></span> Imagen de firma
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default" id="quitarpanel">
                <div class="panel-body">
                    <h3><span class="fa fa-picture-o"></span> Cambiar Firma <span data-toggle="tooltip" 
                                                                                  data-placement="top" 
                                                                                  title="400 x 100" 
                                                                                  class="pull-right fa fa-exclamation-circle"></span></h3>                                    
                    <p>Seleciona la imagen de  <code>firma de la frase</code> para reemplazar la actual</p>
                    <form action="<?php echo "assets/modules/" . $panel . "/control.php"; ?>" method="post" enctype="multipart/form-data">
                        <div style="margin-top: 10px; margin-bottom: 10px;margin-left: 20px;">
                            <input  class="fileinput btn-info" type="file" name="fileToUpload" id="fileToUpload" data-filename-placement="inside" title="Buscar imagen">
                        </div>
                </div>
                <div class="panel-footer">
                    <button class="pull-right btn btn-success" name="submitnewlogo"  type="submit"  id="submitnewlogo" data-toggle="tooltip" data-placement="right" title="Cambiar Logo">
                        <span class="beforeLoad" ><span class="fa fa-upload"></span> Cambiar firma </span>
                        <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                    </button>
                </div>
                </from>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">                            
            <div class="panel-body panel-body-image">
                <img src="../assets/img/frase/fondo.jpg" alt="Slide1"/>
                <a href="#" class="panel-body-inform">
                    <span class="fa fa-edit"></span>
                </a>
            </div>
            <div class="panel-body">
                <div class="panel panel-default">
                    <form id="slide1form" action="<?php echo "assets/modules/" . $panel . "/control.php"; ?>" method="post" enctype="multipart/form-data">
                        <div class="panel-heading">
                            <h6>Cambiar imagen <span data-toggle="tooltip" 
                                                     data-placement="top" 
                                                     title="1920 x 1080" 
                                                     class="pull-right fa fa-exclamation-circle"></span></h6>
                        </div>
                        <div class="panel-body">                                   
                            <p>Seleciona la imagen de  <code>fondo de la secci&oacute;n</code> para reemplazar la actual</p>
                            <div style="margin-top: 10px; margin-bottom: 10px;margin-left: 20px;">
                                <input name="slide1file" type="file" id="file-simple"/>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button class="pull-right btn btn-success" name="bgimagefrase"  type="submit"  id="bgimagefrase" data-toggle="tooltip" data-placement="right" title="Editar Slide">
                                <span class="beforeLoad" > Cambiar Fondo </span>
                                <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="panel-footer text-muted">
                <span class="fa fa-image"></span> Slider Principal
                <span class="fa fa-list"></span> 1
            </div>
        </div>

    </div>
</div>