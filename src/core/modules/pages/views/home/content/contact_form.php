<!-- contact_form content_<?php echo $content_id; ?> -->

<div id="content_<?php echo $content_id; ?>" class="container">
	<div class="row mt-3">
		<div class="col">
			<div class="card border-info">
		
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
					<div class="row">
					<div class="col-sm-8">
<?php
			} 
?>
						<!-- Specific to Contact Form -->
						<div id="contact-form">			
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
?>								<li class="list-group-item bg-light">
									<a href="<?php echo $file['file_prefix'].'/'.$file['file_name'];?>" title="<?php echo $file['sdesc'];?>"><?php echo $file['file_name'];?></a>
								</li>
<?php
					}
?>
							</div>
<?php			
				}
?>
					
							<form id="submit-feedback-form" class="pageForm" method="post" role="form">
					
								<div class="form-group">
									<label for="feedback_name"><?php echo $term_feedback_name ?></label>
									<input id="feedback_name" class="form-control" name="feedback_name" type="text" maxlength="255" placeholder="<?php echo $term_feedback_name_placeholder ?>"/>
								</div>
								
								<div class="form-group">
									<label for="feedback_email"><?php echo $term_feedback_email ?></label>
									<input id="feedback_email" class="form-control" name="feedback_email" type="text" maxlength="255" placeholder="<?php echo $term_feedback_email_placeholder ?>"/>
								</div>
								
								<div class="form-group">
									<label for="feedback_phone"><?php echo $term_feedback_phone ?></label>
									<input id="feedback_phone" class="form-control" name="feedback_phone" type="text" maxlength="255" value="" placeholder="<?php echo $term_feedback_phone_placeholder ?>"/>
								</div>

                                <?php
                                if($use_captcha)
                                {
                                    ?>
                                    <div class="input-group input-group-lg mb-3">

                                        <div class="input-group-append">
                                            <span class="input-group-text" id="captcha-code"><img src="/lib/captcha/captcha.php"></span>
                                        </div>

                                        <input name="captcha" type="text" class="form-control" aria-label="Enter Captcha Code Here" aria-describedby="captcha" required>

                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="form-group">
									<label for="feedback_text"><?php echo $term_feedback_text ?></label>
									<textarea id="feedback_text" class="form-control" name="feedback_text"></textarea>
								</div>
										
								<div class="form-group">
									<input hidden="hidden" id="link_id" name="link_id" type="text" value="<?php echo $link_id; ?>" />
									<input hidden="hidden" id="content_id" name="content_id" type="text" value="<?php echo $content_id; ?>" />
									<button id="submit-feedback-form-btn" class="btn btn-primary" type="submit"><?php echo $term_feedback_submit ?></button>
								</div>
								
								<div id="dialog"></div>
									
							</form>
							
						</div>
					
						<div id="submitted-success" class="view_option iow-callout iow-callout-info not-showing">
							<h3><?php echo $submitted_heading ?></h3>
							<h4><?php echo $submitted_sdesc ?></h4>
							<?php echo $submitted_content ?>
						</div>

<?php  
			if(!empty($pageContentFileViewArray[$content_id]['image']))
			{
?>
					</div>
					<div class="col-sm-4 center">
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
					</div>
<?php
			} 
?>
				</div>
			</div>
		</div>		
	</div>
</div>

<!-- end of contact_form content_<?php echo $content_id; ?> -->