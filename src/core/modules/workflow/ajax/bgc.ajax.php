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
final class bgc extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('bgc');
        $out = null;
        $this->personal_db = new \core\modules\personal\models\common\db();
        $this->workflow_db = new \core\modules\workflow\models\common\db();
        switch($this->option)
        {
            case 'list':

                $out = $this->workflow_db->getTrackerDatatable('workflow_bgc_tracker', ['notification_on', 'confirmed_on']);

                break;

            case 'send-notification':
                $dt = date('Y-m-d H:i:s');

                $this->common_db = new \core\modules\workflow\models\common\common;
                if ($this->system_register->getModuleIsInstalled('workflow')) {
                    

                    if ($this->workflow_db->getActiveWorkflow('workflow_bgc_tracker', 'address_book_id', $_POST['address_book_id'])) {
                        $workflow = $this->workflow_db->updateTrackers('workflow_bgc_tracker', $_POST['address_book_id'], [
                            'notification_on' => $dt,
                            'notification_by' => $_SESSION['user_id'],
                            'status' => 'send_notification',
                            'notes' => 'Send notification to CM'
                        ]);

                        if ($workflow !== 1) {
                            throw new RuntimeException('Error updating workflow '.$workflow);
                        }
                    }    
                }
                $send = $this->common_db->sendBgcNotification($_POST['address_book_id']);

                if (!$send) {
                    return $this->response([
                        'message' => 'Failed send notification!'
                    ], 500);
                }
                
                $out['message'] = 'Notification has been send to CM!';
            break;

            case 'confirm-bgc':
                $dt = date('Y-m-d H:i:s');
                $this->common_db = new \core\modules\workflow\models\common\common;

                if ($this->system_register->getModuleIsInstalled('workflow')) {
                    

                    if ($this->workflow_db->getActiveWorkflow('workflow_bgc_tracker', 'address_book_id', $_POST['address_book_id'])) {
                        $workflow = $this->workflow_db->updateTrackers('workflow_bgc_tracker', $_POST['address_book_id'], [
                            'confirmed_on' => date('Y-m-d H:i:s'),
                            'confirmed_by' => $_SESSION['user_id'],
                            'notes' => 'BGC was confirm by CM, waiting for accepted/reject',
                            'status' => 'confirmed'
                        ]);

                        if ($workflow !== 1) {
                            return $this->response([
                                'message' => 'Failed updating workflow'
                            ], 500);
                        }

                        $workflow_data = $this->workflow_db->getTracker('workflow_bgc_tracker', $_POST['address_book_id'], array('notification_by'));
                        $address_book = $this->personal_db->getAddressBookByUserId($workflow_data['notification_by']);
                        $send = $this->common_db->sendAdminConfirmationBgc($address_book['address_book_id'], $_POST['address_book_id']);
        
                        if (!$send) {
                            return $this->response([
                                'message' => 'Failed sending request file message to candidate'
                            ], 500);
                        }
                    }    
                }

                $out['message'] = 'Confirmation successfully!';
            break;

            case 'accept-bgc':
				if ($this->system_register->getModuleIsInstalled('workflow')) {
					$this->workflow_db = new \core\modules\workflow\models\common\db;

					if ($this->workflow_db->getActiveWorkflow('workflow_bgc_tracker', 'address_book_id', $this->page_options[1])) {
						$workflow = $this->workflow_db->updateTrackers('workflow_bgc_tracker', $this->page_options[1], [
							'accepted_on' => date('Y-m-d H:i:s'),
							'accepted_by' => $_SESSION['user_id'],
							'notes' => 'BGC accepted',
							'status' => 'accepted'
						]);

						if ($workflow !== 1) {
							return $this->response([
								'message' => 'Error update tracker'
							], 500);
						}
					}
				}

				$out['message'] = 'BGC status has been accepted';
			break;
			case 'reject-bgc':
				if ($this->system_register->getModuleIsInstalled('workflow')) {
					$this->workflow_db = new \core\modules\workflow\models\common\db;

					if ($this->workflow_db->getActiveWorkflow('workflow_bgc_tracker', 'address_book_id', $this->page_options[1])) {
						$workflow = $this->workflow_db->updateTrackers('workflow_bgc_tracker', $this->page_options[1], [
							'rejected_on' => date('Y-m-d H:i:s'),
							'rejected_by' => $_SESSION['user_id'],
							'notes' => 'BGC document was rejected',
							'status' => 'rejected'
						]);

						if ($workflow !== 1) {
							return $this->response([
								'message' => 'Error update tracker'
							], 500);
						}
					}
				}

				$out['message'] = 'BGC status has been rejected';
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