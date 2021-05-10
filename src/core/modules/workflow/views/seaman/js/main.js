$(document).ready(function () {

    $('#table_status_search, #table_level_search').materialSelect();
    
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

    const table = $('#list_seaman').DataTable({
            "processing": true,
            "serverSide": true,
            'responsive': true,
            "ajax": {
                "url": "/ajax/workflow/seaman/list",
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
                        return '<a href="/personal/home/'+row.address_book_id+'">'+row.fullname+'<br>' + row.main_email+'</a>';
                    },
                    "targets": 0
                },
                {
                    "render": function (data, type, row) {
                        if (data == 'request_file' && row.request_file_on != '0000-00-00 00:00:00'){
                            return data + `<br><span class="badge badge-info">Send request on: ${row.request_file_on}`
                        }
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
                    "render": function (data, type, row) {

                        var html = ``;
                        if(row.status == 'request_file'){
                            html += `<a  class="btn-sm btn-info btn-request-file" href="#" title="Request File" ><i class="fa fa-plus"></i> Request File</a>`;
                        }
                        else if(row.status == 'review_file'){
                            html += `<a  class="btn-sm btn-info btn-review-file" href="#" title="Review File" ><i class="fa fa-dollar-sign"></i> Review</a>`;
                        }
                        else if(row.status == 'rejected'){
                            html += `<a  class="btn-sm btn-info btn-rejected" href="#" title="Rejected" ><i class="fa fa-dollar-sign"></i> Rejected</a>`;
                        }


                        return html;
                    },
                    "targets": -1
                }
            ],
        });

    $('#table_status_search, #table_level_search').on('change', function () {
        table.ajax.reload()
    });

    $(document).on('click','.btn-request-file', function () {
        var data = table.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Send request seaman to Candidate?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/seaman/request-file',
                    datatype: 'json',
                    data: {
                        'address_book_id': data.address_book_id
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload(false);

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

    $(document).on('click', '.btn-review-file', function() {
        let data = table.row(this.closest('tr')).data();

        showLoadingModal();
        $.get('/ajax/workflow/seaman/file-preview/'+data.address_book_id, function(response) {
            Swal.close();
            $('#code').html(response.seaman_id);
            $('#fullname').html(response.full_name || (response.given_names + ' ' + response.family_name));
            $('#nationality').html(response.nationality);
            $('#date').html(response.from_date);
            $('#to_date').html(response.to_date);
            $('.file-preview').html('<img src="'+response.url+'" class="img img-fluid" />');

            $('.accept_seaman, .reject_seaman').attr('data-seaman-id', response.seaman_id);
            $('#seamanModal').modal('show');
        });
    });

    $('.accept_seaman').click(function()
		{
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing accept this seaman book",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{	
                    showLoadingModal();
					$.ajax({
						url: "/ajax/workflow/seaman/accept-seaman/"+$(this).data('seaman-id'),
						type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
						});
                        table.ajax.reload();
                        $('#seamanModal').modal('hide');
					})
					.fail(error => {
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: error.response.message
						});
					});
				}
			});
		});

		$('.reject_seaman').click(function()
		{
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing reject this seaman book",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{	
                    showLoadingModal();
					$.ajax({
						url: "/ajax/workflow/seaman/reject-seaman/"+$(this).data('seaman-id'),
						type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
                        });
                        table.ajax.reload();
                        $('#seamanModal').modal('hide');
					})
					.fail(error => {
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: error.response.message
						});
					});
				}
			});
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