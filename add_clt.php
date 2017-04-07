<?php 


		/**
		   *	AJOUTER FOURNISSEUR
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//RECUPERATION DES VARIABLES
			//var_dump($_POST);exit;
			$clt_nom = htmlentities(trim($_POST['clt_nom']));
			$clt_pren = htmlentities(trim($_POST['clt_pren']));
			$clt_adresse = htmlentities(trim($_POST['clt_adresse']));
			$clt_bur = htmlentities(trim($_POST['clt_bur']));
			$clt_porte = htmlentities(trim($_POST['clt_porte']));
			$clt_contact = htmlentities(trim($_POST['clt_contact']));
			$clt_age = implode("-", array_reverse(explode("/", $_POST['clt_age'])));
			//var_dump($_POST);exit;
			//GESTION DES ERREURS DE SAISIE
			 $errors = array();
			 $valid=true;
         
        if(empty($clt_nom))
        {
            $errors[0]='Veuillez saisir le nom du client';
            $valid=false;
        }
		 if(empty($clt_pren))
        {
            $errors[1]='Veuillez saisir le pr&eacute;nom du client';
            $valid=false;
        }
		 if(empty($clt_adresse))
        {
            $errors[2]='Veuillez saisir l\'addresse du client';
            $valid=false;
        }
	
		 if(empty($clt_contact))
        {
            $errors[3]='Veuillez saisir le contact du client';
            $valid=false;
        }
		 if(empty($clt_age))
        {
            $errors[4]='Veuillez saisir la date de naissance du client';
            $valid=false;
        }
		
        if($valid == true){
		
			//CREATION client
			  $sql = "INSERT INTO `client`(`clt_nom`, `clt_pren`,clt_age, `clt_adresse`, `clt_contact`,`clt_bur`, `clt_porte`, `clt_flag_actif`) VALUES(?,?,?,?,?,?,?,?);
";	

		$req = $pdo->prepare($sql);
		
		$req->execute(array($clt_nom,$clt_pren,$clt_age,$clt_adresse,$clt_contact,$clt_bur,$clt_porte,1));
		
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
			echo '<script>location.href="page.php?p=liste_clt&insert=ok"</script>';
			
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
                              <a href="page.php?p=liste_clt"><span class="caption"> Liste des clients</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter un client</span></a>
                              
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
                                             <div class="caption">Ajouter un Client</div>
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
                                                            <label class="col-md-4 control-label">Nom: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="clt_nom" id="clt_nom" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['clt_nom'])?$_POST['clt_nom']:''; ?>" />
                                                              <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
                                                        	<div class="form-group">
                                                            <label class="col-md-4 control-label">Pr&eacute;nom: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="clt_pren" id="clt_pren" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['clt_pren'])?$_POST['clt_pren']:''; ?>" />
                                                              <?php if(!empty($errors[1])): ?>
															   <span class="alert-danger"><?= $errors[1]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Date de naissance : <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                                <input type="text" name="clt_age" id="clt_age" class="form-control form-control-inline date-picker" value="<?= !empty($_POST['clt_age'])?$_POST['clt_age']:''; ?>"  size="45" maxlength="255" />
                                                                <?php if(!empty($errors[4])): ?>
                                                                <span class="alert-danger"><?= $errors[4]; ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                    </div>  
                                                        	<div class="form-group">
                                                            <label class="col-md-4 control-label">Adresse: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="clt_adresse" id="clt_adresse" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['clt_adresse'])?$_POST['clt_adresse']:''; ?>" />
                                                              <?php if(!empty($errors[2])): ?>
															   <span class="alert-danger"><?= $errors[2]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
													</div></div>
													<div class="col-md-6">
													   <div class="row">
                                                       <div class="form-group">
                                                            <label class="col-md-4 control-label">Telephonne: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="clt_contact" id="clt_contact" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['clt_contact'])?$_POST['clt_contact']:''; ?>" />
                                                              <?php if(!empty($errors[3])): ?>
															   <span class="alert-danger"><?= $errors[3]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Bureau:</label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="clt_bur" id="clt_bur" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['clt_bur'])?$_POST['clt_bur']:''; ?>" />
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                        	<div class="form-group">
                                                            <label class="col-md-4 control-label">Porte:</label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="clt_porte" id="clt_porte" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['clt_porte'])?$_POST['clt_porte']:''; ?>" />
                                                            </div>
                                                            
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

