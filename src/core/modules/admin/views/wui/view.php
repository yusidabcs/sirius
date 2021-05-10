<div class="card">
	
	<div class="card-header gradient-card-header blue-gradient">
		<h4 class="text-white text-center"><?php echo $term_page_header ?></h4>
	</div>
	
	<div class="card-body">
		
		<form method="post" action="<?php echo $post; ?>">
		
			<nav>
				
				<div class="nav nav-pills justify-content-center" role="tablist">
					
					<a class="nav-item nav-link active" id="nav-navConfig-tab" data-toggle="tab" href="#navConfig" role="tab" aria-controls="nav-home" aria-selected="true"><?php echo $term_tab_nav_config ?></a>
					<a class="nav-item nav-link" id="nav-linkConfig-tab" data-toggle="tab" href="#linkConfig" role="tab" aria-controls="nav-link" aria-selected="false"><?php echo $term_tab_link_config ?></a>
	
				</div>
				
			</nav>
					
			<div class="tab-content">
				
				<div class="tab-pane fade show active" id="navConfig" role="tabpanel" aria-labelledby="nav-systemConfig-tab">
				    
					<div class="card">
		
						<div class="card-header peach-gradient text-white text-center">
							
						  <h5><?php echo $term_heading_nav_config ?></h5>
						  
						</div>
						
						<div class="card-body">
						
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input type="checkbox" class="form-check-input" id="nav_useTopNav" name="useTopNav" value="1" <?php if($nav['useTopNav']) echo 'checked' ?>>
										<label class="form-check-label" for="nav_useTopNav"><?php echo $term_label_nav_useTopNav ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input type="checkbox" class="form-check-input" id="nav_useBottomNav" name="useBottomNav" value="1" <?php if($nav['useBottomNav']) echo 'checked' ?>>
										<label class="form-check-label" for="nav_useBottomNav"><?php echo $term_label_nav_useBottomNav ?></label>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="nav_topNavBar" name="topNavBar" class="form-control" type="text" value="<?php echo $nav['topNavBar']; ?>" />
										<label for="nav_topNavBar"><?php echo $term_label_nav_topNavBar ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input id="nav_bottomNavBar" name="bottomNavBar" class="form-control" type="text" value="<?php echo $nav['bottomNavBar']; ?>" />
										<label for="nav_bottomNavBar"><?php echo $term_label_nav_bottomNavBar ?></label>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="nav_mainColour" name="mainColour" class="form-control" type="text" value="<?php echo $nav['mainColour']; ?>" />
										<label for="nav_mainColour"><?php echo $term_label_nav_mainColour ?></label>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
				
				<div class="tab-pane fade" id="linkConfig" role="tabpanel" aria-labelledby="nav-siteConfig-tab">
					
					<div class="card">
		
						<div class="card-header peach-gradient text-white text-center">
							
						  <h5><?php echo $term_heading_link_config ?></h5>
						  
						</div>
						
						<div class="card-body">
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input type="checkbox" class="form-check-input" id="link_useInTopNav" name="useInTopNav" value="1" <?php if($link['useInTopNav']) echo 'checked' ?>>
										<label class="form-check-label" for="link_useInTopNav"><?php echo $term_label_nav_useInTopNav ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input type="checkbox" class="form-check-input" id="link_useInBottomNav" name="useInBottomNav" value="1" <?php if($link['useInBottomNav']) echo 'checked' ?>>
										<label class="form-check-label" for="link_useInBottomNav"><?php echo $term_label_nav_useInBottomNav ?></label>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="link_facebook" name="facebook" class="form-control" type="text" value="<?php echo $link['facebook']; ?>" />
										<label for="link_facebook"><?php echo $term_label_link_facebook ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input id="link_twitter" name="twitter" class="form-control" type="text" value="<?php echo $link['twitter']; ?>" />
										<label for="link_twitter"><?php echo $term_label_link_twitter ?></label>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="link_instagram" name="instagram" class="form-control" type="text" value="<?php echo $link['instagram']; ?>" />
										<label for="link_instagram"><?php echo $term_label_link_instagram ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input id="link_youtube" name="youtube" class="form-control" type="text" value="<?php echo $link['youtube']; ?>" />
										<label for="link_youtube"><?php echo $term_label_link_youtube ?></label>
									</div>
								</div>
							</div>
								
						</div>
					</div>
				</div>
				
			</div>
		
			<button class="btn btn-default btn-block" type="submit" name="action" value="update_site_interface" ><?php echo $term_submit_update ?></button>
			
		</form>
							
	</div>
</div>
