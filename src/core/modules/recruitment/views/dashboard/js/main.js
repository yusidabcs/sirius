var ctx = document.getElementById('myChart').getContext('2d');

const loadingModal = function() {
	Swal.fire({
		title: 'Loading',
		text: 'Please wait...',
		icon: 'info',
		allowOutsideClick: false,
		allowEscapeKey: false,
		showConfirmButton: false
	});
	Swal.showLoading();
}

const load_total_recruitment = function() {
    $.ajax({
        url: "/ajax/workflow/recruitment/total",
        type: 'POST',
        cache: false,
        timeout: 10000
    })
        .done(response => {
            let total = 0;
            $('#normal_level').find('> span').html(0);
            $('#soft_level').find('> span').html(0);
            $('#hard_level').find('> span').html(0);
            $('#deadline_level').find('> span').html(0);
            $('#all_level').find('> span').html(0);

            $.each(response,  (index,item) => {
                total += parseInt(item.total);

                if(item.level == 1){
                    $('#normal_level').find('> span').html(item.total)
                }
                else if(item.level == 2){
                    $('#soft_level').find('> span').html(item.total)
                }
                else if(item.level == 3){
                    $('#hard_level').find('> span').html(item.total)
                }
                else if(item.level == 4){
                    $('#deadline_level').find('> span').html(item.total)
                }

                $('#all_level').find('> span').html(total);
            })

        })
        .fail(response => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Connection to Server Failed!'
            });
        }).always(function () {
    });
}

const load_total_personal_reference = function() {
    $.ajax({
        url: "/ajax/workflow/job_application_tracker/total-personal-reference",
        type: 'POST',
        cache: false,
        timeout: 10000
    })
        .done(response => {
            let total = 0;
            $('#personal_normal_level').find('> span').html(0);
            $('#personal_soft_level').find('> span').html(0);
            $('#personal_hard_level').find('> span').html(0);
            $('#personal_deadline_level').find('> span').html(0);
            $('#personal_all_level').find('> span').html(0);
            $.each(response,  (index,item) => {
                total += parseInt(item.total);

                if(item.level == 1){
                    $('#personal_normal_level').find('> span').html(item.total)
                }
                else if(item.level == 2){
                    $('#personal_soft_level').find('> span').html(item.total)
                }
                else if(item.level == 3){
                    $('#personal_hard_level').find('> span').html(item.total)
                }
                else if(item.level == 4){
                    $('#personal_deadline_level').find('> span').html(item.total)
                }
            });


            $('#personal_all_level').find('> span').html(total);

        })
        .fail(response => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Connection to Server Failed!'
            });
        }).always(function () {
    });
}

const load_total_profesional_reference = function() {
    $.ajax({
        url: "/ajax/workflow/job_application_tracker/total-profesional-reference",
        type: 'POST',
        cache: false,
        timeout: 10000
    })
        .done(response => {
            let total = 0;
            $('#profesional_normal_level').find('> span').html(0);
            $('#profesional_soft_level').find('> span').html(0);
            $('#profesional_hard_level').find('> span').html(0);
            $('#profesional_deadline_level').find('> span').html(0);
            $('#profesional_all_level').find('> span').html(0);
            $.each(response,  (index,item) => {
                total += parseInt(item.total);

                if(item.level == 1){
                    $('#profesional_normal_level').find('> span').html(item.total)
                }
                else if(item.level == 2){
                    $('#profesional_soft_level').find('> span').html(item.total)
                }
                else if(item.level == 3){
                    $('#profesional_hard_level').find('> span').html(item.total)
                }
                else if(item.level == 4){
                    $('#profesional_deadline_level').find('> span').html(item.total)
                }
            });


            $('#profesional_all_level').find('> span').html(total);

        })
        .fail(response => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Connection to Server Failed!'
            });
        }).always(function () {
    });
}

const load_total_english_test = function() {
    $.ajax({
        url: "/ajax/workflow/job_application_tracker/total-english-test",
        type: 'POST',
        cache: false,
        timeout: 10000
    })
        .done(response => {
            let total = 0;
            $('#english_normal_level').find('> span').html(0);
            $('#english_soft_level').find('> span').html(0);
            $('#english_hard_level').find('> span').html(0);
            $('#english_deadline_level').find('> span').html(0);
            $('#english_all_level').find('> span').html(0);
            $.each(response,  (index,item) => {
                total += parseInt(item.total);

                if(item.level == 1){
                    $('#english_normal_level').find('> span').html(item.total)
                }
                else if(item.level == 2){
                    $('#english_soft_level').find('> span').html(item.total)
                }
                else if(item.level == 3){
                    $('#english_hard_level').find('> span').html(item.total)
                }
                else if(item.level == 4){
                    $('#english_deadline_level').find('> span').html(item.total)
                }
            });

            $('#english_all_level').find('> span').html(total);

        })
        .fail(response => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Connection to Server Failed!'
            });
        }).always(function () {
    });
}

const load_total_premium_service = function() {
    $.ajax({
        url: "/ajax/workflow/job_application_tracker/total-premium-service",
        type: 'POST',
        cache: false,
        timeout: 10000
    })
        .done(response => {
            let total = 0;
            $('#premium_normal_level').find('> span').html(0);
            $('#premium_soft_level').find('> span').html(0);
            $('#premium_hard_level').find('> span').html(0);
            $('#premium_deadline_level').find('> span').html(0);
            $('#premium_all_level').find('> span').html(0);
            $.each(response,  (index,item) => {
                total += parseInt(item.total);

                if(item.level == 1){
                    $('#premium_normal_level').find('> span').html(item.total)
                }
                else if(item.level == 2){
                    $('#premium_soft_level').find('> span').html(item.total)
                }
                else if(item.level == 3){
                    $('#premium_hard_level').find('> span').html(item.total)
                }
                else if(item.level == 4){
                    $('#premium_deadline_level').find('> span').html(item.total)
                }
            });


            $('#premium_all_level').find('> span').html(total);

        })
        .fail(response => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Connection to Server Failed!'
            });
        }).always(function () {
    });

}

const load_total_interview_ready = function() {
    $.ajax({
        url: "/ajax/workflow/job_application_tracker/total-interview",
        type: 'POST',
        cache: false,
        timeout: 10000
    })
        .done(response => {
            let total = 0;
            $('#interview_normal_level').find('> span').html(0);
            $('#interview_soft_level').find('> span').html(0);
            $('#interview_hard_level').find('> span').html(0);
            $('#interview_deadline_level').find('> span').html(0);
            $('#interview_all_level').find('> span').html(0);
            $.each(response,  (index,item) => {
                total += parseInt(item.total);

                if(item.level == 1){
                    $('#interview_normal_level').find('> span').html(item.total)
                }
                else if(item.level == 2){
                    $('#interview_soft_level').find('> span').html(item.total)
                }
                else if(item.level == 3){
                    $('#interview_hard_level').find('> span').html(item.total)
                }
                else if(item.level == 4){
                    $('#interview_deadline_level').find('> span').html(item.total)
                }

                $('#interview_all_level').find('> span').html(total);
            })

        })
        .fail(response => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Connection to Server Failed!'
            });
        }).always(function () {
    });
}

$(document).ready(function () {
    let filter = {
        status: '',
        level: 0
    };

    const dt_table = $('#recruitment_tracker').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/workflow/recruitment/list",
            "type": "POST",
            data: function (d) {
                d.status = filter.status;
                d.level = filter.level;
            }
        },
        "columns": [
            {"data": "number_given_name"},
            {"data": "status"},
            {"data": "number_given_name"},
            {"data": "entity_family_name"},
            {"data": "main_email", },
            {"data": "level"},
            {"data": null}

        ],
        "order": [[ 1, "DESC" ]],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a class="open-link" target="_blank" href="/personal/home/'+row.address_book_id+'" title="Show Personal">'+row.entity_family_name+' '+row.number_given_name + '<br>' + row.main_email+'</a>'
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    title_tooltips = row.status;
                    if(row.short_description!=null) {
                        title_tooltips = row.short_description;
                    }
                    return `<span data-toggle="tooltip" title="`+title_tooltips+`">`+row.status+`</span>`;
                },
                "targets": 1
            },
            {
                "render": function(_, _, row) {
                    return row.number_given_name
                },
                "targets": 2,
                "visible": false 
            },
            {
                "render": function(_, _, row) {
                    return row.entity_family_name
                },
                "targets": 3,
                "visible": false 
            },
            {
                "render": function(_, _, row) {
                    return row.main_email
                },
                "targets": 4,
                "visible": false 
            },
            {
                "render": function (data, type, row) {
                    if (data == '1')
                        return `<span class="text-success">Normal</span>`
                    if (data == '2')
                        return `<span class="text-info">Soft Warning</span>`
                    if (data == '3')
                        return `<span class="text-warning">Hard Warning</span>`
                    if (data == '4')
                        return `<span class="text-danger">Deadline</span>`
                },
                "targets": -2
            },
            {
                "render": function ( data, type, row ) {
                    var html = '<div class="container row text-center ">'+
                        ' <a data-type="recruitment" data-id="'+row.address_book_id+'" class="delete_tracker btn-sm btn-danger" href="javascript:;"><i class="fa fa-times" title="Delete Data"></i></a>'+
                        '<div>';
                    return html;
                },
                "searchable": false, "orderable": false,"targets": -1
            }
        ],
    });

    const dt_table2 = $('#personal_reference_tracker').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/workflow/job_application_tracker/list-personal-reference",
            "type": "POST",
            data: function (d) {
                d.status = filter.status;
                d.level = filter.level;
            }
        },
        "columns": [
            {"data": "number_given_name"},
            {"data": "status"},
            {"data": "number_given_name"},
            {"data": "entity_family_name"},
            {"data": "main_email", },
            {"data": "level"},
            {"data": null}

        ],
        "order": [[ 1, "DESC" ]],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a class="open-link" target="_blank" href="/personal/home/'+row.address_book_id+'/ref" title="Show Personal">'+row.entity_family_name+' '+row.number_given_name + ' <br>' + row.main_email+'</a>'
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    title_tooltips = row.status;
                    if(row.short_description!=null) {
                        title_tooltips = row.short_description;
                    }
                    return `<span data-toggle="tooltip" title="`+title_tooltips+`">`+row.status+`</span>`;
                },
                "targets": 1
            },
            {
                "render": function(_, _, row) {
                    return row.number_given_name
                },
                "targets": 2,
                "visible": false 
            },
            {
                "render": function(_, _, row) {
                    return row.entity_family_name
                },
                "targets": 3,
                "visible": false 
            },
            {
                "render": function(_, _, row) {
                    return row.main_email
                },
                "targets": 4,
                "visible": false 
            },
            {
                "render": function (data, type, row) {
                    if (data == '1')
                        return `<span class="text-success">Normal</span>`
                    if (data == '2')
                        return `<span class="text-info">Soft Warning</span>`
                    if (data == '3')
                        return `<span class="text-warning">Hard Warning</span>`
                    if (data == '4')
                        return `<span class="text-danger">Deadline</span>`
                },
                "targets": -2
            },
            {
                "render": function ( data, type, row ) {
                    var html = '<div class="container row text-center ">'+
                        ' <a data-type="personal_reference" data-id="'+row.reference_check_id+'" class="delete_tracker btn-sm btn-danger" href="javascript:;"><i class="fa fa-times" title="Delete Data"></i></a>'+
                        '<div>';
                    return html;
                },
                "searchable": false, "orderable": false,"targets": -1
            }
        ],
    });

    const dt_table3 = $('#profesional_reference_tracker').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/workflow/job_application_tracker/list-profesional-reference",
            "type": "POST",
            data: function (d) {
                d.status = filter.status;
                d.level = filter.level;
            }
        },
        "columns": [
            {"data": "number_given_name"},
            {"data": "status"},
            {"data": "number_given_name"},
            {"data": "entity_family_name"},
            {"data": "main_email", },
            {"data": "level"},
            {"data": null}

        ],
        "order": [[ 1, "DESC" ]],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a class="open-link" target="_blank" href="/personal/home/'+row.address_book_id+'/ref" title="Show Personal">'+row.entity_family_name+' '+row.number_given_name + ' <br>' + row.main_email+'</a>'
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    title_tooltips = row.status;
                    if(row.short_description!=null) {
                        title_tooltips = row.short_description;
                    }
                    return `<span data-toggle="tooltip" title="`+title_tooltips+`">`+row.status+`</span>`;
                },
                "targets": 1
            },
            {
                "render": function(_, _, row) {
                    return row.number_given_name
                },
                "targets": 2,
                "visible": false 
            },
            {
                "render": function(_, _, row) {
                    return row.entity_family_name
                },
                "targets": 3,
                "visible": false 
            },
            {
                "render": function(_, _, row) {
                    return row.main_email
                },
                "targets": 4,
                "visible": false 
            },
            {
                "render": function (data, type, row) {
                    if (data == '1')
                        return `<span class="text-success">Normal</span>`
                    if (data == '2')
                        return `<span class="text-info">Soft Warning</span>`
                    if (data == '3')
                        return `<span class="text-warning">Hard Warning</span>`
                    if (data == '4')
                        return `<span class="text-danger">Deadline</span>`
                },
                "targets": -2
            },
            {
                "render": function ( data, type, row ) {
                    var html = '<div class="container row text-center ">'+
                        ' <a data-type="profesional_reference" data-id="'+row.reference_check_id+'" class="delete_tracker btn-sm btn-danger" href="javascript:;"><i class="fa fa-times" title="Delete Data"></i></a>'+
                        '<div>';
                    return html;
                },
                "searchable": false, "orderable": false,"targets": -1
            }
        ],
    });

    const dt_table4 = $('#english_test_tracker').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/workflow/job_application_tracker/list-english-test",
            "type": "POST",
            data: function (d) {
                d.status = filter.status;
                d.level = filter.level;
            }
        },
        "columns": [
            {"data": "number_given_name"},
            {"data": "status"},
            {"data": "number_given_name"},
            {"data": "entity_family_name"},
            {"data": "main_email", },
            {"data": "level"},
            {"data": null}

        ],
        "order": [[ 1, "DESC" ]],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a class="open-link" target="_blank" href="/personal/home/'+row.address_book_id+'/documents/english" title="Show Personal">'+row.entity_family_name+' '+row.number_given_name + ' <br>' + row.main_email+'</a>'
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    title_tooltips = row.status;
                    if(row.short_description!=null) {
                        title_tooltips = row.short_description;
                    }
                    return `<span data-toggle="tooltip" title="`+title_tooltips+`">`+row.status+`</span>`;
                },
                "targets": 1
            },
            {
                "render": function(_, _, row) {
                    return row.number_given_name
                },
                "targets": 2,
                "visible": false 
            },
            {
                "render": function(_, _, row) {
                    return row.entity_family_name
                },
                "targets": 3,
                "visible": false 
            },
            {
                "render": function(_, _, row) {
                    return row.main_email
                },
                "targets": 4,
                "visible": false 
            },
            {
                "render": function (data, type, row) {
                    if (data == '1')
                        return `<span class="text-success">Normal</span>`
                    if (data == '2')
                        return `<span class="text-info">Soft Warning</span>`
                    if (data == '3')
                        return `<span class="text-warning">Hard Warning</span>`
                    if (data == '4')
                        return `<span class="text-danger">Deadline</span>`
                },
                "targets": -2
            },
            {
                "render": function ( data, type, row ) {
                    var html ='<div class="text-left d-flex">';
                    if(row.status=='request_file') {
                        html += 
                        ' <a data-type="english_test" data-id="'+row.address_book_id+'" class="request_file_englist_test btn-sm btn-info" href="javascript:;" data-toggle="tooltip" title="Request File"><i class="fas fa-paper-plane"></i></a>';
                    }
                    html += 
                        ' <a data-type="english_test" data-id="'+row.address_book_id+'" class="delete_tracker btn-sm btn-danger" href="javascript:;"><i class="fa fa-times" title="Delete Data"></i></a>';
                    

                    html +='</div>';
                    return html;
                },
                "searchable": false, "orderable": false,"targets": -1
            }
        ],
    });

    const dt_table5 = $('#premium_service_tracker').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/workflow/job_application_tracker/list-premium-service",
            "type": "POST",
            data: function (d) {
                d.status = filter.status;
                d.level = filter.level;
            }
        },
        "columns": [
            {"data": "number_given_name"},
            {"data": "status"},
            {"data": "number_given_name"},
            {"data": "entity_family_name"},
            {"data": "main_email", },
            {"data": "level"},
            {"data": null}

        ],
        "order": [[ 1, "DESC" ]],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a class="open-link" target="_blank" href="/personal/home/'+row.address_book_id+'" title="Show Personal">'+row.entity_family_name+' '+row.number_given_name + ' <br>' + row.main_email+'</a>'
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    title_tooltips = row.status;
                    if(row.short_description!=null) {
                        title_tooltips = row.short_description;
                    }
                    return `<span data-toggle="tooltip" title="`+title_tooltips+`">`+row.status+`</span>`;
                },
                "targets": 1
            },
            {
                "render": function(_, _, row) {
                    return row.number_given_name
                },
                "targets": 2,
                "visible": false 
            },
            {
                "render": function(_, _, row) {
                    return row.entity_family_name
                },
                "targets": 3,
                "visible": false 
            },
            {
                "render": function(_, _, row) {
                    return row.main_email
                },
                "targets": 4,
                "visible": false 
            },
            {
                "render": function (data, type, row) {
                    if (data == '1')
                        return `<span class="text-success">Normal</span>`
                    if (data == '2')
                        return `<span class="text-info">Soft Warning</span>`
                    if (data == '3')
                        return `<span class="text-warning">Hard Warning</span>`
                    if (data == '4')
                        return `<span class="text-danger">Deadline</span>`
                },
                "targets": -2
            },
            {
                "render": function ( data, type, row ) {
                    var html = '<div class="container row text-center ">'+
                        ' <a data-type="premium_service" data-id="'+row.address_book_id+'" class="delete_tracker btn-sm btn-danger" href="javascript:;"><i class="fa fa-times" title="Delete Data"></i></a>'+
                        '<div>';
                    return html;
                },
                "searchable": false, "orderable": false,"targets": -1
            }
        ],
    });

    const dt_table6 = $('#interview_tracker').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/workflow/job_application_tracker/list-interview",
            "type": "POST",
            data: function (d) {
                d.status = filter.status;
                d.level = filter.level;
            }
        },
        "columns": [
            {"data": "number_given_name"},
            {"data": "status"},
            {"data": "number_given_name"},
            {"data": "entity_family_name"},
            {"data": "main_email", },
            {"data": "level"},
            {"data": null}

        ],
        "order": [[ 1, "DESC" ]],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a class="open-link" target="_blank" href="/personal/home/'+row.address_book_id+'" title="Show Personal">'+row.entity_family_name+' '+row.number_given_name + ' <br>' + row.main_email+'</a>'
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    title_tooltips = row.status;
                    if(row.short_description!=null) {
                        title_tooltips = row.short_description;
                    }
                    return `<span data-toggle="tooltip" title="`+title_tooltips+`">`+row.status+`</span>`;
                },
                "targets": 1
            },
            {
                "render": function(_, _, row) {
                    return row.number_given_name
                },
                "targets": 2,
                "visible": false 
            },
            {
                "render": function(_, _, row) {
                    return row.entity_family_name
                },
                "targets": 3,
                "visible": false 
            },
            {
                "render": function(_, _, row) {
                    return row.main_email
                },
                "targets": 4,
                "visible": false 
            },
            {
                "render": function (data, type, row) {
                    if (data == '1')
                        return `<span class="text-success">Normal</span>`
                    if (data == '2')
                        return `<span class="text-info">Soft Warning</span>`
                    if (data == '3')
                        return `<span class="text-warning">Hard Warning</span>`
                    if (data == '4')
                        return `<span class="text-danger">Deadline</span>`
                },
                "targets": -2
            },
            {
                "render": function ( data, type, row ) {
                    var html = '<div class="container row text-center ">'+
                        ' <a data-type="interview_ready" data-id="'+row.address_book_id+'" class="delete_tracker btn-sm btn-danger" href="javascript:;"><i class="fa fa-times" title="Delete Data"></i></a>'+
                        '<div>';
                    return html;
                },
                "searchable": false, "orderable": false,"targets": -1
            }
        ],
    });

    const dt_table7 = $('#stcw_tracker').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ajax/workflow/job_application_tracker/list-stcw",
            "type": "POST",
            data: function (d) {
                d.status = filter.status;
                d.level = filter.level;
            }
        },
        "columns": [
            {"data": "number_given_name"},
            {"data": "stcw_type"},
            {"data": "status"},
            {"data": "number_given_name"},
            {"data": "entity_family_name"},
            {"data": "main_email", },
            {"data": "level"},

        ],
        "order": [[ 1, "DESC" ]],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a class="open-link" target="_blank" href="/personal/home/'+row.address_book_id+'" title="Show Personal">'+row.entity_family_name+' '+row.number_given_name + ' <br>' + row.main_email+'</a>'
                },
                "targets": 0
            },
            {
                "render": function(_, _, row) {
                    return row.number_given_name
                },
                "targets": 3,
                "visible": false 
            },
            {
                "render": function(_, _, row) {
                    return row.entity_family_name
                },
                "targets": 4,
                "visible": false 
            },
            {
                "render": function(_, _, row) {
                    return row.main_email
                },
                "targets": 5,
                "visible": false 
            },
            {
                "render": function (data, type, row) {
                    if (data == '1')
                        return `<span class="text-success">Normal</span>`
                    if (data == '2')
                        return `<span class="text-info">Soft Warning</span>`
                    if (data == '3')
                        return `<span class="text-warning">Hard Warning</span>`
                    if (data == '4')
                        return `<span class="text-danger">Deadline</span>`
                },
                "targets": -1
            },
        ],
    });

    $(document).on('mouseover', 'tr', function () {
        $('[data-toggle="tooltip"]').tooltip({
            trigger: 'hover',
            html: true
        });
    });

    $('#all_level, #normal_level, #soft_level, #hard_level, #deadline_level').on('click', function(e) {
        e.preventDefault();
        filter.level = $(this).data('level');
        dt_table.ajax.reload();
    });

    $('select[name="verification_filter"]').on('change', function() {
        filter.status = $(this).val();
        dt_table.ajax.reload();
    });

    $('#personal_all_level, #personal_normal_level, #personal_soft_level, #personal_hard_level, #personal_deadline_level').on('click', function(e) {
        e.preventDefault();
        filter.level = $(this).data('level');
        dt_table2.ajax.reload();
    });

    $('select[name="personal_filter"]').on('change', function() {
        filter.status = $(this).val();
        dt_table2.ajax.reload();
    });

    $('#profesional_all_level, #profesional_normal_level, #profesional_soft_level, #profesional_hard_level, #profesional_deadline_level').on('click', function(e) {
        e.preventDefault();
        filter.level = $(this).data('level');
        dt_table3.ajax.reload();
    });

    $('select[name="profesional_filter"]').on('change', function() {
        filter.status = $(this).val();
        dt_table3.ajax.reload();
    });
    
    $('#english_all_level, #english_normal_level, #english_soft_level, #english_hard_level, #english_deadline_level').on('click', function(e) {
        e.preventDefault();
        filter.level = $(this).data('level');
        dt_table4.ajax.reload();
    });

    $('select[name="english_filter"]').on('change', function() {
        filter.status = $(this).val();
        dt_table4.ajax.reload();
    });

    $('#premium_all_level, #premium_normal_level, #premium_soft_level, #premium_hard_level, #premium_deadline_level').on('click', function(e) {
        e.preventDefault();
        filter.level = $(this).data('level');
        dt_table5.ajax.reload();
    });

    $('select[name="premium_filter"]').on('change', function() {
        filter.status = $(this).val();
        dt_table5.ajax.reload();
    });

    $('#interview_all_level, #interview_normal_level, #interview_soft_level, #interview_hard_level, #interview_deadline_level').on('click', function(e) {
        e.preventDefault();
        filter.level = $(this).data('level');
        dt_table6.ajax.reload();
    });

    $('select[name="interview_filter"]').on('change', function() {
        filter.status = $(this).val();
        dt_table6.ajax.reload();
    });

    $('#stcw_all_level, #stcw_normal_level, #stcw_soft_level, #stcw_hard_level, #stcw_deadline_level').on('click', function(e) {
        e.preventDefault();
        filter.level = $(this).data('level');
        dt_table7.ajax.reload();
    });

    $('select[name="stcw_filter"]').on('change', function() {
        filter.status = $(this).val();
        dt_table7.ajax.reload();
    });
    
    load_total_recruitment();    
    load_total_personal_reference();    
    load_total_profesional_reference();    
    load_total_english_test();    
    load_total_premium_service();    
    load_total_interview_ready();    

    $.ajax({
        url: "/ajax/workflow/job_application_tracker/total-stcw",
        type: 'POST',
        cache: false,
        timeout: 10000
    })
        .done(response => {
            let total = 0;

            $.each(response,  (index,item) => {
                total += parseInt(item.total);

                if(item.level == 1){
                    $('#stcw_normal_level').find('> span').html(item.total)
                }
                else if(item.level == 2){
                    $('#stcw_soft_level').find('> span').html(item.total)
                }
                else if(item.level == 3){
                    $('#stcw_hard_level').find('> span').html(item.total)
                }
                else if(item.level == 4){
                    $('#stcw_deadline_level').find('> span').html(item.total)
                }

                $('#stcw_all_level').find('> span').html(total);
            })

        })
        .fail(response => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Connection to Server Failed!'
            });
        }).always(function () {
    });

    $.ajax({
        url: "/ajax/recruitment/main/dashboard",
        type: 'POST',
        cache: false,
        timeout: 10000
    })
        .done(response => {
            var date = []
            var data = []
            $.each(response, function (index, item) {
                date.push(item.date)
                data.push(item.total)
            })
            var chart = new Chart(ctx, {
                // The type of chart we want to create
                type: 'line',

                // The data for our dataset
                data: {
                    labels: date,
                    datasets: [{
                        label: 'Monthly Registration',
                        borderColor: '#007bff',
                        data: data
                    }]
                },

                // Configuration options go here
                options: {
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Date'
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                min: 0,
                                stepSize: 10,
                                suggestedMin: 50,
                                suggestedMax: 50
                            }
                        }]
                    },
                }
            });


        })
        .fail(response => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Connection to Server Failed!'
            });
        }).always(function () {
    });

    $(document).on('click','.delete_tracker',function(){
        let type = $(this).data('type');
        let id = $(this).data('id');

        swal.fire({
            title: 'Are you sure?',
            text: "This action will delete this item?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
			cancelButtonText: 'Cancel',
			focusConfirm: false,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`/ajax/workflow/main/delete-tracker`,{
                    headers: {
                        "Content-Type": "application/json"
                      },
                    method : 'POST',
                    body : JSON.stringify({
                        id: id,
                        type: type,
                      })
                })
                .then(response => {
                    if (!response.status) {
                        Swal.showValidationMessage(`Error could not delete tracker data.`)
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
                    title: 'Great!',
                    html: result.value.message,
				})
				if (result.value.status=='ok') {
                    switch (result.value.type) {
                        case 'recruitment':
                            dt_table.ajax.reload();
                            load_total_recruitment();
                            break;
                        case 'personal_reference':
                            dt_table2.ajax.reload();
                            load_total_personal_reference();
                            break;
                        case 'profesional_reference':
                            dt_table3.ajax.reload();
                            load_total_profesional_reference();
                            break;
                        case 'english_test':
                            dt_table4.ajax.reload();
                            load_total_english_test();
                            break;
                        case 'premium_service':
                            dt_table5.ajax.reload();
                            load_total_premium_service();
                            break;
                        case 'interview_ready':
                            dt_table6.ajax.reload();
                            load_total_interview_ready();
                            break;   
                        default:
                            break;
                    }
				}
            }
        })
    })

    $(document).on('click','.request_file_englist_test',function(){
        let id = $(this).data('id');

        swal.fire({
            title: 'Are you sure?',
            text: "This action will send email to candidate.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Send',
			cancelButtonText: 'Cancel',
			focusConfirm: false,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`/ajax/recruitment/main/request-englist-test`,{
                    headers: {
                        "Content-Type": "application/json"
                      },
                    method : 'POST',
                    body : JSON.stringify({
                        id: id
                      })
                })
                .then(response => {
                    if (!response.status) {
                        Swal.showValidationMessage(`Error could not send email.`)
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
                    title: result.value.message
                    //html: result.value.message,
				})
				if (result.value.status=='ok') {
                    //reload table englist test tracker
                    dt_table4.ajax.reload();
				}
            }
        })
    })

});
