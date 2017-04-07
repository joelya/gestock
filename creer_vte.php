<?php 
	
		/**
		   *	
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 
		 	//RECUPERATION DES VARIABLES
		 	
		 $search = htmlentities(trim($_POST['search']));
		
			//GESTION DES RESTRICTIONS
			 $errors = array();
			 $valid=true;
         
        if(empty($search))
        {
            $errors[0]='Veuillez saisir le nom, prenom ou le matricule du client';
            $valid=false;
        }
         
        if($valid == true){
			$dateMod = date("Y-m-d").' '.date("H:i:s");
			//Affichage des données 
		$sql = 'SELECT `clt_id`, `clt_nom`, `clt_pren`, `clt_age`, `clt_adresse`, `clt_tel`, `clt_sexe`, `clt_bur`, `clt_porte`, `clt_contact`, `clt_flag_actif` FROM `client` WHERE clt_nom = "'.$search.'" OR clt_pren = "%'.$search.'" OR clt_code = "'.$search.'"  AND `clt_flag_actif` = 1 ';
		$list = $pdo->prepare($sql);
		//var_dump($list);exit;
		$list->execute();
		
			}
	 }
	
		
?>
 <!-- BEGIN PAGE BAR -->
 <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                              <a href="<?= $_SESSION['proflis']?>.php">Accueil</a>
                                <i class="fa fa-circle"></i>
                            </li>
                             <li>
                              <a href="#"><span class="caption">Rechercher un client</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            
                      </ul>
                       
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE--><!-- END PAGE TITLE-->
					 <div class="row">
                        <div class="col-md-12">
                            <div class="tabbable-line boxless tabbable-reversed">
                                 <div class="tab-content">
                                  
								
                                        <div class="portlet box blue-hoki">
                                            <div class="portlet-title">
                                             <div class="caption">Rechercher un client
                                          </div></div>
                                            <div class="portlet-body form">
  <form action="" method="post" class="form-horizontal">                                          
                                                <!-- BEGIN FORM-->
											
                                                    <div class="form-actions top">
                                                        
                                                    </div>
                                                    <div class="form-body">
													<div class="row">
													<div class="col-md-12">
													<div class="row">
													 
														<div class="form-group">
                                                            <label class="col-md-4 control-label">Identité du client:</label>
                                                            <div class="col-md-7">
                                							  <input class="form-control" name="search" type="text"  autocomplete="off" style="width:300px;" placeholder="nom ou prenom du client"/>
                                                             <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                        </div>
													</div>
													
													<div class="row"> 
													<div class="col-md-8 col-md-offset-4">
														<div>
														<input class="btn green" type="submit" name="valid_info" value="Valider" />
														</div>
													</div>                      
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
                      <?php if (isset($list)) :?>
                      <!--tableau-->
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
  <th>Visite</th>
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
                <td>&nbsp;</td>
                <td align="left" nowrap="nowrap"><a href="<?= $_SESSION['proflis']?>.php?p=fiche_patient&clt_id=<?php echo crypturl($result['clt_id']); ?>" class="btn btn-outline  btn-sm blue"> <i class="fa fa-stethoscope"></i>Servir</a>&nbsp;</a></td>
            </tr>

           <?php endforeach; ?>

</tbody>

                                        

                                 
                                </table>
                                  </div>
                                  <?php endif;?>
                                  <!--end tableau-->


