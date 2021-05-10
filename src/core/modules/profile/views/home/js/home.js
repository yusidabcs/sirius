
$(document).ready(function(){

	// let cv_template = $('#cv-template').val();
	// $('#cv-template').on('change',function(){
	// 	cv_template = $(this).val();
	// 	generateCV(cv_template);
	// })

	// $('#btn_cv').on('click',function(){
	// 	$('#modalCV').modal('show');
	// 	generateCV(cv_template);
	// });

	// $('#download-cv').on('click',function(){
	// 	const win = window.open('/ajax/cv/main/generate-cv-pdf/'+cv_template);
	// 	if (win) {
	// 		win.focus();
	// 	} else {
	// 		Swal.fire({
	// 		  type: 'warning',
	// 		  title: 'Blocked',
	// 		  text: 'Please allow pop up to download the file!'
	// 		});
	// 	}
	// });

    $('[data-tooltip]').tooltip();
    
    //update user details
    
	$('#changeUserModal').on('hidden.bs.modal', function () 
	{
	    $('#resetDetailsButton').click();
    });
    
    $('#changeDetailsButton').click(function(evt){
	    
	    evt.preventDefault();
        var btn = $('#changeDetailsButton');
        var text = btn.html();
	    const username = $('#username').val().trim();
	    const email = $('#email').val().trim();;
	    
	    const username_orig = $('#username_orig').val();
		const email_orig = $('#email_orig').val();
		
	    if( username == '' && email == '')
	    {
			
			Swal.fire({
				icon: 'warning',
				title: 'Empty',
				text: 'Username & Email must not empty'
			});
			return
		}

	    if( username == username_orig && email == email_orig)
	    {
		    return;
	    }
        btn.attr('disabled',true).html('Updating..');
	    $.post('/ajax/profile/main/updateUserDetails', {
			username: username,
			email: email
		})
		.done(function (d) 
		{	
			if(d.ok) 
			{
				$('#changeDetailsInfo').html('<p><strong>Done OK</strong></p>');
				
				const email = $('#email').val();
				const user_username = $('#username').val();
				const main_mailto = '<a href="mailto:'+email+'">'+email+'</a>';
				
				//reset originals
				$('#username_orig').val(user_username);
				$('#email_orig').val(email);
				
				//update form
				$('#user_username').text(user_username);
				$('#main_email').html(main_mailto);

				Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: d.message
				});
                $('#changeUserModal').modal('hide')
                btn.attr('disabled',false).html(text);
			} else {

                btn.attr('disabled',false).html(text);
				Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: d.message
				});				
			}
			
		})
		.fail(function () 
		{	
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Connection Failed: There was a connection error.  The internet may be down or there might be an issue with the server.'
			});
		});	

    });

	$('#resetDetailsButton').click(function(evt)
	{    
	    evt.preventDefault();
	    
	    $('#username').val($('#username_orig').val());
		$('#email').val($('#email_orig').val());
		$('#changeDetailsInfo').html('');

	});
    
    //update user password
    
	$('#changePassword').on('hidden.bs.modal', function () 
	{
	    $('#restPasswordButton').click();
    });
    
	$('#changePasswordButton').click(function(evt)
	{    
	    evt.preventDefault();
	    var btn = $('#changePasswordButton');
	    var text = btn.html();
	    const password_current = $('#password_current').val().trim();
		const password_new = $('#password_new').val().trim();
		const password_confirm = $('#password_confirm').val().trim();
	    
	    if( password_current == '' || password_new == '' || password_confirm == '')
	    {
		    $('#changePasswordInfo').html('<p><strong>All fields are required!</strong></p>');
		    return;
	    }
	    
	    if( password_new != password_confirm )
	    {
		    $('#changePasswordInfo').html('<p><strong>New and Confirm must be the same!</strong></p>');
		    return;
	    }
	    btn.attr('disabled',true).html('Updating..');

	    $.post('/ajax/profile/main/updateUserPassword', {
			password_current: password_current,
			password_new: password_new,
			password_confirm: password_confirm
		})
		.done(function (d) 
		{
			if(d.ok)
			{
				//reset originals
				$('#restPasswordButton').click();
				Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: d.message
				});
                $('#changePassword').modal('hide')
                btn.attr('disabled',false).html(text);
			} else {

				Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: d.message
				});
                btn.attr('disabled',false).html(text);
			}
			
		})
		.fail(function () 
		{	
			$('#changePasswordInfo').html('<p><strong>Connection Failed:</strong> There was a connection error.  The internet may be down or there might be an issue with the server.</p>');
            btn.attr('disabled',false).html(text);
		});	

    });

	$('#restPasswordButton').click(function(evt)
	{    
	    evt.preventDefault();
	    
	    $('#password_current').val('');
		$('#password_new').val('');
		$('#password_confirm').val('');
		$('#changePasswordInfo').html('');
		
	});
    
});