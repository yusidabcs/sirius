<div class="container">
    <div class="card">
        <div class="card-header gradient-card-header blue-gradient d-flex justify-content-between">
            <h4 class="text-white"><?php echo $term_header ?></h4>

        </div>
        <div class="card-body w-auto">

            <div class="container">

                <div class="row">
                    <div class="col-md-4 md-form">
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

                    <div class="col-md-4 md-form">
                        <label for="table_level_search"><?php echo $term_table_select_level ?></label>
                        <select id="table_level_search" name="table_partner_search" class="mdb-select"
                                searchable="Search">
                            <option value=""><?php echo $term_table_select_level; ?></option>
                            <?php

                            $html = '';
                            foreach ($level as $index => $item) {
                                $html .= '<option value="' . $item . '" >' . ucfirst($item) . '</option>';
                            }
                            echo $html;
                            ?>
                        </select>
                    </div>
                </div>

                <hr>

                <table class="table" id="list_interview_security_check" data-url="<?php echo $base_url?>">
                    <thead>
                    <tr>
                        <th></th>
                        <th><?php echo $term_tr_name ?></th>
                        <th><?php echo $term_tr_job_application ?></th>
                        <th><?php echo $term_tr_status ?></th>
                        <th><?php echo $term_tr_warning_level ?></th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="passport_file_modal" tabindex="-1" role="dialog" aria-labelledby="passport_file_form"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="passport_file_form" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLongTitle">Upload Passport</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="md-form">
                        <input type="hidden" class="form-control" name="job_application_id" id="job_application_id">
                        <input id="passport_file" name="passport_file" type="file" class="md-form"  accept="application/pdf" required>
                        <label for="passport_file">Upload Passport File</label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Save</button>
                </div>
            </div><!-- modal-content -->
        </form>
    </div>
</div>

<div class="modal fade" id="clearance_file_modal" tabindex="-1" role="dialog" aria-labelledby="clearance_file_modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="clearance_file_form" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLongTitle">Upload Security Check</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="file-field">
                        <div class="btn btn-primary btn-sm float-left">
                            <span>Choose file</span>
                            <input type="file" name="clearance_file" accept="image/x-png,image/gif,image/jpeg,application/pdf" required>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Upload your file">
                        </div>
                    </div>

                    <div class="md-form">
                        <label for="">Securiy Check Result</label>
                        <br>
                        <!-- Material inline 1 -->
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="materialInline1" name="status" value="accepted">
                            <label class="form-check-label" for="materialInline1">Accept</label>
                        </div>

                        <!-- Material inline 2 -->
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="materialInline2" name="status" value="denied">
                            <label class="form-check-label" for="materialInline2">Denied</label>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Save</button>
                </div>
            </div><!-- modal-content -->
        </form>
    </div>
</div>