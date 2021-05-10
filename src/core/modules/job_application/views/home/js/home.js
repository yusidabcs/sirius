$('#my_jobs').DataTable();

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

$(document).on('click', '.btn-upload-ol', function () {
    var data = $(this).data('job_application_id');
    $('#upload_ol_form').find('input[name=job_application_id]').val(data);
    $('#upload-ol-modal').modal('show');
    return false;
});

$('.detail-job-application').on('click', function () 
{
    const job_applicant_id = $(this).data('id');
    //ajax call to check job applicant status
    $.ajax({
        url: '/ajax/personal/reference/status/'+job_applicant_id,
        method: 'get',
        success: function (rs) 
        {
            if (rs.person)
                $('#td_personal_ref').html(rs.person);
            if (rs.work)
                $('#td_work_ref').html(rs.work);
        },
        error: function (rs) 
        {    
            Swal.fire({
                type: 'erorr',
                title: 'Failed',
                text: rs.message
            });
        }
    })
    $('#progress-job-'+job_applicant_id).modal('show');
});

$('#upload_ol_form').submit(function (event) {
    event.preventDefault();
    var btn = $(this).find('[type=submit]');
    // mencegah browser mensubmit form.
    btn.attr('disabled', true);
    btn.html('Saving..');
    loadingModal();

    $.ajax({
        type: 'POST',
        "url": "/ajax/job_application/offer_letter",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: rs => {
            hideLoadingModal();
            Swal.fire({
                icon: 'success',
                title: 'Notification!',
                text: rs.message
            }).then(function(result) {
                if (result.value) {
                    window.location.reload();
                }
            });
        },
        error: response => {
            hideLoadingModal();
            btn.attr('disabled', false);
            btn.html('Save');
            if (response.status == 400) {
                text = ''
                $.each(response.responseJSON.errors, (index, item) => {
                    text += item + '<br>';
                })
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: text
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something errors. Please contact admin support!'
                });
            }
        }
    })
});