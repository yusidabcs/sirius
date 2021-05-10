<div class="card mb-4">

    <h5 class="card-header info-color text-center py-3">
        <strong><?php echo $term_heading ?></strong>
    </h5>
	
	<div class="card-body px-lg-5 pt-0">

		<div class="row">
			<div class="col-12">
<?php
			if($personOnly)
			{
?>

				<div class="form-group not-showing  ">
					<label for="type" class="col-form-label"><?php echo $term_type ?></label>
                    <select id="type" class="md-form mdb-select disabled" name="main[type]">
                        <option value="per" selected><?php echo $term_per ?></option>
                    </select>
				</div>
<?php
			} else {
?>
				<div class="form-group">
                    <label for="type" ><?php echo $term_type ?></label>
                    <select id="type" class="md-form mdb-select" name="main[type]">
                        <option value="per" <?php if($type == 'per') echo 'selected'; ?>><?php echo $term_per ?></option>
                        <option value="ent" <?php if($type == 'ent') echo 'selected'; ?>><?php echo $term_ent ?></option>
                    </select>
				</div>			
<?php
			}
?>
				<div class="form-group per">
					<label for="title" class="col-form-label"><?php echo $term_title ?></label>
                    <input id="title" class="form-control" name="main[title]" type="text" maxlength="10" value="<?php echo $title ?>">
				</div>

				<div class="form-group ">
					<label for="entity_family_name" class="col-form-label per"><?php echo $term_entity_family_name_per ?></label>
	                <label for="entity_family_name" class="col-form-label ent"><?php echo $term_entity_family_name_ent ?></label>
                    <input id="entity_family_name" class="form-control" name="main[entity_family_name]" type="text" maxlength="100" value="<?php echo $entity_family_name ?>" >
				</div>

				<div class="form-group">
					<label for="number_given_name" class="col-form-label per"><?php echo $term_number_given_name_per ?></label>
	                <label for="number_given_name" class="col-form-label ent"><?php echo $term_number_given_name_ent ?></label>
                    <input id="number_given_name" class="form-control" name="main[number_given_name]" type="text" maxlength="100" value="<?php echo $number_given_name ?>" >
				</div>

				<div class="form-group per">
					<label for="middle_names" class="col-form-label"><?php echo $term_middle_names ?></label>
                    <input id="middle_names" class="form-control" name="main[middle_names]" type="text" maxlength="255" value="<?php echo $middle_names ?>">
				</div>

				<div class="form-group per">
					<label for="dob" class="col-form-label"><?php echo $term_dob ?></label>
                    <input id="dob" class="form-control dob" name="main[dob]" type="text" placeholder="<?php echo $term_dob_placeholder ?>" readonly="readonly" value="<?php echo $dob ?>" data-min-date="<?php echo $dob_min ?>" data-max-date="<?php echo $dob_max ?>">
				</div>

				<div class="form-group per">
					<label for="sex" class="col-form-label"><?php echo $term_sex ?></label>
                    <select id="sex" class="md-form mdb-select" name="main[sex]">
                        <option value="not specified" <?php if($sex == 'not specified') echo 'selected'; ?>><?php echo $term_sex_select ?></option>
                        <option value="male" <?php if($sex == 'male') echo 'selected'; ?>><?php echo $term_sex_male ?></option>
                        <option value="female" <?php if($sex == 'female') echo 'selected'; ?>><?php echo $term_sex_female ?></option>
                    </select>
				</div>

				<input id="email_required" type="text" value="<?php echo ADDRESS_BOOK_MAIN_REQUIRE_EMAIL; ?>" hidden >
<?php				
			if($fixedEmail)
			{
?>
				<div id="user_email_group" class="form-group row  has-feedback">
					<label for="main_email" class="col-lg-2 col-form-label"><?php echo $term_main_email ?></label>
					<div class="col-lg-10">
						<div class="md-form mt-0">
							<input id="main_email" class="form-control" name="main[main_email]" type="text" maxlength="255" value="<?php echo $mainEmail ?>" readonly>
							<span id="user_result" class="glyphicon form-control-feedback"></span>
						</div>
					</div>
				</div>
<?php
			} else {
?>
				<div id="user_email_group" class="form-group row  has-feedback">
					<label for="main_email" class="col-lg-2 col-form-label"><?php echo $term_main_email ?></label>
					<div class="col-lg-10">
						<div class="md-form mt-0">
							<input id="main_email" class="form-control" name="main[main_email]" type="email" maxlength="255" value="">
							<span id="user_result" class="glyphicon form-control-feedback"></span>
						</div>
					</div>
				</div>
<?php
			}
?>
				<input id="per_address_book_id" name="main[per_address_book_id]" type="text" value="<?php echo $per_address_book_id ?>" hidden >

				<div id="allow_contact_email_div" class="not-showing form-check">
				    <input type="checkbox" class="form-check-input" id="contact_allowed" name="main[contact_allowed]" value="1" <?php if($contact_allowed) { echo 'checked'; } ?>>
				    <label class="form-check-label" for="contact_allowed"><?php echo $term_contact_allowed ?></label>
				</div>
				
				<div id="main_add_user_per" class="not-showing">
					
					<div id="add_new_user_div" class="form-check">
					    <input type="checkbox" class="form-check-input" id="add_new_user" name="main[add_new_user]" value="1" <?php if($add_new_user) { echo 'checked'; } ?>>
					    <label class="form-check-label" for="add_new_user"><?php echo $term_add_new_user ?></label>
					</div>
					
					<div id="send_new_user_email_div" class="form-check">
					    <input type="checkbox" class="form-check-input" id="send_new_user_email" name="main[send_new_user_email]" value="1" <?php if($send_new_user_email) { echo 'checked'; } ?>>
					    <label class="form-check-label" for="send_new_user_email"><?php echo $term_send_new_user_email ?></label>
					</div>
					
				</div>
								
				<div class="right">	
					<p>
						<button id="checkMainDetails" type="button" class="btn btn-warning"><?php echo $term_button_main_check; ?></button>
					</p>
				</div>
								
				<div id="ent_admin" class="not-showing">
												
					<div class="card mb-4">

					    <h6 class="card-header blue-gradient white-text text-center">
					        <strong><?php echo $term_ent_admin_heading ?></strong>
					    </h6>
						
						<div class="card-body px-lg-5 pt-0">
									
							<div id="ent_admin_same_email_div" class="form-check not-showing my-3">
							    <input type="checkbox" class="form-check-input" id="ent_admin_same_email" name="main[ent_admin][same_email]" value="1" <?php if($ent_admin['same_email']) { echo 'checked'; } ?>>
							    <label class="form-check-label" for="ent_admin_same_email"><?php echo $term_ent_admin_same_email ?></label>
							</div>
									
							<div id="ent_admin_email_div">
								
								<div id="ent_admin_email_group" class="form-group has-feedback mt-3">
									<label for="ent_admin_email" class="col-form-label"><?php echo $term_main_email ?></label>
                                    <input id="ent_admin_email" class="form-control" name="main[ent_admin][email]" type="text" maxlength="255" value="<?php echo $ent_admin['email'] ?>">
                                    <span id="ent_admin_email_result" class="glyphicon form-control-feedback"></span>
								</div>

							</div>
							
							<input id="ent_admin_per_address_book_id" name="main[ent_admin][per_address_book_id]" type="text" value="<?php echo $ent_admin['per_address_book_id'] ?>" hidden >

							<div id="ent_admin_new_details">
								
								<div class="form-group">
									<label for="ent_admin_title" class="col-form-label"><?php echo $term_title ?></label>
                                    <input id="ent_admin_title" class="form-control" name="main[ent_admin][title]" type="text" maxlength="10" value="<?php echo $ent_admin['title'] ?>">
								</div>	
									
								<div class="form-group ">
									<label for="ent_admin_family_name" class="col-form-label"><?php echo $term_entity_family_name_per ?></label>
                                    <input id="ent_admin_family_name" class="form-control" name="main[ent_admin][family_name]" type="text" maxlength="100" value="<?php echo $ent_admin['family_name'] ?>">
								</div>
								
								<div class="form-group ">
									<label for="ent_admin_given_name" class="col-form-label"><?php echo $term_number_given_name_per ?></label>
                                    <input id="ent_admin_given_name" class="form-control" name="main[ent_admin][given_name]" type="text" maxlength="100" value="<?php echo $ent_admin['given_name'] ?>">
								</div>
								
								<div class="form-group">
									<label for="ent_admin_middle_names" class="col-form-label"><?php echo $term_middle_names ?></label>
                                    <input id="ent_admin_middle_names" class="form-control" name="main[ent_admin][middle_names]" type="text" maxlength="255" value="<?php echo $ent_admin['middle_names'] ?>">
								</div>

								<div class="form-group">
									<label for="ent_admin_dob" class="col-form-label"><?php echo $term_dob ?></label>
                                    <input id="ent_admin_dob" class="form-control dob" name="main[ent_admin][dob]" type="text" placeholder="<?php echo $term_dob_placeholder ?>" readonly="readonly" value="<?php echo $ent_admin['dob'] ?>" data-min-date="<?php echo $dob_min ?>" data-max-date="<?php echo $dob_max ?>">

                                </div>

								<div class="form-group row">
									<label for="ent_admin_sex" class="col-form-label"><?php echo $term_sex ?></label>
                                    <select id="ent_admin_sex" class="md-form mdb-select" name="main[ent_admin][sex]">
                                        <option value="not specified" <?php if($ent_admin['sex'] == 'not specified') echo 'selected'; ?>><?php echo $term_sex_select ?></option>
                                        <option value="male" <?php if($ent_admin['sex'] == 'male') echo 'selected'; ?>><?php echo $term_sex_male ?></option>
                                        <option value="female" <?php if($ent_admin['sex'] == 'female') echo 'selected'; ?>><?php echo $term_sex_female ?></option>
                                    </select>
								</div>				

								<div id="ent_admin_check_boxes" class="not-showing">		
										
									<div id="ent_admin_allow_contact_email_div" class="form-check">
									    <input type="checkbox" class="form-check-input" id="ent_admin_contact_allowed" name="main[ent_admin][contact_allowed]" value="1" <?php if($ent_admin['contact_allowed']) { echo 'checked'; } ?>>
									    <label class="form-check-label" for="ent_admin_contact_allowed"><?php echo $term_contact_allowed ?></label>
									</div>
									
									<div id="ent_admin_send_new_user_div">
										
										<div id="ent_admin_add_new_user_div" class="form-check">
										    <input type="checkbox" class="form-check-input" id="ent_admin_add_new_user" name="main[ent_admin][add_new_user]" value="1" <?php if($ent_admin['add_new_user']) { echo 'checked'; } ?>>
										    <label class="form-check-label" for="ent_admin_add_new_user"><?php echo $term_add_new_user ?></label>  
										</div>
										
										<div id="ent_admin_send_new_user_email_div" class="form-check">
										    <input type="checkbox" class="form-check-input" id="ent_admin_send_new_user_email" name="main[ent_admin][send_new_user_email]" value="1" <?php if($ent_admin['send_new_user_email']) { echo 'checked'; } ?>>
										    <label class="form-check-label" for="ent_admin_send_new_user_email"><?php echo $term_send_new_user_email ?></label>
										</div>
										
										<div class="right">	
											<p>
												<button id="checkAdminDetails" type="button" class="btn btn-warning"><?php echo $term_button_ent_admin_check; ?></button>
											</p>
										</div>
									</div>
								</div>
							</div>		
						</div>
					</div>
				</div>		
			</div>
		</div>	
	</div>
</div>
