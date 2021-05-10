<!-- Card -->
<div class="card chart-card" id="entry-0">

    <!-- Card content -->
    <div class="card-body pb-5">

        <!-- Title -->
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="card-title font-weight-bold" id="title-heading-entry-0">New Content</h4>
            <button id="content-delete-0" class="btn btn-sm btn-danger content-delete" data-id="0" type="button"><?php echo $term_section_delete_button ?></button>
        </div>

        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab-0" data-toggle="tab" href="#content_tab_0" role="tab"
                   aria-controls="home"
                   aria-selected="true">Default Content</a>
            </li>
            <li class="nav-item file_tab_0">
                <a class="nav-link" id="files-tab-0" data-toggle="tab" href="#file_tab_0" role="tab"
                   aria-controls="profile"
                   aria-selected="false">Images & File</a>
            </li>
        </ul>

        <div >
            <div class="tab-content">

                <!--content part-->
                <div class="tab-pane fade show active" id="content_tab_0" role="tabpanel" aria-labelledby="home-tab-0">

                    <form id="form-entry-0" class="contentForm" method="post" action="#" role="form" enctype="multipart/form-data">

                        <input name="content_name" type="hidden" value="entry-0">

                        <div class="form-group">
                            <label><?php echo $term_content_type_label ?></label>
                            <select id="content-type-entry-0" data-id="0" class="md-form form-control content-type-entry" name="content_type">
                                <option value="article"><?php echo $term_content_type_article_label ?></option>
                                <option value="banner"><?php echo $term_content_type_banner_label ?></option>
                                <option value="banner_top"><?php echo $term_content_type_banner_top_label ?></option>
                                <option value="contact_form" ><?php echo $term_content_type_contact_form_label ?></option>
                                <option value="gallery"><?php echo $term_content_type_gallery_label ?></option>
                                <option value="slideshow"><?php echo $term_content_type_slideshow_label ?></option>
                                <option value="testimonial"><?php echo $term_content_type_testimonial_label ?></option>
                                <option value="text_box"><?php echo $term_content_type_textbox_label ?></option>
                                <?php
                                if(PAGES_REGISTER_USE)
                                {
                                    ?>
                                    <option value="register"><?php echo $term_content_type_register_label ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div id="image_position_area_0" class="border p-3 d-none">
                            <label><?php echo $term_image_position_label ?></label><br>
                            <!-- Material inline 1 -->
                            <div class="form-check form-check-inline">
                                <input type="radio" name="image_position_0"
                                    <?php echo (!empty($value['image_position']) && $value['image_position'] == 'left')? 'checked' : '' ?>
                                    class="form-check-input image_position_0"  value="left" id="checkbox_left_0" >
                                <label class="form-check-label" for="checkbox_left_0">LEFT</label>
                            </div>

                            <!-- Material inline 2 -->
                            <div class="form-check form-check-inline">
                                <input type="radio" name="image_position_0"
                                    <?php echo (!empty($value['image_position']) && $value['image_position'] == 'right')? 'checked' : '' ?>
                                    class="form-check-input image_position_0" id="checkbox_right_0" value="right">
                                <label class="form-check-label" for="checkbox_right_0">RIGHT</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="radio" name="image_position_0" 
                                    <?php echo (!empty($value['image_position']) && $value['image_position'] == 'top')? 'checked' : '' ?>
                                    class="form-check-input image_position_0" id="checkbox_top_0" value="top">
                                <label class="form-check-label" for="checkbox_top_0">TOP</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="radio" name="image_position_0"
                                    <?php echo (!empty($value['image_position']) && $value['image_position'] == 'bottom')? 'checked' : '' ?>
                                    class="form-check-input image_position_0" id="checkbox_bottom_0" value="bottom">
                                <label class="form-check-label" for="checkbox_bottom_0">BOTTOM</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="radio" name="image_position_0" 
                                    <?php echo (!empty($value['image_position']) && $value['image_position'] == 'background')? 'checked' : '' ?>
                                    class="form-check-input image_position_0" id="checkbox_bg_0" value="background">
                                <label class="form-check-label" for="checkbox_bg_0">BACKGROUND</label>
                            </div>
                        </div>

                        <div class="md-form">
                            <input id="show_heading-entry-0" name="show_heading" type="checkbox" class="form-check-input" value="1" >
                            <label for="show_heading-entry-0"><?php echo $term_heading_show_label; ?></label>
                        </div>

                        <div class="form-group">
                            <label><?php echo $term_heading_label ?></label>
                            <input id="heading-entry-0" class="form-control" name="heading" type="text" maxlength="100" value=""/>
                        </div>

                        <div class="form-group">
                            <label><?php echo $term_sdesc_label ?></label>
                            <input id="sdesc-entry-0" class="form-control" name="sdesc" type="text" maxlength="255" value=""/>
                        </div>

                        <div class="form-group">
                            <label><?php echo $term_text_label ?></label>
                            <textarea id="content-entry-0" class="section_text_new form-control content-text-entry" name="content"></textarea>
                        </div>
                        <?php
                        //get the template required
                        $key = 0;
                        $file = DIR_MODULE_VIEWS.'/edit/content/contact_form.php';
                        require $file;
                        ?>
                        <div class="form-group">
                            <button id="content-submit-0" data-id="0" class="btn btn-primary content-submit" type="submit"><?php echo $term_section_update_button ?></button>
                        </div>

                    </form>

                </div>

                <div class="tab-pane fade " id="file_tab_0" role="tabpanel" aria-labelledby="profile-tab-0">

                    <div class="row">

                        <div class="col-12">

                            <!-- Image and File Upload -->
                            <div class="border border-warning rounded p-3">

                                <form class="contentForm mt-3" method="post" action="#" role="form"
                                      enctype="multipart/form-data" data-section="<?php echo $next_content_id?>">

                                    <input id="link_id_0" name="link_id" type="hidden" value="<?php echo $link_id ?>">
                                    <input id="section_0" name="section" type="hidden" value="entry">
                                    <input id="entry_0" name="entry" type="hidden" value="<?php echo $next_content_id?>">

                                    <!-- type -->
                                    <label for="file_type" class="mdb-main-label">Upload Type</label><br>
                                    <div class="form-check form-check-inline">
                                        <input id="radio1_0" class="form-check-input file_type_0" type="radio" name="file_type_0" value="image">
                                        <label for="radio1_0" class="form-check-label">Image</label>
                                        <input id="radio2_0" class="form-check-input file_type_0" type="radio" name="file_type_0" value="file">
                                        <label for="radio2_0" class="form-check-label">File</label>
                                    </div>
                                
                                    
                                    <!-- type -->

                                    <div class="file-field">

                                        <div class="btn btn-primary btn-sm float-left">
                                            <span><?php echo $term_file_choose ?></span>
                                            <input data-type="file_type_0" name="file" class="file" type="file"
                                                   accept="application/pdf, image/jpg, image/jpeg, image/gif, image/png, image/vnd.wap.wbmp" required>
                                        </div>

                                        <div class="file-path-wrapper">
                                            <input class="file-path validate" type="text"
                                                   placeholder="<?php echo $term_file_upload ?>" readonly>
                                        </div>

                                    </div>

                                    <div class="form-group mt-3">
                                        <label><?php echo $term_file_title_label ?></label>
                                        <input class="form-control" name="title" type="text"
                                               maxlength="100"/>
                                    </div>

                                    <div class="form-group">
                                        <label><?php echo $term_file_sdesc_label ?></label>
                                        <input  class="form-control" name="sdesc" type="text"
                                                maxlength="255"/>
                                    </div>

                                    <div class="md-form right">
                                        <input  name="status" type="checkbox" class="form-check-input"
                                                value="1" checked>
                                        <label for="status"><?php echo $term_file_status_label; ?></label>
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-primary btn-block btn-submit"
                                                type="submit"><?php echo $term_file_submit ?></button>
                                    </div>

                                </form>

                            </div>
                            <!-- file and File Upload -->
                        </div>

                    </div>

                    <!-- Start Images and Files for Page -->

                    <ul class="nav nav-tabs mt-5" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#images-0" role="tab"
                               aria-controls="images"
                               aria-selected="true">Images</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#files-0" role="tab"
                               aria-controls="files"
                               aria-selected="false">Files</a>
                        </li>
                    </ul>
                    <div class="tab-content" >
                        <div class="tab-pane fade show active" id="images-0" role="tabpanel" aria-labelledby="home-tab">
                            <div class="border rounded mt-4 p-3">
                                <div class="list-group-flush list-files-sortable" id="list-image-0">

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="files-0" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="border rounded mt-4 p-3">
                                <div class="list-group-flush list-files-sortable" id="list-file-0">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Images and Files -->
                </div>
            </div>
        </div>
    </div>


</div>