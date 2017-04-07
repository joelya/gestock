<?php 
	include('./model/connexion.php');
	$pdo=connect();
	
		/**
		   *	AJOUTER RAYON
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//RECUPERATION DES VARIABLES
		 	$fonct_lib = htmlentities(trim($_POST['fonct_lib']));
			
			
		    //GESTION DES RESTRICTIONS
			 $errors = array();
			 $valid=true;
         
        if(empty($fonct_lib))
        {
            $errors[0]='Veuillez saisir une fonction';
            $valid=false;
        }
         
        if($valid == true){
			$dateMod = date("Y-m-d").' '.date("H:i:s");
			//Insertion 
			  $sql = "INSERT INTO fonction(
             `fonct_lib`,`fonct_date_crea`, `fonct_date_mod`, `flag`)
    VALUES (?, ?, ?, ?);
";	
		$req = $pdo->prepare($sql);
		$req->execute(array($fonct_lib,$dateMod,$dateMod,1));
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
			echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_fonct&insert=ok"</script>';
			
			
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
                              <a href="<?= $_SESSION['proflis']?>.php?p=liste_fonct"><span class="caption"> Liste des rayons</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter fonction</span></a>
                              
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
                                             <div class="caption">Ajouter fonction</div>
                                          </div>
                                            <div class="portlet-body form">
                                            
                                                <!-- BEGIN FORM-->
											
                                                    <div class="form-actions top">
                                                        
                                                    </div>
                                                    <div class="form-body">
													<div class="row">
													<div class="col-md-12">
													<div class="row">
													
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Libellé de la fonction : <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="fonct_lib" id="fonct_lib" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['fonct_lib'])?$_POST['fonct_lib']:''; ?>" />
                                                              <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
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


