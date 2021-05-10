<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		
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
			    <?php echo $term_tattoo_panel_title; ?> <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
		    </h3>
			
			<form method="post" id="tattoo">
				
				<div class="card-body">
					
					<!-- main tattoo info -->
					<div class="card mb-4">

						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-info-circle"></i> <?php echo $term_tattoo_type_heading ?>
						</h4>
						
						<div class="card-body table-responsive">
							
							<table class="table table-bordered table-sm table-responsive-sm">
								
								<tr>
									<th width="20%" class="right align-middle"><?php echo $term_tattoo_info_location; ?></th>
									<td colspan="2" class="text-center">
										
										<a href="/core/images/tattoo_location.jpg" data-toggle="lightbox" data-gallery="" data-footer="<?php echo $term_tattoo_location_caption;?>" data-type="image">
											<figure class="figure">
												<img src="/core/images/tattoo_location.jpg"  class="img-fluid z-depth-1" title="<?php echo $term_tattoo_location_caption;?>" alt="<?php echo $term_tattoo_location_caption; ?>">
												<figcaption class="figure-caption text-center mt-2">
												    <?php echo $term_tattoo_location_caption." - Click to Enlarge"; ?>
												</figcaption>
											</figure>
										</a>

									</td>
								</tr>
								
								<tr>
									<th width="20%" class="right align-middle"><?php echo $term_tattoo_other_info_label; ?></th>
									<td colspan="2">
										<?php echo $term_tattoo_other_info_text; ?>
									</td>
								</tr>
								
							</table>
							
							<table class="table table-bordered table-sm table-responsive-sm">
								
								<tr>
									<th width="20%" class="right align-middle required"><?php echo $term_tattoo_location; ?></th>
									<td colspan="2">
										<div class="form-group">
											<select id="location" name="location" class="mdb-select md-form" searchable="Search" required>
												<option value=""><?php echo $term_tattoo_location_select_please; ?></option>
<?php
												foreach($locationArray as $location)
												{
?>											
													<option value="<?php echo $location; ?>" <?php if($tattoo['location'] == $location) echo 'selected="Selected"'; ?>><?php echo ucfirst($location); ?></option>
<?php
												}
?>
											</select>
										</div>
									</td>
								</tr>
								
								<tr class="other_selected">
									<th width="20%" class="right align-middle required"><?php echo $term_tattoo_short_description; ?></th>
									<td colspan="2">
										<input type="text" class="form-control" id="short_description" name="short_description" value="<?php echo $tattoo['short_description']; ?>" required>
										<input type="hidden" name="tattoo_id" value="<?php echo $tattoo['tattoo_id']; ?>"
									</td>
								</tr>
								
								<tr class="other_selected">
									<th class="right align-middle required">
										<?php echo $term_tattoo_concealable; ?>
										<input type="hidden" id="concealable_current" name="concealable_current" value="<?php echo $tattoo['concealable']; ?>">
									</th>
									<td width="40%">
										<div class="iow-ck-button">
									        <label>
										        <input type="radio" class="concealable" id="concealable" name="concealable" value="yes" hidden="hidden">
										        <span class="tattoo"><?php echo $term_tattoo_concealable_yes; ?></span>
									        </label>
								        </div>
									</td>
									<td width="40%">
										<div class="iow-ck-button">
									        <label>
										        <input type="radio" class="concealable" id="not_concealable" name="concealable" value="no" hidden="hidden">
										        <span class="tattoo"><?php echo $term_tattoo_concealable_no; ?></span>
									        </label>
								        </div>
									</td>
								</tr>
																
							</table>
							
						</div>
						
					</div>			
					
					<!-- start tattoo image -->
					<div id="tattoo_image" class="card col-sm-12 col-md-12 m-0 p-0 ">
						
						<h4 class="card-header amy-crisp-gradient white-text text-center py-4">
							<i class="fas fa-image"></i> <?php echo $term_tattoo_image_heading ?>
						</h4>
						
						<div class="card-body">
							<div class="container">				
								<input type="hidden" id="tattoo_base64" name="tattoo_base64">							
	<?php
							$class = 'not-showing';
							if(!empty($tattoo['filename'])) 
							{
								$class='';
	?>										
								<!-- tattoo image if any -->
								<div class="text-center">
									<img id="curr_img" class="img-fluid" src="/ab/show/<?php echo $tattoo['filename'] ?>" alt="Current Tattoo Image" >
									<input type="hidden" id="tattoo_current" name="tattoo_current" value="<?php echo $tattoo['filename'] ?>">
								</div>
	<?php
							}
	?>
							<div class="text-center <?php echo $class;?>" id="d_curr_img">
								<img id="curr_img" class="img-fluid" src="/ab/show/<?php echo $tattoo['filename'] ?>" alt="Current Tattoo Image" >
								<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								<hr>
							</div>

								<ul class="nav nav-tabs md-pills pills-unique nav-fill" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="portrait-tab" data-toggle="tab" href="#portrait" role="tab" aria-controls="portrait" aria-selected="true">
											<i class="far fa-file-image"> </i> <?php echo $term_tattoo_tab_portrait ?>
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="landscape-tab" data-toggle="tab" href="#landscape" role="tab" aria-controls="landscape" aria-selected="true">
											<i class="far fa-image"></i> <?php echo $term_tattoo_tab_landscape ?>
										</a>
									</li>
								</ul>
								<hr>

								<div class="tab-content">
									
									<div id="portrait" class="tab-pane fade show active">
							
										<div class="form-group">
											<label for="tattoo_input_portrait" class="required"><?php echo $term_tattoo_image_choose_file; ?></label>
											<input type="file" class="col-12" id="tattoo_input_portrait" accept=".jpg,.png,.gif" >
										</div>
										
										<div id="tattoo_croppie_wrap_portrait" class="mw-100 w-auto mh-100 h-auto">
											<div id="tattoo_croppie_portrait" data-banner-width="500" data-banner-height="700"></div>
										</div>
										
										<button class="btn btn-default btn-block not-showing" type="button" id="tattoo_result_portrait"><?php echo $term_tattoo_image_crop; ?></button>
			
									</div>

									<div id="landscape" class="tab-pane fade">
		
										<div class="form-group">
											<label for="tattoo_input_landscape" class="required"><?php echo $term_tattoo_image_choose_file; ?></label>
											<input type="file" class="col-12" id="tattoo_input_landscape" accept=".jpg,.png,.gif" >
										</div>
										
										<div id="tattoo_croppie_wrap_landscape" class="mw-100 w-auto mh-100 h-auto">
											<div id="tattoo_croppie_landscape" data-banner-width="700" data-banner-height="500"></div>
										</div>
										
										<button class="btn btn-default btn-block not-showing" type="button" id="tattoo_result_landscape"><?php echo $term_tattoo_image_crop; ?></button>
				
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
							<button type="submit" name="next" value="home" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_tattoo; ?></button>
							<button type="submit" name="next" value="again" class="btn btn-md btn-primary font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_tattoo_add; ?></button>
						</div>
					</div>
					
				</div>
			</form>
			
		</div>
	</div>
</div>

<!-- end of personal tattoo -->
