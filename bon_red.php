<?php
session_start();
//var_dump($_SESSION);exit;
include('model/connexion.php');
$pdo=connect();

if(isset($_POST["action_bon"])) //Check value of $_POST["action_bon"] variable value is set to not
{
 //For Load All Data
 if($_POST["action_bon"] == "Load") 
 {
	
//POUR AFFICHER LE BON REDUCTIONE ET LE NAP
 $state = $pdo->prepare("SELECT (uu.vent_total - uu.tot)AS nap FROM (SELECT SUM(b.bon_mont)AS tot,vb.vente_id,v.vent_total FROM vente_bon vb INNER JOIN bon_reduction b ON vb.bon_id = b.bon_id JOIN vente v ON vb.vente_id = v.vente_id WHERE v.vente_id='".$_SESSION['codev']."') uu");
  $state->execute();
  $resulta = $state->fetchAll();
  	 
//AFFICHER BON DE REDUCTION AJOUTE
$statement = $pdo->prepare("SELECT vb.id, vb.vente_id, vb.bon_id,b.bon_code,b.bon_mont FROM vente_bon vb INNER JOIN bon_reduction b ON vb.bon_id = b.bon_id JOIN vente v ON vb.vente_id = v.vente_id  WHERE v.vente_id='".$_SESSION['codev']."'");
  $statement->execute();
  $result_bon = $statement->fetchAll();
  
//POUR VERIFIER SI UNE VENTE A DEJA ETE BOUCLEE -- AFFICHER ETAT
		$clot_regl = $pdo->prepare("SELECT `vente_flag_actif` FROM `vente` WHERE `vente_id` ='".$_SESSION['codev']."' AND vente_flag_actif =1");
		$clot_regl->execute();
		$countReg = $clot_regl->rowCount();
		
  $output = '';
    
  if($state->rowCount() > 0)
  {
   foreach($resulta as $ro)
   { 
   
    $output .= '
	<table class="table table-bordered">';
	if($countReg<1){
	$output .= '
    <tr>
	 <td colspan="3"  align="center"><strong>NET a payer</strong></td>
     <td colspan="4" ><strong>'.$ro["nap"].'</strong></td>
	 
    </tr>
   ';
   }
   }
  }  
  
  $output .= '
    <tr>
	<th width="20%">&nbsp;</th>
	<th width="20%">&nbsp;</th>
     <th width="20%">Bon reduction</th>
     <th width="25%">Montant</th>
	 <th width="20%">&nbsp;</th>
	 <th>Supprimer</th>
	 
    </tr>
  ';
  
  if($statement->rowCount() > 0)
  {
   foreach($result_bon as $row)
   {
    $output .= '
    <tr>
	<td width="20%">&nbsp;</td>
	<td width="20%">&nbsp;</td>
     <td>'.$row["bon_code"].'</td>
     <td>'.$row["bon_mont"].'</td>
	 <td width="20%">&nbsp;</td>'; ?>
 <?php if ($countReg==0){ 
	 $output .='
     <td><button type="button" id="'.$row["id"].'" class="btn btn-danger btn-xs deletebon">Supprimer</button></td>
    </tr> ';
   }
  }
  }
if($statement->rowCount() == 0)
  {
   $output .= '
    <tr>
     <td colspan="6" align="center">Aucun bon ajout&eacute;</td>
    </tr>
   ';
    
  }
 $output .= '</table>';
  echo $output;
 }
 
//This code for Ajouter new Records
$errors_vte = array();
$valid_vte=true;
//GESTION DE LA VENTE
	$sql_vente= "SELECT `vente_id`,`medp_id`, `comerc_id`, `auth_id`, `vente_ref`, `vente_date`, `vente_rapport`, `vente_remise`, `vent_total`, `vente_flag_modif`, `vente_flag_creer`, `vente_flag_clot` FROM `vente` WHERE `vente_id` =  '".$_SESSION['codev']."'";
	$lavente = $pdo->prepare($sql_vente);
    $lavente->execute();
	foreach ($lavente as $unevente){
		$vent_total = $unevente['vent_total'];
	}

 if($_POST["action_bon"] == "Ajouter")
 {
	 $sql_bon = "SELECT `bon_id`, `bon_code`, `bon_lib`, `bon_mont`, `bon_flag_actif` FROM `bon_reduction` WHERE bon_code='".$_POST["venteBon"]."' AND `bon_flag_actif`=1";
	$list_bon = $pdo->prepare($sql_bon);
    $list_bon->execute();
			
			 if($list_bon->rowCount() == 0){
				 echo 'Bon invalide !';
           		 $valid_vte=false;
			 }
			 elseif($list_bon->rowCount()==1){
				 $valid_vte=true;
				 foreach($list_bon as $unbon){
				  $bon_id=$unbon['bon_id'];
				  $bon_mont=$unbon['bon_mont'];
				 }
				 }
	if($valid_vte == true){
	
  $statement = $pdo->prepare("
   INSERT INTO `vente_bon`(`vente_id`, `bon_id`) VALUES (:vente_id, :vente_bon)
  ");
  
  $result_bon = $statement->execute(
   array(
    ':vente_id' => $_SESSION["codev"],
    ':vente_bon' => $bon_id
   )
   
   );
  if(!empty($result_bon))
  {
	$update = "UPDATE bon_reduction 
			SET bon_flag_actif = '0' WHERE bon_id = '".$bon_id."'";
			$pdo->query($update);	
			$clot_regl = $pdo->prepare("SELECT `vente_flag_actif` FROM `vente` WHERE `vente_id` ='".$_SESSION['codev']."' AND vente_flag_actif =1");
		$clot_regl->execute();
		$countReg = $clot_regl->rowCount();
			if($lavente->rowCount()==1 AND $countReg<1){
			//VARIATION DU NET A PAYER
			$vente_nap = $vent_total-$bon_mont;
			$leversement = "UPDATE vente 
			SET vente_nap = '".$vente_nap."' 
			WHERE vente_id = '".$_SESSION['codev']."'";
			$pdo->query($leversement);
 
			}
   echo 'Bon ajouté';
  }
 
 }
 }
 if($_POST["action_bon"] == "Deletebon")
 {
	//ACTIVER LE BON
	  $sql_bon2 = "SELECT id, `vente_id`, `bon_id` FROM `vente_bon` WHERE id='".$_POST["id"]."'";
	$list_bon2 = $pdo->prepare($sql_bon2);
    $list_bon2->execute(); 
	 foreach($list_bon2 as $unbon2){
				  $bon_id2=$unbon2['bon_id'];
				 }
	$update1 = "UPDATE bon_reduction 
			SET bon_flag_actif = '1' WHERE bon_id = '".$bon_id2."'";
			$pdo->query($update1);
	//MAJ NET A PAYER
	$update2 = "UPDATE vente AS U1, (SELECT b.bon_id,v.vente_id, b.bon_code, b.bon_lib, b.bon_mont, b.bon_flag_actif FROM bon_reduction b INNER JOIN vente_bon vb ON vb.bon_id=b.bon_id JOIN vente v ON v.vente_id = vb.vente_id WHERE vb.bon_id='".$bon_id2."' AND v.vente_id = '".$_SESSION['codev']."') AS U2 SET U1.vente_nap = U1.vente_nap+U2.bon_mont WHERE U2.vente_id = U1.vente_id ";
			$pdo->query($update2);	
  $statement = $pdo->prepare(
   "DELETE FROM vente_bon WHERE id = :id"
  );
  $result = $statement->execute(
   array(
    ':id' => $_POST["id"]
   )
  );
  if(!empty($result))
  {		
   echo 'Suppression reussie';
  }
 }
 }
?>