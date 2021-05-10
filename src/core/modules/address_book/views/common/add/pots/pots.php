<div class="card mb-4">

    <h5 class="card-header peach-gradient white-text text-center py-3">
        <strong><?php echo $term_pots_heading ?></strong>
    </h5>
	
	<div class="card-body px-lg-5 pt-0">
		<div class="row">
			<div class="col-12">
					
				<div id="pots">
					
					<input id="adress_book_pots_default_type" value="<?php echo $pots_default_type ?>" class="not-showing">
					<input id="adress_book_pots_default_country" value="<?php echo $pots_default_country ?>" class="not-showing">
					
					<div id="pots_entries">
	<?php
					foreach( $pots as $key => $value)
					{
	?>						
						<div id="pots_entry_<?php echo $key ?>" class="card mt-3 clonedInput_pots">
							
							<div class="card-body m-2">
								
								<!-- type select : mobile or landline -->
								<div class="form-group ">
									<label for="pots_type_<?php echo $key ?>" class="col-form-label"><?php echo $term_pots_type ?></label>
                                    <select id="pots_type_<?php echo $key ?>" class="md-form mdb-select" name="pots[<?php echo $key ?>][type]">
                                        <option value="landline" <?php if($value['type'] == 'landline') echo 'selected' ?>><?php echo $term_pots_landline ?></option>
                                        <option value="mobile" <?php if($value['type'] == 'mobile') echo 'selected' ?>><?php echo $term_pots_mobile ?></option>
                                    </select>
								</div>		
								<!-- end type -->
								
								<!-- country select -->
								<div class="form-group">
									<label for="pots_country_<?php echo $key ?>" class="col-form-label"><?php echo $term_pots_country ?></label>
                                    <select id="pots_country_<?php echo $key ?>" class="md-form mdb-select" name="pots[<?php echo $key ?>][country]" searchable="Search country..">
                                        <?php
                                        foreach($countryDialCodes as $countryCode => $value)
                                        {
                                            if($countryCode == $value['country'])
                                            {
                                                echo '<option value="'.$countryCode.'" selected>'.$dial['country'].' (+'.$dial['dialCode'].') </option>';

                                            } else {
                                                echo '<option value="'.$countryCode.'">'.$dial['country'].' (+'.$dial['dialCode'].') </option>';
                                            }
                                        }
                                        ?>
                                    </select>
								</div>
								<!-- end country -->
								
								<!-- number input -->
								<div class="form-group">
									<label for="pots_number_<?php echo $key ?>" class="col-form-label"><?php echo $term_pots_number ?></label>
                                    <input id="pots_number_<?php echo $key ?>" class="form-control" name="pots[<?php echo $key ?>][number]" type="text" maxlength="12" value="<?php echo $pots[$key]['number'] ?>">
								</div>
								<!-- end number -->
								
								<div class="row ml-2">
									<!-- private enabled bool -->
									<div class="form-check form-check-inline">
									    <input type="checkbox" class="form-check-input" id="pots_private_<?php echo $key ?>" name="pots[<?php echo $key ?>][private]" value="1" <?php if($pots[$key]['private']) { echo 'checked'; } ?>>
									    <label class="form-check-label" for="pots_private_<?php echo $key ?>"><?php echo $term_pots_private ?></label>
									</div>
									<!-- end private -->
									
									<!-- whatsapp! enabled bool -->
									<div class="form-check form-check-inline">
									    <input type="checkbox" class="form-check-input" id="pots_whatsapp_<?php echo $key ?>" name="pots[<?php echo $key ?>][whatsapp]" value="1" <?php if($pots[$key]['whatsapp']) { echo 'checked'; } ?>>
									    <label class="form-check-label" for="pots_whatsapp_<?php echo $key ?>"><?php echo $term_pots_whatsapp ?></label>
									</div>
									<!-- end whatsapp! -->
									
									<!-- viber enabled bool -->
									<div class="form-check form-check-inline">
									    <input type="checkbox" class="form-check-input" id="pots_viber_<?php echo $key ?>" name="pots[<?php echo $key ?>][viber]" value="1" <?php if($pots[$key]['viber']) { echo 'checked'; } ?>>
									    <label class="form-check-label" for="pots_viberp_<?php echo $key ?>"><?php echo $term_pots_viber ?></label>
									</div>
									<!-- end viber -->
								</div>
								
								<div class="row mx-2 mt-3">
									<button class="btn btn-danger btn-block remove"><?php echo $term_remove_entry ?></button>
								</div>
						
							</div>
						</div>
<?php						
					}
?>	    		
					</div>
						
					<button class="btn btn-info btn-block mt-3 clone"><?php echo $term_pots_add ?></button>
							
				</div>
								
				<!-- POTS Clone Template -->
				<div class="not-showing">
					
					<div id="pots_entry_template" class="card mt-3 clonedInput_pots">
						
						<div class="card-body m-2">
							
							<!-- type select : mobile or landline -->
							<div class="form-group row">
								<label for="pots_type_{X}" class="col-lg-2 col-form-label"><?php echo $term_pots_type ?></label>
								<div class="col-lg-10">
									<div class="md-form mt-0">
										<select id="pots_type_{X}" class="md-form mdb-select" name="pots[{X}][type]">
											<option value="landline"><?php echo $term_pots_landline ?></option>
											<option value="mobile"><?php echo $term_pots_mobile ?></option>
										</select>
									</div>
								</div>
							</div>		
							<!-- end type -->
							
							<!-- country select -->
							<div class="form-group row">
								<label for="pots_country_{X}" class="col-lg-2 col-form-label"><?php echo $term_pots_country ?></label>
								<div class="col-lg-10">
									<div class="md-form mt-0">
										<select id="pots_country_{X}" class="md-form mdb-select" name="pots[{X}][country]" searchable="Search country..">>
<?php
										foreach($countryDialCodes as $countryCode => $value)
										{
											echo '<option value="'.$countryCode.'">'.$value['country'].' (+'.$value['dialCode'].') </option>';
										}
?>
										</select>
									</div>
								</div>
							</div>
							<!-- end country -->
							
							<!-- number input -->
							<div class="form-group row">
								<label for="pots_number_{X}" class="col-lg-2 col-form-label"><?php echo $term_pots_number ?></label>
								<div class="col-lg-10">
									<div class="md-form mt-0">
										<input id="pots_number_{X}" class="form-control" name="pots[{X}][number]" type="text" maxlength="12" value="">
									</div>
								</div>
							</div>
							<!-- end number -->
							
							<div class="row ml-2">
								<!-- private enabled bool -->
								<div class="form-check form-check-inline">
								    <input type="checkbox" class="form-check-input" id="pots_private_{X}" name="pots[{X}][private]" value="1">
								    <label class="form-check-label" for="pots_private_{X}"><?php echo $term_pots_private ?></label>
								</div>
								<!-- end private -->
								
								<!-- whatsapp! enabled bool -->
								<div class="form-check form-check-inline">
								    <input type="checkbox" class="form-check-input" id="pots_whatsapp_{X}" name="pots[{X}][whatsapp]" value="1">
								    <label class="form-check-label" for="pots_whatsapp_{X}"><?php echo $term_pots_whatsapp ?></label>
								</div>
								<!-- end whatsapp! -->
								
								<!-- viber enabled bool -->
								<div class="form-check form-check-inline">
								    <input type="checkbox" class="form-check-input" id="pots_viber_{X}" name="pots[{X}][viber]" value="1">
								    <label class="form-check-label" for="pots_viberp_{X}"><?php echo $term_pots_viber ?></label>
								</div>
								<!-- end viber -->
							</div>
							
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