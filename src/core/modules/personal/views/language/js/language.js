$(document).ready(function()
{
	$('#language').materialSelect();
	$('#experience').materialSelect();
	
	//add a row
	$('#add_lang').click(function() {

		const language = $("#language option:selected");
		const language_value = language.val();
		const language_title = language.text();

		const level = $("input[name='level']:checked");
		const level_value = level.val();
		const level_title = level.next('span').text();
		
		const experience = $("#experience option:selected");
		const experience_value = experience.val();
		const experience_title = experience.text();

		if($('input[name=level]').is(':checked'))
		{
			// check if already existed
			if ($("span>div>div>div:contains('"+language_title+"')").data('value') == undefined ){
				new_tr = 
				'<span class="row mb-2 mb-sm-0">'+
					'<div class="col-10 text-left">'+
						'<div class="row">'+
							'<div class="col-12 col-sm-4 border" data-value="'+language_value+'">'+language_title+'<input type="hidden" name="language[]" value="'+language_value+'"></div>'+
							'<div class="col-12 col-sm-4 border d-none d-sm-block">'+level_title+'<input type="hidden" name="level[]" value="'+level_value+'"></div>'+
							'<div class="col-12 col-sm-4 border d-none d-sm-block">'+experience_title+'<input type="hidden" name="experience[]" value="'+experience_value+'"></div>'+
						'</div>'+
					'</div>'+
					'<div class="col-2 text-center delete_lang border" title="Delete Language"><i class="far fa-trash-alt"></i></div>'+
				'</span>';
				$('#list_body').append(new_tr);
				
				//reset input
				$("#language").prop('selectedIndex',0);
				$('#select-options-language').prev('input[type=text]').val(($("#language option:selected").text()));
				$("#experience").prop('selectedIndex',0);
				$('#select-options-experience').prev('input[type=text]').val(($("#experience option:selected").text()));
				$("input[name='level']:checked").prop('checked',false);
			}else{
				Swal.fire({
					icon: 'warning',
					text: 'Language already inserted',
				});
			}
		}else{
			Swal.fire({
				icon: 'warning',
				text: 'Please select level proficiency first to continue',
			});
		}
	});
	
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

	//delete a row
	$('#list_body').on('click', '.delete_lang', function(event) {
		
		swal.fire({

            title: 'Delete this language?',
            text: 'Are you sure! Once you submit, this language will be deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'

        }).then((result) => {

            if(result.value)
            {
				event.preventDefault();
				$(this).closest('span').remove();
			}
		});
	});

});