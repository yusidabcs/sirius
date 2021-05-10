
<!-- start of user home -->

<div class="row mt-3 justify-content-sm-center">
	<div class="col-xs-12 col-sm-10 col-md-8">
		<div class="card border-dark">

<?php
		if($normal)
		{	
?>
			<div class="card-header">
				<h1><?php echo $term_page_header ?></h1>
			</div>
			
			<div class="card-body">
				<table class="table">
					<tbody>
						<tr>
							<th><?php echo $term_username_label; ?></th>
							<td><?php echo $username; ?> </td>
						</tr>
						
						<tr>
							<th><?php echo $term_email_label; ?> </th>
							<td><?php echo $email; ?> </td>
						</tr>
						
						<tr>
							<th><?php echo $term_security_label; ?> </th>
							<td><?php echo $security; ?></td>
						</tr>
						
						<tr>
							<th><?php echo $term_group_label ?> </th>
							<td><?php echo $group; ?> </td>
						</tr>
					</tbody>
				</table>
						
				<p class="button-area">
					
					<a class="btn btn-primary" href="<?php echo $changeDetails; ?>" role="button"><?php echo $term_change_details_button ?></a>
					
					<a class="btn btn-info" href="<?php echo $changePassword; ?>" role="button"><?php echo $term_change_password_button ?></a>
					
				</p>
<?php
			if($isAdmin)
			{
?>		
				<p>
					<a class="btn btn-default" href="<?php echo $gotoAdmin; ?>" role="button"><?php echo $term_goto_admin_button ?></a>
				</p>
<?php
			}
?>		
			</div>	
<?php
	}

/*EDIT FORM*/
	if($editForm)
	{	
?>
			<div class="card-header">
				<h1><?php echo $term_page_header_edit ?></h1>
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
					echo "					<strong>{$title}</strong> {$value}\n";
					echo "				</p>\n";
				}			
?>			
				</div>
<?php			
			}
?>	
				<form method="post" action="<?php echo $post; ?>">
					
					<div class="form-group">
						<label for="username"><?php echo $term_username_label ?></label>
						<input id="username" class="form-control" name="username" type="text" value="<?php echo $username; ?>" autofocus />
					</div>
					
					<div class="form-group">
						<label for="email"><?php echo $term_email_label ?></label>
						<input id="email" class="form-control" name="email" type="text" value="<?php echo $email; ?>" />
					</div>
			
					<div class="form-group">
						<input name="user_id" type="hidden" value="<?php echo $user_id; ?>" />
						<input class="btn btn-primary" type="submit" value="<?php echo $term_update_button; ?>" />
						<a class="btn btn-warning" href="<?php echo $goback; ?>" role="button"><?php echo $term_goback_button ?></a>
					</div>
						
				</form>
				
			</div>
<?php
	}

/*UPDATE PASSWORD*/
	if($passwordForm)
	{	
?>
			<div class="card-header">
				<h1><?php echo $term_page_header_password ?></h1>
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
					echo "					<strong>{$title}</strong> {$value}\n";
					echo "				</p>\n";
				}			
?>			
				</div>
<?php			
		}
?>	
				<form method="post" action="<?php echo $post; ?>">
			
					<div class="form-group">
						<label for=""><?php echo $term_password_current ?></label>
						<input id="password-current" autocomplete="current-pasword" class="form-control" name="password_current" type="password" value="" autofocus />
					</div>
					
					<div class="form-group">
						<label for="password-new"><?php echo $term_password_new ?></label>
						<input id="password-new" autocomplete="new-pasword" class="form-control" name="password_new" type="password" value="" />
					</div>
					
					<div class="form-group">
						<label for="password-confirm"><?php echo $term_password_confirm ?></label>
						<input id="password-confirm" autocomplete="new-pasword" class="form-control" name="password_confirm" type="password" value="" />
					</div>
					
					<div class="form-group">
						<input name="user_id" type="hidden" value="<?php echo $user_id; ?>" />
						<input class="btn btn-primary" type="submit" value="<?php echo $term_update_button; ?>" />
						<a class="btn btn-warning link-button" href="<?php echo $goback; ?>" role="button"><?php echo $term_goback_button ?></a>
					</div>
						
				</form>
			</div>
<?php
	}
?>
		</div>
	</div>
</div>

<!-- end of user home -->
