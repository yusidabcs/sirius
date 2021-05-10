const table = $('#list_job').DataTable(
    {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/job/jobdemand/list",
            "type": "POST"
        },
        "columns": [
            { "data": 'job_speedy_code' },
            { "data": 'demand',"searchable": false },
            { "data": 'allocation', "searchable": false  },
            { "data": 'job_title'},
            { "data": null, "searchable": false  },
            { "data": null, "searchable": false  },
        ],
        "columnDefs": [
            {
                "render": function ( data, type, row ) {
                    var html = '<a href="#" class="btn btn-info btn-sm detail_demand" data-code="'+row.job_speedy_code+'"><i class="fa fa-eye"></i></a>';
                    return html;
                },
                "targets": -1
            },
            {
                "render": function ( data, type, row ) {

                    return row.demand - row.allocation;
                },
                "targets": -2
            },
            {
                "render": function ( data, type, row ) {
                    return row.job_speedy_code +' - '+row.job_title;
                },
                "targets": 0
            },
            {
                "render": function ( data, type, row ) {
                    return data == null ? 0 : data;
                },
                "targets": 1
            },
            {
                "render": function ( data, type, row ) {
                    return row.allocation == null ? 0 : row.allocation;
                },
                "targets": 2
            },
            {
                "visible": false,
                "targets": 3
            },

        ],
    } );
var table_demand = null;
$('body').on('click','.detail_demand', function ()
{
    $('#add_demand').modal('show')
    $('#add_demand').on('shown.bs.modal', function() {
        $(document).off('focusin.modal');
    });
    var code = $(this).data('code');
    if(table_demand)
        table_demand.destroy();
    $('#job_speedy_code').val(code);
    table_demand = $('#add_demand_table').DataTable(
        {
            "processing": true,
            "pageLength": $('#add_demand_table').data('limit'),
            "serverSide": true,
            "ajax": {
                "url": "/ajax/job/jobspeedy/jobmaster/"+code,
                "type": "POST"
            },
            "columns": [
                { "data": "job_code" },
                { "data": 'brand_code' },
                { "data": 'demand' },
                { "data": 'allocation' },
                { "data": null, "searchable": false  },
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        var html = '<a href="#" class="btn btn-sm btn-info update-demand" data-id="'+row.job_master_id+'"><i class="fa fa-pen"></i></a>';
                        return html;
                    },
                    "targets": -1
                },
                {
                    "render": function ( data, type, row ) {
                        return row.job_code +' - '+row.job_title;
                    },
                    "targets": 0
                },

            ],
        } );
    return false;
})

$('body').on('click','.update-demand', async function ()
{
    // Data Picker Initialization
    var job_master_id = $(this).data('id')
    const {value: formValues} = await Swal.fire({
        title: 'Update Demand',
        html: '' +
        '<div class="md-form">' +
        '<label for="demand">New demand</label>' +
        '<input id="demand" class="form-control" type="number" value="1" min="0">' +
        '</div>' +
        '<div class="md-form">' +
        '  <label for="expiry_on">Expiry On</label>' +
        '  <input placeholder="Selected date" type="text" id="expiry_on" class="form-control datepicker">' +
        '</div>' +
        '<div class="md-form">' +
        '   <label for="expiry_on">Reason</label>' +
        '  <input type="text" maxlength="255" id="reason" class="form-control">' +
        '</div>',
        confirmButtonText: 'Save',
        onOpen: () => {

            $('#expiry_on').pickadate({
                min: new Date()
            });
            $('.swal2-actions').css('z-index',0)
        },
        preConfirm: () => {
            var demand = document.getElementById('demand').value;
            var expiry_on = document.getElementById('expiry_on').value;
            var reason = document.getElementById('reason').value;
            if(expiry_on == ''){
                Swal.showValidationMessage(
                    `Please input expiry date!`
                )
                return false;
            }
            if(reason == ''){
                Swal.showValidationMessage(
                    `Please input reason!`
                )
                return false;
            }
            return {
                demand,
                expiry_on,
                reason
            };
        }
    })

    Swal.fire({
        title: 'Are you sure?',
        text: "Old demand will expired and the new demand will apply.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Update it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "/ajax/job/jobdemand/update_demand/"+job_master_id,
                type: 'POST',
                data: formValues,
                cache: true,
                timeout: 10000
            })
                .done(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Notification.',
                        text: response.message
                    });
                    table_demand.ajax.reload(false)
                    table.ajax.reload(false)
                }).fail(rs => {
            });
        }
    })


    return false;
})

$('#add_demand_form').on('submit', function (e) {
    e.preventDefault();
    
    var btn = $(this).find('[type=submit]')
    btn.attr('disabled',true).html('Saving..')
    $.ajax({
        url: "/ajax/job/jobdemand/addingdemand",
        type: 'POST',
        data: $(this).serialize(),
        cache: true,
        timeout: 10000
    })
        .done(response => {
            Swal.fire({
                icon: 'success',
                title: 'Notification.',
                text: response.message
            });
            table_demand.ajax.reload(false);
            btn.attr('disabled',false).html('Update')
            $('#add_demand').modal('hide')
            location.reload();
        }).fail(rs => {
        btn.attr('disabled',false).html('Update')
        btn.attr('disabled',false).html('Save')
    });
    return false;
})
