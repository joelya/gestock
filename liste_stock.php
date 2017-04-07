<?php
   //POUR RECUPERER LE SEUIL
	$sql_seuil = "SELECT `seuil_id`, `seuil_val` FROM `param_seuil`";
	$list_seuil = $pdo->prepare($sql_seuil);
    $list_seuil->execute();
	foreach ($list_seuil as $infoseuil){
	$leseuil = $infoseuil['seuil_val'];
	} 
 if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)&& !empty($_FILES) && isset($_POST['valid_info'])){
 
$extensions_valides = array( 'txt' , 'csv' , 'xlsx' , 'xls' );
$extension_upload = strtolower(substr(strrchr($_FILES['lefichier']['name'], '.')  ,1)  );
if ( in_array($extension_upload,$extensions_valides) ) {
 	
  $dossier = 'files/1/';
//Créer un identifiant difficile à deviner
  $nom = md5(uniqid(rand(), true));
   
   
 $nom = "{$dossier}{$nom}.{$extension_upload}";

$resultat = move_uploaded_file($_FILES['lefichier']['tmp_name'],$nom);

}
     if($resultat)
	 {
        function read_csv($filename)
        {
        // ouverture du fichier
            $FILE=fopen($filename,"r");
        // lire ligne par ligne et couper colonne par colonne
            while ($ARRAY[]=fgetcsv($FILE,0,";"));
        // fermer le fichier
            fclose($FILE) ;
        // effacer la dernière ligne
        array_pop($ARRAY);
        // renvoi le tableau
            return $ARRAY ;
        }
    
    $data=read_csv($nom);
	
    for($i=1;$i<sizeof($data);$i++)
    {
		 
		 $req = $pdo->prepare('UPDATE produit SET prd_lib = ?,prd_code = ?,prd_qte = ?,prd_prix_ttc = ? WHERE prd_code = ?');
			
		$req->execute(array($data[$i][1],$data[$i][2],$data[$i][7],$data[$i][5],$data[$i][2]));
		if($req){
			$reponse = "update reussite<br>";
		}
		else
		$reponse = "problème update<br>";
		
      // echo $data[$i][0]."<br>";
	
    }
	 }
 }
	$sql_prod = "SELECT p.prd_id,f.fam_libel, p.fam_id, p.prd_lib, p.prd_code, p.prd_qte, p.prd_prix_ht, p.prd_prix_ttc, p.prd_tva, p.prd_flag_actif FROM produit p inner join famille f ON p.fam_id = f.fam_id ";
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
                    <br/>
                <div class="note note-success">Pour regulariser le stock il faut importer un fichier au format Csv. Vous pouvez d'abord telecharger l'etat actuel au format Excel (Csv)</div>
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
                                                      <form method="POST" action="#" enctype="multipart/form-data">
                                                      <div class="row">
													  <div class="col-md-12">
														<div class="form-group col-md-3">
                                                            <label class="control-label">Telecharger votre fichier:</label></div><input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                                                            <div class="col-md-4">
                                                            
                                							  <input type="file" name="lefichier" id="lefichier" class="form-control"> 
                                                             <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                        <div class="form-group col-md-5"><input class="btn default" type="submit" name="valid_info" value="Regulariser le stock" /></div></div>
													</div></form>
                                                                
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
                                <table width="496" class="table table-striped table-bordered table-hover" id="sample_3" >
                                                        <!--ici-->
                                  
                                    <thead>
<tr>
  
  <th width="19" nowrap="nowrap">n°</th>
  <th width="38" nowrap="nowrap">libellé</th>
  <th width="80" nowrap="nowrap">code produit</th>
  <th width="43" nowrap="nowrap">famille</th>
  <th width="94" nowrap="nowrap">Fournisseur(s)</th>
  <th width="45" nowrap="nowrap">prix ttc</th>
  <th width="41" nowrap="nowrap">prix ht</th>
  <th width="52" nowrap="nowrap">quantit&eacute;</th>
  <th width="26" nowrap="nowrap">Etat</th>
  </thead>
    <tbody>
<?php foreach ($list as $key => $prod): ?>

            <tr>
                <td nowrap="nowrap">
                   <?= $key+1; ?>
                </td>
                <td nowrap="nowrap">
                   <?= ucfirst(strtolower($prod['prd_lib'])) ?>
                </td>
                <td nowrap="nowrap"><?= ucfirst(strtolower($prod['prd_code'])) ?></td>
               <td nowrap="nowrap">
                   <?= ucfirst(strtolower($prod['fam_libel'])) ?>
                </td>
                <?php
					$sql_fp = "SELECT p.prd_id, p.fam_id, p.prd_lib, p.prd_code, p.prd_qte, p.prd_prix_ht, p.prd_prix_ttc, p.prd_tva, p.prd_flag_actif,f.fourn_lib FROM fournisseur_prod fp LEFT JOIN produit p ON fp.prd_id = p.prd_id LEFT JOIN fournisseur f ON f.fourn_id = fp.fourn_id  WHERE p.prd_id = '".$prod['prd_id']."' GROUP BY f.fourn_id";
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
                 <td nowrap="nowrap"><?= $prod['prd_qte'] ?></td>
                <td nowrap="nowrap"><?php 
				if($prod['prd_qte']==0){ echo '<span class="label label-sm label-danger">rupture de stock</span>';}
				elseif($prod['prd_qte']>0 AND $prod['prd_qte']<=$leseuil) {echo '<span class="label label-sm label-warning">Stock critique!</span>';}   ?></td>
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


