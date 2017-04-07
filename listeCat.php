<?php
session_start();
// Vérification des paramètres d'accès au fichier liste.php
if(isset($_GET['go']) || isset($_GET['fam_id']) || isset($_GET['sfam_id'])) {
	// connexion à la base de données
	try {
		$bdd = new PDO('mysql:host=localhost;dbname=gestock_v1', 'webmaster', 'webmaster2017');
	} catch(Exception $e) {
		exit('Impossible de se connecter à la base de données.');
	}
	
	$json = array();
	
	if(isset($_GET['go'])) {
		// requête qui récupère les localités un
		
$requete = "SELECT `fam_id`, `fam_code`, `fam_libel`, `fam_flag_actif` FROM `famille` WHERE fam_flag_actif=1";
		
		// exécution de la requête
		$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
		// Création de la liste
		while($donnees = $resultat->fetch(PDO::FETCH_ASSOC)) {
			// je remplis un tableau et mettant l'id en index
			$json[$donnees["fam_id"]][] = utf8_encode($donnees["fam_libel"]);
		}
	}
	elseif(isset($_GET['fam_id'])) {
		// requête qui récupère les localités un
		$requete = "SELECT * FROM `sous_famille` WHERE `fam_id` = '".$_GET['fam_id']."' AND sfam_id<>1 ORDER BY sfam_lib ASC";
		// exécution de la requête
		$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
		// Création de la liste
		while($donnees = $resultat->fetch(PDO::FETCH_ASSOC)) {
			// je remplis un tableau et mettant l'id en index
			$json[$donnees["sfam_id"]][] = utf8_encode($donnees["sfam_lib"]);
		}
	}
	
    elseif(isset($_GET['sfam_id'])) {
		// requête qui récupère les localités un
		$requete = "SELECT * FROM `autre_famille` WHERE sfam_id = '".$_GET['sfam_id']."' ORDER BY autref_id ASC";
		// exécution de la requête
		$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
		// Création de la liste
		while($donnees = $resultat->fetch(PDO::FETCH_ASSOC)) {
			// je remplis un tableau et mettant l'id en index
			$json[$donnees["autref_id"]][] = utf8_encode($donnees["autref_lib"]);
		}
	}
	echo json_encode($json);
}

?>