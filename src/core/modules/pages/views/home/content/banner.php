<!-- banner content_<?php echo $content_id; ?> -->

<div id="content_<?php echo $content_id; ?>" class="container">
	<div class="row mt-3">
		<div class="col">
			<div class="card border-dark">

<?php 
			if(!empty($pageContentFileViewArray[$content_id]['image']))
			{		
				$image = array_pop($pageContentFileViewArray[$content_id]['image']);
?>	
				<img src="<?php echo $image['image_prefix']; ?>" class="card-img-top img-fluid" alt="<?php echo $image['title']; ?>">
<?php
			}
?>
				<div class="card-body">
<?php
			//If there is a Heading then we need to add it
			if(!empty($value['heading']) && $value['show_heading']) 
			{
?>	
					<h2 class="card-title"><?php echo $value['heading']; ?></h2>		
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
							<a href="<?php echo $file['file_prefix'] ;?>" title="<?php echo $file['sdesc'];?>"><?php echo $file['file_name'];?></a>
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
	</div>
</div>

<!-- end of banner content_<?php echo $content_id; ?> -->