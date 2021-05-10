$(document).ready(function()
{	
	//select mdb
	$('#address_country_1').materialSelect();
	$('#address_country_2').materialSelect();
	$('#address_physical_pobox_1').materialSelect();
	$('#address_physical_pobox_2').materialSelect();
	$('#address_state_1').materialSelect();
	$('#address_state_2').materialSelect();
	
	//show the correct items for the the address field based on physical_pobox for main address
	$('#address_physical_pobox_1').change(function() {
		var physical_pobox = $(this).val();
		if(physical_pobox == 'physical')
		{
			$('.physical_1').show();
			$('.pobox_1').hide();
		} else {
			$('.physical_1').hide();
			$('.pobox_1').show();
		}
	});
	
	//update the countrySubCodes when country changes
	$('#address_country_1').change(function() {
		var countryCode = $(this).val();
		
		//post off the leaf for data
			$.get('/ajax/address_book/countrysubcodes/'+countryCode)
			.done(function (d) {
				var states = $('#address_state_1').empty();
				if(d)
				{	
					$('#state_1').show();
					$.each(d, function( code,name) {
				      states.append('<option value="' + code + '">' + name + '</option>');
				    });
				} else {
					$('#state_1').hide();
					states.append('<option value="0" selected="selected">Not Applicable</option>');
				}
			})
			.fail(function () {
				alert('Update of Country Sub Codes Failed');
			});
	});
	
	//Second Address Show
	$('#address_same_2').change(function() {
		if($(this).prop( "checked" ))
		{
			$("#address_entry_2").hide();
		} else {
			$("#address_entry_2").show();
		}
	});
	
	//show the correct items for the the address field based on physical_pobox for second address
	$('#address_physical_pobox_2').change(function() {
		var physical_pobox = $(this).val();
		if(physical_pobox == 'physical')
		{
			$('.physical_2').show();
			$('.pobox_2').hide();
		} else {
			$('.physical_2').hide();
			$('.pobox_2').show();
		}
	});
	
	//update the countrySubCodes when country changes
	$('#address_country_2').change(function() {
		var countryCode = $(this).val();
		
		//post off the leaf for data
			$.get('/ajax/address_book/countrysubcodes/'+countryCode)
			.done(function (d) {
				var states = $('#address_state_2').empty();
				if(d)
				{	
					$('#state_2').show();
					$.each(d, function( code,name) {
				      states.append('<option value="' + code + '">' + name + '</option>');
				    });
				} else {
					$('#state_2').hide();
					states.append('<option value="0" selected="selected">Not Applicable</option>');
				}
			})
			.fail(function () {
				alert('Update of Country Sub Codes Failed');
			});
	});


	//Initiate on load
	
	//set for correctly
	$('#address_physical_pobox_1').change();
	$('#address_physical_pobox_2').change();
	$('#address_same_2').change();
	
	//fix initial state of states
	if( $('#state_1').has('option').length < 1 ) {
		$('#state_1').hide();
	};
	
	if( $('#state_2').has('option').length < 1 ) {
		$('#state_2').hide();
	};
	
});