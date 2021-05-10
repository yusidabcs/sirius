$(document).ready(function () {

    const current = new Date();
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

    $('#table_status_search, #table_level_search').materialSelect();
    $('#invoice_expected_on').pickadate({
        format: 'yyyy-mm-dd'
    });
    let flatpickr = $('.flatpickr').flatpickr({
        enableTime: true,
        minDate: current,
        dateFormat: 'Y-m-d H:i',
        defaultDate: new Date().setDate(current.getDate() + 1),
        altInput: true
    });

    const table = $('#list_visa').DataTable({
            "processing": true,
            "serverSide": true,
            'responsive': true,
            "ajax": {
                "url": "/ajax/workflow/visa/list",
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
                        if (data == 'register_visa' && row.send_notification_on != '0000-00-00 00:00:00'){
                            return data + `<br><span class="badge badge-info">Send request on: ${row.send_notification_on}`
                        }

                        if (data == 'docs_application' && row.docs_application_on != '0000-00-00 00:00:00'){
                            return data + `<br><span class="badge badge-info">Docs application on: ${row.docs_application_on}`
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
                        if(row.status === 'register_visa' && row.send_notification_on !== '0000-00-00 00-00-00') {
                            html += `<a  class="btn-sm btn-info btn-send-notification" href="#" title="Send Notification" ><i class="fa fa-plus"></i> Send Notification</a>`
                        }
                        if(row.status === 'docs_application' && row.docs_application_date !== '0000-00-00 00-00-00') {
                            html += `<a  class="btn-sm btn-info btn-send-docs-application" href="#" title="Docs Application" ><i class="fa fa-plus"></i> Docs Application</a>`
                        }
                        else if(row.status == 'upload_visa' && row.upload_visa_on !== '0000-00-00 00:00:00'){
                            html += `<a  class="btn-sm btn-info btn-review-visa" href="#" title="Review File" ><i class="fa fa-dollar-sign"></i> Review</a>`;
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

    $(document).on('click', '.btn-review-visa', function(e) {
        e.preventDefault();
        let data = table.row(this.closest('tr')).data();
        showLoadingModal();
        
        $.post('/ajax/workflow/visa/file-preview/'+data.address_book_id, { visa_type: data.visa_type }, function(response) {
            Swal.close();
            $('#visa_id').html(response.visa_id);
            $('#visa_type').html(response.type);
            $('#date_of_issue').html(response.from_date);
            $('#expired_date').html(response.to_date);

            $('.confirm-visa, .reject-visa').attr('data-visa-id', response.visa_id);
            $('.confirm-visa, .reject-visa').attr('data-visa-type', response.type);

            $('.file-preview').html('<img src="'+response.url+'" class="img img-fluid" />');
            $('#visaModal').modal('show');
        });
    });

    $(document).on('click', '.btn-send-notification', function(e) {
        e.preventDefault();
        let data = table.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Notify CM to register visa application?',
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
                    url: '/ajax/workflow/visa/send-notification',
                    data: {
                        'address_book_id': data.address_book_id,
                        'country_code': data.country_code,
                        'visa_type': data.visa_type
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload();

                    },
                    error: function (response) {
                        $('input[name="address_book_id"]').val('');
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
    });
    
    $(document).on('click', '.btn-send-docs-application', function(e) {
        e.preventDefault();
        let data = table.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Notify CM to complete docs application?',
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
                    url: '/ajax/workflow/visa/notif-docs-application',
                    data: {
                        'address_book_id': data.address_book_id,
                        'country_code': data.country_code,
                        'visa_type': data.visa_type
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload();

                    },
                    error: function (response) {
                        $('input[name="address_book_id"]').val('');
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
	});

    $(document).on('click','.btn-request-file', function () {
        var data = table.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Send request medical file check to Candidate?',
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
                    url: '/ajax/workflow/medical/request-file',
                    data: {
                        'address_book_id': data.address_book_id,
                        'visa_type': data.visa_type
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

        });
    });

    $('.confirm-visa').click(function()
		{
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing accept this visa document",
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
						url: "/ajax/workflow/visa/accept-visa/"+$(this).data('visa-id'),
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
                        $('#visaModal').modal('hide');
                        table.ajax.reload();
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

		$('.reject-visa').click(function()
		{
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing reject this visa document",
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
						url: "/ajax/workflow/visa/reject-visa/"+$(this).data('visa-id'),
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
                        $('#visaModal').modal('hide');
                        table.ajax.reload();
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