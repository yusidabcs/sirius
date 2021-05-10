$(document).ready(function()
{
	//select
	$('#countryCode_id').materialSelect();
	$('#type').materialSelect();
	
	//fit
	var fit_current = $('#fit_current').val();
	
	if(fit_current == 'fit')
	{
		$('#fit').prop("checked", true);
		
	} else if( fit_current == 'not_fit' ) {
		
		$('#not_fit').prop("checked", true);
	}
	
	//expire
	
	if( $('#certificate_expire').is(':checked') )
	{
		$('#certificate_expire_no').hide();
		$('#certificate_expire_yes').show().parent('div').prev().addClass('required');
		
	} else {
		$('#certificate_expire_yes').hide().parent('div').prev().removeClass('required');
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
	
	//calendar
		
	
	const today = new Date();
	const dd = String(today.getDate()).padStart(2, '0');
	const mm = String(today.getMonth()).padStart(2, '0');
	const yyyy = today.getFullYear();

    $('.calendar-dos').pickadate({
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
		selectYears: true,
		format: 'd mmm yyyy',
		formatSubmit: 'yyyy-mm-dd',
		//restrict medical input date of start from 10 year previous until today,
		min: new Date(yyyy-10,mm,dd),
		max: new Date(yyyy,mm,dd)
	})
	
	$('.calendar-exp').pickadate({
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
		selectYears: true,
		format: 'd mmm yyyy',
		formatSubmit: 'yyyy-mm-dd',
		//restrict medical input date of expired from today to 10 year from now,
		min: new Date(yyyy,mm,dd),
		max: new Date(yyyy+10,mm,dd)
	})
	
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
	let orientation = 'portrait';
	//-- Portrait --
	
	var uploadCropPortrait;
	//detect viewport and compare with inserted attribute data
	const b_width = $('#medical_croppie_portrait').data('banner-width');
	const b_height = $('#medical_croppie_portrait').data('banner-height');
	const v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/2;
	const v_height = b_height/b_width*v_width;

	//choose appropriate width and height based on device
	const crop_width = (b_width>v_width) ? v_width : b_width; 
	const crop_height = (b_height>v_height) ? v_height : b_height; 
	// const crop_width = b_width; 
	// const crop_height = b_height; 

	uploadCropPortrait = $('#medical_croppie_portrait').croppie({
		viewport: {
			width: crop_width,
			height: crop_height
		},
		boundary: {
			width: crop_width*1.1,
			height: crop_height*1.1
	    },
		enableExif: true
	});
	
	$('#medical_result_portrait').on('click', function (ev) {
		const file_choosen = $('#medical_input_portrait').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
			$('#medical_base64').val('');
			
			uploadCropPortrait.croppie('result', {
				type: 'canvas',
				size: 'original'
			}).then(function (resp) {
				resizeImage(resp, b_width, b_height).then((resp) => {
					popupResult({
						src: resp
					},'Portrait Image');
					
					$('#medical_base64').val(resp);
					orientation = 'portrait';
					$('#curr_img').prop('src',resp);
					$('#d_curr_img').show();
					$('#medical_croppie_wrap_portrait').hide();
					$('#medical_result_portrait').hide();
					document.getElementById("medical_image").scrollIntoView();
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
	
	$('#medical_input_portrait').on('change', function () {
		const file_choosen = $('#medical_input_portrait').val();

		//check if image is choosen before start cropping
		if (file_choosen !== ""){
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			readFile(this,uploadCropPortrait,'#medical_croppie_wrap_portrait');
			$('#medical_result_portrait').show();
			$('#update_crop').hide();
		}
	});
	$('#update_crop').on('click',function(){
		$('#medical_croppie_wrap_'+orientation).show();
		$('#medical_result_'+orientation).show();
		$(this).hide();
		document.getElementById("medical_croppie_wrap_"+orientation).scrollIntoView();
	})
	//-- Landscape --
	
	var uploadCropLandscape;
	//detect viewport and compare with inserted attribute data
	const ls_b_width = $('#medical_croppie_landscape').data('banner-width');
	const ls_b_height = $('#medical_croppie_landscape').data('banner-height');
	const ls_v_height = ls_b_height/ls_b_width*v_width;

	//choose appropriate width and height based on device
	const ls_crop_width = (ls_b_width>v_width) ? v_width : ls_b_width; 
	const ls_crop_height = (ls_b_height>ls_v_height) ? ls_v_height : ls_b_height;
	// const ls_crop_width = ls_b_width; 
	// const ls_crop_height = ls_b_height; 

	uploadCropLandscape = $('#medical_croppie_landscape').croppie({
		viewport: {
			width: ls_crop_width,
			height: ls_crop_height
		},
		boundary: {
			width: ls_crop_width*1.1,
			height: ls_crop_height*1.1
	    },
		enableExif: true
	});
	
	$('#medical_result_landscape').on('click', function (ev) {
		const file_choosen = $('#medical_input_landscape').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
			$('#medical_base64').val('');
			
			uploadCropLandscape.croppie('result', {
				type: 'canvas',
				size: 'original'
			}).then(function (resp) {
				resizeImage(resp, ls_b_width, ls_b_height).then((resp) => {
					popupResult({
						src: resp
					},'Landscape Image');
					
					$('#medical_base64').val(resp);
					orientation = 'landscape';
					$('#curr_img').prop('src',resp);
					$('#d_curr_img').show();
					$('#medical_croppie_wrap_landscape').hide();
					$('#medical_result_landscape').hide();
					document.getElementById("medical_image").scrollIntoView();
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
	
	$('#medical_input_landscape').on('change', function () {
		const file_choosen = $('#medical_input_landscape').val();

		//check if image is choosen before start cropping
		if (file_choosen !== ""){
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			readFile(this,uploadCropLandscape,'#medical_croppie_wrap_landscape');
			$('#medical_result_landscape').show();
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
		if(!$('input[name=fit]').is(':checked'))
		{
			status = false;
			empty.push('Certificate Result');
		}


		date_issue = $('#certificate_date').val();
		if(!$.trim(date_issue).length) 
		{
			status = false;
			empty.push('Start Date');
		}

		if( $('#certificate_expire').prop('checked') )
		{
			date_exp = $('#certificate_expiry').val();
			if(!$.trim(date_exp).length) 
			{
				status = false;
				empty.push('Expiry Date');
			}else{
				ts_date_issue = new Date(date_issue).getTime();
				ts_date_exp = new Date(date_exp).getTime();

				if (ts_date_exp < ts_date_issue){
					status = false;
					empty.push('Expiry Date Must be <= than Issued Date');
				}
			}
		}

		//check image
		if(!$('#curr_img').length){
			if(!$.trim($('input[name=medical_base64]').val()).length) 
			{
				status = false;
				empty.push('Certificate Image, Please adjust and crop the image before submitting the form');
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