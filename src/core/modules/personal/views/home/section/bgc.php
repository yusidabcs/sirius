<!-- bgcs tab -->
<div id="bgc" class="tab-pane fade in" role="tabpanel">
		<div class="row">
			<!-- start bgc panel -->
			<div class="col-lg-12">

				<div class="card card-cascade mb-4">	
					<div class="card-body card-body-cascade table-responsive">
						<h3 class="card-header-title"><?php echo $term_bgc_heading; ?></h3>
						<table class="table table-passport-information table-sm table-responsive-sm">
							<thead>
								<tr>
									<th><?php echo $term_bgc_heading_status; ?></th>
									<th><?php echo $term_bgc_heading_level; ?></th>
									<th><?php echo $term_bgc_heading_notes; ?></th>
                                    <th><?php echo $term_bgc_heading_action; ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($bgcWorkflowList as $bgc)
								{
?>
									<tr>
										<td><?php echo $bgc['status']; ?></td>
										<td>
                                        <?php
                                            switch ($bgc['level']) {
                                                case '1':
                                                    echo '<span class="text-success">Normal</span>';
                                                    break;

                                                case '2':
                                                    echo '<span class="text-success">Soft Warning</span>';
                                                    break;

                                                case '3':
                                                    echo '<span class="text-warning">Hard Warning</span>';
                                                    break;
                                                
                                                default:
                                                    echo '<span class="text-danger">Hard Warning</span>';
                                                    break;
                                            }
                                        ?>
                                        </td>
										<td><?php echo $bgc['notes']; ?></td>
                                        <td>
                                            <?php if($bgc['status'] === 'send_notification' && $bgc['notification_on'] !== '0000-00-00 00:00:00'): ?>
                                                <a href="#" data-address-book-id="<?php echo $bgc['address_book_id'] ?>" class="btn btn-primary confirm-bgc"><?php echo $term_bgc_confirm ?></a>
                                            <?php endif ?>
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
				</div>
			</div>
			<!-- end bgc panel -->

			
		</div>
	</div>
<!-- end Englishs tab -->