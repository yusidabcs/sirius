<?php
    if (isset($pageContentFileViewArray[$content_id]['image']))
        $images = $pageContentFileViewArray[$content_id]['image'];
    $image_position = $value['image_position'];

    if (isset($images) && $image_position === 'bottom' || $image_position === 'top') {
        $keys = array_keys($images);
        $first_image = $images[$keys[0]];
        unset($images[$keys[0]]);
        unset($keys);
    }

?>

<!-- article content_<?php echo $content_id; ?> -->
<section  class="article" style="<?php echo $image_position == 'background' ? 'background-image: url('.$first_image['image_prefix'].'/large/'.$first_image['file_name'].')' : ''?>; padding-top: 5rem; padding-bottom: 5rem;">
    <div id="content_<?php echo $content_id; ?>" class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <?php
                    //If there is a Heading then we need to add it
                    if(!empty($value['heading']) && $value['show_heading'])
                    {
                        ?>
                        <h2 class="text-center"><?php echo $value['heading']; ?></h2>
                        <?php
                    }
                ?>
                <?php if(!empty($value['sdesc'])): ?>
                    <p class="font-weight-light pb-5 pt-3 text-center"><?php echo $value['sdesc'] ?></p>
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

                <?php if(isset($images)): ?>
                    <?php foreach($images as $image): ?>
                        <?php 
                            $class = ''; 
                            if ($image_position == 'right' || $image_position == 'left') {
                                $class .= 'float-'.$image_position.' clear-'.$image_position;
                            }
                        ?>
                        <div class="image-article text-center <?php echo $class; ?>">
                            <a href="<?php echo $image['image_prefix'].'/large/'.$image['file_name'] ;?>" data-toggle="lightbox" data-gallery="<?php echo 'register-'.$content_id; ?>" data-title="<?php echo $image['title'];?>" data-footer="<?php echo $image['sdesc'];?>" data-type="image">
                                    <img src="<?php echo $image['image_prefix'].'/page/'.$image['file_name'];?>" class="img img-thumbnail text-<?php echo $image_position ?>" alt="<?php echo $image['sdesc'];?>">
                            </a>
                            <p class="image-title"><?php echo $image['title'];?></p>
                        </div>
                    <?php endforeach ?>
                <?php endif ?>

                <?php echo $value['content']; ?>
                
                <?php
                    if(isset($first_image) && $image_position == 'bottom')
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
            </div>
        </div>
    </div>

</section>
<!-- end of article content_<?php echo $content_id; ?> -->
