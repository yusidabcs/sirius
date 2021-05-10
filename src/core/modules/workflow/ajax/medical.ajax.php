<?php
namespace core\modules\workflow\ajax;

/**
 * Final main class.
 *
 * @final
 * @extends		module_ajax
 * @package 	finance
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 15 Jun 2020
 */
final class medical extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('medical');
        $out = null;
        $this->personal_db = new \core\modules\personal\models\common\db();
        $this->workflow_db = new \core\modules\workflow\models\common\db();
        switch($this->option)
        {
            case 'list':

                $out = $this->workflow_db->getTrackerDatatable('workflow_medical_tracker', ['request_appointment_date_on', 'request_file_on','medical_type']);

                break;

            case 'request-appointment-date':
                $dt = date('Y-m-d H:i:s');

                if ($this->system_register->getModuleIsInstalled('workflow')) {
                    
                    $this->common_db = new \core\modules\workflow\models\common\common;

                    if ($this->workflow_db->getActiveWorkflow('workflow_medical_tracker', 'address_book_id', $_POST['address_book_id'])) {
                        $workflow = $this->workflow_db->updateTrackers('workflow_medical_tracker', $_POST['address_book_id'], [
                            'request_appointment_date_on' => $dt,
                            'request_appointment_date_by' => $_SESSION['user_id'],
                            'notes' => 'Sending appointment date request to candidate'
                        ]);

                        if ($workflow !== 1) {
                            throw new RuntimeException('Error updating workflow '.$workflow);
                        }
                    }    
                }
                $send = $this->common_db->sendMedicalAppointmentDate($_POST['address_book_id']);

                if (!$send) {
                    return $this->response([
                        'message' => 'Failed sending request appointment message to candidate'
                    ], 500);
                }
                
                $out['message'] = 'Request appointment has been send to candidate';
            break;

            case 'set-appointment-date':
                $dt = date('Y-m-d H:i:s');
                $this->common_db = new \core\modules\workflow\models\common\common;

                if ($this->system_register->getModuleIsInstalled('workflow')) {
                    

                    if ($this->workflow_db->getActiveWorkflow('workflow_medical_tracker', 'address_book_id', $_POST['address_book_id'])) {
                        $workflow = $this->workflow_db->updateTrackers('workflow_medical_tracker', $_POST['address_book_id'], [
                            'appointment_date_on' => $_POST['appointment_date'],
                            'appointment_date_by' => $_SESSION['user_id'],
                            'notes' => 'Appointment date has been set, waiting response from administrator'
                        ]);

                        if ($workflow !== 1) {
                            return $this->response([
                                'message' => 'Failed updating workflow'
                            ], 500);
                        }

                        $workflow_data = $this->workflow_db->getTracker('workflow_medical_tracker', $_POST['address_book_id'], array('request_appointment_date_by'));
                        $address_book = $this->personal_db->getAddressBookByUserId($workflow_data['request_appointment_date_by']);
                        $send = $this->common_db->sendAdminConfirmationAppointment($address_book['address_book_id'], $_POST['address_book_id'], 'medical', $dt);
        
                        if (!$send) {
                            return $this->response([
                                'message' => 'Failed sending request file message to candidate'
                            ], 500);
                        }
                    }    
                }

                $out['message'] = 'Appointment date has been set!';
            break;

            case 'request-file':

                if ($this->system_register->getModuleIsInstalled('workflow')) {
                    $this->workflow_db = new \core\modules\workflow\models\common\db;
                    $this->common_db = new \core\modules\workflow\models\common\common;

                    if ($this->workflow_db->getActiveWorkflow('workflow_medical_tracker', 'address_book_id', $_POST['address_book_id'])) {
                        $this->workflow_db->updateTrackers('workflow_medical_tracker', $_POST['address_book_id'], [
                            'request_file_on' => date('Y-m-d H:i:s'),
                            'request_file_by' => $_SESSION['user_id'],
                            'notes' => 'requesting file to candidate'
                        ]);
                    }
                }

                $send = $this->common_db->sendPersonalRequestFile($_POST['address_book_id'], 'medical');

                if (!$send) {
                    return $this->response([
                        'message' => 'Failed sending request file message to candidate'
                    ], 500);
                }
                $out['message'] = 'Request file has been send to candidate';
            break;

            case 'file-preview':

                $out = $this->personal_db->getPreviewMedical($this->page_options[1], $_POST['medical_type']);
            break;

            case 'accept-medical':
				if ($this->system_register->getModuleIsInstalled('workflow')) {
                    $medical = $this->personal_db->getMedical($this->page_options[1]);
					$this->workflow_db = new \core\modules\workflow\models\common\db;

					if ($this->workflow_db->getActiveWorkflow('workflow_medical_tracker', 'address_book_id', $medical['address_book_id'])) {
						$workflow = $this->workflow_db->updateTrackers('workflow_medical_tracker', $medical['address_book_id'], [
							'accepted_on' => date('Y-m-d H:i:s'),
							'accepted_by' => $_SESSION['user_id'],
							'notes' => 'Medical document has been accepted',
							'status' => 'accepted'
						],'medical_type',$medical['type']);

						if ($workflow !== 1) {
							return $this->response([
								'message' => 'Error update tracker'
							], 500);
						}
					}
				}

				$this->personal_db->updateMedicalStatus($this->page_options[1], 'accepted');
				$out['message'] = 'Medical status has been accepted';
			break;
			case 'reject-medical':
				if ($this->system_register->getModuleIsInstalled('workflow')) {
					$medical = $this->personal_db->getMedical($this->page_options[1]);
					$this->workflow_db = new \core\modules\workflow\models\common\db;

					if ($this->workflow_db->getActiveWorkflow('workflow_medical_tracker', 'address_book_id', $medical['address_book_id'])) {
						$workflow = $this->workflow_db->updateTrackers('workflow_medical_tracker', $medical['address_book_id'], [
							'rejected_on' => date('Y-m-d H:i:s'),
							'rejected_by' => $_SESSION['user_id'],
							'notes' => 'Medical document was rejected, waiting candidate to upload the new one',
							'status' => 'rejected'
						],'medical_type',$medical['type']);

						if ($workflow !== 1) {
							return $this->response([
								'message' => 'Error update tracker'
							], 500);
						}
					}
				}

				$this->personal_db->updateMedicalStatus($this->page_options[1], 'rejected');
				$out['message'] = 'Medical status has been rejected';
			break;

            default:
                throw new \Exception('Unsupported operation: ' . $this->option);
                break;
        }

        if(!empty($out))
        {
            return $this->response($out);
        } else {
            return ;
        }
    }

}
?>