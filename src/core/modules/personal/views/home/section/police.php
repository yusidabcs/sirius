<!-- police tab -->
<div id="police_check" class="tab-pane fade in" role="tabpanel">
    <div class="card  mb-4">

        <div class="card-body card-body-cascade">

            <h4 class="card-header-title mb-3"><?php echo $term_police_heading; ?></h4>

            <table id="police_data" class="table table-responsive-md w-100">
                <thead>
                    <tr>
                        <td ><?php echo $term_police_heading_police; ?></td>
                        <td>Status</td>
                        <td ><?php echo $term_police_heading_from; ?></td>
                        <td ><?php echo $term_police_heading_to; ?></td>
                        <td ></td>
                    </tr>
                </thead>

                <tfoot>
                &nbsp;
                </tfoot>
            </table>

        </div>

        <div class="card-footer text-center">
            <a href="<?php echo $police_link.'/new'; ?>" class="btn btn-sm btn-info" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_police_add; ?>"><i class="far fa-plus-square"></i> <?php echo $term_police_add; ?></a>
        </div>

    </div>
</div>
<!-- end police tab -->

<div class="modal fade" id="policeModal" tabindex="-1" role="dialog" aria-labelledby="policeModal" aria-hidden="true">
<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
    <div class="modal-content">
        <div class="modal-header">

            <h4 class="model-title white-text">Police Check Detail</h4>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="white-text">&times;</span>
            </button>

        </div>

        <div class="modal-body">

            <table id="policet_data_details" class="table table-bordered" style="white-space: normal">

                <tr class="info">

                </tr>
                <tr>
                    <th class="right"><?php echo $term_passport_table_full_name; ?></th>
                    <td colspan="5" id="police_full_name"></td>
                </tr>
                <tr>
                    <th class="right"><?php echo $term_passport_table_nationality; ?></th>
                    <td colspan="2" id="police_nationality"></td>
                    <th class="right"><?php echo $term_passport_table_sex; ?></th>
                    <td colspan="2" id="police_sex"></td>
                </tr>

                <tr>
                    <th class="right"><?php echo $term_passport_table_dob; ?></th>
                    <td colspan="2" id="police_dob"></td>
                    <th class="right"><?php echo $term_passport_table_pob; ?></th>
                    <td colspan="2" id="police_pob"></td>
                </tr>

                <tr>
                    <th class="right"><?php echo $term_passport_table_from_date; ?></th>
                    <td colspan="2" id="police_from_date"></td>

                    <th class="right"><?php echo $term_passport_table_to_date; ?></th>
                    <td colspan="2" id="police_to_date"></td>

                </tr>

                <tr>
                    <th class="right"><?php echo $term_passport_table_place_issued; ?></th>
                    <td colspan="2" id="police_place_issued"></td>
                </tr>

            </table>

        </div>
    </div>
</div>
</div>