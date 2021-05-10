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
			    <?php echo $term_employment_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
		    </h3>
		    			
			<form method="post" id="employment">
				
				<div class="card-body">
					<!-- employer info -->
					<div class="card mb-4">
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="far fa-question-circle"></i> <?php echo $term_employment_type_heading ?>
						</h4>
						
						<div class="card-body">
							
							<div class="row">
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_employment_employer_name; ?></b></div>
										<div class="col-sm-8">
											<input class="form-control" type="text" id="employer" name="employer" value="<?php echo $employment['employer']; ?>" required>
											<input type="hidden" name="employment_id" value="<?php echo $employment['employment_id']; ?>">
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right"><b><?php echo $term_employment_employer_email; ?></b></div>
										<div class="col-sm-8">
											<input class="form-control" type="email" id="email" name="email" value="<?php echo $employment['email']; ?>">
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right"><b><?php echo $term_employment_employer_website; ?></b></div>
										<div class="col-sm-8">
											<input class="form-control" type="url" id="website" name="website" value="<?php echo $employment['website']; ?>" placeholder="http://">
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right"><b><?php echo $term_employment_employer_phone; ?></b></div>
										<div class="col-sm-8">
											<input class="form-control" type="tel" id="phone" name="phone" value="<?php echo $employment['phone']; ?>">
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row align-items-center">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_employment_employer_country; ?></b></div>
										<div class="form-group col-sm-8">
											<select id="countryCode_id" name="countryCode_id" class="mdb-select md-form" searchable="Search country" required>
												<option value=""><?php echo $term_employment_table_select_please; ?></option>
<?php
												foreach($countryCodes as $id => $country)
												{
?>											
													
													<option value="<?php echo $id; ?>" <?php if($employment['countryCode_id'] == $id) echo 'selected="Selected"'; ?>><?php echo $country; ?></option>
<?php
												}
?>
											</select>
										</div>
									</div>
								</div>
								
								<div class=" col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_employment_active; ?></b>
											<input type="hidden" id="active_current" name="active_current" value="<?php echo $employment['active']; ?>">
										</div>
										<div class="col-sm-4 iow-ck-button">
											<label>
												<input type="radio" class="active" id="active" name="active" value="active" hidden="hidden">
												<span class="employment"><?php echo $term_employment_active_yes; ?></span>
											</label>
										</div>
										<div class="col-sm-4 iow-ck-button">
											<label>
												<input type="radio" class="active" id="not_active" name="active" value="not_active" hidden="hidden">
												<span class="employment"><?php echo $term_employment_active_no; ?></span>
											</label>
										</div>
									</div>
								</div>
								
							</div>
							
						</div>
					</div>
					
					<!-- employment information -->
					<div id="employment_information" class="card mb-4 not-showing">
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-info-circle"></i> <?php echo $term_employment_info_heading ?>
						</h4>
						
						<div class="card-body">
									
							<div id="employment_data_details" class="row">
								
								<div class="col-12 pt-3">
									<div class="row align-items-center">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_employment_table_relevance; ?></b></div>
										<div class="form-group col-sm-8">
											<select id="job_category_id" name="job_speedy_category_id" class="mdb-select md-form" required searchable="Search">
												<option value=""><?php echo $term_employment_table_select_please; ?></option>
												<?php foreach ($job_categories as $category) { ?>
													<?php if($category['parent_id'] == 0) { ?>
														<option value="<?php echo $category['job_speedy_category_id']?>" <?php echo ($employment['job_speedy_category_id'] == $category['job_speedy_category_id'])? 'selected':'' ?> ><?php echo $category['name']?></option>
															<?php foreach ($job_categories as $category2) { ?>
																<?php if($category2['parent_id'] == $category['job_speedy_category_id']) { ?>
																	<option value="<?php echo $category2['job_speedy_category_id']?>" <?php echo ($employment['job_speedy_category_id'] == $category2['job_speedy_category_id'])? 'selected':'' ?> > &nbsp;&nbsp;&nbsp;<?php echo $category2['name']?></option>
																<?php } ?>
															<?php } ?>

													<?php } ?>
												<?php } ?>
                                                <option value="0">Other</option>
											</select>
										</div>
									</div>
								</div>

								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_employment_table_job_title; ?></b></div>
										<div class="col-sm-8"><input class="form-control" type="text" id="job_title" name="job_title" value="<?php echo $employment['job_title']; ?>" required></div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_employment_table_description; ?></b></div>
										<div class="col-sm-8">
											<textarea class="form-control" rows="5" id="description" name="description" required><?php echo $employment['description']; ?></textarea>
										</div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row align-items-center">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_employment_table_type; ?></b></div>
										<div class="form-group col-sm-8">
											<select id="type" name="type" class="mdb-select md-form" required>
												<option value=""><?php echo $term_employment_table_select_please; ?></option>
												<option value="casual" <?php if($employment['type'] == 'casual') echo 'selected="Selected"'; ?>><?php echo $term_employment_table_type_casual; ?></option>
												<option value="part_time" <?php if($employment['type'] == 'part_time') echo 'selected="Selected"'; ?>><?php echo $term_employment_table_type_part_time; ?></option>
												<option value="full_time" <?php if($employment['type'] == 'full_time') echo 'selected="Selected"'; ?>><?php echo $term_employment_table_type_full_time; ?></option>
											</select>
										</div>
									</div>
								</div>


								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_employment_table_from_date; ?></b></div>
										<div class="col-sm-8"><input class="form-control calendar-dos" type="text" id="from_date" name="from_date" value="<?php echo $employment['view_from']; ?>" ></div>
											
										<div class="col-12 pt-3">
											<div class="row">
												<div class="col-sm-4 text-left text-sm-right"><b><?php echo $term_employment_table_to_date; ?></b></div>
												<div class="col-sm-8">
													<div id="active_job" class="not-showing">
														<em><?php echo $term_employment_table_to_current; ?></em>
													</div>
													<div id="not_active_job" class="not-showing">
														<input class="form-control calendar-dof" type="text" id="to_date" name="to_date" value="<?php echo $employment['view_to']; ?>">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<!-- employment image -->
					<div id="employment_image" class="card">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-image"></i> <?php echo $term_employment_image_heading ?>
						</h4>
						
						<div class="card-body">
							<div class="container ">			
								<input type="hidden" id="employment_base64" name="employment_base64">
								
	<?php
							$class = 'not-showing';
							if(!empty($employment['filename'])) 
							{
								$class = '';
	?>										
								<!-- employment image if any -->
								<div class="text-center">
									<input type="hidden" id="employment_current" name="employment_current" value="<?php echo $employment['filename'] ?>">
								</div>

	<?php
							}
	?>
							<div class="text-center <?php echo $class;?>" id="d_curr_img">
								<img id="curr_img" class="img-fluid"  src="/ab/show/<?php echo $employment['filename'] ?>" alt="Current Employment Proof Image" >
								<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								<hr>
							</div>
									
								<ul class="nav nav-tabs md-pills pills-unique nav-fill" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="portrait-tab" data-toggle="tab" href="#portrait" role="tab" aria-controls="portrait" aria-selected="true">
											<i class="far fa-file-image"> </i> <?php echo $term_employment_tab_portrait ?>
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="landscape-tab" data-toggle="tab" href="#landscape" role="tab" aria-controls="landscape" aria-selected="true">
											<i class="far fa-image"> </i> <?php echo $term_employment_tab_landscape ?>
										</a>
									</li>
								</ul>
								
								<hr>
								
								<div class="tab-content">

									<div id="portrait" class="tab-pane fade show active">
									
										<div class="form-group">
											<label for="employment_input_portrait" class="required"><?php echo $term_employment_image_choose_file; ?></label>
											<input type="file" class="col-12" id="employment_input_portrait" accept=".jpg,.png,.gif" >
										</div>
										
										<div id="employment_croppie_wrap_portrait" class="mw-100 w-auto mh-100 h-auto">
											<div id="employment_croppie_portrait" data-banner-width="600" data-banner-height="800"></div>
										</div>
										
										<button class="btn btn-default btn-block not-showing" type="button" id="employment_result_portrait"><?php echo $term_employment_image_crop; ?></button>
			
									</div>
			
									<div id="landscape" class="tab-pane fade">
				
										<div class="form-group">
											<label for="employment_input_landscape"><?php echo $term_employment_image_choose_file; ?></label>
											<input type="file" class="col-12" id="employment_input_landscape" accept=".jpg,.png,.gif" >
										</div>
										
										<div id="employment_croppie_wrap_landscape" class="mw-100 w-auto mh-100 h-auto">
											<div id="employment_croppie_landscape" data-banner-width="800" data-banner-height="600"></div>
										</div>
										
										<button class="btn btn-default btn-block not-showing" type="button" id="employment_result_landscape"><?php echo $term_employment_image_crop; ?></button>
				
									</div>
			
								</div>
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
							<button type="submit" name="next" value="home" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_employment; ?></button>
							<button type="submit" name="next" value="again" class="btn btn-md btn-primary font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_employment_add; ?></button>
						</div>
					</div>	
					
				</div>
				
			</form>
			
		</div>
	</div>
</div>
