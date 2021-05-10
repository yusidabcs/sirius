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
			    <?php echo $term_visa_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
		    </h3>
			
			<form method="post" id="visa">
				
				<div class="card-body">
					<!-- main visa -->
					<div id="visa_type_select" class="card mb-4">
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="far fa-question-circle"></i> <?php echo $term_visa_type_heading ?>
						</h4>
						
						<div class="card-body">
						
							<div class="row align-items-center">
								<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_visa_country; ?></b></div>
								
								<div class="col-sm-8 form-group">
									<select id="countryCode_id" name="countryCode_id" class="mdb-select md-form" searchable="Search country" required>
										<option value=""><?php echo $term_visa_table_select_please; ?></option>
<?php
										foreach($countryCodes as $id => $country)
										{
?>											
											
											<option value="<?php echo $id; ?>" <?php if($visa['countryCode_id'] == $id) echo 'selected="Selected"'; ?>><?php echo $country; ?></option>
<?php
										}
?>
									</select>
								</div>
								
								<div class="col-sm-4 text-left text-sm-right required">
									<b><?php echo $term_visa_active; ?></b>
									<input type="hidden" id="active_current" name="active_current" value="<?php echo $visa['active']; ?>">
								</div>
								<div class="iow-ck-button col-sm-4">
									<label>
										<input type="radio" class="active" id="active" name="active" value="active" hidden="hidden">
										<span class="visa"><?php echo $term_visa_active_yes; ?></span>
									</label>
								</div>
								<div class="iow-ck-button col-sm-4">
									<label>
										<input type="radio" class="active" id="not_active" name="active" value="not_active" hidden="hidden">
										<span class="visa"><?php echo $term_visa_active_no; ?></span>
									</label>
								</div>
								
								<div class="col-sm-4 text-left text-sm-right required"><b><?php echo $term_visa_name_on; ?></b></div>
								<div class="iow-ck-button col-sm-4">
									<label>
										<input type="radio" class="name_style" id="type_full_name" name="name_style" value="full" hidden="hidden">
										<span class="visa"><?php echo $term_visa_name_full_only; ?></span>
									</label>
								</div>
								<div class="iow-ck-button col-sm-4">
									<label>
										<input type="radio" class="name_style" id="type_separate_names" name="name_style" value="separate" hidden="hidden">
										<span class="visa"><?php echo $term_visa_name_separate; ?></span>
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<!-- visa information -->
					<div id="visa_information" class="not-showing">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-info-circle"></i> <?php echo $term_visa_info_heading ?>
						</h4>
						
						<div class="card-body">
							
							<div id="visa_data_details" class="row" >
								
								<div class="col-12 col-md-4 pt-2">
									<div class="row">
										<div class="col-sm-3 col-md-4 text-left text-sm-right required"><b><?php echo $term_visa_table_type; ?></b></div>
										<div class="col-sm-9 col-md-8"><input class="form-control" type="text" id="type" name="type" value="<?php echo $visa['type']; ?>" maxlength="50" required></div>
									</div>
								</div>
								<div class="col-12 col-md-4 pt-2">									
									<div class="row">
										<div class="col-sm-3 col-md-4 text-left text-sm-right required"><b><?php echo $term_visa_table_class; ?></b></div>
										<div class="col-sm-9 col-md-8"><input class="form-control" type="text" id="class" name="class" value="<?php echo $visa['class']; ?>" maxlength="10" required></div>
									</div>
								</div>
								<div class="col-12 col-md-4 pt-2">									
									<div class="row">
										<div class="col-sm-3 col-md-4 text-left text-sm-right required"><b><?php echo $term_visa_table_number; ?></b></div>
										<div class="col-sm-9 col-md-8 "><input class="form-control" type="text" id="visa_id" name="visa_id" value="<?php echo $visa['visa_id']; ?>" maxlength="12" required></div>
									</div>
								</div>
								
								<div class="info">
									
								</div>
								
								<div class="full_name_tr col-12 pt-4">
									<div class="row">
										<div class="col-sm-3 col-md-2 text-left text-sm-right required"><b><?php echo $term_visa_table_full_name; ?></b></div>
										<div class="col-sm-9 col-md-10"><input class="form-control" type="text" id="full_name" name="full_name" value="<?php echo $visa['full_name']; ?>"></div>
									</div>
								</div>
								
								<div class="separate_names_tr col-sm-12 col-md-12 pt-4">
									<div class="row">
										<div class="col-sm-3 col-md-2 text-left text-sm-right required"><b><?php echo $term_visa_table_family_name; ?></b></div>
										<div class="col-sm-9 col-md-4"><input class="form-control" type="text" id="family_name" name="family_name" value="<?php echo $visa['family_name']; ?>"></div>
										<div class="col-sm-3  col-md-2 text-left text-sm-right required"><b><?php echo $term_visa_table_given_names; ?></b></div>
										<div class="col-sm-9 col-md-4"><input class="form-control" type="text" id="given_names" name="given_names" value="<?php echo $visa['given_names']; ?>"></div>
									</div>
								</div>
								
								

								<div class="col-12 col-md-12 pt-2 ">
									<div class="row align-items-center">
										<div class="col-sm-3 col-md-2 text-left text-sm-right required"><b><?php echo $term_visa_table_place_issued; ?></b></div>
										<div class="col-sm-9 col-md-4"><input class="form-control" type="text" id="place_issued" name="place_issued" value="<?php echo $visa['place_issued']; ?>" required></div>
										<div class="col-sm-3 col-md-2 mt-2 mt-sm-0 text-left text-sm-right required"><b><?php echo $term_visa_table_entry; ?></b></div>
										<div class="col-sm-9 col-md-4 form-group  ">
											<select id="entry" name="entry" class="mdb-select md-form" required>
												<option value=""><?php echo $term_visa_table_select_please; ?></option>
												<option value="single" <?php if($visa['entry'] == 'single') echo 'selected="Selected"'; ?>><?php echo $term_visa_table_select_single; ?></option>
												<option value="multiple" <?php if($visa['entry'] == 'multiple') echo 'selected="Selected"'; ?>><?php echo $term_visa_table_select_multiple; ?></option>
											</select>
										</div>
									</div>
								</div>	

																	
								<div class="col-12 col-md-6 pt-2">
									<div class="row">
										<div class="col-sm-3 col-md-4 text-left text-sm-right required"><b><?php echo $term_visa_table_from_date; ?></b></div>
										<div class="col-sm-9 col-md-8"><input class="form-control calendar-doi" type="text" id="from_date" name="from_date" value="<?php echo $visa['from_date']; ?>" ></div>
									</div>
								</div>

								<div class="col-12 col-md-6 pt-2">
									<div class="row">
										<div class="col-sm-3 col-md-4 text-left text-sm-right required"><b><?php echo $term_visa_table_to_date; ?></b></div>
										<div class="col-sm-9 col-md-8"><input class="form-control calendar-exp" type="text" id="to_date" name="to_date" value="<?php echo $visa['to_date']; ?>"></div>
									</div>
								</div>
								
								<div class="col-12 col-md-12 pt-2 ">
									<div class="row align-items-center">
										<div class="col-sm-3 col-md-2 text-left text-sm-right required"><b><?php echo $term_visa_table_authority; ?></b></div>
										<div class="col-sm-9 col-md-4"><input class="form-control" type="text" id="authority" name="authority" value="<?php echo $visa['authority']; ?>" required></div>
										<div class="col-sm-3 col-md-2 mt-2 mt-sm-0 text-left text-sm-right required"><b><?php echo $term_visa_table_passport_id; ?></b></div>
										<div class="col-sm-9 col-md-4 form-group">
											<select id="passport_id" name="passport_id" class="mdb-select md-form" required>
												<option value=""><?php echo $term_visa_table_select_please; ?></option>
<?php
											foreach($passportArray as $passport)
											{
?>
												<option value="<?php echo $passport; ?>" <?php if($visa['passport_id'] == $passport) echo 'selected="Selected"'; ?>><?php echo $passport; ?></option>
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
					
					<!-- visa information -->
					<div id="visa_image" class="card col-sm-12 col-md-12 m-0 p-0 ">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-image"></i> <?php echo $term_visa_image_heading ?>
						</h4>
						
						<div class="card-body">		
							<div class="container ">						
<?php
							$class ="not-showing";
							if(!empty($visa['filename'])) {
								$class = "";
	?>										
									<!-- visa image if any -->
									<div class="text-center">
										<input type="hidden" id="visa_current" name="visa_current" value="<?php echo $visa['filename'] ?>">
									</div>
									
									<!-- end of visa photo -->	
	<?php
							}
	?>
							<div class="text-center <?php echo $class;?>" id="d_curr_img">
								<img id="curr_img" class="img-fluid" src="/ab/show/<?php echo $visa['filename'] ?>" alt="Current Visa Image" >
								<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								<hr>
							</div>		
								<div class="form-group">
									<label for="visa_input" class="required"><?php echo $term_visa_image_choose_file; ?></label>
									<input type="file" class="col-12" id="visa_input" accept=".jpg,.png,.gif"  <?php echo (empty($visa['filename'])) ? 'required' : '' ?>>
									<input type="hidden" id="visa_base64" name="visa_base64">
								</div>
								
								<div id="visa_croppie_wrap" class="mw-100 w-auto mh-100 h-auto">
									<div id="visa_croppie" data-banner-width="600" data-banner-height="400"></div>
								</div>
								
								<button class="btn btn-default btn-block not-showing" type="button" id="visa_result"><?php echo $term_visa_image_crop; ?></button>
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
							<button type="submit" name="next" value="home" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_visa; ?></button>
							<button type="submit" name="next" value="again" class="btn btn-md btn-primary font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_visa_add; ?></button>
						</div>
					</div>
				</div>
				
			</form>
			
		</div>
	</div>
</div>

