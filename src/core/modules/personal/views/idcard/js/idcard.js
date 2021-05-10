$(document).ready(function()
{
	//select
	$('#countryCode_id').materialSelect();
	$('#type').materialSelect();

	const today = new Date();
	const dd = String(today.getDate()).padStart(2, '0');
	const dde = String(today.getDate()+1).padStart(2, '0');
	const mm = String(today.getMonth()).padStart(2, '0');
	const yyyy = today.getFullYear();

    $('.calendar-doi').pickadate({
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
		selectYears: true,
		format: 'd mmm yyyy',
		formatSubmit: 'yyyy-mm-dd',
		//restrict id validity input date from 50 year previous until today,
		min: new Date(yyyy-50,mm,dd),
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
		//restrict id card expire input date to 20 year from now
		min: new Date(yyyy,mm,dde),
		max: new Date(yyyy+20,mm,dd)
	})
	
	const exp_picker = cal_exp.pickadate('picker');
	//set expiry date based on idcard  active or inactive
	$('input[name=active]').change(function()
	{
		$('#to_date').val('');
		if ($(this).val() == 'active')
		{
			//f active, set an expiration date from now to the next 20 years
			exp_picker.set('min', new Date(yyyy,mm,dde));
			exp_picker.set('max', new Date(yyyy+20,mm,dd));
			$('#id_expire').prop('checked',false).prop('disabled',false);
		}else{
			//if inactive, set an expiration date from the previous 50 years to today
			exp_picker.set('min', new Date(yyyy-50,mm,dd));
			exp_picker.set('max', new Date(yyyy,mm,dd));
			$('#id_expire').prop('checked',true).prop('disabled',true);
		}
		//checkbox expire also need to change
		$('#id_expire').change();
	});

	//set up active on open
	var active_current = $('#active_current').val();
	
	if(active_current == 'active')
	{
		$('#active').prop("checked", true);
		
	} else if( active_current == 'not_active' ) {
		
		$('#not_active').prop("checked", true);
	}

	//expire

	if( $('#id_expire').is(':checked') )
	{
		$('#id_expire_no').hide();
		$('#id_expire_yes').show();
		
	} else {
		$('#id_expire_yes').hide();
		$('#id_expire_no').show();
	}
	
	$('#id_expire').change(function(event){
		
		if($(this).is(':checked'))
		{
			$('#id_expire_no').hide();
			$('#id_expire_yes').show().parent('div').prev().addClass('required');
			
		} else {
			$('#id_expire_yes').hide().parent('div').prev().removeClass('required');
			$('#id_expire_no').show();
			$('#to_date').val('');
			
		}

	});

	//open it up if the values are set
	var full_name = $('#full_name').val();
	var family_name = $('#family_name').val();
	var given_names = $('#given_names').val();

	if(full_name)
	{
		$('#idcard_information').show();
		$('#type_full_name').prop("checked", true);
		$('#family_name').val('');
		$('#given_names').val('');
		$('.separate_names_tr').hide();
		$('.full_name_tr').show();
		$('#idcard_image').show();
		
	} else if( family_name || given_names ) {
		
		$('#idcard_information').show();
		$('#type_separate_names').prop("checked", true);
		$('#full_name').val('');
		$('.full_name_tr').hide();
		$('.separate_names_tr').show();
		$('#idcard_image').show();
	}
	
	//handle a change of type
	$('.name_style').on('change', function () {
		
		var style = $(this).val();
		
		$('#idcard_information').show();
		
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
		
		$('#idcard_image').show();
				
	});

	
	//stuff for image

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
            	
            }
            
            reader.readAsDataURL(input.files[0]);
            
        } else {
	        swal("Sorry - you're browser doesn't support the FileReader API");
	    }
	}
	
	//-- FRONT --
	
	var uploadCrop;
	
	//detect viewport and compare with inserted attribute data
	const b_width = $('#idcard_croppie').data('banner-width');
	const b_height = $('#idcard_croppie').data('banner-height');
	const v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/2;
	const v_height = b_height/b_width*v_width;

	//choose appropriate width and height based on device
	const crop_width = (b_width>v_width) ? v_width : b_width; 
	const crop_height = (b_height>v_height) ? v_height : b_height; 
	// const crop_width = b_width; 
	// const crop_height = b_height; 

	uploadCrop = $('#idcard_croppie').croppie({
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
	
	$('#idcard_result').on('click', function (ev) {
		const file_choosen = $('#idcard_input').val();

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
					},'ID Card Front');
					
					$('#idcard_base64').val(resp);
					$('#curr_img').prop('src',resp);
					$('#d_curr_img').show();
					$('#idcard_croppie_wrap').hide();
					$('#idcard_result').hide();
					document.getElementById("idcard_image_front").scrollIntoView();
					$('#update_crop_front').show();
				})
			});
		}else{
			Swal.fire({
				icon: 'warning',
				text: 'Please choose an image first',
			});
		}
	});
	
	$('#idcard_input').on('change', function () {
		const file_choosen = $('#idcard_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== ""){
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			readFile(this,uploadCrop,'#idcard_croppie_wrap');
			$('#idcard_result').show();
			$('#update_crop_front').hide();
		}
	});

	$('#update_crop_front').on('click',function(){
		$('#idcard_croppie_wrap').show();
		$('#idcard_result').show();
		$(this).hide();
		document.getElementById("idcard_croppie_wrap").scrollIntoView();
	})
	
	//-- BACK --
		
	var uploadCropBack;
	const back_b_width = $('#idcard_back_croppie').data('banner-width');
	const back_b_height = $('#idcard_back_croppie').data('banner-height');
	const back_v_height = back_b_height/back_b_width*v_width;

	//choose appropriate width and height based on device
	const back_crop_width = (back_b_width>v_width) ? v_width : back_b_width; 
	const back_crop_height = (back_b_height>back_v_height) ? back_v_height : back_b_height;
	// const back_crop_width = back_b_width; 
	// const back_crop_height = back_b_height;  

	uploadCropBack = $('#idcard_back_croppie').croppie({
		viewport: {
			width: back_crop_width,
			height: back_crop_height
		},
		boundary: {
			width: back_crop_width*1.1,
			height: back_crop_height*1.1
		},
		enableExif: true,
		enableOrientation: true
	});
	
	$('#idcard_back_result').on('click', function (ev) {
		const file_choosen = $('#idcard_back_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
			uploadCropBack.croppie('result', {
				type: 'canvas',
				size: 'original'
			}).then(function (resp) {
				resizeImage(resp, back_b_width, back_b_height).then((resp) => {
					popupResult({
						src: resp
					},'ID Card Back');
					
					$('#idcard_back_base64').val(resp);
					$('#curr_img_back').prop('src',resp);
					$('#d_curr_img_back').show();
					$('#idcard_back_croppie_wrap').hide();
					$('#idcard_back_result').hide();
					document.getElementById("idcard_image_back").scrollIntoView();
					$('#update_crop_back').show();
				})
			});
		}else{
			Swal.fire({
				icon: 'warning',
				text: 'Please choose an image first',
			});
		}
	});
	
	$('#idcard_back_input').on('change', function () {
		const file_choosen = $('#idcard_back_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== ""){
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			readFile(this,uploadCropBack,'#idcard_back_croppie_wrap');
			$('#idcard_back_result').show();
			$('#update_crop_back').hide();
		}
	});

	$('#update_crop_back').on('click',function(){
		$('#idcard_back_croppie_wrap').show();
		$('#idcard_back_result').show();
		$(this).hide();
		document.getElementById("idcard_back_croppie_wrap").scrollIntoView();
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

		//active checked or not
		if(!$('input[name=active]').is(':checked'))
		{
			status = false;
			empty.push('ID Card Active');
		}

		//name
		if(!$('input[name=name_style]').is(':checked'))
		{
			status = false;
			empty.push('Name on ID Card');
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

		
		//check date issued
		const date_issue = $('#from_date').val();
		if(!$.trim(date_issue).length) 
		{
			status = false;
			empty.push('Date of Issue');
		}

		//if tick expire
		if( $('#id_expire').prop('checked') )
		{
			date_exp = $('#to_date').val();
			if(!$.trim(date_exp).length) 
			{
				status = false;
				empty.push('Date of Expiry');
			}

			ts_date_issue = new Date(date_issue).getTime();
			ts_date_exp = new Date(date_exp).getTime();

			if (ts_date_exp < ts_date_issue){
				status = false;
				empty.push('Expired Date Must be <= than Issued Date');
			}
		}

		//check image
		if(!$('#curr_img').length){
			if(!$.trim($('input[name=idcard_base64]').val()).length) 
			{
				status = false;
				empty.push('Front of ID Card Image, Please adjust and crop the image before submitting the form');
			}
		}

		if(!$('#curr_img_back').length){
			if(!$.trim($('input[name=idcard_back_base64]').val()).length) 
			{
				status = false;
				empty.push('Back of ID Card Image, Please adjust and crop the image before submitting the form');
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