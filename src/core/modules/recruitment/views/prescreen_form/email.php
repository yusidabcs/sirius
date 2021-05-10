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
        border: 1px solid #eceeef;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #eceeef;
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
    <p>
        Hello <?php echo $applicant['full_name'] ?>,<br><br>
        Here the result of you pre screen interview with Approval of your Local Partner/Licensed Partner (LP). <br>
        Please read carefully the submitted answer. <br>
        Please accept the result if correct and please request for revision if any wrong answer.
    </p>
    <hr>
    <div class="" style="margin: 30px;
    padding: 50px;
    border: 1px solid #eee;
    background: #fefefe;">

        <center><h3 >Candidate Detail</h3></center>
        <table class="table">
            <thead class="info lighten-2">
            <tr>
                <td width="60%">Approval Local Partner/Licensed Partner (LP)'s Name:</td>
                <td width="40%" id="header_lp_name"><?php echo $applicant['partner_name'] ?></td>
            </tr>
            <tr>
                <td width="60%">Approval Local Partner/Licensed Partner (LP)'s Pre-screener:</td>
                <td width="40%" id="header_lp_prescreener"><?php echo $applicant['prescreener_full_name'] ?></td>
            </tr>
            <tr>
                <td width="60%">Pre-screening Interview Date (D-M-Y):</td>
                <td width="40%" id="header_lp_interview_date"><?php echo date('d M Y'); ?></td>
            </tr>
            <tr>
                <td width="60%">Applicant's Full Name (as shown on Passport):</td>
                <td width="40%" id="header_full_name">
                    <?php echo $applicant['full_name'] ?><br>
                    <?php echo $applicant['email'] ?>
                </td>
            </tr>
            <tr>
                <td width="60%">Position Applying For:</td>
                <td width="40%" id="header_position"><?php echo $applicant['job_position'] ?></td>
            </tr>
            </thead>
        </table>
    </div>
    <hr>
    <div class="" style="margin: 30px;
    padding: 50px;
    border: 1px solid #eee;
    background: #fefefe;">

        <center><h3 class="text-center">Pre Screen Result</h3></center>
        <table class="table ">
            <tr>
                <td width="60%">Question</td>
                <td>Answer</td>
            </tr>
            <?php
            foreach ($questions as $key => $value) {
                ?>
                <tr>
                    <td style="<?php echo ($value['type'] == 'heading') ? 'background-color:#eee' : ''?>">
                        <?php if ($value['type'] == 'heading') { ?>
                            <h5><?php echo $value['question'] ?></h5>
                        <?php } else { ?>
                            <?php if ($value['parent_id'] != 0) { ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php } ?>
                            <?php echo $value['question'] ?>
                        <?php } ?>
                    </td>
                    <td style="<?php echo ($value['type'] == 'heading') ? 'background-color:#eee' : ''?>">
                        <?php
                        switch ($value['type']) {
                            case "tf":
                                echo $answers[$value['question_id']]['answer'];
                                if($answers[$value['question_id']]['text']!=null || $answers[$value['question_id']]['text']!='') {
                                echo " (<i>".$answers[$value['question_id']]['text']."</i>)";    
                                }
                                ?>
                                <?php
                                break;

                            case "sa":
                                ?>

                                <p class="form-control"
                                          ><?php echo(isset($answers[$value['question_id']]) ? $answers[$value['question_id']]['text'] : '') ?></p>


                                <?php
                                break;

                            default:
                                ?>


                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php if ($value['childs']) { ?>

                        <?php foreach ($value['childs'] as $child) { ?>

                            <tr>
                                <td style="padding-left: 30px">
                                    <?php if ($child['type'] == 'heading') { ?>
                                        <h5><?php echo $child['question'] ?></h5>
                                    <?php } else { ?>
                                        <?php echo $child['sequence'] ?>. <?php echo $child['question'] ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php
                                    switch ($child['type']) {
                                        case "tf":

                                            echo $answers[$child['question_id']]['answer'];
                                            if($answers[$child['question_id']]['text']!=null || $answers[$child['question_id']]['text']!='') {
                                                echo " ( 
                                                    <i>".$answers[$child['question_id']]['text']."</i> )";    
                                                }
                                            ?>


                                            <?php
                                            break;

                                        case "sa":
                                            ?>
                                            <p class="form-control"
                                                      ><?php echo(isset($answers[$child['question_id']]) ? $answers[$child['question_id']]['text'] : '') ?></p>


                                            <?php
                                            break;

                                        default:
                                            ?>


                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                <?php } ?>



            <?php } ?>
        </table>
        <hr>
        <p style="text-align: center"> <i>I agree and take responsibility that the data I input is in accordance with reality.</i>
        <br>
        <br>
        <br>
            <a href="<?php echo $accept_link?>" style="border:1px solid green;padding: 10px 15px;background: #81cc91;color: white;text-decoration: none;">Agree & Accept Result</a>
        </p>

        <p style="text-align: center">Or</p>
        <p style="text-align: center"> <a href="<?php echo $revision_link?>" style="border: 1px solid orangered;padding: 10px 15px;background: orange;color: white;text-decoration: none;">Revision & Retake Pre-Interview Screen</a></p>

    </div>

</div>