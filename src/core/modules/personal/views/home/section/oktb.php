<!-- passport and visa tab -->
<div id="oktb_tab" class="tab-pane fade in" role="tabpanel">

<div class="card mb-4">
    <div class="card-body card-body-cascade">
        <h3 class="card-header-title mb-3"><?php echo $term_oktb_heading; ?></h3>
        <table id="oktb_data" class="table table-oktb-information table-sm table-responsive-md w-100">
            <thead>
                <tr>
                    <th style="width:5%;"></th>
                    <th style="width:10%;"><?php echo $term_oktb_heading_from; ?></th>
                    <th style="width:10%;"><?php echo $term_oktb_heading_to; ?></th>
                    <th style="width:35%;"><?php echo $term_oktb_heading_oktb; ?></th>
                    <th style="width:10%;"></th>
                </tr>
            </thead>

            <tfoot>
            &nbsp;
            </tfoot>
        </table>

    </div>

    <div class="card-footer text-center">
        <a href="<?php echo $oktb_link.'/new'; ?>" class="btn btn-sm btn-info add-oktb d-none" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_oktb_add; ?>"><i class="far fa-plus-square"></i> <?php echo $term_oktb_add; ?></a>
    </div>

</div>
</div>
<!-- end passport tab -->

<!-- Oktb Modal -->
<div class="modal fade" id="oktbModal" tabindex="-1" role="dialog" aria-labelledby="oktbModal" aria-hidden="true">
<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
<div class="modal-content">
    <div class="modal-header">

        <h4 class="model-title white-text"><?php echo $term_oktb_modal_heading; ?></h4>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="white-text">&times;</span>
        </button>

    </div>

    <div class="modal-body">

        <table id="oktb_data_details" class="table table-bordered" style="white-space: normal">

            <tr>
                <th class="right"><?php echo $term_oktb_table_type; ?></th>
                <td colspan="2" id="oktb_type"></td>
                <th class="right"><?php echo $term_oktb_table_number; ?></th>
                <td colspan="2" id="oktb_number"></td>
            </tr>

            <tr class="info">

            </tr>

            <tr>
                <th class="right"><?php echo $term_oktb_table_from_date; ?></th>
                <td colspan="2" id="oktb_date_of_issue"></td>

                <th class="right"><?php echo $term_oktb_table_to_date; ?></th>
                <td colspan="2" id="oktb_valid_until"></td>

            </tr>

        </table>

    </div>
</div>
</div>
</div>
<!-- End Oktb Modal -->