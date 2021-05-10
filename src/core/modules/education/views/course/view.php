<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gradient-card-header blue-gradient">
            <h4 class="text-white text-center"><?php echo $term_header ?></h4>
            <div>
                <a id="add_new_course" href="#" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> <?php echo $term_create_course ?></a>
            </div>
        </div>
        <div class="card-body w-auto">

            <div class="row">
                <div class="col-md-3 md-form">
                    <label for="table_status_search"><?php echo $term_table_select_status ?></label>
                    <select id="table_status_search" class="mdb-select"
                            searchable="Search">
                        <option value=""><?php echo $term_table_select_status; ?></option>
                        <?php

                        $html = '';
                        foreach ($status as $index => $item) {
                            $html .= '<option value="' . $item . '" >' . ucwords(str_replace('_',' ', $item)) . '</option>';
                        }
                        echo $html;
                        ?>
                    </select>
                </div>

                <div class="col-md-3 md-form">
                        <!--The "from" Date Picker -->
                    <input placeholder="Selected starting date" type="text" id="startingDate" class="form-control datepicker">
                    <label for="startingDate">Filter Start Date</label>
                </div>

                <div class="col-md-3 md-form">
                <input placeholder="Selected starting date" type="text" id="endingDate" class="form-control datepicker">
                    <label for="endingDate">Filter End Date</label>
                </div>
            </div>

            <table class="table" id="list_education_course">
                <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th width='20%'>Course Name</th>
                    <th width='10%'>Status</th>
                    <th width='35%'>Description</th>
                    <th width='15%'>Created On</th>
                    <th width='15%'>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<!-- InsertModal -->
<div class="modal fade" id="add_course_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <!-- Form -->
        <form id="add_course_form" method="post">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="insertModal"><?php echo $term_create_course ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                    <div class="md-form mt-3">
                        <input type="text" id="course_name" name="course_name"
                                class="form-control" required>
                        <label for="course_name"><?php echo $term_course_name ?></label>
                    </div>

                    <div class="mt-3 text-left">
                        <label for="short_description"
                                class="float-left"><?php echo $term_short_description ?></label>
                        <textarea id="short_description" name="short_description" maxlength="255" class="form-control" placeholder="Enter Description" required></textarea>
                        <span id="charactersRemaining"></span>
                    </div>
                    
                    <div class="mt-3 text-left">
                        <label for="description"
                                class="float-left"><?php echo $term_description ?></label>
                        <textarea id="description" name="submitted_text" class="form-control"
                                    required></textarea>
                    </div>

                    <div class="mt-3 p-3 m-1">
                            <label for="status"><?php echo $term_status ?></label>
                            <div class="form-group ">
                                <!-- Material inline 1 -->
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="status_active" class="form-check-input"
                                            value="active" name="status" checked>
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>

                                <!-- Material inline 2 -->
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="status_disabled" class="form-check-input"
                                            value="disabled" name="status">
                                    <label class="form-check-label" for="status_disabled">Disabled</label>
                                </div>
                            </div>
                    </div>
                    
                    <!-- file image -->
                    <div class="mt-3 text-left">
                        <div id="image_course" class="card mb-4">

                            <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                <i class="fas fa-image"></i> <?php echo $term_image_course_heading; ?>
                            </h4>

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="file_course"><?php echo $term_image_course_choose_file; ?></label>
                                    <input type="file" class="col-12" id="image_course_input" accept=".jpg,.png,.gif" >
                                    <input type="hidden" id="image_course_base64" name="image_course_base64">
                                </div>

                                <div id="image_course_croppie_wrap" class="mw-100 w-auto mh-100 h-auto">
                                    <div id="image_course_croppie" data-image-course-width="600" data-image-course-height="400"></div>
                                </div>

                                <button class="btn btn-default btn-block not-showing" type="button" id="image_course_result"><?php echo $term_course_image_crop; ?></button>

                            </div>

                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <!-- Send button -->
                <button class="btn btn-warning btn-md waves-effect" data-dismiss="modal">Cancel</button>
                <button class="btn btn-success btn-md waves-effect" id="add_course_btn" type="submit">Save Course</button>
            </div>
        </div>
        </form>
        <!-- Form -->
    </div>
</div><!-- InsertModal -->

<!-- UpdateModal -->
<div class="modal fade" id="update_course_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="content_loading">
            <div class="spinner_loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        </div>
        <!-- Form -->
        <form id="update_course_form" method="post">
        <input type="hidden" name="course_id" id="course_id" value="0">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="insertModal"><?php echo $term_update_course ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                    <div class="md-form mt-3">
                        <input type="text" id="e_course_name" name="e_course_name"
                                class="form-control" required>
                        <label for="e_course_name"><?php echo $term_course_name ?></label>
                    </div>

                    <div class="mt-3 text-left">
                        <label for="e_short_description"
                                class="float-left"><?php echo $term_short_description ?></label>
                        <textarea id="e_short_description" name="e_short_description" maxlength="255" class="form-control" placeholder="Enter Description" required></textarea>
                        <span id="e_charactersRemaining"></span>
                    </div>
                    
                    <div class="mt-3 text-left">
                        <label for="e_description"
                                class="float-left"><?php echo $term_description ?></label>
                        <textarea id="e_description" name="submitted_text" class="form-control"
                                    required></textarea>
                    </div>

                    <div class="mt-3 p-3 m-1">
                            <label for="e_status"><?php echo $term_status ?></label>
                            <div class="form-group ">
                                <!-- Material inline 1 -->
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="e_status_active" class="form-check-input"
                                            value="active" name="e_status" checked>
                                    <label class="form-check-label" for="e_status_active">Active</label>
                                </div>

                                <!-- Material inline 2 -->
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="e_status_disabled" class="form-check-input"
                                            value="disabled" name="e_status">
                                    <label class="form-check-label" for="e_status_disabled">Disabled</label>
                                </div>
                            </div>
                    </div>
                    
                    <!-- file image -->
                    <div class="mt-3 text-left">
                        <div id="e_image_course" class="card mb-4">

                            <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                <i class="fas fa-image"></i> <?php echo $term_image_course_heading; ?>
                            </h4>

                            <div class="card-body">
                                <div class="not-showing">
                                    <img id="image_course_current_img" src="" alt="Current  Image" >
                                    <input type="hidden" id="image_course_current" name="image_course_current" value="">
                                </div>

                                <hr>
                                <div class="form-group">
                                    <label for="e_file_course"><?php echo $term_image_course_choose_file; ?></label>
                                    <input type="file" class="col-12" id="e_image_course_input" accept=".jpg,.png,.gif" >
                                    <input type="hidden" id="e_image_course_base64" name="e_image_course_base64">
                                </div>

                                <div id="e_image_course_croppie_wrap" class="mw-100 w-auto mh-100 h-auto">
                                    <div id="e_image_course_croppie" data-image-course-width="600" data-image-course-height="400"></div>
                                </div>

                                <button class="btn btn-default btn-block not-showing" type="button" id="e_image_course_result"><?php echo $term_course_image_crop; ?></button>

                            </div>

                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <!-- Send button -->
                <button class="btn btn-warning btn-md" data-dismiss="modal">Cancel</button>
                <button class="btn btn-success btn-md" id="update_course_btn" type="submit">Save Course</button>
            </div>
        </div>
        </form>
        <!-- Form -->
    </div>
</div><!-- InsertModal -->