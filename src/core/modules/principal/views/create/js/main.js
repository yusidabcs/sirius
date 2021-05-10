$(document).ready(function()
{
    $('#address_book_id').materialSelect();

    var xhr = null;
    var edit_brand = false;
    var brand = null;
    var principal = {
            brands: [],
        };

    $('#principal_area').hide();

    $('#address_book_id').hide().prop('disabled',true);

    var timer_search_ab;

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
            $('#address_book_id').hide().find('option:not([disabled])').remove();

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

    $('#address_book_id').on('change', function()
    {    
        $('#principal_area').show();
        principal.address_book_id = $(this).val()

    });

    $('#add_brand').on('click', function()
    {
        edit_brand = false;
        $('.btn-brand').attr('disabled',true);
        $('#code').siblings('.invalid-feedback').hide();
        $('#brand_form').find('#name').val('');
        $('#brand_form').find('#code').val('');
        $('#brand_form').find('#code_valid').hide();
        $('#brand_form_modal').modal('show');

    });

    $(document).on('click','.edit-brand', function()
    {
        edit_brand = true;
        $('.btn-brand').attr('disabled',false);
        $('#code').siblings('.invalid-feedback').hide();
        $('#brand_form').find('#code_valid').hide();
        const selectedCode = $(this).data('code');
        principal.brands.forEach(function(item, i) 
        {
            if( item.code == selectedCode )
            {
                brand = item;
                $('#brand_form').find('#name').val(item.name).change();
                $('#brand_form').find('#code').val(item.code).change();
                $('#brand_form').find('#code_valid').show();
            }
        })

        $('#brand_form_modal').modal('show');
        
    })

    //delete brand
    $(document).on('click','.delete-brand', function()
    {
        const selectedCode = $(this).data('code').toString();

        principal.brands.forEach(function(item, index)
        {
            if (item.code == selectedCode )
            {
                principal.brands.splice(index,1);
                return ;
            }
        });

       renderBrand();
    })

    function renderBrand()
    {
        $('#brand_place').html('');
        
        principal.brands.forEach(function(brand, i) 
        {
            $('#brand_place').append(
                `<li class="list-group-item" >
                    ${brand.name} (${brand.code}) 
                    <a href="#" class="float-right ml-3 edit-brand" data-code="${brand.code}"><i class="fa fa-edit"></i></a> 
                    <a href="#" class="float-right text-danger delete-brand" data-code="${brand.code}"><i class="fa fa-times"></i></a>
                </li>`
            );
        })
    }

    function submitBrand()
    {
        $('#brand_form').on('submit', function()
        {
            const name = $(this).find('#name').val();
            const code = $(this).find('#code').val();

            if(edit_brand)
            {
                edit_brand = false;
                 principal.brands.forEach(function(item, i) 
                 {
                    if ( item.code == brand.code )
                    {
                        index = i;
                        principal.brands[i].name = name;
                        principal.brands[i].code = code;
                    }
                })
            }else{
                
                principal.brands.push({
                    name : name,
                    code : code
                });
            }
            
            $(this).trigger('reset');

            $('#brand_form_modal').modal('hide');

            renderBrand();

            return false;
        });
    }

    function savePrincipal()
    {
        $('#principal_form').on('submit', function()
        {
            if( principal.brands.length == 0 )
            {
                Swal.fire({
                    icon: 'warning',
                    title: 'Information!',
                    text: 'Principal must have at least 1 brand.'
                });
                return false;
            }

            const code = $('#principal_code').val();

            if( code == '' )
            {
                return false
            }

            principal.code = code

            //post off the leaf for data
            $.post('/ajax/principal/store', principal)
                .done(function (d) 
                {
                    if( d.insert )
                    {
                        Swal.fire({
                            icon: 'success',
                            title: 'Information!',
                            text: d.message
                        });
                        $('.back-btn').get(0).click();
                    }
                })
                .fail(function () 
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Failed',
                        text: 'The check could not be done because we could not talk to the server.'
                    });

                });

            return false;
        });

    }

    submitBrand();
    savePrincipal();

    var timer_principal_code;

    $('#principal_code').on('keyup', function()
    {
        const spinner = $('#principal_code_spinner');
        const valid = $('#principal_code_valid');
        const searchPrincipal = $('#principal_code').val().trim();
        
        clearTimeout(timer_principal_code); 
        spinner.hide();
        valid.hide();
        $('.btn-principal').attr('disabled',false);

        if(searchPrincipal == '')
                return;

        spinner.show();
        //Give a second delay to see if the user is finished typing to reduce many AJAX call per user keyup
        timer_principal_code = setTimeout(function()
        {           
            //check the pattern first before calling AJAX
            var regex = new RegExp('^[a-zA-Z0-9-]+$');

            if ( regex.test(searchPrincipal) )
            {
                if(xhr)
                    xhr.abort()

                //post off the leaf for data
                $.post('/ajax/principal/validatecode/principal/'+searchPrincipal)
                    .done( (response) => 
                    {
                        if( response.duplicate )
                        {
                            $('#principal_code').siblings('.invalid-feedback').show();
                            $('.btn-principal').attr('disabled',true);
                            valid.hide();
                        }else{
                            $('#principal_code').siblings('.invalid-feedback').hide();
                            $('.btn-principal').attr('disabled',false);
                            valid.show();
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
            }else{
                spinner.hide();
                Swal.fire({
                    icon: 'warning',
                    title: 'Wrong Pattern',
                    html: $('.principal_code_format').html()
                });
            }
        }, 1000);

    })

    //ajax check duplicate brand code
    var timer_brand_code;
    $('#code').on('keyup', function()
    {
        const spinner = $('#code_spinner');
        const valid = $('#code_valid');
        const searchBrand = $('#code').val().trim();
        
        clearTimeout(timer_brand_code);         
        spinner.hide();
        valid.hide();

        $('.btn-brand').attr('disabled',true);

        if(searchBrand == '')
            return;

        spinner.show();

        //Give a second delay to see if the user is finished typing to reduce many ajax call per user keyup
        timer_brand_code = setTimeout(function()
        {   
            //check local array first then check data in server
            let duplicate = false;
            principal.brands.forEach(function(item, i) 
            {
                if(item.code == searchBrand)
                {
                    duplicate = true;        
                    return false; 
                }
            })

            if ( !duplicate )
            {
                if(xhr)
                    xhr.abort()
                //post off the leaf for data
                $.post('/ajax/principal/validatecode/brand/'+searchBrand)
                    .done( (response) => 
                    {
                        if( response.duplicate )
                        {
                            $('.btn-brand').attr('disabled',true);
                            $('#code').siblings('.invalid-feedback').show();
                            valid.hide();
                        }else{
                            $('.btn-brand').attr('disabled',false);
                            $('#code').siblings('.invalid-feedback').hide();
                            valid.show();
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
            }else{

                spinner.hide();
                Swal.fire({
                    icon: 'warning',
                    title: 'Duplicate brand code',
                    text: 'Brand code already inserted in list brand'
                });
                
            }

        }, 1000);
    })

});