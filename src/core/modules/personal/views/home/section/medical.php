	<!-- medical tab -->
	<div id="medical" class="tab-pane fade in" role="tabpanel">

    <!-- Medical Workflow -->
        <?php if($medicalWorkflowList): ?>
            <div class="card card-cascade mb-4">
                <div class="card-body card-body-cascade">
                    <h3 class="card-header-title mb-3"><?php echo $term_medical_workflow_title; ?></h3>

                    <table class="table table-medical-information table-sm table-responsive-md w-100">
                        <thead>
                            <tr>
                                <th width="15%"><?php echo $term_medical_workflow_heading_status; ?></th>
                                <th width="15%"><?php echo $term_medical_workflow_heading_level; ?></th>
                                <th width="15%"><?php echo $term_medical_workflow_heading_appointment_on; ?></th>
                                <th width="25%"><?php echo $term_medical_workflow_heading_note; ?></th>
                                <th width="15%"><?php echo $term_medical_workflow_heading_action; ?></th>
                                <th width="10%">&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach($medicalWorkflowList as $medical_workflow): ?>
                                <tr>
                                    <td><?php echo $medical_workflow['status'] ?></td>
                                    <td>
                                        <?php
                                            switch ($medical_workflow['level']) {
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
                                    <td><?php echo ($medical_workflow['appointment_date_on'] !== '0000-00-00 00:00:00') ? date('d M Y', strtotime($medical_workflow['appointment_date_on'])) : 'Not Set' ?></td>
                                    <td><?php echo $medical_workflow['notes']; ?></td>
                                    <td>
                                        <?php if($medical_workflow['status'] === 'request_appointment_date' && $medical_workflow['appointment_date_on'] === '0000-00-00 00:00:00'): ?>
                                            <a href="#" class="btn-sm btn-info btn-set-appointment-date" data-type="medical">
                                                <?php echo $term_medical_workflow_appointment_date ?>    
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
    <!-- End Medical Workflow -->

        <div class="card card-cascade mb-4">

            <div class="card-body card-body-cascade">
                <h3 class="card-header-title mb-3"><?php echo $term_medical_heading; ?></h3>
                <table id="medical_data" class="table table-medical-information table-sm table-responsive-md w-100">
                    <thead>
                        <tr>
                            <th width="5%">&nbsp;</th>
                            <th width="10%"><?php echo $term_medical_heading_date; ?></th>
                            <th width="10%"><?php echo $term_medical_heading_type; ?></th>
                            <th width="15%"><?php echo $term_medical_heading_status; ?></th>
                            <th width="15%"><?php echo $term_medical_heading_result; ?></th>
                            <th width="45%"><?php echo $term_medical_heading_image; ?></th>
                            <th width="10%">&nbsp;</th>
                        </tr>
                    </thead>

                    <tfoot>
                    &nbsp;
                    </tfoot>
                </table>

            </div>

            <div class="card-footer text-center">
                <a href="<?php echo $medical_link.'/new'; ?>" class="btn btn-sm btn-info" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_medical_add; ?>"><i class="far fa-plus-square"></i> <?php echo $term_medical_add; ?></a>
            </div>

        </div>

    <!-- Vaccine Workflow -->
        <?php if($vaccineWorkflowList): ?>
            <div class="card card-cascade mb-4">
                <div class="card-body card-body-cascade">
                    <h3 class="card-header-title mb-3"><?php echo $term_vaccine_workflow_title; ?></h3>

                    <table class="table table-medical-information table-sm table-responsive-md">
                        <thead>
                            <tr>
                                <th width="10%"><?php echo $term_vaccine_workflow_heading_status; ?></th>
                                <th width="10%"><?php echo $term_vaccine_workflow_heading_level; ?></th>
                                <th width="15%"><?php echo $term_vaccine_workflow_heading_appointment_on; ?></th>
                                <th width="25%"><?php echo $term_vaccine_workflow_heading_note; ?></th>
                                <th width="45%"><?php echo $term_vaccine_workflow_heading_action; ?></th>
                                <th width="10%">&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach($vaccineWorkflowList as $vaccine_workflow): ?>
                                <tr>
                                    <td><?php echo $vaccine_workflow['status'] ?></td>
                                    <td>
                                        <?php
                                            switch ($vaccine_workflow['level']) {
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
                                    <td><?php echo ($vaccine_workflow['appointment_date_on'] !== '0000-00-00 00:00:00') ? date('d M Y', strtotime($vaccine_workflow['appointment_date_on'])) : 'Not Set' ?></td>
                                    <td><?php echo $vaccine_workflow['notes'] ?></td>
                                    <td>
                                        <?php if($vaccine_workflow['status'] === 'request_appointment_date' && $vaccine_workflow['appointment_date_on'] === '0000-00-00 00:00:00'): ?>
                                            <a href="#" class="btn-sm btn-info btn-set-appointment-date" data-type="vaccine">
                                                <?php echo $term_vaccine_workflow_appointment_date ?>    
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
    <!-- End Vaccine Workflow -->
        <div class="card card-cascade mb-4">

            <div class="card-body card-body-cascade">
                <h3 class="card-header-title mb-3"><?php echo $term_vaccination_heading; ?></h3>
                <table id="vaccine_data" class="table table-vaccination-information table-sm table-responsive-md w-100">
                    <thead>
                        <tr>
                            <th width="5%"></th>
                            <th width="20%"><?php echo $term_vaccination_heading_date; ?></th>
                            <th width="15%"><?php echo $term_vaccination_heading_type; ?></th>
                            <th width="15%"><?php echo $term_vaccination_heading_status; ?></th>
                            <th width="35%"><?php echo $term_vaccination_heading_image; ?></th>
                            <th width="10%"></th>
                        </tr>
                    </thead>
                    <tfoot>
                    &nbsp;
                    </tfoot>
                </table>

            </div>

            <div class="card-footer text-center">
                <a href="<?php echo $vaccination_link.'/new'; ?>" class="btn btn-sm btn-info" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_vaccination_add; ?>"><i class="far fa-plus-square"></i> <?php echo $term_vaccination_add; ?></a>
            </div>

        </div>
	</div>
	<!-- end medical tab -->

<!-- Vaccine Modal -->
<div class="modal fade" id="vaccinationModal" tabindex="-1" role="dialog" aria-labelledby="vaccinationModal" aria-hidden="true">

<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
    <div class="modal-content">
        <div class="modal-header">

            <h4 class="model-title white-text"><?php echo $term_vaccination_modal_heading; ?></h4>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="white-text">&times;</span>
            </button>

        </div>

        <div class="modal-body">

        <table id="vaccination_vaccination_details" class="table table-bordered" style="white-space: normal">

            <thead>
            <tr>
                <th colspan="4" class="center"><?php echo $term_vaccination_table_vaccination_heading; ?></th>
            </tr>
            </thead>

            <tbody>

            <tr>
                <th class="right"><?php echo $term_vaccination_table_doctor; ?></th>
                <td colspan="3" id="vaccine_doctor"></td>
            </tr>

            <tr>
                <th class="right"><?php echo $term_vaccination_table_vaccination_number; ?></th>
                <td colspan="3" id="vaccine_vaccination_number"></td>
            </tr>

            <tr>
                <th class="right" width="15%"><?php echo $term_vaccination_table_vaccination_date; ?></th>
                <td width="35%" id="vaccine_view_vaccination_date"></td>
                <th class="right" width="15%"><?php echo $term_vaccination_table_vaccination_expiry; ?></th>
                <td width="35%" id="vaccine_view_vaccination_expiry"></td>
            </tr>

            </tbody>
        </table>

            <table id="vaccination_institution_details" class="table table-bordered" style="white-space: normal">

                <thead>
                <tr>
                    <th colspan="4" class="center"><?php echo $term_vaccination_table_institution_heading; ?></th>
                </tr>
                </thead>

                <tbody>

                <tr>
                    <th class="right"><?php echo $term_vaccination_table_institution; ?></th>
                    <td colspan="3" id="vaccine_institution"></td>
                </tr>

                <tr>
                    <th class="right"><?php echo $term_vaccination_table_country; ?></th>
                    <td id="vaccine_country"></td>
                    <th class="right"><?php echo $term_vaccination_table_phone; ?></th>
                    <td id="vaccine_phone"></td>
                </tr>

                <tr>
                    <th class="right"><?php echo $term_vaccination_table_website; ?></th>
                    <td id="vaccine_website"></td>
                    <th class="right"><?php echo $term_vaccination_table_email; ?></th>
                    <td id="vaccine_email"></td>
                </tr>

                </tbody>

            </table>

        </div>
    </div>
</div>
</div>
<!-- END Vaccine Modal -->

<!-- Medical Modal -->
<div class="modal fade" id="medicalModal" tabindex="-1" role="dialog" aria-labelledby="medicalModal" aria-hidden="true">

<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
    <div class="modal-content">
        <div class="modal-header">

            <h4 class="model-title white-text"><?php echo $term_medical_modal_heading; ?></h4>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="white-text">&times;</span>
            </button>

        </div>

        <div class="modal-body">

        <table id="medical_certificate_details" class="table table-bordered" style="white-space: normal">

            <thead>
            <tr>
                <th colspan="4" class="center"><?php echo $term_medical_table_certificate_heading; ?></th>
            </tr>
            </thead>

            <tbody>

                <tr>
                    <th class="right"><?php echo $term_medical_table_doctor; ?></th>
                    <td colspan="3" id="medical_doctor"></td>
                </tr>

                <tr>
                    <th class="right"><?php echo $term_medical_table_certificate_number; ?></th>
                    <td colspan="3" id="medical_certificate_number"></td>
                </tr>

                <tr>
                    <th class="right" width="15%"><?php echo $term_medical_table_certificate_date; ?></th>
                    <td width="35%" id="medical_view_certificate_date"></td>
                    <th class="right" width="15%"><?php echo $term_medical_table_certificate_expiry; ?></th>
                    <td width="35%" id="medical_view_certificate_expiry"></td>
                </tr>

            </tbody>
            </table>
            <hr>

            <table id="medical_institution_details" class="table table-bordered" style="white-space: normal">

                <thead>
                <tr>
                    <th colspan="4" class="center"><?php echo $term_medical_table_institution_heading; ?></th>
                </tr>
                </thead>

                <tbody>

                <tr>
                    <th class="right"><?php echo $term_medical_table_institution; ?></th>
                    <td colspan="3" id="medical_institution"></td>
                </tr>

                <tr>
                    <th class="right"><?php echo $term_medical_table_country; ?></th>
                    <td id="medical_country"></td>
                    <th class="right"><?php echo $term_medical_table_phone; ?></th>
                    <td id="medical_phone"></td>
                </tr>

                <tr>
                    <th class="right"><?php echo $term_medical_table_website; ?></th>
                    <td id="medical_website"></td>
                    <th class="right"><?php echo $term_medical_table_email; ?></th>
                    <td id="medical_email"></td>
                </tr>

                </tbody>

            </table>


        </div>
    </div>
</div>
</div>
<!-- END Medical Modal -->