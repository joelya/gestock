<?php
session_start();
//var_dump($_SESSION);exit;
require_once 'dbconfig.php';
//VERIFICATION SI AUCUNE PRESCRIPTION EXISTE
$presv = $db_con->prepare("SELECT * FROM `prescrire_medi` INNER JOIN prescription ON prescrire_medi.`presc_id` = prescription.`presc_id` JOIN medicament ON prescrire_medi.`medi_id` = medicament.`medi_id` WHERE prescription.`presta_id` = '".@$_SESSION['codep']."' AND prescrire_medi.clot = 0 ORDER BY prescrire_medi.prescr_id DESC");
		$presv->execute();
		$countV = $presv->rowCount();
		
	if($_POST)
	{
		$medi_id = $_POST['medi_id'];
		$prescr_qty = $_POST['prescr_qty'];
		$prescr_forme = htmlentities(trim($_POST['prescr_forme']));
		$prescr_poso = htmlentities(trim($_POST['prescr_poso']));
		try{
			/**
			*INSERTION DE LA PRESCRIPTION
			**/
			$presc_code = strtoupper('PRESC'.substr(md5(time()),0,10));
			$dateMod = date("Y-m-d").' '.date("H:i:s");
			 $sql_presc = "INSERT INTO `prescription`( `presta_id`, `presc_code`,`presc_date_crea`, `presc_date_mod`,`flag`)
    VALUES (?,?,?,?,?)";	
		
		$req_presc = $db_con->prepare($sql_presc);
		
		$req_presc->execute(array($_SESSION['codep'],$presc_code,$dateMod,$dateMod,1));
		
		if($countV==0){
		$presc_id = $db_con->lastInsertId();
		$_SESSION['presc'] = $presc_id;
		}
		elseif($countV >0){
		$presc_id = @$_SESSION['presc'];
		}
		/**
        *   INSERTION DES LIGNE DE MEDICAMENT
        **/
			
			
			$stmt = $db_con->prepare("INSERT INTO `prescrire_medi`(`presc_id`, `medi_id`, `prescr_qty`, `prescr_forme`, `prescr_poso`, `prescr_date_crea`, `prescr_date_mod`) VALUES(:presc_id,:medi_id, :prescr_qty, :prescr_forme, :prescr_poso,:date_crea,:date_mod)");
			
			$stmt->bindParam(":presc_id",$presc_id);
			$stmt->bindParam(":medi_id", $medi_id);
			$stmt->bindParam(":prescr_qty", $prescr_qty);
			$stmt->bindParam(":prescr_forme", $prescr_forme);
			$stmt->bindParam(":prescr_poso", $prescr_poso);
			$stmt->bindParam(":date_crea", $dateMod);
			$stmt->bindParam(":date_mod", $dateMod);
			
			
			if($stmt->execute())
			{
				$prescrire_id = $db_con->lastInsertId();
				$_SESSION['stmt_count'] = 'ok';
				//$_SESSION['prescrire_id'] = $prescrire_id;
				echo 'Medicament ajouté';
				
			}
			else{
				echo "Query Problem";
			}	
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

?>