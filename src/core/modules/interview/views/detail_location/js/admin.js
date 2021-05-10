$(document).ready(function () {
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

    var id = $('#interview_location_id').val();
    const table = $('#detail_schedule_candidate').DataTable( {
            "processing": true,
            "serverSide": true,
            'responsive': true ,
            "ajax": {
                "url": "/ajax/interview/schedule/list_candidate/"+id,
                "type": "POST",
                data: function (d) {
                }
            },
            "columns": [
                { "data" : 'main_email'},
                { "data" : 'job_title'},
                { "data" : null},
                { "data" : 'number_given_name'}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return row.title +' ' +row.entity_family_name+ '' + row.number_given_name + '<br> (' + row.main_email + ')';
                    },
                    "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        return row.job_speedy_code +' - ' + row.job_title;
                    },
                    "targets": 1
                },

                {
                    "render": function ( data, type, row ) {
                        console.log(row.job_application_status)
                        if(row.job_application_status == 'interview')
                            data = '<label class="badge badge-warning">Not interviewed</label>'
                        else if(row.job_application_status == 'hired' || row.job_application_status == 'allocated')
                            data = '<label class="badge badge-success">Hired</label>'
                        else if(row.job_application_status == 'security' )
                            data = '<label class="badge badge-success">Security Check</label>'
                        else
                            data = '<label class="badge badge-danger">Not Hired</label>'
                        return data
                    },
                    "targets": 2
                },


                {
                    "render": function ( data, type, row ) {
                        var url = $('#detail_schedule_candidate').data('url')
                        var status_interview = $('#status_interview').val()
                        var html = ''
                        if(row.job_application_status == 'interview'){
                            html += `<div class="container d-flex text-center ">
                                    <a class="btn btn-sm btn-success btn-interview" href="${url}/interview/${row.schedule_id}" >Interview</a>
                                <div>`;
                        }


                        if(status_interview == 0)
                            html += `<div class="container d-flex text-center ">
                                    <a class="btn btn-sm btn-light" href="#" >Interview date not started</a>
                                <div>`;
                        if(row.job_application_status == 'hired' || row.job_application_status == 'not_hired') {
                            html += `<div class="container d-flex text-center ">
                                    <a class="btn btn-sm btn-info btn-result" data-ja-id="${row.job_application_id}" >Result</a>
                                <div>`;
                        }


                        return html;
                    },
                    "targets": -1
                }
            ],
            'order': [
                [1, 'asc']
            ]
        }
    );

    $(document).on('click','.btn-result', function () {
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

    $('#btn-close-interview').on('click', function () {

        Swal.fire({
            title: 'Close Interview',
            text: "If interview closed, you will not able to continue interviewing the available candidate .",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Close it!'
        }).then((result) => {
            if (result.value) {

                showLoadingModal();
                $.ajax({
                    url: '/ajax/interview/location/close/'+$(this).data('id'),
                    type: 'POST', //send it through get method
                    datatype : 'json',
                    success: (rs) => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification...',
                            text: 'Successfully close interview!'
                        });
                        window.location.href = $(this).attr('href');
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
            }
        })
        return false;
    })

});
