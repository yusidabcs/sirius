$(document).ready(function () {

    $('#table_status_search, #table_level_search').materialSelect();
    $('#invoice_expected_on').pickadate({
        format: 'yyyy-mm-dd'
    });
    

    const table = $('#list_finance_principal').DataTable({
            "processing": true,
            "serverSide": true,
            'responsive': true,
            "ajax": {
                "url": "/ajax/workflow/principal/list",
                "type": "POST",
                cache: false,
                data: function (d) {
                    d.status = $('#table_status_search').val()
                    d.level = $('#table_level_search').val()
                    d.start_date = $('#startingDate').val()
                    d.end_date = $('#endingDate').val()
                }
            },
            "columns": [
                {"data": 'candidate'},
                {"data": 'status'},
                {"data": 'level'},
                {"data": 'created_on'},
                {"data": 'entity_family_name'},
                {"data": 'number_given_name'},
                {"data": null},
            ],
            "columnDefs": [
                {
                    "render": function (data, type, row) {
                        return data+'<br>' + row.main_email;
                    },
                    "targets": 0
                },
                {
                    "render": function (data, type, row) {
                        if (data == 'pay_invoice')
                            return `<span class="badge badge-warning">Pay Invoice</span> <br> Expected paid on: ${row.invoice_expected_on}`
                        return data;
                    },
                    "targets": 1
                },

                {
                    "render": function (data, type, row) {
                        if (data == '1')
                            return `<span class="text-success">Normal</span>`
                        if (data == '2')
                            return `<span class="text-info">Soft Warning</span>`
                        if (data == '3')
                            return `<span class="text-warning">Hard Warning</span>`
                        if (data == '4')
                            return `<span class="text-danger">Deadline</span>`
                    },
                    "targets": 2
                },

                {
                    "render": function(data, type, row) {
                        return data
                    },
                    "targets": 3
                },

                {
                    "visible": false,
                    "targets": 4
                },

                {
                    "visible": false,
                    "targets": 5
                },

                {
                    "render": function (data, type, row) {

                        var html = ``;
                        if(row.status == 'generate_invoice'){
                            html += `<a  class="btn-sm btn-info btn-generate-invoice" href="#" title="Generate Invoice" ><i class="fa fa-plus"></i> Invoice</a>`;
                        }
                        else if(row.status == 'pay_invoice'){
                            html += `<a  class="btn-sm btn-info btn-pay-invoice" href="#" title="Pay Invoice" ><i class="fa fa-dollar-sign"></i> Pay</a>`;
                        }


                        return html;
                    },
                    "targets": -1
                }
            ],
        });

    $('#table_status_search, #table_level_search, #startingDate, #endingDate').on('change', function () {
        table.ajax.reload()
    });

    var from_input = $('#startingDate').pickadate()
    from_picker = from_input.pickadate('picker')
    var to_input = $('#endingDate').pickadate(),
        to_picker = to_input.pickadate('picker')

// Check if there’s a “from” or “to” date to start with and if so, set their appropriate properties.
    if (from_picker.get('value')) {
        to_picker.set('min', from_picker.get('select'))
    }
    if (to_picker.get('value')) {
        from_picker.set('max', to_picker.get('select'))
    }

// Apply event listeners in case of setting new “from” / “to” limits to have them update on the other end. If ‘clear’ button is pressed, reset the value.
    from_picker.on('set', function (event) {
        if (event.select) {
            to_picker.set('min', from_picker.get('select'))
        } else if ('clear' in event) {
            to_picker.set('min', false)
        }
    })
    to_picker.on('set', function (event) {
        if (event.select) {
            from_picker.set('max', to_picker.get('select'))
        } else if ('clear' in event) {
            from_picker.set('max', false)
        }
    })

    $(document).on('click','.btn-generate-invoice', function () {
        var data = table.row(this.closest('tr')).data();
        $('#generate-invoice-modal').modal('show')
        $('#generate-invoice-form').find('input[name=job_application_id]').val(data.job_application_id)
    })

    $('#generate-invoice-form').on('submit', function (e) {
        e.preventDefault()

        Swal.fire({
            title: 'Save Generate Invoice',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/principal/generate_invoice',
                    data: $(this).serialize(),
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload(false);
                        $('#generate-invoice-modal').modal('hide')

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
    });

    $(document).on('click','.btn-pay-invoice', function () {
        var data = table.row(this.closest('tr')).data();
        $('#pay-invoice-modal').modal('show')
        $('#pay-invoice-form').find('input[name=job_application_id]').val(data.job_application_id)
    })

    $('#pay-invoice-form').on('submit', function (e) {
        e.preventDefault()

        Swal.fire({
            title: 'Update Invoice',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/principal/pay_invoice',
                    data: $(this).serialize(),
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload(false);
                        $('#pay-invoice-modal').modal('hide')
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
    });
});