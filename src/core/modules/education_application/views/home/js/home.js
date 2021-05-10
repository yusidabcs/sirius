function loadTableListCourse() {
    $('#content_table').html(
        `<tr>
            <td colspan="6">Loading Data...</td>
        </tr>`
    );
    $.ajax({
        url: "/ajax/education_application/main/getMyCourse",
        type: 'POST',
        cache: true,
        timeout: 10000
    })
        .done(response => {
            let table = '';
            if(response.length==0) {
                table+=`
                <tr>
                    <td colspan="6">
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-info-circle"></i>
                            You haven't join for course. Please select the available course to join one.
                        </div>
                    </td>
                </tr>
                `;
            }

            let no=0;
            $.each(response,function(i,val){
                no++;
                table+=`
                <tr>
                    <td>
                        `+no+`
                    </td>
                    <td>
                        `+val['course_name']+`
                    </td>
                    <td>
                        `+val['created_on']+`
                    </td>
                    <td>
                        `+val['status']+`
                    </td>
                    <td>
                        `+val['last_modified']+`
                    </td>
                    <td>`;

                    if(val['status']=='request') { 
                        table +=`<a id="btn_cancel_status" data-id="`+val['course_request_id']+`" data-toggle="modal" class="white-text btn-sm btn-warning" href="#" title="Cancel Course"><i class="fas fa-window-close"></i> Cancel</a>`;
                    }
                    table +=`</td>
                </tr>
                `;
            });
            $('#content_table').html(table);
        }).fail(response => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something errors. Please contact admin support!'
            });
        });;

    return false;
}

$(document).ready(function(){
    loadTableListCourse();
    $('#content_table').on('click','#btn_cancel_status', function () 
    {
        var id = $(this).data('id');
        swal.fire({
            title: "Cancel Course Request",
			text: 'Are You sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Cancel',
			cancelButtonText: 'Close',
			focusConfirm: false,
            showLoaderOnConfirm: true,
            preConfirm: () => {
					return fetch(`/ajax/education_application/main/cancel-course/`+id,{
						headers: {
							"Content-Type": "application/json"
						  },
						method : 'GET'
					})
					.then(response => {
					    if (!response.status) {
					        Swal.showValidationMessage(`Error could not delete user data.`)
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
                    html: result.value.message,
				})
				if (result.value.status=='ok') {
					loadTableListCourse();
				}
            }
        })
    });

})

