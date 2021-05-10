$(document).ready(function () {
    $('.mdb-select').materialSelect()
    const table = $('#list_job').DataTable({
            "processing": true,
            "serverSide": true,
            'responsive': true,
            "ajax": {
                "url": "/ajax/job/listjob/master",
                "type": "POST",
                "data": function(d) {
                    d.job_speedy = $('#table_job_speedy_search').val();
                    d.principal_code = $('#filterPrincipalCode').val();
                    d.brand_code = $('#filterBrandCode').val();
                }
            },
            "columns": [
                {"data": null,"searchable": false},
                {"data": "brand_code"},
                {
                    "data": "job_code",
                    render: function (data, type, row) {
                        return (type === 'display') // return diferent data for display and for sorting
                            ? `${row.job_code} <br> <small><i>(${(!row.job_speedy_code ? 'no speedy job' : row.job_speedy_code)  }) ${(!row.job_speedy_code ? '' : row.job_speedy_title)}`
                            : data;
                    }
                },
                {"data": "job_speedy_code", "visible": false},//to be searched in search text input but not need to be displayed in table
                {"data": "job_title"},
                {
                    "data": 'minimum_salary',
                    render: function (data, type, row) {
                        const start = $.fn.dataTable.render.number(',', '.', 0, '$ ').display(row.minimum_salary)
                        const end = $.fn.dataTable.render.number(',', '.', 0, '$ ').display(row.max_salary)
                        return start + ' - ' + end;
                    }
                    , "class": "text-right"
                },
                {"data": "demand","searchable": false},
                {"data": null,"searchable": false}
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
                        return row.principal_code + ' <br>' + data;
                    }
                },
                {
                    "render": function (data, type, row) {
                        var html = `<div class="container row text-center ">
                                    <a data-toggle="modal" class="col-6 job_edit btn-sm btn-light" href="#" data-id="${row.job_code}" 
                                        data-code="${row.job_speedy_code}"
                                        data-job-title="${row.job_title}"
                                        data-principal-code="${row.principal_code}"
                                        data-cost-center="${row.cost_center}"
                                        data-brand-code="${row.brand_code}"
                                        data-minimum-salary="${row.minimum_salary}"
                                        data-mid-salary="${row.mid_salary}"
                                        data-max-salary="${row.max_salary}"><i class="fa fa-edit" title="Edit Data"></i></a>
                                    <a data-toggle="modal" class="col-6 job_demand btn-sm btn-info text-white" href="#" data-id="${row.job_code}" title="Demand History"><i class="fas fa-anchor"></i></a>
                                <div>`;
                        return html;
                    },
                    "targets": -1
                }
            ],
            'select': {
                'style': 'multi'
            },
            'order': [
                [1, 'asc']
            ]
        }
    );

    table.on('select', function (e, dt, type, indexes) {
        const data = table.rows({selected: true}).data();
        if (data.length > 0)
            $('#update_selected_btn').show()
    });

    table.on('deselect', function (e, dt, type, indexes) {
        const data = table.rows({selected: true}).data();
        if (data.length == 0) {
            $('#update_selected_btn').hide()
        }

    });


    $(document).on('change', '#cb_select_all', function () {
        if ($(this).prop('checked')) {
            table.rows({page: 'current'}).select();
            $('#update_selected_btn').show()
        } else {
            table.rows({page: 'current'}).deselect();
            $('#update_selected_btn').hide()
        }
    });

    $('#update_selected_btn').click(function () {
        //multi select
        const data = table.rows({selected: true}).data();

        if (data.length > 0) {
            let selected_arr = [];
            $('#multi_select').val(1);

            for (var i = 0; i < data.length; i++) {
                selected_arr.push(data[i].job_code);
            }

            $('#job_code').val(JSON.stringify(selected_arr));
            $('#edit_job_master_modal').modal('show');
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'No job selected',
                text: 'Please select job first to continue'
            });
        }

    });

    function fetchBrandCode(brand) {

        if (brand === '') return false;

        return new Promise(function(resolve, reject) {
    
            $.ajax({
                url: '/ajax/job/getprincipalbrand/' + brand,
                method: 'POST',
                success: function(response) {
                    resolve(response);
                },
                error: function(error) {
                    reject(error);
                }
            });
        });
    }

    //remove select all 
    $('select[name=list_job_length]').change(function () {
        $('#cb_select_all').prop('checked', false);
        $('#update_selected_btn').hide()
    });

    $('#list_job_filter input[type=search]').on('keyup', function () {
        $('#cb_select_all').prop('checked', false);
        $('#update_selected_btn').hide()
    });

    $(document).on('click', '.job_edit', function () {
        table.rows({
            page: 'current'
        }).deselect();

        $(this).closest('tr').addClass('selected');
        $('#cb_select_all').prop('checked', false);
        var self = $(this);

        const id = $(this).data('id');
        const code = $(this).data('code');
        const costCenter = $(this).data('cost-center');
        const jobTitle = $(this).data('job-title');
        const principalCode = $(this).data('principal-code');
        const minSalary = $(this).data('minimum-salary');
        const midSalary = $(this).data('mid-salary');
        const maxSalary = $(this).data('max-salary');

        $('#multi_select').val(0);
        $('#edit_job_master_modal_single').modal('show');

        $('#edit_job_form_single #jobSpeedyCodeEdit').val(code);
        $('#edit_job_form_single #jobCodeEdit').val(id);
        $('#edit_job_form_single #principalCodeEdit').materialSelect({destroy: true});
        $('#edit_job_form_single #principalCodeEdit').val(principalCode);
        $('#edit_job_form_single #principalCodeEdit').materialSelect();

        fetchBrandCode(principalCode).then(function(response) {
            const brandCode = self.data('brand-code');
            const brandCodeEdit = $('#edit_job_form_single #brandCodeEdit');
    
            if (response) {
                brandCodeEdit.materialSelect({
                    destroy: true
                });
                var html = '<option value="" selected>Select Brand Code</option>';
                
                response.forEach(function(item) {
                    html += '<option value="'+item['principal_brand_code']+'">'+item['name']+'</option>';
                });
                
                brandCodeEdit.html(html);
                brandCodeEdit.val(brandCode);
                brandCodeEdit.materialSelect();
                
            }
        }).catch(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Something went wrong, please try again!'
            });
        });

        $('#edit_job_form_single #costCenterEdit').val(costCenter);
        $('#edit_job_form_single #jobTitleEdit').val(jobTitle);
        $('#edit_job_form_single #minimumSalaryEdit').val(minSalary);
        $('#edit_job_form_single #mediumSalaryEdit').val(midSalary);
        $('#edit_job_form_single #maximumSalaryEdit').val(maxSalary);

    });

    $('#edit_job_form').on('submit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        $.ajax({
            // Your server script to process the upload
            url: '/ajax/job/updatejobmaster',
            type: 'POST',
            // Form data
            data: new FormData($('#edit_job_form')[0]),

            // Tell jQuery not to process data or worry about content-type
            // You *must* include these options!
            cache: false,
            contentType: false,
            processData: false,

            // Custom XMLHttpRequest
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    // For handling the progress of the upload
                    myXhr.upload.addEventListener('progress', function (e) {
                        if (e.lengthComputable) {
                            $('#save_job').attr('disabled', true);
                            $('#save_job').html('Loading..');
                        }
                    }, false);
                }
                return myXhr;
            },
            success: rs => {
                if (rs.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: rs.message
                    });

                    $('#edit_job_master_modal').modal('hide');
                    table.ajax.reload();

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: rs.message
                    });
                }
                $('#save_job').removeAttr('disabled');
                $('#save_job').text('Save');
                $(this).trigger('reset');
                $('#cb_select_all').prop('checked', false);
            }

        });
        return false;
    });

    $('#edit_job_form_single').on('submit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        $('#save_job_single').removeAttr('disabled');
        $('#save_job_single').text('Loading....');

        $.ajax({
            // Your server script to process the upload
            url: '/ajax/job/updatejobmaster',
            type: 'POST',
            // Form data
            data: new FormData($('#edit_job_form_single')[0]),

            // Tell jQuery not to process data or worry about content-type
            // You *must* include these options!
            cache: false,
            contentType: false,
            processData: false,
            success: rs => {
                if (rs.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: rs.message
                    });

                    $('#edit_job_master_modal_single').modal('hide');
                    table.ajax.reload();

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: rs.message
                    });
                }
                $('#save_job_single').removeAttr('disabled');
                $('#save_job_single').text('Save');
                $(this).trigger('reset');
            },
            error: error => {
                $('#save_job_single').removeAttr('disabled');
                $('#save_job_single').text('Save');
                if (error.status === 406) {
                    
                    Swal.fire({
                        icon: error.responseJSON.type,
                        title: 'Warning',
                        text: error.responseJSON.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong, please try again!'
                    });
                }
            }

        });
        return false;
    });

    $(document).on('click', '.job_demand', function () {
        $.ajax({
            // Your server script to process the upload
            url: '/ajax/job/jobdemand/' + $(this).data('id'),
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,

            // Custom XMLHttpRequest
            xhr: function () {
                const myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    // For handling the progress of the upload
                    myXhr.upload.addEventListener('progress', (e) => {
                        if (e.lengthComputable) {
                            $(this).attr('disabled', true);
                        }
                    }, false);
                }
                return myXhr;
            },
            success: rs => {
                $('#demand_table tbody').html('');

                if (rs.length == 0) {
                    $('#demand_table tbody').html('<tr><td colspan="3">No demand yet.</td></tr>');
                } else {
                    $.each(rs, function (key, value) {
                        $('#demand_table tbody').append('<tr><td>' + value.month + ' - ' + value.year + '</td><td>' + value.demand + ' <br> (' + value.sex + ')</td><td>' + ((value.status) ? 'active' : 'expired') + '</td></tr>')
                    });
                }

                $(this).attr('disabled', false);
            },
            error: rs => {
                $(this).attr('disabled', false);
            }
        });
        $('#job_master_demand_modal').modal('show');
    })

    $('#importjobmaster').on('submit', function () {
        $.ajax({
            // Your server script to process the upload
            url: '/ajax/job/importjobmaster',
            type: 'POST',
            // Form data
            data: new FormData($('#importjobmaster')[0]),

            // Tell jQuery not to process data or worry about content-type
            // You *must* include these options!
            cache: false,
            contentType: false,
            processData: false,

            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    // For handling the progress of the upload
                    myXhr.upload.addEventListener('progress', function (e) {
                        if (e.lengthComputable) {
                            $('#import_job_master').attr('disabled', true);
                            $('#import_job_master').html('Loading..');
                        }
                    }, false);
                }
                return myXhr;
            },
            success: rs => {
                if (rs.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: rs.message
                    });
                    $('#import_job_master').removeAttr('disabled');
                    $('#import_job_master').text('Import');
                    $(this).trigger('reset');
                    $('#importJobModal').modal('hide');
                    table.ajax.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: rs.message
                    });
                    $('#import_job_master').removeAttr('disabled');
                    $('#import_job_master').text('Import');
                    $(this).trigger('reset');
                }
            },
            error: rs => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Woops, something wrong..'
                });
                $('#import_job_master').attr('disabled', false);
                $('#import_job_master').html('Import');
            }
        });
        return false;
    });

    $('#importjobdemand').on('submit', function () {
        $.ajax({
            // Your server script to process the upload
            url: '/ajax/job/importjobdemand',
            type: 'POST',
            // Form data
            data: new FormData($('#importjobdemand')[0]),
            // Tell jQuery not to process data or worry about content-type
            // You *must* include these options!
            cache: false,
            contentType: false,
            processData: false,

            // Custom XMLHttpRequest
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    // For handling the progress of the upload
                    myXhr.upload.addEventListener('progress', function (e) {
                        if (e.lengthComputable) {
                            $('#import_job_demand').attr('disabled', true);
                            $('#import_job_demand').html('Loading..');
                        }
                    }, false);
                }
                return myXhr;
            },
            success: rs => {
                if (rs.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: rs.message
                    });
                    $('#import_job_demand').removeAttr('disabled');
                    $('#import_job_demand').text('Import');
                    $(this).trigger('reset');
                    $('#importJobDemandModal').modal('hide');
                    table.ajax.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: rs.message
                    });
                    $('#import_job_demand').removeAttr('disabled');
                    $('#import_job_demand').text('Import');
                    $(this).trigger('reset');
                }
            },
            error: rs => {
                $('#import_job_demand').removeAttr('disabled');
                $('#import_job_demand').text('Import');
            }
        });
        return false;
    });

    //search by hidden data in table
    $('#table_job_speedy_search, #filterPrincipalCode, #filterBrandCode').on('change', function () {
        table.ajax.reload();
    });

    $('#expire_on').pickadate({
        format: 'yyyy-mm-dd',
        min: new Date(),
    });

    $('#addjobmaster').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        var btnSubmit = form.find('button[type="submit"]');

        btnSubmit.text('Adding....');
        btnSubmit.addClass('disabled');

        $.ajax({
            url: '/ajax/job/addjobmaster',
            method: 'POST',
            data: form.serialize(),
            success: function(res) {
                Swal.fire({
                    icon: res.status,
                    title: 'Notification',
                    text: res.message
                });
                form.trigger('reset');
                form.find('select').val('');
                form.find('select').trigger('change');
                btnSubmit.text('Save');
                btnSubmit.removeClass('disabled');
                $('#addJobModal').modal('hide');
                table.ajax.reload();
            },
            error: function(error) {
                if (error.status == 406) {
                    Swal.fire({
                        icon: error.responseJSON.type,
                        title: 'Warning',
                        text: error.responseJSON.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Warning',
                        text: error.responseText
                    });
                }

                btnSubmit.text('Save');
                btnSubmit.removeClass('disabled');
            }
        })
    });

    $('#principalCode').on('change', function() {

        var brandCode = $(document).find('#brandCode');

        brandCode.attr('disabled', true);

        $.ajax({
            url: '/ajax/job/getprincipalbrand/' + $(this).val(),
            method: 'POST',
            success: function(response) {
                brandCode.removeAttr('disabled');

                if (response) {
                    brandCode.materialSelect({
                        destroy: true
                    });
                    var html = '<option value="" selected>Select Brand Code</option>';
                    
                    response.forEach(function(item) {
                        html += '<option value="'+item['principal_brand_code']+'">'+item['name']+'</option>';
                    });
                    
                    brandCode.html(html);
                    brandCode.materialSelect();
                    
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Warning',
                    text: error.responseText
                });
            }
        });

    });

    $('#principalCodeEdit').on('change', function() {

        var brandCode = $(document).find('#brandCodeEdit');

        brandCode.attr('disabled', true);

        $.ajax({
            url: '/ajax/job/getprincipalbrand/' + $(this).val(),
            method: 'POST',
            success: function(response) {
                brandCode.removeAttr('disabled');

                if (response) {
                    brandCode.materialSelect({
                        destroy: true
                    });
                    var html = '<option value="" selected>Select Brand Code</option>';
                    
                    response.forEach(function(item) {
                        html += '<option value="'+item['principal_brand_code']+'">'+item['name']+'</option>';
                    });
                    
                    brandCode.html(html);
                    brandCode.materialSelect();
                    
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Warning',
                    text: error.responseText
                });
            }
        });

    });

    $('#filterPrincipalCode').on('change', function() {

        var brandCode = $(document).find('#filterBrandCode');

        brandCode.attr('disabled', true);

        $.ajax({
            url: '/ajax/job/getprincipalbrand/' + $(this).val(),
            method: 'POST',
            success: function(response) {
                brandCode.removeAttr('disabled');

                if (response) {
                    brandCode.materialSelect({
                        destroy: true
                    });
                    var html = '<option value="" selected>Select Brand Code</option>';
                    
                    response.forEach(function(item) {
                        html += '<option value="'+item['principal_brand_code']+'">'+item['name']+'</option>';
                    });
                    
                    brandCode.html(html);
                    brandCode.materialSelect();
                    
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Warning',
                    text: error.responseText
                });
            }
        });

    });

});