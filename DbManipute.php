<?php
session_start();
include('db.php');
 if(isset($_REQUEST['actionfunction']) && $_REQUEST['actionfunction']!=''){
$actionfunction = $_REQUEST['actionfunction'];
  
   call_user_func($actionfunction,$_REQUEST,$con,$limit,$adjacent);
}
function saveData($data,$con){
//VERIFICATION SI AUCUNE PRESCRIPTION EXISTE
$presv = $db_con->prepare("SELECT * FROM `prescrire_medi` INNER JOIN prescription ON prescrire_medi.`presc_id` = prescription.`presc_id` JOIN medicament ON prescrire_medi.`medi_id` = medicament.`medi_id` WHERE prescription.`presta_id` = '".@$_SESSION['codep']."' AND prescrire_medi.clot = 0 ORDER BY prescrire_medi.prescr_id DESC");
$presv->execute();
$countV = $presv->rowCount();	

  $medi_id = $data['medi_id'];
  $prescr_qty = $data['prescr_qty'];
  $prescr_forme = $data['prescr_forme'];
  $prescr_poso = $data['prescr_poso'];
  
		if($countV >0){
		$presc_id = @$_SESSION['presc'];
		}
  		if($countV==0){
				 /**
			*INSERTION DE LA PRESCRIPTION
			**/
			
			$presc_code = strtoupper('PRESC'.substr(md5(time()),0,10));
			$dateMod = date("Y-m-d").' '.date("H:i:s");
			$presta = $_SESSION['codep'];
			 $sql_presc = "INSERT INTO `db_doctor_secu`.`prescription` (`presc_id`, 	`presta_id`, `presc_code`, `presc_date_crea`, `presc_date_mod`, `flag`) values(NULL,'$presta','$presc_code','$dateMod','$dateMod',1)";	
		    $con->query($sql_presc);
			//$presc_id = $con->lastInsertId();		
			$presc_id=$con->insert_id;
			$_SESSION['presc'] = $presc_id;
			}
			//echo $presc_id;exit;
		/**
        *   INSERTION DES LIGNE DE MEDICAMENT
        **/
			
	  
	  $sql = "insert into prescrire_medi(presc_id,medi_id,prescr_qty,prescr_forme,prescr_poso) values('$presc_id','$medi_id','$prescr_qty','$prescr_forme','$prescr_poso')";
	  if($con->query($sql)){
		echo "added";
	  }
	  else{
	  echo "error";
	  }
  
}
function editUser($data,$con){
	//var_dump($data);exit;
  $userid = $data['uneprescript'];
  $userid = base64_decode($userid);
   $sql = "select prescrire_medi.`prescr_id`,prescrire_medi.`presc_id`,prescrire_medi.`medi_id`,prescrire_medi.`prescr_qty`,prescrire_medi.`prescr_forme`,prescrire_medi.`prescr_poso`,prescrire_medi.`prescr_date_crea`,prescrire_medi.`prescr_date_mod`,prescrire_medi.`flag`,prescrire_medi.`clot`,medicament.medi_lib from prescrire_medi INNER JOIN medicament ON medicament.`medi_id` = prescrire_medi.`medi_id` where prescr_id=$userid";
  $uneprescript = $con->query($sql);
    if($uneprescript->num_rows>0){
   $uneprescript = $uneprescript->fetch_array(MYSQLI_ASSOC);
  ?>
  <form name='signup' id='signup'>
      <div class='row form-group'>
	      <label class="col-md-4 control-label">M&eacute;dicaments : </label>
		   <div class="col-md-7"> <select class="form-control"  name="medi_id" id="medi_id" >																<?php 
																
		 require_once 'dbconfig.php';
		$medi = $db_con->prepare("SELECT * FROM medicament WHERE medicament.flag=1 ORDER BY medicament.medi_lib ASC");
		 $medi->execute();
																while($row=$medi->fetch(PDO::FETCH_ASSOC)) { ?>
																<option value="<?= $row['medi_id']; ?>" <?php if($uneprescript['medi_id']==$row['medi_id']) echo 'selected' ;?>>
																<?= $row['medi_lib']; ?></option>
																<?php } ?>
		</select></div>
	   </div>
	   <div class="row form-group">
			<label class="col-md-4 control-label">Quantit&eacute; : <span style="color:#F00"> (*)</span></label>
			<div class="col-md-7">
			  <input type="text" class="form-control" name="prescr_qty" id="prescr_qty" value='<?php echo $uneprescript['prescr_qty']?>' placeholder='Quantite'/>
			</div>
	  </div>
	  
	  <div class="row form-group">
			<label class="col-md-4 control-label">Forme : <span style="color:#F00"> (*)</span></label>
			<div class="col-md-7">
			  <input type="text" class="form-control" name="prescr_forme" id="prescr_forme" value='<?php echo $uneprescript['prescr_forme']?>' placeholder='Entrer forme'/>
			</div>
	  </div>
	  <div class="row form-group">
			<label class="col-md-4 control-label">Posologie : <span style="color:#F00"> (*)</span></label>
			<div class="col-md-7">
			  <input type="text" class="form-control" name="prescr_poso" id="prescr_poso" value='<?php echo $uneprescript['prescr_poso']?>' placeholder='Entrer une posologie'/>
			</div>
	  </div>
	   <input type="hidden" name="actionfunction" value="updateData" />
	   <input type="hidden" name="uneprescript" value="<?php echo base64_encode($uneprescript['prescr_id']) ?>" />
	   <div class='row'>
	       <input type='button' id='updatesubmit' class='submit' value='Mettre a jour' />
		   
	   </div>
   </form>
  <?php } 
}
function showData($data,$con,$limit,$adjacent){
  $page = $data['page'];
   if($page==1){
   $start = 0;  
  }
  else{
  $start = ($page-1)*$limit;
  }
   
  $sql = "select * from prescrire_medi order by prescr_id asc";
  $rows  = $con->query($sql);
  echo $rows  = $rows->num_rows;
  
  $sql = "SELECT * FROM `prescrire_medi` INNER JOIN prescription ON prescrire_medi.`presc_id` = prescription.`presc_id` JOIN medicament ON prescrire_medi.`medi_id` = medicament.`medi_id` WHERE prescription.`presta_id` = '".@$_SESSION['codep']."' ORDER BY prescrire_medi.prescr_id DESC ";
  
  $data = $con->query($sql);
  $str='<tr class="head"><td>m&eacute;dicaments</td><td>Qauntit&eacute;</td><td>Forme</td><td>Posologie</td><td></td></tr>';
  if($data->num_rows>0){
   while( $row = $data->fetch_array(MYSQLI_ASSOC)){
      $str.="<tr id='".$row['prescr_id']."'><td>".$row['medi_lib']."</td><td>".$row['prescr_qty']."</td><td>".$row['prescr_forme']."</td><td>".$row['prescr_poso']."</td><td><input type='button' class='ajaxedit' value='Modifier' uneprescript='".base64_encode($row['prescr_id'])."' /> <input type='button' class='ajaxdelete' value='Supprimer' uneprescript='".base64_encode($row['prescr_id'])."' ></td></tr>";
   }
   }else{
    $str .= "<td colspan='5'>No Data Available</td>";
   }   
echo $str;  
}
function updateData($data,$con){
	
  $medi_id = $data['medi_id'];
  $prescr_qty = $data['prescr_qty'];
  $prescr_poso = $data['prescr_poso'];
  $prescr_forme = $data['prescr_forme'];
  $uneprescript = $data['uneprescript'];
  $uneprescript = base64_decode($uneprescript);
  $sql = "update prescrire_medi set medi_id='$medi_id',prescr_qty='$prescr_qty',prescr_forme='$prescr_forme',prescr_poso='$prescr_poso' where prescr_id=$uneprescript";
  if($con->query($sql)){
    echo "updated";
  }
  else{
   echo "error";
  }
  }
  function deleteUser($data,$con,$limit,$adjacent){
    $uneprescript = $data['uneprescript'];
    $uneprescript = base64_decode($uneprescript);	
	$sql = "delete from prescrire_medi where prescr_id=$uneprescript";
	if($con->query($sql)){
	  showData($data,$con,$limit,$adjacent);
	}
	else{
	echo "error";
	}
  }


?>