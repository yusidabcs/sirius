$(document).ready(function () {
    const showLoadingModal = function() {
        Swal.fire({
            title: 'Loading',
            icon: 'info',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false
        });
        Swal.showLoading();
    }
    $('#table_status_search, #table_level_search').materialSelect();
    $('#invoice_expected_on').pickadate({
        format: 'yyyy-mm-dd'
    });

    const table = $('#list_finance_psf').DataTable({
            "processing": true,
            "serverSide": true,
            'responsive': true,
            "ajax": {
                "url": "/ajax/workflow/travelpack/list",
                "type": "POST",
                cache: false,
                data: function (d) {
                    d.status = $('#table_status_search').val()
                    d.level = $('#table_level_search').val()
                    d.start_date = $('#startingDate').val()
                    d.end_date = $('#endingDate').val()
                }
            },
            "columns": [
                {"data": 'fullname'},
                {"data": 'status'},
                {"data": 'level'},
                {"data": 'created_on'},
                {"data": 'entity_family_name'},
                {"data": 'number_given_name'},
                {"data": null}
            ],
            "columnDefs": [
                {
                    "render": function (data, type, row) {
                        return data+'<br>' + row.main_email;
                    },
                    "targets": 0
                },
                {
                    "render": function (data, type, row) {
                        if (data == 'pay_invoice')
                            return `<span class="badge badge-warning">Pay Invoice</span> <br> Expected paid on: ${row.invoice_expected_on}`
                        return data;
                    },
                    "targets": 1
                },
                {
                    "render": function (data, type, row) {
                        if (data == '1')
                            return `<span class="text-success">Normal</span>`
                        if (data == '2')
                            return `<span class="text-info">Soft Warning</span>`
                        if (data == '3')
                            return `<span class="text-warning">Hard Warning</span>`
                        if (data == '4')
                            return `<span class="text-danger">Deadline</span>`
                    },
                    "targets": 2
                },

                {
                    "render": function(data) {
                        return data
                    },
                    "targets": 3
                },

                {
                    "visible": false,
                    "targets": 4
                },

                {
                    "visible": false,
                    "targets": 5
                },


                {
                    "render": function (data, type, row) {

                        var html = ``;
                        if(row.status == 'generate_invoice'){
                            html += `<a  class="btn-sm btn-info btn-generate-invoice" href="#" title="Generate Invoice" ><i class="fa fa-plus"></i> Invoice</a>`;
                        }
                        else if(row.status == 'pay_invoice'){
                            html += `<a  class="btn-sm btn-info btn-pay-invoice" href="#" title="Pay Invoice" ><i class="fa fa-dollar-sign"></i> Pay</a> <a  class="btn-sm btn-warning btn-pay-resend white-text" href="#" title="resend invoice" ><i class="fa fa-paper-plane"></i> Resend Invoice</a>`;
                        }


                        return html;
                    },
                    "targets": -1
                }
            ],
        });

    // $('#table_status_search, #table_level_search').on('change', function () {
    //     table.ajax.reload()
    // });

    $(document).on('click','.btn-generate-invoice', function () {
        var data = table.row(this.closest('tr')).data();
        $('#generate-invoice-modal').modal('show')
        $('#generate-invoice-form').find('input[name=job_application_id]').val(data.job_application_id);
        $('#generate-invoice-form').find('input[name=address_book_id]').val(data.address_book_id);
    })

    $('#generate-invoice-form').on('submit', function (e) {
        e.preventDefault()

        Swal.fire({
            title: 'Save Generate Invoice',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/travelpack/generate_invoice',
                    data: $(this).serialize(),
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload(false);
                        $('#generate-invoice-modal').modal('hide')

                    },
                    error: function (response) {

                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        })
    });

    // cropie for generate invoice form
    var $uploadCrop;
    //detect viewport and compare with inserted attribute data
    const b_width = $('#invoice_croppie').data('invoice-width');
    const b_height = $('#invoice_croppie').data('invoice-height');
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
            title: 'Generate Invoice',
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

        $('#generate-invoice-modal button[type="submit"]').prop("disabled",false);
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

                $('#invoice_croppie_wrap').show();
            }

            reader.readAsDataURL(input.files[0]);

        } else {
            swal("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    $uploadCrop = $('#invoice_croppie').croppie({
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


    $('#invoice_input').on('change', function ()
    {
        const file_choosen = $('#invoice_input').val();

		//check if image is choosen before start cropping
        if (file_choosen !== "")
        {
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
            $('#generate-invoice-modal button[type="submit"]').prop("disabled",true);
            readFile(this);
            $('#invoice_croppie').show();
            $('#invoice_result').show();
            $('#update_crop').hide();
		}
    });

    $('#invoice_result').on('click', function (ev) 
    {
        const file_choosen = $('#invoice_input').val();

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

                    $('#invoice_base64').val(resp);
                    $('#invoice_img').prop('src',resp);
                    $('#invoice_img').parent().show();
                    $('#invoice_croppie_wrap').hide();
                    $('#invoice_result').hide();
                    document.getElementById("invoice_image").scrollIntoView();
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
		$('#invoice_croppie_wrap').show();
		$('#invoice_result').show();
		$(this).hide();
		document.getElementById("invoice_croppie_wrap").scrollIntoView();
    })

    $(document).on('click','.btn-pay-resend', function() {
        var data = table.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Resend Invoice?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Send!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/travelpack/resend-invoice',
                    data: {
                        address_book_id:data.address_book_id,
                        job_application_id:data.job_application_id
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                    },
                    error: function (response) {

                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        })
    })
    $('#table_status_search, #table_level_search, #startingDate, #endingDate').on('change', function () {
        table.ajax.reload()
    });

    var from_input = $('#startingDate').pickadate()
    from_picker = from_input.pickadate('picker')
    var to_input = $('#endingDate').pickadate(),
        to_picker = to_input.pickadate('picker')

// Check if there’s a “from” or “to” date to start with and if so, set their appropriate properties.
    if (from_picker.get('value')) {
        to_picker.set('min', from_picker.get('select'))
    }
    if (to_picker.get('value')) {
        from_picker.set('max', to_picker.get('select'))
    }

// Apply event listeners in case of setting new “from” / “to” limits to have them update on the other end. If ‘clear’ button is pressed, reset the value.
    from_picker.on('set', function (event) {
        if (event.select) {
            to_picker.set('min', from_picker.get('select'))
        } else if ('clear' in event) {
            to_picker.set('min', false)
        }
    })
    to_picker.on('set', function (event) {
        if (event.select) {
            from_picker.set('max', to_picker.get('select'))
        } else if ('clear' in event) {
            from_picker.set('max', false)
        }
    })

    $(document).on('click','.btn-pay-invoice', function () {
        var data = table.row(this.closest('tr')).data();
        $('#pay-invoice-modal').modal('show')
        $('#pay-invoice-form').find('input[name=job_application_id]').val(data.job_application_id);
        $('#pay-invoice-form').find('input[name=address_book_id]').val(data.address_book_id)
    })

     // cropie for pay image
     var $pay_uploadCrop;
     //detect viewport and compare with inserted attribute data
     const pay_b_width = $('#pay_croppie').data('pay-width');
     const pay_b_height = $('#pay_croppie').data('pay-height');
     const pay_v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/1.4;
     const pay_v_height = pay_b_height/pay_b_width*pay_v_width;
 
     //choose appropriate width and height based on device
     const pay_crop_width = (pay_b_width>pay_v_width) ? pay_v_width : pay_b_width; 
     const pay_crop_height = (pay_b_height>pay_v_height) ? pay_v_height : pay_b_height; 
 
     function pay_popupResult(result)
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
             title: 'Pay Invoice',
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
 
         $('#pay-invoice-modal button[type="submit"]').prop("disabled",false);
     }
 
     function pay_readFile(input)
     {
         if (input.files && input.files[0])
         {
             var reader = new FileReader();
 
             reader.onload = function (e)
             {
                 $pay_uploadCrop.croppie('bind', 
                 {
                     url: e.target.result
                 });
 
                 $('#pay_croppie_wrap').show();
             }
 
             reader.readAsDataURL(input.files[0]);
 
         } else {
             swal("Sorry - you're browser doesn't support the FileReader API");
         }
     }
 
     $pay_uploadCrop = $('#pay_croppie').croppie({
         viewport: {
             width: pay_crop_width,
             height: pay_crop_height
         },
         boundary: {
             width: pay_crop_width*1.1,
             height: pay_crop_height*1.1
         },
         enableExif: true
     });
 
 
     $('#pay_input').on('change', function ()
     {
         const file_choosen = $('#pay_input').val();
 
         //check if image is choosen before start cropping
         if (file_choosen !== "")
         {
             Swal.fire({
                 icon: 'warning',
                 text: 'Please adjust and crop the image before submitting the form',
             });
             $('#pay-invoice-modal button[type="submit"]').prop("disabled",true);
             pay_readFile(this);
             $('#pay_croppie').show();
             $('#pay_result').show();
             $('#pay_update_crop').hide();
         }
     });
 
     $('#pay_result').on('click', function (ev) 
     {
         const file_choosen = $('#pay_input').val();
 
         //check if image is choosen before start cropping
         if (file_choosen !== "")
         {
             $pay_uploadCrop.croppie('result',
             {
                 type: 'canvas',
                 size: 'original'
             }).then(function (resp) 
             {
                 resizeImage(resp, pay_b_width, pay_b_height).then((resp) => {
                     pay_popupResult({
                         src: resp
                     });
 
                     $('#pay_base64').val(resp);
                     $('#pay_img').prop('src',resp);
                     $('#pay_img').parent().show();
                     $('#pay_croppie_wrap').hide();
                     $('#pay_result').hide();
                     document.getElementById("pay_image").scrollIntoView();
                     $('#pay_update_crop').show();
                 })
             });
         }else{
             Swal.fire({
                 icon: 'warning',
                 text: 'Please choose an image first',
             });
         }
     });
     $('#pay_update_crop').on('click',function(){
         $('#pay_croppie_wrap').show();
         $('#pay_result').show();
         $(this).hide();
         document.getElementById("pay_croppie_wrap").scrollIntoView();
     })

    $('#pay-invoice-form').on('submit', function (e) {
        e.preventDefault()

        Swal.fire({
            title: 'Update Invoice',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/travelpack/pay_invoice',
                    data: $(this).serialize(),
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table.ajax.reload(false);
                        $('#pay-invoice-modal').modal('hide')
                    },
                    error: function (response) {

                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        })
    });
});