 <script type="text/javascript" src="jquery-1.7.1.min.js"></script>
  <script type="text/javascript" src="liste.js"></script>
<?php 


		/**
		   *	AJOUTER FOURNISSEUR
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//RECUPERATION DES VARIABLES
			//var_dump($_POST);exit;
			$vend_code = htmlentities(trim($_POST['vend_code']));
			$vend_nom = htmlentities(trim($_POST['vend_nom']));
			$vend_prenom = htmlentities(trim($_POST['vend_prenom']));
			$vend_email = htmlentities(trim($_POST['vend_email']));
			$caiss_id = htmlentities(trim($_POST['caiss_id']));
			$vend_contact = htmlentities(trim($_POST['vend_contact']));
			$vend_sexe = htmlentities(trim($_POST['vend_sexe']));
			$pvte_id = htmlentities(trim($_POST['pvte_id']));
			$auth_user = addslashes(htmlentities(trim($_POST['auth_user'])));
			$auth_pwd = htmlentities(trim($_POST['auth_pwd']));
			$auth_pwdCrypt = md5($auth_pwd);
		    //GESTION DES ERREURS DE SAISIE
			 $errors = array();
			 $valid=true;
         
        if(empty($vend_code))
        {
            $errors[0]='Veuillez saisir le code du point de vente';
            $valid=false;
        }
		 if(empty($vend_nom))
        {
            $errors[1]='Veuillez saisir le libell&eacute; du point de vente';
            $valid=false;
        }
		 if(empty($vend_prenom))
        {
            $errors[2]='Veuillez saisir le contact du point de vente';
            $valid=false;
        }
		 if(empty($vend_email))
        {
            $errors[3]='Veuillez saisir l\'adresse du point de vente';
            $valid=false;
        }
		 if(empty($caiss_id))
        {
            $errors[4]='Veuillez saisir l\'adresse du point de vente';
            $valid=false;
        }
		 if(empty($vend_contact))
        {
            $errors[5]='Veuillez saisir l\'adresse du point de vente';
            $valid=false;
        }
		if(empty($auth_user) || strlen($auth_user)<3)
        {
            $errors[7]='Veuillez saisir un login de 3 caract&egrave;re minimum';
            $valid=false;
        }
		if(empty($auth_pwd))
        {
            $errors[8]='Veuillez saisir un mot de passe';
            $valid=false;
        }
        elseif (strlen($auth_pwd) < 6) {
			 $errors[9]='le mot de passe doit contenir au moins 6 caractères';
            $valid=false;
		}
		
        if($valid == true){
		     try{ 
			//CREATION VENDEUR
			  $sql = "INSERT INTO `vendeur`(`auth_id`,`pvte_id`,`caiss_id`, `vend_code`, `vend_nom`, `vend_prenom`, `vend_email`, `vend_sexe`, `vend_contact`, `vend_flag_actif`) VALUES(?,?,?,?,?,?,?,?,?,?);
";	

		$req = $pdo->prepare($sql);
		//var_dump($req,$_POST);exit;
		$req->execute(array(1,$pvte_id,$caiss_id,$vend_code,$vend_nom,$vend_prenom,$vend_email,$vend_sexe,$vend_contact,1));
		
		$vend_id1 = $pdo->lastInsertId();
		//CREATION COMPTE VENDEUR
		$dateMod = date("Y-m-d").' '.date("H:i:s");
		 $sql_user = "INSERT INTO `authentification`(`prof_id`,`pvte_id`, `auth_nom`, `auth_pnom`, `auth_user`, `auth_pwd`, `auth_date_con`, `auth_date_crea`, `auth_date_mod`, `flag`)
    VALUES (?, ?, ?, ?, ?,?, ?, ?,?, ?);
";	

		$req_user = $pdo->prepare($sql_user);
		$req_user->execute(array(2,$pvte_id,$vend_nom,$vend_prenom,$auth_user,$auth_pwdCrypt,$dateMod,$dateMod,$dateMod,1));
		$auth_id1 = $pdo->lastInsertId();
		
		//MISE A JOUR DU USER CREE DANS VENDEUR 
		$update = "UPDATE vendeur 
			SET auth_id = '".$auth_id1."'
			WHERE vend_id = '".$vend_id1."'";
		 
		$pdo->query($update);
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
			echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_vend&insert=ok"</script>';
			}
			catch(PDOException $e){
			
			$errors[7]='ce code vendeur est deja utilis&eacute;!';
			//echo $e->getMessage();
		}
		
			}
		
		
			}
	 
	 
?>
 <!-- BEGIN PAGE BAR -->
 

<body>
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                              <a href="<?= $_SESSION['proflis']?>.php">Accueil</a>
                                <i class="fa fa-circle"></i>
                            </li>
                             <li>
                              <a href="<?= $_SESSION['proflis']?>.php?p=liste_vend"><span class="caption"> Liste des vendeurs</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter un vendeur</span></a>
                              
                            </li>
                        </ul>
                       
                    </div><br/>
                    <div class="note note-success">Les champs marqués d'un astérisque <span style="color:#F00"> (*)</span> 
					sont obligatoires </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE--><!-- END PAGE TITLE-->
					 <div class="row">
                        <div class="col-md-12">
                            <div class="tabbable-line boxless tabbable-reversed">
                                 <div class="tab-content">
                                  
								
                                        <div class="portlet box blue-hoki">
                                          <div class="portlet-title">
                                             <div class="caption">Ajouter un vendeur</div>
                                          </div>
                                          
															
                                            <div class="portlet-body form">
                                   <form action="" method="post" class="form-horizontal">
                                                <!-- BEGIN FORM-->

                                                <div class="form-actions top"></div>
													
                                     <div class="form-body">
													<?php if(!empty($errors[7])): ?>
                                          <div class="alert alert-danger alert-dismissable">
																<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
																 <a href="" class="alert-link"><?= $errors[7]; ?></a>
														  </div>			 <?php endif; ?>
													<div class="row">
													<div class="col-md-12">
													<div class="col-md-6">
													<div class="row">
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Code: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="vend_code" id="vend_code" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['vend_code'])?$_POST['vend_code']:''; ?>" />
                                                              <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
															<div class="form-group">
                                                            <label class="col-md-4 control-label">Nom: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="vend_nom" id="vend_nom" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['vend_nom'])?$_POST['vend_nom']:''; ?>" />
                                                              <?php if(!empty($errors[1])): ?>
															   <span class="alert-danger"><?= $errors[1]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
                                                        	<div class="form-group">
                                                            <label class="col-md-4 control-label">Pr&eacute;nom: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="vend_prenom" id="vend_prenom" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['vend_prenom'])?$_POST['vend_prenom']:''; ?>" />
                                                              <?php if(!empty($errors[2])): ?>
															   <span class="alert-danger"><?= $errors[2]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
                                                        
                                                        	<div class="form-group">
                                                            <label class="col-md-4 control-label">Email: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="vend_email" id="vend_email" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['vend_email'])?$_POST['vend_email']:''; ?>" />
                                                              <?php if(!empty($errors[3])): ?>
															   <span class="alert-danger"><?= $errors[3]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
													</div></div>
													<div class="col-md-6">
													   <div class="row">
                                                       <div class="form-group">
                                                            <label class="col-md-4 control-label">Boutique: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <select id="localite_un" class="form-control" name="pvte_id">
                                                                <option value="">Choisissez une Boutique
                                                              </select>
                                                              <?php if(!empty($errors[4])): ?>
															   <span class="alert-danger"><?= $errors[4]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Caisse: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <select id="localite_deux" class="form-control" name="caiss_id">
                                                                <option value="">Choisissez une caisse
                                                              </select>
                                                              <?php if(!empty($errors[5])): ?>
															   <span class="alert-danger"><?= $errors[5]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
                                                        
                                                        	<div class="form-group">
                                                            <label class="col-md-4 control-label">Contact: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="vend_contact" id="vend_contact" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['vend_contact'])?$_POST['vend_contact']:''; ?>" />
                                                              <?php if(!empty($errors[6])): ?>
															   <span class="alert-danger"><?= $errors[6]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
													   </div>
                                                       <!---radio-->
                                                       <div class="form-group">
														<label class="col-md-4 control-label">Sexe:</label>
														<div class="col-md-7">
																   <div class="md-radio-inline">
																	<div class="md-radio">
																	<input type="radio" id="radio6" name="vend_sexe" class="md-radiobtn" value="1" checked>
																	<label  for="radio6">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>Masculin</label>
																	</div>
																	<div class="md-radio">
																	<input type="radio" id="radio7" name="vend_sexe" class="md-radiobtn" value="0">
																	<label for="radio7">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>Feminin</label>
																	</div>
															</div>
													     </div>
													  </div>														
                                                       <!--end radio-->
                                                       <div class="form-group">
                                                            <label class="col-md-4 control-label">Login: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="auth_user" id="auth_user" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['auth_user'])?$_POST['auth_user']:''; ?>" />
                                                              <?php if(!empty($errors[7])): ?>
															   <span class="alert-danger"><?= $errors[7]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Mot de passe: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="password" name="auth_pwd" id="auth_pwd" size="45" maxlength="255" class="form-control" value="" />
                                                              <?php if(!empty($errors[8])): ?>
															   <span class="alert-danger"><?= $errors[8]; ?></span>
															 <?php endif; ?>
                                                             <?php if(!empty($errors[9])): ?>
															   <span class="alert-danger"><?= $errors[9]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
</div>
                                                       
													</div>
													<br>
													<div class="row"> 
													<div class="col-md-7 col-md-offset-5">
														<div>
														<input class="btn green" type="submit" name="valid_info" value="Valider" />
														 <input class="btn default" type="button" name="annuler" value="Annuler" />
														</div>
													</div>                      
													</div>
									   </div>
                                   </form>                                

								 
                                            </div></div>         <!-- END FORM-->
                                  </div>
                            </div>
							
						</div>
					  </div>

</body>

