<div class="card mb-4">

    <h5 class="card-header peach-gradient white-text text-center py-3">
        <strong><?php echo $term_avatar_heading ?></strong>
    </h5>
	
	<div class="card-body px-lg-5 pt-0">
		<div class="row">
			<div class="col-12">

				<div class="form-group mt-3">
					<input type="file" id="avatar_input" accept=".jpg,.png,.gif" >
					<input type="hidden" id="avatar_base64" name="avatar[avatar_base64]">
				</div>
				
                <div id="avatar_croppie_wrap">
                    <div id="avatar_croppie"></div>
                </div>
                
                <div class="mt-5">
                	<button class="btn btn-default btn-block" type="button" id="avatar_result">Crop It</button>
                </div>
                
			</div>
		</div>
	</div>
</div>	