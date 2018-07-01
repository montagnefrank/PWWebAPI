<?php
////////////////////////////////////////////////////////////////////////////BLOG  VIEW
?>
<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#" gotopanel='home' class="menu_btn">homecubic</a></li>
    <li><a >Website</a></li>
    <li class="active"> Blog </li>
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

    <?php
    //**VENTANA DE LISTADO DE PROYECTOS 
    $titles_select = "SELECT * FROM hc_misc";
    $titles_result = $link->query($titles_select) or die($link->error);
    $titles_row = array();
    $titles_result->data_seek(0);
    $titles_row[0] = $titles_result->fetch_array(MYSQLI_ASSOC);
    ?>
    <div class="row bloglist">
        <div class="col-md-6">
            <div class="panel panel-default">                            
                <div class="panel-body panel-body-image">
                    <img src="../assets/img/blogtitle.jpg" alt="Fondo de blog" style="max-height: 190px;"/>
                    <a href="#" class="panel-body-inform">
                        <span class="fa fa-edit"></span>
                    </a>
                </div>
                <div class="panel-body">
                    <h3>Editar el listado de blogs <span data-toggle="tooltip" 
                                                         data-placement="top" 
                                                         title="1920 x 1080" 
                                                         class="pull-right fa fa-exclamation-circle"></span></h3>
                    <input id="BloglistTitle" type="text" class="form-control pushbottom uptext1" value="<?php echo $titles_row[0]['blogTitleSite']; ?>" placeholder="Rellena el texto">
                    <textarea id="BloglistSubtitle" class="form-control" rows="2"><?php echo $titles_row[0]['blogSubtitleSite']; ?></textarea>
                    <div class="panel panel-default pushtop32">
                        <input name="blolistbgimg" type="file" id="blolistbgimg"/>
                        <button class="pull-right btn btn-success" name="blolistbgimgbtn"  type="submit"  id="blolistbgimgbtn" data-toggle="tooltip" data-placement="right" title="Cambiar Imagen">
                            <span class="beforeLoad" > Cambiar Imagen </span>
                            <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <?php
        $selectportf = "SELECT * FROM hc_blog ";
        $resultportf = $link->query($selectportf) or die($link->error);
        while ($rowportf = $resultportf->fetch_array(MYSQLI_BOTH)) {
            if ($rowportf[9] == 1) {
                $checked = 'on';
                $awe = 'fa-check';
            } else {
                $checked = 'off';
                $awe = 'fa-times';
            }
            $title = (strlen($rowportf[1]) > 30) ? substr($rowportf[1],0,30).'...' : $rowportf[1];
            $desc = (strlen($rowportf[2]) > 30) ? substr($rowportf[2],0,30).'...' : $rowportf[2];
            echo ' 
            <div class="col-md-6">
                <div class="panel panel-default">                            
                    <div class="panel-body panel-body-image">
                        <img src="../assets/img/blog/' . $rowportf[5] . '.jpg" alt="Imagen de Blog" style="height: 300px;"/>
                        <a href="#" class="panel-body-inform panel-body-inform-' . $checked . '">
                            <span class="fa ' . $awe . '"></span>
                        </a>
                    </div>
                    <div class="panel-body">
                        <h3>' . $title . ' &mdash; ' . $desc . '</h3>
                        <div class="hidethis_force idBlogContainer">' . $rowportf[0] . '</div>
                    </div>
                    <div class="panel-footer text-muted">
                        <button class="pull-right btn btn-success editblogbtn" type="button" data-toggle="tooltip" data-placement="right" title="Editar Blog">
                            <span class="beforeLoad" > Editar Entrada </span>
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
                        <h2><span class="fa fa-edit"></span> Editar Entrada de Blog</h2>
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
                            <div class="col-md-4">
                                <h4>Titulo:</h4>
                                <input name="editTitleBlog" id="editTitleBlog" type="text" class="form-control" value="" placeholder="Ingresa el texto" required="required"/>   
                            </div>
                            <div class="col-md-4">
                                <h4>Categoria:</h4>
                                <input name="editCatBlog" id="editCatBlog" type="text" class="form-control" value="" placeholder="Ingresa el texto" required="required"/>   
                            </div>
                            <div class="col-md-4">
                                <h4>Ebook:</h4>
                                <input name="editEbookBlog" id="editEbookBlog" type="text" class="form-control" value="" placeholder="Ingresa el texto" required="required"/>   
                            </div>
                        </div>
                        <h4 class="pushtop">Descripci&oacute;n:</h4>
                        <input name="editSubtitleBlog" id="editSubtitleBlog" type="text" class="form-control" value="" placeholder="Ingresa el texto" />
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
                        <h4>Imagen de Post: <span data-toggle="tooltip" 
                                                  data-placement="top" 
                                                  title="1920 x 1080" 
                                                  class="pull-right fa fa-exclamation-circle"></span></h4>
                        <div class="block push-up-10 postimage">
                            <img src="../assets/img/blog/05.jpg" style="width: 100%;" />
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
                    <h2><span class="fa fa-edit"></span> Nueva Entrada de BLog</h2>
                </div>
                <form>
                    <div class="panel-body">
                        <div class="col-md-8">
                            <div class="row pushbottom32">
                                <div class="col-md-4">
                                    <h4>Titulo:</h4>
                                    <input name="newBlogTitle" id="newBlogTitle" type="text" class="form-control" value="" placeholder="Ingresa el texto" required="required"/>   
                                </div>
                                <div class="col-md-4">
                                    <h4>Categoria:</h4>
                                    <input name="newBlogCat" id="newBlogCat" type="text" class="form-control" value="" placeholder="Ingresa el texto" required="required"/>   
                                </div>
                                <div class="col-md-4">
                                    <h4>Ebook:</h4>
                                    <input name="newBlogEbook" id="newBlogEbook" type="text" class="form-control" value="" placeholder="Ingresa el texto" required="required"/>   
                                </div>
                            </div>
                            <h4 class="pushtop">Descripci&oacute;n:</h4>
                            <input type="text" class="form-control" name="newBlogSubtitle" id="newBlogSubtitle" value="" placeholder="Ingresa el texto" required="required"/>
                            <div class="col-md-12 pushtop32">
                                <h4>Entrada de Blog:</h4>
                                <textarea class="summernote" id="htmlentrada" rows="6"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Recusandae, nostrum, cumque culpa provident aliquam commodi assumenda laudantium magnam illo nostrum. Donec nibh sapien, molestie quis elementum et, dim non atino ipsum.</p><p>Fusce non ante sed lorem rutrum feugiat. Vestibulum pellentesque, purus ut&nbsp;dignissim consectetur, nulla erat ultrices purus, ut&nbsp;consequat sem elit non sem.Morbi lacus massa, euismod ut turpis molestie, tristique sodales est. Integer sit amet mi id sapien tempor molestie in nec massa.Fusce non ante sed lorem rutrum feugiat.</p><blockquote class="mb-40 mt-40"><p>Vivamus sit amet facilisis metus. Fusce felis libero, hendrerit eu plac erat at, facilisis sit amet libero. Etiam eget interdum lorem. </p><footer>Someone famous in <cite title="Source Title">Source Title</cite></footer></blockquote><p>Pellentesque venenatis tellus non purus tincidunt vitae ultrices tellus eleifend. Praesent quam augue, accumsan nec tempus dapibus, pharetra ac lacus. Nunc eleifend consequat justo id dapibus. In ut consequat massa. Nunc scelerisque suscipit leo nec imperdiet. Vestibulum pulvinar adipiscing turpis vitae ultrices. Suspendisse eu lectus dui, vitae lobortis lorem. Fusce gravida nibh et ante accusan molestie.</p><pre class="mt-30 mb-30">&lt;p&gt;Sample text here...&lt;/p&gt;</pre><p>Fusce id dui sem. Cras gravida odio et magna faucbus iaculis. Suspendisse eu lectus dui, vitae lobortis lorem. Fusce gravida nibh et ante accusan molestie. Duis convallis semper felis. Curabitur fringilla placerat vestibulum. Aenean dignissim libero et quam tristique vel vehicula nunc suscipit. Fusce id dui sem.  Cras gravida odio et magna faucbus iaculis. Vestibulum ante ipsum primis in faucibus orci. Curabitur fringilla placerat vestibulum. Aenean dignissim libero et quam tristique nunc suscipit.</p></textarea>
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
                                        <input name="newBlogHeaderImg" type="file" id="newBlogHeaderImg"/>
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
                                        <input name="newBlogPostImg" type="file" id="newBlogPostImg"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>