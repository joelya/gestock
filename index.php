<?php
	include('./model/connexion.php');
	include('./function/pwd.php');
	$pdo=connect();
	session_start();
	if(@$_GET['kill']){
	// On écrase le tableau de session
	//$_SESSION['page'] = 1;
	unset($_SESSION['page']); 
	unset($_SESSION['auth_id']);
	unset($_SESSION['user']);
	unset($_SESSION['name']);
	unset($_SESSION['pname']);
	unset($_SESSION['proflis']);
	unset($_SESSION['image']);
	unset($_SESSION['fullimage']);
	unset($_SESSION['codep']);
	unset($_SESSION['pageS']);
	unset($_SESSION['stmt_count']);
	unset($_SESSION['countimage000']);
	//session_destroy();
		}
	//var_dump($_SESSION);exit;
    $error = false;
		if(!empty($_POST['login']) && !empty($_POST['pwd']))
		{
		
			$login = $_POST['login'];
			$pwd = $_POST['pwd'];
			$pwdDecrypt = md5($_POST['pwd']);
			$sql = "SELECT * FROM authentification INNER JOIN profil ON authentification.prof_id = profil.prof_id JOIN point_vente ON point_vente.pvte_id = authentification.pvte_id  WHERE authentification.flag=1 AND auth_user = '".$login."' AND auth_pwd = '".$pwdDecrypt."'";
			$req = $pdo->prepare($sql);
			$req->execute();
			$auth = $req->fetch();
			$count = $req->rowCount();
			if ($count == 1)
			{
				$_SESSION['auth_id'] = $auth['auth_id'];
				$_SESSION['pvte_lib'] = $auth['pvte_lib'];
				$_SESSION['boutique'] = $auth['pvte_id'];
				$_SESSION['user'] = $auth['auth_user'];
				$_SESSION['name'] = $auth['auth_nom'];
				$_SESSION['pname'] = $auth['auth_pnom'];
				$_SESSION['profile'] = $auth['prof_id'];
				$_SESSION['proflis'] = $auth['prof_lib'];
				$_SESSION['image'] = $auth['lien_img'];
				$_SESSION['fullimage'] = $auth['lien_img_reel'];
				
				/* GESTION HEUR DE CONNEXION*/
				$dateMod = date("Y-m-d").' '.date("H:i:s");
				$sql_date_con = "UPDATE `db_doctor_secu`.`authentification` SET `auth_date_con`= '".$dateMod."' WHERE `auth_id` ='".$_SESSION['auth_id']."'";                                           
				$pdo->query($sql_date_con);

				$sql2 = "SELECT lien_img FROM authentification  WHERE lien_img = '0' AND auth_id = '".$_SESSION['auth_id']."'";
				$req2 = $pdo->prepare($sql2);
				$req2->execute();
				$countimg = $req2->rowCount();
				$_SESSION['countimage000'] = $countimg ;
				if(@$_SESSION['page']){
				header("Location:".$_SESSION['proflis'].".php?".$_SESSION['page']."");
				
				}
				elseif(!@$_SESSION['page']){
											  
				header("Location:".$_SESSION['proflis'].".php?p=Acceuil&con=ok");
				
				}
			}
			else
			{
				
				$error = true;
			}
		}
	
?>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>E-lunetterie.::.logiciel de gestion de lunetterie</title>
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
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="assets/pages/css/login.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/pages/css/login-5.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- END HEAD -->

    <body class=" login">
        <!-- BEGIN : LOGIN PAGE 5-1 -->
        <div class="user-login-5">
            <div class="row bs-reset">
                <div class="col-md-6 bs-reset">
                    <div class="login-bg" style="background-image:url(assets/pages/img/login/bg1.jpg)">
                    </div>
                </div>
                
                <!--debut container-->
                
            <div class="col-md-6 login-container bs-reset">
                
            <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <form class="login-form" method="post"  action="">
			<center style="padding:20px"><img src="assets/pages/img/login/ldf.png" width='300px' /></center>
                <p style="color:#FFFFFF;font-size: 19px;">Authentification</p>
                
                <div class="form-group">
                    
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">Nom utilisateur</label>
                    <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Nom utilisateur" name="login" /> </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Mot de passe</label>
                    <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Mot de passe" name="pwd" /></div>
                <div class="form-actions">
                   <center><button type="submit" name="auth" class="btn primary uppercase">Connexion</button></center> 
                </div>
             <br/> <span  style="color:#FFF;">© Tous droits réservés </span>
            </form>
			<br/><br/>
			<?php if ($error ==true): ?>
			<span class="alert alert-block alert-danger fade in">
               <?php echo "Veuillez verifiez vos paramètres de connexion"; ?>  
			</span>
			<?php endif; ?>
            <!-- END LOGIN FORM -->
        
        </div>
                <!--end container-->
                </div> 
            </div>
        </div>
        <!-- END : LOGIN PAGE 5-1 -->
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
        <script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/login-5.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>

</html>