$(document).ready(function()
{
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

    var table = $('#partner_table').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/partner/listpartners",
            "type": "POST"
        },
        "columns": [
            { "data": "entity_family_name" },
            { "data": "partner_code" },
            { "data": "countryCode_id" },
            { "data": "status" },
            { "data": null },
        ],
        "columnDefs": [
            {
                "render": function ( data, type, row ) {
                    var code = data;
                    var link = $('#register_link').val()+'/'+code;
                    var html = code + '<br><button class="btn btn-sm btn-info btn-copy" title="copy link" data-link="'+link+'"><i class="far fa-copy"></i></button>';
                    return html;
                },
                "targets": 1
            },
            {
                "render": function ( data, type, row ) {
                    var country = JSON.parse(data);
                    var html = ''
                    $.each(country, (i,item) => {
                        html += '<label class="badge badge-info ml-1">'+item+'</label>'
                    })
                    return html;
                },
                "targets": 2
            },
            {
                "render": function ( data, type, row ) {
                    var html = ''
                    if(data == '1')
                        html += '<label class="badge badge-success ml-1">Active</label>'
                    else
                        html += '<label class="badge badge-warning ml-1">Disabled</label>'

                    if(row.partner_type!=null) {
                        var partner_type = JSON.parse(row.partner_type);
                        $.each(partner_type, (i,item) => {
                            html += '<label class="badge badge-info ml-1">'+item+'</label>'
                        })
                    } else {
                        html += '<label class="badge badge-info ml-1">'+row.type+'</label>'
                    }
                    return html;
                },
                "targets": 3
            },
            {
                "render": function ( data, type, row ) {
                    var link_edit = $('#partner_table').data('link-edit')
                    var html='<div class="text-right">';
                    if(row['filename']!='') {
                        html +='<a href="/ab/show/'+row['filename']+'" class="btn btn-sm btn-warning" data-toggle="lightbox" title="Banner Partner" data-type="image"><i class="fa fa-image"></i></a>';
                    }
                    html += '<a href="'+link_edit+'/'+data['address_book_id']+'" class="btn btn-sm btn-success" data-toggle="tooltip" title="Delete"><i class="fa fa-edit"></i></a>';
                    if(data.status == '1'){
                        html += '<a href="#" data-id="'+data.address_book_id+'" class="btn btn-sm btn-primary partner-disable-btn" data-toggle="tooltip" title="Disable"><i class="fa fa-ban"></i></a>';
                    }else{
                        html += '<a href="#" data-id="'+data.address_book_id+'" class="btn btn-sm btn-warning partner-enable-btn" data-toggle="tooltip" title="Enable"><i class="fa fa-user-check"></i></a>';
                    }
                        html += '<a href="#" data-id="'+data.address_book_id+'" class="btn btn-sm btn-danger partner-delete-btn" data-toggle="tooltip" title="Delete"><i class="fa fa-times"></i></a>';
                        html +='</div>';
                    return html;
                },
                "targets": -1
            },
        ],
    } );
    //confirmation dialog for each action

    $('body').on('click','.partner-delete-btn',function(e)
    {
        swal.fire({
            title: 'Delete Partner?',
            text: 'This action will delete this partner. Are You Sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete !'
        }).then((result) => {
            if(result.value)
            {
                const id = $(this).data('id');
                $.post('/ajax/partner/delete/'+id)
                    .done(rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Information!',
                            text: rs.message
                        });
                        table.ajax.reload();
                    })
            }
            return false;
		});
    });

    $('body').on('click','.partner-enable-btn',function(e)
    {
        swal.fire({
            title: 'Enable Partner?',
            text: 'This action will enable this partner. Are You Sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Enable !'
        }).then((result) => {
            if(result.value)
            {
				const id = $(this).data('id');
                $.post('/ajax/partner/enable/'+id)
                    .done(rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Information!',
                            text: rs.message
                        });
                        table.ajax.reload();
                    })
            }
            return false;
		});
        
    });

    $('body').on('click','.partner-disable-btn',function(e)
    {
        swal.fire({
            title: 'Disable Partner?',
            text: 'This action will disable this partner. Are You Sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Disable !'
        }).then((result) => {
            if(result.value)
            {
				const id = $(this).data('id');
                $.post('/ajax/partner/disable/'+id)
                    .done(rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Information!',
                            text: rs.message
                        });
                        table.ajax.reload();
                    })
            }
            return false;
		});
        
    });

    function copyToClipboard (str) {
        const el = document.createElement('textarea');
        el.value = str;
        el.setAttribute('readonly', '');
        el.style.position = 'absolute';
        el.style.left = '-9999px';
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
    };
    $('body').on('click','.btn-copy', function () {
        copyToClipboard($(this).data('link'))
    })
});