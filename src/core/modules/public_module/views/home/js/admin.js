$(document).ready(function(){
	$('.mdb-select').materialSelect();

    const table = $('#list_job').DataTable( {
        "processing": true,
		"pageLength": $('#list_job').data('limit'),
        "serverSide": true,
        "ajax": {
            "url": "/ajax/job/listjob",
            "type": "POST"
        },
        "columns": [
            { "data": "principal_id" },
            { "data": "branch_id" },
            { "data": "cost_id" },
            { "data": "job_code" },
			{ "data": "job_description" },
			{ "data": "minimum_salary" },
			{ "data": "mid_salary" },
			{ "data": "max_salary" },
			{ "data": "created_at" },
            { "data": null },
        ],
        "columnDefs": [
            {
                "render": function ( data, type, row ) {
                    var html = '<div class="container row"><a data-toggle="modal" class="col-sm-6 jobm_edit " href="#">\n' +
                        '             <i class="fa fa-edit btn-sm " title="Edit Data"></i>\n' +
                        '      </a><a data-toggle="modal" class="col-sm-6 jobm_delete " href="#">\n' +
                        '             <i class="fa fa-times btn-sm btn-danger" title="Delete Data"></i>\n' +
                        '      </a><div>';
                    return html;
                },
                "targets": -1
            }
        ],
    } );

    $('.dataTables_length').addClass('bs-select');
    
    $(document).on('click','.add_new_jobm',function () {
		//insert modal
        $('#new_jobm_modal').modal('show')
	})
	
	$('#list_job tbody').on( 'click', '.jobm_edit', function () {
		const data = table.row(this.closest('tr')).data();

		//edit modal
		
		$('#edit_jobm_modal').modal('show');
		//fill data
		$('#e_jobm_id').val(data.id);
		$('#e_principal_id').val(data.principal_id).change();
		$('#e_job_code').val(data.job_code).change();
		$('#e_branch_id').val(data.branch_id).change();
		$('#e_cost_id').val(data.cost_id).change();
		$('#e_job_code').val(data.job_code).change();
		$('#e_job_description').val(data.job_description).change();
		$('#e_minimum_salary').val(data.minimum_salary).change();
		$('#e_mid_salary').val(data.mid_salary).change();
		$('#e_max_salary').val(data.max_salary).change();

	} );

	/*$(document).on('click','.jobm_edit',function () {
		//edit modal
		const id = $(this).data('id');
		$('#e_jobm_id').val(id);
		//set all data
		console.log(table.row);
		$('#edit_jobm_modal').modal('show');
		
	})*/
	

    //editable
	/*$('.editable').jipor({
		replyProcess: 1
	})
	    .on('jipor:fail', function(ev, jqxhr, textStatus, errorThrown) {
	        alert('Failed! ' + errorThrown)
	});*/
	
	//ajax to change user information
	$('.ajax_check_field').change(function(){
		//disable submit till we finish
		$('#add_user').attr('disabled', 'disabled');
		var meme = $(this);
		var title_name = meme.prop('name');
		var title_value = meme.val()
		
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
				
				var answer = jQuery.parseJSON(msg);
				
				if(answer.good)
				{
					meme.val(answer.reply);
					meme.next().text('');
				} else {
					meme.next().text(answer.note);
				}
				
				//ok let submit work again
		        $('#add_user').removeAttr('disabled');
			})
			.fail(function() {
				alert( "Error could not check the value" );

                $('#add_user').removeAttr('disabled');
			});
		}
	});
	
	//ajax to change user information
	$('.pass_change').click(function(e){
		
		//stop it actually submitting
		e.preventDefault();
		
		//get the correct variables
		var id = $(this).val();
		var passNew = $("#password-new");
		var passConfirm = $("#password-confirm");
		var new_val = passNew.val();
		var confirm_val = passConfirm.val();
		var modal = $("#password_modal");
		
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
					.done(function(msg) {
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
							alert(answer.note);
						}
					})
					.fail(function() {
						alert( "Error could not update the password." );
					});
				} else {
					alert('The password must be 6 or more characters long.');
				}
			} else {
				alert('The two fields are not the same.');
			}
		} else {
			alert('You must fill in both of the fields.');
		}
		return;
	});

	$("#form_edit").submit(function(e) {
		$('input[name=action]').val('edit');
	});
	$("#form_insert").submit(function(e) {
        //form check data
        
        var message = '';
        $('#principal_id').val(Math.floor(Math.random() * 3)+1);
        $('#branch_id').val(Math.floor(Math.random() * 3)+1);
        $('#cost_id').val(Math.floor(Math.random() * 3)+1);
        $('#job_code').val('JC'+Math.floor(Math.random() * 3)+1);
        $('#job_description').val('JDESC'+Math.floor(Math.random() * 3)+1);
        $('#minimum_salary').val(Math.floor(Math.random() * 3)*1000);
        $('#mid_salary').val((Math.floor(Math.random() * 3)+1)*1000);
        $('#max_salary').val((Math.floor(Math.random() * 3)+2)*1000);
		$('input[name=action]').val('insert');
		
        // e.preventDefault();
        
		/*
		//country
		const country =	$( "#country option:selected" ).val();
		if(country == 'not specified')
		{ 
			message += "<tr><th>Country Required</th><td>Please select a country</td></tr>";
		}
		
		//given name
		var given_name = $('#given_name').val();
		if(given_name == '') {
			message += "<tr><th>Given Name</th><td>If you only have one name put it in the Given Name field please</td></tr>";
		}
				
		//dob
		var dob =	$("#dob").val();
		if(dob == '') {
			message += "<tr><th>Date of Birth</th><td>Please fill in your date of birth</td></tr>";
		}
		
		//sex
		var sex =	$( 'input[name=sex]:checked' ).val();
		if(sex != 'male' && sex != 'female') 
		{
			message += "<tr><th>Sex</th><td>Please fill in your sex</td></tr>";
		}
		
		
		
		//make sure they answered the catcha
		if( $('#catchaAnswer').length )
		{
			if($( "#catchaAnswer" ).val() == '') 
			{
				message += "<tr><th>Security Code</th><td>Please enter the security code</td></tr>";
			}
		}
		console.log(message)
		if(message)
		{

			Swal.fire({
				title:'Errors Detected',
				html:'<table width="100%" class="text-left table"><thead><tr><th width="30%">Item</th><th>Comment</th></tr></thead><tbody>'+message+'</tbody></table>',
				icon:'warning'
			});
			
			return false;
		}
		
		//double check the details
		if($('#main_email').hasClass( "valid" ))
		{		
			//check the user first
			var family_name = $('#family_name').val();
			var middle_names = $('#middle_names').val();
			var main_email = $('#main_email').val();
			var register_ajax = $("#register_ajax").val();
			
			//make sure they answered the catcha
			if( $('#catchaAnswer').length )
			{
				var catchaAnswer = $("#catchaAnswer").val();
			} else {
				var catchaAnswer = 'none required';
			}
			
			//check the name
			$.post('/ajax/register/home/userNameCheck', {
				register_ajax: register_ajax,
                captcha: catchaAnswer,
				family_name: family_name,
				given_name: given_name,
				middle_names: middle_names,
				dob: dob,
				sex: sex,
				main_email: main_email
			})
			.done(function (d) {
				
				if(d.level == 'error')
				{
					swal(d.heading, d.message, d.level);
				} else {
					
					var age = getAge($("#dob").val());
					
					swal.fire({
						
						title: 'IMPORTANT: Is this correct?',
						html: "<p>Your full name in <em>western order</em> would be:<br><strong>"+given_name +" "+middle_names+" "+family_name+"</strong></p><hr><p>Your full name in <em>eastern order</em> would be:<br><strong>"+family_name +" "+middle_names+" "+given_name+"</strong></p><hr><p>You are <strong>"+age+"</strong> years old and you are a <strong>"+sex+"</strong><br><p>",
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, correct!',
						cancelButtonText: "No, I'll change it!",
						confirmButtonClass: 'btn btn-success',
						cancelButtonClass: 'btn btn-danger',
						buttonsStyling: false
						
					}).then((result) => {
						if (result.value) {
							$("#form-register").unbind().submit();
						} else if (result.dismiss === swal.DismissReason.cancel) {
							swal(
								'No Problem',
								'Please correct the information and submit it again :-)',
								'error'
							)
						}
					});
				}	
			})
			.fail(function () {
				swal('Connection Failed', 'The check could not be done because we could not talk to the server.', 'error');
				return false;
			});
		
		} else {
			swal('Oops ... No Valid Email?','You must specify a valid email address to register!','warning');
			return false;
		}*/
	});
});