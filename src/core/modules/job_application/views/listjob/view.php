<div class="card">

    <div class="card-header gradient-card-header blue-gradient text-center">
        <h4 class="text-white"><?php echo $term_page_header ?></h4>
    </div>
    <div class="card-body">
        <?php if(!empty($jobs)){?>
            <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i>
            There are <b><?=array_sum(array_map("count", $jobs));?> jobs</b> that meet the minimum requirements of your personal data.
            </div>

            <div class="row row-cols-1 row-cols-md-1">
                <div class="col mb-4 category pt-3" id="category">
                    <!-- start card -->
                    <div class="row">
                    <?php
                        foreach ($categories as $category){
                            ?>
                            <?php
                            if (isset($jobs[$category['job_speedy_category_id']])) { 
                            ?>
                                <?php
                                foreach ($jobs[$category['job_speedy_category_id']] as $job){
                                ?>
    <!-- start card -->
    <div class="col-lg-6 col-xs-12">
    <div class="card mb-4">
        <div class="card-body pb-3">

            <!-- Title -->
            <h4 class="card-title font-weight-bold"><a href="#" data-toggle="modal" data-target="#overview<?php echo $job['job_speedy_code']?>"><?php echo $job['job_title']?></a></h4>
            <!-- Text -->
            <p class="card-text badge badge-pill badge-default"><?php echo ($category['name']).'';?></p>
            <div class="d-flex justify-content-between mt-2 mb-2">
                <p class="text-justify">
                    <?php echo $job['short_description']?>
                </p>
            </div>
            <hr class="">
                <div class="right">
                    <a href="#" class="btn btn-primary btn-sm waves-effect waves-light" data-toggle="modal" data-target="#overview<?php echo $job['job_speedy_code']?>"><i class="fa fa-eye"></i> Detail</a>
                    <a href="<?php echo $baseURL.'/applyjob/'.$job['job_speedy_code']?>/<?php echo $address_book_id?>" class="btn btn-success btn-sm waves-effect waves-light"><i class="fas fa-paper-plane"></i> Apply Now</a>
                </div>
                <!-- Modal detail  -->
                <div class="modal fade" id="overview<?php echo $job['job_speedy_code']?>" tabindex="-1" role="dialog" aria-labelledby="listjobModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header d-flex justify-content-between">
                                <h5 class="modal-title"><?php echo $job['job_speedy_code']?> - <?php echo $job['job_title']?></h5>

                                <h5 class="font-italic pull-right text-info"><?php echo $term_start_from.($job['min_salary'] / 100)?></h5>
                            </div>
                            <div class="modal-body text-left">
                                <!--Card image-->
                                <div class="view overlay">
                                    <?php if(isset($category['banner'])) {?>
                                        <img class="card-img-top" src="/ao/show/<?php echo $category['banner']?>"
                                                alt="Card image cap">
                                    <?php } else {?>
                                        <img class="card-img-top" src="/core/images/dumy.jpg"
                                                alt="Card image cap">
                                    <?php }?>
                                    <!--Title-->
                                    <h4 class="card-title" style="position: absolute;bottom: 0;left: 15px;"><?php echo $category['name']?></h4>
                                </div>
                                <p><?php echo $job['short_description']?></p>
                                <div class="d-flex justify-content-around border border-info p-3 mb-3">
                                    <div class="form-check <?php echo !$job['stcw_req'] ? 'not-showing' : ''?>">
                                        <input type="checkbox" class="form-check-input" id="stcw_cb_<?php echo $job['job_speedy_code']?>"  <?php echo $job['stcw_req'] ? 'checked' : ''?> disabled>
                                        <label class="form-check-label" for="stcw_cb_<?php echo $job['job_speedy_code']?>"><?php echo $term_stcw_req?> </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="min_education_cb_<?php echo $job['job_speedy_code']?>"  <?php echo $job['min_education'] ? 'checked' : ''?> disabled>
                                        <label class="form-check-label" for="min_education_cb_<?php echo $job['job_speedy_code']?>"><?php echo $job['min_education'] ? $term_pre_min_education.ucfirst($job['min_education']).$term_post_min_education : ''?> </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="english_cb_<?php echo $job['job_speedy_code']?>" checked disabled>
                                        <label class="form-check-label" for="english_cb_<?php echo $job['job_speedy_code']?>"><?php echo $job['min_english_experience'].$term_post_english_experience?></label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="experience_cb_<?php echo $job['job_speedy_code']?>" checked disabled>
                                        <?php 
                                        $experience_year =  floor($job['min_experience'] / 12);
                                        $experience_month =  $job['min_experience'] % 12;
                                        $experience='';
                                        if($experience_year>0) {
                                            $experience .= $experience_year.' Year ';
                                        }
                                        $experience .= $experience_month.' Month ';
                                        ?>
                                        <label class="form-check-label" for="experience_cb_<?php echo $job['job_speedy_code']?>"><?php echo $experience.$term_post_experience ?> </label>
                                    </div>
                                </div>
                                <h4><?php echo $term_job_requirement?></h4>
                                <?php echo $job['min_requirement']?>
                            </div>
                            <div class="modal-footer">
                                <a href="<?php echo $baseURL.'/applyjob/'.$job['job_speedy_code']?>/<?php echo $address_book_id?>" class="btn btn-info btn-block"><?php echo $term_apply?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal detail -->

        </div>

    </div>
    </div>
    <!-- end card -->
                                <?php } ?>

                            
                            <?php } ?>
                    <?php } ?>
                    </div>
                    <!-- end card -->
                    
                </div>
            </div>

        <?php }else{?>
                                        
            <div class="alert alert-danger">
                <?php echo ($mode == 'recruitment')? $term_no_available_job_recruitment : $term_no_available_job?>
            </div>

        <?php } ?>
        

    </div>
</div>