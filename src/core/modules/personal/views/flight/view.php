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
			    <?php echo $term_flight_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
		    </h3>
			
			<form method="post" id="flight">
				
				<div class="card-body">
					<!-- main flight -->
					<div id="flight_type_select" class="card mb-4">
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="far fa-question-circle"></i> <?php echo $term_flight_type_heading ?>
						</h4>
						
						<div class="card-body">
						
							<div class="row align-items-center">
								<div class="col-sm-2 text-left text-sm-right required"><b><?php echo $term_flight_number; ?></b></div>
								
								<div class="col-sm-4">
									<input type="text" name="flight_number" id="flight_number" value="<?php echo $flight['flight_number']; ?>" class="form-control">
								</div>
								
								<div class="col-sm-2 text-left text-sm-right required">
									<b><?php echo $term_flight_departure_date; ?></b>
								</div>
								<div class="col-sm-4">
									<input type="text" name="departure_date" id="departure_date" value="<?php echo (!empty($flight['departure_date'])) ? date('d M Y', strtotime($flight['departure_date'])) : ''; ?>" class="form-control calendar-doi">
								</div>
							</div>
						</div>
					</div>
					
					
					<div id="flight_image" class="card col-sm-12 col-md-12 m-0 p-0 ">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-image"></i> <?php echo $term_flight_image_heading ?>
						</h4>
						
						<div class="card-body">		
							<div class="container ">						
<?php
							$class = "not-showing";
							if(!empty($flight['filename'])) {
								$class = "";
	?>										
									<!-- flight image if any -->
									<div class="text-center">
										<input type="hidden" id="flight_current" name="flight_current" value="<?php echo $flight['filename'] ?>">
									</div>
									<!-- end of flight photo -->	
	<?php
							}
	?>
							<div class="text-center <?php echo $class;?>" id="d_curr_img">
								<img id="curr_img" class="img-fluid" src="/ab/show/<?php echo $flight['filename'] ?>" alt="Current Flight Image" >
								<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								<hr>
							</div>		
								<div class="form-group">
									<label for="flight_input" class="required"><?php echo $term_flight_image_choose_file; ?></label>
									<input type="file" class="col-12" id="flight_input" accept=".jpg,.png,.gif"  <?php echo (empty($flight['filename'])) ? 'required' : '' ?>>
									<input type="hidden" id="flight_base64" name="flight_base64">
								</div>
								
								<div id="flight_croppie_wrap" class="mw-100 w-auto mh-100 h-auto not-showing ">
									<div id="flight_croppie" data-banner-width="600" data-banner-height="400"></div>
								</div>
								
								<button class="btn btn-default btn-block not-showing" type="button" id="flight_result"><?php echo $term_flight_image_crop; ?></button>
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
							<button type="submit" name="next" value="home" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_flight; ?></button>
							<button type="submit" name="next" value="again" class="btn btn-md btn-primary font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_flight_add; ?></button>
						</div>
					</div>
				</div>
				
			</form>
			
		</div>
	</div>
</div>

