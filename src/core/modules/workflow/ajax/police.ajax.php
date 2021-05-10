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
final class police extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;
    protected $police_db, $personal_db;
    public function run()
    {
        $this->authorizeAjax('police');
        $out = null;
        $this->police_db = new \core\modules\workflow\models\common\police_check_db();
        $this->personal_db = new \core\modules\personal\models\common\db();
        switch($this->option)
        {
            case 'list':

                $out = $this->police_db->getTrackersDatatable();

                break;

            case 'request-file':

                $data = $_POST;
                $rule = [
                    'address_book_id' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }

                $rs = 0;
                if ($this->system_register->getModuleIsInstalled('workflow')) {
                    $this->workflow_db = new \core\modules\workflow\models\common\db;
                    $this->common_db = new \core\modules\workflow\models\common\common;

                    if ($this->workflow_db->getActiveWorkflow('workflow_police_tracker', 'address_book_id', $data['address_book_id'])) {
                        $update_data = [
                            'request_file_on' => date('Y-m-d H:i:s'),
                            'request_file_by' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0 ,
                          ];
                          $rs = $this->police_db->updateTrackers($data['address_book_id'],$update_data);

                          $send = $this->common_db->sendPersonalRequestFile($data['address_book_id'], 'police');
                    }
                }

                
                if ($rs > 0) {
                    return $this->response([
                        'message' => 'Successfully update police check tracker.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully update police check tracker.']
                ], 400);

                break;
            case 'file-preview':
                $out = $this->personal_db->getPreviewPolice($this->page_options[1]);
            break;
            
            case 'accept-police':
				if ($this->system_register->getModuleIsInstalled('workflow')) {
                    $police = $this->personal_db->getPolice($this->page_options[1]);
					$this->workflow_db = new \core\modules\workflow\models\common\db;

					if ($this->workflow_db->getActiveWorkflow('workflow_police_tracker', 'address_book_id', $police['address_book_id'])) {
						$workflow = $this->workflow_db->updateTrackers('workflow_police_tracker', $police['address_book_id'], [
							'accepted_on' => date('Y-m-d H:i:s'),
							'accepted_by' => $_SESSION['user_id'],
							'notes' => 'Police document has been accepted',
							'status' => 'accepted'
						]);

						if ($workflow !== 1) {
							return $this->response([
								'message' => 'Error update tracker'
							], 500);
						}
					}
				}

				$this->personal_db->updatePoliceStatus($this->page_options[1], 'accepted');
				$out['message'] = 'Police status has been accepted';
			break;
			case 'reject-police':
				if ($this->system_register->getModuleIsInstalled('workflow')) {
					$police = $this->personal_db->getPolice($this->page_options[1]);
					$this->workflow_db = new \core\modules\workflow\models\common\db;

					if ($this->workflow_db->getActiveWorkflow('workflow_police_tracker', 'address_book_id', $police['address_book_id'])) {
						$workflow = $this->workflow_db->updateTrackers('workflow_police_tracker', $police['address_book_id'], [
							'rejected_on' => date('Y-m-d H:i:s'),
							'rejected_by' => $_SESSION['user_id'],
							'notes' => 'Police document was rejected, waiting candidate to upload the new one',
							'status' => 'rejected'
						]);

						if ($workflow !== 1) {
							return $this->response([
								'message' => 'Error update tracker'
							], 500);
						}
					}
				}

				$this->personal_db->updatePoliceStatus($this->page_options[1], 'rejected');
				$out['message'] = 'Police status has been rejected';
			break;
            case 'review':

                $data = $_POST;
                $rule = [
                    'address_book_id' => 'required',
                    'police_id' => 'required',
                    'status' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $address_book_id = $data['address_book_id'];
                $police_id = $data['police_id'];
                unset($data['address_book_id']);
                unset($data['police_id']);
                $rs = $this->police_db->updateTrackers($address_book_id, $data);
                $rs = $this->personal_db->reviewPolice($police_id, $data['status']);

                if ($rs > 0) {
                    return $this->response([
                        'message' => 'Successfully update police check tracker.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully update police check tracker.']
                ], 400);

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