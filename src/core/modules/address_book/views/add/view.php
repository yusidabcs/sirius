<div class="container">
    <div class="page-header text-center p-3">
        <h2><?php echo $term_page_header ?></h2>
    </div>

    <?php
    if (isset($errors) && is_array($errors)) {
        ?>
        <div class="iow-callout iow-callout-warning">
            <h2 class="text-warning"><?php echo $term_error_legend ?></h2>
            <?php
            foreach ($errors as $key => $value) {
                $tname = 'term_' . $key . '_label';
                $title = isset($$tname) ? $$tname : $key;
                echo "				<p class=\"text-warning\"><strong>{$title}</strong> {$value}</p>\n";
            }
            ?>
        </div>
        <?php
    }
    ?>
    <form id="form-address_book" method="post" action="<?php echo $myURL; ?>" enctype="multipart/form-data">
        <div id="address_book_form " class="row">
            <input type="hidden" name="redirect_to" value="<?php echo $redirect_to?>">
            <div class="col-md-6">
                <?php
                include($main_file);
                ?>
            </div>

            <div class="col-md-6">

                <?php
                if (ADDRESS_BOOK_ALLOW_AVATAR) {
                    ?>
                    <?php
                    include($avatar_file);
                    ?>

                    <?php
                }
                ?>

                <?php
                include($pots_file);
                ?>


                <?php
                include($internet_file);
                ?>
            </div>

            <div class="col-md-6">
                <?php
                include($address_file);
                ?>

            </div>

        </div>


        <div class="">
            <hr>
            <p>
                <button id="add" type="submit" class="btn btn-primary btn-block"><?php echo $term_button_add_submit; ?></button>
            </p>
        </div>
    </form>

</div>