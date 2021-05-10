function format ( d ) 
{
    return  'Job Description: <br>'+d.short_description;       
} 
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var min = parseInt( $('#min').val(), 10 );
        var max = parseInt( $('#max').val(), 10 );
        var age = parseFloat( data[3] ) || 0; // use data for the age column
 
        if ( ( isNaN( min ) && isNaN( max ) ) ||
             ( isNaN( min ) && age <= max ) ||
             ( min <= age   && isNaN( max ) ) ||
             ( min <= age   && age <= max ) )
        {
            return true;
        }
        return false;
    }
);

$(document).ready(function()
{

    $('#import_job_category_btn').on('click', function () {
        $('#importJobModal').modal('show')
    })

    $('#importjobmaster').on('submit', function ()
    {
        $.ajax({
            // Your server script to process the upload
            url: '/ajax/job/importjobspeedy',
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
                    if (table != null)
                        table.ajax.reload()
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


    //init
	$('.mdb-select').materialSelect();
	
	$("#min_requirement").trumbowyg({
		tagsToKeep: ['i', 'script[src]','hr', 'img', 'embed', 'iframe', 'input', 'class'],
        semantic: {
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
	
	$('#e_min_requirement').trumbowyg({
		tagsToKeep: ['i', 'script[src]','hr', 'img', 'embed', 'iframe', 'input', 'class'],
        semantic: {
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

    var table = null;
    $('#table_category_search').on('change', function (){
        table.ajax.reload()
    });
    table = $('#list_job').DataTable(
        {
            "processing": true,
            "pageLength": $('#list_job').data('limit'),
            "serverSide": true,
            colReorder: true,
            "ajax": {
                "url": "/ajax/job/jobspeedy/list",
                "type": "POST",
                data: function (d) {
                    d.job_speedy_category_id = $('#table_category_search').val()
                }
            },
            rowReorder: true,
            "columns": [
                { "data": "sequence" },
                { "data": "job_speedy_code", 'class' : 'job_speedy_code'  },
                { "data": "job_title" },
                { "data": "short_description"},
                { "data": "category_name" },
                { "data": "created_on" },
                { "data": null },
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        var html = '<div class="container row text-center ">'+
                            '<a data-toggle="modal" class="col-sm-6 job_edit btn-sm btn-light" href="#"><i class="fa fa-edit" title="Edit Data"></i></a>'+
                            '<a data-toggle="modal" data-id="'+row.job_speedy_code+'" class="col-sm-6 job_delete btn-sm btn-danger" href="#"><i class="fa fa-times" title="Delete Data"></i></a>'+
                            '<div>';
                        return html;
                    },
                    "targets": -1
                },
                {
                    "render": function ( data, type, row ) {
                        return '<div class="text-center"><a class="btn-sm btn-light details-control" href="#">Expand</a></div>';
                    },
                    "targets": 3
                }
            ],
        } );
    table.on( 'row-reorder', function ( e, settings, details ) {
        $.each(settings, function (index, item) {
            $.ajax({
                url: "/ajax/job/jobspeedy/updateSequence",
                type: 'POST',
                data: {
                    'job_speedy_code' : $(item.node).find('.job_speedy_code').html(),
                    'sequence' : item.newPosition + 1,
                },
            })
        })
    });
    // Array to track the ids of the details displayed rows
    var detailRows = [];

    $(document).on( 'click', 'tr a.details-control', function ()
    {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
        const idx = $.inArray( tr.attr('id'), detailRows );
        if ( row.child.isShown() )
        {
            $(this).html('Expand');
            tr.removeClass( 'details' );
            row.child.hide();

            // Remove from the 'open' array
            detailRows.splice( idx, 1 );

        }
        else {
            $(this).html('Hide')
            tr.addClass( 'details' );
            row.child( format( row.data() ) ).show();

            // Add to the 'open' array
            if ( idx === -1 ) {
                detailRows.push( tr.attr('id') );
            }
        }
        return false;
    } );

    // On each draw, loop over the `detailRows` array and show any child rows
    table.on( 'draw', function ()
    {
        $.each( detailRows, function ( i, id )
        {
            $('#'+id+' a.details-control').trigger( 'click' );
        } );
    } );


	
	// Array to track the ids of the details displayed rows
	var detailRows = [];
 
	/*$('#list_job tbody').on( 'click', 'tr a.details-control', function () 
	{
        var tr = $(this).closest('tr');
        var row = table.row( tr );
        const idx = $.inArray( tr.attr('id'), detailRows );
		if ( row.child.isShown() ) 
		{
			$(this).html('Expand');
            tr.removeClass( 'details' );
            row.child.hide();
			
            // Remove from the 'open' array
			detailRows.splice( idx, 1 );
			
        }
        else {
			$(this).html('Hide')
            tr.addClass( 'details' );
            row.child( format( row.data() ) ).show();
			
            // Add to the 'open' array
            if ( idx === -1 ) {
                detailRows.push( tr.attr('id') );
            }
        }
    } );*/
 

	
    $('.dataTables_length').addClass('bs-select');
    
	$(document).on('click','.add_new_job',function () 
	{
		//insert modal
		$('#add_job_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
		
	})


	$("#add_job_speedy_form").submit(function(e) 
	{
        e.preventDefault();
		const data = new FormData(this);
		const form = $(this);
        $('#add_job_speedy_form #add_job_btn').html('Saving...');
        $('#add_job_speedy_form #add_job_btn').addClass('disabled');

        $.ajax({
            url: "/ajax/job/jobspeedy/insert",
            type: 'POST',
            enctype: 'multipart/form-data',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
        })
			.done(response => 
			{
                Swal.fire({
                    icon: 'success',
                    title: 'Great!',
                    text: response.message
				});
                //location.reload();
				form[0].reset();
				$('#job_code_valid').hide();
				$('#add_job_modal').modal('hide');
				$('#min_requirement').trumbowyg('empty');
                if (table != null)
                    table.ajax.reload();

                $('#country_id, #principal, #brand').materialSelect({
                    destroy: true
                });
                $('#country_id').val('');
                $('#principal').val('');
                $('#brand').html('<option value="">Select Brand Code</option>');
                $('#job_master').html('');

                $('#country_id, #principal, #brand').materialSelect();

                $('#add_job_speedy_form #add_job_btn').html('Send');
                $('#add_job_speedy_form #add_job_btn').removeClass('disabled');
                
            })
			.fail(response => 
			{
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

                $('#add_job_speedy_form #add_job_btn').html('Send');
                $('#add_job_speedy_form #add_job_btn').removeClass('disabled');

            });
        return false;
    });

	
	$('body').on( 'click', '.job_edit', function ()
	{
		const data = table.row(this.closest('tr')).data();
		//edit modal

        console.log(data.job_speedy_category_id)
		$('#edit_job_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
		//fill data
        let country = data.country;
        //console.log(country);
        $('#e_country_id').materialSelect({
            destroy: true
        });
        $('#e_country_id').val(country.split(','));
        $('#e_country_id').materialSelect();
        

		$('#e_job_speedy_code').val(data.job_speedy_code).change();
		$('#e_job_title').val(data.job_title).change();
		$('#e_short_description').val(data.short_description);
		$('#e_min_requirement').trumbowyg('html',data.min_requirement);
		$('#e_job_speedy_category_id').val(data.job_speedy_category_id);
		$('#e_min_salary').val(parseInt(data.min_salary) /100).change();
		$('#e_max_salary').val(parseInt(data.max_salary)/100).change();
		$('#e_min_experience').val(data.min_experience).change();
        //$('#e_min_experience_month').val(data.min_experience_month).change();
        $('#e_min_education').val(data.min_education).change().materialSelect();
		$('#e_min_english_experience').val(data.min_english_experience).change();
		$('#old_job_speedy_code').val(data.job_speedy_code);

		if ( data.stcw_req == 1 )
		{
			$('#e_stcw_req1').prop('checked',true);
		}else{
			$('#e_stcw_req2').prop('checked',true);
		}

        if(data.cover_image !=='') {
            $('#current_cover_image img').attr('src','/fm/image/'+data.cover_image);
            $('#image_prev').val(data.cover_image);
            $('#current_cover_image').show();
        } else {
            $('#image_prev').val('');
            $('#current_cover_image').hide();
        }
	});

	$(document).on('click','.job_delete',function (e) // button job delete
	{
		Swal.fire({
            title: 'Are you sure?',
            text: "This action will delete this item?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {	
				e.preventDefault();
				$.ajax({
					url: "/ajax/job/jobspeedy/delete",
					type: 'POST',
					data: {
						job_speedy_code: $(this).data('id')
					},
					cache: false,
					timeout: 10000
				})
				.done(response => {
					Swal.fire({
					  icon: 'success',
					  title: 'Great!',
					  text: response.message
					});

                    $('#edit_job_modal').modal('hide');
                    if (table != null)
					    table.ajax.reload();
				})
				.fail(response => {
					Swal.fire({
					  icon: 'error',
					  title: 'Oops...',
					  text: 'Connection to Server Failed!'
					});
				});
            }
        });
		
	})

	$("#form_edit").submit(function(e) 
	{
		e.preventDefault();
		const form = $(this);
        $('#edit_job_btn').html('Saving...');
        $('#edit_job_btn').addClass('disabled');
		$('#e_min_salary').val($('#e_min_salary').val()*100);
		$('#e_max_salary').val($('#e_max_salary').val()*100);
		const data = new FormData(this);
		$.ajax({
			url: "/ajax/job/jobspeedy/update",
			type: 'POST',
			data: data,
			cache: false,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data'
		})
		.done(response => 
		{
			Swal.fire({
			  icon: 'success',
			  title: 'Great!',
			  text: response.message
			});
            //location.reload();
			form[0].reset();
			$('#e_job_code_valid').hide();
			$('#edit_job_modal').modal('hide');
			$('#e_min_requirement').trumbowyg('empty');
            if (table != null)
			    table.ajax.reload();
                
                $('#e_principal, #e_brand').materialSelect({
                    destroy: true
                });
                //$('#e_country_id').val([]);
                $('#e_principal').val('');
                $('#e_brand').html('<option value="">Select Brand Code</option>');
                $('#e_job_master').html('');


                $('#e_principal, #e_brand').materialSelect();
                $('#current_cover_image').hide();
                $('#edit_job_btn').html('Edit');
                $('#edit_job_btn').removeClass('disabled');
		})
		.fail(response => {
			Swal.fire({
			  icon: 'error',
			  title: 'Oops...',
			  text: 'Connection to Server Failed!'
			});

            $('#edit_job_btn').html('Edit');
            $('#edit_job_btn').removeClass('disabled');
		});

        
	});


	var timer_job_code;
	var xhr = null;
	//check duplicate code
	$( "body" ).on('keyup','#job_speedy_code',function()
	{  
		const spinner = $('#job_code_spinner');
        const valid = $('#job_code_valid');
		const searchString = $(this).val().trim();
        clearTimeout(timer_job_code); 
        spinner.hide();
		valid.hide();

        $('#add_job_btn').prop('disabled',true);
        if(searchString == '')
				return;
				
        spinner.show();

        //Give a second delay to see if the user is finished typing to reduce many ajax call per user keyup
        timer_job_code = setTimeout(function()
		{
			if(xhr)
				xhr.abort()
			
			xhr = $.ajax({
						url: "/ajax/job/jobspeedy/checkjobcode",
						type: 'POST',
						data: {
							code:searchString,
						},
						cache: false,
						timeout: 10000
					})
					.done(response => 
					{
						if (response.duplicate) 
						{
							$('#job_speedy_code_help').html(response.message).show();
							$('#add_job_btn').attr('disabled',true);
                            valid.hide();
						}else{
							$('#job_speedy_code_help').html(response.message).hide();
							$('#add_job_btn').attr('disabled',false);
							valid.show();
						}
						
					})
					.fail(response => {
						
					}).always(function()
					{
						spinner.hide();
					});
		}, 1000);
	
	});

	var timer_e_job_code;
	$( "body" ).on('keyup','#e_job_speedy_code',function()
	{  
		const spinner = $('#e_job_code_spinner');
        const valid = $('#e_job_code_valid');
		const searchString = $(this).val().trim();
		const defaultCode = $('#old_job_speedy_code').val();
        clearTimeout(timer_e_job_code); 
        spinner.hide();
		valid.hide();
		
       
        if(searchString == '')
			return;
		
		$('#edit_job_btn').prop('disabled',true);
		spinner.show();
		
        //Give a second delay to see if the user is finished typing to reduce many ajax call per user keyup
        timer_e_job_code = setTimeout(function()
        { 
			if ( searchString == defaultCode )
			{
				$('#edit_job_btn').prop('disabled',false);
				spinner.hide();
				valid.hide();
				return;
			}

			if(xhr)
				xhr.abort()
			
			xhr = $.ajax({
						url: "/ajax/job/jobspeedy/checkjobcode",
						type: 'POST',
						data: {
							code:searchString,
						},
						cache: false,
						timeout: 10000
					})
					.done(response => {

						if (response.duplicate) {
							$('#e_job_speedy_code_help').html(response.message).show();
							$('#edit_job_btn').attr('disabled',true);
                            valid.hide();
						}else{
							$('#e_job_speedy_code_help').html(response.message).hide();
							$('#edit_job_btn').attr('disabled',false);
							valid.show();
						}
						
					})
					.fail(response => {
						
					}).always(function()
					{
						spinner.hide();
					});
		},1000);
	});

    var el;

    function countCharacters(e) {
        var textEntered, countRemaining, counter;
        textEntered = document.getElementById('short_description').value;
        counter = (255 - (textEntered.length));
        countRemaining = document.getElementById('charactersRemaining');
        countRemaining.textContent = counter + ' char left';
    }
    function countCharacters2(e) {
        var textEntered, countRemaining, counter;
        textEntered = document.getElementById('e_short_description').value;
        counter = (255 - (textEntered.length));
        countRemaining = document.getElementById('charactersRemaining2');
        countRemaining.textContent = counter + ' char left';
    }
    el = document.getElementById('short_description');
    el.addEventListener('keyup', countCharacters, false);

    el = document.getElementById('e_short_description');
    el.addEventListener('keyup', countCharacters2, false);

    $('.country_section h4').click(function(){
        $('.chk_country_section').slideToggle();
    });

    /*bannerUpload();
    $('#update_crop_banner').on('click',function(){
        $('#banner_croppie_wrap').show();
        $('#banner_result').show();
        $(this).hide();
        document.getElementById("banner_croppie_wrap").scrollIntoView();
    })*/

    $('#principal').on('change', function() {

        var brandCode = $(document).find('#brand');
        var JobMaster = $(document).find('#job_master');

        if($(this).val()=='') {
            brandCode.materialSelect({
                destroy: true
            });
            var html = '<option value="" selected>Select Brand Code</option>';
            brandCode.html(html);
            brandCode.materialSelect();
            $('#brand').trigger('change');
            return ;
        }
        brandCode.materialSelect({
            destroy: true
        });
        var html = '<option value="" selected>Loading...</option>';
        brandCode.html(html);
        brandCode.materialSelect();

        var html = '<option value="s" disabled>Loading...</option>';
        JobMaster.html(html);

        $.ajax({
            url: '/ajax/job/getprincipalbrand/' + $(this).val(),
            method: 'POST',
            success: function(response) {
                brandCode.removeAttr('disabled');

                if (response) {
                    brandCode.materialSelect({
                        destroy: true
                    });
                    var html = '<option value="" selected>Select Brand Code</option>';
                    
                    response.forEach(function(item) {
                        html += '<option value="'+item['principal_brand_code']+'">'+item['name']+'</option>';
                    });
                    
                    brandCode.html(html);
                    brandCode.materialSelect();
                    $('#brand').trigger('change');
                    
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Warning',
                    text: error.responseText
                });
            }
        });

    });

    $('#e_principal').on('change', function() {

        var brandCode = $(document).find('#e_brand');
        var JobMaster = $(document).find('#e_job_master');

        if($(this).val()=='') {
            brandCode.materialSelect({
                destroy: true
            });
            var html = '<option value="" selected>Select Brand Code</option>';
            brandCode.html(html);
            brandCode.materialSelect();
            $('#e_brand').trigger('change');
            return ;
        }
        brandCode.materialSelect({
            destroy: true
        });
        var html = '<option value="" selected>Loading...</option>';
        brandCode.html(html);
        brandCode.materialSelect();

        var html = '<option value="s" disabled>Loading...</option>';
        JobMaster.html(html);

        $.ajax({
            url: '/ajax/job/getprincipalbrand/' + $(this).val(),
            method: 'POST',
            success: function(response) {
                brandCode.removeAttr('disabled');

                if (response) {
                    brandCode.materialSelect({
                        destroy: true
                    });
                    var html = '<option value="" selected>Select Brand Code</option>';
                    
                    response.forEach(function(item) {
                        html += '<option value="'+item['principal_brand_code']+'">'+item['name']+'</option>';
                    });
                    
                    brandCode.html(html);
                    brandCode.materialSelect();
                    $('#e_brand').trigger('change');
                    
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Warning',
                    text: error.responseText
                });
            }
        });

    });

    $('#brand').on('change', function() {
        let principal = $('#principal').val();
        let brand = $('#brand').val();
        let id_job_master = 'job_master';
        load_job_master(principal,brand,id_job_master);
    })

    $('#e_brand').on('change', function() {
        let principal = $('#e_principal').val();
        let brand = $('#e_brand').val();
        let id_job_master = 'e_job_master';
        load_job_master(principal,brand,id_job_master);
    })

    $('#job_master').select2({
        width: '100%',
        multiple: true,
        placeholder: "Select Job Master",
        allowClear: true
    });

    $('#e_job_master').select2({
        width: '100%',
        multiple: true,
        placeholder: "Select Job Master",
        allowClear: true
    });
});

function load_job_master(p_principal,p_brand,p_id_job_master) {
    var JobMaster = $(document).find('#'+p_id_job_master);

    let principal = p_principal;
    let brand = p_brand;
    if(principal=='') {
        //var html = '';
        var html = '';
        JobMaster.html(html);
        return ;
    }

    var html = '<option value="s" disabled>Loading...</option>';
    JobMaster.html(html);

    $.ajax({
        url: '/ajax/job/jobspeedy/getJobMaster',
        data: {principal:principal,brand:brand},
        method: 'POST',
        cache: false,
        success: function(response) {
            //console.log(response);
            // brandCode.removeAttr('disabled');

            if (response) {
                var html = '';
                
                response.forEach(function(item) {
                    html += '<option value="'+item['job_code']+'">'+item['job_code']+' - '+item['job_title']+'</option>';
                });
                
                JobMaster.html(html);
                
            }
        },
        error: function(error) {
            Swal.fire({
                icon: 'error',
                title: 'Warning',
                text: error.responseText
            });
        }
    });

}

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
