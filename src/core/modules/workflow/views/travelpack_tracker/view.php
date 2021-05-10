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

            <table class="table" id="list_finance_psf">
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

<!-- generate invoice -->
<div class="modal fade" id="generate-invoice-modal" tabindex="-1" role="dialog" aria-labelledby="generate-invoice-modal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-notify modal-success" role="document">
        <form id="generate-invoice-form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title white-text"
                        id="">Generate Invoice</h5>
                    <button type="button" class="close white-text" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="job_application_id">
                    <input type="hidden" name="address_book_id">

                    <div class="md-form">
                        <input placeholder="Invoice Number" type="number" name="invoice_number" id="invoice_number" class="form-control">
                        <label for="invoice_number">Invoice Number</label>
                    </div>
                    <div class="md-form">
                        <input placeholder="Selected date" type="text" id="invoice_expected_on" name="invoice_expected_on" class="form-control datepicker">
                        <label for="invoice_expected_on">Invoice Expected Date</label>
                    </div>

                    <!-- file image -->
                        <div class="mt-3 p-1">

                            <!-- banner photo -->
                            <div id="invoice_image" class="card mb-4">

                                <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                    <i class="fa fa-image"></i> Invoice Image
                                </h4>

                                <div class="card-body">

                                    <div class="text-center not-showing">
                                        <img src="" alt="Current Invoice Image" id="invoice_img" class="img-fluid">
                                        <button class="btn btn-default btn-block not-showing mt-2" type="button" id="update_crop">Crop Photo</button>
								        <hr>
                                    </div>

                                    <div class="form-group">
                                        <label for="invoice_input">Choose Image</label>
                                        <input type="file" class="col-12" id="invoice_input" accept=".jpg,.png,.gif" >
                                        <input type="hidden" id="invoice_base64" name="invoice_base64">
                                    </div>

                                    <div id="invoice_croppie_wrap" class="mw-100 w-auto mh-100 h-auto not-showing">
                                        <div id="invoice_croppie" data-invoice-width="600" data-invoice-height="400"></div>
                                    </div>

                                    <button class="btn btn-default btn-block not-showing" type="button" id="invoice_result">Crop IT</button>

                                </div>

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

<!--Pay Invoice Modal-->
<div class="modal fade" id="pay-invoice-modal" tabindex="-1" role="dialog" aria-labelledby="payW-invoice-modal"
     aria-hidden="true">
    <div class="modal-dialog modal-notify modal-success modal-lg" role="document">
        <form id="pay-invoice-form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title white-text"
                        id="">Pay Invoice</h5>
                    <button type="button" class="close white-text" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="job_application_id">
                    <input type="hidden" name="address_book_id">
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
                        <label for="notes">Notes</label>
                    </div>
                    <!-- file image -->
                    <div class="mt-3 p-1">

                        <!-- banner photo -->
                        <div id="pay_image" class="card mb-4">

                            <h4 class="card-header amy-crisp-gradient white-text text-center py-4">
                                <i class="fa fa-image"></i> Pay Image
                            </h4>

                            <div class="card-body">

                                <div class="text-center not-showing">
                                    <img src="" alt="Current Pay Image" id="pay_img" class="img-fluid">
                                    <button class="btn btn-default btn-block not-showing mt-2" type="button" id="pay_update_crop">Crop Photo</button>
                                    <hr>
                                </div>

                                <div class="form-group">
                                    <label for="pay_input">Choose Image</label>
                                    <input type="file" class="col-12" id="pay_input" accept=".jpg,.png,.gif" >
                                    <input type="hidden" id="pay_base64" name="pay_base64">
                                </div>

                                <div id="pay_croppie_wrap" class="mw-100 w-auto mh-100 h-auto not-showing">
                                    <div id="pay_croppie" data-pay-width="600" data-pay-height="400"></div>
                                </div>

                                <button class="btn btn-default btn-block not-showing" type="button" id="pay_result">Crop IT</button>

                            </div>

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