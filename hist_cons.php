<?php
//connexion à la base de données
include('./model/connexion.php');
include('./function/mois.php');
$pdo=connect();
//coût de la consultation
$sql_acte_medi = "SELECT * FROM acte_medical WHERE flag=1 ORDER BY acte_med_lib ASC";
	$list_acte_medi = $pdo->prepare($sql_acte_medi);
    $list_acte_medi->execute();
	foreach($list_acte_medi as $cout){
	$cout=$cout['acte_med_prix'];
	}
	
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

$hist_cons = "SELECT COUNT(MONTH(p.`presta_date_crea`)) AS Prestation,MONTH(p.presta_date_crea) AS Mois 
FROM prestation p WHERE p.flag_clot = 1 
 AND p.presta_date_crea >= '".$deb."' AND p.presta_date_crea <= '".$fin."' GROUP BY MONTH(p.presta_date_crea)"
;
	$list = $pdo->prepare($hist_cons);
    $list->execute();
	$count_hist_cons = $list->rowCount();

	//var_dump($_POST,$list,$deb);exit;
	$tot = 0;
	foreach ($list as $data){
	
	$uneprestation[] = $data['Prestation'];
	$unmois[] = $data['Mois'];
	$tot = $tot + $data['Prestation'];
	}
	
	 $taille = sizeof(@$uneprestation);
	

	}

 }
?><head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Historique des consultations</title>

		<script type="text/javascript" src="assets/hightchat/jquery.min.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
$(function () {
    // Create the chart
    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'STATISTIQUE DES CONSULTATIONS PAR MOIS'
        },
        subtitle: {
            text: '<?php if($_POST['date_debut']!='') echo 'Période du '.$_POST['date_debut'];  if($_POST['date_fin']!='') echo ' Au '.$_POST['date_fin'];?>'
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Consultations effectuées (en %)'
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.1f}%'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> du total<br/>'
        },

        series: [{
            name: 'Mois',
            colorByPoint: true,
			
			
            data: [<?php  for($i=0;$i<$taille;$i++){
	 $prespercent[$i] = ($uneprestation[$i]*100)/$tot;
	  $lesmois[] = getMois($unmois[$i]);
			?>
			{
                name: '<?php echo getMois($unmois[$i]); ?>',
                y: <?php echo ($prespercent[$i]); ?>,
                drilldown: '<?php echo getMois($unmois[$i]); ?>'
            },<?php }; ?>]
        }],
        drilldown: {
            series: [<?php $taille_session = sizeof($lesmois); for($k=0;$i<$taille_session;$k++){?>   {
                name: '<?php echo $lesmois[$k]; ?>',
                id: '<?php echo $lesmois[$k]; ?><?php for($l=0;$l<$taille_du_jour;$l++){?>',
                data: [
                    [
                        '<?php echo $lejr[$l]; ?>',
                       <?php echo $lacons[$l]; ?>
                    ],
                 ]<?php } ?>
            }, <?php }; ?> ]
        }
    });
});
		</script>
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
                              <a href="#"><span class="caption"> Historique des consultations</span></a>
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
															  <input class="btn green" type="submit" name="valid_info" value="Rechercher" /><br/><br/> 
<?php if (isset($_POST['valid_info'])){ if($count_hist_cons>0){ ?>															  <a href="#" class="btn default" onclick="NewWindow('print_hist_cons.php?deb=<?= $_POST['date_debut']?>&fin=<?= $_POST['date_fin']; ?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" title="Aper&ccedil;u de la prestation">&nbsp;&nbsp;Aperçu &nbsp;&nbsp;</a><br/><br/><br/>
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



<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<?php if (isset($_POST['valid_info'])){ if($count_hist_cons>0){ ?>	
<table width="100%" class="table table-striped table-bordered table-hover" id="sample_3" >
                                                        <!--ici-->
                                  
                                    <thead>
<tr>
  
  <th nowrap="nowrap">N°</th>
  <th nowrap="nowrap">Mois</th>
  <th nowrap="nowrap">Vente &eacute;ffectu&eacute;es</th>
  <th nowrap="nowrap">Pourcentage</th>
  <th nowrap="nowrap"><div align="center">Coût</div></th>
  </thead>
    <tbody>
    
		<?php  $untaux=0;$uneprest=0; 
        for($k=0;$k<$taille;$k++){?>

            <tr>
              <td><?= $k+1; ?></td>
              <td><?= getMois($unmois[$k]) ?></td>
              <td><?php echo $uneprestation[$k];$uneprest+=$uneprestation[$k] ; ?></td>
              <td><?=round(($uneprestation[$k]*100/$tot),2);$untaux+=round(($uneprestation[$k]*100/$tot),2)?>%</td>
              <td><div align="center"><?php echo ($uneprestation[$k]*$cout).' Fcfa'; ?></div></td>
            </tr>
              <?php }; ?>
            <tr>
                <td colspan="2" bgcolor="#D6D6D6"><strong>Total g&eacute;n&eacute;ral</strong></td>
               <td bgcolor="#D6D6D6">
                   <strong><?php echo $uneprest;?>
                </strong></td>
                <td bgcolor="#D6D6D6">
                   <strong>
                   <?=round(($untaux),2) ?>
                   %</strong></td>
                <td bgcolor="#D6D6D6"><div align="center"><strong><?php echo ($uneprest*$cout);?>  Fcfa</strong></div></td>
            </tr>
</tbody></table><?php }}?>
