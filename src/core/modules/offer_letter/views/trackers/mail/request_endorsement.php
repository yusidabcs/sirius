<style>
    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
    }

    .table th,
    .table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #eceeef;
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #eceeef;
    }

    .table tbody + tbody {
        border-top: 2px solid #eceeef;
    }

    .table .table {
        background-color: #fff;
    }

    .table-sm th,
    .table-sm td {
        padding: 0.3rem;
    }

    .table-bordered {
        border: 1px solid #c9c9c9;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #c9c9c9;
    }

    .table-bordered thead th,
    .table-bordered thead td {
        border-bottom-width: 2px;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-active,
    .table-active > th,
    .table-active > td {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-hover .table-active:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-hover .table-active:hover > td,
    .table-hover .table-active:hover > th {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-success,
    .table-success > th,
    .table-success > td {
        background-color: #dff0d8;
    }

    .table-hover .table-success:hover {
        background-color: #d0e9c6;
    }

    .table-hover .table-success:hover > td,
    .table-hover .table-success:hover > th {
        background-color: #d0e9c6;
    }

    .table-info,
    .table-info > th,
    .table-info > td {
        background-color: #d9edf7;
    }

    .table-hover .table-info:hover {
        background-color: #c4e3f3;
    }

    .table-hover .table-info:hover > td,
    .table-hover .table-info:hover > th {
        background-color: #c4e3f3;
    }

    .table-warning,
    .table-warning > th,
    .table-warning > td {
        background-color: #fcf8e3;
    }

    .table-hover .table-warning:hover {
        background-color: #faf2cc;
    }

    .table-hover .table-warning:hover > td,
    .table-hover .table-warning:hover > th {
        background-color: #faf2cc;
    }

    .table-danger,
    .table-danger > th,
    .table-danger > td {
        background-color: #f2dede;
    }

    .table-hover .table-danger:hover {
        background-color: #ebcccc;
    }

    .table-hover .table-danger:hover > td,
    .table-hover .table-danger:hover > th {
        background-color: #ebcccc;
    }

    .thead-inverse th {
        color: #fff;
        background-color: #292b2c;
    }

    .thead-default th {
        color: #464a4c;
        background-color: #eceeef;
    }
</style>
<p>
    Hello Mr/Mrs. <?php echo $to_name ?>,
</p>
<p>
    Please kindly see the below mentioned applicants' CVs and Interview notes for your review and approval.
</p><p>
    Please kindly click on "HERE" to open their files
</p>
<table class="table table-bordered table-responsive-sm">
    <tr>
        <td>No</td>
        <td>Name</td>
        <td>Job</td>
        <td>CV</td>
        <td>Interview Note</td>
    </tr>
    <?php foreach($endorser as $index => $item) {?>
    <tr>
        <td><?php echo ($index + 1)?></td>
        <td><?php echo $item['candidate']?></td>
        <td><?php echo $item['job_code']?> - <?php echo $item['job_title']?></td>
        <td><a href="<?php echo HTTP_TYPE.SITE_WWW.'/job-application/cv/'.$item['address_book_id'] ?>">HERE</a> </td>
        <td><a href="<?php echo HTTP_TYPE.SITE_WWW.'/job-application/interview_note/'.$item['job_application_id'] ?>">HERE</a> </td>
    </tr>
    <?php } ?>
</table>

<p>
    Best Regards
</p>