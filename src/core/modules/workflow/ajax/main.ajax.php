<?php
namespace core\modules\workflow\ajax;

/**
 * Final main class.
 * 
 * @final
 * @extends		module_ajax
 * @package 	workflow
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 13 Jul 2020
 */
final class main extends \core\app\classes\module_base\module_ajax {
		
	protected $optionRequired = true;
	
	public function run()
	{	
		$out = null;
		
		switch($this->option) 
		{	
			case 'TEST':			
				
				echo "<pre>";
				print_r($_POST);
				echo "</pre>";
				die('END');

				break;
			case 'delete-tracker' :
				$workflow_db = new \core\modules\workflow\models\common\db();

				$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
                if ($contentType === "application/json") {
                    $content = trim(file_get_contents("php://input"));
                    $decoded = json_decode($content, true);
					
					$type = $decoded['type'];
					$id = $decoded['id'];
					
					switch ($type) {
						case 'recruitment':
							$field = 'address_book_id';
							$table = 'workflow_recruitment_tracker';
							break;
						case 'personal_reference':
							$field = 'reference_check_id';
							$table = 'workflow_personal_reference_tracker';
							break;
						case 'profesional_reference':
							$field = 'reference_check_id';
							$table = 'workflow_profesional_reference_tracker';
							break;
						case 'english_test':
							$field = 'address_book_id';
							$table = 'workflow_english_test_tracker';
							break;
						case 'premium_service':
							$field = 'address_book_id';
							$table = 'workflow_premium_service_tracker';
							break;
						case 'interview_ready':
							$field = 'address_book_id';
							$table = 'workflow_interview_ready_tracker';
							break;
						default:
							throw new \Exception('Unsupported type: ' . $type);
							break;
					}

					$delete_tracker = $workflow_db->deleteTracker($table,$field,$id);
					if($delete_tracker>0){
						$out = [
							'message' => 'Successfully delete item.',
							'status' => 'ok',
							'type' => $type
						];
					} else {
						$out = [
							'message' => 'There is no data deleted.',
							'status' => 'warning'
						];
					}
				}
				
				break;
			default:
				throw new \Exception('Unsupported operation: ' . $this->option);
				break;
		}
		
		if(!empty($out))
		{
			header('Content-Type: application/json; charset=utf-8');
			return json_encode($out);
		} else {
			return ;
		}				
	}
	
}
?>