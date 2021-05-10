<!-- medical tab -->
<div id="sbk" class="tab-pane fade in" role="tabpanel">

    <div class="card card-cascade mb-4">

        <div class="card-body card-body-cascade">
            <h4 class="card-header-title mb-3">Seaman Books</h4>
            <table id="seaman_data" class="table w-100" id="" data-ab-id="<?php echo $address_book_id; ?>">
                <thead>
                    <tr>
                        <td></td>
                        <td>From</td>
                        <td>To</td>
                        <td>Status</td>
                        <td>Seaman Book</td>
                        <td></td>
                    </tr>
                </thead>

            </table>
        </div>

        <div class="card-footer text-center">
            <a href="<?php echo $seaman_link.'/new'; ?>" class="btn btn-sm btn-info" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_seaman_add; ?>"><i class="far fa-plus-square"></i> <?php echo $term_seaman_add; ?></a>
        </div>

    </div>
</div>
<!-- end medical tab -->
<!-- preview modal -->
<div class="modal fade" id="seamanModal" tabindex="-1" role="dialog" aria-labelledby="seamanModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-notify modal-info" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="model-title white-text"><?php echo $term_seaman_modal_heading; ?></h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>

            <div class="modal-body">

                <table id="seaman_data_details" class="table table-bordered" style="white-space: normal">

                    <tr>
                        
                        <th class="right"><?php echo $term_seaman_table_number; ?></th>
                        <td colspan="5" id="seaman_id"></td>
                    </tr>

                    <tr class="info">

                    </tr>
                    <tr>
                        <th class="right"><?php echo $term_seaman_table_full_name; ?></th>
                        <td colspan="5" id="seaman_full_name"></td>
                    </tr>
                    <tr>
                        <th class="right"><?php echo $term_seaman_table_family_name; ?></th>
                        <td colspan="5" id="seaman_family_name"></td>
                    </tr>

                    <tr>
                        <th class="right"><?php echo $term_seaman_table_given_names; ?></th>
                        <td colspan="5" id="seaman_given_names"></td>
                    </tr>
                    <tr>
                        <th class="right"><?php echo $term_seaman_table_nationality; ?></th>
                        <td colspan="2" id="seaman_nationality"></td>
                        <th class="right"><?php echo $term_seaman_table_sex; ?></th>
                        <td colspan="2" id="seaman_sex"></td>
                    </tr>

                    <tr>
                        <th class="right"><?php echo $term_seaman_table_dob; ?></th>
                        <td colspan="2" id="seaman_dob"></td>
                        <th class="right"><?php echo $term_seaman_table_pob; ?></th>
                        <td colspan="2" id="seaman_pob"></td>
                    </tr>

                    <tr>
                        <th class="right"><?php echo $term_seaman_table_from_date; ?></th>
                        <td colspan="2" id="seaman_from_date"></td>

                        <th class="right"><?php echo $term_seaman_table_to_date; ?></th>
                        <td colspan="2" id="seaman_to_date"></td>

                    </tr>

                    <tr>
                        <th class="right"><?php echo $term_seaman_table_authority; ?></th>
                        <td colspan="4" id="seaman_authority"></td>
                    </tr>

                </table>

            </div>
        </div>
    </div>
</div>
<!-- END preview -->