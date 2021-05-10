	<!-- referrence tab -->
	<div id="ref" class="tab-pane fade in" role="tabpanel">
		<div class="row">
			
			<!-- start referrence -->
			<div class="col-12">
				<div class="card card-cascade mb-4">	
					<div class="view view-cascade gradient-card-header blue-gradient">
						<!-- Title -->
						<h3 class="card-header-title mb-3"><?php echo $term_reference_heading_work; ?></h3>
					</div>
					<div class="card-body card-body-cascade">

						<table class="table table-reference-information table-sm table-responsive-md">
							<thead>
								<tr>
									<th width="5%">&nbsp;</th>
									<th width="25%"><?php echo $term_reference_heading_name; ?></th>
									<th width="15%"><?php echo $term_reference_heading_company; ?></th>
									<th width="15%"><?php echo $term_reference_heading_country; ?></th>
									<th width="15%"><?php echo $term_reference_heading_number; ?></th>
									<th width="15%"><?php echo $term_reference_heading_email; ?></th>
									<th width="10%">&nbsp;</th>
								</tr>
							</thead>
							<tbody>
<?php						
							foreach($referenceList['work'] as $reference_id => $reference)
							{
?>				
								<tr class="<?php echo $reference['tr_status']; ?>">
									
									<td>
										<a href="#" class="delete_reference" id="<?php echo $reference_id; ?>"><i class="far fa-trash-alt text-danger"  title="<?php echo $term_icon_delete; ?>"></i></a>
										<input type="hidden" id="reference_file_<?php echo $reference_id; ?>" value="<?php echo $reference['filename'] ?>">
									</td>
									
									<td id="ref_name_<?php echo $reference_id; ?>"><?php echo empty($reference['family_name']) ? $reference['given_names'] : strtoupper($reference['family_name']).', '.$reference['given_names']; ?></td>
									<td><?php echo $reference['entity_name']; ?></td>
									<td><?php echo $reference['country']; ?></td>
									<td><?php echo '+'.$reference['number']; ?></td>
									<td><?php echo $reference['email']; ?></td>
									<td></td>
									
									<td align="right" style="white-space: nowrap">
									<div class="btn-group">

									<?php
										if($mode == 'recruitment' && $reference['tr_status'] === 'rgba-green-slight')
										{
	?>
											<a class="btn btn-light btn-sm export-pdf" href="#" title="Export to PDF" data-reference-id="<?php echo $reference_id ?>">
												<i class="fas fa-file-pdf text-success"></i></a>
	<?php
										}
	?>
	<?php
										if(!empty($reference['filename']))
										{
	?>
											<a class="btn btn-light btn-sm" href="/ab/show/<?php echo $reference['filename']; ?>" data-toggle="lightbox" data-gallery="<?php echo $reference['safe_id'];?>" data-footer="Referrer: <?php echo empty($reference['family_name']) ? $reference['given_names'] : strtoupper($reference['family_name']).', '.$reference['given_names']; ?>" data-type="image">
												<i class="far fa-image text-success"></i></a>
	<?php
										}

	?>
	<?php                            	
										if ($mode == 'recruitment'){
	?>
											<a class="btn btn-light btn-sm" href="<?php echo $baseURL.'/reference_check/'.$reference_id; ?>">
												<i class="far fa-check-circle text-info" title="<?php echo $term_reference_icon_check; ?>"></i></a>
	<?php 
										}
	?>
											<a class="btn btn-light btn-sm" href="<?php echo $reference_link.'/work/'.$reference_id; ?>">
												<i class="far fa-edit text-warning" title="<?php echo $term_reference_icon_edit; ?>"></i></a>
											<!-- preview -->
											<a class="btn btn-light btn-sm" data-toggle="modal" data-target="#referenceModal_<?php echo $reference_id; ?>" href="#"><i class="far fa-file-alt text-primary" title="<?php echo $term_icon_preview; ?>"></i></a>
											
									</div>
										<!-- preview modal-->
										<div class="modal fade" id="referenceModal_<?php echo $reference_id; ?>" tabindex="-1" role="dialog" aria-labelledby="medicalModal_<?php echo $reference_id; ?>" aria-hidden="true">
											
											<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
												<div class="modal-content">
													<div class="modal-header">
														
														<h4 class="model-title white-text"><?php echo $term_reference_heading_work; ?> (<?php echo empty($reference['family_name']) ? $reference['given_names'] : strtoupper($reference['family_name']).', '.$reference['given_names']; ?>)</h4>
														
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true" class="white-text">&times;</span>
														</button>
														
													</div>
													
													<div class="modal-body">
		
														<table id="reference_address" class="table table-bordered" style="white-space: normal">
															
																<tr>
																	<td class="center"><?php echo $term_reference_table_heading_fullname; ?></td>
																	<td>
																		<?php echo $reference['given_names'] . ' ' . $reference['family_name']; ?>
																	</td>
																</tr>
																<tr>
																	<td class="center"><?php echo $term_reference_table_heading_email; ?></td>
																	<td> <?php echo $reference['email']; ?></td>
																</tr>
																<tr>
																	<td class="center"><?php echo $term_reference_table_heading_phone; ?></td>
																	<td><?php echo '+'.$reference['number']; ?></td>
																</tr>
																<tr>
																	<td class="center"><?php echo $term_reference_table_heading_country; ?></td>
																	<td><?php echo $reference['country']; ?></td>
																</tr>
																<tr>
																	<th class="center"><?php echo $term_reference_table_heading_address; ?></th>
																	<td>																		
<?php
																	if(empty($reference['line_1']) && empty($reference['line_2']) && empty($reference['line_3']))
																	{
																		echo $term_reference_table_no_address; 
																	} else {
																		
																		echo empty($reference['line_1']) ? '' : $reference['line_1'].'<br />';
																		echo empty($reference['line_2']) ? '' : $reference['line_2'].'<br />';
																		echo empty($reference['line_3']) ? '' : $reference['line_3'].'<br />';
																	}
?>		
																	</td>
																</tr>
																<tr>
																	<td class="center"><?php echo $term_reference_table_heading_relation; ?></td>
																	<td><?php echo $reference['relationship']; ?></td>
																</tr>
														</table>
														
														<hr>
														
														<table id="reference_check<?php echo $reference_id ?>" class="table table-bordered" style="white-space: normal">
															<tr>
																<td class="center" colspan="6"><?php echo $term_reference_table_heading_reference_check; ?></td>
															</tr>


															<tr>
																<td>
																	Requested on
																</td>
																<td>
																	Completed on
																</td>
																<td>
																	Confirmed on
																</td>
																<td>
																	Rejected on
																</td>
																<td>
																	Contact method
																</td>
																<td>
																	Status
																</td>
															</tr>
															<?php if(count($reference['reference_checks']) > 0): ?>
														
																<?php foreach($reference['reference_checks'] as $key => $rf): ?>
																	<tr>
																		<td>
																		<?php if($rf['requested_on'] !== '0000-00-00 00:00:00' && !empty($rf['user_requested'])): ?>
																				<?php echo date('M d, Y h:i:s A', strtotime($rf['requested_on'])) ?> <br>
																				By <?php echo $rf['user_requested'] ?>
																			<?php else: echo '-' ?>
																		<?php endif ?>
																		</td>
																		<td>
																		<?php if($rf['completed_on'] !== '0000-00-00 00:00:00' && !empty($rf['user_completed'])): ?>
																				<?php echo date('M d, Y h:i:s A', strtotime($rf['completed_on'])) ?> <br>
																				By <?php echo $rf['user_completed'] ?>
																			<?php else: echo '-' ?>
																		<?php endif ?>
																		</td>
																		<td>
																		<?php if($rf['confirmed_on'] !== '0000-00-00 00:00:00' && !empty($rf['user_confirmed'])): ?>
																				<?php echo date('M d, Y h:i:s A', strtotime($rf['confirmed_on'])) ?> <br>
																				By <?php echo $rf['user_confirmed'] ?>
																			<?php else: echo '-' ?>
																		<?php endif ?>
																		</td>
																		<td>
																		<?php if($rf['rejected_on'] !== '0000-00-00 00:00:00' && !empty($rf['user_rejected'])): ?>
																				<?php echo date('M d, Y h:i:s A', strtotime($rf['rejected_on'])) ?> <br>
																				By <?php echo $rf['user_rejected'] ?>
																			<?php else: echo '-' ?>
																		<?php endif ?>
																		</td>
																		<td><?php echo $rf['contact_method']; ?></td>
																		<td><label class="badge <?php echo $rf['status'] == 'sending' ? 'badge-warning' : ($rf['status'] == 'completed' ? 'btn-info' : ($rf['status'] == 'confirmed' ? 'btn-success' : 'badge-warning')) ?>"><?php echo $rf['status'] ?? 'Pending' ?></label></td>
																	</tr>
																<?php endforeach ?>
																		<?php else: echo "<td colspan='6' style='text-align: center; font-style: italic'>Reference check for Mr/Mrs. ".$reference['given_names'] . ' ' . $reference['family_name'] . ' not available </td>' ?>
															<?php endif ?>
															
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
						<a href="<?php echo $reference_link.'/work/new'; ?>" class="btn btn-md btn-primary" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_reference_add_work; ?>"><i class="far fa-plus-square"></i> <?php echo $term_reference_add_work; ?></a>
					</div>
					
				</div>
			</div>
			<!-- end work reference panel -->
		
			<!-- start personal reference panel -->
			<div class="col-12">
				<div class="card card-cascade mb-4">	
					<div class="view view-cascade gradient-card-header blue-gradient">
						<!-- Title -->
						<h3 class="card-header-title mb-3"><?php echo $term_reference_heading_personal; ?></h3>
					</div>
					<div class="card-body card-body-cascade">
						
						<table class="table table-reference-information table-sm table-responsive-md">
							<thead>
								<tr>
									<th width="5%">&nbsp;</th>
									<th width="25%"><?php echo $term_reference_heading_name; ?></th>
									<th width="15%"><?php echo $term_reference_heading_organisation; ?></th>
									<th width="15%"><?php echo $term_reference_heading_country; ?></th>
									<th width="15%"><?php echo $term_reference_heading_number; ?></th>
									<th width="15%"><?php echo $term_reference_heading_email; ?></th>
									<th width="10%">&nbsp;</th>
								</tr>
							</thead>
							<tbody>
<?php						
							foreach($referenceList['personal'] as $reference_id => $reference)
							{
?>				
								<tr class="<?php echo $reference['tr_status']; ?>">
									
									<td>
										<a href="#" class="delete_reference" id="<?php echo $reference_id; ?>"><i class="far fa-trash-alt text-danger"  title="<?php echo $term_icon_delete; ?>"></i></a>
										<input type="hidden" id="reference_file_<?php echo $reference_id; ?>" value="<?php echo $reference['filename'] ?>">
									</td>
									
									<td id="ref_name_<?php echo $reference_id; ?>"><?php echo empty($reference['family_name']) ? $reference['given_names'] : strtoupper($reference['family_name']).', '.$reference['given_names']; ?></td>
									<td><?php echo $reference['entity_name']; ?></td>
									<td><?php echo $reference['country']; ?></td>
									<td><?php echo '+'.$reference['number']; ?></td>
									<td><?php echo $reference['email']; ?></td>
									
									<td align="right" style="white-space: nowrap">
									<div class="btn-group">
									<?php
										if($mode == 'recruitment' && $reference['tr_status'] === 'rgba-green-slight')
										{
	?>
											<a class="btn btn-light btn-sm export-pdf" href="#" title="Export to PDF" data-reference-id="<?php echo $reference_id ?>">
												<i class="fas fa-file-pdf text-success"></i></a>
	<?php
										}
	?>
									
<?php
									if(!empty($reference['filename']))
									{
?>										
										<a class="btn btn-light btn-sm" href="/ab/show/<?php echo $reference['filename']; ?>" data-toggle="lightbox" data-gallery="<?php echo $reference['safe_id'];?>" data-footer="Referrer: <?php echo empty($reference['family_name']) ? $reference['given_names'] : strtoupper($reference['family_name']).', '.$reference['given_names']; ?>" data-type="image">
											<i class="far fa-image text-success"></i></a>
<?php
									}
?>
<?php                            	
									if ($mode == 'recruitment'){
?>
                                        <a class="btn btn-light btn-sm" href="<?php echo $baseURL.'/reference_check/'.$reference_id; ?>">
                                            <i class="far fa-check-circle text-info" title="<?php echo $term_reference_icon_check; ?>"></i></a>
<?php								}?>
										<a class="btn btn-light btn-sm" href="<?php echo $reference_link.'/personal/'.$reference_id; ?>">
											<i class="far fa-edit text-warning" title="<?php echo $term_reference_icon_edit; ?>"></i></a>
											
										<!-- preview -->
										<a class="btn btn-light btn-sm" data-toggle="modal" data-target="#referenceModal_<?php echo $reference_id; ?>" href="#"><i class="far fa-file-alt text-primary" title="<?php echo $term_icon_preview; ?>"></i></a>
										
									</div>
		
										<!-- preview modal-->
										<div class="modal fade" id="referenceModal_<?php echo $reference_id; ?>" tabindex="-1" role="dialog" aria-labelledby="referenceModal_<?php echo $reference_id; ?>" aria-hidden="true">
											
											<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
												<div class="modal-content">
													<div class="modal-header">
														
														<h4 class="model-title white-text"><?php echo $term_reference_heading_personal; ?> (<?php echo empty($reference['family_name']) ? $reference['given_names'] : strtoupper($reference['family_name']).', '.$reference['given_names']; ?>)</h4>
														
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true" class="white-text">&times;</span>
														</button>
														
													</div>
													
													<div class="modal-body">
														
														<table id="reference_address" class="table table-bordered" style="white-space: normal">
															
															<tr>
																<td class="center"><?php echo $term_reference_table_heading_fullname; ?></td>
																<td>
																	<?php echo $reference['given_names'] . ' ' . $reference['family_name']; ?>
																</td>
															</tr>
															<tr>
																<td class="center"><?php echo $term_reference_table_heading_email; ?></td>
																<td> <?php echo $reference['email']; ?></td>
															</tr>
															<tr>
																<td class="center"><?php echo $term_reference_table_heading_phone; ?></td>
																<td><?php echo '+'.$reference['number']; ?></td>
															</tr>
															<tr>
																<td class="center"><?php echo $term_reference_table_heading_country; ?></td>
																<td><?php echo $reference['country']; ?></td>
															</tr>
															<tr>
																<th class="center"><?php echo $term_reference_table_heading_address; ?></th>
																<td>																		
<?php
																if(empty($reference['line_1']) && empty($reference['line_2']) && empty($reference['line_3']))
																{
																	echo $term_reference_table_no_address; 
																} else {
																	
																	echo empty($reference['line_1']) ? '' : $reference['line_1'].'<br />';
																	echo empty($reference['line_2']) ? '' : $reference['line_2'].'<br />';
																	echo empty($reference['line_3']) ? '' : $reference['line_3'].'<br />';
																}
?>		
																</td>
															</tr>
															<tr>
																	<td class="center"><?php echo $term_reference_table_heading_relation; ?></td>
																	<td><?php echo $reference['relationship']; ?></td>
																</tr>
														</table>
														
														<hr>
														
														<table id="reference_check<?php echo $reference_id ?>" class="table table-bordered" style="white-space: normal">
																<tr>
																	<td class="center" colspan="6"><?php echo $term_reference_table_heading_reference_check; ?></td>
																</tr>

																<tr>
																	<td>
																		Requested on
																	</td>
																	<td>
																		Completed on
																	</td>
																	<td>
																		Confirmed on
																	</td>
																	<td>
																		Rejected on
																	</td>
																	<td>
																		Contact method
																	</td>
																	<td>
																		Status
																	</td>
																</tr>
															
																<?php if(count($reference['reference_checks']) > 0): ?>
																<?php foreach($reference['reference_checks'] as $key => $rf): ?>
																	<tr>
																		<td>
																		<?php if($rf['requested_on'] !== '0000-00-00 00:00:00' && !empty($rf['user_requested'])): ?>
																				<?php echo date('M d, Y h:i:s A', strtotime($rf['requested_on'])) ?> <br>
																				By <?php echo $rf['user_requested'] ?>
																			<?php else: echo '-' ?>
																		<?php endif ?>
																		</td>
																		<td>
																		<?php if($rf['completed_on'] !== '0000-00-00 00:00:00' && !empty($rf['user_completed'])): ?>
																				<?php echo date('M d, Y h:i:s A', strtotime($rf['completed_on'])) ?> <br>
																				By <?php echo $rf['user_completed'] ?>
																			<?php else: echo '-' ?>
																		<?php endif ?>
																		</td>
																		<td>
																		<?php if($rf['confirmed_on'] !== '0000-00-00 00:00:00' && !empty($rf['user_confirmed'])): ?>
																				<?php echo date('M d, Y h:i:s A', strtotime($rf['confirmed_on'])) ?> <br>
																				By <?php echo $rf['user_confirmed'] ?>
																			<?php else: echo '-' ?>
																		<?php endif ?>
																		</td>
																		<td>
																		<?php if($rf['rejected_on'] !== '0000-00-00 00:00:00' && !empty($rf['user_rejected'])): ?>
																				<?php echo date('M d, Y h:i:s A', strtotime($rf['rejected_on'])) ?> <br>
																				By <?php echo $rf['user_rejected'] ?>
																			<?php else: echo '-' ?>
																		<?php endif ?>
																		</td>
																		<td><?php echo $rf['contact_method']; ?></td>
																		<td><label class="badge <?php echo $rf['status'] == 'sending' ? 'badge-warning' : ($rf['status'] == 'completed' ? 'btn-info' : ($rf['status'] == 'confirmed' ? 'btn-success' : 'badge-warning')) ?>"><?php echo $rf['status'] ?? 'Pending' ?></label></td>
																	</tr>
																<?php endforeach ?>
																<?php else : echo "<td colspan='6' style='text-align: center; font-style: italic'>Reference check for Mr/Mrs. ".$reference['given_names'] . ' ' . $reference['family_name'] . ' not available </td>' ?>
																<?php endif ?>
															
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
						<a href="<?php echo $reference_link.'/personal/new'; ?>" class="btn btn-md btn-primary" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_reference_add_personal; ?>"><i class="far fa-plus-square"></i> <?php echo $term_reference_add_personal; ?></a>
					</div>
		
				</div>
			</div>
			<!-- end personal reference panel -->
		
		</div>
	</div>
	<!-- end referrence tab -->