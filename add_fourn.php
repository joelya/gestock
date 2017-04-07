<?php 

		/**
		   *	AJOUTER FOURNISSEUR
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//RECUPERATION DES VARIABLES
			$fourn_code = htmlentities(trim($_POST['fourn_code']));
			$fourn_contact = htmlentities(trim($_POST['fourn_contact']));
			$fourn_lib = htmlentities(trim($_POST['fourn_lib']));
			$fourn_addr = $_POST['fourn_addr'];
			$fourn_email = $_POST['fourn_email'];
		    //GESTION DES ERREURS DE SAISIE
			 $errors = array();
			 $valid=true;
         
        if(empty($fourn_code))
        {
            $errors[0]='Veuillez saisir le code fournisseur';
            $valid=false;
        }
		 if(empty($fourn_lib))
        {
            $errors[1]='Veuillez saisir le libell&eacute; du fournuisseur';
            $valid=false;
        }
		 if(empty($fourn_contact))
        {
            $errors[2]='Veuillez saisir le contact du fournisseur';
            $valid=false;
        }
		 if(empty($fourn_addr))
        {
            $errors[3]='Veuillez saisir l\'adresse du fournisseur';
            $valid=false;
        }
		
		if(empty($fourn_email))
        {
            $errors[4]='Veuillez saisir l\'email du fournisseur';
            $valid=false;
        }
		  
		
        if($valid == true){
		
			//Insertion 
			//var_dump($_POST);exit;
			  $sql = "INSERT INTO `fournisseur`(`fourn_code`, `fourn_lib`, `fourn_addr`, `fourn_contact`, `fourn_email`, `fourn_falg_actif`)  VALUES (?, ?, ?, ?, ?, ?);
";	

		$req = $pdo->prepare($sql);
		
		$req->execute(array($fourn_code,$fourn_lib,$fourn_addr,$fourn_contact,$fourn_email,1));
		
		
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
			echo '<script>location.href="page.php?p=liste_fourn&insert=ok"</script>';
			
			}
		
		
			}
	 
	 
?>
 <!-- BEGIN PAGE BAR -->
 

<body>
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                              <a href="page.php">Accueil</a>
                                <i class="fa fa-circle"></i>
                            </li>
                             <li>
                              <a href="page.php?p=liste_fourn"><span class="caption"> Liste des fournisseurs</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter fournisseurs</span></a>
                              
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
                                             <div class="caption">Ajouter un fournisseur</div>
                                          </div>
                                          
                                            <div class="portlet-body form">
                                   <form action="" method="post" class="form-horizontal">
                                                <!-- BEGIN FORM-->
											
                                                    <div class="form-actions top"></div>
													
                                     <div class="form-body">
													
													<div class="row">
													<div class="col-md-12">
													<div class="col-md-6">
													<div class="row">
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Code fournisseur: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="fourn_code" id="fourn_code" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['fourn_code'])?$_POST['fourn_code']:''; ?>" />
                                                              <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Libell&eacute;: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="fourn_lib" id="fourn_lib" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['fourn_lib'])?$_POST['fourn_lib']:''; ?>" />
                                                              <?php if(!empty($errors[1])): ?>
															   <span class="alert-danger"><?= $errors[1]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Contact: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="fourn_contact" id="fourn_contact" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['fourn_contact'])?$_POST['fourn_contact']:''; ?>" />
                                                              <?php if(!empty($errors[2])): ?>
															   <span class="alert-danger"><?= $errors[2]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
														
													</div></div>
													<div class="col-md-6">
													   <div class="row">
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Adresse :<span style="color:#F00">(*)</span></label>
                                                            
                                                            <div class="col-md-7">
                                                              <input type="text" name="fourn_addr" id="fourn_addr" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['fourn_addr'])?$_POST['fourn_addr']:''; ?>" />
                                                              <?php if(!empty($errors[3])): ?>
                                                              <span class="alert-danger">
                                                              <?= $errors[3]; ?>
                                                              </span>
                                                              <?php endif; ?>
                                                            </div>
                                                        </div>
                                                         <!--gestion des fournisseurs-->
                                                          <div class="form-group">
													       <label class="col-md-4 control-label">Email : <span style="color:#F00"> (*)</span> </label>
															<div class="col-md-7">
															  <input type="text" name="fourn_email" id="fourn_email" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['fourn_email'])?$_POST['fourn_email']:''; ?>" />
															</span>
															  <?php if(!empty($errors[4])): ?>
																<span class="alert-danger"><?= $errors[4]; ?></span>
															 <?php endif; ?>
                                                        </div>
														</div>
                                                        <!--end fourn-->
													   </div></div>
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
