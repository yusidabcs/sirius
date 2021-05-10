<div class="card">
	
	<div class="card-header gradient-card-header blue-gradient">
		<h4 class="text-white text-center"><?php echo $term_page_header ?></h4>
	</div>
	
	<div class="card-body">
	
		<form method="post" action="<?php echo $post; ?>">			
			<table class="table">
				<thead>
					<tr>
						<th>Module Name</th>
						<th>Visible</th>
						<th>Allow Multiple</th>
						<th>Access Security</th>
						<th>Admin Security</th>
						<th>Action</th>
					</tr>
				</thead>
				
				<tbody>
			
			<?php
				foreach($configured_modules_a as $module => $value)
				{
					if($value['configured'])
					{
			?>
			
					<tr class="rgba-green-slight">
						<td class="align-middle"><h6><?php echo $module; ?></h6></td>
						<td class="align-middle"><?php echo $value['visible'] ? 'YES' : 'NO'; ?></td>
						<td class="align-middle"><?php echo $value['allow_multiple'] ? 'YES' : 'NO'; ?></td>
						
						<td class="align-middle">
			<?php 
							if($value['visible'])
							{
			?>
							<select name="<?php echo $module; ?>[security_access]" class="mdb-select md-form m-0">
								<?php 
									foreach($security_level_id_a as $security_level_id => $v)
									{
										if($security_level_id == $value['security_access'])
										{
											echo '<option value="'.$security_level_id.'" selected="selected">*'.$security_level_id.'*</option>';
										} else {
											echo '<option value="'.$security_level_id.'">'.$security_level_id.'</option>';
										}
									}
								?>
							</select>
			
			<?php
							} else {
								echo $value['security_access']; 
							}
			?>
						</td>
						
						<td class="align-middle">
			<?php 
							if($value['visible'])
							{
			?>
							<select name="<?php echo $module; ?>[security_admin]" class="mdb-select md-form m-0">
								<?php 
									foreach($security_level_id_a as $security_level_id => $v)
									{
										if($security_level_id == $value['security_admin'])
										{
											echo '<option value="'.$security_level_id.'" selected="selected">*'.$security_level_id.'*</option>';
										} else {
											echo '<option value="'.$security_level_id.'">'.$security_level_id.'</option>';
										}
									}
								?>
							</select>
			
			<?php
							} else {
								echo $value['security_admin']; 
							}
			?>
						</td>
						
						<td class="align-middle">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" id="checkbox_<?php echo $module; ?>" name="<?php echo $module; ?>[uninstall]" value="1">
								<label class="form-check-label" for="checkbox_<?php echo $module; ?>">Unistall <?php echo $module; ?></label>
							</div>
						</td>
					</tr>
						
			<?php			
					} else {
			?>
			
					<tr class="rgba-orange-slight">
						<td class="align-middle"><h6><?php echo $module; ?></h6></td>
						<td class="align-middle"><?php echo $value['visible'] ? 'YES' : 'NO'; ?></td>
						<td class="align-middle"><?php echo $value['allow_multiple'] ? 'YES' : 'NO'; ?></td>
						
						<td class="align-middle">
							<select name="<?php echo $module; ?>[security_access]">
								<?php 
									foreach($security_level_id_a as $security_level_id => $v)
									{
										if($security_level_id == 'ADMIN')
										{
											echo '<option value="'.$security_level_id.'" selected="selected">*'.$security_level_id.'*</option>';
										} else {
											echo '<option value="'.$security_level_id.'">'.$security_level_id.'</option>';
										}
									}
								?>
							</select>
			
						</td>
						
						<td class="align-middle">
							<select name="<?php echo $module; ?>[security_admin]">
								<?php 
									foreach($security_level_id_a as $security_level_id => $v)
									{
										if($security_level_id == 'ADMIN')
										{
											echo '<option value="'.$security_level_id.'" selected="selected">*'.$security_level_id.'*</option>';
										} else {
											echo '<option value="'.$security_level_id.'">'.$security_level_id.'</option>';
										}
									}
								?>
							</select>
						</td>
						
						<td class="align-middle">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" id="checkbox_<?php echo $module; ?>" name="<?php echo $module; ?>[install]" value="1">
								<label class="form-check-label" for="checkbox_<?php echo $module; ?>">Install <?php echo $module; ?></label>
							</div>
						</td>
					</tr>
						
			<?php			
					}
				} 
			?>		
					
				</tbody>
			</table>
			
			<button class="btn btn-default btn-block" type="submit" name="action" value="update_modules"><?php echo $term_submit ?></button> 
			
		</form>
			
	</div>
</div>
