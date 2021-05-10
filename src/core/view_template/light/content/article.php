<?php
    if (isset($pageContentFileViewArray[$content_id]['image']))
        $images = $pageContentFileViewArray[$content_id]['image'];
    $image_position = $value['image_position'];

    $text_order = 'order-lg-1';
    $image_order = 'order-lg-2';

    $keys = array_keys($images);
    $first_image = $images[$keys[0]];
    unset($images[$keys[0]]);
    unset($keys);

    if ($image_position === 'left') {
        $text_order = 'order-lg-2';
        $image_order = 'order-lg-1';
    }

?>

<!-- article content_<?php echo $content_id; ?> -->
<section class="article" style="<?php echo $image_position == 'background' ? 'background-image: url('.$first_image['image_prefix'].'/large/'.$first_image['file_name'].')' : ''?>;">
    <div id="content_<?php echo $content_id; ?>" class="row">
        <div class="col-12 col-sm-6 col-md-6 col-lg-6 d-flex jusitfy-content-center showcase-text <?php echo $text_order; ?>">
            <?php
                //If there is a Heading then we need to add it
                if(!empty($value['heading']) && $value['show_heading'])
                {
                    ?>
                    <h2><?php echo $value['heading']; ?></h2>
                    <?php
                }
            ?>
            <?php if(!empty($value['sdesc'])): ?>
                <p class="font-weight-light pb-3 pt-3 text-center"><?php echo $value['sdesc'] ?></p>
            <?php endif ?>

            <?php
                if(isset($first_image) && $image_position == 'top')
                {
                    ?>
                    <div class="image-article d-block text-center">
                        <a href="<?php echo $first_image['image_prefix'].'/large/'.$first_image['file_name'] ;?>" data-toggle="lightbox" data-gallery="<?php echo 'register-'.$content_id; ?>" data-title="<?php echo $first_image['title'];?>" data-footer="<?php echo $first_image['sdesc'];?>" data-type="image">
                                <img src="<?php echo $first_image['image_prefix'].'/page/'.$first_image['file_name'];?>" class="img img-fluid text-<?php echo $image_position ?>" alt="<?php echo $first_image['sdesc'];?>">
                        </a>
                        <p class="image-title"><?php echo $first_image['title'];?></p>
                    </div>
                <?php
                }
            ?>

            <?php echo $value['content']; ?>
            
        </div>
        <div class="col-12 col-sm-6 col-lg-6 showcase-img <?php echo $image_order ?>" style="background-image: url('<?php echo $first_image['image_prefix'].'/page/'.$first_image['file_name'];?>')">
        </div>
    </div>

</section>
<!-- end of article content_<?php echo $content_id; ?> -->
