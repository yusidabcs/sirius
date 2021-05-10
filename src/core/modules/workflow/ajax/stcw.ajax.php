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
final class stcw extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('stcw');
        $out = null;
        $this->personal_db = new \core\modules\personal\models\common\db();
        $this->workflow_db = new \core\modules\workflow\models\common\db();
        switch($this->option)
        {
            case 'list':

                $out = $this->workflow_db->getTrackerDatatable('workflow_stcw_tracker', array('request_file_on','stcw_type'));

                break;

            case 'request-file':

                if ($this->system_register->getModuleIsInstalled('workflow')) {
                    $this->common_db = new \core\modules\workflow\models\common\common;

                    if ($this->workflow_db->getActiveWorkflow('workflow_stcw_tracker', 'address_book_id', $_POST['address_book_id'])) {
                        $this->workflow_db->updateTrackers('workflow_stcw_tracker', $_POST['address_book_id'], [
                            'request_file_on' => date('Y-m-d H:i:s'),
                            'request_file_by' => $_SESSION['user_id'],
                            'notes' => 'requesting file to candidate',
                            'status' => 'request_file'
                        ]);
                    }
                }

                $send = $this->common_db->sendPersonalRequestFile($_POST['address_book_id'], 'stcw');

                if (!$send) {
                    return $this->response([
                        'message' => 'Failed sending request file message to candidate'
                    ], 500);
                }
                $out['message'] = 'Request file has been send to candidate';
            break;

            case 'file-preview':

                $out = $this->personal_db->getEducationSTCW($this->page_options[1], $_POST['stcw_type']);
            break;

            case 'confirmstcw':

				if($this->system_register->getModuleIsInstalled('workflow')) {
					if ($this->workflow_db->getActiveWorkflow('workflow_stcw_tracker', 'address_book_id', $this->page_options[1])) {
                        $stcw = $this->personal_db->getEducation($this->page_options[2]);
						$workflow = $this->workflow_db->updateTrackers('workflow_stcw_tracker', $this->page_options[1], [
							'accepted_on' => date('Y-m-d H:i:s'),
							'accepted_by' => $_SESSION['user_id'],
							'notes' => 'STCW document accepted',
							'status' => 'accepted'
						],'stcw_type',$stcw['stcw_type']);
	
						if ($workflow != 1) {
							$out['message'] = 'Could not update workflow';
							break;
						}
			
					}
				}

				$update = $this->personal_db->updateStcwDocumentStatus($this->page_options[2], 'accepted');

				if($update === 1) {
					$out['message'] = 'STWC document has been confirmed';
				} else {
					$out['message'] = $update;
				}

			break;

			case 'rejectstcw':

				if ($this->workflow_db->getActiveWorkflow('workflow_stcw_tracker', 'address_book_id', $this->page_options[1])) {
                    $stcw = $this->personal_db->getEducation($this->page_options[2]);
					$this->workflow_db->updateTrackers('workflow_stcw_tracker', $this->page_options[1], [
						'rejected_on' => date('Y-m-d H:i:s'),
						'rejected_by' => $_SESSION['user_id'],
						'notes' => 'STCW document rejected, wating for user upload again',
						'status' => 'rejected'
					],'stcw_type',$stcw['stcw_type']);
				}
				$update = $this->personal_db->updateStcwDocumentStatus($this->page_options[2], 'rejected');

				if ($update === 1) {
					$out['message'] = 'STWC document has been rejected';
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