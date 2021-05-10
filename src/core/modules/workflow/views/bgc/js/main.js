$(document).ready(function () {

    const current = new Date();

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

    const table = $('#list_bgc').DataTable({
            "processing": true,
            "serverSide": true,
            'responsive': true,
            "ajax": {
                "url": "/ajax/workflow/bgc/list",
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
                        if (data == 'send_notification' && row.send_notification_on != '0000-00-00 00:00:00'){
                            return data + `<br><span class="badge badge-info">Send notification on: ${row.notification_on}`
                        }

                        if (data == 'confirmed' && row.confirmed_on !== '0000-00-00 00:00:00'){
                            return data + `<br><span class="badge badge-info">Confirmed on: ${row.confirmed_on}`
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
                        if(row.status === 'pending') {
                            html += `<a  class="btn-sm btn-info btn-send-notification" href="#" title="Send Notification" ><i class="fa fa-plus"></i> Send Notification</a>`
                        }
                        else if(row.status === 'confirmed') {
                            html += `<div class="btn-group"><a  class="btn-sm btn-info btn-accept-bgc" href="#" title="Accept BGC" ><i class="fa fa-plus"></i> Accept</a><a  class="btn-sm btn-danger btn-reject-bgc" href="#" title="Reject BGC" ><i class="fa fa-plus"></i> Reject</a></div>`
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

    $(document).on('click', '.btn-review-file', function() {
        let data = table.row(this.closest('tr')).data();
        $('#bgcModal').modal('show');

        
        $.get('/ajax/workflow/bgc/file-preview/'+data.address_book_id, function(response) {
            $('#institution').html(response.institution);
            $('#doctor').html(response.doctor);
            $('#vaccination_number').html(response.vaccination_number);
            $('#vaccination_date').html(response.vaccination_date);
            $('#vaccination_expiry').html(response.vaccination_expiry);

            $('.confirm-bgc, .reject-bgc').attr('data-bgc-id', response.vaccination_id);

            $('.file-preview').html('<img src="'+response.url+'" class="img img-fluid" />');
        });
    });

    $(document).on('click', '.btn-send-notification', function(e) {
        e.preventDefault();
        let data = table.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Send BGC notification to CM?',
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
                    url: '/ajax/workflow/bgc/send-notification',
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
            title: 'Send request vaccination file check to Candidate?',
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
                    url: '/ajax/workflow/bgc/request-file',
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

    $(document).on('click', '.btn-accept-bgc', function(){
        var data = table.row(this.closest('tr')).data();
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing accept this BGC document",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{	
					$.ajax({
						url: "/ajax/workflow/bgc/accept-bgc/"+data.address_book_id,
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
                        $('#bgcModal').modal('hide');
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

		$(document).on('click', '.btn-reject-bgc', function(){
            var data = table.row(this.closest('tr')).data();
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing reject this BGC document",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{	
					$.ajax({
						url: "/ajax/workflow/bgc/reject-bgc/"+data.address_book_id,
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
                        $('#bgcModal').modal('hide');
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

    $(document).on('click','.btn-pay-invoice', function () {
        var data = table.row(this.closest('tr')).data();
        $('#pay-invoice-modal').modal('show')
        $('#pay-invoice-form').find('input[name=job_application_id]').val(data.job_application_id)
    })

});