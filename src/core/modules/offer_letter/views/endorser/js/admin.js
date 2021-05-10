$(document).ready(function () {


    $('#address_book_id, #job_master_id').materialSelect()

    const table = $('#list_offer_letter').DataTable( {
            "processing": true,
            "serverSide": true,
            'responsive': true ,
            "ajax": {
                "url": "/ajax/offer_letter/endorser/list",
                "type": "POST",
                data: function (d) {
                }
            },
            "columns": [
                { "data" : 'job_title'},
                { "data" : 'endorser',orderable: false},
                { "data" : 'allowance_days'},
                { "data" : null}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return row.principal_code + ' ' + row.brand_code + ' <br> ('+row.job_code+')' + row.job_title
                    },
                    "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        return row.endorser + ' <br> ' + row.main_email
                    },
                    "targets": 1
                },

                {
                    "render": function ( data, type, row ) {
                        //<a  class="btn-sm btn-light btn-edit" href="#" ><i class="fa fa-edit" title="Edit Data"></i></a>
                        var html = `<div class="container d-flex text-center ">
                                    <a class="btn-sm btn-danger text-white btn-delete" href="#" title=""><i class="fas fa-times"></i></a>
                                <div>`;
                        return html;
                    },
                    "targets": -1
                }
            ],
            'order': [
                [1, 'asc']
            ]
        }
    );



    $('.btn-create-security').on('click', function () {
        $('#offer_letter_modal').modal('show')
    })

    $('#principal_brand_code').change(function(){
       var principal_code =  $(this).find(':selected').data('principal-code')
        $('#principal_code').val(principal_code)
    })

    $('#offer_letter_form').on('submit', function () {
        var btn = $(this).find('button[type=submit]');
        var text = btn.html()
        btn.attr('disabled',true);
        $.ajax({
            url: '/ajax/offer_letter/endorser/insert',
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
                $('#offer_letter_form').trigger("reset");
                $('#offer_letter_modal').modal('hide')
                btn.attr('disabled',false);
                btn.html(text);
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
        return false;
    })

    var timer_search_ab;
    var xhr = null
    $('body').on('keyup','#search_ab',function()
    {
        const spinner = $('#search_ab_spinner');
        const searchString = $('#search_ab').val().trim();
        clearTimeout(timer_search_ab);
        spinner.hide();

        if(searchString == '')
            return;

        spinner.show();

        //Give a second delay to see if the user is finished typing to reduce many ajax call per user keyup
        timer_search_ab = setTimeout(function()
        {
            $('#address_book_id').find('option:not([disabled])').remove();

            if(xhr)
                xhr.abort();

            //post off the leaf for data
            xhr = $.post('/ajax/address_book/searchaddressbooks/offer_letter_endorser/'+searchString, {type: 'per'})
                .done(function (response)
                {
                    if (response.length)
                    {
                        $('#search_ab').siblings('.invalid-feedback').hide();

                        $('#div_ab').show();
                        $('#address_book_id').prop('disabled',false);
                        //data-secondary-text
                        let html = '';
                        $.each(response, function(i, item)
                        {
                            html += `<option value="${item.address_book_id}" data-secondary-text="${item.main_email}">${item.entity_family_name}</option>`
                            // $('#address_book_id').append(new Option(item.main_email, item.address_book_id));
                        });
                        $('#address_book_id').append(html);

                    }else{

                        $('#search_ab').siblings('.invalid-feedback').show()
                        $('#div_ab').hide();
                    }
                })
                .fail(function ()
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Failed',
                        text: 'The check could not be done because we could not talk to the server.'
                    });

                }).always(function()
                {
                    spinner.hide();
                });

        }, 1000);

    });

    $('body').on('click','.btn-delete', function () {
        const data = table.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Remove Item',
            text: "You will remove this data from database.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if(result.value)
                $.ajax({
                    url: '/ajax/offer_letter/endorser/delete/'+data.job_master_id,
                    type: 'POST',
                    datatype : 'json',
                    success: function(rs) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload();
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

});
