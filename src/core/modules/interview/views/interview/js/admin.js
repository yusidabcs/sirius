function reload_number_question(type) {
    if(type=='specific') {
        $('#specific-questions').find('textarea').each((index,item) => {
            $('#'+$(item).attr('id')).parent('li').find("span").text(index+1);
        })
    } else {
        $('#general-questions').find('textarea').each((index,item) => {
            $('#'+$(item).attr('id')).parent('li').find("span").text(index+1);
        })  
    }
}

$(document).ready(function () {

    $('#job_speedy_code').materialSelect();
    $('#job_master_id').materialSelect();
    $('#interview_result_principal').materialSelect();

    const showLoadingModal = function() {
        Swal.fire({
            title: 'Loading',
            icon: 'info',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false
        });
        Swal.showLoading();
    }

    $('#job_speedy_code').on('change', function () {
        if($(this).val() != ''){
            $.ajax({
                url: "/ajax/job/jobspeedy/job_master_list/"+$(this).val(),
                type: 'POST',
            })
                .done(response =>
                {
                    $('#job_master_id').html('<option value="">Please select job</option>');
                    $.each(response, (index,item) => {
                        $('#job_master_id').append(`<option value="${item.job_master_id}">${item.principal_code} - ${item.brand_code} - ${item.job_code} - ${item.job_title}</option>`)
                    })
                })
                .fail(response => {
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
        }
    })


    $('input[type=radio][name=change_job_speedy]').change(function() {
        if (this.value == '1') {
            $('#change_job_speedy_place').show()
        }
        else if (this.value == '0') {
            $('#change_job_speedy_place').hide()
        }
    });

    $('input[type=radio][name=job_master_prefer]').change(function() {
        if (this.value == '1') {
            $('#job_master_prefer_place').show()
        }
        else if (this.value == '0') {
            $('#job_master_prefer_place').hide()
        }
    });

    $('.btn-submit').on('click', function () {
        var status = $(this).val();
        var general = [];
        $('#general-questions').find('textarea').each((index,item) => {
            general.push($(item).attr('id'))
        });
        var specific = [];
        $('#specific-questions').find('textarea').each((index,item) => {
            specific.push($(item).attr('id'))
        });

        if(general.length==0 || specific.length==0) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Make sure General & Spesific Question at leat one question!'
            });
            return false;
        }

        $('#interview_form').on('submit', function () {
            var data = $(this).serialize();
            swal.fire({
                title: 'Submit this interview result?',
                text: 'Are you sure! Once this submitted, it can not be edited!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Save it'
            }).then((result) =>
            {
                if(result.value)
                {
                    showLoadingModal();
                    $.ajax({
                        url: "/ajax/interview/interview/insert",
                        type: 'POST',
                        data: data+'&status='+status
                    })
                        .done(response =>
                        {
                            Swal.fire({
                                icon: 'success',
                                title: 'Notification.',
                                text: response.message
                            });
                            window.location.href = $('.btn-back').attr('href');
                        })
                        .fail(response => {
                            if(response.status == 400)
                            {
                                text = ''
                                $.each(response.responseJSON.errors, (index,item) => {
                                    text += item + '<br>';
                                })
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    html: text
                                });
                            }else{
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something errors. Please contact admin support!'
                                });
                            }
                        });
                }
            });
            return false;
        })
    })

    //new general question
    var other_question = {}
    var count_question = 0
    var id = []
    $('#other-general-question').on('click', function(){
        id = []
        $('#general-questions').find('textarea').each((index,item) => {
            id.push($(item).attr('id'))
        })
        $.ajax({
            url: "/ajax/interview/question/others",
            type: 'POST',
            data: {
                job: $(this).data('job'),
                type: $(this).data('type'),
                question_ids: id
            }
        })
            .done(response =>
            {
                $.each(response, function(index,item) {
                    $('#general-questions-select').append(
                        `<div class="form-check">
                        <input class="form-check-input" type="radio" name="other_question" id="${item.question_id}" data-question="${item.question}" value="${item.question_id}">
                        <label class="form-check-label" for="${item.question_id}">
                            ${item.question}
                        </label>
                    </div>`
                    );
                });
                
                $('#add-general-questions').modal('show')
                
                
            })
            .fail(response => {
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
    });

    $('body').on('change','input[type=radio][name=other_question]',function() {
        
        if (this.value == '000') {
            $('#new_general_question_form').show()
        }
        else {
            other_question = {
                id: $(this).attr('id'),
                question: $(this).data('question'),
            }
            $('#new_general_question_form').hide()
        }
    });

    $('#save_question_btn').on('click', function(){
        var question_id = $('input[type=radio][name=other_question]:checked').val();
        console.log(question_id)
        if(question_id == '000'){
            var question_text = $('#new_general_question').val();
            $('#general-questions').append(
                `<li class="list-group-item">
                <span>${id.length + 1}</span>. ${question_text} <small><a href="javascript:;" data-id="00${count_question}" class="link_delete_question text-danger">Delete Question?</a></small>
                <textarea name="answer[${question_text}]['general']" id="00${count_question}"
                          class="form-control mt-3" required></textarea>
            </li>`
            );
            count_question++
        }else if(question_id!= undefined){
            $('#general-questions').append(
                `<li class="list-group-item">
                <span>${id.length + 1}</span>. ${other_question.question} <small><a href="javascript:;" data-id="${other_question.id}" class="link_delete_question text-danger">Delete Question?</a></small>
                <textarea name="answer[${other_question.id}]['general']" id="${other_question.id}"
                          class="form-control mt-3" required></textarea>
            </li>`);
        }

      $('#new_general_question_form').hide()
      $('#new_general_question').val('');
        $('#add-general-questions').modal('hide')

       
    })

    $('#add-general-questions').on('hidden.bs.modal', function (e) {
         $('#general-questions-select').html(`<div class="form-check"><input class="form-check-input" type="radio" name="other_question" id="exampleRadios2" value="000">
        <label class="form-check-label" for="exampleRadios2">
            New Question
        </label></div>`);
      })

      //add specific 
      //new general question
    var specific_question = {}
    var count_specific_question = 0
    var id = []
    $('#other-specific-question').on('click', function(){
        id = []
        $('#specific-questions').find('textarea').each((index,item) => {
            id.push($(item).attr('id'))
        })
        $.ajax({
            url: "/ajax/interview/question/others",
            type: 'POST',
            data: {
                job: $(this).data('job'),
                type: $(this).data('type'),
                question_ids: id
            }
        })
            .done(response =>
            {
                $.each(response, function(index,item) {
                    $('#specific-questions-select').append(
                        `<div class="form-check">
                        <input class="form-check-input" type="radio" name="other_specific_question" id="${item.question_id}" data-question="${item.question}" value="${item.question_id}">
                        <label class="form-check-label" for="${item.question_id}">
                            ${item.question}
                        </label>
                    </div>`
                    );
                });
                
                $('#add-specific-questions').modal('show')
                
                
            })
            .fail(response => {
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
    });

    $('body').on('change','input[type=radio][name=other_specific_question]',function() {
        
        if (this.value == '000') {
            $('#new_specific_question_form').show()
        }
        else {
            other_question = {
                id: $(this).attr('id'),
                question: $(this).data('question'),
            }
            $('#new_specific_question_form').hide()
        }
    });

    $('#save_specific_question_btn').on('click', function(){
        var question_id = $('input[type=radio][name=other_specific_question]:checked').val();
        console.log(question_id)
        if(question_id == '000'){
            var question_text = $('#new_specific_question').val();
            $('#specific-questions').append(
                `<li class="list-group-item">
                <span>${id.length + 1}</span>. ${question_text} <small><a href="javascript:;" data-id="00${count_question}" class="link_delete_question text-danger">Delete Question</a></small>
                <textarea name="answer[${question_text}]['specific']" id="00${count_question}"
                          class="form-control mt-3" required></textarea>
            </li>`
            );
            count_question++
        }else if(question_id!= undefined){
            $('#specific-questions').append(
                `<li class="list-group-item">
                <span>${id.length + 1}</span>. ${other_question.question} <small><a href="javascript:;" data-id="${other_question.id}" class="link_delete_question text-danger">Delete Question</a></small>
                <textarea name="answer[${other_question.id}]['specific']" id="${other_question.id}"
                          class="form-control mt-3" required></textarea>
            </li>`);
        }

      $('#new_specific_question_form').hide()
      $('#new_specific_question').val('');
        $('#add-specific-questions').modal('hide')

       
    })

    $('#add-specific-questions').on('hidden.bs.modal', function (e) {
         $('#specific-questions-select').html(`<div class="form-check"><input class="form-check-input" type="radio" name="other_specific_question" id="exampleRadios3" value="000">
        <label class="form-check-label" for="exampleRadios3">
            New Question
        </label></div>`);
      })

    $(document).on('click','.link_delete_question',function() {
        let id = $(this).data('id');
        $('#'+id).parent('li').remove();
        reload_number_question('specific');
        reload_number_question('general');
    })
});
