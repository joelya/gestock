<?php
if(isset($_GET['auth_ids']) AND isset($_GET['fl'])){
		
			$auth_ids = $_GET['auth_ids'];
			$flag = $_GET['fl'];
			if($flag=='1'){$flagup = 0;}
			elseif($flag=='0'){$flagup = 1;}
				
					$update = "UPDATE authentification
					SET flag = '".$flagup."'
					WHERE auth_id = '".$auth_ids."'";
					//var_dump($update);exit;
					$pdo->query($update);
					echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=liste_user&sup=ok"</script>';	
	}

//liste des utilisateurs

	$sql_auth = "SELECT authentification.`auth_id`,authentification.`auth_nom`,authentification.`auth_pnom`,authentification.`auth_user`,authentification.`auth_date_con`,authentification.`auth_date_crea`,authentification.`auth_date_mod`,authentification.`flag`,profil.prof_lib FROM `authentification` INNER JOIN profil WHERE authentification.prof_id = profil.prof_id  ORDER BY authentification.auth_user ASC";
	$list = $pdo->prepare($sql_auth);
    $list->execute();
	
?>
<script>
function show_confirm(isbn,flag)
{
var con = confirm("Voulez vous modifier le statut!!!");
if(con==true){
location.href='page.php?p=liste_user&auth_ids='+isbn+'&fl='+flag;
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
                                <a href="#">Liste des utilisateurs</a>
                                
                            </li>
                           
                        </ul>
                         </div>
                    <!-- END PAGE BAR -->
                
				 <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> 
                                  Liste des utilisateurs
                    </h3>
                    <!-- END PAGE TITLE-->
                      <div class="row">
                        <div class="col-md-12">
                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                       
                                    </div>
                                                               <a class="btn green" href="<?= $_SESSION['proflis']?>.php?p=add_user"><i class="fa fa-plus"></i>Ajouter</a>
                                                                
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
  <th nowrap="nowrap">Profil de connexion</th>
  <th nowrap="nowrap">utilisateur</th>
  <th nowrap="nowrap">Derni&egrave;re connexion</th>
  <th nowrap="nowrap">Date d'ajout</th>
  <th nowrap="nowrap">Dernière modification</th>
  <th nowrap="nowrap">Action</th>
  
  
</thead>
    <tbody>
<?php foreach ($list as $key => $result): ?>

            <tr>
                <td>
                   <?= $key+1; ?>
                </td>
                <td>
                   <?= ucfirst(strtolower($result['prof_lib'])) ?>
                </td>
               <td>
                   <?= ucfirst(strtolower($result['auth_nom'])) ?> <?= ucfirst(strtolower($result['auth_pnom'])) ?>
                </td>
                <td>
                   <?= $result['auth_date_con'] ?>
                </td>
                <td>
                   <?= $result['auth_date_crea'] ?>
                </td>
                <td>
                	<?= $result['auth_date_mod'] ?>
                </td>
               
                                               <td align="left" nowrap="nowrap"><a href="<?= $_SESSION['proflis']?>.php?p=up_user&auth_id=<?php echo crypturl($result['auth_id']); ?>" class="btn btn-outline btn-circle btn-sm blue">
                                                            <i class="fa fa-edit"></i> Editer</a>&nbsp;
 <?php if($result['flag']==1){ ?><a id="<?php echo $result['auth_id']; ?>" onclick='show_confirm( <?= $result['auth_id'];?>,<?= $result['flag'] ;?>)'  href="#" title="Supprimer"><img src="assets/pages/img/unlock-16.png" /></a><?php }?> <?php if($result['flag']==0){ ?>
  <a href="#" onclick='show_confirm(<?= $result['auth_id'];?>,<?= $result['flag'] ;?>)'><img src="assets/pages/img/padlock-10-16.png"/></a><?php }?>
				
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


