$(document).ready(function()
{
	//select
	$('#countryCode_id').materialSelect();
	$('#level').materialSelect();
	$('#stcw_type').materialSelect();
	$('#attended_countryCode_id').materialSelect();
	$('#type').materialSelect();
	
	//english
	var english_current = $('#english_current').val();
	
	if(english_current == 'yes')
	{
		$('#english').prop("checked", true);
		
	} else if( english_current == 'no' ) {
		
		$('#not_english').prop("checked", true);
	}

	if ($('#level').val() === 'stcw') {
		$('.stcw-type').removeClass('d-none');
	} else {
		$('#stcw_type').prop('required',false);
	}

	$('#level').on('change', function() {
		if ($(this).val() === 'stcw') {
			$('.stcw-type').removeClass('d-none');
			$('#stcw_type').prop('required',true);
		} else {
			$('.stcw-type').addClass('d-none');
			$('#stcw_type').prop('required',false);
			
		}
	});

	//calendar

	const today = new Date();
	const dd = String(today.getDate()).padStart(2, '0');
	const mm = String(today.getMonth()).padStart(2, '0');
	const yyyy = today.getFullYear();

	const cal_dos = $('.calendar-dos').pickadate({
		labelMonthNext: 'Go to the next month',
		labelMonthPrev: 'Go to the previous month',
		labelMonthSelect: 'Pick a month from the dropdown',
		labelYearSelect: 'Pick a year from the dropdown',
		selectMonths: true,
		selectYears: true,
		format: 'd mmm yyyy',
		formatSubmit: 'yyyy-mm-dd',
		//restrict education input date of start from 100 year previous until today,
		min: new Date(yyyy-100,mm,dd),
		max: new Date(yyyy,mm,dd)
	})

	const cal_dof = $('.calendar-dof').pickadate({
		labelMonthNext: 'Go to the next month',
		labelMonthPrev: 'Go to the previous month',
		labelMonthSelect: 'Pick a month from the dropdown',
		labelYearSelect: 'Pick a year from the dropdown',
		selectMonths: true,
		selectYears: true,
		format: 'd mmm yyyy',
		formatSubmit: 'yyyy-mm-dd',
		//restrict education input date of finish from 100 year previous until today,
		min: new Date(yyyy-100,mm,dd),
		max: new Date(yyyy,mm,dd)
	})

	const dof_picker = cal_dof.pickadate('picker');
	//start date change
	cal_dos.change(function()
	{
		if ($('#not_active').prop('checked'))
		{
			dof_picker.set('min', new Date($(this).val()));
			cal_dof.prop('disabled',false);
		}
	});

	$('.calendar-exp').pickadate({
		labelMonthNext: 'Go to the next month',
		labelMonthPrev: 'Go to the previous month',
		labelMonthSelect: 'Pick a month from the dropdown',
		labelYearSelect: 'Pick a year from the dropdown',
		selectMonths: true,
		selectYears: true,
		format: 'd mmm yyyy',
		formatSubmit: 'yyyy-mm-dd',
		//restrict education input date of finish from 100 year previous until today,
		min: new Date(yyyy,mm,dd),
		max: new Date(yyyy+100,mm,dd)
	})

	//active
	var active_current = $('#active_current').val();
	
	if(active_current == 'active')
	{
		$('#not_active_job').hide().parent('div').prev().removeClass('required');
		$('#active').prop("checked", true);
		$('#education_information').show();
		$('#active_job').show();
		$('.certificate_info').show();
		$('#education_image').show();


		$('#certificate_date').removeAttr('required');
		$('#certificate_number').removeAttr('required');
		$('#certificate_number_placeholder').removeClass('required');
		$('#certificate_date_placeholder').removeClass('required');
		$('#landscape_placeholder').removeClass('required');
		$('#potrait_placeholder').removeClass('required');
		
	} else if( active_current == 'not_active' ) {
		
		$('#active_job').hide();
		$('#not_active').prop("checked", true);
		$('#education_information').show();
		$('#not_active_job').show().parent('div').prev().toggleClass('required');
		$('.certificate_info').show();
		$('#education_image').show();
		//init start finish date
		if (!cal_dos.val())
		{
			cal_dof.prop('disabled',true);
		}else{
			cal_dof.prop('disabled',false);
			dof_picker.set('min', new Date(cal_dos.val()));
		}

		$('#certificate_number_placeholder').toggleClass('required');
	}
	
	$('.active').change(function(event){
		
		$('#education_information').show();
		console.log($(this).val())
		if($(this).val() == 'active')
		{
			$('.certificate_info').hide();
			$('#not_active_job').hide().parent('div').prev().removeClass('required');
			$('#active_job').show();
			$('#to_date').val('');
			$('#certificate_expire').prop( 'checked', false );
			$('#certificate_expiry').val('');
			$('#certificate_number').val('');
			$('#certificate_date').val('');

			$('#certificate_number_placeholder').removeClass('required');
			$('#certificate_date_placeholder').removeClass('required');
			$('#landscape_placeholder').removeClass('required');
			$('#potrait_placeholder').removeClass('required');


			$('#certificate_date').attr('required',false);
			$('#certificate_date').attr('required',false);
			$('#certificate_number').attr('required',);
		} else {
			$('#active_job').hide();
			$('#not_active_job').show().parent('div').prev().addClass('required');
			$('.certificate_info').show();
			//disable finish date when start date not set
			if (!cal_dos.val())
			{
				cal_dof.prop('disabled',true);
			}else{
				cal_dof.prop('disabled',false);
				dof_picker.set('min', new Date(cal_dos.val()));
			}
			$('#certificate_number_placeholder').addClass('required');
			$('#certificate_date_placeholder').addClass('required');
			$('#landscape_placeholder').addClass('required');
			$('#potrait_placeholder').addClass('required');


			$('#certificate_date').attr('required',true);
			$('#certificate_number').attr('required',true);
		}
		
		$('#education_image').show();
        $('#level').materialSelect();
        $('#type').materialSelect();
        $('#stcw_type').materialSelect();
        $('#attended_countryCode_id').materialSelect();
		
	});
	
	
	//expire
	
	if( $('#certificate_expire').is(':checked') )
	{
		$('#certificate_expire_no').hide();
		$('#certificate_expire_yes').show();
		
	} else {
		$('#certificate_expire_yes').hide();
		$('#certificate_expire_no').show();
	}
	
	$('#certificate_expire').change(function(event){
		
		if($(this).is(':checked'))
		{
			$('#certificate_expire_no').hide();
			$('#certificate_expire_yes').show().parent('div').prev().addClass('required');
			
		} else {
			$('#certificate_expire_yes').hide().parent('div').prev().removeClass('required');
			$('#certificate_expire_no').show();
			$('#certificate_expiry').val('');
		}

	});
	
	
	
	//stuff for image
	let orientation = 'portrait';
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
			width: 'auto',
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
	
	//-- Portrait --
	
	var uploadCropPortrait;
	//detect viewport and compare with inserted attribute data
	const b_width = $('#education_croppie_portrait').data('banner-width');
	const b_height = $('#education_croppie_portrait').data('banner-height');
	const v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/2;
	const v_height = b_height/b_width*v_width;

	//choose appropriate width and height based on device
	const crop_width = (b_width>v_width) ? v_width : b_width; 
	const crop_height = (b_height>v_height) ? v_height : b_height;
	
	// const crop_width = b_width; 
	// const crop_height = b_height; 

	uploadCropPortrait = $('#education_croppie_portrait').croppie({
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
	
	$('#education_result_portrait').on('click', function (ev) {
		const file_choosen = $('#education_input_portrait').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
			$('#education_base64').val('');
			
			uploadCropPortrait.croppie('result', {
				type: 'canvas',
				size: 'original'
			}).then(function (resp) {
				resizeImage(resp, b_width, b_height).then((resp) => {
					popupResult({
						src: resp
					},'Portrait Image');
					
					$('#education_base64').val(resp);
					orientation = 'portrait';
					$('#curr_img').prop('src',resp);
					$('#d_curr_img').show();
					$('#education_croppie_wrap_portrait').hide();
					$('#education_result_portrait').hide();
					document.getElementById("education_image").scrollIntoView();
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
	
	$('#education_input_portrait').on('change', function () {
		const file_choosen = $('#education_input_portrait').val();

		//check if image is choosen before start cropping
		if (file_choosen !== ""){
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			readFile(this,uploadCropPortrait,'#education_croppie_wrap_portrait');
			$('#education_result_portrait').show();
			$('#update_crop').hide();
		}
	});
	$('#update_crop').on('click',function(){
		$('#education_croppie_wrap_'+orientation).show();
		$('#education_result_'+orientation).show();
		$(this).hide();
		document.getElementById("education_croppie_wrap_"+orientation).scrollIntoView();
	})
	//-- Landscape --
	
	var uploadCropLandscape;
	//detect viewport and compare with inserted attribute data
	const ls_b_width = $('#education_croppie_landscape').data('banner-width');
	const ls_b_height = $('#education_croppie_landscape').data('banner-height');
	const ls_v_height = ls_b_height/ls_b_width*v_width;

	//choose appropriate width and height based on device
	const ls_crop_width = (ls_b_width>v_width) ? v_width : ls_b_width; 
	const ls_crop_height = (ls_b_height>ls_v_height) ? ls_v_height : ls_b_height;
	
	// const ls_crop_width = ls_b_width; 
	// const ls_crop_height = ls_b_height; 


	uploadCropLandscape = $('#education_croppie_landscape').croppie({
		viewport: {
			width: ls_crop_width,
			height: ls_crop_height
		},
		boundary: {
			width: ls_crop_width*1.1,
			height: ls_crop_height*1.1
		},
		enableExif: true,
		enableOrientation: true
	});
	
	$('#education_result_landscape').on('click', function (ev) {
		const file_choosen = $('#education_input_landscape').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
			$('#education_base64').val('');
			
			uploadCropLandscape.croppie('result', {
				type: 'canvas',
				size: 'original'
			}).then(function (resp) {
				resizeImage(resp, ls_b_width, ls_b_height).then((resp) => {
					popupResult({
						src: resp
					},'Landscape Image');
					
					$('#education_base64').val(resp);
					orientation = 'landscape';
					$('#curr_img').prop('src',resp);
					$('#d_curr_img').show();
					$('#education_croppie_wrap_landscape').hide();
					$('#education_result_landscape').hide();
					document.getElementById("education_image").scrollIntoView();
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
	
	$('#education_input_landscape').on('change', function () {
		const file_choosen = $('#education_input_landscape').val();

		//check if image is choosen before start cropping
		if (file_choosen !== ""){
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			readFile(this,uploadCropLandscape,'#education_croppie_wrap_landscape');
			$('#education_result_landscape').show();
			$('#update_crop').hide();
		}
	});

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
		if(!$('input[name=english]').is(':checked'))
		{
			status = false;
			empty.push('Course Language');
		}

		if(!$('input[name=active]').is(':checked'))
		{
			status = false;
			empty.push('Currently Studying');
		}

		//certificate start, finish date
		date_from = $('#from_date').val();
		if(!$.trim(date_from).length) 
		{
			status = false;
			empty.push('Start Date');
		}
			
		if( $('#not_active').prop('checked') )
		{
			date_to = $('#to_date').val();
			if(!$.trim(date_to).length) 
			{
				status = false;
				empty.push('Finish Date');
			}else{
				ts_date_from = new Date(date_from).getTime();
				ts_date_to = new Date(date_to).getTime();

				if (ts_date_to < ts_date_from){
					status = false;
					empty.push('Finish Date Must be <= than Start Date');
				}
			}
		}

		//certificate date, expired
		cert_date = $('#certificate_date').val();
		
		education_status = $('.active:checked').val();

		if(education_status == 'not_active'){
			if(!$.trim(cert_date).length) 
			{
				status = false;
				empty.push('Certificate Date');
			}
		}
		
		if( $('#certificate_expire').prop('checked') )
		{
			cert_exp = $('#certificate_expiry').val();
			if(!$.trim(cert_exp).length) 
			{
				status = false;
				empty.push('Certificate Expiry Date');
			}else{
				ts_cert_date = new Date(cert_date).getTime();
				ts_cert_exp = new Date(cert_exp).getTime();

				if (ts_cert_exp < ts_cert_date){
					status = false;
					empty.push('Certificate Expired Date Must be <= than Certificate Date');
				}
			}
		}

		//check image
		if(education_status == 'not_active'){
			if(!$('#curr_img').length){
				if(!$.trim($('input[name=education_base64]').val()).length) 
				{
					status = false;
					empty.push('Certificate Image, Please adjust and crop the image before submitting the form');
				}
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