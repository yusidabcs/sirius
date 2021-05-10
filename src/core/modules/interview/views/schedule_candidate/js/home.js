$(document).ready(function () {
    $('#search_country').materialSelect();
    $('#search_status').materialSelect();
    $('#table_partner_search').materialSelect();
    $('#table_status_search').materialSelect();
    $('#table_country_search').materialSelect();
    $('#table_register_method').materialSelect();
    $('#timezone').materialSelect();
    $('#schedule_on').dateTimePicker();

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
    const hideLoadingModal = function() {
        Swal.hideLoading();
    }

    var dt_table = $('#recruitments').DataTable({
        "processing": true,
        "serverSide": true,
        "order":[],
        "ajax": {
            "url": "/ajax/recruitment/applicant",
            "type": "POST",
            data: function (d) {
                d.partner_id = $('#table_partner_search').val()
                d.status = 'interview'
                d.country = $('#table_country_search').val()
                d.schedule = 1
                d.register_method = $('#table_register_method').val()
            }
        },
        "columns": [
            {"data": "fullname"},
            {"data": "main_email"},
            {"data": "job_title"},
            {"data": "country"},
            {"data": "partner_name"},
            {"data": null},
            {"data": null, "searchable": false, "sortable": false},
        ],
        "columnDefs": [
            {
                "render": function(data, type, row) {
                    var flag = '';
        
                    if (row.created_by == 0) {
                        flag = '<span class="badge badge-success"> Public</span>';
                    } else {
                        flag = '<span class="badge badge-warning"> Admin Inputed</span>';
                    }

                    html = '<a href="/personal/home/' + row.address_book_id + '" class="p-1 white" target="_blank"><i class="far fa-user text-success" title="Show Personal"></i></a> ';
                    
                    return html + row.fullname + '<br>' + flag;
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    return row.job_speedy_code + ' - ' + row.job_title
                },
                "targets": 2
            },
            {
                "render": function (data, type, row) {
                    if ((data == null) || (data == ''))
                        data = 'No partner';
                    return data;
                },
                "targets": 4
            },
            {
                "render": function (data, type, row) {
                    data ='Not Set';
                    if(row.type=='online') {
                        data = row.schedule_on;
                    } else {
                        data = row.start_on;
                    }
                    return data;
                },
                "targets": 5
            },
            {
                "render": function (data, type, row) {
                    let html = ''
                    html += '<a data-id="' + row['address_book_id'] + '" class="btn btn-success btn-sm pl-2 pr-2 pt-1 pb-1 text-white interview_scheduling" title="Schedule for interview"><i class="fa fa-eye"></i> Schedule</a> ';
                    return html;
                },
                "targets": -1
            }
        ]
    });

    $('#table_status_search, #table_partner_search, #table_country_search, #table_register_method').on('change', function () {
        dt_table.ajax.reload()
    });
    //Interview Schedulling
    var schedule = null;
    $(document).on('click', '.interview_scheduling', function () {
        const data = dt_table.row(this.closest('tr')).data();
        const ab_id = $(this).data('id');
        showLoadingModal();
        $.ajax({
            url: "/ajax/interview/schedule/job_application/"+data.job_application_id,
            type: 'POST',
        })
            .done(response => {
                hideLoadingModal();
                $('body').removeClass('swal2-shown swal2-height-auto');
                $('html').removeClass('swal2-shown swal2-height-auto');
                $('.swal2-container').remove();

                var html = '<table class="table table-border mt-3 text-left">';
                if(response.interview_schedule.type == 'online'){
                    schedule = response.interview_schedule
                    var title = 'Online Interview Schedule Detail';
                    html += '<tr><td>Start On</td><td>:</td><td>' + response.interview_schedule.schedule_on + '</td></tr>' +
                        '<tr><td>Timezone</td><td>:</td><td>' + response.interview_schedule.timezone + '</td></tr>' +
                        '<tr><td>Interviewer</td><td>:</td><td>' + (response.interview_schedule.interviewer ? response.interview_schedule.interviewer : '-')  + '</td></tr>';
                }else if(response.interview_schedule.type == 'physical'){
                    var title = 'Interview Schedule Detail';
                    html += '<tr><td>Start On</td><td>:</td><td>' + response.interview_schedule.start_on + '</td></tr>' +
                        '<tr><td>Finish On</td><td>:</td><td>' + response.interview_schedule.finish_on + '</td></tr>' +
                        '<tr><td>Interviewer</td><td>:</td><td>' + (response.interview_schedule.interviewer ? response.interview_schedule.interviewer : '-')  + '</td></tr>' +
                        '<tr><td>Location</td><td>:</td><td>' + response.interview_schedule.countryCode_id + ' - ' + response.interview_schedule.countrySubCode_id + ' <hr>' + response.interview_schedule.address + '</td></tr>' +
                        '<tr><td>Google Map</td><td>:</td><td>' + response.interview_schedule.google_map + '</td></tr>';
                }

                if(response.interview_result.length == 0){
                    html += '<tr><td><button class="btn btn-info btn-sm btn-change-schedule" data-schedule-type="'+response.interview_schedule.type+'" data-schedule-id="'+response.interview_schedule.schedule_id+'" data-location-id="'+response.interview_schedule.interview_location_id+'" data-partner-id="'+data.partner_id+'">Change Schedule</button></td><td></td><td><button class="btn btn-danger btn-sm btn-remove-schedule" data-schedule-id="'+response.interview_schedule.schedule_id+'" data-ab-id="'+ab_id+'">Remove Schedule</button></td></tr>';
                }
                html +='</table>';
                if(response.interview_result.length > 0){
                    html +='<center>Interview Result</center><table class="table mt-3">' +
                    '<tr><td>Interview Result</td><td>:</td><td>' + response.interview_result.status + '</td></tr>' +
                    '</table>';
                }
                Swal.fire({
                    icon: 'info',
                    title: title,
                    html: html,
                    text: '',
                    showCloseButton: true,
                    showCancelButton: false,
                    showConfirmButton: false,
                });
            })
            .fail(response => {
                hideLoadingModal();
                $('body').removeClass('swal2-shown swal2-height-auto');
                $('html').removeClass('swal2-shown swal2-height-auto');
                $('.swal2-container').remove();

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Connection to Server Failed!'
                });
            })


    });


    $('body').on('click','.btn-change-schedule', function () {
        var partner_id = $(this).data('partner-id');
        var interview_location_id = $(this).data('location-id');
        var schedule_id = $(this).data('schedule-id');
        var ja_id = $(this).data('ja-id');
        var type = $(this).data('schedule-type');
        showLoadingModal();
        if(type == 'physical'){
            $.ajax({
                url: "/ajax/interview/location/polling",
                type: 'POST',
                data: {
                    'organizer_id': partner_id,
                    'interview_location_id': interview_location_id,
                },
            })
                .done(response => {
                    Swal.close();
                    var data = {}
                    $.each(response, (index, item) => {
                        data[item.interview_location_id] =  item.interview_title+' '+ item.start_on +' - ' + item.finish_on
                    });
                    Swal.fire({
                        title: 'Select schedule',
                        input: 'select',
                        inputOptions:data ,
                        inputPlaceholder: 'Select a schedule',
                        showCancelButton: true,
                        inputValidator: (interview_location_id) => {
                            return new Promise((resolve) => {
                                if (interview_location_id !== '') {
                                    Swal.fire({
                                        title: 'Are you sure?',
                                        text: "This will submit the applicant to interview schedule?",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Yes'
                                    }).then((result) => {
                                        if (result.value) {
                                            showLoadingModal();
                                            $.ajax({
                                                url: "/ajax/interview/schedule/update_physical_schedule",
                                                type: 'POST',
                                                data: {
                                                    'schedule_id': schedule_id,
                                                    'interview_location_id': interview_location_id,
                                                },
                                            })
                                                .done(response => {
                                                    Swal.close();
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Information',
                                                        text: response.message
                                                    })
                                                    dt_table.ajax.reload(false);

                                                    //resolve()
                                                })
                                                .fail(response => {
                                                    Swal.close();
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Oops...',
                                                        text: 'Connection to Server Failed!'
                                                    });

                                                    //resolve()
                                                })

                                        }
                                    });
                                } else {
                                    resolve('You need to select interview location')
                                }
                            })
                        }
                    })
                })
                .fail(response => {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Connection to Server Failed!'
                    });
                })
        }else{
            swal.close()
            $('#interview_scheduling_modal').modal('show')
            $('#schedule_on').val(schedule.schedule_on)
            $('#timezone').val(schedule.timezone)
        }
    })

    $('body').on('click','.btn-remove-schedule', function () {
        var schedule_id = $(this).data('schedule-id');
        var address_book_id = $(this).data('ab-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This will remove candidate from interview schedule?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    url: "/ajax/interview/schedule/remove_schedule",
                    type: 'POST',
                    data: {
                        'schedule_id': schedule_id,
                        'address_book_id': address_book_id,
                    },
                })
                    .done(response => {
                        hideLoadingModal();
                        $('body').removeClass('swal2-shown swal2-height-auto');
                        $('html').removeClass('swal2-shown swal2-height-auto');
                        $('.swal2-container').remove();

                        Swal.fire({
                            icon: 'success',
                            title: 'Information',
                            text: response.message
                        })
                        dt_table.ajax.reload();
                    })
                    .fail(response => {
                        hideLoadingModal();
                        $('body').removeClass('swal2-shown swal2-height-auto');
                        $('html').removeClass('swal2-shown swal2-height-auto');
                        $('.swal2-container').remove();

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Connection to Server Failed!'
                        });

                        resolve()
                    })

            }
        });
    })

    $(document).on('click', '#submit_interview_scheduling', function (e) {
        var btn = $(this)
        var schedule_on = $("#interview_scheduling_modal input[name='schedule_on']").val();
        var timezone = $("#interview_scheduling_modal select[name='timezone']").val();
        if(schedule_on == '' || timezone == ''){
            Swal.fire({
                title: 'Warning',
                icon: 'warning',
                text: "Schedule and Time Zone cannot be empty!"
            });
            return false;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "This will submit the applicant to interview schedule?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                e.preventDefault();
                btn.attr('disabled',true)
                $.ajax({
                    url: "/ajax/interview/schedule/update_online_schedule",
                    type: 'POST',
                    data: {
                        'schedule_id': schedule.schedule_id,
                        'schedule_on': schedule_on,
                        'timezone': timezone,
                    },
                })
                    .done(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Information',
                            text: response.message
                        })
                        btn.attr('disabled',false)
                        $('#interview_scheduling_modal').modal('hide');
                        dt_table.ajax.reload(false);
                    })
                    .fail(response => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Connection to Server Failed!'
                        });
                        btn.attr('disabled',false)
                    })

            }
        });
    });
});

