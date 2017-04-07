<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
<link href="assets/datatables.min.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="assets/jquery-1.11.3-jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	
	$("#btn-view").hide();
	
	$("#btn-add").click(function(){
		$(".content-loader").fadeOut('slow', function()
		{
			$(".content-loader").fadeIn('slow');
			$(".content-loader").load('add_form.php');
			$("#btn-add").hide();
			$("#btn-view").show();
		});
	});
	
	$("#btn-view").click(function(){
		
		$("body").fadeOut('slow', function()
		{
			$("body").load('medicament.php');
			$("body").fadeIn('slow');
			window.location.href="medicament.php";
		});
	});
	
});
</script>

</head>

<body>
    


	<div class="container">
      
        
        <button class="btn btn-info" type="button" id="btn-add"> <span class="glyphicon glyphicon-pencil"></span> &nbsp; Ajouter m&eacute;dicament</button>
       
        <hr />
        
        <div class="content-loader">
        
        <table cellspacing="0" width="100%"  class="table table-striped table-hover table-responsive">
        <thead>
        <tr>
        <th>Médicament</th>
        <th>Quantité</th>
        <th>Posologie</th>
        <th>Supprimer</th>
        </tr>
        </thead>
        <tbody>
        <?php
        require_once 'dbconfig.php';
        
        $stmt = $db_con->prepare("SELECT * FROM `prescrire_medi` INNER JOIN prescription ON prescrire_medi.`presc_id` = prescription.`presc_id` JOIN medicament ON prescrire_medi.`medi_id` = medicament.`medi_id` WHERE prescription.`presta_id` = '".@$_SESSION['codep']."' ORDER BY prescrire_medi.prescr_id DESC");
        $stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<tr>
			<td><?php echo $row['medi_lib']; ?></td>
			<td><?php echo $row['prescr_qty']; ?></td>
			<td><?php echo $row['prescr_poso']; ?></td>
			<td align="center"><a id="<?php echo $row['prescr_id']; ?>" class="delete-link" href="#" title="Delete">
			<img src="delete.png" width="20px" />
            </a></td>
			</tr>
			<?php
		}
		?>
        </tbody>
        </table>
        
        </div>

    </div>
    
    <br />
    
  

    
<script src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/datatables.min.js"></script>
<script type="text/javascript" src="crud.js"></script>


</body>
</html>