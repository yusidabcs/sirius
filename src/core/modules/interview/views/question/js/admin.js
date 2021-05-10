$(document).ready(function () {
    $('.mdb-select').materialSelect();

    var id = $('#schedule_id').val();
    var question_table = $('#list_question').DataTable( {
            "processing": true,
            "serverSide": true,
            'responsive': true ,
            "ajax": {
                "url": "/ajax/interview/question/list",
                "type": "POST",
                data: function (d) {
                    d.type = $('#table_type_search').val()
                    d.job_speedy_code = $('#table_job_search').val()
                }
            },

            "columns": [
                { "data" : 'question'},
                { "data" : 'type'},
                { "data" : 'job_title'},
                { "data" : null}
            ],
            "columnDefs": [

                {
                    "render": function ( data, type, row ) {
                        var html = `<div class="container d-flex text-center ">`;

                        if(row.locked == 0){
                            console.log(row.locked)
                            html += `<a class="btn btn-sm btn-info btn-edit" data-id="${row.question_id}" ><i class="fa fa-edit"></i></a>`;
                            html += `<a class="btn btn-sm btn-danger delete_question_btn" data-id="${row.question_id}" ><i class="fa fa-trash-alt"></i></a>`;
                        }
                            html += `<div>`;
                        return html;
                    },
                    "targets": -1
                }
            ],
        }
    );

    $('#table_type_search, #table_job_search').on('change',function () {
        question_table.ajax.reload();
    })

    $("#new_question_form input[name='type']").on('change', function () {

        if($("#new_question_form input[name='type']:checked").val() == 'specific'){
            $('#new_question_form #jobs').removeClass('not-showing')
            //$('#create_question_form job_speedy_code').attr('required',true)
        }else{
            $('#new_question_form #jobs').addClass('not-showing')
            //$('#create_question_form #job_speedy_code').removeAttr('required')
        }
    })

    $("#edit_question_form input[name='type']").on('change', function () {

        if($("#edit_question_form input[name='type']:checked").val() == 'specific'){
            $('#edit_question_form #jobs').removeClass('not-showing')
            //$('#edit_question_form #job_speedy_code').attr('required',true)
        }else{
            $('#edit_question_form #jobs').addClass('not-showing')
            //$('#edit_question_form #job_speedy_code').removeAttr('required')
        }
    })

    $('#new_question_form').on('submit', function () {
        var btn = $(this).find('button[type=submit]');
        btn.attr('disabled', true)
        $.ajax({
            url: "/ajax/interview/question/insert",
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Information',
                    html: response.message
                });
                $('#new_question_modal').modal('hide')
                btn.attr('disabled', false)
                $('#new_question_form').trigger('reset')
                question_table.ajax.reload(false)
            },
            error: function (response) {

                if(response.status == 400)
                {
                    text = ''
                    $.each(response.responseJSON.errors, (index,item) => {
                        text += item +' <br>';
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
                btn.attr('disabled', false)
            }
        })

        return false;
    })


    $('body').on('click','.btn-edit', function ()
    {
        var id = $(this).data('id')
        $.ajax({
            url: "/ajax/interview/question/get/"+id,
            type: 'POST',
            cache: true,
            timeout: 10000
        })
            .done(response => {
                $('#edit_question_modal').modal('show')
                $('#edit_question_form input[name=question_id]').val(response.question_id);
                $('#edit_question_form textarea[name=question]').val(response.question)
                $('#edit_question_form input[name=type][value='+response.type+']').prop('checked',true)

                if (response.type == 'specific')
                {
                    $('#edit_question_form #jobs').removeClass('not-showing');

                }else{
                    $('#edit_question_form #jobs').addClass('not-showing');
                }
                var job = [];
                $.each(response.question_job, function (index,item) {
                    job.push(item.job_speedy_code)
                })
                $("#job_speedy_code").val(job);

                $('#edit_question_form textarea[name=help]').val(response.help)
                $('#edit_question_form textarea[name=answer_heading]').val(response.answer_heading)
                $('#edit_question_form radio[name=status][value='+response.status+']').prop('checked',true)
            });

        return false;
    });

    $('#edit_question_form').on('submit', function () {
        var btn = $(this).find('button[type=submit]');
        btn.attr('disabled', true)
        $.ajax({
            url: "/ajax/interview/question/update",
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Information',
                    html: response.message
                });
                $('#edit_question_modal').modal('hide')
                btn.attr('disabled', false)
                question_table.ajax.reload(false)
            },
            error: function (response) {

                if(response.status == 400)
                {
                    text = ''
                    $.each(response.responseJSON.errors, (index,item) => {
                        text += item +' <br>';
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
                btn.attr('disabled', false)
            }
        })

        return false;
    })

    $(document).on('click','.delete_question_btn',function () {
        const id = $(this).data('id')
        swal.fire({
            title: 'Delete this item?',
            text: 'Are you sure! Once you delete it can never be recovered!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete !'
        }).then((result) =>
        {
            if(result.value)
            {
                $.ajax({
                    url: "/ajax/interview/question/delete/"+id,
                    type: 'POST',
                })
                    .done(response =>
                    {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification.',
                            text: response.message
                        });
                        question_table.ajax.reload(false)
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
        });
        return false;
    });



    $('.add_new_question').on('click', function () {

        $('#new_question_modal').modal({
            'keyboard': false,
            'backdrop': 'static'
        })
    })
    //check type change in insert modal
    $(document).on('change','#type',function ()
    {
        if ($(this).val() == 'tf')
        {
            $('#more_div').removeClass('not-showing');
        }else{
            $('#more_div').addClass('not-showing');
        }
    });
})


