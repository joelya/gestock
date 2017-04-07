<?php
include_once 'dbconfig.php';

//LISTE DES MEDICAMENTS

if($_GET['edit_id'])
{
	$id = $_GET['edit_id'];	
	$stmt=$db_con->prepare("SELECT * FROM `prescrire_medi` INNER JOIN prescription ON prescrire_medi.`presc_id` = prescription.`presc_id` JOIN medicament ON prescrire_medi.`medi_id` = medicament.`medi_id` WHERE prescrire_medi.prescr_id = :id");
	//$stmt=$db_con->prepare("SELECT * FROM tbl_employees WHERE emp_id=:id");
	$stmt->execute(array(':id'=>$id));	
	$row2=$stmt->fetch(PDO::FETCH_ASSOC);
}

?>
<style type="text/css">
#dis{
	display:none;
}
</style>


	
    
    <div id="dis">
    
	</div>
        
 	
	 <form method='post' id='emp-UpdateForm' action='#'>
 
    <table class='table table-bordered'>
 		<input type='hidden' name='id' value='<?php echo $row2['prescr_id']; ?>' />
        <tr>
            <td>Médicament</td>
            
            <td><select class="bs-select form-control" data-live-search="true" data-size="8" name="medi_id" id="medi_id" >
																<?php 
																
		
		$medi = $db_con->prepare("SELECT * FROM medicament WHERE medicament.flag=1 ORDER BY medicament.medi_lib ASC");
		 $medi->execute();
																while($row=$medi->fetch(PDO::FETCH_ASSOC)) { ?>
																<option value="<?= $row['medi_id']; ?>" >
																<?= $row['medi_lib']; ?></option>
																<?php } ?>
																</select></td>
        </tr>
 
        <tr>
            <td>Quantité</td>
            <td><input type='text' name='prescr_qty' class='form-control' value='<?php echo $row2['prescr_qty']; ?>' required></td>
        </tr>
        <tr>
            <td>Forme</td>
            <td><input type='text' name='prescr_forme' class='form-control' value='<?php echo $row2['prescr_forme']; ?>' required></td>
        </tr>
 
        <tr>
            <td>Posologie</td>
            <td><input type='text' name='prescr_poso' class='form-control' value='<?php echo $row2['prescr_poso']; ?>' required></td>
        </tr>
 
        <tr>
            <td colspan="2">
            <button type="submit" class="btn btn-primary" name="btn-update" id="btn-update">
    		<span class="glyphicon glyphicon-plus"></span> Modifier
			</button>
            </td>
        </tr>
 
    </table>
</form>
     
