<?php
////////////////////////////////////////////////////////////////////////////EBOOKS  VIEW
?>
<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#" gotopanel='home' class="menu_btn">homecubic</a></li>
    <li><a >Website</a></li>
    <li class="active"> Libros para Descargar </li>
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
        <div class="col-md-4 newblog hidethis savenewblog">                        
            <a href="#" class="tile tile-success tile-valign "><span class="fa fa-save"></span><img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;width: 100%;" /></a>                        
        </div> 
        <div class="col-md-4 editblog hidethis deleteblog">                        
            <a href="#" class="tile tile-danger tile-valign "><span class="fa fa-times-circle-o"></span><img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;width: 100%;" /></a>                        
        </div> 
    </div>
    
    <?php //**VENTANA DE LISTADO DE EBOOKS  ?>
    <div class="row bloglist">
        <?php
        $selectportf = "SELECT * FROM hc_pdf ";
        $resultportf = $link->query($selectportf) or die($link->error);
        while ($rowportf = $resultportf->fetch_array(MYSQLI_BOTH)) {
            if ($rowportf[6] == 1) {
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
                        <img src="../assets/img/pdf/' . $rowportf[4] . '.jpg" alt="Imagen de Blog" style="height: 300px;"/>
                        <a href="#" class="panel-body-inform panel-body-inform-' . $checked . '">
                            <span class="fa ' . $awe . '"></span>
                        </a>
                    </div>
                    <div class="panel-body">
                        <h3>' . $rowportf[1] . ' &mdash; ' . $rowportf[2] . '</h3>
                        <div class="hidethis_force idBlogContainer">' . $rowportf[0] . '</div>
                    </div>
                    <div class="panel-footer text-muted">
                        <button class="pull-right btn btn-success editblogbtn" type="button" data-toggle="tooltip" data-placement="right" title="Editar Ebook">
                            <span class="beforeLoad" > Editar Entrada </span>
                            <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                        </button>
                        <button class="pull-right pushright btn btn-info previewblogbtn" type="button" data-toggle="tooltip" data-placement="right" title="Ver Ebook">
                            <span class="blogidtopreview hidethis">' . $rowportf[0] . '</span>
                            <span class="beforeLoad" > Ver Entrada </span>
                            <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                        </button>
                    </div>
                    
                </div>

            </div>
           ';
        }
        ?>

    </div>

    <?php //**VENTANA DE EDITAR PROYECTO  ?>
    <div class="row editblog hidethis ">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="col-md-6">
                        <h2><span class="fa fa-edit"></span> Editar Ebook</h2>
                    </div>
                    <div class="col-md-6">
                        <label class="switch pull-right editblogcheckbox">
                            <input type="checkbox" class="switch" value="1" checked="checked" />
                            <span></span>
                        </label>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-8">
                        <div class="hidethis_force editidBlogContainer"></div>
                        <div class="row pushbottom32">
                            <div class="col-md-6">
                                <h4>Titulo:</h4>
                                <input name="editTitleBlog" id="editTitleBlog" type="text" class="form-control" value="" placeholder="Ingresa el texto" required="required"/>   
                            </div>
                            <div class="col-md-6">
                                <h4>Categoria:</h4>
                                <input name="editCatBlog" id="editCatBlog" type="text" class="form-control" value="" placeholder="Ingresa el texto" required="required"/>   
                            </div>
                        </div>
                        <button class="pull-right btn btn-success pushtop " name="editTitlesBlogBtn"  type="submit"  id="editTitlesBlogBtn" data-toggle="tooltip" data-placement="right" title="editar titulos">
                            <span class="beforeLoad" ><span class="fa fa-save"></span> Editar </span>
                            <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                        </button>
                        <div class="col-md-12">
                            <div class="col-md-12 pushtop32">
                                <h4>Resumen del proyecto:</h4>
                                <textarea class="summernote" rows="6" id="edithtmlentrada"></textarea>
                            </div>
                            <div class="col-md-6 saveedithtmlboxes pushtop">                        
                                <a href="#" class="tile tile-success tile-valign ">
                                    <span class="fa fa-save"></span>
                                    <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;width: 100%;" />
                                </a>                        
                            </div> 
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h4>Imagen de Cabecera:<span data-toggle="tooltip" 
                                                     data-placement="top" 
                                                     title="1920 x 1080" 
                                                     class="pull-right fa fa-exclamation-circle"></span></h4>
                        <div class="block push-up-10 headerimage">
                            <img src="../assets/img/blog/11.jpg" style="width: 100%;" />
                        </div>
                        <div class="col-md-12 hidethis uploadheader">
                            <div class="panel panel-default">
                                <div class="panel-body">                                   
                                    <input name="newheaderimgBlog" type="file" id="newheaderimgBlog"/>
                                    <button class="pull-right btn btn-success" name="newheaderimgBlogbtn"  type="button"  id="newheaderimgBlogbtn" data-toggle="tooltip" data-placement="right" title="Cambiar Imagen de Cabecera">
                                        <span class="beforeLoad" ><span class="fa fa-upload"></span> Subir </span>
                                        <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                                    </button>
                                </div>
                            </div>
                        </div>
                        <h4>Archivo PDF: 
                            <span data-toggle="tooltip" data-placement="top" 
                                  title="Reemplazar archivo" 
                                  class="pull-right fa fa-exclamation-circle"></span>
                        </h4>
                        <div class="col-md-12 postimage">                        
                            <a href="#" class="tile tile-danger tile-valign"><span class="fa fa-file-pdf-o"></span></a>                        
                        </div>   
                        <div class="col-md-12 hidethis uploadpostimage">
                            <div class="panel panel-default" id="quitarpanel">
                                <div class="panel-body">                                   
                                    <input name="newpostimgBlog" type="file" id="newpostimgBlog"/>
                                    <button class="pull-right btn btn-success" name="newpostimgBlogbtn"  type="submit"  id="newpostimgBlogbtn" data-toggle="tooltip" data-placement="right" title="Cambiar imagen de Publicacion">
                                        <span class="beforeLoad" ><span class="fa fa-upload"></span> Subir </span>
                                        <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php //**VENTANA DE AGREGAR NUEVO PROYECTO  ?>
    <div class="row newblog hidethis ">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><span class="fa fa-edit"></span> Nuevo Ebook</h2>
                </div>
                <form>
                    <div class="panel-body">
                        <div class="col-md-8">
                            <div class="row pushbottom32">
                                <div class="col-md-6">
                                    <h4>Titulo:</h4>
                                    <input name="newBlogTitle" id="newBlogTitle" type="text" class="form-control" value="" placeholder="Ingresa el texto" required="required"/>   
                                </div>
                                <div class="col-md-6">
                                    <h4>Subt&iacute;tulo:</h4>
                                    <input name="newBlogCat" id="newBlogCat" type="text" class="form-control" value="" placeholder="Ingresa el texto" required="required"/>   
                                </div>
                            </div>
                            <div class="col-md-12 pushtop32">
                                <h4>Texto HTML:</h4>
                                <textarea class="summernote" id="htmlentrada" rows="6">
                                    <li>
                                        <h3>¿De qué se trata este e-book?</h3>
                                        <p>Contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido.</p>
                                    </li>
                                    <li>
                                        <h3>¿Por qué este contenido es importante?</h3>
                                        <p>Contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido contenido</p>
                                    </li>
                                    <li>
                                        <h3>¿Cómo me beneficiará este contenido?</h3>
                                        <p>Este e-book te ayudará a:</p>
                                        <p> </p>
                                        <p>- Contenido</p>
                                        <p>- Contenido</p>
                                        <p>- Contenido</p>
                                        <p>- Contenido</p>
                                        <p>- Contenido</p>
                                    </li>
                                </textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4>Imagen de Cabecera: <span data-toggle="tooltip" 
                                                          data-placement="top" 
                                                          title="425 x 214" 
                                                          class="pull-right fa fa-exclamation-circle"></span></h4>
                            <div class="col-md-12 ">
                                <div class="panel panel-default">
                                    <div class="panel-body">                                   
                                        <input name="newBlogHeaderImg" type="file" id="newBlogHeaderImg"/>
                                    </div>
                                </div>
                            </div> 
                            <h4>Libro en PDF: 
                                <span data-toggle="tooltip" data-placement="top" title="NO mayor a 5MB" class="pull-right fa fa-exclamation-circle"></span>
                            </h4>
                            <div class="col-md-12 ">
                                <div class="panel panel-default">
                                    <div class="panel-body">                                   
                                        <input name="newBlogHeaderImg" type="file" id="newBlogPostImg"/>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>