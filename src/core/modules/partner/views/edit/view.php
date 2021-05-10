
<section>
    <div class="container">

        <?php
        //If Error
        if(isset($errors) && is_array($errors))
        {
            ?>
            <div class="iow-callout iow-callout-warning">
                <h4 class="text-warning"><?php echo $term_error_legend ?></h4>
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
                    echo "				<p class=\"text-success\"><strong>{$title}</strong> {$value}</p>\n";
                }
                ?>
            </div>
            <?php
        }
        ?>

        <div class="card">
            <div class="card-header gradient-card-header blue-gradient">
                <h4 class="text-white text-center"><?php echo $term_local_partner_header ?></h4>
            </div>

            <!-- Card content -->
            <div class="card-body">
                <!-- Form -->
                <form id="form_partner_edit" style="color: #757575;" method="POST" action="<?php echo $myURL?>" enctype="multipart/form-data">
                    <input type="hidden" id="address_book_id" name="address_book_id" value="<?php echo $partner['address_book_id']; ?>"/>
                    <p>Address book detail.</p>
                    <div class="border p-3">
                        <button class="btn btn-danger float-right edit-address_book"><?php echo $term_edit_btn?></button>
                        <p>Name : <?php echo $partner['entity_family_name']?></p>
                        <p>Email : <?php echo $partner['main_email'] ?></p>
                    </div>
                    
                    <p class="alert alert-info partner_code_format mb-2 mt-2"><?php echo $term_partner_code_format?></p>
                    <!-- Code -->
                    <div class="form-group">
                        <label for="partner_type"><?php echo $term_partner_type ?></label>
                            
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="chk_lp" id="chk_lp" value="lp" <?php echo (in_array('LP',$partner_type)) ? 'checked':'' ?>>
                                <label class="form-check-label" for="chk_lp">LP</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="chk_lep" id="chk_lep" value="lep" <?php echo (in_array('LEP',$partner_type)) ? 'checked':'' ?>>
                                <label class="form-check-label" for="chk_lep">LEP</label>
                            </div>
                        <!-- <select id="partner_type" name="partner_type" class="mdb-select" required>
                            <option value="" disabled><?php echo $term_partner_type_none ?></option>
                            <option value="lp" <?php echo ($partner['type'] == 'lp') ? 'selected':'' ?>>LP</option>
                            <option value="lep" <?php echo ($partner['type'] == 'lep') ? 'selected':'' ?>>LEP</option>
                        </select> -->
                        <!-- <label for="partner_type"><?php echo $term_partner_type ?></label> -->
                    </div>

                    <div class="md-form">
                        
                        <div class="float-right mr-4">
                            <div id="partner_code_spinner" class="not-showing spinner-border position-absolute" role="status" aria-hidden="true"></div>
                            <div id="partner_code_valid" class="fa fa-lg fa-check mt-2 position-absolute text-success" role="status" aria-hidden="true"></div>
                        </div>  
                        <input type="text" id="partner_code" name="partner_code" class="form-control" value="<?php echo $partner['partner_code']?>" pattern="[a-zA-Z0-9-]+" required>
                        <label for="partner_code"><?php echo $term_partner_code ?></label>
                        <div class="invalid-feedback">
                            <p class="alert alert-warning"><?php echo $term_partner_code_should_unique?></p>
                        </div>

                        <div id="partner_code_warning" class="text-warning"></div>
                        <div id="partner_code_success" class="text-success"></div>
                    </div>

                    <div class="mt-3 border p-3">

                        <h5>Covered Area</h5>

                        <!-- Country -->
                        <div class="md-form">
                            <select id="country" name="countryCode_id[]" class="mdb-select colorful-select dropdown-primary md-form" multiple
                                    searchable="Search here.." required>
                                <option value="" disabled>Choose your country</option>
                                <?php foreach ($countries as $index => $country):?>
                                    <option value="<?php echo $index?>"  <?php echo in_array($index, json_decode($partner['countryCode_id'])) ? 'selected' : ''?> ><?php echo $country ?></option>
                                <?php endforeach;?>
                            </select>
                            <label for="country"><?php echo $term_partner_country ?></label>
                        </div>

                        <!-- Sub Country -->
                        <div id="sub_countries" class="row m-0">
                            <?php
                            $subcountries = json_decode($partner['countrySubCode_id'],true);
                            //generate subcountry select option html for each country
                            foreach($partner_subcountries as $key => $value)
                            {
                                if(count($value) == 0){
                                    continue;
                                }
                                $html = '<div class="col-md-6 subcountry" id="'.$key.'">';
                                $html .= '<div class="m-1 border p-3">';
                                $html .= '<select id="sub_country_'.$key.'" name="countrySubCode_id['.$key.'][]" class="mdb-select colorful-select dropdown-primary md-form" multiple searchable="Search here.." required>';
                                foreach ($value as $subcode => $data)
                                {
                                    $html .= '<option value="'.$subcode.'" '. ( ( ( in_array($subcode, $subcountries[$key]) )  || ($subcountries[$key][0] == '999') ) ? ' selected': '' ).'  >'.$data.'</option>';
                                }
                                $html .= '</select>';
                                $html .= '<label for="sub_country_'.$key.'">Subcountry - '.$countries[$key].' </label>';
                                $html .= '</div>';
                                $html .= '<input type="hidden" name="countrySubCode_idLength['.$key.']" value="'.count($value).'">';
                                $html .= '</div>';
                                echo $html;
                            }
                            ?>
                        </div>


                    </div>
                    <!-- start key person parner -->
                    <div class="mt-3">
                        <div class="card ent">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                Entity Contacts
                                <button type="button" class="btn btn-info btn-sm " id="ab_add_contact_btn"><i class="fa fa-plus"></i> New</button>
                            </div>
                            <div class="card-body list_ent_admin">
                                <ul class="list-group">

                <?php
                            foreach($ent_admin_details as $ent_admin_id => $value)
                            {						
                ?>
                                <li class="list-group-item">
                                    <i class="fas fa-user"></i> <?php echo $value['key_person'] ?>

                                    <p><strong><?php echo $value['full_name'] ?></strong> (<?php echo $value['email'] ?>) <a href="#" class="btn btn-link btn-sm ab_delete_contact_btn float-right" data-address_book_per_id="<?php echo $ent_admin_id?>" ><i class="far fa-trash-alt fa-2x text-danger" ></i></a> </p>
                                </li>
                <?php
                            }
                ?>

                                </ul>
                            </div>
                        </div>


                        <!-- Modal -->
                        <div class="modal fade" id="ab_add_contact_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Contact</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body pt-0">
                                        <div class="p-1">

                                            <div id="ent_admin_email_div">
                                                <div id="ent_admin_email_group" class="form-group has-feedback mt-3">
                                                    <label for="ent_admin_email" class="col-form-label">Main User Email</label>
                                                    <input id="ent_admin_email" class="form-control" name="email" type="email" maxlength="255" value="">
                                                    <span id="ent_admin_email_result" class="glyphicon form-control-feedback"></span>
                                                </div>

                                                <button type="button" class="btn btn-info btn-sm btn-block" id="ab_contact_check_email">Check Email</button>
                                                <br>
                                            </div>

                                            <div id="ab_contact_exist" class="not-showing">

                                                <input id="address_book_per_id" name="address_book_per_id" type="hidden" value="">
                                                <input id="address_book_ent_id" name="address_book_ent_id" type="hidden" value="">

                                                <div class="form-group">
                                                    <label for="security_level_id" class="control-label "><?php echo $term_person_type ?></label>
                                                    <div class="">
                                                        <select required name="person_type" class="form-control">
                                                            <option value="key_person" >Key person</option>
                                                            <option value="owner" >Owner</option>
                                                            <option value="manager" >Manager</option>
                                                            <option value="staff" selected>Staff</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <button type="button" class="btn btn-success btn-block btn-sm" id="link_ab_entity">Link Address Book</button>
                                            </div>
                                            <div id="ab_contact_info" class="border p-3 not-showing">

                                                <div id="ent_admin_new_details">

                                                    <div class="form-group">
                                                        <label for="ent_admin_title" class="col-form-label"><?php echo $term_title ?> <span class="required"></span></label>
                                                        <input id="ent_admin_title" class="form-control" name="title" type="text" maxlength="10" value="">
                                                    </div>

                                                    <div class="form-group ">
                                                        <label for="ent_admin_family_name" class="col-form-label"><?php echo $term_entity_family_name_per ?> <span class="required"></span></label>
                                                        <input id="ent_admin_family_name" class="form-control" name="family_name" type="text" maxlength="100" value="" >
                                                    </div>

                                                    <div class="form-group ">
                                                        <label for="ent_admin_given_name" class="col-form-label"><?php echo $term_number_given_name_per ?> <span class="required"></span></label>
                                                        <input id="ent_admin_given_name" class="form-control" name="given_name" type="text" maxlength="100" value="">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="ent_admin_middle_names" class="col-form-label"><?php echo $term_middle_names ?></label>
                                                        <input id="ent_admin_middle_names" class="form-control" name="middle_names" type="text" maxlength="255" value="">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="ent_admin_dob" class="col-form-label"><?php echo $term_dob ?> <span class="required"></span></label>
                                                        <input id="ent_admin_dob" class="form-control dob" name="dob" type="text" placeholder="<?php echo $term_dob_placeholder ?>" readonly="readonly" value="" data-min-date="<?php echo $dob_min ?>" data-max-date="<?php echo $dob_max ?>">

                                                    </div>

                                                    <div class="form-group">
                                                        <label for="ent_admin_sex" class="col-form-label"><?php echo $term_sex ?></label>

                                                        <!-- Default unchecked -->
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" id="sex_male" name="sex" value="male">
                                                            <label class="custom-control-label" for="sex_male"><?php echo $term_sex_male ?></label>
                                                        </div>

                                                        <!-- Default checked -->
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" id="sex_female" name="sex" value="female" >
                                                            <label class="custom-control-label" for="sex_female"><?php echo $term_sex_female ?></label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="security_level_id" class="control-label "><?php echo $term_person_type ?></label>
                                                        <div class="">
                                                            <select required name="person_type" class="form-control">
                                                                <option value="key_person" >Key person</option>
                                                                <option value="owner" >Owner</option>
                                                                <option value="manager" >Manager</option>
                                                                <option value="staff" selected>Staff</option>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div id="ent_admin_check_boxes" class="mb-3">

                                                        <div id="ent_admin_allow_contact_email_div" class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="ent_admin_contact_allowed" name="contact_allowed" value="1" checked>
                                                            <label class="form-check-label" for="ent_admin_contact_allowed"><?php echo $term_contact_allowed ?></label>
                                                        </div>

                                                        <div id="ent_admin_send_new_user_div">

                                                            <div id="ent_admin_add_new_user_div" class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="ent_admin_add_new_user" name="add_new_user" value="1" checked>
                                                                <label class="form-check-label" for="ent_admin_add_new_user"><?php echo $term_add_new_user ?></label>
                                                            </div>

                                                            <div id="ent_admin_send_new_user_email_div" class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="ent_admin_send_new_user_email" name="send_new_user_email" value="1" checked>
                                                                <label class="form-check-label" for="ent_admin_send_new_user_email"><?php echo $term_send_new_user_email ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-primary btn-block" id="create_ab_entity">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end key person parner -->

                    <div class="mt-3">

                        <!-- banner photo -->
                        <div id="banner_image" class="card mb-4">

                            <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                <i class="fas fa-image"></i> <?php echo $term_banner_image_heading; ?>
                            </h4>

                            <div class="card-body">

                                <?php
                                $class = 'not-showing';
                                if(!empty($partner_file['filename'])) {
                                    $class = '';
                                    ?>
                                    <!-- banner image if any -->
                                    <div>
                                        <input type="hidden" id="banner_current" name="banner_current" value="<?php echo $partner_file['filename']; ?>">
                                    </div>
                                    <!-- end of banner image-->
                                    <?php
                                }
                                ?>
                                <div class="text-center <?php echo $class;?>">
                                        <img src="/ab/show/<?php echo $partner_file['filename']; ?>" id="banner_img" class="img-fluid" alt="Current Banner Image" >
                                        <button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								<hr>
                                    </div>
                                <div class="form-group">
                                    <label for="banner_input"><?php echo $term_banner_image_choose_file; ?></label>
                                    <input type="file" id="banner_input" accept=".jpg,.png,.gif" >
                                    <input type="hidden" id="banner_base64" name="banner_base64">
                                </div>

                                <div id="banner_croppie_wrap" class="mw-100 w-auto mh-100 h-auto not-showing">
                                    <div id="banner_croppie" data-banner-width="931" data-banner-height="230"></div>
                                </div>

                                <button class="btn btn-default btn-block not-showing" type="button" id="banner_result"><?php echo $term_banner_image_crop; ?></button>

                            </div>

                        </div>

                    </div>

                        <!-- Send button -->
                    <div class="justify-content-center">
                        <div class="row flex-column-reverse flex-lg-row">
                            <div class="col-lg-6 left">
                                <a id="go_back" href="<?php echo $back_link ?>" class="btn btn-warning font-weight-bold btn-sm-mobile-100 waves-effect" role="button"><i class="fas fa-arrow-circle-left"></i> <?php echo $term_back_btn; ?></a>
                            </div>
                            <div class="col-lg-6 right">
                                <button type="submit" class="btn btn-success font-weight-bold btn-sm-mobile-100 waves-effect"><i class="fas fa-save"></i> <?php echo $term_edit_btn; ?></button>
                            </div>
                        </div>
                        
                        <!-- <a href="<?php echo $back_link ?>" class="btn back-btn btn-warning btn-rounded waves-effect"><?php echo $term_back_btn?></a>
                        <button class="btn btn-info btn-rounded z-depth-0 waves-effect btn-partner" type="submit"><?php echo $term_edit_btn?></button> -->
                    </div>
                </form>
                <!-- Form -->

                <!-- Update address book modal -->
                <div class="modal fade" id="update_ab_form_modal" tabindex="-1" role="dialog" aria-labelledby="abModal"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        
                        <form id="update_ab_form" v-on:submit.prevent="submitUpdateAB">
                            <input type="hidden" id="page_link" value="<?php echo $page_link?>">
                            <input type="hidden" id="old_ab" value="<?php echo $partner['address_book_id'] ?>">
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <h4 class="modal-title w-100 font-weight-bold"><?php echo $term_update_ab_title ?></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body mx-3">
                                    <p><?php echo $term_partner_search_by_address_book?></p>
                                    <div class="row border m-0">
                                        <div class="col-md-12">
                                            <div class="md-form">
                                                <div class="float-right mr-4">
                                                    <div id="search_ab_spinner" class="not-showing spinner-border position-absolute" role="status" aria-hidden="true"></div>
                                                </div>
                                                <input type="text" class="form-control" name="" id="search_ab">    
                                                <label for="search_ab"><?php echo $term_address_book_search?></label>
                                                <div class="invalid-feedback">
                                                    <p class="alert alert-warning"><?php echo $term_address_book_email_not_found?></p>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="col-md-12 not-showing" id="div_ab">
                                            <div class="md-form">
                                                <select class="mdb-select md-form" id="new_ab" searchable="<?php echo $term_general_search?>">
                                                    <option value="" disabled selected>Choose Address Book</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-center">
                                    <button type="button" class="btn btn-default btn-edit-address_book" disabled><?php echo $term_edit_btn?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>