$('#req_verification').click(function(){
    let btn = $(this);
    let html_text_area = `
    <div class="text-center mb-2">Are you sure? Please make sure the data is corrected before request again</div>
    <label for="info_request"
    class="float-left">Message</label>
    <textarea id="info_request" name="info_request" maxlength="255" class="form-control" placeholder="Enter message..." required></textarea>
    <span id="charactersRemaining"></span>
    `;
        swal.fire({
            title: 'Request Verification Again?',
            html: html_text_area,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Request Verification!'

        }).then((result) => {
            if(result.value)
            {
                btn.prop('disabled',true);
                $('#modal_loading').modal('show');
                let info_request = $('#info_request').val();
				info_request = info_request==''?'Requested by user':info_request;
                $.post('/ajax/personal/main/requestVerification',{ info_request: info_request})
                    .done(function (d) {
                        if(d.success)
                        {
                            $('#modal_loading').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                text: d.message,
                            }).then((result) => {
                                location.reload(true);
                            });
                            
                        } else {
                            $('#modal_loading').modal('hide');
                            swal.fire('Failed', 'There was an error.  The server did not send back a success but it sent back: '+d);
                        }

                    })
                    .fail(function () {
                        $('#modal_loading').modal('hide');
                        swal.fire('Connection Failed', 'There was a connection error.  The internet may be down or there might be an issue with the server.', 'error');
                    });
            }
        });

});