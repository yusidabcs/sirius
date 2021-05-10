<div class="card mb-4">

    <h5 class="card-header blue-gradient white-text text-center py-4">
        <strong><?php echo $term_avatar_heading ?></strong>
    </h5>
	
	<div class="card-body px-lg-5 pt-0 text-center">
				
<?php
	$class = "not-showing";
	if(!empty($avatar)) {
		$class = '';
?>
			<input type="hidden" id="avatar_current" name="avatar[current]" value="<?php echo $avatar[0]['filename'] ?>">
<?php
	} 
?>			
		<div class="mt-2" id="avatar_image">
			<img src="/ab/show/<?php echo $avatar[0]['filename'] ?>" id="img_show" alt="Current Avatar" class="<?php echo $class;?>">
			<button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
		</div>			
		<div class="form-group mt-3">
			<input type="file" id="avatar_input" accept=".jpg,.png,.gif" >
			<input type="hidden" id="avatar_base64" name="avatar[avatar_base64]">
		</div>
		
        <div id="avatar_croppie_wrap">
            <div id="avatar_croppie"></div>
        </div>
        
        <div class="mt-2">
        	<button class="btn btn-success btn-block not-showing" type="button" id="avatar_result"><i class="fas fa-crop-alt"></i> Crop It</button>
        </div>
				
	</div>

</div>
