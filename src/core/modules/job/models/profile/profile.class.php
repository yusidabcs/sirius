<?php
namespace core\modules\job\models\profile;
	
/**
 * Final survey_actual profile class.
 *
 * @final
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 22 July 2017
 */
final class profile extends \core\app\classes\module_base\module_profile {
	
	public function __construct($address_book_id)
	{	
		parent::__construct();

		//need to know the personal link
		$menu_register = \core\app\classes\menu_register\menu_register::getInstance();
		$job_link_id = $menu_register->getModuleLink('job');
		$job_application_id = $menu_register->getModuleLink('job_application');

		if(empty($job_link_id))
		{
			$msg = "Please set a page in menu for job to continue!";
			throw new \RuntimeException($msg);
		}

        if(empty($job_application_id))
        {
            $msg = "Please set a page in menu for job application to continue!";
            throw new \RuntimeException($msg);
        }
        if(empty($_SESSION['address_book_id'])){
            $this->setViewVariables('show_profile',false);
            return;
        }
        $this->personal_db = new \core\modules\personal\models\common\db;
        $data = $this->personal_db->checkVerification($_SESSION['address_book_id']);
        if(empty($data)){
            $this->setViewVariables('show_profile',false);
            return;
        }
        if($data['status'] != 'verified'){
            $this->setViewVariables('show_profile',false);
            return;
        }
		$job_link = '/'.$job_link_id.'/';

		$this->setViewVariables('job_link',$job_link_id);
		$this->setViewVariables('job_application_link','/'.$job_application_id);
        $this->setViewVariables('show_job',true);
		$this->setViewJs('main');

		$profil_common = new \core\modules\personal\models\common\common;
		$qualification = $profil_common->checkJobQualification($_SESSION['address_book_id']);

		$job_db = new \core\modules\job\models\common\db();
		$demand = $job_db->getJobWithDemand();
		if(count($demand)>0) {
			$str_demand = implode("','",$demand);
			$str_demand = "'".$str_demand."'";
			$job_info = $job_db->getCustomizedJobSpeedyWithDemand($qualification,5,$str_demand);
		} else {
			$job_info=array();
		}
		
		$this->setViewVariables('job_info',$job_info);
        $this->setViewVariables('show_profile',true);

		return false;
	}
	
}
?>