$(function () {

	$('[data-toggle="tooltip"]').tooltip();
	
	$('.yes').click( function()
	{ 
		var id = $(this).prop('id');
		var info = id.split('_');
		var answer = info[0];
		var question = info[1];
		
		$('#text_'+question).show();	
	});
	
	$('.no').click( function()
	{ 
		var id = $(this).prop('id');
		var info = id.split('_');
		var answer = info[0];
		var question = info[1];
		
		$('#text_'+question).hide();
	});
	
})