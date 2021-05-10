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

            <table class="table" id="list_oktb">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th width="150px">Oktb Type</th>
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

<!-- preview modal -->	
<div class="modal fade" id="oktbModal" tabindex="-1" role="dialog" aria-labelledby="educationModal" aria-hidden="true">
											
    <div class="modal-dialog modal-lg modal-notify modal-info" role="document">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title white-text" id="myModalLabel">OKTB Document</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>
            <div class="modal-body">

                <table id="education_certificate_details" class="table table-bordered" style="white-space: normal">
                    
                    <thead>
                        <tr>
                            <th colspan="2" class="center">OKTB File Preview</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td width="30%">OKTB Number</td>
                            <td id="oktb_number"></td>
                        </tr>
                        <tr>
                            <td width="30%">OKTB Type</td>
                            <td id="oktb_type"></td>
                        </tr>
                        <tr>
                            <td width="30%">Date of Issue</td>
                            <td id="date_of_issue"></td>
                        </tr>
                        <tr>
                            <td width="30%">Valid Until</td>
                            <td id="valid_until"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="file-preview"></td>
                        </tr>
                    
                    </tbody>
                    
                </table>
                
                <div class="d-flex justify-content-center">
                    <a href="#" data-oktb-id="" class="btn btn-primary mr-2 confirm-oktb">Confirm</a>
                    <a href="#" data-oktb-id="" class="btn btn-danger reject-oktb">Reject</a>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- END preview -->