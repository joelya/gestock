<?php 
	include('./model/connexion.php');
	$pdo=connect();
	//liste des services		
			$sql_svc = "SELECT * FROM service WHERE flag=1 ORDER BY svc_lib ASC";
		$list_svc = $pdo->prepare($sql_svc);
      $list_svc->execute();
	  //end liste services
		
		/**
		   *	AJOUTER RAYON
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//RECUPERATION DES VARIABLES
		 	$ray_lib = htmlentities(trim($_POST['ray_lib']));
			$svc_id = htmlentities(trim($_POST['svc_id']));
			
		    //GESTION DES RESTRICTIONS
			 $errors = array();
			 $valid=true;
         
        if(empty($ray_lib))
        {
            $errors[0]='Veuillez saisir une direction';
            $valid=false;
        }
         
        if($valid == true){
			$dateMod = date("Y-m-d").' '.date("H:i:s");
			//Insertion 
			  $sql = "INSERT INTO rayon(
             `ray_lib`, `svc_id`,`ray_date_crea`, `ray_date_mod`, `flag`)
    VALUES (?, ?, ?, ?, ?);
";	
		$req = $pdo->prepare($sql);
		$req->execute(array($ray_lib,$svc_id,$dateMod,$dateMod,1));
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
			echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_ray&insert=ok"</script>';
			
			
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
                              <a href="<?= $_SESSION['proflis']?>.php?p=liste_ray"><span class="caption"> Liste des rayons</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter rayons</span></a>
                              
                            </li>
                        </ul>
                       
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE--><!-- END PAGE TITLE-->
					 <div class="row">
                        <div class="col-md-12">
                            <div class="tabbable-line boxless tabbable-reversed">
                                 <div class="tab-content">
                                  
								<form action="" method="post" class="form-horizontal">
                                        <div class="portlet box blue-hoki">
                                            <div class="portlet-title">
                                             <div class="caption">Ajouter rayon</div>
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
                                                            <label class="col-md-4 control-label">service:</label>
                                                            <div class="col-md-7">
                                                               <select class="bs-select form-control" data-live-search="true" data-size="8" name="svc_id" id="svc_id" >
																<?php foreach ($list_svc as $result_svc): ?>
																<option value="<?= $result_svc['svc_id']; ?>" >
																
																<?= $result_svc['svc_lib']; ?></option>
																<?php endforeach; ?>
																</select>
														    </div>
                                                        </div> 
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Nom du rayon:</label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="ray_lib" id="ray_lib" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['ray_lib'])?$_POST['ray_lib']:''; ?>" />
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


