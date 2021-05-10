$(document).ready(function()
{
    $('#table_partner_search').materialSelect();
    $('#table_country_search').materialSelect();
    $('#table_register_method').materialSelect();


    const table =  $('#list_verification').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/recruitment/recruitment",
            "type": "POST",
            data: function (d) {
                d.partner_id = $('#table_partner_search').val()
                d.status = 'request_ver'
                d.country = $('#table_country_search').val()
                d.register_method = $('#table_register_method').val()
            }
        },
        "columns": [
            { "data": "fullname" },
            { "data": "main_email" },
            { "data": "country" },
            { "data": "partner_name" },
            { "data": "status" },
            { "data": null, "searchable": false, "sortable": false },
        ],
        "columnDefs": [
            {
                "render": function(data, type, row) {
                    var flag = '';

                    if (row.created_by == 0) {
                        flag = '<span class="badge badge-success">Registered From Public</span>';
                    } else {
                        flag = '<span class="badge badge-warning">Registered From Admin Inputed</span>';
                    }

                    return row.fullname + '<br>' + flag;
                },
                "targets": 0
            },
            {
                "render": function ( data, type, row ) {
					if (((row.partner_name == null) || (row.partner_name == ''))&&((row.partner_lep_name == null) || (row.partner_lep_name == ''))) {
                        data = '<span class="text-warning">No partner</span>';
                    } else {
                        let html='';
                        if ((row.partner_name != null) && (row.partner_name != '')){
                            html += row.partner_name+' <span class="badge badge-primary"> LP</span>';
                        }
                        if ((row.partner_lep_name != null) && (row.partner_lep_name != '')){
                            if(html!='') {
                                html +='<br>';
                            }
                            html += row.partner_lep_name+' <span class="badge badge-primary"> LEP</span>';
                        }
                        data=html;
                    }
                    return data;
                },
                "targets": 3
            },
            {
                "render": function ( data, type, row ) {
                    const hide = table.ajax.json().hide;
                    var html = '<div class="d-flex justify-content-around"><a class="p-1 white border" href="/personal/home/'+row['address_book_id']+'"><i class="far fa-user text-success" title="Show Personal"></i></a>';
                    html += '<button type="button" data-id="'+row['address_book_id']+'" class="p-1 white border show_summary"><i class="fas fa-search-plus text-info" title="Show Summary"></i></button>';
                    if (hide != 'hide')
                        html += '<button data-toggle="modal" class="change_local_partner p-1 white border " data-id="' + row.address_book_id + '" data-partner-id="' + row.partner_id + '" data-lep-id="' + row.partner_lep_id + '"><i class="fas fa-handshake text-warning" title="Edit Partner"></i></button>'; 
                    html +=  '<button type="button" data-id="'+row['address_book_id']+'" class="p-1 white border edit_verification"><i class="fas fa-edit text-warning" title="Edit Verification"></i></button>';
                    html +=  '<button type="button" data-id="'+row['address_book_id']+'" class="p-1 white border show_history"><i class="fas fa-history text-info" title="Show History"></i></button></div>';
                    
                    return html;
                },
                "targets": -1
            },
            {
                "render": function ( data, type, row ) {
                    if ((data == null) || (data == ''))
                        data = '<span class="badge badge-light">Unverified</span>';
                    if(data == 'unverified')
                        return '<span class="badge badge-light">Unverified</span>';
                    else if(data == 'process')
                        return '<span class="badge badge-default">Process</span>';
                    else if(data == 'request')
                        return '<span class="badge badge-info">Request</span>';
                    else if(data == 'verified')
                        return '<span class="badge badge-success">Verified</span>';
                    else if(data == 'rejected')
                        return '<span class="badge badge-dark">Rejected</span>';
                    return data;
                },
                "targets": -2
            },
        ],
    } );


    $('#table_partner_search, #table_country_search, #table_register_method').on('change', function () {
        table.ajax.reload()
    });

    $(document).on('click', '.edit_verification', function () {
        //show edit modal
        const data = table.row(this.closest('tr')).data();
        if(data.partner_id == null){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select the LP for this candidate!'
            });
            return;
        }
        $('#edit_verification_modal').modal('show');
        $('#verification_status').val(data.status);
        $('#verification_info').html(data.info).change();
        $('#edit_id').val(data.address_book_id);
    });

    $('#list_verification tbody').on( 'click', '.show_history', function () 
    {
        //load ajax history data 
        $('#history_verification_modal').modal('show');
        $.ajax({
            url: "/ajax/recruitment/status/getVerificationHistory",
            type: 'POST',
            data: {
                dt_id : $(this).data('id')
            },
            cache: false,
            timeout: 10000
        })
        .done(response => {
            let str = '';
            if (response.length)
            {
                str = 
                `<ul class="list-group">
                    <li class="list-group-item">
                        <span class="row">
                            <span class="col-md-4"><b>Timestamp</b></span>
                            <span class="col-md-4 "><b>Status</b></span>
                            <span class="col-md-4"><b>Information</b></span>
                        </span>
                    </li>`; 
                let reject_count = 0;
                
                $.each(response,function(key,data)
                {
                    if ( data.status == 'rejected' ){
                        reject_count++;
                    }

                    str += 
                    `<li class="list-group-item ">
                        <span class="row">
                            <span class="col-md-4">${data.created_on}</span>
                            <span class="col-md-4">${data.status}</span>
                            <span class="col-md-4">${data.verification_info}</span>
                        </span>
                    </li>`; 
                })

                str += '</ul>';

                if ( reject_count >= 3 )
                {   
                    str = '<p class="alert alert-danger">This user has been rejected ' +reject_count + ' times</p>'+str;
                }

            }else{
				str = 'No verification History';
            }
            $('#history_verification_modal .modal-body').html(str);
        })
        .fail(response => {
            Swal.fire({
              type: 'error',
              title: 'Oops...',
              text: 'Connection to Server Failed!'
            });
        });
        
    });
    
    //Local partner modal
    $(document).on('click', '.change_local_partner', function () {
        selected_address_book_id = $(this).data('id');
        selected_partner_id = $(this).data('partner-id');
        selected_lep_id = $(this).data('lep-id');
        var btn = $(this);
        btn.attr('disabled', true);
        //get all local partner AJAX
        var partner_xhr = $.ajax({
            url: "/ajax/recruitment/partner",
            type: 'POST',
            data: {
                address_book_id: selected_address_book_id,
                action: 'get'
            },
            cache: false,
            timeout: 10000
        })
            .done(function (answer) {

                if (answer.good) {
                    $('#partner_new').empty();
                    $('#partner_lep').empty();
                    let html = '<option value="">Select Partner</option>';
                    $.each(answer.reply.data_lp, function (key, data) {
                        if (data.id == selected_partner_id) {
                            html += '<option value="' + data.id + '" selected>' + data.name + '</option>';
                        } else {
                            html += '<option value="' + data.id + '">' + data.name + '</option>';
                        }
                    })
                    $('#partner_new').append(html);
                    $('#partner_new').materialSelect();

                    html = '<option value="">Select Partner</option>';
                    $.each(answer.reply.data_lep, function (key, data) {
                        if (data.id == selected_lep_id) {
                            html += '<option value="' + data.id + '" selected>' + data.name + '</option>';
                        } else {
                            html += '<option value="' + data.id + '">' + data.name + '</option>';
                        }
                    })
                    $('#partner_lep').append(html);
                    $('#partner_lep').materialSelect();
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: answer.note,
                    });
                }

                $('#partner_modal').modal('show');
                btn.attr('disabled', false);
            })
            .fail(function () {
                Swal.fire({
                    icon: 'error',
                    text: "Error could not fetch list partner.",
                });
                btn.attr('disabled', false);
            });
    })

    //delete partner
    $('.partner_delete').click(function (e) {
        //check current partner for this user
        const type = $(this).data('type');
        //const new_partner_id = type=='lp'?$("#partner_new").val():$("#partner_lep").val();
        const current_partner = type=='lp'?selected_partner_id:selected_lep_id;
        let text = "";
        if(type=="lep") {
            text = "Education";
        }
        if(current_partner!=null && current_partner!='') {
            const modal = $("#partner_modal");
            swal.fire({
                title: "Are You Sure?",
                text: "This action will remove License "+text+" Partner from this user. ",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'No'
            }).then((result) => {

                if (result.value) {
                    $('.partner_change').prop('disabled',true);
                    $('.partner_delete').html('<i class="fas fa-spinner fa-spin"></i> DELETING...').prop('disabled',true);
                    //ok good to go
                    $.ajax({
                        url: "/ajax/recruitment/partner",
                        type: 'POST',
                        data: {
                            action: 'delete',
                            type:type,
                            address_book_id: selected_address_book_id,
                            current_partner_id: selected_partner_id
                        },
                        cache: false,
                        timeout: 10000
                    })
                        .done(function (answer) {
                            // const answer = jQuery.parseJSON(msg);
                            if (answer.good) {
                                //clear values
                                modal.modal('toggle');
                                Swal.fire({
                                    icon: 'success',
                                    text: answer.message,
                                })
                                table.ajax.reload(null, false);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    text: answer.note,
                                });
                            }
                            $('.partner_change').prop('disabled',false);
                            $('.partner_delete').html('DELETE PARTNER?').prop('disabled',false);
                        })
                        .fail(function () {
                            Swal.fire({
                                icon: 'error',
                                text: "Error could not update partner.",
                            });
                            $('.partner_change').prop('disabled',false);
                            $('.partner_delete').html('DELETE PARTNER?').prop('disabled',false);
                        });
                }
                ;
            });

            return;
        } else {
            Swal.fire({
                icon: 'error',
                text: "The User has no License "+text+" Partner!",
            });
        }
    });

    //partner edit
    $('.partner_change').click(function (e) {
        const type = $(this).data('type');
        const new_partner_id = type=='lp'?$("#partner_new").val():$("#partner_lep").val();
        const current_partner = type=='lp'?selected_partner_id:selected_lep_id;
        // const partnerNew = $("#partner_new");
        // const new_partner_id = partnerNew.val();
        const modal = $("#partner_modal");
        if (new_partner_id > 0) {
            if (new_partner_id != current_partner) {
                $('.partner_change').html('<i class="fas fa-spinner fa-spin"></i> SAVING...').prop('disabled',true);
                $('.partner_delete').prop('disabled',true);
                $.ajax({
                    url: "/ajax/recruitment/partner",
                    type: 'POST',
                    data: {
                        action: 'change',
                        type:type,
                        address_book_id: selected_address_book_id,
                        new_partner_id: new_partner_id,
                        current_partner_id:current_partner
                    },
                    cache: false,
                    timeout: 20000
                })
                    .done(function (answer) {
                        if (answer.good) {
                            //clear values
                            if(type=="lep") {
                                selected_lep_id = new_partner_id;
                            } else {
                                selected_partner_id = new_partner_id;
                            }
                            //modal.modal('toggle');
                            Swal.fire({
                                icon: 'success',
                                text: answer.message,
                            })
                            table.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: answer.note,
                            });
                        }
                        $('.partner_change').html('CHANGE PARTNER').prop('disabled',false);
                        $('.partner_delete').prop('disabled',false);
                    })
                    .fail(function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            text: "Error could not update the partner.",
                        });
                        console.log(xhr);
                        $('.partner_change').html('CHANGE PARTNER').prop('disabled',false);
                        $('.partner_delete').prop('disabled',false);
                    });
            } else {
                Swal.fire({
                    icon: 'warning',
                    text: 'Partner data unchanged',
                });
            }
        } else {
            Swal.fire({
                icon: 'warning',
                text: 'You must select a partner first.',
            });
        }
        return;

    });

    $('#edit_verification_btn').click(function()
    {
        var btn = $(this)
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
					  icon: 'success',
					  title: 'Verification status edited!',
					  text: response.message
					});

					$('#edit_verification_modal').modal('hide');
                    table.ajax.reload();
                    btn.attr('disabled', false);
                    btn.html('Edit');
				})
				.fail(response => {
					Swal.fire({
					  icon: 'error',
					  title: 'Oops...',
					  text: 'Connection to Server Failed!'
                    });
                    btn.attr('disabled', false);
                    btn.html('Edit');
				});
            }
        });
    });

    //post off the leaf for data
    $(document).on('click','.show_summary', function () 
    {
        const id = $(this).data('id');
        $('#summary_modal').modal('show')
        $.post('/ajax/recruitment/summary/'+id)
            .done(rs => {
                if(Object.keys(rs).length > 0)
				{
					$('#summary').html('');

					$.each(rs, function(key,val){
						if(key == 'created_on' || key == 'modified_on'){
							return;
						}
						$('#summary').append(`
							<div class="col-sm-6 col-md-4 col-lg-3 mb-3">
								<div class="card p-3 text-center">
									<i class="fas ${val.icon} fa-3x ${(val.value > 0 ? 'text-info' : 'text-warning')} "></i>
									<p class="mb-0">
										${key} <br>
										<span class="h4">${val.value}</span>
									</p>
								</div>
							</div>`);
					});

				}else{
					$('#summary').html('Personal data not inputted yet!');
				}
            })
    });

} );