<div class="card">

    <div class="card-header gradient-card-header blue-gradient">
        <h4 class="text-white text-center"><?php echo $term_page_header ?></h4>
    </div>
    <div class="card-body">
        <div id="table_search" class="container">
            <div class="row">
                <div class="col-md-4 md-form">
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
                <div class="col-md-4 md-form">
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

                <div class="col-md-4 md-form">
                    <select id="table_register_method" class="mdb-select">
                        <option value=""><?php echo $term_table_register_method_all ?></option>
                        <option value="0">From Public</option>
                        <option value="-1">From Admin Inputed</option>
                    </select>
                    <label for="table_register_method"><?php echo $term_table_register_method_filter ?></label>
                </div>
                <!--<div class="col-md-4 md-form">
                    <select id="table_status_search" class="mdb-select">
                        <option value=""><?php /*echo $term_table_select_all */?></option>
                        <?php
/*                        $html = '';
                        foreach($list_status as $key => $status)
                        {
                            $html.= '<option value="'.$status.'" '.($active_status == $status ? 'selected' : '').'>'.ucwords($status).'</option>';
                        }
                        echo $html;
                        */?>
                    </select>
                    <label for="table_status_search"><?php /*echo $term_table_status_filter */?></label>
                </div>-->
            </div>
        </div>
        <div class="table-responsive">

            <table id="recruitments" class="table table-sm table-striped table-bordered "
                   cellspacing="0" width="100%" data-prescreen-url="<?php echo $prescreen_base_url?>">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Job</th>
                    <th>Country</th>
                    <th>Partner</th>
                    <th>Date Interview</th>
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
                <div class="modal-content">
                    <div class="modal-header border-bottom-0 text-center">
                        <h4 class="modal-title w-100 text-white" id="myModalLabel">Pre Interview Checklist</h4>
                    </div>
                    <div class="modal-body table-responsive">
                        <div id="validator_div" class="border p-3">
                            <table id="validator" class="table table-sm">
                            </table>
                        </div>

                        <table class="table table-sm ">
                            <tbody id="passport_not_exist" class="not-showing">
                            <tr>
                                <td colspan="4" class="text-warning text-center">Warning, please input passport
                                    details
                                </td>
                            </tr>
                            </tbody>

                            <tbody id="passport_exist">
                                <tr>
                                    <td>
                                        <h5>Passport</h5>
                                    </td>
                                    <td id="passport_document">

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
                            </tbody>

                            <tbody>
                                <tr>
                                    <td id="" colspan="2" class="pt-4">
                                        <h5>Complete Speedy Global Checklist</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="checklist" colspan="2" class="p-3"></td>
                                </tr>
                            </tbody>

                            <tbody >
                            <tr>
                                <td colspan="2">
                                    <h5>Ctrac Detail</h5>
                                <table class="table">
                                    <tr>
                                        <td>Send Ctrac access request: </td>
                                        <td id="send_ctrack_on">
                                            <input type="text" value="" class="form-control"> <i class="fa fa-pencil-square"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ctrac <strong>accessed</strong> by candidate confirmed: </td>
                                        <td id="ctrack_accessed_on"></td>
                                    </tr>
                                    <tr>
                                        <td>Candidate <strong>completed</strong> initial Ctrac: </td>
                                        <td id="ctrack_completed_on"></td>
                                    </tr>
                                </table>
                                <td>
                            </tr>
                            </tbody>

                            <tr>
                                <td><h5>Latest CV:</h5></td>
                                <td id="general_infomation">
                                    <button id="show-cv" class="btn btn-info btn-link btn-sm" data-id=""> <i class="fa fa-eye"></i> CV</button>
                                </td>
                            </tr>
                            <tr id="full_body_photo">
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h5>STCW Document</h5>
                                    <table id="stcw_document" class="table table-bordered table-sm"></table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h5>Reference Check</h5>
                                    <table id="reference" class="table table-bordered table-sm"></table>
                                </td>
                            </tr>
                            </tbody>
                            <tr class="mb-2">
                                <td colspan="2">
                                    <h5>English Test</h5>
                                    <table id="english_test" class="table table-bordered table-sm"></table>
                                </td>
                            </tr>

                            <tr>
                                <td class="w-50">Speedy Global Premium Service Agreement:</td>
                                <td id="tr_premium_status" class="w-50"></td>
                            </tr>
                        </table>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button class="btn btn-info" id="accept_btn" data-id="">Accept & Agree Pre-Interview Checklist Are Correct</button>
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
                    <div clas="d-flex justify-content-between container">
                        <div class="mb-3">
                            Premium Status : <span class="p-2 badge badge-primary text-capitalize" id="premium-status">No Data</span>
                        </div>
                        <div id="premium-info" class="alert alert-primary not-showing" role="alert"></div>
                    </div>
                    <div class="d-flex justify-content-between" id="premium_file">
                        <a class="btn btn-primary" id="premium_file_show_btn" target="_blank"><i class="fa fa-eye"></i> <?php echo $term_premium_show_button?></a>
                        <a class="btn btn-success" id="premium_file_download_btn" target="_blank"><i class="fa fa-download"></i> <?php echo $term_premium_download_button?></a>
                    </div>
                    
                    <div class="mt-3 text-left" id="premium-send-form">    
                        <form id="premium_service">
                            <input type="hidden" id="address_book_id" name="address_book_id" />
                            <input type="hidden" id="job_application_id" name="job_application_id" />
                            <input type="hidden" id="status" name="status" />
                            
                            <div class="form-group">
                                <label for="type"><?php echo $term_premium_type?></label>
                                <select id="type" name="type" class="form-control mb-3">
                                    <option value="early">Early</option>
                                    <option value="late">Late</option>
                                </select>
                            </div>
                            <div class="md-form">
                                <input type="text" id="premium_email" name="premium_email" class="form-control" readonly/>
                                <label for="email"><?php echo $term_premium_email?></label>
                            </div>
                        </form>
                        <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect" id="offer_premium_service_btn" type="button"><?php echo $term_premium_button?></button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Premium Service Modal -->

        <!-- CTrac Modal -->
        <div class="modal fade" id="ctrac_modal" tabindex="-1" role="dialog" aria-labelledby="myCVmodalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">

                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h4 class="modal-title w-100 font-weight-bold">Ctrac Status Update</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="container modal-body mb-3">
                            <form id="ctrac_form">
                            <div class="form-input">
                                <label for="">Send Ctrac access request:</label>
                                <input type="text" name="send_ctrac_on" class="form-control datepicker">
                            </div>
                            <div class="form-input">
                                <label for="">Ctrac accessed by candidata confirmed:</label>
                                <input type="text" name="ctrac_accessed_on" class="form-control datepicker">
                            </div>
                            <div class="form-input">
                                <label for="">Candidate completed initial Ctrac:</label>
                                <input type="text" name="ctrac_completed_on" class="form-control datepicker">
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
                <div class="modal-body " id="pre-screen-modal-body">

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-info" id="update-interview-btn"> Accept candidate to interview!</button>
                </div>
            </div>
        </div>
    </div>
<!--End Prescreen Modal-->

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
                    <div class="md-form">

                        <input placeholder="Date and Time" id="schedule_on" name="schedule_on" type="text" data-open="picker2" class="form-control date-time picker-opener" required>
                        <input placeholder="Selected date" type="text" id="picker2"  class="form-control time-date-ghost">
                        <input placeholder="Selected time" data-open="picker2" type="text" class="form-control timepicker time-date-ghost">

                        <label for="schedule_on">Schedule On</label>
                    </div>
                    <div class="md-form">
                        <select name="timezone" id="timezone" class="mdb-select md-form" searchable="Search timezone..">
                            <?php
                            foreach ($timezones as $key => $item ){
                                $select ='<option value="'.$item.'"';
                                $select .= '>'.$item.'</option>';
                                echo $select;
                            }
                            ?>
                        </select>
                        <label for="timezone" >Select Timezone</label>
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-info" id="submit_interview_scheduling"> Update Schedule</button>
                </div>
            </div>
        </div>
    </div>