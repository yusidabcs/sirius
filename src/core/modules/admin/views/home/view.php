<div class="card">
	
	<div class="card-header gradient-card-header blue-gradient">
		<h4 class="text-white text-center"><?php echo $term_page_header ?></h4>
	</div>
	
	<div class="card-body">
	
		<nav>
			
			<div class="nav nav-tabs" role="tablist">
				
				<a class="nav-item nav-link active" id="nav-log-tab" data-toggle="tab" href="#log" role="tab" aria-controls="nav-home" aria-selected="true"><?php echo $term_tab_log ?></a>
				<a class="nav-item nav-link" id="nav-systemini-tab" data-toggle="tab" href="#systemIni" role="tab" aria-controls="nav-profile" aria-selected="false"><?php echo $term_tab_system_ini ?></a>
				<a class="nav-item nav-link" id="nav-dbupdate-tab" data-toggle="tab" href="#dbUpdate" role="tab" aria-controls="nav-profile" aria-selected="false"><?php echo $term_tab_db_update ?></a>
				<a class="nav-item nav-link" id="nav-filemanager-tab" data-toggle="tab" href="#fileManager" role="tab" aria-controls="nav-profile" aria-selected="false"><?php echo $term_tab_file_manager ?></a>
				<a class="nav-item nav-link" id="nav-clearcaches-tab" data-toggle="tab" href="#clearCaches" role="tab" aria-controls="nav-profile" aria-selected="false"><?php echo $term_tab_clear_caches ?></a>

			</div>
		
		</nav>
		
		<div class="tab-content">
			
			<div class="tab-pane fade show active" id="log" role="tabpanel" aria-labelledby="nav-log-tab">
				
				<div class="card">
	
					<div class="card-header peach-gradient text-white text-center">
						
					  <h5><?php echo $term_legend_log ?></h5>
					  
					</div>
					
					<div class="card-body">
				
<?php
							if(isset($log) && !empty($log))
							{
								echo $log;
							} else {
?>	
							<p><strong>No Log Information</strong></p>		
								
<?php		
							}	
?>
					</div>
				</div>
			</div>
				
			<div class="tab-pane fade" id="systemIni" role="tabpanel" aria-labelledby="nav-systemini-tab">

				<div class="card">
	
					<div class="card-header peach-gradient text-white text-center">
						
					  <h5><?php echo $term_legend_system_ini ?></h5>
					  
					</div>
					
					<div class="card-body">
						
						<form method="post" action="<?php echo $post; ?>">
							<button type="submit" name="action" value="ini_sync" class="btn btn-default btn-block"><?php echo $term_submit_system_ini ?></button>
						</form>
						
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="dbUpdate" role="tabpanel" aria-labelledby="nav-dbupdate-tab">

				<div class="card">
	
					<div class="card-header peach-gradient text-white text-center">
						
					  <h5><?php echo $term_legend_db_update ?></h5>
					  
					</div>
					
					<div class="card-body">
						
						<form method="post" action="<?php echo $post; ?>">
							<button type="submit" name="action" value="db_update" class="btn btn-default btn-block"><?php echo $term_submit_db_update ?></button>
						</form>
						
					</div>
				</div>
			</div>
	 
			<div class="tab-pane fade" id="fileManager" role="tabpanel" aria-labelledby="nav-fileManager-tab">
				
				<div class="card">
	
					<div class="card-header peach-gradient text-white text-center">
						
					  <h5><?php echo $term_legend_file_manager ?></h5>
					  
					</div>
					
					<div class="card-body">
						
						<form method="post" action="<?php echo $post; ?>">
<?php
						if($hasFMDir)
						{
?>
							<button type="submit" name="action" value="fm_clean" class="btn btn-default btn-block"><?php echo $term_submit_file_manager_clean  ?></button>
<?php
						} else {
?>
							<button type="submit" name="action" value="fm_update_storage" class="btn btn-default btn-block"><?php echo $term_submit_file_manager_storage  ?></button>
<?php
						}
?>
						</form>
						
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="clearCaches" role="tabpanel" aria-labelledby="nav-clearcaches-tab">
				
				<div class="card">
	
					<div class="card-header peach-gradient text-white text-center">
						
					  <h5><?php echo $term_legend_clear_caches ?></h5>
					  
					</div>
					
					<div class="card-body">

						<form method="post" action="<?php echo $post; ?>">
							<button type="submit" name="action" value="clear_caches" class="btn btn-default btn-block"><?php echo $term_submit_clear_caches  ?></button>
						</form>
					</div>
				</div>
			</div>
		
		</div>
	</div>
</div>