<div class="row">
<div class="container col-md-8 text-center">
    <?php
    if(isset($errors) && is_array($errors))
    {
        ?>
        <div class="row mb-3">
            <div class="col">
                <div class="iow-callout iow-callout-warning">
                    <h2 class="text-warning"><?php echo $term_error_legend ?></h2>
                    <?php
                    foreach($errors as $key => $value)
                    {
                        $tname = 'term_'.$key.'_label';
                        $etitle = isset($$tname) ? $$tname : $key;
                        echo "				<p class=\"text-warning\"><strong>{$etitle}</strong> {$value}</p>\n";
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    ?>
    <div class="row">
        <div class="col">
            <!-- <div class="row">
                <div class="col text-center mb-4">
                    
                </div>
            </div> -->

            <div class="card">

                <?php
                if(isset($partner_file['filename']))
                {
                    echo '<img width="100%" class="" src="/ao/show/'.$partner_file['filename'].'" alt="partner banner">';
                }
                ?>

                <div class="card-header blue-gradient white-text text-center py-4">
                    <h4><?php echo $term_slogan_heading;?></h4>
                    <span><?php echo $term_sub_slogan_heading;?></span>
                    <br>
                    <!-- <strong><?php echo $term_page_heading; if (isset($partner_data)){echo ' '.$partner_data['entity_family_name'];}?></strong> -->
                </div>
                

                <!--Card content-->
                <div class="card-body px-lg-5 pt-0">

                    <!-- Form -->
                    <form id="form-register" method="post" action="<?php echo $myURL; ?>">
                        <input type="hidden" name="reCAPTCHA_Register_Token" id="reCAPTCHA_Register_Token">
                        <?php
                        if (isset($partner_data['address_book_id']))
                        {
                        ?>
                        <input type="hidden" id="partner_id" name="partner_id" value="<?php echo $partner_data['address_book_id'] ?>">
                        <?php
                        }
                        ?>
                        <!-- Country Row -->
                        <div class="row justify-content-center align-items-center">
                            <div class="col-xl-6 col-xs-12">
                                <div class="md-form mt-0">
                                    <label text-success><?php echo $term_citizen_country ?>:</label>
                                </div>
                            </div>

                            <div class="col-xl-6 col-xs-12">
                                <select class="mdb-select md-form" searchable="Search country.." id="country" name="country">

                                    <option class="none" value="not specified" disabled <?php if($country == 'not specified' && $current_location == 'UNKNOWN') echo "selected"; ?>><?php echo $term_country_select; ?></option>
                                    <?php
                                    foreach($countries as $code => $name)
                                    {
                                        $infoClass = isset($countries_info_code[$code]) ? $countries_info_code[$code] : 'default';
                                        $selected = (strtolower($name) == strtolower($current_location)) ? 'selected':'';

                                        strtolower($name) === strtolower($current_location) ? "selected":'';
                                        if($code == $country)
                                        {
                                            echo '<option class="'.$infoClass.'" value="'.$code.'" '.$selected.'>'.$name."</option>\n";
                                        } else {
                                            echo '<option class="'.$infoClass.'" value="'.$code.'" '.$selected.'>'.$name."</option>\n";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <?php
                        foreach($country_code_info as $code => $value)
                        {
                            ?>
                            <div id="<?php echo $code; ?>" class="row country-info not-showing">
                                <div class="col mb-4">
                                    <div class="iow-callout iow-callout-<?php echo $value['type']; ?>">
                                        <h2 class="text-<?php echo $value['type']; ?>"><?php echo $value['heading']; ?></h2>
                                        <p class="text-left"><?php echo $value['short_description']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                        <div id="allowed"  class="not-showing">


                            <!-- E-mail -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="md-form mt-2">
                                        <input type="email" data-recaptcha="<?php echo $recaptcha; ?>" id="main_email" class="form-control" name="main_email" maxlength="255" value="<?php echo $main_email ?>">
                                        <label for="main_email"><?php echo $term_main_email ?></label>
                                    </div>
                                </div>
                            </div>

                            <!-- Family Name Grid Row -->
                            <div class="row">
                                <!-- Title -->
                                <div class="col-lg-2">
                                    <div class="md-form mt-0">
                                        <!-- <input type="text" class="form-control" placeholder="<?php echo $term_title ?>" id="title"  name="title" maxlength="10" value="<?php echo $title ?>"> -->
                                        <select class="browser-default custom-select" id ="title" name="title">
                                            <option value="Mr">Mr</option>
                                            <option value="Mrs">Mrs</option>
                                            <option value="Miss">Miss</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Family Name -->
                                <div class="col-lg-10">
                                    <div class="md-form mt-0">
                                        <input type="text" class="form-control" placeholder="<?php echo $term_family_name ?>" id="family_name"  name="family_name" maxlength="100" value="<?php echo $family_name ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Given Names Grid Row -->
                            <div class="row">
                                <!-- Given Names -->
                                <div class="col-lg-6">
                                    <div class="md-form mt-0">
                                        <input type="text" class="form-control" placeholder="<?php echo $term_given_name ?>" id="given_name"  name="given_name" maxlength="100" value="<?php echo $given_name ?>">
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

                            <hr class="info-color">

                            <div class="row">
                                <!-- Acknowledge -->
                                <div class="col-lg-6 text-left">
                                    <h5><i class="far fa-check-circle"></i> <?php echo $term_acknowledge ?></h5>
                                </div>

                                <div class="col-lg-6 text-left">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="accurate" name="accurate" value="1" <?php if($accurate) { echo 'checked'; } ?>>
                                        <label class="form-check-label" for="accurate"><?php echo $term_accurate ?></label>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="english" name="english" value="1" <?php if($english) { echo 'checked'; } ?>>
                                        <label class="form-check-label" for="english"><?php echo $term_english ?></label>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="register" name="register" value="1" <?php if($register) { echo 'checked'; } ?>>
                                        <label class="form-check-label" for="register"><?php echo $term_register ?></label>
                                    </div>
                                </div>
                            </div>

                            <?php if($recaptcha): ?>
                                <script nonce="<?php echo $nonce; ?>">
                                grecaptcha.ready(function () {
                                    grecaptcha.execute('<?php echo $recaptcha; ?>', { action: 'register' }).then(function (token) {
                                        $('#reCAPTCHA_Register_Token').val(token);
                                    });
                                });
                                </script>

                            <?php endif ?>


                            <?php
                            if($use_captcha)
                            {
                                ?>
                                <div class="input-group input-group-lg mb-3">

                                    <div class="input-group-append">
                                        <span class="input-group-text" id="captcha-code"><img src="/lib/captcha/captcha.php"></span>
                                    </div>

                                    <input id="catchaAnswer" name="captcha" type="text" class="form-control" aria-label="Enter Captcha Code Here" aria-describedby="captcha" required>

                                </div>
                                <?php
                            }
                            ?>


                            <hr class="info-color">

                            <div class="row">
                                <!-- Submit -->
                                <div class="col">

                                    <input id="register_ajax" value="<?php echo $register_ajax ?>" hidden="hidden" />
                                    <button id="submit_button" type="submit" class="btn btn-lg btn-success btn-block" disabled="disable"><?php echo $term_register_now_button ?></button>
                                    <p id="email_note" class="text-warning text-center">
                                        <strong>Note : The main email can't be empty!</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
    
    <?php


    if($isAdmin)
    {
        ?>
        <div class="row mt-5">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <p><a href="<?php echo $edit_link; ?>" class="btn btn-info" role="button"><?php echo $term_go_edit; ?></a></p>
            </div>
        </div>
        <?php
    }
    ?>


</div>