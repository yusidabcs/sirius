<!-- form logout -->
<div class="card">

	<h5 class="card-header warning-color white-text text-center py-4">
		<strong><?php echo $term_page_header ?></strong>
	</h5>

	<!--Card content-->
	<div class="card-body px-lg-5 pt-0">
			
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
		<div class="iow-callout iow-callout-warning">
			
			<p>
				<?php echo $term_page_text ?>
			</p>
		</div>
		
		<!-- Form -->
		<form class="text-center" style="color: #757575;" method="post" action="<?php echo $post; ?>" >
			
			<input type="hidden" name="logout" value="go_now">
			
			<button class="btn btn-outline-warning btn-rounded btn-block my-4 waves-effect z-depth-0"  type="submit"><?php echo $term_logout_button ?></button>			

		</form>
		<!-- Form -->
		
	</div>
	
</div>
<!-- form logout -->

