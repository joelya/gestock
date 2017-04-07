<?php
//liste des travailleurs
	
	$sql_clt = "SELECT `clt_id`, `clt_code`,YEAR(now())-YEAR(clt_age) AS anniv,`clt_nom`,`clt_age` ,`clt_pren`,`clt_adresse`, `clt_tel`, `clt_sexe`, `clt_bur`, `clt_porte`, `clt_contact`, `clt_flag_actif` FROM `client` WHERE MONTH(`clt_age`) = MONTH(now()) AND DAY(clt_age) = DAY(now())";
	$list = $pdo->prepare($sql_clt);
    $list->execute();
	//POUR CHANGER LE STATUT
	
	
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

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
                      Les anniversaires du jour
                    </h3>
                    <!-- END PAGE TITLE-->
                      <div class="row">
                        <div class="col-md-12">
                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                       
                                    </div>
                                                            
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
  <th>Date naissance</th>
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
                <td><?= implode("/", array_reverse(explode("-", $result['clt_age']))) ?></td>
                <td align="left" nowrap="nowrap"><?php echo '<span class="label label-sm label-info">'.$result['anniv'].'ans aujourd\'hui</span>';  ?></td>
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


