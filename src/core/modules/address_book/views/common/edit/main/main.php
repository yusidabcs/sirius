<div class="card mb-4">

    <h5 class="card-header blue-gradient white-text text-center py-4">
        <strong><?php echo $term_heading ?></strong>
    </h5>
	
	<div class="card-body px-lg-5 pt-0">
		
		<input id="address_book_id" name="address_book_id" type="text" value="<?php echo $address_book_id ?>" class="not-showing" />
<?php
	if(ADDRESS_BOOK_MAIN_REQUIRE_EMAIL)
	{
?>
		<input id="email_required" type="text" value="1" class="not-showing" />
<?php
	} else {
?>
		<input id="email_required" type="text" value="" class="not-showing" />
<?php						
	}
?>						
		<!-- Type Row -->
        <div class="form-group mt-3">
            <label for="type"><?php echo $term_type ?></label>
            <select class="form-control" id="type" name="main[type]" disabled>
                <option value="per" <?php if($type == 'per') echo 'selected="selected"'; ?>><?php echo $term_per ?></option>
                <option value="ent" <?php if($type == 'ent') echo 'selected="selected"'; ?>><?php echo $term_ent ?></option>
            </select>
        </div>

		<!-- Family Name Grid Row -->
	    <div class="row">
	        <!-- Title -->
	        <div class="col-lg-4 per">
	            <div class="md-form mt-4">
                    <select class="browser-default custom-select" id ="title" name="main[title]">
                        <option value="Mr" <?php echo ($title == 'Mr')? 'selected':'' ?>>Mr</option>
                        <option value="Mrs" <?php echo ($title == 'Mrs')? 'selected':'' ?>>Mrs</option>
                        <option value="Miss" <?php echo ($title == 'Miss')? 'selected':'' ?>>Miss</option>
                    </select>
	            </div>
	        </div>
	        <!-- Entity or Family Name -->
	        <div id="ent_fam_div">
	            <div class="md-form mt-4">
		            <label class="per" for="entity_family_name"><?php echo $term_entity_family_name_per ?></label>
					<label class="ent" for="entity_family_name"><?php echo $term_entity_family_name_ent ?></label>
					<input type="text" class="form-control" id="entity_family_name" name="main[entity_family_name]" maxlength="100" value="<?php echo $entity_family_name ?>">
	            </div>
	        </div>
	    </div>
	    
	    <!-- Number or Given Names Grid Row -->
	    <div class="row">
	        <!-- Given Names -->
	        <div id="num_giv_div">
	            <div class="md-form mt-4">
		            <label class="per" for="entity_family_name"><?php echo $term_number_given_name_per ?> <span class="required"></span></label>
					<label class="ent" for="entity_family_name"><?php echo $term_number_given_name_ent ?> <span class="required"></span></label>
	                <input type="text" class="form-control" id="number_given_name"  name="main[number_given_name]" maxlength="100" value="<?php echo $number_given_name ?>">
	            </div>
	        </div>
	        <!-- Middle Names -->
	        <div class="col-lg-6 per">
	            <div class="md-form mt-4">
		            <label for="middle_names"><?php echo $term_middle_names ?> </label>
	                <input type="text" class="form-control" id="middle_names"  name="main[middle_names]" maxlength="255" value="<?php echo $middle_names ?>">
	            </div>
	        </div>
	    </div>

		<!-- Private Details Grid Row -->
		<div class="row per">
	        
	        <!-- DOB -->
			<div class="col-lg-6">
				
				<div class="md-form mt-4">
					<label for="dob"><?php echo $term_dob ?> <span class="required"></span></label>
					<input type="text" id="dob" class="form-control" name="main[dob]" readonly="readonly" value="<?php echo $dob ?>" data-min-date="<?php echo $dob_min ?>" data-max-date="<?php echo $dob_max ?>" >
				</div>
			</div>
	        
	        <!-- Sex -->
	        <div class="col-lg-6">
		        <div class="md-form mt-4">
<?php
				if($sex == 'not specified')
				{
?>
			        <!-- Not Specified -->
					<div class="form-check form-check-inline">
						<input type="radio" class="form-check-input" id="not_specified" name="main[sex]" value="not specified" checked>
						<label class="form-check-label" for="not_specified"><?php echo $term_sex_ns ?></label>
					</div>
<?php
				}
?>
					<!-- Male -->
					<div class="form-check form-check-inline">
						<input type="radio" class="form-check-input" id="male" name="main[sex]" value="male" <?php if($sex == 'male') echo 'checked'; ?>>
						<label class="form-check-label" for="male"><?php echo $term_sex_male ?></label>
					</div>
					<!-- Female -->
					<div class="form-check form-check-inline">
						<input type="radio" class="form-check-input" id="female" name="main[sex]" value="female" <?php if($sex == 'female') echo 'checked'; ?>>
						<label class="form-check-label" for="female"><?php echo $term_sex_female ?></label>
					</div>
		        </div>
	        </div>

		</div>

		<!-- E-mail Grid Row -->
	    <div class="row">  
			<!-- Main Email Address -->
			<div class="col-lg-8">
	            <div class="md-form">
	                <input type="email" id="main_email" class="form-control" name="main[main_email]" maxlength="255" value="<?php echo $main_email ?>" disabled>
	                <label for="main_email"><?php echo $term_main_email ?> <span class="required"></span></label>
	            </div>
                <div class="md-form">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" id="change_email" name="main[change_email]" type="checkbox" value="1">
                        <label class="form-check-label" for="change_email"><?php echo $term_change_email ?></label>
                    </div>
                </div>
			</div>
			<!-- allow contact -->
			<div class="col-lg-4 pt-xs-0 pt-lg-4 mb-4">
				<div id="allow_contact_email_div" class="form-check form-check-inline not-showing">
					<input class="form-check-input" id="contact_allowed" name="main[contact_allowed]" type="checkbox" value="1" <?php if($contact_allowed) { echo 'checked="checked"'; } ?>>
					<label class="form-check-label" for="contact_allowed"><?php echo $term_contact_allowed ?></label>
				</div>
			</div>
	    </div>

				
<!-- add the admin details -->
<?php
	if($type == 'ent')
	{
?>
		<div class="card ent">
            <div class="card-header d-flex justify-content-between align-items-center">
                Entity Contacts
                <button type="button" class="btn btn-info btn-sm " id="ab_add_contact_btn"><i class="fa fa-plus"></i> New</button>
            </div>
			<div class="card-body">
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
                                    <label for="ent_admin_email" class="col-form-label"><?php echo $term_main_email ?></label>
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
                                            <option value="staff" >Staff</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="role_id" class="control-label "><?php echo $term_role_id ?></label>
                                    <div class="">
                                        <select required id="role_id" name="role_id" class="form-control">
                                            <?php
                                            foreach($roles as $value)
                                            {
                                                ?>
                                                <option value="<?php echo $value['role_id']; ?>" ><?php echo $value['role_name']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-success btn-block btn-sm" id="link_ab_entity">Link Address Book</button>
                            </div>
                            <div id="ab_contact_info" class="border p-3 not-showing">

                                <div id="ent_admin_new_details">

                                    <div class="form-group">
                                        <label for="ent_admin_title" class="col-form-label"><?php echo $term_title ?></label>
                                        <input id="ent_admin_title" class="form-control" name="title" type="text" maxlength="10" value="">
                                    </div>

                                    <div class="form-group ">
                                        <label for="ent_admin_family_name" class="col-form-label"><?php echo $term_entity_family_name_per ?></label>
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
                                                <option value="staff" >Staff</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="role_id" class="control-label "><?php echo $term_role_id ?></label>
                                        <div class="">
                                            <select required id="role_id" name="role_id" class="form-control">
                                                <?php
                                                foreach($roles as $value)
                                                {
                                                    ?>
                                                    <option value="<?php echo $value['role_id']; ?>" ><?php echo $value['role_name']; ?></option>
                                                    <?php
                                                }
                                                ?>
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

<?php						
	}
?>	
	</div>

</div>
				