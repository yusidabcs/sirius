<div class="container">
    <div class="card">
        <div class="card-header d-flex align-items-center gradient-card-header blue-gradient">
            <h4 class="text-center"><?php echo $term_page_header ?></h4>

			<a href="#" class="btn btn-sm btn-success ml-auto" data-toggle="modal" data-target="#addCampaignModal">
				<i class="fa fa-plus"></i> Add new campaign
			</a>
        </div>
		<div class="row p-3">

		</div>
        <div class="card-body w-auto">

            <table class="table" id="list_campaign">
                <thead>
					<tr>
						<th>Name</th>
						<th>Email Template</th>
						<th>Collection</th>
						<th>Total Tracker</th>
						<th>Status</th>
						<th>Collection</th>
						<th>Created</th>
						<th>Action</th>
					</tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addCampaignModal" tabindex="-1" role="dialog" aria-labelledby="addCampaignModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCampaignModalLabel">Add new campaign</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
	  </div>
	  <div class="modal-body">
		  <div class="row">
			  	<div class="col-md-12 form-group">
					<label for="subject">Campaign Name</label>
					<input type="text" name="name" class="form-control">
				</div>
				<div class="col-md-12 form-group">
					<label class="control-label">Select Template</label>
					<select name="email_template" id="email_template" class="form-control select2-with-search">
						<?php foreach($templates as $key => $template): ?>
							<option value="<?php echo $template['name'] ?>"><?php echo ucwords(str_replace('_', ' ', str_replace('.html', '', $template['name']))) ?></option>
						<?php endforeach ?>
					</select>
					
				</div>
				<div class="col-md-12 form-group">
					<label class="control-label">Select Status</label>
					<select name="status" id="status" class="form-control select2
					">
						<option value="draf">Draf</option>
						<option value="pending">Pending</option>
						<option value="active">Active</option>
					</select>
					
				</div>
				<div class="col-md-12 form-group">
					<label for="" class="d-block">Select Source</label>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="select_collection" name="source_type" value="collection" class="custom-control-input" checked>
						<label class="custom-control-label" for="select_collection">From Collection</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="select_email" name="source_type" value="email" class="custom-control-input">
						<label class="custom-control-label" for="select_email">From Subscribers</label>
					</div>
				</div>
				<div id="collection_wrapper" class="col-md-12">
					<label class="control-label">Select Collection</label>
					<select name="collection" id="collection" class="form-control select2-with-search">
						<?php foreach($collections as $collection): ?> ?>
							<option value="<?php echo $collection['collection_id'] ?>">
								<?php echo $collection['name']; ?>
							</option>
						<?php endforeach ?>
					</select>
					
				</div>
				<div id="email_wrapper" class="col-md-12 d-none">
					<label class="control-label">Subscriber</label>
					<select name="emails[]" id="emails" class="form-control" multiple>
						<?php foreach($emails as $key => $email): ?>
							<option value="<?php echo $email['email'] ?>"><?php echo $email['full_name'] . ' ('.$email['email'].')' ?></option>
						<?php endforeach ?>
					</select>
					
				</div>
		  </div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id="addCampaign">Add Campaign</button>
		</div>
    </div>
  </div>
</div>

<div class="modal fade" id="editCampaignModal" tabindex="-1" role="dialog" aria-labelledby="editCampaignModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	<form id="editCampaign" method="POST">
		<div class="modal-header">
			<h5 class="modal-title" id="editCampaignModalLabel">Edit campaign</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			<input type="hidden" name="campaign_id">
			<div class="row">
				<div class="col-md-12 form-group">
					<label for="collection_name">Campaign Name</label>
					<input type="text" name="name" class="form-control">
				</div>
				<div class="col-md-12">
					<label class="control-label">Select Template</label>
					<select name="email_template" id="edit_email_template" class="form-control select2-with-search">
						<?php foreach($templates as $key => $template): ?>
							<option value="<?php echo $template['name'] ?>"><?php echo ucwords(str_replace('_', ' ', $template['name'])) ?></option>
						<?php endforeach ?>
					</select>
					
				</div>
				<div class="col-md-12">
					<label class="control-label">Select Status</label>
					<select name="status" id="status_edit" class="form-control select2">
						<option value="draf">Draf</option>
						<option value="pending">Pending</option>
						<option value="active">Active</option>
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

<div class="modal fade" id="campaignDetailModal" tabindex="-1" role="dialog" aria-labelledby="campaignDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="campaignDetailModalLabel">Trackers</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			<div class="row justify-content-end mb-3">
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Filter by status</label>
						<select id="status_filter" class="form-control select2">
							<option value="">All</option>
							<option value="pending">Pending</option>
							<option value="sent">Sent</option>
							<option value="opened">Opened</option>
						</select>
						
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<table id="tracker_list" class="table table-striped w-100">
						<thead>
							<tr>
								<td>Email</td>
								<td>Subject</td>
								<td>Tracker Code</td>
								<td>Status</td>
								<td>Updated on</td>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		</div>
    </div>
  </div>
</div>