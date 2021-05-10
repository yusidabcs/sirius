<div class="row">
	<div class="col-12" >
		
		<!-- Main Card -->
		<div class="card mb-4">
		    <h3 class="card-header blue-gradient white-text text-center py-4">
			    <?php echo ucfirst($checklist_type) ?> Checklist : <?php if(!empty($main['title'])) echo $main['title'].' '; ?><?php if(!empty($main['entity_family_name'])) echo $main['entity_family_name'].', '; ?><?php echo $main['number_given_name']; ?> <?php if(!empty($main['middle_names'])) echo $main['middle_names']; ?>
			</h3>
						
			<form method="post" id="checklist">
				
				<div class="card-body">
					<div class=" table-personal-checklist">
						<div class="row header text-center">
							<div class="col-md-8" ><b><?php echo $term_checklist_question_heading; ?></b></div>
							<div class="col-md-4 d-none d-md-block" ><b><?php echo $term_checklist_answer_heading; ?></b></div>
						</div>
<?php
						foreach($checklist as $question_id => $value)
						{
?>							
							<div class="question_row pt-2 align-items-center border-top">
								<div class="row">
									<div class="col-md-8 required">
										<?php echo $value['question'] ?> 
										<a href="#" data-toggle="tooltip" title="<?php echo $value['help'] ?>" data-placement="right"><i class="fas fa-info-circle" aria-hidden="true"></i></a>
									</div>
	<?php								
								switch ($value['answer']) 
								{
									case "yes":		    
	?>
										<div class="col-6 col-md-2">
											<div class="iow-ck-button">
												<label>
													<input type="radio" name="<?php echo $checklist_type; ?>[<?php echo $question_id ?>][answer]" value="yes" hidden="hidden" checked="checked">
													<span id="yes_<?php echo $question_id ?>" class="yes"><?php echo $term_answer_button_yes; ?></span>
												</label>
											</div>
										</div>
										
										<div class="col-6 col-md-2">
											<div class="iow-ck-button">
												<label>
													<input type="radio" name="<?php echo $checklist_type; ?>[<?php echo $question_id ?>][answer]" value="no" hidden="hidden">
													<span id="no_<?php echo $question_id ?>" class="no"><?php echo $term_answer_button_no; ?></span>
												</label>
											</div>
										</div>
	<?php
										break;
										
									case "no":
	?>
										<div class="col-6 col-md-2">
											<div class="iow-ck-button">
												<label>
													<input type="radio" name="<?php echo $checklist_type; ?>[<?php echo $question_id ?>][answer]" value="yes" hidden="hidden">
													<span id="yes_<?php echo $question_id ?>" class="yes"><?php echo $term_answer_button_yes; ?></span>
												</label>
											</div>
										</div>
										
										<div class="col-6 col-md-2">
											<div class="iow-ck-button">
												<label>
													<input type="radio" name="<?php echo $checklist_type; ?>[<?php echo $question_id ?>][answer]" value="no" hidden="hidden" checked="checked">
													<span id="no_<?php echo $question_id ?>" class="no"><?php echo $term_answer_button_no; ?></span>
												</label>
											</div>
										</div>
										
	<?php
										break;
										
									default:
	?>
										<div class="col-6 col-md-2">
											<div class="iow-ck-button">
												<label>
													<input type="radio" name="<?php echo $checklist_type; ?>[<?php echo $question_id ?>][answer]" value="yes" hidden="hidden">
													<span id="yes_<?php echo $question_id ?>" class="yes"><?php echo $term_answer_button_yes; ?></span>
												</label>
											</div>
										</div>
										
										<div class="col-6 col-md-2">
											<div id="no_<?php echo $question_id ?>" class="iow-ck-button">
												<label>
													<input type="radio" name="<?php echo $checklist_type; ?>[<?php echo $question_id ?>][answer]" value="no" hidden="hidden">
													<span id="no_<?php echo $question_id ?>" class="no"><?php echo $term_answer_button_no; ?></span>
												</label>
											</div>
										</div>

	<?php
								}
	?>			
							
							</div>
<?php
					switch ($value['answer']) 
					{
						case "yes":
?>
							<div id="text_<?php echo $question_id ?>" class="explaination_row col-md-12">
<?php
							break;
							
						default:
?>
							<div id="text_<?php echo $question_id ?>" class="explaination_row not-showing col-md-12" >
<?php							
					}
?>
								<div class="col-md-12">
									<div class="form-group">
										<label for="comment"><?php echo $term_answer_more_info; ?></label>
										<textarea class="form-control" rows="5" name="<?php echo $checklist_type; ?>[<?php echo $question_id ?>][text]"><?php echo $value['text'] ?></textarea>
									</div>
								</div>
							</div>
<?php
						}	
?>							
						</div>
					</div>
					
				</div>

				<div class="card-footer">
					<div class="row flex-column-reverse flex-lg-row">
						<div class="col-lg-6 col-xs-12 left">
							<a id="go_back" href="<?php echo $back_url ?>" class="btn btn-md btn-warning font-weight-bold btn-sm-mobile-100" role="button"><i class="fas fa-arrow-circle-left"></i> <?php echo $term_go_back; ?></a>
						</div>
						<div class="col-lg-6 col-xs-12 right">
							<button type="submit" class="btn btn-md btn-success font-weight-bold btn-sm-mobile-100"><i class="fas fa-save"></i> <?php echo $term_save_checklist; ?></button>
						</div>
					</div>
				</div>
				
			</form>
			
		</div>
	</div>
</div>
