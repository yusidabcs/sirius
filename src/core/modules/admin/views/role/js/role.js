$(document).ready(function(){
	$('.child_all').hide();
	const showLoadingModal = function() {
		Swal.fire({
			title: 'Loading Permission...',
			icon: 'info',
			allowOutsideClick: false,
			allowEscapeKey: false,
			showConfirmButton: false
		});
		Swal.showLoading();
	}

	//select
	$('#selectUser').materialSelect();

	table = $('#role_list').DataTable({
		"processing": true,
		"serverSide": true,
		'responsive': true,
		"ajax": {
			"url": "/ajax/admin/role/list",
			"type": "POST",
			cache: false
		},
		"columns": [
			{"data": 'role_id'},
			{"data": 'role_name'},
			{"data": null},
			{"data": 'total_users'},
			{"data": null}
		],
		"columnDefs": [
			{
				"render": function ( data, type, row ) {
					var html = '<div class="container row text-center ">'+
						'<a data-id="'+row.role_id+'" data-name="'+row.role_name+'" data-toggle="modal" class="btn-sm btn-light btn_update_role" title="Edit Data" href="#"><i class="fa fa-edit"></i></a> '+
						`<a data-role-id="${row.role_id}" data-permission='${(row.permission) ? row.permission:''}' class="btn-sm btn-light btn_see_permission" title="See Permission" href="#"><i class="fa fa-eye"></i></a> `+
						' <a data-toggle="modal" data-id="'+row.role_id+'" class="btn-sm btn-danger btn_delete_role" title="Delete Data" href="#"><i class="fa fa-times"></i></a>'+
						' <a data-toggle="modal" data-id="'+row.role_id+'" data-name="'+row.role_name+'" class="btn-sm btn-secondary btn_assign_role" title="Assign Role" href="#"><i class="fa fa-user"></i></a>'+
						'<div>';
					return html;
				},
				"searchable": false, "orderable": false,"targets": -1
			},
			{
				"render": function(_, _, row) {
					if (!row.permission) {
						return 0;
					}
					var permissions = JSON.parse(row.permission);

					return Object.keys(permissions).length;
				},
				"searchable": false, "targets": 2
			}
		]
	});

	var permissions = null;
	var permissionKeys = null;
	var currentEdit = null;
	var currentRow = null;
	var inlineEdit = {
		role_id: null,
		permission_key: null,
		permission_value: null
	};

	function cleanInlineEditingValue() {
		inlineEdit.role_id = null;
		inlineEdit.permission_key = null;
		inlineEdit.permission_value = null;
	}

	function renderPermissionTable(tableElement, permissionKeys)
	{
		var html = '';
		permissionKeys.forEach(function(permissionKey) {
			html += `<tr>
						<td>${permissionKey}</td>
						<td><a href="#" data-permission-key="${permissionKey}" class="permission-editable">${permissions[permissionKey]}</a></td>
					<tr>`;
		});

		tableElement.find('tbody').html(html);
	}

	function submitRole(e) {
		e.preventDefault();

		var btn = $('#addRoleModal').find('.btn-add');

		btn.addClass('disabled');
		btn.text('Saving....');

		var role_name = $('#addRoleModal').find('input[name="role_name"]');

		$.ajax({
			url: '/ajax/admin/role/add',
			method: 'POST',
			data: {
				'role_name': role_name.val()
			},
			success: function(response) {
				Swal.fire('Information', response.message, response.status);

				role_name.val('');
				table.ajax.reload();

				btn.removeClass('disabled');
				btn.text('Save');
			},
			error: function(error) {
				Swal.fire('Error', 'Oops something went wrong, please contact the administrator', 'error');

				btn.removeClass('disabled');
				btn.text('Save');
			}
		});
	}

	function assignRole(e) {
		e.preventDefault();

		var btn = $('#assignRoleModal').find('.btn-assign');

		btn.addClass('disabled');
		btn.text('Saving....');

		var user_id = $('#assignRoleModal').find('select[name="user_id[]"]');
		var role_id = $('#assignRoleModal').find('input[name="role_id"]');

		$.ajax({
			url: '/ajax/admin/role/assign-role',
			method: 'POST',
			data: {
				'user_id': user_id.val(),
				'role_id': role_id.val()
			},
			success: function(response) {
				Swal.fire('Information', response.message, response.status);

				role_id.val('');
				$('#selectUser').val('');
				$('#selectUser').trigger('change');
				$('#selectUser').materialSelect({
					destroy: true
				});
				$('#selectUser').materialSelect();

				$('#assignRoleModal').modal('hide');

				btn.removeClass('disabled');
				btn.text('Save');
				table.ajax.reload();
			},
			error: function(error) {
				Swal.fire('Error', 'Oops something went wrong, please contact the administrator', 'error');

				btn.removeClass('disabled');
				btn.text('Save');
			}
		});
	}

	function updateRole(e) {
		e.preventDefault();

		var form = $('#updateRoleForm');
		var btn = $('.btn-update');

		btn.text('Updating...');
		btn.addClass('disabled');

		$.ajax({
			url: '/ajax/admin/role/update/' + form.find('input[name="role_id"]').val(),
			method: 'POST',
			data: {
				role_name: form.find('input[name="role_name"]').val()
			},
			success: function(res) {
				Swal.fire('Success', res.message, res.status);

				$('#updateRoleModal').modal('hide');
				table.ajax.reload();

				btn.text('Update');
				btn.removeClass('disabled');
			},
			error: function() {
				Swal.fire('Error', 'Something went wrong, please contact administrator', 'error');

				btn.text('Update');
				btn.removeClass('disabled');
			}
		});
	}

	$('#addRoleModal').on('submit', submitRole);

	$('.btn-add').on('click', submitRole);

	$('.save-permission').on('click', function(e) {
		e.preventDefault();

		var data = new FormData($('#assignPermission')[0]);
		var btn = $(this);

		btn.text('saving....');
		btn.addClass('disabled');

		$.ajax({
			url: '/ajax/admin/role/save-permission',
			method: 'POST',
			data: data,
			processData: false,
			contentType: false,
            cache: false,
            enctype: 'multipart/form-data',
			success: function(response) {

				Swal.fire('Information', response.message, response.status);
				
				btn.text('SAVE PERMISSIONS');
				btn.removeClass('disabled');
			},
			error: function() {
				Swal.fire('Oops', 'Something went wrong, please contact the administrator', 'error');

				btn.text('SAVE PERMISSIONS');
				btn.removeClass('disabled');
			}
		})

	});

	/*
	$('#roleNameSelect').on('change', function() {
		var form = $('#assignPermission');

		form.find('input[type="radio"]').prop('checked', false);

		$.get('/ajax/admin/role/get-role/' + $(this).val(), function(response) {

			if (!response.data) {
				return;
			}

			var permissionKeys = Object.keys(response.data);

			permissionKeys.forEach(function(key) {
				var input = document.getElementById(`${key}.${response.data[key]}`);

				if (input) {
					
					input.checked = true;
				}
			});
		});
	});
	*/

	$('#roleNameSelect').on('change', function() {
		var form = $('#assignPermission');

		form.find('.check_permission').prop('checked', false);
		form.find('.check_module').prop('checked', false);
		form.find('.check_model').prop('checked', false);
		$('.save-permission').removeClass('disabled');
		if($(this).val()==''){
			$('.save-permission').addClass('disabled');
			$('.child_all').hide();
			return false;
		}
		showLoadingModal();
		$.get('/ajax/admin/role/get-role/' + $(this).val(), function(response) {
			//$(this).siblings('.child_' + this.id).toggle();
			$('.child_all').show();
			if (!response.data) {
				Swal.close();
				return;
			}

			var permissionKeys = Object.keys(response.data);

			permissionKeys.forEach(function(key) {
				id = key.replaceAll(".","_");
				var input = document.getElementById(`${id}`);
				if (input) {
					if(response.data[key]=='allow') {
						$('#label_permission_'+id).text('Allow');
						$('#text_permission_'+id).val('allow');
						input.checked=true;
					} else {
						$('#label_permission_'+id).text('Not Allow');
						$('#text_permission_'+id).val('not_allow');
						input.checked=false;
					}
					
				}
			});
			Swal.close();
		});
	});

	$(document).on('click', '.btn_assign_role', function(e) {
		e.preventDefault();

		var modal = $('#assignRoleModal');

		modal.modal('show');
		modal.find('#roleName').text($(this).data('name'));
		modal.find('input[name="role_id"]').val($(this).data('id'));

		$.get('/ajax/admin/role/user-role/' + $(this).data('id'), function(response) {
			var user_ids = response.data.map(item => item.user_id);

			modal.find('#selectUser').val(user_ids);
			modal.find('#selectUser').trigger('change');
		});
	});

	$('#assignRoleForm').on('submit', assignRole);
	$('.btn-assign').on('click', assignRole);

	$(document).on('click', '.btn_update_role', function(e) {
        e.preventDefault();

        var modal = $('#updateRoleModal');

        modal.modal('show');
        modal.find('input[name="role_id"]').val($(this).data('id'));
        modal.find('input[name="role_name"]').val($(this).data('name'));
	});
	
	$('#updateRoleForm').on('submit', updateRole);
	$('.btn-update').on('click', updateRole);

	$(document).on('click', '.btn_delete_role', function(e) {
		e.preventDefault();

		swal.fire({
            title: 'Are you sure?',
            text: "This action will delete this item?, all users that associated with this role will be reset",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
			cancelButtonText: 'Cancel',
			focusConfirm: false,
            showLoaderOnConfirm: true
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: '/ajax/admin/role/delete/' + $(this).data('id'),
					method: 'POST',
					success: function(res) {
						Swal.fire('Success', res.message, res.status);
						table.ajax.reload();
					},
					error: function() {
						Swal.fire('Error', 'Something went wrong, please contact administrator', 'error');
					}
				});
			}
		});
	});

	$('#nav-permission-tab').on('show.bs.tab', function() {
		$.get('/ajax/admin/role/get-roles', function(response) {
			$('#assignPermission').find('input[type="radio"]').prop('checked', false);
			var roleSelect = $('#roleNameSelect');
			var options = `<option value="">Select Role</option>`;

			roleSelect.materialSelect({
				destroy: true
			});

			response.data.forEach(function(role) {
				options += `<option value="${role.role_id}">${role.role_name}</option>`;
			});

			roleSelect.html(options);

			roleSelect.materialSelect();
		});
	});

	$('#nav-role-tab').on('show.bs.tab', function() {
		if (table) {
			table.ajax.reload();
		}
	});

	$(document).on('click', '.btn_see_permission', function(e) {
		e.preventDefault();

		var modal = $('#seePermissionModal');
		permissions = $(this).data('permission');
		permissionKeys = Object.keys(permissions).sort();

		renderPermissionTable($('#permissionUser'), permissionKeys);
		modal.modal('show');
		inlineEdit.role_id = $(this).data('role-id');

	});

	$('#filterPermission').on('keyup', function(e) {
		var val = $(this).val();
		var filter = permissionKeys.filter(function(item) {
			return item.indexOf(val) > -1;
		});

		renderPermissionTable($('#permissionUser'), filter);
	});

	$('.allow_all').on('change', function(e) {
		var name = $(this).attr('name');

		$(".permission-item input[id^='"+name+"'][id$='.allow']").prop('checked', true);
		$(".permission-item input[id^='"+name+"'][id$='.not_allow']").prop('checked', false);
	});
	$('.disallow_all').on('change', function(e) {
		var name = $(this).attr('name');

		$(".permission-item input[id^='"+name+"'][id$='.allow']").prop('checked', false);
		$(".permission-item input[id^='"+name+"'][id$='.not_allow']").prop('checked', true);
	});

	$(document).on('dblclick', '.permission-editable', function(e) {
		var form = `<form class="form-inline inline-editing">`;
		currentEdit = $(this).text();
		currentRow = $(this);

		inlineEdit.permission_key = $(this).data('permission-key');

		form += `<div class="form-group">
				<select class="form-control edit-permission-inline">
					<option ${(currentEdit === 'allow') ? 'selected':''} value="allow">Allow</option>
					<option ${(currentEdit === 'not_allow') ? 'selected':''} value="not_allow">Not Allow</option>
				</select>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-sm btn-primary">Save</button>
				<button type="button" class="btn btn-sm btn-danger cancel-inline-edit">&times;</button>
			</div>
		</form>`;

		$(this).html(form);
	});

	$(document).on('click', '.cancel-inline-edit', function(e) {
		currentRow.html(currentEdit);
	});

	$(document).on('submit', '.inline-editing', function(e) {
		e.preventDefault();
		inlineEdit.permission_value = $(document).find('.edit-permission-inline').val();

		$.ajax({
			url: '/ajax/admin/role/edit-permission-value',
			method: 'POST',
			data: inlineEdit,
			success: function(res) {
				Swal.fire('Information', res.message, res.status);
				currentRow.html(inlineEdit.permission_value);
			},
			error: function(res) {
				Swal.fire('Information', 'Something went wrong, please contact the administrator!', 'error');
			}
		});
	});

	$('#seePermissionModal').on('hide.bs.modal', function(e) {
		cleanInlineEditingValue();
	});

	$(document).on('change','.check_permission',function() {
		let id = $(this).attr('id');
		if($(this).prop("checked") == true){
			$('#label_permission_'+id).text('Allow');
			$('#text_permission_'+id).val('allow');
		} else {
			$('#label_permission_'+id).text('Not Allow');
			$('#text_permission_'+id).val('not_allow');
		}
	})

	$(document).on('change','.check_module',function() {
		let id = $(this).attr('id');
		if($(this).prop("checked") == true){
			$('.'+id).prop('checked',true).trigger('change');
			$(this).parent().parent().parent().siblings('.child_tr_' + this.id).show();
		} else {
			$('.'+id).prop('checked',false).trigger('change');
			//$(this).parent().parent().parent().siblings('.child_tr_' + this.id).hide();
		}
	})

	$(document).on('change','.check_model',function() {
		let id = $(this).attr('id');
		if($(this).prop("checked") == true){
			$('.'+id).prop('checked',true).trigger('change');
		} else {
			$('.'+id).prop('checked',false).trigger('change');
		}
	})

	$(document).on('click','tr.parent',function(){
		$(this).siblings('.child_' + this.id).toggle();
	})

});