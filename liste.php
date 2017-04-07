<?php
session_start();
// Vérification des paramètres d'accès au fichier liste.php
if(isset($_GET['go']) || isset($_GET['localite_un']) || isset($_GET['localite_deux'])) {
	// connexion à la base de données
	try {
		$bdd = new PDO('mysql:host=localhost;dbname=gestock_v1', 'webmaster', 'webmaster2017');
	} catch(Exception $e) {
		exit('Impossible de se connecter à la base de données.');
	}
	
	$json = array();
	
	if(isset($_GET['go'])) {
		// requête qui récupère les localités un
		$condition1='';
		if ($_SESSION['boutique']<>6) {
                $condition1.=" AND  pvte_id ='".$_SESSION['boutique']."'";
             }
$requete = "SELECT `pvte_id`, `pvte_code`, `pvte_lib`, `pvt_contact`, `pvt_adresse` FROM `point_vente` WHERE 1=1".$condition1;
		
		// exécution de la requête
		$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
		// Création de la liste
		while($donnees = $resultat->fetch(PDO::FETCH_ASSOC)) {
			// je remplis un tableau et mettant l'id en index
			$json[$donnees["pvte_id"]][] = utf8_encode($donnees["pvte_lib"]);
		}
	}
	elseif(isset($_GET['localite_un'])) {
		// requête qui récupère les localités un
		$requete = "SELECT * FROM `caisse` WHERE `pvte_id` = '".$_GET['localite_un']."'  ORDER BY caiss_lib ASC";
		// exécution de la requête
		$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
		// Création de la liste
		while($donnees = $resultat->fetch(PDO::FETCH_ASSOC)) {
			// je remplis un tableau et mettant l'id en index
			$json[$donnees["caiss_id"]][] = utf8_encode($donnees["caiss_lib"]);
		}
	}
	elseif(isset($_GET['localite_deux'])) {
		// requête qui récupère les localités un
		$requete = "SELECT * FROM service WHERE caiss_id = '".$_GET['localite_deux']."' AND flag = 1  ORDER BY svc_lib ASC";
		// exécution de la requête
		$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
		// Création de la liste
		while($donnees = $resultat->fetch(PDO::FETCH_ASSOC)) {
			// je remplis un tableau et mettant l'id en index
			$json[$donnees["svc_id"]][] = utf8_encode($donnees["svc_lib"]);
		}
	}
	 // envoi du résultat au success
	echo json_encode($json);
}

?>