<div class="container-fluid">
    <div class="row mb-3 mt-0">
        <div class="col-md-6">
            <h2><?php echo $term_page_header.$link_id ?></h2>
        </div>
        <div class="col-md-6">
            <a href="<?php echo $view_link; ?>" class="btn btn-sm btn-primary float-right" role="button"><?php echo $term_go_view; ?></a>
        </div>
    </div>
    <div class="row">

        <div class="col-md-3">

            <!--start list page card-->
            <div class="card" >
                <div class="card-header">
                    List Content
                </div>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item page-selection" data-id="entry-core">
                        <a style="display: block" href="#pagecontent_core">
                            Page Core
                        </a>
                    </li>
                </ul>
                <ul class="list-group list-group-flush" id="list_page_placeholder">
                    <?php foreach ($pageContentInfoArray as $key => $item) { ?>
                        <li data-toggle="tooltip" title="Drag to update sequence" class="list-group-item page-selection sortable" data-id="entry-<?php echo $key; ?>" id="sidebar-heading-entry-<?php echo $key; ?>">
                            <a style="display: block" href="#pagecontent<?php echo $key; ?>">
                                <?php echo $item['heading']; ?>
                            </a>
                        </li>
                    <?php } ?>

                </ul>

                <button type="button" id="contentAdd" class="btn btn-info btn-rounded"><i class="fas fa-plus"></i> <?php echo $term_content_add_button ?></button>
            </div>
            <hr>

        </div>

        <div class="col-md-9">

            <!--Core Page-->
            <div class="card chart-card" id="entry-core">

                <!-- Card content -->
                <div class="card-body pb-0">

                    <!-- Title -->
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title font-weight-bold">Core Content</h4>
                    </div>
                    <!-- Text -->
                    <hr>



                    <!-- Page core-->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#content_tab" role="tab" aria-controls="home"
                               aria-selected="true">Default Content</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#file_tab" role="tab" aria-controls="profile"
                               aria-selected="false">Images & File</a>
                        </li>

                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="content_tab" role="tabpanel" aria-labelledby="home-tab">

                            <form id="section-info" method="post" action="#" role="form" enctype="multipart/form-data">

                                <input id="link_id" name="link_id" type="hidden" value="<?php echo $link_id ?>">
                                <input id="next_content_id" name="next_content_id" type="hidden" value="<?php echo $next_content_id ?>">

                                <div class="row">

                                    <div class="col-md-4">

                                        <div class="md-form">
                                            <input id="show_heading" name="show_heading" type="checkbox" class="form-check-input" value="1" <?php if($show_heading) echo 'checked'; ?>>
                                            <label for="show_heading"><?php echo $term_heading_show_label; ?></label>
                                        </div>

                                    </div>

                                    <!-- page only -->
                                    <div class="col-md-4 page-only">

                                        <div class="md-form">
                                            <input id="show_anchors" name="show_anchors" type="checkbox" class="form-check-input" value="1" <?php if($show_anchors) echo 'checked'; ?>>
                                            <label for="show_anchors"><?php echo $term_page_anchor_label; ?></label>
                                        </div>

                                    </div>

                                    <div class="col-md-4 page-only">

                                        <div class="form-group">
                                            <label for="page_keywords"><?php echo $term_page_keywords_label ?></label>
                                            <input id="page_keywords" class="form-control" name="page_keywords" type="text" maxlength="255" value="<?php echo $page_keywords ?>" />
                                        </div>

                                    </div>
                                    <!-- page only -->

                                    <div class="col-md-8 entry-only" style="display: none;">

                                        <div class="form-group">
                                            <select id="content_type" class="md-form mdb-select" name="content_type">
                                                <option value="article"><?php echo $term_content_type_article_label ?></option>
                                                <option value="banner"><?php echo $term_content_type_banner_label ?></option>
                                                <option value="banner_top"><?php echo $term_content_type_banner_top_label ?></option>
                                                <option value="contact_form"><?php echo $term_content_type_contact_form_label ?></option>
                                                <option value="gallery"><?php echo $term_content_type_gallery_label ?></option>
                                                <option value="slideshow"><?php echo $term_content_type_slideshow_label ?></option>
                                                <option value="testimonial"><?php echo $term_content_type_testimonial_label ?></option>
                                                <?php
                                                if(PAGES_REGISTER_USE)
                                                {
                                                    ?>
                                                    <option value="register"><?php echo $term_content_type_register_label ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <label class="mdb-main-label"><?php echo $term_content_type_label ?></label>
                                        </div>

                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="heading"><?php echo $term_heading_label ?></label>
                                    <input id="page_heading" class="form-control" name="page_heading" type="text" maxlength="100" value="<?php echo $page_heading ?>" required/>
                                </div>

                                <div class="form-group">
                                    <label for="sdesc"><?php echo $term_sdesc_label ?></label>
                                    <input id="sdesc" class="form-control" name="page_sdesc" type="text" maxlength="255" value="<?php echo $page_sdesc ?>"  />
                                </div>

                                <div class="form-group">
                                    <label for="section_text"><?php echo $term_text_label ?></label>
                                    <textarea id="section_text" class="form-control" name="page_text" required><?php echo $page_text ?></textarea>
                                </div>


                                <div class="form-group">
                                    <button id="submit-page-info" class="btn btn-primary" type="submit"><?php echo $term_section_update_button ?></button>
                                </div>

                            </form>

                        </div>
                        <div class="tab-pane fade" id="file_tab" role="tabpanel" aria-labelledby="profile-tab">

                            <div class="row">

                                <div class="col-12">

                                    <!-- Image and File Upload -->
                                    <div class="border border-warning rounded p-3">

                                        <form id="page-file-form" data-section="core" class="contentForm mt-3" method="post" action="#" role="form" enctype="multipart/form-data">

                                            <input id="link_id" name="link_id" type="hidden" value="<?php echo $link_id ?>">
                                            <input id="section" name="section" type="hidden" value="page">
                                            <input id="entry_core" name="entry" type="hidden" value="core">

                                            <!-- type -->
                                            
                                            <div class="md-form">
                                                <label class="mdb-main-label">Upload Type</label><br>

                                                <div class="form-check form-check-inline">
                                                    <input id="radio1_core" class="form-check-input" type="radio" name="file_type" value="image" checked>
                                                    <label for="radio1_core" class="form-check-label">Image</label>
                                                    <input id="radio2_core" class="form-check-input" type="radio" name="file_type" value="file">
                                                    <label for="radio2_core" class="form-check-label">File</label>
                                                </div>
                                            </div>
                                            <!-- type -->

                                            <div class="file-field">

                                                <div class="btn btn-primary btn-sm float-left">
                                                    <span><?php echo $term_file_choose ?></span>
                                                    <input data-type="file_type" id="page-file" name="file" type="file" accept="application/pdf, image/jpg, image/jpeg, image/gif, image/png, image/vnd.wap.wbmp" required>
                                                </div>

                                                <div class="file-path-wrapper">
                                                    <input class="file-path validate" type="text" placeholder="<?php echo $term_file_upload ?>" readonly>
                                                </div>

                                            </div>

                                            <div class="form-group mt-3">
                                                <label><?php echo $term_file_title_label ?></label>
                                                <input id="title" class="form-control" name="title" type="text" maxlength="100" />
                                            </div>

                                            <div class="form-group">
                                                <label><?php echo $term_file_sdesc_label ?></label>
                                                <input id="sdesc" class="form-control" name="sdesc" type="text" maxlength="255" />
                                            </div>

                                            <div class="md-form right">
                                                <input id="status" name="status" type="checkbox" class="form-check-input" value="1" checked >
                                                <label for="status"><?php echo $term_file_status_label; ?></label>
                                            </div>


                                            <div class="form-group">
                                                <button class="btn btn-primary btn-block btn-submit" type="submit"><?php echo $term_file_submit ?></button>
                                            </div>

                                        </form>

                                    </div>
                                    <!-- file and File Upload -->
                                </div>

                            </div>

                            <!-- Start Images and Files for Page -->

                            <ul class="nav nav-tabs mt-5" id="myTab2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#images" role="tab" aria-controls="images"
                                       aria-selected="true">Images</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files"
                                       aria-selected="false">File</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent2">
                                <div class="tab-pane fade show active" id="images" role="tabpanel" aria-labelledby="home-tab">

                                    <div id="images-page" class="border rounded mt-4 p-3">

                                        <div class="list-group-flush list-files-sortable" id="list-image-core">

                                            <?php
                                            foreach($files as $id => $value)
                                            {
                                                if( $value['model'] == 'page' &&  $value['model_id'] == 'image')
                                                {
                                                    ?>
                                                    <div class="list-group-item " id="file_wrapper_<?php echo $id; ?>"
                                                         data-id="<?php echo $id; ?>"
                                                         data-model="<?php echo $value['model']?>"
                                                         data-model_id="<?php echo $value['model_id']?>"
                                                    >

                                                        <div class="row">

                                                            <div class="col-4">
                                                                <img class="img-fluid" src="<?php echo $value['image_prefix'].'/page/'.$value['file_name']; ?>" alt="<?php echo $value['title']; ?>">
                                                            </div>

                                                            <div class="col-8">

                                                                <div class="form-group">
                                                                    <input name="title" id="title_<?php echo $id?>" type="text" class="form-control" value="<?php echo $value['title']; ?>" size="100">
                                                                </div>

                                                                <div class="form-group">
                                                                    <input name="sdesc" id="sdesc_<?php echo $id?>" type="text" class="form-control" value="<?php echo $value['sdesc']; ?>" size="254">
                                                                </div>
                                                                <div class="md-form">

                                                                    <input name="active_<?php echo $id?>" id="status_<?php echo $id?>" type="checkbox" class="form-check-input" value="1" <?php if($value['status'] == 1) echo 'checked'; ?>>
                                                                    <label for="status_<?php echo $id?>"><?php echo $term_image_active_label; ?></label>
                                                                </div>

                                                                <div class="md-form">
                                                                    <button class="btn-floating btn-sm btn-success update_file" data-sequence="<?php echo $value['sequence'] ?>" data-file-id="<?php echo $id?>"><i class="fas fa-edit"></i> </button>
                                                                    <button class="btn-floating btn-sm btn-danger delete_file" data-file-id="<?php echo $id?>"><i class="fas fa-trash"></i></button>
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
                                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="profile-tab">

                                    <div id="file-page" class="border rounded mt-4 p-3">

                                        <div class="list-group-flush list-files-sortable" id="list-file-core">

                                            <?php
                                            foreach($files as $id => $value)
                                            {
                                                if( $value['model'] == 'page' &&  $value['model_id'] == 'file')
                                                {
                                                    ?>
                                                    <div class="list-group-item" id="file_wrapper_<?php echo $id; ?>"
                                                         data-id="<?php echo $id; ?>"
                                                         data-model="<?php echo $value['model']?>"
                                                         data-model_id="<?php echo $value['model_id']?>"
                                                    >

                                                        <div class="row">

                                                            <div class="col-4">
                                                                <a href="<?php echo $value['image_prefix'] . '/' . $value['file_name']; ?>"
                                                                   title="<?php echo $value['sdesc']; ?>"><?php echo $value['file_name']; ?></a>
                                                            </div>

                                                            <div class="col-8">

                                                                <div class="form-group">
                                                                    <input name="title" id="title_<?php echo $id?>" type="text" class="form-control" value="<?php echo $value['title']; ?>" size="100">
                                                                </div>

                                                                <div class="form-group">
                                                                    <input name="sdesc" id="sdesc_<?php echo $id?>" type="text" class="form-control" value="<?php echo $value['sdesc']; ?>" size="254">
                                                                </div>
                                                                <div class="md-form">

                                                                    <input name="active_<?php echo $id?>" id="status_<?php echo $id?>" type="checkbox" class="form-check-input" value="1" <?php if($value['status'] == 1) echo 'checked'; ?>>
                                                                    <label for="status_<?php echo $id?>"><?php echo $term_image_active_label; ?></label>
                                                                </div>

                                                                <div class="md-form">
                                                                    <button class="btn-floating btn-sm btn-success update_file" data-sequence="<?php echo $value['sequence'] ?>" data-file-id="<?php echo $id?>"><i class="fas fa-edit"></i></button>
                                                                    <button class="btn-floating btn-sm btn-danger delete_file" data-file-id="<?php echo $id?>"><i class="fas fa-trash"></i></button>
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

            <!--template for new page section-->
            <?php
            $file = DIR_MODULE_VIEWS.'/edit/content/new.php';
            include $file;
            ?>

            <!--Page section-->
            <div id="section_placeholder">

                <?php foreach ($pageContentInfoArray as $key => $value) { ?>
                    <?php $file = DIR_MODULE_VIEWS.'/edit/content/standard-new.php';
                    include $file;
                    ?>
                <?php }?>
            </div>


        </div>
    </div>

</div>