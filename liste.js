// JavaScript Document
$(document).ready(function() {
	var $localite_un = $('#localite_un');
	var $localite_deux = $('#localite_deux');
	var $localite_trois = $('#localite_trois');

	// chargement de la liste de localité un
	$.ajax({
		url: 'liste.php',
		data: 'go', // on envoie $_GET['go']
		dataType: 'json', // on veut un retour JSON
		success: function(json) {
			$.each(json, function(index, value) {
				// pour chaque noeud JSON
				// on ajoute l option dans la liste
				$('#localite_un').append('<option value="'+ index +'">'+ value +'</option>');
			});
		}
	});

	// à la sélection de la localité un dans la liste
	$localite_un.on('change', function() {
		var val = $(this).val(); // on récupère la valeur de la localité un
		if(val != '') {
			$localite_deux.empty(); // on vide la liste de localité deux
			$localite_deux.append('<option value="">Choisissez un point de vente</option>');
	
			$.ajax({
				url: 'liste.php',
				data: 'localite_un='+ val, // on envoie $_GET['localite_un']
				dataType: 'json',
				success: function(json) {
					$.each(json, function(index, value) {
						$localite_deux.append('<option value="'+ index +'">'+ value +'</option>');
					});
				}
			});
		}
		else {
			$localite_deux.empty();
			$localite_deux.append('<option value="">choisissez un point de vente</option>');
			$localite_trois.empty(); // on vide la liste de localité deux
			$localite_trois.append('<option value="">Choisissez une caisse</option>');
		}
	});

	// à la sélection de la localité deux dans la liste
	$localite_deux.on('change', function() {
		var val = $(this).val(); // on récupère la valeur de la localité deux
		if(val != '') {
			$localite_trois.empty(); // on vide la liste de localité trois
			$localite_trois.append('<option value="">Choisissez une caisse</option>');
			
			$.ajax({
				url: 'liste.php',
				data: 'localite_deux='+ val, // on envoie $_GET['localite_deux']
				dataType: 'json',
				success: function(json) {
					$.each(json, function(index, value) {
						$localite_trois.append('<option value="'+ index +'">'+ value +'</option>');
					});
				}
			});
		}
		else {
			$localite_trois.empty();
			$localite_trois.append('<option value="">Choisissez une caisse</option>');
		}
	});
});