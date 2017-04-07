<?php 
	
		$sql_fam = "SELECT `fam_id`, `fam_code`, `fam_libel`, `fam_flag_actif` FROM `famille` WHERE fam_flag_actif=1 AND fam_id <>1";
	$liste_fam = $pdo->prepare($sql_fam);
    $liste_fam->execute();
		/**
		   *	AJOUTER DIRECTION
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//RECUPERATION DES VARIABLES
		 	$sfam_code = addslashes (htmlentities(trim($_POST['sfam_code'])));
			$sfam_lib = addslashes (htmlentities(trim($_POST['sfam_lib'])));
			$fam_id = addslashes (htmlentities(trim($_POST['fam_id'])));
			//GESTION DES RESTRICTIONS
			 $errors = array();
			 $valid=true;
      
		 if(empty($sfam_code))
        {
            $errors[0]='Veuillez saisir un code';
            $valid=false;
        }
		 if(empty($sfam_lib))
        {
            $errors[1]='Veuillez saisir un libell&eacute;';
            $valid=false;
        }
            
        if(empty($fam_id))
        {
            $errors[2]='Veuillez selectionnez la cath&eacute;gorie rattach&eacute;e';
            $valid=false;
        }
        if($valid == true){
			//Insertion 
			  $sql = "INSERT INTO `sous_famille`(`fam_id`, `sfam_lib`, `sfam_code`, `sfam_flag_actif`) VALUES(?, ?, ?, ?);
";	
		$req = $pdo->prepare($sql);
		$req->execute(array($fam_id,$sfam_lib,$sfam_code,1));
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
			echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_sfam&insert=ok"</script>';
			
			
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
                              <a href="<?= $_SESSION['proflis']?>.php?p=liste_sfam"><span class="caption"> Liste des sous cathegorie de produits</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter sous cathegorie de produits</span></a>
                              
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
                                             <div class="caption">Ajouter sous cathegorie de produit</div></div>
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
                                                            <label class="col-md-4 control-label">Code</label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="sfam_code" id="sfam_code" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['sfam_code'])?$_POST['sfam_code']:''; ?>" />
                                                              <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Libellé:</label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="sfam_lib" id="sfam_lib" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['sfam_lib'])?$_POST['sfam_lib']:''; ?>" />
                                                              <?php if(!empty($errors[1])): ?>
															   <span class="alert-danger"><?= $errors[1]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Cathegorie de produit :</label>
                                                            <div class="col-md-7">
                                							  <select class="bs-select form-control" data-live-search="true" data-size="8" name="fam_id" id="fam_id" >
															<?php foreach ($liste_fam as $result_fam): ?>
																<option value="<?= $result_fam['fam_id']; ?>" >
																
																<?= $result_fam['fam_libel']; ?></option>
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


