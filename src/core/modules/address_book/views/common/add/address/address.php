<div class="card mb-4">

    <h5 class="card-header info-color white-text text-center py-3">
        <strong><?php echo $term_address_heading ?></strong>
    </h5>
	
	<div class="card-body pt-0">

		<div class="row">
			<div class="col-12">
				
				<div class="card my-4">

				    <h6 class="card-header success-color white-text text-center">
				        <strong><?php echo $term_address_heading_main ?></strong>
				    </h6>
					
					<div class="card-body px-lg-5 pt-0">
						
						
						<div class="form-group mt-3">
							<label for="address_country_1" class="col-form-label"><?php echo $term_address_country ?></label>
                            <select id="address_country_1" class="md-form mdb-select" name="address[main][country]" searchable="Search country..">
                                <?php
                                foreach($countries as $code => $name)
                                {
                                    if($code == $address['main']['country'])
                                    {
                                        echo '<option value="'.$code.'" selected >'.$name."</option>\n";
                                    } else {
                                        echo '<option value="'.$code.'">'.$name."</option>\n";
                                    }
                                }
                                ?>
                            </select>
						</div>		

<?php
					if(ADDRESS_BOOK_ADDRESS_DETAILS_FIRST_ENTRY == "PHY")
					{	
?>					
						<div class="form-group mt-3">
							<label for="address_physical_pobox_1" class="col-form-label"><?php echo $term_address_physical_pobox ?></label>
                            <select id="address_physical_pobox_1" class="md-form mdb-select disabled" name="address[main][physical_pobox]">
                                <option value="physical" selected ><?php echo $term_address_physical_only; ?></option>
                            </select>
						</div>					
<?php
					} else {
?>
						<div class="form-group mt-3">
							<label for="address_physical_pobox_1" class="col-form-label"><?php echo $term_address_physical_pobox ?></label>
                            <select id="address_physical_pobox_1" class="md-form mdb-select" name="address[main][physical_pobox]">
                                <option value="physical"<?php if($address['main']['physical_pobox'] == 'physical') echo ' selected' ?>><?php echo $term_address_physical ?></option>
                                <option value="pobox"<?php if($address['main']['physical_pobox'] == 'pobox') echo ' selected' ?>><?php echo $term_address_pobox ?></option>
                            </select>
						</div>						
<?php
					}
?>						
						<div class="form-group">
							<label for="address_care_of_1" class="col-form-label"><?php echo $term_address_care_of ?></label>
                            <input id="address_care_of_1" class="form-control" name="address[main][care_of]" type="text" maxlength="255" value="<?php echo $address['main']['care_of'] ?>">
						</div>

						<div class="form-group">
							<label for="address_line_1_1" class="col-form-label physical_1"><?php echo $term_address_line_1_physical ?></label>
							<label for="address_line_1_1" class="col-form-label pobox_1"><?php echo $term_address_line_1_pobox ?></label>
                            <input id="address_line_1_1" class="form-control" name="address[main][line_1]" type="text" maxlength="255" value="<?php echo $address['main']['line_1'] ?>">
						</div>
						
						<div class="form-group physical_1">
							<label for="address_line_2_1" class="col-form-label physical_1"><?php echo $term_address_line_2 ?></label>
                            <input id="address_line_2_1" class="form-control" name="address[main][line_2]" type="text" maxlength="255" value="<?php echo $address['main']['line_2'] ?>">
						</div>

						<div class="form-group">
							<label for="address_suburb_1" class="col-form-label"><?php echo $term_address_suburb ?></label>
                            <input id="address_suburb_1" class="form-control" name="address[main][suburb]" type="text" maxlength="255" value="<?php echo $address['main']['suburb'] ?>">
						</div>

						<div id= "state_1" class="form-group mt-3">
							<label for="address_state_1" class="col-form-label"><?php echo $term_address_state ?></label>
							<div class="col-lg-10">
                                <select id="address_state_1" class="md-form mdb-select" name="address[main][state]" searchable="Search state">
                                    <?php
                                    foreach($countrySubCodes_1 as $code => $name)
                                    {
                                        if($code == $address['main']['state'])
                                        {
                                            echo '<option value="'.$code.'" selected>'.$name."</option>\n";
                                        } else {
                                            echo '<option value="'.$code.'">'.$name."</option>\n";
                                        }
                                    }
                                    ?>
                                </select>
							</div>
						</div>	
						
						<div class="form-group ">
							<label for="address_postcode_1" class="col-form-label"><?php echo $term_address_postcode ?></label>
                            <input id="address_postcode_1" class="form-control" name="address[main][postcode]" type="text" maxlength="15" value="<?php echo $address['main']['postcode'] ?>">
						</div>
						
						<div class="physical_1">
							
							<hr>
							
							<div class="form-group ">
								<label for="address_latitude_1" class="col-form-label"><?php echo $term_address_latitude ?></label>
                                <input id="address_latitude_1" class="form-control" name="address[main][latitude]" type="number" min="-90.000000" max="90.000000" step="0.000001" value="<?php echo $address['main']['latitude'] ?>">
							</div>
							
							<div class="form-group ">
								<label for="address_longitude_1" class="col-form-label"><?php echo $term_address_longitude ?></label>
                                <input id="address_longitude_1" class="form-control" name="address[main][longitude]" type="number" min="-180.000000" max="180.000000" step="0.000001" value="<?php echo $address['main']['longitude'] ?>">
							</div>
							
						</div>
						
					</div>
				</div>
						
<?php
			if(ADDRESS_BOOK_ADDRESS_DETAILS > 1)
			{
?>				
				<div class="card my-4">

				    <h6 class="card-header blue-gradient white-text text-center">
				        <strong><?php echo $term_address_heading_second ?></strong>
				    </h6>
					
					<div class="card-body px-lg-5 pt-0">
						
						<div class="form-check mt-4">
						    <input type="checkbox" class="form-check-input" id="address_same_2" name="address[postal][same]" value="1" <?php if($address['postal']['same'] == 1) echo 'checked' ?>>
						    <label class="form-check-label" for="address_same_2"><?php echo $term_address_same ?></label>
						</div>
					
						<div id="address_entry_2" class="not-showing">
						
							<div class="form-group mt-3">
								<label for="address_country_2" class="col-form-label"><?php echo $term_address_country ?></label>
                                <select id="address_country_2" class="md-form mdb-select" name="address[postal][country]" searchable="Search country..">
                                    <?php
                                    foreach($countries as $code => $name)
                                    {
                                        if($code == $address['postal']['country'])
                                        {
                                            echo '<option value="'.$code.'" selected >'.$name."</option>\n";
                                        } else {
                                            echo '<option value="'.$code.'">'.$name."</option>\n";
                                        }
                                    }
                                    ?>
                                </select>
							</div>		
	
							<div class="form-group  mt-3">
								<label for="address_physical_pobox_2" class="col-form-label"><?php echo $term_address_physical_pobox ?></label>
                                <select id="address_physical_pobox_2" class="md-form mdb-select" name="address[postal][physical_pobox]">
                                    <option value="physical"<?php if($address['postal']['physical_pobox'] == 'physical') echo ' selected' ?>><?php echo $term_address_physical ?></option>
                                    <option value="pobox"<?php if($address['postal']['physical_pobox'] == 'pobox') echo ' selected' ?>><?php echo $term_address_pobox ?></option>
                                </select>
							</div>
											
							<div class="form-group ">
								<label for="address_care_of_2" class="col-form-label"><?php echo $term_address_care_of ?></label>
                                <input id="address_care_of_2" class="form-control" name="address[postal][care_of]" type="text" maxlength="255" value="<?php echo $address['postal']['care_of'] ?>">
							</div>
	
							<div class="form-group ">
								<label for="address_line_1_2" class="col-form-label physical_2"><?php echo $term_address_line_1_physical ?></label>
								<label for="address_line_1_2" class="col-form-label pobox_2"><?php echo $term_address_line_1_pobox ?></label>
                                <input id="address_line_1_2" class="form-control" name="address[postal][line_1]" type="text" maxlength="255" value="<?php echo $address['postal']['line_1'] ?>">
							</div>
							
							<div class="form-group  physical_2">
								<label for="address_line_2_2" class="col-form-label physical_2"><?php echo $term_address_line_2 ?></label>
                                <input id="address_line_2_2" class="form-control" name="address[postal][line_2]" type="text" maxlength="255" value="<?php echo $address['postal']['line_2'] ?>">
							</div>
	
							<div class="form-group ">
								<label for="address_suburb_2" class="col-form-label"><?php echo $term_address_suburb ?></label>
                                <input id="address_suburb_2" class="form-control" name="address[postal][suburb]" type="text" maxlength="255" value="<?php echo $address['postal']['suburb'] ?>">
							</div>
	
							<div id= "state_2" class="form-group  mt-3">
								<label for="address_state_2" class="col-form-label"><?php echo $term_address_state ?></label>
                                <select id="address_state_2" class="md-form mdb-select" name="address[postal][state]" searchable="Search country..">
                                    <?php
                                    foreach($countrySubCodes_2 as $code => $name)
                                    {
                                        if($code == $address['postal']['state'])
                                        {
                                            echo '<option value="'.$code.'" selected>'.$name."</option>\n";
                                        } else {
                                            echo '<option value="'.$code.'">'.$name."</option>\n";
                                        }
                                    }
                                    ?>
                                </select>
							</div>	
							
							<div class="form-group ">
								<label for="address_postcode_2" class="col-form-label"><?php echo $term_address_postcode ?></label>
                                <input id="address_postcode_2" class="form-control" name="address[postal][postcode]" type="text" maxlength="15" value="<?php echo $address['postal']['postcode'] ?>">
							</div>
							
							<div class="physical_2">
								
								<hr>
								
								<div class="form-group ">
									<label for="address_latitude_2" class="col-form-label physical_2"><?php echo $term_address_latitude ?></label>
                                    <input id="address_latitude_2" class="form-control" name="address[postal][latitude]" type="number" min="-90.000000" max="90.000000" step="0.000001" value="<?php echo $address['postal']['latitude'] ?>">
								</div>
								
								<div class="form-group ">
									<label for="address_longitude_2" class="col-form-label physical_2"><?php echo $term_address_longitude ?></label>
                                    <input id="address_longitude_2" class="form-control" name="address[postal][longitude]" type="number" min="-180.000000" max="180.000000" step="0.000001" value="<?php echo $address['postal']['longitude'] ?>">
								</div>
								
							</div>
					
						</div>
						
					</div>
				</div>
<?php
			}
?>		
			</div>
		</div>
	</div>
</div>
			