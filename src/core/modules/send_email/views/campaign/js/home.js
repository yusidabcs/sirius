$(document).ready(function()
{
	$('.select2').select2({
        width: '100%',
		minimumResultsForSearch: Infinity
    });
	$('.select2-with-search').select2({
        width: '100%'
    });
	$('#emails').select2({
        width: '100%',
        multiple: true,
        placeholder: "Select Subscribers",
        allowClear: true
    });

	const table = $('#list_campaign').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/send_email/campaign/list",
			"type": "POST",
			cache: false
		},
		"columns": [
			{"data": 'name'},
			{"data": 'email_template'},
			{"data": 'collection_name'},
			{"data": 'total_trackers'},
			{"data": 'status'},
			{"data": 'collection_name'},
			{"data": 'created_on'},
			{"data": null}
		],
		"columnDefs": [
			{
				"render": function(data, type, row) {
					return row.collection_name ?? 'No Collection'
				},
				"targets": 2
			},
			{
				"render": function(data, type, row) {
					var openRate = parseInt(row.open_rate) + '%';

					if (row.status === 'pending') {
						return '<span class="badge badge-secondary">Pending</span><br> <small>Open Rate: '+openRate+'</small>';
					} else if(row.status === 'active') {
						return '<span class="badge badge-success">Active</span><br> <small>Open Rate: '+openRate+'</small>';
					} else if(row.status === 'draf') {
						return '<span class="badge badge-warning">Draf</span> <br> <small>Open Rate: '+openRate+'</small>';
					} else {
						return '<span class="badge badge-primary">Finish</span> <br> <small>Open Rate: '+openRate+'</small>';
					}
				},
				"targets": 4
			},


			{
				"render": function (data, type, row) {

					var html = `<a class="text-success edit-campaign" data-campaign="${row.campaign_id}" href="#" data-placement="bottom" title="Edit campaign"><i class="fa fa-edit"></i></a>
					<a class="text-danger delete-campaign" data-campaign="${row.campaign_id}" href="#" data-placement="bottom" title="Delete subscriber"><i class="fa fa-trash"></i></a>`;

					html += `&nbsp;<a class="text-primary detail-campaign" data-campaign="${row.campaign_id}" href="#" data-placement="bottom" title="See Details"><i class="fa fa-file"></i></a>`;

					if (row.status === 'draf' || row.status === 'pending') {
						html += `&nbsp;<a class="text-success activate-campaign" data-campaign="${row.campaign_id}" href="#" data-placement="bottom" title="Activate Campaign"><i class="fa fa-check-circle"></i></a>`;
					}

					if (row.status === 'active') {
						html += `&nbsp;<a class="text-warning draf-campaign" data-campaign="${row.campaign_id}" href="#" data-placement="bottom" title="Draf Campaign"><i class="fa fa-times"></i></a>`;
					}


					return html;
				},
				"targets": -1
			}
		],
	});

	function trackerCampaignTable(campaign_id) {

		return $('#tracker_list').DataTable({
			"processing": true,
			"serverSide": true,
			'responsive': true,
			"ajax": {
				"url": "/ajax/send_email/campaign/listTracker/" + campaign_id,
				"type": "POST",
				data: function(d) {
					d.status = $('#status_filter').val()
				},
				cache: false
			},
			"columns": [
				{"data": 'email'},
				{"data": 'subject'},
				{"data": 'tracker_code'},
				{"data": 'status'},
				{"data": 'updated_on'}
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
						return row.subject
					},
					"targets": 1
				},
				{
					"render": function (data, type, row) {
						return row.tracker_code
					},
					"targets": 2
				},
				{
					"render": function (data, type, row) {
						return row.status
					},
					"targets": 3
				},
				{
					"render": function (data, type, row) {
						if (row.updated_on === '0000-00-00 00:00:00') {
							return '-';
						}
						return moment(row.updated_on).format('DD MMM YYYY, hh:mm a')
					},
					"targets": 4
				}
			],
		});
	}

	/*$('#email_template').materialSelect();
	$('#edit_email_template').materialSelect();
	$('#emails').materialSelect();
	$('#emails_edit').materialSelect();
	$('#status_filter').materialSelect();
	$('#collection').materialSelect();
	$('#status').materialSelect();
	$('#status_edit').materialSelect();*/

	$(document).on('click', '.delete-campaign', function(e) {
		e.preventDefault();
		Swal.fire({
            title: 'Are you sure?',
            text: "After campaign deleted, it cannot be restored also all trackers will be deleted to",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) =>  {
			if (result.value) {
				$.ajax({
					method: 'POST',
					url: '/ajax/send_email/campaign/delete',
					data: {
						campaign_id: $(this).data('campaign')
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

	$(document).on('click', '.edit-campaign', function(e) {
		e.preventDefault();

		$.ajax({
			method: 'POST',
			url: '/ajax/send_email/campaign/edit',
			data: {
				campaign_id: $(this).data('campaign')
			},
			success: function(response) {
				var editForm = $('#editCampaign');
				var campaign_name = editForm.find('input[name="name"]');
				var email_template = editForm.find('select[name="email_template"]');
				var campaign_id = editForm.find('input[name="campaign_id"]');
				var status = editForm.find('select[name="status"]');
				
				campaign_name.val(response.campaign.name);
				email_template.val(response.campaign.email_template);
				campaign_id.val(response.campaign.campaign_id);
				status.val(response.campaign.status);

				campaign_name.focus();
				email_template.trigger('change');
				status.trigger('change');
				$('#editCampaignModal').modal('show');


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

	$(document).on('click', '.detail-campaign', function(e) {
		e.preventDefault();

		if ($.fn.dataTable.isDataTable($('#tracker_list'))) {
			$('#tracker_list').DataTable().destroy();
		}

		$('#campaignDetailModal').modal('show');

		const tracker_table = trackerCampaignTable($(this).data('campaign'));

		$('#status_filter').on('change', function() {
			tracker_table.ajax.reload();
		});
	});

	$('#addCampaign').on('click', function(e) {
		e.preventDefault();
		var btn = $(this)

		btn.addClass('disabled');
		btn.html('Adding....');

		var name = $('input[name="name"]');
		var email_template = $('select[name="email_template"]');
		var emails = $('select[name="emails[]"]');
		var collection = $('select[name="collection"]');
		var status = $('select[name="status"]');

		$.ajax({
			url: '/ajax/send_email/campaign/add',
			method: 'POST',
			data: {
				'name': name.val(),
				'email_template': email_template.val(),
				'emails': emails.val(),
				'source_type': $('input[name="source_type"]:checked').val(),
				'collection': collection.val(),
				'status': status.val()
			},
			success: function(response) {
				Swal.fire('info', response.message);
				btn.removeClass('disabled');
				btn.html('Add new campaign');

				$('#addCampaignModal').modal('hide');

				table.ajax.reload();

				name.val('');
				email_template.val('').trigger('change');
				collection.val('').trigger('change');
				emails.val('').trigger('change');
				status.val('').trigger('change');
				$('#select_collection').attr('checked', true).click();

				/*$('#collection').materialSelect({
					destroy: true
				});
				$('#collection').materialSelect();

				$('#emails').materialSelect({
					destory: true
				});
				$('#emails').materialSelect();

				$('#email_template').materialSelect({
					destroy: true
				});
				$('#email_template').materialSelect();

				$('#status').materialSelect({
					destroy: true
				});
				$('#status').materialSelect();
				*/

			},
			error: function() {
				$(this).removeClass('disabled');
				$(this).html('Add new campaign');

			}
		});
	});

	$('#editCampaign').on('submit', function(e) {
		e.preventDefault();
		var btn = $(this).find('button[type="submit"]');

		btn.addClass('disabled');
		btn.html('Saving....');

		var campaign_name = $(this).find('input[name="name"]');
		var email_template = $(this).find('select[name="email_template"]');
		var campaign_id = $(this).find('input[name="campaign_id"]');
		var status = $(this).find('select[name="status"]');

		$.ajax({
			url: '/ajax/send_email/campaign/update',
			method: 'POST',
			data: {
				'campaign_id': campaign_id.val(),
				'name': campaign_name.val(),
				'email_template': email_template.val(),
				'status': status.val()
			},
			success: function(response) {
				Swal.fire('info', response.message);

				btn.removeClass('disabled');
				btn.html('Save');
				$('#editCampaignModal').modal('hide');

				btn.removeClass('disabled');
				btn.html('Add campaign');
				
				table.ajax.reload();

				campaign_name.val('');
				email_template.val('').trigger('change');

			},
			error: function() {
				btn.removeClass('disabled');
				btn.html('Save');

			}
		});
	});

	$('input[name="source_type"]').on('change', function() {
		var collection_wrapper = $('#collection_wrapper');
		var email_wrapper = $('#email_wrapper');

		if ($(this).val() === 'collection') {
			collection_wrapper.addClass('d-block');
			collection_wrapper.removeClass('d-none');

			email_wrapper.removeClass('d-block');
			email_wrapper.addClass('d-none');
		} else {
			collection_wrapper.removeClass('d-block');
			collection_wrapper.addClass('d-none');

			email_wrapper.addClass('d-block');
			email_wrapper.removeClass('d-none');
		}
	});

	$(document).on('click', '.activate-campaign', function(e) {
		e.preventDefault();

		Swal.fire({
            title: 'Activate campaign?',
            text: "After campaign activate, email blast will start",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) =>  {
			if (result.value) {
				$.ajax({
					method: 'POST',
					url: '/ajax/send_email/campaign/activate',
					data: {
						campaign_id: $(this).data('campaign')
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
	})

	$(document).on('click', '.draf-campaign', function(e) {
		e.preventDefault();

		Swal.fire({
			title: 'Draf campaign?',
			text: "After campaign draf, email blast will stop",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes'
		}).then((result) =>  {
			if (result.value) {
				$.ajax({
					method: 'POST',
					url: '/ajax/send_email/campaign/draf',
					data: {
						campaign_id: $(this).data('campaign')
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
	})


});