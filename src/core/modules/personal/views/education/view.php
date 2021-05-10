<?php
if(isset($errors) && is_array($errors))
{
?>
	<div class="iow-callout iow-callout-warning">
		<h2 class="text-warning"><?php echo $term_error_legend; ?></h2>
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
			    <?php echo $term_education_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
		    </h3>
			
			<form method="post" id="education">
				
				<div class="card-body">
					<!-- employer info -->
					<div class="card mb-4">
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="far fa-question-circle"></i> <?php echo $term_education_type_heading ?>
						</h4>
						
						<div class="card-body">
							
							<div class="row">
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-3 text-left text-sm-right required"><b><?php echo $term_education_institution_name; ?></b></div>
										<div class="col-sm-9">
											<input class="form-control" type="text" id="institution" name="institution" value="<?php echo $education['institution']; ?>" required >
											<input type="hidden" name="education_id" value="<?php echo $education['education_id']; ?>">
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-3 text-left text-sm-right"><b><?php echo $term_education_institution_email; ?></b></div>
										<div class="col-sm-9">
											<input class="form-control" type="email" id="email" name="email" value="<?php echo $education['email']; ?>">
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-3 text-left text-sm-right"><b><?php echo $term_education_institution_website; ?></b></div>
										<div class="col-sm-9">
											<input class="form-control" type="url" id="website" name="website" value="<?php echo $education['website']; ?>" placeholder="http://">
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-3 text-left text-sm-right"><b><?php echo $term_education_institution_phone; ?></b></div>
										<div class="col-sm-9">
											<input class="form-control" type="tel" id="phone" name="phone" value="<?php echo $education['phone']; ?>">
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row align-items-center">
										<div class="col-sm-3 text-left text-sm-right required"><b><?php echo $term_education_institution_country; ?></b></div>
										<div class="form-group col-sm-9">
											<select id="countryCode_id" name="countryCode_id" class="mdb-select md-form" searchable="Search" required>
												<option value=""><?php echo $term_education_table_select_please; ?></option>
<?php
												foreach($countryCodes as $id => $country)
												{
?>
													<option value="<?php echo $id; ?>" <?php if($education['countryCode_id'] == $id) echo 'selected="Selected"'; ?>><?php echo $country; ?></option>
<?php
												}
?>
											</select>
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-3 text-left text-sm-right required">
											<b><?php echo $term_education_english; ?></b>
											<input type="hidden" id="english_current" name="english_current" value="<?php echo $education['english']; ?>">
										</div>
										<div class="col-sm-9 ">
											<div class="row">
												<div class="iow-ck-button col-sm-6">
													<label>
														<input type="radio" class="english" id="english" name="english" value="yes" hidden="hidden">
														<span class="education"><?php echo $term_education_english_yes; ?></span>
													</label>
												</div>
												<div class="iow-ck-button col-sm-6">
													<label>
														<input type="radio" class="english" id="not_english" name="english" value="no" hidden="hidden">
														<span class="education"><?php echo $term_education_english_no; ?></span>
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-3 text-left text-sm-right required">
											<b><?php echo $term_education_active; ?></b>
											<input type="hidden" id="active_current" name="active_current" value="<?php echo $education['active']; ?>">
										</div>
										<div class="col-sm-9">
											<div class="row">
												<div class="iow-ck-button col-sm-6">
													<label>
														<input type="radio" class="active" id="active" name="active" value="active" hidden="hidden">
														<span class="education"><?php echo $term_education_active_yes; ?></span>
													</label>
												</div>
												<div class="iow-ck-button col-sm-6">
													<label>
														<input type="radio" class="active" id="not_active" name="active" value="not_active" hidden="hidden">
														<span class="education"><?php echo $term_education_active_no; ?></span>
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								
							</div>
							
						</div>
					</div>
					
					<!-- employment information -->
					<div id="education_information" class="card mb-4 not-showing">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-info-circle"></i> <?php echo $term_education_info_heading ?>
						</h4>
						<div class="card-body">

							<div class="row" id="education_data_details" >
								
								<div class="col-12 col-md-12">
									<div class="row">
										<div class="col-sm-3 col-md-3 pt-3 text-left text-sm-right required"><b><?php echo $term_education_table_from_date; ?></b></div>
										<div class="col-sm-9 col-md-3 pt-3"><input class="form-control calendar-dos" type="text" id="from_date" name="from_date" value="<?php echo $education['view_from']; ?>" ></div>
										<div class="col-sm-3 col-md-3 pt-3 text-left text-sm-right"><b><?php echo $term_education_table_to_date; ?></b></div>
										<div class="col-sm-9 col-md-3 pt-3">
											<div id="active_job" class="not-showing">
												<em><?php echo $term_education_table_to_current; ?></em>
											</div>
											<div id="not_active_job" class="not-showing">
												<input class="form-control calendar-dof" type="text" id="to_date" name="to_date" value="<?php echo $education['view_to']; ?>">
											</div>
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row align-items-center">
										<div class="col-sm-3 text-left text-sm-right required"><b><?php echo $term_education_table_level.' '.$education['level']; ?></b></div>
										
										<div class="form-group col-sm-9">
											<select id="level" name="level" class="mdb-select md-form" required>
												<option value=""><?php echo $term_education_table_select_please; ?></option>
												<option value="school" <?php if($education['level'] == 'school') echo 'selected="Selected"'; ?>><?php echo $term_education_table_level_school; ?></option>
												<option value="certificate" <?php if($education['level'] == 'certificate') echo 'selected="Selected"'; ?>><?php echo $term_education_table_level_certificate; ?></option>
												<option value="stcw" <?php if($education['level'] == 'stcw') echo 'selected="Selected"'; ?>><?php echo $term_education_table_level_stcw; ?></option>
												<option value="diploma" <?php if($education['level'] == 'diploma') echo 'selected="Selected"'; ?>><?php echo $term_education_table_level_diploma; ?></option>
												<option value="degree" <?php if($education['level'] == 'degree') echo 'selected="Selected"'; ?>><?php echo $term_education_table_level_degree; ?></option>
												<option value="honours" <?php if($education['level'] == 'honours') echo 'selected="Selected"'; ?>><?php echo $term_education_table_level_honours; ?></option>
												<option value="masters" <?php if($education['level'] == 'masters') echo 'selected="Selected"'; ?>><?php echo $term_education_table_level_masters; ?></option>
												<option value="doctorate" <?php if($education['level'] == 'doctorate') echo 'selected="Selected"'; ?>><?php echo $term_education_table_level_doctorate; ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-12 stcw-type d-none">
									<div class="row align-items-center">
										<div class="col-sm-3 text-center text-sm-right required"><b>Select STCW type</b></div>
										<div class="col-sm-9">
											<select id="stcw_type" name="stcw_type" class="mdb-select md-form" required >
												<option value=""><?php echo $term_stcw_table_select_please; ?></option>
												<option value="bst" <?php if($education['stcw_type'] == 'bst') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_bst; ?></option>
												<option value="sat" <?php if($education['stcw_type'] == 'sat') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_sat; ?></option>
												<option value="cm" <?php if($education['stcw_type'] == 'cm') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_cm; ?></option>
												<option value="dsd" <?php if($education['stcw_type'] == 'dsd') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_dsd; ?></option>
												<option value="sfsat" <?php if($education['stcw_type'] == 'sfsat') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_sfsat; ?></option>
												<option value="bpst" <?php if($education['stcw_type'] == 'bpst') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_bpst; ?></option>
												<option value="ps" <?php if($education['stcw_type'] == 'ps') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_ps; ?></option>
												<option value="sr" <?php if($education['stcw_type'] == 'sr') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_sr; ?></option>
												<option value="efa" <?php if($education['stcw_type'] == 'efa') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_efa; ?></option>
												<option value="fp" <?php if($education['stcw_type'] == 'fp') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_fp; ?></option>
												<option value="ff" <?php if($education['stcw_type'] == 'ff') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_ff; ?></option>
												<option value="pst" <?php if($education['stcw_type'] == 'pst') echo 'selected="Selected"'; ?>><?php echo $term_stcw_table_select_pst; ?></option>
											</select>
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-3 text-left text-sm-right required"><b><?php echo $term_education_table_qualification; ?></b></div>
										<div class="col-sm-9">
											<input class="form-control" type="text" id="qualification" name="qualification" value="<?php echo $education['qualification']; ?>" required >
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-3 text-left text-sm-right required"><b><?php echo $term_education_table_description; ?></b></div>
										<div class="col-sm-9">
											<textarea class="form-control" rows="5" id="description" name="description" required><?php echo $education['description']; ?></textarea>
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row align-items-center">
										<div class="col-sm-3 text-left text-sm-right required"><b><?php echo $term_education_table_type; ?></b></div>
										<div class="form-group col-sm-9">
											<select id="type" name="type" class="mdb-select md-form" required>
												<option value=""><?php echo $term_education_table_select_please; ?></option>
												<option value="online" <?php if($education['type'] == 'online') echo 'selected="Selected"'; ?>><?php echo $term_education_table_type_online; ?></option>
												<option value="part_time" <?php if($education['type'] == 'part_time') echo 'selected="Selected"'; ?>><?php echo $term_education_table_type_part_time; ?></option>
												<option value="full_time" <?php if($education['type'] == 'full_time') echo 'selected="Selected"'; ?>><?php echo $term_education_table_type_full_time; ?></option>
											</select>
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row align-items-center">	
										<div class="col-sm-3 text-left text-sm-right required"><b><?php echo $term_education_table_attended_country; ?></b></div>
										<div class="form-group col-sm-9">
											<select id="attended_countryCode_id" name="attended_countryCode_id" class="mdb-select md-form" searchable="Search" required>
												<option value=""><?php echo $term_education_table_select_please; ?></option>
<?php
												foreach($countryCodes as $id => $country)
												{
?>	
													<option value="<?php echo $id; ?>" <?php if($education['attended_countryCode_id'] == $id) echo 'selected="Selected"'; ?>><?php echo $country; ?></option>
<?php
												}
?>
											</select>
										</div>
									</div>
								</div>
													
								<div class="col-lg-6 pt-3">
									<div class="row">
										<div id="certificate_number_placeholder" class="col-sm-4 col-md-6 text-left text-sm-right required"><b><?php echo $term_education_table_certificate_number; ?></b></div>
										<div class="col-sm-8 col-md-6">
											<input class="form-control" type="text" id="certificate_number" name="certificate_number" value="<?php echo $education['certificate_number']; ?>" required>
										</div>
									</div>
								</div>
								<div class="col-lg-6 pt-3">
									<div class="row">
										<div id="certificate_date_placeholder" class="col-sm-4 col-md-6 text-left text-sm-right required"><b><?php echo $term_education_table_certificate_date; ?></b></div>
										<div class="col-sm-8 col-md-6">
											<input class="form-control calendar-dos" type="text" id="certificate_date" name="certificate_date" value="<?php echo $education['certificate_from']; ?>" >
										</div>
									</div>

								</div>	
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-3 col-md-3 text-left text-sm-right"><b><?php echo $term_education_table_certificate_expire; ?></b></div>
										
										<div class="col-sm-9 col-md-3 form-check ">
											<input type="checkbox" class="form-check-input" id="certificate_expire" <?php echo empty($education['certificate_to']) ? '' : 'checked'; ?>>
											<label class="form-check-label" for="certificate_expire"><?php echo $term_eduction_table_certificate_expire_label; ?></label>
										</div>

										<div class="col-sm-3 col-md-3 text-right"><b><?php echo $term_education_table_certificate_expiry; ?></b></div>
										<div class="col-sm-9 col-md-3 text-left text-sm-right">
											
											<div id="certificate_expire_no">
												<em><?php echo $term_education_table_to_certificate; ?></em>
											</div>
											<div id="certificate_expire_yes" class="not-showing">
												
												<input class="form-control calendar-exp" type="text" id="certificate_expiry" name="certificate_expiry" value="<?php echo $education['certificate_to']; ?>">
											</div>
										</div>
									</div>
								</div>

							
							</div>
								
						</div>
					</div>
					
					<!-- education image -->
					<div id="education_image" class="card">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-image"></i> <?php echo $term_education_image_heading ?>
						</h4>
						
						<div class="card-body">
							<div class="container">				
								<input type="hidden" id="education_base64" name="education_base64">
								
	<?php
							$class = 'not-showing';
							if(!empty($education['filename']))
								$class='';
							{
	?>										
								<!-- education image if any -->
								<div class=" text-center">
									<input type="hidden" id="education_current" name="education_current" value="<?php echo $education['filename'] ?>">
								</div>
								<!-- end of education photo -->	
	<?php
							}
	?>
							<div class="text-center <?php echo $class;?>" id="d_curr_img">
								<img id="curr_img" class="img-fluid" src="/ab/show/<?php echo $education['filename'] ?>" alt="Current Education Image" >
								<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								<hr>
							</div>		
								<ul class="nav nav-tabs md-pills pills-unique nav-fill" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="portrait-tab" data-toggle="tab" href="#portrait" role="tab" aria-controls="portrait" aria-selected="true">
											<i class="far fa-file-image"> </i> <?php echo $term_education_tab_portrait ?>
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="landscape-tab" data-toggle="tab" href="#landscape" role="tab" aria-controls="landscape" aria-selected="true">
											<i class="far fa-image"> </i> <?php echo $term_education_tab_landscape ?>
										</a>
									</li>
								</ul>
								<hr>

								<div class="tab-content">
									
									<div id="portrait" class="tab-pane fade show active">
										<div class="form-group">
											<label id="potrait_placeholder" for="education_input_portrait" class="required"><?php echo $term_education_image_choose_file; ?></label>
											<input type="file" class="col-12" id="education_input_portrait" accept=".jpg,.png,.gif" >
										</div>
										
										<div id="education_croppie_wrap_portrait" class="mw-100 w-auto mh-100 h-auto">
											<div id="education_croppie_portrait" data-banner-width="600" data-banner-height="800"></div>
										</div>
										
										<button class="btn btn-default btn-block not-showing" type="button" id="education_result_portrait"><?php echo $term_education_image_crop; ?></button>
			
									</div>

									<div id="landscape" class="tab-pane fade">
		
										<div class="form-group">
											<label id="landscape_placeholder" for="education_input_landscape" class="required"><?php echo $term_education_image_choose_file; ?></label>
											<input type="file" class="col-12" id="education_input_landscape" accept=".jpg,.png,.gif" >
										</div>
										
										<div id="education_croppie_wrap_landscape" class="mw-100 w-auto mh-100 h-auto">
											<div id="education_croppie_landscape" data-banner-width="800" data-banner-height="600"></div>
										</div>
										
										<button class="btn btn-default btn-block not-showing" type="button" id="education_result_landscape"><?php echo $term_education_image_crop; ?></button>
				
									</div>

								</div>
							</div>
						</div>
						<!-- End of education image -->
						
					</div>
				</div>
				
				<div class="card-footer">
					<div class="row flex-column-reverse flex-lg-row">
						<div class="col-lg-6 left">
							<a id="go_back" href="<?php echo $back_url ?>" class="btn btn-md btn-warning font-weight-bold btn-sm-mobile-100" role="button"><i class="fas fa-arrow-circle-left"></i> <?php echo $term_go_back; ?></a>
						</div>
						<div class="col-lg-6 right">
							<button type="submit" name="next" value="home" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_education; ?></button>
							<button type="submit" name="next" value="again" class="btn btn-md btn-primary font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_education_add; ?></button>
						</div>
					</div>
					
				</div>
				
			</form>
			
		</div>
	</div>
</div>
