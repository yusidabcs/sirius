<?php
namespace core\modules\job_application\models\applyjob;
/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 July 2017
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'applyjob';
	
	protected $redirect;
	protected $nextModel;
	
	public function __construct()
	{
		parent::__construct();

		return;
	}
	
	protected function processPost()
	{
		$this->authorize();
		$data = $_POST;
		//default rule
		$rule = [
			'address_book_id' => 'required|int',
			'job_speedy_code' => 'required|min:3|max:4',
			'personal_reference_id' => 'required|int',
			'relevance' => 'required',
		];
		//another rule if there is minimum experience
		if  ($data['min_experience'] > 0)
		{
			$rule['employment_id'] = 'required|int';
			$rule['work_reference_id'] = 'required|int';
		}

		$validator = new \core\app\classes\validator\validator($data, $rule);

		if( !$validator->hasErrors() )
		{
			$this->job_db = new \core\modules\job\models\common\db;
			if ($this->system_register->getModuleIsInstalled('workflow')) {
				$this->workflow_db = new \core\modules\workflow\models\common\db;
	
				if (!$this->workflow_db->initializeAllApplicationTrackers($data['address_book_id'])) {
					$msg = 'Error initialize trackers '.$data['address_book_id'];
					throw new \RuntimeException($msg);
				}
			}
			
			$affected_rows = $this->job_db->insertJobApplication($data);
			
			$application = $this->job_db->lastJobApplication();
			$site_a = parse_ini_file(DIR_SECURE_INI.'/site_config.ini');

			$this->job_db->sendNotificationEmailToLP($application, $application['partner_name'], $application['partner_main_email'], $site_a['SITE_EMAIL_NAME'], $site_a['SITE_EMAIL_ADD']);

			//check if success insert to job application table
			if ($affected_rows != 1)
			{
				$msg = 'Error input job application '.$data['address_book_id'];
				throw new \RuntimeException($msg);
			}

			//redirect back to candidate job-application home page if inserted by admin
			if (isset($data['mode']) && ($data['mode'] == 'recruitment'))
			{
				$this->redirect = $this->baseURL.'/home/'.$data['address_book_id'];
			}else{
				$this->redirect = $this->baseURL;			
			}
		}else{
			$msg = 'Error input job application '.implode(', ',$validator->getValidationErrors()['errors']);
			throw new \RuntimeException($msg);
		}
	}

}
?>