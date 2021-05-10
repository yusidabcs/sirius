$(document).ready(function()
{
	$('.select2').select2({
        width: '100%',
		minimumResultsForSearch: Infinity
    });
	$('.select2-with-search').select2({
        width: '100%'
    });

	const table = $('#list_subscriber').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/send_email/subscriber/list",
			"type": "POST",
			data: function(d) {
				d.collection_id = $('#collection_filter').val(),
				d.status = $('#status_filter').val()
			},
			cache: false
		},
		"columns": [
			{"data": 'email'},
			{"data": 'full_name'},
			{"data": 'status'},
			{"data": 'collection_name'},
			{"data": null}
		],
		"columnDefs": [
			{
				"render": function (data, type, row) {
					return row.email;
				},
				"targets": 0
			},
			{
				"render": function (data, type, row) {
					return row.full_name
				},
				"targets": 1
			},
			{
				"render": function (data, type, row) {
					if (row.status == '1')
						return `<span class="text-success">Active</span>`
					else if(row.status == '0')
						return `<span class="text-secondary">Disabled</span>`
				},
				"targets": 2
			},
			{
				"render": function(_, _, row) {
					return row.collection_name
				}
			},


			{
				"render": function (data, type, row) {

					var html = `<a class="text-danger delete-subscriber" data-email="${row.email}" href="#" data-placement="bottom" title="Delete subscriber"><i class="fa fa-trash"></i></a>`;

					if (row.status == 1) {
						html += `&nbsp;<a class="text-warning disable-subscriber" data-email="${row.email}" href="#" data-placement="bottom" title="Disable subscriber"><i class="fa fa-ban"></i></a>`
					}

					if (row.status == 0) {
						html += `&nbsp;<a class="text-success activate-subscriber" data-email="${row.email}" href="#" data-placement="bottom" title="Activate subscriber"><i class="fa fa-check"></i></a>`
					}

					return html;
				},
				"targets": -1
			}
		],
	});

	$('#subscribers').materialSelect();
	$('#template_name').materialSelect();
	$('#collection_id').materialSelect();
	$('#collection_filter').materialSelect();
	$('#status_filter').materialSelect();

	$(document).on('click', '.delete-subscriber', function(e) {
		e.preventDefault();
		Swal.fire({
            title: 'Are you sure?',
            text: "After subscriber deleted, it cannot be restored ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) =>  {
			if (result.value) {
				$.ajax({
					method: 'POST',
					url: '/ajax/send_email/subscriber/delete',
					data: {
						email: $(this).data('email')
					},
					success: function(response) {
						if (response.status === 'success') {
							Swal.fire({
								title: 'Information',
								text: response.message,
								icon: 'info'
							});
						}
						table.ajax.reload();
					},
					error: function(error) {
						Swal.fire({
							title: 'Information',
							text: 'Something went wrong, please contact admin support',
							icon: 'error'
						});
					}
				})
			}
		})
	});

	$(document).on('click', '.disable-subscriber', function(e) {
		e.preventDefault();
		Swal.fire({
            title: 'Are you sure?',
            text: "Disable this subscriber?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) =>  {
			if (result.value) {
				$.ajax({
					method: 'POST',
					url: '/ajax/send_email/subscriber/disable',
					data: {
						email: $(this).data('email')
					},
					success: function(response) {
						if (response.status === 'success') {
							Swal.fire({
								title: 'Information',
								text: response.message,
								icon: 'info'
							});
						}
						table.ajax.reload();
					},
					error: function(error) {
						Swal.fire({
							title: 'Information',
							text: 'Something went wrong, please contact admin support',
							icon: 'error'
						});
					}
				})
			}
		})
	});

	$(document).on('click', '.activate-subscriber', function(e) {
		e.preventDefault();
		Swal.fire({
            title: 'Are you sure?',
            text: "Activate this subscriber?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) =>  {
			if (result.value) {
				$.ajax({
					method: 'POST',
					url: '/ajax/send_email/subscriber/activate',
					data: {
						email: $(this).data('email')
					},
					success: function(response) {
						if (response.status === 'success') {
							Swal.fire({
								title: 'Information',
								text: response.message,
								icon: 'info'
							});
						}
						table.ajax.reload();
					},
					error: function(error) {
						Swal.fire({
							title: 'Information',
							text: 'Something went wrong, please contact admin support',
							icon: 'error'
						});
					}
				})
			}
		})
	});
	
	$(document).on('submit', '#importForm', function(e) {
		e.preventDefault();
		var formData = new FormData();
		var file_input = $('input[name=import_file]');
		var collection_id = $('select[name=collection_id]');
		var new_collection = $(document).find('input[name=new_collection]');
		var btnSubmit = $('#btnSubmit');

		formData.append('import_file', file_input[0].files[0]);
		formData.append('collection_id', collection_id.val());
		formData.append('new_collection', new_collection.val() || '');
		
		btnSubmit.addClass('disabled');
		btnSubmit.html('Importing.....');

		$.ajax({
			url: '/ajax/send_email/subscriber/import',
			method: 'POST',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			success: function(response) {
				Swal.fire({
					title: 'Information',
					text: response.message,
					icon: 'info'
				});
				table.ajax.reload();
				$('#modalImport').modal('hide');
				
				btnSubmit.removeClass('disabled');
				btnSubmit.html('Import');
				file_input.val('');
				collection_id.val('');
				new_collection.val('');
				$('#newCollection').addClass('d-none');

				// $('#collection_id').materialSelect({
				// 	destroy: true
				// });
				// $('#collection_id').materialSelect();
			},
			error: function() {
				Swal.fire({
					title: 'Information',
					text: 'Something went wrong, please contact admin support',
					icon: 'error'
				});

				btnSubmit.removeClass('disabled');
				btnSubmit.html('Import');
				file_input.val('');
			}
		});
	});

	$('#sendEmail').on('click', function(e) {
		e.preventDefault();

		$(this).addClass('disabled');
		$(this).html('Sending....');

		var subject = $('input[name="subject"]');
		var title = $('input[name="title"]');
		var subscribers = $('select[name="subscribers[]"]');
		var template_name = $('select[name="template_name"]');

		$.ajax({
			url: '/ajax/send_email/subscriber/send',
			method: 'POST',
			data: {
				'subject': subject.val(),
				'title': title.val(),
				'subscribers': subscribers.val(),
				'template_name': template_name.val()
			},
			success: function(response) {
				Swal.fire('info', response.message);
				$(this).removeClass('disabled');
				$(this).html('Send Email');
				$('#sendEmailModal').modal('hide');

				subject.val('');
				title.val('');
				subscribers.val('');
				template_name.val('');

			},
			error: function() {
				$(this).removeClass('disabled');
				$(this).html('Send Email');

			}
		})
	})

	$('#collection_id').on('change', function(e) {
		if ($(this).val() == -1) {
			$('#newCollection').removeClass('d-none');
		} else {
			$('#newCollection').addClass('d-none');
		}
	});

	$('#collection_filter,#status_filter').on('change', function() {
		table.ajax.reload();
	});


});