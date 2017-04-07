<?php
include('./function/mois.php');
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

$condition='';
if (!empty($deb)) {
                $condition.=" AND  v.vente_date >='".$deb."'";
             }
if (!empty($deb)) {
                $condition.=" AND  v.vente_date <='".$fin."'";
             }		 
$historique = "SELECT COUNT(v.vente_id) AS Vente,MONTH(v.vente_date) AS Mois 
FROM vente v WHERE v.vente_flag_actif = 1 
 AND 1=1  ".$condition." GROUP BY MONTH(v.vente_date)"
;
	$list = $pdo->prepare($historique);
	//var_dump($count_historique,$list,$deb);exit;
    $list->execute();
	$count_historique = $list->rowCount();


	$tot = 0;
	foreach ($list as $data){
	
	$uneVente[] = $data['Vente'];
	$unmois[] = $data['Mois'];
	$tot = $tot + $data['Vente'];
	}
	
	 $taille = sizeof(@$uneVente);
	}

 }
?><head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Historique des Ventes par période</title>

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
            text: 'STATISTIQUE DES VENTES par période PAR MOIS'
        },
        subtitle: {
            text: '<?php if($_POST['date_debut']!='') echo 'Période du '.$_POST['date_debut'];  if($_POST['date_fin']!='') echo ' Au '.$_POST['date_fin'];?>'
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Ventes par période effectuées (en %)'
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
	 $prespercent[$i] = ($uneVente[$i]*100)/$tot;
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
                              <a href="#"><span class="caption"> Historique des ventes par période</span></a>
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
															  <label class="control-label">Date de Debut : <span style="color:#F00"> (*)</span></label>
															  <input type="text" name="date_debut" id="date_debut" class="form-control form-control-inline date-picker" value="<?= !empty($_POST['date_debut'])?($_POST['date_debut']):''; ?>" />
													    </div>
															  <div class="col-md-4">	
															 <label class="control-label">Date de fin : <span style="color:#F00"> (*)</span></label>
															 <input type="text" name="date_fin" id="date_fin" class="form-control form-control-inline date-picker" value=<?php echo date("d/m/Y H:i:s"); ?>" />
															  </div>
															  <div class="col-md-4">
																<br/>
															  <input class="btn green" type="submit" name="valid_info" value="Rechercher" /><br/><br/> 
<?php if (isset($_POST['valid_info'])){ if($count_historique>0){ ?>															  <a href="#" class="btn default" onclick="NewWindow('print_histp.php?deb=<?= crypturl($_POST['date_debut'])?>&fin=<?= crypturl($_POST['date_fin']); ?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" title="Aper&ccedil;u de la Vente">&nbsp;&nbsp;Aperçu &nbsp;&nbsp;</a><br/><br/><br/>
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
<?php if (isset($_POST['valid_info'])){ if($count_historique>0){ ?>	
<table width="100%" class="table table-striped table-bordered table-hover" id="sample_3" >
                                                        <!--ici-->
                                  
                                    <thead>
<tr>
  
  <th nowrap="nowrap">N°</th>
  <th nowrap="nowrap">Mois</th>
  <th nowrap="nowrap">Vente(s) &eacute;ffectu&eacute;e(s)</th>
  <th nowrap="nowrap">Pourcentage</th>
  </thead>
    <tbody>
    
		<?php  $untaux=0;$uneprest=0; 
        for($k=0;$k<$taille;$k++){?>

            <tr>
              <td><?= $k+1; ?></td>
              <td><?= getMois($unmois[$k]) ?></td>
              <td><?php echo $uneVente[$k];$uneprest+=$uneVente[$k] ; ?></td>
              <td><?=round(($uneVente[$k]*100/$tot),2);$untaux+=round(($uneVente[$k]*100/$tot),2)?>%</td>
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
            </tr>
</tbody></table><?php }}?>
