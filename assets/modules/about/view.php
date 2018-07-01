<?php
///////////////////////////////////////////////////////////////////// ABOUT VIEW             
$query = "SELECT * FROM hc_aboutwidget ";
$result = $link->query($query) or die($link->error);
$row = array();
$result->data_seek(0);
$row[0] = $result->fetch_array(MYSQLI_NUM);
$result->data_seek(1);
$row[1] = $result->fetch_array(MYSQLI_NUM);
$result->data_seek(2);
$row[2] = $result->fetch_array(MYSQLI_NUM);
$result->data_seek(3);
$row[3] = $result->fetch_array(MYSQLI_NUM);
?>
<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#" gotopanel='home' class="menu_btn">homecubic</a></li>
    <li><a > Interfaz</a></li>
    <li class="active" id="showview">Quienes Somos</li>
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
    <div class="row">
        <div class="col-md-8">   
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="fa fa-cogs"></span> Editar textos</h3>                                
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                    </ul>                                   
                </div>
                <div class="panel-body">
                    <?php
                    $titles_select = "SELECT * FROM hc_misc";
                    $titles_result = $link->query($titles_select) or die($link->error);
                    $titles_row = array();
                    $titles_result->data_seek(0);
                    $titles_row[0] = $titles_result->fetch_array(MYSQLI_ASSOC);
                    ?>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Texto primario</label>
                        <div class="col-md-10">
                            <textarea type="text" class="form-control pushbottom aboutTitle" ><?php echo $titles_row[0]['aboutTitleSite']; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Texto auxiliar</label>
                        <div class="col-md-10">
                            <textarea type="text" class="form-control pushbottom aboutSubtitle" rows="4"><?php echo $titles_row[0]['aboutSubtitleSite']; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button class="pull-right btn btn-info" name="edittitles"  type="button" onClick="addnewabouttitles();" id="edittitles" data-toggle="tooltip" data-placement="right" title="Editar Titulos">
                        <span class="beforeLoad" ><span class="fa fa-plus"></span> Editar Titulos </span>
                        <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;">
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">   
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="fa <?php echo $row[0][1]; ?>"></span> <?php echo $row[0][2]; ?></h3>                                
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                    </ul>                                   
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Titulo</label>
                        <div class="col-md-10">
                            <textarea type="text" class="form-control pushbottom edittitle1" placeholder="Ingrese el titulo"><?php echo $row[0][2]; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Texto</label>
                        <div class="col-md-10">
                            <textarea type="text" class="form-control pushbottom editsubtitle1" placeholder="Ingrese el texto"><?php echo $row[0][3]; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Icono</label>
                        <div class="col-md-10">
                            <button id="pick_icon1" class="btn btn-default" data-iconset="fontawesome" data-icon="<?php echo $row[0][1]; ?>" role="iconpicker"></button>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button class="pull-right btn btn-info" name="editbtn1"  type="submit" onClick="editwidget1();" id="editbtn1" data-toggle="tooltip" data-placement="right" title="Editar Widget">
                        <span class="beforeLoad" ><span class="fa fa-pencil"></span> Editar </span>
                        <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;">
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">   
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="fa <?php echo $row[1][1]; ?>"></span> <?php echo $row[1][2]; ?></h3>                                
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                    </ul>                                   
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Titulo</label>
                        <div class="col-md-10">
                            <textarea type="text" class="form-control pushbottom edittitle2" placeholder="Ingrese el titulo"><?php echo $row[1][2]; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Texto</label>
                        <div class="col-md-10">
                            <textarea type="text" class="form-control pushbottom editsubtitle2" placeholder="Ingrese el texto"><?php echo $row[1][3]; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Icono</label>
                        <div class="col-md-10">
                            <button id="pick_icon2" class="btn btn-default" data-iconset="fontawesome" data-icon="<?php echo $row[1][1]; ?>" role="iconpicker"></button>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button class="pull-right btn btn-info" name="editbtn2"  type="submit" onClick="editwidget2();" id="editbtn2" data-toggle="tooltip" data-placement="right" title="Editar Widget">
                        <span class="beforeLoad" ><span class="fa fa-pencil"></span> Editar </span>
                        <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;">
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">   
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="fa <?php echo $row[2][1]; ?>"></span> <?php echo $row[2][2]; ?></h3>                                
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                    </ul>                                   
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Titulo</label>
                        <div class="col-md-10">
                            <textarea type="text" class="form-control pushbottom edittitle3" placeholder="Ingrese el titulo"><?php echo $row[2][2]; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Texto</label>
                        <div class="col-md-10">
                            <textarea type="text" class="form-control pushbottom editsubtitle3" placeholder="Ingrese el texto"><?php echo $row[2][3]; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Icono</label>
                        <div class="col-md-10">
                            <button id="pick_icon3" class="btn btn-default" data-iconset="fontawesome" data-icon="<?php echo $row[2][1]; ?>" role="iconpicker"></button>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button class="pull-right btn btn-info" name="editbtn3"  type="submit" onClick="editwidget3();" id="editbtn3" data-toggle="tooltip" data-placement="right" title="Editar Widget">
                        <span class="beforeLoad" ><span class="fa fa-pencil"></span> Editar </span>
                        <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;">
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">   
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="fa <?php echo $row[3][1]; ?>"></span> <?php echo $row[3][2]; ?></h3>                                
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                    </ul>                                   
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Titulo</label>
                        <div class="col-md-10">
                            <textarea type="text" class="form-control pushbottom edittitle4" placeholder="Ingrese el titulo"><?php echo $row[3][2]; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Texto</label>
                        <div class="col-md-10">
                            <textarea type="text" class="form-control pushbottom editsubtitle4" placeholder="Ingrese el texto"><?php echo $row[3][3]; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Icono</label>
                        <div class="col-md-10">
                            <button id="pick_icon4" class="btn btn-default" data-iconset="fontawesome" data-icon="<?php echo $row[3][1]; ?>" role="iconpicker"></button>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button class="pull-right btn btn-info" name="editbtn4"  type="submit" onClick="editwidget4();" id="editbtn4" data-toggle="tooltip" data-placement="right" title="Editar Widget">
                        <span class="beforeLoad" ><span class="fa fa-pencil"></span> Editar </span>
                        <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;">
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">                            
            <div class="panel-body panel-body-image">
                <img src="../assets/img/quienes/fondo.jpg" alt="Slide1"/>
                <a href="#" class="panel-body-inform">
                    <span class="fa fa-edit"></span>
                </a>
            </div>
            <div class="panel-body">
                <h3>Editar la imagen <span data-toggle="tooltip" 
                              data-placement="top" 
                              title="960 x 650" 
                              class="pull-right fa fa-exclamation-circle"></span></h3>
                <div class="panel panel-default">
                    <form id="slide1form" action="<?php echo "assets/modules/" . $panel . "/control.php"; ?>" method="post" enctype="multipart/form-data">
                        <div class="panel-heading">
                            <h6>Cambiar imagen</h6>
                        </div>
                        <div class="panel-body">                                   
                            <p>Seleciona la imagen de  <code>fondo de la seccion</code> para reemplazar la actual</p>
                            <div style="margin-top: 10px; margin-bottom: 10px;margin-left: 20px;">
                                <input name="slide1file" type="file" id="file-simple"/>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button class="pull-right btn btn-success" name="newaboutimg"  type="submit"  id="newaboutimg" data-toggle="tooltip" data-placement="right" title="Editar Imagen">
                                <span class="beforeLoad" > Editar imagen </span>
                                <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="panel-footer text-muted">
                <span class="fa fa-image"></span> Imagen Lateral
                <span class="fa fa-list"></span> 1
            </div>
        </div>

    </div>
</div>