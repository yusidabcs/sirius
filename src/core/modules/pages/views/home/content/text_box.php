<?php
    if (isset($pageContentFileViewArray[$content_id]['image']))
        $images = $pageContentFileViewArray[$content_id]['image'];
    $image_position = $value['image_position'];
    if (isset($images))
        $first_image = reset($images);
?>

<!-- article content_<?php echo $content_id; ?> -->
<section  class="article" style="<?php echo $image_position == 'background' ? 'background-image: url('.$first_image['image_prefix'].'/large/'.$first_image['file_name'].')' : ''?>">
    <div id="content_<?php echo $content_id; ?>" class="container">
        <div class="row mt-3">
            <div class="col">
                <div class="card">

                    <?php
                    if(isset($first_image) && $image_position == 'top')
                    {
                        ?>
                        <img src="<?php echo $first_image['image_prefix'].'/'.$first_image['file_name'];?>" class="card-img-top" alt="<?php echo $first_image['sdesc'];?>">
                    <?php
                    }
                    ?>

                    <?php
                    //If there is a Heading then we need to add it
                    if(!empty($value['heading']) && $value['show_heading'])
                    {
                        ?>
                        <h2 class="card-header"><?php echo $value['heading']; ?></h2>
                        <?php
                    }
                    ?>
                    <div class="card-body">
                        <?php
                        if(!empty($first_image))
                        {
                        ?>
                        <div class="row">
                            <div class="<?php echo $image_position == 'left' ? 'col-sm-8 order-last ' : ($image_position == 'right' ? 'col-sm-8 order-first' : 'col-md-12')?>">
                                <?php
                                }
                                ?>

                                <?php
                                if(!empty($value['sdesc']))
                                {
                                    ?>
                                    <h3 class="card-title"><?php echo $value['sdesc']; ?></h3>
                                    <?php
                                }
                                ?>

                                <?php
                                if(!empty($value['content']))
                                {
                                    ?>
                                    <div class="card-text"><?php echo $value['content']; ?></div>
                                    <?php
                                }
                                ?>

                                <?php
                                if(!empty($pageContentFileViewArray[$content_id]['file']))
                                {
                                    ?>
                                    <div class="list-group text-center">
                                        <?php
                                        foreach($pageContentFileViewArray[$content_id]['file'] as $file)
                                        {
                                            ?>						<li class="list-group-item bg-light">
                                            <a href="<?php echo $file['file_prefix'].'/'.$file['file_name'];?>" title="<?php echo $file['sdesc'];?>"><?php echo $file['file_name'];?></a>
                                        </li>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                                ?>

                                <?php
                                if(!empty($images) && ($image_position == 'left'  || $image_position == 'right' ))
                                {
                                ?>
                            </div>
                            <div class="col-sm-4 center">
                                <?php
                                foreach($images as $image)
                                {
                                    ?>
                                    <card class="card mb-3">
                                        <a href="<?php echo $image['image_prefix'].'/large/'.$image['file_name'] ;?>" data-toggle="lightbox" data-gallery="<?php echo 'register-'.$content_id; ?>" data-title="<?php echo $image['title'];?>" data-footer="<?php echo $image['sdesc'];?>" data-type="image">
                                            <img src="<?php echo $image['image_prefix'].'/page/'.$image['file_name'];?>" class="card-img-top" alt="<?php echo $image['sdesc'];?>">
                                        </a>
                                        <div class="card-body text-center">
                                            <p class="card-text"><?php echo $image['title'];?></p>
                                        </div>
                                    </card>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                    </div>
                
                </div>
                <?php
                    if(isset($first_image) && $image_position == 'bottom')
                    {
                        ?>
                        <img src="<?php echo $first_image['image_prefix'].'/'.$first_image['file_name'];?>" class="img img-fluid" alt="<?php echo $first_image['sdesc'];?>">
                    <?php
                    }
                    ?>
            </div>
        </div>
    </div>

</section>
<!-- end of article content_<?php echo $content_id; ?> -->