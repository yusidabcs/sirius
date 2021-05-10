<div class="card mb-4">

    <h5 class="card-header peach-gradient white-text text-center py-3">
        <strong><?php echo $term_internet_heading ?></strong>
    </h5>
	
	<div class="card-body px-lg-5 pt-0">
		<div class="row">
			<div class="col-12">
					
				<div id="internet">
					
					<input id="adress_book_internet_default_type" value="<?php echo $internet_default_type ?>" class="not-showing">
					
					<div id="internet_entries">
<?php
					foreach( $internet as $key => $value)
					{
?>						
						<div id="internet_entry_<?php echo $key ?>" class="card mt-3 clonedInput_internet">

							<div class="card-body m-2">
								
								<!-- type select : mobile or landline -->
								<div class="form-group">
									<label for="internet_type_<?php echo $key ?>" class="col-form-label"><?php echo $term_internet_type ?></label>
                                    <select id="internet_type_<?php echo $key ?>" class="form-control" name="internet[<?php echo $key ?>][type]" searchable="Search">
                                        <option value="facebook"<?php if($value['type'] == 'facebook') echo ' selected' ?>><?php echo $term_internet_facebook ?></option>
                                        <option value="google-plus"<?php if($value['type'] == 'google-plus') echo ' selected' ?>><?php echo $term_internet_google_plus ?></option>
                                        <option value="instagram"<?php if($value['type'] == 'instagram') echo ' selected' ?>><?php echo $term_internet_instagram ?></option>
                                        <option value="linked-in"<?php if($value['type'] == 'linked-in') echo ' selected' ?>><?php echo $term_internet_linked_in ?></option>
                                        <option value="skype"<?php if($value['type'] == 'skype') echo ' selected' ?>><?php echo $term_internet_skype ?></option>
                                        <option value="twitter"<?php if($value['type'] == 'twitter') echo ' selected' ?>><?php echo $term_internet_twitter ?></option>
                                        <option value="youtube-channel"<?php if($value['type'] == 'youtube-channel') echo ' selected' ?>><?php echo $term_internet_youtube_channel ?></option>
                                        <option value="youtube-video"<?php if($value['type'] == 'youtube-video') echo ' selected' ?>><?php echo $term_internet_youtube_video ?></option>
                                    </select>
								</div>		
								<!-- end type -->
							
							
								<!--id input -->
								<div class="form-group row">
									<label for="internet_id_<?php echo $key ?>" class="col-lg-2 col-form-label"><?php echo $term_internet_id ?></label>
									<div class="col-lg-10">
										<div class="md-form mt-0">
											<input id="internet_id_<?php echo $key ?>" class="form-control" name="internet[<?php echo $key ?>][id]" type="text" maxlength="255" value="<?php echo $internet[$key]['id'] ?>">
										</div>
									</div>
								</div>
								<!-- end id -->
									
								<div class="row mx-2 mt-3">
									<button class="btn btn-danger btn-block remove"><?php echo $term_remove_entry ?></button>
								</div>
								
							</div>
						</div>
<?php						
					}
?>	    
					</div>
					
					<button class="btn btn-info btn-block mt-3 clone"><?php echo $term_internet_add ?></button>
					
				</div>
				
				<!-- Internet Clone Template -->
				<div class="not-showing">
					<div id="internet_entry_template" class="card mt-3 clonedInput_internet">
						<div class="card-body m-2">
							
							<!-- type select : mobile or landline -->
							<div class="form-group">
								<label for="internet_type_{X}" class="col-form-label"><?php echo $term_internet_type ?></label>
                                <select id="internet_type_{X}" class="md-form mdb-select" name="internet[{X}][type]" searchable="Search">
                                    <option value="facebook"><?php echo $term_internet_facebook ?></option>
                                    <option value="google-plus"><?php echo $term_internet_google_plus ?></option>
                                    <option value="instagram"><?php echo $term_internet_instagram ?></option>
                                    <option value="linked-in"><?php echo $term_internet_linked_in ?></option>
                                    <option value="skype"><?php echo $term_internet_skype ?></option>
                                    <option value="twitter"><?php echo $term_internet_twitter ?></option>
                                    <option value="youtube-channel"><?php echo $term_internet_youtube_channel ?></option>
                                    <option value="youtube-video"><?php echo $term_internet_youtube_video ?></option>
                                </select>
							</div>		
							<!-- end type -->

							<!--id input -->
							<div class="form-group">
								<label for="internet_id_{X}" class="col-form-label"><?php echo $term_internet_id ?></label>
                                <input id="internet_id_{X}" class="form-control" name="internet[{X}][id]" type="text" maxlength="255" value="">
							</div>
							<!-- end id -->
								
							<div class="row mx-2 mt-3">
								<button class="btn btn-danger btn-block remove"><?php echo $term_remove_entry ?></button>
							</div>
							
						</div>
					</div>
				</div>
				<!-- End of Clone Template -->
				
			</div>
		</div>
	</div>
</div>