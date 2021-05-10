$(document).ready(function () {
    $('#search_country').materialSelect();
    $('#search_status').materialSelect();
    $('#table_partner_search').materialSelect();
    $('#table_country_search').materialSelect();
    $('#table_register_method').materialSelect();
    var selected_address_book_id, selected_partner_id;

    const dt_table = $('#recruitments').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/recruitment/applicant",
            "type": "POST",
            data: function (d) {
                d.partner_id = $('#table_partner_search').val()
                d.status = 'rejected,canceled'
                d.country = $('#table_country_search').val()
                d.register_method = $('#table_register_method').val()
            }
        },
        "columns": [
            {"data": null},
            {"data": "fullname"},
            {"data": "main_email"},
            {"data": "country", "searchable": false},
            {"data": "partner_name", "searchable": false},
            {"data": "status", "searchable": false},
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
                "render": function(data, type, row) {
                    var flag = '';
        
                    if (row.created_by == 0) {
                        flag = '<span class="badge badge-success">Registered From Public</span>';
                    } else {
                        flag = '<span class="badge badge-warning">Registered From Admin Inputed</span>';
                    }

                    return row.fullname + '<br>' + flag;
                },
                "targets": 1
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
                    const hide = dt_table.ajax.json().hide;
                    let html = '<div class=" d-flex justify-content-around"><a href="/personal/home/' + row['address_book_id'] + '" class="p-1 white border"><i class="far fa-user text-success" title="Show Personal"></i></a>';
                    html += '<button type="button" data-id="' + row['address_book_id'] + '" class="p-1 white border show_summary"><i class="fas fa-search-plus text-info" title="Show Summary"></i></button>';
                    if (hide != 'hide')
                        html += '<button data-toggle="modal" class="change_local_partner p-1 white border " data-id="' + row.address_book_id + '" data-partner-id="' + row.partner_id + '"><i class="fas fa-handshake text-warning" title="Edit Partner"></i></button>';
                    html += '<button type="button" data-id="' + row['address_book_id'] + '" class="p-1 white border edit_verification"><i class="fas fa-edit text-warning" title="Edit Verification"></i></button>';
                    html += '<button type="button" data-id="' + row['address_book_id'] + '" class="p-1 white border show_history"><i class="fas fa-history text-info" title="Show History"></i></button></div>';

                    return html;
                },
                "targets": -1
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
                "targets": -2
            },
        ],
    });

    $('#table_partner_search, #table_country_search, #table_register_method').on('change', function () {
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
        $('#edit_verification_modal').modal('show');
        $('#verification_status').val(data.status);
        $('#verification_info').val(data.info).change();
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
                    type: 'error',
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
                    let html = '<option value="">Select Partner</option>';
                    $.each(answer.reply, function (key, data) {
                        if (data.id == selected_partner_id) {
                            html += '<option value="' + data.id + '" selected>' + data.name + '</option>';
                        } else {
                            html += '<option value="' + data.id + '">' + data.name + '</option>';
                        }
                    })
                    $('#partner_new').append(html);
                    $('#partner_new').materialSelect();
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

    //delete partner
    $('.partner_delete').click(function (e) {
        const modal = $("#partner_modal");
        swal.fire({
            title: "Are You Sure?",
            text: "This action will remove local partner from this user. ",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'No'
        }).then((result) => {

            if (result.value) {
                //ok good to go
                $.ajax({
                    url: "/ajax/recruitment/partner",
                    type: 'POST',
                    data: {
                        action: 'delete',
                        address_book_id: selected_address_book_id
                    },
                    cache: false,
                    timeout: 10000
                })
                    .done(function (answer) {
                        // const answer = jQuery.parseJSON(msg);
                        if (answer.good) {
                            //clear values
                            modal.modal('toggle');
                            Swal.fire({
                                icon: 'success',
                                text: answer.message,
                            })
                            dt_table.ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: answer.note,
                            });
                        }
                    })
                    .fail(function () {
                        Swal.fire({
                            icon: 'error',
                            text: "Error could not update partner.",
                        });
                    });
            }
            ;
        });

        return;
    });

    //partner edit
    $('.partner_change').click(function (e) {
        const partnerNew = $("#partner_new");
        const new_partner_id = partnerNew.val();
        const modal = $("#partner_modal");
        if (new_partner_id > 0) {
            if (new_partner_id != selected_partner_id) {
                $.ajax({
                    url: "/ajax/recruitment/partner",
                    type: 'POST',
                    data: {
                        action: 'change',
                        address_book_id: selected_address_book_id,
                        new_partner_id: new_partner_id
                    },
                    cache: false,
                    timeout: 10000
                })
                    .done(function (answer) {
                        if (answer.good) {
                            //clear values
                            partnerNew.val('');
                            modal.modal('toggle');
                            Swal.fire({
                                icon: 'success',
                                text: answer.message,
                            })
                            dt_table.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: answer.note,
                            });
                        }
                    })
                    .fail(function () {
                        Swal.fire({
                            icon: 'error',
                            text: "Error could not update the partner.",
                        });
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
                            type: 'success',
                            title: 'Verification status edited!',
                            text: response.message
                        });

                        $('#edit_verification_modal').modal('hide');
                        dt_table.ajax.reload();
                    })
                    .fail(response => {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Connection to Server Failed!'
                        });
                    });
            }
        });
    });
});
