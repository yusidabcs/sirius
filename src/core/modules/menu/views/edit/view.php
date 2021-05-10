<!-- start of menu edit -->

<div class="row mt-3">
	<div class="col">
		<div class="card border-dark">
			
			<div class="card-header">
				<h1 class="card-title"><?php echo $term_page_header ?></h1>
			</div>
			
			<div class="card-body">
		
<?php
				if(isset($errors) && is_array($errors))
				{
?>
					<div class="iow-callout iow-callout-warning">
						<h2 class="text-warning"><?php echo $term_error_legend ?></h2>
<?php
					foreach($errors as $key => $value)
					{
						$tname = 'term_login_'.$key.'_label';
						$title = isset($$tname) ? $$tname : $key;
						echo "				<p>\n";
						echo "					<span>{$title} {$value}\n";
						echo "				</p>\n";
					}			
?>			
					</div>
<?php			
				}
?>		
				<form method="post" action="<?php echo $myURL; ?>">
					
					<div class="form-group row">
						<label for="parent_id" class="col-sm-2 col-form-label"><?php echo $term_page_parent ?></label>
						<div class="col-sm-10">
							<select class="mdb-select md-form" name="parent_id" autofocus="autofocus">
<?php 
								foreach($pageParent as $option)
								{
									if($option['selected'] == 1)
									{
										echo '<option value="'.$option['link_id'].'" selected="selected">'.$option['spacer'].' *'.$option['menu_title'].'*</option>';
									} else {
										echo '<option value="'.$option['link_id'].'">'.$option['spacer'].' '.$option['menu_title'].'</option>';
									}
								}
?>
							</select>
							<input type="hidden" id="parent_id_orig" name="parent_id_orig" value="<?php echo $parent_id; ?>" />
						</div>
					</div>
					
					<div class="form-group row">
						<label for="ajax_check_title_menu" class="col-sm-2 col-form-label"><?php echo $term_title_menu ?></label>
						<div class="col-sm-10">
							<input id="ajax_check_title_menu" class="form-control" type="text" name="title_menu" value="<?php echo $title_menu; ?>" />
							<span class="text-danger"></span>
							<input type="hidden" id="title_menu_orig" name="title_menu_orig" value="<?php echo $title_menu; ?>" />
						</div>
					</div>
					
					<div class="form-group row">
						<label for="ajax_check_title_page" class="col-sm-2 col-form-label"><?php echo $term_title_page ?></label>
						<div class="col-sm-10">
							<input id="ajax_check_title_page" class="form-control" type="text" name="title_page" value="<?php echo $title_page; ?>" />
							<span class="text-danger"></span>
							<input type="hidden" id="title_page_orig" name="title_page_orig" value="<?php echo $title_page; ?>" />
						</div>
					</div>
			
					<div class="form-group row">
						<label for="ajax_check_link_id" class="col-sm-2 col-form-label"><?php echo $term_link_id ?></label>
						<div class="col-sm-10">
							<input id="ajax_check_link_id" class="form-control" type="text" name="link_id" value="<?php echo $link_id; ?>" readonly="readonly" />
							<input type="hidden" id="link_id_orig" name="link_id_orig" value="<?php echo $link_id; ?>" />
						</div>
					</div>
			
					<div class="form-group row">
						<label for="menu_sequence_no" class="col-sm-2 col-form-label"><?php echo $term_sequence_no ?></label>
						<div class="col-sm-10">
							<input id="menu_sequence_no" class="form-control" type="text" name="sequence_no" value="<?php echo $sequence_no; ?>" />
						</div>
					</div>
					
<?php
						if($allowRedirect)
						{
?>
							<div class="form-group row">
								<label for="menu_redirect_url" class="col-sm-2 col-form-label"><?php echo $term_redirect_url ?></label>
								<div class="col-sm-10">
									<input id="menu_redirect_url" class="form-control" type="text" name="redirect_url" value="<?php echo $redirect_url; ?>" />
								</div>
							</div>
<?php		
						} else {
?>
							<input type="hidden" name="redirect_url" value="" />
<?php
						}
?>
					
					<div class="form-group row urlToggle">
						<label for="menu_templage_name" class="col-sm-2 col-form-label"><?php echo $term_template_name ?></label>
						<div class="col-sm-10">
							<select id="menu_templage_name" class="mdb-select md-form" name="template_name">
<?php 
									foreach($pageTemplateArray as $name)
									{
										if($name == $template_name)
										{
											echo '<option value="'.$name.'" selected="selected">*'.$name.'*</option>';
										} else {
											echo '<option value="'.$name.'">'.$name.'</option>';
										}
									}
?>
							</select>
						</div>
					</div>
					
					<div class="form-group row urlToggle">
						<div class="col-sm-10">
							<select id="menu_module_id" class="mdb-select md-form" name="module_id">
<?php 
									foreach($pageModuleArray as $name)
									{
										if($name == $module_id)
										{
											echo '<option value="'.$name.'" selected="selected">*'.$name.'*</option>';
										} else {
											echo '<option value="'.$name.'" '.($name == 'pages' ? 'selected' : '').' >'.$name.'</option>';
										}
									}
?>
							</select>
							<label for="menu_module_id" class="col-sm-2 col-form-label"><?php echo $term_module_id ?></label>
						</div>
					</div>
					
					<div class="form-group row">
						<label for="menu_security_id" class="col-sm-2 col-form-label"><?php echo $term_security_level_id ?></label>
						<div class="col-sm-10">
							<select id="menu_security_id" class="mdb-select md-form" name="security_level_id">
								<option value="NONE" <?php echo ($security_level_id === 'NONE') ? 'selected':'' ?>>Guest</option>
								<option value="USER" <?php echo ($security_level_id === 'USER') ? 'selected':'' ?>>User Access</option>
							</select>
						</div>
					</div>
					
					<input type="hidden" name="group_id" value="ALL" value="<?php echo $group_id ?>">
					
					<div class="form-group row">
						<label class="col-sm-2 col-form-label"><?php echo $term_site_link ?></label>
						<div class="col-sm-10">
							
							<div class="form-check form-check-inline">
								<input class="form-check-input" id="menu_main_link" type="checkbox" name="main_link" value="1" <?php if($main_link) echo 'checked' ?> />
								<label class="form-check-label" for="menu_main_link"><?php echo $term_main_link ?></label>
							</div>
							
							<div class="form-check form-check-inline">
								<input class="form-check-input" id="menu_quick_link" type="checkbox" name="quick_link" value="1" <?php if($quick_link) echo 'checked' ?> /> 
								<label class="form-check-label" for="menu_quick_link"><?php echo $term_quick_link ?></label>
							</div>
							
							<div class="form-check form-check-inline">
								<input class="form-check-input" id="menu_bottom_link" type="checkbox" name="bottom_link" value="1" <?php if($bottom_link) echo 'checked' ?> /> 
								<label class="form-check-label" for="menu_bottom_link"><?php echo $term_bottom_link ?></label>
							</div>
			
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-2 col-form-label"><?php echo $term_sitemap ?></label>
						<div class="col-sm-10">
							<div class="form-check form-check-inline">
								<input class="form-check-input" id="menu_sitemap" type="checkbox" name="sitemap" value="1" <?php if($sitemap) echo 'checked' ?> />
								<label for="menu_sitemap" class="form-check-label"><?php echo $term_sitemap_label ?></label>
							</div>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-2 col-form-label"><?php echo $term_status ?></label>
						<div class="col-sm-10">
							<div class="form-check form-check-inline">
								<input class="form-check-input" id="menu_status" type="checkbox" name="status" value="1" <?php if($status) echo 'checked' ?> />
								<label for="menu_status" class="form-check-label"><?php echo $term_status_label ?></label>
							</div>
						</div>
					</div>
					
					<div class="center">
						
						<hr>
					    
					    <button class="btn btn-primary" type="submit" name="action" value="update"><?php echo $term_update ?></button>
						<a class="btn btn-warning" href="<?php echo $baseURL; ?>" role="button"><?php echo $term_goback ?></a>
<?php 
					if($canDelete)
					{
?>
						<a class="btn btn-danger confirm" href="<?php echo $baseURL; ?>/delete/<?php echo $link_id; ?>" role="button"><?php echo $term_delete ?></a>
<?php
					};
?>
					</div>
		
				</form>
			</div>
		</div>
	</div>	
</div>

<!-- end of menu edit -->
