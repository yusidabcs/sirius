//these need to be defined immediately

//BS file-input templates
var footerTemplate = 
'<div class="file-thumbnail-footer">\n' +
'   <div style="margin:5px 0">\n' +
'       <input type="text" class="form-control input-sm file-id" value="{caption}" disabled>\n' +
'       <input type="text" hidden class="file-title-original" value="{TAG_TITLE}" ">\n' +
'       <input type="text" class="form-control input-sm file-title-new" value="{TAG_TITLE}" placeholder="Enter title ...">\n' +
'       <textarea hidden class="file-description-original" rows="3" ">{TAG_DESCRIPTION}</textarea>\n' +
'       <textarea class="form-control iow-fileinput-textarea file-description-new" rows="3" placeholder="Enter description...">{TAG_DESCRIPTION}</textarea>\n' +
'       <input type="checkbox" hidden class="file-status-original" value="1" {TAG_CHECKED}>\n' +
'       <div class="form-check"><input type="checkbox" class="form-check-input file-status-new" id="{TAG_VIS_ID}" value="1" {TAG_CHECKED}><label class="form-check-label" for="{TAG_VIS_ID}">Visible</label></div>\n' +
'       </div>\n' +
'   </div>\n' +
'   {actions}\n' +
'</div>';

var actionsTemplate =
'<div class="file-actions">\n' +
'    <div class="file-footer-buttons">\n' +
'        <span class="ajax-indicator-off"></span>{other}{delete}' +
'    </div>\n' +
'    {indicator}\n' +
'    <div class="clearfix"></div>\n' +
'</div>';

var otherActionButtonsTemplate = 
'<button type="button" ' +
'    class="kv-file-edit btn btn-sm btn-default btn-outline-secondary iow-update-button" ' +
'    title="Update" data-url="{caption}" style="display: none">\n' +
'    <i class="fas fa-edit"></i>\n' +
'</button>\n';

function makeExtraData(el)
{
	var fiv = $(el),
		out,
		stat_old, 
		stat_new;

    if (fiv.find(".file-status-original").prop( "checked" )) {
	    stat_old = 1;
	} else {
	    stat_old = 0;
	}
	
	if (fiv.find(".file-status-new").prop( "checked" )) {
	    stat_new = 1;
	} else {
	    stat_new = 0;
	}
    
    var info_obj = 
    {
        "insert": fiv.hasClass("file-preview-initial") ? "NO" : "YES",
        "id": fiv.find(".file-id").val(),
        "title_old": fiv.find(".file-title-original").val(),
        "title_new": fiv.find(".file-title-new").val(),
        "description_old" : fiv.find(".file-description-original").val(),
        "description_new" : fiv.find(".file-description-new").val(),
        "status_old" : stat_old,
        "status_new" : stat_new
    }
    
    if( info_obj.insert == "YES" || info_obj.title_old != info_obj.title_new || info_obj.description_old != info_obj.description_new || info_obj.status_old != info_obj.status_new )
    {
	    out = JSON.stringify(info_obj);
	} else {
		out = '';
	}
	return out;
};

function runPageImages(link_id,initialPreview_image,initialPreviewConfig_image,initialPreviewThumbTags_image)
{
	var initialPreview_d,
		initialPreviewConfig_d,
		initialPreviewThumbTags_d;
	
	//load the image bootstrap 4 and Font Awesome 5 file input - for page images
	$('#image-page').fileinput({
		showClose: false,
		theme: "fas",
	    uploadUrl: '/ajax/pages/fileinput/image/'+link_id+'/page',
		uploadAsync: false,
		multiple: true,
		maxFileCount: 5,
		overwriteInitial: false,
		showUpload: true,
		otherActionButtons: otherActionButtonsTemplate,
		layoutTemplates: {
			footer: footerTemplate,
			actions: actionsTemplate
		},
	    previewThumbTags: {
	        '{TAG_TITLE}': '',        	// no value
	        '{TAG_DESCRIPTION}': '',   	// no value
	        '{TAG_STATUS}': '',   		// no value
	        '{TAG_VIS_ID}': function() {
                    var text = "";
					var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
					for (var i = 0; i < 10; i++)
					{
						text += possible.charAt(Math.floor(Math.random() * possible.length));
					}
					return text;
                }        				// random value for visibility checkbox 
	    },
	    initialPreview: initialPreview_image,
	    initialPreviewConfig: initialPreviewConfig_image,
	    initialPreviewThumbTags: initialPreviewThumbTags_image,
		uploadExtraData: function()
	    {
		    var out = [], itemData, i = 0;
		     
		    //iterate through all the files on the system
			$("#" + this.previewInitId + '-0').parents(".tab-pane").find(".kv-preview-thumb").each(function()
	        {
		        itemData = makeExtraData(this);
			    if(itemData.length > 0)
			    {
				    out[i] = itemData;
				    i++;
				}
			});
			
			return out;
	    }
	});
};

function runPageFiles(link_id,initialPreview_file,initialPreviewConfig_file,initialPreviewThumbTags_file)
{
	//load the image bootstrap 4 and Font Awesome 5 file input - for page files 
	$('#file-page').fileinput({
		showClose: false,
		theme: "fas",
	    uploadUrl: '/ajax/pages/fileinput/file/'+link_id+'/page',
	    uploadAsync: false,
	    multiple: true,
	    maxFileCount: 5,
		overwriteInitial: false,
		showUpload: true,
		otherActionButtons: otherActionButtonsTemplate,
		layoutTemplates: {
			footer: footerTemplate,
			actions: actionsTemplate
		},
	    previewThumbTags: {
	        '{TAG_TITLE}': '',        	// no value
	        '{TAG_DESCRIPTION}': '',   	// no value
	        '{TAG_STATUS}': ''   		// no value
	    },
	    initialPreview: initialPreview_file,
	    initialPreviewConfig: initialPreviewConfig_file,
	    initialPreviewThumbTags: initialPreviewThumbTags_file,
	    uploadExtraData: function() 
	    {
		    var out = [], itemData, i = 0;
		     
		    //iterate through all the files on the system
			$("#" + this.previewInitId + '-0').parents(".tab-pane").find(".kv-preview-thumb").each(function()
	        {
		        itemData = makeExtraData(this);
			    if(itemData.length > 0)
			    {
				    out[i] = itemData;
				    i++;
				}
			});
			return out;
	    }
	});	
};

function runPageShowUpload()
{
	$(".iow-update-button").show();
		
	$(".iow-update-button").on('click', function(e)
	{
		//need to stop it from firing twice
		e.stopImmediatePropagation();
		
		var out = [], el, itemData, link_id = $("#link_id").val();
		
		el = $(this).parents(".file-preview-initial");
				
		itemData = makeExtraData( el[0] );
		    
	    if(itemData.length > 0)
		{
			//send to the server
			$.ajax({
				url: "/ajax/pages/fileupdate",
				type: 'POST',
				data: {
						'link_id':	link_id,
						'update' :	itemData
				},
				cache: false,
				timeout: 10000
			})
			.done(function(msg) {
				alert('Updated');
			})
			.fail(function() {
				alert('FAIL!');
			});
		}
		
	});
};

function runContentImages(link_id,number,initialPreview_image,initialPreviewConfig_image,initialPreviewThumbTags_image)
{
	var initialPreview_d,
		initialPreviewConfig_d,
		initialPreviewThumbTags_d;
	
	//load the image bootstrap 4 and Font Awesome 5 file input - for content images
	$('#image-entry-'+number).fileinput({
		showClose: false,
		theme: "fas",
	    uploadUrl: '/ajax/pages/fileinput/image/'+link_id+'/'+number,
		uploadAsync: false,
		multiple: true,
		maxFileCount: 5,
		overwriteInitial: false,
		showUpload: true,
		otherActionButtons: otherActionButtonsTemplate,
		layoutTemplates: {
			footer: footerTemplate,
			actions: actionsTemplate
		},
	    previewThumbTags: {
	        '{TAG_TITLE}': '',        	// no value
	        '{TAG_DESCRIPTION}': '',   	// no value
	        '{TAG_STATUS}': ''   		// no value
	    },
	    initialPreview: initialPreview_image,
	    initialPreviewConfig: initialPreviewConfig_image,
	    initialPreviewThumbTags: initialPreviewThumbTags_image,
		uploadExtraData: function()
	    {
		    var out = [], itemData, i = 0;
		    
		    //!FUCKING AROUND HERE
		     
		    //iterate through all the files on the system
			$("#" + this.previewInitId + '-0').parents(".tab-pane").find(".kv-preview-thumb").each(function()
	        {
		        console.log(this);
		        
		        itemData = makeExtraData(this);
		        
			    if(itemData.length > 0)
			    {
				    out[i] = itemData;
				    i++;
				}
			});
			return out;
	    }
	});
};

function runContentFiles(link_id,number,initialPreview_file,initialPreviewConfig_file,initialPreviewThumbTags_file)
{
	//load the image bootstrap 4 and Font Awesome 5 file input - for content files
	$('#file-entry-'+number).fileinput({
		showClose: false,
		theme: "fas",
	    uploadUrl: '/ajax/pages/fileinput/file/'+link_id+'/'+number,
	    uploadAsync: false,
	    multiple: true,
	    maxFileCount: 5,
		overwriteInitial: false,
		showUpload: true,
		otherActionButtons: otherActionButtonsTemplate,
		layoutTemplates: {
			footer: footerTemplate,
			actions: actionsTemplate
		},
	    previewThumbTags: {
	        '{TAG_TITLE}': '',        	// no value
	        '{TAG_DESCRIPTION}': '',   	// no value
	        '{TAG_STATUS}': ''   		// no value
	    },
	    initialPreview: initialPreview_file,
	    initialPreviewConfig: initialPreviewConfig_file,
	    initialPreviewThumbTags: initialPreviewThumbTags_file,
	    uploadExtraData: function() 
	    {
		    var out = [], itemData, i = 0;
		     
		    //iterate through all the files on the system
			$("#" + this.previewInitId + '-0').parents(".tab-pane").find(".kv-preview-thumb").each(function()
	        {
		        itemData = makeExtraData(this);
			    if(itemData.length > 0)
			    {
				    out[i] = itemData;
				    i++;
				}
			});
			return out;
	    }
	});	
};

function runContentShowUpload()
{
	$(".iow-update-button").show();
		
	$(".iow-update-button").on('click', function(e)
	{
		//need to stop it from firing twice
		e.stopImmediatePropagation();
		
		var out = [], el, itemData, link_id = $("#link_id").val();
		
		el = $(this).parents(".file-preview-initial");
				
		itemData = makeExtraData( el[0] );
		    
	    if(itemData.length > 0)
		{
			//send to the server
			$.ajax({
				url: "/ajax/pages/fileupdate",
				type: 'POST',
				data: {
						'link_id':	link_id,
						'update' :	itemData
				},
				cache: false,
				timeout: 10000
			})
			.done(function(msg) {
				alert('Updated');
			})
			.fail(function() {
				alert('FAIL!');
			});
		}
		
	});
};

function runContentTypeSelect(number)
{
	//submit the content information
	$("#content-type-entry-"+number).on('change', function(e)
	{
		//set up var
		var type = $(this).val();
		
		if(type == 'contact_form')
		{
			$('#contact-form-'+number).show();
		} else {
			$('#contact-form-'+number).hide();
		}
	});
};

function runContentSubmit(number)
{
	//submit the content information
	$("#content-submit-"+number).on('click', function(e)
	{
		//set up var
		var tnum = $(this).attr('id').replace('content-submit-','');
		
		//stop the submit process completely
		e.preventDefault();
		
		//setup variables
		var	link_id = $("#link_id").val(),
			content_id = tnum,
			content_type = $("#content-type-entry-"+tnum).val(),
			show_heading = $("#show_heading-entry-"+tnum).is(":checked") ? '1' : '0',
			heading = $("#heading-entry-"+tnum).val(),
			sdesc = $("#sdesc-entry-"+tnum).val(),
			text = $("#content-entry-"+tnum).val(),
			sequence = getEntryIndexArray();
		
		//extra for contact_form
		if(content_type == 'contact_form' )
		{
			var to_name = $("#contact-to-name-"+tnum).val(),
				to_email = $("#contact-to-email-"+tnum).val(),
				to_subject = $("#contact-to-subject-"+tnum).val(),
				submitted_heading = $("#contact-submitted-heading-"+tnum).val(),
				submitted_sdesc = $("#contact-submitted-sdesc-"+tnum).val(),
				submitted_content = $("#contact-submitted-entry-"+tnum).val()
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
						'content': text,
						'to_name': to_name,
						'to_email': to_email,
						'to_subject': to_subject,
						'submitted_heading': submitted_heading,
						'submitted_sdesc': submitted_sdesc,
						'submitted_content': submitted_content,
						'sequence': sequence
				},
				cache: false,
				timeout: 10000
			})
			.done(function(msg) {
				//setup the content heading
				if( $("#heading-entry-"+tnum).val().length > 0 )
				{
					$("#title_heading-entry-"+tnum).text( $("#heading-entry-"+tnum).val() );
					$('#files-entry-'+tnum).show();
					$("#addContent").show();
				}
				alert('Updated');
			})
			.fail(function() {
				alert('FAIL!');
			});
		} else {
			//alert that a page can not be updated without a heading
			alert("You need to have something in the Content Heading to process it.");
		}; 
	});
};

function runContentDelete(number)
{
	//submit the content information
	$("#content-delete-"+number).on('click', function(e)
	{
		//set up var
		var tnum = $(this).attr('id').replace('content-delete-','');
		
		//stop the submit process completely
		e.preventDefault();
		
		//setup variables
		var	link_id = $("#link_id").val(),
			content_id = tnum;
	
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
			$('#entry-'+tnum).next(".ui-sortable-handle").remove();
			$('#entry-'+tnum).remove();
		})
		.fail(function() {
			alert('FAIL!');
		});
	});
};

function getEntryIndexArray()
{
	var sequence = Array();
	    
    $('.entrylist > li').each(function( index ) {
	    
	  if(index != 0)
	  {
	  	sequence[index] = this.id;
	  }
	  
	});
	
	return sequence;
}


//make sure we wait till after the document is ready
$(document).ready(function(){
	
	//select
	$('.content-type-entry').material_select();
	
	//set the link id
	var	link_id = $("#link_id").val()
	
	//attach Summer Note to Text Areas
	$('#page_text').summernote();
	
	//decide if we can show the add content button
	if( $("#page_heading").val().length > 0 )
	{
		$("#title_page-heading").text( $("#page_heading").val() );
		$("#addContent").show();
	}
	
	//submit the page information
	$("#submit-page-info").on('click', function(e)
	{
		//stop the submit process completely
		e.preventDefault();
		
		//setup variables
		var	link_id = $("#link_id").val(),
			show_heading = $("#show_heading").is(":checked") ? '1' : '0',
			page_heading = $("#page_heading").val(),
			page_sdesc = $("#page_sdesc").val(),
			page_keywords = $("#page_keywords").val(),
			page_text = $('#page_text').val(),
			show_anchors =	$("#show_anchors").is(":checked") ? '1' : '0';

		if( page_heading.length > 0)
		{
			//send to the server
			$.ajax({
				url: "/ajax/pages/pageinfo",
				type: 'POST',
				data: {
						'link_id' :	link_id,
						'show_heading' : show_heading,
						'page_heading': page_heading,
						'page_sdesc': page_sdesc,
						'page_keywords': page_keywords,
						'page_text': page_text,
						'show_anchors':	show_anchors
				},
				cache: false,
				timeout: 10000
			})
			.done(function(msg) {
				alert('Updated');
				$("#addContent").show();
			})
			.fail(function() {
				alert('FAIL!');
			});
		} else {
			//alert that a page can not be updated without a heading
			alert("You need to have something in the Page Heading to process it.");
		}; 
		
	});
	
	//the number for the next content item
	var num = $("#next_content_id").val();
	
	//produce a new content section as required
    $("#contentAdd").click(function(){
	    
	    //clone and change the text inputs to the correct number
		$("#entry-0").clone().attr('id', 'entry-'+num).appendTo(".entrylist");
		
		//remove hidden
		$('#entry-'+num).removeAttr( "class" );
		
		//fix up collapse
		$('#entry-'+num +' #button-collapse-entry-0').attr({
			'id': 'button-collapse-entry-'+num,
			'data-target': '#collapse-entry-'+num,
			'ariacontrols': 'collapse-entry-'+num,
		});
				
		$('#entry-'+num +' #collapse-entry-0').attr('id','collapse-entry-'+num);
		
		//title
		$('#entry-'+num +' #title_heading-entry-0').attr('id','title_heading-entry-'+num);
		
		//make the contentBox a block
		$('.entrylist #entry-'+num +' .contentBox').css("display", "block");
		
		//update the form id
		$('#entry-'+num +' #form-entry-0').attr('id','form-entry-'+num);
		
		//update the hidden field to story this content id
		$('#entry-'+num +' input[name=content_name]').attr('value','entry-'+num);
		
		//update id and name for content type
		$('#entry-'+num +' #content-type-entry-0').attr('id','content-type-entry-'+num);
			
		//update id and name for show heading entry
		$('#entry-'+num +' #show_heading-entry-0').attr('id','show_heading-entry-'+num);
				
		//update id and name for heading entry
		$('#entry-'+num +' #heading-entry-0').attr('id','heading-entry-'+num);
				
		//update id and name for sdesc entry
		$('#entry-'+num +' #sdesc-entry-0').attr('id','sdesc-entry-'+num);
				
		//update id and name for heading entry
		$('#entry-'+num +' #content-entry-0').attr({
			'id': 'content-entry-'+num,
			'class': 'form-control content-text-entry-'+num,
		});
		
		//--- Contact Form special entries
		
		//update contact form id
		$('#entry-'+num +' #contact-form-0').attr('id','contact-form-'+num);
		
		//update id and name for to name 
		$('#entry-'+num +' #contact-to-name-0').attr('id','contact-to-name-'+num);
		
		//update id and name for to email
		$('#entry-'+num +' #contact-to-email-0').attr('id','contact-to-email-'+num);
		
		//update id and name for subject
		$('#entry-'+num +' #contact-to-subject-0').attr('id','contact-to-subject-'+num);
		
		//update id and name for submitted heading
		$('#entry-'+num +' #contact-submitted-heading-0').attr('id','contact-submitted-heading-'+num);
				
		//update id and name for submitted sdesc
		$('#entry-'+num +' #contact-submitted-sdesc-0').attr('id','contact-submitted-sdesc-'+num);
				
		//update id and name for submitted entry
		$('#entry-'+num +' #contact-submitted-entry-0').attr({
			'id': 'contact-submitted-entry-'+num,
			'class': 'form-control content-text-entry-'+num,
		});
		
		//--- End Contact Form special entries
						 
		//fix up the content submit button
		$('#entry-'+num +' #content-submit-0').attr('id','content-submit-'+num);
		
		//fix up the content delete button
		$('#entry-'+num +' #content-delete-0').attr('id','content-delete-'+num);
		
		//update the files area id
		$('#entry-'+num +' #files-entry-0').attr('id','files-entry-'+num);
		
		//update the tab panel id
		$('#entry-'+num +' #tabpanel-entry-0').attr('id','tabpanel-entry-'+num);
		
		//update the images and entry division to the new id
		$('#entry-'+num +' #tab-images-entry-0').attr('id','tab-images-entry-'+num);
		$('#entry-'+num +' #tab-files-entry-0').attr('id','tab-files-entry-'+num);
		
		//update the referrence to the content division for images and files
		$('#entry-'+num +' #tab-images-link-0').attr({
			id: 'tab-images-link-'+num,
			href: '#tab-images-entry-'+num
		});
		
		$('#entry-'+num +' #tab-files-link-0').attr({
			id: 'tab-files-link-'+num,
			href: '#tab-files-entry-'+num
		});
		
		//update the id's for the data entry itself so file input works
		$('#entry-'+num +' #image-entry-0').attr({
		  id: 'image-entry-'+num,
		  name: 'image-entry-'+num+'[]'
		});
		
		$('#entry-'+num +' #file-entry-0').attr({
		  id: 'file-entry-'+num,
		  name: 'file-entry-'+num+'[]'
		});

		//always make sure the first one is showing
		$('#tabpanel-entry-'+num +' a:first').tab('show');
				
		//stop them from adding more until they finish adding this one
		$("#addContent").hide();
		
		alert('Apply '+num);
		
		//select
		$('#content-type-entry-'+num).material_select();
		
		//apply summer note to the textarea
		$('#content-entry-'+num).summernote();
		
		//run the various functions to make it all work	
		runContentImages(link_id,num,'','','');
		runContentFiles(link_id,num,'','','');
		runContentTypeSelect(num);
		runContentSubmit(num);
		runContentDelete(num);
		
		//number plass
		num++;

	});
	
	//Change the Font Awsome Indicator
	$('button[data-toggle="collapse"]').click(function() {
		  $(this)
        .find('[data-fa-i2svg]')
        .toggleClass('fa-minus-square')
        .toggleClass('fa-plus-square');
	}); 
	
});
