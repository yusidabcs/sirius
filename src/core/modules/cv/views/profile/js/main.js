const showLoadingModal = function() {
	Swal.fire({
		title: 'Preparing Your CV...',
		icon: 'info',
		allowOutsideClick: false,
		allowEscapeKey: false,
		showConfirmButton: false
	});
	Swal.showLoading();
}

function generateCV(cv_template) {
	$('#loading-cv').show();
	$('#download-cv').addClass('disabled');
	$.ajax({
		url: "/ajax/cv/main/generate-cv/"+cv_template,
		type: 'POST',
		data: {
			dt_id: ''
		}
	})
		.done(response => {
			$("#cv-content").html(response);
			$('#loading-cv').hide();
			$('#download-cv').removeClass('disabled');
		})
		.fail(response => {
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'Connection to Server Failed!'
			});
			$('#loading-cv').hide();
			$('#download-cv').removeClass('disabled');
		});
}
function checkCVHash() {

	$.ajax({
		url: "/ajax/cv/main/checkCVHash",
		type: 'POST',
		data: {}
	})
		.done(response => {
			$("#cp-link-cv").data('hash',response);
		})
		.fail(response => {
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'Connection to Server Failed!'
			});
		});
}

let cv_template = $('#cv-template').val();

$('#cp-link-cv').on('click',function(){

    $.ajax({
		url: "/ajax/cv/main/checkCVHash",
		type: 'POST',
		data: {
            template: cv_template,
        }
	})
    .done(response => {
        let link = $(this).data('link');
        let hash = response;
        let $temp = $("<input>");
        $("#modalCV").append($temp);
        $temp.val(link+hash).select();
        document.execCommand("copy");
        $temp.remove();
        $('#cp-link-cv').attr('title','Copied!').tooltip('enable').tooltip('show');
    })
    .fail(response => {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Connection to Server Failed!'
        });
    });

    
});
$('#cp-link-cv').on('hidden.bs.tooltip', function () {
    $('#cp-link-cv').removeAttr('title').tooltip('disable');
});

$('#cv-template').on('change',function(){
    cv_template = $(this).val();
    generateCV(cv_template);
})

$('#btn_cv').on('click',function(){
    $('#modalCV').modal('show');
    generateCV(cv_template);
});

/*$('#download-cv').on('click',function(){
    const win = window.open('/ajax/cv/main/generate-cv-pdf/'+cv_template);
    if (win) {
        win.focus();
    } else {
        Swal.fire({
            type: 'warning',
            title: 'Blocked',
            text: 'Please allow pop up to download the file!'
        });
    }
});*/

$('#download-cv').on('click',function(){
	showLoadingModal();
	let hash;
	let link = $(this).data('link');
	//get hash
	$.ajax({
		url: "/ajax/cv/main/checkCVHash",
		type: 'POST',
		data: {
            template: cv_template,
        }
	})
    .done(response => {
        hash = response;
		link += hash;
		//link = 'https://www.google.com/';
		$.ajax({
			url: "https://generator.ngide.net/pdf?target="+link,
			type: 'GET',
			dataType: 'json'
		})
		.done(response => {
			Swal.close();
			const win = window.open(response.path);
			if (win) {
				win.focus();

			} else {
				Swal.fire({
					type: 'warning',
					title: 'Blocked',
					text: 'Please allow pop up to download the file!'
				});
			}
		})
		.fail(response => {
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'Connection to Server Failed!'
			});
		}); //end get response pdf server
    })
    .fail(response => {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Connection to Server Failed!'
        });
    });
    
});