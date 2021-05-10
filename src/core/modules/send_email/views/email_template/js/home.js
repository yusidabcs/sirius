$(document).ready(function()
{
    $('.select2').select2({
        width: '100%',
		minimumResultsForSearch: Infinity
    });
	$('.select2-with-search').select2({
        width: '100%'
    });

    const table = $('#list_template').DataTable({
        "processing": true,
        "serverSide": true,
        'responsive': true,
        "ajax": {
            "url": "/ajax/send_email/template/list",
            "type": "POST",
            cache: false,
            "data": function(d) {
                d.type = $('#type_filter').val()
            }
        },
        "columns": [
            {"data": 'name'},
            {"data": 'title'},
            {"data": 'subject'},
            {"data": 'type'},
            {"data": null}
        ],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return row.name;
                },
                "targets": 0
            },


            {
                "render": function (data, type, row) {

                    var html = `<a data-template="${row.template_id}" class="text-success edit-template" href="#" data-placement="bottom" title="Edit Template"><i class="fa fa-edit"></i></a>
                    <a data-template="${row.name}" class="text-success btn-preview" href="#" data-placement="bottom" title="Preview Template"><i class="fa fa-eye"></i></a>
                    <a data-template="${row.name}" class="text-primary send-email" href="#" data-placement="bottom" title="Test Send Email"><i class="fa fa-paper-plane"></i></a>
                    <a data-template="${row.template_id}" class="text-danger delete-template" href="#" data-placement="bottom" title="Delete Template"><i class="fa fa-trash"></i></a>`;


                    return html;
                },
                "targets": -1
            }
        ],
    });

    // $('#template_type').materialSelect();
    // $('#template_type_edit').materialSelect();
    // $('#type_filter').materialSelect();
    
    // $('#header_template_edit').materialSelect();
    // $('#footer_template_edit').materialSelect();

    $('#type_filter').on('change', function() {
        table.ajax.reload();
    });

    $(document).on('click', '.btn-preview', function(e) {
        e.preventDefault();

        var template_type = 'content';
        var template_name = $(this).data('template');

        if (template_name === 'header') {
            template_type = 'header';
        } else if(template_name === 'footer') {
            template_type = 'footer';
        } else if(template_name === 'master') {
            template_type = 'master';
        }

        $.ajax({
            url: '/ajax/send_email/template/preview/' + template_name,
            method: 'POST',
            data: {
                'type': template_type
            },
            success: function(response) {
                $('#iframe_id').attr("srcdoc", response.content);
                $('#preview_modal').modal('show');
            }
        });
        
    });

    $(document).on('click', '.edit-template', function(e) {
        e.preventDefault();

        var modal = $('#modalEditTemplate');

        let header_template = modal.find('select[name="header_template"]');
        let footer_template = modal.find('select[name="footer_template"');
        let main_template = modal.find('select[name="main_template"');

        let btn = $(this);

        $.ajax({
            url: '/ajax/send_email/template/get-template-parts',
            method: 'GET',
            success: function(response) {
                let html = `<option value="">None</option>`;
                
                response.content.forEach((item) => {
                    let nameDisplay = item.name.replace('_', ' ').split(' ').map((word) => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');

                    html += `<option value="${item.name}">${nameDisplay}</option>`;
                });

                header_template.html(html);
                footer_template.html(html);
                main_template.html(html);
                modal.modal('show');

                $.ajax({
                    url: '/ajax/send_email/template/edit/' + btn.data('template'),
                    method: 'POST',
                    success: function(response) {
        
                        modal.find('input[name="template_id"]').val(response.template_id);
                        modal.find('input[name="name"]').val(response.name);
                        modal.find('input[name="subject"]').val(response.subject);
                        modal.find('input[name="title"]').val(response.title);
                        modal.find('select[name="template_type"]').val(response.type).trigger('change');
                        modal.find('select[name="header_template"]').val(response.header_template).trigger('change');
                        modal.find('select[name="footer_template"]').val(response.footer_template).trigger('change');
                        modal.find('select[name="main_template"]').val(response.main_template).trigger('change');
                        /*$('#template_type_edit').materialSelect({
                            destroy: true
                        });
                        $('#template_type_edit').materialSelect();*/
                        modal.find('textarea[name="content"]').val(response.content);
                        if (response.type !== 'template_part') {
                            $('#header_template_edit').parent().parent().parent().removeClass('d-none');
                        } else {
                            $('#header_template_edit').parent().parent().parent().addClass('d-none');
                        }

                        /*header_template.materialSelect();
                        footer_template.materialSelect();
                        main_template.materialSelect();*/
                    }
                });
            }
        });
    })

    $('#createTemplate').on('click', function(e) {
        e.preventDefault();

        var btn = $(this);

        btn.addClass('disabled');
        btn.html('Creating....');

        $.ajax({
            url: '/ajax/send_email/template/add',
            method: 'POST',
            data: {
                'name': $('input[name="name"]').val(),
                'subject': $('input[name="subject"]').val(),
                'title': $('input[name="title"]').val(),
                'type': $('select[name="template_type"]').val(),
                'submitted_text': $('textarea[name="content"]').val(),
                'footer_template': $('select[name="footer_template"]').val(),
                'header_template': $('select[name="header_template"]').val(),
                'main_template': $('select[name="main_template"]').val()
            },
            success: function(response) {
                Swal.fire('Information', response.message);

                btn.removeClass('disabled');
                btn.html('Create');

                $('#modalCreateTemplate').modal('hide');
                $('input[name="name"]').val('');
                $('input[name="title"]').val('');
                $('input[name="subject"]').val('');
                $('select[name="template_type"]').val('').trigger('change');
                $('textarea[name="content"]').val('');

                /*$('#template_type').materialSelect({
                    destroy: true
                });
                $('#template_type').materialSelect();

                $('#footer_template').materialSelect({
                    destroy: true
                });
                $('#footer_template').materialSelect();

                $('#header_template').materialSelect({
                    destroy: true
                });
                $('#header_template').materialSelect();
                $('#main_template').materialSelect({
                    destroy: true
                });
                $('#main_template').materialSelect();*/
                table.ajax.reload();
            },
            error: function() {
                btn.removeClass('disabled');
                btn.html('Create');

                table.ajax.reload();

                Swal.fire('Error', 'Something went wrong!', 'error');
            }
        });
    });

    $('#updateTemplate').on('click', function(e) {
        e.preventDefault();

        var btn = $(this);
        var modal = $('#modalEditTemplate');

        btn.addClass('disabled');
        btn.html('Saving....');

        $.ajax({
            url: '/ajax/send_email/template/update/' + modal.find('input[name="template_id"]').val(),
            method: 'POST',
            data: {
                'name': modal.find('input[name="name"]').val(),
                'subject': modal.find('input[name="subject"]').val(),
                'title': modal.find('input[name="title"]').val(),
                'type': modal.find('select[name="template_type"]').val(),
                'submitted_text': modal.find('textarea[name="content"]').val(),
                'header_template': modal.find('select[name="header_template"]').val(),
                'footer_template': modal.find('select[name="footer_template"]').val(),
                'main_template': modal.find('select[name="main_template"]').val()
            },
            success: function(response) {
                Swal.fire('Information', response.message);

                btn.removeClass('disabled');
                btn.html('Save');

                modal.modal('hide');
                modal.find('input[name="name"]').val('');
                modal.find('input[name="subject"]').val('');
                modal.find('select[name="template_type"]').val('').trigger('change');
                modal.find('textarea[name="content"]').val('');
                modal.find('select[name="main_template"]').val('').trigger('change');
                modal.find('select[name="header_template"]').val('').trigger('change');
                modal.find('select[name="footer_template"]').val('').trigger('change');
                table.ajax.reload();
            },
            error: function() {
                btn.removeClass('disabled');
                btn.html('Save');

                table.ajax.reload();

                Swal.fire('Error', 'Something went wrong!', 'error');
            }
        });
    });

    $(document).on('click', '.delete-template', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "After template deleted, it cannot be restored ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) =>  {
            if (result.value) {
                $.ajax({
                    method: 'POST',
                    url: '/ajax/send_email/template/delete',
                    data: {
                        template_id: $(this).data('template')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Information',
                                text: response.message,
                                icon: 'info'
                            });
                        }
                        table.ajax.reload();
                    },
                    error: function(error) {
                        Swal.fire({
                            title: 'Information',
                            text: 'Something went wrong, please contact admin support',
                            icon: 'error'
                        });
                    }
                })
            }
        })
    });

    $('#modalCreateTemplate').on('show.bs.modal', function() {
        let header_template = $(this).find('select[name="header_template"]');
        let footer_template = $(this).find('select[name="footer_template"');
        let main_template = $(this).find('select[name="main_template"');
        
        $.ajax({
            url: '/ajax/send_email/template/get-template-parts',
            method: 'GET',
            success: function(response) {
                let html = `<option value="">None</option>`;
                
                response.content.forEach((item) => {
                    let nameDisplay = item.name.replace('_', ' ').split(' ').map((word) => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');

                    html += `<option value="${item.name}">${nameDisplay}</option>`;
                });

                header_template.html(html);
                footer_template.html(html);
                main_template.html(html);

                /*header_template.materialSelect();
                footer_template.materialSelect();
                main_template.materialSelect();*/
            }
        });
    });

    $('#template_type, #template_type_edit').on('change', function() {
        if ($(this).val() !== 'template_part') {
            $(this).parent().parent().next().removeClass('d-none');
        } else {
            $(this).parent().parent().next().addClass('d-none');
        }
    });

    $(document).on('click', '.send-email', function(e) {
        e.preventDefault();

        var modal = $('#modalSendEmail');
        modal.modal('show');

        modal.find('input[name="template_name"]').val($(this).data('template'))
    })

    $('#sendEmailTemplate').on('click', function(e) {
        var self = $(this);
        var modal = $('#modalSendEmail');

        self.text('Sending....');
        self.addClass('disabled');

        $.ajax({
            url: '/ajax/send_email/template/send',
            method: 'POST',
            data: {
                from_email: modal.find('input[name="from_email"]').val(),
                to_email: modal.find('input[name="to_email"]').val(),
                template_name: modal.find('input[name="template_name"]').val()
            },
            success: function(res) {
                self.text('Send');
                self.removeClass('disabled');

                modal.find('input[name="from_email"]').val('');
                modal.find('input[name="to_email"]').val('');

                modal.modal('hide');
                Swal.fire('Information', res.message, res.status);
            },
            error: function() {
                Swal.fire({
                    title: 'Information',
                    text: 'Something went wrong, please contact admin support',
                    icon: 'error'
                });
                self.text('Send');
                self.removeClass('disabled');
            }
        })
    });


});