
<!-- start of pages edit content standard entry-<?php echo $key ?> -->

<div class="card chart-card entry-content not-showing" id="entry-<?php echo $key ?>">

    <!-- Card content -->
    <div class="card-body pb-0">

        <!-- Title -->

        <div class="d-flex justify-content-between align-items-center">
            <h4 class="card-title font-weight-bold" id="title-heading-entry-<?php echo $key ?>"><?php echo $value['heading'] ?></h4>
            <button id="content-delete-<?php echo $key ?>" class="btn btn-sm btn-danger content-delete" data-id="<?php echo $key ?>" type="button"><?php echo $term_section_delete_button ?></button>
        </div>
        <hr>    
        <!-- Text -->

        <div >
            <div class="tab-content p-0">

                <div >
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab-<?php echo $key ?>" data-toggle="tab" href="#content_tab_<?php echo $key ?>" role="tab"
                               aria-controls="home"
                               aria-selected="true">Default Content</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="files-tab-<?php echo $key ?>" data-toggle="tab" href="#file_tab_<?php echo $key ?>" role="tab"
                               aria-controls="profile"
                               aria-selected="false">Images & Files</a>
                        </li>
                    </ul>
                    <div class="tab-content">

                        <!--content part-->
                        <div class="tab-pane fade show active" id="content_tab_<?php echo $key ?>" role="tabpanel" aria-labelledby="home-tab-<?php echo $key ?>">

                            <form id="form-entry-<?php echo $key ?>" class="contentForm" method="post" action="#" role="form" enctype="multipart/form-data">

                                <input name="content_name" type="hidden" value="entry-<?php echo $key ?>">

                                <div class="form-group">
                                    <label><?php echo $term_content_type_label ?></label>
                                    <select id="content-type-entry-<?php echo $key ?>" data-id="<?php echo $key ?>"  class="md-form form-control content-type-entry" name="content_type">
                                        <option value="article"<?php if($value['content_type'] == 'article') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_article_label ?></option>
                                        <option value="banner"<?php if($value['content_type'] == 'banner') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_banner_label ?></option>
                                        <option value="banner_top"<?php if($value['content_type'] == 'banner_top') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_banner_top_label ?></option>
                                        <option value="contact_form" <?php if($value['content_type'] == 'contact_form') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_contact_form_label ?></option>
                                        <option value="gallery"<?php if($value['content_type'] == 'gallery') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_gallery_label ?></option>
                                        <option value="slideshow"<?php if($value['content_type'] == 'slideshow') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_slideshow_label ?></option>
                                        <option value="testimonial"<?php if($value['content_type'] == 'testimonial') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_testimonial_label ?></option>
                                        <option value="text_box" <?php if($value['content_type'] == 'text_box') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_textbox_label ?></option>
                                        
                                        <?php
                                        if(PAGES_REGISTER_USE)
                                        {
                                            ?>
                                            <option value="register"<?php if($value['content_type'] == 'register') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_register_label ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div id="image_position_area_<?php echo $key ?>" class="border p-3" <?php echo ($value['content_type'] == 'banner' || $value['content_type'] == 'banner_top' || $value['content_type'] == 'gallery'|| $value['content_type'] == 'slideshow' )? 'not-showing':'' ?>">
                                    <label><?php echo $term_image_position_label ?></label><br>
                                    <!-- Material inline 1 -->
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="image_position_<?php echo $key ?>"
                                            <?php echo (!empty($value['image_position']) && $value['image_position'] == 'left')? 'checked' : '' ?>
                                            class="form-check-input image_position_<?php echo $key ?>"
                                            id="checkbox_left_<?php echo $key ?>" value="left">
                                        <label class="form-check-label" for="checkbox_left_<?php echo $key ?>">LEFT</label>
                                    </div>

                                    <!-- Material inline 2 -->
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="image_position_<?php echo $key ?>"
                                            <?php echo (!empty($value['image_position']) && $value['image_position'] == 'right')? 'checked' : '' ?>
                                            class="form-check-input image_position_<?php echo $key ?>"
                                            id="checkbox_right_<?php echo $key ?>" value="right">
                                        <label class="form-check-label" for="checkbox_right_<?php echo $key ?>">RIGHT</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="image_position_<?php echo $key ?>"
                                            <?php echo (!empty($value['image_position']) && $value['image_position'] == 'top')? 'checked' : '' ?>
                                            class="form-check-input image_position_<?php echo $key ?>"
                                            id="checkbox_top_<?php echo $key ?>" value="top">
                                        <label class="form-check-label" for="checkbox_top_<?php echo $key ?>">TOP</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="image_position_<?php echo $key ?>"
                                            <?php echo (!empty($value['image_position']) && $value['image_position'] == 'bottom')? 'checked' : '' ?>
                                            class="form-check-input image_position_<?php echo $key ?>"
                                            id="checkbox_bottom_<?php echo $key ?>" value="bottom">
                                        <label class="form-check-label" for="checkbox_bottom_<?php echo $key ?>">BOTTOM</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="image_position_<?php echo $key ?>"
                                                <?php echo (!empty($value['image_position']) && $value['image_position'] == 'background')? 'checked' : '' ?>
                                            class="form-check-input image_position_<?php echo $key ?>"
                                            id="checkbox_bg_<?php echo $key ?>" value="background">
                                        <label class="form-check-label" for="checkbox_bg_<?php echo $key ?>">BACKGROUND</label>
                                    </div>
                                </div>

                                <div class="md-form">
                                    <input id="show_heading-entry-<?php echo $key ?>" name="show_heading" type="checkbox" class="form-check-input" value="1" <?php if($value['show_heading'] == 1) echo 'checked'; ?>>
                                    <label for="show_heading-entry-<?php echo $key ?>"><?php echo $term_heading_show_label; ?></label>
                                </div>

                                <div class="form-group">
                                    <label><?php echo $term_heading_label ?></label>
                                    <input id="heading-entry-<?php echo $key ?>" class="form-control" name="heading" type="text" maxlength="100" value="<?php echo $value['heading'] ?>"/>
                                </div>


                                <div class="form-group">
                                    <label><?php echo $term_sdesc_label ?></label>
                                    <input id="sdesc-entry-<?php echo $key ?>" class="form-control" name="sdesc" type="text" maxlength="255" value="<?php echo $value['sdesc'] ?>"/>
                                </div>

                                <div class="form-group">
                                    <label><?php echo $term_text_label ?></label>
                                    <textarea id="content-entry-<?php echo $key ?>" class="section_text form-control content-text-entry-<?php echo $key ?>" name="content"><?php echo $value['content'] ?></textarea>
                                </div>
                                <?php
                                $file = DIR_MODULE_VIEWS.'/edit/content/contact_form.php';
                                require $file;
                                ?>
                                <div class="form-group">
                                    <button id="content-submit-<?php echo $key ?>" data-id="<?php echo $key ?>" class="btn btn-primary content-submit" type="submit"><?php echo $term_section_update_button ?></button>
                                </div>

                            </form>

                        </div>

                        <!--file part-->
                        <div class="tab-pane fade" id="file_tab_<?php echo $key ?>" role="tabpanel" aria-labelledby="profile-tab-<?php echo $key ?>">

                            <div class="row">

                                <div class="col-12">

                                    <!-- Image and File Upload -->
                                    <div class="border border-warning rounded p-3">

                                        <form class="contentForm mt-3" method="post" action="#" role="form"
                                              enctype="multipart/form-data" data-section="<?php echo $key?>">

                                            <input id="link_id_<?php echo $key ?>" name="link_id" type="hidden" value="<?php echo $link_id ?>">
                                            <input id="section_<?php echo $key ?>" name="section" type="hidden" value="entry">
                                            <input id="entry_<?php echo $key ?>" name="entry" type="hidden" value="<?php echo $key?>">

                                            <!-- type -->
                                            <label class="mdb-main-label">Upload Type</label><br>
                                            <div class="form-check form-check-inline">
                                                <?php
                                                        $html = '';
                                                        if ($value['content_type'] == 'banner' || $value['content_type'] == 'banner_top' || $value['content_type'] == 'gallery'|| $value['content_type'] == 'slideshow' )
                                                        {
                                                            $html .="<input id='radio1_".$key."' class='form-check-input' type='radio' name='file_type_".$key."' value='image' checked>";
                                                            $html .="<label for='radio1_".$key."' class='form-check-label'>Image</label>";
                                                            $html .="<input id='radio2_".$key."' class='form-check-input' type='radio' name='file_type_".$key."' value='file'>";
                                                            $html .="<label for='radio2_".$key."' class='form-check-label'>File</label>";
                                                        }else{
                                                            $html .="<input id='radio1_".$key."' class='form-check-input' type='radio' name='file_type_".$key."' value='image'>";
                                                            $html .="<label for='radio1_".$key."' class='form-check-label'>Image</label>";
                                                            $html .="<input id='radio2_".$key."' class='form-check-input' type='radio' name='file_type_".$key."' value='file'>";
                                                            $html .="<label for='radio2_".$key."' class='form-check-label'>File</label>";
                                                        }
                                                        echo $html;
                                                    ?>
                                            </div>
                                            
                                            <!-- type -->

                                            <div class="file-field">

                                                <div class="btn btn-primary btn-sm float-left">
                                                    <span><?php echo $term_file_choose ?></span>
                                                    <input data-type="file_type_<?php echo $key ?>" name="file" class="file" type="file"
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
                                    <a class="nav-link active" data-toggle="tab" href="#images-<?php echo $key ?>" role="tab"
                                       aria-controls="images"
                                       aria-selected="true">Images</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#files-<?php echo $key ?>" role="tab"
                                       aria-controls="files"
                                       aria-selected="false">Files</a>
                                </li>
                            </ul>
                            <div class="tab-content" >
                                <div class="tab-pane fade show active" id="images-<?php echo $key ?>" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="border rounded mt-4 p-3">
                                        <div class="list-group-flush list-files-sortable" id="list-image-<?php echo $key ?>">
                                            <?php
                                            if(isset($pageContentFileViewArray[$key]) && isset($pageContentFileViewArray[$key]['image'])) {
                                                foreach ($pageContentFileViewArray[$key]['image'] as $id => $value) {
                                                    ?>
                                                    <div class="list-group-item "
                                                         id="file_wrapper_<?php echo $value['file_manager_id']; ?>"
                                                         data-id="<?php echo $value['file_manager_id']; ?>"
                                                         data-model="<?php echo $value['model']?>"
                                                         data-model_id="<?php echo $value['model_id']?>">

                                                        <div class="row">

                                                            <div class="col-4">
                                                                <img class="img-fluid"
                                                                     src="<?php echo $value['image_prefix'] . '/page/' . $value['file_name']; ?>"
                                                                     alt="<?php echo $value['title']; ?>">
                                                            </div>

                                                            <div class="col-8">
                                                                <div class="form-group">
                                                                    <input name="title"
                                                                           id="title_<?php echo $value['file_manager_id'] ?>"
                                                                           type="text" class="form-control"
                                                                           value="<?php echo $value['title']; ?>"
                                                                           size="100">
                                                                </div>
                                                                <div class="form-group">
                                                                    <input name="sdesc"
                                                                           id="sdesc_<?php echo $value['file_manager_id'] ?>"
                                                                           type="text" class="form-control"
                                                                           value="<?php echo $value['sdesc']; ?>"
                                                                           size="254">
                                                                </div>
                                                                <div class="md-form">
                                                                    <input name="active_<?php echo $value['file_manager_id'] ?>"
                                                                           id="status_<?php echo $value['file_manager_id'] ?>"
                                                                           type="checkbox" class="form-check-input"
                                                                           value="1" <?php if ($value['status'] == 1) echo 'checked'; ?>>
                                                                    <label for="status_<?php echo $value['file_manager_id'] ?>"><?php echo $term_image_active_label; ?></label>
                                                                </div>
                                                                <div class="md-form">
                                                                    <button class="btn-floating btn-sm btn-success update_file"                                                                    
                                                                            data-sequence="<?php echo $value['file_manager_id'] ?>" 
                                                                            data-file-id="<?php echo $value['file_manager_id'] ?>">
                                                                        <i class="fas fa-edit"></i></button>
                                                                    <button class="btn-floating btn-sm btn-danger delete_file"
                                                                            data-file-id="<?php echo $value['file_manager_id'] ?>">
                                                                        <i class="fas fa-trash"></i></button>
                                                                </div>

                                                            </div>
                                                        </div>

                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="files-<?php echo $key ?>" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="border rounded mt-4 p-3">
                                        <div class="list-group-flush list-files-sortable" id="list-file-<?php echo $key ?>">
                                            <?php
                                            if(isset($pageContentFileViewArray[$key]) && isset($pageContentFileViewArray[$key]['file'])) {
                                                foreach ($pageContentFileViewArray[$key]['file'] as $id => $value) {
                                                    ?>
                                                    <div class="list-group-item "
                                                         id="file_wrapper_<?php echo $value['file_manager_id']; ?>"
                                                         data-id="<?php echo $value['file_manager_id']; ?>"
                                                         data-model="<?php echo $value['model']?>"
                                                         data-model_id="<?php echo $value['model_id']?>">

                                                        <div class="row">

                                                            <div class="col-4">
                                                                <a href="<?php echo $value['file_prefix'] . '/' . $value['file_name']; ?>"
                                                                   title="<?php echo $value['sdesc']; ?>"><?php echo $value['file_name']; ?></a>
                                                            </div>

                                                            <div class="col-8">

                                                                <div class="form-group">
                                                                    <input name="title"
                                                                           id="title_<?php echo $value['file_manager_id'] ?>"
                                                                           type="text" class="form-control"
                                                                           value="<?php echo $value['title']; ?>"
                                                                           size="100">
                                                                </div>

                                                                <div class="form-group">
                                                                    <input name="sdesc"
                                                                           id="sdesc_<?php echo $value['file_manager_id'] ?>"
                                                                           type="text" class="form-control"
                                                                           value="<?php echo $value['sdesc']; ?>"
                                                                           size="254">
                                                                </div>
                                                                <div class="md-form">

                                                                    <input name="active_<?php echo $value['file_manager_id'] ?>"
                                                                           id="status_<?php echo $value['file_manager_id'] ?>"
                                                                           type="checkbox" class="form-check-input"
                                                                           value="1" <?php if ($value['status'] == 1) echo 'checked'; ?>>
                                                                    <label for="status_<?php echo $value['file_manager_id'] ?>"><?php echo $term_image_active_label; ?></label>
                                                                </div>

                                                                <div class="md-form">
                                                                    <button class="btn-floating btn-sm btn-success update_file"
                                                                            data-file-id="<?php echo $value['file_manager_id'] ?>"
                                                                            data-sequence="<?php echo $value['sequence'] ?>">
                                                                        <i class="fas fa-edit"></i></button>
                                                                    <button class="btn-floating btn-sm btn-danger delete_file"
                                                                            data-file-id="<?php echo $value['file_manager_id'] ?>">
                                                                        <i class="fas fa-trash"></i></button>
                                                                </div>

                                                            </div>
                                                        </div>

                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
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
    </div>
</div>

<!-- end of pages edit content standard entry-<?php echo $key ?> -->

