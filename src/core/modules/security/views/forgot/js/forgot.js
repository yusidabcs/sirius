$(document).ready(function(){
	
	//ajax to change user information
	$('#reset_password').click(function(e){
		
		//stop it actually submitting
		e.preventDefault();
		
		//get the correct variables
		var user = $("#securityFormUsername").val();
		var text = $('#reset_password').html()
		
		//let's do some error checking
		if(user)
		{
			//spinner/loading
			$('#reset_password').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...').addClass('disabled');
						
			//ok good to go
			$.ajax({
				url: '/ajax/security/resetpass',
				type: 'POST',
				data: {
					'user': user,
					'reCaptcha': $('#reCAPTCHA_Forgot_Token').val()
				},
				dataType: 'json',
				cache: false,
				timeout: 10000
			})
			.done(function(d) {
                $("#captcha").val('');
                $("#securityFormUsername").val('');
				if(d.show == 'confirm_sent') {
					//clear values
					$("#securityFormUsername").val('');
					$("#captcha").val('');
					//set the email value
					$('#confirm_email').text(d.email);
					//hide all options
					$('.view_option').hide();
					//show the confirm message
					$('#confirm_sent').show();
				} else if(d.show == 'confirm_failed') {
					//clear values
					$("#securityFormUsername").val('');
					$('#confirm_email').text('');
					//hide all options
					$('.view_option').hide();
					//show the confirm message
					$('#confirm_failed').show();
				} else if(d.show == 'captcha_failed') {
					//clear values
					$("#captcha").val('');
					$('#confirm_email').text('');
					//hide all options
					$('.view_option').hide();
					//show the confirm message
					$('#captcha_failed').show();
				} else {
					//clear values
					$("#securityFormUsername").val('');
					$("#captcha").val('');
					$('#confirm_email').text('');
					//hide all options
					alert('Unknown Reply');
				}

                $('#reset_password').html(text).removeClass('disabled');
			})
			.fail(function(msg) {
				alert( "Error we can not process request at this time." );
                $('#reset_password').html(text).removeClass('disabled');
			})
			.always(function() {
				//drop the spinner
                $('#reset_password').html(text).removeClass('disabled');
			});	
		} else {
			alert('You must fill in both of the fields.');
		}
		return;
	});
	
	$('.try_again').click(function(e){
		location.reload();
	});
});