<?php 
	include('./model/connexion.php');
	$pdo=connect();
	
		
		/**
		   *	UPDATE DES CHAMPS MODIFIES
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//RECUPERATION DES VARIABLES
		 	$ent_lib = htmlentities(trim($_POST['ent_lib']));
			$ent_adr = htmlentities(trim($_POST['ent_adr']));
			$ent_cont = htmlentities(trim($_POST['ent_cont']));
		    //GESTION DES RESTRICTIONS
			 $errors = array();
			 $valid=true;
         
        if(empty($ent_lib))
        {
            $errors[0]='Veuillez saisir un libellé';
            $valid=false;
        }
         
        
        if(empty($ent_adr))
        {
            $errors[1]='Veuillez saisir une adresse';
            $valid=false;
        }
       
         
       if(empty($ent_cont))
        {
            $errors[2]='Veuillez saisir un num&eacute;ro de t&eacute;l&eacute;phonne';
            $valid=false;
        }
		
        /*elseif (!preg_match("#^0[1-68]([-. ]?[0-9]{2}){4}$#",$ent_cont))
        {
            $errors[3]= 'Veuillez saisir un num&eacute;ro de t&eacute;l&eacute;phonne valide';
			$valid=false;
        }*/
         
       
		
		 
		 	if($valid == true){
			$dateMod = date("Y-m-d").' '.date("H:i:s");
			//Insertion 
			  $sql = "INSERT INTO entreprise(
             `ent_lib`, `ent_adr`, `ent_cont`, `ent_date_crea`, `ent_date_mod`, `flag`)
    VALUES (?, ?, ?, ?, ?, ?);
";	
		$req = $pdo->prepare($sql);
		$req->execute(array($ent_lib,$ent_adr,$ent_cont,$dateMod,$dateMod,1));
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
			echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_ent&insert=ok"</script>';
			
			
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
                              <a href="<?= $_SESSION['proflis']?>.php?p=liste_ent"><span class="caption"> Liste des entreprises</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter entreprise</span></a>
                              
                            </li>
                        </ul>
                       
                    </div>
                    <br/>
                    <div class="note note-success">Les champs marqués d'un astérisque <span style="color:#F00"> (*)</span> 
					sont obligatoires </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE--><!-- END PAGE TITLE-->
					 <div class="row">
                        <div class="col-md-12">
                            <div class="tabbable-line boxless tabbable-reversed">
                                 <div class="tab-content">
                                  
								<form action="" method="post" class="form-horizontal">
                                        <div class="portlet box blue-hoki">
                                            <div class="portlet-title">
                                             <div class="caption">Mise à jour entreprise</div>
                                          </div>
                                            <div class="portlet-body form">
                                            
                                                <!-- BEGIN FORM-->
											
                                                    <div class="form-actions top">
                                                        
                                                    </div>
                                                    <div class="form-body">
													<div class="row">
													<div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Libelle : <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="ent_lib" id="ent_lib" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['ent_lib'])?$_POST['ent_lib']:''; ?>" />
                                                              <?php if(!empty($errors[0])): ?>
								   <span class="alert-danger"><?= $errors[0]; ?></span>
                                 <?php endif; ?>
															</div>
                                                        </div>
                                                       
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Adresse : <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                            <input type="text" name="ent_adr" id="ent_adr" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['ent_adr'])?$_POST['ent_adr']:''; ?>" />
   <?php if(!empty($errors[1])): ?>
								   <span class="alert-danger"><?= $errors[1]; ?></span>
                                 <?php endif; ?>                                                         </div>
                                                        </div>
														
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Contact : <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="ent_cont" id="ent_cont" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['ent_cont'])?$_POST['ent_cont']:''; ?>"  />
            <?php if(!empty($errors[2])): ?>
								   <span class="alert-danger"><?= $errors[2]; ?></span>
                                   
                                 <?php endif; ?>
                                                                                    </div>
                                                         </div>
													
														
														 </div>
                                                       </div>
																										   
<br>													  <div class="row"> 
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


