<div class="container">
    <div class="card">
        <div class="card-header gradient-card-header blue-gradient d-flex justify-content-between">
            <h4 class="text-white"><?php echo $term_header ?></h4>
            <div>
                <a href="<?php echo $base_url?>/location" class="btn btn-sm btn-success float-right"> <i class="fa fa-arrow-left"></i> Back</a>
                <?php if($location['status'] == 1) {?>
                    <a href="<?php echo $base_url?>/detail_location/<?php echo $location['interview_location_id']?>" data-id="<?php echo $location['interview_location_id']?>" class="btn btn-sm btn-warning float-right " id="btn-close-interview"> <i class="fa fa-warning"></i> Close Interview</a>
                <?php }?>
            </div>
        </div>
        <div class="card-body w-auto">
            <div class="container">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                           aria-selected="true">Schedule</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                           aria-selected="false">Summary</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <!--Grid column-->
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td>Organizer</td>
                                        <td>:</td>
                                        <td><?php echo $location['organizer']?></td>
                                    </tr>
                                    <tr>
                                        <td>Location</td>
                                        <td>:</td>
                                        <td>
                                            <?php echo $location['country']?> - <?php echo $location['subcountry']?> <br>
                                            <?php echo $location['address']?> <br>
                                            <a class="btn btn-link" href="<?php echo $location['google_map']?>" target="_blank">Google Map</a>
                                        </td>
                                    </tr>


                                </table>
                            </div>
                            <!--Grid column-->
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td>Start</td>
                                        <td>:</td>
                                        <td>
                                            <?php
                                            echo date('d M Y H:i',strtotime($location['start_on']));
                                            ?>
                                            <input type="hidden" id="status_interview" value="<?php echo date('Y-m-d',strtotime($location['start_on'])) == date('Y-m-d') ? 1 : 0; ?>">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Finish</td>
                                        <td>:</td>
                                        <td><?php echo date('d M Y H:i',strtotime($location['finish_on']))?></td>
                                    </tr>

                                    <tr>
                                        <td>Status</td>
                                        <td>:</td>
                                        <td><?php echo $location['status'] ? '<label class="badge badge-success">Active</label>' : '<label class="badge badge-warning">Not Active</label>'?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td>Total Hire</td>
                                        <td>:</td>
                                        <td><?php echo $summary['total_hire']?></td>
                                    </tr>
                                    <tr>
                                        <td>Total Not Hire</td>
                                        <td>:</td>
                                        <td><?php echo $summary['total_not_hire']?></td>
                                    </tr>
                                    <tr>
                                        <td>Total Withdraw</td>
                                        <td>:</td>
                                        <td><?php echo $summary['total_withdraw']?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td>Summary By Job </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php foreach($summary['job_groups'] as $job) {?>
                                        <tr>
                                            <td><?php echo $job['job_speedy_code']?> - <?php echo $job['job_title']?></td>
                                            <td>:</td>
                                            <td><?php echo $job['total']?></td>
                                        </tr>
                                    <?php }?>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
                <h4>List Candidate</h4>
                <hr>
                <input type="hidden" id="interview_location_id" value="<?php echo $location['interview_location_id']?>">
                <table class="table" id="detail_schedule_candidate" data-url="<?php echo $base_url?>">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Job</th>
                        <th>Interview Status</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>

                <div class="modal fade" id="interview_result_modal" tabindex="-1" role="dialog" aria-labelledby="interview_result_modal"
                     aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"
                                        id="exampleModalLongTitle"><?php echo $term_interview_result ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <center><h5>Question & Answer</h5></center>
                                    <table class="table" id="interview_answer">

                                    </table>
                                    <center><h5>Interview Result</h5></center>
                                    <table class="table" id="interview_result">

                                    </table>
                                </div>
                                <div class="modal-footer">
                                </div>
                            </div><!-- modal-content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>