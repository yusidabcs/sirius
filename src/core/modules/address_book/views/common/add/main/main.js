$(document).ready(function()
{

    //set up the date picker for dob
    $('.dob').pickadate({
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
        selectYears: 100,
        min: new Date($('#dob').data('min-date')),
        max: new Date($('#dob').data('max-date')),
        formatSubmit: 'yyyy-mm-dd',
    })

	//select mdb
	$('#type').materialSelect();
	$('#sex').materialSelect();
	$('#ent_admin_sex').materialSelect();
	
	$('#main_email').change(function() {
		
		var main_email = $(this).val();
		var type = $('#type').val();
		
		//default settings
		$('#user_email_group').removeClass( "has-success" ).removeClass( "has-warning" ).removeClass( "has-error" );
		$('#user_result').removeClass( "glyphicon-ok" ).removeClass( "glyphicon-warning-sign" ).removeClass( "glyphicon-remove" );
		$('#per_address_book_id').val(0);

		if(main_email == '')
		{
			$('#allow_contact_email_div').hide();
			$('#main_add_user_per').hide();
					
			if(type == 'ent')
			{
				$('#ent_admin').show();
				$('#ent_admin_same_email').prop( "checked", false);
				$('#ent_admin_same_email_div').hide();
				$('#ent_admin_email_div').show();
				$('#ent_admin_email').change();
				
			} else {
				$('#ent_admin').hide();
			}
			
			//no contact because it is blank
			$('#contact_allowed').prop( "checked", false );
			
			//turn them off for the ent_admin
            $('#add_new_user').prop( "checked", false );
            $('#send_new_user_email').prop( "checked", false );
			
		} else if(type == 'ent' && main_email == $('#ent_admin_email').val()) {
			
			$('#allow_contact_email_div').show();
			$('#ent_admin').show();
			$('#ent_admin_same_email').prop( "checked", true );
			$('#ent_admin_same_email_div').show();
			$('#ent_admin_email').val('');
			$('#ent_admin_email_div').hide();
			$('#main_email').change();
		
		} else {
			
			//post off the leaf for data
			$.post('/ajax/address_book/main/mainEmailCheck', {
				type: type,
				main_email: main_email
			})
			.done(function (d) {
				
				if(d.heading != '' && d.message != '' && d.level != '')
				{
					swal.fire(d.heading, d.message, d.level);
				}
				
				if(d.level == 'success') 
				{
					$('#user_email_group').addClass( "has-success" );
					$('#user_result').addClass( "glyphicon-ok" );
					
					$('#allow_contact_email_div').show();
					
					$('#contact_allowed').prop( "checked", true );
					
					$('#per_address_book_id').val(d.per_address_book_id);
					
					if($('#type').val() == 'ent')
					{
						//entity
						
						$('#main_add_user_per').hide();
						$('#ent_admin').hide();
						
						//always turn off the values for person
						$('#add_new_user').prop( "checked", false );
			            $('#send_new_user_email').prop( "checked", false );
			            
			            //and turn them off for the ent_admin
			            $('#ent_admin_add_new_user').prop( "checked", false );
	                    $('#ent_admin_send_new_user_email').prop( "checked", false );
						
					} else {
						
						//person
						
						if(d.showAdd)
						{
							$('#main_add_user_per').show();
							$('#ent_admin').hide();
							
							//always turn off the values for person
							$('#add_new_user').prop( "checked", true );
				            $('#send_new_user_email').prop( "checked", true );
				            
				            //and turn them off for the ent_admin
				            $('#ent_admin_add_new_user').prop( "checked", false );
		                    $('#ent_admin_send_new_user_email').prop( "checked", false );
							
						} else {
							$('#main_add_user_per').hide();
							$('#ent_admin').hide();
							
							//always turn off the values for person
							$('#add_new_user').prop( "checked", false );
				            $('#send_new_user_email').prop( "checked", false );
				            
				            //and turn them off for the ent_admin
				            $('#ent_admin_add_new_user').prop( "checked", false );
		                    $('#ent_admin_send_new_user_email').prop( "checked", false );
						}
					}
				}
				
				else if(d.level == 'warning')
				{
					$('#user_email_group').addClass( "has-warning" );
					$('#user_result').addClass( "glyphicon-warning-sign" );
					$('#allow_contact_email_div').show();
					$('#contact_allowed').prop( "checked", true );

					if($('#type').val() == 'ent')
					{
						
						//entity
						
						$('#main_add_user_per').hide();
						$('#ent_admin').show();
						
						if(d.sameRequired)
						{
							$('#ent_admin_same_email_div').hide();
						} else {
							$('#ent_admin_same_email_div').show();
						}
						
						if(d.showAdd)
						{
							$('#ent_admin_send_new_user_div').show();
							
							//always turn off the values for person
							$('#add_new_user').prop( "checked", false );
				            $('#send_new_user_email').prop( "checked", false );
				            
				            //and turn them off for the ent_admin
				            $('#ent_admin_add_new_user').prop( "checked", true );
		                    $('#ent_admin_send_new_user_email').prop( "checked", true );
		                    
						} else {
							$('#ent_admin_send_new_user_div').hide();
							
							//always turn off the values for person
							$('#add_new_user').prop( "checked", false );
				            $('#send_new_user_email').prop( "checked", false );
				            
				            //and turn them off for the ent_admin
				            $('#ent_admin_add_new_user').prop( "checked", false );
		                    $('#ent_admin_send_new_user_email').prop( "checked", false );
						}
						
						if($('#ent_admin_email').val() == '')
						{
							$('#ent_admin_same_email').prop( "checked", true );
							$('#ent_admin_same_email_div').show();
							$('#ent_admin_email').val('');
							$('#ent_admin_email_div').hide();
							$('#ent_admin_check_boxes').show();
						} else {
							$('#ent_admin_same_email').prop( "checked", false );
							$('#ent_admin_same_email').change();
							$('#ent_admin_email').change();
						}

					} else {
						
						//person
						
						if(d.showAdd)
						{
							$('#main_add_user_per').show();
							$('#ent_admin').hide();
							
							//always turn off the values for person
							$('#add_new_user').prop( "checked", true );
				            $('#send_new_user_email').prop( "checked", true );
				            
				            //and turn them off for the ent_admin
				            $('#ent_admin_add_new_user').prop( "checked", false );
		                    $('#ent_admin_send_new_user_email').prop( "checked", false );
		                    
		                    
						} else {
							$('#main_add_user_per').hide();
							$('#ent_admin').hide();
							
							//always turn off the values for person
							$('#add_new_user').prop( "checked", false );
				            $('#send_new_user_email').prop( "checked", false );
				            
				            //and turn them off for the ent_admin
				            $('#ent_admin_add_new_user').prop( "checked", false );
		                    $('#ent_admin_send_new_user_email').prop( "checked", false );
						}
					}
					
				}
				
				else if(d.level == 'error')
				{
					$('#user_email_group').addClass( "has-error" );
					$('#user_result').addClass( "glyphicon-remove" );
					$('#allow_contact_email_div').hide();
					$('#contact_allowed').prop( "checked", false );
					$('#main_add_user_per').hide();
					
					//always turn off the values for person
					$('#add_new_user').prop( "checked", false );
		            $('#send_new_user_email').prop( "checked", false );
		            
		            //and turn them off for the ent_admin
		            $('#ent_admin_add_new_user').prop( "checked", false );
                    $('#ent_admin_send_new_user_email').prop( "checked", false );
					
					if($('#type').val() == 'ent')
					{
						$('#ent_admin').show();
						$('#ent_admin_same_email').prop( "checked", false );
						$('#ent_admin_same_email_div').hide();
						$('#ent_admin_email_div').show();
						$('#ent_admin_new_details').show();
						$('#ent_admin_email').change();
					} else {
						$('#ent_admin').hide();
					}
				}
				
			})
			.fail(function () {
				swal.fire('Connection Failed', 'The check could not be done because we could not talk to the server.', 'error');
			});
		}
		
	});

	$('#ent_admin_same_email').change(function() {
		
		//reset the value of the per_address_book_id
		$('#ent_admin_per_address_book_id').val(0);
		
		if($(this).prop( "checked" ))
		{
			$('#main_email').change();
		} else {
			$('#ent_admin_email_group').removeClass( "has-success" ).removeClass( "has-warning" ).removeClass( "has-error" );
			$('#ent_admin_email_result').removeClass( "glyphicon-ok" ).removeClass( "glyphicon-warning-sign" ).removeClass( "glyphicon-remove" );
			$('#ent_admin_email_div').show();
			$('#ent_admin_check_boxes').hide();
		}
	});

	$('#ent_admin_email').change(function() {
		
		var ent_admin_email = $(this).val();
		
		$('#ent_admin_email_group').removeClass( "has-success" ).removeClass( "has-warning" ).removeClass( "has-error" );
		$('#ent_admin_email_result').removeClass( "glyphicon-ok" ).removeClass( "glyphicon-warning-sign" ).removeClass( "glyphicon-remove" );
		$('#ent_admin_same_email').prop( "checked", false );
		$('#ent_admin_per_address_book_id').val(0);
		
		
		if(ent_admin_email == '') {
			if($('#main_email').val() != '' && $('#user_email_group').hasClass( "has-warning" ))
			{
				$('#ent_admin_same_email_div').show();
			}
			$('#ent_admin_new_details').show();
			$('#ent_admin_check_boxes').hide();
			
			//no contact because it is blank
			$('#ent_admin_contact_allowed').prop( "checked", false );
			
			//turn them off for the ent_admin
            $('#ent_admin_add_new_user').prop( "checked", false );
            $('#ent_admin_send_new_user_email').prop( "checked", false );
			
					
		} else if(ent_admin_email == $('#main_email').val() ) {
			
			$('#ent_admin_same_email').prop( "checked", true );
			$('#ent_admin_same_email_div').show();
			$('#ent_admin_email').val('');
			$('#ent_admin_email_div').hide();
			$('#ent_admin_new_details').show();
			$('#main_email').change();
			
		} else {
			
			//post off the leaf for data
			$.post('/ajax/address_book/main/adminEmailCheck', {
				admin_email: ent_admin_email
			})
			.done(function (d) {
				
				if(d.heading != '' && d.message != '' && d.level != '')
				{
					swal.fire(d.heading, d.message, d.level);
				}
				
				if(d.level == 'success') 
				{
					$('#ent_admin_email_group').addClass( "has-success" );
					$('#ent_admin_email_result').addClass( "glyphicon-ok" );
					
					$('#ent_admin_same_email_div').hide();
					$('#ent_admin_new_details').hide();
					$('#ent_admin_check_boxes').hide();
					
					$('#ent_admin_per_address_book_id').val(d.per_address_book_id);
					
		            //turn them off for the ent_admin
		            $('#ent_admin_add_new_user').prop( "checked", false );
                    $('#ent_admin_send_new_user_email').prop( "checked", false );
				}
				
				if(d.level == 'warning') 
				{
					$('#ent_admin_email_group').addClass( "has-warning" );
					$('#ent_admin_email_result').addClass( "glyphicon-warning-sign" );
					
					$('#ent_admin_same_email_div').hide();
					$('#ent_admin_new_details').show();
					$('#ent_admin_check_boxes').show();
					
					if(d.showAdd)
					{
						$('#ent_admin_send_new_user_div').show();
						
						//turn them off for the ent_admin
			            $('#ent_admin_add_new_user').prop( "checked", true );
	                    $('#ent_admin_send_new_user_email').prop( "checked", true );
                    
					} else {
						$('#ent_admin_send_new_user_div').hide();
						
						//turn them off for the ent_admin
			            $('#ent_admin_add_new_user').prop( "checked", false );
	                    $('#ent_admin_send_new_user_email').prop( "checked", false );
					}
					
				}
				
				if(d.level == 'error') 
				{
					$('#ent_admin_email_group').addClass( "has-error" );
					$('#ent_admin_email_result').addClass( "glyphicon-remove" );
					
					if($('#main_email').val() != '' && $('#user_email_group').hasClass( "has-warning" ))
					{
						$('#ent_admin_same_email_div').show();
					}
					
					$('#ent_admin_new_details').show();
					$('#ent_admin_check_boxes').hide();
					
					//turn them off for the ent_admin
		            $('#ent_admin_add_new_user').prop( "checked", false );
                    $('#ent_admin_send_new_user_email').prop( "checked", false );
				}
				
			})
			.fail(function () {
				swal.fire('Connection Failed', 'The check could not be done because we could not talk to the server.', 'error');
			});
			
		}
		
	});

	$('#checkMainDetails').click(function() {
		
		$('button[type="submit"]').prop("disabled",true);
		
		var type  = $('#type').val();
		var entity_family_name = $('#entity_family_name').val();
		var number_given_name = $('#number_given_name').val();
		var middle_names = $('#middle_names').val();
		var dob = $('#dob').val();
		var sex = $('#sex').val();
		var main_email = $('#main_email').val();
		
		//post off the leaf for data
		$.post('/ajax/address_book/main/userNameCheck', {
			type: type,
			entity_family_name: entity_family_name,
			number_given_name: number_given_name,
			middle_names: middle_names,
			dob: dob,
			sex: sex,
			address_book_id: 0,
			main_email: main_email
		})
		.done(function (d) {
			swal.fire(d.heading, d.message, d.level);
			
			if(d.level == 'error')
			{
				$('button[type="submit"]').prop("disabled",true);
			} else {
				$('button[type="submit"]').prop("disabled",false);
			}
			
		})
		.fail(function () {
			swal.fire('Connection Failed', 'The check could not be done because we could not talk to the server.', 'error');
			
			$('button[type="submit"]').prop("disabled",false);
		});
		
	});

	$('#checkAdminDetails').click(function() {
		
		$('button[type="submit"]').prop("disabled",true);
		
		var type  = 'per';
		var entity_family_name = $('#ent_admin_family_name').val();
		var number_given_name = $('#ent_admin_given_name').val();
		var middle_names = $('#ent_admin_middle_names').val();
		var dob = $('#ent_admin_dob').val();
		var sex = $('#ent_admin_sex').val();
		var main_email = $('#ent_admin_email').val();
		
		//post off the leaf for data
		$.post('/ajax/address_book/main/userNameCheck', {
			type: type,
			entity_family_name: entity_family_name,
			number_given_name: number_given_name,
			middle_names: middle_names,
			dob: dob,
			sex: sex,
			address_book_id: 0,
			main_email: main_email
		})
		.done(function (d) {
			swal.fire(d.heading, d.message, d.level);
			
			if(d.level == 'error')
			{
				$('button[type="submit"]').prop("disabled",true);
			} else {
				$('button[type="submit"]').prop("disabled",false);
			}
			
		})
		.fail(function () {
			swal.fire('Connection Failed', 'The check could not be done because we could not talk to the server.', 'error');
			
			$('button[type="submit"]').prop("disabled",false);
		});
		
	});
	
	$('#add_new_user').change(function() {
		
		if($(this).prop( "checked" ))
		{
			$('#send_new_user_email').prop( "checked", true );
			$('#send_new_user_email_div').show();
		} else {
			$('#send_new_user_email').prop( "checked", false );
			$('#send_new_user_email_div').hide();
		}
	})
	
	$('#ent_admin_add_new_user').change(function() {
		if($(this).prop( "checked" ))
		{
			$('#ent_admin_send_new_user_email').prop( "checked", true );
			$('#ent_admin_send_new_user_email_div').show();
		} else {
			$('#ent_admin_send_new_user_email').prop( "checked", false );
			$('#ent_admin_send_new_user_email_div').hide();
		}
	})
	
	$("form").submit(function() {
		
		//do not check if we are using an existing client the form MUST use these id's
		if( $('#existing_client_selected_name').val() != '' && $('#existing_client_selected_id').val() > 0  )
		{
			return;
		}
		
		var type = $('#type').val();
		var title = $('#title').val();
		var dob = $('#dob').val();
		var entity_family_name = $('#entity_family_name').val();
		var number_given_name = $('#number_given_name').val();
		var main_email = $('#main_email').val();
		var user_email_group = $('#user_email_group');
		var ent_admin_given_name = $('#ent_admin_given_name').val();
		var per_address_book_id = $('#per_address_book_id').val();
		var ent_admin_per_address_book_id = $('#ent_admin_per_address_book_id').val();
		var email_required = $('#email_required').val();
		var ent_admin_email = $('#ent_admin_email').val();
		var ent_admin_email_group = $('#ent_admin_email_group');
		var ent_admin_same_email = $('#ent_admin_same_email');
		
		if(type == 'ent') //entity
		{
			//entities must have a name
			if(entity_family_name == '')
			{
				swal.fire('Blank Organisation Name','The organisation name can not be blank','error');
				return false;
			}
			
			//the contact person needs a first name always
			if(per_address_book_id == 0 && ent_admin_per_address_book_id == 0)
			{
				if(ent_admin_given_name == '')
				{
					swal.fire('Contact Person Given Name','The Key Contact Person must have at least a given name and can not be blank','error');
					return false;
				}
			}
			
			//contact person is not using the same email
			if(!ent_admin_same_email.prop( "checked" )) 
			{
				//if an email is requried then it must be set
				if(email_required == 1)
				{
					if(ent_admin_email == '')
					{
						swal.fire('No Key Person Email!','You need to add an email for the Key Contact Person.','error');
						return false;
						
					}
				}
				
				//their email address can not have errors
				if(ent_admin_email_group.hasClass( "has-error" )) 
				{
					swal.fire('Email Error','Need a valid email address for the Key Contact Person.','error');
					return false;
				}
			}
										
		} else { //person
			
			//people must have a name
			if(number_given_name == '')
			{
				swal.fire('Blank Given Name','The given name for a person can not be blank','error');
				return false;
			}
            if(main_email == '')
            {
                swal.fire('Blank Email','The main email for person can not be blank','error');
                return false;
            }
            if(title == '')
            {
                swal.fire('Blank Title','The title for person can not be blank','error');
                return false;
            }
		}
		
		//common to both if email is required then it can not be blank
		if(email_required == 1)
		{
			if(main_email = '' )
			{
				swal.fire('No Email','We requried a valid email address before we can continue.','error');
				return false;
			}
		}
		
		//we can not accept a main email that is an error
		if(user_email_group.hasClass( "has-error" ) )
		{
			swal.fire('Email Error','There is an error with the main email that must be corrected before submitting.','error');
			return false;
		}
		
		return true;
	});
	
	$('#type').change(function() {
		var type = $(this).val();
		if(type == 'per')
		{
			$('.per').show();
			$('.ent').hide();
		} else {
			$('.per').hide();
			$('.ent').show();
		}
		
		if( $('#main_email').val().length > 0 )
		{
			$('#main_email').change();
		} 

	});
	
	/* things that start when the pages loads */
	
	$('#type').change();
	
	$('#ent_admin_same_email').change();
	
	if( $('#main_email').val().length > 0 )
	{
		$('#main_email').change();
	} 
	
	if( $('#ent_admin_email').val().length > 0 )
	{
		$('#ent_admin_email').change();
	}
	
	//set up the date picker for dob
	//$(".dob").flatpickr();
	
	
	
});