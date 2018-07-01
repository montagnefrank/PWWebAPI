<?php
////////////////////////////////////////////////////////////////////////////TWEETS  VIEW
?>
<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#" gotopanel='home' class="menu_btn">homecubic</a></li>
    <li><a >Website</a></li>
    <li class="active"> Testimonios </li>
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
    <div class="col-md-6">   
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="fa fa-cogs"></span> Editar titulo</h3>                                
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
                    <label class="col-md-2 control-label">Titulo</label>
                    <div class="col-md-10">
                        <textarea type="text" class="form-control pushbottom tweetsTitle" ><?php echo $titles_row[0]['tweetsTitleSite']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button class="pull-right btn btn-info" name="edittitles"  type="submit"  id="edittitles" data-toggle="tooltip" data-placement="right" title="Edtar Menu">
                    <span class="beforeLoad" ><span class="fa fa-save"></span> Editar Titulo </span>
                    <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;">
                </button>
            </div>
        </div>
    </div>


    <div class="col-md-4">                        
        <a href="#" class="tile tile-info tile-valign newmember"><span class="fa fa-plus-square"></span></a>                        
    </div>  

    <div class="col-md-6 hidethis tweetsformpanel">   
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="fa fa-cogs"></span> <span id="modaltitle">Editar Mensajes</span></h3>                                
                <ul class="panel-controls">
                    <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                </ul>                                   
            </div>
            <div class="panel-body">
                <form id="member_form" action="<?php echo "assets/modules/" . $panel . "/control.php"; ?>" role="form" class="form-horizontal" method="post">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h6>Imagen de perfil <span data-toggle="tooltip" 
                              data-placement="top" 
                              title="200 x 200" 
                              class="pull-right fa fa-exclamation-circle"></span></h6>
                        </div>
                        <div class="panel-body">                                   
                            <div style="margin-top: 10px; margin-bottom: 10px;margin-left: 20px;">
                                <input name="teamProfile_file" type="file" id="file-simple1"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nombre</label>
                        <div class="col-md-10">
                            <input name="name_team" type="text" class="form-control" placeholder="Nombre del usuario" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Cargo</label>
                        <div class="col-md-10">
                            <input name="job_team" type="text" class="form-control" placeholder="Cargo del usuario" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Testimonio</label>
                        <div class="col-md-10">
                            <input name="profile_team" type="text" class="form-control" placeholder="Breve testimonio" /> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Estado</label>
                        <div class="col-md-10">
                            <label class="switch">
                                <input type="checkbox" class="switch switchcheck" name="status_check"  value="1" checked="checked"/>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <input id="typeaction" name="newtweet" type="hidden" class="form-control" value="true"/>
                    <input id="memberid" name="tweetid" type="hidden" class="form-control" value="true"/>
                </form>
            </div>
            <div class="panel-footer">
                <button class="pull-right btn btn-success" 
                        name="editTweet"  
                        type="submit"  
                        id="editTweet" 
                        data-toggle="tooltip" 
                        data-placement="right" 
                        title="Edtar testimonio">
                    <span class="beforeLoad" ><span class="fa fa-save"></span> Guardar Cambios </span>
                    <img class="loading_img" src="assets/img/loadingbar.gif" width="80" style="display: none;" />
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">   
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="fa fa-cogs"></span> Editar Mensaje</h3>                                
            <ul class="panel-controls">
                <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
            </ul>                                   
        </div>
        <div class="panel-body">
            <form action="<?php echo "assets/modules/" . $panel . "/control.php"; ?>" role="form" class="form-horizontal" method="post">
                <?php
                $menu_select = "SELECT idTweet,photoTweet,nameTweet,jobTweet,profileTweet,statusTweet FROM hc_tweets ";
                $menu_result = $link->query($menu_select) or die($link->error);
                $idlist = '';
                while ($menu_row = $menu_result->fetch_array(MYSQLI_BOTH)) {
                    if ($menu_row['statusTweet'] == 1) {
                        $checked = 'checked';
                    } else {
                        $checked = '';
                    }
                    echo '   
                            <div class="col-md-4">

                                <div class="panel panel-default">
                                    <div class="panel-body profile bg-primary">

                                        <div class="profile-image">
                                            <img src="../assets/img/tweets/' . $menu_row['photoTweet'] . '.jpg" alt="' . $menu_row['nameTweet'] . '">
                                        </div>
                                        <div class="profile-data">
                                            <div class="profile-data-name">' . $menu_row['nameTweet'] . '</div>
                                            <div class="profile-data-title">' . $menu_row['jobTweet'] . '</div>
                                        </div>
                                        <div class="profile-controls">
                                            <a href="#" class="profile-control-left deletemember" onClick="notyConfirm(' . $menu_row['idTweet'] . ');"><span class="fa fa-times"></span></a>
                                            <a href="#" class="profile-control-left pushtop32 noborder">
                                                <label class="switch">
                                                    <input type="checkbox" class="switch" name="' . $menu_row['idTweet'] . '_check"  value="1" ' . $checked . '/>
                                                    <span></span>
                                                </label>
                                            </a>
                                            <a href="#" class="profile-control-right edittweet"><span class="fa fa-edit"></span></a>
                                        </div>

                                    </div>
                                    <div class="panel-body list-group">
                                        <a href="#" class="list-group-item teamprofile"><span class="help-block">' . $menu_row['profileTweet'] . '</span></a>
                                        <a href="#" class="list-group-item hidethis2 idteam"><span class="help-block">' . $menu_row['idTweet'] . '</span></a>
                                    </div>                            
                                </div>

                            </div>
                         ';
                    $idlist .= $menu_row['idTweet'] . ",";
                }
                $idlist = substr(trim($idlist), 0, -1);
                echo '<input name="idList" type="hidden" class="form-control" value="' . $idlist . '" >'
                ?>
        </div>
    </div>
</form>
</div>
</div>