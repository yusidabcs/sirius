<div class="container">
    <div class="card">
        <div class="card-header d-flex align-items-center gradient-card-header blue-gradient">
            <h4 class="text-center"><?php echo $term_page_header ?></h4>

			<a href="#" class="btn btn-sm btn-success ml-auto" data-toggle="modal" data-target="#addReminderModal">
				<i class="fa fa-plus"></i> Add New Reminder
			</a>
        </div>
		<div class="row p-3">

		</div>
        <div class="card-body w-auto">

            <table class="table" id="list_reminder">
                <thead>
					<tr>
						<th>Reminder Name</th>
						<th>Campaign</th>
						<th>Cron Time</th>
						<th>Status</th>
						<th>Last Run</th>
						<th>Action</th>
					</tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addReminderModal" tabindex="-1" role="dialog" aria-labelledby="addReminderModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addReminderModalLabel">Add new reminder</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
	  </div>
	  <div class="modal-body">
		  <div class="row">
			  	<div class="col-md-12 form-group">
					<label for="subject">Title</label>
					<input type="text" name="title" class="form-control" required>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label class="control-label">Select Cron Time</label>
						<select name="cron_timing" id="cron_timing" class="form-control select2" required>
							<option value="daily">Daily</option>
							<option value="every_2_days">Every 2 Days</option>
							<option value="every_3_days">Every 3 Days</option>
							<option value="weekly">Weekly</option>
						</select>
					</div>
					
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label for="control-label">Select Campaign</label>
						<select name="campaign" id="campaign" class="form-control select2-with-search" required>
							<?php foreach($campaigns as $campaign): ?> ?>
								<option value="<?php echo $campaign['campaign_id'] ?>">
									<?php echo $campaign['name']; ?>
								</option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
		  </div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id="addReminder">Add Reminder</button>
		</div>
    </div>
  </div>
</div>

<div class="modal fade" id="editReminderModal" tabindex="-1" role="dialog" aria-labelledby="editReminderModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	<form id="editReminder" method="POST">
		<input type="hidden" name="reminder_id">
		<div class="modal-header">
			<h5 class="modal-title" id="editReminderModalLabel">Edit reminder</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			<div class="row">
			  	<div class="col-md-12 form-group">
					<label for="subject">Title</label>
					<input type="text" name="title" class="form-control" required>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label class="control-label">Select Cron Time</label>
						<select name="cron_timing" id="cron_timing_edit" class="form-control select2" required>
							<option value="daily">Daily</option>
							<option value="every_2_days">Every 2 Days</option>
							<option value="every_3_days">Every 3 Days</option>
							<option value="weekly">Weekly</option>
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label class="control-label">Select Campaign</label>
						<select name="campaign" id="campaign_edit" class="form-control select2-with-search" required>
							<?php foreach($campaigns as $campaign): ?> ?>
								<option value="<?php echo $campaign['campaign_id'] ?>">
									<?php echo $campaign['name']; ?>
								</option>
							<?php endforeach ?>
						</select>
					</div>
					
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