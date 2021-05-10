$(document).ready(function(){
	$('#e_role_id').materialSelect();
    const table = $('#list_user').DataTable( {
        "processing": true,
		"pageLength": $('#list_user').data('limit'),
        "serverSide": true,
        "ajax": {
            "url": "/ajax/user/user/list",
            "type": "POST"
        },
        "columns": [
            { "data": "username" },
			{ "data": "email" },
			{ "data": "role_name"},
			{ "data": "last_login" },
			{ "data": "status" },
            { "data": null },
        ],
        "columnDefs": [
            {
                "render": function ( data, type, row ) {
					let html = `<a data-toggle="modal" class="change_roles_group" href="#" data-role_id="${row.role_id}" data-id="${row.user_id}">
									<i class="fas fa-edit" title="Change Roles"></i>
								</a>&nbsp;
								<a data-toggle="modal" class="change_password" href="#" data-id="${row.user_id}">
									<i class="fas fa-lock" title="Change Password"></i>
								</a>&nbsp;
								<a data-toggle="modal" id="delete_user" class="delete_user" href="#" data-id="${row.user_id}" data-email="${row.email}">
									<i class="far fa-trash-alt" title="Delete User"></i>
                                </a>`;
                    return html;
                },
                "targets": -1
            },
        ],
    } );
	var selectedId = 0;
    $('.dataTables_length').addClass('bs-select');
	
	$(document).on('click','#delete_user',function () 
	{
		let id = $(this).data('id');
		let email = $(this).data('email');

		swal.fire({
            title: "Delete Option ("+email+") ?",
			html: `
			<div class="row pt-2 pb-2">
				<div class="text-left col-lg-8 offset-lg-2">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="1" id="check_user">
						<label class="form-check-label" for="check_user">
							Delete User
						</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="1" id="check_ab">
						<label class="form-check-label" for="check_ab">
							Delete Address Book
						</label>
					</div>
				</div>
			</div>
			`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
			cancelButtonText: 'Cancel',
			focusConfirm: false,
            showLoaderOnConfirm: true,
            preConfirm: () => {
				let check_user = $('#check_user').is(':checked');
				let check_ab = $('#check_ab').is(':checked');
				if((!check_ab)&&(!check_user)) {
					Swal.showValidationMessage(
						`Please check at least one option delete!`
						)
				} else {
					return fetch(`/ajax/user/user/delete`,{
						headers: {
							"Content-Type": "application/json"
						  },
						method : 'POST',
						body : JSON.stringify({
							user_id: id,
							email: email,
							check_user: check_user,
							check_ab : check_ab
						  })
					})
					.then(response => {
					    if (!response.status) {
					        Swal.showValidationMessage(`Error could not delete user data.`)
					    }
					    return response.json()
					})
					.catch(error => {
					    Swal.showValidationMessage(
					    `Request failed: ${error}`
					    )
					})
				}
                
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    icon: 'success',
                    html: result.value.message,
				})
				if (result.value.status=='ok') {
					//reload data user
					table.ajax.reload();
				}
            }
        })
		
	})
	
	$(document).on('click','.change_password',function () 
	{
        const id = $(this).data('id');
        $('#password_modal').modal('show')
        $('#password_modal').find('[name=user_id]').first().val(id)
	})

	$(document).on('click','.change_roles_group',function () 
	{
		const id = $(this).data('id');
		const role_id = $(this).data('role_id');
		$('#group_roles_modal').modal('show')
		$('#group_roles_modal').find('#e_user_id').val(id);
		$('#group_roles_modal').find('#e_role_id').val(role_id);
		$('#group_roles_modal').find('#e_role_id').trigger('change');
	})
	
	//ajax to change user information
	$('.ajax_check_field').change(function()
	{
		//disable submit till we finish
		$('#add_user').attr('disabled',true);
		const meme = $(this);
		const title_name = meme.prop('name');
		const title_value = meme.val()
		
		if( title_value.length === 0 )
		{
			meme.next().text('This field can not be blank');	
		} else {
			$.ajax({
				url: "/ajax/user/test",
				type: 'POST',
				data: {
					nam: title_name,
					val: title_value
				},
				cache: false,
				timeout: 10000
			})
			.done(function(msg) {
				
				const answer = jQuery.parseJSON(msg);
				
				if(answer.good)
				{
					meme.val(answer.reply);
					meme.next().text('');

					//ok let submit work again
			        $('#add_user').removeAttr('disabled');
				} else {
					meme.next().text(answer.note);
				}
				
			})
			.fail(function() {
				Swal.fire({
					icon: 'error',
					text: 'Error, could not check the value',
				})
			});
		}
	});
	
	//ajax to change user information
	$('.pass_change').click(function(e)
	{	
		//stop it actually submitting
		e.preventDefault();
		
		//get the correct variables
		const id = $(this).val();
		const passNew = $("#password-new");
		const passConfirm = $("#password-confirm");
		const new_val = passNew.val();
		const confirm_val = passConfirm.val();
		const modal = $("#password_modal");
		
		//let's do some error checking
		if(new_val && confirm_val)
		{
			if(new_val == confirm_val)
			{
				if(new_val.length > 5)
				{
					//ok good to go
					$.ajax({
						url: "/ajax/user/pass",
						type: 'POST',
						data: {
							user_id: id,
							new_pas: new_val
						},
						cache: false,
						timeout: 10000
					})
					.done(function(msg) 
					{
						var answer = jQuery.parseJSON(msg);
						if(answer.good)
						{
							//clear values
							passNew.val('');
							passConfirm.val('');
							//close the modal
							modal.modal('toggle');
                            Swal.fire({
                                icon: 'success',
                                text: answer.message,
                            })
						} else {
							Swal.fire({
                                icon: 'error',
                                text: answer.note,
                            })
						}
					})
					.fail(function() {
						Swal.fire({
							icon: 'error',
							text: "Error could not update the password.",
						})
					});
				} else {
					Swal.fire({
						icon: 'warning',
						text: 'The password must be 6 or more characters long.',
					})
				}
			} else {
				Swal.fire({
					icon: 'warning',
					text: 'Confirm password value must equal password value',
				})
			}
		} else {
			Swal.fire({
				icon: 'warning',
				text: 'You must fill in both of the fields.',
			})
		}
		return;
	});

	//ajax to change user group and roles
	$('.change_security_level').click(function(e)
	{	
		//stop it actually submitting
		e.preventDefault();
		
		//get the correct variables
		const id = $('#e_user_id').val();
		const security_level_id = $('#e_security_level_id').val();

		const modal = $("#group_roles_modal");
		
		//let's do some confirmation
		swal.fire({
            title: 'User ecurity level',
            text: 'Change user security level to '+$('#e_security_level_id option:selected').html()+'?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Change !'
        }).then((result) => {
            if(result.value)
            {
				//ok good to go
				$.ajax({
					url: "/ajax/user/user/update/security_level",
					type: 'POST',
					data: {
						user_id: id,
						security_level_id: security_level_id
					},
					cache: false,
					timeout: 10000
				})
				.done(function(response) 
				{
					Swal.fire({
						icon: 'success',
						title: 'Great!',
						text: response.message
					});
					modal.modal('hide');
					table.ajax.reload();
				})
				.fail(function(response) {
					if(response.status == 400)
					{
						text = ''
						$.each(response.responseJSON.errors, (index,item) => {
							text += item;
						})
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: text
						});
					}else{
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: 'Something errors. Please contact admin support!'
						});
					}
				});
			}
		});
				
		return;
	});

	$('.change_role').click(function(e)
	{	
		
		//get the correct variables
		const id = $('#e_user_id').val();
		const role_id = $('#e_role_id').val();
		const modal = $("#group_roles_modal");
		
		//let's do some confirmation
		swal.fire({
            title: 'User Role',
            text: 'Change user role to '+$('#e_role_id option:selected').html()+'?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Change !'
        }).then((result) => {
            if(result.value)
            {
				//ok good to go
				$.ajax({
					url: "/ajax/user/user/update/role",
					type: 'POST',
					data: {
						user_id: id,
						role_id: role_id
					},
					cache: false,
					timeout: 10000
				})
				.done(function(response) 
				{
					Swal.fire({
						icon: 'success',
						title: 'Great!',
						text: response.message
					});
					modal.modal('hide');
					$('#e_role_id').val('');
					$('#e_role_id').trigger('change');
					table.ajax.reload();
					
				})
				.fail(function(response) {
					if(response.status == 400)
					{
						text = ''
						$.each(response.responseJSON.errors, (index,item) => {
							text += item;
						})
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: text
						});
					}else{
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: 'Something errors. Please contact admin support!'
						});
					}
				});
			}
		});
				
		return;
	});
	

	$('#add_user_btn').on('click',function()
	{
		$('#add_user_form').modal('show')
	});

	$('#country').materialSelect();

	//show the country info
	$( "#country" ).change(function()
	{
		$('#allowed').hide();

		const infoClass =	$( "#country option:selected" ).attr("class");
		$( ".country-info").hide();
		$( "#"+infoClass).show();

		if(infoClass !== 'default')
		{
			$('#allowed').fadeIn();
		}
	});

	
	//set up the date picker for dob
    $('#dob').pickadate(
	{
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
        selectYears: true,
		min: new Date($('#dob').data('min-date')),
		max: new Date($('#dob').data('max-date')),
		format: 'yyyy-mm-dd',
    })

	$('#add_address_book').on('change',function()
	{
    	$('#address_book_area').toggleClass('not-showing')
    });

	$('#add_user_form').on('submit', function()
	{
    	let rs = true;
    	const add_address_book = $("#add_address_book").is(':checked');

		if (add_address_book)
		{
    		const title = $('#title').val();
			if (title == '')
			{
    			$('#title').focus()
    			rs = false
			}
			
	    	const given_name = $('#given_name').val();
			if (given_name == '')
			{
	    		$('#given_name').focus()
    			rs = false
			}
			
	    	const sex = $("input[name='sex']:checked").val();
			if (sex == '' || sex == undefined)
			{
	    		$("input[name='sex']").focus()
    			rs = false
    		}

    		const dob = $("#dob").val();
	    	if (dob == ''){
	    		$("#dob").focus()
    			rs = false
    		}
    	}
    
    	return rs;
    })



});