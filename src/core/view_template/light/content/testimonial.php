
<!-- slideshow content_<?php echo $content_id; ?> -->

<section id="testimonial_<?php echo $content_id; ?>" class="article" style="padding-top: 5rem; padding-bottom: 5rem">
	<div class="container">
		<?php if(!empty($value['heading']) && $value['show_heading']): ?>
			<h2 class="text-center"><?php echo $value['heading'] ?></h2>
		<?php endif ?>

		<?php if(!empty($value['sdesc'])): ?>
			<p class="font-weight-light text-center"><?php echo $value['sdesc'] ?></p>
		<?php endif ?>
		<div class="row">
				<div class="col-lg-8 offset-2">
				
					<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
					<!-- Wrapper for slides -->
					<div class="carousel-inner">
					<?php $index = 0; ?>
						<?php foreach($pageContentFileViewArray[$content_id]['image'] as $image): ?>

						<?php
							$carousel_class = 'carousel-item';

							if($index === 0) {
								$carousel_class .= ' active';
							}
						?>
							<div class="<?php echo $carousel_class ?>">
								<div class="row" style="padding: 20px">
									<div class="col-lg-8 order-lg-2">

										<button style="border: none;"><i class="fa fa-quote-left testimonial_fa" aria-hidden="true"></i></button>
										<p class="testimonial_para">
											<?php echo $image['sdesc']; ?>
										</p><br>
									</div>
									<div class="col-lg-4 order-lg-1 d-flex align-items-center">
										<div class="row">
											<div class="col-sm-4">
												<div class="img-frame">
													<img src="<?php echo $image['image_prefix'].'/large/'.$image['file_name'] ?>" class="img-responsive" style="width: 80px">
												</div>
											</div>
											<div class="col-sm-8 d-flex align-items-center">
												<h4 class="ml-auto"><strong><?php echo $image['title']; ?></strong></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php $index++; endforeach ?>
					</div>
					<div class="d-flex controls testimonial_control justify-content-end">
						<a class="left btn btn-default testimonial_btn" href="#carousel-example-generic"
						data-slide="prev">Prev</a>
		
						<a class="right btn btn-default testimonial_btn" href="#carousel-example-generic"
						data-slide="next">Next</a>
					</div>
				</div>
		</div>
	</div>
</section>

<!-- end of slideshow content_<?php echo $content_id; ?> -->
