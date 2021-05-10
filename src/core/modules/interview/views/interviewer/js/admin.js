$(document).ready(function () {

    $('#address_book_id').materialSelect();

    function loadInterviewer(){
        $.ajax({
            url: "/ajax/interview/interviewer/list",
            type: 'POST',
        })
            .done(response => {
                $('#list_interviewer').DataTable({
                    "data": response,
                    "columns": [
                        {"data": null},
                        {"data": null},
                    ],
                    "columnDefs": [
                        {
                            "render": function (data, type, row) {
                                return row.title + ' ' + row.entity_family_name + ' ' + row.number_given_name + '<br> (' + row.main_email + ')';
                            },
                            "targets": 0
                        },

                        {
                            "render": function (data, type, row) {

                                html = `<div class="container">
                                    <a class="btn btn-sm btn-danger btn-delete" data-ab-id="${row.address_book_id}" ><i class="fa fa fa-times"></i></a>
                                <div>`;

                                return html;
                            },
                            "targets": -1
                        }
                    ],
                });
            })
            .fail(response => {

            });
    }

    loadInterviewer();

    $('.btn-add-interviewer').on('click', function () {
        $('#interviewer_modal').modal('show')
    })

    $('#interviewer_form').on('submit', function () {
        $.ajax({
            url: "/ajax/interview/interviewer/insert",
            type: 'POST',
            data: $(this).serialize()
        })
            .done(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Notification.',
                    text: response.message
                });
                $('#interviewer_modal').modal('hide')
                location.reload();
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
        return false;
    })
    
    $('body').on('click','.btn-delete', function () {
        var id = $(this).data('ab-id');
        swal.fire({
            title: 'Delete this data?',
            text: 'Are you sure to delete this item?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it'
        }).then((result) =>
        {
            if(result.value)
            {
                $.ajax({
                    url: "/ajax/interview/interviewer/delete/"+id,
                    type: 'POST'
                })
                    .done(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification.',
                            text: response.message
                        });
                        location.reload();
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
    })
});
