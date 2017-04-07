<style type="text/css">
#dis{
	display:none;
}
</style>
<div id="dis">
    <!-- here message will be displayed -->
	</div>
        
 	
	 <form method='post' id='emp-SaveForm' action="#">
 
    <table class='table table-bordered'>
 
        <tr>
            <td>Médicament</td>
            
            <td>
            <select data-live-search="true" data-live-search-style="startsWith"  class="bs-select form-control"  name="medi_id" id="medi_id" ><option>--Selectionner--</option>
																<?php 
																
		 require_once 'dbconfig.php';
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
            <td><input type='text' name='prescr_qty' class='form-control' required></td>
        </tr>
        <tr>
            <td>Forme</td>
            <td><input type='text' name='prescr_forme' class='form-control' required></td>
        </tr>
 
        <tr>
            <td>Posologie</td>
            <td><input type='text' name='prescr_poso' class='form-control' required></td>
        </tr>
 
        <tr>
            <td colspan="2">
            <button type="submit" class="btn default" name="btn-save" id="btn-save">
    		<span class="glyphicon glyphicon-plus"></span>Ajouter ce m&eacute;dicament
			</button>  
            </td>
        </tr>
 
    </table>
</form>
     
