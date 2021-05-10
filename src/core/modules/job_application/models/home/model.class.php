<?php
namespace core\modules\job_application\models\home;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 July 2017
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
        $user_db = new \core\modules\user\models\common\user_db;
	    $job_db = new \core\modules\job\models\common\db();
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        $user_id = $_SESSION['user_id'];
        $user_info_array = $user_db->selectUserDetails($user_id);
        //fix up the array
		$address_book_id = $address_book_db->getPersonhAddressBookIdFromEmail($user_info_array[$user_id]['email']);
		$this->mode = 'personal';

		if(!empty($this->page_options[0]))
        {
			if (is_numeric($this->page_options[0]))
			{
				//check user security level       
				if ( isset($_SESSION['user_security_level']) && $this->system_register->getModuleSecurityLevel(MODULE,'security_admin') <= $_SESSION['user_security_level'] )
				{
					$param_id = $this->page_options[0];
					if ($address_book_db->checkAddressID($param_id))
					{
						$this->mode = 'recruitment';
						$address_book_id = $param_id;
					}else{	
						$msg = "No data found with that address book id";
						throw new \RuntimeException($msg);
					}
				}else{
					$msg = "Only admin can access this feature!";
					throw new \RuntimeException($msg);
				}
			}else{
				$msg = "Wrong address book id parameter format";
				throw new \RuntimeException($msg);
			}
		}
		
		$this->address_book_id = $address_book_id;
		$this->jobs = $this->_getJobApplications();

        $personal_db = new \core\modules\personal\models\common\db;
        foreach ($this->jobs as $index => $job){
            $this->jobs[$index]['personal_reference_check'] = $personal_db->getLatestReferenceCheck($job['personal_reference_id']);
            $this->jobs[$index]['work_reference_check'] = $personal_db->getLatestReferenceCheck($job['work_reference_id']);
            $this->jobs[$index]['premium_service'] = $job_db->getJobPremiumServiceByABId($job['address_book_id']);
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
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useSweetAlert();
		$this->view_variables_obj->addViewVariables('address_book_id', $this->address_book_id);
		$this->view_variables_obj->addViewVariables('mode', $this->mode);
        $this->view_variables_obj->addViewVariables('jobs', $this->jobs);
        $this->view_variables_obj->addViewVariables('baseURL', $this->baseURL);
		return;
	}
		
	private function _getJobApplications()
	{
		$job_db = new \core\modules\job\models\common\db();
		$jobs = $job_db->getJobApplications($this->address_book_id);
		$tracker_db = $tracker_db = new \core\modules\workflow\models\common\db;

		foreach ($jobs as $key => $job) {
			$offer_letter_tracker = $tracker_db->getOfferLetterTracker($job['job_application_id']);

			if (($job['status'] === 'allocated' && $offer_letter_tracker['status'] === 'offer_letter') && ($offer_letter_tracker['request_offer_letter_on'] !== '0000-00-00 00:00:00' && $offer_letter_tracker['offer_letter_file_on'] === '0000-00-00 00:00:00')) {
				$jobs[$key]['request_offer_letter'] = true;
			} else {
				$jobs[$key]['request_offer_letter'] = false;
			}
		}

		return $jobs;
	}
		
}
?>