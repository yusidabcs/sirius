$(document).ready(function () {

    $('#table_status_search').materialSelect();

    const table = $('#list_education_course').DataTable({
            "processing": true,
            "serverSide": true,
            'responsive': true,
            "ajax": {
                "url": "/ajax/education/main/list",
                "type": "POST",
                cache: false,
                data: function (d) {
                    d.status = $('#table_status_search').val()
                    d.start_date = $('#startingDate').val()
                    d.end_date = $('#endingDate').val()
                },
                error: function(xhr, error, code) {
                    if (xhr.status === 403 || xhr.status === 401) {
                        swal.fire('Forbidden Access', xhr.responseJSON.message, xhr.responseJSON.status);
                    }
                }
            },
            "columns": [
                {"data": null},
                {"data": 'course_name'},
                {"data": 'status'},
                {"data": 'short_description'},
                {"data": 'created_on'},
                {"data": null}
            ],
            "order" : [[1,'asc']],
            "columnDefs": [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    "searchable": false, "orderable": false, "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        var html = '<div class="container row text-center ">'+
                            '<a id="btn_update_course" data-id="'+row.course_id+'" data-toggle="modal" class="col-sm-6 btn-sm btn-light" href="#"><i class="fa fa-edit" title="Edit Data"></i></a> '+
                            ' <a id="btn_delete_course" data-toggle="modal" data-id="'+row.course_id+'" class="col-sm-6 btn-sm btn-danger" href="#"><i class="fa fa-times" title="Delete Data"></i></a>'+
                            '<div>';
                        return html;
                    },
                    "searchable": false, "orderable": false,"targets": 5
                }
            ]
        });
        

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

    $("#description").trumbowyg({
		tagsToKeep: ['i', 'script[src]','hr', 'img', 'embed', 'iframe', 'input', 'class'],
        semantic: {
            'b': 'strong',
            'i': 'em',
            'strike': 'del',
            'div': 'div' // Editor does nothing on div tags now
        },
	    btns: [
		    ['viewHTML'],
	        ['formatting'],
	        ['strong', 'em', 'del'],
	        ['superscript', 'subscript'],
		    ['foreColor', 'backColor'],
	        ['link'],
	        ['insertImage'],
	        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
	        ['unorderedList', 'orderedList'],
	        ['horizontalRule'],
	        ['removeformat'],
	        ['fullscreen']
	    ]
	});
	
	$('#e_description').trumbowyg({
		tagsToKeep: ['i', 'script[src]','hr', 'img', 'embed', 'iframe', 'input', 'class'],
        semantic: {
            'i': 'em',
            'b': 'strong',
            'strike': 'del',
            'div': 'div' // Editor does nothing on div tags now
        },
	    btns: [
		    ['viewHTML'],
	        ['formatting'],
	        ['strong', 'em', 'del'],
	        ['superscript', 'subscript'],
		    ['foreColor', 'backColor'],
	        ['link'],
	        ['insertImage'],
	        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
	        ['unorderedList', 'orderedList'],
	        ['horizontalRule'],
	        ['removeformat'],
	        ['fullscreen']
	    ]
    });
    
    function countCharacters(e) {
        var textEntered, countRemaining, counter;
        textEntered = document.getElementById('short_description').value;
        counter = (255 - (textEntered.length));
        countRemaining = document.getElementById('charactersRemaining');
        countRemaining.textContent = counter + ' char left';
    }
    el = document.getElementById('short_description');
    el.addEventListener('keyup', countCharacters, false);

    function e_countCharacters(e) {
        var textEntered, countRemaining, counter;
        textEntered = document.getElementById('e_short_description').value;
        counter = (255 - (textEntered.length));
        countRemaining = document.getElementById('e_charactersRemaining');
        countRemaining.textContent = counter + ' char left';
    }
    el_e = document.getElementById('e_short_description');
    el_e.addEventListener('keyup', e_countCharacters, false);

    function imageCourseUpload() {
        var $uploadCrop;
        //detect viewport and compare with inserted attribute data
        const b_width = $('#image_course_croppie').data('image-course-width');
        const b_height = $('#image_course_croppie').data('image-course-height');
        const v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/2;
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
                title: 'Image Course',
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
    
                    $('#image_course_croppie_wrap').show();
                }
    
                reader.readAsDataURL(input.files[0]);
    
            } else {
                swal("Sorry - you're browser doesn't support the FileReader API");
            }
        }
    
        $uploadCrop = $('#image_course_croppie').croppie({
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
    
        $('#image_course_croppie').hide();
    
        $('#image_course_input').on('change', function ()
        {
            const file_choosen = $('#image_course_input').val();
            
            //check if image is choosen before start cropping
            if (file_choosen !== "")
            {
                Swal.fire({
                    icon: 'warning',
                    text: 'Please adjust and crop the image before submitting the form',
                });
                $('button[type="submit"]').prop("disabled",true);
                readFile(this);
                $('#image_course_croppie').show()
                $('#image_course_result').show()
            }
        });
    
        $('#image_course_result').on('click', function (ev)
        {
            const file_choosen = $('#image_course_input').val();
    
            //check if image is choosen before start cropping
            if (file_choosen !== "")
            {
                $uploadCrop.croppie('result',
                    {
                        type: 'canvas',
                        size: 'viewport'
                    }).then(function (resp)
                {
                    popupResult({
                        src: resp
                    });
    
                    $('#image_course_base64').val(resp);
                });
            }else{
                Swal.fire({
                    icon: 'warning',
                    text: 'Please choose an image first',
                });
            }
        });
    }

    function e_imageCourseUpload() {
        var $uploadCrop;
        //detect viewport and compare with inserted attribute data
        const b_width = $('#e_image_course_croppie').data('image-course-width');
        const b_height = $('#e_image_course_croppie').data('image-course-height');
        const v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/2;
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
                title: 'Image Course',
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
    
                    $('#e_image_course_croppie_wrap').show();
                }
    
                reader.readAsDataURL(input.files[0]);
    
            } else {
                swal("Sorry - you're browser doesn't support the FileReader API");
            }
        }
    
        $uploadCrop = $('#e_image_course_croppie').croppie({
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
    
        $('#e_image_course_croppie').hide();
    
        $('#e_image_course_input').on('change', function ()
        {
            const file_choosen = $('#e_image_course_input').val();
            
            //check if image is choosen before start cropping
            if (file_choosen !== "")
            {
                Swal.fire({
                    icon: 'warning',
                    text: 'Please adjust and crop the image before submitting the form',
                });
                $('button[type="submit"]').prop("disabled",true);
                readFile(this);
                $('#e_image_course_croppie').show()
                $('#e_image_course_result').show()
            }
        });
    
        $('#e_image_course_result').on('click', function (ev)
        {
            const file_choosen = $('#e_image_course_input').val();
    
            //check if image is choosen before start cropping
            if (file_choosen !== "")
            {
                $uploadCrop.croppie('result',
                    {
                        type: 'canvas',
                        size: 'viewport'
                    }).then(function (resp)
                {
                    popupResult({
                        src: resp
                    });
    
                    $('#e_image_course_base64').val(resp);
                });
            }else{
                Swal.fire({
                    icon: 'warning',
                    text: 'Please choose an image first',
                });
            }
        });
    }

    imageCourseUpload();
    e_imageCourseUpload();

    $(document).on('click','#add_new_course',function () 
	{
		$('#add_course_modal').modal();
		
    })
    
    $('#add_course_form').on('submit', function () 
    {
        let btn = $('#add_course_btn');
        btn.html('Saving...').attr('disabled',true);
        $.ajax({
            url: "/ajax/education/main/insert",
            type: 'POST',
            processData: false,
            contentType: false,
            cache: false,
            enctype: 'multipart/form-data',
            data:  new FormData(this),
        })
            .done(response => 
            {
                btn.removeAttr('disabled').html('Save Course');
                $('#add_course_modal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Notification.',
                    text: response.message
                });
                table.ajax.reload();
                $("#add_course_form").trigger("reset");
                $('#description').trumbowyg('empty');
            })
            .fail(response => {
                btn.removeAttr('disabled').html('Save Course');
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something errors. Please contact admin support!'
                });
            });

        return false;
    });

    $(document).on('click','#btn_update_course', function () 
    {
        $('.content_loading').show();
        $('#update_course_form').hide();
        $('#update_course_modal').modal('show');
        var id = $(this).data('id')
        $.ajax({
            url: "/ajax/education/main/get/"+id,
            type: 'POST',
            cache: true,
            timeout: 10000
        })
            .done(response => {
                $('#course_id').val(id);
                $('#e_course_name').val(response.course_name);
                $('#e_description').trumbowyg('html',response.description);
                $('#e_short_description').val(response.short_description);
                if ( response.status == 'active' )
                {
                    $('#e_status_active').prop('checked',true);
                }else{
                    $('#e_status_disabled').prop('checked',true);
                }

                $('#image_course_current').val('');
                $('#image_course_current_img').attr('src','');
                $('#image_course_current_img').parent().hide();
                $('#e_image_course_input').val('');
                $('#e_image_course_croppie').hide();
                $('#e_image_course_result').hide();

                if(response.files.length>0) {
                    $('#image_course_current').val(response.files[0].filename)
                    $('#image_course_current_img').attr('src','/ao/show/'+response.files[0].filename);
                    $('#image_course_current_img').parent().show();
                }
                $('.content_loading').hide();
                $('#update_course_form').show();
            });

        return false;
    });

    $('#update_course_form').on('submit', function () {
        let btn = $('#update_course_btn');
        btn.html('Saving...').attr('disabled',true);
        var id = $('#course_id').val()
        $.ajax({
            url: "/ajax/education/main/update/"+id,
            type: 'POST',
            data: $(this).serialize(),
        })
            .done(response => {
                btn.removeAttr('disabled').html('Save Course');
                Swal.fire({
                    icon: 'success',
                    title: 'Notification.',
                    text: response.message
                });
                table.ajax.reload();
                $('#update_course_modal').modal('hide');
                $('#e_description').trumbowyg('empty');
                $("#update_course_form").trigger("reset");
            })
            .fail(response => {
                btn.removeAttr('disabled').html('Save Course');
                if(response.status == 400)
                {
                    text = ''
                    $.each(response.responseJSON.errors, (index,item) => {
                        text += item;
                    })
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: text
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something errors. Please contact admin support!'
                    });
                }
            });
    
        return false;
    });

    $(document).on('click','#btn_delete_course',function (e) 
	{
		swal.fire({
            title: 'Are you sure?',
            text: "This action will delete this item?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
			cancelButtonText: 'Cancel',
			focusConfirm: false,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`/ajax/education/main/delete/`+$(this).data('id'),{
                    headers: {
                        "Content-Type": "application/json"
                        },
                    method : 'GET'
                })
                .then(response => {
                    if (!response.status) {
                        Swal.showValidationMessage(`Error could not delete course data.`)
                    }
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(
                    `Request failed: ${error}`
                    )
                })
                
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    icon: 'success',
                    title: 'Great!',
                    html: result.value.message,
				})
				if (result.value.status=='ok') {
					table.ajax.reload();
				}
            }
        })

		
		
    })
    
});