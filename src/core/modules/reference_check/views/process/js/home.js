$(document).ready(function(){
	
	//submit the form
	$("#reference-check").submit(function(e) {

        //check the reference answer
        $.post('/ajax/reference_check/check', $(this).serializeArray())
            .done(function (rs) {
            	var html = '<table class="table text-left">';
            	$.each(rs.questions, (index, question) => {
					html += '<tr><td width="50%">'+question.question+'</td><td style="background:#eee">'+rs.answer[question.question_id]+'</td></tr>'
				});
                html += '</table>';
                Swal.fire({

                    title: 'Warning: Is this data correct?',
                    html: html,
                    icon: 'warning',
                    showCancelButton: true,
                    width: '80%',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, correct!',
                    cancelButtonText: "No, I'll change it!"

                }).then((result) => {
                    if (result.value) {
                        $('#submit_button').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...').addClass('disabled');
                        $("#reference-check").unbind().submit();
                        return true;
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire(
                            'No Problem',
                            'Please correct the information and submit it again :-)',
                            'error'
                        );
                        return false;
                    }
                });
            })
            .fail(function () {
                swal('Connection Failed', 'The check could not be done because we could not talk to the server.', 'error');
                return false;
            });
        return false;
	});
	
	//everytime we load
	if( $('#main_email').val() != '') 
	{
		$('#main_email').change();
	}
	
	if( $( "#country option:selected" ).val() != 'not specified')
	{
		$('#country').change();
	}
		
});