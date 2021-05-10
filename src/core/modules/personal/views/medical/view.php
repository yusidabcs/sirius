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
			    <?php echo $term_medical_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
		    </h3>
			
			<form method="post" id="medical">
				
				<div class="card-body">	
					<!-- medical info -->
					<div class="card mb-4">
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="far fa-question-circle"></i> <?php echo $term_medical_type_heading ?>
						</h4>
						
						<div class="card-body">

							<div class="row">
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_medical_institution_name; ?></b></div>
										<div class="col-sm-8">
											<input class="form-control" type="text" id="institution" name="institution" value="<?php echo $medical['institution']; ?>" required>
											<input type="hidden" name="medical_id" value="<?php echo $medical['medical_id']; ?>">
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right"><b><?php echo $term_medical_institution_email; ?></b></div>
										<div class="col-sm-8">
											<input class="form-control" type="email" id="email" name="email" value="<?php echo $medical['email']; ?>">
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right"><b><?php echo $term_medical_institution_website; ?></b></div>
										<div class="col-sm-8">
											<input class="form-control" type="url" id="website" name="website" value="<?php echo $medical['website']; ?>" placeholder="http://">
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right"><b><?php echo $term_medical_institution_phone; ?></b></div>
										<div class="col-sm-8">
											<input class="form-control" type="tel" id="phone" name="phone" value="<?php echo $medical['phone']; ?>">
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row align-items-center">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_medical_institution_country; ?></b></div>
										
										<div class="form-group col-sm-8">
											<select id="countryCode_id" name="countryCode_id" class="mdb-select md-form" searchable="Search country" required>
												<option value=""><?php echo $term_medical_table_select_please; ?></option>
<?php
												foreach($countryCodes as $id => $country)
												{
?>											
													
													<option value="<?php echo $id; ?>" <?php if($medical['countryCode_id'] == $id) echo 'selected="Selected"'; ?>><?php echo $country; ?></option>
<?php
												}
?>
											</select>
										</div>
									</div>
								</div>
																
							</div>
							
						</div>
					</div>
					
					<!-- medical data -->
					<div class="card mb-4" id="medical_data">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-info-circle"></i> <?php echo $term_medical_info_heading ?>
						</h4>
						<div class="card-body">

							<div id="medical_result" class="row" >
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right">
											<b><?php echo $term_medical_fit; ?></b>
											<input type="hidden" id="fit_current" name="fit_current" value="<?php echo $medical['fit']; ?>">
										</div>
										<div class="iow-ck-button col-sm-4">
											<label>
												<input type="radio" class="fit" id="fit" name="fit" value="fit" hidden="hidden">
												<span class="medical"><?php echo $term_medical_fit_fit; ?></span>
											</label>
										</div>
										<div class="iow-ck-button col-sm-4">
											<label>
												<input type="radio" class="fit" id="not_fit" name="fit" value="not_fit" hidden="hidden">
												<span class="medical"><?php echo $term_medical_not_fit; ?></span>
											</label>
										</div>
									</div>
								</div>
							
								<div class="col-12 pt-3 text-center"><b><?php echo $term_medical_certifcate_details; ?></b></div>

								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right required">
											<b><?php echo $term_medical_table_doctor; ?></b>
										</div>
										<div class="col-sm-8">
											<input class="form-control" type="text" id="doctor" name="doctor" value="<?php echo $medical['doctor']; ?>" required>
										</div>
									</div>
								</div>
									
								<div class="col-12 pt-3">
									<div class="row align-items-center">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_medical_table_type; ?></b></div>
										<div class="col-sm-8">
												<div class="form-group">
													<select id="type" name="type" class="mdb-select md-form" required>
														<option value=""><?php echo $term_medical_table_select_please; ?></option>
														<option value="rcl" <?php if($medical['type'] == 'rcl') echo 'selected="Selected"'; ?>><?php echo $term_medical_table_type_rcl; ?></option>
														<option value="ccl" <?php if($medical['type'] == 'ccl') echo 'selected="Selected"'; ?>><?php echo $term_medical_table_type_ccl; ?></option>
														<option value="eng1" <?php if($medical['type'] == 'eng1') echo 'selected="Selected"'; ?>><?php echo $term_medical_table_type_eng1; ?></option>
														<option value="norwegian" <?php if($medical['type'] == 'norwegian') echo 'selected="Selected"'; ?>><?php echo $term_medical_table_type_norwegian; ?></option>
														<option value="other" <?php if($medical['type'] == 'other') echo 'selected="Selected"'; ?>><?php echo $term_medical_table_type_other; ?></option>
													</select>
												</div>
										</div>
									</div>
								</div>			

								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 col-md-3 text-left text-sm-right required
										"><b><?php echo $term_medical_table_certificate_number; ?></b></div>
										<div class="col-sm-8 col-md-3 "><input class="form-control" type="text" id="certificate_number" name="certificate_number" value="<?php echo $medical['certificate_number']; ?>" required></div>
												
										<div class="col-sm-4 col-md-3 pt-3 pt-md-0 text-left text-sm-right required"><b><?php echo $term_medical_table_certificate_date; ?></b></div>
										<div class="col-sm-8 col-md-3 pt-3 pt-md-0"><input class="form-control calendar-dos" type="text" id="certificate_date" name="certificate_date" value="<?php echo $medical['certificate_from']; ?>" ></div>
									</div>
								</div>
									
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 col-md-3 text-left text-sm-right"><b><?php echo $term_medical_table_certificate_expire; ?></b></div>
										<div class="form-check col-sm-8 col-md-3">
											<input type="checkbox" class="form-check-input" id="certificate_expire" <?php echo empty($medical['certificate_to']) ? '' : 'checked'; ?>>
											<label class="form-check-label" for="certificate_expire"><?php echo $term_medical_table_certificate_expire_label; ?></label>
										</div>
												
										<div class="col-sm-4 col-md-3 text-left text-sm-right"><b><?php echo $term_medical_table_certificate_expiry; ?></b></div>
										<div class="col-sm-8 col-md-3">
											<div id="certificate_expire_no">
												<em><?php echo $term_medical_table_to_certificate; ?></em>
											</div>
											<div id="certificate_expire_yes" class="not-showing">
												<input class="form-control calendar-exp" type="text" id="certificate_expiry" name="certificate_expiry" value="<?php echo $medical['certificate_to']; ?>">
											</div>
										</div>
									</div>
								</div>
												
							</div>
							
						</div>
					</div>
					
					<!-- start medical image -->
					<div id="medical_image"  class="card ">
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-image"></i> <?php echo $term_medical_image_heading ?>
						</h4>
						<div class="card-body">
							<div class="container">	
								<input type="hidden" id="medical_base64" name="medical_base64">
	<?php
							$class="not-showing";
							if(!empty($medical['filename'])) 
							{
								$class='';
	?>										
								<!-- medical image if any -->
								<div class="text-center">
									<input type="hidden" id="medical_current" name="medical_current" value="<?php echo $medical['filename'] ?>">
								</div>
								<!-- end of medical photo -->	
	<?php
							}
	?>
							<div class="text-center <?php echo $class;?>" id="d_curr_img">
								<img id="curr_img" class="img-fluid" src="/ab/show/<?php echo $medical['filename'] ?>" alt="Current Medical Image" >
								<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								<hr>
							</div>		
								<ul class="nav nav-tabs md-pills pills-unique nav-fill" role="tablist">
									<li class="nav-item">
										<a  class="nav-link active" id="portrait-tab" data-toggle="tab" href="#portrait" role="tab" aria-controls="portrait" aria-selected="true">
											<i class="far fa-file-image"> </i> <?php echo $term_medical_tab_portrait ?>
										</a>
									</li>
									<li class="nav-item">
										<a  class="nav-link" id="landscape-tab" data-toggle="tab" href="#landscape" role="tab" aria-controls="landscape" aria-selected="true">
											<i class="far fa-image"> </i> <?php echo $term_medical_tab_landscape ?>
										</a>
									</li>
								</ul>
								<hr>

								<div class="tab-content">
									
									<div id="portrait" class="tab-pane fade show active">
							
										<div class="form-group">
											<label for="medical_input_portrait" class="required"><?php echo $term_medical_image_choose_file; ?></label>
											<input type="file" class="col-12" id="medical_input_portrait" accept=".jpg,.png,.gif" >
										</div>
										
										<div id="medical_croppie_wrap_portrait" class="mw-100 w-auto mh-100 h-auto">
											<div id="medical_croppie_portrait" data-banner-width="600" data-banner-height="800"></div>
										</div>
										
										<button class="btn btn-default btn-block not-showing" type="button" id="medical_result_portrait"><?php echo $term_medical_image_crop; ?></button>
			
									</div>

									<div id="landscape" class="tab-pane fade">
		
										<div class="form-group">
											<label for="medical_input_landscape" class="required"><?php echo $term_medical_image_choose_file; ?></label>
											<input type="file" class="col-12" id="medical_input_landscape" accept=".jpg,.png,.gif" >
										</div>
										
										<div id="medical_croppie_wrap_landscape" class="mw-100 w-auto mh-100 h-auto">
											<div id="medical_croppie_landscape" data-banner-width="800" data-banner-height="600"></div>
										</div>
										
										<button class="btn btn-default btn-block not-showing" type="button" id="medical_result_landscape"><?php echo $term_medical_image_crop; ?></button>
				
									</div>

								</div>
							</div>
						</div>
					</div>
										
				</div>
				
				<div class="card-footer right">
					<div class="row flex-column-reverse flex-lg-row">
						<div class="col-lg-6 left">
							<a id="go_back" href="<?php echo $back_url ?>" class="btn btn-md btn-warning font-weight-bold btn-sm-mobile-100" role="button"><i class="fas fa-arrow-circle-left"></i> <?php echo $term_go_back; ?></a>
						</div>
						<div class="col-lg-6 right">
							<button type="submit" name="next" value="home" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_medical; ?></button>
							<button type="submit" name="next" value="again" class="btn btn-md btn-primary font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_medical_add; ?></button>
						</div>
					</div>
				</div>
				
			</form>
			
		</div>
	</div>
</div>
