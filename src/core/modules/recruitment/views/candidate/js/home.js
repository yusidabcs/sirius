$(document).ready(function () {
    $('#search_country').materialSelect();
    $('#search_status').materialSelect();
    $('#table_partner_search').materialSelect();
    $('#table_status_search').materialSelect();
    $('#table_country_search').materialSelect();
    $('#table_register_method').materialSelect();
    $('#table_job_category_search').materialSelect();
    var selected_address_book_id, selected_partner_id;

    const dt_table = $('#recruitments').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/recruitment/recruitment",
            "type": "POST",
            data: function (d) {
                d.partner_id = $('#table_partner_search').val()
                d.status = $('#table_status_search').val()
                d.country = $('#table_country_search').val()
                d.register_method = $('#table_register_method').val()
                d.job_category = $('#table_job_category_search').val()
            }
        },
        "columns": [
            {"data": "fullname"},
            {"data": "main_email"},
            {"data": "country", "searchable": false},
            {"data": "partner_name", "searchable": false},
            {"data": "status", "searchable": false},
            {"data": "created_on", "searchable": false},
            {"data": null, "searchable": false, "sortable": false},
        ],
        "order": [[ 5, "DESC" ]],
        "columnDefs": [
            {
                "render": function(data, type, row) {
                    var flag = '';

                    if (row.created_by == 0) {
                        flag = '<span class="badge badge-success">Public</span>';
                    } else {
                        flag = '<span class="badge badge-warning">Admin</span>';
                    }

                    return row.fullname + '<br>' + flag;
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    // if ((data == null) || (data == ''))
                    //     data = 'No partner';
                    if (((row.partner_name == null) || (row.partner_name == ''))&&((row.partner_lep_name == null) || (row.partner_lep_name == ''))) {
                        data = '<span class="text-warning">No partner</span>';
                    } else {
                        let html='';
                        if ((row.partner_name != null) && (row.partner_name != '')){
                            html += row.partner_name+' <span class="badge badge-primary"> LP</span>';
                        }
                        if ((row.partner_lep_name != null) && (row.partner_lep_name != '')){
                            if(html!='') {
                                html +='<br>';
                            }
                            html += row.partner_lep_name+' <span class="badge badge-primary"> LEP</span>';
                        }
                        data=html;
                    }
                    return data;
                },
                "targets": 3
            },
            {
                "render": function (data, type, row) {
                    if ((data == null) || (data == ''))
                        data = '<span class="badge badge-light">Unverified</span>';
                    if(data == 'unverified')
                        return '<span class="badge badge-light">Unverified</span>';
                    else if(data == 'process')
                        return '<span class="badge badge-default">Process</span>';
                    else if(data == 'request')
                        return '<span class="badge badge-info">Request</span>';
                    else if(data == 'verified')
                        return '<span class="badge badge-success">Verified</span>';
                    else if(data == 'rejected')
                        return '<span class="badge badge-dark">Rejected</span>';
                    return data;
                },
                "targets": 4
            },
            {
                "render": function (data, type, row) {
                    const hide = dt_table.ajax.json().hide;
                    
                    let html = '<div class=" d-flex justify-content-around">'; 
                    if (row['status'] == 'verified')
                    {
                        //html += '<a data-id="' + row['address_book_id'] + '" class="p-1 white border show_premium_service"><i class="fas ';
                        //html += (row['premium_status'] == 'accepted')? 'fa-dollar-sign text-primary': 'fa-dollar-sign text-warning';
                        //html += '" title="Premium Service"></i></a>';
                    }
                    html += '<a href="/personal/home/' + row['address_book_id'] + '" target="_blank" class="p-1 white border"><i class="far fa-user text-success" title="Show Personal"></i></a>';
                    html += '<button type="button" data-id="' + row['address_book_id'] + '" class="p-1 white border show_summary"><i class="fas fa-search-plus text-info" title="Show Summary"></i></button>';
                    if (hide != 'hide')
                        html += '<button data-toggle="modal" class="change_local_partner p-1 white border " data-id="' + row.address_book_id + '" data-partner-id="' + row.partner_id + '" data-lep-id="' + row.partner_lep_id + '"><i class="fas fa-handshake text-warning" title="Edit Partner"></i></button>';
                    html += '<button type="button" data-id="' + row['address_book_id'] + '" class="p-1 white border edit_verification"><i class="fas fa-edit text-warning" title="Edit Verification"></i></button>';
                    html += '<button type="button" data-id="' + row['address_book_id'] + '" class="p-1 white border show_history"><i class="fas fa-history text-info" title="Show History"></i></button></div>';

                    return html;
                },
                "targets": -1
            }
        ],
    });

    $('#table_partner_search,#table_status_search, #table_country_search, #table_register_method, #table_job_category_search').on('change', function () {
        dt_table.ajax.reload()
    });


    //post off the leaf for data
    $(document).on('click', '.show_summary', function () {
        const id = $(this).data('id');

        $.post('/ajax/recruitment/summary/' + id)
            .done(rs => {
                $('#summary_modal').modal('show')
                if (Object.keys(rs).length > 0) {
                    $('#summary').html('');

                    $.each(rs, function (key, val) {
                        if (key == 'created_on' || key == 'modified_on') {
                            return;
                        }
                        $('#summary').append(`
							<div class="col-sm-6 col-md-4 col-lg-3 mb-3">
								<div class="card p-3 text-center">
									<i class="fas ${val.icon} fa-3x ${(val.value > 0 ? 'text-info' : 'text-warning')} "></i>
									<p class="mb-0">
										${key} <br>
										<span class="h4">${val.value}</span>
									</p>
								</div>
							</div>`);
                    });

                } else {
                    $('#summary').html('Personal data not inputted yet!');
                }

            })
    });

    $(document).on('click', '.edit_verification', function () {
        //show edit modal
        const data = dt_table.row(this.closest('tr')).data();
        if(data.partner_id == null){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select the LP for this candidate!'
            });
            return;
        }
        $.get('/ajax/recruitment/verificationinfo/'+data.address_book_id, function(response) {
            $('#verification_info').val(response.verification_info).change();
        });

        $('#edit_verification_modal').modal('show');
        $('#verification_status').val(data.status);
        
        $('#edit_id').val(data.address_book_id);
    });

    $(document).on('click', '.show_history', function () {
        //load ajax history data 
        var btn = $(this);
        btn.attr('disabled', true);
        $.ajax({
            url: "/ajax/recruitment/status/getVerificationHistory",
            type: 'POST',
            data: {
                dt_id: $(this).data('id')
            },
            cache: false,
            timeout: 10000
        })
            .done(response => {
                $('#history_verification_modal').modal('show');
                let str = '';
                if (response.length) {
                    str = 
                    `<ul class="list-group text-capitalize">
                        <li class="list-group-item">
                            <span class="row">
                                <span class="col-md-3"><b>Timestamp</b></span>
                                <span class="col-md-3"><b>Status</b></span>
                                <span class="col-md-3"><b>Information</b></span>
                                <span class="col-md-3"><b>By</b></span>
                            </span>
                        </li>`;
                    var reject_count = 0;

                    $.each(response, function (key, data) 
                    {
                        if (data.status == 'rejected') {
                            reject_count++;
                        }
                        str += 
                        `<li class="list-group-item">
                            <span class="row">
                                <span class="col-md-3">${data.created_on}</span>
                                <span class="col-md-3">${data.status}</span>
                                <span class="col-md-3">${data.verification_info}</span>
                                <span class="col-md-3">${(data.given_name || '') + ' ' + (data.family_name || '')}</span>
                            </span>
                        </li>`;
                    })

                    str += '</ul>';

                    if (reject_count >= 3) {
                        str = '<p class="alert alert-danger">This user has been rejected ' + reject_count + ' times</p>' + str;
                    }

                } else {
                    str = 'No verification History';
                }
                $('#history_verification_modal .modal-body').html(str);
                btn.attr('disabled', false);
            })
            .fail(response => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Connection to Server Failed!'
                });
                btn.attr('disabled', false);
            });

    });

    //Local partner modal
    $(document).on('click', '.change_local_partner', function () {
        selected_address_book_id = $(this).data('id');
        selected_partner_id = $(this).data('partner-id');
        selected_lep_id = $(this).data('lep-id');
        var btn = $(this);
        btn.attr('disabled', true);
        //get all local partner AJAX
        var partner_xhr = $.ajax({
            url: "/ajax/recruitment/partner",
            type: 'POST',
            data: {
                address_book_id: selected_address_book_id,
                action: 'get'
            },
            cache: false,
            timeout: 10000
        })
            .done(function (answer) {

                if (answer.good) {
                    $('#partner_new').empty();
                    $('#partner_lep').empty();
                    let html = '<option value="">Select Partner</option>';
                    $.each(answer.reply.data_lp, function (key, data) {
                        if (data.id == selected_partner_id) {
                            html += '<option value="' + data.id + '" selected>' + data.name + '</option>';
                        } else {
                            html += '<option value="' + data.id + '">' + data.name + '</option>';
                        }
                    })
                    $('#partner_new').append(html);
                    $('#partner_new').materialSelect();

                    html = '<option value="">Select Partner</option>';
                    $.each(answer.reply.data_lep, function (key, data) {
                        if (data.id == selected_lep_id) {
                            html += '<option value="' + data.id + '" selected>' + data.name + '</option>';
                        } else {
                            html += '<option value="' + data.id + '">' + data.name + '</option>';
                        }
                    })
                    $('#partner_lep').append(html);
                    $('#partner_lep').materialSelect();
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: answer.note,
                    });
                }

                $('#partner_modal').modal('show');
                btn.attr('disabled', false);
            })
            .fail(function () {
                Swal.fire({
                    icon: 'error',
                    text: "Error could not fetch list partner.",
                });
                btn.attr('disabled', false);
            });
    })

    //send email reminder to All user
    $('#send_email_reminder').on('click', function () {
        swal.fire({
            title: "Are You Sure?",
            text: "This action will send email to all candidate. ",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`/ajax/recruitment/applicant/send_reminder_all_user`)
                .then(response => {
                    if (!response.status) {
                        Swal.showValidationMessage(`Error could not send email.`)
                    }
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(
                    `Request failed: ${error}`
                    )
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    icon: 'success',
                    text: result.value.status,
                })
            }
        })
        
    })
    //delete partner
    $('.partner_delete').click(function (e) {
        //check current partner for this user
        const type = $(this).data('type');
        //const new_partner_id = type=='lp'?$("#partner_new").val():$("#partner_lep").val();
        const current_partner = type=='lp'?selected_partner_id:selected_lep_id;
        let text = "";
        if(type=="lep") {
            text = "Education";
        }
        if(current_partner!=null && current_partner!='') {
            const modal = $("#partner_modal");
            swal.fire({
                title: "Are You Sure?",
                text: "This action will remove License "+text+" Partner from this user. ",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'No'
            }).then((result) => {

                if (result.value) {
                    $(`.partner_change[data-type='${type}']`).prop('disabled',true);
                    $(`.partner_delete[data-type='${type}']`).html('<i class="fas fa-spinner fa-spin"></i> DELETING...').prop('disabled',true);
                    //ok good to go
                    $.ajax({
                        url: "/ajax/recruitment/partner",
                        type: 'POST',
                        data: {
                            action: 'delete',
                            type:type,
                            address_book_id: selected_address_book_id,
                            current_partner_id: selected_partner_id
                        },
                        cache: false,
                        timeout: 10000
                    })
                        .done(function (answer) {
                            // const answer = jQuery.parseJSON(msg);
                            if (answer.good) {
                                //clear values
                                modal.modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    text: answer.message,
                                })
                                dt_table.ajax.reload(null,false);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    text: answer.note,
                                });
                            }
                            $(`.partner_change[data-type='${type}']`).prop('disabled',false);
                            $(`.partner_delete[data-type='${type}']`).html('DELETE PARTNER?').prop('disabled',false);
                        })
                        .fail(function () {
                            Swal.fire({
                                icon: 'error',
                                text: "Error could not update partner.",
                            });
                            $(`.partner_change[data-type='${type}']`).prop('disabled',false);
                            $(`.partner_delete[data-type='${type}']`).html('DELETE PARTNER?').prop('disabled',false);
                        });
                }
                ;
            });

            return;
        } else {
            Swal.fire({
                icon: 'error',
                text: "The User has no License "+text+" Partner!",
            });
        }
    });

    //partner edit
    $('.partner_change').click(function (e) {
        const type = $(this).data('type');
        const new_partner_id = type=='lp'?$("#partner_new").val():$("#partner_lep").val();
        const current_partner = type=='lp'?selected_partner_id:selected_lep_id;
        // const partnerNew = $("#partner_new");
        // const new_partner_id = partnerNew.val();
        const modal = $("#partner_modal");
        if (new_partner_id > 0) {
            if (new_partner_id != current_partner) {
                $(`.partner_change[data-type='${type}']`).html('<i class="fas fa-spinner fa-spin"></i> SAVING...').prop('disabled',true);
                $(`.partner_delete[data-type='${type}']`).prop('disabled',true);
                $.ajax({
                    url: "/ajax/recruitment/partner",
                    type: 'POST',
                    data: {
                        action: 'change',
                        type:type,
                        address_book_id: selected_address_book_id,
                        new_partner_id: new_partner_id,
                        current_partner_id:current_partner
                    },
                    cache: false,
                    timeout: 20000
                })
                    .done(function (answer) {
                        if (answer.good) {
                            //clear values
                            if(type=="lep") {
                                selected_lep_id = new_partner_id;
                            } else {
                                selected_partner_id = new_partner_id;
                            }
                            //modal.modal('toggle');
                            Swal.fire({
                                icon: 'success',
                                text: answer.message,
                            })
                            dt_table.ajax.reload(null,false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: answer.note,
                            });
                        }
                        $(`.partner_change[data-type='${type}']`).html('CHANGE PARTNER').prop('disabled',false);
                        $(`.partner_delete[data-type='${type}']`).prop('disabled',false);
                    })
                    .fail(function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            text: "Error could not update the partner.",
                        });
                        $(`.partner_change[data-type='${type}']`).html('CHANGE PARTNER').prop('disabled',false);
                        $(`.partner_delete[data-type='${type}']`).prop('disabled',false);
                    });
            } else {
                Swal.fire({
                    icon: 'warning',
                    text: 'Partner data unchanged',
                });
            }
        } else {
            Swal.fire({
                icon: 'warning',
                text: 'You must select a partner first.',
            });
        }
        return;

    });

    $('#edit_verification_btn').click(function () {
        var btn = $(this)
        Swal.fire({
            title: 'Are you sure?',
            text: "Please make sure to check the data before changing this verification status",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                btn.attr('disabled',true)
                $.ajax({
                    url: "/ajax/recruitment/status/changeVerification",
                    type: 'POST',
                    data: {
                        dt_id: $('#edit_id').val(),
                        dt_status: $('#verification_status').val(),
                        dt_verification_info: $('#verification_info').val()
                    },
                    cache: false,
                    timeout: 10000
                })
                    .done(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Verification status edited!',
                            text: response.message
                        });

                        $('#edit_verification_modal').modal('hide');
                        dt_table.ajax.reload(null, false);
                        btn.attr('disabled',false)
                    })
                    .fail(response => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Connection to Server Failed!'
                        });
                        btn.attr('disabled',false)
                    });
            }
        });
    });

    //Premium Service

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

    $('#offer_premium_service_btn').click(function () 
    {
        const this_btn = $(this)
        this_btn.prop('disabled', true);

        $.ajax({
            url: "/ajax/job/premium/send_email",
            type: 'POST',
            data: $('#premium_service').serialize(),
            cache: false,
            timeout: 10000
        })
            .done(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Premium Service Email Sent!',
                    text: response.message
                });
                $('#premium_service')[0].reset();
                $('#show_premium_service_modal').modal('hide');
                dt_table.ajax.reload(null, false);
            })
            .fail(response => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Connection to Server Failed!'
                });
            }).always(function()
            {
                this_btn.prop('disabled', false);
            });
    });

    //--Premium Service
    
    // export excel
    $('#export_candidate').on('click',function(){
        let country = $('#table_country_search').val();
        let status = $('#table_status_search').val();
        let partner = $('#table_partner_search').val();
        let job_category = $('#table_job_category_search').val();

        let mapForm = document.createElement("form");
        mapForm.target = "_blank";    
        mapForm.method = "POST";
        mapForm.action = "/ajax/recruitment/main/export";

        // Create an input
        let input_status = document.createElement("input");
        input_status.type = "text";
        input_status.name = "status";
        input_status.value = status;

        let input_country = document.createElement("input");
        input_country.type = "text";
        input_country.name = "country";
        input_country.value = country;

        let input_partner = document.createElement("input");
        input_partner.type = "text";
        input_partner.name = "partner";
        input_partner.value = partner;

        let input_job_category = document.createElement("input");
        input_job_category.type = "text";
        input_job_category.name = "job_category";
        input_job_category.value = job_category;
        // Add the input to the form
        mapForm.appendChild(input_status);
        mapForm.appendChild(input_country);
        mapForm.appendChild(input_partner);
        mapForm.appendChild(input_job_category);

        // Add the form to dom
        document.body.appendChild(mapForm);

        // Just submit
        mapForm.submit();

        document.body.removeChild(mapForm);
    })
    // end export excel

    $('#verification_status').on('change', function() {
        if ($(this).val() === 'verified') {
            $.ajax({
                url: '/ajax/recruitment/checkpersonalcomplete/' + $('#edit_id').val(),
                method: 'POST',
                success: function(response) {
                    if (!response.personal_complete) {
                        Swal.fire({
                            title: 'Warning',
                            text: response.message,
                            icon: 'warning'
                        });

                        $('#edit_verification_btn').attr('disabled', true);
                    }
                }
            });
        }

        $('#edit_verification_btn').removeAttr('disabled');
    });
});
