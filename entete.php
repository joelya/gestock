
<div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="<?= $_SESSION['proflis']?>.php">
                        <img src="assets/pages/img/login/moov.png" style="margin-top:-0px" width="150" height="auto" alt="logo" class="logo-default" /></a>
                    <div class="menu-toggler sidebar-toggler">
                       <span></span>
                    </div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                    <span></span>
                </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN TOP NAVIGATION MENU -->
               <span style="color:#FFFFFF; font-size:20px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;<?= $_SESSION['proflis']?> : 
                      <?= $_SESSION['pvte_lib']?> </strong></span>  
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <!-- BEGIN NOTIFICATION DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                         <!-- END NOTIFICATION DROPDOWN -->
                        <!-- BEGIN INBOX DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                         <!-- END INBOX DROPDOWN -->
                        <!-- BEGIN TODO DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <!-- END TODO DROPDOWN -->
                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <?php if($_SESSION['countimage000']==0){ ?>
                            <img src="<?= $_SESSION['image']?>" class="img-circle" ><?php } ?>
                            <?php if($_SESSION['countimage000']==1){ ?>
                                <img alt="" class="img-circle" src="assets/layouts/layout/img/avatar3_small.jpg" /><?php } ?>
                                <span class="username username-hide-on-mobile">  <?= $_SESSION['user']; ?> </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                   <a href="<?= $_SESSION['proflis']?>.php?p=profil">
                                        <i class="icon-user"></i> Mon profil </a>
                                </li>
                                
                                <li>
                                    <a href="documentation/ADMINISTRATEUR.pdf"  target="_blank">
                                        <i class="glyphicon glyphicon-question-sign"></i>Aide
                                    </a>
                                </li>
                                <li class="divider"> </li>
                                <li>
                                    <a href="index.php">
                                        <i class="icon-lock"></i> Ecran de veille </a>
                                </li>
                                <li>
                                    <a href="logout.php">
                                        <i class="icon-key"></i> Déconnexion </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->
                        <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <li class="dropdown dropdown-quick-sidebar-toggler">
                            <a href="javascript:;" class="dropdown-toggle">
                                <i class="icon-logout"></i>
                            </a>
                        </li>
                        <!-- END QUICK SIDEBAR TOGGLER -->
                    </ul>
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END HEADER INNER -->
        </div>




