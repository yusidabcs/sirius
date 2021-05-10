<!-- form security forgot -->
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
				<h2 class="text-warning"><?php echo $term_error_legend ?></h2>
<?php
			foreach($errors as $key => $value)
			{
				$tname = 'term_'.$key.'_label';
				$title = isset($$tname) ? $$tname : $key;
				echo "				<p class=\"text-warning\"><strong>{$title}</strong> {$value}</p>\n";
			}			
?>			
			</div>
<?php			
		}
?>

        <div id="confirm_sent" class="view_option iow-callout iow-callout-info" style="display: none;">
            <h3><?php echo $term_confirm_heading ?></h3>
            <p><?php echo $term_confirm_information ?></p>
        </div>

        <div id="confirm_failed" class="view_option iow-callout iow-callout-warning" style="display: none;">
            <h3><?php echo $term_failed_heading ?></h3>
            <p><?php echo $term_failed_information ?></p>
            <div>
                <button class="btn btn-primary try_again"><?php echo $term_reset_button ?></button>
            </div>
        </div>

        <div id="captcha_failed" class="view_option iow-callout iow-callout-danger" style="display: none;">
            <h3><?php echo $term_captcha_heading ?></h3>
            <p><?php echo $term_captcha_information ?></p>
            <div>
                <button class="btn btn-primary try_again"><?php echo $term_reset_button ?></button>
            </div>
        </div>
		
		<!-- Form -->
		<form class="text-center" method="post" action="<?php echo $post; ?>" >
			<input type="hidden" name="reCAPTCHA_Forgot_Token" id="reCAPTCHA_Forgot_Token">
			<!-- Usernmame / Email -->
			<div class="md-form">
				<input name="username" autocomplete="username" type="text" value="" id="securityFormUsername" class="form-control" placeholder="<?php echo $term_username_placeholder ?>" autofocus>
				<label for="securityFormUsername"><?php echo $term_username_label ?></label>
			</div>
			
			<?php if($use_captcha): ?>
				<div class="input-group input-group-lg mb-3">
				
				<div class="input-group-append">
					<span class="input-group-text" id="captcha-code"><img src="/lib/captcha/captcha.php"></span>
				</div>
				
				<input name="captcha" id="captcha" type="text" class="form-control" aria-label="Enter Captcha Code Here" aria-describedby="captcha" required>
				</div>
			<?php endif ?>

				<?php
			//use Google reCAPTCHA js
			if(!empty($recaptcha))
			{
			?>
			<script nonce="<?php echo $nonce; ?>">
			grecaptcha.ready(function () {
				grecaptcha.execute('<?php echo $recaptcha; ?>', { action: 'forgot' }).then(function (forgot_token) {
					$('#reCAPTCHA_Forgot_Token').val(forgot_token);
				});
			});
			</script>
			<?php
				}
			?>
	
			<div class="form-group">
				<button id="reset_password" type="submit" class="btn btn-primary"><?php echo $term_recover_button ?></button>
			</div>

		</form>
		<!-- Form -->
		
	</div>
	
</div>
<!-- form security forgot -->