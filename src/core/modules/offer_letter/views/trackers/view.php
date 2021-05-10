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
                        <select id="table_status_search" name="table_status_search" class="mdb-select"
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
                </div>
                <table class="table" id="list_interview_security_report" >
                    <thead>
                    <tr>
                        <th></th>
                        <th><?php echo $term_tr_name ?></th>
                        <th><?php echo $term_tr_job_application ?></th>
                        <th><?php echo $term_tr_status ?></th>
                        <th><?php echo $term_tr_allocated_on ?></th>
                        <th><?php echo $term_tr_warning_level ?></th>
                        <th><?php echo $term_tr_option ?></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="interview_security_modal" tabindex="-1" role="dialog" aria-labelledby="interview_security_modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="interview_security_form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLongTitle"><?php echo $term_create_security ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="md-form">
                        <div class="float-right mr-4">
                            <div id="search_ab_spinner" class="not-showing spinner-border position-absolute" role="status" aria-hidden="true"></div>
                        </div>
                        <input type="text" class="form-control" name="" id="search_ab">
                        <label for="search_ab"><?php echo $term_address_book_search?></label>
                        <div class="invalid-feedback">
                            <p class="alert alert-warning"><?php echo $term_address_book_email_not_found?></p>
                        </div>
                    </div>

                    <div class="not-showing" id="div_ab">
                        <div class="md-form">
                            <label for="address_book_id"><?php echo $term_label_address_book?></label>
                            <select class="mdb-select md-form" id="address_book_id" name="address_book_id" searchable="Search" required>
                                <option value="" disabled selected>Choose Address Book</option>
                            </select>
                        </div>
                    </div>

                    <div class="md-form">
                        <label for="level"><?php echo $term_label_level?></label>
                        <select class="mdb-select md-form" name="level" id="level" searchable="Search" required>
                            <option value="" disabled selected>Choose Level</option>
                            <?php foreach($levels as $key => $item) { ?>
                                <option value="<?php echo $item?>"><?php echo ucfirst($item) ?></option>
                            <?php } ?>
                        </select>
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

<!--Request OL Modal-->
<div class="modal fade" id="request-ol-modal" tabindex="-1" role="dialog" aria-labelledby="request-ol-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="request_ol_form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id=""><?php echo $term_request_ol ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="info_request_ol"></p>
                    <div class="md-form">
                        <input placeholder="Selected date" type="text" id="request_offer_letter_on" class="form-control datepicker">
                        <label for="request_offer_letter_on"><?php echo $term_ol_date ?></label>
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

<!--Request OL Modal-->
<div class="modal fade" id="upload-ol-modal" tabindex="-1" role="dialog" aria-labelledby="request-ol-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="upload_ol_form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id=""><?php echo $term_request_ol ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="job_application_id" name="job_application_id">
                    <div class="file-field">
                        <div class="btn btn-primary btn-sm float-left">
                            <span>Choose file</span>
                            <input type="file" name="offer_letter_file" accept="image/x-png,image/gif,image/jpeg,application/pdf" required>
                        </div>
                        <div class="file-path-wrapper">

                            <input class="file-path validate" type="text" placeholder="Upload your file" required>
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


<!-- OL Acceptance Modal-->
<div class="modal fade" id="ol-acceptance-modal" tabindex="-1" role="dialog" aria-labelledby="ol-acceptance-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="ol-acceptance-form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id=""><?php echo $term_acceptance_ol ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="job_application_id">
                    <div class="md-form">
                        <input placeholder="Selected date" type="text" id="candidate_accepted_on" name="candidate_accepted_on" class="form-control datepicker">
                        <label for="candidate_accepted_on"><?php echo $term_ol_acceptance_date ?></label>
                    </div>
                    <div class="md-form">
                        <p>Acceptance Status</p>
                        <!-- Material inline 1 -->
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="ac1" name="status" value="personal_data" required>
                            <label class="form-check-label" for="ac1">Accepted</label>
                        </div>

                        <!-- Material inline 2 -->
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="ac2" name="status" value="denied" required>
                            <label class="form-check-label" for="ac2">Denied</label>
                        </div>
                    </div>

                    <div class="md-form">
                        <input placeholder="Notes" type="text" id="accp_notes" name="notes" class="form-control ">
                        <label for="accp_notes">Notes</label>
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

<!--Personal data modal-->
<div class="modal fade" id="personal-data-modal" tabindex="-1" role="dialog" aria-labelledby="personal-data-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="personal-data-form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id=""><?php echo $term_personal_data ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="job_application_id">
                    <div class="file-field">
                        <div class="btn btn-primary btn-sm float-left">
                            <span>Choose Personal Data File</span>
                            <input type="file" name="personal_data_file" accept="image/x-png,image/gif,image/jpeg,application/pdf" required>
                        </div>
                        <div class="file-path-wrapper">

                            <input class="file-path validate" type="text" placeholder="Upload your file" required>
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


<!--Update Endorsement Modal-->
<div class="modal fade" id="upload-endorsement-modal" tabindex="-1" role="dialog" aria-labelledby="upload-endorsement-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="upload-endorsement-form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id=""><?php echo $term_update_endorsement ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="file-field">
                        <div class="btn btn-primary btn-sm float-left">
                            <span>Update Endorsement File</span>
                            <input type="file" name="endorsement_file" accept="image/x-png,image/gif,image/jpeg,application/pdf" required>
                        </div>
                        <div class="file-path-wrapper">

                            <input class="file-path validate" type="text" placeholder="Upload your file" required>
                        </div>
                    </div>

                    <div class="md-form">
                        <p>Endorsement Status</p>
                        <!-- Material inline 1 -->
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="materialInline1" name="status" value="accepted" required>
                            <label class="form-check-label" for="materialInline1">Accepted</label>
                        </div>

                        <!-- Material inline 2 -->
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="materialInline2" name="status" value="denied" required>
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

<!-- LOE Modal-->
<div class="modal fade" id="loe-modal" tabindex="-1" role="dialog" aria-labelledby="loe-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="loe-form" >
            <input type="hidden" name="job_demand_master_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id=""><?php echo $term_loe_modal ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="job_application_id">
                    <div class="md-form">
                        <input placeholder="Selected date" type="text" id="loe_date" name="loe_date" class="form-control datepicker" required>
                        <label for="candidate_accepted_on"><?php echo $term_loe_date ?></label>
                    </div>

                    <div class="md-form">
                        <input placeholder="Selected date" type="text" id="deploy_date" name="deploy_date" class="form-control datepicker" required>
                        <label for="candidate_accepted_on"><?php echo $term_deploy_date ?></label>
                    </div>

                    <div class="md-form">
                        <input placeholder="Selected date" type="text" id="deploy_date_end" name="deploy_date_end" class="form-control datepicker" required>
                        <label for="candidate_accepted_on"><?php echo $term_deploy_date_end?></label>
                    </div>

                    <div class="form-group">
                        <label for="visa_type">Visa Type</label>
                        <select name="visa_types[]" id="visa_types" class="select2">
                            <?php foreach($visa_types as $key => $visa): ?>
                                <option value="<?php echo $visa['visa_type'] ?>"><?php echo $visa['visa_type'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="oktb_types">Oktb Type</label>
                        <select name="oktb_types[]" id="oktb_types" class="select2">
                            <?php foreach($oktb_types as $key => $oktb): ?>
                                <option value="<?php echo $oktb['oktb_type'] ?>"><?php echo $oktb['oktb_type'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="stcw_types">Stcw Type</label>
                        <select name="stcw_types[]" id="stcw_types" class="select2">
                            <option value="bst">BST</option>
                            <option value="sat">SAT</option>
                            <option value="cm">CM</option>
                            <option value="dsd">DSD</option>
                            <option value="sfsat">SFSAT</option>
                            <option value="sfbt">SFTBT</option>
                            <option value="bpst">BPST</option>
                            <option value="ps">PS</option>
                            <option value="sr">SR</option>
                            <option value="eva">EFA</option>
                            <option value="fp">FP</option>
                            <option value="ff">FF</option>
                            <option value="pst">PST</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="medical_types">Medical Type</label>
                        <select name="medical_types[]" id="medical_types" class="select2">
                            <option value="rcl">RCL</option>
                            <option value="ccl">CCL</option>
                            <option value="norwegian">Norwegian</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="vaccine_types">Vaccination Type</label>
                        <select name="vaccine_types[]" id="vaccine_types" class="select2">
                            <option value="mmr">MMR</option>
                            <option value="yellow_fever">Yello Fever</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="file-field">
                        <div class="btn btn-primary btn-sm float-left">
                            <span>Choose file</span>
                            <input type="file" name="loe_file" accept="image/x-png,image/gif,image/jpeg,application/pdf" required>
                        </div>
                        <div class="file-path-wrapper">

                            <input class="file-path validate" type="text" placeholder="Upload LOE file" required>
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