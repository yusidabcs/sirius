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
    
    let flatpickr = $('.flatpickr').flatpickr({
        enableTime: true,
        minDate: current,
        dateFormat: 'Y-m-d H:i',
        defaultDate: new Date().setDate(current.getDate() + 1),
        altInput: true
    });

    const table = $('#list_medical').DataTable({
            "processing": true,
            "serverSide": true,
            'responsive': true,
            "ajax": {
                "url": "/ajax/workflow/medical/list",
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
                {"data": 'medical_type'},
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

                        if (data == 'request_appointment_date' && row.request_appointment_date_on !== '0000-00-00 00:00:00'){
                            return data + `<br><span class="badge badge-info">Request appointment date on: ${row.request_appointment_date_on}`
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
                        if(row.status === 'request_appointment_date') {
                            html += `<a  class="btn-sm btn-info btn-request-appointment-date" href="#" title="Request Appointment" ><i class="fa fa-plus"></i> Request Appointment</a>`
                        }
                        else if(row.status == 'request_file'){
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

    $(document).on('click', '.btn-review-file', function() {
        let data = table.row(this.closest('tr')).data();
        
        showLoadingModal();
        
        $.post('/ajax/workflow/medical/file-preview/'+data.address_book_id, { medical_type: data.medical_type }, function(response) {
            Swal.close();
            $('#institution').html(response[0].institution);
            $('#doctor').html(response[0].doctor);
            $('#certificate_number').html(response[0].certificate_number);
            $('#certificate_date').html(response[0].certificate_date);
            $('#certificate_expiry').html(response[0].certificate_expiry);

            $('.confirm-medical, .reject-medical').attr('data-medical-id', response[0].medical_id);

            $('.file-preview').html('<img src="'+response[0].url+'" class="img img-fluid" />');
            $('#medicalModal').modal('show');
        });
    });

    $(document).on('click', '.btn-request-appointment-date', function(e) {
        e.preventDefault();
        let data = table.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Send request medical appointment date to Candidate?',
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
                    url: '/ajax/workflow/medical/request-appointment-date',
                    data: {
                        'address_book_id': data.address_book_id,
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

        });
    });

    $('.confirm-medical').click(function()
		{
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing accept this medical document",
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
						url: "/ajax/workflow/medical/accept-medical/"+$(this).data('medical-id'),
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
                        $('#medicalModal').modal('hide');
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

		$('.reject-medical').click(function()
		{
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing reject this medical document",
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
						url: "/ajax/workflow/medical/reject-medical/"+$(this).data('medical-id'),
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
                        $('#medicalModal').modal('hide');
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