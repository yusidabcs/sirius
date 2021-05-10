$(document).ready(function () {

    function clearSelection() {
        $('.select2').val(null);
        $('.select2-selection__rendered').html('');
    }

    $('#request_offer_letter_on').pickadate({
        format: 'yyyy-mm-dd'
    });

    $('#candidate_accepted_on').pickadate({
        format: 'yyyy-mm-dd'
    });

    $('#loe_date').pickadate({
        format: 'yyyy-mm-dd'
    });

    $('#deploy_date').pickadate({
        format: 'yyyy-mm-dd'
    });

    $('#deploy_date').on('change', function() {
        $('#deploy_date_end').pickadate({
            format: 'yyyy-mm-dd',
            min: $(this).val()
        });
    });

    $('#table_status_search').materialSelect();

    $('.select2').select2({
        width: '100%',
        placeholder: 'Select items',
        multiple: true
    });

    $('#address_book_id, #principal_code, #level').materialSelect()

    const table = $('#list_interview_security_report').DataTable({
            "processing": true,
            "serverSide": true,
            'responsive': true,
            "ajax": {
                "url": "/ajax/offer_letter/trackers/list",
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
                {"data": 'allocated_on'},
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
                    "render": function (data, type, row) {
                        return data + '<br>' + row['main_email'];
                    },
                    "targets": 1
                },

                {
                    "render": function (data, type, row) {
                        return row['job_code'] + ' - ' + data + '<br>' + row.principal_code;
                    },
                    "targets": 2
                },
                {
                    "render": function (data, type, row) {
                        if (data == 'endorsement') {
                            html = `<span class="text-warning">${data}</span>` + `<br> Expected on: ` + row.endorsement_expected_on
                            html += row.endorsement_requested_on != '0000-00-00 00:00:00' ? `<br> Request on: ` + row.endorsement_requested_on : ''
                            return html;
                        }
                        if (data == 'offer_letter') {
                            html = `<span class="text-warning">${data}</span>`
                            html += row.request_offer_letter_on != '0000-00-00 00:00:00' ? `<br> Request on: ` + row.request_offer_letter_on : ''
                            return html;
                        }
                        if (data == 'candidate_acceptance')
                            return `<span class="text-warning">${data}</span> <br>` + ((row.candidate_accepted_on != '0000-00-00 00:00:00') ? `Accepted on: ${row.candidate_accepted_on}` : '')
                        if (data == 'personal_data')
                            return `<span class="text-warning">${data}</span>`
                        else if (data == 'accepted') {
                            return `<span class="text-success">${data}</span>`
                        }
                        else if (data == 'denied') {
                            return `<span class="text-danger">${data}</span>`
                        }
                        return data
                    },
                    "targets": 3
                },

                {
                    "render": function(data, type, row) {
                        var dt = new Date(row.allocated_on);

                        return dt.getDate() + '/' + (dt.getMonth() + 1) + ', ' + dt.getFullYear();
                    },
                    "targets": 4
                },

                {
                    "render": function (data, type, row) {
                        if (data == 'normal')
                            return `<span class="badge badge-success">Normal</span>`
                        if (data == 'soft')
                            return `<span class="badge badge-info">Soft Warning</span>`
                        if (data == 'hard')
                            return `<span class="badge badge-warning">Hard Warning</span>`
                        if (data == 'deadline')
                            return `<span class="badge badge-danger">Deadline</span>`
                    },
                    "targets": 5
                },


                {
                    "render": function (data, type, row) {
                        var html = '<div class="d-flex flex-column">'

                        if (row.status == 'endorsement') {

                            html += `<a class="btn-sm btn-info text-white btn-send-endorsement" href="#" title="">Send Endorsement</a>`;
                            if (row.endorsement_requested_on != '0000-00-00 00:00:00') {
                                html += `<a class="btn-sm btn-success text-white btn-upload-endorsement" href="#" title=""><i class="fa fa-file"></i> Endorsement File</a>`;
                            }
                        }

                        else if (row.status == 'offer_letter') {

                            html += `<a class="btn-sm btn-info text-white btn-request-ol" href="#" title=""><i class="fa fa-mail-bulk"></i> Request Offer Letter</a>`;
                            if (row.request_offer_letter_on != '0000-00-00 00:00:00') {
                                html += `<a class="btn-sm btn-success text-white btn-upload-ol" href="#" title=""><i class="fa fa-file-pdf"></i> Upload Offer Letter</a>`;
                            }
                        }

                        else if (row.status == 'candidate_acceptance') {

                            html += `<a class="btn-sm btn-info text-white btn-candidate-acceptance" href="#" title="Update Offer Acceptance">Accept Offer Letter</a>`;

                        }

                        else if (row.status == 'personal_data') {

                            html += `<a class="btn-sm btn-info text-white btn-personal-data" href="#" title="Update Personal Data"><i class="fa fa-user"></i> Update Personal data</a>`;

                        }else if (row.status == 'accepted') {
                            html += `<a class="btn-sm btn-success text-white btn-loe" href="#" title="Input LOE"><i class="fa fa-file-contract"></i> LOE</a>`;
                        }
                        html += '</div>';

                        return html;
                    },
                    "targets": -1
                }
            ],
            'order': [
                [1, 'asc']
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
        }
    );

    const loadingModal = function() {
        Swal.fire({
            title: 'Loading',
            text: 'Please wait...',
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

    $('#table_status_search').on('change', function () {
        table.ajax.reload();
    })

    //endorsement
    var job_application_id = []
    $(document).on('click', '.btn-send-endorsement', function () {
        var selected_candidate = table.rows({selected: true}).data();
        job_application_id = []
        $.each(selected_candidate, (i, item) => {
            if (item.status == 'endorsement') {
                job_application_id.push(item.job_application_id)
            }
        })
        if (job_application_id.length == 0) {
            alert('Please select candidate with status endorsement');
            return false;
        }
        $('#info_endorsement_ol').html('You select ' + job_application_id.length + ' candidate.')
        $('#request-endorsement-modal').modal('show');

        Swal.fire({
            title: 'Send Endorsement Request',
            text: "You will sent endorsement request.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                loadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/offer_letter/trackers/send_endorsement',
                    data: {
                        job_application_id: job_application_id
                    },
                    success: rs => {
                        hideLoadingModal();
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload();

                    },
                    error: function (response) {
                        hideLoadingModal();
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

        })
        return false;
    });

    $(document).on('click', '.btn-upload-endorsement', function () {

        var selected_candidate = table.rows({selected: true}).data();
        job_application_id = []
        $.each(selected_candidate, (i, item) => {
            if (item.status == 'endorsement') {
                job_application_id.push(item.job_application_id)
            }
        })
        if (job_application_id.length == 0) {
            alert('Please select candidate with status endorsement');
            return false;
        }

        $('#upload-endorsement-modal').modal('show')
        return false;
    });

    $('#upload-endorsement-form').on('submit', function (e) {
        e.preventDefault();
        var selected_candidate = table.rows({selected: true}).data();
        job_application_id = []
        $.each(selected_candidate, (i, item) => {
            if (item.status == 'endorsement') {
                job_application_id.push(item.job_application_id)
            }
        })
        if (job_application_id.length == 0) {
            alert('Please select candidate with status endorsement');
            return false;
        }
        var data = new FormData(this);
        data.append('job_application_id[]', job_application_id);

        var text = 'You will update ' + job_application_id.length + ' offer letter tracker with status ' + $(this).find('[name=status]:checked').val() + '. You can not update this again. So make sure this correct.'

        Swal.fire({
            title: 'Update Endorsement',
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {

            if (result.value) {

                $.ajax({
                    type: 'POST',
                    url: '/ajax/offer_letter/trackers/update_endorsement',
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload();
                        $('#upload-endorsement-modal').modal('hide')
                        $('#upload-endorsement-form').trigger('reset')
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
                });
            }

        })
    })

    //update request OL
    var job_application_id = []
    $(document).on('click', '.btn-request-ol', function () {
        var selected_candidate = table.rows({selected: true}).data();
        job_application_id = []
        $.each(selected_candidate, (i, item) => {
            if (item.status == 'offer_letter') {
                job_application_id.push(item.job_application_id)
            }
        })
        if (job_application_id.length == 0) {
            alert('Please select candidate');
            return false;
        }
        $('#info_request_ol').html('You select ' + job_application_id.length + ' candidate.')
        $('#request-ol-modal').modal('show');
        return false;
    })

    $('#request_ol_form').on('submit', function (e) {
        e.preventDefault();
        var btn = $(this).find('[type=submit]');
        Swal.fire({
            title: 'Set Request Offer Letter',
            text: "You will set the date for request offer letter.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value)
            btn.attr('disabled', true);
            loadingModal();
            $.ajax({
                url: '/ajax/offer_letter/trackers/request_offer_letter',
                data: {
                    job_application_id: job_application_id,
                    request_offer_letter_on: $('#request_offer_letter_on').val(),
                },
                type: 'POST',
                datatype: 'json',
                success: function (rs) {
                    hideLoadingModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Notification!',
                        text: rs.message
                    });
                    table.ajax.reload();
                    $('#request_ol_form').trigger("reset");
                    $('#request-ol-modal').modal('hide')
                    btn.attr('disabled', false);
                },
                error: function (response) {
                    hideLoadingModal();
                    btn.attr('disabled', false);
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
        })
    })

    $(document).on('click', '.btn-upload-ol', function () {
        var data = table.row(this.closest('tr')).data();
        $('#upload_ol_form').find('input[name=job_application_id]').val(data.job_application_id);
        $('#upload-ol-modal').modal('show');
        return false;
    });

    $('#upload_ol_form').submit(function (event) {
        var btn = $(this).find('[type=submit]');
        // mencegah browser mensubmit form.
        event.preventDefault();
        btn.attr('disabled', true);
        btn.html('Saving..');
        loadingModal();

        $.ajax({
            type: 'POST',
            "url": "/ajax/offer_letter/trackers/upload_offer_letter",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: rs => {
                hideLoadingModal();
                table.ajax.reload(false)
                Swal.fire({
                    icon: 'success',
                    title: 'Notification!',
                    text: rs.message
                });
                $(this).trigger('reset');
                $('#upload-ol-modal').modal('hide');
                btn.attr('disabled', false)
                btn.html('Save')
            },
            error: response => {
                hideLoadingModal();
                btn.attr('disabled', false);
                btn.html('Save');
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
    });

    $(document).on('click', '.btn-candidate-acceptance', function (e) {
        e.preventDefault();
        var data = table.row(this.closest('tr')).data();

        $('#ol-acceptance-form').find('input[name=job_application_id]').val(data.job_application_id);
        $('#ol-acceptance-modal').modal('show')
    })
    $('#ol-acceptance-form').on('submit', function (e) {
        e.preventDefault();
        var btn = $(this).find('[type=submit]');
        var acceptance_date = $(this).find('#candidate_accepted_on').val();
        var acceptance_status = $(this).find('input[name="status"]:checked').val();
        if (acceptance_status === 'personal_data') {
            acceptance_status = 'accepted';
        }
        var notes = $(this).find('#accp_notes').val();
        
        var html = `
            <p>You will set the date for offer letter acceptance and status.</p>
            <p>Please make sure data is correct<p>
            <table class='table table-bordered'>
                <tr>
                    <td>Offer Letter Acceptance Date</td>
                    <td>${acceptance_date}</td>
                </tr>
                <tr>
                    <td>Acceptance Status</td>
                    <td>${acceptance_status}</td>
                </tr>
                <tr>
                    <td>Notes</td>
                    <td>${notes}</td>
                </tr>
            </table>
        `;
        Swal.fire({
            title: 'Offer Letter Acceptance Date',
            html: html,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                btn.attr('disabled', true);
                btn.html('Saving..');
                $.ajax({
                    url: '/ajax/offer_letter/trackers/offer_letter_acceptance',
                    data: $(this).serialize(),
                    type: 'POST',
                    datatype: 'json',
                    success: function (rs) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload();
                        $('#ol-acceptance-form').trigger("reset");
                        $('#ol-acceptance-modal').modal('hide')
                        btn.attr('disabled', false);
                        btn.html('Save');
                    },
                    error: function (response) {

                        btn.attr('disabled', false);
                        btn.html('Save');
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
        })
    })


    //personal data
    $(document).on('click', '.btn-personal-data', function () {
        var data = table.row(this.closest('tr')).data();
        $('#personal-data-form').find('input[name=job_application_id]').val(data.job_application_id)
        $('#personal-data-modal').modal('show')
    })
    $('#personal-data-form').on('submit', function (e) {
        e.preventDefault();
        var btn = $(this).find('[type=submit]');
        Swal.fire({
            title: 'Upload Personal Data',
            text: "You will update candidate personal data form.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                btn.attr('disabled', true);
                btn.html('Saving..');

                $.ajax({
                    type: 'POST',
                    url: '/ajax/offer_letter/trackers/personal_data',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload();
                        $('#personal-data-form').trigger("reset");
                        $('#personal-data-modal').modal('hide');
                        btn.attr('disabled', false);
                        btn.html('Save');

                    },
                    error: function (response) {

                        btn.attr('disabled', false);
                        btn.html('Save');
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

        })
    })


    //loe
    $(document).on('click', '.btn-loe', function (e) {
        e.preventDefault();
        var data = table.row(this.closest('tr')).data();
        clearSelection();
        $('#loe-form').find('input[name=job_application_id]').val(data.job_application_id);
        $('#loe-form').find('input[name=job_demand_master_id]').val(data.job_demand_master_id);
        $('#loe-modal').modal('show');
    })
    $('#loe-form').on('submit', function (e) {
        e.preventDefault();
        var btn = $(this).find('[type=submit]');
        var visaTypes = $('#loe-form').find('#visa_types').val();
        var oktbTypes = $('#loe-form').find('#oktb_types').val();
        var medicalTypes = $('#loe-form').find('#medical_types').val();
        var vaccineTypes = $('#loe-form').find('#vaccine_types').val();
        var stcwTypes = $('#loe-form').find('#stcw_types').val();

        if (visaTypes.length === 0) {
            swal.fire('Warning', 'Please select visa type', 'warning');
        } else if(oktbTypes.length === 0) {
            swal.fire('Warning', 'Please select oktb type', 'warning');
        } else if(stcwTypes.length === 0) {
            swal.fire('Warning', 'Please select stcw type', 'warning');
        }else if(medicalTypes.length === 0) {
            swal.fire('Warning', 'Please select medical type', 'warning');
        } else if(vaccineTypes.length === 0) {
            swal.fire('Warning', 'Please select vaccine type', 'warning');
        } else {

            var html_table = `
                <p>You will update candidate loe and move the CM to deployment.</p>
                <p>The following data will deployed. Make sure is correct.</p>
                <table style='text-transform: uppercase;' class="table table-bordered">
                    <tr>
                        <td><strong>OKTB TYPE</strong></td>
                        <td>${oktbTypes}</td>
                    <tr>
                        <td><strong>VISA TYPE</strong></td>
                        <td>${visaTypes}</td>
                    </tr>
                    <tr>
                        <td><strong>STCW TYPE</strong></td>
                        <td>${stcwTypes}</td>
                    </tr>
                    <tr>
                        <td><strong>MEDICAL TYPE</strong></td>
                        <td>${medicalTypes}</td>
                    </tr>
                    <tr>
                        <td><strong>Vaccination TYPE</strong></td>
                        <td>${vaccineTypes}</td>
                    </tr>
                    
                </table>
            `
            Swal.fire({
                title: 'Submit LOE detail',
                html: html_table,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Continue!'
            }).then((result) => {
                if (result.value) {
                    btn.attr('disabled', true);
                    btn.html('Saving..');
    
                    $.ajax({
                        type: 'POST',
                        url: '/ajax/offer_letter/trackers/loe',
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: rs => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Notification!',
                                text: rs.message
                            });
                            table.ajax.reload();
                            $('#loe-form').trigger("reset");
                            $('#loe-modal').modal('hide');
                            $('#loe_date').val('');
                            $('#deploy_date').val('');
                            $('#deploy_date_end').val('');
                            btn.attr('disabled', false);
                            btn.html('Save');
    
                        },
                        error: function (response) {
    
                            btn.attr('disabled', false);
                            btn.html('Save');
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


    })


});
