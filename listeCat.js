// JavaScript Document
$(document).ready(function() {
	var $fam_id = $('#fam_id');
	var $sfam_id = $('#sfam_id');
	var $autref_id = $('#autref_id');

	// chargement de la listeCat de localité un
	$.ajax({
		url: 'listeCat.php',
		data: 'go', // on envoie $_GET['go']
		dataType: 'json', // on veut un retour JSON
		success: function(json) {
			$.each(json, function(index, value) {
				// pour chaque noeud JSON
				// on ajoute l option dans la listeCat
				$('#fam_id').append('<option value="'+ index +'">'+ value +'</option>');
			});
		}
	});

	// à la sélection de la localité un dans la listeCat
	$fam_id.on('change', function() {
		var val = $(this).val(); // on récupère la valeur de la localité un
		if(val != '') {
			$sfam_id.empty(); // on vide la listeCat de localité deux
			$sfam_id.append('<option value="1">--Aucune--</option>');
	
			$.ajax({
				url: 'listeCat.php',
				data: 'fam_id='+ val, // on envoie $_GET['fam_id']
				dataType: 'json',
				success: function(json) {
					$.each(json, function(index, value) {
						$sfam_id.append('<option value="'+ index +'">'+ value +'</option>');
					});
				}
			});
		}
		else {
			$sfam_id.empty();
			$sfam_id.append('<option value="">choisissez une sous categorie</option>');
			$autref_id.empty(); // on vide la listeCat de localité deux
			$autref_id.append('<option value="">Choisissez une categorie</option>');
		}
	});

	// à la sélection de la localité deux dans la listeCat
	$sfam_id.on('change', function() {
		var val = $(this).val(); // on récupère la valeur de la localité deux
		if(val != '') {
			$autref_id.empty(); // on vide la listeCat de localité trois
			$autref_id.append('<option value="">Choisissez une categorie</option>');
			
			$.ajax({
				url: 'listeCat.php',
				data: 'sfam_id='+ val, // on envoie $_GET['sfam_id']
				dataType: 'json',
				success: function(json) {
					$.each(json, function(index, value) {
						$autref_id.append('<option value="'+ index +'">'+ value +'</option>');
					});
				}
			});
		}
		else {
			$autref_id.empty();
			$autref_id.append('<option value="">Choisissez une SF</option>');
		}
	});
});