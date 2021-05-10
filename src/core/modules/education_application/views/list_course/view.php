<div class="card">
    <input type="hidden" id="link-education" value="<?php echo $link_education?>">
    <div class="card-header gradient-card-header blue-gradient text-center">
        <h4 class="text-white"><?php echo $term_page_header ?></h4>
    </div>
    <div class="card-body">
        <?php if(!empty($courses)){?>
            <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i>
            There are <b><?php echo count($courses);?> courses</b> that you can request to join.
            </div>

            <div class="row row-cols-1 row-cols-md-1">
                <div class="col mb-4 category pt-3" id="category">
                    <!-- start card -->
                    <div class="row">
                    <?php
                        foreach ($courses as $value){
                            ?>
    <!-- start card -->
    <div class="col-lg-6 col-xs-12">
    <div class="card mb-4">
        <div class="card-body pb-3">

            <!-- Title -->
            <h4 class="card-title font-weight-bold"><a href="#"><?php echo $value['course_name']?></a></h4>
            <!-- Text -->
            <div class="d-flex justify-content-between mt-2 mb-2">
                <p class="text-justify">
                    <?php echo $value['short_description']?>
                </p>
            </div>
            <hr class="">
                <div class="right">
                    <a href="#" data-id="<?php echo $value['course_id'] ?>" class="btn btn-success btn-sm waves-effect waves-light btn_join_course"><i class="fas fa-paper-plane"></i> Join Now</a>
                </div>

        </div>

    </div>
    </div>
    <!-- end card -->

                    <?php } ?>
                    </div>
                    <!-- end card -->
                    
                </div>
            </div>

        <?php }else{?>
                                        
            <div class="alert alert-danger">

                <?php echo $term_no_available_course?>
            </div>

        <?php } ?>
        

    </div>
</div>
<style>
    .blockquote p {
    padding: 0;
    font-size: 1rem;
}
</style>
<div class="modal fade" id="join_course_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div style="text-align:center;padding-top:30%" class="content_loading">
            <div style="color: white;width:80px;height:80px" class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Form -->
        <form id="form_request_join_course" method="post">
        <input type="hidden" name="course_id" id="course_id" value="0">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="title_modal"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <img src="" class="img-fluid mx-auto d-block mb-4">
                <h4>Description</h4>
                <p id="short_description"></p>
                <blockquote class="blockquote mb-0">
                    <p id="full_description"></p>
                </blockquote>       
            </div>
            <div class="modal-footer">
                <!-- Send button -->
                <button class="btn btn-warning btn-md" data-dismiss="modal">Cancel</button>
                <button class="btn btn-success btn-md" id="btn_submit_request" type="submit">Request Join</button>
            </div>
        </div>
        </form>
        <!-- Form -->
    </div>
</div>