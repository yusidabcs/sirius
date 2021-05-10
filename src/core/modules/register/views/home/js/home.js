function getAge(dob)
{
	dob = new Date(dob);
	var today = new Date();
	var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
	return age;
}


$(document).ready(function(){
	//need for mdbselect
	$('.mdb-select').materialSelect();

	//hide navbar if partner slug exist
	if ($('#partner_id').length){
		$('header').hide();
	}
    var mydate = new Date($('#dob').data('min-date'));
	
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
		format: 'yyyy-mm-dd',
    })
	
	//show the country info
	$( "#country" ).change(function() {

		$('#allowed').hide();

		var infoClass =	$( "#country option:selected" ).attr("class");
		$( ".country-info").hide();
		$( "#"+infoClass).show();

		if(infoClass !== 'default'){
			$('#allowed').fadeIn();
		}
	});
	//check the email
	$('#main_email').change(function() {
		var self = $(this);
		var main_email = $(this).val();
		var register_ajax = $("#register_ajax").val();

		if (main_email === '' || main_email === null) {
			$('#email_note').addClass('d-block');
			$('#submit_button').attr('disabled', true);
		} else {
			$('#email_note').addClass('d-none');
			$('#email_note').removeClass('d-block');
		}

		if(main_email)
		{
			grecaptcha.ready(function () {
				grecaptcha.execute(self.data('recaptcha'), { action: 'emailCheck' }).then(function (token) {
					$.post('/ajax/register/home/emailCheck', {
						main_email: main_email,
						register_ajax: register_ajax,
						captcha:  token,
					})
					.done(function (d) {
				
						if(d.level == 'success') 
						{
							$('#main_email').removeClass( "invalid" );
							$('#main_email').addClass( "valid" );
							$('#submit_button').prop( "disabled", false );
					
						} else if(d.level == 'error') {
							$('#main_email').removeClass( "valid" );
							$('#main_email').addClass( "invalid" );
							$('#submit_button').prop( "disabled", true );
							Swal.fire({
								icon: d.level,
								title: d.heading,
								text: d.message
							});
					
						} else if(d.level == 'warning'){
							Swal.fire({
								icon: d.level,
								title: d.heading,
								text: d.message
							});
						}
				
					})
					.fail(function () {
						Swal.fire({
							icon: 'error',
							title: 'Connection Failed',
							text: 'The check could not be done because we could not talk to the server.'
						});
					});
				}).catch(() => {
					Swal.fire({
						icon: 'error',
						title: 'Recaptcha Failed',
						text: 'Google recatpcha not defined'
					});
				});
			});
			
		}
	});
	
	//submit the form
	$("#form-register").submit(function(e) {
		
		var acknowledge_ok = true;
		var message = '';
		
		e.preventDefault();
		
		//country
		var country =	$( "#country option:selected" ).val();
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
		
		//acknowlege
		if( $("#accurate").prop('checked') == false )
		{ 
			$("#accurate").removeClass( "is-valid" );
			$("#accurate").addClass( "is-invalid" );
			acknowledge_ok = false;
		} else {
			$("#accurate").removeClass( "is-invalid" );
			$("#accurate").addClass( "is-valid" );
		}
		
		if( $("#english").prop('checked') == false )
		{ 
			$("#english").removeClass( "is-valid" );
			$("#english").addClass( "is-invalid" );
			acknowledge_ok = false;
		} else {
			$("#english").removeClass( "is-invalid" );
			$("#english").addClass( "is-valid" );
		}
		
		if( $("#register").prop('checked') == false )
		{ 
			$("#register").removeClass( "is-valid" );
			$("#register").addClass( "is-invalid" );
			acknowledge_ok = false;
		} else {
			$("#register").removeClass( "is-invalid" );
			$("#register").addClass( "is-valid" );
		}
		
		if(!acknowledge_ok)
		{
			message += "<tr><th>Acknowledgement</th><td>Please acknowledge all the boxes</td></tr>";
		}
		
		//make sure they answered the catcha
		if( $('#catchaAnswer').length )
		{
			if($( "#catchaAnswer" ).val() == '') 
			{
				message += "<tr><th>Security Code</th><td>Please enter the security code</td></tr>";
			}
		}
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
            $('#submit_button').prop( "disabled", true ).html('Checking..');
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
                $('#submit_button').prop( "disabled", false).html('Register Now!');
				if(d.level == 'error')
				{
					Swal.fire(
						d.heading,
						d.message,
						d.level
					  )
				} else {
					
					var age = getAge($("#dob").val());
					
					swal.fire({
						
						title: 'IMPORTANT: Is this correct?',
						html: "<p>Your full name in <em>western order</em> would be:<br><strong>"+given_name +" "+middle_names+" "+family_name+"</strong></p><hr><p>Your full name in <em>eastern order</em> would be:<br><strong>"+family_name +" "+middle_names+" "+given_name+"</strong></p><hr><p>You are <strong>"+age+"</strong> years old and you are a <strong>"+sex+"</strong><br><p>",
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, correct!',
						cancelButtonText: "No, I'll change it!"
						
					}).then((result) => {
						if (result.value) {
							$('#submit_button').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...').addClass('disabled');
							$("#form-register").unbind().submit();
						} else if (result.dismiss === Swal.DismissReason.cancel) {
							Swal.fire(
								'No Problem',
								'Please correct the information and submit it again :-)',
								'error'
							);
						}
					});
				}	
			})
			.fail(function () {
                $('#submit_button').prop( "disabled", false).html('Register Now!');
				swal.fire('Connection Failed', 'The check could not be done because we could not talk to the server.', 'error');
				return false;
			});
		
		} else {
			swal.fire('Oops ... No Valid Email?','You must specify a valid email address to register!','warning');
			return false;
		}
	});
	
	//everytime we load
	if( $('#main_email').val() != '') 
	{
		$('#main_email').change();
	}
	
	if( $( "#country option:selected" ).val() != 'not specified')
	{
		$('#country').change();
	}
		
});