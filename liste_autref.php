<?php
//PARAMETRAGE CAISSE

	$sql_autref = "SELECT f.fam_libel,sf.sfam_lib,a.autref_id, a.sfam_id, a.autref_code, a.autref_lib, a.autref_flag_actif FROM autre_famille a INNER JOIN sous_famille sf ON sf.sfam_id = a.sfam_id JOIN famille f ON sf.fam_id=f.fam_id WHERE a.autref_flag_actif=1 AND a.autref_id<>1";
	$list = $pdo->prepare($sql_autref);
    $list->execute();
	
?>

      <!-- BEGIN PAGE BAR -->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <a href="<?= $_SESSION['proflis']?>.php">Accueil</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <a href="#">Liste des Autre catégories de produit</a>
                                
                            </li>
                           
                        </ul>
                         </div>
                        
				 <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> 
                                  Liste des autres catégories de produit
                    </h3>
                    <!-- END PAGE TITLE-->
                      <div class="row">
                        <div class="col-md-12">
                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                       
                                    </div>
                                                               <a class="btn green" href="<?= $_SESSION['proflis']?>.php?p=add_autref"><i class="fa fa-plus"></i>Ajouter</a>
                                                                
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
                                  <div class="alert alert-block alert-danger fade in">
                                        <button type="button" class="close" data-dismiss="alert"></button>
                                                                           <p>Suppression reussie</p>
                                  </div>
                                    <?php endif;?> 
                                     
                                    <div class="table-container">
                                <table width="100%" class="table table-striped table-bordered table-hover" id="sample_3" >
                                                        <!--ici-->
                                  
                                    <thead>
<tr>
  
  <th nowrap="nowrap">N°</th>
  <th nowrap="nowrap">Code</th>
  <th nowrap="nowrap">categorie</th>
  <th nowrap="nowrap">Sous categorie</th>
  <th nowrap="nowrap"> Autre categorie</th>
  <th nowrap="nowrap">Action</th>
  
  
</thead>
    <tbody>
<?php foreach ($list as $key => $result): ?>

            <tr>
                <td>
                   <?= $key+1; ?>  
                </td>
                <td><?= $result['autref_code'] ?></td>
                <td><?= $result['fam_libel'] ?></td>
                <td><?= $result['sfam_lib'] ?></td>
                <td>
                   <?= $result['autref_lib'] ?>
                </td>
               <td align="left" nowrap="nowrap"><a href="<?= $_SESSION['proflis']?>.php?p=up_autref&autref_id=<?php echo crypturl($result['autref_id']); ?>" class="btn btn-outline btn-circle btn-sm blue">
                               <i class="fa fa-edit"></i> Editer</a>&nbsp;</a>
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


