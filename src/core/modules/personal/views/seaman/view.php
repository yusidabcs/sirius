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

<div class="container">
	<div  >
		
		<!-- Main Card -->
		<div class="card mb-4">
		    <h3 class="card-header blue-gradient white-text text-center py-4">
			    <?php echo $term_seaman_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
		    </h3>
			
			<form method="post" id="passport">
				
				<div class="card-body">
					<!-- main passport -->
					<div id="main_seaman_data" class="card mb-4">
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="far fa-question-circle"></i> <?php echo $term_seaman_type_heading; ?>
						</h4>
						
						<div class="card-body">
					
							<div class="row align-items-center">
								<div class="col-md-4 text-left text-md-right required"><b><?php echo $term_seaman_country; ?></b></div>
								
								<div class="col-md-8 form-group ">
									<select id="countryCode_id" name="countryCode_id" class="mdb-select md-form" searchable="Search" required>
										<option value=""><?php echo $term_seaman_table_select_please; ?></option>
<?php
										foreach($countryCodes as $id => $country)
										{
?>											
											
											<option value="<?php echo $id; ?>" <?php if($seaman['countryCode_id'] == $id) echo 'selected="Selected"'; ?>><?php echo $country; ?></option>
<?php
										}
?>
									</select>
								</div>
									
								
								<div class="col-md-4 text-left text-md-right required">
									<b><?php echo $term_seaman_active; ?></b>
									<input type="hidden" id="active_current" name="active_current" value="<?php echo $seaman['active']; ?>">
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="iow-ck-button">
										<label>
											<input type="radio" class="active" id="active" name="active" value="active" hidden="hidden">
											<span class="passport"><?php echo $term_seaman_active_yes; ?></span>
										</label>
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="iow-ck-button">
										<label>
											<input type="radio" class="active" id="not_active" name="active" value="not_active" hidden="hidden">
											<span class="passport"><?php echo $term_seaman_active_no; ?></span>
										</label>
									</div>
								</div>
								
								<div class="col-md-4 text-left text-md-right required"><b><?php echo $term_seaman_name_on; ?></b></div>
								<div class="col-sm-6 col-md-4">
									<div class="iow-ck-button">
										<label>
											<input type="radio" class="name_style" id="type_full_name" name="name_style" value="full" hidden="hidden">
											<span class="passport"><?php echo $term_seaman_name_full_only; ?></span>
										</label>
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="iow-ck-button">
										<label>
											<input type="radio" class="name_style" id="type_separate_names" name="name_style" value="separate" hidden="hidden">
											<span class="passport"><?php echo $term_seaman_name_sep; ?></span>
										</label>
									</div>
								</div>

							</div>							
						</div>
					</div>
					
					
					<!-- seaman information -->
					<div id="seaman_information" class="card mb-4 not-showing">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-info-circle"></i> <?php echo $term_seaman_info_heading; ?>
						</h4>
						
						<div class="card-body">
							
							<div id="seaman_data_details" class="row align-items-center" >
								
								<div class="offset-sm-6 col-sm-2 offset-lg-0 col-lg-2 text-left text-sm-right required"><b><?php echo $term_seaman_table_number; ?></b></div>
								<div class="col-sm-4 col-lg-3"><input class="form-control" type="text" id="sbk_id" name="sbk_id" value="<?php echo $seaman['sbk_id']; ?>" maxlength="12" required></div>
								
								<div class="info col-12">
									
								</div>
								
								<div class="full_name_tr col-12 pt-3">
									<div class="row">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_seaman_table_full_name; ?></b></div>
										<div class="col-sm-10"><input class="form-control" type="text" id="full_name" name="full_name" value="<?php echo $seaman['full_name']; ?>"></div>
									</div>
								</div>
								
								<div class="separate_names_tr col-sm-12 col-md-6 pt-3">
									<div class="row">
										<div class="col-sm-2 col-md-4 text-left text-sm-right required"><b><?php echo $term_seaman_table_family_name; ?></b></div>
										<div class="col-sm-10 col-md-8"><input class="form-control" type="text" id="family_name" name="family_name" value="<?php echo $seaman['family_name']; ?>"></div>
									</div>
								</div>
								
								<div class="separate_names_tr col-12 col-md-6 pt-3">
									<div class="row">
										<div class="col-sm-2 col-md-4 text-left text-sm-right required"><b><?php echo $term_seaman_table_given_names; ?></b></div>
										<div class="col-sm-10 col-md-8"><input class="form-control" type="text" id="given_names" name="given_names" value="<?php echo $seaman['given_names']; ?>"></div>
									</div>
								</div>

								<div class="col-12 pt-3">
									<div class="row align-items-center">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_seaman_table_nationality; ?></b></div>
										<div class="col-sm-4"><input class="form-control" type="text" id="nationality" name="nationality" value="<?php echo $seaman['nationality']; ?>" required></div>

										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_seaman_table_sex; ?></b></div>
										<div class="col-sm-4 form-group">
											<select id="sex" name="sex" class="mdb-select md-form align-middle" required>
												<option value=""><?php echo $term_seaman_table_select_please; ?></option>
												<option value="female" <?php if($seaman['sex'] == 'female') echo 'selected="Selected"'; ?>><?php echo $term_seaman_table_select_female; ?></option>
												<option value="male" <?php if($seaman['sex'] == 'male') echo 'selected="Selected"'; ?>><?php echo $term_seaman_table_select_male; ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_seaman_table_dob; ?></b></div>
										<div class="col-sm-4"><input class="form-control calendar-dob" type="text" id="dob" name="dob" value="<?php echo $seaman['dob']; ?>" required></div>

										<div class="col-sm-2 text-left text-sm-right align-middle required"><b><?php echo $term_seaman_table_pob; ?></b></div>
										<div class="col-sm-4"><input class="form-control" type="text" id="pob" name="pob" value="<?php echo $seaman['pob']; ?>" required></div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_seaman_table_from_date; ?></b></div>
										<div class="col-sm-4"><input class="form-control calendar-doi" type="text" id="from_date" name="from_date" value="<?php echo $seaman['from_date']; ?>" ></div>

										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_seaman_table_to_date; ?></b></div>
										<div class="col-sm-4"><input class="form-control calendar-exp" type="text" id="to_date" name="to_date" value="<?php echo $seaman['to_date']; ?>"></div>
									</div>
								</div>
								
								<div class="col-12 pt-3">
									<div class="row">
										<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_seaman_table_authority; ?></b></div>
										<div class="col-sm-4"><input class="form-control" type="text" id="authority" name="authority" value="<?php echo $seaman['authority']; ?>" required></div>
									</div>
								</div>
								
							</div>
								
						</div>
					</div>
					
					<!-- passport photo -->
					<div id="seaman_image"  class="card col-sm-12 col-md-12 m-0 p-0 ">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-image"></i> <?php echo $term_seaman_image_heading; ?>
						</h4>
						
						<div class="card-body">
							<div class="container">							
	<?php
							$class = "not-showing";
							if(!empty($seaman['filename'])) {
								$class="";
	?>										
								<!-- passport image if any -->
								<div>
									<input type="hidden" id="seaman_current" name="seaman_current" value="<?php echo $seaman['filename']; ?>">
								</div>
								
								<!-- end of passport photo -->	
	<?php
							}
	?>
							<div class="text-center <?php echo $class;?>" id="d_curr_img">
								<img id="curr_img" class="img-fluid" src="/ab/show/<?php echo $seaman['filename']; ?>" alt="Current Seaman Image" >
								<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								<hr>
							</div>		

								<div class="form-group">
									<label for="seaman_input" class="required"><?php echo $term_seaman_image_choose_file; ?></label>
									<input type="file" class="col-12" id="seaman_input" accept=".jpg,.png,.gif" <?php echo (empty($seaman['filename'])) ? 'required' : '' ?>>
									<input type="hidden" id="seaman_base64" name="seaman_base64">
								</div>
								
								<div id="seaman_croppie_wrap" class="mw-100 w-auto mh-100 h-auto not-showing text-center">
									<div id="seaman_croppie"  data-banner-width="700" data-banner-height="500"></div>
								</div>
												
								<button class="btn btn-default btn-block not-showing" type="button" id="seaman_result"><?php echo $term_seaman_image_crop; ?></button>
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
							<button type="submit" name="next" value="home" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_seaman; ?></button>
							<button type="submit" name="next" value="again" class="btn btn-md btn-primary font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_seaman_add; ?></button>
						</div>
					</div>
				</div>
				
			</form>
			
		</div>
	</div>
</div>
