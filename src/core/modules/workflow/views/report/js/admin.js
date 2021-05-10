
var xhr = null
function search_list_staff() {
    let partner = $('#select_partner').val();
    
    if(xhr)
        xhr.abort();

    $('#address_book_id').materialSelect({
        destroy: true
    });
    $('#address_book_id').html('<option value="" disabled>Loading...</option>');
    $('#address_book_id').materialSelect();
    
    let html = '<option value="" disabled selected>Choose Address Book</option>';
    //post off the leaf for data
    xhr = $.post('/ajax/address_book/searchaddressbooks/workflow_reports/email', {type: 'per',partner:partner})
        .done(function (response)
        {
            
            if (response.length)
            {

                $('#address_book_id').prop('disabled',false);
                //data-secondary-text
                let html = '<option value="" disabled selected>Choose Address Book</option>';
                $.each(response, function(i, item)
                {
                    html += `<option value="${item.address_book_id}" data-secondary-text="${item.main_email}">${item.fullname} - ${item.main_email}</option>`
                });
                $('#address_book_id').html(html);
                
            }else{
                $('#address_book_id').html(html);
            }

            $('#address_book_id').materialSelect({
                destroy: true
            });
            $('#address_book_id').materialSelect();
        })
        .fail(function ()
        {
            Swal.fire({
                icon: 'error',
                title: 'Connection Failed',
                text: 'The check could not be done because we could not talk to the server.'
            });
            $('#address_book_id').html(html);
            $('#address_book_id').materialSelect({
                destroy: true
            });
            $('#address_book_id').materialSelect();
        });
        
}

$(document).ready(function () {


    $('#address_book_id, #principal_code, #level, #workflow_tracker, #level_update, #workflow_tracker_update, #select_partner, #level_type, #tracker, #partner').materialSelect()

    const table = $('#list_interview_security_report').DataTable( {
            "processing": true,
            "serverSide": true,
            'responsive': true ,
            "ajax": {
                "url": "/ajax/workflow/report/list",
                "type": "POST",
                data: function (d) {
                    d.level = $('#level_type').val()
                    d.tracker = $('#tracker').val()
                    d.partner = $('#partner').val()
                }
            },
            "columns": [
                { "data" : 'entity_family_name'},
                { "data" : 'level'},
                { "data" : null},
                { "data" : null},
                { "data" : null}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return row['fullname'] +' <br> '+row['main_email'];
                    },
                    "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        var html = ''
                        $.each(row.workflow, (index,item )=> {
                            html += '<span class="badge badge-info mr-1">'+item.workflow+'</span>'
                        })
                        return html;
                    },
                    "targets": 2
                },
                {
                    "render": function ( data, type, row ) {
                        return row['partner_fullname'];
                    },
                    "targets": 3
                },

                {
                    "render": function ( data, type, row ) {
                        //<a  class="btn-sm btn-light btn-edit" href="#" ><i class="fa fa-edit" title="Edit Data"></i></a>
                        var html = `<div class="container d-flex text-center ">
                                    <a class="btn-sm btn-danger text-white btn-delete" href="#" title=""><i class="fas fa-times"></i></a>
                                    <a class="btn-sm btn-success text-white btn-edit" href="#" title=""><i class="fas fa-pen"></i></a>
                                <div>`;
                        return html;
                    },
                    "targets": -1, "searchable": false, "orderable": false
                }
            ],
            'order': [
                [1, 'asc']
            ]
        }
    );
     $('#level_type, #tracker, #partner').on('change', function () {
        table.ajax.reload()
    });

    $('.btn-create-security').on('click', function () {
        search_list_staff();
        $('#interview_security_modal').modal('show')
    })

    $('#principal_brand_code').change(function(){
       var principal_code =  $(this).find(':selected').data('principal-code')
        $('#principal_code').val(principal_code)
    })

    $('#interview_security_form').on('submit', function () {
        var btn = $(this).find('button[type=submit]');
        var text = btn.html()
        btn.attr('disabled',true);
        btn.html('Saving....');
        $.ajax({
            url: '/ajax/workflow/report/insert',
            data: $(this).serialize(),
            type: 'POST',
            datatype : 'json',
            success: function(rs) {
                Swal.fire({
                    icon: 'success',
                    title: 'Notification!',
                    text: rs.message
                });
                table.ajax.reload();
                $('#interview_security_form').trigger("reset");
                $('#interview_security_modal').modal('hide')
                btn.attr('disabled',false);
                btn.html('Save');
                //$('#div_ab').hide();
                $('#level, #workflow_tracker, #address_book_id, #select_partner').materialSelect({
                    destroy: true
                    });
                
                $('#level, #address_book_id, #select_partner, #workflow_tracker').val('');
                $('#level, #workflow_tracker, #address_book_id, #select_partner').materialSelect();
            },
            error: function(response) {

                btn.attr('disabled',false);
                btn.html('Save');
                if(response.status == 400)
                {
                    text = ''
                    $.each(response.responseJSON.errors, (index,item) => {
                        text += item + '<br>';
                    })
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: text
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something errors. Please contact admin support!'
                    });
                }
            }
        });
        return false;
    })
    $('#interview_security_update_form').on('submit', function () {
        var btn = $(this).find('button[type=submit]');
        var text = btn.html()
        btn.html('Saving...');
        btn.attr('disabled',true);
        $.ajax({
            url: '/ajax/workflow/report/update',
            data: $(this).serialize(),
            type: 'POST',
            datatype : 'json',
            success: function(rs) {
                Swal.fire({
                    icon: 'success',
                    title: 'Notification!',
                    text: rs.message
                });
                table.ajax.reload();
                $('#interview_security_update_form').trigger("reset");
                $('#interview_security_update_modal').modal('hide')
                btn.attr('disabled',false);
                btn.html('Save');
            },
            error: function(response) {

                btn.attr('disabled',false);
                btn.html('Save');
                if(response.status == 400)
                {
                    text = ''
                    $.each(response.responseJSON.errors, (index,item) => {
                        text += item + '<br>';
                    })
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: text
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something errors. Please contact admin support!'
                    });
                }
            }
        });
        return false;
    })

    search_list_staff();
    $('body').on('change','#select_partner',function()
    {
        search_list_staff();
    });



    $('body').on('click','.btn-delete', function () {
        const data = table.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Remove Interview Security',
            text: "You will delete this item?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if(result.value)
                $.ajax({
                    url: '/ajax/workflow/report/delete',
                    data: data,
                    type: 'POST',
                    datatype : 'json',
                    success: function(rs) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload();
                        //$('#interview_security_form').trigger("reset");
                        $('#interview_security_modal').modal('hide')
                    },
                    error: function(response) {

                        btn.attr('disabled',false);
                        btn.html(text);
                        if(response.status == 400)
                        {
                            text = ''
                            $.each(response.responseJSON.errors, (index,item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
        })
        return false;
    })
    $('body').on('click','.btn-edit', function () {
        const data = table.row(this.closest('tr')).data();
        $('#interview_security_update_modal #modal-title').html('Update Reports - '+data.fullname);
        $('#interview_security_update_modal').modal('show')
        $('#level_update, #workflow_tracker_update').materialSelect({
            destroy: true
            });
        $('#level_update').val(data.level)
        $('#address_book_id_update').val(data.address_book_id)

        //var tracker = $('#workflow_tracker_update').val();
        var tracker = [];
        $.each(data.workflow, (i, item) => {
            tracker.push(item.workflow)
        })
        $('#workflow_tracker_update').val(tracker)

        $('#level_update, #workflow_tracker_update').materialSelect();
        return false;
    })

});
