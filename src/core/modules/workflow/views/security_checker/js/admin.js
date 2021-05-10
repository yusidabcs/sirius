$(document).ready(function () {


    $('#address_book_id, #principal_code, #countryCode_id').materialSelect()

    const table = $('#list_interview_security').DataTable( {
            "processing": true,
            "serverSide": true,
            'responsive': true ,
            "ajax": {
                "url": "/ajax/workflow/security_checker/list",
                "type": "POST",
                data: function (d) {
                }
            },
            "columns": [
                { "data" : 'principal_code'},
                { "data" : 'countryCode_id'},
                { "data" : 'checker'},
                { "data" : null}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return row.checker + ' <br> ' + row.main_email
                    },
                    "targets": -2
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
        $.ajax({
            url: '/ajax/workflow/security_checker/insert',
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
            xhr = $.post('/ajax/address_book/searchaddressbooks/principal/'+searchString)
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
                        setTimeout(function(){
                            $('#select-options-address_book_id').css({
                                'display': 'block',
                                'position': 'absolute',
                                'top': '0px',
                                'left': '0px',
                                'opacity': '1'
                            });
                        }, 200);

                    }else{

                        $('#search_ab').siblings('.invalid-feedback').show()
                        $('#div_ab, #principal_area').hide();
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


    $('#startingDate, #endingDate').on('change', function () {
        table.ajax.reload();
    });


    $('body').on('click','.btn-delete', function () {
        const data = table.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Remove Interview Security',
            text: "You will remove interview security from database.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if(result.value)
                $.ajax({
                    url: '/ajax/workflow/security_checker/delete',
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
                        $('#interview_security_form').trigger("reset");
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

});
