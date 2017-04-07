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
	
  $statement = $connection->prepare("SELECT c.id, c.prd_id, c.cmd_id, p.prd_lib,p.prd_qte,p.prd_prix_ttc, c.qty_cmd FROM commande_prod c INNER JOIN produit p ON p.prd_id=c.prd_id JOIN commande cde ON cde.cmd_id = c.cmd_id  WHERE cde.cmd_id  ='".$_SESSION['codec']."'");
  $statement->execute();
  $result = $statement->fetchAll();
  
  //POUR VERIFIER SI UNE VENTE A DEJA ETE BOUCLEE -- AFFICHER ETAT
		$clot_regl = $connection->prepare("SELECT `cmd_flag_clot` FROM `commande` WHERE `cmd_id` ='".$_SESSION['codec']."' AND cmd_flag_clot =1");
		$clot_regl->execute();
		$countReg = $clot_regl->rowCount();
 
  $output = '';
  $output .= '
   <table class="table table-bordered">
    <tr>
     <th width="40%">Articles</th>
     <th width="40%">Quantit&eacute; command&eacute</th>
	 <th nowrap="nowrap" width="40%">Prix TTC</th>
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
	 <td>'.$row["qty_cmd"].'</td>
     <td>'.$row["prd_prix_ttc"].'</td>' ?>
	  <?php if ($countReg==0){ 
	 $output .=' 
     <td><button type="button" id="'.$row["id"].'" class="btn btn-warning btn-xs update">Modifier</button></td>
     <td><button type="button" id="'.$row["id"].'" class="btn btn-danger btn-xs delete">Supprimer</button></td>
    </tr> ';
	 }
  }
		   
  }
  else
  {
   $output .= '
    <tr>
     <td colspan="7" align="center">Aucune donn&eacute;s trouv&eacute;es</td>
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
   INSERT INTO commande_prod (prd_id, cmd_id,qty_cmd) 
   VALUES (:prd_id, :cmd_id, :qty_cmd)
  ");
  
  
  $result = $statement->execute(
   array(
    ':prd_id' => $_POST["prdId"],
	':cmd_id' => $_SESSION['codec'],
    ':qty_cmd' => $_POST["qtyServi"]
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
   "SELECT * FROM commande_prod 
   WHERE id = '".$_POST["id"]."' 
   LIMIT 1"
  );
  $statement->execute();
  $result = $statement->fetchAll();
  foreach($result as $row)
  {
   $output["prd_id"] = $row["prd_id"];
   $output["qty_cmd"] = $row["qty_cmd"];
  }
  echo json_encode($output);
 }

 if($_POST["action"] == "Modifier")
 {
  $statement = $connection->prepare(
   "UPDATE commande_prod 
   SET prd_id = :prd_id, qty_cmd = :qty_cmd 
   WHERE id = :id
   "
  );
  $result = $statement->execute(
   array(
    ':prd_id' => $_POST["prdId"],
    ':qty_cmd' => $_POST["qtyServi"],
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
   "DELETE FROM commande_prod WHERE id = :id"
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
 