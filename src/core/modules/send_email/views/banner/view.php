<div class="row mt-3">
	<div class="col">
		<div class="card border-dark">
			
			<div class="card-header">
				<h1 class="card-title"><?php echo $term_page_header ?></h1>
			</div>
			
			<div class="card-body">

				<form id="form-address_book" class="form-horizontal" role="form" method="post" action="<?php echo $myURL; ?>" enctype="multipart/form-data">	
					
						<!-- start of address_book common banner -->
						<div id="banner" class="jumbotron">
									
							<fieldset>
								
								<legend><i class="fas fa-image"></i> <?php echo $term_banner_heading ?></legend>
								
								<h3><small><?php echo $term_banner_heading_main ?></small></h3>
<?php
							if($img_src)
							{
?>
								<div class="form-group">
									<label for="banner_existing_image"><?php echo $term_banner_existing_image_label ?></label>
									<img src="<?php echo $img_src; ?>" alt="Existing Banner" width="620" height="100">
								</div>
<?php
							}
?>
								<div class="form-group">
									<label for="banner_input"><?php echo $term_banner_input_label ?></label>
									<input type="file" id="banner_input" accept="banner/*" >
									<input type="hidden" id="banner_base64" name="banner_base64">
								</div>
								
		                        <div id="banner_croppie_wrap">
		                            <div id="banner_croppie"></div>
		                        </div>
		                        
		                        <button class="btn btn-info" type="button" id="banner_result"><?php echo $term_banner_result_button ?></button>
		                    
							</fieldset>
							
						</div>
						<!-- End of Clone Template -->
						
						<!-- start of address_book common banner -->
						<div id="css" class="jumbotron">
									
							<fieldset>
								
								<legend><i class="fab fa-css3"></i> <?php echo $term_css_heading ?></legend>
								
								<h3><small><?php echo $term_css_heading_main ?></small></h3>
								
								<div class="form-group">
									<label for="css_info"><?php echo $term_css_input_label ?></label>
									<textarea rows="20" cols="60" id="css_info" name="css_info"><?php echo $css_info ?></textarea>
								</div>
		                    
							</fieldset>
							
						</div>
						<!-- End of Clone Template -->
	
					<div class="right">
						<hr>	
						<p>
							<button id="add" type="submit" class="btn btn-primary"><?php echo $term_button_add_submit; ?></button>
						</p>
					</div>
				</form>	
			</div>	
		</div>
	</div>
</div>