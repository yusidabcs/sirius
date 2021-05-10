
				<!-- start of address_book common avatar modal -->
<?php
						if(!empty($avatar)) {
?>										
							<!-- avatar if any -->
							<div>
								<img src="/ab/show/<?php echo $avatar[0]['filename'] ?>" alt="Current Avatar" >
								<input type="hidden" id="avatar_current" name="avatar[current]" value="<?php echo $avatar[0]['filename'] ?>">
							</div>
							<!-- end of avatar -->	
<?php
						} else {
?>		
							<div>
								<p><strong><?php echo $term_avatar_no_image ?></strong></p>
							</div>
<?php
						}
?>		

				<!-- end of address_book common avatar modal -->