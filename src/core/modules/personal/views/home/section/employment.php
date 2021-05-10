<!-- employment tab -->
	<div id="employ" class="tab-pane fade in" role="tabpanel">
		<div class="row">
		<!-- start employ -->
			<div class="col-12">
				<div class="card card-cascade mb-4">	
					<div class="view view-cascade gradient-card-header blue-gradient">
						<!-- Title -->
						<h3 class="card-header-title mb-3"><?php echo $term_employment_heading; ?></h3>
					</div>
					<div class="card-body card-body-cascade">

						<table id="employements-data" class="table table-employment-information table-sm table-responsive-md">
							<thead>
								<tr>
									<th width="5%">&nbsp;</th>
									<th width="10%"><?php echo $term_employment_heading_from; ?></th>
									<th width="10%"><?php echo $term_employment_heading_to; ?></th>
									<th width="15%"><?php echo $term_employment_heading_length; ?></th>
									<th width="15%"><?php echo $term_employment_heading_category; ?></th>
									<th width="20%"><?php echo $term_employment_heading_job; ?></th>
									<th width="20%"><?php echo $term_employment_heading_employer; ?></th>
									<th width="5%">&nbsp;</th>
								</tr>
							</thead>
							<tbody>
<?php						
							foreach($employmentList as $employment_id => $employment)
							{
?>				
								<tr>
									
									<td>
										<a href="#" class="delete_employment" id="<?php echo $employment_id; ?>"><i class="far fa-trash-alt text-danger"  title="<?php echo $term_icon_delete; ?>"></i></a>
										<input type="hidden" id="employment_file_<?php echo $employment_id; ?>" value="<?php echo $employment['filename'] ?>">
									</td>
									
									<td><?php echo $employment['short_from']; ?></td>
									<td><?php echo $employment['short_to']; ?></td>
									<td align="center"><?php echo $employment['length']; ?></td>
									<td><?php echo $employment['job_speedy_category']; ?></td>
									<td><?php echo $employment['job_title']; ?></td>
									<td id="employment_employer_<?php echo $employment_id; ?>"><?php echo $employment['employer']; ?></td>
									
									<td align="right" style="white-space: nowrap">
		
<?php
									if(!empty($employment['filename']))
									{
?>											
										<a href="/ab/show/<?php echo $employment['filename']  ?>" data-toggle="lightbox" data-gallery="<?php echo $employment['safe_id'];?>" data-footer="<?php echo $employment['job_title'].' at '.$employment['employer'];?>" data-type="image">
											<i class="far fa-image text-success"></i></a>
<?php
									}
?>
										<a href="<?php echo $employment_link.'/'.$employment_id; ?>">
											<i class="far fa-edit text-warning" title="<?php echo $term_employment_icon_edit; ?>"></i></a>
											
										<!-- preview -->
										<a data-toggle="modal" data-target="#employmentModal_<?php echo $employment_id; ?>" href="#"><i class="far fa-file-alt text-primary" title="<?php echo $term_icon_preview; ?>"></i></a>
										
										<!-- preview modal -->						
										<div class="modal fade" id="employmentModal_<?php echo $employment_id; ?>" tabindex="-1" role="dialog" aria-labelledby="employmentModal_<?php echo $employment_id; ?>" aria-hidden="true">
											
											<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
												<div class="modal-content">
													<div class="modal-header">
														
														<h4 class="modal-title white-text" id="employmentLabel"><?php echo $term_employment_modal_heading; ?> (<?php echo $employment['job_title']; ?>)</h4>
														
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true" class="white-text">&times;</span>
														</button>

													</div>
													<div class="modal-body">
		
														<table id="employment_data_details" class="table table-bordered" style="white-space: normal">
															
															<thead>
																<tr>
																	<th colspan="4" class="center"><?php echo $term_employment_table_work_heading; ?></th>
																</tr>
															</thead>
															
															<tbody>

																<tr>
																	<th class="right"><?php echo $term_employment_table_job_title; ?></th>
																	<td colspan="3"><?php echo $employment['job_title']; ?></td>
																</tr>
																
																<tr>
																	<th class="right"><?php echo $term_employment_table_description; ?></th>
																	<td colspan="3"><?php echo $employment['description']; ?></td>
																</tr>
																
																<tr>
																	<th class="right" width="15%"><?php echo $term_employment_table_from; ?></th>
																	<td width="35%"><?php echo $employment['view_from']; ?></td>
																	<th class="right" width="15%"><?php echo $term_employment_table_to; ?></th>
																	<td width="35%"><?php echo empty($employment['to_date']) ? '- current -' : $employment['view_to']; ?></td>
																</tr>
															
															</tbody>
															
														</table>
														
														<hr>
														
														<table id="employment_data_details" class="table table-bordered" style="white-space: normal">
															
															<thead>
																<tr>
																	<th colspan="4" class="center"><?php echo $term_employment_table_employer_heading; ?></th>
																</tr>
															</thead>
															
															<tbody>
																<tr>
																	<th class="right"><?php echo $term_employment_table_employer; ?></th>
																	<td colspan="3"><?php echo $employment['employer']; ?></td>
																</tr>
																
																<tr>
																	<th class="right"><?php echo $term_employment_table_country; ?></th>
																	<td><?php echo $employment['country']; ?></td>
																	<th class="right"><?php echo $term_employment_table_phone; ?></th>
																	<td>
<?php
																	if(empty($employment['phone']))
																	{
?>
																		<?php echo $term_employment_table_phone_none; ?>
<?php	
																	} else {
?>
																		<?php echo $employment['phone']; ?>
<?php
																	}
?>	
																	</td>
																</tr>
																
																<tr>
																	<th class="right"><?php echo $term_employment_table_website; ?></th>
																	<td>
<?php
																	if(empty($employment['website']))
																	{
?>
																		<?php echo $term_employment_table_website_none; ?>
<?php	
																	} else {
?>
																		<?php echo $employment['website']; ?>
<?php
																	}
?>	
																	</td>
																	<th class="right"><?php echo $term_employment_table_email; ?></th>
																	<td>
<?php
																	if(empty($employment['email']))
																	{
?>
																		<?php echo $term_employment_table_email_none; ?>
<?php	
																	} else {
?>
																		<a href="mailto:'<?php echo $employment['employer']; ?>'<<?php echo $employment['email']; ?>>"><?php echo $employment['email']; ?></a>
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
						<a href="<?php echo $employment_link.'/new'; ?>" class="btn btn-md btn-info" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_employment_add; ?>"><i class="far fa-plus-square"></i> <?php echo $term_employment_add; ?></a>
					</div>
					
				</div>
			</div>
		
		<!-- end employment panel -->
		
		</div>
	</div>
<!-- end employment tab -->
