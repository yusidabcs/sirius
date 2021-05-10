$(document).ready(function()
{
	$('.select2').select2({
        width: '100%',
		minimumResultsForSearch: Infinity
    });
	$('.select2-with-search').select2({
        width: '100%'
    });
    const showLoadingModal = function() {
		Swal.fire({
			title: 'Loading...',
			icon: 'info',
			allowOutsideClick: false,
			allowEscapeKey: false,
			showConfirmButton: false
		});
		Swal.showLoading();
	}
	const hideLoadingModal = function() {
		Swal.hideLoading();
	}

	/*$('#cron_timing').materialSelect();
	$('#template_name').materialSelect();
	$('#collection').materialSelect();
	$('#campaign').materialSelect();

	$('#cron_timing_edit').materialSelect();
	$('#template_name_edit').materialSelect();
	$('#collection_edit').materialSelect();
	$('#campaign_edit').materialSelect();*/

	const table = $('#list_reminder').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/send_email/reminder/list",
			"type": "POST",
			cache: false
		},
		"columns": [
			{"data": 'title'},
			{"data": "campaign_name"},
			{"data": 'cron_timing'},
			{"data": 'is_active'},
			{"data": 'last_run'},
			{"data": null}
		],
		"columnDefs": [
			{
				"render": function (data, type, row) {
					if (row.is_active == 1) {
						return '<span class="badge badge-success">Active</span>';
					} else {
						return '<span class="badge badge-danger">Not Active</span>';
					}
				},
				"targets": 3
			},


			{
				"render": function (data, type, row) {

					var html = `<a class="text-primary edit-reminder mr-2" data-reminder="${row.reminder_id}" href="#" data-placement="bottom" title="Edit reminder"><i class="fa fa-edit"></i></a>`;

					html += `<a class="text-danger delete-reminder mr-2" data-reminder="${row.reminder_id}" href="#" data-placement="bottom" title="Delete reminder"><i class="fa fa-trash"></i></a>`;
					if (row.is_active == 0) {
						html += `<a class ="text-success activate-reminder mr-2" data-reminder="${row.reminder_id}" href="#" data-placement="bottom" title="Activate Reminder"><i class="fas fa-check-circle"></i></a>`
					} else {
						html += `<a class ="text-danger deactivate-reminder mr-2" data-reminder="${row.reminder_id}" href="#" data-placement="bottom" title="Deactivate Reminder"><i class="fas fa-times"></i></a>`
						
					}


					return html;
				},
				"targets": -1
			}
		],
	});

	$('#select_campaign, #select_collection').on('click', function(e) {
		if (e.target.value === 'campaign') {
			$('#collection_wrapper').addClass('d-none');
			$('#campaign_wrapper').removeClass('d-none');
		} else {
			$('#collection_wrapper').removeClass('d-none');
			$('#campaign_wrapper').addClass('d-none');
		}
	});

	$('#select_campaign_edit, #select_collection_edit').on('click', function(e) {
		if (e.target.value === 'campaign') {
			$('#collection_wrapper_edit').addClass('d-none');
			$('#campaign_wrapper_edit').removeClass('d-none');
		} else {
			$('#collection_wrapper_edit').removeClass('d-none');
			$('#campaign_wrapper_edit').addClass('d-none');
		}
	});

	$(document).on('click', '.activate-reminder', function(e) {
		e.preventDefault();
		Swal.fire({
            title: 'Are you sure?',
            text: "After reminder activated it will start the background process",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
			if (result.value) {
				showLoadingModal();
				$.ajax({
					method: 'POST',
					url: '/ajax/send_email/reminder/activate/'+$(this).data('reminder'),
					success: function(response) {
						if (response.status === 'success') {
							Swal.fire({
								title: 'Information',
								text: response.message,
								icon: 'info'
							});
						}
						hideLoadingModal();
						table.ajax.reload();
					},
					error: function() {
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

	$(document).on('click', '.deactivate-reminder', function(e) {
		e.preventDefault();
		Swal.fire({
            title: 'Are you sure?',
            text: "After reminder activated it will stop the background process",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
			if (result.value) {
				showLoadingModal();
				$.ajax({
					method: 'POST',
					url: '/ajax/send_email/reminder/deactivate/'+$(this).data('reminder'),
					success: function(response) {
						if (response.status === 'success') {
							Swal.fire({
								title: 'Information',
								text: response.message,
								icon: 'info'
							});
						}
						hideLoadingModal();
						table.ajax.reload();
					},
					error: function() {
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

	$(document).on('click', '.delete-reminder', function(e) {
		e.preventDefault();
		Swal.fire({
            title: 'Are you sure?',
            text: "After reminder deleted, it cannot be restored ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) =>  {
			if (result.value) {
				showLoadingModal();
				$.ajax({
					method: 'POST',
					url: '/ajax/send_email/reminder/delete',
					data: {
						reminder_id: $(this).data('reminder')
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
						hideLoadingModal();
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

	$(document).on('click', '.edit-reminder', function(e) {
		e.preventDefault();

		$.ajax({
			method: 'POST',
			url: '/ajax/send_email/reminder/edit/'+$(this).data('reminder'),
			success: function(response) {
				var editForm = $('#editReminder');

				editForm.find('input[name="reminder_id"]').val(response.id);
				var title = editForm.find('input[name="title"]');
				var cron_timing = editForm.find('select[name="cron_timing"]');
				var campaign = editForm.find('select[name="campaign"]');
				
				title.val(response.title);
				title.trigger('change');

				cron_timing.val(response.cron_timing);
				cron_timing.trigger('change');


				campaign.val(response.campaign_id);
				campaign.trigger('change');

				$('#editReminderModal').modal('show');
			},
			error: function(error) {
				Swal.fire({
					title: 'Information',
					text: 'Something went wrong, please contact admin support',
					icon: 'error'
				});
			}
		});
	});

	$('#editReminder').on('submit', function(e) {
		e.preventDefault();
		var btn = $(this).find('button[type="submit"]');

		btn.addClass('disabled');
		btn.html('Saving....');
		var editForm = $(this);

		var title = editForm.find('input[name="title"]');
		var cron_timing = editForm.find('select[name="cron_timing"]');
		var campaign = editForm.find('select[name="campaign"]');

		$.ajax({
			url: '/ajax/send_email/reminder/update/'+editForm.find('input[name="reminder_id"]').val(),
			method: 'POST',
			data: {
				'title': title.val(),
				'cron_time': cron_timing.val(),
				'campaign_id': campaign.val()
			},
			success: function(response) {
				Swal.fire('info', response.message);

				btn.removeClass('disabled');
				btn.html('Save');
				$('#editReminderModal').modal('hide');
				
				table.ajax.reload();

				title.val('');
				cron_timing.val('');
				campaign.val('');

				/*cron_timing.materialSelect({
					destroy: true
				});
				cron_timing.materialSelect();
				campaign.materialSelect({
					destroy: true
				});
				campaign.materialSelect();*/
				cron_timing.trigger('change');
				campaign.trigger('change');

			},
			error: function() {
				btn.removeClass('disabled');
				btn.html('Save');

			}
		});
	});

	$('#addReminder').on('click', function(e) {
		e.preventDefault();
		var btn = $(this);

		btn.addClass('disabled');
		btn.html('Adding Reminder....');

		var title = $('input[name="title"]');
		var template_name = $('select[name="template_name"]');
		var cron_timing = $('select[name="cron_timing"]');
		var campaign = $('#campaign');

		$.ajax({
			url: '/ajax/send_email/reminder/add',
			method: 'POST',
			data: {
				'title': title.val(),
				'template_name': template_name.val(),
				'cron_time': cron_timing.val(),
				'campaign_id': campaign.val()
			},
			success: function(response) {
				Swal.fire('info', response.message);
				btn.removeClass('disabled');
				btn.html('Add Reminder');
				$('#addReminderModal').modal('hide');

				table.ajax.reload();

				title.val('');
				cron_timing.val('');
				campaign.val('');

				/*cron_timing.materialSelect({
					destroy: true
				});
				cron_timing.materialSelect();

				campaign.materialSelect({
					destroy: true
				});
				campaign.materialSelect();*/
				cron_timing.trigger('change');
				campaign.trigger('change');

			},
			error: function() {
				btn.removeClass('disabled');
				btn.html('Add Reminder');

			}
		});
	});


});