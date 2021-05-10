$(document).ready(function () {

    // $.ajax({
    //     url: '/ajax/interview/location/nonactive',
    //     data: $(this).serialize(),
    //     type: 'POST', 
    //     datatype : 'json',
    //     success: function(rs) {
    //     },
    //     error: function(response) {
    //     }
    // });

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

    const table = $('#list_location').DataTable( {
            "processing": true,
            "serverSide": true,
            'responsive': true ,
            "ajax": {
                "url": "/ajax/interview/location/list",
                "type": "POST",
                data: function (d) {
                    d.start_on = $('#startingDate').val()
                    d.finish_on = $('#endingDate').val()
                }
            },
            "columns": [
                { "data" : null},
                { "data" : null},
                { "data" : 'interview_name'},
                { "data" : 'total_candidate'},
                { "data" : null}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return row.start_on +' <br> ' +row.finish_on + '<br>' + (row.status == 0 ? '<label class="badge badge-danger">Not Active</label>' : '<label class="badge badge-success">Active</label>')
                            + '<br>' + (row.visible == 0 ? '<label class="badge badge-danger">Not Visible</label>' : '<label class="badge badge-success">Visible</label>');
                    },
                    "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        return row.country_name +' - ' + row.country_sub_name + '  <hr> ' +row.address +' <hr> <a href="'+row.google_map+'" target="_blank">' +row.google_map+'</a>';
                    },
                    "targets": 1
                },

                {
                    "render": function ( data, type, row ) {
                        return row.organizer_name
                    },
                    "targets": 2
                },

                {
                    "render": function ( data, type, row ) {
                        var url = $('#list_location').data('url')
                        var html = `<div class="container d-flex text-center ">
                                    <a  class="btn-sm btn-light" href="${url}/edit/${row.interview_location_id}" ><i class="fa fa-edit" title="Edit Data"></i></a>
                                    <a class="btn-sm btn-danger text-white schedule_delete_btn" href="#" data-id="${row.interview_location_id}" title=""><i class="fas fa-times"></i></a>
                                    <a class="btn-sm btn-info text-white" href="${url}/detail_location/${row.interview_location_id}" data-id="${row.interview_location_id}" title=""><i class="fas fa-users"></i></a>
                                <div>`;
                        return html;
                    },
                    "targets": -1
                }
            ],
            'order': [
                [0, 'desc']
            ]
        }
    );

    $('#startingDate, #endingDate').on('change', function () {
        table.ajax.reload();
    });

    $(document).on('click','.schedule_delete_btn', function (e) {

        var id = $(this).data('id')
        Swal.fire({
            title: 'Are you sure?',
            text: "This action will delete this item?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                e.preventDefault();
                showLoadingModal();
                $.ajax({
                    url: '/ajax/interview/location/delete/'+id,
                    data: $(this).serialize(),
                    type: 'POST', //send it through get method
                    datatype : 'json',
                    success: function(rs) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload();
                    },
                    error: function(response) {

                        btn.attr('disabled',false);
                        btn.html(text);
                        if(response.status == 400)
                        {
                            text = ''
                            $.each(response.responseJSON.errors, (index,item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        }else{
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


// Get the elements
    var from_input = $('#startingDate').pickadate(),
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
