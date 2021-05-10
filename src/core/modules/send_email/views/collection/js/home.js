$(document).ready(function()
{
	$('.select2').select2({
        width: '100%',
		minimumResultsForSearch: Infinity
    });
	$('.select2-with-search').select2({
        width: '100%'
    });
	$('#candidates').select2({
        width: '100%',
        multiple: true,
        placeholder: "Select Candidates",
        allowClear: true
    });

	$('#emails').select2({
        width: '100%',
        multiple: true,
        placeholder: "Select Subscribers",
        allowClear: true
    });
	$('#emails_edit').select2({
        width: '100%',
        multiple: true,
        placeholder: "Select Subscribers",
        allowClear: true
    });

    /*$('#table_partner_search').materialSelect();
    $('#table_status_search').materialSelect();
    $('#table_country_search').materialSelect();
    $('#table_job_category_search').materialSelect();
	$('#candidates').materialSelect();*/

	const table = $('#list_collection').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/send_email/collection/list",
			"type": "POST",
			cache: false
		},
		"columns": [
			{"data": 'name'},
			{"data": 'total_email'},
			{"data": null}
		],
		"columnDefs": [
			{
				"render": function (data, type, row) {
					return row.name;
				},
				"targets": 0
			},
			{
				"render": function (data, type, row) {
					return row.total_email;
				},
				"targets": 1
			},


			{
				"render": function (data, type, row) {
					var excludes = ['personal_not_complete', 'profile_not_complete', 'registration_submission', 'english_test'];

					var html = `<a class="text-success edit-collection" data-collection="${row.collection_id}" href="#" data-placement="bottom" title="Edit collection"><i class="fa fa-edit"></i></a>`;

					if (excludes.indexOf(row.name) === -1) {
						html += `<a class="text-danger delete-collection" data-collection="${row.collection_id}" href="#" data-placement="bottom" title="Delete collection"><i class="fa fa-trash"></i></a>`;
					}


					return html;
				},
				"targets": -1
			}
		],
	});

	function renderSelectSubscriber() {
		const select = $('#emails');
		const select2 = $('#emails_edit');

		$.ajax({
			type: 'GET',
			url: '/ajax/send_email/subscriber/all',
			success: function(response) {
				let html = '';

				response.forEach(function(item) {
					html += `<option value="${item.email}">${item.full_name}(${item.email})</option>`;
				});

				select.append(html);
				select.trigger('change');
				//select.materialSelect();
				select2.append(html);
				select2.trigger('change');
				//select2.materialSelect();
			}
		});
	}

	function filterCandidate() {
		return new Promise(function(resolve, reject) {
			$.ajax({
				type: 'POST',
				url: '/ajax/recruitment/main/all-candidate',
				data: {
					'country': $('#table_country_search').val(),
					'status': $('#table_status_search').val(),
					'job_category': $('#table_job_category_search').val()
				},
				success: function(response) {
					resolve(response)
				},
				error: function(error) {
					reject(error)
				}
			});
		});
	}

	$('.apply-filter-candidates').on('click', function(e) {
		e.preventDefault();

		const context = $(this);
		const select = $('#candidates');

		context.attr('disabled', true);
		context.text("Applying....");
		select.attr('disabled', true);

		filterCandidate().then(function(response) {
			context.removeAttr('disabled');
			context.text("Apply Filter");

			if (response.data.length === 0) {
				return;
			}

			let html = ``;
			response.data.forEach(function(item) {
				html += `<option value="${item.main_email}">${item.entity_family_name + ' ' + item.number_given_name}(${item.main_email})</option>`;
			});

			select.html(html);
			/*select.materialSelect({
				destroy: true
			});
			select.materialSelect();*/
			
			select.removeAttr('disabled');
			select.trigger('change');
		}).catch(function(error) {
			console.log(error);
			swal.fire('Warning', 'Something went wrong, please contact the administrator!');

			context.removeAttr('disabled', true);
			context.text("Apply Filter");
		});
	})

	$('#radioSubscriber, #radioCandidate').on('click', function(e) {
		if (e.target.value === 'from_subscriber') {
			$('.source-candidate').addClass('d-none');
			$('.source-subscriber').removeClass('d-none');
		} else {
			$('.source-candidate').removeClass('d-none');
			$('.source-subscriber').addClass('d-none');
		}
	});

	renderSelectSubscriber();

	$(document).on('click', '.delete-collection', function(e) {
		e.preventDefault();
		Swal.fire({
            title: 'Are you sure?',
            text: "After collection deleted, it cannot be restored ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) =>  {
			if (result.value) {
				$.ajax({
					method: 'POST',
					url: '/ajax/send_email/collection/delete',
					data: {
						collection_id: $(this).data('collection')
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

	$(document).on('click', '.edit-collection', function(e) {
		e.preventDefault();

		$.ajax({
			method: 'POST',
			url: '/ajax/send_email/collection/edit',
			data: {
				collection_id: $(this).data('collection')
			},
			success: function(response) {
				var editForm = $('#editCollection');
				var collection_name = editForm.find('input[name="collection_name"]');
				var collection_id = editForm.find('input[name="collection_id"]');
				var emails = editForm.find('select[name="emails[]"]');
				
				collection_name.val(response.collection.name);
				collection_id.val(response.collection.collection_id);

				if (response.subscribers) {
					
					emails.val(response.subscribers.map((item) => item.email));
				} else {
					emails.val([]);
				}


				collection_name.focus();

				$('#editCollectionModal').modal('show');
				emails.trigger('change');

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

	$('#editCollection').on('submit', function(e) {
		e.preventDefault();
		var btn = $(this).find('button[type="submit"]');

		btn.addClass('disabled');
		btn.html('Saving....');

		var collection_name = $(this).find('input[name="collection_name"]');
		var emails = $(this).find('select[name="emails[]"]');
		var collection_id = $(this).find('input[name="collection_id"]');

		$.ajax({
			url: '/ajax/send_email/collection/update',
			method: 'POST',
			data: {
				'collection_id': collection_id.val(),
				'collection_name': collection_name.val(),
				'emails': emails.val()
			},
			success: function(response) {
				Swal.fire('info', response.message);

				btn.removeClass('disabled');
				btn.html('Save');
				$('#editCollectionModal').modal('hide');
				
				table.ajax.reload();

				collection_name.val('');
				emails.val([]);
				/*$('#emails_edit').materialSelect({
					destroy: true
				});
				$('#emails_edit').materialSelect();*/
				$('#emails_edit').trigger('change');
			},
			error: function() {
				btn.removeClass('disabled');
				btn.html('Save');

			}
		});
	});

	$('#addCollection').on('submit', function(e) {
		e.preventDefault();
		var btn = $(this).find('button[type="submit"]');

		btn.addClass('disabled');
		btn.html('Adding Collection....');

		var collection_name = $('input[name="collection_name"]');
		var emails = $('select[name="emails[]"]');
		var candidates = $('select[name="candidates[]"]');
		var source = $('input[name="source"]:checked');

		$.ajax({
			url: '/ajax/send_email/collection/add',
			method: 'POST',
			data: {
				'collection_name': collection_name.val(),
				'emails': (source.val() === 'from_subscriber') ? emails.val():candidates.val()
			},
			success: function(response) {
				Swal.fire('info', response.message);
				btn.removeClass('disabled');
				btn.html('Add Collection');
				$('#addCollectionModal').modal('hide');

				table.ajax.reload();

				collection_name.val('');
				emails.val([]);
				candidates.val([]);
				/*$('#emails').materialSelect({
					destroy: true
				});
				$('#emails').materialSelect();*/
				$('#emails').trigger('change');
				$('#candidates').trigger('change');

			},
			error: function() {
				btn.removeClass('disabled');
				btn.html('Add Collection');

			}
		});
	});


});