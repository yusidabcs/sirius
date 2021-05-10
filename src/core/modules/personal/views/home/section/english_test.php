<div class="tab-pane fade in" id="english_tab" role="tabpanel">
    <div class="card mb-4">
        <div class="card-body">

            <h4 class="card-header-title mb-3"><?php echo $term_english_heading; ?></h4>
            <hr>
            <table id="english_data" class="table table-bordered table-sm table-responsive-md w-100">
                <thead class="">
                <tr>
                    <th><?php echo $term_english_heading_what; ?></th>
                    <th><?php echo $term_english_heading_when; ?></th>
                    <th><?php echo $term_english_heading_score; ?></th>
                    <th><?php echo $term_english_heading_cert; ?></th>
                    <th>&nbsp;</th>
                </tr>
                </thead>

                <tfoot>
                &nbsp;
                </tfoot>
            </table>
        </div>
        <div class="card-footer text-center">
            <a href="<?php echo $english_link.'/new'; ?>" class="btn btn-sm btn-info" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_english_add; ?>"><i class="far fa-plus-square"></i> <?php echo $term_english_add; ?></a>
        </div>
    </div>
</div>

<!-- preview modal -->
<div class="modal fade" id="englishModal" tabindex="-1" role="dialog" aria-labelledby="englishModal" aria-hidden="true">

<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
    <div class="modal-content">
        <div class="modal-header">

            <h4 class="model-title white-text" id="englishLabel"><?php echo $term_english_modal_heading; ?></h4>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="white-text">&times;</span>
            </button>

        </div>

        <div class="modal-body">
            <table id="english_data_details" class="table table-bordered table-sm" style="white-space: normal">
                <tbody>
                <tr>
                    <th class="right" style="width:10%;"><?php echo $term_english_table_when; ?></th>
                    <td style="width:25%;" id="english_when"></td>
                    <th class="right" style="width:10%;"><?php echo $term_english_table_type; ?></th>
                    <td style="width:30%;" id="english_type"></td>
                    <th class="right" style="width:10%;"><?php echo $term_english_table_overall; ?></th>
                    <td style="width:15%;" id="english_overall"></td>
                </tr>

                <tr>
                    <th class="right" colspan="2"><?php echo $term_english_table_breakdown; ?></th>
                    <td colspan="4" id="english_breakdown"></td>
                </tr>

                <tr>
                    <th class="right" colspan="2"><?php echo $term_english_table_where; ?></th>
                    <td colspan="4" id="english_where"></td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>
</div>

<!-- end preview -->