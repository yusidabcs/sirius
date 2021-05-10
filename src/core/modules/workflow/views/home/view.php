
<!-- start of workflow home -->

<div class="row ">
	<div class="col">
		<div class="card">
			
			<div class="card-header gradient-card-header blue-gradient">
				<h3 class="text-white text-center">Workflow Dashboard</h3>
			</div>
			
			<div class="card-body">
				<div class="row">
				<?php
					$cols = array_keys($allTracker); 
					foreach ($cols as $key => $item) {
				?>
					<div class="col-lg-4 col-sm-6 col-xs-12 pb-3 pt-3">
						<div class="card">
							<div class="card-body pb-3">
							<a href="<?=$allTracker[$item]['link']?>"><h4 class="card-title font-weight-bold"><?=$allTracker[$item]['heading']?></h4></a>
								<div class="justify-content-between">
									<ul class="list-group list-group-flush">
										<li class="list-group-item">All Level <span class="badge badge-pill badge-success"><?=$allTracker[$item]['count']['all_level']?></span></li>
										<li class="list-group-item">Normal <span class="badge badge-pill badge-success"><?=$allTracker[$item]['count']['normal']?></span></li>
										<li class="list-group-item">Soft Warning <span class="badge badge-pill badge-warning"><?=$allTracker[$item]['count']['soft_warning']?></span></li>
										<li class="list-group-item">Hard Warning <span class="badge badge-pill badge-warning"><?=$allTracker[$item]['count']['hard_warning']?></span></li>
										<li class="list-group-item">Deadline <span class="badge badge-pill badge-danger"><?=$allTracker[$item]['count']['deadline']?></span></li>
									</ul>
								</div>
								<div class="">
									<hr class="">
									<a href="<?=$allTracker[$item]['link']?>" class="btn btn-primary btn-block btn-sm"> View Details <i class="fas fa-angle-double-right"></i></a>
								</div>

							</div>
						</div>
					</div>
				<?php
					} 
				?>

				</div>
			</div>
			
		</div>
	</div>
</div>

<!-- end of workflow home -->
