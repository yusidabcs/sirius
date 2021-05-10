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

    <div class="row">
        <div class="col-12" >
            <!-- Main Card -->
            <div class="card mb-4">
                <h3 class="card-header blue-gradient white-text text-center py-4">
                    <?php echo $term_language_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
                </h3>
                <form method="post">

                    <!-- Main Card -->
                    <div class="card-body">
                        <!-- add language information -->
                        <div id="language_info_add" class="card mb-4">
                            <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                <i class="fas fa-plus-square"></i> <?php echo $term_language_heading_language; ?>
                            </h4>

                            <div class="card-body">
                                <div class="row align-items-center">
                                        <div class="text-left text-md-right col-md-4 required"><b><?php echo $term_language_language; ?></b></div>
                                        <div class="col-md-8">
                                            <select id="language" name="language" class="mdb-select md-form" searchable="Select language">
                                                <?php
                                                foreach($languageCodes as $code => $description)
                                                {
                                                    ?>
                                                    <option value="<?php echo $code; ?>"><?php echo $description; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="text-left text-md-right col-md-4 required"><b><?php echo $term_language_level; ?></b></div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="iow-ck-button col-12 col-sm-6 col-md-4 col-lg-2">
                                                    <label>
                                                        <input type="radio" class="level" id="level_1" name="level" value="1" hidden="hidden">
                                                        <span class="langlevel"><?php echo $term_language_level_1; ?></span>
                                                    </label>
                                                </div>

                                                <div class="iow-ck-button col-12 col-sm-6 col-md-4 col-lg-3">
                                                    <label>
                                                        <input type="radio" class="level" id="level_2" name="level" value="2" hidden="hidden">
                                                        <span class="langlevel"><?php echo $term_language_level_2; ?></span>
                                                    </label>
                                                </div>

                                                <div class="iow-ck-button col-12 col-sm-6 col-md-4 col-lg-3">
                                                    <label>
                                                        <input type="radio" class="level" id="level_3" name="level" value="3" hidden="hidden">
                                                        <span class="langlevel"><?php echo $term_language_level_3; ?></span>
                                                    </label>
                                                </div>

                                                <div class="iow-ck-button col-12 col-sm-6 col-md-6 col-lg-2">
                                                    <label>
                                                        <input type="radio" class="level" id="level_4" name="level" value="4" hidden="hidden">
                                                        <span class="langlevel"><?php echo $term_language_level_4; ?></span>
                                                    </label>
                                                </div>

                                                <div class="iow-ck-button col-12 col-sm-6 col-md-6 col-lg-2">
                                                    <label>
                                                        <input type="radio" class="level" id="level_5" name="level" value="5" hidden="hidden">
                                                        <span class="langlevel"><?php echo $term_language_level_5; ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-left text-md-right col-md-4 required"><b><?php echo $term_language_experience; ?></b></div>
                                        <div class="col-md-8">
                                            <select id="experience" name="experience" class="mdb-select md-form m-0">
                                                <option value="0"><?php echo $term_language_experience_0; ?></option>
                                                <option value="1"><?php echo $term_language_experience_1; ?></option>
                                                <option value="2"><?php echo $term_language_experience_2; ?></option>
                                                <option value="3"><?php echo $term_language_experience_3; ?></option>
                                                <option value="4"><?php echo $term_language_experience_4; ?></option>
                                                <option value="5"><?php echo $term_language_experience_5; ?></option>
                                                <option value="6"><?php echo $term_language_experience_6; ?></option>
                                                <option value="7"><?php echo $term_language_experience_7; ?></option>
                                                <option value="8"><?php echo $term_language_experience_8; ?></option>
                                                <option value="9"><?php echo $term_language_experience_9; ?></option>
                                            </select>
                                        </div>
                                    </tr>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button id="add_lang" type="button" class="btn btn-md btn-default btn-block"><i class="fas fa-plus-circle"></i> <?php echo $term_language_add; ?></button>
                            </div>

                        </div>

                        <!-- language list -->
                        <div id="language_info_list" class="card mb-4">

                            <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                <i class="fas fa-list-alt"></i> <?php echo $term_language_heading_list; ?>
                            </h4>

                            <div class="card-body">
                                <div class="row mb-2 mb-sm-0 ">
                                    <div class="col-10 text-left ">
                                        <div class="row ">
                                            <div class="col-12 col-sm-4 border"><b><?php echo $term_language_heading_list_language; ?></b></div>
                                            <div class="col-12 col-sm-4 border d-none d-sm-block"><b><?php echo $term_language_heading_list_level; ?></b></div>
                                            <div class="col-12 col-sm-4 border d-none d-sm-block"><b><?php echo $term_language_heading_list_experience; ?></b></div>
                                        </div>
                                    </div>
                                    <div class="col-2 border ">&nbsp</div>
                                </div>
                                <div class="" id="list_body">
                                    <?php
                                    if (!empty($language))
                                    {
                                        echo '<input type="hidden" name="def_lang_count" value="'.count($language).'">';
                                        foreach($language as $languageCode_id => $lang)
                                        {
                                        ?>
                                            <span class="row mb-2 mb-sm-0">
                                                <div class="col-10 text-left">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-4 border" data-value="<?php echo $languageCode_id; ?>"><?php echo $languageCodes[$languageCode_id]; ?><input type="hidden" name="keep[]" value="<?php echo $languageCode_id; ?>" /></div>
                                                        <div class="col-12 col-sm-4 border d-none d-sm-block"><?php $term = 'term_language_level_'.$lang['level']; echo $$term; ?></div>
                                                        <div class="col-12 col-sm-4 border d-none d-sm-block"><?php $term = 'term_language_experience_'.$lang['experience']; echo $$term; ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-2 delete_lang border text-center " title="Delete Language"><i class="far fa-trash-alt"></i></div>
                                            </span>
                                        <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <div class="row flex-column-reverse flex-lg-row">
                            <div class="col-lg-6 col-xs-12 left">
                                <a id="go_back" href="<?php echo $back_url ?>" class="btn btn-md btn-warning font-weight-bold btn-sm-mobile-100" role="button"><i class="fas fa-arrow-circle-left"></i> <?php echo $term_go_back; ?></a>
                            </div>
                            <div class="col-lg-6 col-xs-12 right">
                                <button type="submit" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_language_save; ?></button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>