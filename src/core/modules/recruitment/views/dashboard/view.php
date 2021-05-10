<div class="card">
    <div class="card-header gradient-card-header blue-gradient">
        <h4 class="text-white text-center"><?php echo $term_page_header ?></h4>
    </div>

    <div class="card-body">

        <div class="row">
            <div class="col-md-12">
                <canvas id="myChart" height="100"></canvas>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h5>Workflow Trackers</h5>
            </div>
            <div class="col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#personalverification" role="tab" aria-controls="home" aria-selected="true">Personal Verification</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#personalreference" role="tab" aria-controls="profile" aria-selected="false">Personal Reference</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#profesionalreference" role="tab" aria-controls="contact" aria-selected="false">Profesional Reference</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#english" role="tab" aria-controls="contact" aria-selected="false">English</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#permiumservice" role="tab" aria-controls="contact" aria-selected="false">Premium Service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#interview" role="tab" aria-controls="contact" aria-selected="false">Interview</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="personalverification" role="tabpanel" aria-labelledby="home-tab">
                    <div class="card mt-2">
                        <!-- Card content -->
                        <div class="card-body">
                            <h5 class="card-title">Personal Verification Tracker</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="border">
                                        <div class="list-group list-group-flush">
                                            <a class="list-group-item list-group-item-action waves-effect " id="all_level" data-level="1" >
                                                All Level
                                                <span class="badge badge-success badge-pill pull-right">
                                                    0
                                                </span>
                                            </a>
                                            <a class="list-group-item list-group-item-action waves-effect " id="normal_level" data-level="1" >
                                                Normal
                                                <span class="badge badge-success badge-pill pull-right">
                                                    0
                                                </span>
                                            </a>
                                            <a class="list-group-item list-group-item-action waves-effect" id="soft_level" data-level="2">
                                                Soft Warning
                                                <span class="badge badge-warning badge-pill pull-right">0</span>
                                            </a>
                                            <a class="list-group-item list-group-item-action waves-effect" id="hard_level" data-level="3">
                                                Hard Warning
                                                <span class="badge badge-warning badge-pill pull-right">0</span>
                                            </a>
                                            <a class="list-group-item list-group-item-action waves-effect" id="deadline_level" data-level="4">
                                                Deadline
                                                <span class="badge badge-danger badge-pill pull-right">0</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                <form action="#" class="form-inline verification_form_filter mb-3">
                                    <div class="form-group">
                                        <select class="form-control" name="verification_filter">
                                            <option value="">Filter by milestone</option>
                                            <option value="request_verification">Request Verification</option>
                                            <option value="process">Process</option>
                                            <option value="accepted">Accepted</option>
                                            <option value="denied">Denined</option>
                                        </select>
                                    </div>
                                </form>
                                    <div class="table-responsive">
                                        <table id="recruitment_tracker" class="table w-100">
                                            <thead>
                                            <tr>
                                                <td>Name</td>
                                                <td>Status</td>
                                                <td>Number Given Name</td>
                                                <td>Entity Family Name </td>
                                                <td>Main Email</td>
                                                <td>Level</td>
                                                <td>Action</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="text-center">
                                <a href="<?php echo $verification_link ?>"
                                class="btn btn-sm btn-primary <?php echo ($unverified_count > 0) ? '' : 'disabled' ?>"><?php echo $term_view_details ?></a>
                            </div>

                        </div>
                    </div>
                    </div>
                    <div class="tab-pane fade" id="personalreference" role="tabpanel" aria-labelledby="profile-tab">
                        <!-- Personal Reference -->
                        <div class="card mt-2">
                            <!-- Card content -->
                            <div class="card-body">
                                <h5 class="card-title">Personal Reference Tracker</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="border">
                                            <div class="list-group list-group-flush">
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="0" id="personal_all_level">
                                                    All Level
                                                    <span class="badge badge-success badge-pill pull-right" >
                                                        0
                                                    </span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="1" id="personal_normal_level">
                                                    Normal
                                                    <span class="badge badge-success badge-pill pull-right" >
                                                        0
                                                    </span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="2" id="personal_soft_level">
                                                    Soft Warning
                                                    <span class="badge badge-warning badge-pill pull-right">0</span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="3" id="personal_hard_level">
                                                    Hard Warning
                                                    <span class="badge badge-warning badge-pill pull-right">0</span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="4" id="personal_deadline_level">
                                                    Deadline
                                                    <span class="badge badge-danger badge-pill pull-right">0</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                    <form action="#" class="form-inline personal_form_filter mb-3">
                                        <div class="form-group">
                                            <select class="form-control" name="personal_filter">
                                                <option value="">Filter by milestone</option>
                                                <option value="request">Request</option>
                                                <option value="confirmation">Confirmation</option>
                                                <option value="review">Review</option>
                                                <option value="accepted">Accepted</option>
                                                <option value="rejected">Rejected</option>
                                            </select>
                                        </div>
                                    </form>
                                        <div class="table-responsive">
                                            <table id="personal_reference_tracker" class="table w-100">
                                                <thead>
                                                <tr>
                                                    <td>Name</td>
                                                    <td>Status</td>
                                                    <td>Number Given Name</td>
                                                    <td>Entity Family Name </td>
                                                    <td>Main Email</td>
                                                    <td>Level</td>
                                                    <td>Action</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="text-center">
                                    <a href="/recruitments/applicant"
                                    class="btn btn-sm btn-primary <?php echo ($unverified_count > 0) ? '' : 'disabled' ?>"><?php echo $term_view_details ?></a>
                                </div>

                            </div>
                        </div>
                        <!-- End Personal Reference -->
                    </div>
                    <div class="tab-pane fade" id="profesionalreference" role="tabpanel" aria-labelledby="contact-tab">
                        <!-- Profesional Reference Reference -->
                        <div class="card mt-2">
                            <!-- Card content -->
                            <div class="card-body">
                                <h5 class="card-title">Profesional Reference Tracker</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="border">
                                            <div class="list-group list-group-flush">
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="0" id="profesional_all_level">
                                                    All Level
                                                    <span class="badge badge-success badge-pill pull-right" >
                                                        0
                                                    </span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="1" id="profesional_normal_level">
                                                    Normal
                                                    <span class="badge badge-success badge-pill pull-right" >
                                                        0
                                                    </span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="2" id="profesional_soft_level">
                                                    Soft Warning
                                                    <span class="badge badge-warning badge-pill pull-right">0</span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="3" id="profesional_hard_level">
                                                    Hard Warning
                                                    <span class="badge badge-warning badge-pill pull-right">0</span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="4" id="profesional_deadline_level">
                                                    Deadline
                                                    <span class="badge badge-danger badge-pill pull-right">0</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <form action="#" class="form-inline profesional_form_filter mb-3">
                                            <div class="form-group">
                                                <select class="form-control" name="profesional_filter">
                                                    <option value="">Filter by milestone</option>
                                                    <option value="request">Request</option>
                                                    <option value="confirmation">Confirmation</option>
                                                    <option value="review">Review</option>
                                                    <option value="accepted">Accepted</option>
                                                    <option value="rejected">Rejected</option>
                                                </select>
                                            </div>
                                        </form>
                                        <div class="table-responsive">
                                            <table id="profesional_reference_tracker" class="table w-100">
                                                <thead>
                                                <tr>
                                                    <td>Name</td>
                                                    <td>Status</td>
                                                    <td>Number Given Name</td>
                                                    <td>Entity Family Name </td>
                                                    <td>Main Email</td>
                                                    <td>Level</td>
                                                    <td>Action</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="text-center">
                                    <a href="/recruitments/applicant"
                                    class="btn btn-sm btn-primary <?php echo ($unverified_count > 0) ? '' : 'disabled' ?>"><?php echo $term_view_details ?></a>
                                </div>

                            </div>
                        </div>
                        <!-- End Profesional Reference -->
                    </div>
                    <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="contact-tab">
                        <!-- English Test -->
                        <div class="card mt-2">
                            <!-- Card content -->
                            <div class="card-body">
                                <h5 class="card-title">English Test Tracker</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="border">
                                            <div class="list-group list-group-flush">
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="0" id="english_all_level">
                                                    All Level
                                                    <span class="badge badge-success badge-pill pull-right" >
                                                        0
                                                    </span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="1" id="english_normal_level">
                                                    Normal
                                                    <span class="badge badge-success badge-pill pull-right" >
                                                        0
                                                    </span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="2" id="english_soft_level">
                                                    Soft Warning
                                                    <span class="badge badge-warning badge-pill pull-right">0</span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="3" id="english_hard_level">
                                                    Hard Warning
                                                    <span class="badge badge-warning badge-pill pull-right">0</span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="4" id="english_deadline_level">
                                                    Deadline
                                                    <span class="badge badge-danger badge-pill pull-right">0</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <form action="#" class="form-inline english_form_filter mb-3">
                                            <div class="form-group">
                                                <select class="form-control" name="english_filter">
                                                    <option value="">Filter by milestone</option>
                                                    <option value="request_file">Request File</option>
                                                    <option value="review_file">Review File</option>
                                                    <option value="accepted">Accepted</option>
                                                    <option value="rejected">Rejected</option>
                                                </select>
                                            </div>
                                        </form>
                                        <div class="table-responsive">
                                            <table id="english_test_tracker" class="table w-100">
                                                <thead>
                                                <tr>
                                                    <td>Name</td>
                                                    <td>Status</td>
                                                    <td>Number Given Name</td>
                                                    <td>Entity Family Name </td>
                                                    <td>Main Email</td>
                                                    <td>Level</td>
                                                    <td>Action</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="text-center">
                                    <a href="/recruitments/applicant"
                                    class="btn btn-sm btn-primary <?php echo ($unverified_count > 0) ? '' : 'disabled' ?>"><?php echo $term_view_details ?></a>
                                </div>

                            </div>
                        </div>
                        <!-- End English Test -->
                    </div>
                    <div class="tab-pane fade" id="permiumservice" role="tabpanel" aria-labelledby="contact-tab">
                        <!-- Premium Servie -->
                        <div class="card mt-2">
                            <!-- Card content -->
                            <div class="card-body">
                                <h5 class="card-title">Premium Service Tracker</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="border">
                                            <div class="list-group list-group-flush">
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="0" id="premium_all_level">
                                                    All Level
                                                    <span class="badge badge-success badge-pill pull-right" >
                                                        0
                                                    </span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="1" id="premium_normal_level">
                                                    Normal
                                                    <span class="badge badge-success badge-pill pull-right" >
                                                        0
                                                    </span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="2" id="premium_soft_level">
                                                    Soft Warning
                                                    <span class="badge badge-warning badge-pill pull-right">0</span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="3" id="premium_hard_level">
                                                    Hard Warning
                                                    <span class="badge badge-warning badge-pill pull-right">0</span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="4" id="premium_deadline_level">
                                                    Deadline
                                                    <span class="badge badge-danger badge-pill pull-right">0</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <form action="#" class="form-inline premium_form_filter mb-3">
                                            <div class="form-group">
                                                <select class="form-control" name="premium_filter">
                                                    <option value="">Filter by milestone</option>
                                                    <option value="request_psf">Request PSF</option>
                                                    <option value="candidate_verification">Condidate Verification</option>
                                                    <option value="confirm_psf">Confirm PSF</option>
                                                    <option value="accepted">Accepted</option>
                                                </select>
                                            </div>
                                        </form>
                                        <div class="table-responsive">
                                            <table id="premium_service_tracker" class="table w-100">
                                                <thead>
                                                <tr>
                                                    <td>Name</td>
                                                    <td>Status</td>
                                                    <td>Number Given Name</td>
                                                    <td>Entity Family Name </td>
                                                    <td>Main Email</td>
                                                    <td>Level</td>
                                                    <td>Action</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="text-center">
                                    <a href="/recruitments/applicant"
                                    class="btn btn-sm btn-primary <?php echo ($unverified_count > 0) ? '' : 'disabled' ?>"><?php echo $term_view_details ?></a>
                                </div>

                            </div>
                        </div>
                        <!-- End Premium Service -->
                    </div>
                    <div class="tab-pane fade" id="interview" role="tabpanel" aria-labelledby="contact-tab">
                        <!-- Premium Servie -->
                        <div class="card mt-2">
                            <!-- Card content -->
                            <div class="card-body">
                                <h5 class="card-title">Interview Tracker</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="border">
                                            <div class="list-group list-group-flush">
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="0" id="interview_all_level">
                                                    All Level
                                                    <span class="badge badge-success badge-pill pull-right" >
                                                        0
                                                    </span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="1" id="interview_normal_level">
                                                    Normal
                                                    <span class="badge badge-success badge-pill pull-right" >
                                                        0
                                                    </span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="2" id="interview_soft_level">
                                                    Soft Warning
                                                    <span class="badge badge-warning badge-pill pull-right">0</span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="3" id="interview_hard_level">
                                                    Hard Warning
                                                    <span class="badge badge-warning badge-pill pull-right">0</span>
                                                </a>
                                                <a class="list-group-item list-group-item-action waves-effect" data-level="4" id="interview_deadline_level">
                                                    Deadline
                                                    <span class="badge badge-danger badge-pill pull-right">0</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <form action="#" class="form-inline premium_form_filter mb-3">
                                            <div class="form-group">
                                                <select class="form-control" name="interview_filter">
                                                    <option value="">Filter by milestone</option>
                                                    <option value="request_schedule">Request Schedule</option>
                                                    <option value="accepted">Accepted</option>
                                                    <option value="rejected">Rejected</option>
                                                </select>
                                            </div>
                                        </form>
                                        <div class="table-responsive">
                                            <table id="interview_tracker" class="table w-100">
                                                <thead>
                                                <tr>
                                                    <td>Name</td>
                                                    <td>Status</td>
                                                    <td>Number Given Name</td>
                                                    <td>Entity Family Name </td>
                                                    <td>Main Email</td>
                                                    <td>Level</td>
                                                    <td>Action</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="text-center">
                                    <a href="/recruitments/applicant"
                                    class="btn btn-sm btn-primary <?php echo ($unverified_count > 0) ? '' : 'disabled' ?>"><?php echo $term_view_details ?></a>
                                </div>

                            </div>
                        </div>
                        <!-- End Premium Service -->
                    </div>
                </div>
            </div>
            
        </div>

        <div class="row mt-5">
            <div class="col-md-6">
                <div class="card">
                    <!-- Card content -->
                    <div class="card-body">

                        <!-- Title -->
                        <h4 class="card-title">Total Candidate</h4>
                        <!-- Text -->
                        <div class="d-flex justify-content-between">
                            <p class="display-4 align-self-end"><?php echo $total_candidate ?></p>
                        </div>

                    </div>
                    <div class="card-footer">
                        <a href="<?php echo $base_url . '/candidate' ?>"
                           class="btn btn-sm btn-primary btn-block  <?php echo ($total_candidate > 0) ? '' : 'disabled' ?>"><?php echo $term_view_details ?></a>
                    </div>

                </div>
                <br>

                <div class="card">
                    <!-- Card content -->
                    <div class="card-body">

                        <!-- Title -->
                        <h4 class="card-title"><?php echo $term_total_apply ?></h4>
                        <!-- Text -->
                        <div class="d-flex justify-content-between">
                            <p class="display-4 align-self-end"><?php echo $applyjob_count ?></p>
                        </div>

                    </div>

                    <div class="card-footer">
                        <a href="<?php echo $base_url . '/applicant/applied' ?>"
                           class="btn btn-sm btn-primary btn-block  <?php echo ($applyjob_count > 0) ? '' : 'disabled' ?>"><?php echo $term_view_details ?></a>
                    </div>

                </div>
                <br>

                <div class="card">
                    <!-- Card content -->
                    <div class="card-body">

                        <!-- Title -->
                        <h4 class="card-title"><?php echo $term_total_interview ?></h4>
                        <!-- Text -->
                        <div class="d-flex justify-content-between">
                            <p class="display-4 align-self-end"><?php echo $total_interview_job ?></p>
                        </div>

                    </div>
                    <div class="card-footer">
                        <a href="<?php echo $base_url . '/applicant/interview' ?>"
                           class="btn btn-sm btn-primary btn-block  <?php echo ($total_interview_job > 0) ? '' : 'disabled' ?>"><?php echo $term_view_details ?></a>
                    </div>

                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <!-- Card content -->
                    <div class="card-body">

                        <!-- Title -->
                        <h4 class="card-title"><?php echo $term_accepted_job ?></h4>
                        <!-- Text -->
                        <div class="d-flex justify-content-between">
                            <p class="display-4 align-self-end"><?php echo $total_accepted_job ?></p>
                        </div>

                    </div>

                    <div class="card-footer">
                        <a href="<?php echo $base_url . '/applicant' ?>"
                           class="btn btn-sm btn-primary btn-block  <?php echo ($total_accepted_job > 0) ? '' : 'disabled' ?>"><?php echo $term_view_details ?></a>
                    </div>

                </div>
                <br>
                <div class="card">
                    <!-- Card content -->
                    <div class="card-body">

                        <!-- Title -->
                        <h4 class="card-title"><?php echo $term_rejected ?></h4>
                        <!-- Text -->
                        <div class="d-flex justify-content-between">
                            <p class="display-4 align-self-end"><?php echo $rejected_count ?></p>
                        </div>

                    </div>

                    <div class="card-footer">
                        <a href="<?php echo $rejected_link ?>"
                           class="btn btn-sm btn-primary btn-block  <?php echo ($rejected_count > 0) ? '' : 'disabled' ?>"><?php echo $term_view_details ?></a>
                    </div>

                </div>
                <br>
            </div>

        </div>

    </div>
</div>