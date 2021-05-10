<!-- form security forgot -->
<div class="card">

    <h5 class="card-header info-color white-text text-center py-4">
        <strong><?php echo $term_page_header ?></strong>
    </h5>

    <!--Card content-->
    <div class="card-body">
        <form id="change_pass_form" method="post" action="#">
            <div class="form-group">
                <label for="securityFormUsername"><?php echo $term_username_label ?></label>
                <input disabled="disabled" id="username" name="username" type="text" value="<?php echo $username; ?>" class="form-control" />
                <input hidden="hidden" id="user_id" name="user_id" value="<?php echo $user_id; ?>" />
            </div>

            <div class="form-group">
                <label for="password-new"><?php echo $term_password_new ?></label>
                <input id="password-new" class="form-control" name="password_new" type="password" value="" />
            </div>

            <div class="form-group">
                <label for="password-confirm"><?php echo $term_password_confirm ?></label>
                <input id="password-confirm" class="form-control" name="password_confirm" type="password" value="" />
            </div>
            <div class="form-group">
                <button id="pass_change" class="pass_change btn btn-danger" name="checksum"  value="<?php echo $checksum; ?>" type="submit"><?php echo $term_update_pass_button; ?></button>
            </div>
        </form>

        <div id="pass_changed" class="view_option iow-callout iow-callout-info not-showing" >
            <h3><?php echo $term_changed_heading ?></h3>
            <p><?php echo $term_changed_information ?></p>
        </div>
    </div>
</div>