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
final class visa extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('visa');
        $out = null;
        $this->personal_db = new \core\modules\personal\models\common\db();
        $this->workflow_db = new \core\modules\workflow\models\common\db();
        switch($this->option)
        {
            case 'list':

                $out = $this->workflow_db->getTrackerDatatable('workflow_visa_tracker', ['send_notification_on', 'country_code', 'visa_type', 'docs_application_on', 'docs_application_date', 'upload_visa_on']);

                break;

            case 'send-notification':
                $dt = date('Y-m-d H:i:s');

                if ($this->system_register->getModuleIsInstalled('workflow')) {
                    
                    $this->common_db = new \core\modules\workflow\models\common\common;

                    if ($this->workflow_db->getActiveWorkflow('workflow_visa_tracker', 'address_book_id', $_POST['address_book_id'], array('visa_type' => $_POST['visa_type']))) {
                        $workflow = $this->workflow_db->updateVisaTrackers($_POST['visa_type'], $_POST['address_book_id'], [
                            'send_notification_on' => $dt,
                            'send_notification_by' => $_SESSION['user_id'],
                            'notes' => 'Notify CM to register visa application'
                        ]);

                        if ($workflow !== 1) {
                            throw new \RuntimeException('Error updating workflow '.$workflow);
                        }
                    }    
                }
                $send = $this->common_db->sendVisaRegistrationDate($_POST['address_book_id']);

                if (!$send) {
                    return $this->response([
                        'message' => 'Failed sending request file message to candidate'
                    ], 500);
                }
                
                $out['message'] = 'Notification has been send to CM';
            break;

            case 'set-docs-application':
                $dt = date('Y-m-d H:i:s');
                $this->common_db = new \core\modules\workflow\models\common\common;

                if ($this->system_register->getModuleIsInstalled('workflow')) {
                    
                    $filename = $this->_processPaymentReceiptImage($_POST['address_book_id'], null, $_POST['payment_receipt_base64']);

                    if ($this->workflow_db->getActiveWorkflow('workflow_visa_tracker', 'address_book_id', $_POST['address_book_id'], ['visa_type' => $_POST['visa_type']] )) {
                        $workflow = $this->workflow_db->updateVisaTrackers($_POST['visa_type'], $_POST['address_book_id'], [
                            'register_visa_on' => $dt,
                            'register_visa_by' => $_SESSION['user_id'],
                            'docs_application_date' => $_POST['docs_application_date'],
                            'booking_number' => $_POST['booking_number'],
                            'payment_receipt_file' => $filename,
                            'notes' => 'CM upload docs application',
                            'status' => 'docs_application'
                        ]);

                        if ($workflow !== 1) {
                            return $this->response([
                                'message' => 'Failed updating workflow'
                            ], 500);
                        }
                    } else {
                        return $this->response([
                                'message' => 'Failed updating workflow'
                            ], 500);
                    }    
                }

                $workflow_data = $this->workflow_db->getTracker('workflow_visa_tracker', $_POST['address_book_id'], array('send_notification_by', 'docs_application_date'), array('visa_type' => $_POST['visa_type']));

                $address_book = $this->personal_db->getAddressBookByUserId($workflow_data['send_notification_by']);
                $send = $this->common_db->sendAdminConfirmationDocsApplication($address_book['address_book_id'], $_POST['address_book_id'], 'visa', $workflow_data['docs_application_date']);

                if (!$send) {
                    return $this->response([
                        'message' => 'Failed sending request file message to candidate'
                    ], 500);
                }

                $out['message'] = 'Payment receipt has been uploaded!';
            break;

            case 'notif-docs-application':
                $dt = date('Y-m-d H:i:s');
                $this->common_db = new \core\modules\workflow\models\common\common;

                if ($this->system_register->getModuleIsInstalled('workflow')) {

                    if ($this->workflow_db->getActiveWorkflow('workflow_visa_tracker', 'address_book_id', $_POST['address_book_id'], ['visa_type' => $_POST['visa_type'], 'country_code' => $_POST['country_code']] )) {
                        $workflow = $this->workflow_db->updateVisaTrackers($_POST['visa_type'], $_POST['address_book_id'], [
                            'docs_application_on' => $dt,
                            'docs_application_by' => $_SESSION['user_id'],
                            'notes' => 'Send notification to CM to complete docs application'
                        ]);

                        if ($workflow !== 1) {
                            return $this->response([
                                'message' => 'Failed updating workflow'
                            ], 500);
                        }
                    } else {
                        return $this->response([
                                'message' => 'Failed updating workflow'
                            ], 500);
                    } 
                }

                $workflow_data = $this->workflow_db->getTracker('workflow_visa_tracker', $_POST['address_book_id'], array('docs_application_date'), array('visa_type' => $_POST['visa_type']));

                $send = $this->common_db->sendDocsApplicationReminder($_POST['address_book_id'], $workflow_data['docs_application_date']);

                if (!$send) {
                    return $this->response([
                        'message' => 'Failed sending request file message to candidate'
                    ], 500);
                }

                $out['message'] = 'Reminder has been send!';
            break;

            case 'set-interview-date':

                $this->common_db = new \core\modules\workflow\models\common\common;
                $this->workflow_db = new \core\modules\workflow\models\common\db;
                if ($this->system_register->getModuleIsInstalled('workflow')) {

                    if ($this->workflow_db->getActiveWorkflow('workflow_visa_tracker', 'address_book_id', $_POST['address_book_id'], ['visa_type' => $_POST['visa_type'], 'country_code' => $_POST['country_code']])) {
                        $this->workflow_db->updateVisaTrackers($_POST['visa_type'], $_POST['address_book_id'], [
                            'interview_date' => $_POST['interview_date'],
                            'notes' => 'CM going to interview',
                            'status' => 'interview'
                        ]);
                    } else {
                        return $this->response([
                            'message' => 'Workflow not found!'
                        ], 500);
                    }
                }

                $workflow_data = $this->workflow_db->getTracker('workflow_visa_tracker', $_POST['address_book_id'], array('interview_date', 'docs_application_by'), array('visa_type' => $_POST['visa_type']));
                
                $address_book = $this->personal_db->getAddressBookByUserId($workflow_data['docs_application_by']);
                $send = $this->common_db->sendAdminConfirmationInterviewDate($address_book['address_book_id'], $_POST['address_book_id'], 'visa interview date', $_POST['interview_date']);

                if (!$send) {
                    return $this->response([
                        'message' => 'Failed sending request file message to candidate'
                    ], 500);
                }
                $out['message'] = 'Interview date has been set, you can upload your visa document after interview date pass';
            break;

            case 'file-preview':
                $out = $this->personal_db->getPreviewVisa($this->page_options[1], $_POST['visa_type']);
            break;

            case 'accept-visa':
				if ($this->system_register->getModuleIsInstalled('workflow')) {
                    $visa = $this->personal_db->getVisa($this->page_options[1]);
					$this->workflow_db = new \core\modules\workflow\models\common\db;

					if ($this->workflow_db->getActiveWorkflow('workflow_visa_tracker', 'address_book_id', $visa['address_book_id'], ['visa_type' => $visa['type']])) {
						$workflow = $this->workflow_db->updateVisaTrackers($visa['type'], $visa['address_book_id'], [
							'accepted_on' => date('Y-m-d H:i:s'),
							'accepted_by' => $_SESSION['user_id'],
							'notes' => 'Visa document has been accepted',
							'status' => 'accepted'
						]);

						if ($workflow !== 1) {
							return $this->response([
								'message' => 'Error update tracker'
							], 500);
						}
					}
				}

				$this->personal_db->updateVisaStatus($this->page_options[1], 'accepted');
				$out['message'] = 'visa status has been accepted';
			break;
			case 'reject-visa':
				if ($this->system_register->getModuleIsInstalled('workflow')) {
					$visa = $this->personal_db->getVisa($this->page_options[1]);
					$this->workflow_db = new \core\modules\workflow\models\common\db;

					if ($this->workflow_db->getActiveWorkflow('workflow_visa_tracker', 'address_book_id', $visa['address_book_id'], ['visa_type' => $visa['type']])) {
						$workflow = $this->workflow_db->updateVisaTrackers($visa['type'], $visa['address_book_id'], [
							'rejected_on' => date('Y-m-d H:i:s'),
							'rejected_by' => $_SESSION['user_id'],
							'notes' => 'Visa document was rejected, waiting candidate to upload the new one',
							'status' => 'rejected'
						]);

						if ($workflow !== 1) {
							return $this->response([
								'message' => 'Error update tracker'
							], 500);
						}
					}
				}

				$this->personal_db->updateVisaStatus($this->page_options[1], 'rejected');
				$out['message'] = 'visa status has been rejected';
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

    private function _processPaymentReceiptImage($address_book_id,$payment_receipt_current,$payment_receipt_base64)
	{
		$filename = 'none';
		
		//decode
        $data = $payment_receipt_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($payment_receipt_current)
		{
			//delete the current payment_receipt image
			$address_book_common->deleteAddressBookFile($payment_receipt_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'payment_receipt',0);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in payment_receipt for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'payment_receipt',0);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in payment_receipt for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}

}
?>