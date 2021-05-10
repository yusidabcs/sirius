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

    .table-inverse {
        color: #fff;
        background-color: #292b2c;
    }

    .table-inverse th,
    .table-inverse td,
    .table-inverse thead th {
        border-color: #fff;
    }

    .table-inverse.table-bordered {
        border: 0;
    }

    .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
        -ms-overflow-style: -ms-autohiding-scrollbar;
    }

    .table-responsive.table-bordered {
        border: 0;
    }
</style>
<div >
    <div class="" style="margin: 30px;
    padding: 50px;
    border: 1px solid #eee;
    background: #fefefe;">


        <p>
            Hello <?php echo $to_name ?>
        </p>
        <p>Here the list of our candidate that need further security clearance. </p>
        <hr>

        <center><h3 class="text-center">Request Clearance List</h3></center>
        <table class="table table-bordered table-striped table-responsive-sm">
            <thead>
                <tr>
                    <th>No</th>
                    <th align="left">Candidate</th>
                    <th align="left">Link to passport file</th>
                </tr>
            </thead>
            <?php
            foreach ($group_check as $key => $value) {
                ?>
                <tr>
                    <td align="left">
                        <?php echo $key + 1 ?>
                    </td>
                    <td align="left">
                        <?php echo $value['candidate'] ?><br>
                        <?php echo $value['candidate_email'] ?>
                    </td>
                    <td align="left">
                        <a href="<?php echo HTTP_TYPE.SITE_WWW."/secure_file/show/".$value['hash'] ?>"><?php echo HTTP_TYPE.SITE_WWW."/secure_file/show/".$value['hash'] ?></a>
                    </td>
                </tr>

            <?php } ?>
        </table>

        <p>Please send us the request clearance feedback maximal in 7 days. </p>
        <p>Thank you.</p>


    </div>

</div>