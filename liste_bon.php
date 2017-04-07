<?php

if(isset($_GET['bon_ids'])){
		
			$bon = $_GET['bon_ids'];
			$bon_flag_actif = $_GET['fl'];
			if($bon_flag_actif=='1'){$bon_flag_actifup = 0;}
			elseif($bon_flag_actif=='0'){$bon_flag_actifup = 1;}
				
					$update = "UPDATE bon_reduction
					SET bon_flag_actif = '".$bon_flag_actifup."'
					WHERE bon_id = '".$bon."'";
					//var_dump($update);exit;
					$pdo->query($update);
					echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_bon&sup=ok"</script>';		
	}
//PARAMETRAGE BON

	$sql_bon = "SELECT `bon_id`, `bon_code`, `bon_lib`, `bon_mont`, `bon_flag_actif` FROM `bon_reduction`";
	$list = $pdo->prepare($sql_bon);
    $list->execute();
	
?>
<script>
function show_confirm(isbn,bon_flag_actif)
{
var con = confirm("Voulez vous modifier le statut!!!");
if(con==true){
location.href='page.php?p=liste_bon&bon_ids='+isbn+'&fl='+bon_flag_actif;
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
                                <a href="#">Liste des Bons de reduction</a>
                                
                            </li>
                           
                        </ul>
                         </div>
                    <!-- END PAGE BAR -->
                
				 <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> 
                                  Liste des Bons de reduction
                    </h3>
                    <!-- END PAGE TITLE-->
                      <div class="row">
                        <div class="col-md-12">
                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                       
                                    </div>
                                                               <a class="btn green" href="<?= $_SESSION['proflis']?>.php?p=add_bon"><i class="fa fa-plus"></i>Ajouter</a>
                                                                
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
                                  <div class="alert alert-block alert-info fade in">
                                        <button type="button" class="close" data-dismiss="alert"></button>
                                                                           <p>Statut chang&eacute;</p>
                                  </div>
                                    <?php endif;?> 
                                     
                                    <div class="table-container">
                                <table width="100%" class="table table-striped table-bordered table-hover" id="sample_3" >
                                                        <!--ici-->
                                  
                                    <thead>
<tr>
  
  <th nowrap="nowrap">N°</th>
  <th nowrap="nowrap">Code</th>
  <th nowrap="nowrap">Lib&eacute;ll&eacute;</th>
  <th nowrap="nowrap">Montant</th>
  <th nowrap="nowrap">Action</th>
   <th nowrap="nowrap">Etat</th>
  
  
</thead>
    <tbody>
<?php foreach ($list as $key => $result): ?>

            <tr>
                <td>
                   <?= $key+1; ?>  
                </td>
                <td><?= $result['bon_code'] ?></td>
                <td><?= $result['bon_lib'] ?></td>
                <td>
                   <?= $result['bon_mont'] ?>
                </td>
               <td align="left" nowrap="nowrap"><a href="<?= $_SESSION['proflis']?>.php?p=up_bon&bon_id=<?php echo crypturl($result['bon_id']); ?>" class="btn btn-outline btn-circle btn-sm blue">
                               <i class="fa fa-edit"></i> Editer</a>&nbsp;</a>
                               <?php if($result['bon_flag_actif']==1){ ?><a id="<?php echo $result['bon_id']; ?>" onclick='show_confirm( <?= $result['bon_id'];?>,<?= $result['bon_flag_actif'] ;?>)'  href="#" title="D&eacute;v&eacute;rouill&eacute;"><img src="assets/pages/img/unlock-16.png" /></a><?php }?> <?php if($result['bon_flag_actif']==0){ ?>
  <a href="#" title="v&eacute;rouill&eacute;" onclick='show_confirm(<?= $result['bon_id'];?>,<?= $result['bon_flag_actif'] ;?>)'><img src="assets/pages/img/padlock-10-16.png"/></a><?php }?>
				
							 </td>
                     <td>
                   <?php if($result['bon_flag_actif']==0)echo '<span class="label label-sm label-danger">Inactif</span>'; else echo '<span class="label label-sm label-success">Actif</span>'; ?>
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


