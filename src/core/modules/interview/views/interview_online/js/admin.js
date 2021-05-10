$(document).ready(function () {

    $('#address_book_id').materialSelect();
    $('#interviewer_filter').materialSelect();

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

    const table = $('#list_schedule').DataTable( {
            "processing": true,
            "serverSide": true,
            'responsive': true ,
            "ajax": {
                "url": "/ajax/interview/schedule/online_list",
                "type": "POST",
                data: function (d) {
                    d.start_on = $('#startingDate').val()
                    d.finish_on = $('#endingDate').val()
                    d.interviewer_id = $('#interviewer_filter').val();
                }
            },
            "columns": [
                { "data" : null},
                { "data" : null},
                { "data" : 'job_title'},
                { "data" : 'schedule_on'},
                { "data" : 'interviewer'},
                { "data" : null},
                { "data" : null}
            ],
            "columnDefs": [
                {
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0,
                    "render": function (data, type, row) {
                        return '';
                    }
                },
                {
                    "render": function ( data, type, row ) {
                        html = '<a href="/personal/home/' + row.address_book_id + '" class="p-1 white" target="_blank"><i class="far fa-user text-success" title="Show Personal"></i></a> ' + row.number_given_name +' ' +row.entity_family_name + ' <br> ' + row.main_email;
                        
                        return html;
                    },
                    "targets": 1
                },
                {
                    "render": function ( data, type, row ) {
                        return data + ' <br> ('+row.timezone+') ';
                    },
                    "targets": 3
                },
                {
                    "render": function ( data, type, row ) {
                        return row.interviewer_id != 0 ? row.interviewer :  ' No Interviewer ';
                    },
                    "targets": -3
                },
                {
                    "render": function ( data, type, row ) {
                        if(row.partner_name===null) {
                            return 'No Partner';
                        } else {
                            return row.partner_name;
                        }
                    },
                    "targets": -2
                },
                // {
                //     "render": function ( data, type, row ) {
                //         return row.interview_result_id != null ? row.job_application_status :  ' Not Yet ';
                //     },
                //     "targets": -2
                // },
                {
                    "render": function ( data, type, row ) {
                        var url = $('#list_schedule').data('url')

                        //<a class="btn-sm btn-danger text-white schedule_delete_btn" href="#" data-id="${row.schedule_id}" title=""><i class="fas fa-times"></i></a>
                        var html = '<div class="btn-group">'
                        if(row.interview_result_id == null){
                            html += `
                                    <a  class="btn-sm btn-info btn-set-interview" href="#" data-schedule-id="${row.schedule_id}" ><i class="fa fa-user" title="Select Interviewer"></i></a>
                                   `;
                        }
                        if(row.interviewer_id != 0 && row.job_application_status == 'interview'){
                            html += `
                                    <a  class="btn-sm btn-success btn-interview" href="${url}/interview/${row.schedule_id}" title="Do Interview" ><i class="fa fa-comment" ></i></a>`
                        }
                        if(row.interview_result_id != null){
                            html += `
                                    <a  class="btn-sm btn-success btn-interview-result" href="#" data-ja-id="${row.job_application_id}"  ><i class="fa fa-comment-alt" title="Interview Result"></i></a>`
                        }
                        html += '</div>'

                        return html;
                    },
                    "targets": -1
                }
            ],

            'select': {
                'style': 'multi'
            },
            'order': [
                [1, 'asc']
            ],
            createdRow: function( row, data, dataIndex ) {
                // Set the data-status attribute, and add a class
                if(data.interviewer_id != 0){
                    $( row ).addClass('have-interviewer');
                }

            }
        }
    );
    var selected_schedule = []
    table.on('select', function (e, dt, type, indexes) {
        if (type === 'row') {
            var rows = table.rows(indexes).nodes().to$();
            $.each(rows, function() {
                if ($(this).hasClass('have-interviewer')) table.row($(this)).deselect();
            })
        }
        selected_schedule = table.rows({selected: true}).data();
    });

    $('#startingDate, #endingDate, #interviewer_filter').on('change', function () {
        table.ajax.reload();
    });

    $(document).on('click','.btn-interview', function () {
        Swal.fire({
            title: 'Do The Interview',
            text: "You will recorded as the interviewer for this candidate.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if(result.value)
                window.location = $(this).attr('href');
        })
        return false
    })



    $(document).on('click','.btn-set-interview', function () {

        $('#interviewer_modal').modal('show')

        $('#interviewer_form').on('submit',function () {

            Swal.fire({
                title: 'Are you sure?',
                text: "This action will update the selected schedule for selected interviewer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    var schedule_id = []
                    for (var i = 0; i < selected_schedule.length; i++) {
                        schedule_id.push(selected_schedule[i].schedule_id);
                    }
                    address_book_id = $('#address_book_id').val()
                    if(schedule_id.length == 0){
                        schedule_id = $(this).data('schedule-id')
                    }
                    showLoadingModal();
                    $.ajax({
                        url: "/ajax/interview/schedule/set_interviewer",
                        type: 'POST',
                        data: {
                            'schedule_id': schedule_id,
                            'address_book_id': address_book_id
                        }
                    })
                        .done(response => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Notification.',
                                text: response.message
                            });
                            $('#interviewer_modal').modal('hide')
                            table.ajax.reload();
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
    })


    $(document).on('click','.btn-interview-result', function () {
        var id = $(this).data('ja-id');
        showLoadingModal();
        $.ajax({
            url: '/ajax/interview/interview/result/'+id,
            data: $(this).serialize(),
            type: 'POST', //send it through get method
            datatype : 'json',
            success: function(rs) {
                Swal.close();
                $('#interview_result_modal').modal('show');
                $('#interview_answer').html('');
                $.each(rs.answer, (index, item) => {
                    $('#interview_answer').append(`<tr><td>${item.question}</td><td>:</td><td>${item.text}</td></tr>`)
                })

                $('#interview_result').html(`<tr>
                                            <td>Interview Type</td>
                                            <td>:</td>
                                            <td>${rs.result.type}</td>
                                        </tr>
                                        <tr>
                                            <td>Interviewer</td>
                                            <td>:</td>
                                            <td>${rs.result.interviewer}</td>
                                        </tr>
                                        <tr>
                                            <td>Communication Level Skill</td>
                                            <td>:</td>
                                            <td>${rs.result.communication_level_skill}</td>
                                        </tr>
                                        <tr>
                                            <td>Comment</td>
                                            <td>:</td>
                                            <td>${rs.result.interview_comment}</td>
                                        </tr>`)

            },
            error: function(response) {

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
            }
        });

    })

    $(document).on('click','.schedule_delete_btn', function (e) {

        var id = $(this).data('id')
        Swal.fire({
            title: 'Are you sure?',
            text: "This action will delete this item?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                e.preventDefault();

                $.ajax({
                    url: '/ajax/interview/schedule/delete/'+id,
                    data: $(this).serialize(),
                    type: 'POST', //send it through get method
                    datatype : 'json',
                    success: function(rs) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload();
                    },
                    error: function(response) {

                        btn.attr('disabled',false);
                        btn.html(text);
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
                    }
                });
            }
        });

        return false;
    })


// Get the elements
    var from_input = $('#startingDate').pickadate(),
        from_picker = from_input.pickadate('picker')
    var to_input = $('#endingDate').pickadate(),
        to_picker = to_input.pickadate('picker')

// Check if there’s a “from” or “to” date to start with and if so, set their appropriate properties.
    if (from_picker.get('value')) {
        to_picker.set('min', from_picker.get('select'))
    }
    if (to_picker.get('value')) {
        from_picker.set('max', to_picker.get('select'))
    }

// Apply event listeners in case of setting new “from” / “to” limits to have them update on the other end. If ‘clear’ button is pressed, reset the value.
    from_picker.on('set', function (event) {
        if (event.select) {
            to_picker.set('min', from_picker.get('select'))
        } else if ('clear' in event) {
            to_picker.set('min', false)
        }
    })
    to_picker.on('set', function (event) {
        if (event.select) {
            from_picker.set('max', to_picker.get('select'))
        } else if ('clear' in event) {
            from_picker.set('max', false)
        }
    })
});
