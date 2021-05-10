<!-- Footer -->
<footer id="footer" class="page-footer mt-5 <?php echo $this->_nav_mainColour; ?>">
	<div class="container pb-5 pt-5">
		<div class="row d-flex align-items-center">
			<div class="col-lg-6 text-lg-left text-center">
				<div class="copyright">
          			© Copyright <strong>Speedy Global</strong>. All Rights Reserved
        		</div>
			</div> 
			<div class="col-lg-6">
				<?php echo (isset($this->_footer_nav) && !empty($this->_footer_nav)) ? $this->_footer_nav : ''; ?>
			</div>
		</div>
	</div>
</footer>
<!-- Footer -->