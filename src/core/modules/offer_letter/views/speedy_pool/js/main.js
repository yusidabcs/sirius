$(document).ready(function () {
    const table = $('#list_pool').DataTable({
            "processing": true,
            "serverSide": true,
            'responsive': true,
            "ajax": {
                "url": "/ajax/offer_letter/main/list_pool",
                "type": "POST",
                data: function (d) {

                }
            },
            "columns": [
                {"data": 'job_speedy_code'},
                {"data": 'count'},
                {"data": 'demand'},
                {"data": 'allocated'},
                {"data": null}
            ],
            "columnDefs": [
                {
                    "render": function (data, type, row) {
                        return row.job_speedy_code + ' - ' + row.job_title;
                    },
                    "targets": 0
                },
                {
                    "render": function (data, type, row) {
                        return row.demand ? row.demand : 0
                    },
                    "targets": 2
                },
                {
                    "render": function (data, type, row) {
                        return row.allocated ? row.allocated : 0
                    },
                    "targets": 3
                },
                {
                    "render": function (data, type, row) {
                        var url = $('#list_schedule').data('url')

                        var html = `<a  class="btn-sm btn-info btn-allocation" href="#" data-job-speedy-code="${row.job_speedy_code}" data-total-pool="${row.count}" ><i class="fa fa-user" title="Allocation"></i></a>`;

                        return html;
                    },
                    "targets": -1
                }
            ],
        }
    );

    var table_demand = null
    var job_speedy_code = ''
    var total_pool = 0

    $(document).on('click', '.btn-allocation', function (e) {
        demand = table.row(this.closest('tr')).data();
        if(demand.demand == 0 || demand.demand == null){
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'No demand request to allocate!'
            });
            return false;
        }

        job_speedy_code = $(this).data('job-speedy-code')
        total_pool = $(this).data('total-pool')

        $('#total_pool').val(total_pool)
        $('#allocation_modal').modal('show')

        if (table_demand)
            table_demand.destroy()

        table_demand = $('#job_demand_table').DataTable(
            {
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "/ajax/job/jobspeedy/jobmaster/" + job_speedy_code,
                    "type": "POST"
                },
                "columns": [
                    {"data": "job_code"},
                    {"data": 'brand_code'},
                    {"data": 'demand'},
                    {"data": 'allocation'},
                    {"data": null, "searchable": false},
                ],
                "columnDefs": [
                    {
                        "render": function (data, type, row) {
                            var html = ''
                            if(row.demand > 0)
                                var html = '<input type="button" class="btn btn-sm btn-info btn-allocate" data-job-master-id="' + row.job_master_id + '" value="Allocate" />'
                            return html;
                        },
                        "targets": -1
                    },
                    {
                        "render": function (data, type, row) {
                            return row.job_code + ' - ' + row.job_title;
                        },
                        "targets": 0
                    },

                ],
            });

        return false;
    })

    var table_candidate_list = null;
    var allocation = null;
    var job_demand_master_id = null;
    var job_master_id = null;
    var address_book_id = []
    var job_application_id = []

    $(document).on('click', '.btn-allocate', function () {

        allocation = table_demand.row(this.closest('tr')).data();
        console.log(allocation.job_demand_master_id)
        job_demand_master_id = allocation.job_demand_master_id
        job_master_id = allocation.job_master_id

        $('#allocation_modal_title').html(' For (' + allocation.brand_code + ') ' + allocation.job_code + ' - ' + allocation.job_title)
        $('#allocation_total').html(total_pool)

        job_master_id = $(this).data('job-master-id');

        if (table_candidate_list)
            table_candidate_list.clear().destroy()

        table_candidate_list = $('#candidate_list').DataTable(
            {
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "/ajax/interview/interview/list_result",
                    "type": "POST",
                    data: function (d) {
                        d.length = total_pool
                        d.start = 0
                        d.job_speedy_code = job_speedy_code,
                        d.status = 'hired'
                    }
                },
                "columns": [
                    {"data": null, "sortable": false},
                    {"data": 'candidate', "sortable": false},
                    {"data": 'main_email', "sortable": false},
                    {"data": 'created_on', "sortable": false}
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

                ],
                'select': {
                    'style': 'multi'
                },
            });

        table_candidate_list.on('select', function (e, dt, type, indexes) {
            address_book_id = []
            job_application_id = []
            selected_schedule = table_candidate_list.rows({selected: true}).data();
            $.each(selected_schedule, (index, item) => {
                address_book_id.push(item.address_book_id)
                job_application_id.push(item.job_application_id)
            })
        });

        $('#allocation_candidate').modal('show')
    })

    $(document).on('submit', '#allocation_form', function () {
        if(address_book_id.length == 0)
            return false;

        Swal.fire({
            title: 'Are you sure?',
            text: "This action will allocate this job application to selected job ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/ajax/offer_letter/main/allocation',
                    type: 'POST',
                    data: {
                        'job_demand_master_id': job_demand_master_id,
                        'address_book_id': address_book_id,
                        'job_application_id': job_application_id,
                        'job_master_id': job_master_id,
                    },
                    success: function (rs) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        $('#allocation_candidate').modal('hide')
                        table.ajax.reload()
                        table_demand.ajax.reload()

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
        });

        return false;
    })

})