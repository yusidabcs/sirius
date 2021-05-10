$(document).ready(function(){
	
	//select
	$('#site_config_default_link').materialSelect();
	
	$('body').on( "click","span.delete_meta",function()
	{
		var tr = $(this).closest("tr");
		tr.remove(); 
	});
	
	$('.add_meta').click(function()
	{
		var table = $(this).closest("table");
		var meta_heading = $(this).attr('id');
		var meta_type = $(this).prev("input").val();
		
		if(meta_type)
		{
			table.find('tbody').append('<tr><td>'+meta_type+'</td><td><input name="'+meta_heading+'['+meta_type+']" class="form-control" type="text" value="" /></td><td><span class="delete_meta" title="Delete"><i class="fas fa-trash"></i></span></td>');
		}
	});

});