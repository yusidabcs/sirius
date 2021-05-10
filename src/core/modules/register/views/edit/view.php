
<!-- start of register edit -->
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="page-header">
                <h1><?php echo $term_page_heading ?></h1>
            </div>


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

        </div>
    </div>

    <form method="post" action="<?php echo $post; ?>">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <?php
                foreach($country_code_info as $code => $value)
                {
                    ?>
                    <div class="jumbotron">

                        <fieldset id="<?php echo $code ?>">

                            <legend><?php echo $code ?></legend>

                            <input name="code[<?php echo $code ?>]" type="text" value="<?php echo $code ?>" hidden="hidden" />

                            <div class="form-group">
                                <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label" for="title"><?php echo $term_type_label ?></label>
                                <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                                    <select id="type-<?php echo $code ?>" name="type[<?php echo $code ?>]" class="form-control">
                                        <option value="danger" <?php if($value['type'] == 'danger') echo 'selected="Selected"'; ?>>Danger</option>
                                        <option value="info" <?php if($value['type'] == 'info') echo 'selected="Selected"'; ?>>Info</option>
                                        <option value="primary" <?php if($value['type'] == 'primary') echo 'selected="Selected"'; ?>>Primary</option>
                                        <option value="success" <?php if($value['type'] == 'success') echo 'selected="Selected"'; ?>>Success</option>
                                        <option value="warning" <?php if($value['type'] == 'warning') echo 'selected="Selected"'; ?>>Warning</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label" for="heading-<?php echo $code ?>"><?php echo $term_heading_label ?></label>
                                <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                                    <input id="heading-<?php echo $code ?>" class="form-control" name="heading[<?php echo $code ?>]" type="text" maxlength="100" value="<?php echo $value['heading'] ?>"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label" for="short_description-<?php echo $code ?>"><?php echo $term_short_description_label ?></label>
                                <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                                    <textarea id="short_description-<?php echo $code ?>" class="form-control" name="short_description[<?php echo $code ?>]" maxlength="255"><?php echo $value['short_description'] ?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">


                                    <select class="mdb-select colorful-select dropdown-primary md-form" multiple searchable="Search here.." name="countries[<?php echo $code ?>][]" >
                                        <?php
                                        foreach($countries as $country_code => $country_name)
                                        {
                                            if(!empty($value['countries']) && in_array($country_code, $value['countries']))
                                            {
                                                echo '<option value="'.$country_code.'" selected="selected">'.$country_name."</option>\n";

                                            } else {
                                                echo '<option value="'.$country_code.'">'.$country_name."</option>\n";
                                            }
                                        }
                                        ?>
                                    </select>

                                    <label class="mdb-main-label"><?php echo $term_countries_label ?></label>
                                </div>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="delete-<?php echo $code ?>" name="delete[<?php echo $code ?>]" value="1">
                                <label class="form-check-label" for="delete-<?php echo $code ?>"><?php echo $term_delete ?></label>
                            </div>

                        </fieldset>

                    </div>

                    <?php
                }
                ?>
                <div class="jumbotron">

                    <fieldset id="new_content">

                        <legend><?php echo $term_new_legend ?></legend>

                        <div class="form-group">
                            <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label" for="code_new"><?php echo $term_code_label ?></label>
                            <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                                <input id="code_new" class="form-control" name="code_new" type="text" maxlength="100" value=""/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label" for="type_new"><?php echo $term_type_label ?></label>
                            <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                                <select name="type_new" id="type_new" class="form-control">
                                    <option value="danger">Danger</option>
                                    <option value="info">Info</option>
                                    <option value="primary">Primary</option>
                                    <option value="success">Success</option>
                                    <option value="warning">Warning</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label" for="heading_new"><?php echo $term_heading_label ?></label>
                            <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                                <input id="heading_new" class="form-control" name="heading_new" type="text" maxlength="100" value=""/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label" for="short_description"><?php echo $term_short_description_label ?></label>
                            <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                                <textarea id="short_description_new" class="form-control" name="short_description_new" maxlength="255"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">


                                <select id="countries_new" class="mdb-select colorful-select dropdown-primary md-form" multiple searchable="Search here.." name="countries_new[]" >
                                    <?php
                                    foreach($countries as $country_code => $country_name)
                                    {
                                        echo '<option value="'.$country_code.'">'.$country_name."</option>\n";
                                    }
                                    ?>
                                </select>

                                <label class="mdb-main-label"><?php echo $term_countries_label ?></label>
                            </div>
                        </div>
                        
                    </fieldset>

                </div>

                <div class = "iow-admin right">
                    <button id="update" class="btn btn-primary" type="submit"><?php echo $term_update_button ?></button>
                </div>


            </div>
        </div>
    </form>
</div>


<!-- start of register edit -->
