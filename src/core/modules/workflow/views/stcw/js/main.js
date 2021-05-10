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

    const table = $('#list_medical').DataTable({
            "processing": true,
            "serverSide": true,
            'responsive': true,
            "ajax": {
                "url": "/ajax/workflow/stcw/list",
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
                {"data": 'stcw_type'},
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
                    "targets": 3
                },

                {
                    "render": function(data) {
                        return data
                    },
                    "targets": 4
                },

                {
                    "visible": false,
                    "targets": 5
                },

                {
                    "visible": false,
                    "targets": 6
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

    // $('#table_status_search, #table_level_search').on('change', function () {
    //     table.ajax.reload()
    // });

    $(document).on('click','.btn-request-file', function () {
        var data = table.row(this.closest('tr')).data();
        //console.log(data);
        Swal.fire({
            title: 'Send request STCW document to CM?',
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
                    url: '/ajax/workflow/stcw/request-file',
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
    })

    $(document).on('click', '.btn-review-file', function() {
        let data = table.row(this.closest('tr')).data();

        showLoadingModal();
        $.post('/ajax/workflow/stcw/file-preview/'+data.address_book_id, { stcw_type: data.stcw_type }, function(response) {
            Swal.close();
            $('#institution').html(response[0].institution);
            $('#qualification').html(response[0].qualification);
            $('#certificate_date').html(response[0].certificate_date);
            $('#certificate_expiry').html(response[0].certificate_expiry);

            $('.confirm-stcw, .reject-stcw').attr('data-address-book-id', response[0].address_book_id);
            $('.confirm-stcw, .reject-stcw').attr('data-education-id', response[0].education_id);

            $('.file-preview').html('<img src="'+response[0].url+'" class="img img-fluid" />');
            $('#stcwModal').modal('show');
        });
    });

    $('.confirm-stcw').on('click', function(e) {
		e.preventDefault();
		swal.fire({
			title: 'Confirmation',
			text: 'Are you sure to confirm this stcw document?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Confirm it'
		}).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    method: 'POST',
                    url: '/ajax/workflow/stcw/confirmstcw/'+$(this).data('address-book-id')+'/'+$(this).data('education-id'),
                    success: function(response) {
                        Swal.fire({
                            type: 'success',
                            title: 'Document status edited!',
                            text: response.message
                        }).then(() => {
                            table.ajax.reload();
                            $('#stcwModal').modal('hide');
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            type: 'error',
                            title: 'Operation failed!',
                            text: 'Something went wrong!'
                        });
                    }
                })
            }
		});
	});

	$('.reject-stcw').on('click', function(e) {
		e.preventDefault();
		swal.fire({
			title: 'Confirmation',
			text: 'Are you sure to reject this stcw document?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Reject it'
		}).then((result) => {
			if (result.value) {
				showLoadingModal();
				$.ajax({
					method: 'POST',
					url: '/ajax/workflow/stcw/rejectstcw/'+$(this).data('address-book-id')+'/'+$(this).data('education-id'),
					success: function(response) {
						Swal.fire({
							type: 'success',
							title: 'Document status edited!',
							text: response.message
						}).then(() => {
                            table.ajax.reload();
                            $('#stcwModal').modal('hide');
						});
					},
					error: function(error) {
						Swal.fire({
							type: 'error',
							title: 'Operation failed!',
							text: 'Something went wrong!'
						});
					}
				})
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