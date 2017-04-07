<?php 
	
	 if(isset($_POST['clot_info'])){
		 //RECUPERATION DES VARIABLES
		 	
		 $fourn_id = htmlentities(trim($_POST['fourn_id']));
		 $cmd_id = htmlentities(trim($_POST['cmd_id']));
		 $liv_ref = htmlentities(trim($_POST['reference']));
		 $liv_com = htmlentities(trim($_POST['commentaire']));
		 $liv_date = implode("-", array_reverse(explode("/", $_POST['liv_date'])));
		 $cmd_flag_recu = htmlentities(trim($_POST['unstatut']));
		//var_dump($_POST);
		 $sql = "INSERT INTO `livraison`(`cmd_id`, `liv_ref`, `liv_com`,liv_date) VALUES(?,?,?,?);
";	
		$req = $pdo->prepare($sql);
		$req->execute(array($cmd_id,$liv_ref,$liv_com,$liv_date));
		
		//MAJ STATUT
		           $update = "UPDATE commande
					SET cmd_flag_recu = '".$cmd_flag_recu."',
					 cmd_flag_actif = '".$cmd_flag_recu."'
					WHERE cmd_id = '".$cmd_id."'";
					//var_dump($update);exit;
					$pdo->query($update);
					
					if($cmd_flag_recu==1){
					 //VARIATION DU STOCK
			 $stock = "UPDATE produit AS U1, (SELECT sum(cp.qty_cmd) as qte,prd_id FROM commande_prod cp WHERE cp.cmd_id = '".$cmd_id."' GROUP BY prd_id) AS U2 SET U1.prd_qte = U1.prd_qte+U2.qte WHERE U2.prd_id = U1.prd_id";
			$pdo->query($stock);
			
			//INSERTION DES LIGNES DE FOURNISSEURS
			$sql_lefourn = "SELECT `id`, `cmd_id`, `prd_id`, `qty_cmd` FROM `commande_prod` WHERE cmd_id = '".$cmd_id."'";
		$list_deforun = $pdo->prepare($sql_lefourn);
		//var_dump($list);exit;
		$list_deforun->execute();
		foreach ($list_deforun as $cefourn){
		$ligne = "INSERT INTO `fournisseur_prod`(`prd_id`, `fourn_id`) VALUES (?,?);
";	
		$lareq = $pdo->prepare($ligne);
		$lareq->execute(array($cefourn['prd_id'],$fourn_id));	
		}
					}
					echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=cmd_livr&sup='.$cmd_flag_recu.'"</script>';	

		 
	 }
		/**
		   *	
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 
		 	//RECUPERATION DES VARIABLES
		 	
		 $search = htmlentities(trim($_POST['search']));
		
			//GESTION DES RESTRICTIONS
			 $errors = array();
			 $valid=true;
         
        if(empty($search))
        {
            $errors[0]='Veuillez saisir le code de la commande';
            $valid=false;
        }
         
        if($valid == true){
			$dateMod = date("Y-m-d").' '.date("H:i:s");
			//Affichage des données 
			
		$sql = 'SELECT cf.cmd_id,cf.cmd_lib,cf.fourn_id,f.fourn_lib, cf.cmd_code,cf.cmd_date_liv, cf.cmd_lib,cf.cmd_flag_clot,cf.cmd_flag_recu FROM `commande` cf INNER JOIN fournisseur f ON cf.fourn_id = f.fourn_id  WHERE cf.cmd_code = "'.$search.'" AND cf.cmd_flag_clot = 1 AND cf.cmd_flag_recu = 0 ';
		$list = $pdo->prepare($sql);
		//var_dump($list);exit;
		$list->execute();
		
			}
	 }
	
		
?>
 <!-- BEGIN PAGE BAR -->
 <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                              <a href="<?= $_SESSION['proflis']?>.php">Accueil</a>
                                <i class="fa fa-circle"></i>
                            </li>
                             <li>
                              <a href="#"><span class="caption">Rechercher un client</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            
                      </ul>
                       
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE--><!-- END PAGE TITLE-->
					 <div class="row">
                        <div class="col-md-12">
                            <div class="tabbable-line boxless tabbable-reversed">
                                 <div class="tab-content">
                                  
								
                                        <div class="portlet box blue-hoki">
                                            <div class="portlet-title">
                                             <div class="caption">Rechercher une commande
                                          </div></div>
                                            <div class="portlet-body form">
  <form action="" method="post" class="form-horizontal">                                          
                                                <!-- BEGIN FORM-->
											
                                                    <div class="form-actions top">
                                                        
                                                    </div>
                                                    <div class="form-body">
													<div class="row">
													<div class="col-md-12">
													<div class="row">
													 
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Entrer le code de la commande:</label>
                                                            <div class="col-md-7">
                                							  <input class="form-control" name="search" type="text"  autocomplete="off" style="width:300px;" placeholder="Code de la commande"/>
                                                             <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
													</div>
													
													<div class="row"> 
													<div class="col-md-8 col-md-offset-4">
														<div>
														<input class="btn green" type="submit" name="valid_info" value="Valider" />
														</div>
													</div>                      
													</div>
												</div>
												</div>
                                               </div>
                                           </form>
								 
                                                <!-- END FORM-->
                                  </div>
                            </div>
							
						</div>
					  </div>
                      <?php if (isset($list)) :?>
                      <!--tableau-->
                      <form action="" method="post">
                      <div class="table-container">
                      
                                <table width="100%" class="table table-striped table-bordered table-hover" id="sample_3" >
                                 
                                                        <!--ici-->
                                  
                                    <thead>
<tr>
  
  <th nowrap="nowrap">Lib&eacute;ll&eacute;</th>
  <th nowrap="nowrap">Reference</th>
  <th nowrap="nowrap">Fournisseur</th>
  <th nowrap="nowrap">Date de livraison prevu</th>
  <th nowrap="nowrap">Date de reception</th>
  <th nowrap="nowrap">N livraison</th>
  <th nowrap="nowrap">Commentaire</th>
  <th nowrap="nowrap"> statut</th>
   <th nowrap="nowrap"> Action</th>
  
  
</thead>
    <tbody>
    
<?php foreach ($list as $key => $result): ?>

            <tr>
            <input type="hidden" size="10" class="form-control" name="cmd_id" id="cmd_id" value="<?= $result['cmd_id'] ?>">
            <input type="hidden" size="10" class="form-control" name="fourn_id" id="fourn_id" value="<?= $result['fourn_id'] ?>">
                <td><?= $result['cmd_lib'] ?></td>
                <td><?= $result['cmd_code'] ?></td>
                <td><?= $result['fourn_lib'] ?></td>
                <td><?php echo  date("d/m/Y", strtotime($result['cmd_date_liv'])); ?></td>
                <td><input type="text" name="liv_date" id="liv_date" class="form-control form-control-inline date-picker" value="<?= date('d/m/Y'); ?>"  size="20" maxlength="10" /></td>
                <td valign="top"><label for="reference"></label>
                  <input type="text" size="10" class="form-control" name="reference" id="reference"></td>
                
               <td valign="top"><label for="commentaire"></label>
                 <textarea class="form-control" name="commentaire" id="commentaire" cols="10" rows="2"></textarea></td>
               <td valign="top"><label for="unstatut"></label>
                 <select class="form-control" name="unstatut" id="unstatut">
                   <option value="1">livr&eacute;</option>
                   <option value="0">Annul&eacute;</option>
                 </select>               
                     <td valign="top">
                       <label for="unstatut"></label>
                       <input class="btn green" type="submit" name="clot_info" value="Valider" onClick="confirm('Voulez-vous confirmer cette livraison???');" />
                     </td>
            </tr>

           <?php endforeach; ?>

</tbody>
</table>
 </div></form>
                                  <?php endif;?>
                                  <!--end tableau-->


