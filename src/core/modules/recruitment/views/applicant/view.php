<div class="card">

    <div class="card-header gradient-card-header blue-gradient">
        <h4 class="text-white text-center"><?php echo $term_page_header ?></h4>
    </div>
    <div class="card-body">
        <div id="table_search" class="container">
            <div class="row">
                <div class="col-md-3 md-form">
                    <select id="table_partner_search" name="table_partner_search" class="mdb-select"
                            searchable="Search">
                        <option value=""><?php echo $term_table_select_please; ?></option>
                        <?php

                        $html = '';
                        foreach ($partners as $partner) {
                            $html .= '<option value="' . $partner['id'] . '" >' . $partner['name'] . '</option>';
                        }
                        echo $html;
                        ?>
                    </select>
                    <label for="table_partner_search"><?php echo $term_table_partner_filter ?></label>
                </div>
                <div class="col-md-3 md-form">
                    <select id="table_country_search" name="table_country_search" class="mdb-select"
                            searchable="Search">
                        <option value=""><?php echo $term_table_select_please; ?></option>
                        <?php
                        $html = '';
                        foreach ($countryCodes as $id => $country) {
                            $html .= '<option value="' . $id . '" >' . $country . '</option>';
                        }
                        echo $html;
                        ?>
                    </select>
                    <label for="table_country_search"><?php echo $term_table_country_filter ?></label>
                </div>

                <div class="col-md-3 md-form">
                    <select id="table_register_method" class="mdb-select">
                        <option value=""><?php echo $term_table_register_method_all ?></option>
                        <option value="0">From Public</option>
                        <option value="-1">From Admin Inputed</option>
                    </select>
                    <label for="table_register_method"><?php echo $term_table_register_method_filter ?></label>
                </div>
            </div>
        </div>
        <div class="table-responsive">

            <table id="recruitments" class="table table-sm table-striped table-bordered "
                   cellspacing="0" width="100%" data-prescreen-url="<?php echo $prescreen_base_url?>">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Job</th>
                    <th>Country</th>
                    <th>Partner</th>
                    <th>Job Status</th>
                    <th>Premium Status</th>
                    <th>Applied On</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
        <!-- Summary Modal -->
        <div class="modal fade bd-example-modal-lg" id="pre-interview-checklist" tabindex="-1" role="dialog"
             aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg modal-notify modal-info">
                <div class="text-center" id="loading_modal_preInterview">
                    <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                </div>
                <div class="modal-content" id="content_preInterview">
                    <div class="modal-header border-bottom-0 text-center">
                        <h4 class="modal-title w-100 text-white" id="myModalLabel">Pre Interview Checklist</h4>
                    </div>
                    <div class="modal-body table-responsive">
                    <div id="validator_div" class="alert alert-warning err_warning" role="alert">
                        <h5 class="alert-heading font-weight-bold">Please complete all the error before you can process the applicant!</h5>
                        <div id="validator">
                        </div>
                    </div>
                        <!-- <div id="validator_div" class="border p-3">
                            <table id="validator" class="table table-sm">
                            </table>
                        </div> -->

                            <!-- start card passport -->
                            <div class="card">
                                <h4 class="card-header h4 d-flex justify-content-center gradient-card-header peach-gradient">
                                        Passport
                                </h4>
                                <div class="card-body">
                                    <div id="passport_not_exist" class="not-showing">
                                        <div class="alert alert-warning" role="alert">
                                        <i class="fas fa-exclamation-triangle"></i> Warning, please input passport details
                                        </div>
                                    </div>
                                    <table class="table table-sm table-responsive-sm" id="passport_exist">
                                    <tr>
                                        <td colspan="2" id="passport_document">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Name on pasport:</td>
                                        <td id="passport_name"></td>
                                    </tr>
                                    <tr>
                                        <td>Passport valid until:</td>
                                        <td id="passport_valid"></td>
                                    </tr>
                                    <tr>
                                        <td>Job applied for:</td>
                                        <td id="applied_job"></td>
                                    </tr>
                                    <tr>
                                        <td>Skype ID:</td>
                                        <td id="skype"></td>
                                    </tr>
                                    <tr>
                                        <td>Email Address:</td>
                                        <td id="email"></td>
                                    </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- end card passport -->
                            <!-- start card checklist -->
                            <div class="card mt-4">
                                <h4 class="card-header h4 d-flex justify-content-center gradient-card-header peach-gradient">
                                    Complete Speedy Global Checklist
                                </h4>
                                <div class="card-body">
                                    <table class="table table-sm table-responsive-sm">
                                        <tr>
                                            <td id="checklist"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- end card checklist -->
                            <!-- start card ctract -->
                            <div class="card mt-4">
                                <h4 class="card-header h4 d-flex justify-content-center gradient-card-header peach-gradient">
                                    Online Application Detail
                                </h4>
                                <div class="card-body">
                                    <table class="table table-sm table-responsive-sm">
                                        <tr>
                                            <td>Send Online Application access request: </td>
                                            <td id="send_ctrack_on">
                                                <input type="text" value="" class="form-control"> <i class="fa fa-pencil-square"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Online Application <b>accessed</b> by candidate confirmed: </td>
                                            <td id="ctrack_accessed_on"></td>
                                        </tr>
                                        <tr>
                                            <td>Candidate <b>completed</b> initial Online Application: </td>
                                            <td id="ctrack_completed_on"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- end card ctract -->
                            <!-- start card cv/foto -->
                            <div class="card mt-4">
                                <h4 class="card-header h4 d-flex justify-content-center gradient-card-header peach-gradient">
                                    Profile
                                </h4>
                                <div class="card-body">
                                    <table class="table table-sm table-responsive-sm">
                                        <tr>
                                            <td colspan="2">
                                                <button id="show-cv" class="btn btn-info btn-sm" data-id=""> <i class="fa fa-eye"></i> See CV</button>
                                            </td>
                                        </tr>
                                        <tr id="full_body_photo">
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- end card cv/foto -->
                             <!-- start card STCW -->
                             <div class="card mt-4">
                                <h4 class="card-header h4 d-flex justify-content-center gradient-card-header peach-gradient">
                                    STCW Document
                                </h4>
                                <div class="card-body">
                                    <table id="stcw_document" class="table table-sm table-responsive-sm table-bordered">
                                    </table>
                                </div>
                            </div>
                            <!-- end card STCW -->
                            <!-- start card reference -->
                            <div class="card mt-4">
                                <h4 class="card-header h4 d-flex justify-content-center gradient-card-header peach-gradient">
                                    Reference Check
                                </h4>
                                <div class="card-body">
                                    <table id="reference" class="table table-sm table-responsive-sm table-bordered">
                                    </table>
                                    <p class="text-warning text-center">
                                        <strong>Important note: </strong> The personal & profesional reference should not came from main family member.
                                    </p>
                                </div>
                            </div>
                            <!-- end card reference -->
                            <!-- start card english test -->
                            <div class="card mt-4">
                                <h4 class="card-header h4 d-flex justify-content-center gradient-card-header peach-gradient">
                                    English Test
                                </h4>
                                <div class="card-body">
                                    <table id="english_test" class="table table-sm table-responsive-sm table-bordered">
                                    </table>
                                </div>
                            </div>
                            <!-- end card english test -->
                            <!-- start card premium service -->
                            <div class="card mt-4">
                                <h4 class="card-header h4 d-flex justify-content-center gradient-card-header peach-gradient">
                                    Premium Service
                                </h4>
                                <div class="card-body">
                                    <div id="tr_premium_status"></div>
                                </div>
                            </div>
                            <!-- end card premium service -->
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button class="btn btn-info not-showing" id="accept_btn" data-id="">Accept & Agree Pre-Interview Checklist Are Correct</button>
                        <button class="btn btn-warning" id="cancel_btn" data-id="">Cancel & Reject The Application</button>
                    </div>

                </div>
            </div>
        </div>


        <!-- CV Modal -->
        <div class="modal fade" id="show_cv_modal" tabindex="-1" role="dialog" aria-labelledby="myCVmodalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form id="show_cv">
                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h4 class="modal-title w-100 font-weight-bold"><?php echo $term_main_show_cv_title ?></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="container modal-body mb-3">
                            <div id="table_cv" class="mx-auto">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End of CV Modal -->

     <!-- Premium Service Modal -->
     <div class="modal fade" id="show_premium_service_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="PremiumServiceModal"><?php echo $term_premium_title ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between">
                        <div class="mb-3">
                            Status : <span class="p-2 badge badge-primary text-capitalize" id="premium-status">No Data</span>
                        </div>
                        <div class="mb-3">
                            Verified : <span class="p-2 badge badge-primary text-capitalize" id="premium-verified">No Data</span>
                        </div>
                    </div>

                    <div id="premium-info" class="alert alert-primary not-showing" role="alert"></div>
                    <div class="d-flex justify-content-between" id="premium_file">
                        <a class="btn btn-primary" id="premium_file_show_btn" target="_blank"><i class="fa fa-eye"></i> <?php echo $term_premium_show_button?></a>
                        <a class="btn btn-success" id="premium_file_download_btn" target="_blank"><i class="fa fa-download"></i> <?php echo $term_premium_download_button?></a>
                    </div>
                    
                    <div class="mt-3 text-left" id="premium-send-form">    
                        <form id="premium_service">
                            <input type="hidden" id="address_book_id" name="address_book_id" />
                            <input type="hidden" id="job_application_id" name="job_application_id" />
                            <input type="hidden" id="status" name="status" value="pending" />
                            
                            <div class="form-group">
                                <label for="type"><?php echo $term_premium_type?></label>
                                <select id="type" name="type" class="form-control mb-3">
                                    <option value="early" selected>Early</option>
                                    <option value="late" disabled>Late</option>
                                </select>
                            </div>
                            <div class="md-form">
                                <input type="text" id="premium_email" name="premium_email" class="form-control" readonly/>
                                <label for="email"><?php echo $term_premium_email?></label>
                            </div>
                            <div class="md-form">
                                <input type="text" id="full_amount" name="full_amount" class="form-control" readonly value="<?php echo PREMIUM_SERVICE_EARLY_RATE?>"/>
                                <label for="full_amount"><?php echo $term_premium_full_amount?></label>
                            </div>
                            <div class="md-form">
                                <input type="text" id="amount" name="amount" class="form-control" value="<?php echo PREMIUM_SERVICE_EARLY_RATE?>"/>
                                <label for="amount"><?php echo $term_premium_amount?></label>
                            </div>

                            <?php if($can_by_pass == 1): ?>
                            <div class="md-form">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="by_pass" name="by_pass" type="checkbox" value="1">
                                    <label class="form-check-label" for="by_pass"><?php echo $term_by_pass ?></label>
                                </div>
                            </div>
                            <div class="md-form acceptance_form d-none">
                                <select name="user_acceptance" id="user_acceptance" class="mdb-select">
                                    <option value="accept">Accept</option>
                                    <option value="reject">Reject</option>
                                </select>
                                <label for="user_acceptance"><?php echo $term_acceptance_select ?></label>
                            </div>
                            <?php endif ?>
                        </form>
                        <button class="btn btn-info btn-rounded btn-block z-depth-0 my-4 waves-effect" id="offer_premium_service_btn" type="button"><?php echo $term_premium_button?></button>
                    </div>

                    <div id="premium-confirm-placeholder">
                        <div class="mt-3 text-left d-flex justify-content-between" >
                            <button class="flex-fill btn btn-success btn-rounded z-depth-0 my-4 waves-effect" id="premium_service_confirm_btn" type="button"><?php echo $term_premium_confirm_button?></button>
                            <button class="flex-fill btn btn-warning btn-rounded  my-4 waves-effect" id="premium_service_resend_btn" type="button"><?php echo $term_premium_resend_button?></button>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Premium Service Modal -->

        <!-- Online Application Modal -->
        <div class="modal fade" id="ctrac_modal" tabindex="-1" role="dialog" aria-labelledby="myCVmodalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">

                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h4 class="modal-title w-100 font-weight-bold">Online Application Status Update</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="container modal-body mb-3">
                            <form id="ctrac_form">
                            <div class="form-input">
                                <label for="">Send Online Application access request:</label>
                                <input type="text" name="send_ctrac_on" class="form-control datepicker" required>
                            </div>
                            <div class="form-input">
                                <label for="">Online Application accessed by candidata confirmed:</label>
                                <input type="text" name="ctrac_accessed_on" class="form-control datepicker" required>
                            </div>
                            <div class="form-input">
                                <label for="">Candidate completed initial Online Application:</label>
                                <input type="text" name="ctrac_completed_on" class="form-control datepicker" required>
                            </div>
                            <br>
                            <button class="btn btn-block btn-info" type="submit">Update</button>

                            </form>
                        </div>
                    </div>
            </div>
        </div>
        <!-- End of CV Modal -->
</div>

<!--Prescreen Modal-->
    <div class="modal fade" id="pre-screening-interview" tabindex="-1" role="dialog"
         aria-labelledby="pre-screening-interviewLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-notify modal-info">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 text-center">
                    <h4 class="modal-title w-100 text-white" id="myModalLabel"><?php echo $term_prescreen_form_header ?>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <div id="pre-screen-modal-body"></div>
                    <div class="row">
                        <div class="col-md-12 table-choose-principal">
                            <div class="pt-3 pb-3 align-items-center border-top peach-gradient text-white p-3">
                                <div class="pl-3">
                                    <h5>Principal</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 offset-lg-3 pt-4 text-center">
                                    <div class="form-group">
                                        <label class="control-label">Please choose Principal</label>
                                        <select name="principal" id="principal" class="form-control">
                                            <option value="">Select Principal</option>
                                            <?php foreach($principals as $principal): ?>
                                                <option value="<?php echo $principal['code'] ?>"><?php echo $principal['code'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- <table class="table table-choose-principal">
                                <tr>
                                    <td width="60%">Principal</td>
                                    <td>
                                        <select name="principal" id="principal" class="form-control">
                                            <option value="">Select Principal</option>
                                            <?php foreach($principals as $principal): ?>
                                                <option value="<?php echo $principal['code'] ?>"><?php echo $principal['code'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </td>
                                </tr>
                            </table> -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-info" id="update-interview-btn"> Accept candidate to interview!</button>
                </div>
            </div>
        </div>
    </div>
<!--End Prescreen Modal-->

    <!--Prescreen Modal-->
    <div class="modal fade" id="interview_scheduling_modal" tabindex="-1" role="dialog"
         aria-labelledby="pre-screening-interviewLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-info">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 text-center">
                    <h4 class="modal-title w-100 text-white" id="myModalLabel"><?php echo $term_scheduling_header ?>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body " id="pre-screen-modal-body">
                    <input type="hidden" id="job_application_id">
                    <label for="schedule_id">Select Schedule</label>
                    <select id="schedule_id" class="form-control" required>
                    </select>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-info" id="submit_interview_scheduling"> Schedule For Interview </button>
                </div>
            </div>
        </div>
    </div>
    <!--End Prescreen Modal-->