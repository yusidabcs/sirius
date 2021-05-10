
<section>
    <div class="container">

        <?php
        //If Error
        if(isset($errors) && is_array($errors))
        {
            ?>
            <div class="iow-callout iow-callout-warning">
                <h4 class="text-warning"><?php echo $term_error_legend ?></h4>
                <?php
                foreach($errors as $key => $value)
                {
                    $tname = 'term_'.$key.'_label';
                    $title = isset($$tname) ? $$tname : $key;
                    echo "				<p class=\"text-warning\"><strong>{$title}</strong> {$value}</p>\n";
                }
                ?>
            </div>
            <?php
        }
        ?>

        <?php
        //If Message

        if(isset($messages) && is_array($messages))
        {
            ?>
            <div class="iow-callout iow-callout-success">
                <h2 class="text-success"><?php echo $term_success_legend ?></h2>
                <?php
                foreach($messages as $key => $value)
                {
                    $tname = 'term_'.$key.'_label';
                    $title = isset($$tname) ? $$tname : $key;
                    echo "				<p class=\"text-success\"><strong>{$title}</strong> {$value}</p>\n";
                }
                ?>
            </div>
            <?php
        }
        ?>

        <div class="card">



            <div class="card-header gradient-card-header blue-gradient">
                <h4 class="text-white text-center"><?php echo $term_local_partner_header ?></h4>
            </div>

            <?php
                if(isset($_SESSION['partner_action'])){
                    //display info message
                    echo '
                    <div class="alert alert-info text-center alert-dismissible fade show" role="alert">
                    <strong>'.$_SESSION['partner_action'].' Partner</strong> '.$_SESSION['partner_action_status'].'
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>';
                    //unset SESSION
                    if(isset($_SESSION['partner_action_status'])){
                        unset($_SESSION['partner_action_status']);
                    }
                    unset($_SESSION['partner_action']);
                }
            ?>
            <!-- Card content -->
            <div class="card-body">
                <!-- Form -->
                <form id="form_partner_create" style="color: #757575;" method="POST" action="<?php echo $myURL?>" enctype="multipart/form-data">

                     <!-- Name -->
                    
                    <h5><?php echo $term_partner_address_book_desc?></h5>
                    <div class="row border m-0">
                        <div class="col-md-6">
                            <div class="md-form">
                                <div class="float-right mr-4">
                                    <div id="search_ab_spinner" class="not-showing spinner-border position-absolute" role="status" aria-hidden="true"></div>
                                </div>
                                <input type="text" class="form-control" name="" id="search_ab" >    
                                <label for="search_ab"><?php echo $term_search_address_book?></label>
                                <div class="invalid-feedback">
                                    <p class="alert alert-warning"><?php echo $term_address_book_email_not_found?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 not-showing" id="div_ab">
                            <div class="md-form">
                                <select class="mdb-select md-form" id="address_book_id" name="address_book_id" searchable="Search here.." required>
                                <option value="" disabled selected><?php echo $term_choose_address_book?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="partner_area" class="not-showing">
                        <p class="alert alert-info partner_code_format mb-2"><?php echo $term_partner_code_format?></p>
                        <!-- Code -->
                        
                        <div class="form-group">
                            <label for="partner_type"><?php echo $term_partner_type ?></label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="chk_lp" id="chk_lp" value="lp">
                                <label class="form-check-label" for="chk_lp">LP</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="chk_lep" id="chk_lep" value="lep">
                                <label class="form-check-label" for="chk_lep">LEP</label>
                            </div>
                            <!-- <select id="partner_type" name="partner_type" class="mdb-select" required>
                                <option value="" disabled selected><?php echo $term_partner_type_none ?></option>
                                <option value="lp">LP</option>
                                <option value="lep">LEP</option>
                            </select> -->
                        </div>
                        
                        <div class="md-form">
                            <label for="partner_code"><?php echo $term_partner_code ?></label>
                            <div class="float-right mr-4">
                                <div id="partner_code_spinner" class="not-showing spinner-border position-absolute" role="status" aria-hidden="true"></div>
                                <div id="partner_code_valid" class="not-showing fa fa-lg fa-check mt-2 position-absolute text-success" role="status" aria-hidden="true"></div>
                            </div>
                            <input type="text" id="partner_code" name="partner_code" class="form-control" pattern="[a-zA-Z0-9-]+" required>
                            <div class="invalid-feedback">
                                <p class="alert alert-warning"><?php echo $term_partner_code_should_unique?></p>
                            </div>
                            <div id="partner_code_warning" class="not-showing alert alert-warning"><?php echo $term_partner_code_should_unique ?></div>
                            <div id="partner_code_success" class="not-showing alert alert-success"><?php echo $term_partner_code_unique ?></div>
                        </div>

                        <div class="mt-3 border p-3">

                            <h5>Covered Area</h5>

                            <!-- Country -->
                            <div class="md-form">
                                <select id="countryCode_id" name="countryCode_id[]" class="mdb-select md-form" multiple
                                        searchable="Search here.." required>
                                    <option value="" disabled>Choose your country</option>
                                    <?php foreach ($countries as $index => $country):?>
                                        <option value="<?php echo $index?>"><?php echo $country ?></option>
                                    <?php endforeach;?>
                                </select>
                                <label for="countryCode_id"><?php echo $term_partner_country ?></label>
                            </div>


                            <div id="sub_countries" class="row m-0">
                                <!-- generated after select country-->
                            </div>

                        </div>


                        <div class="mt-3 p-1">

                            <!-- banner photo -->
                            <div id="banner_image" class="card mb-4">

                                <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                    <i class="fas fa-image"></i> <?php echo $term_banner_image_heading; ?>
                                </h4>

                                <div class="card-body">

                                    <?php
                                    $class="not-showing";
                                    if(!empty($partner['filename'])) {
                                        $class = "";
                                        ?>
                                        <!-- banner image if any -->
                                        <div>
                                            
                                            <input type="hidden" id="banner_current" name="banner_current" value="<?php echo $partner['filename']; ?>">
                                        </div>
                                        <!-- end of banner image-->
                                        <?php
                                    }
                                    ?>
                                    <div class="text-center <?php echo $class;?>">
                                        <img src="/ab/show/<?php echo $partner['filename']; ?>" alt="Current Banner Image" id="banner_img" class="img-fluid">
                                        <button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								        <hr>
                                    </div>

                                    <div class="form-group">
                                        <label for="banner_input"><?php echo $term_banner_image_choose_file; ?></label>
                                        <input type="file" class="col-12" id="banner_input" accept=".jpg,.png,.gif" >
                                        <input type="hidden" id="banner_base64" name="banner_base64">
                                    </div>

                                    <div id="banner_croppie_wrap" class="mw-100 w-auto mh-100 h-auto not-showing">
                                        <div id="banner_croppie" data-banner-width="931" data-banner-height="230"></div>
                                    </div>

                                    <button class="btn btn-default btn-block not-showing" type="button" id="banner_result"><?php echo $term_banner_image_crop; ?></button>

                                </div>

                            </div>

                        </div>

                        <!-- Send button -->
                        <div class="justify-content-center">
                            <div class="row flex-column-reverse flex-lg-row">
                                <div class="col-lg-6 left">
                                    <a id="go_back" href="<?php echo $back_link ?>" class="btn btn-warning font-weight-bold btn-sm-mobile-100 waves-effect" role="button"><i class="fas fa-arrow-circle-left"></i> <?php echo $term_back_btn; ?></a>
                                </div>
                                <div class="col-lg-6 right">
                                    <button type="submit" class="btn btn-success font-weight-bold btn-sm-mobile-100 waves-effect btn-partner"><i class="fas fa-save"></i> <?php echo $term_save_btn; ?></button>
                                </div>
                            </div>
                            <!-- <a href="<?php echo $back_link ?>" class="btn back-btn btn-warning btn-rounded waves-effect"><?php echo $term_back_btn?></a>
                            <button class="btn btn-info btn-rounded z-depth-0 waves-effect btn-partner" type="submit"><?php echo $term_save_btn?></button> -->
                        </div>

                    </div>
                    
                </form>
                <!-- Form -->
                            
            </div>

        </div>
    </div>
</section>