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
			    <?php echo $term_idcheck_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
		    </h3>
			
			<form method="post" id="idcheck">
				
				<div class="card-body">
					<!-- main id info -->
					<div class="card mb-4">
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="far fa-question-circle"></i> <?php echo $term_idcheck_type_heading ?>
						</h4>
						<div class="card-body">
							<div class="row ">								
									<div class="col-sm-4 col-md-2 text-left text-sm-right required"><b><?php echo $term_idcheck_institution_name; ?></b></div>
									<div class="col-sm-8 col-md-10">
										<input class="form-control" type="text" id="institution" name="institution" value="<?php echo $idcheck['institution']; ?>" required >
										<input type="hidden" name="idcheck_id" value="<?php echo $idcheck['idcheck_id']; ?>">
									</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4  col-md-2 text-left text-sm-right required"><b><?php echo $term_idcheck_institution_email; ?></b></div>
										<div class="col-sm-8 col-md-10"><input class="form-control" type="email" id="email" name="email" value="<?php echo $idcheck['email']; ?>" required ></div>
									</div>
								</div>

								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4  col-md-2 text-left text-sm-right required"><b><?php echo $term_idcheck_institution_website; ?></b></div>
										<div class="col-sm-8 col-md-10"><input class="form-control" type="url" id="website" name="website" value="<?php echo $idcheck['website']; ?>" required  placeholder="http://"></div>
									</div>
								</div>
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-4  col-md-2 text-left text-sm-right required"><b><?php echo $term_idcheck_institution_phone; ?></b></div>
										<div class="col-sm-8 col-md-10"><input class="form-control" type="tel" id="phone" name="phone" value="<?php echo $idcheck['phone']; ?>" required ></div>
									</div>
								</div>
								<div class="col-12 pt-3">
									<div class="row align-items-center">
										<div class="col-sm-4  col-md-2 text-left text-sm-right required"><b><?php echo $term_idcheck_institution_country; ?></b></div>
										<div class="col-sm-8 col-md-10 form-group">
											<select id="countryCode_id" name="countryCode_id" class="mdb-select md-form" searchable="Search country" required>
												<option value=""><?php echo $term_idcheck_table_select_please; ?></option>
		<?php
												foreach($countryCodes as $id => $country)
												{
		?>											
													<option value="<?php echo $id; ?>" <?php if($idcheck['countryCode_id'] == $id) echo 'selected="Selected"'; ?>><?php echo $country; ?></option>
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

					<!-- id check information -->
					<div class="card mb-4">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-info-circle"></i> <?php echo $term_idcheck_info_heading ?>
						</h4>
						<div class="card-body">
										
							<div id="idcheck_data_details" class="row " >
								<div class="col-sm-4  col-md-2 text-left text-sm-right required"><b><?php echo $term_idcheck_table_type; ?></b></div>
								<div class="col-sm-8 col-md-10"><input class="form-control" type="text" id="type" name="type" value="<?php echo $idcheck['type']; ?>"  required ></div>
							

								<div class="col-12  pt-3">
									<div class="row">
										<div class="col-sm-4 col-md-2 text-left text-sm-right required"><b><?php echo $term_idcheck_table_idcheck_number; ?></b></div>
										<div class="col-sm-8 col-md-4"><input class="form-control" type="text" id="idcheck_number" name="idcheck_number" value="<?php echo $idcheck['idcheck_number']; ?>" required ></div>

										<div class="col-12 col-md-6  pt-3">		
											<div class="row">
												<div class="col-sm-4 col-md-4 text-left text-sm-right required"><b><?php echo $term_idcheck_table_idcheck_date; ?></b></div>
												<div class="col-sm-8 col-md-8"><input class="form-control calendar-doc" type="text" id="idcheck_date" name="idcheck_date" value="<?php echo $idcheck['idcheck_from']; ?>"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12  pt-3">
									<div class="row">							
										<div class="col-sm-4 col-md-2 text-left text-sm-right"><b><?php echo $term_idcheck_table_idcheck_expire; ?></b></div>
										<div class="col-sm-8 col-md-4 form-check">
											<input type="checkbox" class="form-check-input" id="idcheck_expire" <?php echo empty($idcheck['idcheck_to']) ? '' : 'checked'; ?>>
											<label class="form-check-label" for="idcheck_expire"><?php echo $term_idcheck_table_idcheck_expire_label; ?></label>
										</div>
										<div class="col-12 col-md-6  pt-3">		
											<div class="row">
												<div class="col-sm-4 col-md-4 text-left text-sm-right"><b><?php echo $term_idcheck_table_idcheck_expiry; ?></b></div>
												<div class="col-sm-8 col-md-8">
														<div id="idcheck_expire_no">
															<em><?php echo $term_idcheck_table_to_idcheck; ?></em>
														</div>
														<div id="idcheck_expire_yes" class="not-showing">												
															<input class="form-control calendar-exp" type="text" id="idcheck_expiry" name="idcheck_expiry" value="<?php echo $idcheck['idcheck_to']; ?>">
														</div>
												</div>
											</div>
										</div>
									</div>
								</div>			
							</div>
							
						</div>
					</div>
					
					<!-- start idcheck image -->
					<div id="idcheck_image" class="card">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-image"></i> <?php echo $term_idcheck_image_heading ?>
						</h4>
						
						<div class="card-body">

							<input type="hidden" id="idcheck_base64" name="idcheck_base64">							
<?php
						$class = "not-showing";
						if(!empty($idcheck['filename'])) 
						{
							$class="";
?>										
							<!-- idcheck image if any -->
							<div class="container  text-center">
								<input type="hidden" id="idcheck_current" name="idcheck_current" value="<?php echo $idcheck['filename'] ?>">
							</div>
							<hr>
<?php
						}
?>
							<div class="text-center <?php echo $class;?>" id="d_curr_img">
								<img  id="curr_img" class="img-fluid" src="/ab/show/<?php echo $idcheck['filename'] ?>" alt="Current ID Check Image" >
								<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								<hr>
							</div>

							<ul class="nav nav-tabs md-pills pills-unique nav-fill" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="portrait-tab" data-toggle="tab" href="#portrait" role="tab" aria-controls="portrait" aria-selected="true">
										<i class="far fa-file-image"> </i> <?php echo $term_idcheck_tab_portrait ?>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="landscape-tab" data-toggle="tab" href="#landscape" role="tab" aria-controls="landscape" aria-selected="true">
										<i class="far fa-image"></i> <?php echo $term_idcheck_tab_landscape ?>
									</a>
								</li>
							</ul>
							<hr>

							<div class="tab-content">
								
								<div id="portrait" class="tab-pane fade show active">
						
									<div class="form-group">
										<label for="idcheck_input_portrait" class="required"><?php echo $term_idcheck_image_choose_file; ?></label>
										<input type="file" class="col-12" id="idcheck_input_portrait" accept=".jpg,.png,.gif" >
									</div>
									
			                        <div id="idcheck_croppie_wrap_portrait" class="mw-100 w-auto mh-100 h-auto">
			                            <div id="idcheck_croppie_portrait" data-banner-width="600" data-banner-height="800"></div>
			                        </div>
			                        
			                        <button class="btn btn-default btn-block not-showing" type="button" id="idcheck_result_portrait"><?php echo $term_idcheck_image_crop; ?></button>
		
								</div>

								<div id="landscape" class="tab-pane fade">
	
									<div class="form-group">
										<label for="idcheck_input_landscape"><?php echo $term_idcheck_image_choose_file; ?></label>
										<input type="file" class="col-12" id="idcheck_input_landscape" accept=".jpg,.png,.gif"  >
									</div>
									
			                        <div id="idcheck_croppie_wrap_landscape" class="mw-100 w-auto mh-100 h-auto">
			                            <div id="idcheck_croppie_landscape" data-banner-width="800" data-banner-height="600"></div>
			                        </div>
			                        
			                        <button class="btn btn-default btn-block not-showing" type="button" id="idcheck_result_landscape"><?php echo $term_idcheck_image_crop; ?></button>
			
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
							<button type="submit" name="next" value="home" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_idcheck; ?></button>
							<button type="submit" name="next" value="again" class="btn btn-md btn-primary font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_idcheck_add; ?></button>
						</div>
					</div>
				</div>
				
			</form>
			
		</div>
	</div>
</div>
