<div class="">
    <?php
    if(isset($errors) && is_array($errors))
    {
    ?>
        <div class="iow-callout iow-callout-warning p-3">
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
        <div class="col-12 p-0 px-md-3 mt-0 mt-md-2" >
            <!-- Main Card -->
            <div class="card mb-4">
                <h3 class="card-header blue-gradient white-text text-center py-4">
                    <?php echo $term_english_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
                </h3>

                <form method="post">
                    <!-- Main Card -->
                    <div class="card-body">
                        <!-- text information -->
                        <div id="english_data" class="card mb-4">
                            <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                <i class="fas fa-info-circle"></i> <?php echo $term_english_info_heading ?>
                            </h4>

                            <div class="card-body">
                                <div id="english_data_details" class="row align-items-center " >

                                    <div class="col-md-2 text-left text-md-right required">
                                        <b><?php echo $term_english_table_heading_type; ?></b>
                                        <input type="hidden" name="english_id" value="<?php echo $english['english_id']; ?>"/>
                                    </div>

                                    <div class="form-group col-md-10">
                                        <select id="type" name="type" class="mdb-select md-form" required >
                                            <option value=""><?php echo $term_english_table_select_please; ?></option>
                                            <option value="marlins" <?php if($english['type'] == 'marlins') echo 'selected="Selected"'; ?>><?php echo $term_english_table_select_marlins; ?></option>
                                            <option value="ielts" <?php if($english['type'] == 'ielts') echo 'selected="Selected"'; ?>><?php echo $term_english_table_select_ielts; ?></option>
                                            <option value="toefl" <?php if($english['type'] == 'toefl') echo 'selected="Selected"'; ?>><?php echo $term_english_table_select_toefl; ?></option>
                                            <option value="pte" <?php if($english['type'] == 'pte') echo 'selected="Selected"'; ?>><?php echo $term_english_table_select_pte; ?></option>
                                            <option value="cae" <?php if($english['type'] == 'cae') echo 'selected="Selected"'; ?>><?php echo $term_english_table_select_cae; ?></option>
                                            <option value="oet" <?php if($english['type'] == 'oet') echo 'selected="Selected"'; ?>><?php echo $term_english_table_select_oet; ?></option>
                                            <option value="other" <?php if($english['type'] == 'other') echo 'selected="Selected"'; ?>><?php echo $term_english_table_select_other; ?></option>
                                        </select>
                                    </div>

                                    <div class="col-12 py-2">
                                        <div class="row">
                                            <div class="col-md-2 col-lg-2 text-left text-md-right required"><b><?php echo $term_english_table_heading_overall; ?></b></div>
                                            <div class="col-md-3 col-lg-4">
                                                <input type="text" class="form-control" id="overall" name="overall" maxlength="6" value="<?php echo $english['overall']; ?>" required />
                                            </div>

                                            <div class="col-md-3 col-lg-2 text-left text-md-right required"><b><?php echo $term_english_table_heading_breakdown; ?></b></div>
                                            <div class="col-md-4 col-lg-4">
                                                <div id="breakdown" class="border rounded p-3">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            Breakdown Name
                                                        </div>
                                                        <div class="col-6 d-flex align-items-center justify-content-between">
                                                            Score <i class="fa fa-plus add-breakdown text-success" style="cursor:pointer"></i>
                                                        </div>
                                                    </div>
                                                    <?php foreach($english['breakdown'] as $key => $item) {?>
                                                    <div class="row" id="breakdown_<?php echo str_replace($key,' ','_'); ?>">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <input type="text" class="form-control" id="breakdown_<?php echo str_replace($key,' ','_'); ?>" name="breakdown_name[<?php echo $key; ?>]" value="<?php echo $key; ?>" required />
                                                        </div>
                                                        <div class="col-6 d-flex align-items-center ">
                                                            <input type="number" class="form-control" id="score_<?php echo str_replace($key,' ','_'); ?>" name="score[<?php echo $key; ?>]" value="<?php echo $item; ?>" required />

                                                            <button class="btn btn-link delete-breakdown" type="button" data-key="<?php echo str_replace($key,' ','_'); ?>" ><i class="fa fa-trash"></i></button>
                                                        </div>
                                                    </div>
                                                    <?php }?>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 py-2">
                                        <div class="row">
                                            <div class="col-md-2 col-lg-2 text-left text-md-right required"><b><?php echo $term_english_table_heading_when; ?></b></div>
                                            <div class="col-md-3 col-lg-4">
                                                <input class="calendar form-control" type="text" id="when" name="when" value="<?php echo $english['when']; ?>" required />
                                            </div>

                                            <div class="col-md-3 col-lg-2 text-left text-md-right required"><b><?php echo $term_english_table_heading_where; ?></b></div>
                                            <div class="col-md-4 col-lg-4">
                                                <input type="text" class="form-control" id="where" name="where" value="<?php echo $english['where']; ?>" required />
                                            </div>
                                        </div>
                                    </div>
                                

                                    <tr>
                                    </tr>

                                </div>

                            </div>

                        </div>

                        <!-- test image -->
                        <div id="english_image" class="card col-sm-12 col-md-12 m-0 p-0 ">

                            <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                <i class="fas fa-image"></i> <?php echo $term_english_image_heading ?>
                            </h4>
                            <input type="hidden" id="english_base64" name="english_base64">
                            <div class="card-body">

                            <div class="container ">
                                <?php
                                $class="not-showing";
                                if(!empty($english['filename'])) {
                                    $class="";
                                    ?>
                                    <!-- english image if any -->
                                    <div class="text-center">
                                        <input type="hidden" id="english_current" name="english_current" value="<?php echo $english['filename'] ?>">
                                    </div>
                                    <!-- end of english photo -->
                                    <?php
                                }
                                ?>
                            <div class="text-center <?php echo $class;?>" id="d_curr_img">
                                <img id="curr_img" src="/ab/show/<?php echo $english['filename'] ?>" alt="Current English Certificate" class="img-fluid" >
								<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								<hr>
                            </div>

                            <ul class="nav nav-tabs md-pills pills-unique nav-fill" role="tablist">
                                <li class="nav-item">
                                    <a  class="nav-link active" id="portrait-tab" data-toggle="tab" href="#portrait" role="tab" aria-controls="portrait" aria-selected="true">
                                        <i class="far fa-file-image"> </i> <?php echo $term_english_tab_portrait ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a  class="nav-link" id="landscape-tab" data-toggle="tab" href="#landscape" role="tab" aria-controls="landscape" aria-selected="true">
                                        <i class="far fa-image"> </i> <?php echo $term_english_tab_landscape ?>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
									
									<div id="portrait" class="tab-pane fade show active">
							
                                        <div class="form-group" >
                                            <label for="english_input_portrait" class="required"><?php echo $term_english_image_choose_file; ?></label>
                                            <input type="file" class="col-12"  id="english_input_portrait" accept=".jpg,.png,.gif" <?php echo (empty($english['filename'])) ? 'required' : '' ?>>
                                        </div>

                                        <div id="english_croppie_wrap_portrait" class="mw-100 w-auto mh-100 h-auto d-none">
                                            <div id="english_croppie_portrait" data-banner-width="500" data-banner-height="700"></div>
                                        </div>

                                        <button class="btn btn-default btn-block not-showing" type="button" id="english_result_portrait"><?php echo $term_english_image_crop; ?></button>
			
									</div>

									<div id="landscape" class="tab-pane fade">
		
										<div class="form-group">
											<label for="english_input_landscape" class="required"><?php echo $term_english_image_choose_file; ?></label>
											<input type="file" class="col-12" id="english_input_landscape" accept=".jpg,.png,.gif" >
										</div>
										
										<div id="english_croppie_wrap_landscape" class="mw-100 w-auto mh-100 h-auto d-none">
											<div id="english_croppie_landscape" data-banner-width="800" data-banner-height="600"></div>
										</div>
										
										<button class="btn btn-default btn-block not-showing" type="button" id="english_result_landscape"><?php echo $term_english_image_crop; ?></button>
				
									</div>

								</div>

                            </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer right">
                        <div class="row flex-column-reverse flex-lg-row">
                            <div class="col-lg-6 left">
                                <a id="go_back" href="<?php echo $back_url ?>" class="btn btn-md btn-warning font-weight-bold btn-sm-mobile-100" role="button"><i class="fas fa-arrow-circle-left"></i> <?php echo $term_go_back; ?></a>
                            </div>
                            <div class="col-lg-6 right">
                                <button type="submit" name="next" value="home" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_english; ?></button>
                                <button type="submit" name="next" value="again" class="btn btn-md btn-primary font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_english_add; ?></button>
                            </div>
                        </div>

                    </div>

                </form>

            </div>
        </div>
    </div>

</div>