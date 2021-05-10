$(document).ready(function(){
	
	//ajax to change user information
	$('#pass_change').click(function(e){
		
		//stop it actually submitting
		e.preventDefault();
		
		//get the correct variables
		var btn = $(this);
		var text = btn.html();
		var checksum = $(this).val();
		var passNew = $("#password-new");
		var passConfirm = $("#password-confirm");
		var user_id = $("#user_id").val();
		var new_val = passNew.val();
		var confirm_val = passConfirm.val();
		
		//let's do some error checking
		if(new_val && confirm_val)
		{
			if(new_val == confirm_val)
			{
				if(new_val.length > 5)
				{
                    btn.html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...').addClass('disabled');
					//ok good to go
					$.ajax({
						url: "/ajax/security/passchange",
						type: 'POST',
						data: {
							checksum: checksum,
							user_id: user_id,
							new_pas: new_val
						},
						cache: false,
						timeout: 10000
					})
					.done(function(d) {
						var answer = jQuery.parseJSON(d);
						if(answer.good)
						{
							//clear values
							passNew.val('');
							passConfirm.val('');
							//show success
							$('#change_pass_form').hide();
							$('#pass_changed').show();
						} else {
							alert(answer.note);
						}
                        btn.html(text).removeClass('disabled');
					})
					.fail(function() {
						alert( "Error could not update the password." );
                        btn.html(text).removeClass('disabled');
					})
					.always(function() {
                        btn.html(text).removeClass('disabled');
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
		
});