$(document).ready(function()
{
    $('#back_link').click(function(e){
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
    $('#form_schedule_create .mdb-select').materialSelect();

    // var start_on = $('#start_on').dateTimePicker();
    // var finish_on = $('#finish_on').dateTimePicker();

    $('#start_on_time').pickatime();
    $('#finish_on_time').pickatime();

    let min_date_from = moment().format('YYYY-MM-DD');
    var from_input = $('#start_on_date').pickadate({
        format : 'yyyy-mm-dd',
        min : new Date(min_date_from)
    });
    var from_picker = from_input.pickadate('picker');

    let min_date_to = moment().add(1, 'days').format('YYYY-MM-DD');
    var to_input = $('#finish_on_date').pickadate({
        format : 'yyyy-mm-dd',
        min : new Date(min_date_to)
    });
    var to_picker = to_input.pickadate('picker');

    from_picker.on('set', function (event) {
        if (event.select) {
            let selected = from_picker.get('select');
            let min = moment(selected.obj).add(1, 'days').format('YYYY-MM-DD');
            to_picker.set('min', new Date(min));
        } else if ('clear' in event) {
            to_picker.set('min', new Date(min_date_to))
        }
    })
    to_picker.on('set', function (event) {
        if (event.select) {
            let selected = to_picker.get('select');
            let max = moment(selected.obj).subtract(1, 'days').format('YYYY-MM-DD');
            from_picker.set('max', new Date(max));
        } else if ('clear' in event) {
            from_picker.set('max', false)
        }
    })

    $('#countryCode_id').change(function() {
        var countryCode = $(this).val();
        $('#countrySubCode_id').attr('disabled')
        //post off the leaf for data
        $.get('/ajax/address_book/countrysubcodes/'+countryCode)
            .done(function (d) {
                var states = $('#countrySubCode_id').empty();
                if(d)
                {
                    $.each(d, function( code,name) {
                        states.append('<option value="' + code + '">' + name + '</option>');
                    });
                } else {
                    states.append('<option value="0" selected="selected">Not Applicable</option>');
                }
                $('#countrySubCode_id').removeAttr('disabled')
            })
            .fail(function () {
                swal.fire('Connection Failed', 'Update of Country Sub Codes Failed.', 'error');

            });
    });
    
    $('#form_schedule_create').on('submit', function () {
        var btn = $(this).find('button[type=submit]');
        var text = btn.html();
        btn.attr('disabled',true);
        btn.html('Saving..');

        $.ajax({
            url: '/ajax/interview/location/insert',
            data: $(this).serialize(),
            type: 'POST', //send it through get method
            datatype : 'json',
            success: function(rs) {
                Swal.fire({
                    icon: 'success',
                    title: 'Notification!',
                    text: rs.message
                });
                btn.attr('disabled',false);
                btn.html(text);
                window.location.href = $('#back_link').attr('href');
            },
            error: function(response) {

                btn.attr('disabled',false);
                btn.html(text);
                if(response.status == 400)
                {
                    text = ''
                    $.each(response.responseJSON.errors, (index,item) => {
                        text += item + '<br>';
                    })
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: text
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something errors. Please contact admin support!'
                    });
                }
            }
        });

        return false;
    });
});
