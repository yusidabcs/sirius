

$('#import_job_category_btn').on('click', function () {
    $('#importJobModal').modal('show')
})

$('#importjobmaster').on('submit', function ()
{
    $.ajax({
        // Your server script to process the upload
        url: '/ajax/job/importjobcategory',
        type: 'POST',
        // Form data
        data: new FormData($('#importjobmaster')[0]),

        // Tell jQuery not to process data or worry about content-type
        // You *must* include these options!
        cache: false,
        contentType: false,
        processData: false,

        xhr: function ()
        {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload)
            {
                // For handling the progress of the upload
                myXhr.upload.addEventListener('progress', function (e)
                {
                    if (e.lengthComputable)
                    {
                        $('#import_job_master').attr('disabled', true);
                        $('#import_job_master').html('Loading..');
                    }
                }, false);
            }
            return myXhr;
        },
        success: rs =>
        {
            if(rs.success)
            {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: rs.message
                });
                $('#import_job_master').removeAttr('disabled');
                $('#import_job_master').text('Import');
                $(this).trigger('reset');
                $('#importJobModal').modal('hide');
                getListCategory()
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: rs.message
                });
                $('#import_job_master').removeAttr('disabled');
                $('#import_job_master').text('Import');
                $(this).trigger('reset');
            }
        }
    });
    return false;
});

var categories = [];

function getListCategory()
{
    $.ajax({
        url: "/ajax/job/jobcategory/list",
        type: 'POST',
        data: {

        },
        cache: true,
        timeout: 10000
    })
        .done(response => {

            categories = response;

            $('select[name=parent_id]').html('');
            $('#list_job_category').html('');
            
            $('select[name=parent_id]').append('<option value="0">Main Parent</option>')
            $.each(categories,  (index,item) => {
                if(item.parent_id == 0){
                    $('select[name=parent_id]').append('<option value="'+item.job_speedy_category_id+'">'+item.name+'</option>')
                    if(item.childs != undefined){
                        var html = '<li class="list-group-item" data-id="'+item.job_speedy_category_id+'"> '+item.name+'' +
                            '<a href="#" class="delete_job_category_btn text-danger float-right" data-id="'+item.job_speedy_category_id+'" ><i class="fa fa-times"></i> </a> '+
                            '<a href="" class="edit_job_category_btn text-success float-right mr-3" data-id="'+item.job_speedy_category_id+'"><i class="fa fa-edit"></i></a>' +
                            '<br>' +
                            '<br>' +
                            '<ul class="list-group list_job_category" data-parent="'+item.job_speedy_category_id+'">';
                        $.each(item.childs,  (i,child) => {
                            html += '<li class="list-group-item" data-id="'+child.job_speedy_category_id+'"> '+child.name +
                            '<a href="#" class="delete_job_category_btn text-danger float-right" data-id="'+child.job_speedy_category_id+'" ><i class="fa fa-times"></i> </a> '+
                            '<a href="#" class="edit_job_category_btn text-success float-right mr-3" data-id="'+child.job_speedy_category_id+'"><i class="fa fa-edit"></i> </a>' +
                            '</li>';
                        });
                        html += '</ul>';
                        html += '</li>';
                    }else{
                        var html = '<li class="list-group-item" data-id="'+item.job_speedy_category_id+'"> '+item.name+
                            '<a href="#" class="delete_job_category_btn text-danger float-right" data-id="'+item.job_speedy_category_id+'" ><i class="fa fa-times"></i> </a> '+
                            '<a href="" class="edit_job_category_btn text-success float-right mr-3" data-id="'+item.job_speedy_category_id+'"><i class="fa fa-edit"></i></a>' +
                            '</li>';
                    }
                    $('#list_job_category').append(html)
                }
            })

            $('.list_job_category').sortable({
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                onEnd: function (/**Event*/evt) {
                    var parent = $(evt.to).data('parent')
                    var id = $(evt.item).data('id')
                    var index = evt.newIndex

                    console.log(id)
                    console.log(parent)
                    console.log(index)

                    $.ajax({
                        url: "/ajax/job/jobcategory/updateSequence",
                        type: 'POST',
                        data: {
                            id: id,
                            parent_id: parent,
                            index: index + 1
                        }
                    })
                }
            });

        })
        .fail(response => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Connection to Server Failed!'
            });
        });
}
getListCategory();

$('#add_job_category_btn').on('click', function () 
{
    $('#add_job_category_modal').modal('show')
});

$('#add_job_category_form').on('submit', function () 
{
    $.ajax({
        url: "/ajax/job/jobcategory/insert",
        type: 'POST',
        processData: false,
        contentType: false,
        cache: false,
        enctype: 'multipart/form-data',
        data:  new FormData(this),
    })
        .done(response => 
        {
            categories = response;
            $('#add_job_category_form').trigger('reset');
            $('#add_job_category_modal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Notification.',
                text: response.message
            });
            getListCategory()
            $("#add_job_category_form").trigger("reset");
            $('#banner_croppie_wrap').hide();
            $('#avatar_croppie_wrap').hide();
            $('#curr_banner_add').hide();
            $('#curr_avatar_add').hide();
            $('#update_crop_banner').hide();
            $('#update_crop_avatar').hide();
        })
        .fail(response => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something errors. Please contact admin support!'
            });
        });

    return false;
})

$('body').on('click','.edit_job_category_btn', function () 
{
    var id = $(this).data('id')
    $.ajax({
        url: "/ajax/job/jobcategory/get/"+id,
        type: 'POST',
        cache: true,
        timeout: 10000
    })
        .done(response => {
            $('#edit_job_category_modal').modal('show')
            $('#edit_job_category_form input[name=name]').val(response.name).change();
            $('#edit_job_category_form select[name=parent_id]').val(response.parent_id)
            $('#edit_job_category_form input[name=sequence]').val(response.sequence)
            $('#edit_job_category_form input[name=id]').val(response.job_speedy_category_id)
            $('#edit_job_category_form textarea[name=short_description]').val(response.short_description)

            $('#avatar_current').val('');
            $('#avatar_current_img').attr('src','');
            $('#avatar_current_img').parent().hide();
            $('#avatar_edit_input').val('');
            $('#avatar_edit_croppie').hide();
            $('#avatar_edit_result').hide();
            $('#update_crop_avatar_edit').hide();

            $('#banner_current').val('');
            $('#banner_current_img').attr('src','');
            $('#banner_current_img').parent().hide();
            $('#banner_edit_input').val('');
            $('#banner_edit_croppie').hide();
            $('#banner_edit_result').hide();
            $('#update_crop_banner_edit').hide();

            $.each(response.files, function (index, item) {
                if(item.model_code == 'avatar'){

                    $('#avatar_current').val(item.filename)
                    $('#avatar_current_img').attr('src','/ao/show/'+item.filename)
                    $('#avatar_current_img').parent().show()

                }else if(item.model_code == 'banner'){
                    $('#banner_current').val(item.filename)
                    $('#banner_current_img').attr('src','/ao/show/'+item.filename)
                    $('#banner_current_img').parent().show()
                }
            })
        });

    return false;
});

$('#edit_job_category_form').on('submit', function () {
    var id = $('#edit_job_category_form input[name=id]').val()
    $.ajax({
        url: "/ajax/job/jobcategory/update/"+id,
        type: 'POST',
        data: $(this).serialize(),
    })
        .done(response => {
            categories = response;
            $('#edit_job_category_form').trigger('reset');
            $('#edit_job_category_modal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Notification.',
                text: response.message
            });
            getListCategory()
            $("#edit_job_category_form").trigger("reset");
        })
        .fail(response => {
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

$('body').on('click','.delete_job_category_btn', function () 
{
    const id = $(this).data('id')
    swal.fire({
        title: 'Delete this item?',
        text: 'Are you sure! Once you delete it can never be recovered!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete !'
    }).then((result) => 
    {
        if(result.value)
        {
            $.ajax({
                url: "/ajax/job/jobcategory/delete/"+id,
                type: 'POST',
                data: {

                },
                cache: true,
                timeout: 10000
            })
                .done(response => 
                {
                    Swal.fire({
                        icon: 'success',
                        title: 'Notification.',
                        text: response.message
                    });
                    getListCategory();
                })
                .fail(response => {
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
        }
    });
    return false;
});

function avatarUpload() {
    var $uploadCrop;
    //detect viewport and compare with inserted attribute data
    const b_width = $('#avatar_croppie').data('banner-width');
    const b_height = $('#avatar_croppie').data('banner-height');
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
            title: 'Avatar',
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

                $('#avatar_croppie_wrap').show();
            }

            reader.readAsDataURL(input.files[0]);

        } else {
            swal("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    $uploadCrop = $('#avatar_croppie').croppie({
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

    $('#avatar_input').on('change', function ()
    {
        const file_choosen = $('#avatar_input').val();

        //check if image is choosen before start cropping
        if (file_choosen !== "")
        {
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
            $('button[type="submit"]').prop("disabled",true);
            readFile(this);
            $('#avatar_croppie').show();
            $('#avatar_result').show();
            $('#update_crop_avatar').hide();
        }
    });

    $('#avatar_result').on('click', function (ev)
    {
        const file_choosen = $('#avatar_input').val();

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

                    $('#avatar_base64').val(resp);
                    $('#avatar_current_img_add').prop('src',resp);
                    $('#curr_avatar_add').show();
                    $('#avatar_croppie_wrap').hide();
                    $('#avatar_result').hide();
                    document.getElementById("avatar_image").scrollIntoView();
                    $('#update_crop_avatar').show();
                })
            });
        }else{
            Swal.fire({
                icon: 'warning',
                text: 'Please choose an image first',
            });
        }
    });
}

$('#update_crop_avatar').on('click',function(){
    $('#avatar_croppie_wrap').show();
    $('#avatar_result').show();
    $(this).hide();
    document.getElementById("avatar_croppie_wrap").scrollIntoView();
})

function bannerUpload() {
    var $uploadCrop;
    //detect viewport and compare with inserted attribute data
    const b_width = $('#banner_croppie').data('banner-width');
    const b_height = $('#banner_croppie').data('banner-height');
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
            $('#update_crop_banner').hide();
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
                    $('#banner_current_img_add').prop('src',resp);
                    $('#curr_banner_add').show();
                    $('#banner_croppie_wrap').hide();
                    $('#banner_result').hide();
                    document.getElementById("banner_image").scrollIntoView();
                    $('#update_crop_banner').show();
                })
            });
        }else{
            Swal.fire({
                icon: 'warning',
                text: 'Please choose an image first',
            });
        }
    });
}

$('#update_crop_banner').on('click',function(){
    $('#banner_croppie_wrap').show();
    $('#banner_result').show();
    $(this).hide();
    document.getElementById("banner_croppie_wrap").scrollIntoView();
})

function avatarEditUpload() {
    var $uploadCrop;
    //detect viewport and compare with inserted attribute data
    const b_width = $('#avatar_edit_croppie').data('banner-width');
    const b_height = $('#avatar_edit_croppie').data('banner-height');
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
            title: 'Avatar',
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

                $('#avatar_edit_croppie_wrap').show();
            }

            reader.readAsDataURL(input.files[0]);

        } else {
            swal("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    $uploadCrop = $('#avatar_edit_croppie').croppie({
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


    $('#avatar_edit_input').on('change', function ()
    {
        const file_choosen = $('#avatar_edit_input').val();

        //check if image is choosen before start cropping
        if (file_choosen !== "")
        {
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
            $('button[type="submit"]').prop("disabled",true);
            readFile(this);
            $('#avatar_edit_croppie').show();
            $('#avatar_edit_result').show();
            $('#update_crop_avatar_edit').hide();
        }
    });

    $('#avatar_edit_result').on('click', function (ev)
    {
        const file_choosen = $('#avatar_edit_input').val();

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

                    $('#avatar_edit_base64').val(resp);
                    $('#avatar_current_img').prop('src',resp);
                    $('#avatar_current_img').parent().show();
                    $('#avatar_edit_croppie_wrap').hide();
                    $('#avatar_edit_result').hide();
                    document.getElementById("avatar_image_edit").scrollIntoView();
                    $('#update_crop_avatar_edit').show();
                })
            });
        }else{
            Swal.fire({
                icon: 'warning',
                text: 'Please choose an image first',
            });
        }
    });
}

$('#update_crop_avatar_edit').on('click',function(){
    $('#avatar_edit_croppie_wrap').show();
    $('#avatar_edit_result').show();
    $(this).hide();
    document.getElementById("avatar_edit_croppie_wrap").scrollIntoView();
})

function bannerEditUpload() {
    var $uploadCrop;
    //detect viewport and compare with inserted attribute data
    const b_width = $('#banner_edit_croppie').data('banner-width');
    const b_height = $('#banner_edit_croppie').data('banner-height');
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

                $('#banner_edit_croppie_wrap').show();
            }

            reader.readAsDataURL(input.files[0]);

        } else {
            swal("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    $uploadCrop = $('#banner_edit_croppie').croppie({
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

    $('#banner_edit_croppie').hide();

    $('#banner_edit_input').on('change', function ()
    {
        const file_choosen = $('#banner_edit_input').val();

        //check if image is choosen before start cropping
        if (file_choosen !== "")
        {
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
            $('button[type="submit"]').prop("disabled",true);
            readFile(this);
            $('#banner_edit_croppie').show();
            $('#banner_edit_result').show();
            $('#update_crop_banner_edit').hide();
        }
    });

    $('#banner_edit_result').on('click', function (ev)
    {
        const file_choosen = $('#banner_edit_input').val();

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

                    $('#banner_edit_base64').val(resp);
                    $('#banner_edit_base64').val(resp);
                    $('#banner_current_img').prop('src',resp);
                    $('#banner_current_img').parent().show();
                    $('#banner_edit_croppie_wrap').hide();
                    $('#banner_edit_result').hide();
                    document.getElementById("banner_image_edit").scrollIntoView();
                    $('#update_crop_banner_edit').show();
                })
            });
        }else{
            Swal.fire({
                icon: 'warning',
                text: 'Please choose an image first',
            });
        }
    });
}

$('#update_crop_banner_edit').on('click',function(){
    $('#banner_edit_croppie_wrap').show();
    $('#banner_edit_result').show();
    $(this).hide();
    document.getElementById("banner_edit_croppie_wrap").scrollIntoView();
})

avatarUpload();
bannerUpload();
avatarEditUpload();
bannerEditUpload();