<div class="container">
    <!-- start of user admin -->
    <div class="row mt-3">

        <div class="col">
            <?php
        //If Message

        if(isset($messages) && is_array($messages))
        {
            ?>
            <div class="iow-callout iow-callout-success">
                <h2 class="text-success"><?php echo $term_success_legend ?></h2>
                <?php
                foreach($messages as $key => $value)
                {
                    $tname = 'term_'.$key.'_label';
                    $title = isset($$tname) ? $$tname : $key;
                    echo "              <p class=\"text-success\"><strong>{$title}</strong> {$value}</p>\n";
                }
                ?>
            </div>
            <?php
        }
        ?>


            <div class="card">

                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary"><?php echo $term_page_header_list ?>
                    </h4>
                    <button class="btn btn-success btn-sm" id="add_user_btn"><i class="fa fa-plus"></i> <?php echo $term_add_user_button ?></button>
                </div>
                <div class="card-body table-responsive">

                    <?php
                    if(isset($errors) && is_array($errors))
                    {
                        ?>
                        <div class="iow-callout iow-callout-warning">
                            <h2 class="text-warning"><?php echo $term_legend_error ?></h2>
                            <?php
                            foreach($errors as $key => $value)
                            {
                                $tname = 'term_login_'.$key.'_label';
                                $title = isset($$tname) ? $$tname : $key;
                                echo "              <p>\n";
                                echo "                  <span>{$title} {$value}\n";
                                echo "              </p>\n";
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>

                    <table class="table table-striped table-bordered table-sm table-responsive-sm" id="list_user" summary="Paginated list of users">
                        <thead>
                        <tr>
                            <th scope="col" class="th-sm">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Login</th>
                            <th scope="col">Status</th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        </thead>
                    </table>

                    <!-- Change Password Modal -->
                    <div class="modal fade" id="password_modal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="post" action="<?php echo $modelURL; ?>" >
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel"><?php echo $term_title_pass_change ?></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">


                                    <div class="form-group">
                                        <label for="password-new"><?php echo $term_password_new ?></label>
                                        <input id="password-new" class="form-control" name="password_new" type="password" value="" />
                                    </div>

                                    <div class="form-group">
                                        <label for="password-confirm"><?php echo $term_password_confirm ?></label>
                                        <input id="password-confirm" class="form-control" name="password_confirm" type="password" value="" />
                                    </div>
                                    <div class="form-group">
                                        <button class="pass_change btn btn-danger" name="user_id"  value="<?php /*echo $user_id; */?>" type="submit"><?php echo $term_update_pass_button; ?></button>
                                    </div>

                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End of Change Password Modal -->
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="add_user_form" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" id="add_user_form" action="<?php echo $modelURL ?>" class="form-horizontal" role="form">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel"><?php echo $term_legend_add; ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                                <label for="username" class="control-label"><?php echo $term_username_label ?></label>
                                <div class="">
                                    <input id="username" required class="ajax_check_field form-control" name="username" type="text" value="<?php echo $username; ?>" />
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="control-label"><?php echo $term_email_label ?></label>
                                <div class="">
                                    <input id="email" required class="ajax_check_field form-control" name="email" type="email" value="<?php echo $email; ?>" />
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="role_id" class="control-label "><?php echo $term_role_label ?></label>
                                <div class="">
                                    <select required id="role_id" name="role_id" class="form-control">
                                        <?php
                                        foreach($roles as $key => $role)
                                        {
                                            ?>
                                            <option value="<?php echo $role['role_id']; ?>" <?php echo ($role_id == $role['role_id']) ? 'selected':'' ?> ><?php echo strtoupper($role['role_name']); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="control-label"><?php echo $term_password_new ?></label>
                                <div class="">
                                    <input required id="password" class="ajax_check_field form-control" name="password" type="input" value="<?php echo $password; ?>" />
                                    <span class="response_ok"></span>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="send_user_email" name="send_user_email"  value="1" checked>
                                <label class="form-check-label" for="send_user_email"><?php echo $term_send_user_email ?></label>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="add_address_book" name="add_address_book" value="1">
                                <label class="form-check-label" for="add_address_book">Insert Address Book Detail</label>
                            </div>

                            <div id="address_book_area" class="border p-3 mb-3 not-showing">
                                <!-- Family Name Grid Row -->

                                 <!-- Country Row -->
                                <div class="row justify-content-center align-items-center">
                                    <div class="col">
                                        <div class="md-form mt-0">
                                            <label text-success><?php echo $term_citizen_country ?>:</label>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <select class="mdb-select md-form" searchable="Search country.." id="country" name="country" required>

                                            <option class="none" value="not specified" disabled <?php if($country == 'not specified') echo "selected"; ?>><?php echo $term_country_select; ?></option>;
                                            <?php
                                            foreach($countries as $code => $name)
                                            {
                                                $infoClass = isset($countries_info_code[$code]) ? $countries_info_code[$code] : 'default';

                                                if($code == $country)
                                                {
                                                    echo '<option class="'.$infoClass.'" value="'.$code.'" selected="selected">'.$name."</option>\n";
                                                } else {
                                                    echo '<option class="'.$infoClass.'" value="'.$code.'">'.$name."</option>\n";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <hr>

                                <?php
                                foreach($country_code_info as $code => $value)
                                {
                                    ?>
                                    <div id="<?php echo $code; ?>" class="row country-info not-showing">
                                        <div class="col mb-4">
                                            <div class="iow-callout iow-callout-<?php echo $value['type']; ?>">
                                                <h4 class="text-<?php echo $value['type']; ?>"><?php echo $value['heading']; ?></h4>
                                                <p class="text-left"><?php echo $value['short_description']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>

                                <div class="row">
                                    <!-- Title -->
                                    <div class="col-lg-4">
                                        <div class="md-form mt-0">
                                            <!-- <input type="text" class="form-control" placeholder="<?php echo $term_title ?>" id="title"  name="title" maxlength="10" value="<?php echo $title ?>"> -->
                                            <select class="browser-default custom-select" id ="title" name="title" >
                                                <option value="Mr">Mr</option>
                                                <option value="Mrs">Mrs</option>
                                                <option value="Miss">Miss</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Family Name -->
                                    <div class="col-lg-8">
                                        <div class="md-form mt-0">
                                            <input type="text" class="form-control" placeholder="<?php echo $term_family_name ?>" id="family_name"  name="family_name" maxlength="100" value="<?php echo $family_name ?>" >
                                        </div>
                                    </div>
                                </div>

                                <!-- Given Names Grid Row -->
                                <div class="row">
                                    <!-- Given Names -->
                                    <div class="col-lg-6">
                                        <div class="md-form mt-0">
                                            <input type="text" class="form-control" placeholder="<?php echo $term_given_name ?>" id="given_name"  name="given_name" maxlength="100" value="<?php echo $given_name ?>" >
                                        </div>
                                    </div>
                                    <!-- Middle Names -->
                                    <div class="col-lg-6">
                                        <div class="md-form mt-0">
                                            <input type="text" class="form-control" placeholder="<?php echo $term_middle_names ?>" id="middle_names"  name="middle_names" maxlength="255" value="<?php echo $middle_names ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Private Details Grid Row -->
                                <div class="row">

                                    <!-- DOB -->
                                    <div class="col-lg-6">

                                        <div class="md-form mt-0">
                                            <input type="text" id="dob" class="form-control" name="dob" placeholder="<?php echo $term_dob ?>" readonly="readonly" value="<?php echo $dob ?>" data-min-date="<?php echo $dob_min ?>" data-max-date="<?php echo $dob_max ?>" >
                                        </div>
                                    </div>

                                    <!-- Sex -->
                                    <div class="col-lg-6">
                                        <div class="md-form mt-0">
                                            <!-- Male -->
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" id="male" name="sex" <?php if($sex == 'male') echo 'checked'; ?> value="male">
                                                <label class="form-check-label" for="male"><?php echo $term_sex_male ?></label>
                                            </div>
                                            <!-- Female -->
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" id="female" name="sex" <?php if($sex == 'female') echo 'checked'; ?> value="female">
                                                <label class="form-check-label" for="female"><?php echo $term_sex_female ?></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2">
                                    <input name="action" type="hidden" value="add_new_user" />
                                    <input class="btn btn-primary"  type="submit" value="<?php echo $term_add_button; ?>" id="add_user" />
                                    <a class="btn btn-warning link-button" href="<?php echo $goback; ?>" role="button"><?php echo $term_goback_button ?></a>
                                </div>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End of Add User Modal -->

    <!-- Change Group and Roles Modal -->
    <div class="modal fade" id="group_roles_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="<?php echo $modelURL; ?>" >
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><?php echo $term_title_group_modal ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="e_user_id" name="e_user_id">
                    <div class="form-group">
                        <label for="e_role_id" class="control-label"><?php echo $term_group_label ?></label>
                        <select id="e_role_id" name="e_role_id" class="mdb-select md-form">
                            <?php
                            foreach($roles as $key => $role)
                            {
                                ?>
                                <option value="<?php echo $role['role_id']; ?>"><?php echo strtoupper($role['role_name']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <button class="change_role btn btn-danger" type="button"><?php echo $term_update_role_button; ?></button>
                    </div>

                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End of Change Password Modal -->

    <!-- end of user admin -->

</div>