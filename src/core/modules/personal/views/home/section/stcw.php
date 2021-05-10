<!-- medical tab -->
<div id="stcw" class="tab-pane fade in" role="tabpanel">

    <div class="card card-cascade mb-4">

        <div class="card-body card-body-cascade">
            <h4 class="card-header-title mb-3">STCW Documents</h4>
            <table id="stcw_data" class="table table-responsive-md w-100">
                <thead>
                <tr>
					<th width="5%"></th>
					<th width="10%"><?php echo $term_education_heading_from; ?></th>
					<th width="10%"><?php echo $term_education_heading_to; ?></th>
					<th width="15%"><?php echo $term_education_heading_length; ?></th>
					<th width="25%"><?php echo $term_education_heading_status; ?></th>
					<th width="10%"></th>
                </tr>
                </thead>
                <tfoot>
                &nbsp;
                </tfoot>
            </table>

        </div>
		<div class="card-footer text-center">
			<a href="<?php echo $education_link.'/new'; ?>" class="btn btn-md btn-info" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_education_add; ?>"><i class="far fa-plus-square"></i> Add new certificate</a>
		</div>

    </div>
</div>
<!-- end medical tab -->

<!-- preview modal -->	
<div class="modal fade" id="stcwModal" tabindex="-1" role="dialog" aria-labelledby="educationModal" aria-hidden="true">
											
	<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
		<div class="modal-content">
			<div class="modal-header">
				
				<h4 class="modal-title white-text" id="myModalLabel"><?php echo $term_education_modal_heading; ?></h4>
				
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true" class="white-text">&times;</span>
				</button>

			</div>
			<div class="modal-body">

				<table id="education_certificate_details" class="table table-bordered" style="white-space: normal">
					
					<thead>
						<tr>
							<th colspan="4" class="center"><?php echo $term_education_table_qualification_heading; ?></th>
						</tr>
					</thead>
					
					<tbody>

						<tr>
							<th class="right align-middle"><?php echo $term_education_table_qualification; ?></th>
							<td colspan="3" id="education_qualification"></td>
						</tr>
						
						<tr>
							<th class="right align-middle"><?php echo $term_education_table_description; ?></th>
							<td colspan="3" id="education_description"></td>
						</tr>
						
						<tr>
							<th class="right align-middle" width="15%"><?php echo $term_education_table_from; ?></th>
							<td width="35%" id="education_from_date"></td>
							<th class="right align-middle" width="15%"><?php echo $term_education_table_to; ?></th>
							<td width="35%" id="education_to_date"></td>
						</tr>
						
						<tr>
							<th class="right align-middle" width="15%"><?php echo $term_education_table_level; ?></th>
							<td width="35%" id="education_level"></td>
							<th class="right align-middle" width="15%"><?php echo $term_education_table_type; ?></th>
							<td width="35%" id="education_type"></td>
						</tr>
						
						<tr>
							<th class="right align-middle"><?php echo $term_education_table_attended_country; ?></th>
							<td width="35%" id="education_attended_country"></td>
							<th class="right align-middle" width="15%"><?php echo $term_education_table_english; ?></th>
							<td width="35%" id="education_english"></td>
						</tr>
					
					</tbody>
					
				</table>
				<hr>
				
				<table id="education_certificate_details" class="table table-bordered" style="white-space: normal">
					
					<thead>
						<tr>
							<th colspan="4" class="center"><?php echo $term_education_table_certificate_heading; ?></th>
						</tr>
					</thead>
					
					<tbody>
					
						<tr>
							<th class="right align-middle"><?php echo $term_education_table_certificate_number; ?></th>
							<td colspan="3" id="education_certificate_number"></td>
						</tr>
						
						<tr>
							<th class="right align-middle" width="15%"><?php echo $term_education_table_certificate_date; ?></th>
							<td width="35%" id="education_certificate_date"></td>
							<th class="right align-middle" width="15%"><?php echo $term_education_table_certificate_expiry; ?></th>
							<td width="35%" id="education_certificate_expiry"></td>
						</tr>
						
					</tbody>
				</table>
				<hr>
				
				<table id="education_institution_details" class="table table-bordered" style="white-space: normal">
					
					<thead>
						<tr>
							<th colspan="4" class="center"><?php echo $term_education_table_institution_heading; ?></th>
						</tr>
					</thead>

					<tbody>
						
						<tr>
							<th class="right align-middle"><?php echo $term_education_table_institution; ?></th>
							<td colspan="3" id="education_institution"></td>
						</tr>
						
						<tr>
							<th class="right align-middle"><?php echo $term_education_table_country; ?></th>
							<td id="education_country"></td>
							<th class="right align-middle"><?php echo $term_education_table_phone; ?></th>
							<td id="education_phone"></td>
						</tr>
						
						<tr>
							<th class="right align-middle"><?php echo $term_education_table_website; ?></th>
							<td id="education_website"></td>
							<th class="right align-middle"><?php echo $term_education_table_email; ?></th>
							<td id="education_email"></td>
						</tr>
						
					</tbody>
					
				</table>
			
			</div>
		</div>
	</div>
</div>
<!-- END preview -->