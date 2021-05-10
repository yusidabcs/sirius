<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="text-center"><?php echo $term_header ?></h4>

        </div>
        <div class="card-body w-auto">

            <div class="row">
                <div class="col-md-3 md-form">
                    <label for="table_status_search"><?php echo $term_table_select_status ?></label>
                    <select id="table_status_search" name="table_partner_search" class="mdb-select"
                            searchable="Search">
                        <option value=""><?php echo $term_table_select_status; ?></option>
                        <?php

                        $html = '';
                        foreach ($status as $index => $item) {
                            $html .= '<option value="' . $item . '" >' . ucwords(str_replace('_',' ', $item)) . '</option>';
                        }
                        echo $html;
                        ?>
                    </select>
                </div>

                <div class="col-md-3 md-form">
                    <label for="table_level_search"><?php echo $term_table_select_level ?></label>
                    <select id="table_level_search" name="table_partner_search" class="mdb-select"
                            searchable="Search">
                        <option value=""><?php echo $term_table_select_level; ?></option>
                        <?php

                        $html = '';
                        foreach ($level as $index => $item) {
                            $html .= '<option value="' . $index . '" >' . ucfirst($item) . '</option>';
                        }
                        echo $html;
                        ?>
                    </select>
                </div>

                <div class="col-md-3 md-form">
                        <!--The "from" Date Picker -->
                    <input placeholder="Selected starting date" type="text" id="startingDate" class="form-control datepicker">
                    <label for="startingDate">Filter Start Date</label>
                </div>

                <div class="col-md-3 md-form">
                <input placeholder="Selected starting date" type="text" id="endingDate" class="form-control datepicker">
                    <label for="endingDate">Filter End Date</label>
                </div>
            </div>

            <table class="table" id="list_flight">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Level</th>
                    <th>Created On</th>
                    <th>Family Name</th>
                    <th>Given Name</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="flightModal" role="dialog" aria-labelledby="educationModal" aria-hidden="true">
											
    <div class="modal-dialog modal-lg modal-notify modal-info" role="document">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title white-text" id="myModalLabel">Flight Document</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>
            <div class="modal-body">

                <table id="education_vaccination_details" class="table table-bordered" style="white-space: normal">
                    
                    <thead>
                        <tr>
                            <th colspan="2" class="center">Flight Document Preview</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td width="30%">Flight Number</td>
                            <td id="flight_number"></td>
                        </tr>
                        <tr>
                            <td width="30%">Departure Date</td>
                            <td id="departure_date"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="file-preview"></td>
                        </tr>
                    
                    </tbody>
                    
                </table>
                
                <div class="d-flex justify-content-center">
                    <a href="#" data-flight-id="" class="btn btn-primary mr-2 confirm-flight">Confirm</a>
                    <a href="#" data-flight-id="" class="btn btn-danger reject-flight">Reject</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="appointmentModal" class="modal fade in" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Set Appointment Date</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form id="appointment" action="#" method="post">
            <input type="hidden" name="address_book_id">
            <input type="hidden" name="user_id">
            <div class="modal-body">
                    <div class="form-group">
                        <label for="date">Select Appointment Date</label>
                        <input type="text" name="appointment_date" id="date" class="flatpickr form-control">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
  </div>
</div>