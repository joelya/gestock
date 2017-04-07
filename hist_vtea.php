<?php
include('./function/mois.php');
//SELECTION DES BOUTIQUES
$sqlyear = "SELECT YEAR(v.vente_date) AS annee FROM vente v WHERE v.vente_flag_actif = 1  GROUP BY YEAR(v.vente_date) ORDER BY YEAR(v.vente_date) ASC";
	$listeyear = $pdo->prepare($sqlyear);
    $listeyear->execute();
	
if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 

        $condition='';
		 			
$year = $_POST['year']; 
			 if (!empty($year)) {
                $condition.=' AND  YEAR(v.vente_date)='.$year;
             }
$historique = "SELECT COUNT(v.vente_id) AS Vente,YEAR(v.vente_date) AS ANNEE,SUM(v.vente_nap) As ca3 
FROM vente v WHERE v.vente_flag_actif = 1 AND 1=1 ".$condition." GROUP BY YEAR(v.vente_date) ORDER BY SUM(v.vente_nap) DESC";

	$list = $pdo->prepare($historique);
    $list->execute();
	
	$count_historique = $list->rowCount();
	$tot = 0;
	foreach ($list as $key=>$data){
		$an[] = $data['ANNEE'];
		$vente[] = $data['Vente'];
		$ca3[] = $data['ca3'];
		$tot = $tot+$data['ca3'];
		}
	@$taille =sizeof($an);
	//var_dump($taille);exit;
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
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
		$(function () {
    Highcharts.chart('container', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Chiffre d\'affaire <?php if(isset($year)) echo $year; ?>'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Taux',
            colorByPoint: true,
            data: [<?php  for($i=0;$i<$taille;$i++){
	 $prespercent[$i] = ($vente[$i]*100)/$tot; ?>
			{
                name:'CA (<?php echo ($an[$i]); ?>) : <?php echo ($ca3[$i]); ?> F',
                y: <?php echo ($prespercent[$i]); ?>
            },<?php } ?>]
        }]
    });
});
		</script>
	</head>
 

<!-- BEGIN PAGE BAR -->
                    

	<div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                              <a href="<?= $_SESSION['proflis']?>.php">Accueil</a>
                                <i class="fa fa-circle"></i>
                            </li>
                             <li>
                              <a href="#"><span class="caption"> Historique des vente annuelles</span></a>
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
													  <div class="col-md-4">	
													    <label class="control-label">Selectionnez une année :</label>
															 <select class="bs-select form-control" data-live-search="true" data-size="8" name="year" id="year" >
                                                              <option value="">--Aucun--</option>
															<?php foreach ($listeyear as $resultyear): ?>
																<option value="<?= $resultyear['annee']; ?>" >
																
															   <?= $resultyear['annee']; ?></option>
																<?php endforeach; ?>
													    </select>
													  </div>
															  <div class="col-md-4">
																<br/>
															  <input class="btn green" type="submit" name="valid_info" value="Rechercher" /><br/><br/> 
<?php if (isset($_POST['valid_info'])){ if($count_historique>0){ ?>															  <a href="#" class="btn default" onclick="NewWindow('print_hista.php?deb=<?= crypturl($_POST['year']);?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" title="Aper&ccedil;u de la Vente">&nbsp;&nbsp;Aperçu &nbsp;&nbsp;</a><br/><br/><br/>
<?php }} ?>														</div>                
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
  
  <th nowrap="nowrap">Classement</th>
  <th nowrap="nowrap">Année</th>
  <th nowrap="nowrap">Ventes effectuees</th>
  <th nowrap="nowrap">Chiffre d'affaire</th>
  <th nowrap="nowrap">Pourcentage</th>
  </thead>
    <tbody>
    
		<?php  
		for($j=0;$j<$taille;$j++){
	    ?>

            <tr>
              <td><?= $j+1; ?></td>
              
              <td><?= $an[$j]; ?></td>
              <td><?= $vente[$j]; ?></td>
              <td><?= $ca3[$j]; ?></td>
              <td><?php echo round(($ca3[$j]*100/$ca),2);?>
                %</td>
            </tr>
            
              <?php }; ?>
              <tr>
              <td bgcolor="#D6D6D6"><strong>CA GLOBAL</strong></td>
              <td colspan="4" bgcolor="#D6D6D6"><?= $ca ?></td>
            </tr>
             <?php }}; ?>
</tbody></table>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>