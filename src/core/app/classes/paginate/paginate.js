$(document).ready(function(){

    $('#search-panel').find('a').click(function(e) 
    {
		e.preventDefault();
		
		var param = $(this).attr("href").replace("#","");
		var concept = $(this).text();
		
		$('#search_concept').text(concept);
		$('#search_param').val(param);
		
	});
	
});