<div class="row">
    <div class="col-3">
        <div class="card testimonial-card mb-4">
            <!-- Bacground color -->
            <div class="card-up indigo lighten-1"></div>
            <?php
            if (!empty($avatar)) {
                ?>
                <!-- Avatar -->
                <div class="avatar mx-auto white"><img src="/ab/show/<?php echo $avatar[0]['filename'] ?>"
                                                       alt="Current Avatar" class="rounded-circle"></div>
                <?php
            }
            ?>
            <div class="card-body">
                <h4 class="card-title"><?php if (!empty($main['title'])) echo $main['title'] . ' '; ?><?php echo $main['number_given_name']; ?><?php if (!empty($main['middle_names'])) echo $main['middle_names']; ?><?php echo $main['entity_family_name']; ?></h4>
                <table class="table table-sm text-left" width="80%">
                    <tbody>

                    <tr>
                        <th>Username</th>
                        <td><?php echo $user_info['username'] ?></td>
                    </tr>
                    <?php
                    if (empty($main['dob']) || $main['dob'] == '0000-00-00') {
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
                            <td><?php echo date('d F Y', strtotime($main['dob'])); ?> /
                                Age: <?php echo $main['age']; ?></td>
                        </tr>
                        <?php
                    }

                    if (empty($main['sex'])) {
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

                    if (empty($address['main'])) {
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
                                if ($address['main']['physical_pobox'] == 'physical') {

                                    if (!empty($address['main']['line_1'])) echo $address['main']['line_1'];
                                    if (!empty($address['main']['line_2'])) echo '<br>' . $address['main']['line_2'];
                                    if (!empty($address['main']['suburb'])) echo '<br>' . $address['main']['suburb'] . ' ';
                                    if (!empty($address['main']['state_full'])) echo $address['main']['state_full'] . ' ';
                                    if (!empty($address['main']['postcode'])) echo $address['main']['postcode'];
                                    if (!empty($address['main']['country_full'])) echo '<br>' . $address['main']['country_full'];

                                } else {

                                    if (!empty($address['main']['line_1'])) echo 'PO Box ' . $address['main']['line_1'] . '<br>';
                                    if (!empty($address['main']['suburb'])) echo $address['main']['suburb'] . ' ';
                                    if (!empty($address['main']['state_full'])) echo $address['main']['state_full'] . ' ';
                                    if (!empty($address['main']['postcode'])) echo $address['main']['postcode'];
                                    if (!empty($address['main']['country_full'])) echo '<br>' . $address['main']['country_full'];

                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }

                    if (isset($address['postal'])) {
                        ?>
                        <tr>
                            <th>Postal</th>
                            <td>
                                <?php
                                if ($address['postal']['physical_pobox'] == 'physical') {

                                    if (!empty($address['postal']['line_1'])) echo $address['postal']['line_1'];
                                    if (!empty($address['postal']['line_2'])) echo '<br>' . $address['postal']['line_2'];
                                    if (!empty($address['postal']['suburb'])) echo '<br>' . $address['postal']['suburb'] . ' ';
                                    if (!empty($address['postal']['state_full'])) echo $address['postal']['state_full'] . ' ';
                                    if (!empty($address['postal']['postcode'])) echo $address['postal']['postcode'];
                                    if (!empty($address['postal']['country_full'])) echo '<br>' . $address['postal']['country_full'];

                                } else {

                                    if (!empty($address['postal']['line_1'])) echo 'PO Box ' . $address['postal']['line_1'] . '<br>';
                                    if (!empty($address['postal']['suburb'])) echo $address['postal']['suburb'] . ' ';
                                    if (!empty($address['postal']['state_full'])) echo $address['postal']['state_full'] . ' ';
                                    if (!empty($address['postal']['postcode'])) echo $address['postal']['postcode'];
                                    if (!empty($address['postal']['country_full'])) echo '<br>' . $address['postal']['country_full'];

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
                            <a href="mailto:<?php echo $main['main_email']; ?>"><?php echo $main['main_email']; ?></a>
                        </td>
                    </tr>

                    <?php
                    if (empty($pots)) {
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
                                foreach ($pots as $key => $value) {

                                    if (!empty($value['number'])) {
                                        echo $value['number'] . ' (' . $value['type'] . ')<br>';
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }

                    if (empty($internet)) {
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
                                foreach ($internet as $key => $value) {

                                    if (!empty($value['id'])) {
                                        echo $value['id'] . ' (' . $value['type'] . ')<br>';
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

                <a href="<?php echo $recruitment_link; ?>" class="btn btn-sm btn-primary" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_main_goto_recruitment; ?>"><i class="fas fa-arrow-left"></i> <?php echo $term_main_goto_recruitment; ?></a>
            </div>
        </div>
    </div>
    <ul class="col-9">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="v-pills-home-tab" data-toggle="tab" href="#main" role="tab"
               aria-controls="v-pills-home" aria-selected="true">Main</a>
            </li>
            <?php
            if (!empty($general)) {
                ?>
                <li class="nav-item">
                    <a id="tab_lang" class="nav-link" data-toggle="tab" href="#lang"
                       role="tab"><?php echo $term_tab_lang ?></a>
                </li>
                <?php
                if ($general['seafarer'] || $general['migration']) {
                    ?>
                    <li class="nav-item">
                        <a id="tab_checks" class="nav-link" data-toggle="tab" href="#checks"
                           role="tab"><?php echo $term_tab_checks ?></a>
                    </li>
                    <?php
                }
                ?>

                <?php
                if ($general['passport']) {
                    ?>
                    <li class="nav-item">
                        <a id="tab_passp" class="nav-link" data-toggle="tab" href="#passp"
                           role="tab"><?php echo $term_tab_passp ?></a>
                    </li>
                    <?php
                }
                ?>
                <li class="nav-item">
                    <a id="tab_ids" class="nav-link" data-toggle="tab" href="#ids"
                       role="tab"><?php echo $term_tab_ids ?></a>
                </li>

                <li class="nav-item">
                    <a id="tab_employ" class="nav-link" data-toggle="tab" href="#employ"
                       role="tab"><?php echo $term_tab_employ ?></a>
                </li>

                <li class="nav-item">
                    <a id="tab_edu" class="nav-link" data-toggle="tab" href="#edu"
                       role="tab"><?php echo $term_tab_edu ?></a>
                </li>

                <li class="nav-item">
                    <a id="tab_med" class="nav-link" data-toggle="tab" href="#med"
                       role="tab"><?php echo $term_tab_med ?></a>
                </li>
                <?php
                if ($general['tattoo']) {
                    ?>
                    <li class="nav-item">
                        <a id="tab_tat" class="nav-link" data-toggle="tab" href="#tat"
                           role="tab"><?php echo $term_tab_tat ?></a>
                    </li>
                    <?php
                }
                ?>

                <li class="nav-item">
                    <a id="tab_ref" class="nav-link" data-toggle="tab" href="#ref"
                       role="tab"><?php echo $term_tab_ref ?></a>
                </li>
                <?php
            }
            ?>
        </ul>
        <div class="tab-content" id="v-pills-tabContent">
            <?php
            include('section/main_info.php');
            ?>

            <?php

            if (!empty($general)) {

                include( 'section/language.php');

                if ($general['seafarer'] || $general['migration']) {
                    include('section/checks.php');
                }

                if ($general['passport']) {
                    include( 'section/passport.php');
                }

                include( 'section/ids.php');

                include('section/employment.php');

                include('section/education.php');

                include('section/medical.php');

                if ($general['tattoo']) {
                    include('section/tattoo.php');
                }

                include('section/referrence.php');
            }
            ?>

        </div>
    </div>
</div>