<?php
////////////////////////////////////////////////////////////////////////////PORTFOLIO  VIEW
?>
<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#" gotopanel='home' class="menu_btn">homecubic</a></li>
    <li><a >Website</a></li>
    <li class="active"> Portafolio </li>
</ul>
<!-- BREADCRUMB --> 
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
    <div class="row">
        <div class="col-md-4 addnewbtn">                        
            <a href="#" class="tile tile-info tile-valign "><span class="fa fa-plus-square"></span></a>                        
        </div> 
        <div class="col-md-4 showlistbtn hidethis">                        
            <a href="#" class="tile tile-info tile-valign "><span class="fa fa-list-ul"></span></a>                        
        </div> 
        <div class="col-md-4 newproject hidethis savenewproject">                        
            <a href="#" class="tile tile-success tile-valign "><span class="fa fa-save"></span><img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;width: 100%;" /></a>                        
        </div> 
        <div class="col-md-4 editproject hidethis deleteproject">                        
            <a href="#" class="tile tile-danger tile-valign "><span class="fa fa-times-circle-o"></span><img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;width: 100%;" /></a>                        
        </div> 
    </div>

    <?php //**VENTANA DE LISTADO DE PROYECTOS ?>
    <div class="row projectlist">
        <div class="col-md-6">
            <div class="col-md-8">
                <div class="panel panel-default">                            
                    <div class="panel-body panel-body-image">
                        <img src="../assets/img/port/custom.jpg" alt="Logo"/>
                        <a href="#" class="panel-body-inform">
                            <span class="fa fa-heart-o"></span>
                        </a>
                    </div>
                    <div class="panel-footer text-muted">
                        <span class="fa fa-comment-o"></span> Fondo de custom
                    </div>
                </div>
            </div>
            <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3><span class="fa fa-picture-o"></span> Cambiar imagen de Custom <span data-toggle="tooltip" 
                              data-placement="top" 
                              title="1920 x 1080" 
                              class="pull-right fa fa-exclamation-circle"></span></h3>                                    
                    <input  class="fileinput btn-info" type="file" name="uploadnewcustomImg" id="uploadnewcustomImg" data-filename-placement="inside" title="Buscar Imagen">
                    <button class="pull-right btn btn-success" name="uploadnewcustomImgbtn"  type="submit"  id="uploadnewcustomImgbtn" data-toggle="tooltip" data-placement="right" title="Imagen Custom">
                        <span class="beforeLoad" ><span class="fa fa-upload"></span> Cambiar imagen </span>
                        <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                    </button>
                </div>
            </div>
        </div>
        </div>
        

        <?php
        $selectportf = "SELECT * FROM hc_portfolio ";
        $resultportf = $link->query($selectportf) or die($link->error);
        while ($rowportf = $resultportf->fetch_array(MYSQLI_BOTH)) {
            if ($rowportf[9] == 1) {
                $checked = 'on';
                $awe = 'fa-check';
            } else {
                $checked = 'off';
                $awe = 'fa-times';
            }
            echo ' 
            <div class="col-md-6">
                <div class="panel panel-default">                            
                    <div class="panel-body panel-body-image">
                        <img src="../assets/img/port/' . $rowportf[3] . '.jpg" alt="Slide1" style="height: 300px;"/>
                        <a href="#" class="panel-body-inform panel-body-inform-' . $checked . '">
                            <span class="fa ' . $awe . '"></span>
                        </a>
                    </div>
                    <div class="panel-body">
                        <h3>' . $rowportf[1] . ' &mdash; ' . $rowportf[2] . '</h3>
                        <div class="hidethis_force idPortContainer">' . $rowportf[0] . '</div>
                    </div>
                    <div class="panel-footer text-muted">
                        <button class="pull-right btn btn-success editprojectbtn" name="slide1newimg"  type="submit"  id="slide1newimg" data-toggle="tooltip" data-placement="right" title="Editar Portafolio">
                            <span class="beforeLoad" > Editar Proyecto </span>
                            <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                        </button>
                    </div>
                    
                </div>

            </div>
           ';
        }
        ?>

    </div>

    <?php //**VENTANA DE EDITAR PROYECTO ?>
    <div class="row editproject hidethis ">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="col-md-6">
                        <h2><span class="fa fa-edit"></span> Editar Proyecto</h2>
                    </div>
                    <div class="col-md-6">
                        <label class="switch pull-right editporjectcheckbox">
                            <input type="checkbox" class="switch" name="1_check"  value="1" checked="checked" />
                            <span></span>
                        </label>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-8">
                        <div class="hidethis_force editidPortContainer"></div>
                        <h4>Titulo:</h4>
                        <input name="editTitlePort" id="editTitlePort" type="text" class="form-control" value="" placeholder="Ingresa el texto" />   
                        <h4 class="pushtop">Descripci&oacute;n:</h4>
                        <input name="editSubtitlePort" id="editSubtitlePort" type="text" class="form-control" value="" placeholder="Ingresa el texto" />
                        <button class="pull-right btn btn-success pushtop " name="editTitlesPortBtn"  type="submit"  id="editTitlesPortBtn" data-toggle="tooltip" data-placement="right" title="editar titulos">
                            <span class="beforeLoad" ><span class="fa fa-save"></span> Editar </span>
                            <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                        </button>
                        <h4 class="pushtop32">Imagenes del slider</h4>
                        <div class="gallery " id="links">
                            <div class="row imgsliderContainer">

                            </div>
                            <div class="col-md-8">
                                <div class="panel panel-default" id="quitarpanel">
                                    <div class="panel-body">                                   
                                        <form action="<?php echo "assets/modules/" . $panel . "/control.php"; ?>" method="post" enctype="multipart/form-data">
                                            <h3><span class="fa fa-image"></span> Agregar nueva imagen <span data-toggle="tooltip" 
                              data-placement="top" 
                              title="800 x 800" 
                              class="pull-right fa fa-exclamation-circle"></span></h3>
                                            <div style="margin-top: 10px; margin-bottom: 10px;margin-left: 20px;">
                                                <input name="newsliderimgPort" type="file" id="newsliderimgPort"/>
                                                <button class="pull-right btn btn-success" name="newsliderimgPortbtn"  type="button"  id="newsliderimgPortbtn" data-toggle="tooltip" data-placement="right" title="Agregar nueva imagen">
                                                    <span class="beforeLoad" ><span class="fa fa-upload"></span> Cargar </span>
                                                    <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                                                </button>
                                            </div>
                                    </div>
                                    </from>
                                </div>
                            </div>
                        </div>
                        <h4 class="pushtop32">Slider de planos</h4>
                        <div class="gallery " id="links">
                            <div class="row planosSliderContainer">

                            </div>
                            <div class="col-md-8">
                                <div class="panel panel-default" id="quitarpanel">
                                    <div class="panel-body">                                   
                                        <form action="<?php echo "assets/modules/" . $panel . "/control.php"; ?>" method="post" enctype="multipart/form-data">
                                            <h3><span class="fa fa-building"></span> Agregar nuevo plano <span data-toggle="tooltip" 
                              data-placement="top" 
                              title="1920 x 1080" 
                              class="pull-right fa fa-exclamation-circle"></span></h3>
                                            <div style="margin-top: 10px; margin-bottom: 10px;margin-left: 20px;">
                                                <input name="newplanosimgPort" type="file" id="newplanosimgPort"/>
                                                <button class="pull-right btn btn-success" name="newplanosimgPortbtn"  type="button"  id="newplanosimgPortbtn" data-toggle="tooltip" data-placement="right" title="Agregar Plano">
                                                    <span class="beforeLoad" ><span class="fa fa-upload"></span> Cargar </span>
                                                    <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                                                </button>
                                            </div>
                                    </div>
                                    </from>
                                </div>
                            </div>
                        </div>
                        <h4 class="pushtop32">Listado de Acabados</h4>
                        <div class="gallery " id="links">
                            <div class="row acabadosSliderContainer">

                            </div>
                            <div class="col-md-8">
                                <div class="panel panel-default" id="quitarpanel">
                                    <div class="panel-body">                                   
                                        <form action="<?php echo "assets/modules/" . $panel . "/control.php"; ?>" method="post" enctype="multipart/form-data">
                                            <h3><span class="fa fa-th-large"></span> Agregar nuevo Acabado <span data-toggle="tooltip" 
                              data-placement="top" 
                              title="600 x 600" 
                              class="pull-right fa fa-exclamation-circle"></span></h3>
                                            <div style="margin-top: 10px; margin-bottom: 10px;margin-left: 20px;">
                                                <input name="newacabadosimgPort" type="file" id="newacabadosimgPort"/>
                                                <button class="pull-right btn btn-success" name="newacabadosimgPortbtn"  type="button"  id="newacabadosimgPortbtn" data-toggle="tooltip" data-placement="right" title="Cambiar Logo">
                                                    <span class="beforeLoad" ><span class="fa fa-upload"></span> Cargar </span>
                                                    <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                                                </button>
                                            </div>
                                    </div>
                                    </from>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h4>Imagen de Cabecera: <span data-toggle="tooltip" 
                              data-placement="top" 
                              title="1920 x 1080" 
                              class="pull-right fa fa-exclamation-circle"></span></h4>
                        <div class="block push-up-10 headerimage">
                            <img src="../assets/img/port/11.jpg" style="width: 100%;" />
                        </div>
                        <div class="col-md-12 hidethis uploadheader">
                            <div class="panel panel-default">
                                <div class="panel-body">                                   
                                    <input name="newheaderimgPort" type="file" id="newheaderimgPort"/>
                                    <button class="pull-right btn btn-success" name="newheaderimgPortbtn"  type="button"  id="newheaderimgPortbtn" data-toggle="tooltip" data-placement="right" title="Cambiar Imagen de Cabecera">
                                        <span class="beforeLoad" ><span class="fa fa-upload"></span> Subir </span>
                                        <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                                    </button>
                                </div>
                            </div>
                        </div>      
                        <h4>Banner final: <span data-toggle="tooltip" 
                              data-placement="top" 
                              title="1920 x 700" 
                              class="pull-right fa fa-exclamation-circle"></span></h4>
                        <div class="block push-up-10 bannerimage">
                            <img src="../assets/img/port/15.jpg" style="width: 100%;" />
                        </div> 
                        <div class="col-md-12 uploadbannerimage hidethis">
                            <div class="panel panel-default">
                                <div class="panel-body">                                   
                                    <input name="newbannerimgPort" type="file" id="newbannerimgPort"/>
                                    <button class="pull-right btn btn-success" name="newbannerimgPortbtn"  type="submit"  id="newbannerimgPortbtn" data-toggle="tooltip" data-placement="right" title="Cambiar Imagen de banner">
                                        <span class="beforeLoad" ><span class="fa fa-upload"></span> Subir </span>
                                        <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                                    </button>
                                </div>
                            </div>
                        </div>
                        <h4>Imagen de Post: <span data-toggle="tooltip" 
                              data-placement="top" 
                              title="1920 x 1080" 
                              class="pull-right fa fa-exclamation-circle"></span></h4>
                        <div class="block push-up-10 postimage">
                            <img src="../assets/img/port/05.jpg" style="width: 100%;" />
                        </div> 
                        <div class="col-md-12 hidethis uploadpostimage">
                            <div class="panel panel-default" id="quitarpanel">
                                <div class="panel-body">                                   
                                    <input name="newpostimgPort" type="file" id="newpostimgPort"/>
                                    <button class="pull-right btn btn-success" name="newpostimgPortbtn"  type="submit"  id="newpostimgPortbtn" data-toggle="tooltip" data-placement="right" title="Cambiar imagen de Publicacion">
                                        <span class="beforeLoad" ><span class="fa fa-upload"></span> Subir </span>
                                        <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6 pushtop32">
                            <h4>Detalles y Planos:</h4>
                            <textarea class="summernote" rows="6" id="edithtmldetalles"></textarea>
                        </div>
                        <div class="col-md-6 pushtop32">
                            <h4>Texto para Acabados</h4>
                            <textarea class="summernote" rows="6" id="edithtmlacabados"></textarea>
                        </div>
                        <div class="col-md-12 pushtop32">
                            <h4>Resumen del proyecto:</h4>
                            <textarea class="summernote" rows="6" id="edithtmlresumen"></textarea>
                        </div>
                        <div class="col-md-4 saveedithtmlboxes">                        
                            <a href="#" class="tile tile-success tile-valign ">
                                <span class="fa fa-save"></span>
                                <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;width: 100%;" />
                            </a>                        
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php //**VENTANA DE AGREGAR NUEVO PROYECTO ?>
    <div class="row newproject hidethis ">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><span class="fa fa-edit"></span> Nuevo Proyecto</h2>
                </div>
                <div class="panel-body">
                    <div class="col-md-8">
                        <h4>Titulo:</h4>
                        <input name="newPortTitle" id="newPortTitle" type="text" class="form-control" value="" placeholder="Ingresa el texto" required="required"/>   
                        <h4 class="pushtop">Descripci&oacute;n:</h4>
                        <input type="text" class="form-control" name="newPortSubtitle" id="newPortSubtitle" value="" placeholder="Ingresa el texto" required="required"/>

                        <div class="col-md-6 pushtop32">
                            <h4>Detalles y Planos:</h4>
                            <textarea class="summernote" id="htmldetalles" rows="6"><p><strong>3</strong>&nbsp;bedroom<br><strong>3</strong>&nbsp;bathroom<br><b>3</b>floors</p><p><strong>Surface:</strong>&nbsp;1920 ft<sup>2</sup> (178 m<sup>2</sup>)<br><strong>Footprint:</strong> 16’ x 40’ (4,9 x 12,2 m)<br><strong>Height:</strong>&nbsp;28’-6” (9,7 m)</p><h2 style="font-family:'Open Sans';font-weight:300;color:#595959;">Price</h2><p><strong>Base Price:</strong> $315,000 USD*<br><strong>Foundation*:</strong> $12,000<br><strong>Roof:</strong> $10,000<br><strong>Assembly:</strong> $20,000<br><strong>TOTAL:</strong> $357,000</p></textarea>
                        </div>
                        <div class="col-md-6 pushtop32">
                            <h4>Texto para Acabados</h4>
                            <textarea class="summernote" rows="6" id="htmlacabados">Choose from a variety of top quality surfaces and finishes to add a personal touch your your MekaWorld modular home. Each of these finishes comes standard in the price of your new home.</textarea>
                        </div>
                        <div class="col-md-12 pushtop32">
                            <h4>Resumen del proyecto:</h4>
                            <textarea class="summernote" rows="6" id="htmlresumen"><p class="text-highlight mb-30">Maecenas volutpat, diam enim sagittis uam, id porta ulamis. Sed id dolor consectetur fermentum nibh vomat,  ata umasnu purus. Maecenas volutpat, diam enim sagittis quam, id prta quam. Sed id dolor consectetur. Loremus fermentum nibh volutpat, accumsan purus.</p><p>Perspiciatis unde omnis iste natus error sit voluptatem accus anum doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Cras tellus enim, sagittis aer varius faucibus, molestie in dolor. Mauris molliadipisg elit, in vulputate est volutpat vitae.</p><h4 class="font-open-sans"><strong>Maecenas volutpat</strong></h4><p>Cras tellus enim, sagittis aer varius faucibus, molestie in dolor. Mauris molliadipisg elit, in vulputate est volutpat vitae. Pellentesque convallis nisl sit amet lacus luctus vel consequat ligula suscipit. Aliquam et metus sed tortor eleifend pretium non id urna. Fusce in augue leo, sed cursus nisl. Nullam vel tellus massa. Vivamus porttitor rutrum libero ac mattis. Aliquam congue malesuada mauris vitae dignissim.</p></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h4>Imagen de Cabecera: <span data-toggle="tooltip" 
                              data-placement="top" 
                              title="1920 x 1080" 
                              class="pull-right fa fa-exclamation-circle"></span></h4>
                        <div class="col-md-12 ">
                            <div class="panel panel-default">
                                <div class="panel-body">                                   
                                    <input name="newPortHeaderImg" type="file" id="newPortHeaderImg"/>
                                </div>
                            </div>
                        </div>      
                        <h4>Banner final: <span data-toggle="tooltip" 
                              data-placement="top" 
                              title="1920 x 700" 
                              class="pull-right fa fa-exclamation-circle"></span></h4>
                        <div class="col-md-12 ">
                            <div class="panel panel-default">
                                <div class="panel-body">                                   
                                    <input name="newPortBannerImg" type="file" id="newPortBannerImg"/>
                                </div>
                            </div>
                        </div>
                        <h4>Imagen de Post: <span data-toggle="tooltip" 
                              data-placement="top" 
                              title="1920 x 1080" 
                              class="pull-right fa fa-exclamation-circle"></span></h4>
                        <div class="col-md-12 ">
                            <div class="panel panel-default">
                                <div class="panel-body">                                   
                                    <input name="newPortPostImg" type="file" id="newPortPostImg"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>