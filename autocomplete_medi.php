<?php

//Sécurité pour empêcher d'interroger la base librement : liste des table et des champs utilisables
$check = array(
	'medicament' => array('medi_lib')
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
		$req = $db->query('SELECT DISTINCT medi_lib FROM medicament WHERE medi_lib LIKE "'.$search.'%" AND flag = 1 ORDER BY medi_lib');
		$req->setFetchMode(PDO::FETCH_OBJ);
		
		while($row = $req->fetch()){
		echo '	<suggest>'. $row->$field .'</suggest>';
		}
		
		echo '</suggests>';
	}
}
else{
	die('Requête interdite');
}
$req->closeCursor();
?>