<div class="container">
    <div class="card">
        <div class="card-header d-flex align-items-center gradient-card-header blue-gradient">
            <h4 class="text-center"><?php echo $term_page_header ?></h4>

			<a href="#" data-toggle="modal" data-target="#modalImport" class="btn btn-sm btn-success ml-auto">
				<i class="fa fa-download"></i> Import Subscriber
			</a>
        </div>
		<div class="row justify-content-end pl-3 pr-3 pt-3">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Filter by status</label>
					<select name="status_filter" id="status_filter" class="form-control select2">
						<option value="">All status</option>
						<option value="0">Disabled</option>
						<option value="1">Active</option>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Filter by collection</label>
					<select name="collection_filter" id="collection_filter" class="form-control select2-with-search">
						<option value="">All collection</option>
						<?php foreach($collections as $key => $item): ?>
							<option value="<?php echo $item['collection_id'] ?>"><?php echo $item['name']; ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>
        <div class="card-body w-auto">

            <table class="table" id="list_subscriber">
                <thead>
					<tr>
						<th>Email</th>
						<th>Full Name</th>
						<th>Status</th>
						<th>Collection</th>
						<th>Action</th>
					</tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-labelledby="modalImportLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalImportLabel">Import Subscriber</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form id="importForm" class="form" action="#" method="post" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<input type="file" name="import_file" class="form-control" accept=".xls,.xlsx,.csv">
				</div>
				<div class="form-group">
					<label class="control-label">Select Collection</label>
					<select name="collection_id" id="collection_id" class="form-control select2-with-search">
						<option value="0">No Collection</option>
						<option value="-1">Make new collection</option>
						<?php foreach($collections as $key => $item): ?>
							<option value="<?php echo $item['collection_id'] ?>"><?php echo $item['name']; ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div id="newCollection" class="form-group d-none">
					<label for="newCollection">Input Collection Name</label>
					<input type="text" class="form-control" name="new_collection">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="btnSubmit" type="submit" class="btn btn-primary">Import</button>
			</div>
		</form>
    </div>
  </div>
</div>

<div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sendEmailModalLabel">Send Email For Subscribers</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
	  </div>
	  <div class="modal-body">
		  <div class="row">
			  	<div class="col-md-12 form-group">
					<label for="subject">Subject</label>
					<input type="text" name="subject" class="form-control">
				</div>
				<div class="col-md-12 form-group">
					<label for="subject">Banner Title</label>
					<input type="text" name="title" class="form-control">
				</div>
				<div class="col-md-12 md-form">
					<select name="subscribers[]" id="subscribers" class="mdb-select" searchable="Search" multiple>
						<option value="">All Subscriber</option>
						<?php foreach($subscribers as $key => $subscriber): ?>
						<option value="<?php echo $subscriber['email'] ?>"><?php echo $subscriber['full_name'] . ' ('.$subscriber['email'].')' ?></option>
						<?php endforeach ?>
					</select>
					<label for="subscribers">Select Subscriber</label>
				</div>
				<div class="col-md-12 md-form">
					<select name="template_name" id="template_name" class="mdb-select">
						<?php foreach($templates as $key => $template): ?>
							<option value="<?php echo str_replace('.html', '', $template['name']) ?>"><?php echo ucwords(str_replace('_', ' ', str_replace('.html', '', $template['name']))) ?></option>
						<?php endforeach ?>
					</select>
					<label for="template_name">Select Template</label>
				</div>
		  </div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id="sendEmail">Send Email</button>
		</div>
    </div>
  </div>
</div>