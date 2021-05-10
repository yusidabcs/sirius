<div class="row">
	<div class="col-md-8 offset-md-2">
		<div class="card border-success">
    <div class="card-header"><i class="far fa-check-square" aria-hidden="true"></i> <?php echo $term_submitted_heading ?></div>
    <div class="card-body">
        <p class="card-title"><?php echo $term_submitted_message_heading ?></p>
        <div class="row">
        	<div class="col-lg-8 offset-lg-2">
				<table class="table table-striped">
					<thead>
						<tr class="black text-white">
							<th><?php echo $term_header_item_table; ?></th>
							<th><?php echo $term_header_info_table; ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="2" class="center"><?php echo $term_footer_thank_you; ?></td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<th scope="row" width="40%"><?php echo $term_country_table; ?></th>
							<td><?php echo $countries[$register_info['country']]; ?></td>
						</tr>
						<?php
							if (isset($register_info['local_partner'])) 
							{
							?>
						<tr>
							<th scope="row" width="40%"><?php echo $term_local_partner_table; ?></th>
							<td><?php echo $register_info['local_partner']; ?></td>
						</tr>
						<?php 
							}
						?>
						<tr scope="row">
							<th scope="row"><?php echo $term_title_table ?></th>
							<td><?php echo $register_info['title']; ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo $term_western_table; ?></th>
							<td><?php echo $register_info['given_name'].' '.$register_info['middle_names'].' '.$register_info['family_name']; ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo $term_eastern_table; ?></th>
							<td><?php echo $register_info['family_name'].' '.$register_info['middle_names'].' '.$register_info['given_name']; ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo $term_age_table; ?></th>
							<td><?php echo $age; ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo $term_sex_table; ?></th>
							<td><?php echo $register_info['sex']; ?></td>
						</tr>
						<tr>
							<th scope="row"><?php echo $term_main_email_table; ?></th>
							<td><?php echo $register_info['main_email']; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
        </div>
    </div>
</div>
	</div>
</div>