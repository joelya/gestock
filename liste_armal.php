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
$hist_fam = "SELECT COUNT(MONTH(f.ficheam_date_crea)) AS Fiche_maladie,MONTH(f.ficheam_date_crea) AS Mois FROM fichea_maladie f WHERE f.flag_valider = 1 AND f.ficheam_date_crea >= '".$deb."' AND f.ficheam_date_crea <= '".$fin."' GROUP BY MONTH(f.ficheam_id) ORDER BY MONTH(f.ficheam_id) DESC "
;
	$list = $pdo->prepare($hist_fam);
    $list->execute();
	//var_dump($_POST,$list,$deb,$fin);exit;
	$tot = 0;
	foreach ($list as $data){
	
	$unefiche[] = $data['Fiche_maladie'];
	$unmois[] = $data['Mois'];
	$tot = $tot + $data['Fiche_maladie'];
	}
	 $taille = sizeof(@$unefiche);
	 //var_dump($unefiche);exit;
	}

 }
?><head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Historique fiche arrêt de travail</title>

		
		<style type="text/css">
${demo.css}
		</style>
        <script type="text/javascript" src="assets/hightchat/jquery1.8.min.js"></script>
		<script type="text/javascript">
$(function () {
    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Fiche arrêt de travail'
        },
        subtitle: {
            text: '<?php if($_POST['date_debut']!='') echo 'Période du '.$_POST['date_debut'];  if($_POST['date_fin']!='') echo ' Au '.$_POST['date_fin'];?>'
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Fiche arrêt de travail (%)'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Fiche délivrée entre le <?= $deb ?> et le  <?= $fin ?> : <b>{point.y:.1f} %</b>'
        },
        series: [{
            name: 'Fiche arrêt de travail',
            data:<?php  for($i=0;$i<$taille;$i++){ $prespercent[$i] = ($unefiche[$i]*100)/$tot; ?>
	 			[
                ['<?php echo getMois($unmois[$i]); ?>', <?php echo ($unefiche[$i]); ?>],
                
            ], <?php } ?>
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:.1f}', // one decimal
                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });
});
		</script>
        <script type="text/javascript" src="assets/hightchat/highcharts2.js"></script>
        <script type="text/javascript" src="assets/hightchat/exporting.js"></script>
	</head>
<!-- BEGIN PAGE BAR -->
                    

	
	<div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                              <a href="<?= $_SESSION['proflis']?>.php">Accueil</a>
                                <i class="fa fa-circle"></i>
                            </li>
                             <li>
                              <a href="#"><span class="caption"> Historique des fiches d'arrêt maladie</span></a>
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
											
                                                    <div class="form-actions top">
                                                        
                                                    </div>
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
															  <input class="btn green" type="submit" name="valid_info" value="Rechercher" /><br/><br/><br/><br/>
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
<div id="container" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
<?php if (isset($_POST) AND isset($taille)AND isset($tot)){ ?>
<table width="100%" class="table table-striped table-bordered table-hover" >
                                                        <!--ici-->
                                  
                                    <thead>
<tr>
  
  <th nowrap="nowrap">N°</th>
  <th nowrap="nowrap">Mois</th>
  <th nowrap="nowrap">Fiche d&eacute;livr&eacute;es</th>
  <th nowrap="nowrap">Pourcentage</th>
  <th nowrap="nowrap">Action</th>
  
  
</thead>
    <tbody>
    
<?php  $fiche=0; for($k=0;$k<$taille;$k++){?>

            <tr>
              <td><?= $k+1; ?></td>
              <td><?= getMois($unmois[$k]) ?></td>
              <td><?php echo $unefiche[$k];$fiche+=$unefiche[$k] ; ?></td>
              <td><?=round(($unefiche[$k]*100/$tot),2) ?>%</td>
              <td align="left" nowrap="nowrap">				  </td>
            </tr>
              <?php }; ?>
            <tr>
                <td colspan="2" bgcolor="#D6D6D6"><strong>Total g&eacute;n&eacute;ral</strong></td>
               <td bgcolor="#D6D6D6">
                   <?php echo $fiche;?>
                </td>
                <td bgcolor="#D6D6D6">
                   <?=round(($fiche*100/$tot),2) ?>%</td>
                <td align="left" nowrap="nowrap" bgcolor="#D6D6D6"></td>
            </tr></tbody></table><?php } ?>
	