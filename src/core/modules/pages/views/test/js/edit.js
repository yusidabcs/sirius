//these need to be defined immediately

function getEntryIndexArray()
{
	var sequence = Array();
	    
    $('#entrylist > li').each(function( index ) {
		sequence[index+1] = this.id;
	});
	
	return sequence;
}


//make sure we wait till after the document is ready
$(document).ready(function(){
	
	//set the link id
	var	link_id = $("#link_id").val()
	
	$('#entrylist').sortable({handle: "button", axis: "y"}).bind('sortupdate', function(e, ui) {
		
		sequence = getEntryIndexArray();

		//send to the server
		$.ajax({
			url: "/ajax/pages/contentsort",
			type: 'POST',
			data: {
					'link_id' :	link_id,
					'sequence' : sequence
			},
			cache: false,
			timeout: 10000
		})
		.fail(function() {
			alert('FAILED to update SORT!');
		});
		
	});
	
});