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
			    <?php echo $term_oktb_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
		    </h3>
			
			<form method="post" id="oktb">
				
				<div class="card-body">
					<!-- main oktb -->
					<div id="oktb_type_select" class="card mb-4">
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="far fa-question-circle"></i> <?php echo $term_oktb_info_heading ?>
						</h4>
						
						<div class="card-body">
						
							<div class="row justify-content-center">
								<div class="col-12 col-md-6 pt-2">
									<div class="row">
										<div class="col-sm-3 col-md-4 text-left text-sm-right required"><b><?php echo $term_oktb_table_type; ?></b></div>
										<div class="col-sm-9 col-md-8"><input class="form-control" type="text" id="oktb_type" name="oktb_type" value="<?php echo $oktb['oktb_type']; ?>" required></div>
									</div>
								</div>
								<div class="col-12 col-md-6 pt-2">									
									<div class="row">
										<div class="col-sm-3 col-md-4 text-left text-sm-right required"><b><?php echo $term_oktb_table_number; ?></b></div>
										<div class="col-sm-9 col-md-8 "><input class="form-control" type="text" id="oktb_number" name="oktb_number" value="<?php echo $oktb['oktb_number']; ?>" maxlength="12" required></div>
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-12 col-md-6 pt-2">
									<div class="row">
										<div class="col-sm-3 col-md-4 text-left text-sm-right required"><b><?php echo $term_oktb_table_from_date; ?></b></div>
										<div class="col-sm-9 col-md-8"><input class="form-control calendar-doi" type="text" id="from_date" name="date_of_issue" value="<?php echo $oktb['date_of_issue']; ?>" ></div>
									</div>
								</div>

								<div class="col-12 col-md-6 pt-2">
									<div class="row">
										<div class="col-sm-3 col-md-4 text-left text-sm-right required"><b><?php echo $term_oktb_table_to_date; ?></b></div>
										<div class="col-sm-9 col-md-8"><input class="form-control calendar-exp" type="text" id="to_date" name="valid_until" value="<?php echo $oktb['valid_until']; ?>"></div>
									</div>
								</div>
							</div>
							
							<div class="row align-items justify-content-center">
								
								<div class="col-sm-12 col-md-2 text-left text-sm-right required d-flex align-items-center">
									<b class="ml-auto"><?php echo $term_oktb_active; ?></b>
									<input type="hidden" id="active_current" name="active_current" value="<?php echo $oktb['active']; ?>">
								</div>
								<div class="col-12 col-md-10">
									<div class="row align-items-center">
										<div class="col-md-6">
											<div class="iow-ck-button">
												<label>
													<input type="radio" class="active" id="active" name="active" value="active" hidden="hidden">
													<span class="oktb"><?php echo $term_oktb_active_yes; ?></span>
												</label>
											</div>
										</div>
										<div class="col-md-6">
										
											<div class="iow-ck-button">
												<label>
													<input type="radio" class="active" id="not_active" name="active" value="not_active" hidden="hidden">
													<span class="oktb"><?php echo $term_oktb_active_no; ?></span>
												</label>
											</div>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					
					
					<div id="oktb_image" class="card col-sm-12 col-md-12 m-0 p-0 ">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-image"></i> <?php echo $term_oktb_image_heading ?>
						</h4>
						
						<div class="card-body">		
							<div class="container ">
								<div class="form-group">
									<label for="oktb_input" class="required"><?php echo $term_oktb_image_choose_file; ?></label>
									<input type="file" class="col-12" id="oktb_input_file" accept=".pdf"  <?php echo (empty($oktb['filename'])) ? 'required' : '' ?>>
								</div>

								<div id="pdf-preview" data-url="<?php echo (!empty($oktb['filename'])) ? '/ab/show/' . $oktb['filename'] : '' ?>"></div>
								<input type="hidden" name="file_base64">
								<input type="hidden" name="filename" value="<?php echo $oktb['filename'] ?>">
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
							<button type="submit" name="next" value="home" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_oktb; ?></button>
							<button type="submit" name="next" value="again" class="btn btn-md btn-primary font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_oktb_add; ?></button>
						</div>
					</div>

				</div>
				
			</form>
			
		</div>
	</div>
</div>

