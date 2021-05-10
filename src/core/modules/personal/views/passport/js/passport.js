function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	var expires = "expires="+d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }
  
  function getCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i = 0; i < ca.length; i++) {
	  var c = ca[i];
	  while (c.charAt(0) == ' ') {
		c = c.substring(1);
	  }
	  if (c.indexOf(name) == 0) {
		return c.substring(name.length, c.length);
	  }
	}
	return "";
  }
$(document).ready(function()
{
	$(document).on('click','#understand_cookie', function(){
		if($('#dont_show').prop("checked") == true){
			setCookie("dont_show_sample_passpport", "yes", 5);
		}
	})
	
	
	let cookie_sample_passport = getCookie('dont_show_sample_passpport');
	if(cookie_sample_passport!=='yes') {
		$('#passport_example').modal("show");
	}
	
	//select
	$('#countryCode_id').materialSelect();
	$('#sex').materialSelect();

	const today = new Date();
	const dd = String(today.getDate()).padStart(2, '0');
	const mm = String(today.getMonth()).padStart(2, '0');
	const yyyy = today.getFullYear();

    $('.calendar-dob').pickadate({
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
		selectYears: 100,
		format: 'd mmm yyyy',
		formatSubmit: 'yyyy-mm-dd',
		//restrict input date from 100 year previous until today
		min: new Date(yyyy-100,mm,dd),
		max: new Date(yyyy-17,mm,dd)
	})

	$('.calendar-doi').pickadate({
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
		selectYears: true,
		format: 'd mmm yyyy',
		formatSubmit: 'yyyy-mm-dd',
		//restrict passport validity input date from 10 year previous until today,
		min: new Date(yyyy-10,mm,dd),
		max: new Date(yyyy,mm,dd)
	})

	const cal_exp = $('.calendar-exp').pickadate({
		labelMonthNext: 'Go to the next month',
		labelMonthPrev: 'Go to the previous month',
		labelMonthSelect: 'Pick a month from the dropdown',
		labelYearSelect: 'Pick a year from the dropdown',
		selectMonths: true,
		selectYears: true,
		format: 'd mmm yyyy',
		formatSubmit: 'yyyy-mm-dd',
		min: new Date(yyyy,mm,dd),
		max: new Date(yyyy+10,mm,dd)
	})
	const exp_picker = cal_exp.pickadate('picker');

	//set expiry date based on passport active or inactive
	$('input[name=active]').change(function()
	{
		$('#to_date').val('');
		if ($(this).val() == 'active')
		{
			//f active, set an expiration date from now to the next 10 years
			exp_picker.set('min', new Date(yyyy,mm,dd));
			exp_picker.set('max', new Date(yyyy+10,mm,dd));
		}else{
			//if inactive, set an expiration date from the previous 10 years to today
			exp_picker.set('min', new Date(yyyy-10,mm,dd));
			exp_picker.set('max', new Date(yyyy,mm,dd));
		}
	});

	//set up active on open
	const active_current = $('#active_current').val();
	
	if(active_current == 'active')
	{
		exp_picker.set('min', new Date(yyyy,mm,dd));
		exp_picker.set('max', new Date(yyyy+10,mm,dd));
		$('#active').prop("checked", true);
		
	} else if( active_current == 'not_active' ) {

		exp_picker.set('min', new Date(yyyy-10,mm,dd));
		exp_picker.set('max', new Date(yyyy,mm,dd));
		$('#not_active').prop("checked", true);
	}
	
	//open it up if the values are set
	var full_name = $('#full_name').val();
	var family_name = $('#family_name').val();
	var given_names = $('#given_names').val();
	// full_name=true;
	if(full_name)
	{
		$('#passport_information').show();
		$('#type_full_name').prop("checked", true);
		$('#family_name').val('');
		$('#given_names').val('');
		$('.separate_names_tr').hide();
		$('.full_name_tr').show();
		$('#passport_image').show();
		
	} else if( family_name || given_names ) {
		
		$('#passport_information').show();
		$('#type_separate_names').prop("checked", true);
		$('#full_name').val('');
		$('.full_name_tr').hide();
		$('.separate_names_tr').show();
		$('#passport_image').show();
		
	}

	//handle a change of type
	$('.name_style').on('change', function () {
		
		var style = $(this).val();
		
		$('#passport_information').show();
		
		if(style == 'full')
		{
			$('#family_name').val('');
			$('#given_names').val('');
			$('.separate_names_tr').hide();
			$('.full_name_tr').show();
		} else {
			$('#fullname').val('');
			$('.full_name_tr').hide();
			$('.separate_names_tr').show();
		}
		
		$('#passport_image').show();
				
	});
	
	
	//stuff for image

	function popupResult(result) {
		var html;
		if (result.html) {
			html = result.html;
		}
		if (result.src) {
			html = '<img class="img img-fluid" src="' + result.src + '" />';
		}
		Swal.fire({
			title: 'Passport',
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
	
	var $uploadCrop;

	function readFile(input) {
		
		if (input.files && input.files[0]) {
				
            var reader = new FileReader();
            
            reader.onload = function (e) {
	            
				$uploadCrop.croppie('bind', {
	            	url: e.target.result
	            });
	            
	            $('#passport_croppie_wrap').show();
            	
            }
            
            reader.readAsDataURL(input.files[0]);
            
        } else {
	        swal("Sorry - you're browser doesn't support the FileReader API");
	    }
	}
	//detect viewport and compare with inserted attribute data
	const b_width = $('#passport_croppie').data('banner-width');
	const b_height = $('#passport_croppie').data('banner-height');
	const v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/2;
	const v_height = b_height/b_width*v_width;

	//choose appropriate width and height based on device
	const crop_width = (b_width>v_width) ? v_width : b_width; 
	const crop_height = (b_height>v_height) ? v_height : b_height; 
	// const crop_width = b_width; 
	// const crop_height = b_height; 

	$uploadCrop = $('#passport_croppie').croppie({
		viewport: {
			width: crop_width,
			height: crop_height
		},
		boundary: {
			width: crop_width*1.1,
			height: crop_height*1.1
	    },
		enableExif: true,
		showZoomer: false,
    	enableOrientation: true
	});

	$('#passport_input').on('change', function () {

		const file_choosen = $('#passport_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== ""){
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			readFile(this);
			$('#passport_result').show();
			$('#update_crop').hide();
		}
	});
	
	$('#passport_result').on('click', function (ev) {
		const file_choosen = $('#passport_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
			$uploadCrop.croppie('result', {
				type: 'canvas',
				size: 'original'
			}).then(function (resp) {
				resizeImage(resp, b_width, b_height).then((resp) => {
					popupResult({
						src: resp
					});
					
					$('#passport_base64').val(resp);
					$('#curr_img').prop('src',resp);
					$('#d_curr_img').show();
					$('#passport_croppie_wrap').hide();
					$('#passport_result').hide();
					document.getElementById("passport_image").scrollIntoView();
					$('#update_crop').show();
				})
			});
		}else{
			Swal.fire({
				icon: 'warning',
				text: 'Please choose an image first',
			});
		}
	});

	$('#update_crop').on('click',function(){
		$('#passport_croppie_wrap').show();
		$('#passport_result').show();
		$(this).hide();
		document.getElementById("passport_croppie_wrap").scrollIntoView();
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

	$('button[type=submit]').click(function(){
		
		//Client side form validation
		let empty = [];
		let status = true;
		if(!$('input[name=active]').is(':checked'))
		{
			status = false;
			empty.push('Passport Active');
		}

		if(!$('input[name=name_style]').is(':checked'))
		{
			status = false;
			empty.push('Name on Passport');
		}else{
			if ($('#type_full_name').prop('checked')){
				//check if full name is empty
				if(!$.trim($('input[name=full_name]').val()).length) 
				{
					status = false;
					empty.push('Full Name');
				}

			}else{
				//check if separated name is empty
				if(!$.trim($('input[name=family_name]').val()).length) 
				{
					status = false;
					empty.push('Family Name');
				}
				if(!$.trim($('input[name=given_names]').val()).length) 
				{
					status = false;
					empty.push('Given Names');
				}
				
			}

		}

		//check image
		if(!$('#curr_img').length){
			if(!$.trim($('input[name=passport_base64]').val()).length) 
			{
				status = false;
				empty.push('Passport Image, Please adjust and crop the image before submitting the form');
			}
		}
		
		if(!$.trim($('#dob').val()).length) 
		{
			status = false;
			empty.push('Date of Birth');
		}

		
		
		date_issue = $('#from_date').val();
		if(!$.trim(date_issue).length) 
		{
			status = false;
			empty.push('Date of Issue');
		}

		date_exp = $('#to_date').val();
		if(!$.trim(date_exp).length) 
		{
			status = false;
			empty.push('Date of Expiry');
		}

		ts_passp_date = new Date(date_issue).getTime();
		ts_passp_exp = new Date(date_exp).getTime();

		if (ts_passp_exp < ts_passp_date){
			status = false;
			empty.push('Expired Date Must be <= than Issued Date');
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