<?php
include('./function/mois.php');
//LISTE DES PRODUITS A AJOUTER
	$sql_prod = "SELECT p.prd_id,f.fam_libel, p.fam_id, p.prd_lib, p.prd_code, p.prd_qte, p.prd_prix_ht, p.prd_prix_ttc, p.prd_tva, p.prd_flag_actif FROM produit p inner join famille f ON p.fam_id = f.fam_id ";
	$list_prod = $pdo->prepare($sql_prod);
    $list_prod->execute();
//SELECTION DES BOUTIQUES
$condition1='';
if ($_SESSION['boutique']<>6) {
                $condition1.=" AND  pvte_id ='".$_SESSION['boutique']."'";
             }
$sql_pvte = "SELECT `pvte_id`, `pvte_code`, `pvte_lib`, `pvt_contact`, `pvt_adresse` FROM `point_vente` WHERE 1=1".$condition1;
	$liste_pvte = $pdo->prepare($sql_pvte);
    $liste_pvte->execute();
	
if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 //var_dump($_POST);exit;

        $condition='';
$pvte_id = $_POST['pvte_id']; 

			 if (!empty($pvte_id)) {
                $condition.=' AND  pv.pvte_id='.$pvte_id;
             }
$historique = "SELECT pv.pvte_lib,SUM(p.prd_prix_ttc) as ca2 FROM vendre_produit vp JOIN vente v ON vp.vente_id = v.vente_id JOIN produit p ON vp.prd_id = p.prd_id JOIN authentification a ON v.auth_id = a.auth_id JOIN point_vente pv ON a.pvte_id = pv.pvte_id WHERE vente_flag_actif=1 AND 1=1 ".$condition." GROUP BY pv.pvte_id ORDER BY SUM(p.prd_prix_ttc) DESC";

	$list = $pdo->prepare($historique);
    $list->execute();
	$count_historique = $list->rowCount();

	//var_dump($count_historique,$list,$prd_id,$pvte_id);exit;
	
	}
//CA GLOBAL
$sql_ca = "SELECT SUM(v.vente_nap) as ca FROM vente v WHERE vente_flag_actif=1";
	$liste_ca = $pdo->prepare($sql_ca);
    $liste_ca->execute();
	foreach($liste_ca as $data2){
	$ca = $data2['ca'];
	}
?><head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Historique des Ventes par période</title>
	</head>
 

<!-- BEGIN PAGE BAR -->
                    

	<div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                              <a href="<?= $_SESSION['proflis']?>.php">Accueil</a>
                                <i class="fa fa-circle"></i>
                            </li>
                             <li>
                              <a href="#"><span class="caption"> Historique des ventes par boutique</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            
                        </ul>
                       
    </div>
                    <br/>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE--><!-- END PAGE TITLE-->
					 <div class="row">
                        <div class="col-md-12">
    <div class="tabbable-line boxless tabbable-reversed">
                                 <div class="tab-content">
                                  
								
                                        <div class="portlet box blue-hoki">
                                            <div class="portlet-title">
                                             <div class="caption">Filtré par :</div>
                                          </div>
                                            <div class="portlet-body form">
  <form action="" method="post" class="form-horizontal">                                          
                                                <!-- BEGIN FORM-->
											
                                                    <div class="form-actions top">
                                                        
                                                    </div>
                                                    <div class="form-body">
													<div class="row">
													<div class="col-md-12">
													<div class="row">
													  <div class="col-md-4">	
													    <label class="control-label">Selectionnez une boutique :</label>
															 <select class="bs-select form-control" data-live-search="true" data-size="8" name="pvte_id" id="pvte_id" >
                                                              <?php if ($_SESSION['boutique']==6) { ?><option value="">--Aucun--</option><?php } ?>
															<?php foreach ($liste_pvte as $result_pvte): ?>
																<option value="<?= $result_pvte['pvte_id']; ?>" >
																
															   <?= $result_pvte['pvte_lib']; ?></option>
																<?php endforeach; ?>
													    </select>
													  </div>
															  <div class="col-md-4">
																<br/>
															  <input class="btn green" type="submit" name="valid_info" value="Rechercher" /><br/><br/> 
<?php if (isset($_POST['valid_info'])){ if($count_historique>0){ ?>															  <a href="#" class="btn default" onclick="NewWindow('print_histb.php?pvte_id=<?= crypturl($_POST['pvte_id']) ?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" title="Aper&ccedil;u de la Vente">&nbsp;&nbsp;Aperçu &nbsp;&nbsp;</a><br/><br/><br/>
<?php }} ?>														</div>                
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
    <?php if (isset($_POST['valid_info'])){ if($count_historique>0){ ?>	
<table width="100%" class="table table-striped table-bordered table-hover" id="sample_3" >
                                                        <!--ici-->
                                  
                                    <thead>
<tr>
  
  <th nowrap="nowrap">Classement</th>
  <th nowrap="nowrap">Boutique</th>
  <th nowrap="nowrap">Chiffre d'affaire</th>
  <th nowrap="nowrap">Pourcentage</th>
  </thead>
    <tbody>
    
		<?php  
		foreach ($list as $key=>$data){
			$tot =0;
	    ?>

            <tr>
              <td><?= $key+1; ?></td>
              
              <td><?= $data['pvte_lib']; ?></td>
              <td><?= $data['ca2']; ?></td>
              <td><?=round(($data['ca2']*100/$ca),2);?>
                %</td>
            </tr>
            
              <?php }; ?>
              <tr>
              <td bgcolor="#D6D6D6"><strong>CA GLOBAL</strong></td>
              <td colspan="4" bgcolor="#D6D6D6"><?= $ca ?></td>
            </tr>
             <?php }}; ?>
</tbody></table>
