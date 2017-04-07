<?php
if(isset($_GET['clt_id'])){
				$up = decrypturl($_GET['clt_id']);
				  $_SESSION['leclient'] = $up;
	}
if(isset($_GET['codev'])){
				$codev = decrypturl($_GET['codev']);
				  $_SESSION['codev'] = $codev;
	}
	//GESTION DE LA VENTE
	$sql_vente= "SELECT `vente_id`,`medp_id`, `comerc_id`, `auth_id`, `vente_ref`, `vente_date`, `vente_rapport`, `vente_remise`, `vent_total`, `vente_flag_modif`, `vente_flag_creer`, `vente_flag_clot` FROM `vente` WHERE `vente_id` =  '".decrypturl(@$_GET['codev'])."'";
	$lavente = $pdo->prepare($sql_vente);
    $lavente->execute();
	foreach ($lavente as $unevente){
		$vente_rapport = $unevente['vente_rapport'];
		$vente_remise = $unevente['vente_remise'];
		$vent_total = $unevente['vent_total'];
		$vente_flag_modif = $unevente['vente_flag_modif'];
		$medp_id = $unevente['medp_id'];
		$comerc_id = $unevente['comerc_id'];
		
	}
	
	//LISTE DES MEDECIN PRESCRIPTEURS
	$sql_medp= "SELECT `medp_id`, `medp_nom`, `medp_prenom`,medp_sexe, `medp_flag_actif`, `medp_code` FROM `med_prescritpteur` WHERE medp_id<>1 ";
	$listmp = $pdo->prepare($sql_medp);
    $listmp->execute();
	//LISTE DES COMMERCIAUX
	$sql_commerc= "SELECT `comerc_id`, `commerc_nom`, `commerc_prenom`,commerc_sexe, `commerc_flag_actif`, `commerc_code` FROM `commercial` WHERE comerc_id<>1 ";
	$list_commerc = $pdo->prepare($sql_commerc);
    $list_commerc->execute();
	//liste des Clients pour les informations client

	$sql_clt = "SELECT `clt_id`, `clt_nom`, `clt_pren`,clt_age, `clt_adresse`, `clt_tel`, `clt_sexe`, `clt_bur`, `clt_porte`, `clt_contact`, `clt_flag_actif` FROM `client` WHERE clt_id = '".$_SESSION['leclient']."' AND clt_flag_actif=1";
	$list = $pdo->prepare($sql_clt);
    $list->execute();
	foreach($list as $client){
		$clt_nom=$client['clt_nom'];
		$clt_pren=$client['clt_pren'];
		$clt_age=$client['clt_age'];
		$clt_contact=$client['clt_contact'];
		}
		if(isset($_POST['clot_info'])){
			
		 $update_clot_info = "UPDATE vente SET `vente_flag_clot` = '1' WHERE `vente`.`vente_id` = '".decrypturl(@$_GET['codev'])."'";
		 //var_dump($update_clot_info);exit;
		 $pdo->query($update_clot_info);
		
		}
		
		//REGLEMENT DE FACTURE ET GESTION DE STOCK
		 if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['regler'] ))
     {
		//var_dump($_POST);exit;
		 $errors_vte = array();
		 $valid_vte=true;
		 $remise = 0;
		 //RECUPERATION DU PRIX DE VENTE RELATIVEMENT AU PRIX VERSE
		  $sql_totale = "SELECT vent_total,vente_nap FROM vente WHERE vente_id= '".$_SESSION['codev']."'";
	$list_totale = $pdo->prepare($sql_totale);
    $list_totale->execute();
	foreach($list_totale as $letolale){
	$vent_total = $letolale['vent_total'];
	$vente_nap = $letolale['vente_nap'];
	}
	
		 //RECUPERATION DES VARIABLES
			$vente_versement = htmlentities(trim($_POST['vente_versement']));
			$detail = htmlentities(trim($_POST['detail']));
			$titulaire = htmlentities(trim($_POST['titulaire']));
			$vente_remise=htmlentities(trim($_POST['vente_remise']));
			$mode_paiement=htmlentities(trim($_POST['mode_paiement']));
			$codebanque=htmlentities(trim($_POST['codebanque']));
			$vente_livraison=htmlentities(trim($_POST['vente_livraison']));
			
			if($vente_remise!=NULL){
			$remise = $vent_total*$vente_remise/100;
			}
	
		 //GESTION DES ERREURS DE SAISIE
		if(empty($vente_versement))
				{
					$errors_vte[0]='Aucun montant!';
					$valid_vte=false;
				}
		
		
		//VERIFICATION VERSEMENT
		 if(empty($mode_paiement))
				{
					$errors_vte[1]='Champ requis';
					$valid_vte=false;
				}
		
        //VERIFICATION DES QUANTITES SERVIES
		 $sql_qte = "SELECT p.prd_id,p.prd_qte,SUM(vp.qty_servi) as qty_servi,p.prd_qte-SUM(vp.qty_servi) AS reste FROM vendre_produit vp INNER JOIN produit p ON vp.prd_id=p.prd_id WHERE vp.vente_id = '".$_SESSION['codev']."' GROUP BY vp.prd_id";
	$list_qte = $pdo->prepare($sql_qte);
    $list_qte->execute();
	foreach($list_qte as $lereste){
		
		if($lereste['reste']<0){
			$errors_vte[2]='Verifiez les quantit&eacute;es servies!';
            $valid_vte=false;
			}
			else{
			$valid_vte=true;
			}
		}
	 	 
		
	
		//MAJ DU NET A PAYER
	$vente_napT = $vente_nap-$remise;	
	
	if($mode_paiement=='cheque'){
    $modep_id = 2;
	$vente_versement =0;	
	$vente_versement='';
	}
	if($mode_paiement=='caisse'){
	$codebanque='';
	$detail ='';
	$titulaire ='';
    $modep_id = 1;	
	
	if($vente_versement<$vente_napT){
	$errors_vte[3]='Le montant vers&eacute; doit couvrir la somme a payer!';
    $valid_vte=false;	
	}
	}
	 
        if($valid_vte == true){
			
			//MAJ VERSEMENT
			$reste_a_payer = abs($vente_versement-$vente_napT);
			$leversement = "UPDATE vente 
			SET vente_versement ='".$vente_versement."', vente_remise = '".$vente_remise."',vente_nap = '".$vente_napT."',vente_reste='".$reste_a_payer."',vente_livraison='".$vente_livraison."',vente_flag_actif='1' 
			WHERE vente_id = '".$_SESSION['codev']."'";
			//var_dump($leversement);exit;
			$pdo->query($leversement);
			
			 //MAJ VENTE_MODE_PAIEMENT
			  $sqlmop = "INSERT INTO `vente_modep`(`vente_id`, `modep_id`, `montant`, `titulaire`, `codebanque`, `detail`) VALUES  ('".$_SESSION['codev']."','".$modep_id."','".$vente_versement."','".$titulaire."','".$codebanque."','".$detail."')";
        $reqmop = $pdo->query($sqlmop);
			 //VARIATION DU STOCK
			 $stock = "UPDATE produit AS U1, (SELECT sum(vp.qty_servi) as qte,prd_id FROM vendre_produit vp WHERE vp.vente_id = '".$_SESSION['codev']."' GROUP BY prd_id) AS U2 SET U1.prd_qte = U1.prd_qte-U2.qte WHERE U2.prd_id = U1.prd_id";
			$pdo->query($stock);
			//REINITIALISATION
			$vente_versement=0;
			$vente_remise=0;
			//MISE A JOUR RISTOURNE MEDP ET COMMERCIAL
			//LISTE DES CLIENTS RATTACHES AUX COMMERCIAUX
			$sql_clit = "SELECT `medp_id`, `comerc_id` FROM `vente` WHERE vente_id = '".$_SESSION['codev']."' AND `vente_flag_actif`=1";
			$liste_clit = $pdo->prepare($sql_clit);
			$liste_clit->execute();
			foreach ($liste_clit as  $un){
			$lecomercial=$un['comerc_id'];
			$lemedecin=$un['medp_id'];
			}
			 $sqli = "INSERT INTO `rattacher_client`(`comerc_id`, `clt_id`) VALUES ('".$lecomercial."','".$_SESSION['leclient']."')";
        $reqi = $pdo->query($sqli);
		 $sqli2 = "INSERT INTO `medecin_client`(`medp_id`, `clt_id`) VALUES ('".$lemedecin."','".$_SESSION['leclient']."')";
        $reqi2 = $pdo->query($sqli2);
		//POUR EMPECHER LE RAFRAICHISSEMENT DE LA PAGE	
		
		echo '<script>location.href="'.$_SESSION['proflis'].".php?".$_SESSION['page'].'"</script>';
		}
		
	 }
	/**
	   *	INSERTION DE LA VENTE
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//var_dump($_POST);exit;
		 		//RECUPERATION DES VARIABLES DE LA VENTE 
		
		 	$medp_id = $_POST['medp_id'];
			$auth_id = $_SESSION['auth_id'];
			$comerc_id = htmlentities(trim($_POST['comerc_id']));
			$vente_rapport = htmlentities(trim($_POST['vente_rapport']));
			
		    //GESTION DES ERREURS DE SAISIE
			 $errors = array();
			 $valid=true;
        
        if($valid == true){
		
			
			$dateMod = date("Y-m-d").' '.date("H:i:s");
			/**
			*INSERTION DE LA VENTE
			**/
			$codev =  strtoupper(dechex(crc32(uniqid())));
			 $sql = "INSERT INTO `vente`(`auth_id`,clt_id,`medp_id`,`comerc_id`, `vente_ref`, `vente_date`, `vente_rapport`,`vente_flag_creer`) VALUES (?,?,?,?,?,?,?,?)
";	

		$req = $pdo->prepare($sql);
		
		$req->execute(array($_SESSION['auth_id'],$_SESSION['leclient'],$medp_id,$comerc_id,$codev,$dateMod,$vente_rapport,1));
		$vente_id = $pdo->lastInsertId();
		
		
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
		   	
			//echo $vente_id;exit;
			echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=fiche_patient&codev='.crypturl($vente_id).'&clt_id='.crypturl($_SESSION['leclient']).'"</script>';
			
			}
	 }
	 
	 //MISE A JOUR DE LA VENTE
	 if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['update_info']))
     {
	 
		 //RECUPERATION DES VARIABLES DE LA VENTE MEDICAL
		
		 	$medp_id = $_POST['medp_id'];
			$auth_id = $_SESSION['auth_id'];
			$comerc_id = htmlentities(trim($_POST['comerc_id']));
			$vente_rapport = htmlentities(trim($_POST['vente_rapport']));
			
		    //GESTION DES ERREURS DE SAISIE
			 $errors = array();
			 $valid=true;
         if(empty($vente_rapport))
        {
            $errors[0]='Veuillez saisir le rapport de la visite';
            $valid=false;
        }
        if($valid == true){
		
			
			$dateMod = date("Y-m-d").' '.date("H:i:s");
			/**
			*MISE A JOUR DE LA VENTE
			**/
			$vente_id = decrypturl($_GET['codev']);
			$update = " UPDATE `vente` SET 
						`medp_id`='".$medp_id."',`comerc_id`='".$comerc_id."',
						`vente_rapport`='".$vente_rapport."',`vente_date`='".$dateMod."',`vente_flag_modif`='1'
						WHERE vente_id = '".$vente_id."' ";	
				//var_dump($update);exit;
		       $pdo->query($update);
		
			}
	 }
	//POUR VERIFIER SI UNE VENTE A DEJA ETE CREER
		$clotP = $pdo->prepare("SELECT `vente_flag_creer` FROM `vente` WHERE `vente_id` ='".decrypturl(@$_GET['codev'])."' AND vente_flag_creer =1");
		$clotP->execute();
		$countclotP = $clotP->rowCount();
	//var_dump($clotP);exit;
	//POUR VERIFIER SI UNE VENTE A DEJA ETE BOUCLEE -- AFFICHER ETAT
		$clot_regl = $pdo->prepare("SELECT `vente_flag_actif` FROM `vente` WHERE `vente_id` ='".decrypturl(@$_GET['codev'])."' AND vente_flag_actif =1");
		$clot_regl->execute();
		$countReg = $clot_regl->rowCount();
	//POUR VERIFIER SI UNE VENTE A DEJA ETE MODIFIEE
		$clotM = $pdo->prepare("SELECT `vente_flag_modif` FROM `vente` WHERE `vente_id` =  '".decrypturl(@$_GET['codev'])."' AND vente_flag_modif =1");
		$clotM->execute();
		$countmod = $clotM->rowCount();
		//var_dump($countmod);exit;
//POUR VERIFIER SI UNE VENTE A DEJA ETE FAITE
		$clot_def = $pdo->prepare("SELECT `vente_flag_clot` FROM `vente` WHERE `vente_id` =  '".decrypturl(@$_GET['codev'])."' AND vente_flag_clot =1");
		$clot_def->execute();
		$cloturer= $clot_def->rowCount();
//LES INFOS VENTE CLOTURER
	$venteinfos = $pdo->prepare("SELECT `vente_id`, `clt_id`, `medp_id`, `comerc_id`, `auth_id`, `vente_ref`, `vente_bon`, `vente_date`, `vente_rapport`, `vente_versement`, `vente_remise`, `vent_total`, `vente_nap`, `vente_reste`, `vente_flag_modif`, `vente_flag_creer`, `vente_flag_clot`, `vente_flag_actif` FROM `vente` WHERE `vente_id` =  '".decrypturl(@$_GET['codev'])."' AND vente_flag_actif =1");
	
    $venteinfos->execute();
	$ventT = 0;
	foreach ($venteinfos as $information){
	$letot = $information['vent_total'];
	$leversement = $information['vente_versement'];
	$lenet = $information['vente_nap'];
	$lereste = $information['vente_reste'];
	$lebon = $information['vente_bon'];
	$laremise = $information['vente_remise'];
	$ventT = $ventT+$letot;
	}
	
	//POUR CLOTURER LA VENTE
	//Affiche historique prestation
    $sql_hist = "select * from vente inner join authentification ON vente.auth_id = authentification.auth_id  WHERE clt_id = '".$_SESSION['leclient']."' AND vente.vente_flag_actif=1 ORDER BY vente.vente_date DESC ";
	$list_hist = $pdo->prepare($sql_hist);
    $list_hist->execute();
		//POUR RECUPERER LE SEUIL
	$sql_seuil = "SELECT `seuil_id`, `seuil_val` FROM `param_seuil`";
	$list_seuil = $pdo->prepare($sql_seuil);
    $list_seuil->execute();
	foreach ($list_seuil as $infoseuil){
	$leseuil = $infoseuil['seuil_val'];
	}
		//LISTE DES PRODUITS A AJOUTER
	$sql_prod = "SELECT p.prd_id,f.fam_libel, p.fam_id, p.prd_lib, p.prd_code, p.prd_qte, p.prd_prix_ht, p.prd_prix_ttc, p.prd_tva, p.prd_flag_actif FROM produit p inner join famille f ON p.fam_id = f.fam_id WHERE p.prd_qte>'".$leseuil."' ";
	$list_prod = $pdo->prepare($sql_prod);
    $list_prod->execute();
	

	 ?>
	 
		 
					<div class="page-bar">
                        <ul class="page-breadcrumb">
						<li>
                                <a href="<?= $_SESSION['proflis']?>.php">Accueil</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <a href="<?= $_SESSION['proflis']?>.php?p=creer_vte">Vente</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Traitement Client</span>
                            </li>
                        </ul>
                        
                    </div>
					
                  
<h3 class="page-title">Traitement Client</h3>
					
				  
				 <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                   
                    <div class="profile">
                        <div class="tabbable-line tabbable-full-width">
                            <ul class="nav nav-tabs">


                                <li class="active">
                                    <a href="#tab_1_1" data-toggle="tab"> Traitement Client </a>
                                </li>
                              
                                <li>
                                    <a href="#tab_1_6" data-toggle="tab">Historique des ventes</a>
                                </li>
								
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1_1">
                                    <div class="row">
                                         <div class="col-md-12">
									  
									         <div class="row">
                                                <div class="col-md-8 profile-info">
                                                    <h1 class="font-black sbold uppercase"><?= $clt_nom ?> <?= $clt_pren ?></h1>
                                                    
                                                   
                                                    <ul class="list-inline">
                                                        
                                                        <li>
                                                      <i class="fa fa-calendar"></i> <?php echo $clt_age; ?></li>
                                                        
                                                        <li>
                                                            <i class="fa fa-phone"></i> <?= $clt_contact;?></li>
                                                       
                                                    </ul>
                                                </div>
                                                <!--end col-md-8-->
                                                
                                                <!--end col-md-4-->
                                            </div>
											<!--CREATION DE LA VENTE-->
											
											<div class="col-md-12">
											 <div class="tab-content">
												<div class="portlet light bordered">
														<?php if ($countReg==1){ ?>
													   <center>
													   <a href="#" class="btn default" onclick="NewWindow('print_presta.php?vente_id=<?= $_GET['codev']; ?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" title="Aper&ccedil;u de la vente">Aperçu de la vente</a> </center>
													   <?php } ?>
													   <?php if ($cloturer==0){ ?>
													   <div>
													   <?php if ($countclotP==1 AND $countmod==0){ ?>
													   <div class="alert alert-success alert-dismissable">
														<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
														 <a href="" class="alert-link">Vente créee</a>
														</div>
														<?php } ?>
														<?php if ($countmod==1){ ?>
														   <div class="alert alert-success alert-dismissable">
																<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
																 <a href="" class="alert-link">Vente modifiée</a>
														  </div>
															<?php } ?>
															</div>
														<?php } ?>
													<?php if ($cloturer==0){ ?>
													<div class="portlet-title">
														<div class="caption">
															<i class="icon-social-dribbble font-red"></i>
															<span class="caption-subject font-red bold uppercase">Creation de la vente</span>
														</div>
													</div>
													
                                                    <form action="" method="post" role="form" >
													<div class="row">
													<div class="col-md-12">
													<div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><b>Vendeur</b></label>
                                                            <input name="auth_nom" type="text" class="form-control" id="auth_nom" value="<?= ucfirst(strtolower($_SESSION['name']))?> <?= ucfirst(strtolower($_SESSION['pname']))?>" readonly  /> 
                                                            
														</div>
                                                       
                                                        <div class="form-group"><b>Ophtamologue :</b>
                                                          <select class="bs-select form-control" data-live-search="true" data-size="8" name="medp_id" id="medp_id" >
                                                          <option value="1">--Aucun--</option>
															<?php foreach ($listmp as $result_mp): ?>
																<option value="<?= $result_mp['medp_id']; ?>" <?php if(@$countclotP==1){ if($result_mp['medp_id']==$medp_id){echo "selected"; }} ?>>
																
																<?= $result_mp['medp_nom']; ?>  <?= $result_mp['medp_prenom']; ?></option>
																<?php endforeach; ?>
														  </select>                                                                                                                    
														</div>
														
													</div>
													<div class="col-md-6">
													 <div class="form-group">
                                                            <label class="control-label"><b>Commercial :</b></label>
                                                         <select class="bs-select form-control" data-live-search="true" data-size="8" name="comerc_id" id="comerc_id" >
                                                         <option value="1">--Aucun--</option>
															<?php foreach ($list_commerc as $result_commerc): ?>
																<option value="<?= $result_commerc['comerc_id']; ?>"  <?php if(@$countclotP==1){ if($result_commerc['comerc_id']==$comerc_id){echo "selected"; }} ?>>
																
													<?= $result_commerc['commerc_nom']; ?>	<?= $result_commerc['commerc_prenom']; ?></option>
																<?php endforeach; ?>
														   </select>
														</div>
														<div class="form-group">
													 
                                                      <label class="control-label"><b>Rapport de la visite: </b></label>
                                                            <textarea name="vente_rapport" rows="3" class="form-control" id="vente_rapport" placeholder="Rapport de la visite" <?php if ($cloturer==1){ echo 'readonly'; } ?>><?php if(@$countclotP==1){echo  htmlentities(trim(@$vente_rapport)); }?></textarea>
													  </div>
													</div>
													
														</div>
														</div>
														<?php if ($cloturer==0){ ?>
														<center>
														<?php if (@$countclotP==0){ ?>
														<input class="btn default" type="submit" name="valid_info" value="creer" /> 
														<?php } ?>
														<?php if (@$countclotP==1){ ?>
														<div>
														<input class="btn default" type="submit" name="update_info" value="modifier" />  
														<input class="btn green" type="submit" name="clot_info" value="Valider" />  
														</div>
														<?php }?>
														</center>
														<?php }?>
                                                    </form>
													<?php } ?>
                                                  </div>
											 </div>
									
										</div>
											<!--end-->
											 <?php if ($cloturer==1){ ?>
										<div class="col-md-12">
											 <div class="tab-content">
												<div class="portlet light bordered">
															
													<div class="portlet-title">
														<div class="caption">
															<i class="icon-social-dribbble font-red"></i>
															<span class="caption-subject font-red bold uppercase">Caisse</span>
														</div>
													</div>
                                                   <div class="row">
													<div class="col-md-12">
													<div>
                                                    <?php if(!empty($errors_vte[2])): ?>
                                   <div class="alert alert-danger">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                         <a href="" class="alert-link"><?= $errors_vte[2]; ?></a>
                                  </div>
                                    <?php endif;?>
                                     <?php if(!empty($errors_vte[3])): ?>
                                   <div class="alert alert-danger">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                         <a href="" class="alert-link"><?= $errors_vte[3]; ?></a>
                                  </div>
                                    <?php endif;?>
                                                    
													
												      <div align="right">
														<?php if ($countReg==0){ ?><button type="button" id="modal_button" class="btn btn-info">Ajouter des articles</button><?php } ?>
														<!-- It will show Modal for Ajouter new Records !-->								
                                
												      </div>
													   <br />
												      <div id="result" class="table-responsive"> <!-- Data will load under this tag!-->
														
													   </div>
                                                       <br />
   <div id="result_bon" class="table-responsive"></div>
                                                       <?php if ($countReg==1){ ?>
                                                       <table width="100%" class="table table-striped table-bordered table-hover" >
                                    <tbody>
											<tr>
											  <td nowrap="nowrap">&nbsp;</td>
											  <td nowrap="nowrap">&nbsp;</td>
											  <td nowrap="nowrap"><strong>Total TTC</strong></td>
											  <td nowrap="nowrap"><?= $ventT ?></td>
											  <td nowrap="nowrap">&nbsp;</td>
									  </tr>
											<tr>
											  <td width="20%" nowrap="nowrap">&nbsp;</td>
											  <td width="20%" nowrap="nowrap">&nbsp;</td>
											  <td width="22%" nowrap="nowrap"><strong>Remise (
											     <?= $laremise ?>
											  %)</strong></td>
											  <td nowrap="nowrap">
											    <?= $letot*$laremise/100 ?>
											  </td>
											  <td width="20%" nowrap="nowrap">&nbsp;</td>
									  </tr>
											<tr>
											  <td nowrap="nowrap">&nbsp;</td>
											  <td nowrap="nowrap">&nbsp;</td>
											  <td nowrap="nowrap"><strong>Net a payer</strong></td>
											  <td nowrap="nowrap"><?= $lenet ?></td>
											  <td nowrap="nowrap">&nbsp;</td>
									  </tr>
											<tr>
											  <td nowrap="nowrap">&nbsp;</td>
											  <td nowrap="nowrap">&nbsp;</td>
											  <td nowrap="nowrap"><strong>Montant vers&eacute;</strong></td>
											  <td nowrap="nowrap"><?= $leversement ?></td>
											  <td nowrap="nowrap">&nbsp;</td>
									  </tr>
											<tr>
												<td nowrap="nowrap">&nbsp;</td>
												<td nowrap="nowrap">&nbsp;</td>
												<td nowrap="nowrap"><strong>Monnaie rendue</strong></td>
												<td nowrap="nowrap"><?= $lereste ?></td>
												<td nowrap="nowrap">&nbsp;</td>
											</tr>
                                         </tbody>
								</table>
                                                       <?php } ?>
                                                       <?php if ($countReg==0){ ?>
                                                       <div align="right">
    <button type="button" id="modal_bon" class="btn btn-info">Ajouter bon de reduction</button>
    <!-- It will show Modal for Ajouter new Records !-->
   </div><?php } ?>
    
   
                                                       <?php if ($countReg==0){ ?>
                                                      <form id="form1" name="form1" method="POST" action="">
                                                       	<table width="100%" class="table table-striped table-bordered table-hover" >
                                    <thead>
										<tr>
										   <th nowrap="nowrap">Mode de paiement</th>
										   <th nowrap="nowrap">Details</th>
										   <th colspan="2" nowrap="nowrap">Remise</th>
										   <th colspan="2" nowrap="nowrap">Livraison</th>
										   <th nowrap="nowrap">Action</th>
										</tr>
									</thead>
									<tbody>
					       
											<tr>
												<td>
												  <select class="form-control" name="mode_paiement">                               <option value="">--Selectionnez--</option>
                                                   <option value="caisse">Caisse</option>
                                                  <option value="cheque">Cheque</option>
                                                  <option value="om">Orange Monney</option>
          
     											</select>
                                            <?php if(!empty($errors[1])): ?>
															   <span class="alert-danger"><?= $errors[1]; ?></span>
															 <?php endif; ?>
															
                                   
                                              </td>
												<td>
												   <div id="resultatCaisse" style="display:none">
                                    <input class="form-control" name="vente_versement" type="text" placeholder="Versement" />
                                    </div>
                                    <div id="resultatCheque" style="display:none">
                                   <input class="form-control" name="titulaire" type="text" placeholder="Titulaire" />
                                   <textarea name="detail" class="form-control"  placeholder="Code banque,IBAN,BIC etc."></textarea>
                                    <input class="form-control" name="codebanque" type="text" placeholder="Adresse banque" />
                                    </div></td>
												<td><input type="text" name="vente_remise" id="vente_remise" size="5" maxlength="5" class="form-control" value="<?= !empty($_POST['vente_remise'])?$_POST['vente_remise']:''; ?>" /></td>
												<td>%</td>
												<td><input type="text" name="vente_livraison" id="vente_livraison" size="5" maxlength="5" class="form-control" value="0" /></td>
												<td>Jour(s)</td>
											   <td><input type="submit" name="regler" id="regler" class="btn btn-default" value="regler" />
										      </td>
											</tr>
                                         </tbody>
								</table></form>
                                <div id="ContactForm">
</div>
                            
                                 <?php } ?>
													<!-- This is Customer Modal. It will be use for Ajouter new Records and Update Existing Records!-->
													<div id="customerModal" class="modal fade">
														 <div class="modal-dialog">
														  <div class="modal-content">
														   <div class="modal-header">
															<h4 class="modal-title">Ajouter un article</h4>
														   </div>
														   <div class="modal-body">
															<label>Article</label>
															<select data-size="8" class="bs-select form-control" data-live-search="true" name="prd_id" id="prd_id" >
															<?php foreach ($list_prod as $result_prod): ?>
																<option value="<?= $result_prod['prd_id']; ?>" >
																
																<?= $result_prod['prd_lib']; ?> (<?= $result_prod['prd_code']; ?>)</option>
																<?php endforeach; ?>
														     </select>
															<br />
															<label>Quantit&eacute;</label>
															<input type="text" name="qty_servi" id="qty_servi" class="form-control" />
															<br />
														   </div>
														   <div class="modal-footer">
															<input type="hidden" name="customer_id" id="customer_id" />
															<input type="submit" name="action" id="action" class="btn btn-success" />
															<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
														   </div>
														  </div>
														 </div>
													  </div>
													</div>
													 </div></div>
											 </div>
									
										</div>
										
										</div><?php } ?>
									</div>
									
									</div>
                                <!--tab_1_6-->
                                 <!--end tab-pane-->
                               </div> 
							   <div class="tab-pane" id="tab_1_6">
                                     <div class="row">
										<div class="col-md-12">
                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-title">
                                    <div class="caption"></div>
                                    <div class="actions">
                                    <div class="btn-group btn-group-devided" data-toggle="buttons">
                                      </div>
                                        
                                    </div>
                                </div>
                                <div class="portlet-body">
                               <div class="table-container">
								<table width="100%" class="table table-striped table-bordered table-hover" id="sample_3" >
                                    <thead>
										<tr>
										   <th nowrap="nowrap">N°</th>
										   <th nowrap="nowrap">Code de la vente</th>
										   <th nowrap="nowrap">Identité du vendeur</th>
										   <th nowrap="nowrap">Date</th>
										   <th nowrap="nowrap">Detail</th>
										</tr>
									</thead>
									<tbody>
					        <?php foreach ($list_hist as $key=> $historique) : ?>
											<tr>
												<td>
												   <?= $key+1; ?>
												</td>
												<td>
												   <?= $historique['vente_ref'] ?>
												  
												</td>
												<td>
												    <?= ucfirst(strtoupper($historique['auth_nom'])) ?> <?= ucfirst(strtoupper($historique['auth_pnom'])) ?>
												</td>
												<td><?= ucfirst(strtoupper($historique['vente_date'])) ?></td>
											   <td>
											   <div class="form-group last">
                                           
                                            <div class="col-md-4">
                                             
    								          <center>
											   <a href="#"  onclick="NewWindow('print_presta.php?vente_id=<?= crypturl($historique['vente_id']); ?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" title="Aper&ccedil;u" class="btn btn-xs btn-primary"><i class="fa fa-search"></i></a>
												  
												   </center>
												
											</div>
											</div>
										      </td>
											</tr>
                                             <?php endforeach; ?>
											
										</tbody>
								</table>
								</div>
                                </div>
                            </div>
                            <!-- End: life time stats -->
									</div>
								 </div>
                                </div>
                                
								</div></div></div><!--AJOUT BON DE REDUCTION-->
                                 <div id="bonModal" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <h4 class="modal-title">Ajouter bon de reduction</h4>
   </div>
   <div class="modal-body">
    <label>Entrer le code du bon</label>
    <input type="text" name="vente_bon" id="vente_bon" class="form-control" />
    <br />
    
   </div>
   <div class="modal-footer">
    <input type="hidden" name="bon_id" id="bon_id" />
    <input type="submit" name="action_bon" id="action_bon" class="btn btn-success" />
    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
   </div>
  </div>
 </div>
</div>
                             
                             
<script src="assets/pages/scripts/jquery2.2.0.min.js"></script>
<script>
						
$(document).ready(function(){
 fetchUser(); //This function will load all data on web page when page load
 function fetchUser() // This function will fetch data from table and display under <div id="result">
 {
  var action = "Load";
  $.ajax({
   url : "action.php", //Request send to "action.php page"
   method:"POST", //Using of Post method for send data
   data:{action:action}, //action variable data has been send to server
   success:function(data){
    $('#result').html(data); //It will display data under div tag with id result
   }
  });
 }

 //This JQuery code will Reset value of Modal item when modal will load for Ajouter new records
 $('#modal_button').click(function(){
  $('#customerModal').modal('show'); //It will load modal on web page
  $('#prd_id').val(''); //This will clear Modal first name textbox
  $('#qty_servi').val(''); //This will clear Modal last name textbox
  $('.modal-title').text("Ajouter des articles"); //It will change Modal title to Ajouter new Records
  $('#action').val('Ajouter'); //This will reset Button value ot Ajouter
 });

 //This JQuery code is for Click on Modal action button for Ajouter new records or Update existing records. This code will use for both Ajouter and Update of data through modal
 $('#action').click(function(){
  var prdId = $('#prd_id').val(); //Get the value of first name textbox.
  var qtyServi = $('#qty_servi').val(); //Get the value of last name textbox
  var id = $('#customer_id').val();  //Get the value of hidden field customer id
  var action = $('#action').val();  //Get the value of Modal Action button and stored into action variable
  if(prdId != '' && qtyServi != '') //This condition will check both variable has some value
  {
   $.ajax({
    url : "action.php",    //Request send to "action.php page"
    method:"POST",     //Using of Post method for send data
    data:{prdId:prdId, qtyServi:qtyServi, id:id, action:action}, //Send data to server
    success:function(data){
     alert(data);    //It will pop up which data it was received from server side
     $('#customerModal').modal('hide'); //It will hide Customer Modal from webpage.
     fetchUser();    // Fetch User function has been called and it will load data under divison tag with id result
    }
   });
  }
  else
  {
   alert("Champ requis!"); //If both or any one of the variable has no value them it will display this message
  }
 });

 //This JQuery code is for Update customer data. If we have click on any customer row update button then this code will execute
 $(document).on('click', '.update', function(){
  var id = $(this).attr("id"); //This code will fetch any customer id from attribute id with help of attr() JQuery method
  var action = "Select";   //We have define action variable value is equal to select
  $.ajax({
   url:"action.php",   //Request send to "action.php page"
   method:"POST",    //Using of Post method for send data
   data:{id:id, action:action},//Send data to server
   dataType:"json",   //Here we have define json data type, so server will send data in json format.
   success:function(data){
    $('#customerModal').modal('show');   //It will display modal on webpage
    $('.modal-title').text("Modifier article"); //This code will change this class text to Update records
    $('#action').val("Modifier");     //This code will change Button value to Update
    $('#customer_id').val(id);     //It will define value of id variable to this customer id hidden field
    $('#prd_id').val(data.prd_id);  //It will assign value to modal first name texbox
    $('#qty_servi').val(data.qty_servi);  //It will assign value of modal last name textbox
   }
  });
 });

 //This JQuery code is for Delete customer data. If we have click on any customer row delete button then this code will execute
 $(document).on('click', '.delete', function(){
  var id = $(this).attr("id"); //This code will fetch any customer id from attribute id with help of attr() JQuery method
  if(confirm("Etre vous sur de supprimer cette ligne?")) //Confim Box if OK then
  {
   var action = "Delete"; //Define action variable value Delete
   $.ajax({
    url:"action.php",    //Request send to "action.php page"
    method:"POST",     //Using of Post method for send data
    data:{id:id, action:action}, //Data send to server from ajax method
    success:function(data)
    {
     fetchUser();    // fetchUser() function has been called and it will load data under divison tag with id result
     alert(data);    //It will pop up which data it was received from server side
    }
   })
  }
  else  //Confim Box if cancel then 
  {
   return false; //No action will perform
  }
 });

 
 fetchBon(); //This function will load all data on web page when page load
 function fetchBon() // This function will fetch data from table and display under <div id="result_bon">
 {
  var action_bon = "Load";
  $.ajax({
   url : "bon_red.php", //Request send to "bon_red.php page"
   method:"POST", //Using of Post method for send data
   data:{action_bon:action_bon}, //action_bon variable data has been send to server
   success:function(data){
    $('#result_bon').html(data); //It will display data under div tag with id result_bon
   }
  });
 }

 //This JQuery code will Reset value of Modal item when modal will load for create new records
 $('#modal_bon').click(function(){
  $('#bonModal').modal('show'); //It will load modal on web page
  $('#vente_bon').val(''); //This will clear Modal first name textbox
  $('.modal-title').text("Ajouter bon"); //It will change Modal title to Ajouter new Records
  $('#action_bon').val('Ajouter'); //This will reset Button value ot Ajouter
 });

 //This JQuery code is for Click on Modal action_bon button for Ajouter new records or Update existing records. This code will use for both Ajouter and Update of data through modal
 $('#action_bon').click(function(){
  var venteBon = $('#vente_bon').val(); //Get the value of first name textbox.
  var id = $('#bon_id').val();  //Get the value of hidden field customer id
  var action_bon = $('#action_bon').val();  //Get the value of Modal Action button and stored into action_bon variable
  if(venteBon !='') //This condition will check both variable has some value
  {
   $.ajax({
    url : "bon_red.php",    //Request send to "bon_red.php page"
    method:"POST",     //Using of Post method for send data
    data:{venteBon:venteBon,id:id, action_bon:action_bon}, //Send data to server
    success:function(data){
     alert(data);    //It will pop up which data it was received from server side
     $('#bonModal').modal('hide'); //It will hide Customer Modal from webpage.
     fetchBon();    // Fetch User function has been called and it will load data under divison tag with id result_bon
    }
   });
  }
  else
  {
   alert("Champ requis"); //If both or any one of the variable has no value them it will display this message
  }
 });
 
 //This JQuery code is for Delete customer data. If we have click on any customer row delete button then this code will execute
 $(document).on('click', '.deletebon', function(){
  var id = $(this).attr("id"); //This code will fetch any customer id from attribute id with help of attr() JQuery method
  if(confirm("Etre vous sur de supprimer cette ligne?")) //Confim Box if OK then
  {
   var action_bon = "Deletebon"; //Define action variable value Delete
   $.ajax({
    url:"bon_red.php",    //Request send to "action.php page"
    method:"POST",     //Using of Post method for send data
    data:{id:id, action_bon:action_bon}, //Data send to server from ajax method
    success:function(data)
    {
     fetchBon();    // fetchUser() function has been called and it will load data under divison tag with id result
     alert(data);    //It will pop up which data it was received from server side
    }
   })
  }
  else  //Confim Box if cancel then 
  {
   return false; //No action will perform
  }
 });
});
</script>

<script>
$(document).ready(function() {
     $('select').change(function(){
		 if( $(this).val() == 'caisse' )
          {
              $('#resultatCaisse').css('display','block');
          }
         else{
			 $('#resultatCaisse').css('display','none');
		 }
		 if( $(this).val() == 'cheque' )
          {
              $('#resultatCheque').css('display','block');
          }
         else{
			 $('#resultatCheque').css('display','none');
		 }
     });
});
</script>
