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
final class flight extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('flight');
        $out = null;
        $this->personal_db = new \core\modules\personal\models\common\db();
        $this->workflow_db = new \core\modules\workflow\models\common\db();
        switch($this->option)
        {
            case 'list':

                $out = $this->workflow_db->getTrackerDatatable('workflow_flight_tracker', ['file_uploaded_on', 'request_file_on']);

                break;

            case 'request-file':

                if ($this->system_register->getModuleIsInstalled('workflow')) {
                    $this->workflow_db = new \core\modules\workflow\models\common\db;
                    $this->common_db = new \core\modules\workflow\models\common\common;

                    if ($this->workflow_db->getActiveWorkflow('workflow_flight_tracker', 'address_book_id', $_POST['address_book_id'])) {
                        $this->workflow_db->updateTrackers('workflow_flight_tracker', $_POST['address_book_id'], [
                            'request_file_on' => date('Y-m-d H:i:s'),
                            'request_file_by' => $_SESSION['user_id'],
                            'notes' => 'requesting file to candidate'
                        ]);
                    }
                }

                $send = $this->common_db->sendPersonalRequestFile($_POST['address_book_id'], 'flight');

                if (!$send) {
                    return $this->response([
                        'message' => 'Failed sending request file message to candidate'
                    ], 500);
                }
                $out['message'] = 'Request file has been send to candidate';
            break;

            case 'file-preview':

                $out = $this->personal_db->getPreviewFlight($this->page_options[1]);
            break;

            case 'accept-flight':
				if ($this->system_register->getModuleIsInstalled('workflow')) {
                    $flight = $this->personal_db->getFlight($this->page_options[1]);
					$this->workflow_db = new \core\modules\workflow\models\common\db;

					if ($this->workflow_db->getActiveWorkflow('workflow_flight_tracker', 'address_book_id', $flight['address_book_id'])) {
						$workflow = $this->workflow_db->updateTrackers('workflow_flight_tracker', $flight['address_book_id'], [
							'accepted_on' => date('Y-m-d H:i:s'),
							'accepted_by' => $_SESSION['user_id'],
							'notes' => 'Flight document has been accepted',
							'status' => 'accepted'
						]);

						if ($workflow !== 1) {
							return $this->response([
								'message' => 'Error update tracker'
							], 500);
						}
					}
				}

				$this->personal_db->updateFlightStatus($this->page_options[1], 'accepted');
				$out['message'] = 'Flight status has been accepted';
			break;
			case 'reject-flight':
				if ($this->system_register->getModuleIsInstalled('workflow')) {
					$flight = $this->personal_db->getFlight($this->page_options[1]);
					$this->workflow_db = new \core\modules\workflow\models\common\db;

					if ($this->workflow_db->getActiveWorkflow('workflow_flight_tracker', 'address_book_id', $flight['address_book_id'])) {
						$workflow = $this->workflow_db->updateTrackers('workflow_flight_tracker', $flight['address_book_id'], [
							'rejected_on' => date('Y-m-d H:i:s'),
							'rejected_by' => $_SESSION['user_id'],
							'notes' => 'Flight document was rejected, waiting candidate to upload the new one',
							'status' => 'rejected'
						]);

						if ($workflow !== 1) {
							return $this->response([
								'message' => 'Error update tracker'
							], 500);
						}
					}
				}

				$this->personal_db->updateFlightStatus($this->page_options[1], 'rejected');
				$out['message'] = 'Flight status has been rejected';
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