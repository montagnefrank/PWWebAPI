<?php
////////////////////////////////////////////////////////////////////////////USERS  VIEW
?>
<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="index.php?panel=index.php">Inicio </a></li>
    <li>Usuarios</li>
</ul>
<!-- BREADCRUMB --> 
<div class="page-title">                    
    <h2><span class="fa fa-users"></span> Administraci&oacute;n de Usuarios</h2>
</div>    
<div class="page-content-wrap" >   
    <div class="row customalert hidethis">
        <div class="col-md-12">
            <div class="widget widget-info widget-item-icon">
                <div class="widget-item-left">
                    <span class="fa fa-exclamation-circle"></span>
                </div>
                <div class="widget-data">
                    <div class="widget-title">Notificación</div>
                    <div class="widget-subtitle">
                        <div role="alert" class="customalert_text">
                            Mensaje de error
                        </div>
                    </div>
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
                        </div>
                    </div>
                </div>
        ';
        unset($_SESSION['msg']);
    }
    ?>
    <div class="col-md-4">
        <a href="#" class="btn btn-info btn-lg newuser_btn" >
            <span>Nuevo </span> <span class="fa fa-plus-square"></span>
           
        </a>                        
        <a href="#" class="btn btn-danger btn-lg cancel_btn hidethis ">
            <span>Regresar </span> <span class="fa fa-arrow-circle-left"></span>
        </a>                        
        <a href="#" class="savenew_btn btn btn-success btn-lg hidethis" id="savenew_btn">
            <span>Guardar </span> <span class="fa fa-save"></span>
        </a>                        
        <a href="#" class="saveedit_btn btn btn-success btn-lg hidethis" id="saveedit_btn">
            <span>Actualizar </span><i class="fas fa-sync-alt"></i>
        </a>                        
    </div>
    <div class="col-md-12 hidethis newuser_panel push-up-20">   
        <div class="row">                        
            <div class="col-md-5 col-sm-5 col-xs-5">
                <form action="#" class="form-horizontal">
                    <div class="panel panel-default">                                
                        <div class="panel-body">
                            <div class="text-center" id="user_image">
                                <img src="assets/img/users/user.jpg" class="img-thumbnail"/>
                            </div>                                    
                        </div>
                        <div class="panel-body form-group-separated">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">usuario</label>
                                <div class="col-md-9 col-xs-7">
                                    <input id="username_input" type="text" placeholder="Usuario del sistema" class="form-control alphanumonly" required="required"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">contrase&ntilde;a</label>
                                <div class="col-md-9 col-xs-7">
                                    <input id="password_input" type="password" placeholder="Ingrese la contrase&ntilde;a" class="form-control" required="required"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">confirmar</label>
                                <div class="col-md-9 col-xs-7">
                                    <input id="passwordconf_input" type="password" placeholder="Confirme la contrase&ntilde;a" class="form-control" required="required"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-7 col-sm-7 col-xs-7">
                <form action="#" class="form-horizontal">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3><i class="fas fa-pencil-alt"></i> Perfil de usuario</h3>
                            <p>Ingrese la informaci&oacute;n del usuario.</p>
                        </div>
                        <div class="panel-body form-group-separated">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Nombre</label>
                                <div class="col-md-9 col-xs-7">
                                    <input id="usernombre_input" type="text" value="" placeholder="Ingresa el nombre" maxlength="40" class="form-control alphaonly" required="required"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Vehiculo</label>
                                <div class="col-md-9 col-xs-7">
                                    <input id="userapellido_input" type="text" value="" placeholder="Ingresa el vehiculo" maxlength="40" class="form-control alphaonly" required="required"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Telefono</label>
                                <div class="col-md-9 col-xs-7">
                                    <input id="usercargo_input" type="text" value="" placeholder="Ingresa el número de telefono" maxlength="30" class="form-control numonly" required="required"/>
                                </div>
                            </div>   
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Placa</label>
                                <div class="col-md-9 col-xs-7">
                                    <input id="newuser_est_select" type="text" value="" placeholder="Ingresa la placa" maxlength="30" class="form-control numonly" required="required"/>
                                </div>
                            </div>                                         
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Rol</label>
                                <div class="col-md-9 col-xs-7">
                                    <select id="newuser_rol_select" class="form-control select" data-style="btn-success">
                                        <option value="0">Seleccione</option>
                                        <option value="1">ADMINISTRADOR DE LOCAL</option>
                                        <option value="2">SALONERO</option>
                                        <option value="3">CAJERO CARTIFICADO</option>
                                        <option value="4">MENSAJERO REPARTIDOR</option>
                                        <option value="5">CONSERJE</option>
                                        <option value="6">GERENTE / AFINES</option>
                                        <option value="7">SOPORTE TECNICO</option>
                                    </select>
                                </div>
                            </div>       
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row"><br><br><br><br><br><br><br><br><br><br><br><br></div>
    </div>
    <div class="col-md-12 hidethis edituser_panel push-up-20">   
        <div class="row">                        
            <div class="col-md-5 col-sm-5 col-xs-5">
                <form action="#" class="form-horizontal">
                    <div class="panel panel-default">                                
                        <div class="panel-body">
                            <div class="text-center" id="edituser_image">
                                <img src="assets/img/users/user.jpg" class="img-thumbnail"/>
                            </div>                                    
                        </div>
                        <div class="panel-body form-group-separated">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">usuario</label>
                                <div class="col-md-9 col-xs-7">
                                    <input id="editusername_input" type="text" placeholder="Usuario del sistema" class="form-control alphanumonly" required="required"/>
                                </div>
                            </div>
                            <div class="form-group">                                        
                                <div class="col-md-12 col-xs-12">
                                    <a class="btn btn-danger btn-block btn-rounded restpass_btn" id="restartPass_btn">Restablecer contrase&ntilde;a</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-7 col-sm-7 col-xs-7">
                <form action="#" class="form-horizontal">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3><span class="fa fa-edit"></span> Perfil de usuario</h3>
                            <p>Aqu&iacute; puede actualizar la informaci&oacute;n del usuario.</p>
                        </div>
                        <div class="panel-body form-group-separated">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Nombre</label>
                                <div class="col-md-9 col-xs-7">
                                    <input id="editusernombre_input" type="text" value="" placeholder="Ingresa el nombre" maxlength="40" class="form-control alphaonly" required="required"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Vehiculo</label>
                                <div class="col-md-9 col-xs-7">
                                    <input id="edituserapellido_input" type="text" value="" placeholder="Ingresa el vehiculo" maxlength="40" class="form-control alphaonly" required="required"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Telefono</label>
                                <div class="col-md-9 col-xs-7">
                                    <input id="editusercargo_input" type="text" value="" placeholder="Ingresa el número de telefono" maxlength="30" class="form-control numonly" required="required"/>
                                </div>
                            </div>  
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Placa</label>
                                <div class="col-md-9 col-xs-7">
                                    <input id="newuser_est_select" type="text" value="" placeholder="Ingresa la placa" maxlength="30" class="form-control numonly" required="required"/>
                                </div>
                            </div>                                        
                            <div class="form-group">
                                <label class="col-md-3 col-xs-5 control-label">Rol</label>
                                <div class="col-md-9 col-xs-7">
                                    <select id="edituser_rol_select" class="form-control select" data-style="btn-success">
                                        <option value="0">Seleccione</option>
                                        <option value="1">ADMINISTRADOR DE LOCAL</option>
                                        <option value="2">SALONERO</option>
                                        <option value="3">CAJERO CARTIFICADO</option>
                                        <option value="4">MENSAJERO REPARTIDOR</option>
                                        <option value="5">CONSERJE</option>
                                        <option value="6">GERENTE / AFINES</option>
                                        <option value="7">SOPORTE TECNICO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="hidethis_force edituser_id_cont"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row"><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br></div>
    </div>
    
</div>
<div class="col-md-12 userlist_panel push-up-20">   
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="fa fa-users"></span> Usuarios del sistema</h3>                                                
        </div>
        <div class="panel-body usersList">
            <!--LOS USUARIOS SE MUESTRAN DE FORMA DINANICA-->
        </div>
    </div>
</div>