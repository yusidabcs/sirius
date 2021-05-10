$(document).ready(function () {

    $(document).ready(function() {
        $('.mdb-select').materialSelect();
    });

    const table = $('#list_interview_principal').DataTable( {
            "processing": true,
            "serverSide": true,
            //'responsive': true ,
            "ajax": {
                "url": "/ajax/interview/interview/list_result",
                "type": "POST",
                data: function (d) {
                    d.start_on = $('#startingDate').val()
                    d.end_on = $('#endingDate').val()
                    d.principal = $('#principal').val()
                    d.organizer_id = $('#organizer').val()
                    d.type = $('#type').val()
                    d.status = $('#status').val()
                    d.job_speedy_code = $('#job').val(),
                    d.menu = 'principal'
                }
            },
            "columns": [
                { "data" : 'candidate'},
                { "data" : null},
                { "data" : 'organizer'},
                { "data" : 'principal_fullname'},
                { "data" : 'status'},
                { "data" : 'created_on'},
                { "data" : null}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return data + ' <br> ' + row.main_email;
                    },
                    "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        return row.job_speedy_code + ' - ' + row.job_title;
                    },
                    "targets": 1
                },

                {
                    "render": function ( data, type, row ) {
                        return row.type == 'online'  ? 'Online' : data;
                    },
                    "targets": 2
                },

                {
                    "render": function ( data, type, row ) {
                        if(data == 'hired'){
                            return '<span class="text-info">Hired</span>'
                        }else if(data == 'allocated'){
                            return '<span class="text-success">Allocated</span>'
                        }else if(data == 'not_hired'){
                            return '<span class="text-danger">Not Hired</span>'
                        }else{
                            return data;
                        }
                    },
                    "targets": 4
                },

                {
                    "render": function ( data, type, row ) {
                        var html = `<div class="container d-flex text-center ">
                                    <a  class="btn-sm btn-success btn-interview-result" href="#" data-ja-id="${row.job_application_id}"  ><i class="fa fa-comment-alt" title="Interview Result"></i></a>&nbsp; 
                                    <a  class="btn-sm btn-warning btn-pdf" href="/ajax/interview/interview/pdf-interview/${row.job_application_id}" target="_blank" data-id="${row.job_application_id}" title="PDF detail interview result" ><i class="fas fa-file-pdf"></i></a>
                                <div>`;

                        return html;
                    },
                    "targets": -1, 'orderable':false, 'searchable':false
                }
            ],
            'order': [
                [5, 'asc']
            ]
        }
    );

    $('#startingDate, #endingDate, #principal, #organizer, #type, #status, #job').on('change', function () {
        table.ajax.reload();
    });

    $('#btn_export_modal').on('click',function(){
        window.open('/ajax/interview/interview//pdf-interview/'+$(this).data('id'));
    })

    $(document).on('click','.btn-interview-result', function () {
        var id = $(this).data('ja-id');
        $('#interview_result_modal').modal('show');
        $('#btn_export_modal').data('id',id);
        $.ajax({
            url: '/ajax/interview/interview/result/'+id,
            data: $(this).serialize(),
            type: 'POST', //send it through get method
            datatype : 'json',
            success: function(rs) {
                $('#interview_answer').html('');
                $.each(rs.answer, (index, item) => {
                    $('#interview_answer').append(`<tr><td width="50%">${item.question}</td><td width="5%">:</td><td>${item.text}</td></tr>`)
                })

                $('#interview_result').html(`<tr>
                                            <td width="50%">Interview Type</td>
                                            <td width="5%">:</td>
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






// Get the elements
    var from_input = $('#startingDate').pickadate({
        format: 'mmmm dd, yyyy'
    }),
        from_picker = from_input.pickadate('picker')
    var to_input = $('#endingDate').pickadate({
        format: 'mmmm dd, yyyy'
    }),
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
