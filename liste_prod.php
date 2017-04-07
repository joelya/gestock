<?php
    

	$sql_prod = "SELECT p.prd_id,f.fam_libel,sf.sfam_lib,m.marque_lib,p.fam_id, p.prd_lib, p.prd_code, p.prd_qte, p.prd_prix_ht, p.prd_prix_ttc, p.prd_tva, p.prd_flag_actif FROM produit p left join famille f ON p.fam_id = f.fam_id left join sous_famille sf on sf.sfam_id=p.sfam_id left join marque m on m.marque_id=p.marque_id ORDER BY p.prd_id DESC";
	$list = $pdo->prepare($sql_prod);
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
                                <a href="#">liste des produits</a>
                                
                            </li>
                           
                        </ul>
                         </div>
                    <!-- end page bar -->
                
				 <!-- begin page title-->
                    <h3 class="page-title"> 
                                  liste des produits
                    </h3>
                    <!-- end page title-->
                      <div class="row">
                        <div class="col-md-12">
                            <!-- begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                       
                                    </div>
                                                               <a class="btn green" href="<?= $_SESSION['proflis']?>.php?p=add_prod"><i class="fa fa-plus"></i>ajouter</a>
                                                                
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
                                <?php if (isset($_get['r'])): ?>
                                   <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                         <a href="" class="alert-link"> mise à jour reussie</a>
                                  </div>
                                    <?php endif;?>
                                    
                                    <?php if (isset($_get['insert'])): ?>
                                   <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                         <a href="" class="alert-link">insertion reussie</a>
                                  </div>
                                    <?php endif;?>
                                     
                                    <?php if (isset($_get['sup'])): ?>
                                  <div class="alert alert-block alert-danger fade in">
                                        <button type="button" class="close" data-dismiss="alert"></button>
                                                                           <p>suppression reussie</p>
                                    </div>
                                    <?php endif;?> 
                                     
                                    <div class="table-container">
                                <table width="491" class="table table-striped table-bordered table-hover" id="sample_3" >
                                                        <!--ici-->
                                  
                                    <thead>
<tr>
  <th width="45">code produit</th>
  
  <th width="38" nowrap="nowrap">libellé</th>
  <th width="64" nowrap="nowrap">Catégorie</th>
  <th width="60">sous catégorie</th>
  <th width="52" nowrap="nowrap">Marque</th>
  <th width="94" nowrap="nowrap">Fournisseur(s)</th>
  <th width="45">prix ttc</th>
  <th width="41">prix ht</th>
  <th width="38" nowrap="nowrap">action</th>
  
  
</thead>
    <tbody>
<?php foreach ($list as $key => $prod): ?>

            <tr>
              <td nowrap="nowrap"><?= $prod['prd_code'] ?></td>
                <td nowrap="nowrap">
                   <?= ucfirst(strtolower($prod['prd_lib'])) ?>
                </td>
                <td nowrap="nowrap">
                   <?= ucfirst(strtolower($prod['fam_libel'])) ?>
                </td>
               <td nowrap="nowrap"><?= ucfirst(strtolower($prod['sfam_lib'])) ?></td>
               <td nowrap="nowrap"><?= ucfirst(strtolower($prod['marque_lib'])) ?></td>
                <?php
					$sql_fp = "SELECT p.prd_id, p.fam_id, p.prd_lib, p.prd_code, p.prd_qte, p.prd_prix_ht, p.prd_prix_ttc, p.prd_tva, p.prd_flag_actif,f.fourn_lib FROM fournisseur_prod fp LEFT JOIN produit p ON fp.prd_id = p.prd_id LEFT JOIN fournisseur f ON f.fourn_id = fp.fourn_id  WHERE p.prd_id = '".$prod['prd_id']."' GROUP BY f.fourn_id ";
	$listfp = $pdo->prepare($sql_fp);
    $listfp->execute(); ?>
				
               <td nowrap="nowrap">
			    <?php  foreach ($listfp as $fp){ ?>
			   - <?= ucfirst(strtolower($fp['fourn_lib'])) ?><br>
			   <?php }?>
               </td>
                <td nowrap="nowrap">
                
                   <?=  $prod['prd_prix_ttc']; ?>
                </td>
                 <td nowrap="nowrap">
                
                    <?= number_format($prod['prd_prix_ht'], 2, ',', ' ') ?>
                </td>
                <td align="left" nowrap="nowrap"><a href="<?= $_SESSION['proflis']?>.php?p=up_prod&prd_id=<?php echo crypturl($prod['prd_id']); ?>" class="btn btn-outline btn-circle btn-sm blue">
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


