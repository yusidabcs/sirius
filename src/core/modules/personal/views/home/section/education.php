	<!-- education tab -->
	<div id="edu" class="tab-pane fade in" role="tabpanel">
		<div class="row">
		<!-- start education -->
			<div class="col-12">
				<div class="card card-cascade mb-4">	
					<div class="view view-cascade gradient-card-header blue-gradient">
						<!-- Title -->
						<h3 class="card-header-title mb-3"><?php echo $term_education_heading; ?></h3>
					</div>
					<div class="card-body card-body-cascade">

						<table id="educations-data" class="table table-education-information table-sm table-responsive-md">
							<thead>
								<tr>
									<th width="5%">&nbsp;</th>
									<th width="10%"><?php echo $term_education_heading_from; ?></th>
									<th width="10%"><?php echo $term_education_heading_to; ?></th>
									<th width="15%"><?php echo $term_education_heading_length; ?></th>
									<th width="25%"><?php echo $term_education_heading_qualification; ?></th>
									<th width="25%"><?php echo $term_education_heading_institution; ?></th>
									<th width="10%">&nbsp;</th>
								</tr>
							</thead>
							<tbody>
<?php						
							foreach($educationList as $education_id => $education)
							{
?>				
								<tr class="<?php echo $education['tr_status']; ?>">
									
									<td>
										<a href="#" class="delete_education" id="<?php echo $education_id; ?>"><i class="far fa-trash-alt text-danger"  title="<?php echo $term_icon_delete; ?>"></i></a>
										<input type="hidden" id="education_file_<?php echo $education_id; ?>" value="<?php echo $education['filename'] ?>">
									</td>
									
									<td><?php echo $education['short_from']; ?></td>
									<td><?php echo $education['short_to']; ?></td>
									<td align="center"><?php echo $education['length']; ?></td>
									<td><?php echo $education['qualification']; ?></td>
									<td id="education_institution_<?php echo $education_id; ?>"><?php echo $education['institution']; ?></td>
									
									<td align="right" style="white-space: nowrap">
		
<?php
									if(!empty($education['filename']))
									{
?>										
										<a href="/ab/show/<?php echo $education['filename']  ?>" data-toggle="lightbox" data-gallery="<?php echo $education['safe_id'];?>" data-footer="<?php echo $education['qualification'].' at '.$education['institution'];?>" data-type="image">
											<i class="far fa-image text-success"></i></a>
<?php
									}
?>
										<a href="<?php echo $education_link.'/'.$education_id; ?>">
											<i class="far fa-edit text-warning" title="<?php echo $term_education_icon_edit; ?>"></i></a>
											
										<!-- preview -->
										<a data-toggle="modal" data-target="#educationModal_<?php echo $education_id; ?>" href="#"><i class="far fa-file-alt text-primary" title="<?php echo $term_icon_preview; ?>"></i></a>
										
										<!-- preview modal -->	
										<div class="modal fade" id="educationModal_<?php echo $education_id; ?>" tabindex="-1" role="dialog" aria-labelledby="educationModal_<?php echo $education_id; ?>" aria-hidden="true">
											
											<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
												<div class="modal-content">
													<div class="modal-header">
														
														<h4 class="modal-title white-text" id="myModalLabel"><?php echo $term_education_modal_heading; ?> (<?php echo $education['qualification']; ?>)</h4>
														
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
																	<td colspan="3"><?php echo $education['qualification']; ?></td>
																</tr>
																
																<tr>
																	<th class="right align-middle"><?php echo $term_education_table_description; ?></th>
																	<td colspan="3"><?php echo $education['description']; ?></td>
																</tr>
																
																<tr>
																	<th class="right align-middle" width="15%"><?php echo $term_education_table_from; ?></th>
																	<td width="35%"><?php echo $education['view_from']; ?></td>
																	<th class="right align-middle" width="15%"><?php echo $term_education_table_to; ?></th>
																	<td width="35%"><?php echo empty($education['to_date']) ? $term_education_table_current : $education['view_to']; ?></td>
																</tr>
																
																<tr>
																	<th class="right align-middle" width="15%"><?php echo $term_education_table_level; ?></th>
																	<td width="35%"><?php echo ucfirst($education['level']); ?></td>
																	<th class="right align-middle" width="15%"><?php echo $term_education_table_type; ?></th>
																	<td width="35%"><?php echo $education['type']; ?></td>
																</tr>
																
																<tr>
																	<th class="right align-middle"><?php echo $term_education_table_attended_country; ?></th>
																	<td width="35%"><?php echo $education['attended_country']; ?></td>
																	<th class="right align-middle" width="15%"><?php echo $term_education_table_english; ?></th>
																	<td width="35%"><?php echo ucfirst($education['english']); ?></td>
																</tr>
															
															</tbody>
															
														</table>
<?php
													if(!empty($education['certificate_number']))
													{
?>												
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
																	<td colspan="3"><?php echo $education['certificate_number']; ?></td>
																</tr>
																
																<tr>
																	<th class="right align-middle" width="15%"><?php echo $term_education_table_certificate_date; ?></th>
																	<td width="35%"><?php echo $education['view_certificate_date']; ?></td>
																	<th class="right align-middle" width="15%"><?php echo $term_education_table_certificate_expiry; ?></th>
																	<td width="35%"><?php echo empty($education['view_certificate_expiry']) ? $term_education_table_certificate_expire_no : $education['view_certificate_expiry']; ?></td>
																</tr>
																
															</tbody>
														</table>
<?php
													}
?>	
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
																	<td colspan="3"><?php echo $education['institution']; ?></td>
																</tr>
																
																<tr>
																	<th class="right align-middle"><?php echo $term_education_table_country; ?></th>
																	<td><?php echo $education['country']; ?></td>
																	<th class="right align-middle"><?php echo $term_education_table_phone; ?></th>
																	<td>
<?php
																	if(empty($education['phone']))
																	{
?>
																		<?php echo $term_education_table_phone_none; ?>
<?php	
																	} else {
?>
																		<?php echo $education['phone']; ?>
<?php
																	}
?>	
																	</td>
																</tr>
																
																<tr>
																	<th class="right align-middle"><?php echo $term_education_table_website; ?></th>
																	<td>
<?php
																	if(empty($education['website']))
																	{
?>
																		<?php echo $term_education_table_website_none; ?>
<?php	
																	} else {
?>
																		<?php echo $education['website']; ?>
<?php
																	}
?>	
																	</td>
																	<th class="right align-middle"><?php echo $term_education_table_email; ?></th>
																	<td>
<?php
																	if(empty($education['email']))
																	{
?>
																		<?php echo $term_education_table_email_none; ?>
<?php	
																	} else {
?>
																		<a href="mailto:'<?php echo $education['institution']; ?>'<<?php echo $education['email']; ?>>"><?php echo $education['email']; ?></a>
<?php
																	}
?>	
																	</td>
																</tr>
																
															</tbody>
															
														</table>
											
													</div>
												</div>
											</div>
										</div>
										<!-- END preview -->
	
									</td>
								</tr>
<?php
							}
?>
							</tbody>
							<tfoot>
								&nbsp;
							</tfoot>
						</table>
						
					</div>
					
					<div class="card-footer text-center">
						<a href="<?php echo $education_link.'/new'; ?>" class="btn btn-md btn-info" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_education_add; ?>"><i class="far fa-plus-square"></i> <?php echo $term_education_add; ?></a>
					</div>
				</div>
			</div>
		
		<!-- end education -->
		
		</div>
	</div>		
<!-- end education tab -->
