<!-- form login -->
<div class="card">

	<h5 class="card-header info-color white-text text-center py-4">
		<strong><?php echo $term_page_header ?></strong>
	</h5>

	<!--Card content-->
	<div class="card-body px-lg-5 pt-0">
		
<?php
		if(isset($errors) && is_array($errors))
		{
?>
			<div class="iow-callout iow-callout-warning">
				<h4 class="text-warning"><?php echo $term_error_legend ?></h4>
<?php
			foreach($errors as $key => $value)
			{
				$tname = 'term_'.$key.'_label';
				$title = isset($$tname) ? $$tname : $key;
				echo "				<p class=\"text-warning\"><strong>{$title}</strong> : {$value}</p>\n";
			}			
?>			
			</div>
<?php			
		}
?>
		
<?php
		if( isset($_SESSION['system_security_redirect']) && $_SESSION['system_security_redirect'] = 1 )
		{
?>	
			<div class="iow-callout iow-callout-info">
				<p class="text-info">
					
<?php
$note = <<<EOO
{$term_redirect_msg} {$_SESSION['system_security_point']}: {$_SESSION['system_security_reason']}.
EOO;
					echo $note;
?>
				</p>
			</div>
<?php
		}
?>
		<!-- Form -->
		<form id="loginForm" class="text-center" style="color: #757575;" method="post" action="<?php echo $post; ?>" >
			
			
			<input id="reCAPTCHA_Login_Token" name="reCAPTCHA_Login_Token" type="hidden" value="">
<?php
			//use Google reCAPTCHA js
			if(!empty($recaptcha))
			{
?>
<script nonce="<?php echo $nonce; ?>">
grecaptcha.ready(function () {
    grecaptcha.execute('<?php echo $recaptcha; ?>', { action: 'login' }).then(function (login_token) {
        $('#reCAPTCHA_Login_Token').val(login_token);
    });
});
</script>
<?php
			}
?>
			<!-- Email -->
			<div class="md-form">
				<input name="username" autocomplete="username" type="text" value="<?php echo $username; ?>" id="securityFormUsername" class="form-control" required>
				<label for="securityFormUsername"><?php echo $term_username_label ?></label>
			</div>
	
			<!-- Password -->
			<div class="md-form">
				<input name="password" autocomplete="current-password" type="password" value="<?php echo $password ?>" id="securityFormPassword" class="form-control" required>
				<label for="securityFormPassword"><?php echo $term_password_label; ?></label>
			</div>
			
<?php		 
		if($use_captcha)
		{
?>
			<div class="input-group input-group-lg mb-3">
				
				<div class="input-group-append">
					<span class="input-group-text" id="captcha-code"><img src="/lib/captcha/captcha.php"></span>
				</div>
				
				<input name="captcha" type="text" class="form-control" aria-label="Enter Captcha Code Here" aria-describedby="captcha" required>
				
			</div>
<?php
		}
?>		
			<!-- Sign in button -->
			<button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit"><?php echo $term_login_button ?></button>


			<div class="d-flex justify-content-around">
				
				<!-- Forgot password -->
				<div>	
					<a href="<?php echo $forgot_url ?>"><?php echo $term_forgot_button ?></a>
				</div>

<?php		 
		if($register_use)
		{
?>				
				<!-- Register -->				
				<div>
					<a href="<?php echo $register_url ?>"><?php echo $term_register_link ?></a>
				</div>
<?php
		}
?>	
			</div>

		</form>
		<!-- Form -->
		
	</div>
	
</div>
<!-- form login -->
