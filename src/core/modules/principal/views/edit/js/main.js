$(document).ready(function()
{
    var xhr = null;
    var edit_brand = false;
    var brand = null;
    var principal = {
            brands: [],
        };
    var principal_id = $('#principal_id').val();
    
    $('#go_back').click(function(e){
		e.preventDefault();
		swal.fire({
            title: 'Leave form?',
            text: 'Changes you made may not be saved.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Leave !'
        }).then((result) => {
            if(result.value)
            {
				document.location.href = $(this).prop('href');
			}
		});
	});

    //load principal
    $.post('/ajax/principal/detail/'+principal_id)
        .done(response => 
        {
            principal = response.data;
            if (principal.email == undefined ){
                $('body #principal_form').html('No principal data with this id');
                return;
            }
            $('#old_ab').val(principal.address_book_id);
            $('#ab_name').html(principal.number_given_name)
            $('#ab_email').html(principal.email)
            $('#principal_code').val(principal.code).change();
            renderBrand();
        })
        .fail(function () {
            Swal.fire({
                icon: 'error',
                title: 'Connection Failed',
                text: 'The check could not be done because we could not talk to the server.'
            });
        });

    function renderBrand(){
        $('#brand_place').html('')
        principal.brands.forEach(function(brand, i) 
        {
            $('#brand_place').append(
                `<li class="list-group-item" >
                    ${brand.name} (${brand.code})
                    <a href="#" class="float-right ml-3 edit-brand" data-code="${brand.code}" data-index="${i}"><i class="fa fa-edit"></i></a>
                    <a href="#" class="float-right text-danger delete-brand" data-code="${brand.code}" data-index="${i}"><i class="fa fa-times"></i></a>
                </li>`);    
        })
    }

    //add branch modal
    $('#add_brand').on('click', function()
    {
        edit_brand = false;
        $('.btn-brand').attr('disabled',true);
        $('#code').siblings('.invalid-feedback').hide();
        $('#brand_form').find('#name').val('');
        $('#brand_form').find('#code').val('');
        $('#brand_form_modal').modal('show');
        $('#brand_form').find('#code_valid').hide();

    })

    //edit brand modal
    $(document).on('click','.edit-brand', function()
    {
        edit_brand = true;
        $('#code').siblings('.invalid-feedback').hide();
        brand = principal.brands[$(this).data('index')];
        $('#brand_form').find('#name').val(brand.name).change();
        $('#brand_form').find('#code').val(brand.code).change();
        $('#brand_form').find('#code_valid').show();
        $('#brand_form_modal').modal('show');
        return false;
    })

    //delete brand
    $(document).on('click','.delete-brand', function()
        {    
        swal.fire({
			title: 'Delete this brand?',
			text: 'Are you sure? Once you delete it can never be recovered!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
        }).then((result) => 
        {
            if(result.value)
            {
				$.post('/ajax/principal/deletebrand/'+$(this).data('code'))
                .done( (response) => 
                {
                    Swal.fire({
                        icon: 'success',
                        text: response.message
                    });

                    principal.brands.splice($(this).data('index'),1)
                    renderBrand()
                })
                .fail(function () 
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Failed',
                        text: 'The check could not be done because we could not talk to the server.'
                    });
                });
			}
		});

        return false;
    })
    
    
    function submitBrand()
    {
        $('#brand_form').on('submit', function()
        {
            if(edit_brand)
            {
                edit_brand = false
                
                brand.name = $(this).find('#name').val().trim();
                brand.code = $(this).find('#code').val().trim();
                if ( (brand.name == '') && (brand.code == '') )
                    return;

                $.post('/ajax/principal/updatebrand', brand)
                    .done( ( response ) => 
                    {
                        Swal.fire({
                            icon: 'success',
                            text: response.message
                        });
                        brand = {}
                        renderBrand()

                        $('#brand_form_modal').modal('hide');
                    })
                    .fail(function ()
                    {
                        Swal.fire({
                            icon: 'error',
                            title: 'Connection Failed',
                            text: 'The check could not be done because we could not talk to the server.'
                        });
                    });
            }else{
                //post off the leaf for data
                brand = {

                    address_book_id : principal_id,
                    name : $(this).find('#name').val().trim(),
                    code : $(this).find('#code').val().trim(),
                }

                $.post('/ajax/principal/addbrand', brand)
                    .done( ( response ) => 
                    {
                        Swal.fire({
                            icon: 'success',
                            text: response.message
                        });

                        principal.brands.push(brand)
                        brand = {}
                        renderBrand()

                        $('#brand_form_modal').modal('hide');
                    })
                    .fail(function ()
                    {
                        Swal.fire({
                            icon: 'error',
                            title: 'Connection Failed',
                            text: 'The check could not be done because we could not talk to the server.'
                        });
                    });
            }

            return false;
        })
    }

   

    function savePrincipal(){

        $('#principal_form').on('submit', function(){

            if(principal.brands.length == 0)
            {
                Swal.fire({
                    icon: 'warning',
                    title: 'Information!',
                    text: 'Principal must have at least 1 brand.'
                });
                return false;
            }

            principal.code = $('#principal_code').val().trim();
            //post off the leaf for data
            $.post('/ajax/principal/update', principal)
                .done(function (response) 
                {
                    if( response.update >= 0 )
                    {
                        Swal.fire({
                            icon: 'success',
                            title: 'Information!',
                            text: response.message
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
    submitBrand()
    savePrincipal()

    //ajax check duplicate principal code
    var timer_principal_code;

    $('#principal_code').on('keyup', function()
    {
        const spinner = $('#principal_code_spinner');
        const valid = $('#principal_code_valid');
        const searchPrincipal = $('#principal_code').val().trim();
        
        clearTimeout(timer_principal_code); 
        spinner.hide();
        valid.hide();
        $('.btn-principal').attr('disabled',true);

        if(searchPrincipal == '')
                return;

        spinner.show();
        //Give a second delay to see if the user is finished typing to reduce many ajax call per user keyup
        timer_principal_code = setTimeout(function()
        {   
                //check the pattern first before calling AJAX
            var regex = new RegExp('^[a-zA-Z0-9-]+$');

            if ( regex.test(searchPrincipal) )
            {
                if(xhr)
                    xhr.abort();

                //post off the leaf for data
                $.post('/ajax/principal/validatecode/principal/'+searchPrincipal+'/'+principal_id)
                    .done( (response) => 
                    {
                        if(response.duplicate)
                        {
                            $('#principal_code').siblings('.invalid-feedback').show()
                            $('.btn-principal').attr('disabled',true)
                            valid.hide();
                        }else{
                            $('#principal_code').siblings('.invalid-feedback').hide()
                            $('.btn-principal').attr('disabled',false)
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
        }, 1000 );

    });
    
    //ajax check duplicate brand code
    var timer_brand_code;
    $('#code').on('keyup', function()
    {
        const spinner = $('#code_spinner');
        const valid = $('#code_valid');
        const brandCode = $('#code').val().trim();

        clearTimeout(timer_brand_code); 
        spinner.hide();
        valid.hide();
        $('.btn-brand').attr('disabled',true);

        if(brandCode == '')
            return;

        if(edit_brand){
            if(brandCode == brand.code){
                $('.btn-brand').attr('disabled',false);
                $('#code').siblings('.invalid-feedback').hide();
                valid.show();
                return;
            }
        }
        spinner.show();

        //Give a second delay to see if the user is finished typing to reduce many ajax call per user keyup
        timer_brand_code = setTimeout(function()
        { 
            if(xhr)
                xhr.abort()

            //post off the leaf for data
            $.post('/ajax/principal/validatecode/brand/'+brandCode)
                .done( (response) => 
                {
                    if(response.duplicate)
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

        }, 1000);
    })

    //Address Book Functions

    //edit address book modal
    $(document).on('click','.edit-address_book', function()
    {
        $('#update_ab_form_modal').modal('show');
        return false;
    })

    $(document).on('click','.btn-edit-address_book', function(e)
    {
        swal.fire({
            title: 'Update',
            text: 'Confirm update address book?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Update !'
        }).then((result) => 
        {
            if(result.value)
            {
                $('#update_ab_form_modal').modal('hide');
				$('#update_ab_form').submit();
			}
		});
        
    })

    function submitUpdateAB()
    {
        $('#update_ab_form').on('submit', function()
        {
            const new_ab = $(this).find('#new_ab').val();
            const old_ab = $(this).find('#old_ab').val();
            
            if ( (new_ab == '') && (old_ab == '') )
                return;

            $.post('/ajax/principal/updateaddressbook', {
                new_ab : new_ab,
                old_ab : old_ab
            })
                .done( ( response ) => 
                {
                    Swal.fire({
                        icon: 'success',
                        text: response.message
                    }).then(function(){
                        //redirect to new address_book_id
                        window.location=$('#page_link').val()+'/'+new_ab;
                    });
                })
                .fail(function ()
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Failed',
                        text: 'Update could not be done because we could not talk to the server.'
                    });
                });

            return false;
        })
    }
    submitUpdateAB()

    $('#new_ab').materialSelect();

    $('#new_ab').on('change',function()
    {
        $('.btn-edit-address_book').prop('disabled',($(this).val()!='')? false : true);
    });

    var timer_search_ab;
    $('body').on('keyup','#search_ab',function()
    {
        const spinner = $('#search_ab_spinner');
        const searchString = $('#search_ab').val().trim();
        clearTimeout(timer_search_ab); 
        spinner.hide();
        $('.btn-edit-address_book').prop('disabled', true);
        if(searchString == '')
                return;

        spinner.show();

        //Give a second delay to see if the user is finished typing to reduce many ajax call per user keyup
        timer_search_ab = setTimeout(function()
        {   
            $('#new_ab').hide().find('option:not([disabled])').remove();

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
                        $('#new_ab').prop('disabled',false);
                        let html = '';
                        $.each(response, function(i, item) 
                        {
                            html += `<option value="${item.address_book_id}" data-secondary-text="${item.main_email}">${item.entity_family_name}</option>`
                        });
                        $('#new_ab').append(html);
                        setTimeout(function(){ 
                            $('#select-options-new_ab').css({
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

});