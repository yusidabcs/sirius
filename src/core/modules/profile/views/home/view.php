<div class="container">
    <div class="row">
        <div class="col-md-4 ">
            <div class="card testimonial-card mb-4">

                <!-- Bacground color -->
                <div class="card-up indigo lighten-1"></div>

                <?php
                if(!empty($avatar))
                {
                    ?>
                    <!-- Avatar -->
                    <div class="avatar mx-auto white"><img src="/ab/show/<?php echo $avatar[0]['filename'] ?>" alt="Current Avatar" class="rounded-circle"></div>
                    <?php
                }
                ?>

                <div class="card-body">

                    <h4 class="card-title"><?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?> <?php echo $main['entity_family_name']; ?>

                    <?php
                        if ( (!empty($verification)) && ($verification['status'] == 'verified') )
                        {
                    ?>
                        <span class="fa-stack" style="font-size: 0.6rem" title="Verified Member">
                            <i class="fa fa-certificate text-success fa-stack-2x"></i>
                            <i class="fa fa-check fa-stack-1x fa-inverse"></i>
                        </span>
                    <?php 
                        }
                    ?>
                    </h4>


                    <table class="table table-sm text-left" width="80%">

                        <tbody>

                        <tr>
                            <th>Username</th>
                            <td><?php echo $user_info['username'] ?></td>
                        </tr>


                        <?php
                        if(empty($main['dob']) || $main['dob'] == '0000-00-00')
                        {
                            ?>
                            <tr>
                                <th>Born</th>
                                <td>Not Set</td>
                            </tr>
                            <?php
                        } else {
                            ?>
                            <tr>
                                <th>Born</th>
                                <td><?php echo date('d F Y', strtotime($main['dob'])); ?> / Age: <?php echo $main['age']; ?></td>
                            </tr>
                            <?php
                        }
                        ?>


                        <?php
                        if(empty($main['sex']))
                        {
                            ?>
                            <tr>
                                <th>Gender</th>
                                <td>Not Set</td>
                            </tr>
                            <?php
                        } else {
                            ?>
                            <tr>
                                <th>Gender</th>
                                <td><?php echo ucfirst($main['sex']); ?></td>
                            </tr>
                            <?php
                        }
                        ?>

                        <?php
                        if(empty($address['main']))
                        {
                            ?>
                            <tr>
                                <th>Address</th>
                                <td>Not Set</td>
                            </tr>
                            <?php
                        } else {
                            ?>
                            <tr>
                                <th>Address</th>
                                <td>
                                    <?php
                                    if($address['main']['physical_pobox'] == 'physical')
                                    {

                                        if(!empty($address['main']['line_1'])) echo $address['main']['line_1'];
                                        if(!empty($address['main']['line_2'])) echo '<br>'.$address['main']['line_2'];
                                        if(!empty($address['main']['suburb'])) echo '<br>'.$address['main']['suburb'].' ';
                                        if(!empty($address['main']['state_full'])) echo $address['main']['state_full'].' ';
                                        if(!empty($address['main']['postcode'])) echo $address['main']['postcode'];
                                        if(!empty($address['main']['country_full'])) echo '<br>'.$address['main']['country_full'];

                                    } else {

                                        if(!empty($address['main']['line_1'])) echo 'PO Box '.$address['main']['line_1'].'<br>';
                                        if(!empty($address['main']['suburb'])) echo $address['main']['suburb'].' ';
                                        if(!empty($address['main']['state_full'])) echo $address['main']['state_full'].' ';
                                        if(!empty($address['main']['postcode'])) echo $address['main']['postcode'];
                                        if(!empty($address['main']['country_full'])) echo '<br>'.$address['main']['country_full'];

                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }

                        if(isset($address['postal']))
                        {
                            ?>
                            <tr>
                                <th>Postal</th>
                                <td>
                                    <?php
                                    if($address['postal']['physical_pobox'] == 'physical')
                                    {

                                        if(!empty($address['postal']['line_1'])) echo $address['postal']['line_1'];
                                        if(!empty($address['postal']['line_2'])) echo '<br>'.$address['postal']['line_2'];
                                        if(!empty($address['postal']['suburb'])) echo '<br>'.$address['postal']['suburb'].' ';
                                        if(!empty($address['postal']['state_full'])) echo $address['postal']['state_full'].' ';
                                        if(!empty($address['postal']['postcode'])) echo $address['postal']['postcode'];
                                        if(!empty($address['postal']['country_full'])) echo '<br>'.$address['postal']['country_full'];

                                    } else {

                                        if(!empty($address['postal']['line_1'])) echo 'PO Box '.$address['postal']['line_1'].'<br>';
                                        if(!empty($address['postal']['suburb'])) echo $address['postal']['suburb'].' ';
                                        if(!empty($address['postal']['state_full'])) echo $address['postal']['state_full'].' ';
                                        if(!empty($address['postal']['postcode'])) echo $address['postal']['postcode'];
                                        if(!empty($address['postal']['country_full'])) echo '<br>'.$address['postal']['country_full'];

                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <th>Email</th>
                            <td id="main_email">
                                <a href="mailto:<?php echo $main['main_email'];?>"><?php echo $main['main_email'];?></a></td>
                        </tr>

                        <?php
                        if(empty($pots))
                        {
                            ?>
                            <tr>
                                <th>Telephone</th>
                                <td>Not Set</td>
                            </tr>
                            <?php
                        } else {
                            ?>
                            <tr>
                                <th>Telephone</th>
                                <td>
                                    <?php
                                    foreach($pots as $key => $value)
                                    {

                                        if(!empty($value['number']))
                                        {
										    echo '+'.$value['dialInfo']['dialCode'].$value['number'] . ' (' . $value['type'] . ')<br>';
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>


                        <?php
                        if(empty($internet))
                        {
                            ?>
                            <tr>
                                <th>Internet</th>
                                <td>Not Set</td>
                            </tr>
                            <?php
                        } else {
                            ?>
                            <tr>
                                <th>Internet</th>
                                <td>
                                    <?php
                                    foreach($internet as $key => $value)
                                    {
                                        $bHasLink = strpos($value['id'], 'http') !== false || strpos($value['id'], 'www.') !== false;

                                        if($bHasLink){
                                            $value['id'] = '<a href="'.$value['id'].'" target="_blank" class="badge badge-default">Open '.$value['type'].'</a>';
                                        }

                                        if(!empty($value['id']))
                                        {
                                            echo $icon_internet[$value['type']].' '.$value['id'].'<br>';
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>

                        </tbody>
                    </table>

                </div>

                <div class="card-footer text-center">

                    <!-- change user details -->
                    <button class="btn btn-sm btn-primary btn-sm-mobile" data-tooltip="true" data-placement="left" title="Change User Details" data-toggle="modal" data-target="#changeUserModal"><i class="fas fa-user"></i> User Details</button>

                    <!-- Modal -->
                    <div class="modal fade" id="changeUserModal" tabindex="-1" role="dialog" aria-labelledby="changeUserModalTitle" aria-hidden="true">

                        <div  class="modal-dialog modal-dialog-centered" role="document">

                            <!-- Modal content-->
                            <div class="modal-content">

                                <div class="modal-header primary-color text-white">
                                    <h4 class="modal-title w-100" id="myModalLabel">Change User Detail</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">

                                    <form>
                                        <div class="form-group">
                                            <label for="username"><?php echo $term_username_label ?></label>
                                            <input id="username" autocomplete="username" class="form-control" type="text" value="<?php echo $user_info['username'] ?>" autofocus />
                                            <input id="username_orig" type="text" value="<?php echo $user_info['username'] ?>" hidden="hidden" />
                                        </div>

                                        <div class="form-group">
                                            <label for="email"><?php echo $term_email_label ?></label>
                                            <input id="email" autocomplete="email" class="form-control" type="text" value="<?php echo $user_info['email'] ?>" />
                                            <input id="email_orig" type="text" value="<?php echo $user_info['email'] ?>" hidden="hidden" />
                                        </div>

                                        <div id="changeDetailsInfo">
                                        </div>

                                        <div class="form-group">
                                            <button id="changeDetailsButton" class="btn btn-sm btn-warning"><?php echo $term_update_button; ?></button>
                                            <button id="resetDetailsButton" class="btn btn-sm btn-primary"><?php echo $term_reset_button; ?></button>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- change password -->
                    <button class="btn btn-sm btn-danger btn-sm-mobile" data-tooltip="true" data-placement="bottom" title="Change Password" data-toggle="modal" data-target="#changePassword"><i class="fas fa-lock"></i> Password</button>

                    <!-- Modal -->
                    <div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="changePasswordTitle" aria-hidden="true">

                        <div  class="modal-dialog modal-dialog-centered" role="document">

                            <!-- Modal content-->
                            <div class="modal-content">

                                <div class="modal-header danger-color text-white">
                                    <h4 class="modal-title w-100" id="myModalLabel">Change Password</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form>
                                        <div class="form-group">
                                            <label for="password_current"><?php echo $term_password_current ?></label>
                                            <input id="password_current" autocomplete="current-password"  class="form-control" type="password" value="" autofocus />
                                        </div>

                                        <div class="form-group">
                                            <label for="password_new"><?php echo $term_password_new ?></label>
                                            <input id="password_new" autocomplete="new-password"  class="form-control" type="password" value="" />
                                        </div>

                                        <div class="form-group">
                                            <label for="password_confirm"><?php echo $term_password_confirm ?></label>
                                            <input id="password_confirm" autocomplete="new-password"  class="form-control" type="password" value="" />
                                        </div>

                                        <div id="changePasswordInfo">
                                        </div>

                                        <div class="form-group">
                                            <button id="changePasswordButton" class="btn btn-sm btn-warning"><?php echo $term_update_button; ?></button>
                                            <button id="restPasswordButton" class="btn btn-sm btn-primary"><?php echo $term_reset_button; ?></button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    <a href="<?php echo $edit_link; ?>" type="button" class="btn btn-sm btn-warning btn-sm-mobile" data-tooltip="true" data-placement="right" title="Update Profile"><i class="fas fa-edit"></i> <?php echo $term_button_edit_link; ?></a>
                    


                </div>
            </div>
        </div>

        <div class="col-md-8">

            <?php
            if(!$profile_complete){
            ?>
                <div class="card card-warning mb-4">
                    <h3 class="card-header  peach-gradient white-text text-center py-4">
                        <?php echo $term_profile_title; ?>
                    </h3>

                    <div class="card-body">
                        <p><?php echo $term_profile_message; ?></p>

                        <a href="<?php echo $edit_link; ?>" type="button" class="btn btn-sm btn-warning" data-tooltip="true" data-placement="right" title="Update Profile"><i class="fas fa-edit"></i> <?php echo $term_button_edit_link; ?></a>
                    </div>
                </div>
            <?php
            }
            ?>
            <?php
            if (!empty($panels))
                foreach($panels as $module => $panel)
                {
                    ?>
                    <div class="card card-info mb-4">
                        <?php echo '<!-- include profile panel for '.$module.'-->'; ?>
                        <?php include($panel); ?>
                    </div>
                    <?php
                }
            ?>
        </div>

    </div>

</div>