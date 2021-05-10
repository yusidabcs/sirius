$(document).ready(function () {


    $('#table_status_search').materialSelect()
    $('#table_level_search').materialSelect()

    const table = $('#list_interview_security_check').DataTable({
        "processing": true,
        "serverSide": true,
        'responsive': true,
        "ajax": {
            "url": "/ajax/interview/security_check/list",
            "type": "POST",
            data: function (d) {
                d.status = $('#table_status_search').val()
            }
        },
        "columns": [
            {"data": null},
            {"data": 'candidate'},
            {"data": 'job_title'},
            {"data": 'status'},
            {"data": 'level'},
            {"data": null}
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
                targets: 1,
                "render": function (data, type, row) {
                    return data + '<br> '+row.main_email;
                }
            },
            {
                targets: 3,
                "render": function (data, type, row) {
                    if (row.status == 'request_file') {
                        return row.status + '<br> Send On: ' + row.request_file_on
                    }
                    if (row.status == 'request_clearance') {
                        return row.status + '<br> Requested On: ' + row.request_clearance_on
                    }
                    return data;
                }
            },

            {
                "render": function (data, type, row) {
                    if (row.level == 'soft') {
                        return '<label class="badge badge-info">Soft Warning</label>'
                    }
                    else if (row.level == 'hard') {
                        return '<label class="badge badge-warning">Soft Warning</label>'
                    }
                    else if (row.level == 'deadline') {
                        return '<label class="badge badge-danger">Soft Warning</label>'
                    }
                    else {
                        return '<label class="badge badge-success">Normal</label>'
                    }
                },
                "targets": -2
            },
            {
                "render": function (data, type, row) {
                    var html = '<div class="container d-flex text-center ">';
                    if (row.status == 'request_file') {
                        html += `
                                    <a class="btn-sm btn-info text-white btn-request-file" href="#" title=""><i class="fas fa-eye"></i> Send Request File</a>
                                `;
                        if (row.request_file_on !== '0000-00-00 00:00:00') {
                            html += `
                                    <a class="btn-sm btn-info text-white btn-upload-file" href="#" title=""><i class="fas fa-file-pdf"></i> Upload Pasport</a>
                                `;
                        }

                    }
                    else if (row.status == 'request_clearance') {
                        html += `
                                    <a class="btn-sm btn-info text-white btn-request-clearance" href="#" title=""><i class="fas fa-mail-bulk"></i> Send Request Clearance</a>
                                `;
                        if (row.request_clearance_on !== '0000-00-00 00:00:00') {
                            html += `
                                    <a class="btn-sm btn-info text-white btn-update-status" href="#" title=""><i class="fas fa-file-pdf"></i> Update Status</a>
                                `;
                        }
                    }else{
                        html += `<a class="border text-danger" target="_blank" href="/secure_file/show/${row.passport_file_hash}" title=""><small><i class="fas fa-file"></i> Passport File</small> </a>&nbsp;`;
                        html += `<a class="border text-info" target="_blank" href="/secure_file/show/${row.clearance_file_hash}" title=""><small><i class="fas fa-file"></i> Clearance File</small></a>`;
                    }

                    return html;
                },
                "targets": -1
            }
        ],
        select: {
            style: 'multi',
            selector: 'td:first-child'
        },
    });

    $('#table_status_search, #table_level_search').on('change', function () {
        table.ajax.reload();
    });

    $(document).on('click', '.btn-request-file', function () {

        job_application_id = []
        const data = table.rows({selected: true}).data();
        $.each(data, (i, item) => {
            job_application_id.push(item.job_application_id)
        })
        if (job_application_id.length > 0) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action will send request security check file (passport) to candidate?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/ajax/interview/security_check/send_request_file',
                        data: {
                            'job_application_id': job_application_id,
                        },
                        type: 'POST', //send it through get method
                        datatype: 'json',
                        success: function (rs) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Notification!',
                                text: rs.message
                            });
                            table.ajax.reload();
                        },
                        error: function (response) {

                            btn.attr('disabled', false);
                            btn.html(text);
                            if (response.status == 400) {
                                text = ''
                                $.each(response.responseJSON.errors, (index, item) => {
                                    text += item + '<br>';
                                })
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    html: text
                                });
                            } else {
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
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: 'Please select minimal 1 candidate.'
            });
        }
        return false;
    })

    $(document).on('click', '.schedule_delete_btn', function (e) {

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
                    url: '/ajax/interview/location/delete/' + id,
                    data: $(this).serialize(),
                    type: 'POST', //send it through get method
                    datatype: 'json',
                    success: function (rs) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload();
                    },
                    error: function (response) {

                        btn.attr('disabled', false);
                        btn.html(text);
                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
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
    });

    $(document).on('click', '.btn-upload-file', function () {

        const data = table.row(this.closest('tr')).data();
        $('#passport_file_form').find('#job_application_id').val(data.job_application_id)
        $('#passport_file_modal').modal('show');

        return false;
    });

    $(document).on('click', '.btn-update-status', function () {
        job_application_id = []
        const data = table.rows({selected: true}).data();
        $.each(data, (i, item) => {
            job_application_id.push(item.job_application_id)
        })
        if(job_application_id.length != 0){
            $('#clearance_file_modal').modal('show');
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select minimal one candidate!'
            });
        }
        return false;
    });

    $('#clearance_file_form').submit(function (event) {

        var job_application_id = []
        var jobs = table.rows({selected: true}).data();
        var data = new FormData(this);
        $.each(jobs, (i, item) => {
            data.append('job_application_id[]',item.job_application_id)
        })
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "This action will update all the selected security check to : "+$(this).find('input[name=status]:checked').val()+ '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    "url": "/ajax/interview/security_check/upload_clearance_file",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function () {
                        table.ajax.reload(false)

                        $(this).trigger('reset');

                        $('#clearance_file_modal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });

                    },
                    error: function (response) {

                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                })
            }
        });
    });

    // event listener untuk form saat di submit
    $('#passport_file_form').submit(function (event) {
        // mencegah browser mensubmit form.
        console.log(this)
        event.preventDefault();
        $.ajax({
            type: 'POST',
            "url": "/ajax/interview/security_check/upload_passport_file",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function () {
                table.ajax.reload()

                $(this).trigger('reset');

                $('#passport_file_modal').modal('hide');

            }
        })
    });
    
    $(document).on('click','.btn-request-clearance', function () {
        var btn = $(this)
        job_application_id = []
        const data = table.rows({selected: true}).data();
        $.each(data, (i, item) => {
            job_application_id.push(item.job_application_id)
        })
        if (job_application_id.length > 0) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action will send email to principal to request security clearance? It better to send multiple candidate at once!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/ajax/interview/security_check/send_request_clearance',
                        data: {
                            'job_application_id': job_application_id,
                        },
                        type: 'POST', //send it through get method
                        datatype: 'json',
                        success: function (rs) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Notification!',
                                text: rs.message
                            });
                            table.ajax.reload();
                        },
                        error: function (response) {

                            btn.attr('disabled', false);
                            btn.html(text);
                            if (response.status == 400) {
                                text = ''
                                $.each(response.responseJSON.errors, (index, item) => {
                                    text += item + '<br>';
                                })
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    html: text
                                });
                            } else {
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
        }
        return false;
    })
});
