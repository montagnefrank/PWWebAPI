<?php
////////////////////////////////////////////////////////////////////////////GALLERY  VIEW
?>
<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#" gotopanel='home' class="menu_btn">homecubic</a></li>
    <li><a >Website</a></li>
    <li class="active"> Galer&iacute;a </li>
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
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="col-md-6">
                    <h2><span class="fa fa-picture-o"></span> Galer&iacute;a de im&aacute;genes</h2>
                </div>
            </div>
            <div class="panel-body">
                <div class="col-md-8">
                    <div class="hidethis_force editidPortContainer"></div>
                    <h4 class="pushtop32">Imagenes de la galer&iacute;a</h4>
                    <div class="gallery " id="links">
                        <div class="row imgsliderContainer">
                            <?php
                            $path = '../img/';
                            $files = array_diff(scandir($path), array('.', '..'));
                            foreach ($files as $key => $value) {
                                echo ' 
                                        <a class="gallery-item" href="" title="Imagen ' . $value . '" data-gallery>
                                            <div class="image">                              
                                                <img src="../img/' . $value . '" alt="Imagen ' . $value . '"/>                                        
                                                <ul class="gallery-item-controls">
                                                    <li>
                                                        <span class="removethisimg"><i class="fa fa-times"></i>
                                                            <div class="hidethis_force imageidPortContainer">' . $value . '</div>
                                                        </span></li>
                                                </ul>                                                                    
                                            </div>                               
                                        </a>  
                                   ';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-12">
                        <h3><span class="fa fa-sliders"></span> Imagen seleccionada</h3>
                        <div class="block push-up-10 selectedimage">

                        </div> 
                    </div>
                    <div class="col-md-12">
                        <h3><span class="fa fa-code"></span> C&oacute;digo para el post</h3>
                        <div class="block push-up-10 selectedcode">

                        </div> 
                    </div>
                    <div class="col-md-12">
                        <h3><span class="fa fa-globe"></span> URL de la imagen</h3>
                        <div class="block push-up-10 selectedurl">
                            
                        </div> 
                    </div>
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">                                   
                                <form action="<?php echo "assets/modules/" . $panel . "/control.php"; ?>" method="post" enctype="multipart/form-data">
                                    <h3><span class="fa fa-image"></span> Agregar nueva imagen <span data-toggle="tooltip" 
                                                                                                     data-placement="top" 
                                                                                                     title="Menor a 500KB" 
                                                                                                     class="pull-right fa fa-exclamation-circle"></span></h3>
                                    <div style="margin-top: 10px; margin-bottom: 10px;margin-left: 20px;">
                                        <input name="newgalleryImg" type="file" id="newgalleryImg"/>
                                        <button class="pull-right btn btn-success" name="newgalleryImgbtn"  type="button"  id="newgalleryImgbtn" data-toggle="tooltip" data-placement="right" title="Agregar nueva imagen">
                                            <span class="beforeLoad" ><span class="fa fa-save"></span></span>
                                            <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                                        </button>
                                    </div>
                            </div>
                            </from>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>