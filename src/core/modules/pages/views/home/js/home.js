$(document).on('click', '[data-toggle="lightbox"]', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});
            
$(document).ready(function(){
	console.log('this is home');
	
		
	//!FEEDBACK - Contact Form (there can only be one because of the catcha)
	$("#submit-feedback-form").on('submit', function(e)
	{
		//stop the submit process completely
		e.preventDefault();
		var btn = $('#submit-feedback-form-btn');
		var text = btn.html();

		//setup variables
		var	feedback_name = $('#feedback_name').val(),
			feedback_email = $('#feedback_email').val(),
			feedback_phone = $('#feedback_phone').val(),
			catchaAnswer = $('#catchaAnswer').val(),
			feedback_text = $('#feedback_text').val(),
			link_id = $('#link_id').val(),
			content_id = $('#content_id').val(),
			reCAPTCHA_Token
		
		if($('#reCAPTCHA_Token').length)
		{
			reCAPTCHA_Token = $('#reCAPTCHA_Token').val();
		} else {
			reCAPTCHA_Token = '';
		}

        btn.prop("disabled", true);
        btn.html(
            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
        );
		//send to the server
		$.ajax({
			url: "/ajax/pages/feedback",
			type: 'POST',
			data: {
					'feedback_name': feedback_name,
					'feedback_email': feedback_email,
					'feedback_phone': feedback_phone,
					'catchaAnswer': catchaAnswer,
					'feedback_text': feedback_text,
					'link_id': link_id,
					'content_id': content_id,
					'reCAPTCHA_Token': reCAPTCHA_Token
				},
			cache: false,
			timeout: 10000
		})
		.done(function(response) {
			
			if(response.success)
			{
				//clear the form and show the success
				$('#contact-form').hide();
				$('#submitted-success').show();
			} else {
				if (typeof response.message !== 'undefined')
				{
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
				} else if (typeof response !== 'undefined'){
					alert(response);
				} else {
					alert('Something went wrong - refesh and try again');
				}
			}

            btn.prop("disabled", false);
            btn.html(text);
		})
		.fail(function() {
			alert( "Error could not submit feedback." );
            btn.prop("disabled", false);
            btn.html(text);
		});
	});

	if($('#counter').length){
		//send to the server
		$.ajax({
			url: "/ajax/pages/counters",
			type: 'POST',
			cache: false,
			timeout: 10000
		})
		.done(function(response) {
			$('#total_candidate').html(response.total_candidate)
			$('#total_education').html(response.total_education)
			$('#total_job').html(response.total_job)
		})
		.fail(function() {
			//console.log(response)
		});
	}
	
	
});