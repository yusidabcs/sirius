<!-- passport and visa tab -->
	<div id="passport_tab" class="tab-pane fade in show active" role="tabpanel">

        <div class="card mb-4">
            <div class="card-body card-body-cascade">

                <h4 class="card-header-title mb-3"><?php echo $term_passport_heading; ?></h4>

                <table id="passport_data" class="table table-passport-information table-sm w-100">

                    <thead>
                    
                        <tr>
                            <th style="width:5%;"></th>
                            <th style="width:10%;"><?php echo $term_passport_heading_from; ?></th>
                            <th style="width:10%;"><?php echo $term_passport_heading_to; ?></th>
                            <th style="width:15%;"><?php echo $term_passport_heading_length; ?></th>
                            <th style="width:35%;"><?php echo $term_passport_heading_passport; ?></th>
                            <th style="width:10%;"></th>
                        </tr>
                    </thead>

                    
                    <tfoot>
                    &nbsp;
                    </tfoot>
                </table>

            </div>

            <div class="card-footer text-center">
                <a href="<?php echo $passport_link.'/new'; ?>" class="btn btn-sm btn-info" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_passport_add; ?>"><i class="far fa-plus-square"></i> <?php echo $term_passport_add; ?></a>
            </div>

        </div>

    <!-- Visa Workflow -->
    <?php if($visaWorkflowList): ?>
            <div class="card card-cascade mb-4">
                <div class="card-body card-body-cascade">
                    <h3 class="card-header-title mb-3"><?php echo $term_visa_workflow_title; ?></h3>

                    <table class="table table-medical-information table-sm table-responsive-md">
                        <thead>
                            <tr>
                                <th width="15%"><?php echo $term_visa_workflow_heading_status; ?></th>
                                <th width="15%"><?php echo $term_visa_workflow_heading_level; ?></th>
                                <th width="15%"><?php echo $term_visa_workflow_heading_appointment_on; ?></th>
                                <th width="25%"><?php echo $term_visa_workflow_heading_note; ?></th>
                                <th width="20%"><?php echo $term_visa_workflow_heading_action; ?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach($visaWorkflowList as $visa_workflow): ?>
                                <tr>
                                    <td><?php echo $visa_workflow['status'] ?></td>
                                    <td>
                                        <?php
                                            switch ($visa_workflow['level']) {
                                                case '1':
                                                    echo '<span class="text-success">Normal</span>';
                                                    break;

                                                case '2':
                                                    echo '<span class="text-success">Soft Warning</span>';
                                                    break;

                                                case '3':
                                                    echo '<span class="text-success">Hard Warning</span>';
                                                    break;
                                                
                                                default:
                                                    echo '<span class="text-success">Hard Warning</span>';
                                                    break;
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo ($visa_workflow['send_notification_on'] !== '0000-00-00 00:00:00') ? date('d M Y', strtotime($visa_workflow['send_notification_on'])) : 'Not Set' ?></td>
                                    <td><?php echo $visa_workflow['notes']; ?></td>
                                    <td>
                                        <?php if($visa_workflow['status'] === 'register_visa' && $visa_workflow['send_notification_on'] !== '0000-00-00 00:00:00'): ?>
                                            <a href="#" class="btn-sm btn-info btn-set-docs" data-type="<?php echo $visa_workflow['visa_type'] ?>">
                                                <?php echo $term_visa_workflow_notification_done ?>    
                                            </a>
                                        <?php endif ?>

                                        <?php if($visa_workflow['status'] === 'docs_application' && $visa_workflow['docs_application_on'] !== '0000-00-00 00:00:00'): ?>
                                            <a href="#" class="btn-sm btn-info btn-set-interview" data-type="<?php echo $visa_workflow['visa_type'] ?>" data-country="<?php echo $visa_workflow['country_code'] ?>">
                                                <?php echo $term_visa_workflow_set_interview ?>    
                                            </a>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif ?>
    <!-- End Visa Workflow -->

        <div class="card mb-4">
            <div class="card-body card-body-cascade">
                <h3 class="card-header-title mb-3"><?php echo $term_visa_heading; ?></h3>
                <table id="visa_data" class="table table-visa-information table-sm table-responsive-md w-100">
                    <thead>
                    
                        <tr>
                            <th style="width:5%;"></th>
                            <th style="width:10%;"><?php echo $term_visa_heading_from; ?></th>
                            <th style="width:10%;"><?php echo $term_visa_heading_to; ?></th>
                            <th style="width:35%;"><?php echo $term_visa_heading_visa; ?></th>
                            <th style="width:10%;"></th>
                        </tr>
                    </thead>
                    <tfoot>
                    &nbsp;
                    </tfoot>
                </table>

            </div>

            <div class="card-footer text-center">
                <a href="<?php echo $visa_link.'/new'; ?>" class="btn btn-sm btn-info add-visa d-none" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_visa_add; ?>"><i class="far fa-plus-square"></i> <?php echo $term_visa_add; ?></a>
    
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

<!-- Visa Modal -->
<div class="modal fade" id="visaModal" tabindex="-1" role="dialog" aria-labelledby="visaModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-notify modal-info" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="model-title white-text"><?php echo $term_visa_modal_heading; ?></h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>

            <div class="modal-body">

                <table id="visa_data_details" class="table table-bordered" style="white-space: normal">

                    <tr>
                        <th class="right"><?php echo $term_visa_table_type; ?></th>
                        <td id="visa_type"></td>
                        <th class="right"><?php echo $term_visa_table_class; ?></th>
                        <td id="visa_class"></td>
                        <th class="right"><?php echo $term_visa_table_number; ?></th>
                        <td id="visa_visa_id"></td>
                    </tr>

                    <tr class="info">

                    </tr>
                    <tr>
                        <th class="right"><?php echo $term_visa_table_full_name; ?></th>
                        <td colspan="5" id="visa_full_name"></td>
                    </tr>
                    <tr>
                        <th class="right"><?php echo $term_visa_table_family_name; ?></th>
                        <td colspan="5" id="visa_family_name"></td>
                    </tr>

                    <tr>
                        <th class="right"><?php echo $term_visa_table_given_names; ?></th>
                        <td colspan="5" id="visa_given_names"></td>
                    </tr>
                    <tr>
                        <th class="right"><?php echo $term_visa_table_place_issued; ?></th>
                        <td colspan="2" id="visa_place_issued"></td>
                        <th class="right"><?php echo $term_visa_table_entry; ?></th>
                        <td colspan="2" id="visa_entry"></td>
                    </tr>

                    <tr>
                        <th class="right"><?php echo $term_visa_table_from_date; ?></th>
                        <td colspan="2" id="visa_from_date"></td>

                        <th class="right"><?php echo $term_visa_table_to_date; ?></th>
                        <td colspan="2" id="visa_to_date"></td>

                    </tr>

                    <tr>
                        <th class="right"><?php echo $term_visa_table_authority; ?></th>
                        <td colspan="2" id="visa_authority"></td>
                        <th class="right"><?php echo $term_visa_table_passport_id; ?></th>
                        <td colspan="2" id="visa_passport_id"></td>
                    </tr>

                </table>

            </div>
        </div>
    </div>
</div>
<!-- End Visa Modal -->

<!-- Passport Modal -->
<div class="modal fade" id="passportModal" tabindex="-1" role="dialog" aria-labelledby="passportModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-notify modal-info" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="model-title white-text"><?php echo $term_passport_modal_heading; ?></h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>

            </div>

            <div class="modal-body">

                <table id="passport_data_details" class="table table-bordered" style="white-space: normal">

                    <tr>
                        <th class="right"><?php echo $term_passport_table_type; ?></th>
                        <td id="passport_type"></td>
                        <th class="right"><?php echo $term_passport_table_code; ?></th>
                        <td id="passport_code"></td>
                        <th class="right"><?php echo $term_passport_table_number; ?></th>
                        <td id="passport_passport_id"></td>
                    </tr>

                    <tr class="info">

                    </tr>
                    <tr>
                        <th class="right"><?php echo $term_passport_table_full_name; ?></th>
                        <td colspan="5" id="passport_full_name"></td>
                    </tr>
                    <tr>
                        <th class="right"><?php echo $term_passport_table_family_name; ?></th>
                        <td colspan="5" id="passport_family_name"></td>
                    </tr>

                    <tr>
                        <th class="right"><?php echo $term_passport_table_given_names; ?></th>
                        <td colspan="5" id="passport_given_names"></td>
                    </tr>
                    <tr>
                        <th class="right"><?php echo $term_passport_table_nationality; ?></th>
                        <td colspan="2" id="passport_nationality"></td>
                        <th class="right"><?php echo $term_passport_table_sex; ?></th>
                        <td colspan="2" id="passport_sex"></td>
                    </tr>

                    <tr>
                        <th class="right"><?php echo $term_passport_table_dob; ?></th>
                        <td colspan="2" id="passport_dob"></td>
                        <th class="right"><?php echo $term_passport_table_pob; ?></th>
                        <td colspan="2" id="passport_pob"></td>
                    </tr>

                    <tr>
                        <th class="right"><?php echo $term_passport_table_from_date; ?></th>
                        <td colspan="2" id="passport_from_date"></td>

                        <th class="right"><?php echo $term_passport_table_to_date; ?></th>
                        <td colspan="2" id="passport_to_date"></td>

                    </tr>

                    <tr>
                        <th class="right"><?php echo $term_passport_table_place_issued; ?></th>
                        <td colspan="2" id="passport_place_issued"></td>
                        <th class="right"><?php echo $term_passport_table_authority; ?></th>
                        <td colspan="2" id="passport_authority"></td>
                    </tr>

                </table>

            </div>
        </div>
    </div>
</div>
<!-- Passport Modal -->