
<?php 
	
	//liste des clients rattachés aux medecins prescripteurs

	$sql_clt = "SELECT `clt_id`, `clt_nom`, `clt_pren`, `clt_adresse`, `clt_contact`, `clt_flag_actif` FROM `client` ORDER BY `clt_nom` ASC";
	$liste_clt = $pdo->prepare($sql_clt);
    $liste_clt->execute();

		/**
		   *	AJOUTER SERVICE
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 //var_dump($_POST);exit;
			
			//echo $inser;exit;
		 	//RECUPERATION DES VARIABLES
			$medp_nom = htmlentities(trim($_POST['medp_nom']));
			$medp_prenom = htmlentities(trim($_POST['medp_prenom']));
			$medp_sexe = htmlentities(trim($_POST['medp_sexe']));
			$medp_code = htmlentities(trim($_POST['medp_code']));
			@$clt_id = $_POST['clt_id'];
		    //GESTION DES ERREURS DE SAISIE
			 $errors = array();
			 $valid=true;
         
        if(empty($medp_nom))
        {
            $errors[0]='Veuillez saisir un nom pour ce medecin prescripteur';
            $valid=false;
        }
		 if(empty($medp_prenom))
        {
            $errors[1]='Veuillez saisir un prenom pour ce medecin prescripteur';
            $valid=false;
        }
		
		 if(empty($medp_code))
        {
            $errors[2]='Veuillez saisir le code du medecin prescripteur';
            $valid=false;
        }
		
		
		
        if($valid == true){
			
			//Insertion 
			  $sql = "INSERT INTO `med_prescritpteur`(`medp_nom`, `medp_prenom`, `medp_sexe`,`medp_code`) VALUES (?, ?, ?, ?);
";	

		$req = $pdo->prepare($sql);
		
		$req->execute(array($medp_nom,$medp_prenom,$medp_sexe,$medp_code));
		
		/**
        *   INSERTION DES LIGNE DE clients
		 **/
		 if(!empty($clt_id)){
		$medp_id = $pdo->lastInsertId();
		$count = count($clt_id);
        $sqli = "INSERT INTO `medecin_client`(`medp_id`, `clt_id`) VALUES (?,?)";
        $reqi = $pdo->prepare($sqli);

        for($i=0; $i<$count; $i++)
        {
        $reqi->execute(array($medp_id,$clt_id[$i]));	
        }
		 }
		
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
			echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_medp&insert=ok"</script>';
			
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
                              <a href="<?= $_SESSION['proflis']?>.php?p=liste_medp"><span class="caption"> Liste des medecins prescripteurs</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter medecins prescripteurs</span></a>
                              
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
                                             <div class="caption">Ajouter un medecin prescripteur</div>
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
                                							  <input type="text" name="medp_nom" id="medp_nom" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['medp_nom'])?$_POST['medp_nom']:''; ?>" />
                                                              <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Pr&eacute;nom(s): <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="medp_prenom" id="medp_prenom" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['medp_prenom'])?$_POST['medp_prenom']:''; ?>" />
                                                              <?php if(!empty($errors[1])): ?>
															   <span class="alert-danger"><?= $errors[1]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
														<div class="form-group">
														<label class="col-md-4 control-label">Sexe:</label>
														<div class="col-md-7">
																   <div class="md-radio-inline">
																	<div class="md-radio">
																	<input type="radio" id="radio6" name="medp_sexe" class="md-radiobtn" value="1" checked>
																	<label  for="radio6">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>Masculin</label>
																	</div>
																	<div class="md-radio">
																	<input type="radio" id="radio7" name="medp_sexe" class="md-radiobtn" value="0">
																	<label for="radio7">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>Feminin</label>
																	</div>
															</div>
													     </div>
													  </div>
														
													</div></div>
													<div class="col-md-6">
													   <div class="row">
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Code: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="medp_code" id="medp_code" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['medp_code'])?$_POST['medp_code']:''; ?>" />
                                                              <?php if(!empty($errors[2])): ?>
															   <span class="alert-danger"><?= $errors[2]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
                                                        <!--gestion des clients-->
                                                          <div class="form-group">
													       <label for="multiple" class="col-md-4 control-label">Client(s) : <span style="color:#F00"></span> </label>
															<div class="col-md-7 control-label">
                                                            <select name="clt_id[]" multiple class="form-control select2-multiple" id="multiple[]">
																<?php foreach ($liste_clt as $client){ ?>
																<option <?php  if(@$_POST['clt_id'][0]==$client['clt_id']) {echo 'selected';}?> value="<?= $client['clt_id'] ?>"><?= ucfirst(strtolower($client['clt_nom'])) ?> <?= ucfirst(strtolower($client['clt_pren'])) ?>
                                                                </option>
																<?php }; ?>
																</optgroup>
																</select>
															</div></div>
                                                        <!--end client-->
													  
														
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
