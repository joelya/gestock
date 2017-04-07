<?php 
session_start();
//connexion à la base de données
//var_dump($_SESSION);exit;
include('./model/connexion.php');
include('./function/pwd.php');
$pdo=connect();
//ini_set("display_errors",0);error_reporting(0);
 if((empty($_SESSION['name']) && empty($_SESSION['pname']) && empty($_SESSION['proflis'])) || (isset($_GET['kill']) && $_GET['kill']=='true'))
{
	session_destroy();
	header("Location:index.php");
}
 ?>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>E-lunetterie ::. Application de gestion de la lunetterie de HOLY VISION</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
		
        
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS  -->
        <link href="assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />   
        <link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
		
        <link href="assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
		
	    <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
       
       
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        
         <!--debut css tableau-->
       
        <!-- BEGIN PAGE LEVEL PLUGINS -->
		<link href="assets/pages/css/profile.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/plugins/typeahead/typeahead.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/pages/css/profile-2.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        
        <!--end css-->
       <link rel="stylesheet" type="text/css" href="stylemed.css" />
        <link rel="shortcut icon" href="favicon.ico" />
    
    
    </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">

        <!-- BEGIN HEADER -->
        <?php
        include_once("entete.php")?>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- BEGIN SIDEBAR -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- BEGIN SIDEBAR MENU -->
                    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                        <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                        <li class="sidebar-toggler-wrapper hide">
                            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                            <div class="sidebar-toggler">
                                <span></span>
                            </div>
                            <!-- END SIDEBAR TOGGLER BUTTON -->
                        </li>
                        <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
                        <li class="nav-item <?php if(isset($_GET['p'])AND($_GET['p']=='liste_prod'  || $_GET['p']=='liste_caisse'|| $_GET['p']=='liste_pvte'|| $_GET['p']=='add_pvte' || $_GET['p']=='up_pvte'|| $_GET['p']=='liste_marque' || $_GET['p']=='up_marque' || $_GET['p']=='add_marque' || $_GET['p']=='liste_vend' || $_GET['p']=='liste_commerc' || $_GET['p']=='liste_medp' || $_GET['p']=='up_medp'|| $_GET['p']=='add_medp' || $_GET['p']=='liste_fam' || $_GET['p']=='liste_user' || $_GET['p']=='liste_grp' || $_GET['p']=='up_prod' || $_GET['p']=='add_prod' || $_GET['p']=='up_caisse'|| $_GET['p']=='add_caisse' || $_GET['p']=='liste_vend' || $_GET['p']=='add_vend'||$_GET['p']=='up_vend'|| $_GET['p']=='up_commerc' || $_GET['p']=='add_commerc' || $_GET['p']=='liste_fourn' || $_GET['p']=='up_fourn' || $_GET['p']=='add_fourn' || $_GET['p']=='up_fam' || $_GET['p']=='add_fam'|| $_GET['p']=='up_grp' || $_GET['p']=='add_grp' || $_GET['p']=='up_user' || $_GET['p']=='add_user' || $_GET['p']=='liste_trav' || $_GET['p']=='up_trav' || $_GET['p']=='add_trav' || $_GET['p']=='liste_dci' || $_GET['p']=='up_dci' || $_GET['p']=='add_dci' || $_GET['p']=='liste_clt' || $_GET['p']=='up_fourn' || $_GET['p']=='up_acte' || $_GET['p']=='liste_acte' || $_GET['p']=='liste_bon' || $_GET['p']=='up_bon' || $_GET['p']=='add_bon' || $_GET['p']=='liste_sfam' || $_GET['p']=='up_sfam' || $_GET['p']=='add_sfam' || $_GET['p']=='liste_autref' || $_GET['p']=='up_autref' || $_GET['p']=='add_autref'|| $_GET['p']=='add_fourn')){ echo 'start active open '; }?> ">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="icon-settings"></i>
                                <span class="title">Parametres</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item <?php if(isset($_GET['p']) AND ($_GET['p']=='liste_prod' || $_GET['p']=='up_prod' || $_GET['p']=='add_prod')){ echo 'start active open '; }?>">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_prod" class="nav-link ">
                                        <span class="title">produits</span>
                                    </a>
                                </li>
								  <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_fam' || $_GET['p']=='up_fam' || $_GET['p']=='add_fam'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_fam" class="nav-link ">
                                        <span class="title">Categorie des produits</span>
                                    </a>
                                </li>
								 <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_sfam' || $_GET['p']=='up_sfam' || $_GET['p']=='add_sfam'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_sfam" class="nav-link ">
                                        <span class="title">Sous Categorie prod.</span>
                                    </a>
                                </li>
								<li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_autref' || $_GET['p']=='up_autref' || $_GET['p']=='add_autref'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_autref" class="nav-link ">
                                        <span class="title">Autres Categories</span>
                                    </a>
                                </li>
								 <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_marque' || $_GET['p']=='up_marque' || $_GET['p']=='add_marque'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_marque" class="nav-link ">
                                        <span class="title">Marque</span>
                                    </a>
                                </li>
								 <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_pvte' || $_GET['p']=='up_pvte' || $_GET['p']=='add_pvte'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_pvte" class="nav-link ">
                                        <span class="title">Boutique</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_caisse' || $_GET['p']=='up_caisse' || $_GET['p']=='add_caisse'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_caisse" class="nav-link ">
                                        <span class="title">Caisse</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_vend'|| $_GET['p']=='up_vend' || $_GET['p']=='add_vend'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_vend" class="nav-link ">
                                        <span class="title">Vendeurs</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_commerc' || $_GET['p']=='up_commerc' || $_GET['p']=='add_commerc'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_commerc" class="nav-link ">
                                        <span class="title">Commercial</span>
                                    </a>
                                </li>
								<li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_medp' || $_GET['p']=='up_medp' || $_GET['p']=='add_medp'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_medp" class="nav-link ">
                                        <span class="title">Medecin prescripteur</span>
                                    </a>
                                </li>
								<li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_bon' || $_GET['p']=='up_bon' || $_GET['p']=='add_bon'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_bon" class="nav-link ">
                                        <span class="title">Bon de reduction</span>
                                    </a>
                                </li>
								<li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_fourn' || $_GET['p']=='up_fourn' || $_GET['p']=='add_fourn'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_fourn" class="nav-link ">
                                        <span class="title">Fournisseur</span>
                                    </a>
                                </li>
                                
								<li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_clt' || $_GET['p']=='up_clt' || $_GET['p']=='add_clt'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_clt" class="nav-link ">
                                        <span class="title">Client</span>
                                    </a>
                                </li>
                               
                              
                                <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_user' || $_GET['p']=='up_user' || $_GET['p']=='add_user'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_user" class="nav-link ">
                                        <span class="title">Utilisateurs</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_grp' || $_GET['p']=='up_grp' || $_GET['p']=='add_grp'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_grp" class="nav-link ">
                                        <span class="title">Groupe utilisateurs</span>
                                    </a>
                                </li>
                                
                               
                            </ul>
                        </li>
                        
                     
						
						<li class="nav-item <?php if (isset($_GET['p']) AND($_GET['p']=='cmd_clt' || $_GET['p']=='event_clt')){ echo 'start active open '; }?> ">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="alert-info icon-user"></i>
                                <span class="title">Gestion des clients</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
							
                                 <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='event_clt'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=event_clt" class="nav-link ">
                                        <span class="title">Evenement</span>
                                    </a>
                                </li>                           
                          
                            </ul>
                        </li>
						
						  <li class="nav-item <?php if (isset($_GET['p']) AND($_GET['p']=='liste_vte' || $_GET['p']=='creer_vte')){ echo 'start active open '; }?> ">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="alert-info glyphicon glyphicon-shopping-cart"></i>
                                <span class="title">Caisse</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
							
								 <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_vte'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_vte" class="nav-link ">
                                        <span class="title">liste des ventes</span>
                                    </a>
                                </li>
                                 <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='creer_vte'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=creer_vte" class="nav-link ">
                                        <span class="title">Ajouter une vente</span>
                                    </a>
                                </li>                            
								
                            </ul>
							
							
                        </li>
						
						<li class="nav-item <?php if (isset($_GET['p']) AND($_GET['p']=='cmd_livr' || $_GET['p']=='add_livr'||$_GET['p']=='cmd_seuil' || $_GET['p']=='add_seuil'|| $_GET['p']=='cmd_fourn' || $_GET['p']=='add_cmdf')){ echo 'start active open '; }?> ">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="alert-info glyphicon glyphicon-sort"></i>
                                <span class="title">Stock</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
								<li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='cmd_seuil'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=cmd_seuil" class="nav-link ">
                                        <span class="title">Stock de securit&eacute;</span>
                                    </a>
                                </li> 								 
								
								<li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='cmd_fourn'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=cmd_fourn" class="nav-link ">
                                        <span class="title">Commande fournisseur</span>
                                    </a>
                                </li> 								 
								
								<li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='cmd_livr' ){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=cmd_livr" class="nav-link ">
                                        <span class="title">Livraison fournisseur</span>
                                    </a>
                                </li> 								 
								
                            </ul>
							
                        </li>
						<li class="nav-item <?php if (isset($_GET['p']) AND($_GET['p']=='liste_stock')){ echo 'start active open '; }?> ">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="alert-info glyphicon glyphicon-lock"></i>
                                <span class="title">Inventaire</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
							
                                 <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_stock'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_stock" class="nav-link ">
                                        <span class="title">Reguariser le stock</span>
                                    </a>
                                 </li> 
																 
								
                            </ul>
							
							
                        </li>
						
						<li class="nav-item <?php if (isset($_GET['p']) AND($_GET['p']=='liste_fact')){ echo 'start active open '; }?> ">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="alert-info fa fa-money"></i>
                                <span class="title">Facturation</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
							
								 <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='liste_fact'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=liste_fact" class="nav-link ">
                                        <span class="title">Recherche de facures</span>
                                    </a>
                                </li>
                                                      
								
                            </ul>
							
							
                        </li>
						
					 <li class="nav-item <?php if (isset($_GET['p']) AND($_GET['p']=='hist_vteb' || $_GET['p']=='hist_vtepr' || $_GET['p']=='hist_vtep' || $_GET['p']=='hist_achat' || $_GET['p']=='hist_vtea')){ echo 'start active open '; }?> ">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="glyphicon glyphicon-dashboard"></i>
                                <span class="title">Tableau de Bord</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='hist_vteb'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=hist_vteb" class="nav-link ">
                                        <span class="title">Hist des ventes par boutiques</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='hist_vtepr'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=hist_vtepr" class="nav-link ">
                                        <span class="title">Hist des vente par produit</span>
                                    </a>
                                </li>
								
								 <li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='hist_vtep'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=hist_vtep" class="nav-link ">
                                        <span class="title">Hist des vente par période</span>
                                    </a>
                                </li>
								<li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='hist_vtea'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=hist_vtea" class="nav-link ">
                                        <span class="title">Hist des ventes annuelles </span>
                                    </a>
                                </li>
								<li class="nav-item <?php if(isset($_GET['p']) AND $_GET['p']=='hist_achat'){ echo 'start active open '; }?> ">
                                    <a href="<?= $_SESSION['proflis']?>.php?p=hist_achat" class="nav-link ">
                                        <span class="title">Hist des achats annuelles </span>
                                    </a>
                                </li>
								
                            </ul>
                        </li>
					   
                    </ul>
                    <!-- END SIDEBAR MENU -->
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- ESPACE DE TRAVAIL -->
                <div class="page-content">
                  <?php if(!empty($_GET['p'])){include $_GET['p'].'.php';
				  $tabUrl = parse_url ( $_SERVER [ 'REQUEST_URI' ] ) ;
				  $redir = $tabUrl [ 'query' ];
				  $_SESSION['page'] = $redir;
				 
				  }
					  else{include 'acceuil.php';} ?>
			    </div>
					  <!--END ESPACE DE TRAVAIL-->
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <!-- BEGIN QUICK SIDEBAR -->
           <?php include('traitement_not.php'); ?>

<!-- BEGIN QUICK SIDEBAR -->
            <!-- END QUICK SIDEBAR -->
			
			<!-- END QUICK SIDEBAR -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner"> &copy; Tous droits réservés Mars 2017
            </div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
	        
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>
        <script src="assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/horizontal-timeline/horozontal-timeline.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
		<script src="assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
		<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
        <script src="assets/global/plugins/gmaps/gmaps.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
		<!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/dashboard.min.js" type="text/javascript"></script>
		 <script src="assets/pages/scripts/profile.min.js" type="text/javascript"></script>
		  <script src="assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
		
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
		<!-- END THEME LAYOUT SCRIPTS -->
		
        
        <!--script tableau--> 
		
		<!-- BEGIN PAGE LEVEL PLUGINS -->
		
        <script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
		
		 <script src="assets/global/plugins/typeahead/handlebars.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/typeahead/typeahead.bundle.min.js" type="text/javascript"></script>
		 <script src="assets/global/plugins/nouislider/wNumb.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/nouislider/nouislider.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
		
		<script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/table-datatables-buttons.min.js" type="text/javascript"></script>
		<script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
		<script src="assets/pages/scripts/table-datatables-editable.min.js" type="text/javascript"></script>
		
        <!-- END PAGE LEVEL SCRIPTS -->
	
        <!--mes script js active.js-->
		<script src="affiche.js" type="text/javascript"></script>
		<script type="text/javascript" src="scriptmed.js"></script>
		<!-- jQuery easing plugin -->
		<script src="jquery.easing.min.js" type="text/javascript"></script>
		
				<!--end-->
    </body>

</html>