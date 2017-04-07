<?php
//liste des travailleurs
if(isset($_GET['vend_ids']) AND isset($_GET['fl'])){
		
			$vend = $_GET['vend_ids'];
			$vend_flag_actif = $_GET['fl'];
			if($vend_flag_actif=='1'){$vend_flag_actifup = 0;}
			elseif($vend_flag_actif=='0'){$vend_flag_actifup = 1;}
				
					$date = date("Y-m-d").' '.date("H:i:s");
					$update = "UPDATE vendeur
					SET vend_flag_actif = '".$vend_flag_actifup."'
					WHERE vend_id = '".$vend."'";
					//var_dump($update);exit;
					$pdo->query($update);
					echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_vend&sup=ok"</script>';	
	}
	
	$sql_vend = "SELECT vendeur.vend_id, vendeur.caiss_id,caisse.caiss_lib,point_vente.pvte_lib, vendeur.vend_code, vendeur.vend_nom, vendeur.vend_prenom, vendeur.vend_email,vendeur.vend_sexe, vendeur.vend_contact, vendeur.vend_flag_actif FROM `vendeur` INNER JOIN caisse ON vendeur.caiss_id=caisse.caiss_id JOIN point_vente ON caisse.pvte_id = point_vente.pvte_id ORDER BY vendeur.vend_nom ASC";
	$list = $pdo->prepare($sql_vend);
    $list->execute();
	//POUR CHANGER LE STATUT
	
	
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
function show_confirm(isbn,vend_flag_actif)
{
var con = confirm("Voulez vous modifier le statut!!!");
if(con==true){
location.href='page.php?p=liste_vend&vend_ids='+isbn+'&fl='+vend_flag_actif;
//location.replace('?pages=paramettre&tp='+isbn);
}
else{
return false;
}
}
</script>

      <!-- BEGIN PAGE BAR -->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <a href="<?= $_SESSION['proflis']?>.php">Accueil</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <a href="#">Liste des Vendeurs</a>
                                
                            </li>
                           
                        </ul>
                         </div>
                    <!-- END PAGE BAR -->
                
				 <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> 
                                  Liste des Vendeurs
                    </h3>
                    <!-- END PAGE TITLE-->
                      <div class="row">
                        <div class="col-md-12">
                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                       
                                    </div>
                                                               <a class="btn green" href="<?= $_SESSION['proflis']?>.php?p=add_vend"><i class="fa fa-plus"></i>Ajouter</a>
                                                                
                                    <div class="actions">
                                    <div class="btn-group btn-group-devided" data-toggle="buttons">
                                            
                    
     
                                        </div>
                                        <div class="btn-group">
                                            <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                                <i class="fa fa-share"></i>
                                                <span class="hidden-xs"> Outils </span>
                                                <i class="fa fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu pull-right" id="sample_3_tools">
                                                <li>
                                                    <a href="javascript:;" data-action="0" class="tool-action">
                                                        <i class="icon-printer"></i> Imprimer</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:;" data-action="1" class="tool-action">
                                                        <i class="icon-check"></i> Copier</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:;" data-action="2" class="tool-action">
                                                        <i class="icon-doc"></i> PDF</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:;" data-action="3" class="tool-action">
                                                        <i class="icon-paper-clip"></i> Excel</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:;" data-action="4" class="tool-action">
                                                <i class="icon-cloud-upload"></i> CSV</a>                                                </li>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                <?php if (isset($_GET['r'])): ?>
                                   <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                         <a href="" class="alert-link"> Mise à jour reussie</a>
                                  </div>
                                    <?php endif;?>
                                    
                                    <?php if (isset($_GET['insert'])): ?>
                                   <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                         <a href="" class="alert-link">Insertion reussie</a>
                                  </div>
                                    <?php endif;?>
                                     
                                    <?php if (isset($_GET['sup'])): ?>
                                  <div class="alert alert-info">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                         <a href="" class="alert-link">Statut chang&eacute;</a>
                                  </div>
                                    <?php endif;?> 
                                     
                                    <div class="table-container">
                                <table width="100%" class="table table-striped table-bordered table-hover" id="sample_3" >
                                                        <!--ici-->
                                  
                                    <thead>
<tr>
  
  <th nowrap="nowrap">N°</th>
  <th nowrap="nowrap">Code</th>
  <th nowrap="nowrap">Nom</th>
  <th nowrap="nowrap">Prenom</th>
  <th nowrap="nowrap">Email</th>
  <th nowrap="nowrap">Sexe</th>
  <th>Contact</th>
  <th nowrap="nowrap">Point de vente</th>
  <th nowrap="nowrap">Caisse</th>
  <th>Action</th>
  
  
</thead>
    <tbody>
<?php foreach ($list as $key =>  $result): ?>

            <tr>
                <td>
                   <?= $key+1; ?>
                </td>
                <td><?= $result['vend_code'] ?></td>
                <td><?= ucfirst(strtolower($result['vend_nom'])) ?></td>
               <td>
                   <?= ucfirst(strtolower($result['vend_prenom'])) ?>
                </td>
                <td>
                	<?= ucfirst(strtolower($result['vend_email'])) ?>
                </td>
                <td>
                
                	<?php if($result['vend_sexe']==1)
						echo 'Masculin';
						elseif ($result['vend_sexe']==0)
						echo 'Feminin';
					
						 ?>
                </td>
                <td>
                	<?= $result['vend_contact'] ?>
                	
                </td>
                <td><?= $result['pvte_lib'] ?></td>
                <td>
               	<?= $result['caiss_lib'] ?></td>
               
                                               <td align="left" nowrap="nowrap"><a href="<?= $_SESSION['proflis']?>.php?p=up_vend&vend_id=<?php echo crypturl($result['vend_id']); ?>" class="btn btn-outline btn-circle btn-sm blue">
<i class="fa fa-edit"></i> Editer</a>&nbsp;
 <?php if($result['vend_flag_actif']==1){ ?><a id="<?php echo $result['vend_id']; ?>" onclick='show_confirm( <?= $result['vend_id'];?>,<?= $result['vend_flag_actif'] ;?>)'  href="#" title="D&eacute;v&eacute;rouill&eacute;"><img src="assets/pages/img/unlock-16.png" /></a><?php }?> <?php if($result['vend_flag_actif']==0){ ?>
  <a href="#" title="v&eacute;rouill&eacute;" onclick='show_confirm(<?= $result['vend_id'];?>,<?= $result['vend_flag_actif'] ;?>)'><img src="assets/pages/img/padlock-10-16.png"/></a><?php }?>
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


