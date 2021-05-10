<div class="">
    <?php
    if(isset($errors) && is_array($errors))
    {
        ?>
        <div class="iow-callout iow-callout-warning">
            <h2 class="text-warning"><?php echo $term_error_legend ?></h2>
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

    <div class="card mb-4">
        <h3 class="card-header blue-gradient white-text text-center py-4">
            <?php echo $term_general_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
        </h3>
        <form method="post" id="general">
            <!-- Main Card -->
            <div class="card-body row">

                <!-- General Info -->
                <div id="general_info_select" class="card mb-4">
                    <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                        <i class="fas fa-info-circle"></i> <?php echo $term_general_heading_general; ?>
                    </h4>
                    <div class="card-body">
                        <div class="row">
                            <!-- employment -->
                            <div class="col-sm-4 col-md-4 text-left text-sm-right required"><b><?php echo $term_general_employment; ?></b></div>
                            <div class="col-sm-8 col-md-8">
                                <select id="employment" name="employment" class="mdb-select mx-3" required >
                                    <option value=""><?php echo $term_general_table_select_please; ?></option>
                                    <option value="unemployed" <?php if($general['employment'] == 'unemployed') echo 'selected="Selected"'; ?>><?php echo $term_general_employment_unemployed; ?></option>
                                    <option value="casual" <?php if($general['employment'] == 'casual') echo 'selected="Selected"'; ?>><?php echo $term_general_employment_casual; ?></option>
                                    <option value="part_time" <?php if($general['employment'] == 'part_time') echo 'selected="Selected"'; ?>><?php echo $term_general_employment_part_time; ?></option>
                                    <option value="full_time" <?php if($general['employment'] == 'full_time') echo 'selected="Selected"'; ?>><?php echo $term_general_employment_full_time; ?></option>
                                </select>
                            </div>

                            <!-- job_hunting -->
                            <div class="col-sm-4 col-md-4 text-left text-sm-right required">
                                <b><?php echo $term_general_job_hunting; ?></b>
                                <input type="hidden" id="job_hunting_current" name="job_hunting_current" value="<?php echo $general['job_hunting']; ?>">
                            </div>
                            <div class="col-sm-8 col-md-8 ">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="job_hunting" id="job_hunting_yes" name="job_hunting" value="yes" hidden="hidden" >
                                                <span class="general"><?php echo $term_general_job_hunting_yes; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="job_hunting" id="job_hunting_no" name="job_hunting" value="no" hidden="hidden">
                                                <span class="general"><?php echo $term_general_job_hunting_no; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- seafarer -->
                            <div class="col-sm-4 col-md-4 text-left text-sm-right required">
                                <b><?php echo $term_general_seafarer; ?></b>
                                <input type="hidden" id="seafarer_current" name="seafarer_current" value="<?php echo $general['seafarer']; ?>">
                            </div>
                            <div class="col-sm-8 col-md-8">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="seafarer" id="seafarer_yes" name="seafarer" value="yes" hidden="hidden">
                                                <span class="general"><?php echo $term_general_seafarer_yes; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="seafarer" id="seafarer_no" name="seafarer" value="no" hidden="hidden">
                                                <span class="general"><?php echo $term_general_seafarer_no; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- migration -->
                            <div class="col-sm-4 col-md-4 text-left text-sm-right required">
                                <b><?php echo $term_general_migration; ?></b>
                                <input type="hidden" id="migration_current" name="migration_current" value="<?php echo $general['migration']; ?>">
                            </div>
                            <div class="col-sm-8 col-md-8">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="migration" id="migration_yes" name="migration" value="yes" hidden="hidden">
                                                <span class="general"><?php echo $term_general_migration_yes; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="migration" id="migration_no" name="migration" value="no" hidden="hidden">
                                                <span class="general" ><?php echo $term_general_migration_no; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div> 
                    </div>
                </div>

                <!-- Countries and Travel -->
                <div id="general_travel_select" class="card mb-4">
                    <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                        <i class="fas fa-globe"></i> <?php echo $term_general_heading_travel; ?>
                    </h4>
                    <div class="card-body">
                        <div class="row">
                            <!-- country_born -->
                            <div class="text-left text-md-right col-md-4 required"><b><?php echo $term_general_country_born; ?></b></div>
                            <div class="col-md-8">
                                <select id="country_born" name="country_born" class="mdb-select mx-3" searchable="Search.." required >
                                    <option value=""><?php echo $term_general_table_select_please; ?></option>
                                    <?php
                                    foreach($countryCodes as $id => $country)
                                    {
                                        ?>

                                        <option value="<?php echo $id; ?>" <?php if($general['country_born'] == $id) echo 'selected="Selected"'; ?>><?php echo $country; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- country_residence -->
                            <div class="text-left text-md-right col-md-4 required"><b><?php echo $term_general_country_residence; ?></b></div>
                            <div class="col-md-8">
                                <select id="country_residence" name="country_residence" class="mdb-select mx-3" searchable="Search.." required >
                                    <option value=""><?php echo $term_general_table_select_please; ?></option>
                                    <?php
                                    foreach($countryCodes as $id => $country)
                                    {
                                        ?>
                                        <option value="<?php echo $id; ?>" <?php if($general['country_residence'] == $id) echo 'selected="Selected"'; ?>><?php echo $country; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- passport -->
                            <div class="text-left text-sm-right col-sm-4 col-md-4 required">
                                <b><?php echo $term_general_passport; ?></b>
                                <input type="hidden" id="passport_current" name="passport_current" value="<?php echo $general['passport']; ?>">
                            </div>
                            <div class="col-sm-8 col-md-8">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="passport" id="passport_yes" name="passport" value="yes" hidden="hidden">
                                                <span class="general"><?php echo $term_general_passport_yes; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="passport" id="passport_no" name="passport" value="no" hidden="hidden">
                                                <span class="general"><?php echo $term_general_passport_no; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- travelled_overseas -->
                            <div class="text-left text-sm-right col-sm-4 col-md-4 required">
                                <b><?php echo $term_general_travelled_overseas; ?></b>
                                <input type="hidden" id="travelled_overseas_current" name="travelled_overseas_current" value="<?php echo $general['travelled_overseas']; ?>">
                            </div>
                            <div class="col-sm-8 col-md-8">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="travelled_overseas" id="travelled_overseas_yes" name="travelled_overseas" value="yes" hidden="hidden">
                                                <span class="general"><?php echo $term_general_travelled_overseas_yes; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="travelled_overseas" id="travelled_overseas_no" name="travelled_overseas" value="no" hidden="hidden">
                                                <span class="general"><?php echo $term_general_travelled_overseas_no; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div id="general_personal_select" class="card mb-4">
                    <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                        <i class="fas fa-user"></i> <?php echo $term_general_heading_personal; ?>
                    </h4>
                    <div class="card-body">
                        <div class="row">
                            <!-- relationship -->
                            <div class="text-left text-sm-right col-sm-4 col-md-4 required"><b><?php echo $term_general_relationship; ?></b></div>
                            
                            <div class="form-group col-sm-8 col-md-8">
                                <select id="relationship" name="relationship" class="mdb-select mx-3" required >
                                    <option value=""><?php echo $term_general_table_select_please; ?></option>
                                    <option value="committed" <?php if($general['relationship'] == 'committed') echo 'selected="Selected"'; ?>><?php echo $term_general_relationship_committed; ?></option>
                                    <option value="divorced" <?php if($general['relationship'] == 'divorced') echo 'selected="Selected"'; ?>><?php echo $term_general_relationship_divorced; ?></option>
                                    <option value="married" <?php if($general['relationship'] == 'married') echo 'selected="Selected"'; ?>><?php echo $term_general_relationship_married; ?></option>
                                    <option value="single" <?php if($general['relationship'] == 'single') echo 'selected="Selected"'; ?>><?php echo $term_general_relationship_single; ?></option>
                                    <option value="separated" <?php if($general['relationship'] == 'separated') echo 'selected="Selected"'; ?>><?php echo $term_general_relationship_separated; ?></option>
                                </select>
                            </div>

                            <!-- children -->
                            <div class="text-left text-sm-right col-sm-4 col-md-4 required">
                                <b><?php echo $term_general_children; ?></b>
                                <input type="hidden" id="children_current" name="children_current" value="<?php echo $general['children']; ?>">
                            </div>
                            <div class="col-sm-8 col-md-8">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="children" id="children_yes" name="children" value="yes" hidden="hidden">
                                                <span class="general"><?php echo $term_general_children_yes; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="children" id="children_no" name="children" value="no" hidden="hidden">
                                                <span class="general"><?php echo $term_general_children_no; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- tattoo -->
                            
                            <div class="text-left text-sm-right col-sm-4 col-md-4 required">
                                <b><?php echo $term_general_tattoo; ?></b>
                                <input type="hidden" id="tattoo_current" name="tattoo_current" value="<?php echo $general['tattoo']; ?>">
                            </div>
                            <div class="col-sm-8 col-md-8">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="tattoo" id="tattoo_yes" name="tattoo" value="yes" hidden="hidden">
                                                <span class="general"><?php echo $term_general_tattoo_yes; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="tattoo" id="tattoo_no" name="tattoo" value="no" hidden="hidden">
                                                <span class="general"><?php echo $term_general_tattoo_no; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- height_weight -->
                            <div class="text-left text-sm-right col-sm-4 col-md-4 required">
                                <b><?php echo $term_general_height_weight; ?></b>
                                <input type="hidden" id="height_weight_current" name="height_weight_current" value="<?php echo $general['height_weight']; ?>">
                            </div>
                            <div class="col-sm-8 col-md-8">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="height_weight" id="height_weight_me" name="height_weight" value="me" hidden="hidden">
                                                <span class="general"><?php echo $term_general_height_weight_me; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="iow-ck-button">
                                            <label>
                                                <input type="radio" class="height_weight" id="height_weight_im" name="height_weight" value="im" hidden="hidden">
                                                <span class="general"><?php echo $term_general_height_weight_im; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="me" class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4"></div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="height_cm" class="required"><?php echo $term_general_height_cm; ?></label>
                                            <input type="number" class="form-control" id="height_cm" name="height_cm" maxlength="6" value="<?php echo $general['height_cm']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="weight_cm" class="required"><?php echo $term_general_weight_kg; ?></label>
                                            <input type="number" class="form-control" id="weight_kg" name="weight_kg" maxlength="6" value="<?php echo $general['weight_kg']; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="im" class="col-md-12 ">
                                <div class="row">
                                    <div class="col-md-4"></div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="height_in" class="required"><?php echo $term_general_height_in; ?></label>
                                            <input type="number" class="form-control" id="height_in" name="height_in" maxlength="6" value="<?php echo $general['height_in']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="weight_lb" class="required"><?php echo $term_general_weight_lb; ?></label>
                                            <input type="number" class="form-control" id="weight_lb" name="weight_lb" maxlength="6" value="<?php echo $general['weight_lb']; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Next of Kin Information -->
                <div id="general_nok_select" class="card mb-4">
                    <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                        <i class="fas fa-users"></i> <?php echo $term_general_heading_nok; ?>
                    </h4>
                    <div class="card-body">
                        <div class="row">
                            <!-- name -->
                            <div class="form-group col-md-12 pb-2">
                                <div class="row">
                                    <div class="text-left text-md-right col-md-4"><b><?php echo $term_general_nok_family_name; ?></b></div>
                                    <input type="text" class="col-md-8 form-control" id="nok_family_name" name="nok_family_name" maxlength="255" value="<?php echo $general['nok_family_name']; ?>">
                                </div>
                            </div>
                            <div class="form-group col-md-12 pb-2">
                                <div class="row">
                                    <div class="text-left text-md-right col-md-4"><b><?php echo $term_general_nok_given_names; ?></b></div>
                                    <input type="text" class="col-md-8 form-control" id="nok_given_names" name="nok_given_names" maxlength="255" value="<?php echo $general['nok_given_names']; ?>">
                                </div>
                            </div>

                            <!-- relationship -->
                            <div class="form-group col-md-12 pb-2">
                                <div class="row">
                                    <div class="text-left text-md-right col-md-4"><b><?php echo $term_general_nok_relationship; ?></b></div>
                                    <input type="text" class="col-md-8 form-control" id="nok_relationship" name="nok_relationship" maxlength="255" value="<?php echo $general['nok_relationship']; ?>">
                                </div>
                            </div>

                            <!-- address -->
                            <div class="form-group col-md-12 pb-2">
                                <div class="row align-items-center">
                                    <div class="text-left text-md-right col-md-4"><b><?php echo $term_general_nok_address; ?></b></div>
                                    <input type="text" class="col-md-8 form-control" id="nok_line_1" name="nok_line_1" maxlength="255" value="<?php echo $general['nok_line_1']; ?>">
                                    <input type="text" class="offset-md-4 col-md-8 form-control" id="nok_line_2" name="nok_line_2" maxlength="255" value="<?php echo $general['nok_line_2']; ?>">
                                    <input type="text" class="offset-md-4 col-md-8 form-control" id="nok_line_3" name="nok_line_3" maxlength="255" value="<?php echo $general['nok_line_3']; ?>">
                                </div>
                            </div>

                            <!-- country -->
                            <div class="form-group col-md-12 pb-2">
                                <div class="row">
                                    <div class="text-left text-md-right col-md-4"><b><?php echo $term_general_nok_country; ?></b></div>
                                    <select id="nok_country" name="nok_country" class="col-md-8 mdb-select " searchable="Search..">
                                        <option value=""><?php echo $term_general_table_select_please; ?></option>
                                        <?php
                                        foreach($countryCodes as $id => $country)
                                        {
                                        ?>
                                            <option value="<?php echo $id; ?>" <?php if($general['nok_country'] == $id) echo 'selected="Selected"'; ?>><?php echo $country; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <!-- telephone -->
                            <div class="form-group col-md-12 pb-2">         
                                <div class="row align-items-center">                           
                                    <div class="text-left text-md-right col-sm-12 col-md-4 col-lg-4"><b><?php echo $term_general_nok_phone_label; ?></b></div>
                                    
                                    <div class="col-sm-12 col-md-8 col-lg-8 ">
                                        <div class="row">
                                            <!-- numbertype -->
                                            <div class="md-form col-sm-12 ">
                                                <select id="nok_number_type" name="nok_number_type" class="mdb-select">
                                                    <option value=""><?php echo $term_general_table_select_please; ?></option>
                                                    <option value="mobile" <?php if($general['nok_number_type'] == 'mobile') echo 'selected="Selected"'; ?>><?php echo $term_general_nok_phone_mobile; ?></option>
                                                    <option value="landline" <?php if($general['nok_number_type'] == 'landline') echo 'selected="Selected"'; ?>><?php echo $term_general_nok_phone_landline; ?></option>
                                                </select>
                                                <label for="nok_number_type"><?php echo $term_general_nok_number_type?></label>
                                            </div>

                                            <div class="md-form input-group col-sm-12 ">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text md-addon" id="countrydial_prefixs" data-dial='<?php echo json_encode($countryDialCodes)?>'></span>
                                            </div>
                                            <input type="tel" class="form-control" id="nok_number" name="nok_number" maxlength="25" value="<?php echo $general['nok_number']; ?>">

                                            <label for="nok_number"><b><?php echo $term_general_nok_phone_number ?></b></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- email -->
                            <div class="form-group col-md-12 pb-2">
                                <div class="row">
                                    <div class="text-left text-md-right col-md-4"><b><?php echo $term_general_nok_email; ?></b></div>
                                    <input type="email" class="col-md-8 form-control" id="nok_email" name="nok_email" maxlength="255" value="<?php echo $general['nok_email']; ?>">
                                </div>
                            </div>

                            <!-- skype -->
                            <div class="form-group col-md-12 pb-2">
                                <div class="row">
                                    <div class="text-left text-md-right col-md-4"><b><?php echo $term_general_nok_skype; ?></b></div>
                                    <input type="text" class="col-md-8 form-control" id="nok_skype" name="nok_skype" maxlength="255" value="<?php echo $general['nok_skype']; ?>">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Image -->
                <div id="general_image" class="col-6">
                <div class="card">
                    
                    <h4 class="card-header amy-crisp-gradient white-text text-center py-4 ">
                        <i class="fas fa-image"></i> <?php echo $term_general_image_heading; ?>
                    </h4>
                    <div class="card-body ">
                        <div class="container ">
                            <?php
                            $class = 'not-showing';
                            if(!empty($general['filename'])) {
                                $class = '';
                                ?>
                                <!-- general image if any -->
                                <div class="text-center ">
                                    <input type="hidden" id="general_current" name="general_current" value="<?php echo $general['filename']; ?>">
                                </div>
                                <!-- end of general photo -->
                                <?php
                            }
                            ?>
                                <div class="text-center">
                                    <img id="curr_img" src="/ab/show/<?php echo $general['filename']; ?>" class="img-fluid <?php echo $class;?>" alt="Current Image" >
                                    <button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
                                </div>
                                <hr>
                            <div class="form-group ">
                                <label for="general_input" class="required"><?php echo $term_general_image_choose_file; ?></label>
                                <input type="file" class="col-12" id="general_input" accept=".jpg,.png,.gif" <?php echo (empty($general['filename'])) ? 'required' : '' ?>>
                                <input type="hidden" id="general_base64" name="general_base64">
                            </div>
                                <div id="general_croppie_wrap" class="mw-100 w-auto mh-100 h-auto">
                                    <div id="general_croppie" data-banner-width="500" data-banner-height="700"></div>
                                </div>
                            <button class="btn btn-default btn-block not-showing" type="button" id="general_result"><?php echo $term_general_image_crop; ?></button>
                        </div>  
                    </div>
                </div>
                </div>

                <!-- Image -->
                <div id="signature_image" class=" col-6">
                    <div class="card">
                        
                        <h4 class="card-header amy-crisp-gradient white-text text-center py-4 ">
                            <i class="fas fa-image"></i> <?php echo $term_signature_image_heading; ?>
                        </h4>
                        <div class="card-body ">
                            <div class="container ">
                                <?php
                                $class = 'not-showing';
                                if(!empty($general['signature_filename'])) {
                                    $class = '';
                                    ?>
                                    <!-- signature image if any -->
                                    <div class="text-center ">
                                        <input type="hidden" id="signature_current" name="signature_current" value="<?php echo $general['signature_filename']; ?>">
                                    </div>
                                    <!-- end of signature photo -->
                                    <?php
                                }
                                ?>
                                    <div class="text-center">
                                    <?php
                                    if(!empty($general['signature_filename'])) {
                                        $class = '';
                                        ?>
                                        <img id="sig_curr_img" src="/ab/show/<?php echo $general['signature_filename']; ?>" class="img-fluid <?php echo $class;?>" alt="Current Image" >
                                        <?php
                                    }
                                    ?>
                                        
                                        <button class="btn btn-default btn-block not-showing mt-2" type="button" id="signature_update_crop">Crop Photo</button>
                                    </div>
                                    <hr>
                                <div class="form-group ">
                                    <label for="signature_input"><?php echo $term_signature_image_choose_file; ?></label>
                                    <input type="file" class="col-12" id="signature_input" accept=".jpg,.png,.gif">
                                    <input type="hidden" id="signature_base64" name="signature_base64">
                                </div>
                                    <div id="signature_croppie_wrap" class="mw-100 w-auto mh-100 h-auto">
                                        <div id="signature_croppie" data-banner-width="300" data-banner-height="200"></div>
                                    </div>
                                <button class="btn btn-default btn-block not-showing" type="button" id="signature_result"><?php echo $term_signature_image_crop; ?></button>
                            </div>  
                        </div>
                    </div>
                </div>

                <!-- end of main card body -->
            </div>

            <div class="card-footer">
                <div class="row flex-column-reverse flex-lg-row">
                    <div class="col-lg-6 col-xs-12 left">
                        <a id="go_back" href="<?php echo $back_url ?>" class="btn btn-md btn-warning font-weight-bold btn-sm-mobile-100" role="button"><i class="fas fa-arrow-circle-left"></i> <?php echo $term_go_back; ?></a>
                    </div>
                    <div class="col-lg-6 col-xs-12 right">
                        <button type="submit" id="submit-general" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_general_save; ?></button>
                    </div>
                </div>
            </div>

        </form>

    </div>

</div>