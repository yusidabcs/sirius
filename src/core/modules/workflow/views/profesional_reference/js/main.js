$(document).ready(function () {

    const current = new Date();

    $('#table_status_search, #table_level_search').materialSelect();

    let flatpickr = $('.flatpickr').flatpickr({
        enableTime: true,
        minDate: current,
        dateFormat: 'Y-m-d H:i',
        defaultDate: new Date().setDate(current.getDate() + 1),
        altInput: true
    });

    const table = $('#list_profesional_reference').DataTable({
            "processing": true,
            "serverSide": true,
            'responsive': true,
            "ajax": {
                "url": "/ajax/workflow/job_application_tracker/workflow-profesional-reference",
                "type": "POST",
                cache: false,
                data: function (d) {
                    d.status = $('#table_status_search').val()
                    d.level = $('#table_level_search').val()
                    d.startDate = $('#startingDate').val()
                    d.endDate = $('#endingDate').val()
                }
            },
            "columns": [
                {"data": 'candidate'},
                {"data": 'status'},
                {"data": 'level'},
                {"data": 'created_on'},
                {"data": 'entity_family_name'},
                {"data": 'number_given_name'},
                {"data": null}
            ],
            "columnDefs": [
                {
                    "render": function (data, type, row) {
                        return '<a href="/personal/home/'+row.address_book_id+'">'+row.entity_family_name+'<br>' + row.main_email+'</a>';
                    },
                    "targets": 0
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
                    "render": function(data) {
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
                    "orderable": false,
                    "render": function (data, type, row) {

                        var html = ``;
                        // if(row.status === 'request_appointment_date') {
                        //     html += `<a  class="btn-sm btn-info btn-request-appointment-date" href="#" title="Request File" ><i class="fa fa-plus"></i> Request Appointment</a>`
                        // }
                        // else if(row.status == 'request_file'){
                        //     html += `<a  class="btn-sm btn-info btn-request-file" href="#" title="Request File" ><i class="fa fa-plus"></i> Request File</a>`;
                        // }
                        // else if(row.status == 'review_file'){
                        //     html += `<a  class="btn-sm btn-info btn-review-file" href="#" title="Review File" ><i class="fa fa-dollar-sign"></i> Review</a>`;
                        // }
                        // else if(row.status == 'rejected'){
                        //     html += `<a  class="btn-sm btn-info btn-rejected" href="#" title="Rejected" ><i class="fa fa-dollar-sign"></i> Rejected</a>`;
                        // }


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

});