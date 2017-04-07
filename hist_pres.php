<?php
//connexion à la base de données
include('./model/connexion.php');
include('./function/mois.php');
$pdo=connect();
//liste des entreprises
if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 //var_dump($_POST);exit;
		 //GESTION DES ERREURS DE SAISIE
			 $errors = array();
			 $valid=true;
         
        if(empty($_POST['date_debut']))
        {
            $errors[0]='Veuillez saisir une date de debut';
            $valid=false;
        }
		 if(empty($_POST['date_fin']))
        {
            $errors[1]='Veuillez saisir une date de fin';
            $valid=false;
        }
	if($valid=true){
$deb1 = str_replace('/', '-', $_POST['date_debut']);
$fin1 = str_replace('/', '-', $_POST['date_fin']); 
$deb = date('Y-m-d', strtotime($deb1));
$fin = date('Y-m-d', strtotime($fin1));
$hist_cons = "SELECT COUNT(MONTH(p.`presc_date_crea`)) AS Prescription,MONTH(p.`presc_date_crea`) AS Mois FROM prescription p WHERE p.clot = 1
 AND p.presc_date_crea >= '".$deb."' AND p.presc_date_crea <= '".$fin."' GROUP BY MONTH(p.presc_date_crea)"
;
	$list = $pdo->prepare($hist_cons);
    $list->execute();
	$count_hist_presc = $list->rowCount();
	//var_dump($_POST,$list,$deb);exit;
	$tot = 0;
	foreach ($list as $data){
	$uneprescription[] = $data['Prescription'];
	$unmois[] = $data['Mois'];
	$tot = $tot + $data['Prescription'];
	
	}
	
	 $taille = sizeof(@$uneprescription);
	 //var_dump($uneprestation);exit;



	}

 }
?><head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Historique des consultations</title>

		<script type="text/javascript" src="assets/hightchat/jquery.min.js"></script>
		<style type="text/css">
${demo.css}
		</style>
       
	</head>
 

<script src="assets/hightchat/highcharts.js"></script>
<script src="assets/hightchat/data.js"></script>
<script src="assets/hightchat/drilldown.js"></script>



<!-- BEGIN PAGE BAR -->
                    

	<div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                              <a href="<?= $_SESSION['proflis']?>.php">Accueil</a>
                                <i class="fa fa-circle"></i>
                            </li>
                             <li>
                              <a href="<?= $_SESSION['proflis']?>.php?p=liste_dir"><span class="caption"> Historique des prescriptions</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            
                        </ul>
                       
                    </div>
                    <br/>
                    <div class="note note-success">Les champs marqués d'un astérisque <span style="color:#F00"> (*)</span> 
					sont obligatoires </div>
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
											
                                                    <div class="form-actions top"></div>
                                                    <div class="form-body">
													<div class="row">
													<div class="col-md-12">
													<div class="row">
													 
													</div>
													<br>
													<div class="row"> 
													    <div class="col-md-4">	
															  <label class="control-label">Date de Debut : <span style="color:#F00"> (*)</span></label>
															  <input type="text" name="date_debut" id="date_debut" class="form-control form-control-inline date-picker" value="<?= !empty($_POST['date_debut'])?($_POST['date_debut']):''; ?>" />
															  <?php if(!empty($errors[0])): ?>
															  <span class="alert-danger">
															  <?= $errors[0]; ?>
															  </span>
															  <?php endif; ?>
															  </div>
															  <div class="col-md-4">	
															 <label class="control-label">Date de fin : <span style="color:#F00"> (*)</span></label>
															 <input type="text" name="date_fin" id="date_fin" class="form-control form-control-inline date-picker" value="<?= !empty($_POST['date_fin'])?($_POST['date_fin']):''; ?>" />
															 <?php if(!empty($errors[1])): ?>
															  <span class="alert-danger">
															  <?= $errors[1]; ?>
															  </span>
															  <?php endif; ?>
															  </div>
															  <div class="col-md-4">
																<br/>
															  <input class="btn green" type="submit" name="valid_info" value="Rechercher" /><br/><br/><?php if (isset($_POST['valid_info'])){ if($count_hist_presc>0){ ?>															  <a href="#" class="btn default" onclick="NewWindow('print_hist_presc.php?deb=<?= $_POST['date_debut']?>&fin=<?= $_POST['date_fin']; ?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" title="Aper&ccedil;u de la prestation">&nbsp;&nbsp;Aperçu &nbsp;&nbsp;</a>
<?php }} ?>
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




<?php if (isset($_POST['valid_info'])){ if($count_hist_presc>0){ ?>
<table width="100%" class="table table-striped table-bordered table-hover" id="sample_3" >
                                                        <!--ici-->
                                  
                                    <thead>
<tr>
  
  <th nowrap="nowrap">N°</th>
  <th nowrap="nowrap">Mois</th>
  <th nowrap="nowrap">Prescription &eacute;ffectu&eacute;es</th>
  <th nowrap="nowrap">Pourcentage</th>
  </thead>
    <tbody>
    
<?php $uneprescr=0; for($k=0;$k<$taille;$k++){ ?>
		 <tr>
              <td><?= $k+1; ?></td>
              <td><?= getMois($unmois[$k]) ?></td>
              <td><?php echo $uneprescription[$k];$uneprescr+=$uneprescription[$k]; ?></td>
              <td><?=round(($uneprescription[$k]*100/$tot),2) ?>
              % </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Total g&eacute;n&eacute;ral</strong></td>
               <td><?php echo $uneprescr;?></td>
                <td><?=round(($uneprescr*100/$tot),2) ?> %</td>
            </tr>

           <?php }; ?>

</tbody> </table><?php }}?>
