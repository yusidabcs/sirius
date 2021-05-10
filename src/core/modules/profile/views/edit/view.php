<div class="container">
    <?php
    if(isset($errors) && is_array($errors))
    {
        ?>
        <div class="row">
            <div class="col">
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
            </div>
        </div>
        <?php
    }
    ?>

    <div class="row">
        <div class="col-12">

            <form id="form-address_book" method="post" action="<?php echo $myURL; ?>" enctype="multipart/form-data">
                <?php
                if(ADDRESS_BOOK_ALLOW_AVATAR)
                {
                    ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <?php
                            include($main_file);
                            include($address_file);
                            ?>
                        </div>
                        <div class="col-lg-6">
                            <?php
                            include($avatar_file);
                            include($pots_file);
                            include($internet_file);
                            ?>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <?php
                            include($main_file);
                            include($address_file);
                            ?>
                        </div>
                        <div class="col-lg-6">
                            <?php
                            include($pots_file);
                            include($internet_file);
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <hr>

                <p>
                    <button id="update" type="submit" class="btn btn-success btn-block"><i class="fas fa-save"></i> <?php echo $term_button_update_submit; ?></button>
                </p>

            </form>

        </div>
    </div>

</div>