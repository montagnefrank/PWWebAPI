<div class="page-sidebar page-sidebar-fixed scroll mCustomScrollbar _mCS_1 mCS-autoHide">
    <ul class="x-navigation">
        <li class="xn-logo">
            <a href="#" gotopanel='users' class="menu_btn"> Homecubic</a>
            <a href="#" class="x-navigation-control"></a>
        </li>
        <li class="xn-profile">
            <a href="#" class="profile-mini">
                <img src="<?php
                $isavatar = "assets/img/users/" . $user . ".jpg";
                if (file_exists($isavatar)) {
                    echo "assets/img/users/" . $user;
                } else {
                    echo "assets/img/users/default";
                }
                ?>.jpg" alt="<?php echo $user ?>"/>
            </a>
            <div class="profile">
                <div class="profile-image">
                    <img src="<?php
                    $isavatar = "assets/img/users/" . $user . ".jpg";
                    if (file_exists($isavatar)) {
                        echo "assets/img/users/" . $user;
                    } else {
                        echo "assets/img/users/default";
                    }
                    ?>.jpg" alt="<?php echo $user ?>"/>
                </div>
                <div class="profile-data">
                    <div class="profile-data-name">
                        <?php
                        echo $_SESSION['username'];
                        ?>
                        <div class="profile-data-title">
                            <span class="fa fa-user"></span> 
                            <?php echo $user ?></div>
                    </div>
                    <div class="profile-data-title">
                        <span class="fa fa-industry"></span> 
                        <?php
                        echo 'Parked & Washed';
                        ?>
                    </div>
                </div>
                <div class="profile-controls">
<!--                    <a href="pages-profile.html" class="profile-control-left"><span class="fa fa-info"></span></a>
                    <a href="pages-messages.html" class="profile-control-right"><span class="fa fa-envelope"></span></a>-->
                </div>
            </div>                                                                        
        </li>
        <li class="xn-title">Menú principal</li>
        <li class="xn-openable"><a href="#"><span class="fa fa-mobile-phone"></span><span class="xn-text"> App</span></a>
            <ul>
                <li><a href="#" gotopanel='users' class="menu_btn"> Usuarios</a></li>
            </ul>
        </li>  
        <li>
            <a href="#" gotopanel='tools' class="menu_btn">
                <span class="fa fa-cogs"></span>
                <span class="xn-text"> Herramientas</span>
            </a>
        </li>
    </ul>
</div>