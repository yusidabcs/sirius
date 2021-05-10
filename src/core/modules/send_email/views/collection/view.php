<div class="container">
    <div class="card">
        <div class="card-header d-flex align-items-center gradient-card-header blue-gradient">
            <h4 class="text-center"><?php echo $term_page_header ?></h4>

			<a href="#" class="btn btn-sm btn-success ml-auto" data-toggle="modal" data-target="#addCollectionModal">
				<i class="fa fa-plus"></i> Add New Collection
			</a>
        </div>
		<div class="row p-3">

		</div>
        <div class="card-body w-auto">

            <table class="table" id="list_collection">
                <thead>
					<tr>
						<th>Collection Name</th>
						<th>Total Email</th>
						<th>Action</th>
					</tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addCollectionModal" tabindex="-1" role="dialog" aria-labelledby="addCollectionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
	<form id="addCollection" method="POST">
		<div class="modal-header">
			<h5 class="modal-title" id="addCollectionModalLabel">Add new collection</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			<div class="row">
					<div class="col-md-12 form-group">
						<label for="collection_name">Collection Name</label>
						<input type="text" name="collection_name" class="form-control" required>
					</div>
					<div class="col-md-12 form-group">
						<div class="form-inline">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="source" value="from_subscriber" id="radioSubscriber" checked>
								<label class="form-check-label" for="radioSubscriber">
									From Subscriber
								</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="source" value="from_candidate" id="radioCandidate">
								<label class="form-check-label" for="radioCandidate">
									From Candidate List
								</label>
							</div>
						</div>
					</div>
					<div class="col-md-12 source-candidate d-none">
						<div class="row align-items-center">
							<div class="col-md-3">
								<label class="control-label"><?php echo $term_table_country_filter?></label>
								<select id="table_country_search" name="table_country_search" class="form-control select2-with-search">
									<option value=""><?php echo $term_table_select_all_country; ?></option>
		<?php
									$html = '';
									foreach($countryCodes as $id => $country)
									{
										$html.= '<option value="'.$id.'" >'.$country.'</option>';
									}
									echo $html;
		?>
								</select>
								
							</div>
							<div class="col-md-3">
								<label class="control-label"><?php echo $term_table_status_filter?></label>
								<select id="table_status_search" class="form-control select2">
									<option value=""><?php echo $term_table_select_all?></option>
									<?php
									$html = '';
									foreach($list_status as $key => $status)
									{
										$html.= '<option value="'.$status.'">'.ucwords($status).'</option>';
									}
									echo $html;
									?>
								</select>
								
							</div>
							<div class="col-md-3">
							<label class="control-label"><?php echo $term_filter_job_category_label ?></label>
								<select id="table_job_category_search" name="table_category_search" class="form-control select2-with-search">
									<option value=""><?php echo $term_filter_job_category; ?></option>
									<?php foreach ($job_categories as $category) { ?>
										<?php if ($category['parent_id'] == 0) { ?>
											<option value="<?php echo $category['job_speedy_category_id'] ?>"><?php echo $category['name'] ?></option>
												<?php foreach ($job_categories as $category2) { ?>
													<?php if ($category2['parent_id'] == $category['job_speedy_category_id']) { ?>
														<option value="<?php echo $category2['job_speedy_category_id'] ?>"> &nbsp;&nbsp;<?php echo $category2['name'] ?></option>
													<?php } ?>
												<?php } ?>

										<?php } ?>
									<?php } ?>
								</select>
								
							</div>
							<div class="col-md-3 mt-4">
								<button class="btn btn-primary btn-sm float-right apply-filter-candidates">Apply Filter</button>
							</div>
						</div>
						<div class="row pt-3">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label">Candidates</label>
									<select name="candidates[]" id="candidates" class="form-control select2-multiple" multiple >
										
									</select>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-md-12 source-subscriber">
						<label for="emails">Subscribers</label>
						<select name="emails[]" id="emails" class="form-control" multiple searchable>
						</select>
						
					</div>
			</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Add Collection</button>
			</div>
	</form>
    </div>
  </div>
</div>

<div class="modal fade" id="editCollectionModal" tabindex="-1" role="dialog" aria-labelledby="editCollectionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	<form id="editCollection" method="POST">
		<div class="modal-header">
			<h5 class="modal-title" id="editCollectionModalLabel">Edit collection</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
		<input type="hidden" name="collection_id">
			<div class="row">
					<div class="col-md-12 form-group">
						<label for="collection_name">Collection Name</label>
						<input type="text" name="collection_name" class="form-control">
					</div>
					<div class="col-md-12">
						<label class="control-label">Subscribers</label>
						<select name="emails[]" id="emails_edit" class="form-control" multiple>
							<?php foreach($subscribers as $key => $subscriber): ?>
								<option value="<?php echo $subscriber['email'] ?>"><?php echo $subscriber['email'] ?></option>
							<?php endforeach ?>
						</select>
						
					</div>
			</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
	</form>
    </div>
  </div>
</div>