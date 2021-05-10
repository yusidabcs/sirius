<!-- ids -->
	<div id="ids" class="tab-pane fade in" role="tabpanel">

        <div class="card card-cascade mb-4">
            <div class="card-body card-body-cascade">

                <h3 class="card-header-title mb-3"><?php echo $term_idcard_heading; ?></h3>

                <table id="ids_data" class="table table-idcard-information table-sm table-responsive-md w-100">
                    <thead>
                        <tr>
                            <th style="width:5%;"></th>
                            <th style="width:15%;"><?php echo $term_idcard_heading_from; ?></th>
                            <th style="width:15%;"><?php echo $term_idcard_heading_to; ?></th>
                            <th style="width:20%;"><?php echo $term_idcard_heading_number; ?></th>
                            <th style="width:35%;"><?php echo $term_idcard_heading_idcard; ?></th>
                            <th style="width:10%;"></th>
                        </tr>
                    </thead>
                    
                    <tfoot>
                    &nbsp;
                    </tfoot>
                </table>

            </div>

            <div class="card-footer text-center">
                <a href="<?php echo $idcard_link.'/new'; ?>" class="btn btn-sm btn-info" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_idcard_add; ?>"><i class="far fa-plus-square"></i> <?php echo $term_idcard_add; ?></a>
            </div>

        </div>

        <div class="card mb-4">

            <div class="card-body card-body-cascade">
                <h3 class="card-header-title mb-3"><?php echo $term_idcheck_heading; ?></h3>
                <table id="idcheck_data" class="table table-idcheck-information table-sm table-responsive-md w-100">
                    <thead>
                        <tr>
                            <th width="5%">&nbsp;</th>
                            <th width="20%"><?php echo $term_idcheck_heading_date; ?></th>
                            <th width="30%"><?php echo $term_idcheck_heading_country; ?></th>
                            <th width="35%"><?php echo $term_idcheck_heading_image; ?></th>
                            <th width="10%">&nbsp;</th>
                        </tr>
                    </thead>
                    
                    <tfoot>
                    &nbsp;
                    </tfoot>
                </table>

            </div>

            <div class="card-footer text-center">
                <a href="<?php echo $idcheck_link.'/new'; ?>" class="btn btn-sm btn-info" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_idcheck_add; ?>"><i class="far fa-plus-square"></i> <?php echo $term_idcheck_add; ?></a>
            </div>

        </div>
	</div>
<!-- end ids tab -->

<!-- ID Check Modal -->
<div class="modal fade" id="idCheckModal" tabindex="-1" role="dialog" aria-labelledby="idcheckModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-notify modal-info" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title" id="myModalLabel"><?php echo $term_idcheck_modal_heading; ?></h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>


            </div>
            <div class="modal-body">

                <table id="idcheck_institution_details" class="table table-bordered" style="white-space: normal">

                    <thead>
                    <tr>
                        <th colspan="4" class="center"><?php echo $term_idcheck_table_institution_heading; ?></th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr>
                        <th class="right"><?php echo $term_idcheck_table_institution; ?></th>
                        <td colspan="3" id="idcheck_institution"></td>
                    </tr>

                    <tr>
                        <th class="right"><?php echo $term_idcheck_table_country; ?></th>
                        <td id="idcheck_country"></td>
                        <th class="right"><?php echo $term_idcheck_table_phone; ?></th>
                        <td id="idcheck_phone"></td>
                    </tr>

                    <tr>
                        <th class="right"><?php echo $term_idcheck_table_website; ?></th>
                        <td id="idcheck_website"></td>
                        <th class="right"><?php echo $term_idcheck_table_email; ?></th>
                        <td id="idcheck_email"></td>
                    </tr>

                    </tbody>

                </table>

                <hr>

                <table id="idcheck_idcheck_details" class="table table-bordered" style="white-space: normal">

                    <thead>
                    <tr>
                        <th colspan="4" class="center"><?php echo $term_idcheck_table_idcheck_heading; ?></th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr>
                        <th class="right"><?php echo $term_idcheck_table_idcheck_number; ?></th>
                        <td colspan="3" id="idcheck_number"></td>
                    </tr>

                    <tr>
                        <th class="right" width="15%"><?php echo $term_idcheck_table_idcheck_date; ?></th>
                        <td width="35%" id="idcheck_date"></td>
                        <th class="right" width="15%"><?php echo $term_idcheck_table_idcheck_expiry; ?></th>
                        <td width="35%" id="idcheck_expiry"></td>
                    </tr>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<!-- End ID Check Modal -->

<!-- ID Card Modal -->
<div class="modal fade" id="idCardModal" tabindex="-1" role="dialog" aria-labelledby="idcardModal" aria-hidden="true">

<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
    <div class="modal-content">
        <div class="modal-header">

            <h4 class="model-title white-text"><?php echo $term_idcard_modal_heading; ?></h4>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="white-text">&times;</span>
            </button>

        </div>

        <div class="modal-body">

            <table id="ids_data" class="table table-bordered" style="white-space: normal">

                <tr>
                    <th class="right"><?php echo $term_idcard_table_number; ?></th>
                    <td colspan="5" id="id_card_number"></td>
                </tr>

                <tr class="info">

                </tr>
                <tr>
                    <th class="right"><?php echo $term_idcard_table_full_name; ?></th>
                    <td colspan="5" id="id_card_full_name"></td>
                </tr>
                <tr>
                    <th class="right"><?php echo $term_idcard_table_family_name; ?></th>
                    <td colspan="5" id="id_card_family_name"></td>
                </tr>

                <tr>
                    <th class="right"><?php echo $term_idcard_table_given_names; ?></th>
                    <td colspan="5" id="id_card_given_names"></td>
                </tr>
                <tr>
                    <th class="right"><?php echo $term_idcard_table_authority; ?></th>
                    <td colspan="2" id="id_card_authority"></td>
                    <th class="right"><?php echo $term_idcard_table_type; ?></th>
                    <td colspan="2" id="id_card_type"></td>
                </tr>

                <tr>
                    <th class="right"><?php echo $term_idcard_table_from_date; ?></th>
                    <td colspan="2" id="id_card_from"></td>

                    <th class="right"><?php echo $term_idcard_table_to_date; ?></th>
                    <td colspan="2" id="id_card_to"></td>

                </tr>

            </table>

        </div>
    </div>
</div>
</div>
<!-- End ID Card Modal -->