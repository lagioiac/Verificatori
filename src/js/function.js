/*
	Created by FenixoDesign http://www.fenixo.it
	Web & graphic designer freelance based in Rome
*/

$(window).load(function(){
	//Nasconde da mobile la barra degli indirizzi del browser
	setTimeout(function(){window.scrollTo(0, 1);}, 0);
});


$(document).ready(function(){
	// Mobile Menu Toggle
	$('.mobilemenu').click(function() {
	  $('nav ul').toggle('fast')
	});
	
	// ALbero
	$('#accordion').dcAccordion();
	
	// MODALE
	// Cambia semaforo
	$('.changelight').click(function() {
		var id = $(this).attr("id");
		alert(id);
		$('.modale.changelight').fadeIn('fast')
	});	
	// Guarda note
	$('.looknote').click(function() {
		$('.modale.setnote').fadeIn('fast')
	});
	
	
	// Importa files attrezzatura
	$('.importa').click(function() {
		$('.modale.importa').fadeIn('fast')
	});
	
	// Validation
	if($('.form-validate').length > 0)
	{
		$('.form-validate').each(function(){
			var id = $(this).attr('id');
			$("#"+id).validate({
				errorElement:'span',
				errorClass: 'help-block error',
				errorPlacement:function(error, element){
					element.parents('.controls').append(error);
				},
				highlight: function(label) {
					$(label).closest('.control-group').removeClass('error success').addClass('error');
				},
				success: function(label) {
					label.addClass('valid').closest('.control-group').removeClass('error success').addClass('success');
				}
			});
		});
	}	
	
	// Chiudi
	$(".closepopup").click(function() {
		$(this).parent().parent().fadeOut();
		return false;
  	});

});