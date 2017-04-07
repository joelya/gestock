<?php
session_start();
//var_dump($_SESSION);
//Database connection by using PHP PDO
$username = 'webmaster';
$password = 'webmaster2017';
$connection = new PDO( 'mysql:host=localhost;dbname=gestock_v1', $username, $password ); // Ajouter Object of PDO class by connecting to Mysql database
if(isset($_POST["action"])) //Check value of $_POST["action"] variable value is set to not
{
 //For Load All Data
 if($_POST["action"] == "Load") 
 {
	
  $statement = $connection->prepare("SELECT vp.id, vp.prd_id, vp.vente_id, p.prd_lib,p.prd_qte,p.prd_prix_ttc, vp.qty_servi,vp.qty_servi*p.prd_prix_ttc AS tot FROM vendre_produit vp INNER JOIN produit p ON p.prd_id=vp.prd_id JOIN vente vte ON vte.vente_id = vp.vente_id  WHERE vte.vente_id ='".$_SESSION['codev']."'");
  $statement->execute();
  $result = $statement->fetchAll();
  
  //POUR AFFICHER LE TOTAL GENERAL
   $statementgen = $connection->prepare("SELECT  SUM(uu.qty_servi*uu.prd_prix_ttc) AS totgeneral FROM (SELECT vp.id, vp.prd_id, vp.vente_id, p.prd_lib,p.prd_prix_ttc, vp.qty_servi,vp.qty_servi*p.prd_prix_ttc AS tot FROM vendre_produit vp INNER JOIN produit p ON p.prd_id=vp.prd_id JOIN vente vte ON vte.vente_id = vp.vente_id WHERE vte.vente_id ='".$_SESSION['codev']."') uu");
  $statementgen->execute();
  $resultgen = $statementgen->fetchAll();
  //var_dump($statement);exit;
  //POUR VERIFIER SI UNE VENTE A DEJA ETE FAITE
		//POUR VERIFIER SI UNE VENTE A DEJA ETE BOUCLEE -- AFFICHER ETAT
		$clot_regl = $connection->prepare("SELECT `vente_flag_actif` FROM `vente` WHERE `vente_id` ='".$_SESSION['codev']."' AND vente_flag_actif =1");
		$clot_regl->execute();
		$countReg = $clot_regl->rowCount();
  $output = '';
  $output .= '
   <table class="table table-bordered">
    <tr>
     <th width="40%">Articles</th>
     <th width="40%">Quantit&eacute; servie</th>
	  <th width="40%">Quantit&eacute; expos&eacute;</th>
	 <th nowrap="nowrap" width="40%">Prix</th>
	 <th nowrap="nowrap" width="40%">Prix total TTC</th>
     <th width="10%">Modifier</th>
     <th width="10%">Supprimer</th>
    </tr>
  ';
    
  if($statement->rowCount() > 0)
  {
   foreach($result as $row)
   {
    $output .= '
    <tr>
     <td>'.$row["prd_lib"].'</td>
     <td>'.$row["qty_servi"].'</td>
	 <td><span style="color:#F00">'.$row["prd_qte"].'</span></td>
	 <td nowrap="nowrap">'.$row["prd_prix_ttc"].'</td>
	 <td nowrap="nowrap">'.$row["tot"].'</td> '; ?>
     <?php if ($countReg==0){ 
	 $output .=' 
     <td><button type="button" id="'.$row["id"].'" class="btn btn-warning btn-xs update">Modifier</button></td>
     <td><button type="button" id="'.$row["id"].'" class="btn btn-danger btn-xs delete">Supprimer</button></td>
    </tr> ';
	 }
	
   }
    if($statementgen->rowCount() > 0)
  {
   foreach($resultgen as $rowgen)
   { 
   //MAJ VENTE 
	$update = "UPDATE `gestock_v1`.`vente` SET `vent_total` = '".$rowgen["totgeneral"]."' WHERE `vente`.`vente_id` = '".$_SESSION['codev']."'";
	$connection->query($update);
	
	//MAJ NAP 
	//POUR AFFICHER LE BON REDUCTIONE ET LE NAP
 $state = $connection->prepare("SELECT SUM(b.bon_mont)AS tot,vb.vente_id,v.vent_total FROM vente_bon vb INNER JOIN bon_reduction b ON vb.bon_id = b.bon_id JOIN vente v ON vb.vente_id = v.vente_id WHERE v.vente_id='".$_SESSION['codev']."'");
  $state->execute();
  $resulta = $state->fetchAll();
  foreach($resulta as $ro){
  $br = $ro['tot'];
  }
    if ($countReg<1){ 
  $nap = $rowgen["totgeneral"]-$br;
	$update2 = "UPDATE `gestock_v1`.`vente` SET `vente_nap` = '".$nap."' WHERE `vente`.`vente_id` = '".$_SESSION['codev']."'";
	//var_dump($update2);exit;
	$connection->query($update2);
	}
    $output .= '
    <tr>
	
	 <td colspan="3" align="center"><strong>Montant de la vente</strong></td>
     <td colspan="4"><strong>'.$rowgen["totgeneral"].'</strong></td>
	
    </tr>
   ';
   }
  }
 
	   
  }
  else
  {
   $output .= '
    <tr>
     <td colspan="7" align="center">Aucune donn&eacute; trouv&eacute;e</td>
    </tr>
   ';
  }
  $output .= '</table>';
  echo $output;
 }

 //This code for Ajouter new Records
 if($_POST["action"] == "Ajouter")
 {
	 
   $statement = $connection->prepare("
   INSERT INTO vendre_produit (prd_id, vente_id,qty_servi) 
   VALUES (:prd_id, :vente_id, :qty_servi)
  ");
  
  
  $result = $statement->execute(
   array(
    ':prd_id' => $_POST["prdId"],
	':vente_id' => $_SESSION['codev'],
    ':qty_servi' => $_POST["qtyServi"]
   )
  );
  //var_dump($result);exit;
  if(!empty($result))
  {
   echo 'Article Ajouté';
  }
  else{
	  echo 'erreur';
	  }
 }

 //This Code is for fetch single customer data for display on Modal
 if($_POST["action"] == "Select")
 {
  $output = array();
  $statement = $connection->prepare(
   "SELECT * FROM vendre_produit 
   WHERE id = '".$_POST["id"]."' 
   LIMIT 1"
  );
  $statement->execute();
  $result = $statement->fetchAll();
  foreach($result as $row)
  {
   $output["prd_id"] = $row["prd_id"];
   $output["qty_servi"] = $row["qty_servi"];
  }
  echo json_encode($output);
 }

 if($_POST["action"] == "Modifier")
 {
  $statement = $connection->prepare(
   "UPDATE vendre_produit 
   SET prd_id = :prd_id, qty_servi = :qty_servi 
   WHERE id = :id
   "
  );
  $result = $statement->execute(
   array(
    ':prd_id' => $_POST["prdId"],
    ':qty_servi' => $_POST["qtyServi"],
    ':id'   => $_POST["id"]
   )
  );
  if(!empty($result))
  {
   echo 'Ligne modifiée';
  }
 }

 if($_POST["action"] == "Delete")
 {
  $statement = $connection->prepare(
   "DELETE FROM vendre_produit WHERE id = :id"
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
 