    <!-- checklist tab -->
	<div id="checks" class="tab-pane fade in" role="tabpanel">
		<div class="row">
		<!-- start check card -->
			<div class="col-12">
				<div class="card card-cascade mb-4">	
					<div class="view view-cascade gradient-card-header blue-gradient">
						<!-- Title -->
						<h3 class="card-header-title mb-3"><?php echo $term_checklist; ?></h3>
					</div>
					<div class="card-body card-body-cascade">
						<table class="table table-checklist-information table-sm table-responsive-md">
							<thead>
								<tr>
									<th style="width:20%;"><?php echo $term_checklist_date; ?></th>
									<th style="width:35%;"><?php echo $term_checklist_type; ?></th>
									<th style="width:25%;"><?php echo $term_checklist_result; ?></th>
									<th style="width:20%;">&nbsp;</th>
								</tr>
							</thead>
							<tbody>					
<?php
							foreach($checklist as $checklist_type => $value)
							{
?>
								<tr>
									<td><?php echo $value['date']; ?></td>
									<td><?php echo ucfirst($checklist_type); ?></td>
									<td><?php echo $value['result']; ?></td>
									<td style="white-space: nowrap">
										
										<!-- edit -->
										<a href="<?php echo $checklist_link.'/'.$checklist_type; ?>">
											<i class="far fa-edit text-warning" title="<?php echo $term_checklist_icon_edit; ?>"></i>
										</a>
										<!-- end edit -->
<?php
									if($value['display'])
									{
?>								
										<!-- preview -->
										<a data-toggle="modal" data-target="#checksModal_<?php echo $checklist_type; ?>" href="#"><i class="far fa-file-alt text-primary" title="<?php echo $term_icon_preview; ?>"></i></a>
										
										<!-- preview modal-->
										<div class="modal fade" id="checksModal_<?php echo $checklist_type; ?>" tabindex="-1" role="dialog" aria-labelledby="checksModal_<?php echo $checklist_type; ?>" aria-hidden="true">
											
											<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
												<div class="modal-content">
													<div class="modal-header">
														
														<h4 class="model-title white-text"><?php echo ucfirst($checklist_type); ?> <?php echo $term_checklist_modal_heading; ?></h4>
														
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true" class="white-text">&times;</span>
														</button>
														
													</div>
													
													<div class="modal-body" style="white-space: normal">
                                                        <div class="list-group">
<?php

												foreach($value['display'] as $question_id => $value)
												{
?>
                                                    <a href="#!" class="list-group-item list-group-item-action flex-column align-items-start ">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <p class="mb-2 lead"><?php echo $value['heading']; ?></p>
                                                        </div>
                                                        <p class="mb-2"><?php echo $value['text']; ?></p>
                                                    </a>
<?php
												}								
?>
                                                        </div>
													</div>
												</div>
											</div>
										</div>
									
										<!-- end preview -->
<?php
									}								
?>
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
					<div class="card-footer text-center">&nbsp;</div>
				</div>
			</div>
		<!-- end checks card -->	
		</div>	
	</div>
<!-- end checklist tab -->