$(document).on('click', '[data-toggle="lightbox"]', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});

const loadingModal = function() {
	Swal.fire({
		title: 'Loading',
		text: 'Please wait...',
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

function passportDatatable() {
	var passport;

	if ($.fn.dataTable.isDataTable($('#passport_data'))) {
		return false;
	}

	passport = $('#passport_data').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/personal/datatable/passport",
			"type": "POST",
			cache: false,
			data: function (d) {
				d.status = $('#table_status_search').val();
				d.level = $('#table_level_search').val();
			}
		},
		"columns": [
			{"data": null},
			{"data": 'From'},
			{"data": 'To'},
			{"data": 'length'},
			{"data": 'Passport'},
			{"data": null}
		],
		"initComplete": function(settings, json){ 
			var info = this.api().page.info();
			var doc = $(document);

			if (info.recordsTotal === 0) {
				doc.find('.add-visa').addClass('d-none');
				doc.find('.add-oktb').addClass('d-none');
			} else {
				doc.find('.add-visa').removeClass('d-none');
				doc.find('.add-oktb').removeClass('d-none');
			}
		},
		"rowCallback": function(row, data) {
			var ts_to = moment(data.to_date);

			if(data.active == 'not_active')
			{
				$(row).addClass('rgba-red-slight')
			} else if(ts_to.isBefore(moment().add(6, 'months'))) {
				$(row).addClass('rgba-orange-slight');
			} else {
				$(row).addClass('rgba-green-slight');
			}
		},
		"columnDefs": [
			{
				"render": function (data, type, row) {
					return `<a href="#" class="delete_passport" id="${data.passport_id}" data-passport-file="${data.filename}"><i class="far fa-trash-alt text-danger"  title="Delete Passport"></i></a>`;
				},
				"targets": 0
			},
			{
				"render": function (data, type, row) {

					return moment(row.from_date).format('MMM/YY');
				},
				"targets": 1
			},
			{
				"render": function (data, type, row) {

					return moment(row.to_date).format('MMM/YY');
				},
				"targets": 2
			},

			{
				"render": function(data, type, row) {
					let html = `<a href="/ab/show/${row.filename}" data-toggle="lightbox" data-gallery="${row.filename}" data-footer="${row.nationality} : ${row.passport_id}" data-type="image">
						<figure class="figure">
							<img src="/ab/show/${row.filename}-thumb"  class="img-fluid z-depth-1" title="${row.passport_id} Image - Click to Enlarge" alt="${row.passport_id} Image">
							<figcaption class="figure-caption text-center mt-2">
								${row.nationality} : ${row.passport_id}
							</figcaption>
						</figure>
					</a>`;

					return html;
				},
				"targets": 4
			},


			{
				"render": function (data, type, row) {

					var html = `<a href="/personal/passport/${data.passport_id}"><i class="far fa-edit text-warning" title="Edit Passport"></i></a>`;
					html += `<a class="preview_passport" data-passport-id="${data.passport_id}" href="#"><i class="far fa-file-alt text-primary" title="Preview Passport"></i></a>`;
					return html;
				},
				"targets": -1
			}
		],
	});

	$(document).on('click', '.delete_passport', function( event ) {
		
		event.preventDefault();
		var passport_id = $(this).prop('id');
		var passport_current = $(this).data('passport-file');
		
		swal.fire({
			
			title: 'Delete Pasport '+passport_id+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deletePassport', {
					passport_id: passport_id,
					passport_current: passport_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        passport.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();
					
					swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});

	$(document).on('click', '.preview_passport', function(e) {
		e.preventDefault();

		var passportModal = $('#passportModal');
		var passportId = $(this).data('passport-id');

		$.post('/ajax/personal/main/previewPassport', {
			passport_id: passportId,
		}).done(function(response) {
			var passport = response.data;
			passportModal.modal('show');

			if (passport.full_name === '' || passport.full_name === null) {
				$('#passport_full_name').parent().addClass('d-none');
				$('#passport_family_name').parent().removeClass('d-none');
				$('#passport_given_names').parent().removeClass('d-none');
			} else {
				$('#passport_family_name').parent().addClass('d-none');
				$('#passport_given_names').parent().addClass('d-none');
				$('#passport_full_name').parent().removeClass('d-none');
			}

			$('#passport_type').html(passport.type);
			$('#passport_code').html(passport.code);
			$('#passport_passport_id').html(passport.passport_id);
			$('#passport_full_name').html(passport.full_name);
			$('#passport_family_name').html(passport.family_name);
			$('#passport_given_names').html(passport.given_names);
			$('#passport_nationality').html(passport.nationality);
			$('#passport_sex').html(passport.sex);
			$('#passport_dob').html(passport.dob);
			$('#passport_pob').html(passport.pob);
			$('#passport_from_date').html(passport.from_date);
			$('#passport_to_date').html(passport.to_date);
			$('#passport_place_issued').html(passport.place_issued);
			$('#passport_authority').html(passport.authority);


		});
	});
}

function visaDatatable() {
	var visa;

	if ($.fn.dataTable.isDataTable($('#visa_data'))) {
		return false;
	}

	visa = $('#visa_data').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/personal/datatable/visa",
			"type": "POST",
			cache: false,
			data: function (d) {
				d.status = $('#table_status_search').val();
				d.level = $('#table_level_search').val();
			}
		},
		"columns": [
			{"data": null},
			{"data": 'From'},
			{"data": 'To'},
			{"data": 'Passport'},
			{"data": null}
		],
		"rowCallback":  function(row, data) {
			var ts_to = moment(data.to_date);

			if(data.active == 'not_active')
			{
				$(row).addClass('rgba-red-slight')
			} else if(ts_to.isBefore(moment().add(6, 'months'))) {
				$(row).addClass('rgba-orange-slight');
			} else {
				$(row).addClass('rgba-green-slight');
			}
		},
		"columnDefs": [
			{
				"render": function (data, type, row) {
					return `<a href="#" class="delete_visa" id="${data.visa_id}" data-visa-file="${data.filename}"><i class="far fa-trash-alt text-danger"  title="Delete Passport"></i></a>`;
				},
				"targets": 0
			},
			{
				"render": function (data, type, row) {

					return moment(row.from_date).format('MMM/YY');
				},
				"targets": 1
			},
			{
				"render": function (data, type, row) {

					return moment(row.to_date).format('MMM/YY');
				},
				"targets": 2
			},

			{
				"render": function(data, type, row) {
					let html = `<a href="/ab/show/${row.filename}" data-toggle="lightbox" data-gallery="${row.filename}" data-footer="${row.place_issued} : ${row.visa_id}" data-type="image">
						<figure class="figure">
							<img src="/ab/show/${row.filename}-thumb"  class="img-fluid z-depth-1" title="${row.visa_id} Image - Click to Enlarge" alt="${row.visa_id} Image">
							<figcaption class="figure-caption text-center mt-2">
								${row.place_issued} : ${row.visa_id}
							</figcaption>
						</figure>
					</a>`;

					return html;
				},
				"targets": 3
			},


			{
				"render": function (data, type, row) {

					var html = `<a href="/personal/visa/${data.visa_id}"><i class="far fa-edit text-warning" title="Edit Passport"></i></a>`;
					html += `<a class="preview_visa" data-visa-id="${data.visa_id}" href="#"><i class="far fa-file-alt text-primary" title="Preview Passport"></i></a>`;
					return html;
				},
				"targets": -1
			}
		],
	});

	$(document).on('click', '.delete_visa', function( event ) {
		
		event.preventDefault();
		var visa_id = $(this).prop('id');
		var visa_current = $(this).data('visa-file');
		
		swal.fire({
			
			title: 'Delete visa '+visa_id+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteVisa', {
					visa_id: visa_id,
					visa_current: visa_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        visa.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();
					
					swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});

	$(document).on('click', '.preview_visa', function(e) {
		e.preventDefault();

		var visaModal = $('#visaModal');
		var visaId = $(this).data('visa-id');

		$.post('/ajax/personal/main/previewVisa', {
			visa_id: visaId,
		}).done(function(response) {
			var visa = response.data;
			visaModal.modal('show');

			if (visa.full_name === '' || visa.full_name === null) {
				$('#visa_full_name').parent().addClass('d-none');
				$('#visa_family_name').parent().removeClass('d-none');
				$('#visa_given_names').parent().removeClass('d-none');
			} else {
				$('#visa_family_name').parent().addClass('d-none');
				$('#visa_given_names').parent().addClass('d-none');
				$('#visa_full_name').parent().removeClass('d-none');
			}

			$('#visa_type').html(visa.type);
			$('#visa_code').html(visa.code);
			$('#visa_class').html(visa.class);
			$('#visa_visa_id').html(visa.visa_id);
			$('#visa_place_issued').html(visa.place_issued);
			$('#visa_entry').html(visa.entry);
			$('#visa_full_name').html(visa.full_name);
			$('#visa_family_name').html(visa.family_name);
			$('#visa_given_names').html(visa.given_names);
			$('#visa_from_date').html(visa.from_date);
			$('#visa_to_date').html(visa.to_date);
			$('#visa_authority').html(visa.authority);
			$('#visa_passport_id').html(visa.passport_id);


		});
	});
}

function oktbDatatable() {
	var oktb;

	if ($.fn.dataTable.isDataTable($('#oktb_data'))) {
		return false;
	}

	oktb = $('#oktb_data').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/personal/datatable/oktb",
			"type": "POST",
			cache: false,
			data: function (d) {
				d.status = $('#table_status_search').val();
				d.level = $('#table_level_search').val();
			}
		},
		"columns": [
			{"data": null},
			{"data": 'From'},
			{"data": 'To'},
			{"data": 'Passport'},
			{"data": null}
		],
		"columnDefs": [
			{
				"render": function (data, type, row) {
					return `<a href="#" class="delete_oktb" id="${data.oktb_number}" data-oktb-file="${data.filename}"><i class="far fa-trash-alt text-danger"  title="Delete Passport"></i></a>`;
				},
				"targets": 0
			},
			{
				"render": function (data, type, row) {

					return moment(row.date_of_issue).format('MMM/YY');
				},
				"targets": 1
			},
			{
				"render": function (data, type, row) {

					return moment(row.valid_until).format('MMM/YY');
				},
				"targets": 2
			},

			{
				"render": function(data, type, row) {
					let html = `No File for <br /><strong>${row.authority}: ${row.oktb_number}</strong>`;

					if (row.filename) {
						html = `<a href="/ab/show/${row.filename}" class="btn btn-sm btn-primary" target="_blank">
							Preview File
						</a><br>
						<span>Oktb Number : ${row.oktb_number}</span>`;
					}

					return html;
				},
				"targets": 3
			},


			{
				"render": function (data, type, row) {

					var html = `<a href="/personal/oktb/${data.oktb_number}"><i class="far fa-edit text-warning" title="Edit Oktb"></i></a>`;
					html += `<a class="preview_oktb" data-oktb-id="${data.oktb_number}" href="#"><i class="far fa-file-alt text-primary" title="Preview Oktb"></i></a>`;
					return html;
				},
				"targets": -1
			}
		],
	});

	$(document).on('click', '.delete_oktb', function( event ) {
		
		event.preventDefault();
		var oktb_id = $(this).prop('id');
		var oktb_current = $(this).data('oktb-file');
		
		swal.fire({
			
			title: 'Delete oktb '+oktb_id+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteOktb', {
					oktb_id: oktb_id,
					oktb_current: oktb_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        oktb.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();
					
					swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});

	$(document).on('click', '.preview_oktb', function(e) {
		e.preventDefault();

		var oktbModal = $('#oktbModal');
		var oktbId = $(this).data('oktb-id');

		$.post('/ajax/personal/main/previewOktb', {
			oktb_id: oktbId,
		}).done(function(response) {
			var oktb = response.data;
			oktbModal.modal('show');

			$('#oktb_type').html(oktb.oktb_type);
			$('#oktb_number').html(oktb.oktb_number);
			$('#oktb_date_of_issue').html(oktb.date_of_issue);
			$('#oktb_valid_until').html(oktb.valid_until);


		});
	});
}

function idsDatatable() {
	var ids;

	if ($.fn.dataTable.isDataTable($('#ids_data'))) {
		return false;
	}

	ids = $('#ids_data').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/personal/datatable/ids",
			"type": "POST",
			cache: false,
			data: function (d) {
				d.status = $('#table_status_search').val();
				d.level = $('#table_level_search').val();
			}
		},
		"columns": [
			{"data": null},
			{"data": 'From'},
			{"data": 'To'},
			{"data": 'Number'},
			{"data": 'Id Card'},
			{"data": null}
		],
		"rowCallback": function(row, data) {
			var ts_to = moment(data.to_date);
			var ts_to_timestamp = moment(data.to_date).format('X');

			if(data.active == 'not_active')
			{
				$(row).addClass('rgba-red-slight')
			} else if(ts_to.isBefore(moment().add(6, 'months')) && (ts_to_timestamp != 0)) {
				$(row).addClass('rgba-orange-slight');
			} else {
				$(row).addClass('rgba-green-slight');
			}
		},
		"columnDefs": [
			{
				"render": function (data, type, row) {
					return `<a href="#" class="delete_ids" id="${data.idcard_id}" data-ids-file="${data.filename}" data-ids-back-file="${data.filename_back}"><i class="far fa-trash-alt text-danger"  title="Delete Passport"></i></a>`;
				},
				"targets": 0
			},
			{
				"render": function (data, type, row) {

					return moment(row.from_date).format('MMM/YY');
				},
				"targets": 1
			},
			{
				"render": function (data, type, row) {
					if (row.to_date === '0000-00-00') {
						return 'Life time';
					}

					return moment(row.to_date).format('MMM/YY');
				},
				"targets": 2
			},

			{
				"render": function (data, type, row) {

					return row.idcard_orig;
				},
				"targets": 3
			},

			{
				"render": function(data, type, row) {
					let html = `<a href="/ab/show/${row.filename}" data-toggle="lightbox" data-gallery="${row.filename}" data-footer="${row.authority} : ${row.idcard_id}" data-type="image">
						<figure class="figure">
							<img src="/ab/show/${row.filename}-thumb"  class="img-fluid z-depth-1" title="${row.idcard_id} Image - Click to Enlarge" alt="${row.idcard_id} Image">
							<figcaption class="figure-caption text-center mt-2">
								${row.authority} : ${row.idcard_id}
							</figcaption>
						</figure>
					</a>`;

					return html;
				},
				"targets": 4
			},


			{
				"render": function (data, type, row) {

					var html = `<a href="/personal/idcard/${data.idcard_id}"><i class="far fa-edit text-warning" title="Edit ID Card"></i></a>`;
					html += `<a class="preview_ids" data-ids-id="${data.idcard_id}" href="#"><i class="far fa-file-alt text-primary" title="Preview ID Card"></i></a>`;
					return html;
				},
				"targets": -1
			}
		],
	});

	$(document).on('click', '.delete_ids', function( event ) {
		
		event.preventDefault();
		var ids_id = $(this).prop('id');
		var ids_current = $(this).data('ids-file');
		var ids_back_current = $(this).data('ids-back-file');
		
		swal.fire({
			
			title: 'Delete Id Card '+ids_id+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteIDCard', {
					idcard_id: ids_id,
					idcard_current: ids_current,
					idcard_back_current: ids_back_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        ids.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();
					
					swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});

	$(document).on('click', '.preview_ids', function(e) {
		e.preventDefault();

		var idsModal = $('#idCardModal');
		var idsId = $(this).data('ids-id');

		$.post('/ajax/personal/main/previewIdCard', {
			id_card: idsId,
		}).done(function(response) {
			var ids = response.data;
			idsModal.modal('show');

			if (ids.full_name === '' || ids.full_name === null) {
				$('#id_card_full_name').parent().addClass('d-none');
				$('#id_card_family_name').parent().removeClass('d-none');
				$('#id_card_given_names').parent().removeClass('d-none');
			} else {
				$('#id_card_family_name').parent().addClass('d-none');
				$('#id_card_given_names').parent().addClass('d-none');
				$('#id_card_full_name').parent().removeClass('d-none');
			}

			if (ids.to_date === '0000-00-00' || ids.to_date == '') {
				ids.to_date = 'Life time';
			}

			$('#id_card_type').html(ids.type);
			$('#id_card_code').html(ids.code);
			$('#id_card_number').html(ids.idcard_orig);
			$('#id_card_full_name').html(ids.full_name);
			$('#id_card_family_name').html(ids.family_name);
			$('#id_card_given_names').html(ids.given_names);
			$('#id_card_nationality').html(ids.nationality);
			$('#id_card_from').html(ids.from_date);
			$('#id_card_to').html(ids.to_date);
			$('#id_card_authority').html(ids.authority);


		});
	});
}

function idcheckDatatable() {
	var idcheck;

	if ($.fn.dataTable.isDataTable($('#idcheck_data'))) {
		return false;
	}

	idcheck = $('#idcheck_data').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/personal/datatable/idcheck",
			"type": "POST",
			cache: false,
			data: function (d) {
				d.status = $('#table_status_search').val();
				d.level = $('#table_level_search').val();
			}
		},
		"columns": [
			{"data": null},
			{"data": 'Date'},
			{"data": 'Country'},
			{"data": 'Id Card'},
			{"data": null}
		],
		"rowCallback": function(row, data) {
			var ts_to = moment(data.idcheck_expiry);

			if(data.active == 'not_active')
			{
				$(row).addClass('rgba-red-slight')
			} else if(ts_to.isBefore(moment().add(6, 'months'))) {
				$(row).addClass('rgba-orange-slight');
			} else {
				$(row).addClass('rgba-green-slight');
			}
		},
		"columnDefs": [
			{
				"render": function (data, type, row) {
					return `<a href="#" class="delete_idcheck" id="${data.idcheck_id}" data-idcheck-file="${data.filename}"><i class="far fa-trash-alt text-danger"  title="Delete Passport"></i></a>`;
				},
				"targets": 0
			},
			{
				"render": function (data, type, row) {

					return moment(row.idcheck_date).format('MMM/YY');
				},
				"targets": 1
			},
			{
				"render": function (data, type, row) {
					return row.countryCode_id;
				},
				"targets": 2
			},

			{
				"render": function(data, type, row) {
					let html = `<a href="/ab/show/${row.filename}" data-toggle="lightbox" data-gallery="${row.filename}" data-footer="${row.countryCode_id} : ${row.idcheck_id}" data-type="image">
						<figure class="figure">
							<img src="/ab/show/${row.filename}-thumb"  class="img-fluid z-depth-1" title="${row.idcheck_id} Image - Click to Enlarge" alt="${row.idcheck_id} Image">
							<figcaption class="figure-caption text-center mt-2">
								${row.countryCode_id} : ${row.idcheck_number}
							</figcaption>
						</figure>
					</a>`;

					return html;
				},
				"targets": 3
			},


			{
				"render": function (data, type, row) {

					var html = `<a href="/personal/idcheck/${data.idcheck_id}"><i class="far fa-edit text-warning" title="Edit ID Card"></i></a>`;
					html += `<a class="preview_idcheck" data-idcheck-id="${data.idcheck_id}" href="#"><i class="far fa-file-alt text-primary" title="Preview ID Card"></i></a>`;
					return html;
				},
				"targets": -1
			}
		],
	});

	$(document).on('click', '.delete_idcheck', function( event ) {
		
		event.preventDefault();
		var idcheck_id = $(this).prop('id');
		var idcheck_current = $(this).data('idcheck-file');
		
		swal.fire({
			
			title: 'Delete Id Check '+idcheck_id+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteIdcheck', {
					idcheck_id: idcheck_id,
					idcheck_current: idcheck_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        idcheck.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();
					
					swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});

	$(document).on('click', '.preview_idcheck', function(e) {
		e.preventDefault();

		var idcheckModal = $('#idCheckModal');
		var idcheckId = $(this).data('idcheck-id');

		$.post('/ajax/personal/main/previewIdCheck', {
			id_check: idcheckId,
		}).done(function(response) {
			var idcheck = response.data;
			idcheckModal.modal('show');

			if (idcheck.to_date === '0000-00-00' || idcheck.to_date == '') {
				idcheck.to_date = 'Life time';
			}

			$('#idcheck_institution').html(idcheck.institution);
			$('#idcheck_country').html(idcheck.countryCode_id);
			$('#idcheck_phone').html(idcheck.phone);
			$('#idcheck_website').html(idcheck.website);
			$('#idcheck_number').html(idcheck.idcheck_number);
			$('#idcheck_date').html(moment(idcheck.idcheck_date).format('DD MMM YYYY'));
			if (idcheck.idcheck_expiry == '') {
				$('#idcheck_expiry').html('No Expiry');
				
			} else {
				$('#idcheck_expiry').html(moment(idcheck.expiry).format('DD MMM YYYY'));
			}
			$('#idcheck_email').html(`<a href="mailto:${idcheck.email}<${idcheck.institution}>">${idcheck.email}</a>`);


		});
	});
}

function policeDatatable() {
	var police;

	if ($.fn.dataTable.isDataTable($('#police_data'))) {
		return false;
	}

	police = $('#police_data').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/personal/datatable/police",
			"type": "POST",
			cache: false,
			data: function (d) {
				d.status = $('#table_status_search').val();
				d.level = $('#table_level_search').val();
			}
		},
		"columns": [
			{"data": 'Police Check'},
			{"data": 'Status'},
			{"data": 'From'},
			{"data": 'To'},
			{"data": null}
		],
		"rowCallback": function(row, data) {
			var ts_to = moment(data.to_date);

			if(data.active == 'not_active')
			{
				$(row).addClass('rgba-red-slight')
			} else if(ts_to.isBefore(moment().add(6, 'months'))) {
				$(row).addClass('rgba-orange-slight');
			} else {
				$(row).addClass('rgba-green-slight');
			}
		},
		"fnInitComplete": function(settings, json) {
			if (json.mode != 'recruitment') {
				$('.validate-police').addClass('d-none');
			}
		},
		"columnDefs": [
			{
				"render": function (data, type, row) {
					return `<a href="/ab/show/${row.filename}" data-toggle="lightbox" data-gallery="${row.filename}" data-footer="${row.countryCode_id}:${row.police_id}" data-type="image">
					<figure class="figure">
						<i class="far fa-image fa-3x" ></i>
						<figcaption class="figure-caption text-center mt-2">
							${row.countryCode_id} : ${row.police_id}
						</figcaption>
					</figure>
				</a>`;
				},
				"targets": 0
			},
			{
				"render": function(data, type, row) {
					return row.status;
				},
				"targets": 1
			},
			{
				"render": function (data, type, row) {

					return moment(row.from_date).format('MMM/YY');
				},
				"targets": 2
			},
			{
				"render": function (data, type, row) {

					return moment(row.to_date).format('MMM/YY');
				},
				"targets": 3
			},

			{
				"render": function (data, type, row, meta) {

					var html = `<a href="#" class="delete_police" data-police-file="${row.filename}" id="${row.police_id}"><i class="far fa-trash-alt text-danger"  title="Delete Police"></i></a>
					<a href="#" class="validate-police" data-date="${moment().format('YYYY-MM-DD HH:mm:ss')}" data-address-book-id="${row.address_book_id}" data-police-id="${row.police_id}">
					<i class="far fa-check-circle text-info" title="Validate documents?"></i></a>`;
					html += `<a href="/personal/police/${data.police_id}"><i class="far fa-edit text-warning" title="Edit ID Card"></i></a>`;
					html += `<a class="preview_police" data-police-id="${data.police_id}" href="#"><i class="far fa-file-alt text-primary" title="Preview ID Card"></i></a>`;
					return html;
				},
				"targets": -1
			}
		],
	});

	$(document).on('click', '.delete_police', function( event ) {
		
		event.preventDefault();
		var police_id = $(this).prop('id');
		var police_current = $(this).data('police-file');
		
		swal.fire({
			
			title: 'Delete police check '+police_id+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deletePolice', {
					police_id: police_id,
					police_current: police_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        police.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();
					
					swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});

	$(document).on('click', '.preview_police', function(e) {
		e.preventDefault();

		var policeModal = $('#policeModal');
		var policeId = $(this).data('police-id');

		$.post('/ajax/personal/main/previewPolice', {
			police_id: policeId,
		}).done(function(response) {
			var police = response.data;
			policeModal.modal('show');

			$('#police_full_name').html(police.full_name);
			$('#police_nationality').html(police.nationality);
			$('#police_sex').html(police.sex[0].toUpperCase() + police.sex.slice(1));
			$('#police_dob').html(moment(police.dob).format('DD MMM YYYY'));
			$('#police_pob').html(police.pob);
			$('#police_from_date').html(moment(police.from_date).format('DD MMM YYYY'));
			$('#police_to_date').html(moment(police.to_date).format('DD MMM YYYY'));
			$('#police_place_issued').html(police.place_issued);
		});
	});

	$(document).on('click', '.validate-police', function(e) {
    	e.preventDefault();

        var btn = $(this);

        swal.fire({

            title: 'Validate this police check?',
            text: 'Are you sure to validate this police check?',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, validate it!',
            input: 'select',
            inputOptions: {
                'accepted': 'Accepted',
                'rejected': 'Rejected',
            },

        }).then((result) => {

        	if(result.value == 'rejected'){
                var data = {
                	address_book_id: btn.data('address-book-id'),
                	police_id: btn.data('police-id'),
                    status: result.value,
                    rejected_by: btn.data('user-id'),
                    rejected_on: btn.data('date'),
                };
			}else{
                var data = {
                    address_book_id: btn.data('address-book-id'),
                    police_id: btn.data('police-id'),
                    status: result.value,
                    accepted_by: btn.data('user-id'),
                    accepted_on: btn.data('date'),
                };
			}

            $.ajax({
                url: "/ajax/workflow/police/review",
                type: 'POST',
                data: data,
                cache: false,
                timeout: 10000
            })
                .done(response => {
                    Swal.fire({
                        text: result.message,
                    }).then(() => {

                        location.reload();
                    });
                });

        })
    });
}

function medicalDatatable() {
	var medical;

	if ($.fn.dataTable.isDataTable($('#medical_data'))) {
		return false;
	}

	medical = $('#medical_data').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/personal/datatable/medical",
			"type": "POST",
			cache: false,
			data: function (d) {
				d.status = $('#table_status_search').val();
				d.level = $('#table_level_search').val();
			}
		},
		"columns": [
			{"data": null},
			{"data": 'Date'},
			{"data": 'Type'},
			{"data": 'Status'},
			{"data": 'Result'},
			{"data": 'Image'},
			{"data": null}
		],
		"rowCallback": function(row, data) {

			if (data.status == 'pending') {
				$(row).addClass('rgba-orange-slight');
			} else {
				$(row).addClass('rgba-green-slight');
			}

		},
		"fnInitComplete": function(row, json) {
			if (json.mode != 'recruitment') {
				$('.confirm-medical').addClass('d-none');
				$('.reject-medical').addClass('d-none');
			}

		},
		"columnDefs": [
			{
				"render": function (data, type, row) {
					return `<a href="#" class="delete_medical" id="${data.medical_id}" data-medical-file="${data.filename}"><i class="far fa-trash-alt text-danger"  title="Delete Medical"></i></a>`;
				},
				"targets": 0
			},
			{
				"render": function (data, type, row) {

					return moment(row.certificate_date).format('MMM/YY');
				},
				"targets": 1
			},
			{
				"render": function (data, type, row) {

					return row.type;
				},
				"targets": 2
			},
			{
				"render": function (data, type, row) {
					return row.status;
				},
				"targets": 3
			},
			{
				"render": function (data, type, row) {
					return row.fit;
				},
				"targets": 4
			},

			{
				"render": function(data, type, row) {
					let html = `<a href="/ab/show/${row.filename}" data-toggle="lightbox" data-gallery="${row.filename}" data-footer="${row.institution} : ${row.medical_id}" data-type="image">
						<figure class="figure">
							<img src="/ab/show/${row.filename}-thumb"  class="img-fluid z-depth-1" title="${row.medical_id} Image - Click to Enlarge" alt="${row.medical_id} Image">
							<figcaption class="figure-caption text-center mt-2">
								${row.certificate_number}
							</figcaption>
						</figure>
					</a>`;

					return html;
				},
				"targets": 5
			},

			{
				"render": function (data, type, row) {

					var html = `<a href="/personal/medical/${data.medical_id}"><i class="far fa-edit text-warning" title="Edit Medical"></i></a>`;
					
					if (row.status == 'pending') {
						html += `<a href="#" data-medical-id="${row.medical_id}" class="confirm-medical">
						<i class="far fa-check-circle text-info" title="Confirm Medical Test"></i></a>`;
						html += `<a href="#" data-medical-id="${row.medical_id}" class="reject-medical mr-1">
						<i class="far fa-times-circle text-danger" title="Reject Medical Test"></i></a>`;
					}

					html += `<a class="preview_medical" data-medical-id="${data.medical_id}" href="#"><i class="far fa-file-alt text-primary" title="Preview Medical"></i></a>`;
					return html;
				},
				"targets": -1
			}
		],
	});

	$(document).on('click', '.delete_medical', function( event ) {
		
		event.preventDefault();
		var medical_id = $(this).prop('id');
		var medical_current = $(this).data('medical-file');
		
		swal.fire({
			
			title: 'Delete Medical '+medical_id+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteMedical', {
					medical_id: medical_id,
					medical_current: medical_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        medical.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();
					
					swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});

	$(document).on('click', '.preview_medical', function(e) {
		e.preventDefault();

		var medicalModal = $('#medicalModal');
		var medicalId = $(this).data('medical-id');

		$.post('/ajax/personal/main/previewMedical', {
			medical_id: medicalId,
		}).done(function(response) {
			var medical = response.data;
			medicalModal.modal('show');
			$('#medical_institution').html(medical.institution);
			$('#medical_doctor').html(medical.doctor);
			$('#medical_certificate_number').html(medical.certificate_number);
			$('#medical_view_certificate_date').html(moment(medical.certificate_date).format('DD MMM YYYY'));
			if (medical.certificate_expiry == "") {
				$('#medical_view_certificate_expiry').html("No Expiry");
				
			} else {
				$('#medical_view_certificate_expiry').html(moment(medical.certificate_expiry).format('DD MMM YYYY'));
			}

			$('#medical_country').html(medical.countryCode_id);
			$('#medical_phone').html(medical.phone);
			$('#medical_website').html(medical.website);


			$('#medical_email').html(`<a href="mailto:${medical.institution}<${medical.email}>">${medical.email}</a>`)

		});
	});

	$(document).on('click', '.confirm-medical', function(e) {
		e.preventDefault();

		var btn = $(this);

		swal.fire({
			
			title: 'Validate this medical?',
			text: 'Are you sure to validate this medical?',
			icon: 'warning',
			showCancelButton: true,
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, validate it!'
			
		}).then((result) => {
			if (result.value) {
				$.get('/ajax/workflow/job_application_tracker/confirm-medical/'+btn.data('medical-id'), (result) => {
					
					Swal.fire({
						icon: result.status,
						text: result.message,
					}).then(() => {

						medical.ajax.reload();
					});

					
				})
			}
		})
	});

	$(document).on('click', '.reject-medical', function(e) {
		e.preventDefault();

		var btn = $(this);

		swal.fire({
			
			title: 'Reject this medical?',
			text: 'Are you sure to reject this medical?, Once you reject it will deleted automatically',
			icon: 'warning',
			showCancelButton: true,
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, reject it!'
			
		}).then((result) => {
			if (result.value) {
				$.get('/ajax/workflow/job_application_tracker/reject-medical/'+btn.data('medical-id'), (result) => {
					
					Swal.fire({
						icon: result.status,
						text: result.message,
					}).then(() => {

						medical.ajax.reload();
					});

					
				})
			}
		})
	});
}

function vaccinationDatatable() {
	var vaccine;

	if ($.fn.dataTable.isDataTable($('#vaccine_data'))) {
		return false;
	}

	vaccine = $('#vaccine_data').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/personal/datatable/vaccine",
			"type": "POST",
			cache: false,
			data: function (d) {
				d.status = $('#table_status_search').val();
				d.level = $('#table_level_search').val();
			}
		},
		"columns": [
			{"data": null},
			{"data": 'Date'},
			{"data": 'Type'},
			{"data": 'Status'},
			{"data": 'Image'},
			{"data": null}
		],
		"rowCallback": function(row, data) {

			if (data.status == 'pending') {
				$(row).addClass('rgba-orange-slight');
			} else {
				$(row).addClass('rgba-green-slight');
			}

		},
		"fnInitComplete": function(row, json) {
			if (json.mode != 'recruitment') {
				$('.confirm-vaccination').addClass('d-none');
				$('.reject-vaccination').addClass('d-none');
			}

		},
		"columnDefs": [
			{
				"render": function (data, type, row) {
					return `<a href="#" class="delete_vaccine" id="${data.vaccination_id}" data-vaccine-file="${data.filename}"><i class="far fa-trash-alt text-danger"  title="Delete Vaccination"></i></a>`;
				},
				"targets": 0
			},
			{
				"render": function (data, type, row) {

					return moment(row.certificate_date).format('MMM/YY');
				},
				"targets": 1
			},
			{
				"render": function (data, type, row) {

					return row.type;
				},
				"targets": 2
			},
			{
				"render": function (data, type, row) {
					return row.status;
				},
				"targets": 3
			},

			{
				"render": function(data, type, row) {
					let html = `<a href="/ab/show/${row.filename}" data-toggle="lightbox" data-gallery="${row.filename}" data-footer="${row.institution} : ${row.vaccination_number}" data-type="image">
						<figure class="figure">
							<img src="/ab/show/${row.filename}-thumb"  class="img-fluid z-depth-1" title="${row.vaccination_id} Image - Click to Enlarge" alt="${row.vaccination_id} Image">
							<figcaption class="figure-caption text-center mt-2">
								${row.vaccination_number}
							</figcaption>
						</figure>
					</a>`;

					return html;
				},
				"targets": 4
			},

			{
				"render": function (data, type, row) {

					var html = `<a href="/personal/vaccination/${data.vaccination_id}"><i class="far fa-edit text-warning" title="Edit Vaccination"></i></a>`;
					
					if (row.status == 'pending') {
						html += `<a href="#" data-vaccination-id="${row.vaccination_id}" class="confirm-vaccination">
						<i class="far fa-check-circle text-info" title="Confirm Vaccination"></i></a>`;
						html += `<a href="#" data-vaccination-id="${row.vaccination_id}" class="reject-vaccination mr-1">
						<i class="far fa-times-circle text-danger" title="Reject Vaccination"></i></a>`;
					}
					
					html += `<a class="preview_vaccine" data-vaccine-id="${data.vaccination_id}" href="#"><i class="far fa-file-alt text-primary" title="Preview Vaccination"></i></a>`;
					return html;
				},
				"targets": -1
			}
		],
	});

	$(document).on('click', '.delete_vaccine', function( event ) {
		
		event.preventDefault();
		var vaccine_id = $(this).prop('id');
		var vaccine_current = $(this).data('vaccine-file');
		
		swal.fire({
			
			title: 'Delete Vaccination '+vaccine_id+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteVaccination', {
					vaccination_id: vaccine_id,
					vaccination_current: vaccine_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        vaccine.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();
					
					swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});

	$(document).on('click', '.preview_vaccine', function(e) {
		e.preventDefault();

		var vaccineModal = $('#vaccinationModal');
		var vaccineId = $(this).data('vaccine-id');

		$.post('/ajax/personal/main/previewVaccination', {
			vaccine_id: vaccineId,
		}).done(function(response) {
			var vaccine = response.data;
			vaccineModal.modal('show');
			$('#vaccine_institution').html(vaccine.institution);
			$('#vaccine_doctor').html(vaccine.doctor);
			$('#vaccine_vaccination_number').html(vaccine.vaccination_number);
			$('#vaccine_view_vaccination_date').html(moment(vaccine.vaccination_date).format('DD MMM YYYY'));
			if (vaccine.vaccination_expiry == "") {
				$('#vaccine_view_vaccination_expiry').html("No Booster");
				
			} else {
				$('#vaccine_view_vaccination_expiry').html(moment(vaccine.vaccination_expiry).format('DD MMM YYYY'));
			}

			$('#vaccine_country').html(vaccine.countryCode_id);
			$('#vaccine_phone').html(vaccine.phone);
			$('#vaccine_website').html(vaccine.website);


			$('#vaccine_email').html(`<a href="mailto:${vaccine.institution}<${vaccine.email}>">${vaccine.email}</a>`);

		});
	});

	$(document).on('click', '.confirm-vaccination', function(e) {
		e.preventDefault();

		var btn = $(this);

		swal.fire({
			
			title: 'Validate this vaccination?',
			text: 'Are you sure to validate this vaccination?',
			icon: 'warning',
			showCancelButton: true,
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, validate it!'
			
		}).then((result) => {
			if (result.value) {
				$.get('/ajax/workflow/job_application_tracker/confirm-vaccination/'+btn.data('vaccination-id'), (result) => {
					
					Swal.fire({
						icon: result.status,
						text: result.message,
					}).then(() => {

						vaccine.ajax.reload();
					});

					
				})
			}
		})
	});

	$(document).on('click', '.reject-vaccination', function(e) {
		e.preventDefault();

		var btn = $(this);

		swal.fire({
			
			title: 'Reject this vaccination?',
			text: 'Are you sure to reject this vaccination?, Once you reject it will deleted automatically',
			icon: 'warning',
			showCancelButton: true,
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, reject it!'
			
		}).then((result) => {
			if (result.value) {
				$.get('/ajax/workflow/job_application_tracker/reject-vaccination/'+btn.data('vaccination-id'), (result) => {
					
					Swal.fire({
						icon: result.status,
						text: result.message,
					}).then(() => {

						vaccine.ajax.reload();
					});

					
				})
			}
		})
	});
}

function seamanDatatable() {
	var seaman;

	if ($.fn.dataTable.isDataTable($('#seaman_data'))) {
		return false;
	}

	seaman = $('#seaman_data').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/personal/datatable/seaman",
			"type": "POST",
			cache: false,
			data: function (d) {
				d.status = $('#table_status_search').val();
				d.level = $('#table_level_search').val();
			}
		},
		"columns": [
			{"data": null},
			{"data": 'From'},
			{"data": 'To'},
			{"data": 'Status'},
			{"data": 'Seaman'},
			{"data": null}
		],
		"rowCallback": function(row, data) {
			var ts_to = moment(data.to_date);

			if(data.status == 'pending')
			{
				$(row).addClass('rgba-red-slight')
			} else if(ts_to.isBefore(moment().add(6, 'months'))) {
				$(row).addClass('rgba-orange-slight');
			} else {
				$(row).addClass('rgba-green-slight');
			}
		},
		"columnDefs": [
			{
				"render": function (data, type, row) {
					return `<a href="#" class="delete_seaman" id="${data.sbk_id}" data-seaman-file="${data.filename}"><i class="far fa-trash-alt text-danger"  title="Delete Passport"></i></a>`;
				},
				"targets": 0
			},
			{
				"render": function (data, type, row) {

					return moment(row.from_date).format('MMM/YY');
				},
				"targets": 1
			},
			{
				"render": function (data, type, row) {

					return moment(row.to_date).format('MMM/YY');
				},
				"targets": 2
			},
			{
				"render": function (data, type, row) {
					return row.status;
				},
				"targets": 3
			},

			{
				"render": function(data, type, row) {
					let html = `<a href="/ab/show/${row.filename}" data-toggle="lightbox" data-gallery="${row.filename}" data-footer="${row.nationality} : ${row.sbk_id}" data-type="image">
						<figure class="figure">
							<img src="/ab/show/${row.filename}-thumb"  class="img-fluid z-depth-1" title="${row.sbk_id} Image - Click to Enlarge" alt="${row.sbk_id} Image">
							<figcaption class="figure-caption text-center mt-2">
								${row.nationality} : ${row.sbk_id}
							</figcaption>
						</figure>
					</a>`;

					return html;
				},
				"targets": 4
			},


			{
				"render": function (data, type, row) {

					var html = `<a href="/personal/seaman/${data.sbk_id}"><i class="far fa-edit text-warning" title="Edit Seaman"></i></a>`;
					html += `<a class="preview_seaman" data-seaman-id="${data.sbk_id}" href="#"><i class="far fa-file-alt text-primary" title="Preview Seaman"></i></a>`;
					return html;
				},
				"targets": -1
			}
		],
	});

	$(document).on('click', '.delete_seaman', function( event ) {
		
		event.preventDefault();
		var sbk_id = $(this).prop('id');
		var seaman_current = $(this).data('seaman-file');
		
		swal.fire({
			
			title: 'Delete Seaman '+sbk_id+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteSeaman', {
					seaman_id: sbk_id,
					seaman_current: seaman_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        seaman.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();
					
					swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});

	$(document).on('click', '.preview_seaman', function(e) {
		e.preventDefault();

		var seamanModal = $('#seamanModal');
		var seamanId = $(this).data('seaman-id');

		$.post('/ajax/personal/main/previewSeaman', {
			sbk_id: seamanId,
		}).done(function(response) {
			var seaman = response.data;
			seamanModal.modal('show');

			if (seaman.full_name === '' || seaman.full_name === null) {
				$('#seaman_full_name').parent().addClass('d-none');
				$('#seaman_family_name').parent().removeClass('d-none');
				$('#seaman_given_names').parent().removeClass('d-none');
			} else {
				$('#seaman_family_name').parent().addClass('d-none');
				$('#seaman_given_names').parent().addClass('d-none');
				$('#seaman_full_name').parent().removeClass('d-none');
			}
			$('#seaman_id').html(seaman.sbk_id);
			$('#seaman_type').html(seaman.type);
			$('#seaman_code').html(seaman.code);
			$('#seaman_sbk_id').html(seaman.sbk_id);
			$('#seaman_full_name').html(seaman.full_name);
			$('#seaman_family_name').html(seaman.family_name);
			$('#seaman_given_names').html(seaman.given_names);
			$('#seaman_nationality').html(seaman.nationality);
			$('#seaman_sex').html(seaman.sex);
			$('#seaman_dob').html(seaman.dob);
			$('#seaman_pob').html(seaman.pob);
			$('#seaman_from_date').html(seaman.from_date);
			$('#seaman_to_date').html(seaman.to_date);
			$('#seaman_place_issued').html(seaman.place_issued);
			$('#seaman_authority').html(seaman.authority);


		});
	});
}

function stcwDatatable() {
	var stcw;

	if ($.fn.dataTable.isDataTable($('#stcw_data'))) {
		return false;
	}

	stcw = $('#stcw_data').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/personal/datatable/stcw",
			"type": "POST",
			cache: false,
			data: function (d) {
				d.status = $('#table_status_search').val();
				d.level = $('#table_level_search').val();
			}
		},
		"columns": [
			{"data": null},
			{"data": 'From'},
			{"data": 'To'},
			{"data": 'Length'},
			{"data": 'Status'},
			{"data": null}
		],
		"fnInitComplete": function(row, json) {
			if (json.mode != 'recruitment') {
				$('.confirm-stcw').addClass('d-none');
				$('.reject-stcw').addClass('d-none');
			} 
		
		},
		"rowCallback": function(row, data) {
			var ts_to = moment(data.to_date);

			if(data.status == 'pending')
			{
				$(row).addClass('rgba-red-slight')
			} else {
				$(row).addClass('rgba-green-slight');
			}
		},
		"columnDefs": [
			{
				"render": function (data, type, row) {
					return `<a href="#" class="delete_stcw" id="${data.education_id}" data-stcw-file="${data.filename}"><i class="far fa-trash-alt text-danger"  title="Delete Education"></i></a>`;
				},
				"targets": 0
			},
			{
				"render": function (data, type, row) {

					return moment(row.from_date).format('MMM/YY');
				},
				"targets": 1
			},
			{
				"render": function (data, type, row) {

					return moment(row.to_date).format('MMM/YY');
				},
				"targets": 2
			},
			{
				"render": function (data, type, row) {
					var start_date = moment(row.from_date).format('X');
					var end_date = moment(row.to_date).format('X');
					var diff_time = end_date - start_date;

					var year_time = (60 * 60 * 24 * 30 * 12);

					var years = parseInt(diff_time / year_time);
					var months = parseInt((diff_time % (years * year_time) ) / (60 * 60 * 24 * 30));

					return `${years} years, ${months} months`;
				},
				"targets": 3
			},
			{
				"render": function (data, type, row) {
					return row.status;
				},
				"targets": 4
			},

			{
				"render": function (data, type, row) {

					var html = `<a href="/ab/show/${row.filename}" data-toggle="lightbox" data-gallery="${row.education_id}" data-footer="${row.level} At ${row.institution}" data-type="image">
					<i class="far fa-image text-success"></i></a>
					<a href="/personal/education/${data.education_id}"><i class="far fa-edit text-warning" title="Edit Education"></i></a>`;
					
					if (row.status == 'pending' || row.status == 'none') {
						html += `<a href="#" data-stcw-id="${row.education_id}" class="confirm-stcw">
						<i class="far fa-check-circle text-info" title="Confirm STCW Document"></i></a>`;
						html += `<a href="#" data-stcw-id="${row.education_id}" class="reject-stcw">
						<i class="far fa-times-circle text-danger" title="Reject STCW Document"></i></a>`;
					}

					html += ` <a class="preview_stcw" data-stcw-id="${data.education_id}" href="#"><i class="far fa-file-alt text-primary" title="Preview Education"></i></a>`;
					return html;
				},
				"targets": -1
			}
		],
	});

	$(document).on('click', '.delete_stcw', function( event ) {
		
		event.preventDefault();
		var education_id = $(this).prop('id');
		var stcw_current = $(this).data('stcw-file');
		
		swal.fire({
			
			title: 'Delete STCW '+education_id+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteEducation', {
					education_id: education_id,
					education_current: stcw_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        stcw.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();
					
					swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});

	$(document).on('click', '.preview_stcw', function(e) {
		e.preventDefault();

		var stcwModal = $('#stcwModal');
		var stcwId = $(this).data('stcw-id');

		$.post('/ajax/personal/main/previewStcw', {
			education_id: stcwId,
		}).done(function(response) {
			var stcw = response.data;
			stcwModal.modal('show');

			if (stcw.certificate_number == "") {
				$('education_certificate_details').addClass('d-none');
			}

			$('#education_qualification').html(stcw.qualification);
			$('#education_level').html(stcw.level);
			$('#education_institution').html(stcw.institution);
			$('#education_level').html(stcw.certificate_qualification);
			$('#education_description').html(stcw.description);
			$('#education_from_date').html(moment(stcw.from_date).format('DD MMM YYY'));
			$('#education_to_date').html(moment(stcw.to_date).format('DD MMM YYYY'));
			$('#education_type').html(stcw.type);
			$('#education_english').html(stcw.english);

			$('#education_certificate_number').html(stcw.certificate_number);
			$('#education_certificate_date').html(stcw.certificate_date);
			if (stcw.certificate_expiry == "") {
				stcw.certificate_expiry = "- Does Not Expire -";
			}
			$('#education_attended_country').html(stcw.attended_countryCode_id);
			$('#education_certificate_expiry').html(stcw.certificate_expiry);

			$('#education_phone').html(stcw.phone);
			$('#education_website').html(stcw.website);
			$('#education_email').html(`<a href="mailto:${stcw.institution}<${stcw.email}>">${stcw.email}</a>`);
			$('#education_country').html(stcw.countyCode_id);

			if (response.mode != 'recruitment' && response.status != 'pending') {
				$('#stcw-confirm').addClass('hide');
			}

		});
	});

	$(document).on('click', '.confirm-stcw', function(e) {
		e.preventDefault();

		console.log('wowowo');

		var btn = $(this);

		swal.fire({
			
			title: 'Validate this stcw document?',
			text: 'Are you sure to validate this stcw document?',
			icon: 'warning',
			showCancelButton: true,
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, validate it!'
			
		}).then((result) => {
			if (result.value) {
				$.get('/ajax/workflow/job_application_tracker/confirm-stcw/'+btn.data('stcw-id'), (result) => {
					
					Swal.fire({
						icon: result.status,
						text: result.message,
					}).then(() => {

						stcw.ajax.reload();
					});

					
				})
			}
		})
	});

	$(document).on('click', '.reject-stcw', function(e) {
		e.preventDefault();

		console.log('wowowo');

		var btn = $(this);

		swal.fire({
			
			title: 'Reject this stcw document?',
			text: 'Are you sure to reject this stcw document?, Once you reject it will deleted automatically',
			icon: 'warning',
			showCancelButton: true,
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, reject it!'
			
		}).then((result) => {
			if (result.value) {
				$.get('/ajax/workflow/job_application_tracker/reject-stcw/'+btn.data('stcw-id'), (result) => {
					
					Swal.fire({
						icon: result.status,
						text: result.message,
					}).then(() => {

						stcw.ajax.reload();
					});

					
				})
			}
		})
	});
}

function flightDatatable() {
	var flight;

	if ($.fn.dataTable.isDataTable($('#flight_data'))) {
		return false;
	}

	flight = $('#flight_data').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/personal/datatable/flight",
			"type": "POST",
			cache: false,
			data: function (d) {
				d.status = $('#table_status_search').val();
				d.level = $('#table_level_search').val();
			}
		},
		"columns": [
			{"data": null},
			{"data": 'Flight Number'},
			{"data": 'Departure Date'},
			{"data": 'Status'},
			{"data": 'Flight'},
			{"data": null}
		],
		"rowCallback": function(row, data) {
			var ts_to = moment(data.to_date);

			if(data.status == 'pending')
			{
				$(row).addClass('rgba-red-slight')
			} else if(ts_to.isBefore(moment().add(6, 'months'))) {
				$(row).addClass('rgba-orange-slight');
			} else {
				$(row).addClass('rgba-green-slight');
			}
		},
		"columnDefs": [
			{
				"render": function (data, type, row) {
					return `<a href="#" class="delete_flight" id="${data.flight_number}" data-flight-file="${data.filename}"><i class="far fa-trash-alt text-danger"  title="Delete Education"></i></a>`;
				},
				"targets": 0
			},
			{
				"render": function (data, type, row) {

					return row.flight_number;
				},
				"targets": 1
			},
			{
				"render": function (data, type, row) {

					return moment(row.deprture_date).format('DD MMM YYYY');
				},
				"targets": 2
			},
			{
				"render": function (data, type, row) {
					return row.status;
				},
				"targets": 3
			},
			{
				"render": function(data, type, row) {
					let html = `<a href="/ab/show/${row.filename}" data-toggle="lightbox" data-gallery="${row.filename}" data-footer="${row.flight_number}" data-type="image">
						<figure class="figure">
							<img src="/ab/show/${row.filename}-thumb"  class="img-fluid z-depth-1" title="${row.flight_number} Image - Click to Enlarge" alt="${row.flight_number} Image">
							<figcaption class="figure-caption text-center mt-2">
								${row.flight_number}
							</figcaption>
						</figure>
					</a>`;

					return html;
				},
				"targets": 4
			},

			{
				"render": function (data, type, row) {

					var html = `<a href="/personal/flight/${data.flight_number}"><i class="far fa-edit text-warning" title="Edit Education"></i></a>`;
					return html;
				},
				"targets": -1
			}
		],
	});

	$(document).on('click', '.delete_flight', function(e) {
		e.preventDefault();

		Swal.fire({
			title: 'Are you sure?',
			text: "Please be carefull data can't restored once it deleted",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes'
		  }).then((result) => {
			if (result.value)
			{	
				$.ajax({
					url: "/ajax/personal/main/deleteFlight",
					type: 'POST',
					cache: false,
					timeout: 10000,
					data: {
						flight_number: $(this).attr('id'),
						flight_current: $(this).data('flight-file')
					}
				})
				.done(response => {
					Swal.fire({
					  type: 'success',
					  title: 'Information',
					  text: response.message
					}).then(() => {

						flight.ajax.reload();
					});
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
}

function englishDatatable() {
	var english;

	if ($.fn.dataTable.isDataTable($('#english_data'))) {
		return false;
	}

	english = $('#english_data').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/personal/datatable/english",
			"type": "POST",
			cache: false,
			data: function (d) {
				d.status = $('#table_status_search').val();
				d.level = $('#table_level_search').val();
			}
		},
		"columns": [
			{"data": 'Type'},
			{"data": 'Date'},
			{"data": 'Score'},
			{"data": 'Certificate'},
			{"data": null}
		],
		"fnInitComplete": function(row, json) {
			if (json.mode != 'recruitment') {
				$('.confirm-english').addClass('d-none');
				$('.reject-english').addClass('d-none');
			} 
			
		},
		"rowCallback": function(row, data) {
			var ts_to = moment(data.to_date);

			if(data.status == 'pending' || data.status == '')
			{
				$(row).addClass('rgba-red-slight')
			} else {
				$(row).addClass('rgba-green-slight');
			}
		},
		"columnDefs": [
			{
				"render": function (data, type, row) {

					return row.type;
				},
				"targets": 0
			},
			{
				"render": function (data, type, row) {

					return moment(row.when).format('DD MMM YYYY');
				},
				"targets": 1
			},
			{
				"render": function (data, type, row) {

					return row.overall;
				},
				"targets": 2
			},

			{
				"render": function(data, type, row) {
					let html = `<a href="/ab/show/${row.filename}" data-toggle="lightbox" data-gallery="${row.filename}" data-footer="${row.type} - ${row.when} - Image" data-type="image">
					<i class="far fa-image" title="Click to enlarge"></i>
				</a>`;

					return html;
				},
				"targets": 3
			},


			{
				"render": function (data, type, row) {

					var html = `<div class="d-flex justify-content-around"><a href="/personal/english/${data.english_id}"><i class="far fa-edit text-warning" title="Edit English"></i></a>`;
					
					if (row.status == 'pending') {
						html += `<a href="#" data-english-id="${row.english_id}" class="confirm-english">
						<i class="far fa-check-circle text-info" title="Confirm English Test"></i></a>`;
						html += `<a href="#" data-english-id="${row.english_id}" class="reject-english">
						<i class="far fa-times-circle text-danger" title="Reject English Test"></i></a>`;
					}

					html += `<a href="#" class="delete_english" id="${row.english_id}" data-english-file="${row.filename}"><i class="far fa-trash-alt text-danger"  title="Delete English"></i></a>`

					html += `<a class="preview_english" data-english-id="${data.english_id}" href="#"><i class="far fa-file-alt text-primary" title="Preview English"></i></a></div>`;
					return html;
				},
				"targets": -1
			}
		],
	});

	$(document).on('click', '.delete_english', function( event ) {
		
		event.preventDefault();
		var english_id = $(this).prop('id');
		var english_current = $(this).data('english-file');
		
		swal.fire({
			
			title: 'Delete English '+english_id+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteEnglish', {
					english_id: english_id,
					english_current: english_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        english.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();
					
					swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});

	$(document).on('click', '.preview_english', function(e) {
		e.preventDefault();

		var englishModal = $('#englishModal');
		var englishId = $(this).data('english-id');

		$.post('/ajax/personal/main/previewEnglish', {
			english_id: englishId,
		}).done(function(response) {
			var english = response.data;
			var breakdown = '';
			englishModal.modal('show');

			Object.keys(response.data.breakdown).forEach(function(item) {
				breakdown += '<strong style="font-weight: bold">' + item + ' </strong> : ' + response.data.breakdown[item] + '</br>';
			})

			$('#english_when').html(english.when);
			$('#english_type').html(english.type);
			$('#english_overall').html(english.overall);
			$('#english_breakdown').html(breakdown);
			$('#english_where').html(english.where);

		});
	});

	$(document).on('click', '.confirm-english', function(e) {
		e.preventDefault();

		var btn = $(this);

		swal.fire({
			
			title: 'Validate this english test?',
			text: 'Are you sure to validate this english test?',
			icon: 'warning',
			showCancelButton: true,
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, validate it!'
			
		}).then((result) => {
			if (result.value) {
				$.get('/ajax/workflow/job_application_tracker/confirm-english/'+btn.data('english-id'), (result) => {
					
					Swal.fire({
						icon: result.status,
						text: result.message,
					}).then(() => {

						english.ajax.reload();
					});

					
				})
			}
		})
	});

	$(document).on('click', '.reject-english', function(e) {
		e.preventDefault();

		var btn = $(this);

		swal.fire({
			
			title: 'Reject this english test?',
			text: 'Are you sure to reject this english test?, Once you reject it will deleted automatically',
			icon: 'warning',
			showCancelButton: true,
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, reject it!'
			
		}).then((result) => {
			if (result.value) {
				$.get('/ajax/workflow/job_application_tracker/reject-english/'+btn.data('english-id'), (result) => {
					
					Swal.fire({
						icon: result.status,
						text: result.message,
					}).then(() => {

						english.ajax.reload();
					});

					
				})
			}
		})
	});
}

$(document).ready(function()
{
	let language = $('#languages-data').DataTable();
	let employemets = $('#employements-data').DataTable({
		"columnDefs": [
			{"orderable": false,"searchable": false,"targets": 0},
			{"orderable": false,"searchable": false,"targets": 7}
		],
		"order" :[]
	});

	let educations = $('#educations-data').DataTable({
		"columnDefs": [
			{"orderable": false,"searchable": false,"targets": 0},
			{"orderable": false,"searchable": false,"targets": 6}
		],
		"order" :[]
	});

	const current = new Date();
	let flatpickr = $('.flatpickr').flatpickr({
		enableTime: true,
		minDate: current,
		dateFormat: 'Y-m-d H:i',
		defaultDate: new Date().setDate(current.getDate() + 1),
		altInput: true
	});
	
	active_tab = $('#active_tab').val();
	$('#'+active_tab).tab('show');

    var docs_active_tab = $('#docs_active_tab').val();
	$('#'+docs_active_tab).tab('show');

	var active_tab;
	var tab_document = $('#tab_documents');
	var tab_passport = $('#tab_passport');
	var tab_ids = $('#tab_ids');
	var tab_police = $('#tab_police');
	var tab_medical = $('#tab_medical');
	var tab_seaman = $('#tab_seaman');
	var tab_stcw = $('#tab_stcw');
	var tab_flight = $('#tab_flight');
	var tab_english = $('#tab_english');

	const address_book_id = $('#edit_id').val();

	if (tab_document.hasClass('active') && tab_passport.hasClass('active')) {
		passportDatatable();
		visaDatatable();
		oktbDatatable();
	} else if((tab_document.hasClass('active') && tab_ids.hasClass('active'))) {
		idsDatatable();
		idcheckDatatable();
	} else if((tab_document.hasClass('active') && tab_police.hasClass('active'))) {
		policeDatatable();
	} else if((tab_document.hasClass('active') && tab_medical.hasClass('active'))){
		medicalDatatable();
		vaccinationDatatable();
	} else if((tab_document.hasClass('active') && tab_seaman.hasClass('active'))) {
		seamanDatatable();
	} else if((tab_document.hasClass('active') && tab_stcw.hasClass('active'))) {
		stcwDatatable();
	} else if((tab_document.hasClass('active') && tab_flight.hasClass('active'))) {
		flightDatatable();
	} else if((tab_document.hasClass('active') && tab_english.hasClass('active'))) {
		englishDatatable();
	}

	tab_document.on('click', function(e) {
		e.preventDefault();

		passportDatatable();
		visaDatatable();
		oktbDatatable();
	});
	tab_passport.on('click', function(e) {
		e.preventDefault();

		passportDatatable();
		visaDatatable();
		oktbDatatable();
	});
	tab_ids.on('click', function(e) {
		e.preventDefault();

		idsDatatable();
		idcheckDatatable();
	});
	tab_police.on('click', function(e) {
		e.preventDefault();

		policeDatatable();
	});
	tab_medical.on('click', function(e) {
		e.preventDefault();

		medicalDatatable();
		vaccinationDatatable();
	});
	tab_seaman.on('click', function(e) {
		e.preventDefault();

		seamanDatatable();
	});
	tab_stcw.on('click', function(e) {
		e.preventDefault();

		stcwDatatable();
	});
	tab_flight.on('click', function(e) {
		e.preventDefault();

		flightDatatable();
	});
	tab_english.on('click', function(e) {
		e.preventDefault();

		englishDatatable();
	});

	$(document).on('click', '.btn-set-appointment-date', function(e) {
		e.preventDefault();
		
		$('input[name="type"]').val($(this).data('type'));
        $('#appointmentModal').modal('show');
	});

	$(document).on('click', '.btn-set-docs', function(e) {
		e.preventDefault();

		$('input[name="visa_type"]').val($(this).data('type'));

        $('#registerVisaModal').modal('show');
	});

	$(document).on('click', '.btn-set-interview', function(e) {
		e.preventDefault();

		$('input[name="visa_type"]').val($(this).data('type'));
		$('input[name="country_code"]').val($(this).data('country'));
		$('#interviewDateModal').modal('show');
	});

	$('#interview').on('submit', function (e) {
		e.preventDefault();

		let self = $(this);
		
		let type = self.find('input[name="visa_type"]').val();		
		let country = self.find('input[name="country_code"]').val();		
        
        Swal.fire({
            title: 'Set the interview date',
            text: "Set interview date to selected?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/visa/set-interview-date',
                    data: {
                        'address_book_id': self.find('input[name="address_book_id"]').val(),
						'interview_date': self.find('input[name="interview_date"]').val(),
						'country_code': country,
						'visa_type': type
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        }).then(() => {
							location.reload();
						});

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
	
	$('#appointment').on('submit', function (e) {
		e.preventDefault();
		
		let type = $('input[name="type"]').val();		
        
        Swal.fire({
            title: 'Set '+type+' appointment date to selected date?',
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
                    url: '/ajax/workflow/'+type+'/set-appointment-date',
                    data: {
                        'address_book_id': $('input[name="address_book_id"]').val(),
                        'appointment_date': $('input[name="appointment_date"]').val()
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        }).then(() => {
							table.ajax.reload();
						});

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

	$('.register-visa').on('click', function (e) {
		e.preventDefault();
			
        Swal.fire({
            title: 'Set this docs application?',
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
					url: '/ajax/workflow/visa/set-docs-application',
					enctype: 'multipart/form-data',
					data: new FormData($('#registerVisa')[0]),
					cache: false,
					processData: false,
					contentType: false,
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        }).then(() => {
							location.reload();
						});

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

    $('.validate-police').click(function(e) {
    	e.preventDefault();

        var btn = $(this);

        swal.fire({

            title: 'Validate this police check?',
            text: 'Are you sure to validate this police check?',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, validate it!',
            input: 'select',
            inputOptions: {
                'accepted': 'Accepted',
                'rejected': 'Rejected',
            },

        }).then((result) => {

        	if(result.value == 'rejected'){
                var data = {
                	address_book_id: btn.data('address-book-id'),
                	police_id: btn.data('police-id'),
                    status: result.value,
                    rejected_by: btn.data('user-id'),
                    rejected_on: btn.data('date'),
                };
			}else{
                var data = {
                    address_book_id: btn.data('address-book-id'),
                    police_id: btn.data('police-id'),
                    status: result.value,
                    accepted_by: btn.data('user-id'),
                    accepted_on: btn.data('date'),
                };
			}

            $.ajax({
                url: "/ajax/workflow/police/review",
                type: 'POST',
                data: data,
                cache: false,
                timeout: 10000
            })
                .done(response => {
                    Swal.fire({
                        text: result.message,
                    }).then(() => {

                        location.reload();
                    });
                });

        })
    });

	$('.reject-english').click(function(e) {
		e.preventDefault();

		var btn = $(this);

		swal.fire({
			
			title: 'Reject this english test?',
			text: 'Are you sure to reject this english test?, Once you reject it will deleted automatically',
			icon: 'warning',
			showCancelButton: true,
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, reject it!'
			
		}).then((result) => {
			if (result.value) {
				$.get('/ajax/workflow/job_application_tracker/reject-english/'+btn.data('english-id'), (result) => {
					
					Swal.fire({
						icon: result.status,
						text: result.message,
					}).then(() => {

						location.reload();
					});

					
				})
			}
		})
	});

    $('.delete_police').click(function( event ) {

        event.preventDefault();
        var btn = $(this)
        var id = $(this).prop('id');
        var current = $('#police_file_'+id).val();

        swal.fire({

            title: 'Delete Police Check '+id+'?',
            text: 'Are you sure! Once you delete it can never be recovered!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'

        }).then((result) => {

            if(result.value)
            {
                //turn on the spinner
                $('#iow-spinner').show();

                $.post('/ajax/personal/main/deletePolice', {
                    police_id: id,
                    police_current: current
                })
                    .done(function (d) {

                        $('#iow-spinner').hide();

                        if(d.success)
                        {
                            btn.closest('tr').remove();
                            Swal.fire({
                                icon: 'success',
                                text: d.message,
                            })
                        } else {
                            Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
                        }

                    })
                    .fail(function () {

                        $('#iow-spinner').hide();

                        swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
                    });

            }

        });
    });
	
	$('.delete_visa').click(function( event ) {
		
		event.preventDefault();
		var btn = $(this)
		var visa_id = $(this).prop('id');
		var visa_current = $('#visa_file_'+visa_id).val();
		
		swal.fire({
			
			title: 'Delete Visa '+visa_id+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteVisa', {
					visa_id: visa_id,
					visa_current: visa_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        btn.closest('tr').remove();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
						swal('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();
					
					swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});		
			}
			
		});
		
	});
	
	$('.delete_english').click(function( event ) {
		
		event.preventDefault();
		var btn = $(this)
		var english_id = $(this).prop('id');
		var english_current = $('#english_file_'+english_id).val();
		var english_type = $('#english_type_'+english_id).text();

		swal.fire({
			
			title: 'Delete '+english_type+' English Test?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{	
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteEnglish', {
					english_id: english_id,
					english_current: english_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        btn.closest('tr').remove();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();

                    Swal.fire('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});
	
	$('.delete_idcard').click(function( event ) {
		
		event.preventDefault();
		
		var idcard_id = $(this).prop('id');
		var btn = $(this);
		var idcard_original = $('#idcard_original_'+idcard_id).val();
		var idcard_current = $('#idcard_file_'+idcard_id).val();
		var idcard_back_current = $('#idcard_back_file_'+idcard_id).val();
		
		swal.fire({
			
			title: 'Delete ID '+idcard_original+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{	
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteIDCard', {
					idcard_id: idcard_id,
					idcard_current: idcard_current,
					idcard_back_current: idcard_back_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        btn.closest('tr').remove();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();

                    Swal.fire('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
		
	});

    $('.delete_idcheck').click(function( event ) {

        event.preventDefault();

        var idcheck_id = $(this).prop('id');
        var idcheck_current = $('#idcheck_file_'+idcheck_id).val();
        var btn = $(this);

        swal.fire({

            title: 'Delete this ID Check?',
            text: 'Are you sure! Once you delete it can never be recovered!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'

        }).then((result) => {

            if(result.value)
            {
                //turn on the spinner
                $('#iow-spinner').show();

                $.post('/ajax/personal/main/deleteIdcheck', {
                    idcheck_id: idcheck_id,
                    idcheck_current: idcheck_current,
                })
                    .done(function (d) {

                        $('#iow-spinner').hide();

                        if(d.success)
                        {
                            btn.closest('tr').remove();
                            Swal.fire({
                                icon: 'success',
                                text: d.message,
                            })
                        } else {
                            Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
                        }

                    })
                    .fail(function () {

                        $('#iow-spinner').hide();

                        Swal.fire('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
                    });

            }

        });

    });
	
	$('.delete_employment').click(function( event ) {
		
		event.preventDefault();
        var btn = $(this);
		var employment_id = $(this).prop('id');
		var employment_current = $('#employment_file_'+employment_id).val();
		var employment_employer = $('#employment_employer_'+employment_id).text();
		
		swal.fire({
			
			title: 'Delete Employment at '+employment_employer+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteEmployment', {
					employment_id: employment_id,
					employment_current: employment_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        btn.closest('tr').remove();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();

                    Swal.fire('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});
	
	$('.delete_education').click(function( event ) {
		
		event.preventDefault();
        var btn = $(this);
		var education_id = $(this).prop('id');
		var education_current = $('#education_file_'+education_id).val();
		var education_institution = $('#education_institution_'+education_id).text();
		
		swal.fire({
			
			title: 'Delete Education at '+education_institution+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{	
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteEducation', {
					education_id: education_id,
					education_current: education_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        btn.closest('tr').remove();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();

                    Swal.fire('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
		
		});
	});
	
	$('.delete_tattoo').click(function( event ) {
		
		event.preventDefault();
        var btn = $(this);
		var tattoo_id = $(this).prop('id');
		var tattoo_current = $('#tattoo_file_'+tattoo_id).val();
		var tattoo_location = $('#tattoo_location_'+tattoo_id).text();
		
		swal.fire({
			
			title: 'Delete Tattoo on '+tattoo_location+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteTattoo', {
					tattoo_id: tattoo_id,
					tattoo_current: tattoo_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        btn.closest('tr').remove();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
						swal('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();
					
					swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});
	
	$('.delete_reference').click(function( event ) {
		
		event.preventDefault();
        var btn = $(this);
		var reference_id = $(this).prop('id');
		var reference_current = $('#reference_file_'+reference_id).val();
		var reference_name = $('#reference_name_'+reference_id).text();
		
		swal.fire({
			
			title: 'Delete reference '+reference_name+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteReference', {
					reference_id: reference_id,
					reference_current: reference_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        btn.closest('tr').remove();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
						swal('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();
					
					swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});
	

    $('.delete_medical').click(function( event ) {

        event.preventDefault();
        var btn = $(this);
        var medical_id = $(this).prop('id');
        var medical_current = $('#medical_file_'+medical_id).val();

        swal.fire({

            title: 'Delete this medical data?',
            text: 'Are you sure! Once you delete it can never be recovered!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'

        }).then((result) => {

            if(result.value)
            {
                //turn on the spinner
                $('#iow-spinner').show();

                $.post('/ajax/personal/main/deleteMedical', {
                    medical_id: medical_id,
                    medical_current: medical_current
                })
                    .done(function (d) {

                        $('#iow-spinner').hide();

                        if(d.success)
                        {
                            btn.closest('tr').remove();
                            Swal.fire({
                                icon: 'success',
                                text: d.message,
                            })
                        } else {
                            swal('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
                        }

                    })
                    .fail(function () {

                        $('#iow-spinner').hide();

                        swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
                    });

            }

        });
    });
    $('.delete_vaccination').click(function( event ) {

        event.preventDefault();
        var btn = $(this);
        var vaccination_id = $(this).prop('id');
        var vaccination_current = $('#vaccination_file_'+vaccination_id).val();

        swal.fire({

            title: 'Delete this vaccination data?',
            text: 'Are you sure! Once you delete it can never be recovered!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'

        }).then((result) => {

            if(result.value)
            {
                //turn on the spinner
                $('#iow-spinner').show();

                $.post('/ajax/personal/main/deleteVaccination', {
                    vaccination_id: vaccination_id,
                    vaccination_current: vaccination_current
                })
                    .done(function (d) {

                        $('#iow-spinner').hide();

                        if(d.success)
                        {
                            btn.closest('tr').remove();
                            Swal.fire({
                                icon: 'success',
                                text: d.message,
                            })
                        } else {
                            swal('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
                        }

                    })
                    .fail(function () {

                        $('#iow-spinner').hide();

                        swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
                    });

            }

        });
	});

	$('.delete_oktb').click(function( event ) {
		
		event.preventDefault();
		var btn = $(this)
		var oktb_id = $(this).prop('id');
		var oktb_current = $('#oktb_file_'+oktb_id).val();
		
		swal.fire({
			
			title: 'Delete OKTB Document '+oktb_id+'?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
			
		}).then((result) => {
				
			if(result.value)
			{
				//turn on the spinner
				$('#iow-spinner').show();
			
				$.post('/ajax/personal/main/deleteOktb', {
					oktb_id: oktb_id,
					oktb_current: oktb_current
				})
				.done(function (d) {
					
					$('#iow-spinner').hide();
					
					if(d.success)
					{
                        btn.closest('tr').remove();
                        Swal.fire({
                            icon: 'success',
                            text: d.message,
                        })
					} else {
                        Swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
					}
					
				})
				.fail(function () {
					
					$('#iow-spinner').hide();
					
					swal('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
				});	
				
			}
			
		});
	});
	
	//ajax request review
	$('#req_verification').click(function(){
		let btn = $(this);
		let html_text_area = `
		<label for="info_request"
		class="float-left">Message</label>
		<textarea id="info_request" name="info_request" maxlength="255" class="form-control" placeholder="Enter message..." required></textarea>
		<span id="charactersRemaining"></span>
		`;
		const verified_status = $('#verified_status').val();
		console.log(verified_status);
		if ( verified_status == 'rejected' || verified_status == 'process')
		{
			html_text_area = `
				<div class="text-center mb-2">Are you sure? Please make sure the data is corrected before request again</div>
				<label for="info_request"
				class="float-left">Message</label>
				<textarea id="info_request" name="info_request" maxlength="255" class="form-control" placeholder="Enter message..." required></textarea>
				<span id="charactersRemaining"></span>
			`;
			swal.fire({
				title: 'Request Verification Again?',
				html: html_text_area,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Request Verification!'

			}).then((result) => {
				if(result.value)
				{
					btn.prop('disabled',true);
					$('#modal_loading').modal('show');
					let info_request = $('#info_request').val();
					info_request = info_request==''?'Requested by user':info_request;
					$.post('/ajax/personal/main/requestVerification',{ info_request: info_request})
						.done(function (d) {
							if(d.success)
							{
								$('#modal_loading').modal('hide');
								Swal.fire({
									icon: 'success',
									text: d.message,
								}).then((result) => {
									location.reload(true);
								});
								
							} else {
								$('#modal_loading').modal('hide');
								swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
							}
	
						})
						.fail(function () {
							$('#modal_loading').modal('hide');
							swal.fire('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
						});
				}
			});

		}else if (verified_status == 'ready' || verified_status == 'unverified' || verified_status == '')
		{
			swal.fire({
				title: 'Request Verification',
				html: html_text_area,
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Send Request'

			}).then((result) => {
				if(result.value)
				{
					btn.prop('disabled',true);
					$('#modal_loading').modal('show');
					let info_request = $('#info_request').val();
					info_request = info_request==''?'Requested by user':info_request;
					$.post('/ajax/personal/main/requestVerification',{ info_request: info_request})
					.done(function (d) 
					{
						if(d.success)
						{
							$('#modal_loading').modal('hide');
							Swal.fire({
								icon: 'success',
								text: d.message,
							}).then((result) => {
								location.reload(true);
							});
							
						} else {
							$('#modal_loading').modal('hide');
							swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
						}

					})
					.fail(function () {
						$('#modal_loading').modal('hide');
						swal.fire('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
					});
				}
			});
			
		}else{
			//wrong status; do nothing
		}
	});

	//AJAX call show cv
	$('#show_cv_btn').click(function()
	{
		
		$.get('/ajax/personal/main/getCurriculumVitae/'+address_book_id)
			.done(function (response) 
			{
				if(response)
				{
					const table = $('#table_cv');

					let html = ''

					html += `
					<div class="row">
						<div class="col-12 text-capitalize">
							<div class="row">
								<div class="col-12 font-weight-bold mt-3 mb-1"> Personal Data </div>

								<div class="col-3">Name</div>
								<div class="col-9">: 
									${(response.name)? response.name : 'Not Set'}
								</div>

								<div class="col-3">Date Of Birth</div>
								<div class="col-9">: 
									${(response.dob)? response.dob : 'Not Set'}
								</div>

								<div class="col-3">Address</div>
								<div class="col-9">: 
									${(response.address)? response.address : 'Not Set'}
								</div>

								<div class="col-3">Nationality</div>
								<div class="col-9">: 
									${(response.country)? response.country : 'Not Set'}
								</div>

								<div class="col-3">Sex</div>
								<div class="col-9">: 
									${(response.sex)? response.sex :'Not Set'}
								</div>

								<div class="col-3">Height/Weight</div>
								<div class="col-9">: 
									${(response.hw)? response.hw : 'Not Set'}
								</div>

								<div class="col-3">Phone Number</div>
								<div class="col-9">: 
									${(response.number)? response.number : 'Not Set'}
								</div>

								<div class="col-3">Email</div>
								<div class="col-9">: 
									${(response.main_email)? response.main_email : 'Not Set'}
								</div>

							</div>
						</div>
					`;

					html +=`
						<div class="col-12">
							<div class="row">`
					//if there is education data
					if (response.education_count > 0)
					{
						html += `<div class="col-12 font-weight-bold mt-3 mb-1"> Education Background </div>`;
						$.each(response.education_list, function(i,education) 
						{
							html += 
								`<div class="col-3 text-capitalize"> ${education.level} </div>
								<div class="col-9">: ${education.from_date} - ${education.to_date}  &nbsp ${education.institution}</div>`;
						});
					}
					html +=`</div>
						</div>`;
					//if there is employment data
					if (response.employment_count > 0)
					{
						html += `<div class="col-12 font-weight-bold mt-3 mb-1 ">Work Experience</div>`;
						$.each(response.employment_list, function(i,employment)
						{
							html += 
								`<div class="col-12">
								- &nbspI have been working at ${employment.employer}, as a ${employment.job_title} from ${employment.from_date} until ${employment.to_date}
								</div>`;
						});
					}

					//check if personal data is completed
					if (
						!response.name || 
						!response.dob ||
						!response.address ||
						!response.country ||
						!response.sex ||
						!response.hw ||
						!response.number ||
						!response.main_email
						)
					{
						$('.btn-show_cv_pdf').hide().parent('div').html('<div class="mt-4 alert alert-warning text-center">Please complete personal data first, to be able to download the CV</div>');	
					}else{
						$('.btn-show_cv_pdf').show();
					}
					
					table.html(html);
					$('#show_cv_modal').modal('show');
					
				} else {

					Swal.fire({
						icon: 'error',
						title: 'Failed',
						text: 'There was a connection error.  The internet may be down or there might be an issue with the server.'
					})
				}
			}).fail(function () 
			{
				Swal.fire({
					icon: 'error',
					title: 'Connection Failed',
					text: 'There was a connection error.  The internet may be down or there might be an issue with the server.'

				});
			});
			
		$('#show_cv_modal').modal('show');
	});

	$('.btn-show_cv_pdf').click(function()
	{
		const win = window.open('/ajax/personal/main/getCurriculumVitaePDF/'+address_book_id);
		if (win) {
			win.focus();
		} else {
			Swal.fire({
			  type: 'warning',
			  title: 'Blocked',
			  text: 'Please allow pop up to download the file!'
			});
		}
	});

	$('#show_verification_btn').click(function()
	{
		console.log($(this).data('connection'))
		if($(this).data('connection') == null || $(this).data('connection') == ''){
            Swal.fire({
                type: 'warning',
                title: 'Warning',
                text: 'Please select the LP for this candidate before update verification status!'
            });
            return false;
		}
		$('#edit_verification_modal').modal('show');
		const verified_status = $('#verified_status').val();
		$('#verification_status').val(verified_status);
	});
	
	$('#edit_verification_btn').click(function()
	{
		let btn = $(this);
        Swal.fire({
            title: 'Are you sure?',
            text: "Please make sure to check the data before changing this verification status",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
			if (result.value)
			{
				btn.attr('disabled', true);
				btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...');
				$.ajax({
					url: "/ajax/recruitment/status/changeVerification",
					type: 'POST',
					data: {
                        dt_id : $('#edit_id').val(),
                        dt_status : $('#verification_status').val(),
                        dt_verification_info : $('#verification_info').val()
					},
					cache: false,
					timeout: 10000
				})
				.done(response => {
					Swal.fire({
					  type: 'success',
					  title: 'Verification status edited!',
					  text: response.message
					});

					$('#edit_verification_modal').modal('hide');
					location.reload(true);
					btn.attr('disabled', false);
                	btn.html('Edit');
				})
				.fail(response => {
					console.log(response)
					if (response.status === 406) {
						Swal.fire({
							icon: response.responseJSON.type,
							title: 'Oops...',
							text: response.responseJSON.message
						  });
					} else {
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: 'Connection to Server Failed!'
						});
					}
					btn.attr('disabled', false);
                	btn.html('Edit');
				});
            }
        });
    });

	//premium service datatable
    $.ajax({
        "url": "/ajax/job/premium/list",
        "type": "POST",
        data: {
            'address_book_id': $('#premium_service_table').data('ab-id')
        },
        cache: false,
        timeout: 10000
    })
        .done(response => {
        	var data = [
        		response
			];
            $('#premium_service_table').DataTable({
                "data" : data,
                "columns" : [
                    { "data" : null },
                    { "data" : null },
                    { "data" : 'status' },
                    { "data" : null },
                ],
                "columnDefs": [
                    {
                        "render": function (data, type, row) {
                            return row.type + ' - ' + row.contract_type;
                        },
                        "targets": 0
                    },
                    {
                        "render": function (data, type, row) {
                            return row.amount / 100 + ' USD ';
                        },
                        "targets": 1
                    },
                    {
                        "render": function (data, type, row) {
                            var premium_service = ((data == null) || (data == '')) ? 'Not Requested' : data;

                            if (premium_service === 'sending') {
                                premium_service = 'Psf Sent';
                            }

                            if (row.verified != 'unknown') {
                                premium_service += '<br><span class="badge badge-success">'+row.verified+'</span>';
                            }else{
                                premium_service += '<br><span class="badge badge-warning">Unknown</span>';
                            }
                            return premium_service
                        },
                        "targets": 2
                    },
                    {
                        "render": function (data, type, row) {
							var html = ''
                        	if(row.status == 'confirmed' && row.verified == 'accepted'){
                                var html = `<a target="_blank"  class="btn-sm btn-info" href="/ab/show/${row.filename}"  ><i class="fas fa-file-pdf" title="PSF File"></i> PSF File</a>`;
							}
                            return html;
                        },
                        "targets": -1, orderable:false, searchable:false
                    }
                ],
			});
        });


    //Seaman Book List
    //premium service datatable
    $.ajax({
        "url": "/ajax/personal/main/getSeamanBook",
        "type": "POST",
        data: {
            'address_book_id': $('#premium_service_table').data('ab-id')
        },
        cache: false,
        timeout: 10000
    })
        .done(response => {
            var data = [
                response
            ];
            $('#seaman_book_table').DataTable({
                "data" : data,
                "columns" : [
                    { "data" : null },
                    { "data" : null },
                    { "data" : 'status' },
                    { "data" : null },
                ],
                "columnDefs": [
                    {
                        "render": function (data, type, row) {
                            return row.type + ' - ' + row.contract_type;
                        },
                        "targets": 0
                    },
                    {
                        "render": function (data, type, row) {
                            return row.amount / 100 + ' USD ';
                        },
                        "targets": 1
                    },
                    {
                        "render": function (data, type, row) {
                            var premium_service = ((data == null) || (data == '')) ? 'Not Requested' : data;

                            if (premium_service === 'sending') {
                                premium_service = 'Psf Sent';
                            }

                            if (row.verified != 'unknown') {
                                premium_service += '<br><span class="badge badge-success">'+row.verified+'</span>';
                            }else{
                                premium_service += '<br><span class="badge badge-warning">Unknown</span>';
                            }
                            return premium_service
                        },
                        "targets": 2
                    },
                    {
                        "render": function (data, type, row) {
                            var html = ''
                            if(row.status == 'confirmed' && row.verified == 'accepted'){
                                var html = `<a target="_blank"  class="btn-sm btn-info" href="/ab/show/${row.filename}"  ><i class="fa fa-image" title="PSF File"></i></a>`;
                            }
                            return html;
                        },
                        "targets": -1
                    }
                ],
            });
		});

		$('.delete_seaman').on('click', function(e) {
			e.preventDefault();

			Swal.fire({
				title: 'Are you sure?',
				text: "Please be carefull data can't restored once it deleted",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{	
					$.ajax({
						url: "/ajax/personal/main/delete-seaman/"+$(this).attr('id'),
						type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
						}).then(() => {

							location.reload(true);
						});
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

		$('.delete_flight').on('click', function(e) {
			e.preventDefault();

			Swal.fire({
				title: 'Are you sure?',
				text: "Please be carefull data can't restored once it deleted",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{	
					$.ajax({
						url: "/ajax/personal/main/delete-flight/"+$(this).attr('id'),
						type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
						}).then(() => {

							$(this).parent().parent().remove();
						});
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

		//stuff for image

	function popupResult(result) {
		var html;
		if (result.html) {
			html = result.html;
		}
		if (result.src) {
			html = '<img src="' + result.src + '" />';
		}
		Swal.fire({
			title: 'Visa',
			html: html,
			width: 750,
			allowOutsideClick: true
		});
		setTimeout(function(){
			$('.sweet-alert').css('margin', function() {
				var top = -1 * ($(this).height() / 2),
					left = -1 * ($(this).width() / 2);

				return top + 'px 0 0 ' + left + 'px';
			});
		}, 1);
	}
	
	var $uploadCrop;

	function readFile(input) {
		
		if (input.files && input.files[0]) {
				
            var reader = new FileReader();
            
            reader.onload = function (e) {
	            
				$uploadCrop.croppie('bind', {
	            	url: e.target.result
	            });
	            
	            $('#payment_receipt_croppie_wrap').show();
            	
            }
            
            reader.readAsDataURL(input.files[0]);
            
        } else {
            Swal.fire("Sorry - you're browser doesn't support the FileReader API");
	    }
	}

	//detect viewport and compare with inserted attribute data
	const b_width = $('#payment_receipt_croppie').data('banner-width');
	const b_height = $('#payment_receipt_croppie').data('banner-height');
	const v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/2;
	const v_height = b_height/b_width*v_width;

	//choose appropriate width and height based on device
	const crop_width = (b_width>v_width) ? v_width : b_width; 
	const crop_height = (b_height>v_height) ? v_height : b_height; 

	$uploadCrop = $('#payment_receipt_croppie').croppie({
		viewport: {
			width: crop_width,
			height: crop_height
		},
		enableExif: true
	});

	$('#payment_receipt_croppie').addClass('d-none');

	$('#payment_receipt_input').on('change', function () {
		const file_choosen = $('#payment_receipt_input').val();
		$('#payment_receipt_croppie').removeClass('d-none');


		//check if image is choosen before start cropping
		if (file_choosen !== ""){
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
			readFile(this);
		}

	});
	
	$('#payment_receipt_result').on('click', function (ev) {
		const file_choosen = $('#payment_receipt_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
			$uploadCrop.croppie('result', {
				type: 'canvas',
				size: 'viewport'
			}).then(function (resp) {
				popupResult({
					src: resp
				});
				
				$('#payment_receipt_base64').val(resp);
			});
		}else{
			Swal.fire({
				icon: 'warning',
				text: 'Please choose an image first',
			});
		}
	});

	let job_title,data_job_title;
	//handle filter job speedy
	$(document).on('change','#job_category',function(){
		var value = $(this).val();                
		job_title.html('<option value="" disabled selected>Choose Job Title</option>');
		if(value!=='' && value!=null){
			data_job_title.clone().filter("."+value).appendTo(job_title);
		} else {
			data_job_title.clone().appendTo(job_title);
		}
		$('#job_title').materialSelect({
			destroy: true
			});
		$('#job_title').materialSelect();
	});

	//when button apply on modal clicked
	$(document).on('click','#apply_special_job',function(){
		let ab = $('#special_apply_job').data('ab');
		let linkApply = $('#special_apply_job').data('linkapply');
		var value = $('#job_title').val();               
		if(value!=='' && value!=null){
			swal.fire({
				title: 'Are you sure?',
				icon: 'warning',
				showCancelButton: true,
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Apply Now!',
			}).then((result) => {
				if(result.value) {
					let link = linkApply+value+'/'+ab;
					window.open(link,'_SELF');
					$('#apply_special_job').prop('disabled',true);
				}
			});
		} else {
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'Please choose Job Title'
			  });
		}
		$('#job_title').materialSelect({
			destroy: true
			});
		$('#job_title').materialSelect();
	});
	
	//when button apply special jon on personal clicked
	$('#special_apply_job').on('click',function(){
		$('#modal_special_job').modal('show');
		$('.loading-content').show();
		$('.special-job-content').hide();
		$.ajax({
			url: "/ajax/personal/main/listJob",
			type: 'POST',
			cache: false,
			dataType:'json',
		})
		.done(response => {
			if(response.status=='ok') {
				html=`
				<div class="form-group pb-1">
					<select class="mdb-select md-form" name="job_category" id="job_category" searchable="Search here..">
						<option value="" disabled selected>Choose Job Category</option>
						<option value="">Choose Job Category</option>
				`;
				$.each(response.job_category,function(i, val){
					html +=`
							<option value="`+val.job_speedy_category_id+`">`+val.name+`</option>
					`
				});
				html +=`
						</select>
						<label class="mdb-main-label">Job Category</label>
					</div>
				`;

				html +=`
				<div class="form-group pb-1">
					<select class="mdb-select md-form" name="job_title" id="job_title" searchable="Search here..">
						<option value="" disabled selected>Choose Job Title</option>
				`;
				$.each(response.job_speedy,function(i, val){
					html +=`
							<option value="`+val.job_speedy_code+`" class="dtfirst `+val.job_speedy_category_id+`">`+val.job_title+`</option>
					`
				});
				html +=`
						</select>
						<label class="mdb-main-label">Job Title</label>
					</div>
				`;

				$('#select_job_content').html(html);
				$('.loading-content').hide();
				$('.special-job-content').show();
				$('#job_title').materialSelect();
				$('#job_category').materialSelect();

				job_title = $("#job_title");
				data_job_title = job_title.children(".dtfirst").clone();

			}
			
		})
		.fail(error => {
			Swal.fire({
			  type: 'error',
			  title: 'Oops...',
			  text: error.response.message
			});
		});
	});

	$('.export-pdf').on('click', function(e) {
        e.preventDefault();

        loadingModal();

        $.ajax({
            method: 'POST',
            url: '/ajax/personal/reference/export/' + $(this).data('reference-id'),
            success: function(response) {
				hideLoadingModal();
				Swal.fire({
					icon: response.type,
					title: response.message
				}).then((res) => {
					if (res.value) {
						window.open(response.url, '_blank');
					}
				})
            },
            error: function(res) {
				hideLoadingModal();
				if (res.statusCode !== 500) {
					Swal.fire('Notification', res.responseJSON.message, res.responseJSON.type);
				} else {
					Swal.fire('Notification', res.responseText, 'error');
				}
            }
        });
	});
	
	$('#verification_status').on('change', function() {
        if ($(this).val() === 'verified') {
            $.ajax({
                url: '/ajax/recruitment/checkpersonalcomplete/' + $('#edit_id').val(),
                method: 'POST',
                success: function(response) {
                    if (!response.personal_complete) {
                        Swal.fire({
                            title: 'Warning',
                            text: response.message,
                            icon: 'warning'
                        });

                        $('#edit_verification_btn').attr('disabled', true);
                    }
                }
            });
        }

        $('#edit_verification_btn').removeAttr('disabled');
	});
});