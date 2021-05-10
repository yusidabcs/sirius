$(document).ready(function()
{
	//select
	$('#countryCode_id').materialSelect();
	$('#type').materialSelect();
	$('#job_category_id').materialSelect();
	
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
		//restrict employment input date of start from 100 year previous until today,
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
		//restrict employment input date of finish from 100 year previous until today,
		min: new Date(yyyy-100,mm,dd),
		max: new Date(yyyy,mm,dd)
	})
	const dof_picker = cal_dof.pickadate('picker');

	cal_dos.change(function()
	{
		if ($('#not_active').prop('checked'))
		{
			dof_picker.set('min', new Date($(this).val()));
			cal_dof.prop('disabled',false);
		}
	});

	//set up active on open
	var active_current = $('#active_current').val();
	
	if(active_current == 'active')
	{
		$('#active').prop("checked", true);
		$('#employment_information').show();
		$('#not_active_job').hide();
		$('#active_job').show();
		$('#employment_image').show();
		
	} else if( active_current == 'not_active' ) {
		
		$('#not_active').prop("checked", true);
		$('#employment_information').show();
		$('#active_job').hide();
		$('#not_active_job').show();
		$('#employment_image').show();
		//init start finish date
		if (!cal_dos.val())
		{
			cal_dof.prop('disabled',true);
		}else{
			cal_dof.prop('disabled',false);
			dof_picker.set('min', new Date(cal_dos.val()));
		}
	}
	
	$('label .active').change(function(event){
		
		$('#employment_information').show();
		
		if($(this).val() == 'active')
		{
			$('#not_active_job').hide().parent('div').prev().removeClass('required');
			$('#active_job').show();
			$('#to_date').val('');
		} else {
			$('#active_job').hide();
			$('#not_active_job').show().parent('div').prev().addClass('required');
			//disable finish date when start date not set
			if (!cal_dos.val())
			{
				cal_dof.prop('disabled',true);
			}else{
				cal_dof.prop('disabled',false);
				dof_picker.set('min', new Date(cal_dos.val()));
			}
		}
		
		$('#employment_image').show();
		
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
	const b_width = $('#employment_croppie_portrait').data('banner-width');
	const b_height = $('#employment_croppie_portrait').data('banner-height');
	const v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/2;
	const v_height = b_height/b_width*v_width;

	//choose appropriate width and height based on device
	const crop_width = (b_width>v_width) ? v_width : b_width; 
	const crop_height = (b_height>v_height) ? v_height : b_height;
	// const crop_width = b_width; 
	// const crop_height = b_height;  

	uploadCropPortrait = $('#employment_croppie_portrait').croppie({
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
	
	$('#employment_result_portrait').on('click', function (ev) {
		const file_choosen = $('#employment_input_portrait').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
			$('#employment_base64').val('');
			
			uploadCropPortrait.croppie('result', {
				type: 'canvas',
				size: 'original'
			}).then(function (resp) {
				resizeImage(resp, b_width, b_height).then((resp) => {
					popupResult({
						src: resp
					},'Portrait Image');
					orientation = 'portrait';
					$('#employment_base64').val(resp);
					$('#curr_img').prop('src',resp);
					$('#d_curr_img').show();
					$('#employment_croppie_wrap_portrait').hide();
					$('#employment_result_portrait').hide();
					document.getElementById("employment_image").scrollIntoView();
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
	
	$('#employment_input_portrait').on('change', function () {
		const file_choosen = $('#employment_input_portrait').val();

		//check if image is choosen before start cropping
		if (file_choosen !== ""){
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			readFile(this,uploadCropPortrait,'#employment_croppie_wrap_portrait');
			$('#employment_result_portrait').show();
			$('#update_crop').hide();
		}
	});

	$('#update_crop').on('click',function(){
		$('#employment_croppie_wrap_'+orientation).show();
		$('#employment_result_'+orientation).show();
		$(this).hide();
		document.getElementById("employment_croppie_wrap_"+orientation).scrollIntoView();
	})
	
	//-- Landscape --
	
	var uploadCropLandscape;
	//detect viewport and compare with inserted attribute data
	const ls_b_width = $('#employment_croppie_landscape').data('banner-width');
	const ls_b_height = $('#employment_croppie_landscape').data('banner-height');
	const ls_v_height = ls_b_height/ls_b_width*v_width;

	//choose appropriate width and height based on device
	const ls_crop_width = (ls_b_width>v_width) ? v_width : ls_b_width; 
	const ls_crop_height = (ls_b_height>ls_v_height) ? ls_v_height : ls_b_height;
	// const ls_crop_width = ls_b_width; 
	// const ls_crop_height = ls_b_height;  

	uploadCropLandscape = $('#employment_croppie_landscape').croppie({
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
	
	$('#employment_result_landscape').on('click', function (ev) {
		const file_choosen = $('#employment_input_landscape').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
			$('#employment_base64').val('');
			
			uploadCropLandscape.croppie('result', {
				type: 'canvas',
				size: 'original'
			}).then(function (resp) {
				resizeImage(resp, ls_b_width, ls_b_height).then((resp) => {
					popupResult({
						src: resp
					},'Landscape Image');
					
					orientation = 'landscape';
					$('#employment_base64').val(resp);
					$('#curr_img').prop('src',resp);
					$('#d_curr_img').show();
					$('#employment_croppie_wrap_landscape').hide();
					$('#employment_result_landscape').hide();
					document.getElementById("employment_image").scrollIntoView();
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
	
	$('#employment_input_landscape').on('change', function () {
		const file_choosen = $('#employment_input_landscape').val();

		//check if image is choosen before start cropping
		if (file_choosen !== ""){
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			readFile(this,uploadCropLandscape,'#employment_croppie_wrap_landscape');
			$('#employment_result_landscape').show();
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
		if(!$('input[name=active]').is(':checked'))
		{
			status = false;
			empty.push('Currently Employed');
		}


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
            //check image only if not active
            if(!$('#curr_img').length && $('#employment_base64').val() == '')
            {
                if( $('#not_active').prop('checked') ){
                    status = false;
                    empty.push('Employment Proof Image, Please adjust and crop the image before submitting the form');
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