var categories = [];

function getListQuestion()
{
    $.ajax({
        url: "/ajax/recruitment/question/list",
        type: 'POST',
        data: {

        },
        cache: true,
        timeout: 10000
    })
        .done(response => {
            const type = {heading:"Heading", tf:"True / False", sa: "Short Answer"};
            categories = response;

            $('select[name=parent_id]').html('');
            $('#list_question').html('');

            $('select[name=parent_id]').append('<option value="0">No Parent</option>')
            $.each(categories,  (index,item) => {

                if(item.parent_id == 0){
                    if(item.type == 'tf'){
                        $('select[name=parent_id]').append('<option value="'+item.question_id+'">'+item.question+'</option>')
                    }
                    if(item.childs != undefined){
                        var html = '<li class="list-group-item" data-id="'+item.question_id+'" '+ (item.status == 0 ? 'style="text-decoration: line-through;"' : '') +'> <div><label class="badge '+(item.type == 'sa' ? 'badge-info ' : (item.type == 'tf' ? 'badge-success' : 'badge-warning'))+' ">'+type[item.type]+'</label> <br/>'+item.question+'</div>' +
                            '<div>' +
                            '<a href="#" class="delete_question_btn text-danger float-right" data-id="'+item.question_id+'" ><i class="fa fa-times"></i> </a> '+
                            '<a href="" class="edit_question_btn text-success float-right mr-3" data-id="'+item.question_id+'"><i class="fa fa-edit"></i></a>' +
                            '</div>' +
                            '<br>' +
                            '<br>' +
                            '<ul class="list-group list_question" data-parent="'+item.question_id+'">';
                        $.each(item.childs,  (i,child) => {
                            console.log(child)
                            html += '<li class="list-group-item" data-id="'+child.question_id+'" '+ (item.status == 0 ? 'style="text-decoration: line-through;"' : '') +'> <label class="badge '+(item.type == 'sa' ? 'badge-info ' : (item.type == 'tf' ? 'badge-success' : 'badge-warning'))+'">'+type[item.type]+'</label><br/>'+child.question +
                                '<a href="#" class="delete_question_btn text-danger float-right" data-id="'+child.question_id+'" ><i class="fa fa-times"></i> </a> '+
                                '<a href="#" class="edit_question_btn text-success float-right mr-3" data-id="'+child.question_id+'"><i class="fa fa-edit"></i> </a>' +
                                '</li>';
                        });
                        html += '</ul>';
                        html += '</li>';
                    }else{
                        var html = '<li class="list-group-item" data-id="'+item.question_id+'" '+ (item.status == 0 ? 'style="text-decoration: line-through;"' : '') +'> <div><label class="badge '+(item.type == 'sa' ? 'badge-info ' : (item.type == 'tf' ? 'badge-success' : 'badge-warning'))+'">'+type[item.type]+'</label><br/>'+item.question+
                            '</div>' +
                            '<div>' +
                            '<a href="#" class="delete_question_btn text-danger float-right" data-id="'+item.question_id+'" ><i class="fa fa-times"></i> </a> '+
                            '<a href="" class="edit_question_btn text-success float-right mr-3" data-id="'+item.question_id+'"><i class="fa fa-edit"></i></a>' +
                            '</div>'+
                            '</li>';
                    }
                    $('#list_question').append(html)
                }
            })

            $('.list_question').sortable({
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                onEnd: function (/**Event*/evt) {
                    var parent = $(evt.to).data('parent')
                    var id = $(evt.item).data('id')
                    var index = evt.newIndex

                    console.log(id)
                    console.log(parent)
                    console.log(index)

                    $.ajax({
                        url: "/ajax/recruitment/question/updateSequence",
                        type: 'POST',
                        data: {
                            id: id,
                            parent_id: parent,
                                index: index + 1
                        }
                    })
                }
            });

        })
        .fail(response => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Connection to Server Failed!'
            });
        });
}
getListQuestion();

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

//check if has parent, yes: show
$(document).on('change','#parent_id',function ()
{
    if ($(this).val() != '0')
    {
        $('#show_child_div').removeClass('not-showing');
    }else{
        $('#show_child_div').addClass('not-showing');
    }
});

// check type change in edit modal
$(document).on('change','#type_e',function () 
{
    if ($(this).val() == 'tf')
    {
        $('#more_div_e').removeClass('not-showing');
    }else{
        $('#more_div_e').addClass('not-showing');
    }
});

$('#new_question_form').on('submit', function () {
    var btn = $(this).find('button[type=submit]');
    btn.attr('disabled', true)
    $.ajax({
        url: "/ajax/recruitment/question/insert",
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
            getListQuestion()
            $('#new_question_form').trigger('reset')
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
                url: "/ajax/recruitment/question/delete/"+id,
                type: 'POST',
            })
                .done(response =>
                {
                    Swal.fire({
                        icon: 'success',
                        title: 'Notification.',
                        text: response.message
                    });
                    getListQuestion();
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

$('body').on('click','.edit_question_btn', function ()
{
    var id = $(this).data('id')
    $.ajax({
        url: "/ajax/recruitment/question/get/"+id,
        type: 'POST',
        cache: true,
        timeout: 10000
    })
        .done(response => {
            $('#edit_question_modal').modal('show')
            $('#edit_question_form input[name=question_id]').val(response.question_id);
            $('#edit_question_form select[name=parent_id]').val(response.parent_id).change();
            $('#edit_question_form textarea[name=question]').val(response.question)
            $('#edit_question_form select[name=type]').val(response.type)
            if (response.type == 'tf')
            {
                $('#more_div_e').removeClass('not-showing');
            }else{
                $('#more_div_e').addClass('not-showing');
            }

            if (response.parent_id != '0')
            {
                $('#show_child_div_e').removeClass('not-showing');
            }else{
                $('#show_child_div_e').addClass('not-showing');
            }
            $('#edit_question_form select[name=relevance]').val(response.relevance)
            $('#edit_question_form input[name=more][value='+response.more+']').prop('checked',true)
            $('#edit_question_form input[name=show_child][value='+response.show_child+']').prop('checked',true)
            $('#edit_question_form textarea[name=help]').val(response.help)
            $('#edit_question_form textarea[name=answer_heading]').val(response.answer_heading)
            $('#edit_question_form radio[name=status][value='+response.status+']').prop('checked',true)
            $.each(response.files, function (index, item) {
                if(item.model_code == 'avatar'){

                    $('#avatar_current').val(item.filename)
                    $('#avatar_current_img').attr('src','/ao/show/'+item.filename)
                    $('#avatar_current_img').parent().show()

                }else if(item.model_code == 'banner'){
                    $('#banner_current').val(item.filename)
                    $('#banner_current_img').attr('src','/ao/show/'+item.filename)
                    $('#banner_current_img').parent().show()
                }
            })
        });

    return false;
});

$('#edit_question_form').on('submit', function () {
    var btn = $(this).find('button[type=submit]');
    btn.attr('disabled', true)
    $.ajax({
        url: "/ajax/recruitment/question/update",
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
            getListQuestion()
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
