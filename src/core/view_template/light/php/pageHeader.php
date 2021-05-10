<!-- This is no longer included in the base template -->

<!-- You must edit base.template for overall control -->

<!-- pageHeader -->
<h1 class="text-hide"><?php echo $this->_siteTitle; ?></h1>
<?php
	if(!empty($this->_siteSlogan))
	{
		echo "	<h2 class=\"text-hide\">{$this->_pageSlogan}</h2>\n";
	}
?>
<!-- end of pageHeader -->