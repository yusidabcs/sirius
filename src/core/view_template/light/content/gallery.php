
<!-- gallery content_<?php echo $content_id; ?> -->

<section id="gallery_<?php echo $content_id; ?>" style="background-color: #f8f9fa!important; padding-top: 5rem; padding-bottom: 5rem;">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
						
<?php
			//If there is a Heading then we need to add it
			if(!empty($value['heading']) && $value['show_heading']) 
			{
?>	
				<h2 class="text-center"><?php echo $value['heading']; ?></h2>		
<?php			
			}
?>								
<?php
				if(!empty($value['sdesc']))
				{
?>
					<p class="text-center font-weight-light mt-3 mb-5"><?php echo $value['sdesc']; ?></h3>
<?php
				}
?>

<?php  
			if(!empty($pageContentFileViewArray[$content_id]['image']))
			{
?>
					<div class="row">
<?php
				foreach($pageContentFileViewArray[$content_id]['image'] as $image)
				{
?>
						<div class="mx-auto mb-5 mb-lg-0">
							<div class="img-frame">
								<a href="<?php echo $image['image_prefix'].'/large/'.$image['file_name'];?>" data-gallery="<?php echo 'register-'.$content_id; ?>" data-title="<?php echo $image['title'];?>" data-footer="<?php echo $image['sdesc'];?>" data-type="image">
									<img src="<?php echo $image['image_prefix'].'/page/'.$image['file_name'];?>" class="img-fluid mb-3" alt="<?php echo $image['sdesc'];?>">
								</a>
							</div>
							<h5 class="text-center mt-2"><?php echo $image['title'];?></h5>
							<?php if(!empty($image['description'])): ?>
								<p class="font-weight-light mb-0"><?php echo $image['description']; ?></p>
							<?php endif; ?>
						</div>
<?php
			}
?>
					</div>
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
			</div>		
		</div>
	</div>
</section>

<!-- end of gallery content_<?php echo $content_id; ?> -->
