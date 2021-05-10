$(document).ready(function()
{
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
    
    $('#address_book_id').materialSelect();
    //yusida js start here
    var partner = {};
    var xhr = null;

    $('#partner_area').hide();

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
            xhr = $.post('/ajax/address_book/searchaddressbooks/partner/'+searchString)
                .done(function (response)
                {
                    if (response.length)
                    {
                        $('#search_ab').siblings('.invalid-feedback').hide();

                        $('#div_ab').show();
                        $('#address_book_id').prop('disabled',false);

                        let html = '';

                        $.each(response, function(i, item)
                        {
                            html += `<option value="${item.address_book_id}" data-secondary-text="${item.main_email}">${item.entity_family_name}</option>`
                        });

                        $('#address_book_id').append(html);

                        setTimeout(function(){ 
                            $('#select-options-address_book_id').css({
                            'display': 'block',
                            'position': 'absolute',
                            'top': '0px',
                            'left': '0px',
                            'opacity': '1'
                        })
                         }, 500);

                    }else{
                        $('#search_ab').siblings('.invalid-feedback').show()
                        $('#div_ab, #partner_area').hide();
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

    $('#address_book_id').on('change', function()
    {    
        $('#partner_area').show();
        partner.address_book_id = $(this).val();
    });

    $('#address_book_id').materialSelect();
    $('#countryCode_id').materialSelect();
    $('#partner_type').materialSelect();

    var xhr = null;

    var timer_partner_code;

    $('#partner_code').on('keyup',function()
    {
        const spinner = $('#partner_code_spinner');
        const valid = $('#partner_code_valid');
        const partnerCode = $(this).val().trim();
        
        clearTimeout(timer_partner_code); 
        spinner.hide();
        valid.hide();
        $('.btn-partner').prop('disabled',true);

        if(partnerCode == '')
                return;

        spinner.show();
        //Give a second delay to see if the user is finished typing to reduce many AJAX call per user keyup
        timer_partner_code = setTimeout(function()
        {           
            //check the pattern first before calling AJAX
            var regex = new RegExp('^[a-zA-Z0-9-]+$');

            if ( regex.test(partnerCode) )
            {
                if(xhr)
                    xhr.abort();

                //post off the leaf for data
                xhr =  $.post('/ajax/partner/checkpartnercode/'+partnerCode+'')
                .done( (response) => 
                {
                    if( response.duplicate )
                    {
                        $('#partner_code').focus();
                        $('#partner_code').siblings('.invalid-feedback').show();
                        $('.btn-partner').prop('disabled',true);
                        valid.hide();
                    }else{
                        $('#partner_code').siblings('.invalid-feedback').hide();
                        $('.btn-partner').prop('disabled',false);
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
                    html: $('.partner_code_format').html()
                });
            }
        },1000);
        
    });

    $('#sub_countries').hide();

    //check country dropdown, to generate subcountry dropdown based on selected country
    $('#countryCode_id').on('change',function ()
    {
        var countryCode = $(this).val();
        var countryName = {};
        $("#countryCode_id option:selected").each(function ()
        {
            const $this = $(this);
            if ($this.length)
            {
                countryName[$this.val()]= $this.text();
            }
        });
        if ( countryCode.length > 0 )
        {
            $('#sub_countries').show();
            $.ajax({
                url: '/ajax/partner/countries',
                data: {
                    'country_codes' : countryCode,
                },
                type: 'POST', //send it through get method
                datatype : 'json',
                success: function(rs) {
                    var html='';

                    $.each(rs, (country_code,subcountry) =>
                    {
                        if($('#sub_country_'+country_code).length)
                        {
                            return;
                        }

                        if ( Object.keys(subcountry).length > 0 )
                        {
                            //check if select exist
                            html = 
                            `<div class="col-md-6 subcountry" id="${country_code}">
                                <div class="m-1 border p-3">
                                    <select id="sub_country_${country_code}" name="countrySubCode_id[${country_code}][]" class="mdb-select md-form" multiple searchable="Search here.." required>
                                        <option value="" disabled>Select covered subcountry for ${countryName[country_code]}</option>`;
                            $.each(subcountry, function(subcountry_code,subcountry_name)
                            {
                                //iterate each option
                                html += `<option value="${subcountry_code}">${subcountry_name}</option>`;
                            });
                            html += `</select>
                                    <label for="sub_country_${country_code}">Subcountry - ${countryName[country_code]}</label>
                                </div>
                                <input type="hidden" name="countrySubCode_idLength[${country_code}]" value="${Object.keys(subcountry).length}">
                            </div>`;

                            $('#sub_countries').append(html);
                            $('#sub_country_'+country_code).materialSelect();
                        }

                    })

                    $('#sub_countries').children('div.subcountry').each(function()
                    {
                        const id = $(this).prop('id')
                        let exist = false
                        $.each(rs,(country_code,subcountry) =>
                        {
                            if(id == country_code)
                                exist = true
                        });
                        if(!exist){
                            $(this).remove();
                        }


                    })
                },
                error: function(e) {
                    alert('error '+e);
                }
            });

        }else{
            //empty selected
            $('#sub_countries').hide();
        }
    });
    
    var $uploadCrop;
    //detect viewport and compare with inserted attribute data
    const b_width = $('#banner_croppie').data('banner-width');
    const b_height = $('#banner_croppie').data('banner-height');
    const v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/1.4;
    const v_height = b_height/b_width*v_width;

    //choose appropriate width and height based on device
    const crop_width = (b_width>v_width) ? v_width : b_width; 
    const crop_height = (b_height>v_height) ? v_height : b_height; 

    function popupResult(result)
    {
        var html;
        if (result.html)
        {
            html = result.html;
        }

        if (result.src)
        {
            html = '<img src="' + result.src + '" class="img-fluid" />';
        }
        swal.fire({
            title: 'Banner',
            html: html,
            allowOutsideClick: true
        });
        setTimeout(function()
        {
            $('.sweet-alert').css('margin', function() 
            {
                const top = -1 * ($(this).height() / 2),
                    left = -1 * ($(this).width() / 2);

                return top + 'px 0 0 ' + left + 'px';
            });
        }, 1);

        $('button[type="submit"]').prop("disabled",false);
    }

    function readFile(input)
    {
        if (input.files && input.files[0])
        {
            var reader = new FileReader();

            reader.onload = function (e)
            {
                $uploadCrop.croppie('bind', 
                {
                    url: e.target.result
                });

                $('#banner_croppie_wrap').show();
            }

            reader.readAsDataURL(input.files[0]);

        } else {
            swal("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    $uploadCrop = $('#banner_croppie').croppie({
        viewport: {
			width: crop_width,
			height: crop_height
		},
		boundary: {
			width: crop_width*1.1,
			height: crop_height*1.1
		},
		enableExif: true
    });


    $('#banner_input').on('change', function ()
    {
        const file_choosen = $('#banner_input').val();

		//check if image is choosen before start cropping
        if (file_choosen !== "")
        {
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
            $('button[type="submit"]').prop("disabled",true);
            readFile(this);
            $('#banner_croppie').show();
            $('#banner_result').show();
            $('#update_crop').hide();
		}
    });

    $('#banner_result').on('click', function (ev) 
    {
        const file_choosen = $('#banner_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
            $uploadCrop.croppie('result',
            {
                type: 'canvas',
                size: 'original'
            }).then(function (resp) 
            {
                resizeImage(resp, b_width, b_height).then((resp) => {
                    popupResult({
                        src: resp
                    });

                    $('#banner_base64').val(resp);
                    $('#banner_img').prop('src',resp);
                    $('#banner_img').parent().show();
                    $('#banner_croppie_wrap').hide();
                    $('#banner_result').hide();
                    document.getElementById("banner_image").scrollIntoView();
                    $('#update_crop').show();
                })
            });
        }else{
			Swal.fire({
				icon: 'warning',
				text: 'Please choose an image first',
			});
		}
    });
    $('#update_crop').on('click',function(){
		$('#banner_croppie_wrap').show();
		$('#banner_result').show();
		$(this).hide();
		document.getElementById("banner_croppie_wrap").scrollIntoView();
	})
});