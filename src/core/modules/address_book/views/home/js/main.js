$(document).ready(function() {
    var table = $('#user_list_datatable').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/address_book/listaddressbook",
            "type": "POST"
        },
        "columns": [
            { "data": "address_book_name" },
            { "data": "main_email" },
            { "data": "type" },
            { "data": "created_on" },
            { "data": null },
        ],
        "columnDefs": [
            {
                "render": function ( data, type, row ) {
                    if(data == 'per'){
                        return 'Personal'
                    }else{
                        return 'Organisation'
                    }
                },
                "targets": 2
            },
            {
                "render": function ( data, type, row ) {
                    var link_edit = $('#user_list_datatable').data('edit-link');
                    var html = '<a href="'+link_edit+'/'+row['address_book_id']+'"><i class="far fa-edit text-success" title="Edit"></i></a>';
                    html += "<a data-toggle='modal' data-target='#address_book_preview' class='address_book_preview' href='#' data-ab='"+JSON.stringify(row)+"'>" +
                        '                        <i class="fas fa-eye" title="Preview"></i>' +
                        '                        </a>';
                    return html;
                },
                "targets": -1
            },
        ],
    } );

    $('body').on('click','.address_book_preview', function () {
        var ab = $(this).data('ab');

        xhr = $.post('/ajax/address_book/main/getAddressBook/'+ab.address_book_id)
            .done(function (d) {

                if(d.type == 'per') {
                    var name = d.title + ' ' + d.number_given_name + ' ' + d.middle_names + ' ' + d.entity_family_name;
                    $('#address_book_preview #ab_gender').html(d.sex)
                    $('#address_book_preview #ab_born').html(d.dob)
                    $('#address_book_preview #ab_cn_tr').hide()
                    $('#address_book_preview #ab_key_contacts_tr').hide()
                }else {
                    var name = d.entity_family_name ;
                    $('#address_book_preview #ab_cn_tr').show()
                    $('#address_book_preview #ab_key_contacts_tr').show()
                    $('#address_book_preview #ab_gender_tr').hide()
                    $('#address_book_preview #ab_born_tr').hide()
                    $('#address_book_preview #ab_cn').html(d.number_given_name)

                    if(d.ent_admin_details){
                        $('#address_book_preview #ab_key_contacts').html('')
                        $.each(d.ent_admin_details, function (index, item) {
                            $('#address_book_preview #ab_key_contacts').append('<p>'+item.full_name+ '(' + item.email + ')</p>')
                        })
                    }
                }

                if(d.file.length > 0){
                    $('#address_book_preview #ab_avatar').attr('src', 'ab/show/'+d.file[0].filename);
                }else{
                    $('#address_book_preview #ab_avatar').hide()
                }

                $('#address_book_preview .title').html(name);
                d.username ? $('#address_book_preview #ab_username').html(d.username) : '';
                d.main_email ? $('#address_book_preview #ab_email').html(d.main_email ) : '';

                if(d.pots.length > 0){
                    $('#address_book_preview #ab_pots').html('')
                    $.each(d.pots, function (index, item) {
                        $('#address_book_preview #ab_pots').append('<p>'+item.number+ '(' + item.type + ')</p>')
                    })
                }

                if(d.internet.length > 0){
                    $('#address_book_preview #ab_internet').html('')
                    $.each(d.internet, function (index, item) {
                        $('#address_book_preview #ab_internet').append('<p>'+item.id+ '(' + item.type + ')</p>')
                    })
                }

                d.address.main != undefined ? $('#address_book_preview #ab_address').html(d.address.main.care_of +' <br> '
                    +d.address.main.line_1 + '<br>'
                    +d.address.main.line_2 + '<br>'
                    +d.address.main.suburb + '<br>'
                    +d.address.main.state + '<br>'
                    +d.address.main.postcode + '<br>'
                    +d.address.main.country_full + '<br>'
                    ) : '';



            })
            .fail(function () {

            });


    })

} );