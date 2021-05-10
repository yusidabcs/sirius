<!-- slideshow content_<?php echo $content_id; ?> -->

<div id="content_<?php echo $content_id; ?>" class="container">
	<div class="row mt-3">
		<div class="col">
			<div class="card border-dark">
		
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
							<a href="<?php echo $file['file_prefix'] ;?>" title="<?php echo $file['sdesc'];?>"><?php echo $file['file_name'];?></a>
						</li>
<?php
					}
?>
					</div>
<?php			
				}
?>

<?php 
				if(!empty($pageContentFileViewArray[$content_id]['image']))
				{
?>
				
					<div id="carausel_<?php echo $content_id; ?>" class="carousel slide mb-3" data-ride="carousel">
						
					
						<ol class="carousel-indicators">
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
						
						<div class="carousel-inner">
	
<?php
						$count = 0;
						
						foreach($pageContentFileViewArray[$content_id]['image'] as $image)
						{
							if($count == 0)
							{
?>
								<div class="carousel-item active">
									<img class="d-block w-100" src="<?php echo $image['image_prefix']; ?>" alt="<?php echo $image['title']; ?>">
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
		</div>		
	</div>
</div>

<!-- end of slideshow content_<?php echo $content_id; ?> -->