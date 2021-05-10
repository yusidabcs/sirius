<div class="card">
	
	<div class="card-header gradient-card-header blue-gradient">
		<h4 class="text-white text-center"><?php echo $term_page_header ?></h4>
	</div>
	
	<div class="card-body">

		<form class="form-horizontal" method="post" action="<?php echo $post; ?>">
			<table class="table table-bordered table-responsive-sm">
			    <thead>
				    <tr>
					    <th colspan="2">Code</th>
					    <th>Default</th>
					    <th>Local Setting</th>
					</tr>
				</thead>
				<tbody>
<?php						
			foreach($all_defaults as $moduleName => $moduleInfo)
			{
?>						
					<tr class="info">
						<th colspan="4"><h5><?php echo $moduleName; ?></h5></th>
					</tr>
<?php					
	
				foreach($moduleInfo as $defaultName => $nameInfo)
				{
?>									
					<tr>
						<td class="align-middle">
							<!-- Trigger the modal with a button -->
							<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#<?php echo $defaultName; ?>_myModal"><i class="fas fa-info-circle" alt="Information"></i></button>
							
							<!-- Modal -->
							<div class="modal fade" id="<?php echo $defaultName; ?>_myModal" tabindex="-1" role="dialog" aria-labelledby="<?php echo $defaultName; ?>_myModal" aria-hidden="true">
									
								<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
							    
							    	<!-- Modal content-->
									<div class="modal-content">
								    	<div class="modal-header">
											
											<h4 class="model-title white-text">
												<?php echo $defaultName; ?>
											</h4>
											
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true" class="white-text">&times;</span>
												</button>
												
										</div>
										<div class="modal-body">
											
											<p><?php echo $nameInfo['help']; ?></p>
											
										</div>
									</div>
								</div>
							</div>
						</td>
						
						<th class="align-middle"><?php echo $defaultName; ?></th>
<?php
					if(isset($nameInfo['options'][0]) && $nameInfo['options'][0] == 'TEXT') //it is text
					{
						$defaultValue = $nameInfo['default'];
						$options_input = '<div class="md-form"><input type="text" name="'.$defaultName.'" value="'.$nameInfo['local_default'].'"></div>'."\n";
					} else { //it has fixed options
						$defaultValue = $nameInfo['options'][ $nameInfo['default'] ];
						
						$options_input = '<select class="mdb-select md-form" name="'.$defaultName.'">'."\n";
						 
						foreach($nameInfo['options'] as $key => $value)
						{
							if($nameInfo['local_default'] == $key)
							{
								$options_input .= '<option value="'.$key.'" selected="selected">*'.$value.'*</option>'."\n";
							} else {
								$options_input .= '<option value="'.$key.'"> '.$value.'</option>'."\n";
							}
						}
					
						$options_input .= "</select>\n";

					}
?>						
						<td class="align-middle <?php echo $nameInfo['local_default'] == $nameInfo['default'] ? 'rgba-green-slight' : 'rgba-orange-slight' ?>">
							<?php echo $defaultValue; ?>
						</td>
						<td class="align-middle">
							<?php echo $options_input; ?>
						</td>
					</tr>
<?php
				}
			}
?>
				</tbody>
			</table>
			
			<button class="btn btn-default btn-block" type="submit" name="action" value="update_default_module_config" ><?php echo $term_submit_default_module_config ?></button>
			
		</form>
			
	</div>	
</div>
