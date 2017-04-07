<?php 

		/**
		   *	AJOUTER FOURNISSEUR
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//RECUPERATION DES VARIABLES
			$bon_code = htmlentities(trim($_POST['bon_code']));
			$bon_lib = htmlentities(trim($_POST['bon_lib']));
			$bon_mont = htmlentities(trim($_POST['bon_mont']));
		    //GESTION DES ERREURS DE SAISIE
			 $errors = array();
			 $valid=true;
         
        if(empty($bon_code))
        {
            $errors[0]='Veuillez saisir un code pour le bon de reduction';
            $valid=false;
        }
		 if(empty($bon_lib))
        {
            $errors[1]='Veuillez saisir un titre pour le bon de reduction';
            $valid=false;
        }
		 if(empty($bon_mont))
        {
            $errors[2]='Veuillez saisir le montant du bon de reduction';
            $valid=false;
        }
		
		
        if($valid == true){
				
			//Insertion 
			//var_dump($_POST);exit;
			  $sql = "INSERT INTO `bon_reduction`(`bon_code`, `bon_lib`,`bon_mont`)  VALUES (?, ?, ?);
";	

		$req = $pdo->prepare($sql);
		
		$req->execute(array($bon_code,$bon_lib,$bon_mont));
		
		
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
			echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_bon&insert=ok"</script>';
			
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
                              <a href="<?= $_SESSION['proflis']?>.php?p=liste_bon"><span class="caption"> Liste des bons de reduction</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter bons de reduction</span></a>
                              
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
                                             <div class="caption">Ajouter un bon de reduction</div>
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
                                                            <label class="col-md-4 control-label">Code: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="bon_code" id="bon_code" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['bon_code'])?$_POST['bon_code']:''; ?>" />
                                                              <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Titre: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="bon_lib" id="bon_lib" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['bon_lib'])?$_POST['bon_lib']:''; ?>" />
                                                              <?php if(!empty($errors[1])): ?>
															   <span class="alert-danger"><?= $errors[1]; ?></span>
															 <?php endif; ?>
														  </div>
                                                      </div>
														
													</div></div>
													<div class="col-md-6">
													   <div class="row">
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Montant: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="bon_mont" id="bon_mont" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['bon_mont'])?$_POST['bon_mont']:''; ?>" />
                                                              <?php if(!empty($errors[2])): ?>
															   <span class="alert-danger"><?= $errors[2]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
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
