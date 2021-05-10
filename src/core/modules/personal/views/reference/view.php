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

		$relationships = [];

		if ($reference['type'] === 'work') {
			$relationships = [
				'Colleague',
				'Senior Colleague',
				'Supervisor',
				'Team Leader',
				'Head of Department',
				'Assistant Manager',
				'Manager',
				'HR Personnel',
				'CEO/CFO/COO',
				'Owner'
			];
		} else {
			$relationships = [
				'Class Monitor/Vice Monitor',
				'Teacher',
				'Lecturer',
				'Professor',
				'Form Teacher/Teacher-in-charge',
				'Dean/Vice Dean',
				'Principal',
				'Supervisor',
				'Family Business',
				'Self-owner'
			];
		}
?>	

<div class="row">
	<div class="col-12" >
		<!-- Main Card -->
		<div class="card mb-4">			
		    <h3 class="card-header blue-gradient white-text text-center py-4">
			    <?php echo $reference['type'] == 'work' ? $term_reference_panel_title_work : $term_reference_panel_title_personal; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
		    </h3>
			
			<form method="post" id="reference">
				
				<div class="card-body">					
					<!-- main reference -->
					<div id="main_passport_data" class="card mb-4">
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-pencil-alt"></i> <?php echo $term_reference_heading; ?>
						</h4>
						
						<div class="card-body">
							<div class="row">
								
								<!-- family name -->
								<div class="col-12 pt-3">

									<div class="row mb-3">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $reference['type'] == 'work' ? $term_reference_company_name : $term_reference_organisation_name; ?></b></div>
										<div class="col-sm-8 text-left text-sm-right">
											<input type="text" class="form-control" id="entity_name" name="entity_name" maxlength="255" value="<?php echo $reference['entity_name']; ?>" required>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right"><b><?php echo $term_reference_family_name; ?></b></div>
										<div class="col-sm-8">
											<input type="hidden" id="reference_id" name="reference_id" value="<?php echo $reference['reference_id']; ?>">
											<input type="text" class="form-control" id="family_name" name="family_name" maxlength="255" value="<?php echo $reference['family_name']; ?>">
											<?php if($reference['type'] == 'work') {?>
											<p class="text-warning">
												<strong>Important note: </strong> The Referee should be come from your direct supervisor from your previous Employer/workplace
											</p>
											<?php }else { ?>
												<p class="text-warning">
												<strong>Important note: </strong> This Reference should be fill if you don't have any employment history. The Referee should be come from your direct supervisor from your previous Organization/School/Course.  
											</p>

											<?php } ?>
										</div>
									</div>
								</div>
								
								<!-- given names -->
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_reference_given_names; ?></b></div>
										<div class="col-sm-8"><input type="text" class="form-control" id="given_names" name="given_names" maxlength="255" value="<?php echo $reference['given_names']; ?>" required></div>
									</div>
								</div>
								
								<!-- relationship --> 
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_reference_relationship; ?></b></div>
										<div class="col-sm-8">
											<select class="mdb-select md-form" id="relationship" name="relationship" required searchable="Search">
												<option value="">Select Relation</option>
												<?php foreach($relationships as $key => $relation): ?>
													<option value="<?php echo $relation ?>" <?php echo ($relation === $reference['relationship']) ? 'selected':'' ?>><?php echo $relation ?></option>
												<?php endforeach ?>
											</select>
											<label for="relationship">Select Relationship</label>
										</div>
									</div>
								</div>
								
								<!-- address --> 		
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right"><b><?php echo $term_reference_address; ?></b></div>
										<div class="col-sm-8">
											<input type="text" class="form-control mb-3" id="line_1" name="line_1" maxlength="255" value="<?php echo $reference['line_1']; ?>">
											<input type="text" class="form-control mb-3" id="line_2" name="line_2" maxlength="255" value="<?php echo $reference['line_2']; ?>">
											<input type="text" class="form-control mb-3" id="line_3" name="line_3" maxlength="255" value="<?php echo $reference['line_3']; ?>">
										</div>
									</div>
								</div>
								
								<!-- country -->
								<div class="col-12 align-items-center pt-3">
									<div class="row align-items-center">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_reference_country; ?></b></div>
										<div class="form-group col-sm-8">
											<select id="countryCode_id" name="countryCode_id" class="mdb-select md-form" searchable="Search" required>
												<option value=""><?php echo $term_reference_table_select_please; ?></option>
	<?php
												foreach($countryCodes as $id => $country)
												{
	?>											
													
													<option value="<?php echo $id; ?>" <?php if($reference['countryCode_id'] == $id) echo 'selected="Selected"'; ?>><?php echo $country; ?></option>
	<?php
												}
	?>
											</select>
										</div>
									</div>
								</div>

								<!-- telephone cc-->
								<div class="form-group col-md-12 align-items-center pb-2">                                    
									<div class="row align-items-center">
										<div class="text-left text-sm-right col-sm-12 col-md-4 col-lg-4 required"><b><?php echo $term_reference_phone_label; ?></b></div>
									
										<div class="col-sm-12 col-md-8 col-lg-8">
											<div class="row">

												<div class="col-3 col-sm-3 col-md-6 col-lg-4">
													<!-- numbertype -->
													<div class="md-form  ">
														<select id="number_type" name="number_type" class="mdb-select" required>
															<option value=""><?php echo $term_reference_table_select_please; ?></option>
															<option value="mobile" <?php if($reference['number_type'] == 'mobile') echo 'selected="Selected"'; ?>><?php echo $term_reference_phone_mobile; ?></option>
															<option value="landline" <?php if($reference['number_type'] == 'landline') echo 'selected="Selected"'; ?>><?php echo $term_reference_phone_landline; ?></option>
														</select>
														<label for="number_type"><?php echo $term_reference_type?></label>
													</div>
												</div>

												<div class="col-9 col-sm-9 col-md-6 col-lg-4 ">
													<div class="md-form input-group ">
														<div class="input-group-prepend">
															<span class="input-group-text md-addon" id="countrydial_prefixs" data-dial='<?php echo json_encode($countryDialCodes)?>'></span>
														</div>
														<input type="tel" class="form-control" id="number" name="number" maxlength="25" value="<?php echo $reference['number']; ?>" required>

														<label for="number"><b><?php echo $term_reference_phone_number ?></b></label>
													</div>
												</div>											
											</div>
										</div>
									</div>
								</div>

								<!-- email -->
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_reference_email; ?></b></div>
										<div class="col-sm-8"><input type="email" class="form-control" id="email" name="email" maxlength="255" value="<?php echo $reference['email']; ?>" required></div>
									</div>
								</div>
								
								<!-- skype -->
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right"><b><?php echo $term_reference_skype; ?></b></div>
										<div class="col-sm-8"><input type="text" class="form-control" id="skype" name="skype" maxlength="255" value="<?php echo $reference['skype']; ?>"></div>
									</div>
								</div>
								
								<!-- comment -->
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4 text-left text-sm-right"><b><?php echo $term_reference_comment; ?></b></div>
										<div class="col-sm-8">
											<textarea class="form-control" rows="5" id="comment" name="comment"><?php echo $reference['comment']; ?></textarea>
										</div>
									</div>
								</div>					
							</div>

						</div>
					</div>
					
								
					<!-- start reference image -->
					<div id="reference_image" class="card ">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-image"></i> <?php echo $term_reference_image_heading ?>
						</h4>
						
						<div class="card-body">

							<div class="container">	
								<input type="hidden" id="reference_base64" name="reference_base64">							
	<?php
							$class = 'not-showing';
							if(!empty($reference['filename'])) 
							{
								$class = '';
	?>										
								<!-- reference image if any -->
								<div class="text-center">
									<input type="hidden" id="reference_current" name="reference_current" value="<?php echo $reference['filename'] ?>">
								</div>
	<?php
							}
	?>
							<div class="text-center <?php echo $class;?>" id="d_curr_img">
								<img id="curr_img" class="img-fluid" src="/ab/show/<?php echo $reference['filename'] ?>" alt="Current Reference Image" >
								<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								<hr>
							</div>		
								<ul class="nav nav-tabs md-pills pills-unique nav-fill" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="portrait-tab" data-toggle="tab" href="#portrait" role="tab" aria-controls="portrait" aria-selected="true">
											<i class="far fa-file-image"> </i> <?php echo $term_reference_tab_portrait ?>
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="landscape-tab" data-toggle="tab" href="#landscape" role="tab" aria-controls="landscape" aria-selected="true">
											<i class="far fa-image"></i> <?php echo $term_reference_tab_landscape ?>
										</a>
									</li>
								</ul>
								<hr>

								<div class="tab-content">
									
									<div id="portrait" class="tab-pane fade show active">
							
										<div class="form-group">
											<label for="reference_input_portrait"><?php echo $term_reference_image_choose_file; ?></label>
											<input type="file" class="col-12" id="reference_input_portrait" accept=".jpg,.png,.gif" >
										</div>
										
										<div id="reference_croppie_wrap_portrait" class="mw-100 w-auto mh-100 h-auto">
											<div id="reference_croppie_portrait" data-banner-width="600" data-banner-height="800"></div>
										</div>
										
										<button class="btn btn-default btn-block not-showing" type="button" id="reference_result_portrait"><?php echo $term_reference_image_crop; ?></button>
			
									</div>

									<div id="landscape" class="tab-pane fade">
		
										<div class="form-group">
											<label for="reference_input_landscape"><?php echo $term_reference_image_choose_file; ?></label>
											<input type="file" class="col-12" id="reference_input_landscape" accept=".jpg,.png,.gif" >
										</div>
										
										<div id="reference_croppie_wrap_landscape" class="mw-100 w-auto mh-100 h-auto">
											<div id="reference_croppie_landscape" data-banner-width="800" data-banner-height="600"></div>
										</div>
										
										<button class="btn btn-default btn-block not-showing" type="button" id="reference_result_landscape"><?php echo $term_reference_image_crop; ?></button>
				
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
							<button type="submit" name="next" value="home" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_reference; ?></button>
							<button type="submit" name="next" value="again" class="btn btn-md btn-primary font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_reference_add; ?></button>
						</div>
					</div>
					
				</div>
				
				
			</form>
			
		</div>
	</div>
</div>
