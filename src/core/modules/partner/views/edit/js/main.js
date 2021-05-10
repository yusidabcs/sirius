// Material Select Initialization
$(document).ready(function()
{
    const showLoadingModal = function() {
		Swal.fire({
			title: 'Loading...',
			icon: 'info',
			allowOutsideClick: false,
			allowEscapeKey: false,
			showConfirmButton: false
		});
		Swal.showLoading();
	}

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

    $('.mdb-select').materialSelect();
    $('#banner_base64_top').prop('disabled',true);

    var timer_partner_code;
    //check partner code unique id
    var xhr = null
    $('#partner_code').on('keyup',function()
    {
        const spinner = $('#partner_code_spinner');
        const valid = $('#partner_code_valid');
        const partnerCode = $(this).val().trim();
        
        clearTimeout(timer_partner_code); 
        spinner.hide();
        valid.hide();
        $('.btn-partner').attr('disabled',true);

        if(partnerCode == '')
                return;

        spinner.show();
        //Give a second delay to see if the user is finished typing to reduce many ajax call per user keyup
        timer_partner_code = setTimeout(function()
        {   
                //check the pattern first before calling AJAX
            const regex = new RegExp('^[a-zA-Z0-9-]+$');

            if ( regex.test(partnerCode) )
            {
                if(xhr)
                    xhr.abort()

                //post off the leaf for data
                xhr = $.post('/ajax/partner/checkpartnercode/'+partnerCode+'/'+$('#address_book_id').val())
                    .done( (response) => 
                    {
                        if( response.duplicate )
                        {
                            $('#partner_code').siblings('.invalid-feedback').show();
                            $('.btn-partner').attr('disabled',true);
                            valid.hide();
                        }else{
                            $('#partner_code').siblings('.invalid-feedback').hide();
                            $('.btn-partner').attr('disabled',false);
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
        }, 1000 );

    });

    //check country dropdown, to generate subcountry dropdown based on selected country
    $('#country').on('change',function ()
    {
        const countryCode = $(this).val();
        var countryName = {};
        $("#country option:selected").each(function () {
            var $this = $(this);
            if ($this.length) {
                countryName[$this.val()]= $this.text();
            }
        });
        if (countryCode.length>0){

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

                    $.each(rs, (country_code,subcountry) => {

                        if($('#sub_country_'+country_code).length){
                            return;
                        }
                        if ( Object.keys(subcountry).length > 0 )
                        {
                            //check if select exist
                            html = 
                            `<div class="col-md-6 subcountry" id="${country_code}">
                                <div class="m-1 border p-3">
                                    <select id="sub_country_${country_code}" name="countrySubCode_id[${country_code}][]" class=" mdb-select md-form" multiple searchable="Search here.." required>
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

                    $('#sub_countries').children('div.subcountry').each(function(){

                        var id = $(this).attr('id')
                        var exist = false
                        $.each(rs,(country_code,subcountry) => {
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

    function popupResult(result) {
        var html;
        if (result.html) {
            html = result.html;
        }
        if (result.src) {
            html = '<img src="' + result.src + '" class="img-fluid"/>';
            $('#banner_img').attr('src',result.src)
        }
        swal.fire({
            title: 'Banner',
            html: html,
            allowOutsideClick: true
        });
        setTimeout(function(){
            $('.sweet-alert').css('margin', function() {
                var top = -1 * ($(this).height() / 2),
                    left = -1 * ($(this).width() / 2);

                return top + 'px 0 0 ' + left + 'px';
            });
        }, 1);
        $('button[type="submit"]').prop("disabled",false);
    }

    function readFile(input) {

        if (input.files && input.files[0]) {

            var reader = new FileReader();

            reader.onload = function (e) {

                $uploadCrop.croppie('bind', {
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

    $('#banner_input').on('change', function () {
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

            $('#banner_croppie_wrap').show();
            $('#banner_result').show();
            $('#update_crop').hide();
        }

    });

    $('#banner_result').on('click', function (ev) {
        const file_choosen = $('#banner_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
            $uploadCrop.croppie('result', {
                type: 'canvas',
                size: 'original'
            }).then(function (resp) {
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

            $.post('/ajax/partner/updateaddressbook', {
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
            xhr = $.post('/ajax/address_book/searchaddressbooks/partner/'+searchString)
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

    });

    // start entity link data

    $('.ab_delete_contact_btn').click(function (e) {
        e.preventDefault();
		Swal.fire({
            title: 'Delete this data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete !'
        }).then((result) => {
            if(result.value)
            {
                showLoadingModal();
				$.post('/ajax/address_book/main/deleteAddressBookAdminLink', {
                    address_book_per_id: $(this).data('address_book_per_id'),
                    address_book_ent_id: $('#address_book_id').val(),
                })
                    .done(function (d) {
    
                        if(d.success){
                            location.reload();
                        }else{
                            swal.fire('Warning!', d.message, 'error');
                        }
    
                    })
                    .fail(function () {
                        Swal.fire('Connection Failed', 'The check could not be done because we could not talk to the server.', 'error');
                        btn.attr('disabled',false);
                    });
			}
		});
         return false
    });

    $('#ab_add_contact_btn').click(function () {
		$('#ab_add_contact_modal').modal('show');
    });

    $('#ab_contact_check_email').on('click', function () {
		var email = $('#ent_admin_email').val()
        var btn = $(this)
        btn.attr('disabled', true)
        //post off the leaf for data
        $.post('/ajax/address_book/main/contactEmailCheck', {
            email: email
        })
            .done(function (d) {
                swal.fire(d.heading, d.message, d.level);

                if(d.per_address_book_id == 0 && d.level != 'error'){
                	$('#ab_contact_info').show()
                    $('#ab_contact_exist').hide()
				}else if(d.per_address_book_id != 0 && d.level == 'success'){
                	$('#ab_contact_exist').show();
                    $('#ab_contact_info').hide()
                	$('#ab_contact_exist').prepend('<p>Email: '+email+'</p>');
                	$('#address_book_per_id').val(d.per_address_book_id);
				} else {
                    $('#ab_contact_exist').hide();
                    $('#ab_contact_info').hide()
                }
                btn.attr('disabled', false)

            })
            .fail(function () {
                swal.fire('Connection Failed', 'The check could not be done because we could not talk to the server.', 'error');

                $('button[type="submit"]').prop("disabled",false);
                btn.attr('disabled', false)
            });

    });

    $('#link_ab_entity').on('click', function () {
    	var address_book_per_id = $('#ab_contact_exist #address_book_per_id').val()
    	var address_book_ent_id = $('#address_book_id').val()
    	var person_type = $('#ab_contact_exist select[name=person_type]').val()
    	var role_id = 10;//staff
        var btn = $(this)
        btn.attr('disabled',true);
        showLoadingModal();
        $.post('/ajax/address_book/main/linkAddressBookEntity', {
            address_book_per_id: address_book_per_id,
            address_book_ent_id: address_book_ent_id,
            person_type: person_type,
            role_id: role_id,
            security_level_id: 'NONE'
        })
            .done(function (d) {

            	if(d.success){
                    //$('#ab_add_contact_modal').modal('hide');
                    //btn.attr('disabled',false);
                    location.reload();
				}else{
                    swal.fire('Warning!', d.message, 'error');
				}

            })
            .fail(function () {
                swal.fire('Connection Failed', 'The check could not be done because we could not talk to the server.', 'error');
                btn.attr('disabled',false);
            });
    	return false

    });

    $('#ent_admin_dob').pickadate({
        labelMonthNext: 'Go to the next month',
        labelMonthPrev: 'Go to the previous month',
        labelMonthSelect: 'Pick a month from the dropdown',
        labelYearSelect: 'Pick a year from the dropdown',
        max: new Date($('#ent_admin_dob').data('max-date')),
        formatSubmit: 'yyyy-mm-dd',
        format: 'yyyy-mm-dd',
    })

    $('#create_ab_entity').on('click', function () {
        var address_book_ent_id = $('#address_book_id').val()
        var email = $('#ent_admin_email').val()
        var title = $('#ent_admin_new_details #ent_admin_title').val()
        var family_name = $('#ent_admin_new_details #ent_admin_family_name').val()
        var given_name = $('#ent_admin_new_details #ent_admin_given_name').val()
        var middle_names = $('#ent_admin_new_details #ent_admin_middle_names').val()
        var dob = $('#ent_admin_new_details #ent_admin_dob').val()
        var sex = $('#ent_admin_new_details input[name=sex]:checked').val()
        var person_type = $('#ent_admin_new_details select[name=person_type]').val()
        var role_id = 10;
        var contact_allowed = $('#ent_admin_new_details input[name=contact_allowed]').val()
        var add_new_user = $('#ent_admin_new_details input[name=add_new_user]').val()
        var send_new_user_email = $('#ent_admin_new_details input[name=send_new_user_email]').val()
        var btn = $(this)

        if(email == ''){
            swal.fire('Warning!', 'Email is empty', 'error');
            $('#ent_admin_email')[0].checkValidity();
            return;
        }
        if(title == ''){
            swal.fire('Warning!', 'Title is empty', 'error');
            $('#ent_admin_new_details #ent_admin_title')[0].checkValidity();
            return;
        }
        if(given_name == ''){
            swal.fire('Warning!', 'Given name is empty', 'error');
            $('#ent_admin_new_details #ent_admin_title')[0].checkValidity();
            return;
        }
        if(family_name == ''){
            swal.fire('Warning!', 'Family name is empty', 'error');
            $('#ent_admin_new_details #ent_admin_title')[0].checkValidity();
            return;
        }
        if(dob == ''){
            swal.fire('Warning!', 'Dob is empty', 'error');
            $('#ent_admin_new_details #ent_admin_title')[0].checkValidity();
            return;
        }
        if(sex == undefined){
            swal.fire('Warning!', 'Sex is empty', 'error');
            $('#ent_admin_new_details #ent_admin_title')[0].checkValidity();
            return;
        }
        if(person_type == ''){
            swal.fire('Warning!', 'Person Type is empty', 'error');
            $('#ent_admin_new_details #ent_admin_title')[0].checkValidity();
            return;
        }

        Swal.fire({
            title: 'Is data correct?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true,
        }).then((result) => {
            if(result.value)
            {
                showLoadingModal();
                btn.attr('disabled',true)
                $.post('/ajax/address_book/main/addAddressBookAdminLink', {
                    address_book_ent_id: address_book_ent_id,
                    email: email,
                    title: title,
                    family_name: family_name,
                    given_name: given_name,
                    middle_names: middle_names,
                    dob: dob,
                    sex: sex,
                    person_type: person_type,
                    role_id: role_id,
                    security_level_id: 'NONE',
                    contact_allowed: contact_allowed,
                    add_new_user: add_new_user,
                    send_new_user_email: send_new_user_email,
                })
                .done(function (d) {

                    if(d.success){
                        location.reload();
                    }else{
                        swal.fire('Warning!', d.message, 'error');
                    }
                    btn.attr('disabled',false);

                })
                .fail(function () {
                    swal.fire('Connection Failed', 'The check could not be done because we could not talk to the server.', 'error');
                    btn.attr('disabled',false);
                });
            }
        });
        // if(window.confirm('is data correct?')){
            
        // }

        return false
    })

});
