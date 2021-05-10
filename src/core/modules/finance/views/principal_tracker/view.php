<div class="container">
    <div class="card card-primary">
        <div class="card-header gradient-card-header blue-gradient d-flex justify-content-between align-items-center">
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

            <table class="table" id="list_finance_principal">
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


<!--Generate Invoice Modal-->
<div class="modal fade" id="generate-invoice-modal" tabindex="-1" role="dialog" aria-labelledby="generate-invoice-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="generate-invoice-form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id=""><?php echo $term_generate_invoice ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="job_application_id">
                    <div class="md-form">
                        <input placeholder="Invoice Number" type="number" name="invoice_number" id="invoice_number" class="form-control">
                        <label for="invoice_number">Invoice Number</label>
                    </div>
                    <div class="md-form">
                        <input placeholder="Selected date" type="text" id="invoice_expected_on" name="invoice_expected_on" class="form-control datepicker">
                        <label for="invoice_expected_on"><?php echo $term_invoice_expected_date ?></label>
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

<!--Pay Invoice Modal-->
<div class="modal fade" id="pay-invoice-modal" tabindex="-1" role="dialog" aria-labelledby="payW-invoice-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="pay-invoice-form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id=""><?php echo $term_pay_invoice ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="job_application_id">
                    <div class="md-form">
                        <p>Payment Status</p>
                        <!-- Material inline 1 -->
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="materialInline1" name="status" value="paid" required>
                            <label class="form-check-label" for="materialInline1">Paid</label>
                        </div>

                        <!-- Material inline 2 -->
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" id="materialInline2" name="status" value="cancelled" required>
                            <label class="form-check-label" for="materialInline2">Cancelled</label>
                        </div>

                    </div>
                    <br>
                    <div class="md-form">
                        <input placeholder="Put some notes.." type="text" id="notes" name="notes" class="form-control" required>
                        <label for="notes"><?php echo $term_notes ?></label>
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