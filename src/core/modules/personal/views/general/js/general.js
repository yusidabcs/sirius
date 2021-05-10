$(document).ready(function()
{
	
	

	var dials = $('#countrydial_prefixs').data('dial');
	if($('#nok_country').val() != ''){
		var countryCode = $('#nok_country').val();
		$('#countrydial_prefixs').html('+'+dials[countryCode].dialCode);
		$('#nok_number').focus();
	}

	// # General
	//employment
	$('#employment').materialSelect();
	
	// # Countries and Travel
	//select for country
	$('#country_born').materialSelect();
	$('#country_residence').materialSelect();
	
	//# Personal Information
	$('#relationship').materialSelect();
	
	//NOK
	$('#nok_country').materialSelect();
	$('#nok_number_country').materialSelect();
	$('#nok_number_type').materialSelect();
	
	$('#nok_number_type').change(function(){
		$('#nok_number').focus();
	});
	$('#nok_country').change(function(){
		const countryCode = $(this).val();
		$('#countrydial_prefixs').html('+'+dials[countryCode].dialCode);
	});

	
	//set up passport on open
	var passport = $('#passport_current').val();
	
	if(passport == 'yes')
	{
		$('#passport_yes').prop("checked", true);
		
	} else if( passport == 'no' ) {
		
		$('#passport_no').prop("checked", true);
	}

	//set up travelled_overseas on open
	var travelled_overseas = $('#travelled_overseas_current').val();
	
	if(travelled_overseas == 'yes')
	{
		$('#travelled_overseas_yes').prop("checked", true);
		
	} else if( travelled_overseas == 'no' ) {
		
		$('#travelled_overseas_no').prop("checked", true);
	}
	
	//set up job_hunting on open
	var job_hunting = $('#job_hunting_current').val();
	
	if(job_hunting == 'yes')
	{
		$('#job_hunting_yes').prop("checked", true);
		
	} else if( job_hunting == 'no' ) {
		
		$('#job_hunting_no').prop("checked", true);
	}

	//set up seafarer on open
	var seafarer = $('#seafarer_current').val();
	
	if(seafarer == 'yes')
	{
		$('#seafarer_yes').prop("checked", true);
		
	} else if( seafarer == 'no' ) {
		
		$('#seafarer_no').prop("checked", true);
	}
	
	//set up migration on open
	var migration = $('#migration_current').val();
	
	if(migration == 'yes')
	{
		$('#migration_yes').prop("checked", true);
		
	} else if( migration == 'no' ) {
		
		$('#migration_no').prop("checked", true);
	}
	
	//set up children on open
	var children = $('#children_current').val();
	
	if(children == 'yes')
	{
		$('#children_yes').prop("checked", true);
		
	} else if( children == 'no' ) {
		
		$('#children_no').prop("checked", true);
	}
	
	//set up tattoo on open
	var tattoo = $('#tattoo_current').val();
	
	if(tattoo == 'yes')
	{
		$('#tattoo_yes').prop("checked", true);
		
	} else if( tattoo == 'no' ) {
		
		$('#tattoo_no').prop("checked", true);
	}
	
	//set up height_weight on open
	var height_weight = $('#height_weight_current').val();
	
	if(height_weight == 'me')
	{
		$('#height_weight_me').prop("checked", true);
		$('#im').hide();
		$('#me').show();
		
	} else if( height_weight == 'im' ) {
		
		$('#height_weight_im').prop("checked", true);
		$('#me').hide();
		$('#im').show();
	}
		
	//handle a change of type
	$('.height_weight').on('change', function () {
		
		var type= $(this).val();
		
		if(type == 'me')
		{
			$('#im').hide();
			$('#me').show();
		} else {
			$('#me').hide();
			$('#im').show();
		}
				
	});

	//stuff for image <<>>

	function popupResult(result,title) {
		var html;
		if (result.html) {
			html = result.html;
		}
		if (result.src) {
			html = '<img class="img img-fluid" src="' + result.src + '" />';
		}
		swal.fire({
			title: title,
			html: html,
			width: 750,
			allowOutsideClick: true
		});
		setTimeout(function(){
			$('.sweet-alert').css('margin', function() {
				var top = -1 * ($(this).height() / 2),
					left = -1 * ($(this).width() / 2);

				return top + 'px 0 0 ' + left + 'px';
			});
		}, 1);
	}
	
	function readFile(input,uploadArea,preview_id) {
		
		if (input.files && input.files[0]) {
				
            var reader = new FileReader();
            
            reader.onload = function (e) {
	            
				uploadArea.croppie('bind', {
	            	url: e.target.result
	            });
	            
				$(preview_id).show();
				$('#general_result').show();
				$('#update_crop').hide();
            	
            }
            
            reader.readAsDataURL(input.files[0]);
            
        } else {
	        swal("Sorry - you're browser doesn't support the FileReader API");
	    }
	}

	function signatureReadFile(input,uploadArea,preview_id) {
		
		if (input.files && input.files[0]) {
				
            var reader = new FileReader();
            
            reader.onload = function (e) {
	            
				uploadArea.croppie('bind', {
	            	url: e.target.result
	            });
	            
				$(preview_id).show();
				$('#signature_result').show();
				$('#signature_update_crop').hide();
            	
            }
            
            reader.readAsDataURL(input.files[0]);
            
        } else {
	        swal("Sorry - you're browser doesn't support the FileReader API");
	    }
	}
	
	// -- image --
	var uploadCrop;

	//detect viewport and compare with inserted attribute data
	const b_width = $('#general_croppie').data('banner-width');
	const b_height = $('#general_croppie').data('banner-height');
	const v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/2;
	const v_height = b_height/b_width*v_width;

	//choose appropriate width and height based on device
	const crop_width = (b_width>v_width) ? v_width : b_width; 
	const crop_height = (b_height>v_height) ? v_height : b_height;
	// const crop_width = b_width; 
	// const crop_height = b_height; 

	uploadCrop = $('#general_croppie').croppie({
		
		viewport: {
			width: crop_width,
			height: crop_height
		},
		boundary: {
			width: crop_width*1.1,
			height: crop_height*1.1
	    },
		enableExif: true,
		enableOrientation: true
	});
	
	$('#general_result').on('click', function (ev) {
		
		const file_choosen = $('#general_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
			uploadCrop.croppie('result', {
				type: 'canvas',
				size: 'original'
			}).then(function (resp) {
				
				resizeImage(resp, b_width, b_height).then((resp) => {
					popupResult({
						src: resp
					},'Photo of You');
					$('#curr_img').prop('src',resp);
					$('#curr_img').show();
					$('#general_base64').val(resp);
					$('#general_croppie_wrap').hide();
					$('#general_result').hide();
					document.getElementById("general_image").scrollIntoView();
					$('#update_crop').show();
				});
				
			});
		}else{
			Swal.fire({
				icon: 'warning',
				text: 'Please choose an image first',
			});
		}
	});
	
	$('#general_input').on('change', function () {
		const file_choosen = $('#general_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== ""){
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			readFile(this,uploadCrop,'#general_croppie_wrap');
		}
	});

	$('#update_crop').on('click',function(){
		$('#general_croppie_wrap').show();
		$('#general_result').show();
		$(this).hide();
		document.getElementById("general_croppie_wrap").scrollIntoView();
	});
	// -- Finish stuff for image --

	// -- image signature --
	var sigUploadCrop;

	//detect viewport and compare with inserted attribute data
	const sig_b_width = $('#signature_croppie').data('banner-width');
	const sig_b_height = $('#signature_croppie').data('banner-height');
	const sig_v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/2;
	const sig_v_height = sig_b_height/sig_b_width*sig_v_width;

	//choose appropriate width and height based on device
	const sig_crop_width = (sig_b_width>sig_v_width) ? sig_v_width : sig_b_width; 
	const sig_crop_height = (sig_b_height>sig_v_height) ? sig_v_height : sig_b_height;
	// const sig_crop_width = sig_b_width; 
	// const sig_crop_height = sig_b_height; 

	$('#signature_croppie_wrap').hide();
	sigUploadCrop = $('#signature_croppie').croppie({
		
		viewport: {
			width: sig_crop_width,
			height: sig_crop_height
		},
		boundary: {
			width: sig_crop_width*1.1,
			height: sig_crop_height*1.1
	    },
		enableExif: true
	});
	
	$('#signature_result').on('click', function (ev) {
		
		const file_choosen = $('#signature_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
			sigUploadCrop.croppie('result', {
				type: 'canvas',
				size: 'original'
			}).then(function (resp) {
				
				resizeImage(resp, sig_b_width, sig_b_height).then((resp) => {
					popupResult({
						src: resp
					},'Photo of You');
					$('#sig_curr_img').prop('src',resp);
					$('#sig_curr_img').show();
					$('#signature_base64').val(resp);
					$('#signature_croppie_wrap').hide();
					$('#signature_result').hide();
					document.getElementById("signature_image").scrollIntoView();
					$('#signature_update_crop').show();
				});
				
			});
		}else{
			Swal.fire({
				icon: 'warning',
				text: 'Please choose an image first',
			});
		}
	});
	
	$('#signature_input').on('change', function () {
		const file_choosen = $('#signature_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== ""){
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			signatureReadFile(this,sigUploadCrop,'#signature_croppie_wrap');
			$('#signature_croppie_wrap').show();
		}
	});

	$('#signature_update_crop').on('click',function(){
		$('#signature_croppie_wrap').show();
		$('#signature_result').show();
		$(this).hide();
		document.getElementById("signature_croppie_wrap").scrollIntoView();
	})
	// -- Finish stuff for image --
	
	$('#go_back').click(function(e){
		e.preventDefault();
		swal.fire({
            title: 'Leave form?',
            text: 'Changes you made may not be saved.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Leave !'
        }).then((result) => {
            if(result.value)
            {
				document.location.href = $(this).prop('href');
			}
		});
	});

	$('#general').submit(function() {
		//Client side form validation
		let empty = [];
		let status = true;

		//Checking radios
		if(!$('input[name=job_hunting]').is(':checked'))
		{
			status = false;
			empty.push('Looking for a New Job');
		}

		if(!$('input[name=seafarer]').is(':checked'))
		{
			status = false;
			empty.push('Work at Sea');
		}
		
		
		if(!$('input[name=migration]').is(':checked'))
		{
			status = false;
			empty.push('Work on land in Singapore.');
		}
		
		if(!$('input[name=passport]').is(':checked'))
		{
			status = false;
			empty.push('Passport');
		}
		
		if(!$('input[name=travelled_overseas]').is(':checked'))
		{
			status = false;
			empty.push('Overseas Travel');
		}

		if(!$('input[name=children]').is(':checked'))
		{
			status = false;
			empty.push('Children');
		}

		if(!$('input[name=tattoo]').is(':checked'))
		{
			status = false;
			empty.push('Tatto');
		}

		//Checking text if empty

		if ($('#height_weight_me').prop('checked'))
		{
			if(!$.trim($('input[name=height_cm]').val()).length) 
			{
				status = false;
				empty.push('Height cm');
			}
			if(!$.trim($('input[name=weight_kg]').val()).length) 
			{
				status = false;
				empty.push('Weight kg');
			}
		}else{
			if(!$.trim($('input[name=height_in]').val()).length) 
			{
				status = false;
				empty.push('Height in');
			}
			if(!$.trim($('input[name=weight_lb]').val()).length) 
			{
				status = false;
				empty.push('Weight lb');
			}
			
		}

		if ($('#general_current').length <= 0) {
			if(!$.trim($('input[name=general_base64]').val()).length)
			{
				status = false;
				empty.push('Current Photo, Please adjust and crop the image before submitting the form');
			}	
		}
			

		if (status == false)
		{
			Swal.fire(
				'Data required',
				'<div class="text-left"><h4><strong>Please fix all fields to continue</strong></h4>'+
				'<ul>'+
				empty.map(function (list) {
					return '<li>' + list + '</li>';
				}).join('') +
				'</ul>'+
				'</div>',
				'warning'
			);
		}

		return status;
	});
});