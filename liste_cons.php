<?php
//connexion à la base de données
include('./model/connexion.php');
$pdo=connect();
//liste des entreprises

	$sql_pres = "SELECT p.presta_id,p.presta_code,p.flag_clot,tr.trav_id,tr.trav_nom,tr.trav_pnom,auth.auth_nom,auth.auth_pnom,p.presta_date_crea FROM prestationp pp 
INNER JOIN prestation p ON pp.presta_id = p.presta_id 
JOIN pathologie pa ON pp.path_id = pa.path_id 
JOIN authentification auth ON auth.auth_id = p.auth_id
JOIN travailleur tr ON tr.trav_id = p.trav_id 
JOIN fonction f ON f.fonct_id = tr.fonct_id 
GROUP BY p.presta_id
ORDER BY p.presta_id DESC
";
	$list = $pdo->prepare($sql_pres);
    $list->execute();
	
?>
<script>
var win = null;
function NewWindow(mypage,myname,w,h,scroll,pos,niveau)
{
 
 if(pos=="random"){LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;}
 if(pos=="center"){LeftPosition=(screen.width)?(screen.width-w)/2:100;TopPosition=(screen.height)?(screen.height-h)/2:100;}
 else if((pos!="center" && pos!="random") || pos==null){LeftPosition=0;TopPosition=20}
 settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes';
 
 if((win == null || win.closed)&&(mypage!='#'))
 { win=window.open(mypage,myname,settings); win.focus(); } else
 {win.close(); if ((mypage!='#')) { win=window.open(mypage,myname,settings);win.focus();}}
 
}
</script>
<script>
function show_confirm(isbn)
{
var con = confirm("Voulez vous supprimez cette ligne!!!");
if(con==true){
location.href='page.php?p=up_ent&ent_ids='+isbn;
//location.replace('?pages=paramettre&tp='+isbn);
}
else{
location.href='page.php?p=liste_ent';
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
                                <a href="#">Liste des Consultations éffectuées</a>
                                
                            </li>
                           
                        </ul>
                         </div>
                    <!-- END PAGE BAR -->
                
				 <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> 
                                  Liste des Consultations éffectuées
                    </h3>
                    <!-- END PAGE TITLE-->
                      <div class="row">
                        <div class="col-md-12">
                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                       
                                    </div>
                                                               <a class="btn green" href="<?= $_SESSION['proflis']?>.php?p=consulter"><i class="fa fa-plus"></i>Ajouter</a>
                                                                
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
                                 <div class="table-container">
                                <table width="100%" class="table table-striped table-bordered table-hover" id="sample_3" >
                                                        <!--ici-->
                                  
																					<thead>
												<tr>
												  
												  <th nowrap="nowrap">N°</th>
												  <th nowrap="nowrap">Consultation</th>
												  <th nowrap="nowrap">Infirmier</th>
												  <th nowrap="nowrap">Patient</th>
												  <th nowrap="nowrap">Date</th>
												  <th nowrap="nowrap">Statut</th>
												  <th nowrap="nowrap">Action</th>
												  
												  
												</thead>
													<tbody>
												<?php foreach ($list as $key => $pr): ?>

															<tr>
																<td>
																   <?= $key+1; ?>
																</td>
																<td>
																   <?= ucfirst(strtoupper($pr['presta_code'])) ?>
																</td>
															   <td>
																   <?= ucfirst(strtoupper($pr['auth_nom'])) ?>  <?= ucfirst(strtoupper($pr['auth_pnom'])) ?>
																</td>
																 <td>
																	<?= ucfirst(strtoupper($pr['trav_nom'])) ?>  <?= ucfirst(strtoupper($pr['trav_pnom'])) ?>
																</td>
																<td>
																<?=  date("d/m/Y h:i:s", strtotime($pr['presta_date_crea']));?>
																	
																</td>
																<td>
																	<?php if($pr['flag_clot']==1) echo '<span class="label label-sm label-warning">clôturé</span>'; else echo '<span class="label label-sm label-success">En cours</span>';  ?>
																</td>
															   
															 <td align="left" nowrap="nowrap">
															 
															<a href="<?= $_SESSION['proflis']?>.php?p=fiche_patient&codep=<?= $pr['presta_id']; ?>&trav_id=<?= $pr['trav_id']; ?>" class="btn btn-xs btn-primary"><i class="fa fa-search"></i></a>
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


