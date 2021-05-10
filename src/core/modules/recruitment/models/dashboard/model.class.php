<?php
namespace core\modules\recruitment\models\dashboard;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package		recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 23 Nov 2018
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'dashboard';
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
		//we need personal db
		$this->personal_db = new \core\modules\personal\models\common\db;
		$this->job_db = new \core\modules\job\models\common\db;

		//get verification count to be displayed on dashboard
        $this->total_candidate = $this->personal_db->getAllCandidateCount($this->useEntity ? $this->entity['address_book_ent_id'] : false);
        $this->unverified_count = $this->personal_db->getVerificationCount('request',$this->useEntity ? $this->entity['address_book_ent_id'] : false);
        $this->rejected_count = $this->personal_db->getVerificationCount('rejected',$this->useEntity ? $this->entity['address_book_ent_id'] : false);
        $this->applyjob_count = $this->job_db->getAllJobApplicationsCount('applied',$this->useEntity ? $this->entity['address_book_ent_id'] : false);
        $this->total_accepted_job = $this->job_db->getAllJobApplicationsCount('accepted',$this->useEntity ? $this->entity['address_book_ent_id'] : false);
        $this->total_interview_job = $this->job_db->getAllJobApplicationsCount('interview',$this->useEntity ? $this->entity['address_book_ent_id'] : false);
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('dashboard');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{

        $this->view_variables_obj->useChartJs();
        $this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useSweetAlert();

		//POST Variable
		$this->view_variables_obj->addViewVariables('post',$this->myURL);
		
		$this->view_variables_obj->addViewVariables('total_candidate',$this->total_candidate);
		$this->view_variables_obj->addViewVariables('unverified_count',$this->unverified_count);
		$this->view_variables_obj->addViewVariables('rejected_count',$this->rejected_count);
		$this->view_variables_obj->addViewVariables('applyjob_count',$this->applyjob_count);
		$this->view_variables_obj->addViewVariables('total_accepted_job',$this->total_accepted_job);
		$this->view_variables_obj->addViewVariables('total_interview_job',$this->total_interview_job);

		$this->view_variables_obj->addViewVariables('base_url',$this->baseURL);
		$this->view_variables_obj->addViewVariables('verification_link',$this->baseURL.'/request');
		$this->view_variables_obj->addViewVariables('rejected_link',$this->baseURL.'/rejected');
		$this->view_variables_obj->addViewVariables('applicant_link',$this->baseURL.'/applicant');

		return;
	}
		
}
?>