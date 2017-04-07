<?php
    

	$sql_medp= "SELECT `medp_id`, `medp_nom`, `medp_prenom`,medp_sexe, `medp_flag_actif`, `medp_code` FROM `med_prescritpteur`  WHERE medp_id<>1 ";
	$list = $pdo->prepare($sql_medp);
    $list->execute();
	
?>
      <!-- begin page bar -->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <a href="<?= $_SESSION['proflis']?>.php">accueil</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <a href="#">liste des medecins prescripteurs</a>
                                
                            </li>
                           
                        </ul>
                         </div>
                    <!-- end page bar -->
                
				 <!-- begin page title-->
                    <h3 class="page-title"> 
                                  liste des medecins prescripteurs
                    </h3>
                    <!-- end page title-->
                      <div class="row">
                        <div class="col-md-12">
                            <!-- begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                       
                                    </div>
                                                               <a class="btn green" href="<?= $_SESSION['proflis']?>.php?p=add_medp"><i class="fa fa-plus"></i>ajouter</a>
                                                                
                                    <div class="actions">
                                    <div class="btn-group btn-group-devided" data-toggle="buttons">
                                            
                    
     
                                        </div>
                                        <div class="btn-group">
                                            <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                                <i class="fa fa-share"></i>
                                                <span class="hidden-xs"> outils </span>
                                                <i class="fa fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu pull-right" id="sample_3_tools">
                                                <li>
                                                    <a href="javascript:;" data-action="0" class="tool-action">
                                                        <i class="icon-printer"></i> imprimer</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:;" data-action="1" class="tool-action">
                                                        <i class="icon-check"></i> copier</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:;" data-action="2" class="tool-action">
                                                        <i class="icon-doc"></i> pdf</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:;" data-action="3" class="tool-action">
                                                        <i class="icon-paper-clip"></i> excel</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:;" data-action="4" class="tool-action">
                                                <i class="icon-cloud-upload"></i> csv</a>                                                </li>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                <?php if (isset($_GET['r'])): ?>
                                   <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                         <a href="" class="alert-link"> mise à jour reussie</a>
                                  </div>
                                    <?php endif;?>
                                    
                                    <?php if (isset($_GET['insert'])): ?>
                                   <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                         <a href="" class="alert-link">insertion reussie</a>
                                  </div>
                                    <?php endif;?>
                                     
                                    <?php if (isset($_GET['sup'])): ?>
                                  <div class="alert alert-block alert-danger fade in">
                                        <button type="button" class="close" data-dismiss="alert"></button>
                                                                           <p>suppression reussie</p>
                                  </div>
                                    <?php endif;?> 
                                     
                                    <div class="table-container">
                                <table width="100%" class="table table-striped table-bordered table-hover" id="sample_3" >
                                                        <!--ici-->
                                  
                                    <thead>
<tr>
  
  <th nowrap="nowrap">n°</th>
  <th nowrap="nowrap">Code</th>
  <th nowrap="nowrap">Nom</th>
  <th nowrap="nowrap">Pr&eacute;nom(s)</th>
  <th nowrap="nowrap">Sexe</th>
  <th nowrap="nowrap">Client(s)</th>
  <th nowrap="nowrap">action</th>
  
  
</thead>
    <tbody>
<?php foreach ($list as $key => $medp): ?>

            <tr>
                <td nowrap="nowrap">
                   <?= $key+1; ?>
                </td>
                <td nowrap="nowrap"><?= $medp['medp_code'] ?></td>
                <td nowrap="nowrap">
                   <?= ucfirst(strtolower($medp['medp_nom'])) ?>
                </td>
                <td nowrap="nowrap"><?= ucfirst(strtolower($medp['medp_prenom'])) ?></td>
                <td><?php if($medp['medp_sexe']==1)
						echo 'Masculin';
						elseif ($medp['medp_sexe']==0)
						echo 'Feminin';
					
						 ?></td>
               <?php
					$sql_rc = "SELECT rc.medp_id, rc.clt_id,cl.clt_nom,cl.clt_pren FROM medecin_client rc LEFT JOIN med_prescritpteur c ON rc.medp_id=c.medp_id LEFT JOIN client cl ON cl.clt_id = rc.clt_id  WHERE c.medp_id = '".$medp['medp_id']."' GROUP BY rc.clt_id";
	$listrc = $pdo->prepare($sql_rc);
    $listrc->execute(); ?>
				
               <td nowrap="nowrap">
                 <?php  foreach ($listrc as $rc){ ?>
                 - <?= ucfirst(strtolower($rc['clt_nom'])) ?> <?= ucfirst(strtolower($rc['clt_pren'])) ?><br>
                 <?php }?>
               </td>
                <td align="left" nowrap="nowrap"><a href="<?= $_SESSION['proflis']?>.php?p=up_medp&medp_id=<?php echo crypturl($medp['medp_id']); ?>" class="btn btn-outline btn-circle btn-sm blue">
                               <i class="fa fa-edit"></i> editer</a>
				</td>
            </tr>

           <?php endforeach; ?>

</tbody>

                                        

                                 
                                </table>
                                  </div>
                                </div>
                            </div>
                            <!-- end: life time stats -->
                        </div>
                    </div>


