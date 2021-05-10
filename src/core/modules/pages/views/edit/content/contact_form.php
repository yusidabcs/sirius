<div id="contact-form-<?php echo $key ?>" <?php echo ( !(isset($value['content_type'])) || ($value['content_type'] != 'contact_form') )? ' class="not-showing" ' : '' ?>>
	<!-- start of pages edit content ontact_form entry-<?php echo $key ?> -->
	<hr>
	<h4>Contact Form Specific Fields</h4>

	<div class="form-group">
		<label for="contact_to_name"><?php echo $term_contact_to_name_label ?></label>
		<input id="contact-to-name-<?php echo $key ?>" class="form-control" name="contact_to_name" type="text" maxlength="100" value="<?php echo isset($value['to_name']) ? $value['to_name'] : '' ?>" />
	</div>
	
	
	<div class="form-group">
		<label for="contact_to_email"><?php echo $term_contact_to_email_label ?></label>
		<input id="contact-to-email-<?php echo $key ?>" class="form-control" name="contact_to_email" type="text" maxlength="255" value="<?php echo isset($value['to_email'])? $value['to_email'] : '' ?>" />
	</div>
	
	
	<div class="form-group">
		<label for="contact_to_subject"><?php echo $term_contact_to_subject_label ?></label>
		<input id="contact-to-subject-<?php echo $key ?>" class="form-control" name="contact_to_subject" type="text" maxlength="255" value="<?php echo isset($value['to_subject']) ? $value['to_subject'] : '' ?>" />
	</div>
	
	<hr>
	
	<div class="form-group">
		<label for="contact_submitted_heading"><?php echo $term_contact_submitted_heading_label ?></label>
		<input id="contact-submitted-heading-<?php echo $key ?>" class="form-control" name="contact_submitted_heading" type="text" maxlength="100" value="<?php echo isset($value['submitted_heading']) ? $value['submitted_heading'] : '' ?>" />
	</div>

	<div class="form-group">
		<label for="contact_submitted_sdesc"><?php echo $term_contact_submitted_sdesc_label ?></label>
		<input id="contact-submitted-sdesc-<?php echo $key ?>" class="form-control" name="contact_submitted_sdesc" type="text" maxlength="255" value="<?php echo isset($value['submitted_sdesc'])? $value['submitted_sdesc'] : '' ?>" />
	</div>

	<div class="form-group">
		<label for="contact_submitted_content"><?php echo $term_contact_submitted_content_label ?></label>
		<textarea id="contact-submitted-entry-<?php echo $key ?>" class="form-control content-text-entry-<?php echo $key ?>" name="contact_submitted_content"><?php echo isset($value['submitted_content']) ? $value['submitted_content'] : '' ?></textarea>
	</div>
					
	<hr>	

	<!-- end of pages edit content contact_form entry-<?php echo $key ?> -->
</div>
