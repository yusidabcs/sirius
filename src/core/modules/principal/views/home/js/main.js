$(document).ready(function() {
    var table = $('#list_principal').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/principal/all",
            "type": "POST"
        },
        "columns": [
            { "data": "name" },
            { "data": null },
        ],
        "columnDefs": [
            {
                "render": function ( data, type, row ) {
                    var html = row['name'];
                     html += '<br>( '+row['code']+' )'
                    return html;
                },
                "targets": 0
            },
            {
                "render": function ( data, type, row ) {
                    var html = '<div class="text-right"><a href="/principal/edit/'+row['address_book_id']+'"><i class="far fa-edit text-success" title="Edit"></i></a>';
                    var html = html+'<button type="button" data-id="'+row['address_book_id']+'" class="p-1 btn btn-link delete-principal"><i class="fa fa-times text-danger" title="Delete"></i></button></div>';
                    return html;
                },
                "targets": -1
            },
        ],
    } );


    //post off the leaf for data
    $(document).on('click','.delete-principal', function () {
        swal.fire({
			title: 'Delete this item?',
			text: 'Are you sure! Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.value)
            {
                const id = $(this).data('id');
                $.post('/ajax/principal/delete/'+id)
                    .done(rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Information!',
                            text: rs.message
                        });
                        table.ajax.reload();
                    })
            }
        });

    });

} );