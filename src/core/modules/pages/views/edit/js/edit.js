function getEntryIndexArray()
{
    var sequence = Array();

    $('.entry-content').each(function( index ) {

        sequence[index] = this.id;

    });
    return sequence;
}

$(document).ready(function()
{
    //enable tooltip
    $('[data-toggle="tooltip"]').tooltip()

    //hide new content and section content
    $('.entry-content, #entry-0').hide();

	//sort the sections
	$('#list_page_placeholder').sortable({
        onEnd: function (/**Event*/evt) {
            var sequence = Array();

            $('.sortable').each(function( index )
            {
                sequence[index] = $(this).data('id');
            });

            //give half second delay
            setTimeout(function()
            { 
                $.ajax({
                    url: "/ajax/pages/contentsort",
                    type: 'POST',
                    data: {
                        link_id: $('#link_id').val(),
                        sequence: sequence,
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
                    })
                    .fail(response => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Connection to Server Failed!'
                        });
                    });
            },500);
        },
    });


	//var order = $('#sectionSort').sortable('toArray');
	
	//sort the images
    function sortFiles(element = '.list-files-sortable'){
        $(element).sortable({
            onEnd: function (/**Event*/evt) {
                if(evt.newIndex == evt.oldIndex)
                    return;
                $.ajax({
                    url: "/ajax/pages/filesort",
                    type: 'POST',
                    data: {
                        file_manager_id: $(evt.item).data('id'),
                        link_id: $('#link_id').val(),
                        model: $(evt.item).data('model'),
                        model_id: $(evt.item).data('model_id'),
                        new_sequence: evt.newIndex,
                        old_sequence: evt.oldIndex,
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
                    })
                    .fail(response => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Connection to Server Failed!'
                        });
                    });
            },
        });
    }
    sortFiles()
	
	//sort the images
	$('#fileSort').sortable();
	//var order = $('#cardSort').sortable('toArray');
	
	//select boxes
    $('.mdb-select').materialSelect();
    $('.mdb-select').on('click',function () {
        $(this).find('ul').first().css({"maxHeight":"100px"});
    });


	//page selection
    $(document).on('click','.page-selection', function () {
        var target = $(this).data('id')
        $('.entry-content, #entry-core').hide();

        
        if(!$('#'+target).is(":visible")){
            $('#'+target).fadeIn();
            //check if current section has images

            var data = new FormData();
            data.append('model', target);
            data.append('link_id', $('#link_id').val());

            $.ajax({
                url: '/ajax/pages/checkimages',
                method: 'POST',
                dataType: 'json',
                contentType: false,
                processData: false,
                data: data,
                success: function(response) {
                    var imagePosition = $('#image_position_area_'+target.slice(target.length - 1));
                    if (response.data_count === 0) {    
                        imagePosition.hide();
                    } else {
                        imagePosition.show();
                    }
                    
                }
            });
        }
    })

    //create new page content
    $('#contentAdd').on('click',function () {
        var content = $('#next_content_id').val();
        $('#image_position_area_'+content).hide();
        $('.entry-content, #entry-core').hide();

        var template = $('#entry-0').clone();
        var html = template.html().toString();
        html = html.replace(/-0/g,'-'+(content))
        html = html.replace(/_0/g,'_'+(content))
        html = html.replace(/data-id="0"/g,'data-id="'+(content)+'"')
        var new_html = '<div class="card chart-card entry-content" id="entry-'+content+'">'+html+'</div>';
        $('.col-md-9').append(new_html)
        $('#entry-'+content).fadeIn();


        $('#content-entry-'+content).trumbowyg({
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

        //sort file
        sortFiles('#list-image-'+content)
        sortFiles('#list-file-'+content)

        $(this).hide();
    })
	
	//put in the text area
	$("#section_text, .section_text").trumbowyg({
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
		
	//the form should never actually submit
	$("form").submit(function(e){
        e.preventDefault();
    });
	
	//updating page information
	$("#submit-page-info").on('click', function(e)
	{
		var text = $(this).html();

		//stop the submit process completely
		e.preventDefault();

		//just serialize the form data
        var data = $("#section-info").serialize()

		if( $("#page_heading").val().length > 0)
		{
            $(this).prop("disabled", true);
            $(this).html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
            );
			//send to the server
			$.ajax({
				url: "/ajax/pages/pageinfo",
				type: 'POST',
				data: data,
				cache: false,
				timeout: 10000
			})
			.done(response => {

				Swal.fire({
				  icon: 'success',
				  title: 'Great!',
				  text: response.message
				});
                $(this).prop("disabled", false);
                $(this).html(text);
			})
			.fail(response => {
				Swal.fire({
				  icon: 'error',
				  title: 'Oops...',
				  text: 'Connection to Server Failed!'
				});
                $(this).prop("disabled",false);
                $(this).html(text);
			});
		} else {
			Swal.fire({
			  icon: 'error',
			  title: 'Oops...',
			  text: 'You need to have something in the Page Heading to process it.'
			});
		}; 
		
	});
		
	//ensure they don't try to upload a bad file
	$( "#page-file, .file" ).change(function() {

		var file_type = $(this).data('type');
        var type = $("input[name='"+file_type+"']:checked").val();
	    var file = this.files[0];
	    var fileType = file.type;
        var fileSize = file.size;
        
        console.log(fileType);
        

	    if(type == "file")
	    {
            console.log("match");
            
		    var match = ['application/pdf', 'application/msword', 'application/vnd.ms-office', 'image/jpeg', 'image/jpg', 'image/png', 'image/vnd.wap.wbmp'];
            var checkMatch = match.some(function(item) {
                return item == fileType;
            });
			//check file type
			if(!checkMatch)
			{
				Swal.fire({
				  icon: 'error',
				  title: 'Oops...',
				  text: 'Sorry, only PDF, DOC, JPG & PNG files are allowed to upload.'
				});	
				
				$(this).val('');
				
				return false;
			}
			
	    } else if(type == "image"){
		    
            var match = ['image/jpeg', 'image/jpg', 'image/png', 'image/vnd.wap.wbmp'];
            var checkMatch = match.some(function(item) {
                return item == fileType;
            });
		
			//check file type
			if(!checkMatch)
			{
				Swal.fire({
                    icon: 'error',
				  title: 'Oops...',
				  text: 'Sorry, only JPG & PNG files are allowed to upload.'
				});	
				
				$(this).val('');
				
				return false;
			}
			
	    } else {
		    
		    Swal.fire({
                icon: 'error',
			  title: 'Oops...',
			  text: 'Sorry, the selected type is not recognise - Please select upload type first.'
			});	
			
			return false;
			
	    }
		
	    //check the file size
		if(fileSize > 5120000)
		{
			Swal.fire({
			  icon: 'error',
			  title: 'Oops...',
			  text: 'The file you are trying to upload is too big (5 MB is maximum).'
			});
			
			$(this).val('');
			
			return false;
		}
		
		return true;
		
	});
	
	//upload the new image
    $(document).on('submit','#page-file-form, .contentForm',function(e)
    {
        //stop the submit process completely
        e.preventDefault();
        const select = $(this).find('select[name=file_type]');
        const disabled = select.prop("disabled");
        const select_value = select.val();

        if (disabled)
        {
            select.prop("disabled",false);
        }
        const section = $(this).find('input[name=section]').val();
        const entry = $(this).find('input[name=entry]').val();
        const btn = $(this).find('.btn-submit');
        const text = btn.html();
        const form = $(this);

         //check first if page content data already uploaded first before uploading images or file
         if ( entry == 'core' )
         {
             //check in database if already exist
             const link_id = $('#link_id').val();
             $.ajax({
                 url: "/ajax/pages/checkpageinfo/"+link_id,
                 type: 'GET',
                 cache: false,
                 timeout: 10000
             })
                 .done(response => {
                     if (response.message == 'not exist')
                     {
                         Swal.fire({
                             icon: 'error',
                             title: 'Empty page information',
                             text: 'Please submit page information first before continue'
                         });    
                         return;
                     }else{
                         //continue
                         $.ajax({
                             url: '/ajax/pages/fileinput',
                             type: 'POST',
                             data: new FormData(this),
                             dataType: 'json',
                             contentType: false,
                             cache: false,
                             processData: false,
                             beforeSend: function()
                             {
                                 btn.prop("disabled", true);
                                 btn.html(
                                     `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
                                 );
                             },
                             success: response =>
                             {
                                 if(response.error !== undefined)
                                 {
                                     Swal.fire({
                                         icon: 'error',
                                         title: 'Oops...',
                                         text: response.error
                                     });
                                 }
 
                                 btn.prop("disabled", false);
                                 btn.html(text);
 
                                 //append new obj to view
                                 var item = response.data;
                                 var section = $(this).data('section')
                                 var type = item.mime_type.slice(0, 5);
                                 var file_link = '                                            <a href="'+item.file_prefix+'/'+item.file_name+'" title="'+item.sdesc+'">'+item.file_name+'</a>';
                                 var image_link = '                                            <img class="img-fluid" src="'+item.image_prefix+'/page/'+item.file_name+'" alt="'+item.title+'">\n';
 
                                 var html =`                                                     <div class="list-group-item"  id="file_wrapper_${item.file_manager_id}"
                                                                                                     data-id="${item.file_manager_id}"
                                                                                                     data-model="${item.model}"
                                                                                                     data-model_id="${item.model_id}">
                                                                                                     <div class="row"> 
                                                                                                         <div class="col-4">
                                                                                                             ${(type == 'image' ? image_link : file_link)}
                                                                                                         </div>
                                                                                                         <div class="col-8">
                                                                                                             <div class="row">
                                                                                                                 <div class="col-8">
                                                                                                                     <div class="form-group">
                                                                                                                         <input name="title" id="title_${item.file_manager_id}" type="text" class="form-control" value="${item.title}" size="100">
                                                                                                                     </div>
                                                                                                                 </div>
                                                                                                             </div>
                                                                                                             <div class="form-group">
                                                                                                                 <input name="sdesc" type="text" id="sdesc_${item.file_manager_id}" class="form-control" value="${item.sdesc}" size="254">
                                                                                                             </div>
                                                                                                             <div class="md-form">
                                                                                                                 <input name="active" type="checkbox" id="status_${item.file_manager_id}" class="form-check-input" value="1" ${(item.status == 1 ? 'checked' : '')}>
                                                                                                                 <label for="active">Active</label>
                                                                                                             </div>
                                                                                                             <div class="md-form">
                                                                                                                 <button class="btn-floating btn-sm btn-success update_file" data-sequence="${item.sequence}" data-file-id="${item.file_manager_id}"><i class="fas fa-edit"></i></button>
                                                                                                                 <button class="btn-floating btn-sm btn-danger delete_file" data-file-id="${item.file_manager_id}"><i class="fas fa-trash"></i></button>
                                                                                                             </div>
                                                                                                         </div>
                                                                                                     </div>
                                                                                                 </div>`;
                                // console.log($('#entry_'+section).val());
                                // console.log($('#image_position_area_'+section));
                                
                                
                                 (type == 'image' ) 
                                     ? $('#list-image-'+$('#entry_'+section).val()).append(html) 
                                     : $('#list-file-'+$('#entry_'+section).val()).append(html);
                                 
                                 form[0].reset();
                                 if (disabled)
                                 {
                                     select.prop("disabled",true);
                                 }
                                 select.val(select_value);
                             },
                             error: rs => {
                                 btn.prop("disabled", false);
                                 btn.html(text);
                             }
                         });
         
                     }
                 })
                 .fail(response => {
                     Swal.fire({
                         icon: 'error',
                         title: 'Oops...',
                         text: 'Connection to Server Failed!'
                     });
                 });
         }else{
             //if not core, check if exist in <ul> list
             if (!$('.page-selection[data-id="'+section+'-'+entry+'"]').length)
             {
                 Swal.fire({
                     icon: 'error',
                     title: 'Empty page information',
                     text: 'Please submit page information first before continue'
                 });    
                 return;
             }else{
                 //continue
                 
                 $.ajax({
                     url: '/ajax/pages/fileinput',
                     type: 'POST',
                     data: new FormData(this),
                     dataType: 'json',
                     contentType: false,
                     cache: false,
                     processData: false,
                     beforeSend: function(){
                         btn.prop("disabled", true);
                         btn.html(
                             `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
                         );
                     },
                     success: response =>
                     {
                         if(response.error !== undefined){
                             Swal.fire({
                                 icon: 'error',
                                 title: 'Oops...',
                                 text: response.error
                             });
                         }
 
                         btn.prop("disabled", false);
                         btn.html(text);
 
                         //append new obj to view
                         var item = response.data;
 
 
                         var section = $(this).data('section')
                         var type = item.mime_type.slice(0, 5);
                         var file_link = '                                            <a href="'+item.file_prefix+'/'+item.file_name+'" title="'+item.sdesc+'">'+item.file_name+'</a>';
                         var image_link = '                                            <img class="img-fluid" src="'+item.image_prefix+'/page/'+item.file_name+'" alt="'+item.title+'">\n';
 
                         var html =`                                                     <div class="list-group-item"  id="file_wrapper_${item.file_manager_id}"
                                                                                             data-id="${item.file_manager_id}"
                                                                                             data-model="${item.model}"
                                                                                             data-model_id="${item.model_id}">
                                                                                             <div class="row"> 
                                                                                                 <div class="col-4">
                                                                                                     ${(type == 'image' ? image_link : file_link)}
                                                                                                 </div>
                                                                                                 <div class="col-8">
                                                                                                     <div class="row">
                                                                                                         <div class="col-8">
                                                                                                             <div class="form-group">
                                                                                                                 <input name="title" id="title_${item.file_manager_id}" type="text" class="form-control" value="${item.title}" size="100">
                                                                                                             </div>
                                                                                                         </div>
                                                                                                     </div>
                                                                                                     <div class="form-group">
                                                                                                         <input name="sdesc" type="text" id="sdesc_${item.file_manager_id}" class="form-control" value="${item.sdesc}" size="254">
                                                                                                     </div>
                                                                                                     <div class="md-form">
                                                                                                         <input name="active" type="checkbox" id="status_${item.file_manager_id}" class="form-check-input" value="1" ${(item.status == 1 ? 'checked' : '')}>
                                                                                                         <label for="active">Active</label>
                                                                                                     </div>
                                                                                                     <div class="md-form">
                                                                                                         <button class="btn-floating btn-sm btn-success update_file" data-sequence="${item.sequence}" data-file-id="${item.file_manager_id}"><i class="fas fa-edit"></i></button>
                                                                                                         <button class="btn-floating btn-sm btn-danger delete_file" data-file-id="${item.file_manager_id}"><i class="fas fa-trash"></i></button>
                                                                                                     </div>
                                                                                                 </div>
                                                                                             </div>
                                                                                         </div>`;

                        var imagePosition = $('#image_position_area_'+section);
                        imagePosition.show();
                        
                         (type == 'image' ) 
                                     ? $('#list-image-'+$('#entry_'+section).val()).append(html) 
                                     : $('#list-file-'+$('#entry_'+section).val()).append(html);
                         
                         form[0].reset();
                         if (disabled)
                         {
                             select.prop("disabled",true);
                         }
                         select.val(select_value);
                     },
                     error: rs => {
                         btn.prop("disabled", false);
                         btn.html(text);
                     }
                 });
 
             }
         }
    });

	//update file manager
    $('body').on('click','.update_file',function()
    {
		const file_id = $(this).data('file-id');
		const sequence = $(this).data('sequence');

        $.ajax({
            url: "/ajax/pages/fileupdate",
            type: 'POST',
            data: {
                link_id: $('#link_id').val(),
                file_id: file_id,
                title: $('#title_'+file_id).val(),
                sdesc: $('#sdesc_'+file_id).val(),
                status: $('#status_'+file_id).is(':checked') ? 1 : 0,
                sequence: sequence
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
            })
            .fail(response => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Connection to Server Failed!'
                });
            });

	});

	//delete file manager
    $('body').on('click','.delete_file',function()
    {
        const file_id = $(this).data('file-id')
       
        swal.fire({
            title: 'Delete this file?',
            text: 'Are you sure! Once you delete it can never be recovered!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => 
        {
            if ( result.value )
            {
                $.ajax({
                    url: "/ajax/pages/filedelete/"+file_id,
                    type: 'GET',
                    cache: false,
                    timeout: 10000
                })
                    .done(response => {

                        Swal.fire({
                            icon: 'success',
                            title: 'Great!',
                            text: response.message
                        });
                        $('#file_wrapper_'+file_id).remove()
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
    });

    //update page content
    $('body').on('click','.content-submit', function(e)
    {
        //set up var
        var content_id = $(this).data('id');

        //stop the submit process completely
        e.preventDefault();

        //setup variables
        var	link_id = $("#link_id").val(),
            content_type = $("#content-type-entry-"+content_id).val(),
            show_heading = $("#show_heading-entry-"+content_id).is(":checked") ? '1' : '0',
            heading = $("#heading-entry-"+content_id).val(),
            sdesc = $("#sdesc-entry-"+content_id).val(),
            text = $("#content-entry-"+content_id).val(),
            image_position = $(".image_position_"+content_id+":checked").val();
            sequence = getEntryIndexArray();
        //extra for contact_form
        if(content_type == 'contact_form' )
        {
            var to_name = $("#contact-to-name-"+content_id).val(),
                to_email = $("#contact-to-email-"+content_id).val(),
                to_subject = $("#contact-to-subject-"+content_id).val(),
                submitted_heading = $("#contact-submitted-heading-"+content_id).val(),
                submitted_sdesc = $("#contact-submitted-sdesc-"+content_id).val(),
                submitted_content = $("#contact-submitted-entry-"+content_id).val()
        } else {
            var to_name = '',
                to_email = '',
                to_subject = '',
                submitted_heading = '',
                submitted_sdesc = '',
                submitted_content = ''
        }

        if( heading.length > 0)
        {
            //send to the server
            $.ajax({
                url: "/ajax/pages/pagecontent",
                type: 'POST',
                data: {
                    'link_id':	link_id,
                    'content_id': content_id,
                    'content_type': content_type,
                    'show_heading': show_heading,
                    'heading': heading,
                    'sdesc': sdesc,
                    'section_text': text,
                    'to_name': to_name,
                    'to_email': to_email,
                    'to_subject': to_subject,
                    'submitted_heading': submitted_heading,
                    'submitted_sdesc': submitted_sdesc,
                    'submitted_content': submitted_content,
                    'sequence': sequence,
                    'image_position': image_position,
                },
                cache: false,
                timeout: 10000
            })
                .done(function(response) {
                    //setup the content heading
                    $("#title-heading-entry-"+content_id).text( heading );
                    $("#sidebar-heading-entry-"+content_id).text( heading );
                    $('.file_tab_'+content_id).show();
                    $('#contentAdd').show()
                    if(content_id == $('#next_content_id').val()){
                        $('#list_page_placeholder').append(
                            `<li class="list-group-item page-selection" 
                                data-id="entry-${content_id}"
                                id="sidebar-heading-entry-${content_id}">
                                ${heading}
                            </li>`);
                        $('#next_content_id').val(content_id+1)
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Great!',
                        text: response.message
                    });
                })
                .fail(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error'
                    });
                });
        } else {
            //alert that a page can not be updated without a heading
            Swal.fire({
                icon: 'warning',
                title: 'Error',
                text: 'You need to have something in the Content Heading to process it.'
            });
        };
    });

    //delete page content
    $(document).on('click','.content-delete', function(e)
    {
        swal.fire({
            title: 'Delete this item?',
            text: 'Are you sure! Once you delete it can never be recovered!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => 
        {
            if(result.value)
            {
                const link_id = $("#link_id").val()
                const content_id = $(this).data('id');

                //send to the server
                $.ajax({
                    url: "/ajax/pages/pagecontentdelete",
                    type: 'POST',
                    data: {
                        'link_id' :	link_id,
                        'content_id' : content_id
                    },
                    cache: false,
                    timeout: 10000
                })
                    .done(function(msg) {
                        $('#entry-'+content_id).remove();
                        $('#sidebar-heading-entry-'+content_id).remove();
                        $('#entry-core').show();
                        $('#contentAdd').show()
                    })
                    .fail(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'ERROR'
                        });
                    });
            }
        });
    });

    //show contact form
    //submit the content information
    $(document).on('change','.content-type-entry', function(e)
    {
        const target = $(this).data('id')
        //set up var
        const type = $(this).val();

        if(type == 'contact_form')
        {
            $('#contact-form-'+target).show();
        } else {
            $('#contact-form-'+target).hide();
        }

        const select = $("select#file_type_"+target);

        if(type == 'banner' || type == 'banner_top' || type == 'gallery' || type == 'slideshow')
        {
            $('#image_position_area_'+target).hide();
            select.val('image');
            $(select).find('option[value="file"]').prop('disabled',true);
            select.prop("disabled", true);
        }else{
            $('#image_position_area_'+target).show();               
            $(select).find('option[value="file"]').prop('disabled',false);
            select.prop("disabled", false);
        }
    });
		
});
