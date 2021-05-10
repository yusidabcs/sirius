$(document).ready(function()
{
	function popupResult(result) {
		var html;
		if (result.html) {
			html = result.html;
		}
		if (result.src) {
			html = '<img src="' + result.src + '" />';
		}
		swal.fire({
			title: 'New Banner',
			html: html,
			allowOutsideClick: true,
			width: 660
		});
		setTimeout(function(){
			$('.sweet-alert').css('margin', function() {
				var top = -1 * ($(this).height() / 2),
					left = -1 * ($(this).width() / 2);

				return top + 'px 0 0 ' + left + 'px';
			});
		}, 1);
		$('button[type="submit"]').prop("disabled",false);
	}
	
	var $uploadCrop;

	function readFile(input) {
		
		if (input.files && input.files[0]) {
				
            var reader = new FileReader();
            
            reader.onload = function (e) {
	            
				$uploadCrop.croppie('bind', {
	            	url: e.target.result
	            });
	            
	            $('#banner_croppie_wrap').show();
            	
            }
            
            reader.readAsDataURL(input.files[0]);
            
        } else {
	        swal("Sorry - you're browser doesn't support the FileReader API");
	    }
	}

	$uploadCrop = $('#banner_croppie').croppie({
		viewport: {
			width: 620,
			height: 100
		},
		boundary: {
	            width: 640,
	            height: 120
	    },
		enableExif: true
	});

	$('#banner_input').on('change', function () {
		const file_choosen = $('#banner_input').val();

        //check if image is choosen before start cropping
        if (file_choosen !== "")
        {
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			$('button[type="submit"]').prop("disabled",true);
			
			readFile(this);
		}
	});
	
	$('#banner_result').on('click', function (ev) {
			$uploadCrop.croppie('result', {
				type: 'canvas',
				size: 'viewport'
			}).then(function (resp) {
				popupResult({
					src: resp
				});
				
				$('#banner_base64').val(resp);
			});
		});


});