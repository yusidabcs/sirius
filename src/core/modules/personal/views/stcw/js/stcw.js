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
	});

	$('#from_date').on('change', function() {

		$('#to_date').pickadate({
			labelMonthNext: 'Go to the next month',
			labelMonthPrev: 'Go to the previous month',
			labelMonthSelect: 'Pick a month from the dropdown',
			labelYearSelect: 'Pick a year from the dropdown',
			selectMonths: true,
			selectYears: true,
			formatSubmit: 'yyyy-mm-dd',
	
			min: $(this).val(),
			max: new Date(yyyy+15, mm, dd)
		});

	})
	
	//stuff for image

	function popupResult(result) {
		var html;
		if (result.html) {
			html = result.html;
		}
		if (result.src) {
			html = '<img src="' + result.src + '" />';
		}
		swal.fire({
			title: 'English Test Image',
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
	
	
	function readFile(input) {
		
		if (input.files && input.files[0])
		{		
            var reader = new FileReader();
            
            reader.onload = function (e) {
	            
				$uploadCrop.croppie('bind', {
	            	url: e.target.result
	            });   
	            $('#stcw_croppie_wrap').show();
            	
            }
            reader.readAsDataURL(input.files[0]);
        } else {
	        swal("Sorry - you're browser doesn't support the FileReader API");
	    }
	}

	var $uploadCrop;
	//detect viewport and compare with inserted attribute data
	const b_width = $('#stcw_croppie').data('banner-width');
	const b_height = $('#stcw_croppie').data('banner-height');
	const v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/2;
	const v_height = b_height/b_width*v_width;

	//choose appropriate width and height based on device
	const crop_width = (b_width>v_width) ? v_width : b_width; 
	const crop_height = (b_height>v_height) ? v_height : b_height; 

	$uploadCrop = $('#stcw_croppie').croppie({
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

	$('#stcw_input').on('change', function () {
		const file_choosen = $('#stcw_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== ""){
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			readFile(this);
		}
	});
	
	$('#stcw_result').on('click', function (ev) {
		const file_choosen = $('#stcw_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
			$uploadCrop.croppie('result', {
				type: 'canvas',
				size: 'viewport'
			}).then(function (resp) {
				popupResult({
					src: resp
				});
				
				$('#stcw_base64').val(resp);
			});
		}else{
			Swal.fire({
				icon: 'warning',
				text: 'Please choose an image first',
			});
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
			if(!$.trim($('input[name=stcw_base64]').val()).length) 
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