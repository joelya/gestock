<?php 

		/**
		   *	AJOUTER FOURNISSEUR
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//RECUPERATION DES VARIABLES
			$pvte_code = htmlentities(trim($_POST['pvte_code']));
			$pvte_lib = htmlentities(trim($_POST['pvte_lib']));
			$pvt_contact = htmlentities(trim($_POST['pvt_contact']));
			$pvt_adresse = htmlentities(trim($_POST['pvt_adresse']));
		    //GESTION DES ERREURS DE SAISIE
			 $errors = array();
			 $valid=true;
         
        if(empty($pvte_code))
        {
            $errors[0]='Veuillez saisir le code du point de vente';
            $valid=false;
        }
		 if(empty($pvte_lib))
        {
            $errors[1]='Veuillez saisir le libell&eacute; du point de vente';
            $valid=false;
        }
		 if(empty($pvt_contact))
        {
            $errors[2]='Veuillez saisir le contact du point de vente';
            $valid=false;
        }
		 if(empty($pvt_adresse))
        {
            $errors[3]='Veuillez saisir l\'adresse du point de vente';
            $valid=false;
        }
		
        if($valid == true){
		
			//Insertion 
			//var_dump($_POST);exit;
			  $sql = "INSERT INTO `point_vente`(`pvte_code`, `pvte_lib`, `pvt_contact`, `pvt_adresse`) VALUES(?,?,?,?);
";	

		$req = $pdo->prepare($sql);
		
		$req->execute(array($pvte_code,$pvte_lib,$pvt_contact,$pvt_adresse));
		
		
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
			echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_pvte&insert=ok"</script>';
			
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
                              <a href="<?= $_SESSION['proflis']?>.php?p=liste_pvte"><span class="caption"> Liste des boutiques</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter boutique</span></a>
                              
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
                                             <div class="caption">Ajouter une boutique</div>
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
                                							  <input type="text" name="pvte_code" id="pvte_code" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['pvte_code'])?$_POST['pvte_code']:''; ?>" />
                                                              <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
															<div class="form-group">
                                                            <label class="col-md-4 control-label">Libell&eacute;: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="pvte_lib" id="pvte_lib" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['pvte_lib'])?$_POST['pvte_lib']:''; ?>" />
                                                              <?php if(!empty($errors[1])): ?>
															   <span class="alert-danger"><?= $errors[1]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
													</div></div>
													<div class="col-md-6">
													   <div class="row">
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Contact: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="pvt_contact" id="pvt_contact" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['pvt_contact'])?$_POST['pvt_contact']:''; ?>" />
                                                              <?php if(!empty($errors[2])): ?>
															   <span class="alert-danger"><?= $errors[2]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
                                                        	<div class="form-group">
                                                            <label class="col-md-4 control-label">Adresse: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="pvt_adresse" id="pvt_adresse" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['pvt_adresse'])?$_POST['pvt_adresse']:''; ?>" />
                                                              <?php if(!empty($errors[3])): ?>
															   <span class="alert-danger"><?= $errors[3]; ?></span>
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
