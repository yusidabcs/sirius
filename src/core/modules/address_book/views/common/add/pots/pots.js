$(document).ready(function()
{
	//select
	$('.pots_type').materialSelect();
	$('.pots_country').materialSelect();
	
	//the number of cloned items that exist when we start
	var cloneIndex = $(".clonedInput_pots").length;
	
	$( "#pots" ).on( "click", ".clone", function(event) 
	{
		event.preventDefault();
		
		var cloneID = "pots_entry_" +  (cloneIndex);
		
		$("#pots_entry_template").clone()
	        .appendTo("#pots_entries")
	        .attr("id", cloneID);
	    
	    //update the clone
	    $('#'+cloneID).html( $('#'+cloneID).html().replace(/{X}/g, cloneIndex) );

	    //select the correct ones
	    $('#pots_type_'+cloneIndex).val( $('#adress_book_pots_default_type').val() );
	    $('#pots_country_'+cloneIndex).val( $('#adress_book_pots_default_country').val() );
	    
	    //select
	    $('#pots_type_'+cloneIndex).materialSelect();
	    $('#pots_country_'+cloneIndex).materialSelect();
	        	        
	    cloneIndex++;
	});

	//removing
	$( "#pots" ).on( "click", ".remove", function(event) 
	{
		$(this).parents(".clonedInput_pots").remove();
	});
	
	//update default type with the last type changed
	$( "#pots" ).on( "change", ".pots_type", function () {
		$('#adress_book_pots_default_type').val( $(this).val() );
	});
	
	//update default country with the last country changed
	$( "#pots" ).on( "change", ".pots_country", function () {
		$('#adress_book_pots_default_country').val( $(this).val() );
	});

});