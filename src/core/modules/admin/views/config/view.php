<div class="card">
	
	<div class="card-header gradient-card-header blue-gradient">
		<h4 class="text-white text-center"><?php echo $term_page_header ?></h4>
	</div>
	
	<div class="card-body">
		
		<nav>
			
			<div class="nav nav-pills justify-content-center" role="tablist">
				
				<a class="nav-item nav-link active" id="nav-systemConfig-tab" data-toggle="tab" href="#systemConfig" role="tab" aria-controls="nav-home" aria-selected="true"><?php echo $term_tab_system_config ?></a>
				<a class="nav-item nav-link" id="nav-siteConfig-tab" data-toggle="tab" href="#siteConfig" role="tab" aria-controls="nav-profile" aria-selected="false"><?php echo $term_tab_site_config ?></a>
				<a class="nav-item nav-link" id="nav-siteGroupConfig-tab" data-toggle="tab" href="#siteGroupConfig" role="tab" aria-controls="nav-profile" aria-selected="false"><?php echo $term_tab_site_group_config ?></a>
				<a class="nav-item nav-link" id="nav-siteUserConfig-tab" data-toggle="tab" href="#siteUserConfig" role="tab" aria-controls="nav-profile" aria-selected="false"><?php echo $term_tab_site_user_config ?></a>
				<a class="nav-item nav-link" id="nav-siteMeta-tab" data-toggle="tab" href="#siteMeta" role="tab" aria-controls="nav-profile" aria-selected="false"><?php echo $term_tab_site_meta ?></a>
				<a class="nav-item nav-link" id="nav-siteScripts-tab" data-toggle="tab" href="#siteScripts" role="tab" aria-controls="nav-profile" aria-selected="false"><?php echo $term_tab_site_scripts ?></a>

			</div>
			
		</nav>
				
		<div class="tab-content">
			
			<div class="tab-pane fade show active" id="systemConfig" role="tabpanel" aria-labelledby="nav-systemConfig-tab">
			    
				<div class="card">
	
					<div class="card-header peach-gradient text-white text-center">
						
					  <h5><?php echo $term_heading_system_config ?></h5>
					  
					</div>
					
					<div class="card-body">
					
						<form method="post" action="<?php echo $post; ?>">
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input type="checkbox" class="form-check-input" id="system_config_debug" name="DEBUG" value="1" <?php if($system_ini_a['DEBUG']) echo 'checked' ?>>
										<label class="form-check-label" for="system_config_debug"><?php echo $term_label_system_config_debug ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input type="checkbox" class="form-check-input" id="system_config_bcc_sysadmin" name="SYSADMIN_BCC_NEW_USERS" value="1" <?php if($system_ini_a['SYSADMIN_BCC_NEW_USERS']) echo 'checked' ?>>
										<label class="form-check-label" for="system_config_bcc_sysadmin"><?php echo $term_label_system_config_bcc_sysadmin ?></label>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="system_config_webserver_email" name="WEBSERVER_EMAIL" class="form-control" type="text" value="<?php echo $system_ini_a['WEBSERVER_EMAIL']; ?>" />
										<label for="system_config_webserver_email"><?php echo $term_label_system_config_webserver_email ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input id="system_config_pagination" name="PAGINATION_NUMBER" class="form-control" type="text" value="<?php echo $system_ini_a['PAGINATION_NUMBER']; ?>" />
										<label for="system_config_pagination"><?php echo $term_label_system_config_pagination ?></label>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="system_config_sysadmin_name" name="SYSADMIN_NAME" class="form-control" type="text" value="<?php echo $system_ini_a['SYSADMIN_NAME']; ?>" />
										<label for="system_config_sysadmin_name"><?php echo $term_label_system_config_sysadmin_name ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input id="system_config_sysadmin_email" name="SYSADMIN_EMAIL" class="form-control" type="text" value="<?php echo $system_ini_a['SYSADMIN_EMAIL']; ?>" />
										<label for="system_config_sysadmin_email"><?php echo $term_label_system_config_sysadmin_email ?></label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="reference_reply_to" name="REFERENCE_REPLY_TO" class="form-control" type="text" value="<?php echo $system_ini_a['REFERENCE_REPLY_TO']; ?>" />
										<label for="reference_reply_to"><?php echo $term_label_system_config_reference_reply_to ?></label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input type="checkbox" class="form-check-input" id="system_by_pass_user_process" name="BYPASS_USER_PROCESS" value="1" <?php if($system_ini_a['BYPASS_USER_PROCESS']) echo 'checked' ?>>
										<label class="form-check-label" for="system_by_pass_user_process"><?php echo $term_label_system_by_pass_user_process ?></label>
									</div>
								</div>
							</div>
							
							<button class="btn btn-default btn-block" type="submit" name="action" value="update_system_config" ><?php echo $term_submit_system_config ?></button>
														
						</form>
						
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="siteConfig" role="tabpanel" aria-labelledby="nav-siteConfig-tab">
				
				<div class="card">
	
					<div class="card-header peach-gradient text-white text-center">
						
					  <h5><?php echo $term_heading_site_config ?></h5>
					  
					</div>
					
					<div class="card-body">
					
						<form method="post" action="<?php echo $post; ?>">
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_config_salt" name="SALT" class="form-control" type="text" value="<?php echo $site_ini_a['SALT']; ?>" disabled="disabled" />
										<label for="site_config_salt"><?php echo $term_label_site_config_salt ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_config_username" name="USERNAME" class="form-control" type="text" value="<?php echo $site_ini_a['USERNAME']; ?>" />
										<label for="site_config_username"><?php echo $term_label_site_config_username ?></label>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_config_password" name="PASSWORD" class="form-control" type="text" value="<?php echo $site_ini_a['PASSWORD']; ?>" disabled="disabled" />
										<label for="site_config_password"><?php echo $term_label_site_config_password ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_config_new_password" name="site_config_new_password" class="form-control" type="text" value="" />
										<label for="site_config_new_password"><?php echo $term_label_site_config_new_password ?></label>
									</div>
								</div>
							</div>
							
							<hr>
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_config_client_name" name="CLIENT_NAME" class="form-control" type="text" value="<?php echo $site_ini_a['CLIENT_NAME']; ?>" />
										<label for="site_config_client_name"><?php echo $term_label_site_config_client_name ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_config_site_title" name="SITE_TITLE" class="form-control" type="text" value="<?php echo $site_ini_a['SITE_TITLE']; ?>" />
										<label for="site_config_site_title"><?php echo $term_label_site_config_site_title ?></label>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_config_site_slogan" name="SITE_SLOGAN" class="form-control" type="text" value="<?php echo $site_ini_a['SITE_SLOGAN']; ?>" />
										<label for="site_config_site_slogan"><?php echo $term_label_site_config_site_slogan ?></label>
									</div>
								</div>
								<div class="col-md-6">
                                    <label for="site_config_default_link"><?php echo $term_label_site_config_default_link ?></label>
									<select name="LINK_DEFAULT" class="form-control">
										<?php 
											foreach($all_open_links as $open_link)
											{
												if($site_ini_a['LINK_DEFAULT'] == $open_link)
												{
													echo '<option value="'.$open_link.'" selected="selected"> *'.$open_link.'*</option>';
												} else {
													echo '<option value="'.$open_link.'"> '.$open_link.'</option>';
												}
											}
										?>
									</select>
								</div>
							</div>
							
							<hr>
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_config_site_email_name" name="SITE_EMAIL_NAME" class="form-control" type="text" value="<?php echo $site_ini_a['SITE_EMAIL_NAME']; ?>" />
										<label for="site_config_site_email_name"><?php echo $term_label_site_config_site_email_name ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_config_site_email_add" name="SITE_EMAIL_ADD" class="form-control" type="text" value="<?php echo $site_ini_a['SITE_EMAIL_ADD']; ?>" />
										<label for="site_config_site_email_add"><?php echo $term_label_site_config_site_email_add ?></label>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_config_site_email_subject" name="SITE_EMAIL_SUBJECT" class="form-control" type="text" value="<?php echo $site_ini_a['SITE_EMAIL_SUBJECT']; ?>" />
										<label for="site_config_site_email_subject"><?php echo $term_label_site_config_site_email_subject ?></label>
									</div>
								</div>
							</div>
							
							<hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="md-form">
                                        <input id="site_config_site_email_smtp" name="SITE_EMAIL_SMTP" class="form-control" type="text" value="<?php echo $site_ini_a['SITE_EMAIL_SMTP']; ?>" />
                                        <label for="site_config_site_email_smtp"><?php echo $term_label_site_config_site_email_smtp ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="md-form">
                                        <input id="site_config_site_email_smtp_username" name="SITE_EMAIL_SMTP_USERNAME" class="form-control" type="text" value="<?php echo $site_ini_a['SITE_EMAIL_SMTP_USERNAME']; ?>" />
                                        <label for="site_config_site_email_smtp_username"><?php echo $term_label_site_config_site_email_smtp_username ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="md-form">
                                        <input id="SITE_EMAIL_SMTP_PASSWORD" name="SITE_EMAIL_SMTP_PASSWORD" class="form-control" type="text" value="<?php echo $site_ini_a['SITE_EMAIL_SMTP_PASSWORD']; ?>" />
                                        <label for="SITE_EMAIL_SMTP_PASSWORD"><?php echo $term_label_site_config_site_email_smtp_password ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="md-form">
                                        <input id="SITE_EMAIL_SMTP_PORT" name="SITE_EMAIL_SMTP_PORT" class="form-control" type="number" value="<?php echo $site_ini_a['SITE_EMAIL_SMTP_PORT']; ?>" />
                                        <label for="SITE_EMAIL_SMTP_PORT"><?php echo $term_label_site_config_site_email_smtp_port ?></label>
                                    </div>
                                </div>
                            </div>

                            <hr>
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_config_site_tag_manager" name="SITE_TAG_MANAGER" class="form-control" type="text" value="<?php echo $site_ini_a['SITE_TAG_MANAGER']; ?>" />
										<label for="site_config_site_tag_manager"><?php echo $term_label_site_config_site_tag_manager ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_config_site_email_add" class="form-control" type="text" value="<?php echo $site_ini_a['update_id']; ?>" disabled="disabled" />
										<label for="site_config_site_email_add"><?php echo $term_label_site_config_site_update_id ?></label>
									</div>
								</div>
							</div>
							
							<hr>
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_config_site_recaptcha_key" name="SITE_RECAPTCHA_KEY" class="form-control" type="text" value="<?php echo $site_ini_a['SITE_RECAPTCHA_KEY']; ?>" />
										<label for="site_config_site_recaptcha_key"><?php echo $term_label_site_config_site_recaptcha_key ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_config_site_recaptcha_secret" name="SITE_RECAPTCHA_SECRET" class="form-control" type="text" value="<?php echo $site_ini_a['SITE_RECAPTCHA_SECRET']; ?>" />
										<label for="site_config_site_recaptcha_secret"><?php echo $term_label_site_config_site_recaptcha_secret ?></label>
									</div>
								</div>
							</div>
							
							<hr>
							
							<div class="row">
								<div class="col-6">
									<div class="md-form">
										<input id="site_config_search_submit" name="SEARCH_SUBMIT" type="checkbox" class="form-check-input" value="1" <?php if($site_ini_a['SEARCH_SUBMIT']) echo 'checked'; ?>>
										<label for="site_config_search_submit"><?php echo $term_label_site_config_submit_search; ?></label>
									</div>
								</div>
								<div class="col-6">
									<div class="md-form">
										<input id="site_config_site_down" name="SITE_DOWN" type="checkbox" class="form-check-input" value="1" <?php if($site_ini_a['SITE_DOWN']) echo 'checked'; ?>>
										<label for="site_config_site_down"><?php echo $term_label_site_config_site_down ?></label>
									</div>
								</div>
							</div>
							
							<button class="btn btn-default btn-block" type="submit" name="action" value="update_site_config" ><?php echo $term_submit_site_config ?></button>
		
						</form>
						
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="siteGroupConfig" role="tabpanel" aria-labelledby="nav-siteGroupConfig-tab">
				
				<div class="card">
	
					<div class="card-header peach-gradient text-white text-center">
						
					  <h5><?php echo $term_heading_site_group_config ?></h5>
					  
					</div>
					
					<div class="card-body">
						
						<h5>Current Site Groups</h5>
						
						<table class="table table-bordered table-responsive-sm">
							
							<thead>
								<tr>
									<th>&nbsp;</th>
									<th>Title</th>
									<th>Code</th>
									<th>Members</th>
									<th>Fixed</th>
								</tr>
							</thead>
							<tbody>
<?php
							foreach($site_group_config_ini_a as $groupCode => $groupValue)
							{
?>						
								<tr>
									<td>
										<!-- Trigger the modal with a button -->
										<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#<?php echo $groupCode; ?>_myModal"><i class="fas fa-info-circle" alt="Information"></i></button>
										
										<!-- Modal -->
										<div class="modal fade" id="<?php echo $groupCode; ?>_myModal" tabindex="-1" role="dialog" aria-labelledby="<?php echo $groupCode; ?>_myModal" aria-hidden="true">
											
											<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
										    
										    	<!-- Modal content-->
												<div class="modal-content">
											    	<div class="modal-header">
														
														<h4 class="model-title white-text">
															<?php echo $groupValue['title']; ?>
														</h4>
														
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true" class="white-text">&times;</span>
															</button>
															
													</div>
													<div class="modal-body">
														<p><?php echo $groupValue['desc']; ?></p>
													</div>
												</div>
											</div>
										</div>
										
									</td>
									
									<td>
										<strong><?php echo $groupValue['title']; ?></strong>
									</td>
									
									<td>
										<?php echo $groupCode; ?>
									</td>
									
									<td>
										<?php echo $groupValue['members']; ?>
									</td>
									
									<td>
										<?php echo $groupCode == 'IOW' || $groupCode == 'ALL' ? 'YES' : 'NO' ; ?>
									</td>
					
								</tr>
<?php
							}
?>						
							</tbody>
							
						</table>
						
						<hr>
				
						<form method="post" action="<?php echo $post; ?>">
							
							<h5><?php echo $term_legend_site_group ?></h5>
								
							<p>
								<?php echo $term_form_site_group_instruction; ?>
							</p>
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_group_code" name="site_group_code" class="form-control" type="text" value="" />
										<label for="site_group_code"><?php echo $term_label_site_group_code ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_group_title" name="site_group_title" class="form-control" type="text" value="" />
										<label for="site_group_title"><?php echo $term_label_site_group_title ?></label>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_group_desc" name="site_group_desc" class="form-control" type="text" value="" />
										<label for="site_group_desc"><?php echo $term_label_site_group_desc ?></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form">
										<input id="site_group_members" name="site_group_members" class="form-control" type="text" value="" />
										<label for="site_group_members"><?php echo $term_label_site_group_members ?></label>
									</div>
								</div>
							</div>

							<button class="btn btn-default btn-block" type="submit" name="action" value="update_site_group" ><?php echo $term_submit_site_group ?></button>

						</form>
						
					</div>
				</div>
			</div>	
			
			<div class="tab-pane fade" id="siteUserConfig" role="tabpanel" aria-labelledby="nav-siteUserConfig-tab">

				<div class="card">
	
					<div class="card-header peach-gradient text-white text-center">
						
					  <h5><?php echo $term_heading_site_user_config ?></h5>
					  
					</div>
					
					<div class="card-body">
						
						<h5>Current Site Users</h5>
						
						<table class="table table-bordered table-responsive-sm">
							<thead>
								<tr>
									<th>&nbsp;</th>
									<th>Title</th>
									<th>Level</th>
									<th>Code</th>
									<th>Fixed</th>
								</tr>
							</thead>
							<tbody>
<?php
							foreach($site_security_level_config_ini_a as $userCode => $userValue)
							{
?>						
								<tr>
									<td>
										<!-- Trigger the modal with a button -->
										<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#<?php echo $userCode; ?>_myModal"><i class="fas fa-info-circle" alt="Information"></i></button>
																		
										<!-- Modal -->
										<div class="modal fade" id="<?php echo $userCode; ?>_myModal" tabindex="-1" role="dialog" aria-labelledby="<?php echo $userCode; ?>_myModal" aria-hidden="true">
											
											<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
										    
										    	<!-- Modal content-->
												<div class="modal-content">
													
													<div class="modal-header">
														
														<h4 class="model-title white-text">
															<?php echo $userValue['title']; ?>
														</h4>
														
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true" class="white-text">&times;</span>
														</button>
													</div>
													<div class="modal-body">
														<p><?php echo $userValue['desc']; ?></p>
													</div>
												</div>
											</div>
										</div>
										
									</td>
									
									<td>
										<strong><?php echo $userValue['title']; ?></strong>
									</td>
									
									<td>
										<?php echo $userValue['level']; ?>
									</td>
									
									<td>
										<?php echo $userCode; ?>
									</td>
									
									<td>
										<?php echo $userValue['fixed'] == 1 ? 'YES' : 'NO' ; ?>
									</td>
									
								</tr>
<?php
							}
?>						
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="siteMeta" role="tabpanel" aria-labelledby="nav-siteMeta-tab">
				
				<div class="card">
	
					<div class="card-header peach-gradient text-white text-center">
						
					  <h5><?php echo $term_heading_site_meta ?></h5>
					  
					</div>
					
					<div class="card-body">
										
						<form class="form-horizontal" method="post" action="<?php echo $post; ?>">
					
<?php				
						foreach($site_meta_ini_a as $meta_heading => $meta_item)
						{
?>							<h5><?php echo $meta_heading; ?></h5>

							<table class="table table-bordered table-responsive-sm">
								<thead>
									<tr>
										<th style="width:30%">Type</th>
										<th style="width:60%">Content</th>
										<th style="width:10%">&nbsp;</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<td>
<?php
										if($meta_heading != 'charset')
										{
?>
											<div class="input-group mb-3">
												<input type="text" class="form-control" placeholder="New Type ... " aria-label="New Meta Name" >
												<div class="input-group-append add_meta" id="<?php echo $meta_heading; ?>">
													<span class="input-group-text" title="Add"><i class="fas fa-plus-circle"></i></span>
												</div>
											</div>
<?php								
										}							
?>		
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
								</tfoot>
			
								<tbody>
<?php
								if($meta_item)
								{
									foreach($meta_item as $meta_type => $meta_content)
									{
?>						
									<tr>
										<td><?php echo $meta_type; ?></td>
										<td>
											<input name="<?php echo $meta_heading; ?>[<?php echo $meta_type; ?>]" class="form-control" type="text" value="<?php echo $meta_content; ?>" />
										</td>
										<td>
<?php
										if($meta_type != 'charset' && $meta_type != 'viewport' && $meta_type != 'x-ua-compatible')
										{
?>
											<span class="delete_meta" title="Delete"><i class="fas fa-trash"></i></span>
<?php								
										}							
?>													
										</td>
									</tr>
<?php
									}
								}
?>						
								</tbody>	
							</table>
<?php
						}
?>				
							<button class="btn btn-default btn-block" type="submit" name="action" value="update_site_meta" ><?php echo $term_submit_site_meta ?></button>
						
						</form>
						
					</div>
				</div>
						
			</div>
			
			<div class="tab-pane fade" id="siteScripts" role="tabpanel" aria-labelledby="nav-siteScripts-tab">
				
				<div class="card">
	
					<div class="card-header peach-gradient text-white text-center">
						
					  <h5><?php echo $term_heading_site_scripts ?></h5>
					  
					</div>
					
					<div class="card-body">
				
						<form class="form-horizontal" method="post" action="<?php echo $post; ?>">
						
							<div class="md-form amber-textarea active-amber-textarea-2">
								<i class="fas fa-pencil-alt prefix"></i>
								<textarea type="text" id="site_scripts" name="analytics" class="md-textarea form-control" rows="10"><?php echo $site_scripts; ?></textarea>
								<label for="analytics"><?php echo $term_label_site_scripts ?></label>
							</div>
							
							<button class="btn btn-default btn-block" type="submit" name="action" value="update_site_scripts" ><?php echo $term_submit_site_scripts ?></button>
			
						</form>
					</div>
				</div>	
			</div>
			
		</div>
	</div>
</div>
