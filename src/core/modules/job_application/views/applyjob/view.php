<div class="container-fluid">
<div class="card">
    <div class="card-header gradient-card-header blue-gradient d-flex justify-content-between">
        <h4 class="text-white "><?php echo $job['job_speedy_code'].' - '.$job['job_title'] ?></h4>
        <h5 class="text-white font-italic "><?php echo $term_salary_start.' '.($job['min_salary']/100)?></h5>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="border p-5">
                    <h4><?php echo $term_job_detail?></h4>
                    <p><?php echo $job['short_description']?></p>
                    <div class="d-flex justify-content-around border border-info p-3 mb-3">

                        <?php if($job['stcw_req']) {?>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="cb_stcw" checked disabled>
                                <label class="form-check-label" for="cb_stcw"><?php echo $term_need_stcw?></label>
                            </div>
                        <?php }?>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="cb_min_education" checked disabled>
                            <label class="form-check-label" for="cb_min_education"><?php echo $term_pre_min_education.ucfirst($job['min_education']).$term_post_min_education ?> </label>
                        </div>
                        <?php if ($job['min_english_experience'] > 0) {?>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="cb_english" checked disabled>
                            <label class="form-check-label" for="cb_english"><?php echo $job['min_english_experience'].' '.$term_year_english_experience?></label>
                        </div>
                        <?php } ?>

                        <?php if ($job['min_experience'] > 0) {?>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="cb_experience" checked disabled>
                            <?php 
                            $experience_year =  floor($job['min_experience'] / 12);
                            $experience_month =  $job['min_experience'] % 12;
                            $experience='';
                            if($experience_year>0) {
                                $experience .= $experience_year.' Year ';
                            }
                            $experience .= $experience_month.' Month ';
                            ?>
                            <label class="form-check-label" for="cb_experience"><?php echo $experience.$term_year_experience?></label>
                        </div>
                        <?php }?>

                    </div>
                    <h4><?php echo $term_job_requirement?></h4>
                    <?php echo $job['min_requirement']?>

                </div>

            </div>
            <div class="col-md-6">
                <div class="border p-3">
                    <?php if($mode == 'recruitment') { ?>
                        <div class="card mb-3">
                            <h5 class="card-header info-color white-text text-center py-3">
                                <strong>Profil User</strong>
                            </h5>
                            <div class="card-body px-lg-5">
                                <div class="row">
                                    <div class="col-lg-4 pb-2">
                                        <div class="avatar mx-auto white">
                                            <img src="/ab/show/<?php echo $avatar[0]['filename']?>" alt="Profil User" class="rounded-circle img-fluid">
                                        </div>
                                    </div>
                                    <div class="col-lg-8 pb-2 text-left">
                                        <h4 class="card-title">
                                            <?php
                                            if (!empty($main['title']))
                                                echo $main['title'] . ' ';
                                            echo ' '.$main['number_given_name'];
                                            if (!empty($main['middle_names']))
                                                echo ' '.$main['middle_names'];
                                            echo ' '.$main['entity_family_name'];
                                            ?>
                                        </h4>
                                        <table class="table-sm">
                                            <tr>
                                                <th>Gender</th>
                                                <td class="text-left">
                                                    <?php
                                                    echo (empty($main['sex']))
                                                        ? 'Not Set'
                                                        : ucfirst($main['sex']) ;
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td class="text-left">
                                                    <a href="mailto:<?php echo $main['main_email']; ?>"><?php echo $main['main_email']; ?></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Telephone</th>
                                                <td class="text-left">
                                                    <?php
                                                    if (empty($pots))
                                                    {
                                                        echo 'Not Set';
                                                    } else {
                                                        foreach ($pots as $key => $value)
                                                        {
                                                            if (!empty($value['number']))
                                                            {
                                                                echo '+'.$value['dialInfo']['dialCode'].$value['number'] . ' (' . $value['type'] . ')<br>';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="">
                        <?php if($exist_job_flag) {?>
                            <div class="alert alert-warning" role="alert">
                                <h4 class="alert-heading"><?php echo $mode == 'recruitment' ? $term_admin_job_applied :$term_user_job_applied?></h4>
                                <p><?php echo $mode == 'recruitment' ? $term_admin_sorry_one_job :$term_user_sorry_one_job?></p>
                            </div>
                        <?php }?>

                        <?php if($stcw_flag) {?>
                            <div class="alert alert-info" role="alert">
                                <h4 class="alert-heading"><?php echo $term_flag_stcw_header?></h4>
                                <p><?php echo $term_flag_stcw_text?></p>
                                <hr>
                                <a href="<?php echo $update_personal_link?>" class="btn btn-link btn-info"><?php echo $term_update_personal?></a>
                            </div>
                        <?php }?>

                        <?php if($min_education_flag) {?>
                            <div class="alert alert-info" role="alert">
                                <h4 class="alert-heading"><?php echo $term_flag_pre_min_education_header.ucfirst($job['min_education']).$term_flag_post_min_education_header?></h4>
                                <p><?php echo $term_flag_pre_min_education_text.ucfirst($job['min_education']).$term_flag_post_min_education_text?></p>
                                <hr>
                                <a href="<?php echo $update_personal_link?>" class="btn btn-link btn-info"><?php echo $term_update_personal?></a>
                            </div>
                        <?php }?>

                        <?php if($experience_flag) {
                                $experience_year =  floor($job['min_experience'] / 12);
                                $experience_month =  $job['min_experience'] % 12;
                                $experience='';
                                if($experience_year>0) {
                                    $experience .= $experience_year.' Year ';
                                }
                                $experience .= $experience_month.' Month ';
                            ?>
                            <div class="alert alert-info" role="alert">
                                <h4 class="alert-heading"><?php echo $term_flag_experience_header?></h4>
                                <p><?php echo $term_flag_experience_pre_text.' '.$experience.' '.$term_flag_experience_post_text?> </p>
                                <hr>
                                <a href="<?php echo $update_personal_link?>" class="btn btn-link btn-info"><?php echo $term_update_personal?></a>
                            </div>
                        <?php }?>
                        <?php if($english_experience_flag) {?>
                            <div class="alert alert-info" role="alert">
                                <h4 class="alert-heading"><?php echo $term_flag_english_experience_header?></h4>
                                <p> <?php echo $term_flag_english_experience_pre_text.' '.$job['min_english_experience'].' '.$term_flag_english_experience_post_text?></p>
                                <a href="<?php echo $update_personal_link?>" class="btn btn-link btn-info"><?php echo $term_update_personal?></a>
                            </div>
                        <?php }?>
                    </div>

                    <?php if(!$english_experience_flag && !$experience_flag && !$stcw_flag && !$exist_job_flag && !$min_education_flag ) {?>
                        <div class="card">
                            <h5 class="card-header info-color white-text text-center py-4">
                                <strong><?php echo $term_apply_job.' '.(($mode == 'recruitment')? $term_for_user : '')?></strong>
                            </h5>

                            <div class="card-body px-lg-5">
                                <!-- Form -->
                                <form id="apply_job_form" action="<?php echo $myURL?>" method="post" >
                                    <input type="hidden" name="address_book_id" value="<?php echo $address_book_id ?>">
                                    <input type="hidden" name="job_speedy_code" value="<?php echo $job['job_speedy_code'] ?>">
                                    <input type="hidden" name="min_experience" value="<?php echo $job['min_experience'] ?>">
                                    <input type="hidden" name="mode" value="<?php echo $mode ?>">

                                    <p><?php echo $term_select_requirement?></p>

                                    <!-- Work Experience -->

                                    <div class="form-group">
                                        <label for="work"><?php echo $term_work_experience_label?> <?php echo ($job['min_experience'] > 0 ) ? ' | <span class="text-danger">*required</span>' : '' ?></label>

                                        <select class="form-control" id="work" name="employment_id" <?php echo ($job['min_experience'] > 0 ) ? 'required' : '' ?>>
                                            <option value="" ><?php echo $term_choose_option?></option>
                                            <?php foreach ($works as $key => $item) {?>
                                                <option value="<?php echo $key?>" ><?php echo $item['employer'].' - '.$item['job_title']?></option>
                                            <?php }?>
                                        </select>
                                        
                                    </div>

                                    <!-- Personal Reference -->
                                    <div class="form-group">
                                        <label for="personal"><?php echo $term_personal_reference_label?> | <span class="text-danger">*required</span></label>
                                        <?php if ( $personal_references_count > 0 ){?>
                                        <select class="form-control" id="personal" name="personal_reference_id" required>
                                            <option value="" ><?php echo $term_choose_option?></option>
                                            <?php foreach ($references as $key => $item) {
                                                if($item['type'] == 'personal'){
                                                    ?>
                                                    <option value="<?php echo $key?>" ><?php echo $item['family_name'].' '.$item['given_names'].' - '.$item['relationship']?></option>
                                                <?php } }?>
                                        </select>
                                        <?php }else{ ?>
                                            <div class="alert alert-info" role="alert">
                                                <h4 class="alert-heading"><?php echo $term_personal_reference_required_label?></h4>
                                                <p> <?php echo $term_personal_reference_needed?></p>
                                                <a href="<?php echo $update_personal_link?>" class="btn btn-link btn-info"><?php echo $term_update_personal?></a>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <!-- Work Reference -->
                                    <div class="form-group">
                                        <label for="work_ref"><?php echo $term_work_reference_label?> <?php echo ($job['min_experience'] > 0 ) ? '| <span class="text-danger">*required</span>' : '' ?></label>

                                        <select  id="work_ref" class="form-control" name="work_reference_id" <?php echo ($job['min_experience'] > 0 ) ? 'required' : '' ?>>
                                            <option value="" ><?php echo $term_choose_option?></option>
                                            <?php foreach ($references as $key => $item) {
                                                if($item['type'] == 'work'){
                                                    ?>
                                                    <option value="<?php echo $key?>" ><?php echo $item['family_name'].' '.$item['given_names'].' - '.$item['relationship']?></option>
                                                <?php } }?>
                                        </select>
                                        <br/>            
                                        <?php if ( $work_references_count == 0 && $job['min_experience'] > 0){?>
                                            <div class="alert alert-info" role="alert">
                                                <h4 class="alert-heading"><?php echo $term_work_reference_required_label?></h4>
                                                <p> <?php echo $term_work_reference_needed?></p>
                                                <a href="<?php echo $update_personal_link?>" class="btn btn-link btn-info"><?php echo $term_update_personal?></a>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <label><?php echo $term_placement_label?></label>
                                    <div class="d-flex justify-content-around border border-info p-3 mb-2">
                                        <!-- Male -->
                                        <div class="form-check form-check-inline">
                                            <input type="radio" class="form-check-input" id="placement_sea" name="relevance" value="sea" checked required>
                                            <label class="form-check-label" for="placement_sea"><?php echo $term_placement_sea ?></label>
                                        </div>
                                        <!-- Female -->
                                        <div class="form-check form-check-inline">
                                            <input type="radio" class="form-check-input" id="placement_land" name="relevance" value="land">
                                            <label class="form-check-label" for="placement_land"><?php echo $term_placement_land ?></label>
                                        </div>
                                        <!-- Female -->
                                        <div class="form-check form-check-inline">
                                            <input type="radio" class="form-check-input" id="placement_both" name="relevance" value="both">
                                            <label class="form-check-label" for="placement_both"><?php echo $term_placement_both ?></label>
                                        </div>
                                    </div>
                                    <!-- Sign in button -->
                                    <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect" type="submit">Submit</button>

                                </form>
                                <!-- Form -->
                                <div class="modal fade" id="apply_job_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel"><?php echo $term_application_summary?></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body modal-lg">
                                                <p class="text-center"><?php echo ($mode == 'recruitment') ? $term_modal_admin_confirmation :$term_modal_personal_confirmation ?></p>
                                                <table class="table">
                                                    <tr>
                                                        <td><?php echo $term_job_title_label?></td>
                                                        <td><?php echo $job['job_speedy_code'].' - '.$job['job_title'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo $term_work_experience_label?></td>
                                                        <td id="work_experience"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo $term_personal_reference_label?></td>
                                                        <td id="personal_reference"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo $term_work_reference_label?></td>
                                                        <td id="work_reference"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo $term_placement_label?></td>
                                                        <td id="work_placement" class="text-capitalize"></td>
                                                    </tr>
                                                </table>

                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-success" id="submit_job_application"><?php echo $term_btn_submit?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer text-center">
        <?php if ($mode == 'recruitment') {?>
            <a href="<?php echo $listjob_link?>" class="btn btn-primary"><< <?php echo $term_back_to_listjob?></a>
            <a href="<?php echo $update_personal_link?>" class="btn btn-success"><?php echo $term_back_to_personal?> >> </a>
        <?php } ?>
    </div>
</div>
</div>