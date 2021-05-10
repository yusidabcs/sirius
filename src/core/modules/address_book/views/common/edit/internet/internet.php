<div class="card mb-4">

    <h5 class="card-header blue-gradient white-text text-center py-4">
        <strong><?php echo $term_internet_heading ?></strong>
    </h5>
	
	<div id="internet" class="card-body px-lg-5 pt-0">
	
		<div id="internet_entries">
												
			<input id="adress_book_internet_default_type" value="<?php echo $internet_default_type ?>" class="not-showing">
		
<?php
		foreach( $internet as $key => $value)
		{
?>						
			<div id="internet_entry_<?php echo $key ?>" class="clonedInput_internet">
				
				<div class="card mt-4">
					<div class="card-body">
							
						<!-- type select : mobile or landline -->
						<div class="md-form">
                            <label for="internet_type_<?php echo $key ?>"><?php echo $term_internet_type ?></label>
                            <select id="internet_type_<?php echo $key ?>" class="internet_type_select mdb-select md-form"  name="internet[<?php echo $key ?>][type]">
                                <option value="facebook"<?php if($value['type'] == 'facebook') echo ' selected="selected"' ?>><?php echo $term_internet_facebook ?></option>
                                <option value="google-plus"<?php if($value['type'] == 'google-plus') echo ' selected="selected"' ?>><?php echo $term_internet_google_plus ?></option>
                                <option value="instagram"<?php if($value['type'] == 'instagram') echo ' selected="selected"' ?>><?php echo $term_internet_instagram ?></option>
                                <option value="linked-in"<?php if($value['type'] == 'linked-in') echo ' selected="selected"' ?>><?php echo $term_internet_linked_in ?></option>
                                <option value="skype"<?php if($value['type'] == 'skype') echo ' selected="selected"' ?>><?php echo $term_internet_skype ?></option>
                                <option value="twitter"<?php if($value['type'] == 'twitter') echo ' selected="selected"' ?>><?php echo $term_internet_twitter ?></option>
                                <option value="youtube-channel"<?php if($value['type'] == 'youtube-channel') echo ' selected="selected"' ?>><?php echo $term_internet_youtube_channel ?></option>
                                <option value="youtube-video"<?php if($value['type'] == 'youtube-video') echo ' selected="selected"' ?>><?php echo $term_internet_youtube_video ?></option>
                            </select>
				        </div>

						<!-- number or id -->
                        <div class="md-form mt-4">
                            <label for="internet_id_<?php echo $key ?>"><?php echo $term_internet_id ?></label>
                            <input id="internet_id_<?php echo $key ?>" class="form-control" name="internet[<?php echo $key ?>][id]" type="text" maxlength="255" value="<?php echo $internet[$key]['id'] ?>">
                        </div>
				        
				        <div class="text-right">
					        <button class="btn btn-danger remove"><i class="fas fa-trash-alt"></i> <?php echo $term_remove_entry ?></button>
					    </div>
				
					</div>
					
				</div>
			</div>
<?php						
		}
?>	    
		</div>
		
		<div class="mt-4">
			<button class="btn btn-success btn-block clone"><i class="fas fa-plus-square"></i> <?php echo $term_internet_add ?></button>
		</div>
	</div>
</div>
<!-- end of address_book common internet -->
				
				
<!-- Internet Clone Template -->
<dir class="not-showing">
	<div id="internet_entry_template" class="clonedInput_internet">
		
		<div class="card mt-4">
			<div class="card-body">

				<!-- type select : mobile or landline -->
				<div class="md-form">
                    <label for="internet_type_{X}"><?php echo $term_internet_type ?></label>
                    <select id="internet_type_{X}" class="mdb-select md-form" name="internet[{X}][type]">
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
		        
		        <!-- number or id -->
                <div class="md-form mt-4">
                    <label for="internet_id_{X}"><?php echo $term_internet_id ?></label>
                    <input id="internet_id_{X}" class="form-control" name="internet[{X}][id]" type="text" maxlength="255" value="">
                </div>

				<div class="text-right">
					<button class="btn btn-danger remove"><i class="fas fa-trash-alt"></i> <?php echo $term_remove_entry ?></button>
				</div>
				
			</div>
		</div>
	</div>
</dir>	
<!-- End of Clone Template -->
				
				