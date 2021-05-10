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
			    <?php echo $term_vaccination_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
		    </h3>
			
			<form method="post" id="vaccination">
				<div class="card-body">
					<!-- medical info -->
					<div class="card mb-4" id="vaccination_general">						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="far fa-question-circle"></i> <?php echo $term_vaccination_type_heading ?>
						</h4>
						
						<div class="card-body">
							
							<div class="row">
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_vaccination_institution_name; ?></b></div>
										<div class="col-sm-8">
											<input class="form-control" type="text" id="institution" name="institution" value="<?php echo $vaccination['institution']; ?>" required>
											<input type="hidden" name="vaccination_id" value="<?php echo $vaccination['vaccination_id']; ?>">
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right"><b><?php echo $term_vaccination_institution_email; ?></b></div>
										<div class="col-sm-8">
											<input class="form-control" type="email" id="email" name="email" value="<?php echo $vaccination['email']; ?>">
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right"><b><?php echo $term_vaccination_institution_website; ?></b></div>
										<div class="col-sm-8">
											<input class="form-control" type="url" id="website" name="website" value="<?php echo $vaccination['website']; ?>" placeholder="http://">
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right"><b><?php echo $term_vaccination_institution_phone; ?></b></div>
										<div class="col-sm-8">
											<input class="form-control" type="tel" id="phone" name="phone" value="<?php echo $vaccination['phone']; ?>">
										</div>
									</div>
								</div>
								
								<div class="col-12 align-items-center pt-3">
									<div class="row align-items-center">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_vaccination_institution_country; ?></b></div>
										
										<div class="form-group col-sm-8">
											<select id="countryCode_id" name="countryCode_id" class="mdb-select md-form" searchable="Search" required>
												<option value=""><?php echo $term_vaccination_table_select_please; ?></option>
<?php
												foreach($countryCodes as $id => $country)
												{
?>											
													
													<option value="<?php echo $id; ?>" <?php if($vaccination['countryCode_id'] == $id) echo 'selected="Selected"'; ?>><?php echo $country; ?></option>
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
					
					<!-- vaccination data -->
					<div class="card mb-4" id="vaccination_data">
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-info-circle"></i> <?php echo $term_vaccination_info_heading ?>
						</h4>
						<div class="card-body">
									
							<div id="vaccination_data_details" class="row" >
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_vaccination_table_doctor; ?></b></div>
										<div class="col-sm-8"><input class="form-control" type="text" id="doctor" name="doctor" value="<?php echo $vaccination['doctor']; ?>" required></div>
									</div>
								</div>
									
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_vaccination_table_type; ?></b></div>
										<div class="col-sm-8">
											<select name="type" id="type" class="form-control">
												<?php foreach($vaccine_type as $key => $value): ?>
													<option value="<?php echo $key ?>" <?php echo ($vaccination['type'] === $key) ? 'selected':'' ?>><?php echo $value ?></option>
												<?php endforeach ?>
											</select>
										</div>
									</div>
								</div>
																							
								<div class="col-12 col-md-6 pt-3">
									<div class="row">
										<div class="col-sm-4 col-md-6 text-left text-sm-right required"><b><?php echo $term_vaccination_table_vaccination_number; ?></b></div>
										<div class="col-sm-8 col-md-6"><input class="form-control" type="text" id="vaccination_number" name="vaccination_number" value="<?php echo $vaccination['vaccination_number']; ?>" required></div>
									</div>
								</div>

								<div class="col-12 col-md-6 pt-3">
									<div class="row">
										<div class="col-sm-4 col-md-6 text-left text-sm-right required"><b><?php echo $term_vaccination_table_vaccination_date; ?></b></div>
										<div class="col-sm-8 col-md-6 "><input class="form-control calendar-dos" type="text" id="vaccination_date" name="vaccination_date" value="<?php echo $vaccination['vaccination_from']; ?>" ></div>
									</div>
								</div>
									
								<div class="col-12 col-md-6 pt-3">
									<div class="row">
										<div class="col-sm-4 col-md-6 text-left text-sm-right"><b><?php echo $term_vaccination_table_vaccination_expire; ?></b></div>
										<div class="form-check col-sm-8 col-md-6">
											<input type="checkbox" class="form-check-input" id="vaccination_expire" <?php echo empty($vaccination['vaccination_to']) ? '' : 'checked'; ?>>
											<label class="form-check-label" for="vaccination_expire"><?php echo $term_vaccination_table_certificate_expire_label; ?></label>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-6 pt-3">
									<div class="row">
										<div class="col-sm-4 col-md-6 text-left text-sm-right"><b><?php echo $term_vaccination_table_vaccination_expiry; ?></b></div>
										<div class="col-sm-8 col-md-6">
											<div id="vaccination_expire_no"><em><?php echo $term_vaccination_table_to_vaccination; ?></em></div>
											<div id="vaccination_expire_yes" class="not-showing">
												<input class="form-control calendar-exp" type="text" id="vaccination_expiry" name="vaccination_expiry" value="<?php echo $vaccination['vaccination_to']; ?>">
											</div>
										</div>
									</div>
								</div>
									
												
							</div>
							
						</div>
					</div>
					
					<!-- vaccination image -->
					<div id="vaccination_image"  class="card ">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-image"></i> <?php echo $term_vaccination_image_heading ?>
						</h4>
						
						<div class="card-body">
							
							<div class="container">	
								<input type="hidden" id="vaccination_base64" name="vaccination_base64">					
	<?php
							$class = "not-showing";
							if(!empty($vaccination['filename'])) 
							{
								$class = "";
	?>										
								<!-- vaccination image if any -->
								<div class="text-center">
									<input type="hidden" id="vaccination_current" name="vaccination_current" value="<?php echo $vaccination['filename'] ?>">
								</div>
								<!-- end of vaccination photo -->	
	<?php
							}
	?>
								<div class="text-center <?php echo $class;?>" id="d_curr_img">
									<img id="curr_img" class="img-fluid" src="/ab/show/<?php echo $vaccination['filename'] ?>" alt="Current Vaccination Image" >
									<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
									<hr>
								</div>

								<ul class="nav nav-tabs md-pills pills-unique nav-fill" role="tablist">
									<li class="nav-item">
										<a  class="nav-link active" id="portrait-tab" data-toggle="tab" href="#portrait" role="tab" aria-controls="portrait" aria-selected="true">
											<i class="far fa-file-image"> </i> <?php echo $term_vaccination_tab_portrait ?>
										</a>
									</li>
									<li class="nav-item">
										<a  class="nav-link" id="landscape-tab" data-toggle="tab" href="#landscape" role="tab" aria-controls="landscape" aria-selected="true">
											<i class="far fa-image"> </i> <?php echo $term_vaccination_tab_landscape ?>
										</a>
									</li>
									
								</ul>
								
								<hr>

								<div class="tab-content">
									
									<div id="portrait" class="tab-pane fade show active">
							
										<div class="form-group">
											<label for="vaccination_input_portrait" class="required"><?php echo $term_vaccination_image_choose_file; ?></label>
											<input type="file" class="col-12" id="vaccination_input_portrait" accept=".jpg,.png,.gif" >
										</div>
										
										<div id="vaccination_croppie_wrap_portrait" class="mw-100 w-auto mh-100 h-auto">
											<div id="vaccination_croppie_portrait" data-banner-width="600" data-banner-height="800"></div>
										</div>
										
										<button class="btn btn-default btn-block not-showing" type="button" id="vaccination_result_portrait"><?php echo $term_vaccination_image_crop; ?></button>
			
									</div>

									<div id="landscape" class="tab-pane fade">
		
										<div class="form-group">
											<label for="vaccination_input_landscape" class="required"><?php echo $term_vaccination_image_choose_file; ?></label>
											<input type="file" class="col-12" id="vaccination_input_landscape" accept=".jpg,.png,.gif" >
										</div>
										
										<div id="vaccination_croppie_wrap_landscape" class="mw-100 w-auto mh-100 h-auto">>
											<div id="vaccination_croppie_landscape" data-banner-width="800" data-banner-height="600"></div>
										</div>
										
										<button class="btn btn-default btn-block not-showing" type="button" id="vaccination_result_landscape"><?php echo $term_vaccination_image_crop; ?></button>
				
									</div>

								</div>
							</div>
						</div>
					</div>
					<!-- End of vaccination image -->
										
				</div>
				
				<div class="card-footer right">
					<div class="row flex-column-reverse flex-lg-row">
						<div class="col-lg-6 left">
							<a id="go_back" href="<?php echo $back_url ?>" class="btn btn-md btn-warning font-weight-bold btn-sm-mobile-100" role="button"><i class="fas fa-arrow-circle-left"></i> <?php echo $term_go_back; ?></a>
						</div>
						<div class="col-lg-6 right">
							<button type="submit" name="next" value="home" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_vaccination; ?></button>
							<button type="submit" name="next" value="again" class="btn btn-md btn-primary font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_vaccination_add; ?></button>
						</div>
					</div>
				</div>
				
			</form>
			
		</div>
	</div>
</div>