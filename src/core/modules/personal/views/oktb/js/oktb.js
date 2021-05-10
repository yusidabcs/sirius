$(document).ready(function()
{
	//select
	const loadPreview = function() {
		let pdf_preview = $('#pdf-preview');

		if (pdf_preview.data('url') !== '' && pdf_preview.data('url') !== undefined) {
			pdf_preview.html('<iframe src="'+pdf_preview.data('url')+'" width="100%" height="400px"></iframe>');
		}
	}

	const toBase64 = function(file) {
		return new Promise(function(resolve, reject) {
			const fileReader = new FileReader()
			fileReader.readAsDataURL(file);

			fileReader.onload = () => {
				resolve(fileReader.result);
			}

			fileReader.onerror = (error) => {
				reject(error)
			}
		})
	}

	loadPreview();

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
		//restrict oktb validity input date from 10 year previous until today,
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
		//restrict oktb expire input date to 10 year from now
		min: new Date(yyyy,mm,dde),
		max: new Date(yyyy+10,mm,dd)
	})
	const exp_picker = cal_exp.pickadate('picker');
	//set expiry date based on oktb active or inactive
	$('input[name="active"]').change(function()
	{
		if ($(this).val() == 'active')
		{
			//f active, set an expiration date from now to the next 10 years
			exp_picker.set('min', new Date(yyyy,mm,dde));
			exp_picker.set('max', new Date(yyyy+10,mm,dd));
		}else{
			//if inactive, set an expiration date from the previous 10 years to today
			exp_picker.set('min', new Date(yyyy-10,mm,dd));
			exp_picker.set('max', new Date(yyyy,mm,dd));
		}
	});
	//set up active on open
	var active_current = $('#active_current').val();
	
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

	$('#oktb_input_file').on('change', function(e) {
		toBase64(this.files[0]).then(function(data) {
			let file_input = $('input[name="file_base64"]');
			file_input.val(data);

			let pdf_preview = $('#pdf-preview');
			pdf_preview.html('<p class="text-center">Preview File....</p>');

			let oktb_number = $('input[name="oktb_number"]').val();

			if (!oktb_number || oktb_number === undefined) {
				Swal.fire('Warning', 'Please type OKTB number first!');
				file_input.val('');
				pdf_preview.html('');
				$('#oktb_input_file').val('');

			} else {
				
				$.ajax({
					method: 'POST',
					url: '/ajax/personal/main/oktb-file',
					data: {
						'file_base64': $('input[name="file_base64"]').val(),
						'oktb_number': oktb_number 
					},
					success: function(response) {
						let html = '<iframe src="'+response.url+'" width="100%" height="400px"></iframe>';
						$('input[name="filename"]').val(response.filename);
						$('#pdf-preview').html(html);
					},
					error: function() {
						Swal.fire('Oops somthing went wrong!', 'warning');
						file_input.val('');
						pdf_preview.html('');
						$('#oktb_input_file').val('');
					}
				});
			}
		}).catch((error) => {
			Swal.fire('Error preview file', 'warning');
		});
	});


	$('button[type=submit]').click(function(){
		
		//Client side form validation
		let empty = [];
		let status = true;
		if(!$('input[name=active]').is(':checked'))
		{
			status = false;
			empty.push('OKTB Active');
		}

		//check image
		if(!$('#curr_img').length){
			if(!$.trim($('input[name=file_base64]').val()).length && $('input[name="filename"]').val() === null) 
			{
				status = false;
				empty.push('OKTB file, Please insert oktb file before submitting the form');
			}
		}

		date_issue = $('#from_date').val();
		date_exp = $('#to_date').val();

		if(!$.trim(date_issue).length) 
		{
			status = false;
			empty.push('Date of Issue');
		}

		if(!$.trim(date_exp).length) 
		{
			status = false;
			empty.push('Date of Expiry');
		}

		ts_oktb_issue = new Date(date_issue).getTime();
		ts_oktb_exp = new Date(date_exp).getTime();

		if (ts_oktb_exp < ts_oktb_issue){
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