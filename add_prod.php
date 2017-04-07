 <script type="text/javascript" src="jquery-1.7.1.min.js"></script>
  <script type="text/javascript" src="listeCat.js"></script>
<?php 
	
	//liste des familles rattachées aux produits

	$sql_fam = "SELECT * FROM famille  ORDER BY fam_libel ASC";
	$liste_fam = $pdo->prepare($sql_fam);
    $liste_fam->execute();
	//liiste des marques
	$sql_marque= "SELECT `marque_id`,`marque_lib` FROM `marque`";
					$list_marque = $pdo->prepare($sql_marque);
					$list_marque->execute();
	//liste des foxurnisseurs rattachés aux produits

	$sql_fourn = "SELECT `fourn_id`,`fourn_code`,`fourn_lib`,`fourn_addr`,`fourn_contact`,`fourn_email`,`fourn_falg_actif` FROM `fournisseur` ORDER BY `fourn_lib` ASC";
	$liste_fourn = $pdo->prepare($sql_fourn);
    $liste_fourn->execute();

		/**
		   *	AJOUTER SERVICE
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 //var_dump($_POST);exit;
		
			//echo $inser;exit;
		 	//RECUPERATION DES VARIABLES
			@$fam_id = htmlentities(trim($_POST['fam_id']));
			@$sfam_id = htmlentities(trim($_POST['sfam_id']));
			
			@$autref_id = htmlentities(trim($_POST['autref_id']));
			@$marque_id = htmlentities(trim($_POST['marque_id']));
			$prd_lib = htmlentities(trim($_POST['prd_lib']));
			$prd_prix_ttc = htmlentities(trim($_POST['prd_prix_ttc']));
			$prd_code = htmlentities(trim($_POST['prd_code']));
			@$fourn_id = $_POST['fourn_id'];
			$prd_qte = $_POST['prd_qte'];
		    //GESTION DES ERREURS DE SAISIE
			 $errors = array();
			 $valid=true;
         
        if(empty($prd_lib))
        {
            $errors[0]='Veuillez saisir un libell&eacute; pour cet produit';
            $valid=false;
        }
		 if(empty($prd_prix_ttc))
        {
            $errors[1]='Veuillez saisir le prix TTC du produit';
            $valid=false;
        }
		 if(empty($prd_code))
        {
            $errors[7]='Veuillez saisir le code du produit';
            $valid=false;
        }
		 if(empty($autref_id))
        {
            $errors[8]='Veuillez saisir le niveau';
            $valid=false;
        }
		 if(empty($marque_id))
        {
            $errors[2]='Veuillez choisir une marque';
            $valid=false;
        }
		
		 if(empty($fam_id))
        {
            $errors[3]='Veuillez choisir une cat&eacute;gorie';
            $valid=false;
        }
		
		if(empty($sfam_id))
        {
            $errors[4]='Veuillez choisir une sous cat&eacute;gorie';
            $valid=false;
        }
		  if(empty($prd_qte))
        {
            $errors[6]='Veuillez saisir la quantit&eacute;';
            $valid=false;
        }
		
		if(empty($fourn_id))
        {
            $errors[5]='Veuillez Choisir un fournisseur';
            $valid=false;
        }
		
        if($valid == true){
			try{
			//Insertion 
			$prd_prix_ht = $prd_prix_ttc/1.18;
			  $sql = "INSERT INTO `produit`(`marque_id`, `fam_id`, `sfam_id`,autref_id, `prd_lib`, `prd_code`, `prd_qte`, `prd_prix_ht`, `prd_prix_ttc`,`prd_flag_actif`) VALUES (?, ?, ?, ?, ?, ?, ? ,?, ?,?);
";	

		$req = $pdo->prepare($sql);
		
		$req->execute(array($marque_id,$fam_id,$sfam_id,$autref_id,$prd_lib,$prd_code,$prd_qte,$prd_prix_ht,$prd_prix_ttc,1));
		
		/**
        *   INSERTION DES LIGNE DE FOURNISSEURS
        **/
		$prd_id = $pdo->lastInsertId();
		$count = count($fourn_id);
        $sqli = "INSERT INTO `fournisseur_prod`(`prd_id`, `fourn_id`, `fourn_flag_actif`) VALUES (?,?,?)";
        $reqi = $pdo->prepare($sqli);

        for($i=0; $i<$count; $i++)
        {
        $reqi->execute(array($prd_id,$fourn_id[$i],1));	
        }
		
		
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
			echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_prod&insert=ok"</script>';
			
			}
			catch(PDOException $e){
			
			$errors[7]='ce code produit est deja utilis&eacute;!';
			//echo $e->getMessage();
		
			}
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
                              <a href="<?= $_SESSION['proflis']?>.php?p=liste_prod"><span class="caption"> Liste des produits</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter produits</span></a>
                              
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
                                             <div class="caption">Ajouter un produit</div>
                                          </div>
                                          
                                            <div class="portlet-body form">
                                   <form action="" method="post" class="form-horizontal">
                                                <!-- BEGIN FORM-->
											
                                                    <div class="form-actions top"></div>
													
                                     <div class="form-body">
                                     <?php if(!empty($errors[7])): ?>
                                          <div class="alert alert-danger alert-dismissable">
																<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
																 <a href="" class="alert-link"><?= $errors[7]; ?></a>
														  </div>			 <?php endif; ?>
													
													<div class="row">
													<div class="col-md-12">
													<div class="col-md-6">
													<div class="row">
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Lib&eacute;ll&eacute;: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="prd_lib" id="prd_lib" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['prd_lib'])?$_POST['prd_lib']:''; ?>" />
                                                              <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
														<div class="form-group">
                                                            <label class="col-md-4 control-label">code produit: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="prd_code" id="prd_code" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['prd_code'])?$_POST['prd_code']:''; ?>" />
                                                              <?php if(!empty($errors[2])): ?>
															   <span class="alert-danger"><?= $errors[2]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Prix TTC: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="prd_prix_ttc" id="prd_prix_ttc" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['prd_prix_ttc'])?$_POST['prd_prix_ttc']:''; ?>" />
                                                              <?php if(!empty($errors[1])): ?>
															   <span class="alert-danger"><?= $errors[1]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Marque:<span style="color:#F00">(*)</span></label>
                                                            
                                                            <div class="col-md-7">
                                                               <select class="bs-select form-control" data-live-search="true" data-size="8" name="marque_id" id="marque_id" >
															<?php foreach ($list_marque as $result_marque): ?>
																<option value="<?= $result_marque['marque_id']; ?>" >
																
																<?= $result_marque['marque_lib']; ?></option>
																<?php endforeach; ?>
															  </select>
													          <span class="col-md-7 control-label">
														       <?php if(!empty($errors[7])): ?>
                                                               <span class="alert-danger">
                                                               <?= $errors[7]; ?>
                                                               </span>
                                                               <?php endif; ?>
                                                            </span></div>
                                                        </div>
													</div></div>
													<div class="col-md-6">
													   <div class="row">
														
                                                        <!--gestion des fournisseurs-->
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Catégorie:<span style="color:#F00">(*)</span></label>
                                                            
                                                            <div class="col-md-7">
                                                               <select id="fam_id" class="form-control" name="fam_id">
                                                                <option value="">Choisissez une cat&eacute;gorie
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
                                                                <option value="1">--Aucune--
                                                              </select>
													          <span class="col-md-7 control-label">
														       <?php if(!empty($errors[4])): ?>
                                                               <span class="alert-danger">
                                                               <?= $errors[4]; ?>
                                                               </span>
                                                               <?php endif; ?>
                                                            </span></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Autre:<span style="color:#F00">(*)</span></label>
                                                            
                                                            <div class="col-md-7">
                                                               <select id="autref_id" class="form-control" name="autref_id">
                                                                <option value="1">--Aucune--
                                                              </select>
													          <span class="col-md-7 control-label">
														       <?php if(!empty($errors[8])): ?>
                                                               <span class="alert-danger">
                                                               <?= $errors[8]; ?>
                                                               </span>
                                                               <?php endif; ?>
                                                            </span></div>
                                                        </div>
                                                          <div class="form-group">
													       <label for="multiple" class="col-md-4 control-label">Fournisseurs(s) : <span style="color:#F00"> (*)</span> </label>
															<div class="col-md-7 control-label">
                                                            <select name="fourn_id[]" multiple class="form-control select2-multiple" id="multiple[]">
																<?php foreach ($liste_fourn as $fournisseur){ ?>
																<option <?php  if(@$_POST['fourn_id'][0]==$fournisseur['fourn_id']) {echo 'selected';}?> value="<?= $fournisseur['fourn_id'] ?>"><?= ucfirst(strtolower($fournisseur['fourn_lib'])) ?>
                                                                </option>
																<?php }; ?>
																</optgroup>
																</select>
																																 
															   <?php if(!empty($errors[5])): ?>
																<span class="alert-danger"><?= $errors[5]; ?></span>
															 <?php endif; ?>
                                                        </div></div>
                                                        <!--end fourn-->
													   <div class="form-group">
                                                            <label class="col-md-4 control-label">Quantité : <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                                <input type="text" name="prd_qte" id="prd_qte" class="form-control" value="<?= !empty($_POST['prd_qte'])?$_POST['prd_qte']:''; ?>"  size="45" maxlength="255" />
                                                                <?php if(!empty($errors[6])): ?>
                                                                <span class="alert-danger"><?= $errors[6]; ?></span>
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
