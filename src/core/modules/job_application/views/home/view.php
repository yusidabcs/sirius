<div class="card">

    <div class="card-header gradient-card-header blue-gradient">
        <h4 class="text-white text-center"><?php echo ($mode == 'recruitment')? $term_admin_page_header : $term_user_page_header ?></h4>
    </div>

    <div class="card-body">
        <?php if(!$jobs) {?>
            <p><?php echo ($mode == 'recruitment')? $term_admin_no_application : $term_user_no_application ?></p>
        <?php }else { ?>
        <table class="table" id="my_jobs">
            <thead>
            <tr>
                <td>Job Detail</td>
                <td>Apply Date</td>
                <td>Last Updated</td>
                <td>Status</td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($jobs as $key => $item) {?>
                <tr>
                    <td>
                        <?php echo $item['job_title']?> <br>
                        (<?php echo $item['job_speedy_code']?>)
                    </td>
                    <td>
                        <?php echo date('d M Y', strtotime($item['created_on']))?>
                    </td>
                    <td>
                        <?php echo date('d M Y', strtotime($item['modified_on']))?>
                    </td>
                    <td><?php echo ucfirst($item['status'])?></td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm detail-job-application" data-id="<?php echo $item['job_application_id'] ?>"><i class="fa fa-eye"></i> Progress</a>
                        <?php if($item['request_offer_letter']): ?>
                            <a href="#" data-job_application_id="<?php echo $item['job_application_id'] ?>" class="btn btn-info btn-sm btn-upload-ol">  <i class="fa fa-file-pdf"></i> Upload Offer Letter</a>
                        <?php endif ?>
                        <div class="modal fade" id="progress-job-<?php echo $item['job_application_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Application Summary</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body modal-lg table-responsive">
                                        <table class="table">
                                            <tr>
                                                <td>Job Title</td>
                                                <td><?php echo $item['job_speedy_code'].' - '.$item['job_title'] ?></td>
                                            </tr>
                                            <tr>
                                                <td>Personal Reference Check</td>
                                                <td id="td_personal_ref"><?php echo isset($item['personal_reference_check']['status']) ? $item['personal_reference_check']['status'] : '-' ?></td>
                                            </tr>
                                            <tr>
                                                <td>Work Reference Check</td>
                                                <td id="td_work_ref"><?php echo isset($item['personal_reference_check']['status']) ? $item['personal_reference_check']['status'] : '-' ?> </td>
                                            </tr>
                                            <tr>
                                                <td>Premium Service</td>
                                                <td id="td_premium"><?php
                                                    if(count($item['premium_service']) > 0){
                                                        echo '<table>';
                                                        echo '<tr><td>Status: </td><td>'.$item['premium_service']['status'].'</td></tr>';
                                                        echo '<tr><td>Sending on: </td><td>'.$item['premium_service']['sending_on'].'</td></tr>';
                                                        echo '<tr><td>Accepted on: </td><td>'.$item['premium_service']['sending_on'].'</td></tr>';
                                                        echo '<tr><td>Rejected on: </td><td>'.$item['premium_service']['sending_on'].'</td></tr>';
                                                        echo '</table>';
                                                    }else{
                                                        echo 'Pending';
                                                    }?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
            <?php }?>
            </tbody>
        </table>

        <?php } ?>
    </div>
</div>

<div class="modal fade" id="upload-ol-modal" tabindex="-1" role="dialog" aria-labelledby="request-ol-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="upload_ol_form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="">Upload Offer Letter File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="job_application_id" name="job_application_id">
                    <div class="file-field">
                        <div class="btn btn-primary btn-sm float-left">
                            <span>Choose file</span>
                            <input type="file" name="offer_letter_file" accept="image/x-png,image/gif,image/jpeg,application/pdf" required>
                        </div>
                        <div class="file-path-wrapper">

                            <input class="file-path validate" type="text" placeholder="Upload your file" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Save</button>
                </div>
            </div><!-- modal-content -->
        </form>
    </div>
</div>