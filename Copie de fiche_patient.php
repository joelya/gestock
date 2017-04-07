<?php
//var_dump($_GET);exit;
//var_dump($_SESSION);exit;
include('./model/connexion.php');
$pdo=connect();


//$ok = false;
if(isset($_GET['codep'])){
$_SESSION['codep'] = $_GET['codep'];
}
//RECHERCHE DES PRESCRITPIONS DE CETTE CONSULTATION
$stmtP = $pdo->prepare("SELECT * FROM `prescrire_medi` INNER JOIN prescription ON prescrire_medi.`presc_id` = prescription.`presc_id` JOIN medicament ON prescrire_medi.`medi_id` = medicament.`medi_id` WHERE prescription.`presta_id` = '".@$_GET['codep']."' ORDER BY prescrire_medi.prescr_id DESC");
$stmtP->execute();
$countprescrip = $stmtP->rowCount();
foreach($stmtP as $pres){
$presc_id = $pres['presc_id'];
}	
//var_dump($presc_id);exit;	
if(isset($_POST['clot_info'])){
 $update_clot_info = "UPDATE `db_doctor_secu`.`prestation` SET `flag_clot` = '1' WHERE `prestation`.`presta_id` = '".@$_GET['codep']."'";
 $pdo->query($update_clot_info);

}
/* AJOUTER A NOUVEAU
if(isset($_POST['add_prescr'])){
 $update_clot_prescr= "UPDATE `db_doctor_secu`.`prescrire_medi` SET `add` = '1' WHERE `prescrire_medi`.`presc_id` = '".@$presc_id."'";
 $pdo->query($update_clot_prescr);
//unset($_SESSION['presc']); 
}
*/
//CLOTURER LA PRESCRIPTION
if(isset($_POST['clot_prescr'])){
 $update_clot_prescr= "UPDATE `db_doctor_secu`.`prescrire_medi` SET `clot` = '1' WHERE `prescrire_medi`.`presc_id` = '".@$presc_id."'";
 $pdo->query($update_clot_prescr);
unset($_SESSION['presc']); 
}		
 if(isset($_POST['cloture'])){
// $ok = true;
  //var_dump($countclot);exit;
 //var_dump($_SESSION);exit;
 $update = "UPDATE `db_doctor_secu`.`prescription` SET `clot` = '1' WHERE `prescription`.`presta_id` = '".@$_GET['codep']."'";
 $pdo->query($update);
 //LISTE DES PRESCRIPTIONS CLOTS;
		
 }

	$travailleur = $_GET['trav_id'];
	$pageS = 'fiche_patient&trav_id='.$travailleur.'&creer=ok';
	$_SESSION['pageS'] = $pageS;
//TRAITEMENT EN CAS DE VALIDATION FICHE AM
	if(isset($_POST['valid_am'])){
			$val_fiche_am = "UPDATE `fichea_maladie` SET `flag_valider`=1
			WHERE presta_id ='".$_GET['codep']."'";	
			$pdo->query($val_fiche_am);
			}
	 
//TRAITEMENT LORS DE LA MISE A JOUR DE LA FICHE ARRET MALADIE;
	if(isset($_POST['mod_am'])){
			
			//var_dump($_SESSION);var_dump($_POST);exit;
			//$fiche_am_motif = htmlentities(trim($_POST['fiche_am_motif']));
			$fiche_am_date_deb = htmlentities(trim($_POST['fiche_am_date_deb']));
			$fiche_am_date_fin = htmlentities(trim($_POST['fiche_am_date_fin']));
			$codep = $_GET['codep'];
			//INSERTION BD
			$dateMod = date("Y-m-d").' '.date("H:i:s");
			/**
			*INSERTION DE LA FICHE ARRET MALADIE
			**/
			 $up_fiche_am = "UPDATE `fichea_maladie` SET `ficheam_date_deb`='".$fiche_am_date_deb."',
			 `ficheam_date_fin`='".$fiche_am_date_fin."',`flag`=2
			WHERE presta_id ='".$codep."'";	
			$pdo->query($up_fiche_am);
		//REDIRECTION 
		}
	
	 if(isset($_POST['joindre_am'])){
			
			//var_dump($_SESSION);var_dump($_POST);exit;
			//$fiche_am_motif = htmlentities(trim($_POST['fiche_am_motif']));
			 //GESTION DES RESTRICTIONS
			 $errorsfam = array();
			 $valid=true;
         
        if(empty($_POST['fiche_am_date_deb']))
        {
            $errorsfam[0]='Veuillez saisir la date de debut';
            $valid=false;
        }
		 if(empty($_POST['fiche_am_date_fin']))
        {
            $errorsfam[1]='Veuillez saisir la date de fin';
            $valid=false;
        }
       
	   if($valid == true){
			$fiche_am_date_deb = htmlentities(trim($_POST['fiche_am_date_deb']));
			$fiche_am_date_fin = htmlentities(trim($_POST['fiche_am_date_fin']));
			$codep = $_GET['codep'];
			//INSERTION BD
			$dateMod = date("Y-m-d").' '.date("H:i:s");
			/**
			*INSERTION DE LA FICHE ARRET MALADIE
			**/
			 $sql_fiche_am = "INSERT INTO `fichea_maladie`(`presta_id`, `ficheam_date_crea`, `ficheam_date_deb`, `ficheam_date_fin`,`flag_creer`) VALUES(?,?,?,?,?);
";	
		$req_fiche_am = $pdo->prepare($sql_fiche_am);
		$req_fiche_am->execute(array($codep,$dateMod,$fiche_am_date_deb,$fiche_am_date_fin,1));
		
       $url = '<script>location.href="'.$_SESSION['proflis'].'.php?p=fiche_patient&ficheam=creer&codep='.$_GET['codep'].'&trav_id='.$_GET['trav_id'].'</script>';
       echo($url);
		//REDIRECTION 
	   }
			}
	//Affiche historique prestation
    $sql_hist = "select * from prestation inner join authentification ON prestation.auth_id = authentification.auth_id  WHERE trav_id = '".$_GET['trav_id']."' ORDER BY prestation.presta_date_crea DESC ";
	$list_hist = $pdo->prepare($sql_hist);
    $list_hist->execute();
	
	//Affiche historique prescriptions
    $sql_histpres = "SELECT * FROM prestationp pp 
					INNER  JOIN prestation p ON pp.presta_id = p.presta_id
					JOIN authentification a ON a.auth_id = p.auth_id
                    JOIN prescription pr ON pr.presta_id = p.presta_id 
                    JOIN prescrire_medi pm ON pm.presc_id = pr.presc_id 
					JOIN travailleur tr ON tr.trav_id = p.trav_id 
					WHERE tr.trav_id = '".$_GET['trav_id']."'
                    AND pm.clot  = 1
					GROUP BY pr.presc_id
					ORDER BY pr.presc_id DESC";
	$list_histpres = $pdo->prepare($sql_histpres);
    $list_histpres->execute();
	//Affiche historique fiche arrêt maladie
    $sql_histfam = "SELECT * FROM prestation p
					INNER JOIN fichea_maladie fam ON fam.presta_id = p.presta_id
					JOIN authentification a ON a.auth_id = p.auth_id
					JOIN travailleur tr ON tr.trav_id = p.trav_id 
					WHERE tr.trav_id = '".$_GET['trav_id']."' 
					AND fam.flag_valider=1
					ORDER BY fam.presta_id DESC
					";
	$list_histfam = $pdo->prepare($sql_histfam);
    $list_histfam->execute();
	
    //liste des pathologies
	$sql_path = "SELECT * FROM pathologie WHERE flag=1 ORDER BY path_lib ASC";
	$list_path = $pdo->prepare($sql_path);
    $list_path->execute();
	//liste des médicaments
	$sql_medi = "SELECT * FROM medicament WHERE flag=1 ORDER BY medi_lib ASC";
	$list_medi = $pdo->prepare($sql_medi);
    $list_medi->execute();
	//liste des entreprises
	if(isset($_GET['trav_id'])){
	$trav_id = $_GET['trav_id'];
	}
	//liste des travailleurs
$sql_ent = "SELECT ent_lib from travailleur tr 
			INNER JOIN entreprise ent ON tr.ent_id = ent.ent_id 
			WHERE tr.flag = 1 AND trav_id = '".$trav_id."'";
$sql_dir = "select dir_lib from travailleur tr 
			INNER JOIN direction dir ON tr.dir_id = dir.dir_id 
			WHERE tr.flag = 1 AND trav_id = '".$trav_id."'"; 
$sql_svc = "select svc_lib from travailleur tr 
			INNER JOIN service svc ON tr.svc_id = svc.svc_id 
			WHERE tr.flag = 1 AND trav_id = '".$trav_id."'";
			
	$list_ent = $pdo->prepare($sql_ent);
	$list_dir = $pdo->prepare($sql_dir);
	$list_svc = $pdo->prepare($sql_svc);
    $list_ent->execute();
	$list_dir->execute();
	$list_svc->execute();
	
	foreach ($list_ent as $entreprise){
	$ent_lib =  $entreprise['ent_lib'];	
	}
	foreach ($list_dir as $direction){
	$dir_lib =  $direction['dir_lib'];
	}
	foreach ($list_svc as $service){
	$svc_lib =  $service['svc_lib'];
	}
	
	
	$sql_trav = "SELECT * from travailleur tr 
	INNER JOIN fonction f ON tr.fonct_id = f.fonct_id 
	WHERE tr.flag = 1 AND trav_id = '".$trav_id."'";
	$list_trav = $pdo->prepare($sql_trav);
    $list_trav->execute();
	foreach ($list_trav as $result){
	$nom =  $result['trav_nom'];	
	$pnom =  $result['trav_pnom'];	
	$mat =  $result['trav_mat'];	
	$email =  $result['trav_email'];	
	$cont =  $result['trav_cont'];
	$prof =  $result['fonct_lib'];
	$age =  $result['trav_age'];	
	$sexe =  $result['trav_sexe'];
	}
	
		
		/**
		   *	INSERTION DE LA PRESTATION
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//var_dump($_SESSION);exit;
		 		//RECUPERATION DES VARIABLES DE LA PRESTATION MEDICAL
		
		 	@$path_id = $_POST['path_id'];
			$count = count($path_id);
			$presta_com = addslashes (trim($_POST['presta_com']));
			$presta_poids = htmlentities(trim($_POST['presta_poids']));
			$presta_tension = htmlentities(trim($_POST['presta_tension']));
			$presta_taille = htmlentities(trim($_POST['presta_taille']));
			$presta_temp = htmlentities(trim($_POST['presta_temp']));
		
		    //GESTION DES ERREURS DE SAISIE
			 $errors = array();
			 $valid=true;
         
        if(empty($path_id[0]))
        {
            $errors[0]='Veuillez Choisir une maladie';
            $valid=false;
        }
		 if(empty($presta_com))
        {
            $errors[1]='Veuillez saisir le rapport du diagnostic';
            $valid=false;
        }
		 
        if($valid == true){
		
			
			$dateMod = date("Y-m-d").' '.date("H:i:s");
			/**
			*INSERTION DE LA PRESTATION
			**/
			$codep =  strtoupper(dechex(crc32(uniqid())));
			 $sql = "INSERT INTO `prestation`(`presta_code`,`trav_id`,`auth_id`, `presta_temp`, `presta_poids`, `presta_com`, `presta_taille`, `presta_tension`, `presta_date_crea`, `presta_date_mod`,`flag`,`flag_creer`)
    VALUES (?,?, ?, ?, ?,?,?,?,?,?,?,?);
";	

		$req = $pdo->prepare($sql);
		
		$req->execute(array($codep,$trav_id,$_SESSION['auth_id'],$presta_temp,$presta_poids,$presta_com,$presta_taille,$presta_tension,$dateMod,$dateMod,1,1));
		$presta_id = $pdo->lastInsertId();
		//$_GET['codep'] = $presta_id;
	
		
        
		
		/**
        *   INSERTION DES LIGNE DE PATHOLOGIES
        **/

        $sqli = "INSERT INTO `prestationp`(`presta_id`,`path_id`, `prestp_date_crea`, `prestp_date_mod`,`flag`) VALUES (?,?,?,?,?)";
        $reqi = $pdo->prepare($sqli);

        for($i=0; $i<$count; $i++)
        {
        $reqi->execute(array($presta_id,$path_id[$i],$dateMod,$dateMod,1));	
        }
		
		
			 /**
		   *	REDIRECTION JAVASCRIPT
		   **/
		   	$travailleur = $_GET['trav_id'];
			echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=fiche_patient&codep='.$presta_id.'&trav_id='.$trav_id.'"</script>';
			
			}
	 }
	 
	 //MISE A JOUR DE LA PRESTATION
	 if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['update_info']))
     {
	 
		 //var_dump($_SESSION,$_POST);exit;
		 		//RECUPERATION DES VARIABLES DE LA PRESTATION MEDICAL
		
		 	@$path_id2 = $_POST['path_id'];
			$count2 = count($path_id2);
			
			$presta_com2 = addslashes (trim($_POST['presta_com']));
			$presta_poids2 = htmlentities(trim($_POST['presta_poids']));
			$presta_tension2 = htmlentities(trim($_POST['presta_tension']));
			$presta_taille2 = htmlentities(trim($_POST['presta_taille']));
			$presta_temp2 = htmlentities(trim($_POST['presta_temp']));
		
		    //GESTION DES ERREURS DE SAISIE
			 $errors = array();
			 $valid=true;
         
        if(empty($path_id2[0]))
        {
            $errors[0]='Veuillez Choisir une maladie';
            $valid=false;
        }
		 if(empty($presta_com2))
        {
            $errors[1]='Veuillez saisir le rapport du diagnostic';
            $valid=false;
        }
		 
        if($valid == true){
		
			
			$dateMod = date("Y-m-d").' '.date("H:i:s");
			/**
			*MISE A JOUR DE LA PRESTATION
			**/
			$presta_id = $_GET['codep'];
			$update = " UPDATE `prestation` SET 
						`presta_temp`='".$presta_temp2."',`presta_poids`='".$presta_poids2."',
						`presta_com`='".$presta_com2."',`presta_taille`='".$presta_taille2."',
						`presta_tension`='".$presta_tension2."',`presta_date_crea`='".$dateMod."',
						`presta_date_crea`='".$dateMod."',`presta_date_mod`='".$dateMod."',flag=2
						WHERE presta_id = '".$presta_id."' ";	

		       $pdo->query($update);
		
		/**
        *   SUPPRESSION DES LIGNE DE PATHOLOGIES
        **/
		$del = "DELETE FROM `db_doctor_secu`.`prestationp` WHERE `prestationp`.`presta_id` = '".$_GET['codep']."'";
		$pdo->query($del);
      
		/**
        *   INSERTION DES LIGNE DE PATHOLOGIES
        **/

        $sqli = "INSERT INTO `prestationp`(`presta_id`,`path_id`, `prestp_date_crea`, `prestp_date_mod`,`flag`) VALUES (?,?,?,?,?)";
        $reqi = $pdo->prepare($sqli);

        for($i=0; $i<$count2; $i++)
        {
		//echo $path_id2[$i];exit;
        $reqi->execute(array($_GET['codep'],$path_id2[$i],$dateMod,$dateMod,1));	
        }
			}
	 }
	 //METTRE DANS LE TRABLEAU LES PATHOLOGIES APRES VALIDATION
	 
	 //AFFICHER LES Pathologies DIAGNOSTIQUEES 
	$medi_pres = "SELECT * FROM `prestationp` INNER JOIN pathologie ON prestationp.path_id = pathologie.path_id JOIN prestation ON prestationp.presta_id = prestation.presta_id WHERE prestation.presta_id = '".@$_GET['codep']."'  AND prestation.trav_id = '".$trav_id."'";
	 $list_medipres = $pdo->prepare($medi_pres);
    $list_medipres->execute();
	
	 //AFFICHER LES Pathologies DIAGNOSTIQUEES 
	$medi_pr = "SELECT * FROM `prestationp` INNER JOIN pathologie ON prestationp.path_id = pathologie.path_id JOIN prestation ON prestationp.presta_id = prestation.presta_id WHERE prestation.presta_id = '".@$_GET['codep']."'  AND prestation.trav_id = '".$trav_id."'";
	$list_medipr = $pdo->prepare($medi_pr);
    $list_medipr->execute();
	$lespathologie=null;
	foreach ($list_medipr as  $un){
	$lespathologie[]=$un;
	}
	//Affiche les informations DE LA PRESCRIPTION 
    $sql_patient = "select * from prestation inner join authentification ON prestation.auth_id = authentification.auth_id  WHERE trav_id = '".$trav_id."' AND prestation.presta_id = '".@$_GET['codep']."'";
	$list_patient = $pdo->prepare($sql_patient);
    $list_patient->execute();
	foreach ($list_patient as $key => $result_patient){
	$patient_id = $result_patient['presta_id'];
	$patient_code = $result_patient['presta_code'];
	$patient_auth_nom = $result_patient['auth_nom'];
	$patient_auth_pnom = $result_patient['auth_pnom'];
	$patient_date_crea = $result_patient['presta_date_crea'];
	$commentaire = $result_patient['presta_com'];
	$taille = $result_patient['presta_taille'];
	$poids = $result_patient['presta_poids'];
	$tension = $result_patient['presta_tension'];
	$temperature = $result_patient['presta_temp'];
	}
	 
	 //AFFICHER LES INFORMATIONS DE LA FICHE D'ARRET MALADIE
	 
		$fiche_am = $pdo->prepare("SELECT * FROM `fichea_maladie` WHERE `presta_id`='".@$_GET['codep']."' ORDER BY `ficheam_id` DESC");
		$fiche_am->execute();
		foreach ($fiche_am as $am){
			$debut = $am['ficheam_date_deb'];
			$fin =  $am['ficheam_date_fin'];
			}
		  //POUR VERIFIER SI UNE FICHE D'ARRET MALADIE A DEJA ETE CLOTUREE
		
		$clotAma = $pdo->prepare("SELECT `flag_valider` FROM `fichea_maladie` WHERE presta_id = '".@$_GET['codep']."' AND flag_valider =1");
		$clotAma->execute();
		$cloturerAma= $clotAma->rowCount();
	  //POUR VERIFIER SI UNE FICHE D'ARRET MALADIE A DEJA ETE FAITE
		$clotAm = $pdo->prepare("SELECT `flag_creer` FROM `fichea_maladie` WHERE presta_id = '".@$_GET['codep']."' AND flag_creer =1");
		$clotAm->execute();
		$countclotAm= $clotAm->rowCount();
		
	//POUR VERIFIER SI UNE FICHE D'ARRET MALADIE A  ETE MODIFIEE
		$slqmod = $pdo->prepare("SELECT `flag` FROM `fichea_maladie` WHERE presta_id = '".@$_GET['codep']."' AND flag =2");
		$slqmod->execute();
		$countmodAm = $slqmod->rowCount();
		
	 //POUR VERIFIER SI UNE CONSULTATION A DEJA ETE CREER
		$clotP = $pdo->prepare("SELECT `flag_creer` FROM `prestation` WHERE presta_id = '".@$_GET['codep']."' AND flag_creer =1");
		$clotP->execute();
		$countclotP = $clotP->rowCount();
	
	//POUR VERIFIER SI UNE CONSULTATION A DEJA ETE MODIFIEE
		$clotM = $pdo->prepare("SELECT `flag` FROM `prestation` WHERE presta_id = '".@$_GET['codep']."' AND flag =2");
		$clotM->execute();
		$countmod = $clotM->rowCount();
		//var_dump($countmod);exit;
		//var_dump($countclotP);exit;
//POUR VERIFIER SI UNE PRESTATION A DEJA ETE FAITE
		$clot_def = $pdo->prepare("SELECT `flag_clot` FROM `prestation` WHERE presta_id = '".@$_GET['codep']."' AND flag_clot =1");
		$clot_def->execute();
		$cloturer= $clot_def->rowCount();


//POUR VERIFIER SI UNE PRESCRIPTION A DEJA ETE CLOTURER
		$clotprescription = $pdo->prepare("SELECT prescrire_medi.clot FROM `prescrire_medi` INNER JOIN prescription ON prescrire_medi.`presc_id` = prescription.`presc_id` JOIN medicament ON prescrire_medi.`medi_id` = medicament.`medi_id` WHERE prescription.`presta_id` = '".@$_GET['codep']."' AND prescrire_medi.clot = 1");
		$clotprescription->execute();
		$countprescrip= $clotprescription->rowCount();
//POUR VERIFIER SI IL Y A DES MEDICAMENTS AJOUTES
$clotmedicament = $pdo->prepare("SELECT prescrire_medi.clot FROM `prescrire_medi` INNER JOIN prescription ON prescrire_medi.`presc_id` = prescription.`presc_id` JOIN medicament ON prescrire_medi.`medi_id` = medicament.`medi_id` WHERE prescription.`presta_id` = '".@$_GET['codep']."' ");
		$clotmedicament->execute();
		$countmedicament= $clotmedicament->rowCount();
	 //var_dump($countmedicament);exit;
	 ?>
	 
		<script type="text/javascript" src="assets/jquery-1.11.3-jquery.min.js"></script>
		<!--pour rafraichir la div automatiquement -->
		<script type="text/javascript">
		var auto_refresh = setInterval(
		  function ()
		  {
			$('#load_donnees').load('<?php echo $_SESSION['pageS']; ?>').fadeIn("slow");
		  }, 10000); // rafraichis toutes les 10000 millisecondes
		 
		</script>

		<script type="text/javascript">
		$(document).ready(function(){
			
			$("#btn-view").hide();
			
			$("#btn-add").click(function(){
				$(".content-loader").fadeOut('slow', function()
				{
					$(".content-loader").fadeIn('slow');
					$(".content-loader").load('add_form.php');
					$("#btn-add").hide();
					$("#btn-view").show();
				});
			});
			
			$("#btn-view").click(function(){
				
				$("body").fadeOut('slow', function()
				{
					$("body").load('medicament.php');
					$("body").fadeIn('slow');
					
					window.location.reload();
				});
			});
			
		});
		</script>
     <link rel="stylesheet" href="autocomplete.css" type="text/css">
     <script type="text/javascript" src="jquery-1.7.1.min.js"></script>  
     	<div class="page-bar">
                        <ul class="page-breadcrumb">
						<li>
                                <a href="<?= $_SESSION['proflis']?>.php">Accueil</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <a href="<?= $_SESSION['proflis']?>.php?p=consulter">Consultation</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Traitement du patient</span>
                            </li>
                        </ul>
                        
                    </div>
                      <br/>
                    <div class="note note-success">Les champs marqués d'un astérisque <span style="color:#F00"> (*)</span> 
					sont obligatoires </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
					
                  
                    <h3 class="page-title">Traitement du patient</h3>
					
				  
				 <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                   
                    <div class="profile">
                        <div class="tabbable-line tabbable-full-width">
                            <ul class="nav nav-tabs">


                                <li class="active">
                                    <a href="#tab_1_1" data-toggle="tab"> Traitement du patient </a>
                                </li>
                              
                                <li>
                                    <a href="#tab_1_6" data-toggle="tab">Historique des consultations</a>
                                </li>
								<li>
                                    <a href="#tab_1_4" data-toggle="tab">Historique des Prescriptions</a>
                                </li>
								<li>
                                    <a href="#tab_1_2" data-toggle="tab">Historique des fiches d'arrêt maladie</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1_1">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <ul class="list-unstyled profile-nav">
                                                <li>
                                                    <img src="assets/pages/media/profile/people19.png" class="img-responsive pic-bordered" alt="" />
                                                  
                                                </li>
                                                <li>
                                                    <a href="javascript:;">  <?= strtolower($prof);?> </a>
                                                </li>
                                                <?php if ($ent_lib != 'aucun'): ?>
                                                <li>
                                                    <a href="javascript:;">   <?= strtolower($ent_lib);?>
                                                       
                                                    </a>
                                                </li>
                                                <?php endif; ?>
                                                 <?php if ($dir_lib != 'aucun'): ?>
                                                <li>
                                                    <a href="javascript:;"> <?= strtolower($dir_lib);?> </a>
                                                </li>
                                                <?php endif; ?>
                                                <?php if ($svc_lib != 'aucun'): ?>
                                                <li>
                                                    <a href="javascript:;"> <?= strtolower($svc_lib);?> </a>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                       <div class="col-md-9">
									  
									         <div class="row">
                                                <div class="col-md-8 profile-info">
                                                    <h1 class="font-black sbold uppercase"><?= $nom ?> <?= $pnom ?></h1>
                                                    
                                                   
                                                    <ul class="list-inline">
                                                        
                                                        <li>
                                                      <i class="fa fa-calendar"></i> <?php echo $age; ?></li>
                                                        
                                                        <li>
                                                            <i class="fa fa-phone"></i> <?= $cont;?></li>
                                                       
                                                    </ul>
                                                </div>
                                                <!--end col-md-8-->
                                                
                                                <!--end col-md-4-->
                                            </div>
												
										<div class="col-md-12">
											 <div class="tab-content">
												<div class="portlet light bordered">
																 <?php if ($cloturer==1){ ?>
													   <center>
													   <a href="#" class="btn default" onclick="NewWindow('print_presta.php?presta_id=<?= $_GET['codep']; ?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" title="Aper&ccedil;u de la prestation">Aperçu de la consultation</a>
													   
													   </center>
													   <?php } ?>
													   <?php if ($cloturer==0){ ?>
													   <div>
													   <?php if ($countclotP==1 AND $countmod==0){ ?>
													   <div class="alert alert-success alert-dismissable">
														<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
														 <a href="" class="alert-link">Consultation créee</a>
														</div>
														<?php } ?>
														<?php if ($countmod==1){ ?>
														   <div class="alert alert-success alert-dismissable">
																<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
																 <a href="" class="alert-link">Consultation modifiée</a>
														  </div>
															<?php } ?>
															</div>
														<?php } ?>
													<div class="portlet-title">
														<div class="caption">
															<i class="icon-social-dribbble font-red"></i>
															<span class="caption-subject font-red bold uppercase">Consultation</span>
														</div>
													</div>
                                                    <form action="" method="post" role="form" >
													<div class="row">
													<div class="col-md-12">
													<div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"><b>Infirmier</b></label>
                                                            <input name="auth_nom" type="text" class="form-control" id="auth_nom" value="<?= ucfirst(strtolower($_SESSION['name']))?> <?= ucfirst(strtolower($_SESSION['pname']))?>" readonly  /> 
                                                            
														</div>
                                                       
                                                        <div class="form-group">
                                                            <label class="control-label"><b>Température du patient</b></label>
                                                         
														<input type="text" name="presta_temp" id="trav_nom" size="45" maxlength="255" class="form-control" value="<?php if(@$countclotP==1){echo ucfirst(strtoupper(@$temperature));}?>" <?php if ($cloturer==1){ echo 'readonly'; } ?> />                                                                                                                    
														</div>
														 <div class="form-group">
                                                            <label class="control-label"><b>Taille du patient</b></label>
                                                         <input type="text" name="presta_taille" id="presta_taille" size="45" maxlength="255" class="form-control" value="<?php if(@$countclotP==1){ echo ucfirst(strtoupper(@$taille)); }?>" <?php if ($cloturer==1){ echo 'readonly'; } ?> />
														</div>
														
													</div>
													<div class="col-md-6">
													    <div class="form-group">
													       <label for="multiple" class="control-label"><b>Maladie(s) diagnostiquée(s) : </b><span style="color:#F00"> (*)</span> </label>
																<?php if ($cloturer==1) : ?>														   
																<?php $boucle = 0; foreach ($list_medipres as $medicam): ?>
																<?php if($boucle>0){ echo ',';} ?> <?= strtolower($medicam['path_lib']); ?>	
																<?php $boucle++; endforeach; ?>
																<?php endif; ?>	
																<?php if ($cloturer==0) : ?>												
																<select name="path_id[]" multiple class="form-control select2-multiple" id="multiple[]"  <?php if ($cloturer==1){ echo 'readonly=readonly'; } ?>>
																
																<?php foreach ($list_path as $key => $result_path): ?>
																<option <?php if(@$countclotP==1) {foreach (@$lespathologie as $mal){if($result_path['path_id']==@$mal['path_id']) echo 'selected';} }?> value="<?= $result_path['path_id'] ?>"><?= ucfirst(strtolower(@$result_path['path_lib'])) ?>
                                                                </option>
																<?php endforeach; ?>
																</optgroup>
																</select>
																	 <?php endif; ?>															 
																	 <?php if(!empty($errors[0])): ?>
																<span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
                                                        </div>
														<div class="form-group">
                                                            <label class="control-label"><b>Tension du patient</b></label>
                                                            <input type="text" name="presta_tension" id="presta_tension" size="45" maxlength="255" class="form-control" value="<?php if(@$countclotP==1){ echo ucfirst(strtoupper(@$tension)); }?>" <?php if ($cloturer==1){ echo 'readonly'; } ?> />
														</div>
														<div class="form-group">
                                                            <label class="control-label"><b>Poids du patient</b></label>
                                                            <input type="text" name="presta_poids" id="ray_lib" maxlength="255" class="form-control" value="<?php if(@$countclotP==1){ echo ucfirst(strtoupper(@$poids)); } ?>" <?php if ($cloturer==1){ echo 'readonly'; } ?> />
														</div>
													 </div>
													 <div class="form-group">
													 
                                                            <label class="control-label"><b>Diagnostic: </b><span style="color:#F00"> (*)</span></label>
                                                            <textarea name="presta_com" rows="3" class="form-control" id="presta_com" placeholder="Faites un commentaire" <?php if ($cloturer==1){ echo 'readonly'; } ?>><?php if(@$countclotP==1){echo  htmlentities(trim(@$commentaire)); }?></textarea>
                                                            <?php if(!empty($errors[1])): ?>
															   <span class="alert-danger"><?= $errors[1]; ?></span>
															 <?php endif; ?>
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
                                                  </div>
											 </div>
									
										</div>
										</div>
									</div>
									<div class="row">
									<div class="col-md-12">
										<!--TRAITEMENT DE LA PRESCRIPTION -->
										<div class="portlet light bordered">
													<div class="portlet-title">
														<div class="caption">
															<i class="icon-social-dribbble font-red"></i>
															<span class="caption-subject font-red bold uppercase">Prescription médicale</span>
														</div>
													</div>
											<div>
											<form action="" method="post">
													<?php if ($countprescrip>0) : ?>
													<a href="#" class="btn default" onclick="NewWindow('print_prescr.php?presc_id=<?= $presc_id; ?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" title="Aper&ccedil;u de la planification" >Aperçu de la prescription</a>		
													<?php endif; ?>
															
															<?php if ($countprescrip==0) : ?>
													        <button class="btn btn-default" type="button" id="btn-add"> <span class="glyphicon glyphicon-pencil"></span> &nbsp; Ajouter M&eacute;dicament</button>
															<?php if($countmedicament>0) : ?>
															<a href="#" class="btn default" onclick="NewWindow('print_prescr.php?presc_id=<?= $presc_id; ?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" title="Aper&ccedil;u de la planification" >Aperçu de la prescription</a>		
															<input class="btn green" type="submit" name="clot_prescr" value="cloturer" />  
															<?php endif; ?>
															<button class="btn btn-default" type="button" id="btn-view"> <span class="glyphicon glyphicon-eye-open"></span> &nbsp; m&eacute;dicaments prescrits</button>
															<?php endif; ?>
															<hr />
															
															<div class="content-loader">
															
															<table cellspacing="0" width="100%" id="example" class="table table-striped table-hover table-responsive">
															<thead>
															<tr>
															
															<th>Médicament</th>
															<th>Quantité</th>
															<th>Forme</th>
															<th>Posologie</th>
															<th>Modifier</th>
															<th>Supprimer</th>
															</tr>
															</thead>
															<tbody>
															<?php
															require_once 'dbconfig.php';
															
															$stmt = $db_con->prepare("SELECT * FROM `prescrire_medi` INNER JOIN prescription ON prescrire_medi.`presc_id` = prescription.`presc_id` JOIN medicament ON prescrire_medi.`medi_id` = medicament.`medi_id` WHERE prescription.`presta_id` = '".@$_GET['codep']."' ORDER BY prescrire_medi.prescr_id DESC");
															$stmt->execute();
															while($row=$stmt->fetch(PDO::FETCH_ASSOC))
															{
																?>
																<tr>
																<td><?php echo $row['medi_lib']; ?></td>
																<td><?php echo $row['prescr_qty']; ?></td>
																<td><?php echo $row['prescr_forme']; ?></td>
																<td><?php echo $row['prescr_poso']; ?></td>
																<?php if ($countprescrip==0) : ?>
																<td align="center">
																<a id="<?php echo $row['prescr_id']; ?>" class="edit-link" href="#" title="Edit">
																<img src="edit.png" width="20px" />
																</a></td>
																
																<td align="center"><a id="<?php echo $row['prescr_id']; ?>" class="delete-link" href="#" title="Delete">
																<img src="delete.png" width="20px" />
																</a></td>
																<?php endif; ?>
																</tr>
																<?php
															}
															?>
															</tbody>
															</table>
															
															</div>
											  </form>
											</div>
											</div>
										</div>
									<div class="col-md-12">	<?php if($cloturer==1){ ?>
										<div class="col-md-7">
										<div class="portlet light bordered">
													<div class="portlet-title">
														<div class="caption">
															<i class="icon-social-dribbble font-red"></i>
															<span class="caption-subject font-red bold uppercase">Divers</span>
														</div>
													</div>
											<div>
											<form action="" method="post" class="form-horizontal">
                                                <!-- BEGIN FORM-->
											
                                                    <div class="form-actions top"></div>
													
                                                    <div class="form-body">
													
													<div class="row">
													<div class="col-md-12">
													<div class="row">
													<div class="form-group">
                                                            <label class="col-md-4 control-label">direction:</label>
                                                            <div class="col-md-7">
                                                               <select class="bs-select form-control" data-live-search="true" data-size="8" name="dir_id" id="ent_id" >
																<?php foreach ($list_dir as $result_dir): ?>
																<option value="<?= $result_dir['dir_id']; ?>" >
																
																<?= $result_dir['dir_lib']; ?></option>
																<?php endforeach; ?>
																</select>
														    </div>
                                                        </div> 
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Nom du service: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                							  <input type="text" name="svc_lib" id="svc_lib" size="45" maxlength="255" class="form-control" value="<?= !empty($_POST['svc_lib'])?$_POST['svc_lib']:''; ?>" />
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
											</div>
											</div>
										</div>
										<?php } ?>
										<?php if($cloturer==1){ ?>
										<div class="col-md-5">
										<div class="portlet light bordered">
													<div class="portlet-title">
														<div class="caption">
															<i class="icon-social-dribbble font-red"></i>
															<span class="caption-subject font-red bold uppercase">Certificat d'arrêt de travail</span>
														</div>
													</div>
											<form action="" method ="post">
												
												<?php if ($cloturerAma==0){ ?>
												<div>
												<?php if ($countclotAm==1 AND $countmodAm==0){ ?>
												   <div class="alert alert-success alert-dismissable">
														<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
														 <a href="" class="alert-link">Certificat Ajoutée</a>
												  </div>
												<?php } ?>
												<?php if ($countmodAm==1){ ?>
												   <div class="alert alert-success alert-dismissable">
														<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
														 <a href="" class="alert-link">Certificat modifiée</a>
												  </div>
												  <?php } ?>
												  </div>
												  <?php } ?>
												  <?php if ($cloturerAma==1){ ?>
									   <a href="#" class="btn default" onclick="NewWindow('print_fam.php?presta_id=<?= $_GET['codep']; ?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" title="Aper&ccedil;u de la prestation" >Aperçu du certificat</a>
													<?php } ?>
													<div class="form-group">
													 <label><br/><b>Période du repos: </b><span style="color:#F00"> (*)</span></label>
														 <div  class="input-group  input-large date-picker input-daterange"  data-date="2016-11-14" data-date-format="yyyy-mm-dd" >
														 <input type="text" class="form-control" name="fiche_am_date_deb" value="<?php if(@$countclotAm==1){ echo date($debut); } ?>" <?php if ($cloturerAma==1){ echo 'readonly'; } ?>>
	<?php if(!empty($errorsfam[0])): ?>
															   <span class="alert-danger"><?= $errorsfam[0]; ?></span>
															 <?php endif; ?>													<span class="input-group-addon">Au</span>
														<input type="text" class="form-control" name="fiche_am_date_fin" value="<?php if(@$countclotAm==1){ echo date($fin); } ?>" <?php if ($cloturerAma==1){ echo 'readonly'; } ?>> 
<?php if(!empty($errorsfam[1])): ?>
															   <span class="alert-danger"><?= $errorsfam[1]; ?></span>
															 <?php endif; ?>															</div>
														
													</div>
													<?php if ($cloturerAma==0){ ?>
													<div>
													<?php if ($countclotAm==0){ ?>
														<input class="btn default" type="submit" name="joindre_am" value="Ajouter" />  
													<?php } ?>
													<?php if ($countclotAm==1){ ?>
														<input class="btn Default" type="submit" name="mod_am" value="Modifier" />
														<input class="btn green" type="submit" name="valid_am" value="Valider" />
													<?php } ?>
													</div>
													<?php } ?>
											</form>
											</div>
										</div>
										<?php } ?></div>
										
									</div>
									</div>
                                <!--tab_1_6-->
                                 <!--end tab-pane-->
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
										   <th nowrap="nowrap">Code de la consultation</th>
										   <th nowrap="nowrap">Identité de l'infirmier</th>
										   <th nowrap="nowrap">Date de la consultation</th>
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
												   <?= $historique['presta_code'] ?>
												  
												</td>
												<td>
												    <?= ucfirst(strtoupper($historique['auth_nom'])) ?> <?= ucfirst(strtoupper($historique['auth_pnom'])) ?>
												</td>
												<td><?= ucfirst(strtoupper($historique['presta_date_crea'])) ?></td>
											   <td>
											   <div class="form-group last">
                                           
                                            <div class="col-md-4">
                                             
    								          <center>
											   <a href="#"  onclick="NewWindow('print_presta.php?presta_id=<?= $historique['presta_id']; ?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" title="Aper&ccedil;u de la planification" class="btn btn-xs btn-primary"><i class="fa fa-search"></i></a>
												  
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
                                <!--end tab-pane-->
								<!--tab tab_1_4-->
						<div class="tab-pane" id="tab_1_4">
						   <div class="row">
                             <div class="col-md-12">
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
								 
                                 <table width="100%" class="table table-striped table-bordered table-hover" id="sample_2" >
                                    <thead>
										<tr>
										   <th nowrap="nowrap">N°</th>
										   <th nowrap="nowrap">Code de la prescription</th>
										   <th nowrap="nowrap">Identité de l'infirmier</th>
										   <th nowrap="nowrap">Date de la prescription</th>
										   <th nowrap="nowrap">Voir</th>
										</tr>
									</thead>
									<tbody>
					        <?php foreach ($list_histpres as $key=> $historiqueP) : ?>
											<tr>
												<td>
												   <?= $key+1; ?>
												</td>
												<td>
												   <?= $historiqueP['presc_code'] ?>
												  
												</td>
												<td>
												    <?= ucfirst(strtoupper($historiqueP['auth_nom'])) ?> <?= ucfirst(strtoupper($historiqueP['auth_pnom'])) ?>
												</td>
												<td><?= ucfirst(strtoupper($historiqueP['presc_date_crea'])) ?></td>
											   <td>
											   <div class="form-group last">
                                           
                                            <div class="col-md-4">
                                               
												   <center>
												    <a href="#"  onclick="NewWindow('print_prescr.php?presc_id=<?= $historiqueP['presc_id']; ?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" title="Aper&ccedil;u de la planification" class="btn btn-xs btn-primary"><i class="fa fa-search"></i></a> 
												   
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
                           </div></div></div>
								<!--end -->
								<!--tab tab_1_2-->
								<div class="tab-pane" id="tab_1_2">
						   <div class="row">
                             <div class="col-md-12">
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
								 
                                 <table width="100%" class="table table-striped table-bordered table-hover" id="sample_1" >
                                    <thead>
										<tr>
										   <th nowrap="nowrap">N°</th>
										   <th nowrap="nowrap">Identité de l'infirmier</th>
										   <th nowrap="nowrap">Date debut de repos</th>
										   <th nowrap="nowrap">Date de reprise de service</th>
										   <th nowrap="nowrap">Voir</th>
										</tr>
									</thead>
									<tbody>
					        <?php foreach ($list_histfam as $key=> $historiqueF) : ?>
											<tr>
												<td>
												   <?= $key+1; ?>
												</td>
												<td>
												   <?= ucfirst(strtoupper($historiqueF['auth_nom'])) ?> <?= ucfirst(strtoupper($historiqueF['auth_pnom'])) ?>
												  
												</td>
												<td>
												    <?= ucfirst(strtoupper($historiqueF['ficheam_date_deb'])) ?>
												</td>
												<td><?= ucfirst(strtoupper($historiqueF['ficheam_date_fin'])) ?></td>
												
											   <td>
											   <div class="form-group last">
                                           
                                            <div class="col-md-4">
                                               
												   <center>
												    <a href="#"  onclick="NewWindow('print_fam.php?presta_id=<?= $historiqueF['presta_id']; ?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" title="Aper&ccedil;u de la planification" class="btn btn-xs btn-primary"><i class="fa fa-search"></i></a> 
												   
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
                           </div></div></div>
						
								<!--end-->
							
							
						<!--AJOUT DES MEDICAMENTS-->
						<div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
											<div class="modal-dialog">
											<form method='post' id='emp-SaveForm' action="#">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
														<h4 class="modal-title">ARRET MALADIE</h4>
													</div>
													<div class="modal-body">
													 <label>Médicament</label>
														<select class="bs-select form-control" data-live-search="true" data-size="8" name="medi_id" id="medi_id" >
																<?php 
																
		 require_once 'dbconfig.php';
		$medi = $db_con->prepare("SELECT * FROM medicament WHERE medicament.flag=1 ORDER BY medicament.medi_lib ASC");
		 $medi->execute();
																while($rowM=$medi->fetch(PDO::FETCH_ASSOC)) { ?>
																<option value="<?= $rowM['medi_id']; ?>" >
																<?= $rowM['medi_lib']; ?></option>
																<?php } ?>
																</select>
													    <label>Quantité</label>
														<input type='text' name='prescr_qty' class='form-control' required>
														<label>posologie</label>
														<textarea name='prescr_poso' class='form-control' required></textarea>
													</div>
													<div class="modal-footer">
														<button type="button" data-dismiss="modal" class="btn dark btn-outline">Annuler</button>
														<input class="btn green" type="submit" name="joindre_am" value="Joindre" />    
													</div>
												</div>
											</form>
											</div>
										</div>
								
								
						<script src="bootstrap/js/bootstrap.min.js"></script>
						<script type="text/javascript" src="assets/datatables.min.js"></script>
						<script type="text/javascript" src="crud.js"></script>
   