<?php
namespace core\modules\workflow\models\home;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package		workflow
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 Jul 2020
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'home';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();		
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();

		$db_tracker = new \core\modules\workflow\models\common\db();

		$this->allTracker = [
			'bgc' => array(
				'heading' => 'BGC Tracker',
				'link' => '/workflow/bgc'
			),
			'education' => array(
				'heading' => 'Education Tracker',
				'link' => '/workflow/education'
			),
			'english_test' => array(
				'heading' => 'English Test Tracker',
				'link' => '/workflow/english'
			),
			'flight' => array(
				'heading' => 'Flight Tracker',
				'link' => '/workflow/flight'
			),
			'interview_ready' => array(
				'heading' => 'Interview Tracker',
				'link' => '/workflow/interview'
			),
			'medical' => array(
				'heading' => 'Medical Tracker',
				'link' => '/workflow/medical'
			),
			'oktb' => array(
				'heading' => 'OKTB Tracker',
				'link' => '/workflow/oktb'
			),
			'personal_reference' => array(
				'heading' => 'Personal Reference Tracker',
				'link' => '/workflow/personal_reference'
			),
			'recruitment' => array(
				'heading' => 'Personal Verification Tracker',
				'link' => '/workflow/personal_verification'
			),
			'police' => array(
				'heading' => 'Police Check Tracker',
				'link' => '/workflow/police'
			),
			'premium_service' => array(
				'heading' => 'Premium Service Tracker',
				'link' => '/workflow/premium_service'
			),
			'principal' => array(
				'heading' => 'Principal Invoice Tracker',
				'link' => '/workflow/principal'
			),
			'profesional_reference' => array(
				'heading' => 'Profesional Reference Tracker',
				'link' => '/workflow/profesional_reference'
			),
			'psf' => array(
				'heading' => 'PSF Tracker',
				'link' => '/workflow/psf'
			),
			'security' => array(
				'heading' => 'Security Check Tracker',
				'link' => '/workflow/security_check'
			),
			'seaman' => array(
				'heading' => 'SEAMAN Tracker',
				'link' => '/workflow/seaman'
			),
			'stcw' => array(
				'heading' => 'STCW Document Tracker',
				'link' => '/workflow/stcw'
			),
			'travelpack' => array(
				'heading' => 'Travelpack Invoice Tracker',
				'link' => '/workflow/travelpack'
			),
			'vaccination' => array(
				'heading' => 'Vaccination Tracker',
				'link' => '/workflow/vaccine'
			),
			'visa' => array(
				'heading' => 'Visa Tracker',
				'link' => '/workflow/visa'
			)
		];

		$data_count= [];
		$cols = array_keys($this->allTracker);
		foreach ($cols as $key => $item) {
			$col = 'address_book_id';
			if(in_array($item,['security','psf','principal','travelpack'])) {
				$col = 'job_application_id';
			}
			$not_in = "('accepted','paid')";
			if($item=='education'){
				$not_in = "('finish','cancel')";
			}
			$data_count = $db_tracker->getCountTracker($item, $col,$not_in);
			$this->allTracker[$item]['count'] = $data_count;
		}

		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('home');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//POST Variable
		$this->view_variables_obj->addViewVariables('post',$this->myURL);
		$this->view_variables_obj->addViewVariables('allTracker',$this->allTracker);

		if($this->input_obj)
		{
			if($this->input_obj->hasErrors())
			{
				$this->view_variables_obj->addViewVariables('errors',$this->input_obj->getErrors());
			}
			
			if($this->input_obj->hasInputs())
			{
				$array = $this->input_obj->getInputs();
				foreach($array as $key => $value)
				{
					$this->view_variables_obj->addViewVariables($key,$value);
				}
			}
		}
		return;
	}
		
}
?>