$(document).ready(function () {
    $('#search_country').materialSelect();
    $('#search_status').materialSelect();
    $('#table_partner_search').materialSelect();
    $('#table_status_search').materialSelect();
    $('#table_country_search').materialSelect();
    $('#user_acceptance').materialSelect();
    $('#table_register_method').materialSelect();

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
        "order":[],
        "ajax": {
            "url": "/ajax/recruitment/applicant",
            "type": "POST",
            data: function (d) {
                d.partner_id = $('#table_partner_search').val()
                d.status = 'accepted'
                d.country = $('#table_country_search').val()
                d.register_method = $('#table_register_method').val()
            }
        },
        "columns": [
            {"data": "main_email"},
            {"data": "job_title"},
            {"data": "country"},
            {"data": "partner_name"},
            {"data": "status"},
            {"data": "premium_status"},
            {"data": "applied_on"},
            {"data": null, "searchable": false, "sortable": false},

            {"data": 'entity_family_name',"visible": false,},
            {"data": 'fullname',"visible": false,},
            {"data": 'middle_names',"visible": false,},
            {"data": 'middle_names',"visible": false,},
        ],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    var flag = '';
        
                    if (row.created_by == 0) {
                        flag = '<span class="badge badge-success">Public</span>';
                    } else {
                        flag = '<span class="badge badge-warning">Admin</span>';
                    }
                    return '<a target="_blank" href="/personal/home/' + row['address_book_id'] + '" >' + row.fullname + '</a> <br> ' + row.main_email + '<br>' + flag;
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    return row.job_speedy_code + ' - ' + row.job_title
                },
                "targets": 1
            },
            {
                "render": function (data, type, row) {
                    if ((data == null) || (data == ''))
                        data = 'No partner';
                    return data;
                },
                "targets": 3
            },
            {
                "render": function (data, type, row) {
                    if ((data == 'interview'))
                        data += '<br><a data-id="' + row['address_book_id'] + '" class="btn btn-success btn-sm pl-2 pr-2 pt-1 pb-1 text-white interview_scheduling" title="Schedule for interview">Scheduling</a> ';
                    return data;
                },
                "targets": 4
            },
            {
                "render": function (data, type, row) {
                    var premium_service = ((data == null) || (data == '')) ? 'Not Requested' : data;

                    if (premium_service === 'sending') {
                        premium_service = 'Psf Sent';
                    }
                    
                    if (row.premium_verified === 'accepted') {
                        premium_service += '<br><span class="badge badge-success">'+row.premium_verified+'</span>';
                    } else if (row.premium_verified === 'unknown') {
                        premium_service += '<br><span class="badge badge-warning">'+row.premium_verified+'</span>';
                    } else{
                        premium_service += '<br><span class="badge badge-danger">'+row.premium_verified+'</span>';
                    }
                    return premium_service
                },
                "targets": 5
            },
            {
                "render": function (data, type, row) {
                    var prescreening_url = $('#recruitments').data('prescreen-url');
                    var premium_service = '';
                    premium_service = '<a data-id="' + row['address_book_id'] + '" title="Premium Service" class="btn btn-sm btn-outline show_premium_service"><i class="fas ';
                    premium_service += (row['premium_status'] == 'accepted') ? 'fa-dollar-sign text-primary' : 'fa-dollar-sign text-warning';
                    premium_service += '" ></i></a>';

                    var ctrac = ''
                    if(row.status == 'applied') {
                        ctrac = `<a href="#" class="btn btn-sm ctrac-modal" title="Ctrack Status"
                                        data-status="${row.status}" data-id="${row.job_application_id}">
                                        <i class="fas fa-book-open text-info" ></i> Online Application
                                    </a>`;
                    }


                    let html = `<div class="btn-group">   

                                    <a href="#" class="btn btn-sm pre-interview-checklist" title="Pre interview checklist"
                                        data-status="${row.status}" data-id="${row.job_application_id}">
                                        <i class="fas fa-check text-info" ></i>
                                    </a>   
                                    ${premium_service} ${ctrac}`;
                    if (row.status == 'accepted' || row.status == 'interview') {
                        html += `<a href="${prescreening_url + '/' + row.job_application_id }"  class="btn btn-sm  pre-screening-interview" title="Pre Screening Form"
                                        data-href="${prescreening_url + '/' + row.job_application_id }"
                                        data-ja-id="${row.job_application_id}" >
                                        <i class="far fa-comments text-info"></i>
                                    </a>`;
                    }
                    html += '</div>';
                    return html;
                },
                "targets": 7
            },
        ]
    });

    $('#table_status_search, #table_partner_search, #table_country_search,#table_register_method').on('change', function () {
        dt_table.ajax.reload()
    });

    // pre screen interview
    $(document).on('click', '.pre-screening-interview', function (e) {
        e.preventDefault();
        const job_application_id = $(this).data('ja-id');
        const data = dt_table.row(this.closest('tr')).data();
        const href = $(this).data('href');
        //check from ajax, is valid or not. if valid show modal, if not redirect
        showLoadingModal();
        $.ajax({
            url: "/ajax/recruitment/prescreen/check/" + job_application_id,
            type: 'POST',
        })
            .done(function (response) {
                if(response.result == false || (response.status == 'pending' || response.status !== 'revision')) {
                    window.open(href,'_SELF');
                    return true;
                    
                }
                    
                if(response.status !== 'pending' && response.status !== 'revision') {
                    $('#update-interview-btn').data('id', job_application_id);
                    $.ajax({
                        url: "/ajax/recruitment/prescreen/get/" + job_application_id,
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
                            if (response.job_prescreen.status == 'interview' || response.job_prescreen.status == 'sending' || response.applicant.job_status == 'interview') {
                                $('#update-interview-btn').hide();
                                $('.table-choose-principal').hide();
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
                }
            })
            .fail(function () {
                Swal.fire({
                    icon: 'error',
                    text: "Error something wrong",
                });

                return false;
            });
    });

    $(document).on('click', '#ps-resend-btn', function () {
        let btn = $('#ps-resend-btn');
        var id = $(this).data('id')
        Swal.fire({
            title: 'Resend pre screen result to candidate',
            text: "Please make sure all the answer is answered and filled correctly and honestly by candidates .",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Send Now!'
        }).then((result) => {
            if (result.value) {
                btn.html('Sending...').addClass('disabled');
                $.ajax({
                    url: "/ajax/recruitment/prescreen/sendemail/" + id,
                    success: function (rs) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Information',
                            text: rs.message
                        })
                        btn.html('RESEND EMAIL').removeClass('disabled');
                    },
                    error: function (rs) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning',
                            text: 'Something went wrong..'
                        })
                        btn.html('RESEND EMAIL').removeClass('disabled');
                    }
                })
            }
        })
    });

    $(document).on('click', '#ps-reject-btn', function () {
        Swal.fire({
            title: 'Reject the Applicant?',
            text: "Please make sure check all the data before continue",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Reject it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/ajax/recruitment/applicant/reject/' + $(this).data('id'),
                    success: function (rs) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Information',
                            text: rs.message
                        })
                        dt_table.ajax.reload(false);
                        $('#pre-screening-interview').modal('hide');
                    },
                    error: function (rs) {

                    }
                })
            }
        })
    });

    //update to job application to interview
    $(document).on('click', '#update-interview-btn', function () {
        var id = $(this).data('id');
        var principal = $('select[name="principal"]');

        if (principal.val() === '') {
            Swal.fire({
                title: 'Warning',
                text: "Please select principal",
                icon: 'warning'
            })
        } else {
            Swal.fire({
                title: 'Update To Interview',
                text: "Please make sure all the answer is correct, the system will update candidate job status to interview. Once in interview status, you can request for interview schedule",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Update to interview!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "/ajax/recruitment/applicant/accept-interview/" + id,
                        method: "POST",
                        data: {
                            principal_code: principal.val()
                        },
                        success: function (rs) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Information',
                                text: rs.message
                            });
                            dt_table.ajax.reload(false);
                            principal.val('');
                            $('#pre-screening-interview').modal('hide');
                        },
                        error: function (rs) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Warning',
                                text: 'Something went wrong..'
                            })
                        }
                    })
                }
            })
        }
        // Swal.fire({
        //     title: 'Accept the Applicant for Interview?',
        //     text: "Please make sure all the pre interview requirement is complete and correct.",
        //     icon: 'warning',
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     confirmButtonText: 'Yes, Accept it!'
        // }).then((result) => {
        //     if (result.value) {
        //         $.ajax({
        //             url: '/ajax/recruitment/applicant/accept-interview/' + $(this).data('id'),
        //             success: function (rs) {
        //                 Swal.fire({
        //                     icon: 'success',
        //                     title: 'Information',
        //                     text: rs.message
        //                 })
        //                 dt_table.ajax.reload(false);
        //                 $('#pre-screening-interview').modal('hide');
        //             },
        //             error: function (rs) {

        //             }
        //         })
        //     }
        // })
    });
    //end update to job application to interview


    //Premium Service

    $(document).on('click', '.show_premium_service', function () {
        const data = dt_table.row(this.closest('tr')).data();
        //hide premium_file div
        $('#premium_file').removeClass('d-flex').hide();
        $('#premium_file_show_btn').prop('href', '');
        $('#premium_file_download_btn').prop('href', '');

        //set all default value first
        $('#address_book_id').val(data.address_book_id);
        $('#premium_email').val(data.main_email).change();
        $('#status').val(data.premium_status);
        $('#premium-info').hide();
        $('#premium-send-form').show();
        $("#type")[0].selectedIndex = 0;
        $('#premium-status').html('No data');

        if(data.premium_status == null) {

            $('#premium-send-form').show();
            $('#premium-confirm-placeholder').hide();

        }else if( data.premium_status == 'sending'){
            $('#premium-status').html(data.premium_status);

            $('#premium-info').html('Latest sent on : ' + data.sending_on ).show();
            $('#premium-verified').html(data.premium_verified);

            if(data.premium_verified != 'unknown'){
                $('#premium-send-form').hide();
                if(data.premium_verified == 'rejected'){
                    $('#premium_service_resend_btn').show()
                }else{
                    $('#premium_service_resend_btn').hide()
                }
                $('#premium-send-form').hide();
                $('#premium-confirm-placeholder').show();
                $('#premium-info').html('Verified on : ' + data.verified_on ).show();
            }else{
                $('#premium-send-form').show();
                $('#premium-confirm-placeholder').hide();
            }
        }else{
            $('#premium-status').html(data.premium_status);
            $('#premium-verified').html(data.premium_verified);
            
            $('#premium-send-form').hide();
            $('#premium-confirm-placeholder').hide();

            //show premium service file
            if ((data.premium_file != '') && (data.premium_file != null) && (data.premium_file != undefined)) {
                //display show and download button if premium file data exist
                $('#premium_file').addClass('d-flex').show();
                $('#premium_file_show_btn').prop('href', `/ab/show/${data.premium_file}`);
                $('#premium_file_download_btn').prop('href', `/ab/download/${data.premium_file}/${data.fullname}-PSF`);

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Not Found',
                    text: 'Unable to find premium service file'
                });
            }
        }

        $('#show_premium_service_modal').modal('show');
    });
    
    //resend premium service
    $(document).on('click', '#premium_service_resend_btn', function () {
        $('#premium-send-form').show();
        $('#premium-confirm-placeholder').hide();
    })

    //Interview Schedulling
    $(document).on('click', '.interview_scheduling', function () {
        const data = dt_table.row(this.closest('tr')).data();

        $.ajax({
            url: "/ajax/interview/schedule/getschedulegroup/"+data.job_application_id,
            type: 'POST',
        })
            .done(response => {
                if(response.length == 0){
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
                            $.ajax({
                                url: "/ajax/interview/schedule/polling",
                                type: 'POST',
                                data: {
                                    'organizer_id': data.partner_id,
                                    'status': 1,
                                    'start_on' : Date.now(),
                                },
                            })
                                .done(response => {
                                    $('#interview_scheduling_modal #schedule_id').html('<option>Select Schedule</option>');
                                    $('#interview_scheduling_modal #job_application_id').val(data.job_application_id);
                                    $.each(response.data, function (index, item) {
                                        $('#schedule_id').append('<option value="'+item.schedule_id+'">Interview Schedule '+ item.start_on +' - ' + item.finish_on + '</option>');
                                    })
                                    $('#interview_scheduling_modal').modal('show');
                                })
                                .fail(response => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Connection to Server Failed!'
                                    });
                                })

                        }
                    });
                }
                else{
                    var html = '<table class="table mt-3">' +
                        '<tr><td>Start On</td><td>'+response.start_on+'</td></tr>' +
                        '<tr><td>Finish On</td><td>'+response.finish_on+'</td></tr>' +
                        '<tr><td>Interviewer</td><td>'+response.interviewer+'</td></tr>' +
                        '<tr><td>Location</td><td>'+response.countryCode_id + ' - ' + response.countrySubCode_id + ' <hr>'+ response.address+'</td></tr>' +
                        '<tr><td>Google Map</td><td>'+response.google_map+'</td></tr>' +
                        '</table>';
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

        $.ajax({
            url: "/ajax/interview/schedule/setschedulegroup",
            type: 'POST',
            data: {
                'job_application_id': $('#interview_scheduling_modal #job_application_id').val(),
                'schedule_id': $('#interview_scheduling_modal #schedule_id').val(),
            },
        })
            .done(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Information',
                    text: response.message
                })
                dt_table.ajax.reload(false);
                $('#interview_scheduling_modal').modal('hide');
            })
            .fail(response => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Connection to Server Failed!'
                });
            })

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
                $.ajax({
                    url: "/ajax/interview/schedule/setschedulegroup",
                    type: 'POST',
                    data: {
                        'job_application_id': $('#interview_scheduling_modal #job_application_id').val(),
                        'schedule_id': $('#interview_scheduling_modal #schedule_id').val(),
                    },
                })
                    .done(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Information',
                            text: response.message
                        })
                        dt_table.ajax.reload(false);
                        $('#interview_scheduling_modal').modal('hide');
                    })
                    .fail(response => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Connection to Server Failed!'
                        });
                    })

            }
        });
    });


    $('#offer_premium_service_btn').click(function (e) {
        e.preventDefault();

        const this_btn = $(this);
        const by_pass = document.querySelector('#by_pass');

        let title = 'Send verification email?';
        let text = 'This will send premium service offer to candidate';
        let buttonText = 'Yes, Send it!';

        if (by_pass && by_pass.checked) {
            title = 'Complete PSF Process?';
            text = 'You\'re by passing psf process, it will complete psf process without Candidate response make sure all data are correct';
            buttonText = 'Yes, Complete PSF';
        }

        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: buttonText
        }).then(function(result) {
            if (!result.value) {
                return;
            }

            this_btn.prop('disabled', true);
            showLoadingModal();

            $.ajax({
                url: "/ajax/job/premium/send_email",
                type: 'POST',
                data: $('#premium_service').serialize(),
                cache: false,
                timeout: 10000
            }).done(response => {
                    hideLoadingModal();
                    Swal.fire({
                        icon: 'success',
                        title: ($('#by_pass')[0].checked) ? 'Completed!':'Premium Service Email Sent!',
                        text: response.message
                    });
                    $('#premium_service')[0].reset();
                    $('#show_premium_service_modal').modal('hide');
                    $('#by_pass')[0].checked = false;
                    $('#user_acceptance').val('accept');
                    $('#user_acceptance').trigger('change');
                    $('.acceptance_form').addClass('d-none');
                    this_btn.text('Send Verification Email');
                    dt_table.ajax.reload(false);
                })
                .fail(response => {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Connection to Server Failed!'
                    });
                }).always(function () {
                this_btn.prop('disabled', false);
            });

        });
        
    });
    //--Premium Service

    //confirm premium service
    $('#premium_service_confirm_btn').on('click', function () {
        Swal.fire({
            title: 'Confirm premium service?',
            text: "This will update premium service status to confirmed and send confirmation email to candidate. You will not able to revert again.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Confirm it!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    url: '/ajax/job/premium/confirm',
                    'type' : 'POST',
                    'data': {
                          'address_book_id' : $('#address_book_id').val(),
                    },
                    success: function (rs) {
                        hideLoadingModal();
                        Swal.fire({
                            icon: 'success',
                            title: 'Information',
                            text: rs.message
                        })
                        dt_table.ajax.reload(false);
                        $('#show_premium_service_modal').modal('hide');
                    },
                    error: function (rs) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: 'There was an error.'
                        })
                    }
                })
            }
        })
    });

    //post off the leaf for data
    $(document).on('click', '.pre-interview-checklist', function (e) {
        e.preventDefault();

        const id = $(this).data('id');
        const ab_id = $(this).data('ab-id');
        const status = $(this).data('status');
        $('#accept_btn').show();
        $('#show-cv').prop('disabled', true);
        $('#loading_modal_preInterview').show();
        $('#content_preInterview').hide();
        $('#pre-interview-checklist').modal('show');
        $.post('/ajax/recruitment/applicant/pre-interview-checklist/' + id)
            .done(rs => {
                if (rs.passport) {
                    $('#passport_exist').show();
                    $('#passport_not_exist').hide();

                    //Passport
                    if (rs.passport_document) {
                        $('#passport_document').html(`<a class="btn btn-info btn-sm float-left" target="_blank" href="/personal/home/${rs.applicant.address_book_id}/documents/passp"><i class="fa fa-eye"></i> See Passport</a>`);
                    } else {
                        $('#passport_document').html(`<p class="text-warning text-center">Warning, please upload passport document file</p>`);
                    }
                    $('#passport_name').html(rs.passport_name || '<p class="text-warning text-center">Warning, Passport name not set</p>')
                    $('#passport_valid').html
                    (
                        (rs.passport_valid_date)
                            ? rs.passport_valid_date + ' ' + (rs.passport_valid ? ' <label class="badge badge-success p-1">( Valid )</label>' : '<label class="badge badge-danger p-1">( Not Valid, < 13 Month)</label>')
                            : ' <p class="text-warning text-center">Warning, Passport valid date not set</p>'
                    )

                    $('#applied_job').html(rs.applied_job || '<p class="text-warning text-center">Warning, Applied job not set</p>')

                    $('#skype').html(rs.skype || '<p class="text-warning">Warning, Skype ID not set</p>')
                    $('#email').html(rs.email || '<p class="text-warning">Warning, Email not set</p>')

                    //Ctrack
                    $('#send_ctrack_on').html(rs.send_ctrack_on || '<p class="text-warning ">Warning, Send Online Application not set</p>');
                    $('#ctrack_accessed_on').html(rs.ctrack_accessed_on || '<p class="text-warning ">Warning, Online Application accessed on not set</p>');
                    $('#ctrack_completed_on').html(rs.ctrack_completed_on || '<p class="text-warning ">Warning, Online Application completed on not set</p>');

                    //Personal Checklist
                    $('#checklist').html('');
                    if (Object.keys(rs.checklists).length > 0) {
                        let html = '<table class="table ">' +
                            '<thead class="grey lighten-2"><tr><th>Type</th> <th>Date</th> <th>Status</th></tr></thead>';
                        $.each(rs.checklists, (index, item) => {
                            html += `<tr><td>${index}</td><td>${item.date}</td> <td>${item.result}</td></tr>`;
                        })
                        $('#checklist').html(html)
                    } else {
                        $('#checklist').html('<p class="text-warning text-center">Warning, please complete the checklist.</p>');
                    }
                    //CV
                    if(rs.link_cv!='') {
                        $('#show-cv').prop('disabled', false);
                        $('#show-cv').data('id', rs.applicant.address_book_id);
                        $('#show-cv').data('link', rs.link_cv);
                    }

                    $('#reference').html('');
                    //Personal Reference
                    if (Object.keys(rs.personal_reference).length > 0 || Object.keys(rs.work_reference).length > 0)
                        $('#reference').html(
                            `<thead class="grey lighten-2">
                                <tr>
                                    <td class="w-25">Type</td>
                                    <td class="w-25">Contact Method</td>
                                    <td class="w-25">Status</td>
                                    <td class="w-25">Detail</td>
                                </tr>
                            </thead>`);

                    if (rs.personal_reference && rs.personal_reference.reference_id !== undefined) {
                        $('#reference').append(
                            `<tr>
                                <td>Personal Reference</td>
                                <td>${rs.personal_reference.contact_method}</td>
                                <td><span class="${rs.personal_reference.status == 'confirmed' ? 'text-success' : 'text-warning'}">${rs.personal_reference.status}</span></td>
                                <td><a class="btn btn-sm" target="_blank" href="${rs.personal_reference.link}">View</a></td>
                            </tr>`)
                    } else {
                        $('#reference').append('<tr><td colspan="4" class="text-warning text-center">Warning, please complete the personal reference check.</td></tr>')
                    }

                    //professional Reference
                    if (rs.work_reference && rs.work_reference.reference_id !== undefined) {
                        $('#reference').append(
                            `<tr>
                                <td>Professional Reference</td>
                                <td>${rs.work_reference.contact_method}</td>
                                <td><span class="${rs.work_reference.status == 'confirmed' ? 'text-success' : 'text-warning'}">${rs.work_reference.status}</span></td>
                                <td><a class="btn btn-sm" target="_blank" href="${rs.work_reference.link}">View</a></td>
                            </tr>`)
                    } else {
                        $('#reference').append('<tr><td colspan="4" class="text-warning text-center">Warning, please complete the professional reference check.</td></tr>')
                    }
                } else {
                    $('#passport_exist').hide();
                    $('#passport_not_exist').show();
                }

                //Full Body and Avatar Photos
                let photo_html =
                    `<td colspan="2">
                    Candidate Photos:<br/>
                    <div class="row text-center mt-2">`;
                //avatar photo
                if (rs.avatar) {
                    photo_html +=
                        `
                        <a class="col-6" href="/ab/show/${rs.avatar}" target="_blank" data-toggle="lightbox" data-gallery="${rs.avatar}" data-footer="Photo" data-type="image">
                            <figure class="figure">
                                <img src="/ab/show/${rs.avatar}"  class="img-fluid" alt="" title="Photo">
                                <figcaption class="figure-caption">Profile Photo</figcaption>
                            </figure>
                        </a>
                        `;
                } else {
                    photo_html += '<p class="col-6 text-warning text-center">Warning, photo file found.</p>';
                }

                //full body photo
                if (rs.full_body_photo) {
                    photo_html +=
                        `<a class="col-6" href="/ab/show/${rs.full_body_photo}" target="_blank" data-toggle="lightbox" data-gallery="${rs.full_body_photo}" data-footer="Full Body Photo" data-type="image">
                            <figure class="figure">
                                <img src="/ab/show/${rs.full_body_photo}-thumb"  class="img-fluid z-depth-1" alt="" title="Full Body Photo"><br/>
                                <figcaption class="figure-caption">Full body photo</figcaption>
                            </figure>
                        </a>`;
                } else {
                    photo_html += '<p class="col-6 text-warning text-center">Warning, full body photo file not found.</p>';
                }
                photo_html +=
                    `</div>
                </td>`;

                $('#full_body_photo').html(photo_html);

                $('#accept_btn').data('id', rs.applicant.job_application_id)
                $('#cancel_btn').data('id', rs.applicant.job_application_id)

                //English Test
                $('#english_test').html('');
                if (Object.keys(rs.english_test).length > 0) {
                    let html =
                        `<thead class="grey lighten-2">
                            <tr>
                                <td class="w-25">Type</td>
                                <td class="w-25">Score</td>
                                <td class="w-25">Date</td>
                                <td class="w-20">Status</td>
                                <td class="w-25">Detail</td>
                            </tr>
                        </thead>`;

                    $.each(rs.english_test, (index, item) => {
                        html += `<tr >
                                    <td>${item.type}</td>
                                    <td >${item.overall}</td>
                                    <td >${item.when}</td>
                                    <td>${item.status}</td>
                                    <td><a class="btn btn-sm" target="_blank" href="/personal/home/${rs.applicant.address_book_id}/documents/english">View</a></td>
                                </tr>`;
                    })
                    $('#english_test').html(html);
                } else {
                    $('#english_test').append('<tr><td colspan="4" class="text-warning text-center">Warning, please complete the english test.</td></tr>')
                }

                //STCW Document
                $('#stcw_document').html('');
                if (Object.keys(rs.stcw).length > 0) {
                    let html =
                        `<thead class="grey lighten-2">
                            <tr>
                                <td class="w-25">Qualification</td>
                                <td class="w-25">Institution</td>
                                <td class="w-25">Cert. Date</td>
                                <td class="w-25">Detail</td>
                            </tr>
                        </thead>`;
                    $.each(rs.stcw, (index, item) => {
                        html +=
                            `<tr>
                                <td>${item.qualification}</td>
                                <td>${item.institution}</td>
                                <td class="text-right">${item.certificate_date}</td>
                                <td><a class="btn btn-sm" target="_blank" href="/personal/home/${rs.applicant.address_book_id}/edu">View</a></td>
                            </tr>`;
                    })

                    $('#stcw_document').html(html);
                } else {
                    $('#stcw_document').append('<tr><td colspan="4" class="text-warning text-center">Warning, please upload STCW document if applicable.</td></tr>')
                }

                $('#tr_premium_status').html('');

                //Premium Service
                // console.log('hasil : '+Object.entries(rs.premium).length); Object.entries(rs.premium).length > 0
                if (Object.entries(rs.premium).length > 0) {
                    const filename = rs.premium.filename;
                    let html = '';
                    let alert_ps = 'warning';
                    let status_ps = '<span class="font-weight-bold"> Sending </span>';
                    if (rs.premium.verified == 'sending') {
                        alert_ps = 'warning';
                        status_ps = `<span class="font-weight-bold"> Sending </span>`;
                    }
                    else if (rs.premium.verified == 'accepted') {
                        alert_ps = 'success';
                        status_ps = `<span class="font-weight-bold"> Accepted </span>`;
                    }
                    else if (rs.premium.verified == 'rejected') {
                        alert_ps = 'danger';
                        status_ps = `<span class="font-weight-bold"> Rejected </span>`;
                    }
                    html += `<div class="alert alert-`+alert_ps+`" role="alert">
                                Speedy Global Premium Service Agreement : `+status_ps+`
                            </div>`;
                    $('#tr_premium_status').html(html);
                } else {
                    $('#tr_premium_status').html(`
                    <div class="alert alert-warning" role="alert">
                        Warning, no Premium Service data found.
                    </div>
                    `);
                }
                $('#validator_div').hide();
                $('#validator').html('');
                if (rs.errors && Object.keys(rs.errors).length > 0) {
                    //disable accept button
                    //$('#accept_btn').prop('disabled', true);
                    $('#accept_btn').hide();

                    let html =`<ul>`;

                    $.each(rs.errors, (index, item) => {
                        // html += `<tr >
                        //             <td>${item}</td>
                        //         </tr>`;
                        html +=`
                        <li><i class="fas fa-caret-right"></i> ${item}</li>
                        `;
                    })
                    html += `</ul>`;
                    $('#validator').html(html);
                    $('#validator_div').show();
                } else {


                    if (status == 'accepted') {
                        $('#accept_btn').hide();
                    }else{
                        $('#accept_btn').show();
                    }
                }

                $('#loading_modal_preInterview').hide();
                $('#content_preInterview').show();
            })
    });
    $(document).on('click', '#show-cv', function () {
        window.open($(this).data('link'));
    });

    $(document).on('click', '#show-cv-old', function () {
        const table = $('#table_cv');
        table.html('');
        $.get('/ajax/personal/main/getCurriculumVitae/' + $(this).data('id'))
            .done(function (response) {
                if (response) {
                    let html = ''

                    html += `
					<div class="row">
						<div class="col-12 text-capitalize">
							<div class="row">
								<div class="col-12 font-weight-bold mt-3 mb-1"> Personal Data </div>

								<div class="col-3">Name</div>
								<div class="col-9">: 
									${(response.name) ? response.name : 'Not Set'}
								</div>

								<div class="col-3">Date Of Birth</div>
								<div class="col-9">: 
									${(response.dob) ? response.dob : 'Not Set'}
								</div>

								<div class="col-3">Address</div>
								<div class="col-9">: 
									${(response.address) ? response.address : 'Not Set'}
								</div>

								<div class="col-3">Nationality</div>
								<div class="col-9">: 
									${(response.country) ? response.country : 'Not Set'}
								</div>

								<div class="col-3">Sex</div>
								<div class="col-9">: 
									${(response.sex) ? response.sex : 'Not Set'}
								</div>

								<div class="col-3">Height/Weight</div>
								<div class="col-9">: 
									${(response.hw) ? response.hw : 'Not Set'}
								</div>

								<div class="col-3">Phone Number</div>
								<div class="col-9">: 
									${(response.number) ? response.number : 'Not Set'}
								</div>

								<div class="col-3">Email</div>
								<div class="col-9">: 
									${(response.main_email) ? response.main_email : 'Not Set'}
								</div>

							</div>
						</div>
					`;

                    html += `
						<div class="col-12 ">
							<div class="row">`
                    //if there is education data
                    if (response.education_count > 0) {
                        html += `<div class="col-12 font-weight-bold mt-3 mb-1"> Education Background </div>`;
                        $.each(response.education_list, function (i, education) {
                            html +=
                                `<div class="col-3 text-capitalize"> ${education.level} </div>
								<div class="col-9">: ${education.from_date} - ${education.to_date}  &nbsp ${education.institution}</div>`;
                        });
                    }
                    html += `</div>
						</div>`;
                    //if there is employment data
                    if (response.employment_count > 0) {
                        html += `<div class="col-12 font-weight-bold mt-3 mb-1 ">Work Experience</div>`;
                        $.each(response.employment_list, function (i, employment) {
                            html +=
                                `<div class="col-12">
								- &nbspI have been working at ${employment.employer}, as a ${employment.job_title} from ${employment.from_date} until ${employment.to_date}
								</div>`;
                        });
                    }

                    table.html(html);
                    $('#show_cv_modal').modal('show');

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'There was a connection error.  The internet may be down or there might be an issue with the server.'
                    })
                }
            }).fail(function () {
            Swal.fire({
                icon: 'error',
                title: 'Connection Failed',
                text: 'There was a connection error.  The internet may be down or there might be an issue with the server.'

            });
        });
    });

    $(document).on('hidden.bs.modal', function (event) {
        if ($('.modal:visible').length) {
            $('body').addClass('modal-open');
        }
    });

    $(document).on('click', '#accept_btn', function () {
        Swal.fire({
            title: 'Accept the Applicant?',
            text: "Please make sure all the pre interview requirement is complete and correct. Once accepted, the candidate can take pre screen interview test.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Accept it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/ajax/recruitment/applicant/accept/' + $(this).data('id'),
                    success: function (rs) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Information',
                            text: rs.message
                        })
                        dt_table.ajax.reload(false);
                        $('#pre-interview-checklist').modal('hide');
                    },
                    error: function (rs) {

                    }
                })
            }
        })
    })

    $(document).on('click', '#cancel_btn', function () {
        Swal.fire({
            title: 'Cancel the Applicant?',
            text: "Once the application is canceled, they will rejected.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Cancel it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/ajax/recruitment/applicant/cancel/' + $(this).data('id'),
                    success: function (rs) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Information',
                            text: rs.message
                        })
                        dt_table.ajax.reload(false);
                        $('#pre-interview-checklist').modal('hide');
                    },
                    error: function (rs) {

                    }
                })
            }
        })
    })

    $('.datepicker').pickadate({
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        selectMonths: true,
        selectYears: true,
        format: 'yyyy-mm-dd',
    })

    var job_id;
    $(document).on('click', '.ctrac-modal', function (e) {
        e.preventDefault();
        job_id = $(this).data('id')
        $.ajax({
            url: "/ajax/recruitment/applicant/getctrac/" + job_id,
            type: 'POST',
        })
            .done(function (response) {
                $('#ctrac_form input[name=send_ctrac_on]').val(response.send_ctrac_on)
                $('#ctrac_form input[name=ctrac_accessed_on]').val(response.ctrac_accessed_on)
                $('#ctrac_form input[name=ctrac_completed_on]').val(response.ctrac_completed_on)
            })
            .fail(function () {
                Swal.fire({
                    icon: 'error',
                    text: "Error something wrong",
                });
            });
        $('#ctrac_modal').modal('show')
    })

    $('#ctrac_form').on('submit', function (e) {
        e.preventDefault();

        var self = $(this);
        
        var send_ctrac_on = self.find('input[name=send_ctrac_on]').val();
        var ctrac_accessed_on = self.find('input[name=ctrac_accessed_on]').val();
        var ctrac_completed_on = self.find('input[name=ctrac_completed_on]').val();
        
        if (send_ctrac_on === null || send_ctrac_on === '') {
            Swal.fire('Warning', 'Send ctrac on field still empty', 'warning');
            return;
        }
        
        if (ctrac_accessed_on === null || ctrac_accessed_on === '') {
            Swal.fire('Warning', 'Online Application accessed on field still empty', 'warning');
            return;
        }
        
        if (ctrac_completed_on === null || ctrac_completed_on === '') {
            Swal.fire('Warning', 'Online Application completed on field still empty', 'warning');
            return;
        }
        
        showLoadingModal();
        
        $.ajax({
            url: "/ajax/recruitment/applicant/ctrac/" + job_id,
            type: 'POST',
            data: $(this).serialize(),
        })
            .done(function (response) {
                hideLoadingModal();
                Swal.fire({
                    icon: 'success',
                    title: 'Notification..',
                    text: response.message
                });

                dt_table.ajax.reload(false);
                $('#ctrac_modal').modal('hide')
            })
            .fail(function () {
                Swal.fire({
                    icon: 'error',
                    text: "Error something wrong",
                });
            });
        return false;
    });

    $('#by_pass').on('change', function(e) {
        if (e.target.checked) {
            $('.acceptance_form').removeClass('d-none');
            $('#offer_premium_service_btn').text('Complete PSF Process');
        } else {
            $('#offer_premium_service_btn').text('Send Verification Email');
            $('.acceptance_form').addClass('d-none');
        }
    });

});
