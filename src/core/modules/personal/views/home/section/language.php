<!-- Languages tab -->
	<div id="lang" class="tab-pane fade in" role="tabpanel">
		<div class="row">
			<!-- start language panel -->
			<div class="col-lg-12">

				<div class="card card-cascade mb-4">	
					<div class="view view-cascade gradient-card-header blue-gradient">
						<!-- Title -->
						<h3 class="card-header-title mb-3"><?php echo $term_language_heading; ?></h3>
					</div>
					<div class="card-body card-body-cascade table-responsive">
						<table id="languages-data" class="table table-passport-information table-sm">
							<thead>
								<tr>
									<th><?php echo $term_language_heading_name; ?></th>
									<th><?php echo $term_language_heading_level; ?></th>
									<th><?php echo $term_language_heading_years; ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($language as $languageCode_id => $lang)
								{
?>
									<tr>
										<td><?php echo $languageCodes[$languageCode_id]; ?><input type="hidden" name="keep[]" value="<?php echo $languageCode_id; ?>" /></td>
										<td><?php $term = 'term_language_level_'.$lang['level']; echo $$term; ?></td>
										<td><?php $term = 'term_language_experience_'.$lang['experience']; echo $$term; ?></td>
									</tr>
<?php									
								}
?>
							</tbody>
							<tfoot>
								&nbsp;
							</tfoot>
						</table>
					</div>
					<div class="card-footer text-center">
						<a href="<?php echo $language_link; ?>" class="btn btn-md btn-primary" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_language_edit; ?>"><i class="fas fa-edit"></i> <?php echo $term_language_edit; ?></a>
					</div>
				</div>
			</div>
			<!-- end language panel -->

			
		</div>
	</div>
<!-- end Englishs tab -->