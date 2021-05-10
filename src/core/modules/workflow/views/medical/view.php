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

            <table class="table" id="list_medical">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Medical Type</th>
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

<div class="modal fade" id="medicalModal" role="dialog" aria-labelledby="educationModal" aria-hidden="true">
											
    <div class="modal-dialog modal-lg modal-notify modal-info" role="document">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title white-text" id="myModalLabel">Medical Document</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>
            <div class="modal-body">

                <table id="education_certificate_details" class="table table-bordered" style="white-space: normal">
                    
                    <thead>
                        <tr>
                            <th colspan="2" class="center">Medical File Preview</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td width="30%">Certificate Number</td>
                            <td id="certificate_number"></td>
                        </tr>
                        <tr>
                            <td width="30%">Doctor</td>
                            <td id="doctor"></td>
                        </tr>
                        <tr>
                            <td width="30%">Institution</td>
                            <td id="institution"></td>
                        </tr>
                        <tr>
                            <td width="30%">Certificate Date</td>
                            <td id="certificate_date"></td>
                        </tr>
                        <tr>
                            <td width="30%">Certificate Expiry</td>
                            <td id="certificate_expiry"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="file-preview"></td>
                        </tr>
                    
                    </tbody>
                    
                </table>
                
                <div class="d-flex justify-content-center">
                    <a href="#" data-medical-id="" class="btn btn-primary mr-2 confirm-medical">Confirm</a>
                    <a href="#" data-medical-id="" class="btn btn-danger reject-medical">Reject</a>
                </div>
            </div>
        </div>
    </div>
</div>
