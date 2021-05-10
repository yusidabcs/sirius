$('[data-toggle="tooltip"]').tooltip();


$('.true_false').click( function()
{
    var id = $(this).data('id');
    var more = $(this).data('more');
    var value = $(this).data('value');
    if(more == value){
        $('#more_'+id).show().find('textarea').attr('required',true);
    }else{
        $('#more_'+id).hide().find('textarea').removeAttr('required');
    }

    $('#child_'+id+' .child_'+id+'_'+(value ^ 1)).hide();
    $('#child_'+id+' .child_'+id+'_'+(value ^ 1)).find('textarea').removeAttr('required');
    $('#child_'+id+' .child_'+id+'_'+(value ^ 1)).find('input[type=radio]').removeClass('child_radio');


    $('#child_'+id+' .child_'+id+'_'+value).show();
    $('#child_'+id+' .child_'+id+'_'+(value)).find('.child_sa').attr('required',true);
    $('#child_'+id+' .child_'+id+'_'+(value)).find('input[type=radio]').addClass('child_radio');


});

$('input[type=radio]').change(function()
{
    const radio_span = $(this).siblings('.true_false');
    var id = radio_span.data('id');
    var more = radio_span.data('more');
    var value = radio_span.data('value');
    
    $('#yes_'+id).removeClass('bg-warning bg-success');
    $('#no_'+id).removeClass('bg-warning bg-success');
    if(more == value){
        radio_span.addClass('bg-warning');
    }else{
        radio_span.addClass('bg-success');
    }
});

//client validation
$('#prescreen_answer').submit(function(e)
{
    var btn = $(this).find('[type=submit]')
    var text = '<i class="fas fa-thumbs-up"></i> Save Answer';
    //check checked radio
    const total_radio = $('#prescreen_answer .parent_radio ').length/2;
    const checked_radio = $('#prescreen_answer .parent_radio:checked').length;


    const total_child = $('#prescreen_answer .child_radio ').length/2;
    const checked_child = $('#prescreen_answer .child_radio:checked').length;

    if (checked_radio != total_radio || total_child != checked_child)
    {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete',
            text: 'Please answer all of the question'
        });
        return false;
    }
    let condition = true;
    //check all details is has input or not
    $("input[type=radio]:checked").each(function() 
    {
        const tf_span = $(this).siblings('.true_false');
        var id = tf_span.data('id');
        var more = tf_span.data('more');
        var value = tf_span.data('value');
        var child = tf_span.data('child');
        if(more == value && child == 0)
        {
            $('#more_'+id).show();
            if (!$.trim($('#more_'+id+' textarea').val())) 
            { 
                $('#more_'+id+' textarea').focus();
                Swal.fire({
                    icon: 'warning',
                    title: 'Details',
                    text: 'Please give details for the answer'
                });
                condition = false;
               
            }
        }
    });

    if(condition){

        btn.attr('disabled',true)
        btn.html('Saving..')
        $.ajax({
            url: "/ajax/recruitment/prescreen/insert",
            type: 'POST',
            data: $(this).serialize(),
            success: function (rs) {
                btn.attr('disabled',false)
                btn.html(text)
                $.ajax({
                    url: "/ajax/recruitment/prescreen/get/"+rs.job_application_id,
                    type: 'POST',
                    success: function (response) {

                        $('#ps-send-btn').data('id', rs.job_application_id);
                        let html = '';
                        answers = response.answers;
                        $.each(response.questions, (index, item) => {
                            html +=
                                `<div class="question_row pt-3 pb-3 align-items-center border-top ${(item['type'] == 'heading') ? 'peach-gradient text-white p-3' : ''}">
                            <div class="row">
                                <div class="col-md-7">
                                    ${((item['type'] == 'heading') ? `<h5>${item['question']}</h5>` : item['question'])}
                                </div>
                                <div class="col-md-5">`;
                            if (item['type'] == 'tf') {
                                const bool_tf = (answers[item['question_id']]['answer'] == 'yes') ? 1 : 0;
                                html += `<div class="row">
                                        <div class="col-6">
                                            <div class="iow-ck-button">
                                                <label>
                                                    <input type="radio" name="${index}" value="yes" hidden="hidden">
                                                    <span class="text-white ${(answers[item['question_id']]['answer'] == 'yes') ? ((item['more'] == bool_tf) ? ` bg-warning` : 'bg-success') : '' } " >Yes</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="iow-ck-button">
                                                <label>
                                                    <input type="radio" name="${index}" value="no" hidden="hidden">
                                                    <span class="text-white ${(answers[item['question_id']]['answer'] == 'no') ? ((item['more'] == bool_tf) ? ` bg-warning` : 'bg-success') : '' } " >No</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>`;

                                if (item['more'] == bool_tf && item.childs.length==0) {
                                    html += `<p>More details:</p><p class="border p-3" rows="3" readonly>${answers[item['question_id']]['text']}</p>`;
                                }
                            } else if (item['type'] == 'sa') {
                                html += `<p class="border p-3">${((answers[item['question_id']]) ? answers[item['question_id']]['text'] : '')}</p>`;
                            }
                            html += `</div>
                            </div>
                        </div>`;

                            //child
                            $.each(item.childs, (index, child) => {

                                html +=
                                    `<div class="question_row pt-3 pb-3 align-items-center border-top ${(child['type'] == 'heading') ? 'peach-gradient text-white p-3' : ''}">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="pl-3">${((child['type'] == 'heading') ? `<h5>${child['question']}</h5>` : child['sequence']+'. '+child['question'])}</div>
                                </div>
                                <div class="col-md-5">`;
                                if (child['type'] == 'tf') {
                                    const bool_tf = (answers[child['question_id']]['answer'] == 'yes') ? 1 : 0;
                                    html += `<div class="row">
                                        <div class="col-6">
                                            <div class="iow-ck-button">
                                                <label>
                                                    <input type="radio" name="${index}" value="yes" hidden="hidden">
                                                    <span class="text-white ${(answers[child['question_id']]['answer'] == 'yes') ? ((child['more'] == bool_tf) ? ` bg-warning` : 'bg-success') : '' } " >Yes</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="iow-ck-button">
                                                <label>
                                                    <input type="radio" name="${index}" value="no" hidden="hidden">
                                                    <span class="text-white ${(answers[child['question_id']]['answer'] == 'no') ? ((child['more'] == bool_tf) ? ` bg-warning` : 'bg-success') : '' } " >No</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>`;

                                    if (child['more'] == bool_tf) {
                                        html += `<p class="border p-3" >${answers[child['question_id']]['text']}</p>`;
                                    }
                                } else if (child['type'] == 'sa') {
                                    html += `<p class="border p-3">${((answers[child['question_id']]) ? answers[child['question_id']]['text'] : '')}</p>`;
                                }
                                html += `</div>
                            </div>
                        </div>`;
                            });

                        });
                        $('#pre-screen-modal-body').html(html);

                        $('#pre-screening-interview').modal('show');
                        // Swal.fire({
                        //     icon: 'success',
                        //     title: 'Information',
                        //     text: rs.message
                        // });
                    },
                    error: function (rs) {

                    }
                })
            },
            error: function (rs) {
                btn.attr('disabled',false)
                btn.html(text)
            }
        })
    }else{
        btn.attr('disabled',false)
        btn.html(text)
    }
    return false;
});

$("input[type=radio]:checked").each(function() 
{
    const tf_span = $(this).siblings('.true_false');
    var id = tf_span.data('id');
    var more = tf_span.data('more');
    var value = tf_span.data('value');
    if(more == value){
        $('#more_'+id).show();
    }else{
        $('#more_'+id).hide();
    }
});

$('#by_pass').on('change', function(e) {
    if (e.target.checked) {
        $('#ps-send-btn').html('<i class="fas fa-check-circle"></i> Complete prescreen process');
    } else {
        $('#ps-send-btn').html('<i class="fas fa-paper-plane"></i> Send Restult To Candidate');
    }
})

//send prescreen result
$(document).on('click', '#ps-send-btn', function () {
    var btn = $(this)
    var text = $(this).val()
    var id = $(this).data('id')
    var by_pass = $('#by_pass:checked').val();

    var title = 'Send pre screen result to candidate';
    var text = "Please make sure all the answer is answered and filled correctly and honestly by candidates .";
    var confirmButton = 'Yes, Send Now!';

    if (by_pass) {
        title = 'You\'re by passing user process';
        text = "It will complete prescreen process without candidate response, make sure data is correct!";
        confirmButton = 'Complete Now';
    }

    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: confirmButton
    }).then((result) => {
        if (result.value) {
            btn.attr('disabled', true)
            btn.html('Loading..')
            $.ajax({
                method: 'POST',
                url: "/ajax/recruitment/prescreen/sendemail/"+id,
                data: {
                    'by_pass': by_pass ?? 0
                },
                success: function (rs) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Information',
                        text: rs.message
                    }).then((confirm) => {
                        if (confirm.value) {
                            window.location.replace($('#go_back').attr('href'));
                        }
                    });

                },
                error: function (rs) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: 'Someting went wrong..'
                    })
                    btn.attr('disabled', false)
                    btn.html(text)
                }
            })
        }
    })
});
