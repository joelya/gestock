<?php 
	
	$sql_pvte = "SELECT `pvte_id`, `pvte_code`, `pvte_lib`, `pvt_contact`, `pvt_adresse` FROM `point_vente`";
	$liste_pvte = $pdo->prepare($sql_pvte);
    $liste_pvte->execute();
		/**
		   *	AJOUTER DIRECTION
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//RECUPERATION DES VARIABLES
		 	$caiss_lib = addslashes (htmlentities(trim($_POST['caiss_lib'])));
			$caiss_code = addslashes (htmlentities(trim($_POST['caiss_code'])));
			$pvte_id = addslashes (htmlentities(trim($_POST['pvte_id'])));
			//GESTION DES RESTRICTIONS
			 $errors = array();
			 $valid=true;
      
		 if(empty($caiss_lib))
        {
            $errors[0]='Veuillez saisir un libell&eacute;';
            $valid=false;
        }
		 if(empty($caiss_code))
        {
            $errors[1]='Veuillez saisir un code';
            $valid=false;
        }
            
        if(empty($pvte_id))
        {
            $errors[2]='Veuillez selectionnez un point de vente';
            $valid=false;
        }
        if($valid == true){
			//Insertion 
			  $sql = "INSERT INTO `caisse`(`pvte_id`, `caiss_code`, `caiss_lib`, `caiss_flag_actif`) VALUES(?, ?, ?, ?);
";	
		$req = $pdo->prepare($sql);
		$req->execute(array($pvte_id,$caiss_code,$caiss_lib,1));
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
			echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_caisse&insert=ok"</script>';
			
			
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
                              <a href="<?= $_SESSION['proflis']?>.php?p=liste_caisse"><span class="caption"> Liste des Caisses</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter Caisses</span></a>
                              
                            </li>
                        </ul>
                       
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE--><!-- END PAGE TITLE-->
					 <div class="row">
                        <div class="col-md-12">
                            <div class="tabbable-line boxless tabbable-reversed">
                                 <div class="tab-content">
                                  
								
                                        <div class="portlet box blue-hoki">
                                            <div class="portlet-title">
                                             <div class="caption">Ajouter Caisse</div></div>
                                            <div class="portlet-body form">
  <form action="" method="post" class="form-horizontal">                                          
                                                <!-- BEGIN FORM-->
											
                                                    <div class="form-actions top">
                                                        
                                                    </div>
                                                    <div class="form-body">
													<div class="row">
													<div class="col-md-12">
													<div class="row">
													 
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Libell&eacute;</label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="caiss_lib" id="caiss_lib" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['caiss_lib'])?$_POST['caiss_lib']:''; ?>" />
                                                              <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Code:</label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="caiss_code" id="caiss_code" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['caiss_code'])?$_POST['caiss_code']:''; ?>" />
                                                              <?php if(!empty($errors[1])): ?>
															   <span class="alert-danger"><?= $errors[1]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Boutique:</label>
                                                            <div class="col-md-7">
                                							  <select class="bs-select form-control" data-live-search="true" data-size="8" name="pvte_id" id="pvte_id" >
															<?php foreach ($liste_pvte as $result_pvte): ?>
																<option value="<?= $result_pvte['pvte_id']; ?>" >
																
																<?= $result_pvte['pvte_lib']; ?></option>
																<?php endforeach; ?>
															  </select>
                                                              <?php if(!empty($errors[2])): ?>
															   <span class="alert-danger"><?= $errors[2]; ?></span>
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


