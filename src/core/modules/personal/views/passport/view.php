<?php
		if(isset($errors) && is_array($errors))
		{
?>
			<div class="iow-callout iow-callout-warning">
				<h2 class="text-warning"><?php echo $term_error_legend ?></h2>
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

<div class="row">
	<div class="col-12" >
		
		<!-- Main Card -->
		<div class="card mb-4">
		    <h3 class="card-header blue-gradient white-text text-center py-4">
			    <?php echo $term_passport_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
		    </h3>
			
			<form method="post" id="passport">
				
				<div class="card-body">
					<!-- main passport -->
					<div id="main_passport_data" class="card mb-4">
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="far fa-question-circle"></i> <?php echo $term_passport_type_heading; ?>
						</h4>
						
						<div class="card-body">
					
							<div class="row align-items-center">
								<div class="col-md-4 text-left text-md-right required"><b><?php echo $term_passport_country; ?></b></div>
								
								<div class="col-md-8 form-group ">
									<select id="countryCode_id" name="countryCode_id" class="mdb-select md-form" searchable="Search" required>
										<option value=""><?php echo $term_passport_table_select_please; ?></option>
<?php
										foreach($countryCodes as $id => $country)
										{
?>											
											
											<option value="<?php echo $id; ?>" <?php if($passport['countryCode_id'] == $id) echo 'selected="Selected"'; ?>><?php echo $country; ?></option>
<?php
										}
?>
									</select>
								</div>
									
								
								<div class="col-md-4 text-left text-md-right required">
									<b><?php echo $term_passport_active; ?></b>
									<input type="hidden" id="active_current" name="active_current" value="<?php echo $passport['active']; ?>">
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="iow-ck-button">
										<label>
											<input type="radio" class="active" id="active" name="active" value="active" hidden="hidden">
											<span class="passport"><?php echo $term_passport_active_yes; ?></span>
										</label>
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="iow-ck-button">
										<label>
											<input type="radio" class="active" id="not_active" name="active" value="not_active" hidden="hidden">
											<span class="passport"><?php echo $term_passport_active_no; ?></span>
										</label>
									</div>
								</div>
								
								<div class="col-md-4 text-left text-md-right required"><b><?php echo $term_passport_name_on; ?></b></div>
								<div class="col-sm-6 col-md-4">
									<div class="iow-ck-button">
										<label>
											<input type="radio" class="name_style" id="type_full_name" name="name_style" value="full" hidden="hidden">
											<span class="passport"><?php echo $term_passport_name_full_only; ?></span>
										</label>
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="iow-ck-button">
										<label>
											<input type="radio" class="name_style" id="type_separate_names" name="name_style" value="separate" hidden="hidden">
											<span class="passport"><?php echo $term_passport_name_sep; ?></span>
										</label>
									</div>
								</div>

							</div>
							
						</div>
					</div>
					
					
					<!-- passport information -->
					<div id="passport_information" class="card mb-4 not-showing">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-info-circle"></i> <?php echo $term_passport_info_heading; ?>
						</h4>
						
						<div class="card-body">
							
							<div id="passport_data_details" class="row align-items-center" >
								
								<div class="col-sm-2 col-lg-2 text-left text-sm-right required"><b><?php echo $term_passport_table_type; ?></b></div>
								<div class="col-sm-4 col-lg-2"><input class="form-control" type="text" id="type" name="type" value="<?php echo $passport['type']; ?>" maxlength="3" required></div>
								<div class="col-sm-2 col-lg-1 text-left text-sm-right required"><b><?php echo $term_passport_table_code; ?></b></div>
								<div class="col-sm-4 col-lg-2"><input class="form-control" type="text" id="code" name="code" value="<?php echo $passport['code']; ?>" maxlength="3" required></div>
								<div class="offset-sm-6 col-sm-2 offset-lg-0 col-lg-2 text-left text-sm-right required"><b><?php echo $term_passport_table_number; ?></b></div>
								<div class="col-sm-4 col-lg-3"><input class="form-control" type="text" id="passport_id" name="passport_id" value="<?php echo $passport['passport_id']; ?>" maxlength="12" required></div>
								
								<div class="info col-12">
									
								</div>
								
								<div class="full_name_tr col-12 pt-3">
									<div class="row">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_passport_table_full_name; ?></b></div>
										<div class="col-sm-10"><input class="form-control" type="text" id="full_name" name="full_name" value="<?php echo $passport['full_name']; ?>"></div>
									</div>
								</div>
								
								<div class="separate_names_tr col-sm-12 col-md-6 pt-3">
									<div class="row">
										<div class="col-sm-2 col-md-4 text-left text-sm-right required"><b><?php echo $term_passport_table_family_name; ?></b></div>
										<div class="col-sm-10 col-md-8"><input class="form-control" type="text" id="family_name" name="family_name" value="<?php echo $passport['family_name']; ?>"></div>
									</div>
								</div>
								
								<div class="separate_names_tr col-12 col-md-6 pt-3">
									<div class="row">
										<div class="col-sm-2 col-md-4 text-left text-sm-right required"><b><?php echo $term_passport_table_given_names; ?></b></div>
										<div class="col-sm-10 col-md-8"><input class="form-control" type="text" id="given_names" name="given_names" value="<?php echo $passport['given_names']; ?>"></div>
									</div>
								</div>

								<div class="col-12 pt-3">
									<div class="row align-items-center">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_passport_table_nationality; ?></b></div>
										<div class="col-sm-4"><input class="form-control" type="text" id="nationality" name="nationality" value="<?php echo $passport['nationality']; ?>" required></div>

										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_passport_table_sex; ?></b></div>
										<div class="col-sm-4 form-group">
											<select id="sex" name="sex" class="mdb-select md-form align-middle" required>
												<option value=""><?php echo $term_passport_table_select_please; ?></option>
												<option value="female" <?php if($passport['sex'] == 'female') echo 'selected="Selected"'; ?>><?php echo $term_passport_table_select_female; ?></option>
												<option value="male" <?php if($passport['sex'] == 'male') echo 'selected="Selected"'; ?>><?php echo $term_passport_table_select_male; ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_passport_table_dob; ?></b></div>
										<div class="col-sm-4"><input class="form-control calendar-dob" type="text" id="dob" name="dob" value="<?php echo $passport['dob']; ?>" required></div>

										<div class="col-sm-2 text-left text-sm-right align-middle required"><b><?php echo $term_passport_table_pob; ?></b></div>
										<div class="col-sm-4"><input class="form-control" type="text" id="pob" name="pob" value="<?php echo $passport['pob']; ?>" required></div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_passport_table_from_date; ?></b></div>
										<div class="col-sm-4"><input class="form-control calendar-doi" type="text" id="from_date" name="from_date" value="<?php echo $passport['from_date']; ?>" ></div>

										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_passport_table_to_date; ?></b></div>
										<div class="col-sm-4"><input class="form-control calendar-exp" type="text" id="to_date" name="to_date" value="<?php echo $passport['to_date']; ?>"></div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_passport_table_place_issued; ?></b></div>
										<div class="col-sm-4"><input class="form-control" type="text" id="place_issued" name="place_issued" value="<?php echo $passport['place_issued']; ?>" required></div>
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_passport_table_authority; ?></b></div>
										<div class="col-sm-4"><input class="form-control" type="text" id="authority" name="authority" value="<?php echo $passport['authority']; ?>" required></div>
									</div>
								</div>
								
							</div>
								
						</div>
					</div>
					
					<!-- passport photo -->
					<div id="passport_image"  class="card col-sm-12 col-md-12 m-0 p-0 ">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-image"></i> <?php echo $term_passport_image_heading; ?>
						</h4>
						
						<div class="card-body">
							<div class="container">							
	<?php
							$class = 'not-showing';
							if(!empty($passport['filename'])) {
								$class = '';
	?>										
								<!-- passport image if any -->
								<div>
									<input type="hidden" id="passport_current" name="passport_current" value="<?php echo $passport['filename']; ?>">
								</div>
								
								<!-- end of passport photo -->	
	<?php
							}
	?>
							<div class="text-center <?php echo $class;?>" id="d_curr_img">
								<img id="curr_img" class="img-fluid" src="/ab/show/<?php echo $passport['filename']; ?>" alt="Current Passport Image" >
								<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								<hr>
							</div>		

								<div class="form-group">
									<label for="passport_input" class="required"><?php echo $term_passport_image_choose_file; ?></label>
									<input type="file" class="col-12" id="passport_input" accept=".jpg,.png,.gif" <?php echo (empty($passport['filename'])) ? 'required' : '' ?>>
									<input type="hidden" id="passport_base64" name="passport_base64">
								</div>
								
								<div id="passport_croppie_wrap" class="mw-100 w-auto mh-100 h-auto  text-center">
									<div id="passport_croppie"  data-banner-width="500" data-banner-height="700"></div>
								</div>
												
								<button class="btn btn-default btn-block not-showing" type="button" id="passport_result"><?php echo $term_passport_image_crop; ?></button>
							</div>
						</div>
						
					</div>
						
					
				</div>

				<div class="card-footer">
					<div class="row flex-column-reverse flex-lg-row">
						<div class="col-lg-6 left">
							<a id="go_back" href="<?php echo $back_url ?>" class="btn btn-md btn-warning font-weight-bold btn-sm-mobile-100" role="button"><i class="fas fa-arrow-circle-left"></i> <?php echo $term_go_back; ?></a>
						</div>
						<div class="col-lg-6 right">
							<button type="submit" name="next" value="home" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_passport; ?></button>
							<button type="submit" name="next" value="again" class="btn btn-md btn-primary font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_passport_add; ?></button>
						</div>
					</div>
					
				</div>
				
			</form>
			
		</div>
	</div>
</div>

<!-- Modal show passport sample-->
<div class="modal fade" id="passport_example" tabindex="-1" role="dialog" aria-labelledby="passport_example"
  aria-hidden="true">
  <div class="modal-dialog modal-sm modal-notify modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title white-text" id="passport_example">Sample Passport Foto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	      <div>
				<img src="/core/images/sample_passport.jpg" class="img img-fluid"></div>
			</div>
		    <div class="form-check">
				<input type="checkbox" class="form-check-input" id="dont_show" name="dont_show" value="1">
				<label class="form-check-label" for="dont_show">Don't show again!</label>
			</div>
			<div class="text-center pt-3 pb-3 pl-2 pr-2">
			<button id="understand_cookie" type="button" class="btn btn-success btn-block" data-dismiss="modal">Yes, I'm Understand</button>
			</div>
      </div>

    </div>
  </div>
</div>
