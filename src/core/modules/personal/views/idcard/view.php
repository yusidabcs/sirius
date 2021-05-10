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
	<div class="col-12 p-0 px-md-3" >
		<!-- Main Card -->
		<div class="card mb-4">
		    <h3 class="card-header blue-gradient white-text text-center py-4">
			    <?php echo $term_idcard_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
		    </h3>
			
			<form method="post" id="idcard">
				
				<div class="card-body">
					<!-- main id info -->
					<div class="card mb-4">
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="far fa-question-circle"></i> <?php echo $term_idcard_type_heading; ?>
						</h4>
						
						<div class="card-body">
								
							<div class="col-12 ">
								<div class="row align-items-center">
									<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_idcard_country; ?></b></div>
									<div class="col-sm-8 form-group">
										<select id="countryCode_id" name="countryCode_id" class="mdb-select md-form" searchable="Search country" required >
											<option value=""><?php echo $term_idcard_table_select_please; ?></option>
<?php
											foreach($countryCodes as $id => $country)
											{
?>											
												<option value="<?php echo $id; ?>" <?php if($idcard['countryCode_id'] == $id) echo 'selected="Selected"'; ?>><?php echo $country; ?></option>
<?php
											}
?>
										</select>
									</div>
									
									<div class="col-sm-4 text-left text-sm-right required">
										<b><?php echo $term_idcard_active; ?></b>
										<input type="hidden" id="active_current" name="active_current" value="<?php echo $idcard['active']; ?>">
									</div>
									<div class="iow-ck-button col-sm-4">
										<label>
											<input type="radio" class="active" id="active" name="active" value="active" hidden="hidden">
											<span class="idcard"><?php echo $term_idcard_active_yes; ?></span>
										</label>
									</div>
									
									<div class="iow-ck-button col-sm-4">
										<label>
											<input type="radio" class="active" id="not_active" name="active" value="not_active" hidden="hidden">
											<span class="idcard"><?php echo $term_idcard_active_no; ?></span>
										</label>
									</div>
									
									<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_idcard_name_on; ?></b></div>
									
									<div class="iow-ck-button col-sm-4">
										<label>
											<input type="radio" class="name_style" id="type_full_name" name="name_style" value="full" hidden="hidden">
											<span class="idcard"><?php echo $term_idcard_name_full_only; ?></span>
										</label>
									</div>
									<div class="iow-ck-button col-sm-4">
										<label>
											<input type="radio" class="name_style" id="type_separate_names" name="name_style" value="separate" hidden="hidden">
											<span class="idcard"><?php echo $term_idcard_name_sep; ?></span>
										</label>
									</div>
								</div>
							</div>
							
						</div>
					</div>
					
					
					<!-- idcard information -->
					<div id="idcard_information" class="card mb-4 not-showing">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-info-circle"></i> <?php echo $term_idcard_info_heading; ?>
						</h4>
						
						<div class="card-body">
							<div id="idcard_data_details" class="row" >
								
								<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_idcard_table_number; ?></b></div>
								<div class="col-sm-10">
									<input class="form-control" type="text id="idcard_orig" name="idcard_orig" value="<?php echo $idcard['idcard_orig']; ?>" maxlength="25" required>
									<input type="hidden" id="idcard_id" name="idcard_id" value="<?php echo $idcard['idcard_id']; ?>">
								</div>
								
								<div class="info">
									
								</div>
								
								<div class="full_name_tr col-12 pt-3">
									<div class="row">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_idcard_table_full_name; ?></b></div>
										<div class="col-sm-10"><input class="form-control" type="text" id="full_name" name="full_name" value="<?php echo $idcard['full_name']; ?>"></div>
									</div>
								</div>
								
								<div class="separate_names_tr col-sm-12 col-md-6 pt-3">
									<div class="row">
										<div class="col-sm-2  col-md-4 text-left text-sm-right required"><b><?php echo $term_idcard_table_family_name; ?></b></div>
										<div class="col-sm-10 col-md-8"><input class="form-control" type="text" id="family_name" name="family_name" value="<?php echo $idcard['family_name']; ?>"></div>
									</div>
								</div>
								
								<div class="separate_names_tr col-sm-12 col-md-6 pt-3">
									<div class="row">
										<div class="col-sm-2  col-md-4 text-left text-sm-right required"><b><?php echo $term_idcard_table_given_names; ?></b></div>
										<div class="col-sm-10 col-md-8"><input class="form-control" type="text" id="given_names" name="given_names" value="<?php echo $idcard['given_names']; ?>"></div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row align-items-center">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_idcard_table_authority; ?></b></div>
										<div class="col-sm-4"><input class="form-control" type="text id="authority" name="authority" value="<?php echo $idcard['authority']; ?>" required></div>
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_idcard_table_type; ?></b></div>
										<div class="col-sm-4">
											<div class="form-group">
												<select id="type" name="type" class="mdb-select md-form" required>
													<option value=""><?php echo $term_idcard_table_select_please; ?></option>
													<option value="national" <?php if($idcard['type'] == 'national') echo 'selected="Selected"'; ?>><?php echo $term_idcard_table_select_national; ?></option>
													<option value="driver" <?php if($idcard['type'] == 'driver') echo 'selected="Selected"'; ?>><?php echo $term_idcard_table_select_driver; ?></option>
													<option value="other" <?php if($idcard['type'] == 'other') echo 'selected="Selected"'; ?>><?php echo $term_idcard_table_select_other; ?></option>
												</select>
											</div>
										</div>
									</div>
								</div>

								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-2 text-left text-sm-right"><b><?php echo $term_idcard_table_id_expire; ?></b></div>
										<div class="col-sm-4 form-check ">
											<input type="checkbox" class="form-check-input" id="id_expire" name="id_expire" <?php echo empty($idcard['to_date']) ? '' : 'checked'; ?>>
											<label class="form-check-label" for="id_expire"><?php echo $term_idcard_table_id_expire_label; ?></label>
										</div>
										<div class="col-sm-6"></div>							
									</div>
								</div>

								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_idcard_table_from_date; ?></b></div>
										<div class="col-sm-4"><input class="calendar-doi form-control" type="text" id="from_date" name="from_date" value="<?php echo $idcard['from_date']; ?>" ></div>
										
										<div class="col-sm-2 text-left text-sm-right"><b><?php echo $term_idcard_table_to_date; ?></b></div>
										<div class="col-sm-4">
											<div id="id_expire_no">
												<em><?php echo $term_idcard_table_id_never_expire; ?></em>
											</div>
											<div id="id_expire_yes" class="not-showing">
												<input class="calendar-exp form-control" type="text" id="to_date" name="to_date" value="<?php echo $idcard['to_date']; ?>">
											</div>
										</div>
									</div>
								</div>
								
							</div>
								
						</div>
					</div>
					
					<!-- idcard information -->
					<div id="idcard_image" class="card">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-image"></i> <?php echo $term_idcard_image_heading; ?>
						</h4>
						
						<div class="card-body p-0">
								<div id="idcard_image_front" class="roundBox">		
										<h2><?php echo $term_idcard_image_front; ?></h2>
										
		<?php
										$class = "not-showing";
										if(!empty($idcard['filename'])) {
											$class = "";
		?>										
											<!-- idcard image if any -->
											<div class="text-center">
												<input type="hidden" id="idcard_current" name="idcard_current" value="<?php echo $idcard['filename']; ?>">
											</div>
											<!-- end of idcard photo -->	
		<?php
										}
		?>
										<div class="text-center <?php echo $class;?>" id="d_curr_img">
											<img id="curr_img" class="img-fluid" src="/ab/show/<?php echo $idcard['filename']; ?>" alt="Current ID Card Image Front" >
											<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop_front">Crop Photo</button>
											<hr>
										</div>		

										<div class="form-group">
											<label for="idcard_input" class="required"><?php echo $term_idcard_image_choose_file; ?></label>
											<input type="file" class="col-12" id="idcard_input" accept=".jpg,.png,.gif" <?php echo (empty($idcard['filename'])) ? 'required' : '' ?> > 
											<input type="hidden" id="idcard_base64" name="idcard_base64">
										</div>
										
										<div id="idcard_croppie_wrap"  class="mw-100 w-auto mh-100 h-auto">
											<div id="idcard_croppie" data-banner-width="600" data-banner-height="400"></div>
										</div>
										
										<button class="btn btn-default btn-block m-0 not-showing" type="button" id="idcard_result"><?php echo $term_idcard_image_crop; ?></button>
									
								</div><!-- roundBox -->
								
								<div id="idcard_image_back" class="roundBox">

										<h2><?php echo $term_idcard_image_back; ?></h2>
		<?php
										$class="not-showing";
										if(!empty($idcard['filename_back'])) {
											$class="";
		?>										
											<!-- idcard image if any -->
											<div class="text-center">
												<input type="hidden" id="idcard_back_current" name="idcard_back_current" value="<?php echo $idcard['filename_back']; ?>">
											</div>
											
											<!-- end of idcard photo -->	
		<?php
										}
		?>
										
										<div class="text-center <?php echo $class;?>" id="d_curr_img_back">
											<img id="curr_img_back" class="img-fluid" src="/ab/show/<?php echo $idcard['filename_back']; ?>" alt="Current ID Card Image Back" >
											<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop_back">Crop Photo</button>
											<hr>
										</div>
										<div class="form-group">
											<label for="idcard_back_input" class="required"><?php echo $term_idcard_image_choose_file; ?></label>
											<input type="file" class="col-12" id="idcard_back_input" accept=".jpg,.png,.gif"  <?php echo (empty($idcard['filename_back'])) ? '' : '' ?>>
											<input type="hidden" id="idcard_back_base64" name="idcard_back_base64">
										</div>
										
										<div id="idcard_back_croppie_wrap"  class="mw-100 w-auto mh-100 h-auto">
											<div id="idcard_back_croppie" data-banner-width="600" data-banner-height="400"></div>
										</div>
										
										<button class="btn btn-default btn-block m-0 not-showing" type="button" id="idcard_back_result"><?php echo $term_idcard_image_crop; ?></button>
									
								</div><!-- roundBox -->

						</div>
					</div>
								
				</div>

				<div class="card-footer">
					<div class="row flex-column-reverse flex-lg-row">
						<div class="col-lg-6 left">
							<a id="go_back" href="<?php echo $back_url ?>" class="btn btn-md btn-warning font-weight-bold btn-sm-mobile-100" role="button"><i class="fas fa-arrow-circle-left"></i> <?php echo $term_go_back; ?></a>
						</div>
						<div class="col-lg-6 right">
							<button type="submit" name="next" value="home" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_idcard; ?></button>
							<button type="submit" name="next" value="again" class="btn btn-md btn-primary font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_idcard_add; ?></button>
						</div>
					</div>
					
				</div>
				
			</form>
			
		</div>
	</div>
</div>
