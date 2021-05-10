<div class="card mb-4">

    <h5 class="card-header blue-gradient white-text text-center py-4">
        <strong><?php echo $term_address_heading ?></strong>
    </h5>
	
	<div class="card-body px-lg-5 pt-0">
		
		<h3 class="card-title mt-4"><?php echo $term_address_heading_main ?></h3>
		
		<!-- Country Row -->
        <div class="form-group">
            <label for="address_country_1"><?php echo $term_address_country ?><span class="required"></span></label>
            <select id="address_country_1" class="mdb-select md-form" name="address[main][country]" searchable="<?php echo $term_address_country_search ?>">
                <?php
                foreach($countries as $code => $name)
                {
                    if($code == $address['main']['country'])
                    {
                        echo '<option value="'.$code.'" selected="selected">'.$name."</option>\n";
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
		<!-- Entry Type - Physical -->
		<div class="form-group">
            <label for="address_physical_pobox_1"><?php echo $term_address_physical_pobox ?></label>
            <select id="address_physical_pobox_1" class="mdb-select md-form" name="address[main][physical_pobox]">
                <option value="physical" selected="selected"><?php echo $term_address_physical_only; ?></option>
            </select>
        </div>
<?php
	} else {
?>
		<!-- Entry Type - Any -->
		<div class="form-group">
            <label for="address_physical_pobox_1"><?php echo $term_address_physical_pobox ?></label><label for="address_physical_pobox_1"><?php echo $term_address_physical_pobox ?></label>
            <select id="address_physical_pobox_1" class="mdb-select md-form" name="address[main][physical_pobox]">
                <option value="physical"<?php if($address['main']['physical_pobox'] == 'physical') echo ' selected="selected"' ?>><?php echo $term_address_physical ?></option>
                <option value="pobox"<?php if($address['main']['physical_pobox'] == 'pobox') echo ' selected="selected"' ?>><?php echo $term_address_pobox ?></option>
            </select>
        </div>
<?php
	}
?>
		<!-- Care Of -->
        <div class="md-form mt-4">
            <label for="address_care_of_1"><?php echo $term_address_care_of ?></label>
            <input id="address_care_of_1" class="form-control" name="address[main][care_of]" type="text" maxlength="255" value="<?php echo $address['main']['care_of'] ?>">
        </div>
		
		<!-- Line 1 -->
        <div class="md-form mt-4">
            <label for="address_line_1_1" class="physical_1" ><?php echo $term_address_line_1_physical ?> <span class="required"></span></label>
            <label for="address_line_1_1" class="pobox_1" ><?php echo $term_address_line_1_pobox ?></label>
            <input id="address_line_1_1" class="form-control" name="address[main][line_1]" type="text" maxlength="255" value="<?php echo $address['main']['line_1'] ?>">
        </div>
		
		<!-- Line 2 -->
        <div class="md-form mt-4">
            <label for="address_line_2_1" ><?php echo $term_address_line_2 ?></label>
            <input id="address_line_2_1" class="form-control" name="address[main][line_2]" type="text" maxlength="255" value="<?php echo $address['main']['line_2'] ?>">
        </div>
        
        <!-- Suburb -->
        <div class="md-form mt-4">
            <label for="address_suburb_1" ><?php echo $term_address_suburb ?><span class="required"></span></label>
            <input id="address_suburb_1" class="form-control" name="address[main][suburb]" type="text" maxlength="255" value="<?php echo $address['main']['suburb'] ?>">
        </div>
						
						
		<!-- State 1 Row -->
        <div id="state_1" class="form-group">
            <label for="address_state_1"><?php echo $term_address_state ?><span class="required"></span></label>
            <select searchable="Search.." id="address_state_1" class="mdb-select md-form" name="address[main][state]" searchable="<?php echo $term_address_state_search ?>">
                <option value=""><?php echo $term_address_state_default ?></option>
                <?php
                foreach($countrySubCodes_1 as $code => $name)
                {
                    if($code == $address['main']['state'])
                    {
                        echo '<option value="'.$code.'" selected="selected">'.$name."</option>\n";
                    } else {
                        echo '<option value="'.$code.'">'.$name."</option>\n";
                    }
                }
                ?>
            </select>
        </div>
				
		<!-- Post Code-->
        <div class="md-form mt-4">
            <label for="address_postcode_1" ><?php echo $term_address_postcode ?></label>
            <input id="address_postcode_1" class="form-control" name="address[main][postcode]" type="text" maxlength="15" value="<?php echo $address['main']['postcode'] ?>">
        </div>
        
        <!-- Coordinates -->
        <div class="physical_1">
            <div class="md-form mt-4">
                <label for="address_latitude_1"><?php echo $term_address_latitude ?></label>
                <input id="address_latitude_1" class="form-control" name="address[main][latitude]" type="number" min="-90.000000" max="90.000000" step="0.000001" value="<?php echo $address['main']['latitude'] ?>">
            </div>
            <div class="md-form mt-4">
                <label for="address_longitude_1"><?php echo $term_address_longitude ?></label>
                <input id="address_longitude_1" class="form-control" name="address[main][longitude]" type="number" min="-180.000000" max="180.000000" step="0.000001" value="<?php echo $address['main']['longitude'] ?>">
            </div>
			
		</div>

		<hr class="primary-color">
<?php
	if(ADDRESS_BOOK_ADDRESS_DETAILS > 1)
	{
?>				
		<h3 class="card-title mt-4"><?php echo $term_address_heading_second ?></h3>
		
		<div class="col-12">
			<div class="form-check form-check-inline">
				<input class="form-check-input" id="address_same_2" name="main[contact_allowed]" type="checkbox" value="1" <?php if($address['postal']['same'] == 1) { echo 'checked="checked"'; } ?>>
				<label class="form-check-label" for="address_same_2"><?php echo $term_address_same ?></label>
			</div>
		</div>

		<div id="address_entry_2" class="not-showing">
			
			<!-- Address 2 Row -->
	        <div class="form-group">
                <label for="address_country_2"><?php echo $term_address_country ?></label>
                <select id="address_country_2" class="mdb-select md-form" searchable="Search.." name="address[postal][country]">
                    <?php
                    foreach($countries as $code => $name)
                    {
                        if($code == $address['postal']['country'])
                        {
                            echo '<option value="'.$code.'" selected="selected">'.$name."</option>\n";
                        } else {
                            echo '<option value="'.$code.'">'.$name."</option>\n";
                        }
                    }
                    ?>
                </select>
	        </div>
		
			<!-- Entry Type - Any -->
			<div class="form-group">
                <label for="address_physical_pobox_2"><?php echo $term_address_physical_pobox ?></label>
                <select id="address_physical_pobox_2" class="mdb-select md-form" name="address[postal][physical_pobox]">
                    <option value="physical"<?php if($address['postal']['physical_pobox'] == 'physical') echo ' selected="selected"' ?>><?php echo $term_address_physical ?></option>
                    <option value="pobox"<?php if($address['postal']['physical_pobox'] == 'pobox') echo ' selected="selected"' ?>><?php echo $term_address_pobox ?></option>
                </select>
	        </div>
		
			<!-- Care Of -->
            <div class="md-form mb-4">
                <label for="address_care_of_2"><?php echo $term_address_care_of ?></label>
                <input id="address_care_of_2" class="form-control" name="address[postal][care_of]" type="text" maxlength="255" value="<?php echo $address['postal']['care_of'] ?>">
            </div>
					
			<!-- Line 1 -->
            <div class="md-form mt-4">
                <label for="address_line_1_2" class="physical_2" ><?php echo $term_address_line_1_physical ?><span class="required"></span></label>
                <label for="address_line_1_2" class="pobox_2" ><?php echo $term_address_line_1_pobox ?></label>
                <input id="address_line_1_2" class="form-control" name="address[postal][line_1]" type="text" maxlength="255" value="<?php echo $address['postal']['line_1'] ?>">
            </div>
						
			<!-- Line 2 -->
            <div class="md-form mt-4">
                <label for="address_line_2_2" ><?php echo $term_address_line_2 ?></label>
                <input id="address_line_2_2" class="form-control" name="address[postal][line_2]" type="text" maxlength="255" value="<?php echo $address['postal']['line_2'] ?>">
            </div>
			
			 <!-- Suburb -->
            <div class="md-form mt-4">
                <label for="address_suburb_2" ><?php echo $term_address_suburb ?><span class="required"></span></label>
                <input id="address_suburb_2" class="form-control" name="address[postal][suburb]" type="text" maxlength="255" value="<?php echo $address['postal']['suburb'] ?>">
            </div>
	        
	        <!-- State 1 Row -->
	        <div id="state_2" class="form-group">
                <label for="address_state_2"><?php echo $term_address_state ?><span class="required"></span></label>
                <select id="address_state_2" class="mdb-select md-form" name="address[postal][state]">
                    <option value=""><?php echo $term_address_state_default ?></option>
                    <?php
                    foreach($countrySubCodes_2 as $code => $name)
                    {
                        if($code == $address['postal']['state'])
                        {
                            echo '<option value="'.$code.'" selected="selected">'.$name."</option>\n";
                        } else {
                            echo '<option value="'.$code.'">'.$name."</option>\n";
                        }
                    }
                    ?>
                </select>
	        </div>

	        <!-- Post Code-->
            <div class="md-form mt-4">
                <label for="address_postcode_2" ><?php echo $term_address_postcode ?></label>
                <input id="address_postcode_2" class="form-control" name="address[postal][postcode]" type="text" maxlength="15" value="<?php echo $address['postal']['postcode'] ?>">
            </div>
	        
	        <!-- Coordinates -->
	        <div class="physical_2">
							
				<hr>

                <div class="md-form mt-4">
                    <label for="address_latitude_2"><?php echo $term_address_latitude ?></label>
                    <input id="address_latitude_2" class="form-control" name="address[postal][latitude]" type="number" min="-90.000000" max="90.000000" step="0.000001" value="<?php echo $address['postal']['latitude'] ?>">
                </div>

                <div class="md-form mt-4">
                    <label for="address_longitude_2"><?php echo $term_address_longitude ?></label>
                    <input id="address_longitude_2" class="form-control" name="address[postal][longitude]" type="number" min="-180.000000" max="180.000000" step="0.000001" value="<?php echo $address['postal']['longitude'] ?>">
                </div>
				
			</div>
	        
		</div>				
<?php
	}
?>	
    <p class="text-center  p-3"><i>This sign  <span class="required"></span> means the field is required.</i></p>

	</div>
</div>