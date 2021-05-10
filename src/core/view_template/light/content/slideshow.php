
<!-- slideshow content_<?php echo $content_id; ?> -->
<section  class="article" style="<?php echo $image_position == 'background' ? 'background-image: url('.$first_image['image_prefix'].'/large/'.$first_image['file_name'].')' : ''?>; padding-top: 5rem; padding-bottom: 5rem;">
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
				<p class="font-weight-light pb-3 pt-3 text-center"><?php echo $value['sdesc'] ?></p>
			<?php endif ?>

			<?php 
			if(!empty($pageContentFileViewArray[$content_id]['image']))
			{
?>
			
				<div id="carausel_<?php echo $content_id; ?>" class="carousel slide mb-3" style="max-height: 450px" data-ride="carousel">
					
				
					<ol class="carousel-indicators" style="bottom: -20%">
<?php
					
					$count = 0;
						
					foreach($pageContentFileViewArray[$content_id]['image'] as $image)
					{
						if($count == 0)
						{
							echo '		<li data-target="#carousel_'.$content_id.'_'.$count.'" data-slide-to="'.$count.'" class="active"></li>'."\n";
						} else {
							echo '		<li data-target="#carousel_'.$content_id.'_'.$count.'" data-slide-to="'.$count.'"></li>'."\n";
						}
						
						$count++;
					}
?>		
					</ol>
					
					<div class="carousel-inner" style="max-height: 100%">

<?php
					$count = 0;
					
					foreach($pageContentFileViewArray[$content_id]['image'] as $image)
					{
						if($count == 0)
						{
?>
							<div class="carousel-item active">
								<img style="margin-top: -200px" class="d-block w-100" src="<?php echo $image['image_prefix']; ?>" alt="<?php echo $image['title']; ?>">
							</div>
<?php
						} else {
?>
							<div class="carousel-item">
								<img class="d-block w-100" src="<?php echo $image['image_prefix']; ?>" alt="<?php echo $image['title']; ?>">
							</div>
<?php
						}
						
						$count++;
					}
?>
					</div>
					
					<a class="carousel-control-prev" href="#carausel_<?php echo $content_id; ?>" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next" href="#carausel_<?php echo $content_id; ?>" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				
				</div>
				
<?php
			}
?>
			
		</div>
    </div>

</section>

<!-- end of slideshow content_<?php echo $content_id; ?> -->
