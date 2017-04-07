<?php

//PARAMETRAGE CMD

	$sql_cmdf = "SELECT cf.cmd_id,cf.cmd_lib,cf.fourn_id,l.liv_date, l.liv_id,l.liv_ref,cf.cmd_code,cf.cmd_date_liv, cf.cmd_lib,cf.cmd_flag_recu,cf.cmd_flag_recu FROM `commande` cf INNER JOIN livraison l ON cf.cmd_id = l.cmd_id WHERE cf.cmd_flag_recu=1";
	$list = $pdo->prepare($sql_cmdf);
    $list->execute();
	
?>
<script>
function show_confirm(isbn,cmd_flag_recu)
{
var con = confirm("Voulez vous modifier le statut!!!");
if(con==true){
location.href='page.php?p=cmd_fourn&cmd_ids='+isbn+'&fl='+cmd_flag_recu;
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
                                <a href="#">Liste des commandes livr&eacute;es</a>            
                            </li>
                           
                        </ul>
                         </div>
                    <!-- END PAGE BAR -->
                
				 <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> 
                                  Liste des commandes livr&eacute;es</h3>
                    <!-- END PAGE TITLE-->
                      <div class="row">
                        <div class="col-md-12">
                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                       
                                    </div>
                                                               <a class="btn green" href="<?= $_SESSION['proflis']?>.php?p=add_livr"><i class="fa fa-plus"></i>Traiter commande</a>
                                                                
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
                                         <a href="" class="alert-link">Commande cr&eacute;ee</a>
                                  </div>
                                    <?php endif;?>
                                     
                                    <?php if (isset($_GET['sup']) AND ($_GET['sup']==1) ): ?>
                                  <div class="alert alert-block alert-info fade in">
                                        <button type="button" class="close" data-dismiss="alert"></button>
                                                                           <p>Livraison reçu!</p>
                                  </div>
                                    <?php endif;?> 
                                      <?php if (isset($_GET['sup']) AND ($_GET['sup']==0) ): ?>
                                  <div class="alert alert-block alert-danger fade in">
                                        <button type="button" class="close" data-dismiss="alert"></button>
                                                                           <p>Livraison Annul&eacute;e</p>
                                  </div>
                                    <?php endif;?> 
                                     
                                    <div class="table-container">
                                <table width="100%" class="table table-striped table-bordered table-hover" id="sample_3" >
                                                        <!--ici-->
                                  
                                    <thead>
<tr>
  
  <th nowrap="nowrap">N°</th>
  <th nowrap="nowrap">Ref. Livraison</th>
  <th nowrap="nowrap">Ref. commande</th>
  <th nowrap="nowrap">Date de commande</th>
  <th nowrap="nowrap">Date de livraison</th>
  <th nowrap="nowrap">Action</th>
  </thead>
    <tbody>
<?php foreach ($list as $key => $result): ?>

            <tr>
                <td>
                   <?= $key+1; ?>  
                </td>
                <td><?= $result['liv_ref'] ?></td>
                <td><?= $result['cmd_code'] ?></td>
                <td><?php echo  date("d/m/Y", strtotime($result['cmd_date_liv'])); ?></td>
                <td><?php echo  date("d/m/Y", strtotime($result['liv_date'])); ?></td>
                <td>
                   <a href="#" onclick="NewWindow('print_liv.php?liv_id=<?= crypturl($result['liv_id']); ?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" class="btn btn-xs btn-primary"><i class="fa fa-search"></i></a>
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



								