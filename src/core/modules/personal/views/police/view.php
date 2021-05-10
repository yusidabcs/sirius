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
			    <?php echo $term_police_panel_title; ?>
		    </h3>
			
			<form method="post" id="passport">
                <input type="hidden" name="police_id" value="<?php echo $police['police_id']; ?>">
				<div class="card-body">
					<!-- main passport -->
					<div id="main_police_data" class="card mb-4">
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="far fa-question-circle"></i> <?php echo $term_police_type_heading; ?>
						</h4>
						
						<div class="card-body">
					
							<div class="row align-items-center">
								<div class="col-md-4 text-left text-md-right required"><b><?php echo $term_police_country; ?></b></div>
								
								<div class="col-md-8 form-group ">
									<select id="countryCode_id" name="countryCode_id" class="mdb-select md-form" searchable="Search" required>
										<option value=""><?php echo $term_police_table_select_please; ?></option>
<?php
										foreach($countryCodes as $id => $country)
										{
?>											
											
											<option value="<?php echo $id; ?>" <?php if($police['countryCode_id'] == $id) echo 'selected="Selected"'; ?>><?php echo $country; ?></option>
<?php
										}
?>
									</select>
								</div>
									
								
								<div class="col-md-4 text-left text-md-right required">
									<b><?php echo $term_police_active; ?></b>
									<input type="hidden" id="active_current" name="active_current" value="<?php echo $police['active']; ?>">
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="iow-ck-button">
										<label>
											<input type="radio" class="active" id="active" name="active" value="active" hidden="hidden">
											<span class="passport"><?php echo $term_police_active_yes; ?></span>
										</label>
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="iow-ck-button">
										<label>
											<input type="radio" class="active" id="not_active" name="active" value="not_active" hidden="hidden">
											<span class="passport"><?php echo $term_police_active_no; ?></span>
										</label>
									</div>
								</div>
							</div>
							
						</div>
					</div>
					
					
					<!-- passport information -->
					<div id="police_information" class="card mb-4">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-info-circle"></i> <?php echo $term_police_info_heading; ?>
						</h4>
						
						<div class="card-body">
							
							<div id="police_data_details" class="row align-items-center" >

								<div class="info col-12">
									
								</div>
								
								<div class="full_name_tr col-12 pt-3">
									<div class="row">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_police_table_full_name; ?></b></div>
										<div class="col-sm-10"><input class="form-control" type="text" id="full_name" name="full_name" value="<?php echo $police['full_name']; ?>"></div>
									</div>
								</div>

								<div class="col-12 pt-3">
									<div class="row align-items-center">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_police_table_nationality; ?></b></div>
										<div class="col-sm-4"><input class="form-control" type="text" id="nationality" name="nationality" value="<?php echo $police['nationality']; ?>" required></div>

										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_police_table_sex; ?></b></div>
										<div class="col-sm-4 form-group">
											<select id="sex" name="sex" class="mdb-select md-form align-middle" required>
												<option value=""><?php echo $term_police_table_select_please; ?></option>
												<option value="female" <?php if($police['sex'] == 'female') echo 'selected="Selected"'; ?>><?php echo $term_police_table_select_female; ?></option>
												<option value="male" <?php if($police['sex'] == 'male') echo 'selected="Selected"'; ?>><?php echo $term_police_table_select_male; ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_police_table_dob; ?></b></div>
										<div class="col-sm-4"><input class="form-control calendar-dob" type="text" id="dob" name="dob" value="<?php echo $police['dob']; ?>" required></div>

										<div class="col-sm-2 text-left text-sm-right align-middle required"><b><?php echo $term_police_table_pob; ?></b></div>
										<div class="col-sm-4"><input class="form-control" type="text" id="pob" name="pob" value="<?php echo $police['pob']; ?>" required></div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_police_table_from_date; ?></b></div>
										<div class="col-sm-4"><input class="form-control calendar-doi" type="text" id="from_date" name="from_date" value="<?php echo $police['from_date']; ?>" ></div>

										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_police_table_to_date; ?></b></div>
										<div class="col-sm-4"><input class="form-control calendar-exp" type="text" id="to_date" name="to_date" value="<?php echo $police['to_date']; ?>"></div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_police_table_place_issued; ?></b></div>
										<div class="col-sm-4"><input class="form-control" type="text" id="place_issued" name="place_issued" value="<?php echo $police['place_issued']; ?>" required></div>
										<!--<div class="col-sm-2 text-left text-sm-right required"><b><?php /*echo $term_police_table_authority; */?></b></div>
										<div class="col-sm-4"><input class="form-control" type="text" id="authority" name="authority" value="<?php /*echo $police['authority']; */?>" required></div>-->
									</div>
								</div>
								
							</div>
								
						</div>
					</div>
					
					<!-- passport photo -->
					<div id="police_image"  class="card col-sm-12 col-md-12 m-0 p-0 ">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-image"></i> <?php echo $term_police_image_heading; ?>
						</h4>
						
						<div class="card-body">
							<div class="container">							
	<?php
							$class ="not-showing";
							if(!empty($police['filename'])) {
								$class ="";
	?>										
								<!-- passport image if any -->
								<div>
									<input type="hidden" id="police_current" name="police_current" value="<?php echo $police['filename']; ?>">
								</div>
								
								<!-- end of passport photo -->	
	<?php
							}
	?>
							<div class="text-center <?php echo $class;?>" id="d_curr_img">
								<img id="curr_img" class="img-fluid" src="/ab/show/<?php echo $police['filename']; ?>" alt="Current Police Image" >
								<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								<hr>
							</div>		

								<div class="form-group">
									<label for="police_input" class="required"><?php echo $term_police_image_choose_file; ?></label>
									<input type="file" class="col-12" id="police_input" accept=".jpg,.png,.gif" <?php echo (empty($police['filename'])) ? 'required' : '' ?>>
									<input type="hidden" id="police_base64" name="police_base64">
								</div>
								
								<div id="police_croppie_wrap" class="mw-100 w-auto mh-100 h-auto  text-center">
									<div id="police_croppie"  data-banner-width="500" data-banner-height="700"></div>
								</div>
												
								<button class="btn btn-default btn-block not-showing" type="button" id="police_result"><?php echo $term_police_image_crop; ?></button>
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
							<button type="submit" name="next" value="home" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_police; ?></button>
							<button type="submit" name="next" value="again" class="btn btn-md btn-primary font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_police_add; ?></button>
						</div>
					</div>
				</div>
				
			</form>
			
		</div>
	</div>
</div>
