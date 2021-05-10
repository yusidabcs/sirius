
let table_deployment_visa;
let table_deployment_oktb;
let table_deployment_stcw;
let table_deployment_medical;
let table_deployment_vaccination;
let table_deployment_flight;
let table_deployment_police;
let table_deployment_seaman;
let table_deployment_travelpack;
function load_deployment_visa(){
    table_deployment_visa = $('#deployment_list_visa').DataTable({
        "processing": true,
        "serverSide": true,
        'responsive': true,
        'order' : [[4,'desc']],
        "ajax": {
            "url": "/ajax/workflow/visa/list",
            "type": "POST",
            cache: false,
            data: function (d) {
                d.address_book = $('#address_book').val();
            }
        },
        "columns": [
            {"data": 'candidate'},
            {"data": 'visa_type'},
            {"data": 'status'},
            {"data": 'level'},
            {"data": 'created_on'},
            {"data": null}
        ],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a href="/personal/home/'+row.address_book_id+'">'+row.fullname+'<br>' + row.main_email+'</a>';
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    if (data == 'register_visa' && row.send_notification_on != '0000-00-00 00:00:00' && row.send_notification_on != null){
                        return data + `<br><span class="badge badge-info">Send request on: ${row.send_notification_on}`
                    }

                    if (data == 'docs_application' && row.docs_application_on != '0000-00-00 00:00:00'){
                        return data + `<br><span class="badge badge-info">Docs application on: ${row.docs_application_on}`
                    }
                    
                    return data;
                },
                "targets": 2
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
                "targets": 3
            },

            {
                "render": function (data, type, row) {

                    var html = ``;
                    if(row.status === 'register_visa' && row.send_notification_on !== '0000-00-00 00-00-00') {
                        html += `<a  class="btn-sm btn-info visa-btn-send-notification" href="#" title="Send Notification" ><i class="fa fa-plus"></i> Send Notification</a>`
                    }
                    if(row.status === 'docs_application' && row.docs_application_date !== '0000-00-00 00-00-00') {
                        html += `<a  class="btn-sm btn-info visa-btn-send-docs-application" href="#" title="Docs Application" ><i class="fa fa-plus"></i> Docs Application</a>`
                    }
                    else if(row.status == 'upload_visa' && row.upload_visa_on !== '0000-00-00 00:00:00'){
                        html += `<a  class="btn-sm btn-info visa-btn-review-visa" href="#" title="Review File" ><i class="fa fa-dollar-sign"></i> Review</a>`;
                    }
                    else if(row.status == 'rejected'){
                        html += `<a  class="btn-sm btn-info visa-btn-rejected" href="#" title="Rejected" ><i class="fa fa-dollar-sign"></i> Rejected</a>`;
                    }


                    return html;
                },
                "targets": 5, "searchable": false, "orderable": false
            }
        ],
    });
}

function load_deployment_oktb(){
    table_deployment_oktb = $('#deployment_list_oktb').DataTable({
        "processing": true,
        "serverSide": true,
        'responsive': true,
        'order' : [[4,'desc']],
        "ajax": {
            "url": "/ajax/workflow/oktb/list",
            "type": "POST",
            cache: false,
            data: function (d) {
                d.address_book = $('#address_book').val();
            }
        },
        "columns": [
            {"data": 'candidate'},
            {"data": 'oktb_type'},
            {"data": 'status'},
            {"data": 'level'},
            {"data": 'created_on'},
            {"data": null}
        ],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a href="/personal/home/'+row.address_book_id+'">'+row.fullname+'<br>' + row.main_email+'</a>';
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    if (data == 'request_file' && row.request_file_on != '0000-00-00 00:00:00'){
                        return data + `<br><span class="badge badge-info">Send request on: ${row.request_file_on}`
                    }
                    return data;
                },
                "targets": 2
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
                "targets": 3
            },

            {
                "render": function (data, type, row) {

                    var html = ``;
                    if(row.status == 'requirement_check'){
                        html += `<a  class="btn-sm btn-info oktb-btn-requirement-check" href="#" title="Request File" ><i class="fa fa-plus"></i> Request File</a>`;
                    }
                    else if(row.status == 'review_file'){
                        html += `<a  class="btn-sm btn-info oktb-btn-review-file" href="#" title="Review File" ><i class="fa fa-dollar-sign"></i> Review</a>`;
                    }
                    else if(row.status == 'rejected'){
                        html += `<a  class="btn-sm btn-info oktb-btn-rejected" href="#" title="Rejected" ><i class="fa fa-dollar-sign"></i> Rejected</a>`;
                    }


                    return html;
                },
                "targets": 5, "searchable": false, "orderable": false
            }
        ],
    });
}

function load_deployment_stcw() {
    table_deployment_stcw = $('#deployment_list_stcw').DataTable({
        "processing": true,
        "serverSide": true,
        'responsive': true,
        'order' : [[3,'desc']],
        "ajax": {
            "url": "/ajax/workflow/stcw/list",
            "type": "POST",
            cache: false,
            data: function (d) {
                d.address_book = $('#address_book').val();
            }
        },
        "columns": [
            {"data": 'candidate'},
            {"data": 'status'},
            {"data": 'level'},
            {"data": 'created_on'},
            {"data": null}
        ],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a href="/personal/home/'+row.address_book_id+'">'+row.fullname+'<br>' + row.main_email+'</a>';
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    if (data == 'request_file' && row.request_file_on != '0000-00-00 00:00:00' && row.request_file_on != null){
                        return data + `<br><span class="badge badge-info">Send request on: ${row.request_file_on}`
                    }
                    return data;
                },
                "targets": 1
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
                "targets": 2
            },

            {
                "render": function(data) {
                    return data
                },
                "targets": 3
            },

            {
                "render": function (data, type, row) {

                    var html = ``;
                    if(row.status == 'request_file'){
                        html += `<a  class="btn-sm btn-info stcw-btn-request-file" href="#" title="Request File" ><i class="fa fa-plus"></i> Request File</a>`;
                    }
                    else if(row.status == 'review_file'){
                        html += `<a  class="btn-sm btn-info stcw-btn-review-file" href="#" title="Review File" ><i class="fa fa-dollar-sign"></i> Review</a>`;
                    }
                    else if(row.status == 'rejected'){
                        html += `<a  class="btn-sm btn-info stcw-btn-rejected" href="#" title="Rejected" ><i class="fa fa-dollar-sign"></i> Rejected</a>`;
                    }


                    return html;
                },
                "targets": 4, "searchable": false, "orderable": false
            }
        ],
    });
}

function load_deployment_medical() {
    table_deployment_medical = $('#deployment_list_medical').DataTable({
        "processing": true,
        "serverSide": true,
        'responsive': true,
        'order' : [[3,'desc']],
        "ajax": {
            "url": "/ajax/workflow/medical/list",
            "type": "POST",
            cache: false,
            data: function (d) {
                d.address_book = $('#address_book').val();
            }
        },
        "columns": [
            {"data": 'candidate'},
            {"data": 'status'},
            {"data": 'level'},
            {"data": 'created_on'},
            {"data": null}
        ],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a href="/personal/home/'+row.address_book_id+'">'+row.fullname+'<br>' + row.main_email+'</a>';
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    if (data == 'request_file' && row.request_file_on != '0000-00-00 00:00:00' && row.request_file_on != null){
                        return data + `<br><span class="badge badge-info">Send request on: ${row.request_file_on}`
                    }

                    if (data == 'request_appointment_date' && row.request_appointment_date_on !== '0000-00-00 00:00:00' && row.request_appointment_date_on !== null){
                        return data + `<br><span class="badge badge-info">Request appointment date on: ${row.request_appointment_date_on}`
                    }
                    return data;
                },
                "targets": 1
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
                "targets": 2
            },

            {
                "render": function(data) {
                    return data
                },
                "targets": 3
            },
            {
                "render": function (data, type, row) {

                    var html = ``;
                    if(row.status === 'request_appointment_date') {
                        html += `<a  class="btn-sm btn-info medical-btn-request-appointment-date" href="#" title="Request File" ><i class="fa fa-plus"></i> Request Appointment</a>`
                    }
                    else if(row.status == 'request_file'){
                        html += `<a  class="btn-sm btn-info medical-btn-request-file" href="#" title="Request File" ><i class="fa fa-plus"></i> Request File</a>`;
                    }
                    else if(row.status == 'review_file'){
                        html += `<a  class="btn-sm btn-info medical-btn-review-file" href="#" title="Review File" ><i class="fa fa-dollar-sign"></i> Review</a>`;
                    }
                    else if(row.status == 'rejected'){
                        html += `<a  class="btn-sm btn-info medical-btn-rejected" href="#" title="Rejected" ><i class="fa fa-dollar-sign"></i> Rejected</a>`;
                    }


                    return html;
                },
                "targets": 4, "searchable": false, "orderable": false
            }
        ],
    });
}

function load_deployment_vaccination() {
    table_deployment_vaccination = $('#deployment_list_vaccine').DataTable({
        "processing": true,
        "serverSide": true,
        'responsive': true,
        'order' : [[3,'desc']],
        "ajax": {
            "url": "/ajax/workflow/vaccine/list",
            "type": "POST",
            cache: false,
            data: function (d) {
                d.address_book = $('#address_book').val();
            }
        },
        "columns": [
            {"data": 'candidate'},
            {"data": 'status'},
            {"data": 'level'},
            {"data": 'created_on'},
            {"data": null}
        ],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a href="/personal/home/'+row.address_book_id+'">'+row.fullname+'<br>' + row.main_email+'</a>';
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    if (data == 'request_file' && row.request_file_on != '0000-00-00 00:00:00' && row.request_file_on != null){
                        return data + `<br><span class="badge badge-info">Send request on: ${row.request_file_on}`
                    }

                    if (data == 'request_appointment_date' && row.request_appointment_date_on !== '0000-00-00 00:00:00' && row.request_appointment_date_on !== null){
                        return data + `<br><span class="badge badge-info">Request appointment date on: ${row.request_appointment_date_on}`
                    }
                    return data;
                },
                "targets": 1
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
                "targets": 2
            },

            {
                "render": function(data) {
                    return data
                },
                "targets": 3
            },
            {
                "render": function (data, type, row) {

                    var html = ``;
                    if(row.status === 'request_appointment_date') {
                        html += `<a  class="btn-sm btn-info vaccination-btn-request-appointment-date" href="#" title="Request File" ><i class="fa fa-plus"></i> Request Appointment</a>`
                    }
                    else if(row.status == 'request_file'){
                        html += `<a  class="btn-sm btn-info vaccination-btn-request-file" href="#" title="Request File" ><i class="fa fa-plus"></i> Request File</a>`;
                    }
                    else if(row.status == 'review_file'){
                        html += `<a  class="btn-sm btn-info vaccination-btn-review-file" href="#" title="Review File" ><i class="fa fa-dollar-sign"></i> Review</a>`;
                    }
                    else if(row.status == 'rejected'){
                        html += `<a  class="btn-sm btn-info vaccination-btn-rejected" href="#" title="Rejected" ><i class="fa fa-dollar-sign"></i> Rejected</a>`;
                    }


                    return html;
                },
                "targets": 4, "searchable": false, "orderable": false
            }
        ],
    });
}

function load_deployment_flight() {

    table_deployment_flight = $('#deployment_list_flight').DataTable({
        "processing": true,
        "serverSide": true,
        'responsive': true,
        'order' : [[3,'desc']],
        "ajax": {
            "url": "/ajax/workflow/flight/list",
            "type": "POST",
            cache: false,
            data: function (d) {
                d.address_book = $('#address_book').val();
            }
        },
        "columns": [
            {"data": 'candidate'},
            {"data": 'status'},
            {"data": 'level'},
            {"data": 'created_on'},
            {"data": null}
        ],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a href="/personal/home/'+row.address_book_id+'">'+row.fullname+'<br>' + row.main_email+'</a>';
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    if (data == 'request_file' && row.request_file_on != '0000-00-00 00:00:00' && row.request_file_on != null){
                        return data + `<br><span class="badge badge-info">Send request on: ${row.request_file_on}`
                    }

                    if (data == 'request_appointment_date' && row.request_appointment_date_on !== '0000-00-00 00:00:00' && row.request_appointment_date_on !== null){
                        return data + `<br><span class="badge badge-info">Request appointment date on: ${row.request_appointment_date_on}`
                    }
                    return data;
                },
                "targets": 1
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
                "targets": 2
            },

            {
                "render": function(data) {
                    return data
                },
                "targets": 3
            },
            {
                "render": function (data, type, row) {

                    var html = ``;
                    if(row.status == 'request_file'){
                        html += `<a  class="btn-sm btn-info flight-btn-request-file" href="#" title="Request File" ><i class="fa fa-plus"></i> Request File</a>`;
                    }
                    else if(row.status == 'review_file'){
                        html += `<a  class="btn-sm btn-info flight-btn-review-file" href="#" title="Review File" ><i class="fa fa-dollar-sign"></i> Review</a>`;
                    }
                    else if(row.status == 'rejected'){
                        html += `<a  class="btn-sm btn-info flight-btn-rejected" href="#" title="Rejected" ><i class="fa fa-dollar-sign"></i> Rejected</a>`;
                    }


                    return html;
                },
                "targets": 4, "searchable": false, "orderable": false
            }
        ],
    });
}

function load_deployment_police() {
    table_deployment_police = $('#deployment_list_police').DataTable({
        "processing": true,
        "serverSide": true,
        'responsive': true,
        'order' : [[3,'desc']],
        "ajax": {
            "url": "/ajax/workflow/police/list",
            "type": "POST",
            cache: false,
            data: function (d) {
                d.address_book = $('#address_book').val();
            }
        },
        "columns": [
            {"data": 'fullname'},
            {"data": 'status'},
            {"data": 'level'},
            {"data": 'created_on'},
            {"data": null}
        ],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a href="/personal/home/'+row.address_book_id+'">'+data+'<br>' + row.main_email+'</a>';
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    if (data == 'request_file' && row.request_file_on != '0000-00-00 00:00:00' && row.request_file_on !== null){
                        return data + `<br><span class="badge badge-info">Send request on: ${row.request_file_on}`
                    }
                    return data;
                },
                "targets": 1
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
                "targets": 2
            },

            {
                "render": function(data) {
                    return data
                },
                "targets": 3
            },

            {
                "render": function (data, type, row) {

                    var html = ``;
                    if(row.status == 'request_file'){
                        html += `<a  class="btn-sm btn-info police-btn-request-file" href="#" title="Request File" ><i class="fa fa-plus"></i> Request File</a>`;
                    }
                    else if(row.status == 'review_file'){
                        html += `<a  class="btn-sm btn-info police-btn-review-file" href="#" title="Review File" ><i class="fa fa-dollar-sign"></i> Review</a>`;
                    }
                    else if(row.status == 'rejected'){
                        html += `<a  class="btn-sm btn-info police-btn-rejected" href="#" title="Rejected" ><i class="fa fa-dollar-sign"></i> Rejected</a>`;
                    }


                    return html;
                },
                "targets": 4, "searchable": false, "orderable": false
            }
        ],
    });
}

function load_deployment_seaman() {
    table_deployment_seaman = $('#deployment_list_seaman').DataTable({
        "processing": true,
        "serverSide": true,
        'responsive': true,
        'order' : [[3,'desc']],
        "ajax": {
            "url": "/ajax/workflow/seaman/list",
            "type": "POST",
            cache: false,
            data: function (d) {
                d.address_book = $('#address_book').val();
            }
        },
        "columns": [
            {"data": 'candidate'},
            {"data": 'status'},
            {"data": 'level'},
            {"data": 'created_on'},
            {"data": null}
        ],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a href="/personal/home/'+row.address_book_id+'">'+row.fullname+'<br>' + row.main_email+'</a>';
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    if (data == 'request_file' && row.request_file_on != '0000-00-00 00:00:00' && row.request_file_on != ''){
                        return data + `<br><span class="badge badge-info">Send request on: ${row.request_file_on}`
                    }
                    return data;
                },
                "targets": 1
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
                "targets": 2
            },

            {
                "render": function(data) {
                    return data
                },
                "targets": 3
            },

            {
                "render": function (data, type, row) {

                    var html = ``;
                    if(row.status == 'request_file'){
                        html += `<a  class="btn-sm btn-info seaman-btn-request-file" href="#" title="Request File" ><i class="fa fa-plus"></i> Request File</a>`;
                    }
                    else if(row.status == 'review_file'){
                        html += `<a  class="btn-sm btn-info seaman-btn-review-file" href="#" title="Review File" ><i class="fa fa-dollar-sign"></i> Review</a>`;
                    }
                    else if(row.status == 'rejected'){
                        html += `<a  class="btn-sm btn-info seaman-btn-rejected" href="#" title="Rejected" ><i class="fa fa-dollar-sign"></i> Rejected</a>`;
                    }


                    return html;
                },
                "targets": 4, "searchable": false, "orderable": false
            }
        ],
    });
}

function load_deployment_travelpack() {
    table_deployment_travelpack = $('#deployment_list_travelpack').DataTable({
        "processing": true,
        "serverSide": true,
        'responsive': true,
        'order' : [[3,'desc']],
        "ajax": {
            "url": "/ajax/workflow/travelpack/list",
            "type": "POST",
            cache: false,
            data: function (d) {
                d.job_application = $('#job_application').val();
            }
        },
        "columns": [
            {"data": 'candidate'},
            {"data": 'status'},
            {"data": 'level'},
            {"data": 'created_on'},
            {"data": null}
        ],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return '<a href="/personal/home/'+row.address_book_id+'">'+row.fullname+'<br>' + row.main_email+'</a>';
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    if (data == 'pay_invoice') {
                        return `<span class="badge badge-warning">Pay Invoice</span> <br> Expected paid on: ${row.invoice_expected_on}`
                    } else {
                        return data;
                    }
                },
                "targets": 1
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
                "targets": 2
            },

            {
                "render": function(data) {
                    return data
                },
                "targets": 3
            },
            {
                "render": function (data, type, row) {

                    var html = ``;
                    if(row.status == 'generate_invoice'){
                        html += `<a  class="btn-sm btn-info btn-generate-invoice" href="#" title="Generate Invoice" ><i class="fa fa-plus"></i> Invoice</a>`;
                    }
                    else if(row.status == 'pay_invoice'){
                        html += `<a  class="btn-sm btn-info btn-pay-invoice" href="#" title="Pay Invoice" ><i class="fa fa-dollar-sign"></i> Pay</a> <a  class="btn-sm btn-warning btn-pay-resend white-text" href="#" title="resend invoice" ><i class="fa fa-paper-plane"></i> Resend Invoice</a>`;
                    }


                    return html;
                },
                "targets": 4, "searchable": false, "orderable": false
            }
        ],
    });
}

function load_summary_tracker() {
    $.ajax({
        type: 'POST',
        url: '/ajax/deployment/main/summary',
        dataType: 'json',
        data: {address_book_id:$('#address_book').val(),job_application_id:$('#job_application').val()},
        success: rs => {
            $('#visa_status').text(rs.visa.status);
            $('#visa_percentage').attr('style','width: '+rs.visa.percentage+'; height: 15px');
            $('#visa_percentage').html(rs.visa.percentage);

            $('#oktb_status').text(rs.oktb.status);
            $('#oktb_percentage').attr('style','width: '+rs.oktb.percentage+'; height: 15px');
            $('#oktb_percentage').html(rs.oktb.percentage);

            $('#stcw_status').text(rs.stcw.status);
            $('#stcw_percentage').attr('style','width: '+rs.stcw.percentage+'; height: 15px');
            $('#stcw_percentage').html(rs.stcw.percentage);

            $('#medical_status').text(rs.medical.status);
            $('#medical_percentage').attr('style','width: '+rs.medical.percentage+'; height: 15px');
            $('#medical_percentage').html(rs.medical.percentage);

            $('#vaccination_status').text(rs.vaccination.status);
            $('#vaccination_percentage').attr('style','width: '+rs.vaccination.percentage+'; height: 15px');
            $('#vaccination_percentage').html(rs.vaccination.percentage);

            $('#flight_status').text(rs.flight.status);
            $('#flight_percentage').attr('style','width: '+rs.flight.percentage+'; height: 15px');
            $('#flight_percentage').html(rs.flight.percentage);

            $('#police_status').text(rs.police.status);
            $('#police_percentage').attr('style','width: '+rs.police.percentage+'; height: 15px');
            $('#police_percentage').html(rs.police.percentage);

            $('#seaman_status').text(rs.seaman.status);
            $('#seaman_percentage').attr('style','width: '+rs.seaman.percentage+'; height: 15px');
            $('#seaman_percentage').html(rs.seaman.percentage);

            $('#travelpack_status').text(rs.travelpack.status);
            $('#travelpack_percentage').attr('style','width: '+rs.travelpack.percentage+'; height: 15px');
            $('#travelpack_percentage').html(rs.travelpack.percentage);
        },
        error: function (response) {

            if (response.status == 400) {
                text = ''
                $.each(response.responseJSON.errors, (index, item) => {
                    text += item + '<br>';
                })
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: text
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something errors. Please contact admin support!'
                });
            }
        }
    });
}
const showLoadingModal = function() {
    Swal.fire({
        title: 'Loading',
        icon: 'info',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false
    });
    Swal.showLoading();
}


$(document).ready(function () {
	$('#deployment_status').materialSelect();
    $('#table_status_search').materialSelect();

    var from_input = $('#startingDate').pickadate()
    from_picker = from_input.pickadate('picker')
    var to_input = $('#endingDate').pickadate(),
    to_picker = to_input.pickadate('picker')


    if (from_picker.get('value')) {
        to_picker.set('min', from_picker.get('select'))
    }
    if (to_picker.get('value')) {
        from_picker.set('max', to_picker.get('select'))
    }

    from_picker.on('set', function (event) {
        if (event.select) {
            to_picker.set('min', from_picker.get('select'))
        } else if ('clear' in event) {
            to_picker.set('min', false)
        }
    })
    to_picker.on('set', function (event) {
        if (event.select) {
            from_picker.set('max', to_picker.get('select'))
        } else if ('clear' in event) {
            from_picker.set('max', false)
        }
    })

    const table = $('#list_deployment').DataTable({
            "processing": true,
            "serverSide": true,
            'responsive': true,
            "order" : [],
            "ajax": {
                "url": "/ajax/deployment/main/list",
                "type": "POST",
                cache: false,
                data: function (d) {
                    d.status = $('#table_status_search').val()
                    d.startDate = $('#startingDate').val()
                    d.endDate = $('#endingDate').val()
                }
            },
            "columns": [
                {"data": 'candidate'},
                {"data": 'main_email'},
                {"data": 'fullname'},
                {"data": 'job_title'},
                {"data": 'deployment_date'},
                {"data": 'loe_file'},
                {"data": 'status'},
                {"data": null}
            ],
            "columnDefs": [
                {
                    "render": function (data, type, row) {
                        return '<a href="/personal/home/'+row.address_book_id+'">'+row.fullname+'<br>' + row.main_email+'</a>';
                    },
                    "targets": 0
                },
                {
                    "visible": false,
                    "targets": 1
                },
                {
                    "visible": false,
                    "targets": 2
                },
                {
                    "render": function (data, type, row) {
                        return row['job_code'] + ' - ' + data + '<br>' + row.principal_code;
                    },
                    "targets": 4
                },
                {
                    "render": function (data, type, row) {
                        return `<a href="/ab/show/${row.loe_file}" target="_blank">
                        <span class="badge bg-primary"><i class="fas fa-file-pdf"></i> Preview File</span>
                    </a><br><small>LOE Date : `+row.loe_date+`</small>`;
                    },
                    "targets": 5
				},
				{
					"render": function(data) {
						switch (data) {
							case 'pending':
									return `<span class="badge badge-secondary">${data}</span>`;
								break;
							case 'processing':
									return `<span class="badge badge-primary">${data}</span>`;
								break;
							case 'deployed':
									return `<span class="badge badge-success">${data}</span>`;
								break;
							case 'canceled':
								return `<span class="badge badge-warning">${data}</span>`;
								break;	
						
							default:
								return data;
								break;
						}
					},
					"targets": 6
				},
                {
                    "render": function (data, type, row) {

                        var html = `
						<button type="button" class="btn btn-sm btn-success waves-effect waves-light btn-detail-deployment" data-id="`+row.address_book_id+`" data-job-id="`+row.job_application_id+`" title="Detail Deployment"><i class="fas fa-info-circle"></i> Detail</button>`
						;

						var deployment_date = new Date(row.deployment_date);
						var now = new Date();

						if (deployment_date.getTime() < now.getTime()) {
							html += `<button type="button" class="btn btn-sm btn-primary waves-effect waves-light btn-edit-deployment" data-id="`+row.address_book_id+`" title="Edit Deployment"><i class="fas fa-edit"></i> Edit Status</button>`
							;
						}
                        return html;
                    },
                    "targets": -1, "searchable": false, "orderable": false
                }
            ],
        });

    $('#table_status_search, #startingDate, #endingDate').on('change', function () {
        table.ajax.reload()
    });


    $(document).on('click','.btn-detail-deployment',function() {
        $('.content_loading').show();
        $('.modal-content').hide();
        $('#deployment_tracker').modal('show');
        let id = $(this).data('id');
        let job_id = $(this).data('job-id');
        if(id=='' || id==null) {
            id=0;
        }
        if(job_id=='' || job_id==null) {
            job_id=0;
        }

        $('#address_book').val(id);
        $('#job_application').val(job_id);
        load_summary_tracker();
        load_deployment_visa();
        load_deployment_oktb();
        load_deployment_stcw();
        load_deployment_medical();
        load_deployment_vaccination();
        load_deployment_flight();
        load_deployment_police();
        load_deployment_seaman();
        load_deployment_travelpack();
        $('.content_loading').hide();
        $('.modal-content').show();
    });

    $('#deployment_tracker').on('hidden.bs.modal',function(e){
        table_deployment_visa.destroy();
        table_deployment_oktb.destroy();
        table_deployment_stcw.destroy();
        table_deployment_medical.destroy();
        table_deployment_vaccination.destroy();
        table_deployment_flight.destroy();
        table_deployment_police.destroy();
        table_deployment_seaman.destroy();
        table_deployment_travelpack.destroy();
	});
	
	$(document).on('click', '.btn-edit-deployment', function(e) {
		e.preventDefault();
		var modal = $('#deploymentModal');

		modal.modal('show');
		modal.find('input[name="address_book_id"]').val($(this).data('id'));

	});

	$('.update-deployment').on('click', function(e) {

		Swal.fire({
            title: 'Warning',
            text: "You want realy update this deployment to "+$('#deployment_status').val(),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then(function(result) {
			if (result.value) {
				var editDeployment = $('#editDeployment');

				var btn = $(this);

				btn.text('Updating....');
				btn.attr('disabled', true);

				$.ajax({
					type: 'POST',
                    url: '/ajax/deployment/main/update-deployment',
                    data: {
                        'address_book_id': editDeployment.find('input[name="address_book_id"]').val(),
                        'deployment_status': editDeployment.find('select[name="deployment_status"]').val()
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
						});
						
						table.ajax.reload();
					},
					error: function (response) {
						editDeployment.find('input[name="address_book_id"]').val('');
						editDeployment.find('input[name="deployment_status"]').val('');
						editDeployment.find('input[name="deployment_status"]').trigger('change');
                        
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: 'Something errors. Please contact admin support!'
						});
                    }
				});
			}
		});
	});

                // =============VISA Accion=============//
    $(document).on('click', '.visa-btn-send-notification', function(e) {
        e.preventDefault();
        let data = table_deployment_visa.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Notify CM to register visa application?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/visa/send-notification',
                    data: {
                        'address_book_id': data.address_book_id,
                        'country_code': data.country_code,
                        'visa_type': data.visa_type
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table_deployment_visa.ajax.reload();
                        load_summary_tracker();

                    },
                    error: function (response) {
                        $('input[name="address_book_id"]').val('');
                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        });
    });

    $(document).on('click', '.visa-btn-review-visa', function(e) {
        e.preventDefault();
        let data = table_deployment_visa.row(this.closest('tr')).data();
        showLoadingModal();

        $.post('/ajax/workflow/visa/file-preview/'+data.address_book_id, { visa_type: data.visa_type }, function(response) {
            Swal.close();
            $('#visaModal #visa_id').html(response.visa_id);
            $('#visaModal #visa_type').html(response.type);
            $('#visaModal #date_of_issue').html(response.from_date);
            $('#visaModal #expired_date').html(response.to_date);

            $('.visa-confirm-visa, .visa-reject-visa').attr('data-visa-id', response.visa_id);
            $('.visa-confirm-visa, .visa-reject-visa').attr('data-visa-type', response.type);

            $('#visaModal .file-preview').html('<img src="'+response.url+'" class="img img-fluid" />');
            $('#visaModal').modal('show');
        });
    });

    $(document).on('click', '.visa-btn-send-docs-application', function(e) {
        e.preventDefault();
        let data = table_deployment_visa.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Notify CM to complete docs application?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/visa/notif-docs-application',
                    data: {
                        'address_book_id': data.address_book_id,
                        'country_code': data.country_code,
                        'visa_type': data.visa_type
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table_deployment_visa.ajax.reload();
                        load_summary_tracker();
                    },
                    error: function (response) {
                        $('input[name="address_book_id"]').val('');
                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        });
	});

    $(document).on('click','.visa-confirm-visa',function()
		{
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing accept this visa document",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{
                    showLoadingModal();	
					$.ajax({
						url: "/ajax/workflow/visa/accept-visa/"+$(this).data('visa-id'),
                        type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
                        });
                        $('#visaModal').modal('hide');
                        table_deployment_visa.ajax.reload();
                        load_summary_tracker();
					})
					.fail(error => {
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: error.response.message
						});
					});
				}
			});
		});

		$(document).on('click','.visa-reject-visa',function()
		{
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing reject this visa document",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{	
                    showLoadingModal();
					$.ajax({
						url: "/ajax/workflow/visa/reject-visa/"+$(this).data('visa-id'),
                        type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
                        });
                        $('#visaModal').modal('hide');
                        table_deployment_visa.ajax.reload();
                        load_summary_tracker();
					})
					.fail(error => {
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: error.response.message
						});
					});
				}
			});
		});
    // ENd VIsa Action

                     // =============OKTB Accion=============//
    $(document).on('click','.oktb-btn-requirement-check', function () {
        var data = table_deployment_oktb.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Make sure all required documents are complete, continue to request file?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/oktb/request-file',
                    datatype: 'json',
                    data: {
                        'address_book_id': data.address_book_id,
                        'oktb_type': data.oktb_type
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table_deployment_oktb.ajax.reload(false);
                        load_summary_tracker();
                    },
                    error: function (response) {

                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        })
    })

    $(document).on('click', '.oktb-btn-review-file', function(e) {
        e.preventDefault();
        showLoadingModal();
        let data = table_deployment_oktb.row(this.closest('tr')).data();
        $.get('/ajax/workflow/oktb/file-preview/'+data.address_book_id, function(response) {
            Swal.close();
            $('#oktbModal #oktb_number').html(response.oktb_number);
            $('#oktbModal #oktb_type').html(response.oktb_type);
            $('#oktbModal #date_of_issue').html(response.date_of_issue);
            $('#oktbModal #valid_until').html(response.valid_until);

            $('.oktb-confirm-oktb, .oktb-reject-oktb').attr('data-oktb-id', response.oktb_number);

            $('#oktbModal .file-preview').html('<iframe src="/ab/show/'+response['filename']+'" width="100%" height="300px"></iframe>');
            $('#oktbModal').modal('show');
        });
    });

    $(document).on('click','.oktb-confirm-oktb', function(e) {
		e.preventDefault();
		swal.fire({
			title: 'Confirmation',
			text: 'Are you sure to confirm this oktb document?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Confirm it'
		}).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    method: 'POST',
                    url: '/ajax/workflow/oktb/confirm-oktb/'+$(this).data('oktb-id'),
                    success: function(response) {
                        Swal.fire({
                            type: 'success',
                            title: 'Document status edited!',
                            text: response.message
                        }).then(() => {
                            load_summary_tracker();
                            table_deployment_oktb.ajax.reload();
                            $('#oktbModal').modal('hide');
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            type: 'error',
                            title: 'Operation failed!',
                            text: 'Something went wrong!'
                        });
                    }
                })
            }
		});
	});

	$(document).on('click','.oktb-reject-oktb', function(e) {
		e.preventDefault();
		swal.fire({
			title: 'Confirmation',
			text: 'Are you sure to reject this oktb document?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Reject it'
		}).then((result) => {
			if (result.value) {
				showLoadingModal();
				$.ajax({
					method: 'POST',
					url: '/ajax/workflow/oktb/reject-oktb/'+$(this).data('oktb-id'),
					success: function(response) {
						Swal.fire({
							type: 'success',
							title: 'Document status edited!',
							text: response.message
						}).then(() => {
                            load_summary_tracker();
                            table_deployment_oktb.ajax.reload();
                            $('#oktbModal').modal('hide');
						});
					},
					error: function(error) {
						Swal.fire({
							type: 'error',
							title: 'Operation failed!',
							text: 'Something went wrong!'
						});
					}
				})
			}
		});
	});
    
                 // =============END OKTB Accion=============//

                //  ============STCW Action============//
    $(document).on('click','.stcw-btn-request-file', function () {
        var data = table_deployment_stcw.row(this.closest('tr')).data();
        console.log(data);
        Swal.fire({
            title: 'Send request STCW document to CM?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/stcw/request-file',
                    datatype: 'json',
                    data: {
                        'address_book_id': data.address_book_id
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        table_deployment_stcw.ajax.reload(false);
                        load_summary_tracker();

                    },
                    error: function (response) {

                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        })
    })

    $(document).on('click', '.stcw-btn-review-file', function() {
        let data = table_deployment_stcw.row(this.closest('tr')).data();
        showLoadingModal();
        
        $.get('/ajax/workflow/stcw/file-preview/'+data.address_book_id, function(response) {
            Swal.close();
            $('#stcwModal #institution').html(response[0].institution);
            $('#stcwModal #qualification').html(response[0].qualification);
            $('#stcwModal #certificate_date').html(response[0].certificate_date);
            $('#stcwModal #certificate_expiry').html(response[0].certificate_expiry);

            $('.stcw-confirm-stcw, .stcw-reject-stcw').attr('data-address-book-id', response[0].address_book_id);
            $('.stcw-confirm-stcw, .stcw-reject-stcw').attr('data-education-id', response[0].education_id);

            $('#stcwModal .file-preview').html('<img src="'+response[0].url+'" class="img img-fluid" />');
            $('#stcwModal').modal('show');
        });
    });

    $(document).on('click','.stcw-confirm-stcw', function(e) {
        e.preventDefault();
        swal.fire({
            title: 'Confirmation',
            text: 'Are you sure to confirm this stcw document?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Confirm it'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    method: 'POST',
                    url: '/ajax/workflow/stcw/confirmstcw/'+$(this).data('address-book-id')+'/'+$(this).data('education-id'),
                    success: function(response) {
                        Swal.fire({
                            type: 'success',
                            title: 'Document status edited!',
                            text: response.message
                        }).then(() => {
                            load_summary_tracker();
                            table_deployment_stcw.ajax.reload();
                            $('#stcwModal').modal('hide');
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            type: 'error',
                            title: 'Operation failed!',
                            text: 'Something went wrong!'
                        });
                    }
                })
            }
        });
    });

    $(document).on('click','.stcw-reject-stcw', function(e) {
        e.preventDefault();
        swal.fire({
            title: 'Confirmation',
            text: 'Are you sure to reject this stcw document?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Reject it'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    method: 'POST',
                    url: '/ajax/workflow/stcw/rejectstcw/'+$(this).data('address-book-id')+'/'+$(this).data('education-id'),
                    success: function(response) {
                        Swal.fire({
                            type: 'success',
                            title: 'Document status edited!',
                            text: response.message
                        }).then(() => {
                            load_summary_tracker();
                            table_deployment_stcw.ajax.reload();
                            $('#stcwModal').modal('hide');
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            type: 'error',
                            title: 'Operation failed!',
                            text: 'Something went wrong!'
                        });
                    }
                })
            }
        });
    });

    // ===============End Stcw Document=============/

    // ===============Start Medical Action=============/
    $(document).on('click', '.medical-btn-review-file', function() {
        let data = table_deployment_medical.row(this.closest('tr')).data();
        showLoadingModal();

        
        $.get('/ajax/workflow/medical/file-preview/'+data.address_book_id, function(response) {
            Swal.close();
            $('#medicalModal #institution').html(response[0].institution);
            $('#medicalModal #doctor').html(response[0].doctor);
            $('#medicalModal #certificate_number').html(response[0].certificate_number);
            $('#medicalModal #certificate_date').html(response[0].certificate_date);
            $('#medicalModal #certificate_expiry').html(response[0].certificate_expiry);

            $('.medical-confirm-medical, .medical-reject-medical').attr('data-medical-id', response[0].medical_id);

            $('#medicalModal .file-preview').html('<img src="'+response[0].url+'" class="img img-fluid" />');
            $('#medicalModal').modal('show');
        });
    });

    $(document).on('click', '.medical-btn-request-appointment-date', function(e) {
        e.preventDefault();
        let data = table_deployment_medical.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Send request medical appointment date to Candidate?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/medical/request-appointment-date',
                    data: {
                        'address_book_id': data.address_book_id,
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        load_summary_tracker();
                        table_deployment_medical.ajax.reload();

                    },
                    error: function (response) {
                        $('input[name="address_book_id"]').val('');
                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        });
	});

    $(document).on('click','.medical-btn-request-file', function () {
        var data = table_deployment_medical.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Send request medical file check to Candidate?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/medical/request-file',
                    data: {
                        'address_book_id': data.address_book_id
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        load_summary_tracker();
                        table_deployment_medical.ajax.reload(false);

                    },
                    error: function (response) {

                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        });
    });

    $('.medical-confirm-medical').click(function()
		{
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing accept this medical document",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{
                    showLoadingModal();	
					$.ajax({
						url: "/ajax/workflow/medical/accept-medical/"+$(this).data('medical-id'),
						type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
                        });
                        $('#medicalModal').modal('hide');
                        load_summary_tracker();
                        table_deployment_medical.ajax.reload();
					})
					.fail(error => {
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: error.response.message
						});
					});
				}
			});
		});

		$('.medical-reject-medical').click(function()
		{
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing reject this medical document",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{	
                    showLoadingModal();
					$.ajax({
						url: "/ajax/workflow/medical/reject-medical/"+$(this).data('medical-id'),
						type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
                        });
                        load_summary_tracker();
                        $('#medicalModal').modal('hide');
                        table_deployment_medical.ajax.reload();
					})
					.fail(error => {
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: error.response.message
						});
					});
				}
			});
		});
    // ===============End Medical Action=============/

    // ===============Vaccination Action=============/
    $(document).on('click', '.vaccination-btn-review-file', function() {
        let data = table_deployment_vaccination.row(this.closest('tr')).data();
        showLoadingModal();

        
        $.get('/ajax/workflow/vaccine/file-preview/'+data.address_book_id, function(response) {
            Swal.close();
            $('#vaccineModal #institution').html(response[0].institution);
            $('#vaccineModal #doctor').html(response[0].doctor);
            $('#vaccineModal #vaccination_number').html(response[0].vaccination_number);
            $('#vaccineModal #vaccination_date').html(response[0].vaccination_date);
            $('#vaccineModal #vaccination_expiry').html(response[0].vaccination_expiry);

            $('.vaccination-confirm-vaccine, .vaccination-reject-vaccine').attr('data-vaccine-id', response[0].vaccination_id);

            $('#vaccineModal .file-preview').html('<img src="'+response[0].url+'" class="img img-fluid" />');
            $('#vaccineModal').modal('show');
        });
    });

    $(document).on('click', '.vaccination-btn-request-appointment-date', function(e) {
        e.preventDefault();
        let data = table_deployment_vaccination.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Send request vaccination appointment date to Candidate?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/vaccine/request-appointment-date',
                    data: {
                        'address_book_id': data.address_book_id,
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        load_summary_tracker();
                        table_deployment_vaccination.ajax.reload();

                    },
                    error: function (response) {
                        $('input[name="address_book_id"]').val('');
                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        });
    });

    $(document).on('click','.vaccination-btn-request-file', function () {
        var data = table_deployment_vaccination.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Send request vaccination file check to Candidate?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/vaccine/request-file',
                    data: {
                        'address_book_id': data.address_book_id
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        load_summary_tracker();
                        table_deployment_vaccination.ajax.reload(false);

                    },
                    error: function (response) {

                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        });
    });

    $(document).on('click','.vaccination-confirm-vaccine',function()
		{
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing accept this vaccination document",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{	
                    showLoadingModal();
					$.ajax({
						url: "/ajax/workflow/vaccine/accept-vaccine/"+$(this).data('vaccine-id'),
						type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
                        });
                        load_summary_tracker();
                        $('#vaccineModal').modal('hide');
                        table_deployment_vaccination.ajax.reload();
					})
					.fail(error => {
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: error.response.message
						});
					});
				}
			});
		});

		$(document).on('click','.vaccination-reject-vaccine',function()
		{
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing reject this vaccination document",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{	
                    showLoadingModal();
					$.ajax({
						url: "/ajax/workflow/vaccine/reject-vaccine/"+$(this).data('vaccine-id'),
						type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
                        });
                        load_summary_tracker();
                        $('#vaccineModal').modal('hide');
                        table_deployment_vaccination.ajax.reload();
					})
					.fail(error => {
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: error.response.message
						});
					});
				}
			});
		});
    // ===============End Vaccination Action=============/

    // ===============Flight Action=============/
    $(document).on('click', '.flight-btn-review-file', function(e) {
        e.preventDefault();
        let data = table_deployment_flight.row(this.closest('tr')).data();
        showLoadingModal();
        
        $.get('/ajax/workflow/flight/file-preview/'+data.address_book_id, function(response) {
            Swal.close();
            $('#flightModal #flight_number').html(response.flight_number);
            $('#flightModal #departure_date').html(response.departure_date);

            $('.flight-confirm-flight, .flight-reject-flight').attr('data-flight-id', response.flight_number);

            $('#flightModal .file-preview').html('<img src="'+response.url+'" class="img img-fluid" />');
            $('#flightModal').modal('show');
        });
    });

    $(document).on('click','.flight-btn-request-file', function (e) {
        e.preventDefault();
        var data = table_deployment_flight.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Send request flight file check to Candidate?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/flight/request-file',
                    data: {
                        'address_book_id': data.address_book_id
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        load_summary_tracker();
                        table_deployment_flight.ajax.reload(false);

                    },
                    error: function (response) {

                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        });
    });

    $('.flight-confirm-flight').click(function(e)
		{
            e.preventDefault();
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing accept this flight document",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{
                    showLoadingModal();	
					$.ajax({
						url: "/ajax/workflow/flight/accept-flight/"+$(this).data('flight-id'),
						type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
                        });
                        load_summary_tracker();
                        $('#flightModal').modal('hide');
                        table_deployment_flight.ajax.reload();
					})
					.fail(error => {
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: error.response.message
						});
					});
				}
			});
		});

		$('.flight-reject-flight').click(function(e)
		{
            e.preventDefault();
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing reject this flight document",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{	
                    showLoadingModal();
					$.ajax({
						url: "/ajax/workflow/flight/reject-flight/"+$(this).data('flight-id'),
						type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
                        });
                        load_summary_tracker();
                        $('#flightModal').modal('hide');
                        table_deployment_flight.ajax.reload();
					})
					.fail(error => {
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: error.response.message
						});
					});
				}
			});
		});
    // ===============End Flight Action=============/

    // ===============Police Action=============/
    $(document).on('click', '.police-btn-review-file', function(e) {
        e.preventDefault();
        let data = table_deployment_police.row(this.closest('tr')).data();
        showLoadingModal();
        
        $.get('/ajax/workflow/police/file-preview/'+data.address_book_id, function(response) {
            Swal.close();
            $('#policeModal #place_issued').html(response[0].place_issued);
            $('#policeModal #active').html(response[0].active);
            $('#policeModal #police_date').html(response[0].from_date);
            $('#policeModal #police_expiry').html(response[0].to_date);

            $('.police-confirm-police, .police-reject-police').attr('data-police-id', response[0].police_id);

            $('#policeModal .file-preview').html('<img src="'+response[0].url+'" class="img img-fluid" />');
            $('#policeModal').modal('show');
        });
    });


    $(document).on('click','.police-btn-request-file', function () {
        var data = table_deployment_police.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Send request police check to CM?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/police/request-file',
                    data: {
                        'address_book_id': data.address_book_id
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        load_summary_tracker();
                        table_deployment_police.ajax.reload(false);

                    },
                    error: function (response) {

                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        })
    });

    $('.police-confirm-police').click(function(e)
		{
            e.preventDefault();
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing accept this police document",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{
                    showLoadingModal();	
					$.ajax({
						url: "/ajax/workflow/police/accept-police/"+$(this).data('police-id'),
						type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
                        });
                        load_summary_tracker();
                        $('#policeModal').modal('hide');
                        table_deployment_police.ajax.reload();
					})
					.fail(error => {
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: error.response.message
						});
					});
				}
			});
		});

		$('.police-reject-police').click(function(e)
		{
            e.preventDefault();
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing reject this police document",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{	
                    showLoadingModal();
					$.ajax({
						url: "/ajax/workflow/police/reject-police/"+$(this).data('police-id'),
						type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
                        });
                        load_summary_tracker();
                        $('#policeModal').modal('hide');
                        table_deployment_police.ajax.reload();
					})
					.fail(error => {
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: error.response.message
						});
					});
				}
			});
		});
    // ===============End Police Action=============/

    // ===============Seaman Action=============/
    $(document).on('click','.seaman-btn-request-file', function () {
        var data = table_deployment_seaman.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Send request seaman to Candidate?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/seaman/request-file',
                    datatype: 'json',
                    data: {
                        'address_book_id': data.address_book_id
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        load_summary_tracker();
                        table_deployment_seaman.ajax.reload(false);

                    },
                    error: function (response) {

                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        })
    });

    $(document).on('click', '.seaman-btn-review-file', function() {
        let data = table_deployment_seaman.row(this.closest('tr')).data();

        showLoadingModal();
        $.get('/ajax/workflow/seaman/file-preview/'+data.address_book_id, function(response) {
            Swal.close();
            $('#seamanModal #code').html(response.seaman_id);
            $('#seamanModal #fullname').html(response.full_name || (response.given_names + ' ' + response.family_name));
            $('#seamanModal #nationality').html(response.nationality);
            $('#seamanModal #date').html(response.from_date);
            $('#seamanModal #to_date').html(response.to_date);
            $('#seamanModal .file-preview').html('<img src="'+response.url+'" class="img img-fluid" />');

            $('.seaman_accept_seaman, .seaman_reject_seaman').attr('data-seaman-id', response.seaman_id);
            $('#seamanModal').modal('show');

        });
    });

    $('.seaman_accept_seaman').click(function()
		{
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing accept this seaman book",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{	
                    showLoadingModal();
					$.ajax({
						url: "/ajax/workflow/seaman/accept-seaman/"+$(this).data('seaman-id'),
						type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
                        });
                        load_summary_tracker();
                        table_deployment_seaman.ajax.reload();
                        $('#seamanModal').modal('hide');
					})
					.fail(error => {
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: error.response.message
						});
					});
				}
			});
		});

		$('.seaman_reject_seaman').click(function()
		{
			Swal.fire({
				title: 'Are you sure?',
				text: "Please make sure to check the data before changing reject this seaman book",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			  }).then((result) => {
				if (result.value)
				{	
                    showLoadingModal();
					$.ajax({
						url: "/ajax/workflow/seaman/reject-seaman/"+$(this).data('seaman-id'),
						type: 'POST',
						cache: false,
						timeout: 10000
					})
					.done(response => {
						Swal.fire({
						  type: 'success',
						  title: 'Information',
						  text: response.message
                        });
                        load_summary_tracker();
                        table_deployment_seaman.ajax.reload();
                        $('#seamanModal').modal('hide');
					})
					.fail(error => {
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: error.response.message
						});
					});
				}
			});
		});
    // ===============End Seaman Action=============/
    
    // ===============Travelpack Action=============/
    $('#invoice_expected_on').pickadate({
        format: 'yyyy-mm-dd'
    });

    $(document).on('click','.btn-generate-invoice', function () {
        var data = table_deployment_travelpack.row(this.closest('tr')).data();
        $('#generate-invoice-modal').modal('show')
        $('#generate-invoice-form').find('input[name=job_application_id]').val(data.job_application_id);
        $('#generate-invoice-form').find('input[name=address_book_id]').val(data.address_book_id);
    })

    $('#generate-invoice-form').on('submit', function (e) {
        e.preventDefault()

        Swal.fire({
            title: 'Save Generate Invoice',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/travelpack/generate_invoice',
                    data: $(this).serialize(),
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        load_summary_tracker();
                        table_deployment_travelpack.ajax.reload(false);
                        $('#generate-invoice-modal').modal('hide')

                    },
                    error: function (response) {

                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        })
    });

    var $uploadCrop;
    //detect viewport and compare with inserted attribute data
    const b_width = $('#invoice_croppie').data('invoice-width');
    const b_height = $('#invoice_croppie').data('invoice-height');
    const v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/1.4;
    const v_height = b_height/b_width*v_width;

    //choose appropriate width and height based on device
    const crop_width = (b_width>v_width) ? v_width : b_width; 
    const crop_height = (b_height>v_height) ? v_height : b_height; 

    function popupResult(result)
    {
        var html;
        if (result.html)
        {
            html = result.html;
        }

        if (result.src)
        {
            html = '<img src="' + result.src + '" class="img-fluid" />';
        }
        swal.fire({
            title: 'Generate Invoice',
            html: html,
            allowOutsideClick: true
        });
        setTimeout(function()
        {
            $('.sweet-alert').css('margin', function() 
            {
                const top = -1 * ($(this).height() / 2),
                    left = -1 * ($(this).width() / 2);

                return top + 'px 0 0 ' + left + 'px';
            });
        }, 1);

        $('#generate-invoice-modal button[type="submit"]').prop("disabled",false);
    }

    function readFile(input)
    {
        if (input.files && input.files[0])
        {
            var reader = new FileReader();

            reader.onload = function (e)
            {
                $uploadCrop.croppie('bind', 
                {
                    url: e.target.result
                });

                $('#invoice_croppie_wrap').show();
            }

            reader.readAsDataURL(input.files[0]);

        } else {
            swal("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    $uploadCrop = $('#invoice_croppie').croppie({
        viewport: {
			width: crop_width,
			height: crop_height
		},
		boundary: {
			width: crop_width*1.1,
			height: crop_height*1.1
		},
		enableExif: true
    });


    $('#invoice_input').on('change', function ()
    {
        const file_choosen = $('#invoice_input').val();

		//check if image is choosen before start cropping
        if (file_choosen !== "")
        {
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
            $('#generate-invoice-modal button[type="submit"]').prop("disabled",true);
            readFile(this);
            $('#invoice_croppie').show();
            $('#invoice_result').show();
            $('#update_crop').hide();
		}
    });

    $('#invoice_result').on('click', function (ev) 
    {
        const file_choosen = $('#invoice_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
            $uploadCrop.croppie('result',
            {
                type: 'canvas',
                size: 'original'
            }).then(function (resp) 
            {
                resizeImage(resp, b_width, b_height).then((resp) => {
                    popupResult({
                        src: resp
                    });

                    $('#invoice_base64').val(resp);
                    $('#invoice_img').prop('src',resp);
                    $('#invoice_img').parent().show();
                    $('#invoice_croppie_wrap').hide();
                    $('#invoice_result').hide();
                    document.getElementById("invoice_image").scrollIntoView();
                    $('#update_crop').show();
                })
            });
        }else{
			Swal.fire({
				icon: 'warning',
				text: 'Please choose an image first',
			});
		}
    });
    $('#update_crop').on('click',function(){
		$('#invoice_croppie_wrap').show();
		$('#invoice_result').show();
		$(this).hide();
		document.getElementById("invoice_croppie_wrap").scrollIntoView();
    })
    
    // cropie for pay image
    var $pay_uploadCrop;
    //detect viewport and compare with inserted attribute data
    const pay_b_width = $('#pay_croppie').data('pay-width');
    const pay_b_height = $('#pay_croppie').data('pay-height');
    const pay_v_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)/1.4;
    const pay_v_height = pay_b_height/pay_b_width*pay_v_width;

    //choose appropriate width and height based on device
    const pay_crop_width = (pay_b_width>pay_v_width) ? pay_v_width : pay_b_width; 
    const pay_crop_height = (pay_b_height>pay_v_height) ? pay_v_height : pay_b_height; 

    function pay_popupResult(result)
    {
        var html;
        if (result.html)
        {
            html = result.html;
        }

        if (result.src)
        {
            html = '<img src="' + result.src + '" class="img-fluid" />';
        }
        swal.fire({
            title: 'Pay Invoice',
            html: html,
            allowOutsideClick: true
        });
        setTimeout(function()
        {
            $('.sweet-alert').css('margin', function() 
            {
                const top = -1 * ($(this).height() / 2),
                    left = -1 * ($(this).width() / 2);

                return top + 'px 0 0 ' + left + 'px';
            });
        }, 1);

        $('#pay-invoice-modal button[type="submit"]').prop("disabled",false);
    }

    function pay_readFile(input)
    {
        if (input.files && input.files[0])
        {
            var reader = new FileReader();

            reader.onload = function (e)
            {
                $pay_uploadCrop.croppie('bind', 
                {
                    url: e.target.result
                });

                $('#pay_croppie_wrap').show();
            }

            reader.readAsDataURL(input.files[0]);

        } else {
            swal("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    $pay_uploadCrop = $('#pay_croppie').croppie({
        viewport: {
			width: pay_crop_width,
			height: pay_crop_height
		},
		boundary: {
			width: pay_crop_width*1.1,
			height: pay_crop_height*1.1
		},
		enableExif: true
    });


    $('#pay_input').on('change', function ()
    {
        const file_choosen = $('#pay_input').val();

		//check if image is choosen before start cropping
        if (file_choosen !== "")
        {
			Swal.fire({
				icon: 'warning',
				text: 'Please adjust and crop the image before submitting the form',
			});
            $('#pay-invoice-modal button[type="submit"]').prop("disabled",true);
            pay_readFile(this);
            $('#pay_croppie').show();
            $('#pay_result').show();
            $('#pay_update_crop').hide();
		}
    });

    $('#pay_result').on('click', function (ev) 
    {
        const file_choosen = $('#pay_input').val();

		//check if image is choosen before start cropping
		if (file_choosen !== "")
		{
            $pay_uploadCrop.croppie('result',
            {
                type: 'canvas',
                size: 'original'
            }).then(function (resp) 
            {
                resizeImage(resp, pay_b_width, pay_b_height).then((resp) => {
                    pay_popupResult({
                        src: resp
                    });

                    $('#pay_base64').val(resp);
                    $('#pay_img').prop('src',resp);
                    $('#pay_img').parent().show();
                    $('#pay_croppie_wrap').hide();
                    $('#pay_result').hide();
                    document.getElementById("pay_image").scrollIntoView();
                    $('#pay_update_crop').show();
                })
            });
        }else{
			Swal.fire({
				icon: 'warning',
				text: 'Please choose an image first',
			});
		}
    });
    $('#pay_update_crop').on('click',function(){
		$('#pay_croppie_wrap').show();
		$('#pay_result').show();
		$(this).hide();
		document.getElementById("pay_croppie_wrap").scrollIntoView();
	})

    $(document).on('click','.btn-pay-invoice', function () {
        var data = table_deployment_travelpack.row(this.closest('tr')).data();
        $('#pay-invoice-modal').modal('show');
        $('#pay-invoice-form').find('input[name=job_application_id]').val(data.job_application_id);
        $('#pay-invoice-form').find('input[name=address_book_id]').val(data.address_book_id)
    })

    $('#pay-invoice-form').on('submit', function (e) {
        e.preventDefault()

        Swal.fire({
            title: 'Update Invoice',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/travelpack/pay_invoice',
                    data: $(this).serialize(),
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                        load_summary_tracker();
                        table_deployment_travelpack.ajax.reload(false);
                        $('#pay-invoice-modal').modal('hide')
                    },
                    error: function (response) {

                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        })
    });

    $(document).on('click','.btn-pay-resend', function() {
        var data = table_deployment_travelpack.row(this.closest('tr')).data();
        Swal.fire({
            title: 'Resend Invoice?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Send!'
        }).then((result) => {
            if (result.value) {
                showLoadingModal();
                $.ajax({
                    type: 'POST',
                    url: '/ajax/workflow/travelpack/resend-invoice',
                    data: {
                        address_book_id:data.address_book_id,
                        job_application_id:data.job_application_id
                    },
                    success: rs => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification!',
                            text: rs.message
                        });
                    },
                    error: function (response) {

                        if (response.status == 400) {
                            text = ''
                            $.each(response.responseJSON.errors, (index, item) => {
                                text += item + '<br>';
                            })
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: text
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something errors. Please contact admin support!'
                            });
                        }
                    }
                });
            }

        })
    })
    // ===============ENd Travelpack Action=============/

    $('#visaModal, #oktbModal, #stcwModal, #medicalModal, #vaccineModal, #flightModal, #policeModal, #seamanModal, #generate-invoice-modal,#pay-invoice-modal').on('hidden.bs.modal',function(e){
        $('body').addClass('modal-open');
        $('body').attr('style','padding-right: 15px;');
    });
    
});