$(document).ready(function()
{    
	function checkJson(json){
        try{
            json = JSON.parse(json)
        }catch (e) {
            return false;
        }
        return json;
    }
    
    const loadingModal = function() {
        Swal.fire({
            title: 'Loading',
            text: 'Please wait...',
            icon: 'info',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false
        });
        Swal.showLoading();
    }

    const hideLoadingModal = function() {
        Swal.hideLoading();
    }
	
	$('.validate-reference').on('click', function (e) {
        e.preventDefault();
        var method = $(this).data('method');

		$('#reference_check_'+method).modal('show')
    })

    $('#resend_link').on('click', function (e) {
        e.preventDefault();

        loadingModal();
        var ref_id = $(this).data('id');
        var btn = $(this)
        btn.attr('disabled',true)
        $.ajax({
            url: "/ajax/personal/reference/resend/"+ref_id,
            method: 'post',
            success: function (rs) {
                hideLoadingModal();
                Swal.fire({
                    type: 'success',
                    title: 'Notification.',
                    text: rs.message
                });
                btn.attr('disabled',false)
            },
            error: function (rs) {
                hideLoadingModal();
                btn.attr('disabled',false)
            }
        })
    });

    $('#confirm_reference').on('click', function (e) {
        e.preventDefault();

        var ref_id = $(this).data('id');
        var btn = $(this);

        swal.fire({
            title: 'Confirmation',
            text: 'Do you want really to accept this reference?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: "No"
        }).then((result) => {
            if (result.value) {
                loadingModal();
                btn.attr('disabled', true);
                $.ajax({
                    url: "/ajax/personal/reference/confirm/"+ref_id,
                    method: 'post',
                    data: { reference_type: $(this).data('reference-type') },
                    success: function (rs) {
                        hideLoadingModal();
                        Swal.fire({
                            icon: rs.type,
                            title: 'Notification.',
                            text: rs.message
                        }).then((result) => {
                            if (result.value) {
                                
                                btn.attr('disabled',false);
                                location.reload();
                            }
                        });
                    },
                    error: function (rs) {
                        Swal.fire({
                            icon: rs.responseJSON.type,
                            title: 'Warning.',
                            text: rs.responseJSON.message
                        });
                        hideLoadingModal();
                        btn.attr('disabled',false);
                    }
                });
            }
        });
        
    });

    $('#reject_reference').on('click', function(e) {
        e.preventDefault();

        var ref_id = $(this).data('id');
        var btn = $(this);
        btn.attr('disabled',true);
        
        swal.fire({
            title: 'Reject Confirmation',
            text: 'Do you want really to reject this reference?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: "No"
        }).then((result) => {
            if (result.value) {
                loadingModal()
                $.ajax({
                    url: "/ajax/personal/reference/reject/"+ref_id,
                    method: 'post',
                    data: { reference_type: $(this).data('reference-type') },
                    success: function (rs) {
                        hideLoadingModal();
                        Swal.fire({
                            type: 'success',
                            title: 'Notification.',
                            text: rs.message
                        }).then((result) => {
                            if (result.value) {
                                
                                location.reload();
                            }
                        });
                    },
                    error: function (rs) {
                        hideLoadingModal();
                        btn.attr('disabled',false);
                    }
                })
            }
            btn.attr('disabled', false);
        }).catch(() => {
            btn.attr('disabled', false);
        })
    })

	$('#reference_form_phone').on('submit', function (e) {
        e.preventDefault();

        loadingModal();
        var btn = $('button[type=submit]');
        btn.attr('disabled',true).html('Checking...');
        var data = $(this).serializeArray()
	    $.ajax({
            url: '/ajax/reference_check/check',
            data: data,
            method: 'post',
            success: function (rs) {
                hideLoadingModal();
                var html = '<table class="table text-left">';
                $.each(rs.questions, (index, question) => {
                    html += '<tr><td width="50%">'+question.question+'</td><td style="background:#eee">'+rs.answer[question.question_id]+'</td></tr>'
                });
                html += '</table>';
                console.log(rs)
                swal.fire({

                    title: 'IMPORTANT: Is this data already correct?',
                    html: html,
                    width: '80%',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, correct!',
                    cancelButtonText: "No, I'll change it!"

                }).then((result) => {
                    if (result.value) {
                        loadingModal();
                        $.ajax({
                            url: "/ajax/personal/reference",
                            method: 'post',
                            data: data,
                            success: function (rs) {
                                hideLoadingModal();
                                Swal.fire({
                                    type: 'success',
                                    title: 'Notification.',
                                    text: rs.message
                                }).then((result) => {
                                    if (result.value) {
                                        window.location.reload();
                                    }
                                });
                            },
                            error: function (rs) {
                                hideLoadingModal();
                                Swal.fire({
                                    type: 'error',
                                    title: 'Notification.',
                                    text: "Something went wrong, please contact administrator!"
                                });
                            }
                        });

                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire(
                            'No Problem',
                            'Please correct the information and submit it again :-)',
                            'error'
                        );
                        btn.attr('disabled',false).html('Save');
                        return false;
                    }
                });

            },
            error: function (rs) {
                hideLoadingModal();
                Swal.fire({
                    type: 'error',
                    title: 'Notification.',
                    text: "Something went wrong, please contact administrator!"
                });
                btn.attr('disabled',false).html('Save')
            }
        })
		return false;
    })

    $('#reference_form_email').on('submit', function (e) {
        e.preventDefault();

        loadingModal();
        var btn = $('button[type=submit]')
        btn.attr('disabled',true).html('Sending...');
        var data = $(this).serializeArray()

        $.ajax({
            url: "/ajax/personal/reference",
            method: 'post',
            data: data,
            success: function (rs) {
                hideLoadingModal();
                Swal.fire({
                    type: 'success',
                    title: 'Notification.',
                    text: rs.message
                }).then((result) => {
                    if (result.value) {
                        window.location.reload();
                    }
                });
            },
            error: function (rs) {
                hideLoadingModal();
                Swal.fire({
                    type: 'error',
                    title: 'Notification.',
                    text: "Something went wrong, please contact administrator!"
                });
                btn.attr('disabled',false).html('Submit');
            }
        })

        return false;
    });

    $('#retake-reference').on('click', function(e) {
        e.preventDefault();

        loadingModal();

        swal.fire({

            title: 'Confirmation',
            html: 'Once you retake reference check, all answers will be deleted',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, retake it!',
            cancelButtonText: "No!"

        }).then((result) => {
            if (result.value) {
                
                $.ajax({
                    method: 'POST',
                    url: '/ajax/personal/reference/retake/' + $(this).data('reference-id'),
                    success: function(response) {
                        hideLoadingModal();
                        Swal.fire('Notification', response.message, response.status).then((ans) => {
                            if (ans.value) {
                                window.location.reload();
                            }
                        });
                    },
                    error: function() {
                        hideLoadingModal();
                        Swal.fire('Notification', 'Something went wrong!', 'error');
                    }
                });
            }
        })

    });

});