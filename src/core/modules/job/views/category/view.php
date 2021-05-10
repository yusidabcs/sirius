<section app="">
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-center"><?php echo $term_local_job_header ?></h4>
                <div>
                    <a href="#" class="btn btn-success btn-sm " id="add_job_category_btn"> <?php echo $term_create_job_category ?></a>
                    <a href="#" class="btn btn-info btn-sm " id="import_job_category_btn"> <i class="fa fa-file-excel"></i> <?php echo $term_import_job_category ?></a>

                </div>
                <!-- Modal -->
                <div class="modal fade" id="importJobModal" tabindex="-1" role="dialog" aria-labelledby="exportJobModal"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form id="importjobmaster" enctype="multipart/form-data">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $term_import_job_category ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $term_input_import_job_category?></label>
                                        <input type="file" name="file" id="job_master_file" accept=".xls, .xlsx" class="form-control">
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

                <ul class="list-group list_job_category" id="list_job_category" data-parent="0">
                </ul>

                <input type="hidden" id="job_category_link" value="<?php echo $myURL?>" />

                <!-- InsertModal -->
                <div class="modal fade" id="add_job_category_modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="insertModal"><?php echo $term_add_category_title ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <!-- Form -->
                                <form id="add_job_category_form" enctype="multipart/form-data" class="text-center" method="post" style="color: #757575;" action="<?php echo $myURL; ?>">

                                    <!-- Name -->
                                    <div class="md-form mt-3">
                                        <input type="text" id="name" name="name" class="form-control" maxlength="100" required >
                                        <label for="name"><?php echo $term_category_name ?></label>
                                    </div>
                                    <div class="md-form mt-3">
                                        <textarea id="short_description" name="short_description" class="form-control" maxlength="255" ></textarea>
                                        <label for="short_description"><?php echo $term_category_short_description ?></label>
                                    </div>
                                    <!-- Parent -->
                                    <div class="mt-3 text-left">
                                        <label for="parent_id" class="float-left"><?php echo $term_category_parent?></label>
                                        <select id="parent_id" name="parent_id" class="form-control" required>
                                            <option value="0">Main Parent</option>
                                        </select>
                                    </div>

                                    <ul class="nav nav-tabs md-tabs justify-content-center mt-3" id="myTabMD" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link" id="banner-tab-md" data-toggle="tab" href="#banner-md" role="tab" aria-controls="profile-md"
                                               aria-selected="false">Banner</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" id="avatar-tab-md" data-toggle="tab" href="#avatar-md" role="tab" aria-controls="home-md"
                                               aria-selected="true">Avatar</a>
                                        </li>

                                    </ul>
                                    <div class="tab-content card pt-5" id="myTabContentMD">
                                        <div class="tab-pane fade show active" id="avatar-md" role="tabpanel" aria-labelledby="home-tab-md">
                                            <!-- banner photo -->
                                            <div id="avatar_image" class="card mb-4">

                                                <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                                    <i class="fas fa-image"></i> <?php echo $term_avatar_image_heading; ?>
                                                </h4>

                                                <div class="card-body">
                                                    <div id="curr_avatar_add" class="text-center not-showing">
                                                        <div class="">
                                                            <img class="img-fluid" id="avatar_current_img_add" src="" alt="Current  Image" >
                                                            <button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop_avatar">Crop Photo</button>
                                                        </div>
                                                        <hr>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="avatar_input"><?php echo $term_banner_image_choose_file; ?></label>
                                                        <input type="file" class="col-12" id="avatar_input" accept=".jpg,.png,.gif" >
                                                        <input type="hidden" id="avatar_base64" name="avatar_base64">
                                                    </div>

                                                    <div id="avatar_croppie_wrap" class="mw-100 w-auto mh-100 h-auto not-showing">
                                                        <div id="avatar_croppie" data-banner-width="500" data-banner-height="700"></div>
                                                    </div>

                                                    <button class="btn btn-default btn-block not-showing" type="button" id="avatar_result"><?php echo $term_banner_image_crop; ?></button>

                                                </div>

                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="banner-md" role="tabpanel" aria-labelledby="profile-tab-md">
                                            <!-- banner photo -->
                                            <div id="banner_image" class="card mb-4">

                                                <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                                    <i class="fas fa-image"></i> <?php echo $term_banner_image_heading; ?>
                                                </h4>

                                                <div class="card-body">
                                                    <div id="curr_banner_add" class="text-center not-showing">
                                                            <img class="img-fluid" id="banner_current_img_add" src="" alt="Current  Image" >
                                                            <button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop_banner">Crop Photo</button>
                                                        <hr>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="banner_input"><?php echo $term_banner_image_choose_file; ?></label>
                                                        <input type="file" class="col-12" id="banner_input" accept=".jpg,.png,.gif" >
                                                        <input type="hidden" id="banner_base64" name="banner_base64">
                                                    </div>

                                                    <div id="banner_croppie_wrap" class="mw-100 w-auto mh-100 h-auto not-showing">
                                                        <div id="banner_croppie" data-banner-width="683" data-banner-height="281"></div>
                                                    </div>

                                                    <button class="btn btn-default btn-block not-showing" type="button" id="banner_result"><?php echo $term_banner_image_crop; ?></button>

                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <!-- Send button -->
                                    <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect" id="add_job_btn" type="submit"><?php echo $term_save_btn ?> </button>
                                </form>
                                <!-- Form -->
                            </div>
                        </div>
                    </div>
                </div><!-- InsertModal -->

                <!-- EditModal -->
                <div class="modal fade" id="edit_job_category_modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="EditModal"><?php echo $term_edit_category_title ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">

                                <!-- Form -->
                                <form id="edit_job_category_form" class="text-center" method="post" style="color: #757575;" action="<?php echo $myURL; ?>">
                                    <input type="hidden" id="sequence" name="sequence" class="form-control" required>
                                    <input type="hidden" id="id" name="id" class="form-control" required>
                                    <!-- Name -->
                                    <div class="md-form mt-3">
                                        <input type="text" id="name_edit" name="name" class="form-control" maxlength="100" required>
                                        <label for="name_edit"><?php echo $term_category_name ?></label>
                                    </div>

                                    <div class="md-form mt-3">
                                        <textarea id="short_description_edit" name="short_description" class="form-control" maxlength="255" ></textarea>
                                        <label for="short_description_edit"><?php echo $term_category_short_description ?></label>
                                    </div>

                                    <!-- Parent -->
                                    <div class="mt-3 text-left">
                                        <label for="parent_id" class="float-left"><?php echo $term_category_parent?></label>
                                        <select id="parent_id" name="parent_id" class="form-control" required>
                                            <option value="0">Main Parent</option>
                                        </select>
                                    </div>

                                    <ul class="nav nav-tabs md-tabs justify-content-center mt-3" id="myTabMD2" role="tablist">

                                        <li class="nav-item">
                                            <a class="nav-link" id="banner-edit-tab-md" data-toggle="tab" href="#banner-edit-md" role="tab" aria-controls="profile-md"
                                               aria-selected="false">Banner</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" id="avatar-edit-tab-md" data-toggle="tab" href="#avatar-edit-md" role="tab" aria-controls="home-md"
                                               aria-selected="true">Avatar</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content card pt-5" id="myTabContentMD">
                                        <div class="tab-pane fade show active" id="avatar-edit-md" role="tabpanel" aria-labelledby="home-tab-md">
                                            <!-- banner photo -->
                                            <div id="avatar_image_edit" class="card mb-4">

                                                <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                                    <i class="fas fa-image"></i> <?php echo $term_avatar_image_heading; ?>
                                                </h4>

                                                <div class="card-body">

                                                    <!-- banner image if any -->
                                                    <div class="not-showing">
                                                        <img id="avatar_current_img" src="" alt="Current  Image" >
                                                        <input type="hidden" id="avatar_current" name="avatar_current" value="">
                                                        <button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop_avatar_edit">Crop Photo</button>
                                                    </div>

                                                    <hr>

                                                    <div class="form-group">
                                                        <label for="avatar_edit_input"><?php echo $term_banner_image_choose_file; ?></label>
                                                        <input type="file" class="col-12" id="avatar_edit_input" accept=".jpg,.png,.gif" >
                                                        <input type="hidden" id="avatar_edit_base64" name="avatar_base64">
                                                    </div>

                                                    <div id="avatar_edit_croppie_wrap" class="mw-100 w-auto mh-100 h-auto not-showing">
                                                        <div id="avatar_edit_croppie" data-banner-width="500" data-banner-height="700"></div>
                                                    </div>

                                                    <button class="btn btn-default btn-block not-showing" type="button" id="avatar_edit_result"><?php echo $term_banner_image_crop; ?></button>

                                                </div>

                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="banner-edit-md" role="tabpanel" aria-labelledby="profile-tab-md">
                                            <!-- banner photo -->
                                            <div id="banner_image_edit" class="card mb-4">

                                                <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                                    <i class="fas fa-image"></i> <?php echo $term_banner_image_heading; ?>
                                                </h4>

                                                <div class="card-body">

                                                    <!-- banner image if any -->
                                                    <div class="not-showing">
                                                        <img id="banner_current_img" src="" alt="Current  Image" >
                                                        <input type="hidden" id="banner_current" name="banner_current" value="">
                                                        <button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop_banner_edit">Crop Photo</button>
                                                    </div>

                                                    <hr>

                                                    <div class="form-group">
                                                        <label for="banner_edit_input"><?php echo $term_banner_image_choose_file; ?></label>
                                                        <input type="file" class="col-12" id="banner_edit_input" accept=".jpg,.png,.gif" >
                                                        <input type="hidden" id="banner_edit_base64" name="banner_base64">
                                                    </div>

                                                    <div id="banner_edit_croppie_wrap" class="mw-100 w-auto mh-100 h-auto not-showing">
                                                        <div id="banner_edit_croppie" data-banner-width="683" data-banner-height="281"></div>
                                                    </div>

                                                    <button class="btn btn-default btn-block not-showing" type="button" id="banner_edit_result"><?php echo $term_banner_image_crop; ?></button>

                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <!-- Send button -->
                                    <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect" id="edit_job_btn" type="submit"><?php echo $term_update_btn ?> </button>
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