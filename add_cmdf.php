<?php 
if(isset($_GET['codec'])){
	$_SESSION['codec'] = decrypturl($_GET['codec']);
}

      if(isset($_POST['clot_info'])){
		 // var_dump ('ici');exit;
	  $update = "UPDATE commande
					SET cmd_flag_clot = '1'
					WHERE cmd_id = '".$_SESSION['codec']."'";
					//var_dump($update);exit;
					$pdo->query($update);
					echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=cmd_fourn&insert=ok"</script>';	
	  }
		/**
		   *	AJOUTER FOURNISSEUR
		   **/
		    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['valid_info']))
     {
		 	//RECUPERATION DES VARIABLES
			//var_dump($_POST);exit;
			$cmd_lib = htmlentities(trim($_POST['cmd_lib']));
			$cmd_code = htmlentities(trim($_POST['cmd_code']));
			$cmd_date_liv = implode("-", array_reverse(explode("/", $_POST['cmd_date_liv'])));
			$fourn_id = @$_POST['fourn_id'];
			//GESTION DES ERREURS DE SAISIE
			 $errors = array();
			 $valid=true;
         
        if(empty($cmd_code))
        {
            $errors[0]='Veuillez saisir le Code de la commande';
            $valid=false;
        }
		
		 if(empty($cmd_date_liv))
        {
            $errors[1]='Veuillez saisir la date de livraison de la commande';
            $valid=false;
        }
		 if(empty($cmd_lib))
        {
            $errors[2]='Veuillez saisir le libell&eacute; de la commande';
            $valid=false;
        }
        if($valid == true){
			$dateMod = date("Y-m-d").' '.date("H:i:s");
			//CREATION commande
			  $sql = "INSERT INTO `commande`(`fourn_id`, `cmd_code`, `cmd_date`, `cmd_date_liv`, `cmd_lib`, `cmd_flag_actif`) VALUES(?,?,?,?,?,?);
";	

		$req = $pdo->prepare($sql);
		
		$req->execute(array($fourn_id,$cmd_code,$dateMod,$cmd_date_liv,$cmd_lib,1));
		$cmd_id = $pdo->lastInsertId();
		echo '<script>location.href="'.$_SESSION['proflis'].'.php?p=add_cmdf&codec='.crypturl($cmd_id).'"</script>';
			
			}
		}
		 //MISE A JOUR DE LA COMMANDE
	 if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST) && isset($_POST['update_info']))
     {
	 
		 //RECUPERATION DES VARIABLES
		 $cmd_lib = htmlentities(trim($_POST['cmd_lib']));
		$cmd_code = htmlentities(trim($_POST['cmd_code']));
		$cmd_date_liv = implode("-", array_reverse(explode("/", $_POST['cmd_date_liv'])));
		$fourn_id = @$_POST['fourn_id'];
			
		    //GESTION DES ERREURS DE SAISIE
			 $errors = array();
			 $valid=true;
          if(empty($cmd_code))
        {
            $errors[0]='Veuillez saisir le Code de la commande';
            $valid=false;
        }
		
		 if(empty($cmd_date_liv))
        {
            $errors[1]='Veuillez saisir la date de livraison de la commande';
            $valid=false;
        }
        if($valid == true){
		try{ 
		/**
			*MISE A JOUR DE LA COMMANDE
			**/
			$cmd_id = decrypturl($_GET['codec']);
			$update = " UPDATE `commande` SET `cmd_code`='".$cmd_code."',`cmd_date_liv`='".$cmd_date_liv."',cmd_lib = '".$cmd_lib."',fourn_id = '".$fourn_id."',cmd_flag_mod = '1'
					      WHERE cmd_id = '".$cmd_id."' ";	
				//var_dump($update);exit;
		       $pdo->query($update);
			   }
			catch(PDOException $e){
			
			$errors[7]='ce code est deja utilis&eacute;!';
			//echo $e->getMessage();
		}	
		
			}
		
	 }
	 
	 //liste des fouirnisseurs
$sql_fourn = "SELECT `fourn_id`,`fourn_code`,`fourn_lib`,`fourn_addr`,`fourn_contact`,`fourn_email`,`fourn_falg_actif` FROM `fournisseur` ORDER BY `fourn_id` DESC";
	$list_fourn = $pdo->prepare($sql_fourn);
    $list_fourn->execute();
	
//liste des fouirnisseurs
$sql_cmdf = "SELECT cf.cmd_id, cf.fourn_id,f.fourn_lib, cf.cmd_code, cf.cmd_lib,cf.cmd_flag_actif FROM `commande` cf INNER JOIN fournisseur f ON cf.fourn_id = f.fourn_id WHERE cf.cmd_id = '".decrypturl(@$_GET['codec'])."'";
	$list_cmdf = $pdo->prepare($sql_cmdf);
    $list_cmdf->execute();
	foreach($list_cmdf as $result){
		$unfourn = $result['fourn_id'];
		}
//GESTION DE LA COMMANDE
	$sql_cmdf= "SELECT `cmd_id`, `fourn_id`, `cmd_code`, `cmd_date`, `cmd_date_liv`, `cmd_lib`, `cmd_flag_actif` FROM `commande` WHERE `cmd_id` =  '".decrypturl(@$_GET['codec'])."'";
	$lacmdf = $pdo->prepare($sql_cmdf);
    $lacmdf->execute();
	foreach ($lacmdf as $unecmdf){
		$cmd_lib = $unecmdf['cmd_lib'];
		$cmd_code = $unecmdf['cmd_code'];
		$cmd_date_liv = $unecmdf['cmd_date_liv'];
		
	}
	 
		//POUR VERIFIER SI UNE COMMANDE A DEJA ETE CREER
		$clotP = $pdo->prepare("SELECT `cmd_flag_actif` FROM commande WHERE `cmd_id` ='".decrypturl(@$_GET['codec'])."' AND cmd_flag_actif =1");
		$clotP->execute();
		$countclotP = $clotP->rowCount(); 
		//POUR VERIFIER SI UNE COMMANDE A  ETE MODIFIEE
		$clotM = $pdo->prepare("SELECT `cmd_flag_mod` FROM commande WHERE `cmd_id` ='".decrypturl(@$_GET['codec'])."' AND cmd_flag_mod =1");
		$clotM->execute();
		$countclotM = $clotM->rowCount(); 
		//LISTE DES PRODUITS A AJOUTER
	$sql_prod = "SELECT p.prd_id,f.fam_libel, p.fam_id, p.prd_lib, p.prd_code, p.prd_qte, p.prd_prix_ht, p.prd_prix_ttc, p.prd_tva, p.prd_flag_actif FROM produit p inner join famille f ON p.fam_id = f.fam_id WHERE p.prd_qte>0 ";
	$list_prod = $pdo->prepare($sql_prod);
    $list_prod->execute();
	 //POUR VERIFIER SI UNE VENTE A DEJA ETE FAITE
		$clot_def = $pdo->prepare("SELECT `cmd_flag_clot` FROM `commande` WHERE `cmd_id` =  '".decrypturl(@$_GET['codec'])."' AND cmd_flag_clot =1");
		$clot_def->execute();
		$cloturer= $clot_def->rowCount();
?>
 <!-- BEGIN PAGE BAR -->
 

<body>
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                              <a href="page.php">Accueil</a>
                                <i class="fa fa-circle"></i>
                            </li>
                             <li>
                              <a href="page.php?p=cmd_fourn"><span class="caption"> Liste des commandes</span></a>
                               <i class="fa fa-circle"></i>
                            </li>
                            <li>
                              <a href="#"><span class="caption">Ajouter une commande</span></a>
                              
                            </li>
                        </ul>
                       
                    </div><br/>
                    <div class="note note-success">Les champs marqués d'un astérisque <span style="color:#F00"> (*)</span> 
					sont obligatoires </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE--><!-- END PAGE TITLE-->
					 <div class="row">
                        <div class="col-md-12">
                          <div class="tabbable-line boxless tabbable-reversed">
                            <div class="tab-content">
                                  <?php if ($countclotP==1 AND $countclotM==0){ ?>
								<div class="alert alert-success alert-dismissable">
														<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
														 <a href="" class="alert-link">Commande créee</a>
							  </div>
                                                        <?php  } ?>
                                                        <?php if ($countclotM==1 ){ ?>
								<div class="alert alert-success alert-dismissable">
														<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
														 <a href="" class="alert-link">Commande modifi&eacute;e</a>
							  </div>
                                                        <?php  } ?>
                                        <div class="portlet box blue-hoki">
                                          <div class="portlet-title">
                                             <div class="caption">Ajouter une commande</div>
                                          </div>
                                          
                                            <div class="portlet-body form">
                                   <form action="" method="post" class="form-horizontal">
                                                <!-- BEGIN FORM-->

                                                <div class="form-actions top"></div>
													
                                          <div class="form-body">
													<?php if(!empty($errors[7])): ?>
                                          <div class="alert alert-danger alert-dismissable">
																<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
																 <a href="" class="alert-link"><?= $errors[7]; ?></a>
														  </div>			 <?php endif; ?>
													<div class="row">
													<div class="col-md-12">
													<div class="col-md-6">
													<div class="row">
														
															<div class="form-group">
                                                            <label class="col-md-4 control-label">Reference: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="cmd_code" id="cmd_code" size="45" maxlength="255" class="form-control" value="<?php if(@$countclotP==1){echo  htmlentities(trim(@$cmd_code)); }?>"  <?php if ($cloturer==1){ echo 'readonly'; } ?> />
                                                              <?php if(!empty($errors[0])): ?>
															   <span class="alert-danger"><?= $errors[0]; ?></span>
															 <?php endif; ?>
															</div>
                                                            
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Libelle: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <input type="text" name="cmd_lib" id="cmd_lib" size="45" maxlength="255" class="form-control" value="<?php if(@$countclotP==1){echo  htmlentities(trim(@$cmd_lib)); }?>"   <?php if ($cloturer==1){ echo 'readonly'; } ?>/>
                                                              <?php if(!empty($errors[2])): ?>
                                                              <span class="alert-danger">
                                                              <?= $errors[2]; ?>
                                                              </span>
                                                              <?php endif; ?>
                                                            </div>
                                                            
                                                        </div>
															
                                                            
                                                      </div>
													</div>
													<div class="col-md-6">
													   <div class="row">
                                                       <div class="form-group">
                                                            <label class="col-md-4 control-label">Fournisseur: <span style="color:#F00"> (*)</span></label>
                                                            <div class="col-md-7">
                                                              <select class="bs-select form-control" data-live-search="true" data-size="8" name="fourn_id" id="fourn_id"  <?php if ($cloturer==1){ echo 'readonly'; } ?> >
                                                                <?php foreach ($list_fourn as $result_fourn): ?>
                                                                <option value="<?= $result_fourn['fourn_id']; ?>" <?php  if ($result_fourn['fourn_id'] == @$unfourn){ echo 'selected';} ?> >
                                                                  <?= $result_fourn['fourn_lib']; ?>
                                                                </option>
                                                                <?php endforeach; ?>
                                                              </select>
                                                            </div>
                                                            
                                                        </div>
													     <div class="form-group">
													       <label class="col-md-4 control-label">Date de livraison : <span style="color:#F00"> (*)</span></label>
													       <div class="col-md-7">
													         <input type="text" name="cmd_date_liv" id="cmd_date_liv" class="form-control form-control-inline date-picker" value="<?php if(@$countclotP==1){echo  htmlentities(trim(@implode("/", array_reverse(explode("-", $cmd_date_liv))))); }?>"  size="45" maxlength="255"  <?php if ($cloturer==1){ echo 'readonly'; } ?> />
													         <?php if(!empty($errors[1])): ?>
													         <span class="alert-danger">
													           <?= $errors[1]; ?>
												             </span>
													         <?php endif; ?>
												           </div>
												         </div>
													    
													   </div>
                                                      </div>
													</div>
                                                   
													<br>
													<div class="row"> 
													<div class="col-md-7 col-md-offset-5"><?php if ($cloturer==0){ ?>
														<div>
														<?php if (@$countclotP==0){ ?>
														<input class="btn default" type="submit" name="valid_info" value="creer" /> 
														<?php } ?>
														<?php if (@$countclotP==1){ ?>
														<div>
														  <input class="btn default" type="submit" name="update_info" value="modifier" />
														</div>
														<?php }?>
														</center>
														
                                                    
														</div><?php } ?>
													</div>                      
													</div>
									   </div>
                                   </form> 
                                                                  
									
                                    
                                     <!--pour ajouter des produits-->
                                     <!--gestion de la modal-->
                                     
                                     <!--end ajouter des produits-->
							        <!-- END FORM-->
                                  </div>
                            </div>
							
						</div>
					  </div>
                      <?php if ($countclotP==1){ ?>
                      <div id="customerModal" class="modal fade">
														 <div class="modal-dialog">
														  <div class="modal-content">
														   <div class="modal-header">
															<h4 class="modal-title">Ajouter un article</h4>
														   </div>
														   <div class="modal-body">
															<label>Article</label>
															<select class="form-control" data-size="8" name="prd_id" id="prd_id" >
															<?php foreach ($list_prod as $result_prod): ?>
																<option value="<?= $result_prod['prd_id']; ?>" >
																
																<?= $result_prod['prd_lib']; ?></option>
																<?php endforeach; ?>
															  </select>
															<br />
															<label>Quantit&eacute;</label>
															<input type="text" name="qty_cmd" id="qty_cmd" class="form-control" />
															<br />
														   </div>
														   <div class="modal-footer">
															<input type="hidden" name="customer_id" id="customer_id" />
															<input type="submit" name="action" id="action" class="btn btn-success" />
															<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
														   </div>
														  </div>
														 </div>
														</div>
                                     <!--end-->
                                                    <div class="row">
                                                    <div class="col-md-12">
                                                    <?php if ($cloturer==0){ ?>
                                                    <div align="right">
														<button type="button" id="modal_button" class="btn btn-info">Ajouter des articles</button><?php } ?>
														<!-- It will show Modal for Ajouter new Records !-->								
                                
												      </div><?php } ?>
                                                    </div></div>
                                                    <br />
   <div id="result" class="table-responsive"> <!-- Data will load under this tag!-->

   </div><?php if ($countclotP==1 && $cloturer==0){ ?><form method="post"><div align="right"><input class="btn green" type="submit" name="clot_info" value="Valider" onClick="confirm('Voulez-vous valider cette commande???');" /></div></form><?php }?>  </div></div>
					 <script src="assets/pages/scripts/jquery2.2.0.min.js"></script>
<script>
						
$(document).ready(function(){
 fetchUser(); //This function will load all data on web page when page load
 function fetchUser() // This function will fetch data from table and display under <div id="result">
 {
  var action = "Load";
  $.ajax({
   url : "action_cmd.php", //Request send to "action.php page"
   method:"POST", //Using of Post method for send data
   data:{action:action}, //action variable data has been send to server
   success:function(data){
    $('#result').html(data); //It will display data under div tag with id result
   }
  });
 }

 //This JQuery code will Reset value of Modal item when modal will load for Ajouter new records
 $('#modal_button').click(function(){
  $('#customerModal').modal('show'); //It will load modal on web page
  $('#prd_id').val(''); //This will clear Modal first name textbox
  $('#qty_cmd').val(''); //This will clear Modal last name textbox
  $('.modal-title').text("Ajouter des articles"); //It will change Modal title to Ajouter new Records
  $('#action').val('Ajouter'); //This will reset Button value ot Ajouter
 });

 //This JQuery code is for Click on Modal action button for Ajouter new records or Update existing records. This code will use for both Ajouter and Update of data through modal
 $('#action').click(function(){
  var prdId = $('#prd_id').val(); //Get the value of first name textbox.
  var qtyServi = $('#qty_cmd').val(); //Get the value of last name textbox
  var id = $('#customer_id').val();  //Get the value of hidden field customer id
  var action = $('#action').val();  //Get the value of Modal Action button and stored into action variable
  if(prdId != '' && qtyServi != '') //This condition will check both variable has some value
  {
   $.ajax({
    url : "action_cmd.php",    //Request send to "action.php page"
    method:"POST",     //Using of Post method for send data
    data:{prdId:prdId, qtyServi:qtyServi, id:id, action:action}, //Send data to server
    success:function(data){
     alert(data);    //It will pop up which data it was received from server side
     $('#customerModal').modal('hide'); //It will hide Customer Modal from webpage.
     fetchUser();    // Fetch User function has been called and it will load data under divison tag with id result
    }
   });
  }
  else
  {
   alert("champ requis"); //If both or any one of the variable has no value them it will display this message
  }
 });

 //This JQuery code is for Update customer data. If we have click on any customer row update button then this code will execute
 $(document).on('click', '.update', function(){
  var id = $(this).attr("id"); //This code will fetch any customer id from attribute id with help of attr() JQuery method
  var action = "Select";   //We have define action variable value is equal to select
  $.ajax({
   url:"action_cmd.php",   //Request send to "action.php page"
   method:"POST",    //Using of Post method for send data
   data:{id:id, action:action},//Send data to server
   dataType:"json",   //Here we have define json data type, so server will send data in json format.
   success:function(data){
    $('#customerModal').modal('show');   //It will display modal on webpage
    $('.modal-title').text("Modifier article"); //This code will change this class text to Update records
    $('#action').val("Modifier");     //This code will change Button value to Update
    $('#customer_id').val(id);     //It will define value of id variable to this customer id hidden field
    $('#prd_id').val(data.prd_id);  //It will assign value to modal first name texbox
    $('#qty_cmd').val(data.qty_cmd);  //It will assign value of modal last name textbox
   }
  });
 });

 //This JQuery code is for Delete customer data. If we have click on any customer row delete button then this code will execute
 $(document).on('click', '.delete', function(){
  var id = $(this).attr("id"); //This code will fetch any customer id from attribute id with help of attr() JQuery method
  if(confirm("Are you sure you want to remove this data?")) //Confim Box if OK then
  {
   var action = "Delete"; //Define action variable value Delete
   $.ajax({
    url:"action_cmd.php",    //Request send to "action.php page"
    method:"POST",     //Using of Post method for send data
    data:{id:id, action:action}, //Data send to server from ajax method
    success:function(data)
    {
     fetchUser();    // fetchUser() function has been called and it will load data under divison tag with id result
     alert(data);    //It will pop up which data it was received from server side
    }
   })
  }
  else  //Confim Box if cancel then 
  {
   return false; //No action will perform
  }
 });
});
</script>

</body>

