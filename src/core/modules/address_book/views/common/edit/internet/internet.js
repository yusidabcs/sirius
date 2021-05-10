$(document).ready(function()
{
	//select
	$('.internet_type_select').materialSelect();
	
	//the number of cloned items that exist when we start
	var cloneIndex = $(".clonedInput_internet").length;
	
	$( "#internet" ).on( "click", ".clone", function(event) 
	{
		event.preventDefault();
		
		var cloneID = "internet_entry_" +  (cloneIndex);
		
		$("#internet_entry_template").clone()
	        .appendTo("#internet_entries")
	        .attr("id", cloneID);
	    
	    //update the clone
	    $('#'+cloneID).html( $('#'+cloneID).html().replace(/{X}/g, cloneIndex) );

	    //select the correct ones
	    $('#internet_type_'+cloneIndex).val( $('#adress_book_internet_default_type').val() );
	    
	    //mdb select
	    $('#internet_type_'+cloneIndex).materialSelect();
	        	        
	    cloneIndex++;
	});

	//removing
	$( "#internet" ).on( "click", ".remove", function(event) 
	{
		$(this).parents(".clonedInput_internet").remove();
	});
	
	//update default type with the last type changed
	$( "#internet" ).on( "change", ".internet_type", function () {
		$('#adress_book_internet_default_type').val( $(this).val() );
	});

});