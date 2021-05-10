$(document).ready(function()
{
	function popupResult(result) {
		var html;
		if (result.html) {
			html = result.html;
		}
		if (result.src) {
			if(result.src == 'data:,')
			{
				html = '<p>No Image Selected</p>';
			} else {
				html = '<img class="img img-fluid" src="' + result.src + '" />';
			}
		}
		swal.fire({
			title: 'New Avatar',
			html: html,
			allowOutsideClick: true
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
	            
				$('#avatar_croppie_wrap').show();
				$('#avatar_result').show();
            	$('#update_crop').hide();
            }
            
            reader.readAsDataURL(input.files[0]);
            
        } else {
	        swal("Sorry - you're browser doesn't support the FileReader API");
	    }
	}

	$uploadCrop = $('#avatar_croppie').croppie({
		viewport: {
			width: 350,
			height: 350,
			type: 'square'
		},
		boundary: {
	            width: 400,
	            height: 400
	    },
	    showZoomer: false,
		enableExif: true
	});

	$('#avatar_input').on('change', function () {
		const file_choosen = $('#avatar_input').val();

        //check if image is choosen before start cropping
        if (file_choosen !== "")
        {
			$('button[type="submit"]').prop("disabled",true);
			
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			readFile(this);
		}
	});
	
	$('#avatar_result').on('click', function (ev) {
			$uploadCrop.croppie('result', {
				type: 'canvas',
				size: 'original'
			}).then(function (resp) {
				resizeImage(resp, 200, 200).then((resp) => {
					popupResult({
						src: resp
					});
					
					$('#avatar_base64').val(resp);
					$('#img_show').attr('src',resp);
					$('#img_show').show();
					$('#avatar_croppie_wrap').hide();
					$('#avatar_result').hide();
					document.getElementById("avatar_image").scrollIntoView();
					$('#update_crop').show();
				})
			});
		});

		$('#update_crop').on('click',function(){
			$('#avatar_croppie_wrap').show();
			$('#avatar_result').show();
			$(this).hide();
			document.getElementById("avatar_croppie_wrap").scrollIntoView();
		})
});