<div class="card">
	
	<div class="card-header gradient-card-header blue-gradient">
		<h4 class="text-white text-center"><?php echo $term_page_header ?></h4>
	</div>
	
	<div class="card-body">
		
		<nav>
			
			<div class="nav nav-pills justify-content-center" role="tablist">
				
				<a class="nav-item nav-link active" id="nav-role-tab" data-toggle="tab" href="#systemConfig" role="tab" aria-controls="nav-home" aria-selected="true"><?php echo $term_tab_role ?></a>
				<a class="nav-item nav-link" id="nav-permission-tab" data-toggle="tab" href="#siteConfig" role="tab" aria-controls="nav-profile" aria-selected="false"><?php echo $term_tab_permission ?></a>

			</div>
			
		</nav>
				
		<div class="tab-content">
			
			<div class="tab-pane fade show active" id="systemConfig" role="tabpanel" aria-labelledby="nav-systemConfig-tab">
			    
				<div class="card">
	
					<div class="card-header peach-gradient text-white d-flex justify-align-items-center align-items-center">
						
					  <h5><?php echo $term_heading_role ?></h5>
						<a href="#" type="button" class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#addRoleModal"> 
							<i class="fas fa-plus"></i> <?php echo $term_button_create_role ?>
						</a>
					  
					</div>
					
					<div class="card-body">
					
						<table class="table table-bordered" id="role_list">
							<thead>
								<th>ID</th>
								<th>Role Name</th>
								<th>Total Permissions</th>
								<th>Total Users</th>
								<th>Action</th>
							</thead>
							<tbody></tbody>
						</table>
						
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="siteConfig" role="tabpanel" aria-labelledby="nav-siteConfig-tab">
				
				<div class="card">
	
					<div class="card-header peach-gradient text-white text-center">
						
					  <h5><?php echo $term_heading_permission ?></h5>
					  
					</div>
					
					<div class="card-body">
						
						<form class="form" id="assignPermission" action="#">
							<div class="row py-4">
								<div class="col-md-4 offset-md-4">
									<div class="form-group">
										<div class="md-form">
											<select name="role_id" id="roleNameSelect" class="mdb-select" searchable="Search Role">
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-12 text-center">
									<span class="font-italic">Please choose role first to change permission</span>

								</div>
							</div>
							<!--update by egan -->
							<div class="row">
								<div class="col-md-12">
									<div class="d-flex mt-3">
										<button type="button" class="btn btn-primary ml-auto save-permission disabled">Save Permissions</button>
									</div>
								</div>
									
								<div class="col-md-12">
									<table class="table permission-table table-hover">
										<thead>
											<tr class="bg-header">
												<th width="45%">MODULE</th>
												<th>PERMISSION</th>
											</tr>
										</thead>
										<tbody>
										<?php foreach($module_permissions as $moduleName => $moduleValue): 
											$moduleName_ = str_replace('.','_',$moduleName);
											?>
											<tr class="bg-module parent" id="tr_<?php echo $moduleName_?>">
												<td>
													<span class="font-weight-bold"><?php echo strtoupper($moduleName) ?></span>
													<div class="custom-control custom-checkbox custom-control-inline ml-3">
														<input type="checkbox" class="custom-control-input check_module" id="<?php echo $moduleName_?>">
														<label class="custom-control-label white-text" for="<?php echo $moduleName_?>" id="label_permission_<?php echo $moduleName_?>">Allow All</label>
													</div>

												</td>
												<td></td>
											</tr>
											<?php if($moduleValue['permission_config']): ?>
												<?php foreach($moduleValue['permission_config'] as $modelName => $modelValue): 
													$modelName_ = str_replace('.','_',$modelName);
													?>
													<tr class="child_all child_tr_<?php echo $moduleName_?>">
														<td>
															<span class="font-weight-bold pl-4"><?php echo strtoupper($modelName) ?></span>
															<div class="custom-control custom-checkbox custom-control-inline ml-3">
																<input type="checkbox" class="custom-control-input check_model <?php echo $moduleName_ ?>" id="<?php echo $moduleName_ . '_' . $modelName_?>">
																<label class="custom-control-label" for="<?php echo $moduleName_ . '_' . $modelName_?>" id="label_permission_<?php echo $moduleName_ . '_' . $modelName_?>">Allow All</label>
															</div>
														</td>
														<td></td>
													</tr>
													<?php foreach($modelValue as $action => $actionValue): 
														$action_ = str_replace('.','_',$action);
														?>
														<tr class="hover-action child_all child_tr_<?php echo $moduleName_?>">
															<td><span style="padding-left: 3rem!important;" class="font-weight-light link-action"><?php echo $action ?></span> <span class="value-action">: <?php echo $actionValue ?></span></td>
															
															<td>
																<div class="custom-control custom-checkbox">
																<input type="checkbox" class="custom-control-input check_permission <?php echo $moduleName_?> <?php echo $moduleName_ . '_' . $modelName_?>" id="<?php echo $moduleName_ . '_' . $modelName_ . '_' . $action_ ?>">
																<label class="custom-control-label" for="<?php echo $moduleName_ . '_' . $modelName_ . '_' . $action_ ?>" id="label_permission_<?php echo $moduleName_ . '_' . $modelName_ . '_' . $action_ ?>">Not Allow</label>
																</div>

																<input type="hidden" name="permission[<?php echo $moduleName . '.' . $modelName . '.' . $action ?>]" id="text_permission_<?php echo $moduleName_ . '_' . $modelName_ . '_' . $action_ ?>" value="not_allow">
															</td>
														</tr>
													<?php endforeach ?>
												<?php endforeach ?>
											<?php endif ?>
										<?php endforeach ?>
										</tbody>
									</table>
								</div>
								<div class="col-md-12">
									<div class="d-flex mt-3">
										<button type="button" class="btn btn-primary ml-auto save-permission disabled">Save Permissions</button>
									</div>
								</div>
							</div>
							<!--end update -->
							<!--
							<div class="row">
								<div class="col-md-12">
									<div class="d-flex mt-3">
										<button type="button" class="btn btn-primary ml-auto save-permission">Save Permissions</button>
									</div>
									<?php foreach($module_permissions as $moduleName => $moduleValue): ?>
										<div class="form-group">
										
											<h4 style="cursor: pointer" data-toggle="collapse" href="#<?php echo $moduleName ?>" role="button" aria-expanded="false" aria-controls="<?php echo $moduleName ?>"><?php echo strtoupper($moduleName) ?></h4>
											
											<?php if($moduleValue['permission_config']): ?>
												<div id="<?php echo $moduleName ?>" class="collapse p-3">
												<div class="card card-body">
												
													<?php foreach($moduleValue['permission_config'] as $modelName => $modelValue): ?>
														<div class="pl-4" id="<?php echo $modelName ?>">
															<h5><?php echo strtoupper($modelName) ?></h5>
															<div class="d-flex flex-wrap mb-3">
																<?php foreach($modelValue as $action => $actionValue): ?>
																	<div class="permission-item mr-3">
																		<label style="margin-bottom: 0px; margin-top: 15px" for=""><?php echo $action ?></label> <br>
																		<div class="form-check p-0 <?php echo $action ?>">
																			<input type="radio" name="permission[<?php echo $moduleName . '.' . $modelName . '.' . $action ?>]" class="form-check-input" id="<?php echo $moduleName . '.' . $modelName . '.' . $action ?>.allow" value="allow">
																			<label for="<?php echo $moduleName . '.' . $modelName . '.' . $action ?>.allow" class="form-check-label">Allow</label>
																		</div>
																		<div class="form-check p-0 <?php echo $action ?>">
																			<input type="radio" name="permission[<?php echo $moduleName . '.' . $modelName . '.' . $action ?>]" class="form-check-input" id="<?php echo $moduleName . '.' . $modelName . '.' . $action ?>.not_allow" value="not_allow">
																			<label for="<?php echo $moduleName . '.' . $modelName . '.' . $action ?>.not_allow" class="form-check-label">Not Allow</label>
																		</div>
																	</div>
																<?php endforeach ?>
																
															</div>
															<div class="d-flex pb-3">
																<div class="form-check p-0">
																	<input type="radio" id="<?php echo $moduleName . '.' . $modelName . '.allowAll' ?>" value="allow" class="form-check-input allow_all" name="<?php echo $moduleName . '.' . $modelName ?>">
																	<label for="<?php echo $moduleName . '.' . $modelName . '.allowAll' ?>">Allow All</label>
																</div>
																<div class="form-check">
																	<input type="radio" id="<?php echo $moduleName . '.' . $modelName . '.dissallowAll' ?>" value="disallow" class="form-check-input disallow_all" name="<?php echo $moduleName . '.' . $modelName ?>">
																	<label for="<?php echo $moduleName . '.' . $modelName . '.dissallowAll' ?>" class="form-check-label">Disallow All</label>
																</div>
															</div>
														</div>
													<?php endforeach ?>
												</div>
												</div>
											<?php endif ?>
										</div>
										<hr>
									<?php endforeach ?>
																
									<div class="d-flex mt-3">
										<button type="button" class="btn btn-primary ml-auto save-permission">Save Permissions</button>
									</div>
								</div>

							</div>-->
						</form>
					</div>
				</div>
			</div>
		
	</div>
</div>

<div id="addRoleModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add new role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addRoleForm" action="#" class="form" method="POST">
			<div class="form-group">
				<label for="roleName">Role Name</label>
				<input type="text" class="form-control" name="role_name" id="roleName" autofocus>
			</div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-sm btn-add">Save</button>
      </div>
    </div>
  </div>
</div>

<div id="updateRoleModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="updateRoleForm" action="#" class="form" method="POST">
			<input type="hidden" name="role_id">
			<div class="form-group">
				<label for="roleName">Role Name</label>
				<input type="text" class="form-control" name="role_name" id="roleName" autofocus>
			</div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-sm btn-update">Update</button>
      </div>
    </div>
  </div>
</div>

<div id="assignRoleModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Assign <span id="roleName"></span> role To User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="assignRoleForm" action="#" class="form" method="POST">
			<input type="hidden" name="role_id">
			<div class="form-group md-form">
				<select name="user_id[]" id="selectUser" class="mdb-select" searchable="Search User" multiple>
					<option value="" disabled>Select User</option>
					<?php foreach($users as $key => $user): ?>
						<option value="<?php echo $user['user_id'] ?>"> <?php echo $user['entity_family_name'] .' '. $user['number_given_name'] ?>&nbsp;(<?php echo $user['username'] ?>)</option>
					<?php endforeach ?>							
				</select>
				<label for="selectUser">Select User</label>
			</div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-sm btn-assign">Save</button>
      </div>
    </div>
  </div>
</div>

<div id="seePermissionModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">This role has following permission</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="height: 600px;overflow: scroll;">
		<form action="#" class="form-inline pt-3 pb-3">
			<div class="form-group ml-auto">
				<input type="text" id="filterPermission" class="form-control" placeholder="Filter permission">
			</div>
		</form>
        <table class="table table-bordered" id="permissionUser">
			<thead>
				<th width="40%">Action</th>
				<th>Value</th>
			</thead>
			<tbody></tbody>
		</table>
      </div>
    </div>
  </div>
</div>