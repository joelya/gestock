<?php

$sql_vente = "SELECT auth.auth_nom,auth.auth_pnom,v.vente_id,v.clt_id,clt.clt_nom,clt_pren, v.medp_id, v.comerc_id, v.auth_id, v.vente_ref, v.vente_bon, v.vente_date,v.vente_nap ,v.vente_flag_actif FROM vente v INNER JOIN authentification auth ON auth.auth_id = v.auth_id JOIN client clt ON clt.clt_id = v.clt_id WHERE v.vente_flag_actif=1
";
	$list = $pdo->prepare($sql_vente);
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
                                <a href="#">Liste des Ventes éffectuées</a>
                                
                            </li>
                           
                        </ul>
                         </div>
                    <!-- END PAGE BAR -->
                
				 <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> 
                                  Liste des Ventes éffectuées
                    </h3>
                    <!-- END PAGE TITLE-->
                      <div class="row">
                        <div class="col-md-12">
                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                       
                                    </div>
                                                               <a class="btn green" href="<?= $_SESSION['proflis']?>.php?p=creer_vte"><i class="fa fa-plus"></i>Ajouter</a>
                                                                
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
												  <th nowrap="nowrap">Code Vente</th>
												  <th nowrap="nowrap">Vendeur</th>
												  <th nowrap="nowrap">Client</th>
												  <th nowrap="nowrap">Date</th>
												  <th nowrap="nowrap">Montant</th>
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
																   <?= $pr['vente_ref'] ?>
																</td>
															   <td>
																   <?= ucfirst(strtoupper($pr['auth_nom'])) ?>  <?= ucfirst(strtoupper($pr['auth_pnom'])) ?>
																</td>
																 <td>
																	<?= ucfirst(strtoupper($pr['clt_nom'])) ?>  <?= ucfirst(strtoupper($pr['clt_pren'])) ?>
																</td>
																 <td><?=  date("d/m/Y h:i:s", strtotime($pr['vente_date']));?></td>
																<td>
																<?=  $pr['vente_nap'];?>
																	
																</td>
																<td>
																	<?php if($pr['vente_flag_actif']==1) echo '<span class="label label-sm label-warning">clôturé</span>'; else echo '<span class="label label-sm label-success">En cours</span>';  ?>
																</td>
															   
															 <td align="left" nowrap="nowrap">
															 
															<a href="#" onclick="NewWindow('print_presta.php?vente_id=<?= crypturl($pr['vente_id']); ?>','sous_form_cai',screen.width,screen.height,'yes','center',1);" class="btn btn-xs btn-primary"><i class="fa fa-search"></i></a>
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


