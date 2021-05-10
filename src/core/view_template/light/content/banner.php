<?php 
if(!empty($pageContentFileViewArray[$content_id]['image']))
{
	$first_image = reset($pageContentFileViewArray[$content_id]['image']);
	$background_image = '';

	if (!empty($first_image)) {
		$background_image = $first_image['image_prefix'].'/'.$first_image['file_name'];
	}
?>

<!-- banner_top content_<?php echo $content_id; ?> -->

<section class="banner text-white text-center pt-5 pb-5 d-flex align-items-center justify-content-center" style="background-image: url('<?php echo $background_image; ?>'); background-color: rgba(66, 133, 244, 0.85); background-attachment: fixed!important;">
    <?php if(!empty($background_image)): ?>
		<div class="layer"></div>
	<?php endif ?>
    <div class="row align-items-center justify-content-center">
        <div class="col-lg-12">
            <h2 class="banner-title"><?php echo $value['heading'] ?></h2>
            <p><?php echo $value['sdesc']; ?></p>
        </div>
    </div>
</section>

<!-- end of banner_top content_<?php echo $content_id; ?> -->

<?php
}
?>
