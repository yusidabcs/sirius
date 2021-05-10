
<!-- start of pages edit content standard entry-<?php echo $key ?> -->

<li id="entry-<?php echo $key ?>">
    <div class="jumbotron">
        <p class="sort_handle">
            <button id="button-collapse-entry-<?php echo $key ?>" class="btn btn-info iow-toggle-btn" type="button" data-toggle="collapse" data-target="#collapse-entry-<?php echo $key ?>" ariacontrols="collapse-entry-<?php echo $key ?>" aria-expanded="false"> <i class="fas fa-minus-square" aria-hidden="true"></i> <?php echo $term_content_entry_section_label ?></button>
            <span id="title_heading-entry-<?php echo $key ?>"><?php echo $value['heading'] ?></span>
        </p>
        <div id="collapse-entry-<?php echo $key ?>" class="collapse">
            <div class="contentBox">

                <form id="form-entry-<?php echo $key ?>" class="contentForm" method="post" action="#" role="form" enctype="multipart/form-data">

                    <input name="content_name" type="hidden" value="entry-<?php echo $key ?>">

                    <div class="form-group">
                        <label><?php echo $term_content_type_label ?></label>
                        <select  class="md-form mdb-select content-type-entry" name="content_type">
                            <option value="article"<?php if($value['content_type'] == 'article') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_article_label ?></option>
                            <option value="banner"<?php if($value['content_type'] == 'banner') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_banner_label ?></option>
                            <option value="banner_top"<?php if($value['content_type'] == 'banner_top') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_banner_top_label ?></option>
                            <option value="contact_form" <?php if($value['content_type'] == 'contact_form') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_contact_form_label ?></option>
                            <option value="gallery"<?php if($value['content_type'] == 'gallery') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_gallery_label ?></option>
                            <option value="slideshow"<?php if($value['content_type'] == 'slideshow') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_slideshow_label ?></option>
                            <option value="subpages"<?php if($value['content_type'] == 'subpages') { echo ' selected="selected"'; } ?>><?php echo $term_content_type_subpages_label ?></option>
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

                    <div class="md-form">
                        <input id="show_heading-entry-<?php echo $key ?>" name="show_heading" type="checkbox" class="form-check-input" value="1" <?php if($value['show_heading'] == 1) echo 'checked'; ?>>
                        <label for="show_heading-entry-<?php echo $key ?>"><?php echo $term_content_heading_show_label; ?></label>
                    </div>

                    <div class="form-group">
                        <label><?php echo $term_content_heading_label ?></label>
                        <input id="heading-entry-<?php echo $key ?>" class="form-control" name="heading" type="text" maxlength="100" value="<?php echo $value['heading'] ?>"/>
                    </div>

                    <div class="form-group">
                        <label><?php echo $term_content_sdesc_label ?></label>
                        <input id="sdesc-entry-<?php echo $key ?>" class="form-control" name="sdesc" type="text" maxlength="255" value="<?php echo $value['sdesc'] ?>"/>
                    </div>

                    <div class="form-group">
                        <label><?php echo $term_content_text_label ?></label>
                        <textarea id="content-entry-<?php echo $key ?>" class="form-control content-text-entry-<?php echo $key ?>" name="content"><?php echo $value['content'] ?></textarea>
                    </div>
                    <?php
                    //get the template required
                    $template = $pages_common->getTemplate($value['content_type']);
                    if($template != 'standard' )
                    {
                        $file = DIR_MODULE_VIEWS.'/edit/content/'.$template.'.php';
                        require $file;
                    }
                    ?>
                    <div class="form-group">
                        <button id="content-submit-<?php echo $key ?>" class="btn btn-primary content-submit" type="submit"><?php echo $term_content_update_button ?></button>
                        <button id="content-delete-<?php echo $key ?>" class="btn btn-danger" type="button"><?php echo $term_content_delete_button ?></button>
                    </div>

                </form>
                <!-- Start Images and Files for Entry <?php echo $key ?> -->
                <div id="files-entry-<?php echo $key ?>" class="border border-light rounded p-3">
                    <ul class="nav nav-pills" id="tabpanel-entry-<?php echo $key ?>" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-images-link-<?php echo $key ?>" data-toggle="tab" href="#tab-images-entry-<?php echo $key ?>" role="tab" aria-controls="home" aria-selected="true"><?php echo $term_content_images_label ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-files-link-<?php echo $key ?>" data-toggle="tab" href="#tab-files-entry-<?php echo $key ?>" role="tab" aria-controls="profile" aria-selected="false"><?php echo $term_content_files_label ?></a>
                        </li>
                    </ul>

                    <div id="tabpanel-page-content" class="tab-content">
                        <div id="tab-images-entry-<?php echo $key ?>" class="tab-pane fade show active" role="tabpanel" aria-labelledby="images-tab">
                            <input id="image-entry-<?php echo $key ?>" name="image-entry-<?php echo $key ?>[]" type="file" multiple>
                        </div>
                        <div id="tab-files-entry-<?php echo $key ?>" class="tab-pane fade" role="tabpanel" aria-labelledby="files-tab">
                            <input id="file-entry-<?php echo $key ?>" name="file-entry-<?php echo $key ?>[]" type="file" multiple>
                        </div>
                    </div>
                </div>
                <!-- End Images and Files Entry <?php echo $key ?> -->

            </div>
        </div>
    </div>
</li>

<!-- end of pages edit content standard entry-<?php echo $key ?> -->

