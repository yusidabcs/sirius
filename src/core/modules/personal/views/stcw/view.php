<div class="container">
    <?php
    if(isset($errors) && is_array($errors))
    {
    ?>
        <div class="iow-callout iow-callout-warning">
            <h2 class="text-warning"><?php echo $term_error_legend; ?></h2>
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

    <div class="row">
        <div class="col-12" >
            <!-- Main Card -->
            <div class="card mb-4">
                <h3 class="card-header blue-gradient white-text text-center py-4">
                    <?php echo $term_stcw_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
                </h3>

                <form method="post">
                    <!-- Main Card -->
                    <div class="card-body">
                        <!-- text information -->
                        <div id="stcw_data" class="card mb-4">
                            <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                <i class="fas fa-info-circle"></i> <?php echo $term_stcw_info_heading ?>
                            </h4>

                            <div class="card-body">
                                <div id="stcw_data_details" class="row align-items-center " >

                                    <div class="col-md-1 text-left text-md-right required">
                                        <b><?php echo $term_stcw_table_heading_type; ?></b>
                                        <input type="hidden" name="stcw_id" value="<?php echo $stcw['stcw_id']; ?>"/>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <select id="type" name="type" class="mdb-select md-form" required >
                                            <option value=""><?php echo $term_stcw_table_select_please; ?></option>
                                            <option value="bst" <?php if($stcw['type'] == 'bst') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_bst; ?></option>
                                            <option value="sat" <?php if($stcw['type'] == 'sat') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_sat; ?></option>
                                            <option value="cm" <?php if($stcw['type'] == 'cm') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_cm; ?></option>
                                            <option value="dsd" <?php if($stcw['type'] == 'dsd') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_dsd; ?></option>
                                            <option value="sfsat" <?php if($stcw['type'] == 'sfsat') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_sfsat; ?></option>
                                            <option value="bpst" <?php if($stcw['type'] == 'bpst') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_bpst; ?></option>
                                            <option value="ps" <?php if($stcw['type'] == 'ps') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_ps; ?></option>
                                            <option value="sr" <?php if($stcw['type'] == 'sr') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_sr; ?></option>
                                            <option value="efa" <?php if($stcw['type'] == 'efa') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_efa; ?></option>
                                            <option value="fp" <?php if($stcw['type'] == 'fp') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_fp; ?></option>
                                            <option value="ff" <?php if($stcw['type'] == 'ff') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_ff; ?></option>
                                            <option value="pst" <?php if($stcw['type'] == 'pst') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_pst; ?></option>
                                        </select>
                                    </div>

                                    <div class="col-md-1 text-left-text-md-right required">
                                        <b><?php echo $term_stcw_table_heading_serial_no ?></b>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <input type="text" name="serial_no" id="serial_no" class="form-control" value="<?php echo $stcw['serial_no']; ?>">
                                    </div>
                                    <div class="col-md-1 text-left-text-md-right required">
                                        <b><?php echo $term_stcw_table_heading_certificate_no ?></b>
                                    </div>  
                                    <div class="form-group col-md-3">
                                        <input type="text" name="certificate_no" id="certificate_no" class="form-control" value="<?php echo $stcw['certificate_no']; ?>">
                                    </div>

                                    <div class="col-12 py-2">
                                        <div class="row">
                                            <div class="col-md-2 col-lg-2 text-left text-md-right required"><b><?php echo $term_stcw_table_heading_place_issued; ?></b></div>
                                            <div class="col-md-10 col-lg-10">
                                                <input type="text" class="form-control" id="place_issued" name="place_issued" maxlength="20" value="<?php echo $stcw['place_issued']; ?>" required />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 py-2">
                                        <div class="row">
                                            <div class="col-md-2 col-lg-2 text-left text-md-right required"><b><?php echo $term_stcw_table_heading_held_by; ?></b></div>
                                            <div class="col-md-3 col-lg-4">
                                                <input class="form-control" type="text" id="held_by" name="held_by" value="<?php echo $stcw['held_by']; ?>" required />
                                            </div>

                                            <div class="col-md-3 col-lg-2 text-left text-md-right required"><b><?php echo $term_stcw_table_heading_held_at; ?></b></div>
                                            <div class="col-md-4 col-lg-4">
                                                <input type="text" class="form-control" id="held_at" name="held_at" value="<?php echo $stcw['held_at']; ?>" required />
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-12 py-2">
                                        <div class="row">
                                            <div class="col-md-2 col-lg-2 text-left text-md-right required"><b><?php echo $term_stcw_table_heading_from_date; ?></b></div>
                                            <div class="col-md-3 col-lg-4">
                                                <input class="calendar form-control" type="text" id="from_date" name="from_date" value="<?php echo $stcw['from_date']; ?>" required />
                                            </div>

                                            <div class="col-md-3 col-lg-2 text-left text-md-right required"><b><?php echo $term_stcw_table_heading_to_date; ?></b></div>
                                            <div class="col-md-4 col-lg-4">
                                                <input type="text" class="form-control" id="to_date" name="to_date" value="<?php echo $stcw['to_date']; ?>" required />
                                            </div>
                                        </div>
                                    </div>
                                    <tr>
                                    </tr>

                                </div>

                            </div>

                        </div>

                        <!-- test image -->
                        <div id="stcw_image" class="card col-sm-12 col-md-12 m-0 p-0 ">

                            <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                <i class="fas fa-image"></i> <?php echo $term_stcw_image_heading ?>
                            </h4>

                            <div class="card-body">

                            <div class="container ">
                                <?php
                                if(!empty($stcw['filename'])) {
                                    ?>
                                    <!-- stcw image if any -->
                                    <div class="text-center">
                                        <img id="curr_img" src="/ab/show/<?php echo $stcw['filename'] ?>" alt="Current STCW Document" class="img-fluid" >
                                        <input type="hidden" id="stcw_current" name="stcw_current" value="<?php echo $stcw['filename'] ?>">
                                    </div>

                                    <hr>
                                    <!-- end of stcw photo -->
                                    <?php
                                }
                                ?>
                                <div class="form-group" >
                                    <label for="stcw_input" class="required"><?php echo $term_stcw_image_choose_file; ?></label>
                                    <input type="file" class="col-12"  id="stcw_input" accept=".jpg,.png,.gif" <?php echo (empty($stcw['filename'])) ? 'required' : '' ?>>
                                    <input type="hidden" id="stcw_base64" name="stcw_base64">
                                </div>

                                <div id="stcw_croppie_wrap" class="mw-100 w-auto mh-100 h-auto hide">
                                    <div id="stcw_croppie" data-banner-width="500" data-banner-height="700"></div>
                                </div>

                                <button class="btn btn-default btn-block" type="button" id="stcw_result"><?php echo $term_stcw_image_crop; ?></button>

                            </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer right">
                        <a id="go_back" href="<?php echo $back_url ?>" class="btn btn-sm btn-warning" role="button"><i class="fas fa-thumbs-down"></i> <?php echo $term_go_back; ?></a>
                        &nbsp;
                        <button type="submit" name="next" value="again" class="btn btn-sm btn-default"><i class="fas fa-thumbs-up"></i> <?php echo $term_save_stcw_add; ?></button>
                        &nbsp;
                        <button type="submit" name="next" value="home" class="btn btn-sm btn-primary"><i class="fas fa-thumbs-up"></i> <?php echo $term_save_stcw; ?></button>

                    </div>

                </form>

            </div>
        </div>
    </div>

</div>