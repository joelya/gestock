<?php 
	//liste des profils
	$condition1='';
		if ($_SESSION['profile']<>6) {
                $condition1.=" AND  prof_id <>'".$_SESSION['profile']."'";
             }
	$sql_prof = "SELECT * FROM profil WHERE flag=1 AND 1=1 ".$condition1." ORDER BY prof_lib ASC";
	$list_prof = $pdo->prepare($sql_prof);
    $list_prof->execute();
	
	  //end liste profils
	//LISTE BOUTIQUE
	$condition1='';
		if ($_SESSION['boutique']<>6) {
                $condition1.=" AND  pvte_id ='".$_SESSION['boutique']."'";
             }
$sql_pvte = "SELECT `pvte_id`, `pvte_code`, `pvte_lib`, `pvt_contact`, `pvt_adresse` FROM `point_vente` WHERE 1=1".$condition1;
	$list_btq = $pdo->prepare($sql_pvte);
    $list_btq->execute();
	
		/**
		   *	AJOUTER UTILISATEUR
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//RECUPERATION DES VARIABLES
			
			$prof_id = htmlentities(trim($_POST['prof_id']));
			$pvte_id = htmlentities(trim($_POST['pvte_id']));
		 	$auth_nom = addslashes(htmlentities(trim($_POST['auth_nom'])));
			$auth_pnom = addslashes(htmlentities(trim($_POST['auth_pnom'])));
			$auth_user = addslashes(htmlentities(trim($_POST['auth_user'])));
			$auth_pwd = htmlentities(trim($_POST['auth_pwd']));
			$auth_pwdCrypt = md5($auth_pwd);
			
			
		    //GESTION DES RESTRICTIONS
			 $errors = array();
			 $valid=true;
         
        if(empty($auth_nom))
        {
            $errors[0]='Veuillez saisir un nom';
            $valid=false;
        }
		
		if(empty($auth_pnom))
        {
            $errors[1]='Veuillez saisir un prenom';
            $valid=false;
        }
		if(empty($auth_user) || strlen($auth_user)<3)
        {
            $errors[2]='Veuillez saisir un login de 3 caract&egrave;re minimum';
            $valid=false;
        }
		if($prof_id ==6 AND $pvte_id<>6){
			
		 $errors[6]='selection non conforme';
            $valid=false;
		}
		if(empty($auth_pwd))
        {
            $errors[3]='Veuillez saisir un mot de passe';
            $valid=false;
        }
        elseif (strlen($auth_pwd) < 6) {
			 $errors[4]='le mot de passe doit contenir au moins 6 caractères';
            $valid=false;
}
				
        if($valid == true){
			$dateMod = date("Y-m-d").' '.date("H:i:s");
			//Insertion 
			  $sql = "INSERT INTO `authentification`(`prof_id`,`pvte_id`, `auth_nom`, `auth_pnom`, `auth_user`, `auth_pwd`, `auth_date_con`, `auth_date_crea`, `auth_date_mod`, `flag`)
    VALUES (?, ?, ?, ?, ?,?, ?, ?, ?,?);
";	
		$req = $pdo->prepare($sql);
		$req->execute(array($prof_id,$pvte_id,$auth_nom,$auth_pnom,$auth_user,$auth_pwdCrypt,$dateMod,$dateMod,$dateMod,1));
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
			echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_user&insert=ok"</script>';
			
			
					//header('Location:page.php?p=liste_ent&ent_id='.$up);
			}
	 }
	
		
?>
 <!-- BEGIN PAGE BAR -->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                              <a href="<?= $_SESSION['proflis']?>.php">Accueil</a>
                                <i class="fa fa-circle"></i>
                            </li>
                             <li>
                              <a href="<?= $_SESSION['proflis']?>.php?p=liste_user"><span class="caption"> Liste des Utilisateurs</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter Utilisateurs</span></a>
                              
                            </li>
                        </ul>
                       
                    </div>
                    <br/>
                    <div class="note note-success">Les champs marqués d'un astérisque <span style="color:#F00"> (*)</span> 
					sont obligatoires </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <!-- END PAGE TITLE-->
					 <div class="row">
                        <div class="col-md-12">
                            <div class="tabbable-line boxless tabbable-reversed">
                                 <div class="tab-content">
                                 
								<form action="" method="post" class="form-horizontal">
                                        <div class="portlet box blue-hoki">
                                            <div class="portlet-title">
                                             <div class="caption">Ajouter Utilisateur</div>
                                          </div>
                                            <div class="portlet-body form">
                                            
                                                            
                                                <!-- BEGIN FORM-->
											
                                                    <div class="form-actions top">
                                                        
                                                    </div>
                                                    <div class="form-body">
													<div class="row">
													<div class="col-md-12">
													<div class="row">
													<div class="form-group">
                                                            <label class="col-md-4 control-label">Groupe utilisateur:</label>
                                                            <div class="col-md-7">
                                                               <select class="bs-select form-control" data-live-search="true" data-size="8" name="prof_id" id="prof_id" >
																<?php foreach ($list_prof as $result_prof): ?>
																<option value="<?= $result_prof['prof_id']; ?>" >
																
																<?= $result_prof['prof_desc']; ?></option>
																<?php endforeach; ?>
																</select>
														    </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Boutique :</label>
                                                            <div class="col-md-7">
                                                               <select class="bs-select form-control" data-live-search="true" data-size="8" name="pvte_id" id="pvte_id" >
																<?php foreach ($list_btq as $result_btq): ?>
																<option value="<?= $result_btq['pvte_id']; ?>" >
																
																<?= $result_btq['pvte_lib']; ?></option>
																<?php endforeach; ?>
															  </select>
													          <?php if(!empty($errors[6])): ?>
                                                               <span class="alert-danger">
                                                               <?= $errors[6]; ?>
                                                               </span>
                                                               <?php endif; ?>
                                                            </div>
                                                        </div>
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Nom : <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="auth_nom" id="auth_nom" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['auth_nom'])?$_POST['auth_nom']:''; ?>" />
                                                              <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Prenom: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="auth_pnom" id="auth_pnom" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['auth_pnom'])?$_POST['auth_pnom']:''; ?>" />
                                                              <?php if(!empty($errors[1])): ?>
															   <span class="alert-danger"><?= $errors[1]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Login: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="auth_user" id="auth_user" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['auth_user'])?$_POST['auth_user']:''; ?>" />
                                                              <?php if(!empty($errors[2])): ?>
															   <span class="alert-danger"><?= $errors[2]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Mot de passe: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="password" name="auth_pwd" id="auth_pwd" size="45" maxlength="255" class="form-control" value="" />
                                                              <?php if(!empty($errors[3])): ?>
															   <span class="alert-danger"><?= $errors[3]; ?></span>
															 <?php endif; ?>
                                                             <?php if(!empty($errors[4])): ?>
															   <span class="alert-danger"><?= $errors[4]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
                                                        
													</div>
													<br>
													<div class="row"> 
													<div class="col-md-8 col-md-offset-4">
														<div>
														<input class="btn green" type="submit" name="valid_info" value="Valider" />
														 <input class="btn default" type="button" name="annuler" value="Annuler" />
                                                         
                                                      
														</div>
													</div>                      
													</div>
												</div>
												</div>
                                               </div>
                                           </form>
								 
                                                <!-- END FORM-->
                                  </div>
                            </div>
							
						</div>
					  </div>


