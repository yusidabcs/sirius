$(document).ready(function(){
	
	//select
	$('.mdb-select').materialSelect();
	
	//confirm for delete
	$(".confirm").click( function(e){
		
		e.preventDefault();
		
		var url = this.href;
		//console.log(url);
		
		var pageId = $('#ajax_check_title_menu').val();
		
		swal.fire({
			title: "Confirmation required",
			text: "Are you sure you want to delete the menu item '"+pageId+"'?",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!',
			cancelButtonText: 'No, cancel!'
			}).then((result) => {
				
				if(result.value)
				{
					window.location.href = url;
				};
				
				/*
				if(result.dismiss)
				{
					console.log('cancelled');
				};
				*/
			});		
	});
	
	//toggle on an off if the redirect input can be seen
	$('input[name=redirect_url]').blur(function() {
	    if( !$(this).val() ) {
		    $('.urlToggle').show();
	    } else {
		    $('.urlToggle').hide();
	    }
	});
	
	//change functions
	$('#ajax_check_title_menu').focusout(function(){
		
		$('#ajax_check_title_menu').next('span').text('');
		
		var title_new = $('#ajax_check_title_menu').val();
		var title_orig =  $('#title_menu_orig').val();
		
		if( title_new.length === 0 )
		{
			$('#ajax_check_title_menu').val(title_orig);
			
		} else if( title_new != title_orig ) {
			
			$.ajax({
				url: "/ajax/menu/main/testTitle",
				type: 'POST',
				data: {
					title_new: title_new
				},
				cache: false,
				timeout: 10000
			})
			.done(function(msg) {

				var answer = jQuery.parseJSON(msg);
				
				if(answer.good)
				{
					$('#ajax_check_title_menu').val(answer.menu);
					$('#ajax_check_title_menu').next('span').removeClass().addClass('text-success');
					$('#ajax_check_title_menu').next('span').text(answer.note);
				} else {
					$('#ajax_check_title_menu').next('span').removeClass().addClass('text-danger');
					$('#ajax_check_title_menu').next('span').text(answer.note);
				}
			})
			.fail(function() {
				alert( "Error could not check the title value" );
			});
		} else {
			$('#ajax_check_title_menu').val(title_orig);
			$('#ajax_check_title_menu').next().text('Reset to Original');
		}
	});

	//different because the link has to change if this changes!
	$('#ajax_check_title_page').focusout(function(){
		
		$('#ajax_check_title_page').next('span').text('');
		
		var page_new = $('#ajax_check_title_page').val();
		var page_orig =  $('#title_page_orig').val();
		
		var link = $('#ajax_check_link_id');
		var link_orig = $('#link_id_orig').val();
		
		if( page_new.length === 0 )
		{
			$('#ajax_check_title_page').val(page_orig);
			
		} else if( page_new != page_orig ) {
			
			$.ajax({
				url: "/ajax/menu/main/testPage",
				type: 'POST',
				data: {
					page_new: page_new
				},
				cache: false,
				timeout: 10000
			})
			.done(function(msg) {

				var answer = jQuery.parseJSON(msg);
				
				if(answer.good)
				{
					$('#ajax_check_title_page').val(answer.menu);
					link.val(answer.link);
					$('#ajax_check_title_page').next('span').removeClass().addClass('text-success');
					$('#ajax_check_title_page').next('span').text(answer.note);
				} else {
					link.val('');
					$('#ajax_check_title_page').next('span').removeClass().addClass('text-danger');
					$('#ajax_check_title_page').next('span').text(answer.note);
				}
				
			})
			.fail(function() {
				alert( "Error could not check the page value" );
			});
		} else {
			$('#ajax_check_title_page').val(page_orig);
			link.val(link_orig);
			$('#ajax_check_title_page').next().text('');
		}
	});
	
});