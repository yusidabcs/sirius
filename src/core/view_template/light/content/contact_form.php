
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
	
	$bg = '';
	$classes = '';
	if (!empty($first_image)) {
		$bg = $first_image['image_prefix'].'/large/'.$first_image['file_name'];
		$classes .= ' bg-no-repeat ';
	}

?>

<!-- contact_form content_<?php echo $content_id; ?> -->

<section id="content_<?php echo $content_id; ?>" class="article" style="padding-top: 5rem; padding-bottom: 5rem">
	
	<?php if(isset($value['content'])): ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="container">
				<?php echo $value['content']; ?>
			</div>
		</div>
	</div>
	<?php endif ?>

    <div class="row">
		
		<div class="col-12 col-sm-6 col-md-6 col-lg-6 d-flex justify-content-center align-items-center showcase-text order-lg-2 text-white <?php echo $classes ?>" style="background-image: url('<?php echo $bg?>'); background-size: cover; background-position: center; background-color: rgba(66, 133, 244, 0.85); padding: 6rem;">
			<?php if(!empty($bg)): ?>
				<div class="layer"></div>
			<?php endif ?>
			<div class="position-relative">
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
					<p class="font-weight-light text-center"><?php echo $value['sdesc'] ?></p>
				<?php endif ?>
			</div>
            
		</div>
		
        <div class="col-12 col-sm-6 col-lg-6 showcase-img order-lg-1" style="padding: 6rem">
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
    </div>

</section>

<!-- end of contact_form content_<?php echo $content_id; ?> -->
