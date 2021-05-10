
<!-- register content_<?php echo $content_id; ?> -->

<div id="content_<?php echo $content_id; ?>" class="container">
	<div class="row mt-3">
		<div class="col">
			<div class="card border-success">
		
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
			if(!empty($pageContentFileViewArray[$content_id]['image']))
			{
?>
					<div class="col-8">
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
					<!-- Specific to Register -->
					<div class="iow-register-button center">
						<a href="<?php echo PAGES_REGISTER_URL ?>" class="btn btn-success btn-lg" role="button" aria-disabled="true"><?php echo empty($value['heading']) ? "REGISTER NOW!" : $value['heading'] ; ?></a>
					</div>


<?php  
			if(!empty($pageContentFileViewArray[$content_id]['image']))
			{
?>
					</div>
					<div class="col-4">
<?php
				foreach($pageContentFileViewArray[$content_id]['image'] as $image)
				{
?>
					<card class="card mb-3">
						<a href="<?php echo $image['image_prefix'].'/large/'.$image['file_name'];?>" data-toggle="lightbox" data-gallery="<?php echo 'register-'.$content_id; ?>" data-title="<?php echo $image['title'];?>" data-footer="<?php echo $image['sdesc'];?>" data-type="image">
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
<?php
			} 
?>
				</div>
			</div>
		</div>		
	</div>
</div>

<!-- end of register content_<?php echo $content_id; ?> -->
