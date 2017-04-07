<?php

//Sécurité pour empêcher d'interroger la base librement : liste des table et des champs utilisables
$check = array(
	'travailleur' => array('trav_nom','trav_pnom','trav_mat')
);

//Connexion à la base de données
//page de connexion à la base de données
try{
		$db = new PDO('mysql:host=localhost;dbname=db_doctor_secu', 'root', '');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	}
	catch(Exception $e) {
		die ('Error: ' . $e->getMessage());
	}

//REQUETTE
$options = array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' );
$table = (isset($_GET['table']) ? $_GET['table'] : '');
$field = (isset($_GET['field']) ? $_GET['field'] : '');
$search = (isset($_GET['search']) ? $_GET['search'] : '');

if(isset($check[$table]) && in_array($field, $check[$table])){ //Vérification
	if($table && $field && $search){
		$search = strtolower($search);

		header("content-type: application/xml");
		echo '<?xml version="1.0" encoding="iso-8859-1" ?>';
		echo '<suggests>';
		$req = $db->query('SELECT DISTINCT trav_nom,trav_pnom,trav_mat FROM travailleur WHERE trav_nom LIKE "'.$search.'%" OR trav_pnom LIKE "'.$search.'%" OR trav_mat LIKE "'.$search.'%" AND flag=1 ORDER BY trav_nom');
		$req->setFetchMode(PDO::FETCH_OBJ);
		
		while($row = $req->fetch()){
			$mat=  "(".$row->trav_mat.")" ;
			echo "	<suggest class='form-control'>". $row->trav_nom ." ". $row->trav_pnom ." ".$mat ."</suggest>";
		}
		
		echo '</suggests>';
	}
}
else{
	die('Requête interdite');
}
$req->closeCursor();
?>