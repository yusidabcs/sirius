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

    const dt_table = $('#recruitments').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/recruitment/applicant",
            "type": "POST",
            data: function (d) {
                d.partner_id = $('#table_partner_search').val()
                d.status = 'interview'
                d.country = $('#table_country_search').val()
                d.schedule = 0
                d.register_method = $('#table_register_method').val()
            }
        },
        "columns": [
            {"data": "fullname"},
            {"data": "main_email"},
            {"data": "job_title"},
            {"data": "country"},
            {"data": "partner_name"},
            {"data": "status"},
            {"data": "premium_status"},
            {"data": null, "searchable": false, "sortable": false},
        ],
        "columnDefs": [
            {
                "render": function(data, type, row) {
                    var flag = '';
        
                    if (row.created_by == 0) {
                        flag = '<span class="badge badge-success"> Public</span>';
                    } else {
                        flag = '<span class="badge badge-warning"> Admin </span>';
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
                    if ((data == 'interview'))
                        /*data += '<br><a data-id="' + row['address_book_id'] + '" class="btn btn-success btn-sm pl-2 pr-2 pt-1 pb-1 text-white interview_scheduling" title="Schedule for interview">Scheduling</a> ';*/
                    return data;
                },
                "targets": 5
            },
            {
                "render": function (data, type, row) {
                    var premium_service = ((data == null) || (data == '')) ? 'none' : data;
                    if (row.premium_status == 'accepted') {
                        premium_service += '<br><a data-id="' + row['address_book_id'] + '" class="btn btn-success btn-sm pl-2 pr-2 pt-1 pb-1 text-white show_premium_service" title="Premium Service Detail"><i class="fas ';
                        premium_service += (row['premium_status'] == 'accepted') ? 'fa-dollar-sign text-primary' : 'fa-dollar-sign text-warning';
                        premium_service += '" ></i> </a>';
                    }
                    return premium_service
                },
                "targets": 6
            },
            {
                "render": function (data, type, row) {
                    var prescreening_url = $('#recruitments').data('prescreen-url');
                    console.log(row.premium_status)
                    var premium_service = '';
                    if (row.premium_status != 'accepted') {
                        premium_service = '<a data-id="' + row['address_book_id'] + '" class="p-2 white border show_premium_service"><i class="fas ';
                        premium_service += (row['premium_status'] == 'accepted') ? 'fa-dollar-sign text-primary' : 'fa-dollar-sign text-warning';
                        premium_service += '" title="Premium Service"></i> Premium</a>';
                    }


                    let html = `<div class="d-flex justify-content-around flex-column">   

                                    <a href="#" class="p-2 white border interview_scheduling" title="Pre interview checklist"
                                        data-status="${row.status}" data-id="' + row['address_book_id'] + '">
                                        <i class="fas fa-check text-info" ></i> Interview Schedule
                                    </a> `;
                    if (row.status == 'accepted' || row.status == 'interview') {
                        html += `<a href="#"  class="p-2 white border btn-prescreen-preview" title="Pre Screening Form"
                                        data-href="${prescreening_url + '/' + row.job_application_id }"
                                        data-id="${row.job_application_id}" >
                                        <i class="fas fa-list text-info" ></i> Pre Screening
                                    </a>`;
                    }
                    html += '</div>';
                    return html;
                },
                "targets": -1
            },
            {
                "render": function (data, type, row) {

                    let status = 'Not done';
                    if (data == false) {
                        status = 'All Good';
                    } else if (data == true) {
                        status = 'Need Review';
                    }
                    return status;
                },
                "targets": -2
            },
        ]
    });

    $('#table_status_search, #table_partner_search, #table_country_search, #table_register_method').on('change', function () {
        dt_table.ajax.reload()
    });

    //Interview Schedulling
    $(document).on('click', '.interview_scheduling', function (e) {

        e.preventDefault();
        const data = dt_table.row(this.closest('tr')).data();
        showLoadingModal();

        $.ajax({
            url: "/ajax/interview/schedule/job_application/"+data.job_application_id,
            type: 'POST',
        })
            .done(response => {
                if(response.interview_schedule == null){
                    $.ajax({
                        url: "/ajax/interview/location/polling",
                        type: 'POST',
                        data: {
                            'organizer_id': data.partner_id,
                        },
                    })
                        .done(response => {
                            $('#interview_scheduling_modal #interview_location_id').html('<option value="">Select Interview Location</option>');
                            $('#interview_scheduling_modal #job_application_id').val(data.job_application_id);
                            $.each(response, function (index, item) {
                                $('#interview_scheduling_modal #interview_location_id').append('<option value="'+item.interview_location_id+'">'+ item.interview_title +' '+ item.start_on +' - ' + item.finish_on + '</option>');
                            })
                            $('#interview_scheduling_modal').modal('show');

                            hideLoadingModal();
                            $('body').removeClass('swal2-shown swal2-height-auto');
                            $('html').removeClass('swal2-shown swal2-height-auto');
                            $('.swal2-container').remove();
                        })
                        .fail(response => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Connection to Server Failed!'
                            });
                        })

                }
                else{
                    var html = '<table class="table mt-3">' +
                        '<tr><td>Start On</td><td>'+response.start_on+'</td></tr>' +
                        '<tr><td>Finish On</td><td>'+response.finish_on+'</td></tr>' +
                        '<tr><td>Interviewer</td><td>'+response.interviewer+'</td></tr>' +
                        '<tr><td>Location</td><td>'+response.countryCode_id + ' - ' + response.countrySubCode_id + ' <hr>'+ response.address+'</td></tr>' +
                        '<tr><td>Google Map</td><td>'+response.google_map+'</td></tr>' +
                        '</table>';

                        hideLoadingModal();
                        $('body').removeClass('swal2-shown swal2-height-auto');
                        $('html').removeClass('swal2-shown swal2-height-auto');
                        $('.swal2-container').remove();

                    Swal.fire({
                        icon: 'info',
                        title: 'Interview Schedule Info',
                        html: html,
                        text : ''
                    });
                }
            })
            .fail(response => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Connection to Server Failed!'
                });
            })

    });

    $(document).on('click', '#submit_interview_scheduling', function (e) {

        var interview_location_id = $('#interview_scheduling_modal #interview_location_id').val();
        var type = $("#interview_scheduling_modal input[name='type']:checked").val();
        var job_application_id = $('#interview_scheduling_modal #job_application_id').val();
        if(type == 'online'){
            var schedule_on = $("#interview_scheduling_modal input[name='schedule_on']").val();
            var timezone = $("#interview_scheduling_modal select[name='timezone']").val();
            var meeting_code = $("#interview_scheduling_modal input[name='google_meet_code']").val();
            if(schedule_on == '' || timezone == ''){
                return false;
            }
        }else if(type == 'physical'){
            if(interview_location_id == ''){
                return false;
            }
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
                e.preventDefault();
                showLoadingModal();
                $.ajax({
                    url: "/ajax/interview/schedule/set_schedule",
                    type: 'POST',
                    data: {
                        'job_application_id': job_application_id,
                        'interview_location_id': interview_location_id,
                        'type': type,
                        'schedule_on': schedule_on,
                        'timezone' : timezone,
                        'google_meeting_code': meeting_code
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
                        dt_table.ajax.reload(false);
                        $('#interview_scheduling_modal').modal('hide');
                    })
                    .fail(response => {
                        hideLoadingModal();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Connection to Server Failed!'
                        });
                    })

            }
        });
    });

    $(document).on('click', '.show_premium_service', function ()
    {
        const data = dt_table.row(this.closest('tr')).data();

        //hide premium_file div
        $('#premium_file').removeClass('d-flex').hide();
        $('#premium_file_show_btn').prop('href','');
        $('#premium_file_download_btn').prop('href','');

        //set all default value first
        $('#address_book_id').val(data.address_book_id);
        $('#premium_email').val(data.main_email).change();
        $('#status').val(data.premium_status);
        $('#premium-info').hide();
        $('#premium-send-form').show();
        $("#type")[0].selectedIndex = 0;
        $('#premium-status').html('No data');

        if (!(data.premium_type == '' || data.premium_type == 'null' || data.premium_type == undefined))
            $('#type').val(data.premium_type);

        if (!(data.premium_status == '' || data.premium_status == 'null' || data.premium_status == undefined))
        {
            $('#premium-status').html(data.premium_status);

            if (data.premium_status == 'sending')
            {
                $('#premium-info').html('Sent on : ' +data.sending_on + '<br/>Sent by : ' +data.sending_by).show();

            }else if ( data.premium_status == 'accepted' || data.premium_status == 'rejected' || data.premium_status == 'paid' ){

                // if premium status is accepted, hide send email form
                if (data.premium_status == 'accepted')
                {
                    $('#premium-send-form').hide();
                }

                //show premium service file
                if ( (data.premium_file != '') && (data.premium_file != null) && (data.premium_file != undefined) )
                {
                    //display show and download button if premium file data exist
                    $('#premium_file').addClass('d-flex').show();
                    $('#premium_file_show_btn').prop('href',`/ab/show/${data.premium_file}`);
                    $('#premium_file_download_btn').prop('href',`/ab/download/${data.premium_file}/${data.fullname}-PSF`);

                }else{

                    Swal.fire({
                        icon: 'error',
                        title: 'Not Found',
                        text: 'Unable to find premium service file'
                    });
                }
            }
        }

        $('#show_premium_service_modal').modal('show');
    });

    $('input[type=radio][name=type]').change(function() {
        if (this.value == 'online') {
            $('#physical_interview').addClass('not-showing')
            $('#online_interview').removeClass('not-showing')
        }
        else if (this.value == 'physical') {
            $('#physical_interview').removeClass('not-showing')
            $('#online_interview').addClass('not-showing')
        }
    });

    $(document).on('click', '.btn-prescreen-preview', function () {
        var id = $(this).data('id');
        showLoadingModal();
        $.ajax({
            url: "/ajax/recruitment/prescreen/get/" + id,
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                answers = response.answers;
                let html = '';

                html += `<h4 class="text-center">Candidate Detail</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="info lighten-2">
                            <tr>
                                <td width="60%">Approval Local Partner/Licensed Partner (LP)'s Name:</td>
                                <td width="40%" id="header_lp_name">${response.applicant.partner_name}</td>
                            </tr>
                            <tr>
                                <td width="60%">Approval Local Partner/Licensed Partner (LP)'s Pre-screener:</td>
                                <td width="40%"
                                    id="header_lp_prescreener">${response.applicant.prescreener_full_name}</td>
                            </tr>
                            <tr>
                                <td width="60%">Pre-screening Interview Date (D-M-Y):</td>
                                <td width="40%" id="header_lp_interview_date">${response.job_prescreen.created_on}</td>
                            </tr>
                            <tr>
                                <td width="60%">Applicant's Full Name (as shown on Passport):</td>
                                <td width="40%" id="header_full_name">${response.applicant.full_name} <br> ${response.applicant.email}
                                </td>
                            </tr>
                            <tr>
                                <td width="60%">Position Applying For:</td>
                                <td width="40%" id="header_position">${response.applicant.job_position}</td>
                            </tr>
                            <tr>
                                <td width="60%">Status</td>
                                <td width="40%" id="header_status">${response.job_prescreen.status}
                                <br>
                                <button id="ps-resend-btn" data-id="${response.job_prescreen.job_application_id}" class="btn btn-sm btn-info ${response.job_prescreen.status == 'sending' ? '' : 'not-showing'}">Resend Email</button>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <hr>`
                $.each(response.questions, (index, item) => {
                    // if(answers[item['question_id']] == undefined){
                    //     return;
                    // }
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
                    $.each(item.childs, (index, item) => {
                        html +=
                            `<div class="question_row pt-3 pb-3 align-items-center border-top ${(item['type'] == 'heading') ? 'peach-gradient text-white p-3' : ''}">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="pl-3">${((item['type'] == 'heading') ? `<h5>${item['question']}</h5>` : item['sequence'] + '. ' + item['question'])}</div>
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

                            if (item['more'] == bool_tf) {
                                html += `<p class="border p-3" >${answers[item['question_id']]['text']}</p>`;
                            }
                        } else if (item['type'] == 'sa') {
                            html += `<p class="border p-3">${((answers[item['question_id']]) ? answers[item['question_id']]['text'] : '')}</p>`;
                        }
                        html += `</div>
                            </div>
                        </div>`;
                    });

                });
                $('#pre-screen-modal-body').html(html);

                $('#update-interview-btn').show();
                $('#update-interview-btn').attr('data-id', id);
                if(response.job_prescreen.status == 'interview' || response.job_prescreen.status == 'sending' || response.applicant.job_status == 'interview'){
                    $('#update-interview-btn').hide();
                }

                $('#pre-screening-interview').modal('show');
                hideLoadingModal();
                $('body').removeClass('swal2-shown swal2-height-auto');
                $('html').removeClass('swal2-shown swal2-height-auto');
                $('.swal2-container').remove();
            },
            error: function (rs) {
            }
        })
        return false;
    });

});
