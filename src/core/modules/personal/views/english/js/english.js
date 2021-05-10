$(document).ready(function()
{
	//select
	$('#type').materialSelect();

	
	const today = new Date();
	const dd = String(today.getDate()).padStart(2, '0');
	const mm = String(today.getMonth()).padStart(2, '0');
	const yyyy = today.getFullYear();

    $('.calendar').pickadate({
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
        selectYears: true,
		formatSubmit: 'yyyy-mm-dd',
		//restrict input date from 5 year previous until today
		min: new Date(yyyy-5,mm,dd),
		max: new Date(yyyy,mm,dd)
	})
	
	//delete breakdown
	$('body').on('click','.delete-breakdown', function(){
		var target = $(this).data('key')
		$('#breakdown_'+target).remove();
	})
	$('body').on('click','.add-breakdown', function(){
		var i = Math.random()
		$('#breakdown').append(`<div class="row" id="breakdown_${i}">
		<div class="col-6 d-flex align-items-center">
			<input type="text" class="form-control" id="breakdown_${i}" name="breakdown_name[${i}]"  required />
		</div>
		<div class="col-6 d-flex align-items-center ">
			<input type="number" class="form-control" id="score_${i}" name="score[${i}]" value="" required />

			<button class="btn btn-link delete-breakdown" type="button" data-key="${i}" ><i class="fa fa-trash"></i></button>
		</div>
	</div>`);
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
	const b_width = $('#english_croppie_portrait').data('banner-width');
	const b_height = $('#english_croppie_portrait').data('banner-height');
	const v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/2;
	const v_height = b_height/b_width*v_width;

	//choose appropriate width and height based on device
	const crop_width = (b_width>v_width) ? v_width : b_width; 
	const crop_height = (b_height>v_height) ? v_height : b_height; 
	// const crop_width = b_width; 
	// const crop_height = b_height; 

	uploadCropPortrait = $('#english_croppie_portrait').croppie({
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
	
	$('#english_result_portrait').on('click', function (ev) {
		const file_choosen = $('#english_input_portrait').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
			$('#english_base64').val('');
			
			uploadCropPortrait.croppie('result', {
				type: 'canvas',
				size: 'original'
			}).then(function (resp) {
				resizeImage(resp, b_width, b_height).then((resp) => {
					popupResult({
						src: resp
					},'Portrait Image');
					
					$('#english_base64').val(resp);
					orientation = 'portrait';
					$('#curr_img').prop('src',resp);
					$('#d_curr_img').show();
					$('#english_croppie_wrap_portrait').hide();
					$('#english_result_portrait').hide();
					document.getElementById("english_image").scrollIntoView();
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
	
	$('#english_input_portrait').on('change', function () {
		const file_choosen = $('#english_input_portrait').val();

		//check if image is choosen before start cropping
		if (file_choosen !== ""){
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			readFile(this,uploadCropPortrait,'#english_croppie_wrap_portrait');
			$('#english_croppie_wrap_portrait').removeClass('d-none');
			$('#english_result_portrait').show();
			$('#update_crop').hide();
		}
	});
	$('#update_crop').on('click',function(){
		$('#english_croppie_wrap_'+orientation).show();
		$('#english_result_'+orientation).show();
		$(this).hide();
		document.getElementById("english_croppie_wrap_"+orientation).scrollIntoView();
	})
	//-- Landscape --
	
	var uploadCropLandscape;
	//detect viewport and compare with inserted attribute data
	const ls_b_width = $('#english_croppie_landscape').data('banner-width');
	const ls_b_height = $('#english_croppie_landscape').data('banner-height');
	const ls_v_height = ls_b_height/ls_b_width*v_width;

	//choose appropriate width and height based on device
	const ls_crop_width = (ls_b_width>v_width) ? v_width : ls_b_width; 
	const ls_crop_height = (ls_b_height>ls_v_height) ? ls_v_height : ls_b_height;
	// const ls_crop_width = ls_b_width; 
	// const ls_crop_height = ls_b_height; 

	uploadCropLandscape = $('#english_croppie_landscape').croppie({
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
	
	$('#english_result_landscape').on('click', function (ev) {
		const file_choosen = $('#english_input_landscape').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
			$('#english_base64').val('');
			
			uploadCropLandscape.croppie('result', {
				type: 'canvas',
				size: 'original'
			}).then(function (resp) {
				resizeImage(resp, ls_b_width, ls_b_height).then((resp) => {
					popupResult({
						src: resp
					},'Landscape Image');
					
					$('#english_base64').val(resp);
					orientation = 'landscape';
					$('#curr_img').prop('src',resp);
					$('#d_curr_img').show();
					$('#english_croppie_wrap_landscape').hide();
					$('#english_result_landscape').hide();
					document.getElementById("english_image").scrollIntoView();
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
	
	$('#english_input_landscape').on('change', function () {
		const file_choosen = $('#english_input_landscape').val();

		//check if image is choosen before start cropping
		if (file_choosen !== ""){
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			readFile(this,uploadCropLandscape,'#english_croppie_wrap_landscape');
			$('#english_croppie_wrap_landscape').removeClass('d-none');
			$('#english_result_landscape').show();
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

	$('form').submit(function(e)
	{
		//Client side form validation
		let empty = [];
		let status = true;

		if(!$.trim($('#when').val()).length) {
			status = false;
			empty.push('Date of Exam, Please specify date of exam');	
		}
		
		//check image
		if(!$('#curr_img').length){
			if(!$.trim($('input[name=english_base64]').val()).length) 
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