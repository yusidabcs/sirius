$(document).ready(function()
{	
	//select mdb
	//$('#type').materialSelect();
	
	//show the correct items for the address book based on type
	$('#type').change(function() {
		var type = $(this).val();
		if(type == 'per')
		{
			$('.per').show();
			$('.ent').hide();
			
			//fix layout
			$("#ent_fam_div").removeClass('col-12');
			$("#ent_fam_div").addClass('col-lg-8');
			
			$("#num_giv_div").removeClass('col-12');
			$("#num_giv_div").addClass('col-lg-6');
			
		} else {
			$('.per').hide();
			$('.ent').show();
			
			//fix layout
			$("#ent_fam_div").removeClass('col-lg-8');
			$("#ent_fam_div").addClass('col-12');
			
			$("#num_giv_div").removeClass('col-lg-6');
			$("#num_giv_div").addClass('col-12');
			
		}
	});
	
	$('#main_email').change(function() {
		var email = $(this).val();
		if(email == '')
		{
			$('#allow_contact_email_div').hide();
		} else {
			$('#allow_contact_email_div').show();
		}
	});
		
	$( "form" ).submit(function() {
		
		if($('#type').val() == 'ent')
		{
			if($('#entity_family_name').val() == '')
			{
				swal.fire('Stop!','The organisation name can not be blank','error');
				return false;
			}
		} else {
			if($('#number_given_name').val() == '')
			{
				swal.fire('Stop!','The given name for a person can not be blank','error');
				return false;
			}
		}
		
		if($('#email_required').val() == 1 && $('#main_email').val() == '' )
		{
			swal.fire('Stop!','We are requried to have an email for every address book entry.','error');
			return false;
		}
		
		return true;
	});
	
	//initiate when we first open
	$('#type').change();
	
	//fix up email
	$('#main_email').change();
	
	//set up the date picker for dob
    $('#dob').pickadate({
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
        selectYears: 100,
        min: new Date($('#dob').data('min-date')),
        max: new Date($('#dob').data('max-date')),
        formatSubmit: 'yyyy-mm-dd',
        format: 'yyyy-mm-dd',
    })

	$('#ab_add_contact_btn').click(function () {
		$('#ab_add_contact_modal').modal('show');
    });

    $('#ab_contact_check_email').on('click', function () {
		var email = $('#ent_admin_email').val()
        var btn = $(this)
        btn.attr('disabled', true)
        //post off the leaf for data
        $.post('/ajax/address_book/main/contactEmailCheck', {
            email: email
        })
            .done(function (d) {
                swal.fire(d.heading, d.message, d.level);

                if(d.per_address_book_id == 0 && d.level != 'error'){
                	$('#ab_contact_info').show()
                    $('#ab_contact_exist').hide()
				}else if(d.per_address_book_id != 0 && d.level == 'success'){
                	$('#ab_contact_exist').show();
                    $('#ab_contact_info').hide()
                	$('#ab_contact_exist').prepend('<p>Email: '+email+'</p>');
                	$('#address_book_per_id').val(d.per_address_book_id);
				}
                btn.attr('disabled', false)

            })
            .fail(function () {
                swal.fire('Connection Failed', 'The check could not be done because we could not talk to the server.', 'error');

                $('button[type="submit"]').prop("disabled",false);
                btn.attr('disabled', false)
            });

    });

    $('#link_ab_entity').on('click', function () {
    	var address_book_per_id = $('#ab_contact_exist #address_book_per_id').val()
    	var address_book_ent_id = $('#address_book_id').val()
    	var person_type = $('#ab_contact_exist select[name=person_type]').val()
    	var role_id = $('#ab_contact_exist select[name=role_id]').val()
        var btn = $(this)
        btn.attr('disabled',true);
        $.post('/ajax/address_book/main/linkAddressBookEntity', {
            address_book_per_id: address_book_per_id,
            address_book_ent_id: address_book_ent_id,
            person_type: person_type,
            role_id: role_id,
            security_level_id: 'NONE'
        })
            .done(function (d) {

            	if(d.success){
                    $('#ab_add_contact_modal').modal('hide');
                    btn.attr('disabled',false);
                    location.reload();
				}else{
                    swal.fire('Warning!', d.message, 'error');
				}

            })
            .fail(function () {
                swal.fire('Connection Failed', 'The check could not be done because we could not talk to the server.', 'error');
                btn.attr('disabled',false);
            });
    	return false

    });
    $('#ent_admin_dob').pickadate({
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
        selectYears: true,
        min: new Date($('#ent_admin_dob').data('min-date')),
        max: new Date($('#ent_admin_dob').data('max-date')),
        formatSubmit: 'yyyy-mm-dd',
        format: 'yyyy-mm-dd',
    })
    $('#create_ab_entity').on('click', function () {
        var address_book_ent_id = $('#address_book_id').val()
        var email = $('#ent_admin_email').val()
        var title = $('#ent_admin_new_details #ent_admin_title').val()
        var family_name = $('#ent_admin_new_details #ent_admin_family_name').val()
        var given_name = $('#ent_admin_new_details #ent_admin_given_name').val()
        var middle_names = $('#ent_admin_new_details #ent_admin_middle_names').val()
        var dob = $('#ent_admin_new_details #ent_admin_dob').val()
        var sex = $('#ent_admin_new_details input[name=sex]:checked').val()
        var person_type = $('#ent_admin_new_details select[name=person_type]').val()
        var role_id = $('#ent_admin_new_details select[name=role_id]').val()
        var contact_allowed = $('#ent_admin_new_details input[name=contact_allowed]').val()
        var add_new_user = $('#ent_admin_new_details input[name=add_new_user]').val()
        var send_new_user_email = $('#ent_admin_new_details input[name=send_new_user_email]').val()
        var btn = $(this)

        if(email == ''){
            swal.fire('Warning!', 'Email is empty', 'error');
            $('#ent_admin_email')[0].checkValidity();
            return;
        }
        if(title == ''){
            swal.fire('Warning!', 'Title is empty', 'error');
            $('#ent_admin_new_details #ent_admin_title')[0].checkValidity();
            return;
        }
        if(given_name == ''){
            swal.fire('Warning!', 'Given name is empty', 'error');
            $('#ent_admin_new_details #ent_admin_title')[0].checkValidity();
            return;
        }
        if(dob == ''){
            swal.fire('Warning!', 'Dob is empty', 'error');
            $('#ent_admin_new_details #ent_admin_title')[0].checkValidity();
            return;
        }
        if(sex == undefined){
            swal.fire('Warning!', 'Sex is empty', 'error');
            $('#ent_admin_new_details #ent_admin_title')[0].checkValidity();
            return;
        }
        if(person_type == ''){
            swal.fire('Warning!', 'Person Type is empty', 'error');
            $('#ent_admin_new_details #ent_admin_title')[0].checkValidity();
            return;
        }

        if(window.confirm('is data correct?')){
            btn.attr('disabled',true)
            $.post('/ajax/address_book/main/addAddressBookAdminLink', {
                address_book_ent_id: address_book_ent_id,
                email: email,
                title: title,
                family_name: family_name,
                given_name: given_name,
                middle_names: middle_names,
                dob: dob,
                sex: sex,
                person_type: person_type,
                role_id: role_id,
                security_level_id: 'NONE',
                contact_allowed: contact_allowed,
                add_new_user: add_new_user,
                send_new_user_email: send_new_user_email,
            })
                .done(function (d) {

                    if(d.success){
                        location.reload();
                    }else{
                        swal.fire('Warning!', d.message, 'error');
                    }
                    btn.attr('disabled',true)

                })
                .fail(function () {
                    swal.fire('Connection Failed', 'The check could not be done because we could not talk to the server.', 'error');
                    btn.attr('disabled',false);
                });
        }

        return false
    })

    $('.ab_delete_contact_btn').click(function () {
		if(window.confirm('Delete this data?')){
            $.post('/ajax/address_book/main/deleteAddressBookAdminLink', {
                address_book_per_id: $(this).data('address_book_per_id'),
                address_book_ent_id: $('#address_book_id').val(),
            })
                .done(function (d) {

                    if(d.success){
                        location.reload();
                    }else{
                        swal.fire('Warning!', d.message, 'error');
                    }

                })
                .fail(function () {
                    swal.fire('Connection Failed', 'The check could not be done because we could not talk to the server.', 'error');
                    btn.attr('disabled',false);
                });
           
		}
         return false
    });

    $('#change_email').on('change', function(e) {
        var main_email = $('#main_email');

        if (e.target.checked) {
            main_email.removeAttr('disabled');
            main_email.focus();
        } else {
            main_email.attr('disabled', true);
        }
    });

	
});