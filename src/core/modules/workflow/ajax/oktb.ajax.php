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
final class oktb extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('oktb');
        $out = null;
        $this->personal_db = new \core\modules\personal\models\common\db();
        $this->workflow_db = new \core\modules\workflow\models\common\db();
        switch($this->option)
        {
            case 'list':

                $out = $this->workflow_db->getTrackerDatatable('workflow_oktb_tracker', array('request_file_on', 'oktb_type'));

                break;

            case 'request-file':

                if ($this->system_register->getModuleIsInstalled('workflow')) {
                    $this->common_db = new \core\modules\workflow\models\common\common;

                    if ($this->workflow_db->getActiveWorkflow('workflow_oktb_tracker', 'address_book_id', $_POST['address_book_id'], array('oktb_type' => $_POST['oktb_type']))) {
                        $this->workflow_db->updateOktbTrackers($_POST['oktb_type'], $_POST['address_book_id'], [
                            'request_file_on' => date('Y-m-d H:i:s'),
                            'request_file_by' => $_SESSION['user_id'],
                            'requirement_check_on' => date('Y-m-d H:i:s'),
                            'requirement_check_by' => $_SESSION['user_id'],
                            'notes' => 'Requesting file to CM',
                            'status' => 'request_file'
                        ]);
                    }
                }

                $send = $this->common_db->sendPersonalRequestFile($_POST['address_book_id'], 'oktb');

                if (!$send) {
                    return $this->response([
                        'message' => 'Failed sending request file message to candidate'
                    ], 500);
                }
                $out['message'] = 'Request file has been send to CM';
            break;

            case 'file-preview':

                $out = $this->personal_db->getOktbByAddressBook($this->page_options[1], $_POST['oktb_type']);
            break;

            case 'confirm-oktb':
                $oktb = $this->personal_db->getOktb($this->page_options[1]);
				if($this->system_register->getModuleIsInstalled('workflow')) {
					if ($this->workflow_db->getActiveWorkflow('workflow_oktb_tracker', 'address_book_id', $oktb['address_book_id'], array('oktb_type' => $oktb['oktb_type']))) {
						$workflow = $this->workflow_db->updateOktbTrackers($oktb['oktb_type'], $oktb['address_book_id'], [
							'accepted_on' => date('Y-m-d H:i:s'),
							'accepted_by' => $_SESSION['user_id'],
							'notes' => 'oktb document accepted',
							'status' => 'accepted'
						]);
	
						if ($workflow != 1) {
							$out['message'] = 'Could not update workflow';
							break;
						}
			
					}
				}

				$update = $this->personal_db->updateOktbStatus($this->page_options[1], 'accepted');

				if($update === 1) {
					$out['message'] = 'OKTB document has been confirmed';
				} else {
					$out['message'] = $update;
				}

			break;

			case 'reject-oktb':
                $oktb = $this->personal_db->getOktb($this->page_options[1]);
				if ($this->workflow_db->getActiveWorkflow('workflow_oktb_tracker', 'address_book_id', $oktb['address_book_id'], array('oktb_type' => $oktb['oktb_type']))) {
					$this->workflow_db->updateOktbTrackers($oktb['oktb_type'], $oktb['address_book_id'], [
						'rejected_on' => date('Y-m-d H:i:s'),
						'rejected_by' => $_SESSION['user_id'],
						'notes' => 'OKTB document rejected, wating for user upload again',
						'status' => 'rejected'
					]);
				}
				$update = $this->personal_db->updateOktbStatus($this->page_options[1], 'rejected');

				if ($update === 1) {
					$out['message'] = 'OKTB document has been rejected';
				}else {
					$out['message'] = $update;
				}

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