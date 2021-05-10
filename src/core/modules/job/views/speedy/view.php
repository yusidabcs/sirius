<section app="">
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-center"><?php echo $term_local_job_header ?></h4>

                <div>
                    <a href="#" class="btn btn-success btn-sm add_new_job"><i
                                class="fa fa-plus"></i> <?php echo $term_create_job ?></a>

                    <!--<a href="#" class="btn btn-info btn-sm "
                       id="import_job_category_btn"><i class="fa fa-file-excel"></i> <?php /*echo $term_import_job */?></a>-->
                </div>


                <!-- Modal -->
                <div class="modal fade" id="importJobModal" tabindex="-1" role="dialog" aria-labelledby="exportJobModal"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form id="importjobmaster" enctype="multipart/form-data">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"
                                        id="exampleModalLongTitle"><?php echo $term_import_job ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $term_input_job_master_file ?></label>
                                        <input type="file" name="file" id="job_master_file" accept=".xls, .xlsx"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="import_job_master">Update</button>
                                </div>
                            </div><!-- modal-content -->
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body w-auto table-responsive">
                <?php
                if (isset($errors) && is_array($errors)) {
                    ?>
                    <div class="iow-callout iow-callout-warning">
                        <h2 class="text-warning"><?php echo $term_legend_error ?></h2>
                        <?php
                        foreach ($errors as $key => $value) {
                            $tname = 'term_login_' . $key . '_label';
                            $title = isset($$tname) ? $$tname : $key;
                            echo "				<p>\n";
                            echo "					<span>{$title} {$value}\n";
                            echo "				</p>\n";
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>

                <div id="table_search" class="container">
                    <div class="row">
                        <div class="col-md-6 offset-md-3 md-form">
                            <!-- <p class="text-center font-italic text-info">Please select the category first.</p> -->
                            <select id="table_category_search" name="table_category_search" class="mdb-select"
                                    searchable="Search">
                                <option value=""><?php echo $term_table_select_please; ?></option>
                                <?php foreach ($job_categories as $category) { ?>
                                    <?php if ($category['parent_id'] == 0) { ?>
                                        <option value="<?php echo $category['job_speedy_category_id'] ?>"><?php echo $category['name'] ?></option>
                                            <?php foreach ($job_categories as $category2) { ?>
                                                <?php if ($category2['parent_id'] == $category['job_speedy_category_id']) { ?>
                                                    <option value="<?php echo $category2['job_speedy_category_id'] ?>"> &nbsp;&nbsp;<?php echo $category2['name'] ?></option>
                                                <?php } ?>
                                            <?php } ?>

                                    <?php } ?>
                                <?php } ?>
                            </select>
                            <label for="table_category_search"
                                   class="text-center"><?php echo $term_table_category_filter ?></label>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-sm table-responsive-sm" id="list_job"
                       summary="Paginated list of master job">
                    <thead>
                    <tr>
                        <th scope="col" class="th-sm">Order</th>
                        <th scope="col" class="th-sm">Job Code</th>
                        <th scope="col" class="th-sm">Job Title</th>
                        <th scope="col" class="th-sm">Job Description</th>
                        <th scope="col" class="th-sm">Category</th>
                        <th scope="col" class="th-sm">Created On</th>
                        <th scope="col" class="th-sm">Action</th>
                    </tr>
                    </thead>
                </table>
                <input type="hidden" id="post_link" value="<?php echo $myURL ?>"/>

                <!-- InsertModal -->
                <div class="modal fade" id="add_job_modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="insertModal"><?php echo $term_legend_add ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <!-- Form -->
                                <form id="add_job_speedy_form" enctype="multipart/form-data" method="post" style="color: #757575;"
                                      action="<?php echo $myURL; ?>">
                                    <!-- Job Code -->
                                    <div class="md-form mt-3">
                                        <div class="float-right mr-4">
                                            <div id="job_code_spinner"
                                                 class="not-showing spinner-border position-absolute" role="status"
                                                 aria-hidden="true"></div>
                                            <div id="job_code_valid"
                                                 class="not-showing fa fa-lg fa-check mt-2 position-absolute text-success"
                                                 role="status" aria-hidden="true"></div>
                                        </div>
                                        <input type="text" id="job_speedy_code" name="job_speedy_code"
                                               class="form-control" minlength="3" maxlength="4" required>
                                        <label for="job_speedy_code"><?php echo $term_job_speedy_code ?></label>
                                        <div id="job_speedy_code_help" class="not-showing alert alert-warning "></div>
                                    </div>

                                    <!-- Job Name -->
                                    <div class="md-form mt-3">
                                        <input type="text" id="job_title" name="job_title" class="form-control"
                                               maxlength="100" required>
                                        <label for="job_title"><?php echo $term_job_title ?></label>
                                    </div>

                                    <div class="md-form mt-3">
                                        <select id="job_speedy_category_id" name="job_speedy_category_id"
                                                class="mdb-select md-form" required searchable="Search...">
                                            <option value="">Select Category</option>
                                            <?php foreach ($job_categories as $category) { ?>
                                                <?php if ($category['parent_id'] == 0) { ?>
                                                    <option value="<?php echo $category['job_speedy_category_id'] ?>"><?php echo $category['name'] ?></option>
                                                    <?php foreach ($job_categories as $category2) { ?>
                                                        <?php if ($category2['parent_id'] == $category['job_speedy_category_id']) { ?>
                                                            <option value="<?php echo $category2['job_speedy_category_id'] ?>"> &nbsp;&nbsp;<?php echo $category2['name'] ?></option>
                                                        <?php } ?>
                                                    <?php } ?>

                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <label for="job_speedy_category_id"><?php echo $term_job_category ?></label>
                                    </div>

                                    <!-- Job Description -->
                                    <div class="mt-3 text-left">
                                        <label for="short_description"
                                               class="float-left"><?php echo $term_short_description ?></label>
                                        <textarea id="short_description" name="short_description" maxlength="255" class="form-control"
                                                  placeholder="Enter Description" required></textarea>
                                        <span id="charactersRemaining"></span>
                                    </div>

                                    <div class="mt-3 text-left">
                                        <label for="min_requirement"
                                               class="float-left"><?php echo $term_min_requirement ?></label>
                                        <textarea id="min_requirement" name="min_requirement" class="form-control"
                                                  required></textarea>
                                    </div>

                                    <div class="row mt-3 border p-3 m-1">
                                        <div class="col-md-6">

                                            <label for="min_experience"><?php echo $term_stcw_req ?></label>
                                            <div class="form-group ">
                                                <!-- Material inline 1 -->
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" id="stcw_req1" class="form-check-input"
                                                           value="1" name="stcw_req" checked>
                                                    <label class="form-check-label" for="stcw_req1">Yes</label>
                                                </div>

                                                <!-- Material inline 2 -->
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" id="stcw_req2" class="form-check-input"
                                                           value="0" name="stcw_req">
                                                    <label class="form-check-label" for="stcw_req2">No</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="md-form">
                                                <label for="min_education"><?php echo $term_min_education ?></label>
                                                <div class="form-group ">
                                                    <select id="min_education" name="min_education" class="mdb-select" searchable="Search" required>
                                                        <option value=""><?php echo $term_table_select_please; ?></option>
                                                        <?php foreach ($min_education_list as $education) { ?>
                                                            <option value="<?php echo $education ?>"><?php echo ucfirst($education) ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="min_experience"
                                                       class=""><?php echo $term_min_experience ?></label>
                                                       <div class="input-group">
                                                            <input type="number" id="min_experience" name="min_experience"
                                                                class="form-control" min="0" max="99" value="0" required>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text" id="basic-addon2">Month</span>
                                                            </div>
                                                        </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="min_english_experience"
                                                       class="float-left"><?php echo $term_min_english_experience ?></label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control"
                                                           id="min_english_experience" name="min_english_experience"
                                                           min="0" max="99" value="0" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">Year</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 not-showing">
                                            <div class="form-group">
                                                <label for="min_salary"
                                                       class="float-left"><?php echo $term_min_salary ?></label>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">$</span>
                                                    </div>
                                                    <input type="number" class="form-control" id="min_salary"
                                                           name="min_salary" min="0" value="0" readonly required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 not-showing">
                                            <div class="form-group">
                                                <label for="max_salary"
                                                       class="float-left"><?php echo $term_max_salary ?></label>
                                                <div class="input-group">
                                                    <div class="">
                                                        <span class="input-group-text" id="basic-addon2">$</span>
                                                    </div>
                                                    <input type="number" class="form-control" id="max_salary"
                                                           name="max_salary" min="0" value="0" readonly required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdb-form mt-3 border py-3 px-3 country_section">
                                        <select id="country_id" name="countries[]"
                                                class="mdb-select md-form" multiple searchable="Search...">
                                                <option value="" disabled selected>All Country</option>
                                                <?php
                                            $code = array_keys($countries);
                                            foreach ($code as $key => $value) {
                                             ?>
                                                <option value="<?php echo $value;?>"><?php echo $countries[$value];?></option>  
                                            <?php
                                            }
                                           ?>
                                        </select>
                                        <label for="country_id">Country Access (*leave blank if access for all country)</label>
                                    </div>
                                    <div class="row mt-3 border p-3 m-0 principal_section">
                                        <div class="col-md-6">
                                            <select id="principal" name="principal"
                                                    class="mdb-select md-form">
                                                    <option value="">Select Principal</option>
                                                    <?php
                                                foreach ($principal as $key => $value) {
                                                ?>
                                                    <option value="<?php echo $value['code'];?>"><?php echo $value['code'];?>  - <?php echo $value['entity_family_name'];?></option>  
                                                <?php
                                                }
                                            ?>
                                            </select>
                                            <label for="principal">Principal</label>
                                        </div>
                                        <div class="col-md-6">
                                            <select id="brand" name="brand"
                                                    class="mdb-select md-form" searchable="Search...">
                                                    <option value="">Select Brand Code</option>
                                            </select>
                                            <label for="brand">Brand</label>
                                        </div>
                                        <div class="col-xl-12">
                                            <label for="control-label">Job Master</label>
                                            <select id="job_master" name="job_masters[]" multiple='multiple'
                                                    class="form-control">
                                                    <!-- <option value="" disabled selected>Select Job Master</option> -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-3 py-3 banner_section">
                                        <div id="banner_image" class="card mb-4">

                                            <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                                <i class="fas fa-image"></i> Banner Image
                                            </h4>

                                            <div class="card-body">
                                                <!-- <div id="curr_banner_add" class="text-center not-showing">
                                                        <img class="img-fluid" id="banner_current_img_add" src="" alt="Current  Image" >
                                                        <button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop_banner">Crop Photo</button>
                                                    <hr>
                                                </div> -->
                                                <div class="form-group">
                                                    <label for="banner_input">Select Image</label>
                                                    <input type="file" class="col-12" id="banner_input" name="cover_image" accept=".jpg,.png,.gif" >
                                                    <input type="hidden" id="banner_base64" name="banner_base64">
                                                </div>

                                                <!-- <div id="banner_croppie_wrap" class="mw-100 w-auto mh-100 h-auto not-showing">
                                                    <div id="banner_croppie" data-banner-width="683" data-banner-height="281"></div>
                                                </div> -->

                                                <!-- <button class="btn btn-default btn-block not-showing" type="button" id="banner_result">Crop</button> -->

                                            </div>

                                        </div>
                                    </div>
                                    <!-- Send button -->
                                    <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect"
                                            id="add_job_btn" type="submit">Send
                                    </button>
                                </form>
                                <!-- Form -->
                            </div>
                        </div>
                    </div>
                </div><!-- InsertModal -->

                <!-- EditModal -->
                <div class="modal fade" id="edit_job_modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="EditModal"><?php echo $term_legend_edit ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">

                                <!-- Form -->
                                <form id="form_edit" enctype="multipart/form-data" class="" method="post" style="color: #757575;"
                                      action="<?php echo $myURL; ?>">
                                    <input type="hidden" name="old_job_speedy_code" id="old_job_speedy_code">
                                    <!-- Job Code -->
                                    <div class="md-form mt-3">
                                        <div class="float-right mr-4">
                                            <div id="e_job_code_spinner"
                                                 class="not-showing spinner-border position-absolute" role="status"
                                                 aria-hidden="true"></div>
                                            <div id="e_job_code_valid"
                                                 class="not-showing fa fa-lg fa-check mt-2 position-absolute text-success"
                                                 role="status" aria-hidden="true"></div>
                                        </div>
                                        <input type="text" id="e_job_speedy_code" name="e_job_speedy_code"
                                               class="form-control" minlength="3" maxlength="4" required>
                                        <label for="e_job_speedy_code"><?php echo $term_job_speedy_code ?></label>
                                        <div id="e_job_speedy_code_help" class="not-showing alert alert-warning "></div>
                                    </div>

                                    <!-- Job Name -->
                                    <div class="md-form mt-3">
                                        <input type="text" id="e_job_title" name="e_job_title" class="form-control"
                                               required>
                                        <label for="e_job_title"><?php echo $term_job_title ?></label>
                                    </div>

                                    <div class="md-form mt-3">
                                        <select id="e_job_speedy_category_id" name="e_job_speedy_category_id"
                                                class="mdb-select md-form" required searchable="Search">
                                            <option value="">Select Category</option>
                                            <?php foreach ($job_categories as $category) { ?>
                                                <?php if ($category['parent_id'] == 0) { ?>
                                                    <option value="<?php echo $category['job_speedy_category_id'] ?>"><?php echo $category['name'] ?></option>
                                                    <?php foreach ($job_categories as $category2) { ?>
                                                        <?php if ($category2['parent_id'] == $category['job_speedy_category_id']) { ?>
                                                            <option value="<?php echo $category2['job_speedy_category_id'] ?>"> &nbsp;&nbsp;<?php echo $category2['name'] ?></option>
                                                        <?php } ?>
                                                    <?php } ?>

                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <label for="job_speedy_category_id"><?php echo $term_job_speedy_category_id ?></label>
                                    </div>

                                    <!-- Job Description -->
                                    <div class="mt-3 text-left">
                                        <label for="e_short_description"
                                               class="float-left"><?php echo $term_short_description ?></label>
                                        <textarea id="e_short_description" required maxlength="255" name="e_short_description"
                                                  class="form-control" placeholder="Enter Description"
                                                  required></textarea>
                                        <span id="charactersRemaining2"></span>
                                    </div>

                                    <div class="mt-3 ">
                                        <label for="e_min_requirement"><?php echo $term_min_requirement ?></label>
                                        <textarea id="e_min_requirement" name="min_requirement" class="form-control"
                                                  required></textarea>
                                    </div>

                                    <div class="row mt-3 border p-3 m-1">
                                        <div class="col-md-6">
                                            <label for="e_min_experience"><?php echo $term_stcw_req ?></label>
                                            <div class="form-group ">
                                                <!-- Material inline 1 -->
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" id="e_stcw_req1" class="form-check-input"
                                                           value="1" name="e_stcw_req" checked>
                                                    <label class="form-check-label" for="e_stcw_req1">Yes</label>
                                                </div>

                                                <!-- Material inline 2 -->
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" id="e_stcw_req2" class="form-check-input"
                                                           value="0" name="e_stcw_req">
                                                    <label class="form-check-label" for="e_stcw_req2">No</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="md-form">
                                                <label for="e_min_education"><?php echo $term_min_education ?></label>
                                                <div class="form-group ">
                                                    <select id="e_min_education" name="e_min_education" class="mdb-select" required>
                                                        <option value=""><?php echo $term_table_select_please; ?></option>
                                                        <?php foreach ($min_education_list as $education) { ?>
                                                            <option value="<?php echo $education ?>"><?php echo ucfirst($education) ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="e_min_experience"
                                                       class=""><?php echo $term_min_experience ?></label>
                                                       <div class="input-group">
                                                            <input type="number" id="e_min_experience" name="e_min_experience"
                                                                class="form-control" min="0" max="99" value="0" required>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text" id="basic-addon2">Month</span>
                                                            </div>
                                                        </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="e_min_english_experience"
                                                       class="float-left"><?php echo $term_min_english_experience ?></label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control"
                                                           id="e_min_english_experience" name="e_min_english_experience"
                                                           min="0" max="50" value="0" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">Year</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3 border p-3 m-1">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="e_min_salary"
                                                       class="float-left"><?php echo $term_min_salary ?></label>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">USD</span>
                                                    </div>
                                                    <input type="number" readonly class="form-control" id="e_min_salary"
                                                           name="e_min_salary" step="0.01" min="0" value="0" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="e_max_salary"
                                                       class="float-left"><?php echo $term_max_salary ?></label>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon1">USD</span>
                                                    </div>
                                                    <input type="number" readonly class="form-control" id="e_max_salary"
                                                           name="e_max_salary" step="0.01" min="0" value="0" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdb-form mt-3 border py-3 px-3 country_section">
                                        <select id="e_country_id" name="e_countries[]"
                                                class="mdb-select md-form" multiple searchable="Search...">
                                                <option value="" disabled selected>All Country</option>
                                                <?php
                                            $code = array_keys($countries);
                                            foreach ($code as $key => $value) {
                                             ?>
                                                <option value="<?php echo $value;?>"><?php echo $countries[$value];?></option>  
                                            <?php
                                            }
                                           ?>
                                        </select>
                                        <label for="e_country_id">Country Access (*leave blank if access for all country)</label>
                                    </div>
                                    <div class="row mt-3 border p-3 m-0 principal_section">
                                        <div class="col-md-6">
                                            <select id="e_principal" name="e_principal"
                                                    class="mdb-select md-form">
                                                    <option value="">Select Principal</option>
                                                    <?php
                                                foreach ($principal as $key => $value) {
                                                ?>
                                                    <option value="<?php echo $value['code'];?>"><?php echo $value['code'];?> - <?php echo $value['entity_family_name'];?></option>  
                                                <?php
                                                }
                                            ?>
                                            </select>
                                            <label for="e_principal">Principal</label>
                                        </div>
                                        <div class="col-md-6">
                                            <select id="e_brand" name="e_brand"
                                                    class="mdb-select md-form" searchable="Search...">
                                                    <option value="">Select Brand Code</option>
                                            </select>
                                            <label for="e_brand">Brand</label>
                                        </div>
                                        <div class="col-xl-12">
                                            <label for="control-label">Job Master</label>
                                            <select id="e_job_master" name="e_job_masters[]" multiple='multiple'
                                                    class="form-control">
                                                    
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-3 py-3 banner_section">
                                        <div id="banner_image" class="card mb-4">

                                            <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                                <i class="fas fa-image"></i> Banner Image
                                            </h4>

                                            <div class="card-body">
                                                <input type="hidden" name="image_prev" id="image_prev">
                                                <div id="current_cover_image" class="text-center not-showing">
                                                        <img class="img-fluid" src="" alt="Current Cover  Image" >
                                                        <!-- <button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop_banner">Crop Photo</button> -->
                                                    <hr>
                                                </div>
                                                <div class="form-group">
                                                    <label for="e_banner_input">Select Image</label>
                                                    <input type="file" class="col-12" id="e_banner_input" name="e_cover_image" accept=".jpg,.png,.gif" >
                                                    <input type="hidden" id="e_banner_base64" name="e_banner_base64">
                                                </div>

                                                <!-- <div id="banner_croppie_wrap" class="mw-100 w-auto mh-100 h-auto not-showing">
                                                    <div id="banner_croppie" data-banner-width="683" data-banner-height="281"></div>
                                                </div> -->

                                                <!-- <button class="btn btn-default btn-block not-showing" type="button" id="banner_result">Crop</button> -->

                                            </div>

                                        </div>
                                    </div>
                                    <!-- Send button -->
                                    <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect"
                                            id="edit_job_btn" type="submit">Edit
                                    </button>
                                </form>
                                <!-- Form -->
                            </div>
                        </div>
                    </div>
                </div><!-- EditModal -->
            </div>
        </div>
    </div>
</section>