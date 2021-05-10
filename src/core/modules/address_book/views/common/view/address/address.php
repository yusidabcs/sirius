
					<!-- start of address_book common view address -->

<?php
	
		$count = count($address);
		
		foreach($address as $type => $value)
		{
			
			if($value['physical_pobox'] == 'physical')
			{
				if($count > 1)
				{
?>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
<?php					
				} else {
?>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<?php					
				}
?>
						<table class="table">
							<thead>
								<tr>
									<th colspan="4"><?php echo ucfirst($type); ?> <?php echo $term_address_heading; ?></th>
								</tr>
							</thead>
							<tbody>
								
								<tr>
									<th><?php echo $term_address_care_of; ?>:</th>
									<td colspan="3"><?php echo $value['care_of']; ?></td>
								</tr>
								
								<tr>
									<th rowspan="4"><?php echo $term_address_heading; ?>:</th>
									<td colspan="3"><?php echo $value['line_1']; ?></td>
								</tr>
								
								<tr>
									<td colspan="3"><?php echo $value['line_2']; ?></td>
								</tr>
								
								<tr>
									<td><?php echo $value['suburb']; ?></td>
									<td><?php echo $value['state_full']; ?></td>
									<td><?php echo $value['postcode']; ?></td>
								</tr>
								
								<tr>
									<td colspan="3"><?php echo $value['country_full']; ?></td>
								</tr>
								
							</tbody>
						</table>
					</div>
<?php
			} else {
				
				if($count > 1)
				{
?>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
<?php					
				} else {
?>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<?php					
				}
?>
						<table class="table">
							<thead>
								<tr>
									<th><?php echo ucfirst($type); ?> <?php echo $term_address_heading; ?></th>
								</tr>
							</thead>
							<tbody>
								
								<tr>
									<th><?php echo $term_address_care_of; ?>:</th>
									<td colspan="3"><?php echo $value['care_of']; ?></td>
								</tr>
								
								<tr>
									<th rowspan="4"><?php echo $term_address_heading; ?>:</th>
									<td colspan="3"><?php echo $term_address_line_1_pobox; ?> <?php echo $value['line_1']; ?></td>
								</tr>
								
								<tr>
									
									<td><?php echo $value['suburb']; ?></td>
									<td><?php echo $value['state_full']; ?></td>
									<td><?php echo $value['postcode']; ?></td>
								</tr>
								
								<tr>
									
									<td colspan="3"><?php echo $value['country_full']; ?></td>
								</tr>
								
							</tbody>
						</table>
					</div>
<?php
			}
		}
?>					
				<!-- end of address_book common view address -->
				