<div class="">
    <!-- start of register home -->

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
    <?php
    if($registered)
    {
        ?>

        <div class="card">
            <div class="card-header success-color white-text h2"><i class="fas fa-check-circle" aria-hidden="true"></i> <?php echo $term_registered_legend_heading ?></div>
            <div class="card-body">
                <p class="card-text"><?php echo $term_registered_message ?></p>
            </div>
        </div>

        <?php
    } else {

        if(empty($register_info))
        {
            ?>
            <div class="card">
                <div class="card-header bg-warning lighten-1 white-text h2"><i class="fas fa-times-circle" aria-hidden="true"></i> <?php echo $term_bad_legend_heading ?></div>
                <div class="card-body text-center">
                    <p class="card-text"><?php echo $term_bad_message ?></p>
                    <a href="<?php echo $baseURL; ?>" class="btn btn-info"><?php echo $term_go_register ?></a>
                </div>
            </div>

            <?php
        } else {
            ?>

            <div class="card border-1 ">
                <div class="card-header info-color white-text text-center">
                    <h4><i class="far fa-question-circle" aria-hidden="true"></i> <?php echo $term_submitted_heading ?></h4>
                </div>
                <div class="card-body">
                    <p class="card-title text-info"><?php echo $term_submitted_message_heading ?></p>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table_regis_confirm">
                                <thead>
                                <tr class="bg-success text-white">
                                    <th><?php echo $term_header_item_table; ?></th>
                                    <th colspan="2"><?php echo $term_header_info_table; ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td width="35%"><?php echo $term_country_table; ?></td>
                                    <td width="5%">:</td>
                                    <td><?php echo $countries[$register_info['country']]; ?></td>
                                </tr>
                                <?php
                                if (($register_info['partner_id']) != 0)
                                {
                                ?>
                                    <tr>
                                        <td width="35%"><?php echo $term_local_partner_table; ?></td>
                                        <td>:</td>
                                        <td><?php echo $register_info['partner_name']; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td><?php echo $term_title_table ?></td>
                                    <td>:</td>
                                    <td><?php echo $register_info['title']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $term_western_table; ?></td>
                                    <td>:</td>
                                    <td><?php echo $register_info['given_name'].' '.$register_info['middle_names'].' '.$register_info['family_name']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $term_eastern_table; ?></td>
                                    <td>:</td>
                                    <td><?php echo $register_info['family_name'].' '.$register_info['middle_names'].' '.$register_info['given_name']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $term_age_table; ?></td>
                                    <td>:</td>
                                    <td><?php echo $age; ?> Years</td>
                                </tr>
                                <tr>
                                    <td><?php echo $term_sex_table; ?></td>
                                    <td>:</td>
                                    <td><?php echo ucfirst($register_info['sex']); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $term_main_email_table; ?></td>
                                    <td>:</td>
                                    <td><?php echo $register_info['main_email']; ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <form id="form-process" role="form" method="post" action="<?php echo $myURL; ?>" >
                                <div class="form-group center ">
                                    <hr>
                                    <button name="submit_button" type="submit" class="btn btn-lg btn-warning" value="go_register"><?php echo $term_go_register ?></button>
                                    <button name="submit_button" type="submit" class="btn btn-lg btn-info" value="go_process"><?php echo $term_go_process ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }
        ?>

        <?php
    }
    ?>
    <!-- start of register home -->
        </div>
    </div>
</div>