<?php
//liste des travailleurs
if(isset($_GET['clt_ids']) AND isset($_GET['fl'])){
		
			$clt = $_GET['clt_ids'];
			$clt_flag_actif = $_GET['fl'];
			if($clt_flag_actif=='1'){$clt_flag_actifup = 0;}
			elseif($clt_flag_actif=='0'){$clt_flag_actifup = 1;}
				
					$date = date("Y-m-d").' '.date("H:i:s");
					$update = "UPDATE client
					SET clt_flag_actif = '".$clt_flag_actifup."'
					WHERE clt_id = '".$clt."'";
					//var_dump($update);exit;
					$pdo->query($update);
					echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_clt&sup=ok"</script>';	
	}
	
	$sql_clt = "SELECT c.clt_id, c.clt_nom, c.clt_pren,mp.medp_nom,mp.medp_prenom ,c.clt_adresse, c.clt_sexe, c.clt_bur, c.clt_contact,c.clt_porte, c.clt_flag_actif FROM `client` c LEFT JOIN medecin_client mc ON c.`clt_id`= mc.`clt_id` LEFT JOIN med_prescritpteur mp ON mp.medp_id=mc.medp_id GROUP BY  c.clt_id ORDER BY c.clt_nom ASC";
	$list = $pdo->prepare($sql_clt);
    $list->execute();
	//POUR CHANGER LE STATUT
	
	
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
function show_confirm(isbn,clt_flag_actif)
{
var con = confirm("Voulez vous modifier le statut!!!");
if(con==true){
location.href='page.php?p=liste_clt&clt_ids='+isbn+'&fl='+clt_flag_actif;
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
                                <a href="#">Liste des Clients</a>
                                
                            </li>
                           
                        </ul>
                         </div>
                    <!-- END PAGE BAR -->
                
				 <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> 
                                  Liste des Clients
                    </h3>
                    <!-- END PAGE TITLE-->
                      <div class="row">
                        <div class="col-md-12">
                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                       
                                    </div>
                                                               <a class="btn green" href="<?= $_SESSION['proflis']?>.php?p=add_clt"><i class="fa fa-plus"></i>Ajouter</a>
                                                                
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
  <th nowrap="nowrap">Mr, Mlle, Mme</th>
  <th nowrap="nowrap">Adresse</th>
  <th nowrap="nowrap">Cellulaire</th>
  <th nowrap="nowrap">Bureau</th>
  <th nowrap="nowrap">Porte</th>
  <th>Action</th>
  
  
</thead>
    <tbody>
<?php foreach ($list as $key =>  $result): ?>

            <tr>
                <td>
                   <?= $key+1; ?>
                </td>
                <td><?= ucfirst(strtolower($result['clt_nom'])) ?> <?= ucfirst(strtolower($result['clt_pren'])) ?></td>
                <td><?= ucfirst(strtolower($result['clt_adresse'])) ?></td>
               <td>
                   <?= ucfirst(strtolower($result['clt_contact'])) ?>
                </td>
                <td>
                	<?= ucfirst(strtolower($result['clt_bur'])) ?>
                </td>
                <td>
                
                	<?php echo $result['clt_porte']; ?>
                </td>
                
                    <?php
					$sql_rc = "SELECT rc.medp_id, rc.clt_id,c.medp_nom,c.medp_prenom FROM medecin_client rc LEFT JOIN med_prescritpteur c ON rc.medp_id=c.medp_id LEFT JOIN client cl ON cl.clt_id = rc.clt_id WHERE cl.clt_id = '".$result['clt_id']."' GROUP BY rc.clt_id";
	$listrc = $pdo->prepare($sql_rc);
    $listrc->execute(); ?>
				
               <td align="left" nowrap="nowrap"><a href="<?= $_SESSION['proflis']?>.php?p=up_clt&clt_id=<?php echo crypturl($result['clt_id']); ?>" class="btn btn-outline btn-circle btn-sm blue">
                 <i class="fa fa-edit"></i> Editer</a>&nbsp;
                 <?php if($result['clt_flag_actif']==1){ ?><a id="<?php echo $result['clt_id']; ?>" onclick='show_confirm( <?= $result['clt_id'];?>,<?= $result['clt_flag_actif'] ;?>)'  href="#" title="D&eacute;v&eacute;rouill&eacute;"><img src="assets/pages/img/unlock-16.png" /></a><?php }?> <?php if($result['clt_flag_actif']==0){ ?>
                 <a href="#" title="v&eacute;rouill&eacute;" onclick='show_confirm(<?= $result['clt_id'];?>,<?= $result['clt_flag_actif'] ;?>)'><img src="assets/pages/img/padlock-10-16.png"/></a><?php }?>
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


