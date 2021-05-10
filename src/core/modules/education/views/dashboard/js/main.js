function getCountTracker() {
    let period = $('#table_period_search').val();
    let start_date = $('#startingDate').val();
    let end_date = $('#endingDate').val();
    if(start_date!='') {
        start_date = moment(start_date, 'D MMMM, YYYY').format('YYYY-MM-DD');
    }
    if(end_date!='') {
        end_date = moment(end_date, 'D MMMM, YYYY').format('YYYY-MM-DD');
    }
    $.ajax({
        url: "/ajax/workflow/education/count-tracker-dashborad",
        type: 'POST',
        cache: false,
        data: {
            period:period,
            start_date : start_date,
            end_date : end_date
        },
    })
        .done(response => 
        {
            $('#count_tracker #all_level').text(response.all_level);
            $('#count_tracker #normal').text(response.normal);
            $('#count_tracker #soft_warning').text(response.soft_warning);
            $('#count_tracker #hard_warning').text(response.hard_warning);
            $('#count_tracker #deadline').text(response.deadline);
        })
        .fail(response => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something errors while count tracker'
            });
        });

    return false;
}


$(document).ready(function () {

    var from_input = $('#startingDate').pickadate();
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

    $('#table_period_search').materialSelect();
    getCountTracker();
    const table_tracker = $('#list_education_tracker').DataTable({
            "processing": true,
            "serverSide": true,
            'responsive': true,
            "ajax": {
                "url": "/ajax/workflow/education/list-dashbord",
                "type": "POST",
                cache: false,
                data: function (d) {
                    d.period = $('#table_period_search').val();
                    var start_date = $('#startingDate').val();
                    var end_date = $('#endingDate').val();

                    if(start_date!='') {
                        start_date = moment(start_date, 'D MMMM, YYYY').format('YYYY-MM-DD');
                    }
                    if(end_date!='') {
                        end_date = moment(end_date, 'D MMMM, YYYY').format('YYYY-MM-DD');
                    }

                    d.start_date = start_date;
                    d.end_date = end_date;
                }
            },
            "columns": [
                {"data": null},
                {"data": 'main_email'},
                {"data": 'course_name'},
                {"data": 'status'},
                {"data": 'level'},
                {"data": 'created_on'}
            ],
            "order" : [[5,'desc']],
            "columnDefs": [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    "searchable": false, "orderable": false, "targets": 0
                },
                {
                    "render": function (data, type, row) {
                        return '<a href="/personal/home/'+row.address_book_id+'" target="_blank">'+row.entity_family_name+'<br>' + row.main_email+'</a>';
                    },
                    "targets": 1
                },
                {
                    "render": function (data, type, row) {
                        return row.course_name;
                    },
                    "targets": 2
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
                    "targets": 4
                }
            ]
        });        /*getCountRequest();
        table_request.ajax.reload();*/
        
    $('#table_period_search, #startingDate, #endingDate').on('change', function () {
        getCountTracker();
        table_tracker.ajax.reload();
    });

    $('#export_tracker').on('click',function(){
        var period = $('#table_period_search').val();
        var start_date = $('#startingDate').val();
        var end_date = $('#endingDate').val();

        if(start_date!='') {
            start_date = moment(start_date, 'D MMMM, YYYY').format('YYYY-MM-DD');
        }
        if(end_date!='') {
            end_date = moment(end_date, 'D MMMM, YYYY').format('YYYY-MM-DD');
        }

        let mapForm = document.createElement("form");
        mapForm.target = "_blank";    
        mapForm.method = "POST";
        mapForm.action = "/ajax/education/main/export-tracker";

        // Create an input
        let input_period = document.createElement("input");
        input_period.type = "text";
        input_period.name = "period";
        input_period.value = period;

        let input_start_date = document.createElement("input");
        input_start_date.type = "text";
        input_start_date.name = "start_date";
        input_start_date.value = start_date;

        let input_end_date = document.createElement("input");
        input_end_date.type = "text";
        input_end_date.name = "end_date";
        input_end_date.value = end_date;
        // Add the input to the form
        mapForm.appendChild(input_period);

        mapForm.appendChild(input_start_date);
        mapForm.appendChild(input_end_date);

        // Add the form to dom
        document.body.appendChild(mapForm);

        // Just submit
        mapForm.submit();

        document.body.removeChild(mapForm);
    })
    
});