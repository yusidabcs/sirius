	<!-- tattoo tab -->
	<div id="tat" class="tab-pane fade in" role="tabpanel">
		<div class="row">
			
			<!-- start tattoo -->
			<div class="col-12">
				<div class="card card-cascade mb-4">	
					<div class="view view-cascade gradient-card-header blue-gradient">
						<!-- Title -->
						<h3 class="card-header-title mb-3"><?php echo $term_tattoo_heading; ?></h3>
					</div>
					<div class="card-body card-body-cascade">
			
						<table class="table table-tattoo-information table-sm table-responsive-md">
							<thead>
								<tr>
									<th>&nbsp;</th>
									<th><?php echo $term_tattoo_heading_location; ?></th>
									<th><?php echo $term_tattoo_heading_short_description; ?></th>
									<th><?php echo $term_tattoo_heading_concealable; ?></th>
									<th><?php echo $term_tattoo_heading_image; ?></th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
<?php						
							foreach($tattooList as $tattoo_id => $tattoo)
							{
?>				
								<tr>
									
									<td>
										<a href="#" class="delete_tattoo" id="<?php echo $tattoo_id; ?>"><i class="far fa-trash-alt text-danger"  title="<?php echo $term_icon_delete; ?>"></i></a>
										<input type="hidden" id="tattoo_file_<?php echo $tattoo_id; ?>" value="<?php echo $tattoo['filename'] ?>">
									</td>
									
									<td id="tattoo_location_<?php echo $tattoo_id; ?>"><?php echo $tattoo['location']; ?></td>
									<td><?php echo $tattoo['short_description']; ?></td>
									<td><?php echo $tattoo['concealable']; ?></td>
									<td>
<?php
									if(!empty($tattoo['filename']))
									{
?>
										<a href="/ab/show/<?php echo $tattoo['filename'] ?>" data-toggle="lightbox" data-gallery="<?php echo $tattoo['safe_id'];?>" data-footer="<?php echo $tattoo['location']." Image";?>" data-type="image">
											<figure class="figure">
												<img src="/ab/show/<?php echo $tattoo['thumb']; ?>"  class="img-fluid z-depth-1" title="<?php echo $tattoo['location'];?>" alt="<?php echo $tattoo['location']." Image - Click to Enlarge"; ?>">
												<figcaption class="figure-caption text-center mt-2">
												    <?php echo $tattoo['location']; ?>
												</figcaption>
											</figure>
										</a>
<?php
									} else {
										echo "No Image for <br /><strong>{$tattoo['location']} : {$tattoo['short_description']}</strong>";
									}
?>
									</td>
									<td align="right" style="white-space: nowrap">
										
										<a href="<?php echo $tattoo_link.'/'.$tattoo_id; ?>">
											<i class="far fa-edit text-warning" title="<?php echo $term_tattoo_icon_edit; ?>"></i></a>
											
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
						<a href="<?php echo $tattoo_link.'/new'; ?>" class="btn btn-md btn-info" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_tattoo_add; ?>"><i class="far fa-plus-square"></i> <?php echo $term_tattoo_add; ?></a>
					</div>
					
				</div>
			</div>
			<!-- end tattoo -->
		
		</div>
	</div>
	<!-- end tattoo tab -->