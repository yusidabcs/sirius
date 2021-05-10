<div class="card mb-4">

    <h5 class="card-header blue-gradient white-text text-center py-4">
        <strong><?php echo $term_pots_heading ?></strong>
    </h5>
	
	<div id="pots" class="card-body px-lg-5 pt-0">
		
		<div id="pots_entries">
						
			<input id="adress_book_pots_default_type" value="<?php echo $pots_default_type ?>" class="not-showing">
			<input id="adress_book_pots_default_country" value="<?php echo $pots_default_country ?>" class="not-showing">
<?php
		foreach( $pots as $key => $value)
		{
?>			
			<div class="card mt-4">
			    <div class="card-body">
	    			
					<div id="pots_entry_<?php echo $key ?>" class="clonedInput_pots">
									
						<!-- type select -->
                        <div class="form-group">
                            <label for="pots_type_<?php echo $key ?>"><?php echo $term_pots_type ?></label>
                            <select id="pots_type_<?php echo $key ?>" class="mdb-select-old mdb-select md-form" name="pots[<?php echo $key ?>][type]">
                                <option value="landline"<?php if($value['type'] == 'landline') echo ' selected="selected"' ?>><?php echo $term_pots_landline ?></option>
                                <option value="mobile"<?php if($value['type'] == 'mobile') echo ' selected="selected"' ?>><?php echo $term_pots_mobile ?></option>
                            </select>
                        </div>
				        		
						<!-- country select -->
                        <div class="form-group">
                            <label for="pots_country_<?php echo $key ?>"><?php echo $term_pots_country ?></label>
                            <select id="pots_country_<?php echo $key ?>" class="mdb-select-old mdb-select md-form" name="pots[<?php echo $key ?>][country]" searchable="Search country..">
                                <?php
                                foreach($countryDialCodes as $countryCode => $dial)
                                {
                                    if($countryCode == $value['country'])
                                    {
                                        echo '<option value="'.$countryCode.'" selected="selected">'.$dial['country'].' (+'.$dial['dialCode'].') </option>';

                                    } else {
                                        echo '<option value="'.$countryCode.'">'.$dial['country'].' (+'.$dial['dialCode'].') </option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
				     
				        <!-- number -->
				        <div class="form-group row mt-0 mx-2">
					        <label for="number_<?php echo $key ?>" class="col-sm-4 col-form-label"><?php echo $term_pots_number ?></label>
					        <div class="col-sm-8">
					            <div class="md-form mt-0">
					              <input id="number_<?php echo $key ?>" name="pots[<?php echo $key ?>][number]" class="form-control" type="text" maxlength="12" value="<?php echo $pots[$key]['number'] ?>">
					            </div>
					        </div>
					    </div>					
									
						<div class="row mt-0 ml-1 mb-2">
							<!-- private enabled bool -->
							<div class="col-xl-4 col-xs-12 mb-2">
								<div class="form-check form-check-inline">
									<input id="private_<?php echo $key ?>" class="form-check-input" name="pots[<?php echo $key ?>][private]" type="checkbox" value="1" <?php if($pots[$key]['private']) { echo 'checked'; } ?>>
									<label for="private_<?php echo $key ?>" class="form-check-label"><?php echo $term_pots_private ?></label>
								</div>
							</div>
							<!-- end private -->
							
							<!-- whatsapp! enabled bool -->
							<div class="col-xl-4 col-xs-12 mb-2">
								<div class="form-check form-check-inline">
									<input id="whatsapp_<?php echo $key ?>" class="form-check-input" name="pots[<?php echo $key ?>][whatsapp]" type="checkbox" value="1" <?php if($pots[$key]['whatsapp']) { echo 'checked'; } ?>> 
									<label for="whatsapp_<?php echo $key ?>" class="form-check-label"><?php echo $term_pots_whatsapp ?></label>
								</div>
							</div>
							<!-- end whatsapp! -->
							
							<!-- viber enabled bool -->
							<div class="col-xl-4 col-xs-12 mb-2">
								<div class="form-check form-check-inline">
									<input id="viber_<?php echo $key ?>" class="form-check-input" name="pots[<?php echo $key ?>][viber]" type="checkbox" value="1" <?php if($pots[$key]['viber']) { echo 'checked'; } ?>> 
									<label for="viber_<?php echo $key ?>" class="form-check-label"><?php echo $term_pots_viber ?></label>
								</div>
							</div>
							<!-- end viber -->
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
			<button class="btn btn-success btn-block clone"><i class="fas fa-plus-square"></i> <?php echo $term_pots_add ?></button>
		</div>
		
	</div>
</div>
				
<!-- POTS Clone Template -->
<dir class="not-showing">

	<div id="pots_entry_template" class="clonedInput_pots">

		<div class="card mt-4">
			<div class="card-body">

				<!-- type select -->
				<div class="form-group">
                    <label for="pots_type_{X}"><?php echo $term_pots_type ?></label>
                    <select id="pots_type_{X}" class="mdb-select md-form" name="pots[{X}][type]">
                        <option value="landline"><?php echo $term_pots_landline ?></option>
                        <option value="mobile" selected="selected"><?php echo $term_pots_mobile ?></option>
                    </select>
		        </div>

				<!-- country select -->				
				<div class="form-group">
                    <label for="pots_country_{X}"><?php echo $term_pots_country ?></label>
                    <select id="pots_country_{X}" class="mdb-select md-form" name="pots[{X}][country]" searchable="Search country..">
                        <?php
                        foreach($countryDialCodes as $countryCode => $value)
                        {
                            echo '<option value="'.$countryCode.'">'.$value['country'].' (+'.$value['dialCode'].') </option>';
                        }
                        ?>
                    </select>
		        </div>
		
				<!-- number -->
		        <div class="form-group row mt-0 mx-1">
			        <label for="number_{X}" class="col-sm-4 col-form-label"><?php echo $term_pots_number ?></label>
			        <div class="col-sm-8">
			            <div class="md-form mt-0">
			              <input id="number_{X}" name="pots[{X}][number]" class="form-control" type="text" maxlength="12" value="">
			            </div>
			        </div>
			    </div>	
				
				<div class="row ml-1 mb-2">
					<!-- private enabled bool -->
					<div class="col-xl-4 co-xs-12 mb-2">
						<div class="form-check form-check-inline">
							<input id="private_{X}" class="form-check-input" name="pots[{X}][private]" type="checkbox" value="1">
							<label for="private_{X}" class="form-check-label"><?php echo $term_pots_private ?></label>
						</div>
					</div>
					
					<!-- whatsapp! enabled bool -->
					<div class="col-xl-4 co-xs-12 mb-2">
						<div class="form-check form-check-inline">
							<input id="whatsapp_{X}" class="form-check-input" name="pots[{X}][whatsapp]" type="checkbox" value="1"> 
							<label for="whatsapp_{X}" class="form-check-label"><?php echo $term_pots_whatsapp ?></label>
						</div>
					</div>
					
					<!-- viber enabled bool -->
					<div class="col-xl-4 co-xs-12 mb-2">
						<div class="form-check form-check-inline">
							<input id="viber_{X}" class="form-check-input" name="pots[{X}][viber]" type="checkbox" value="1"> 
							<label for="viber_{X}" class="form-check-label"><?php echo $term_pots_viber ?></label>
						</div>
					</div>
					
				</div>
					
				<div class="text-right">
					<button class="btn btn-danger remove"><i class="fas fa-trash-alt"></i> <?php echo $term_remove_entry ?></button>
				</div>
			</div>
		</div>
	</div>
</dir>
				