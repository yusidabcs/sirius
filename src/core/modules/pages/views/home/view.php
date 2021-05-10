<!-- start of pages home -->

<?php
	//we always include banners at the top
	foreach($pageContentInfoArray as $content_id => $value)
	{
		if($value['content_type'] == "banner_top")
		{
			unset($pageContentInfoArray[$content_id]);
			
			$content_file = 'content/banner_top.php';
			include($content_file);
		}
    }
	
	if($show_heading)
	{   
?>

<section  class="article" style="padding-top: 5rem; padding-bottom: 5rem;">
    <div id="content_<?php echo $content_id; ?>" class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <?php
                    //If there is a Heading then we need to add it
                    if(!empty($page_heading))
                    {
                        ?>
                        <h2 class="text-center"><?php echo $page_heading; ?></h2>
                        <?php
                    }
                ?>
                <?php if(!empty($page_sdesc)): ?>
                    <p class="font-weight-light pb-5 pt-3 text-center"><?php echo $page_sdesc;  ?></p>
                <?php endif ?>

                <?php
                    if(!empty($page_keywords))
                    {
                        ?>
                        <p class="iow-keywords"><?php echo $term_keywords; ?><?php echo $page_keywords; ?></p>
                        <?php
                    }
                    ?>

                <?php if(isset($pageContentFileViewArray['page'])): ?>
                    <?php foreach($pageContentFileViewArray['page']['image'] as $image): ?>
                        <div class="image-article float-right clear-right text-center">
                            <a href="<?php echo $image['image_prefix'].'/large/'.$image['file_name'] ;?>" data-toggle="lightbox"  data-title="<?php echo $image['title'];?>" data-footer="<?php echo $image['sdesc'];?>" data-type="image">
                                <img src="<?php echo $image['image_prefix'].'/page/'.$image['file_name'];?>" class="img img-thumbnail" alt="<?php echo $image['sdesc'];?>">
                            </a>
                            <p class="image-title"><?php echo $image['title'];?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if($page_text) echo $page_text; ?>

                <?php
                    if( $show_anchors && !empty($pageContentAnchors) )
                    {
                        ?>
                        <div id="page_top">

                            <div class="list-group list-group-flush">
                                <?php
                                foreach($pageContentAnchors as $id => $title)
                                {
                                    ?>
                                    <a href="#content_<?php echo $id; ?>" class="list-group-item"><?php echo $title; ?></a>
                                    <?php
                                }
                                ?>
                            </div>

                        </div>
                        <?php
                    }
                    ?>

                <?php
                    if(!empty($pageContentFileViewArray['page']['file']))
                    {
                        ?>
                        <div class="list-group text-center">
                            <?php
                            foreach($pageContentFileViewArray['page']['file'] as $file)
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
            </div>
        </div>
    </div>

</section>
<?php
	}
	foreach($pageContentInfoArray as $content_id => $value)
	{
        
        $content_file = DIR_PAGEVIEWS.'/'. $template_name . '/';
        if (is_dir($content_file)) {
            $content_file .=  'content/'.$value['content_type'].'.php';
        }

        if (!is_file($content_file)) {
            $content_file = dirname(__FILE__) . '/content/' . $value['content_type'] . '.php';
            
            if (!is_file($content_file)) {
                throw new Exception("Template file $content_file doesn't exists", 1);
            }
        }

        
        
		include($content_file);
	}
		
	if($isAdmin)
	{
?>
        <nav class="navbar fixed-bottom bg-white d-flex justify-content-center">
            <a href="<?php echo $edit_link; ?>" class="btn btn-sm btn-primary" role="button"><?php echo $term_go_edit; ?></a>
        </nav>
	<!-- start of page-admin pages home -->
	<!-- end of page-admin pages home -->
	
<?php
	}	
?>

<?php
if( $show_anchors )
{
?>
    <a id="back-to-top" href="#" class="btn-floating btn-lg btn-primary back-to-top" role="button"><i class="fas fa-arrow-up"></i></a>
<?php
}
?>
<!-- end of pages home -->
