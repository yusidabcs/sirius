<!-- start of pages edit -->

<div class="page-header">
  <h2><?php echo $term_page_header.$link_id ?></h2>
</div>

<input id="link_id" type="hidden" value="<?php echo $link_id ?>">

<ul id="entrylist">
	
<?php
	//need the page base for standard content php
	//$pages_common = new \iow\modules\pages\models\common\pages_common;
	
	foreach($pageContentInfoArray as $key => $value)
	{
		require DIR_MODULE_VIEWS.'/order/content/standard.php';
	}
?>
	
</ul>

<!-- start of page-admin pages home -->
<div class="container mt-3">
	<div class="row">
		<div class="col-6">
			<p class="text-left"><a href="<?php echo $view_link; ?>" class="btn btn-primary" role="button"><?php echo $term_go_view; ?></a></p>
		</div>
		<div class="col-6">
			<p class="text-right"><a href="<?php echo $edit_link; ?>" class="btn btn-info" role="button"><?php echo $term_go_edit; ?></a></p>
		</div>
	</div>
</div>
<!-- end of page-admin pages home -->

<!-- end of pages edit -->

