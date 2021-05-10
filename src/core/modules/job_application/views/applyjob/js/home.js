var confirm = false
$('#apply_job_form').on('submit', function () {
    $('#apply_job_modal').modal('show');
    return confirm;
})

$('#submit_job_application').on('click', function () {
    confirm = true;
    //disable submit button to prevent multiple submit
    $(this).prop('disabled', true);
    $(this).html('Loading..');
    $('#apply_job_form').submit();
})

$('#work').on('change', function () {
    $('#work_experience').html($('#work option:selected').html())
})
$('#personal').on('change', function () {
    $('#personal_reference').html($('#personal option:selected').html())
})
$('#work_ref').on('change', function () {
    $('#work_reference').html($('#work_ref option:selected').html())
})
$('#work_placement').html($('input[name=relevance]:checked').val())
$('input[name=relevance]').on('change', function () {
    $('#work_placement').html($('input[name=relevance]:checked').val())
})