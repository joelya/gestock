<?php 

		/**
		   *	AJOUTER FOURNISSEUR
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//RECUPERATION DES VARIABLES
			$marque_lib = htmlentities(trim($_POST['marque_lib']));
		    //GESTION DES ERREURS DE SAISIE
			 $errors = array();
			 $valid=true;
         
		 if(empty($marque_lib))
        {
            $errors[0]='Veuillez saisir le libell&eacute;';
            $valid=false;
        }
	
		
        if($valid == true){
		
			//Insertion 
			//var_dump($_POST);exit;
			  $sql = "INSERT INTO `marque`(`marque_lib`) VALUES(?);
";	

		$req = $pdo->prepare($sql);
		
		$req->execute(array($marque_lib));
		
		
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
			echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_marque&insert=ok"</script>';
			
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
                              <a href="<?= $_SESSION['proflis']?>.php?p=liste_marque"><span class="caption"> Liste des marques</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter marque</span></a>
                              
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
                                             <div class="caption">Ajouter une marque</div>
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
                                                           <label class="col-md-4 control-label">Libellé: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="marque_lib" id="marque_lib" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['marque_lib'])?$_POST['marque_lib']:''; ?>" />
                                                              <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
													   </div></div>
													</div>
													<br>
													<div class="row"> 
													<div class="col-md-5 col-md-offset-2">
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
