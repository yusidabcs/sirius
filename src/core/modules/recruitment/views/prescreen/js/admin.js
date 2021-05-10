$(document).ready(function () {
    $('#search_country').materialSelect();
    $('#search_status').materialSelect();
    $('#table_partner_search').materialSelect();
    $('#table_status_search').materialSelect();
    $('#table_country_search').materialSelect();
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

    const dt_prescreen_table = $('#prescreens').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/recruitment/prescreen",
            "type": "POST",
            data: function (d) {
                d.partner_id = $('#table_partner_search').val()
                d.status = $('#table_status_search').val()
                d.country = $('#table_country_search').val()
                d.register_method = $('#table_register_method').val()
            }
        },
        "columns": [
            {"data": null},
            {"data": "number_given_name"},
            {"data": "main_email"},
            {"data": "country", "searchable": false},
            {"data": "status", "searchable": false},
            {"data": "sending_on"},
            {"data": "accepted_on"},
            {"data": null, "searchable": false, "sortable": false},
        ],
        "columnDefs": [
            {
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                "targets": 0
            },
            {
                "render": function(data, _, row) {
                    var flag = '';
        
                    if (row.created_by == 0) {
                        flag = '<span class="badge badge-success">Registered From Public</span>';
                    } else {
                        flag = '<span class="badge badge-warning">Registered From Admin Inputed</span>';
                    }

                    return data + '<br>' + '( ' + row.partner_name + ' )<br>' + flag;
                },
                "targets": 1
            },
            {
                "render": function (data, type, row) {
                    var url = $('#prescreens').data('url');
                    let html = '<div class=" d-flex justify-content-around">';
                    html += '<a href="/personal/home/' + row['address_book_id'] + '" class="p-1 white border"><i class="far fa-user text-success" title="Show Personal"></i></a>';

                    if (row['status'] == 'pending' || row['status'] == 'revision') {
                        html += '<a href="' + url + '/prescreen_form/' + row['job_application_id'] + '" class="p-1 white border"><i class="far fa-comment text-info" title="Pre-screen form"></i></a>';
                    } else if (row['status'] == 'sending' || row['status'] == 'accepted') {
                        html += '<a href="#" class="p-1 white border btn-prescreen-preview" data-id="' + row.job_application_id + '"><i class="far fa-comment text-success" title="Pre-screen form"></i></a>';
                    }
                    html += '</div>';


                    return html;
                },
                "targets": -1
            },

            {
                "render": function (data, type, row) {
                    if ((data == null) || (data == ''))
                        data = 'No partner';
                    return data;
                },
                "targets": 5
            },
            {
                "render": function (data, type, row) {
                    if (data == 'pending')
                        return '<span class="badge badge-dark">Pending</span>';
                    else if (data == 'sending')
                        return '<span class="badge badge-info">Sending</span>';
                    else if (data == 'accepted')
                        return '<span class="badge badge-success">Accepted</span>';
                    else if (data == 'revision')
                        return '<span class="badge badge-warning">Revison</span>';
                    return data;
                },
                "targets": 4
            },
        ],
    });

    $('#table_partner_search,#table_status_search, #table_country_search, #table_register_method').on('change', function () {
        dt_prescreen_table.ajax.reload()
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
                if((response.job_prescreen.status == 'interview' || response.job_prescreen.status == 'sending' || response.applicant.job_status == 'interview')){
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
                            principal.val('');
                            dt_prescreen_table.ajax.reload()
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
    });

});