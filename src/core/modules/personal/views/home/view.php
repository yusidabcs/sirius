<div class="container">
<div class="row"> <!-- Global Row-->
	<input id="active_tab" hidden="hidden" value="<?php echo $active_tab; ?>" />
	<input id="docs_active_tab" hidden="hidden" value="<?php echo $docs_active_tab; ?>" />

    <div class="d-flex" style="flex-direction:column;width:100%">
    
    <?php
    if($progress < 100){
        $info = $verification['verification_info'];
        if (!empty($info))
        {
            $msg ='
                <div class="">
                    <h5 class="card-title">
                            '.$term_main_status.'</label>
                        </h5>
                    <div class="progress md-progress " style="height: 20px">
                        <div class="progress-bar bg-success progress-bar-animated" role="progressbar" style="width: '.$progress.'%'.'; height: 20px" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100">'.ceil($progress).'%</div>
                    </div>
                </div>';
    
            if(!in_array('general',$info)) {
                $msg .= '<div class="alert alert-warning" role="alert">
                    <div class="text-left">';
                $msg .= ($mode == 'recruitment')
                    ? 'You need to fill in the details first to continue '
                    : 'Please fill in the details first to continue ';
    
                $msg .= '<ul>';
    
                foreach ($info as $data){
                    switch ($data) {
                        case "user_info":
                            $data_term = '';
                            break;
                        case "passport":
                            $data_term = '<a href="'.$passport_link.'/new" title="'.$term_passport_heading.'">'.$term_passport_heading.'</a>';
                            break;
                        case "idcard":
                            $data_term = '<a href="'.$idcard_link.'/new" title="'.$term_idcard_heading.'">'.$term_idcard_heading.'</a>';
                            break;
                        case "general":
                            $data_term = $term_general_title;
                            break;
                        case "language":
                            $data_term = '<a href="'.$language_link.'" title="'.$term_language_heading.'">'.$term_language_heading.'</a>';
                            break;
                        case "education":
                            $data_term = '<a href="'.$education_link.'/new" title="'.$term_education_heading.'">'.$term_education_heading.'</a>';
                            break;
                        case "tattoo":
                            $data_term = '<a href="'.$tattoo_link.'/new" title="'.$term_tattoo_heading.'">'.$term_tattoo_heading.'</a>';
                            break;
                        case "reference_personal":
                            $data_term = '<a href="'.$reference_warning_link.'" title="'.$term_reference_heading_personal.'">'.$term_reference_heading_warning.'</a>';
                            break;
                        case "checklist_health":
                            $data_term = '<a href="'.$checklist_link.'/health" title="'.$term_checklist.' Health'.'">'.$term_checklist.' Health'.'</a>';
                            break;
                        case "checklist_character":
                            $data_term = '<a href="'.$checklist_link.'/character" title="'.$term_checklist.' Character'.'">'.$term_checklist.' Character'.'</a>';
                            break;
                        case "vaccination":
                            $data_term = $term_vaccination_heading;
                            break;
                    }
                    $msg .= '<li>'.$data_term.'</li>';
                }
                $msg .= '</ul>';
    
                $msg .= '</div>
                </div>';
            }
        }
        echo $msg;
    }
    ?>
    </div>
    <?php
    $msg='';
    switch ($verification['status']) {
        // case "notready":
        //     $info = $verification['verification_info'];
        //     if (!empty($info))
        //     {
        //         $msg ='
		// 			<div class="">
		// 				<h5 class="card-title">
		// 						'.$term_main_status.'</label>
		// 					</h5>
		// 				<div class="progress md-progress " style="height: 20px">
		// 					<div class="progress-bar bg-success progress-bar-animated" role="progressbar" style="width: '.$progress.'%'.'; height: 20px" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100">'.ceil($progress).'%</div>
		// 				</div>
		// 			</div>';

        //         if(!in_array('general',$info)) {
        //             $msg .= '<div class="alert alert-warning" role="alert">
		// 				<div class="text-left">';
        //             $msg .= ($mode == 'recruitment')
        //                 ? 'You need to fill in the details first to continue '
        //                 : 'Please fill in the details first to continue ';

        //             $msg .= '<ul>';

        //             foreach ($info as $data){
        //                 switch ($data) {
        //                     case "user_info":
        //                         $data_term = '';
        //                         break;
        //                     case "passport":
        //                         $data_term = '<a href="'.$passport_link.'/new" title="'.$term_passport_heading.'">'.$term_passport_heading.'</a>';
        //                         break;
        //                     case "idcard":
        //                         $data_term = '<a href="'.$idcard_link.'/new" title="'.$term_idcard_heading.'">'.$term_idcard_heading.'</a>';
        //                         break;
        //                     case "general":
        //                         $data_term = $term_general_title;
        //                         break;
        //                     case "language":
        //                         $data_term = '<a href="'.$language_link.'" title="'.$term_language_heading.'">'.$term_language_heading.'</a>';
        //                         break;
        //                     case "education":
        //                         $data_term = '<a href="'.$education_link.'/new" title="'.$term_education_heading.'">'.$term_education_heading.'</a>';
        //                         break;
        //                     case "tattoo":
        //                         $data_term = '<a href="'.$tattoo_link.'/new" title="'.$term_tattoo_heading.'">'.$term_tattoo_heading.'</a>';
        //                         break;
        //                     case "reference_personal":
        //                         $data_term = '<a href="'.$reference_link.'/personal/new" title="'.$term_reference_heading_personal.'">'.$term_reference_heading_personal.'</a>';
        //                         break;
        //                     case "checklist_health":
        //                         $data_term = '<a href="'.$checklist_link.'/health" title="'.$term_checklist.' Health'.'">'.$term_checklist.' Health'.'</a>';
        //                         break;
        //                     case "checklist_character":
        //                         $data_term = '<a href="'.$checklist_link.'/character" title="'.$term_checklist.' Character'.'">'.$term_checklist.' Character'.'</a>';
        //                         break;
        //                     case "vaccination":
        //                         $data_term = $term_vaccination_heading;
        //                         break;
        //                 }
        //                 $msg .= '<li>'.$data_term.'</li>';
        //             }
        //             $msg .= '</ul>';

        //             $msg .= '</div>
		// 			</div>';
        //         }
        //     }
        //     break;
        case "ready":
            $msg = '<div class="alert alert-success d-flex justify-content-between align-items-center" role="alert"><br/>';
            $msg .= ($mode == 'recruitment')
                ? '<div>User personal information is complete </div><button id="show_verification_btn" class="btn btn-sm btn-primary" type="button"  data-tooltip="true" data-placement="right" title="'.$term_main_update_verification.'" data-connection="'.@$connection['connection_id'].'"><i class="fas fa-edit"></i> '.$term_main_update_verification.'</button>'
                : '<div>Your personal information is complete. You can request verification now.</div> <button type="button" id="req_verification" class="btn btn-sm btn-success">'.$term_request_verification.'</button>';
            $msg .= '</div>';
            break;
        case "rejected":
            $msg = '<div class="alert alert-warning text-info d-flex justify-content-between align-items-center" role="alert">
							<div><p>Your personal data is rejected, please update again based on below message:</p>
							<p class="text-danger">'.ucfirst($verification['verification_info']).'</p></div>';
            $msg .= ($mode == 'recruitment')
                ? '<button id="show_verification_btn" class="btn btn-sm btn-primary" type="button"  data-tooltip="true" data-placement="right" title="'.$term_main_update_verification.'" data-connection="'.@$connection['connection_id'].'"><i class="fas fa-edit"></i> '.$term_main_update_verification.'</button>'
                : '<button type="button" id="req_verification" class="btn btn-success btn-sm">'.$term_request_verification_again.'</button>';
            $msg .= '</div>';
            break;
        case "unverified":
            $msg = '<div class="alert alert-success d-flex justify-content-between align-items-center" role="alert"><br/>';
            $msg .= ($mode == 'recruitment')
                ? '<div>User personal information is complete </div><button id="show_verification_btn" class="btn btn-sm btn-primary" type="button"  data-tooltip="true" data-placement="right" title="'.$term_main_update_verification.'" data-connection="'.@$connection['connection_id'].'"><i class="fas fa-edit"></i> '.$term_main_update_verification.'</button>'
                : '<div>Your personal information is complete. You can request verification now.</div> <button type="button" id="req_verification" class="btn btn-sm btn-success">'.$term_request_verification.'</button>';
            $msg .= '</div>';
            break;
        case "request":
            $msg = '<div class="alert alert-info d-flex justify-content-between align-item-center" role="alert">';
            $msg .= ($mode == 'recruitment')
                ? 'User personal data still on verification process. <button id="show_verification_btn" class="btn btn-sm btn-primary" type="button"  data-tooltip="true" data-placement="right" data-connection="'.@$connection['connection_id'].'" title="'.$term_main_update_verification.'"><i class="fas fa-edit"></i> '.$term_main_update_verification.'</button>'
                : 'Your personal data still on verification process. We will verify it soon and update your personal status.';
            $msg .= '</div>';
            break;
        case "process":
            $info = $latest_verification['verification_info'];
            $message='';
            if($info!='') {
                $message ='<p>NOTE:  <i>"'.$info.'"</i> </p>';
            }
            $msg = '<div class="alert alert-warning d-flex justify-content-between align-items-center" role="alert">';
            $msg .= ($mode == 'recruitment')
                ? '<div>User personal verification data still on <span class="font-weight-bold">process</span> status. '.$message.'</div><button id="show_verification_btn" class="btn btn-sm btn-primary" type="button"  data-tooltip="true" data-placement="right" title="'.$term_main_update_verification.'" data-connection="'.@$connection['connection_id'].'"><i class="fas fa-edit"></i> '.$term_main_update_verification.'</button>'
                : '
                    <div class="d-flex justify-content-between w-100">
                        <div class="">
                            Your verification data still on <span class="font-weight-bold">process</span> status.'.$message.'
                        </div>
                        <div class="">
                            <button type="button" id="req_verification" class="btn btn-sm btn-success">'.$term_request_verification.'</button>
                        </div>
                    </div>
                
                    ';
            $msg .= '</div>';
            break;
        case "verified":
            $msg = '<div class="alert alert-success d-flex justify-content-between align-items-center" role="alert">';
            $msg .= ($mode == 'recruitment')
                ? '<div class="col-12">
							<div class="row">
								<div class="col-sm-12 col-lg-12">
									User data has been successfully verified. Now user can apply for available jobs
								</div> 
								<div class="col-sm-12 col-lg-12">
									<!--<button id="show_job_table_btn" class="btn btn-sm btn-primary float-right" type="button" data-tooltip="true" data-placement="right" title="'.$term_main_update_user_job.'"><i class="fas fa-edit"></i> '.$term_main_update_user_job.'</button>-->
									<a href="'.$jobapplication_link.'" class="btn btn-sm btn-primary float-left" data-tooltip="true" data-placement="right" title="'.$term_main_update_user_job.'"><i class="fas fa-edit"></i> '.$term_main_update_user_job.'</a>
                                    <button id="show_verification_btn" class="btn btn-sm btn-success float-leftt" type="button" data-tooltip="true" data-placement="right" title="'.$term_main_update_verification.'" data-connection="'.@$connection['connection_id'].'"><i class="fas fa-edit"></i> '.$term_main_update_verification.'</button>
                                    <button id="special_apply_job" class="btn btn-sm btn-warning float-leftt" type="button" data-tooltip="true" data-placement="right" title="'.$term_main_special_apply_job.'" data-ab="'.$address_book_id.'" data-linkapply="'.$jobApply_link.'"><i class="fas fa-edit"></i> '.$term_main_special_apply_job.'</button>
								</div>
							</div>
						</div>'
                : '
                <div class="row">
                    <div class="col-xl-12 col-xs-12">
                        Congratulations, your data has been successfully verified. Now you can apply for available jobs
                    </div>
                    <div class="col-xl-12 col-xs-12">
                        <a href="'.$personal_jobapplication_link.'" class="btn btn-sm btn-primary float-left btn-sm-mobile-100" data-tooltip="true" data-placement="right" title="'.$term_main_update_user_job.'"><i class="fas fa-edit"></i> '.$term_main_update_user_job.'</a>
                    </div>
                </div>
						';
            $msg .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button></div>';
            break;
        case "no_job_hunting":
            $msg = '<div class="alert alert-warning " role="alert">';
            $msg .= '<div>Sorry, but your general information showing that you currently not looking for job. You still can complete your personal data for future job requirement.</div>';
            $msg .= '</div>';
            break;
        
        default:
            if($progress == 100){
                $msg = '<div class="alert alert-success d-flex justify-content-between align-items-center" role="alert"><br/>';
                $msg .= ($mode == 'recruitment')
                    ? '<div>User personal information is complete </div><button id="show_verification_btn" class="btn btn-sm btn-primary" type="button"  data-tooltip="true" data-placement="right" title="'.$term_main_update_verification.'" data-connection="'.@$connection['connection_id'].'"><i class="fas fa-edit"></i> '.$term_main_update_verification.'</button>'
                    : '<div>Your personal information is complete. You can request verification now.</div> <button type="button" id="req_verification" class="btn btn-sm btn-success">'.$term_request_verification.'</button>';
                $msg .= '</div>';
            }
            

    }
    ?>
	<!-- progress bar and info verified -->						
    <input type="hidden" id="verified_status" value="<?php echo $verification['status']; ?>"/>
	<div class="w-100">
    <?php echo $msg?>
    </div>
    <?php if($mode == 'recruitment' && count($jobs)>0) { ?>
    <div class="w-100">
        <div class="alert alert-primary" role="alert">
            <h4 class="alert-heading">User Have Applied Job!</h4>
            <table class="table-sm">
                <tr>
                    <th><b>Job Title</b></th>
                    <td class="pl-4"><?php echo $jobs[0]['job_speedy_code'];?> - <?php echo $jobs[0]['job_title'];?></td>
                </tr>
                <tr>
                    <th><b>Status</b></th>
                    <td class="pl-4"><?php echo ucfirst(str_replace('_',' ',$jobs[0]['status']));?></td>
                </tr>
                <tr>
                    <th><b>Applied On</b></th>
                    <td class="pl-4"><?php echo $jobs[0]['created_on'];?></td>
                </tr>
            </table>
        </div>
    </div>
    <?php } ?>
	<div class="col-sm-12 m-0 p-0 px-2">
		
		
		<!--end of progress bar and info verified -->
		
		<!-- tab list -->
        <ul class="nav nav-tabs d-flex justify-content-center" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="v-pills-home-tab" data-toggle="tab" href="#main" role="tab" aria-controls="v-pills-home" aria-selected="true">Main</a>
            </li>
            <?php
            if (!empty($general)) {
                ?>
                <li class="nav-item">
                    <a id="tab_lang" class="nav-link" data-toggle="tab" href="#lang"
                       role="tab"><?php echo $term_tab_lang ?></a>
                </li>
                <?php
                if ($general['seafarer'] || $general['migration']) {
                    ?>
                    <li class="nav-item">
                        <a id="tab_checks" class="nav-link" data-toggle="tab" href="#checks"
                           role="tab"><?php echo $term_tab_checks ?></a>
                    </li>
                    <?php
                }
                ?>

                <li class="nav-item">
                    <a id="tab_employ" class="nav-link" data-toggle="tab" href="#employ"
                       role="tab"><?php echo $term_tab_employ ?></a>
                </li>

                <li class="nav-item">
                    <a id="tab_edu" class="nav-link" data-toggle="tab" href="#edu"
                       role="tab"><?php echo $term_tab_edu ?></a>
                </li>

                <?php
                if ($general['tattoo']) {
                    ?>
                    <li class="nav-item">
                        <a id="tab_tat" class="nav-link" data-toggle="tab" href="#tat"
                           role="tab"><?php echo $term_tab_tat ?></a>
                    </li>
                    <?php
                }
                ?>

                <li class="nav-item">
                    <a id="tab_ref" class="nav-link" data-toggle="tab" href="#ref"
                       role="tab"><?php echo $term_tab_ref ?></a>
                </li>



                <li class="nav-item">
                    <a id="tab_documents" class="nav-link" data-toggle="tab" href="#documents"
                       role="tab">Documents</a>
                </li>

                <?php
            }
            ?>
		</ul>
		
        <div class="tab-content" id="v-pills-tabContent">
            <?php
            include('section/main_info.php');
            ?>
            <?php

            if (!empty($general)) {

                include( 'section/language.php');

                if ($general['seafarer'] || $general['migration']) {
                    include('section/checks.php');
                }


                include('section/employment.php');

                include('section/education.php');

                if ($general['tattoo']) {
                    include('section/tattoo.php');
                }

                include('section/referrence.php');
            }
            
            ?>

            <!-- Document tab -->
            <div id="documents" class="tab-pane fade in" role="tabpanel">
                <!-- Nav tabs -->
                <div class="row">
                    <div class="col-md-3 p-0">
                        <ul id="tabVertical" class="nav nav-tabs flex-column" role="tablist">

                            <?php
                            if ($general['passport']) {
                                ?>
                                <li class="nav-item">
                                    <a id="tab_passport" class="nav-link active" data-toggle="tab" href="#passport_tab" role="tab">
                                        <?php echo $term_tab_passp ?>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                            <li class="nav-item">
                                <a id="tab_ids" class="nav-link" data-toggle="tab" href="#ids" role="tab">
                                    <?php echo $term_tab_ids ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a id="tab_english" class="nav-link" data-toggle="tab" href="#english_tab" role="tab">
                                    English Tests
                                </a>
                            </li>
                            <li class="nav-item">
                                <a id="tab_psf" class="nav-link" data-toggle="tab" href="#premium_service" role="tab">
                                    Premium Services
                                </a>
                            </li>

                            <li class="nav-item">
                                <a id="tab_police" class="nav-link" data-toggle="tab" href="#police_check" role="tab">
                                    <?php echo $term_tab_police ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a id="tab_medical" class="nav-link" data-toggle="tab" href="#medical" role="tab">
                                    <?php echo $term_tab_med ?>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a id="tab_seaman" class="nav-link" data-toggle="tab" href="#sbk" role="tab">
                                    Seaman Books
                                </a>
                            </li>

                            <li class="nav-item">
                                <a id="tab_stcw" class="nav-link" data-toggle="tab" href="#stcw" role="tab">
                                    STCW Documents
                                </a>
                            </li>
                            <li class="nav-item">
                                <a id="tab_oktb" class="nav-link" data-toggle="tab" href="#oktb_tab" role="tab">
                                    OKTB
                                </a>
                            </li>
                            <li class="nav-item">
                                <a id="tab_flight" class="nav-link" data-toggle="tab" href="#flight" role="tab">
                                    Flight History
                                </a>
                            </li>

                            <li class="nav-item">
                                <a id="tab_flight" class="nav-link" href="#bgc" data-toggle="tab" role="tab">
                                    Background Check
                                </a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-md-9 p-0">
                        <!-- Tab panels -->
                        <div class="tab-content vertical p-0 px-md-3 mt-3 mt-md-0">

                            <?php
                            include('section/english_test.php');

                            if ($general['passport']) {
                                include( 'section/passport.php');
                            }

                            include( 'section/ids.php');
                            include('section/police.php');
                            include('section/medical.php');
                            include('section/flight.php');
                            include('section/stcw.php');
                            include('section/premium_service.php');
                            include('section/sbk.php');
                            include('section/bgc.php');
                            include('section/oktb.php');
                            ?>
                        </div>
                    </div>
                </div>
                <!-- Nav tabs -->
            </div>
            <!-- end police tab -->

        </div>
    </div>

    
</div> <!-- Global row-->
</div>
<!-- CV Modal -->
<div class="modal fade" id="show_cv_modal" tabindex="-1" role="dialog" aria-labelledby="myCVmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="show_cv" >
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
                
                <div class="modal-footer d-flex justify-content-center">
                    <a  class="btn btn-primary btn-show_cv_pdf not-showing" ><?php echo $term_main_download_pdf?></a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End of CV Modal -->

<!-- Edit Verification Modal -->
<div class="modal fade" id="edit_verification_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="EditModal"><?php echo $term_main_update_verification ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                    <input type="hidden" id="edit_id" name="edit_id" value="<?php echo $address_book_id?>">
                    <!-- Verification Select Description -->
                    <div class="mt-3 text-left">
                        <select id="verification_status" name="verification_status" class="form-control mb-3">
                        <?php
                            $html = '';
                            foreach($list_status as $key => $status)
                            {
                                $html.= '<option value="'.$status.'" >'.ucfirst($status).'</option>';
                            }
                            echo $html;
                        ?>
                        </select>
                        <div class="form-group">
                            <label for="verification_info">Verification Info</label>
                            <textarea id="verification_info" name="verification_info" maxlength="255" class="form-control" placeholder="Enter message..." required></textarea>
                            <span id="charactersRemaining"></span>
                            
                        </div>
                    </div>
                    <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect" id="edit_verification_btn" type="button">Edit </button>
            </div>
        </div>
    </div>
</div>
<!-- Edit Verification Modal -->

<!-- Appointment Modal -->
<div id="appointmentModal" class="modal fade in" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Set Appointment Date</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
            <form id="appointment" action="#" method="post">
                <input type="hidden" name="address_book_id" value="<?php echo $_SESSION['personal']['address_book_id'] ?>">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
                <input type="hidden" name="type" value="medical">
                <div class="modal-body">
                        <div class="form-group">
                            <label for="date">Select Appointment Date</label>
                            <input type="text" name="appointment_date" id="date" class="flatpickr form-control">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Appointment Modal -->

<!-- Register Visa Modal -->
<div id="registerVisaModal" class="modal fade in" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Upload Payment Receipt</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
                <div class="modal-body">
                <form id="registerVisa" action="#" method="post">
                    <input type="hidden" name="address_book_id" value="<?php echo $_SESSION['personal']['address_book_id'] ?>">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
                    <input type="hidden" name="visa_type">
                    <input type="hidden" id="payment_receipt_base64" name="payment_receipt_base64">
                    <input type="hidden" name="visa_type" value="">
                    <div class="form-group">
                        <label for="booking_number">Booking Number</label>
                        <input type="text" name="booking_number" id="booking_number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Docs Application Date</label>
                        <input type="text" name="docs_application_date" id="date" class="flatpickr form-control">
                    </div>
                    <div class="form-group">
                        <label for="payment_receipt_input" class="required">Choose File</label>
                            <input type="file" class="col-12" id="payment_receipt_input" accept=".jpg,.png,.gif" >
                        </div>
                        
                        <div id="payment_receipt_croppie_wrap" class="mw-100 w-auto mh-100 h-auto">
                            <div id="payment_receipt_croppie" style="width: 100%; height: 330px;" data-banner-width="400" data-banner-height="330"></div>
                        </div>
                        
                        <button class="btn btn-default btn-block" type="button" id="payment_receipt_result">Crop Image</button>
                    </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary register-visa">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
        </div>
    </div>
</div>
<!-- End Register Visa Modal -->

<!-- Interview Modal -->
<div id="interviewDateModal" class="modal fade in" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Set Interview Date</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
            <form id="interview" action="#" method="post">
                <input type="hidden" name="address_book_id" value="<?php echo $_SESSION['personal']['address_book_id'] ?>">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
                <input type="hidden" name="country_code">
                <input type="hidden" name="visa_type">
                <div class="modal-body">
                        <div class="form-group">
                            <label for="date">Select Interview Date</label>
                            <input type="text" name="interview_date" id="date" class="flatpickr form-control">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Interview Modal -->

<!-- modal spesial job -->
<div class="modal fade" id="modal_special_job" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="text-center modal-dialog" role="document">
        <div class="loading-content lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        <div class="text-left special-job-content modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Apply job for this user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="select_job_content">
                    
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="apply_special_job" class="btn btn-primary">Apply Now</button>
            </div>
        </div>

    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="modal_loading" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="text-center modal-dialog modal-lg" role="document">
    <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
    </div>
</div>