$(document).ready(function() {

    $('.btn_join_course').on('click',function(){
        $('.content_loading').show();
        $('#form_request_join_course').hide();
        $('#join_course_modal').modal('show');
        var id = $(this).data('id')
        $.ajax({
            url: "/ajax/education_application/main/get/"+id,
            type: 'POST',
            cache: true,
            timeout: 10000
        })
            .done(response => {
                $('#course_id').val(id);
                $('#title_modal').html('Join Course ('+response.course_name+')');
                $('#short_description').html(response.short_description);
                $('#full_description').html(response.description);
                if(response.image!='') {
                    $('#form_request_join_course img').attr('src','/ao/show/'+response.image);
                } else {
                    $('#form_request_join_course img').attr('src','');
                }
                $('.content_loading').hide();
                $('#form_request_join_course').show();
            }).fail(response => {
                $('#join_course_modal').modal('hide');
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something errors. Please contact admin support!'
                });
            });;
    
        return false;
    });
    
    $('#form_request_join_course').on('submit', function () {
        let btn = $('#btn_submit_request');
        btn.html('Sending...').attr('disabled',true);
        var id = $('#course_id').val();
        $.ajax({
            url: "/ajax/education_application/main/request/"+id,
            type: 'POST',
            data: $(this).serialize(),
        })
            .done(response => {
                btn.removeAttr('disabled').html('Request Join');
                Swal.fire({
                    icon: 'success',
                    title: 'Notification.',
                    text: response.message
                }).then(function(){
                    window.open($('#link-education').val(),'_SELF');
                });
                
                $('#join_course_modal').modal('hide');
            })
            .fail(response => {
                btn.removeAttr('disabled').html('Request Join');
                if(response.status == 400)
                {
                    text = ''
                    $.each(response.responseJSON.errors, (index,item) => {
                        text += item;
                    })
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: text
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something errors. Please contact admin support!'
                    });
                }
            });
    
        return false;
    });
});
