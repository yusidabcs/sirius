<?php 
if(!empty($pageContentFileViewArray[$content_id]['image']))
{
    $first_image = reset($pageContentFileViewArray[$content_id]['image']);
?>

<!-- banner_top content_<?php echo $content_id; ?> -->

<section class="banner-top text-white text-center pt-5 pb-5 d-flex align-items-center justify-content-center" style="background-image: url('<?php echo $first_image['image_prefix'].'/'.$first_image['file_name']; ?>')">
    <div class="layer"></div>
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
