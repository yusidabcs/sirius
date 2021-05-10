$(document).ready(function () {

    $('#table_status_search').materialSelect();
    $('#table_lp_search').materialSelect();

    const table = $('#list_education_course_request').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/ajax/education/request/list",
                "type": "POST",
                cache: false,
                data: function (d) {
                    d.status = $('#table_status_search').val()
                    d.start_date = $('#startingDate').val()
                    d.end_date = $('#endingDate').val()
                    d.partner = $('#table_lp_search').val()
                }
            },
            "columns": [
                {"data": null},
                {"data": 'fullname'},
                {"data": 'partner_name'},
                {"data": 'course_name'},
                {"data": 'status'},
                {"data": 'created_on'},
                {"data": null}
            ],
            "order" : [],
            "columnDefs": [
                {
                    render: function (data, type, row, meta) {
                        let id = row.course_request_id;
                        var html =`
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input chk_req" id="chk_req_`+id+`" name="chk_req" value="`+id+`">
                            <label class="custom-control-label" for="chk_req_`+id+`"></label>
                        </div>
                        `;
                        return html;
                    },
                    "searchable": false, "orderable": false, "targets": 0
                },
                {
                    "render": function (data, type, row) {
                        return data+'<br>' + row.main_email;
                    },
                    "searchable": true, "orderable": true,"targets": 1
                },
                {
                    "render": function ( data, type, row ) {
                        let id = row.course_request_id;
                        let status = row.status;
                        var html = 
                            '<a id="btn_update_status" data-id="'+id+'" data-status="'+status+'" data-toggle="modal" class="col-sm-6 btn-sm btn-light" href="#"><i class="fa fa-edit" title="Edit Status"></i></a>';
                        return html;
                    },
                    "searchable": false, "orderable": false,"targets": 6
                },

                {
                    render: function (data, type, row, meta) {
                        var html = ''
                        if(data == 'request'){
                            html = '<span class="badge bg-warning">Request</span>'
                        }
                        else if(data == 'accepted'){
                            html = '<span class="badge bg-success">Accepted</span>'
                        }
                        else if(data == 'enrolled'){
                            html = '<span class="badge bg-info">Enrolled</span>'
                        }else if(data == 'cancel'){
                            html = '<span class="badge bg-dark">Cancel</span>'
                        }
                        else{
                            html = '<span class="badge bg-primary">'+data+'</span>'
                        }
                        return html
                    },
                    "searchable": false, "targets": -3
                },
            ]
        });
        
        $('#chk_all').click(function(){
            if($(this).is(":checked")){
                $('.chk_req').prop('checked',true);
            } else{
                $('.chk_req').prop('checked',false);
            }
        });

    $('#table_status_search, #table_level_search, #startingDate, #endingDate, #table_lp_search').on('change', function () {
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

    

    $(document).on('click','#update_all_status',function () 
	{
        let array = []; 
        $("input:checkbox[name=chk_req]:checked").each(function() { 
            array.push($(this).val()); 
        }); 
        if(array.length==0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please checklist item to update!',
            });
        } else {
            //start modal update all status
            $arr_status = {
                Request:'request',
                Accepted:'accepted',
                Enrolled:'enrolled',
                Finish:'finish',
                Cancel:'cancel'
            };
            var status = 'accepted';
            let combo_request = `
            <div class="row pt-2 pb-2">
                <div class="col-lg-10 offset-lg-1">
                    <select id="combo_change_status" class="browser-default custom-select">`;
                        $.each($arr_status,function(i,val) {
                            let select="";
                            if(val==status){
                                select="selected";
                            }
                            combo_request += `<option value="`+val+`" `+select+`>`+i+`</option>`;
                        });
            combo_request += `</select>
                </div>
            </div>
            `;
            $('#combo_change_status').val(status);
            swal.fire({
                title: "Change All Status Selected",
                html: combo_request,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Update',
                cancelButtonText: 'Cancel',
                focusConfirm: false,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    let status_select = $('#combo_change_status').val();
                        return fetch(`/ajax/education/request/change-all-status`,{
                            headers: {
                                "Content-Type": "application/json"
                              },
                            method : 'POST',
                            body : JSON.stringify({
                                id: array,
                                status_select: status_select
                              })
                        })
                        .then(response => {
                            if (!response.status) {
                                Swal.showValidationMessage(`Error could not delete user data.`)
                            }
                            return response.json()
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                            `Request failed: ${error}`
                            )
                        })
                    
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        icon: 'success',
                        html: result.value.message,
                    })
                    if (result.value.status=='ok') {
                        table.ajax.reload();
                        $('#chk_all').prop('checked',false);
                    }
                }
            })
            //end modal update all status
        }
        
		
    })


    
    $(document).on('click','#btn_update_status', function () 
    {
        $arr_status = {
            Request:'request',
            Accepted:'accepted',
            Enrolled:'enrolled',
            Finish:'finish',
            Cancel:'cancel'
        };
        var status = $(this).data('status');
        let combo_request = `
        <div class="row pt-2 pb-2">
            <div class="col-lg-10 offset-lg-1">
                <select id="combo_change_status" class="browser-default custom-select">`;
                    $.each($arr_status,function(i,val) {
                        let select="";
                        if(val==status){
                            select="selected";
                        }
                        combo_request += `<option value="`+val+`" `+select+`>`+i+`</option>`;
                    });
        combo_request += `</select>
            </div>
        </div>
        `;
        var id = $(this).data('id');
        $('#combo_change_status').val(status);
        swal.fire({
            title: "Change Status",
			html: combo_request,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Update',
			cancelButtonText: 'Cancel',
			focusConfirm: false,
            showLoaderOnConfirm: true,
            preConfirm: () => {
				let status_select = $('#combo_change_status').val();
					return fetch(`/ajax/education/request/change-status`,{
						headers: {
							"Content-Type": "application/json"
						  },
						method : 'POST',
						body : JSON.stringify({
							id: id,
							status_select: status_select
						  })
					})
					.then(response => {
					    if (!response.status) {
					        Swal.showValidationMessage(`Error could not delete user data.`)
					    }
					    return response.json()
					})
					.catch(error => {
					    Swal.showValidationMessage(
					    `Request failed: ${error}`
					    )
					})
                
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    icon: 'success',
                    html: result.value.message,
				})
				if (result.value.status=='ok') {
					table.ajax.reload();
				}
            }
        })
    });
    $('#epxort_request').on('click',function(){
        let status = $('#table_status_search').val();
        let partner = $('#table_lp_search').val();
        let start_date = $('#startingDate').val();
        let end_date = $('#endingDate').val();

        let mapForm = document.createElement("form");
        mapForm.target = "_blank";    
        mapForm.method = "POST";
        mapForm.action = "/ajax/education/request/export";

        // Create an input
        let input_status = document.createElement("input");
        input_status.type = "text";
        input_status.name = "status";
        input_status.value = status;

        let input_partner = document.createElement("input");
        input_partner.type = "text";
        input_partner.name = "partner";
        input_partner.value = partner;

        let input_start_date = document.createElement("input");
        input_start_date.type = "text";
        input_start_date.name = "start_date";
        input_start_date.value = start_date;

        let input_end_date = document.createElement("input");
        input_end_date.type = "text";
        input_end_date.name = "end_date";
        input_end_date.value = end_date;
        // Add the input to the form
        mapForm.appendChild(input_status);
        mapForm.appendChild(input_partner);
        mapForm.appendChild(input_start_date);
        mapForm.appendChild(input_end_date);

        // Add the form to dom
        document.body.appendChild(mapForm);

        // Just submit
        mapForm.submit();

        document.body.removeChild(mapForm);
    })
});