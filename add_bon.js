// JavaScript Document
$("#bon").submit(function(e){ // On sélectionne le formulaire par son identifiant
e.preventDefault(); // Le navigateur ne peut pas envoyer le formulaire
var donnees = $(this).serialize();
//alert (donnees);
$.ajax({
type:'POST',
url: 'bon_red.php',
data: donnees,
success: function(response) {
    $('#lebon').html(response);
}});
})