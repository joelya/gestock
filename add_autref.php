 <script type="text/javascript" src="jquery-1.7.1.min.js"></script>
  <script type="text/javascript" src="listeCat.js"></script>
<?php 
	
	$sql_sfam = "SELECT f.fam_libel,sf.sfam_id, sf.fam_id,sf.sfam_code,sf.sfam_lib, sf.sfam_flag_actif FROM sous_famille sf INNER JOIN famille f ON sf.fam_id=f.fam_id WHERE sf.sfam_flag_actif = 1  AND sfam_id <>1";
	$liste_sfam = $pdo->prepare($sql_sfam);
    $liste_sfam->execute();
		
		/**
		   *	AJOUTER DIRECTION
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//RECUPERATION DES VARIABLES
		 	$autref_code = addslashes (htmlentities(trim($_POST['autref_code'])));
			$autref_lib = addslashes (htmlentities(trim($_POST['autref_lib'])));
			$sfam_id = addslashes (htmlentities(trim($_POST['sfam_id'])));
			@$fam_id = htmlentities(trim($_POST['fam_id']));
			//GESTION DES RESTRICTIONS
			 $errors = array();
			 $valid=true;
      
		 if(empty($autref_code))
        {
            $errors[0]='Veuillez saisir un code';
            $valid=false;
        }
		 if(empty($autref_lib))
        {
            $errors[1]='Veuillez saisir un libell&eacute;';
            $valid=false;
        }
            
        if(empty($sfam_id))
        {
            $errors[3]='Veuillez selectionnez  la cath&eacute;gorie rattach&eacute;e';
            $valid=false;
        }    
        if(empty($sfam_id))
        {
            $errors[4]='Veuillez selectionnez  la sous cath&eacute;gorie rattach&eacute;e';
            $valid=false;
        }
        if($valid == true){
			//Insertion 
			  $sql = "INSERT INTO `autre_famille`(`fam_id`,`sfam_id`, `autref_lib`, `autref_code`, `autref_flag_actif`) VALUES(?, ?,?, ?, ?);
";	
		$req = $pdo->prepare($sql);
		$req->execute(array($fam_id,$sfam_id,$autref_lib,$autref_code,1));
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
			echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_autref&insert=ok"</script>';
			
			
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
                              <a href="<?= $_SESSION['proflis']?>.php?p=liste_autref"><span class="caption"> Liste des autres cathegorie de produits</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter sous cathegorie de produits</span></a>
                              
                            </li>
                        </ul>
                       
                    </div>
                    <!-- END PAGE BAR -->
                     <br />
                    <!-- END PAGE BAR -->
                <div class="note note-success">Les champs marqués d'un astérisque <span style="color:#F00"> (*)</span> 
					sont obligatoires </div>
                    <!-- BEGIN PAGE TITLE--><!-- END PAGE TITLE-->
					 <div class="row">
                        <div class="col-md-12">
                            <div class="tabbable-line boxless tabbable-reversed">
                                 <div class="tab-content">
                                  
								
                                        <div class="portlet box blue-hoki">
                                            <div class="portlet-title">
                                             <div class="caption">Ajouter autre cathegorie de produit</div></div>
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
                                							  <input type="text" name="autref_code" id="autref_code" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['autref_code'])?$_POST['autref_code']:''; ?>" />
                                                              <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Libellé:</label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="autref_lib" id="autref_lib" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['autref_lib'])?$_POST['autref_lib']:''; ?>" />
                                                              <?php if(!empty($errors[1])): ?>
															   <span class="alert-danger"><?= $errors[1]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Catégorie:<span style="color:#F00">(*)</span></label>
                                                            
                                                            <div class="col-md-7">
                                                               <select id="fam_id" class="form-control" name="fam_id">
                                                                <option value="">--Selectionnez--</option>
                                                              </select>
													          <span class="col-md-7 control-label">
														       <?php if(!empty($errors[3])): ?>
                                                               <span class="alert-danger">
                                                               <?= $errors[3]; ?>
                                                               </span>
                                                               <?php endif; ?>
                                                            </span></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Sous cat&eacute;gorie:<span style="color:#F00">(*)</span></label>
                                                            
                                                            <div class="col-md-7">
                                                               <select id="sfam_id" class="form-control" name="sfam_id">
                                                                <option value="">--Selectionnez--</option>
                                                              </select>
													          <span class="col-md-7 control-label">
														       <?php if(!empty($errors[4])): ?>
                                                               <span class="alert-danger">
                                                               <?= $errors[4]; ?>
                                                               </span>
                                                               <?php endif; ?>
                                                            </span></div>
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


