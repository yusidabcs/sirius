<?php
namespace core\modules\job_application\models\premium;

use DateTime;

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

	protected $model_name = 'premium';
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
        $this->_checkIfSuitable();
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('premium');
		return;
	}
	
	//required function
	protected function setViewVariables()
    {
        $this->view_variables_obj->addViewVariables('myURL',$this->myURL);
        $this->view_variables_obj->addViewVariables('status',$this->status);
        $this->view_variables_obj->addViewVariables('mode',$this->mode);
        
		return;
	}

    private function _checkIfSuitable()
    {
        if(isset($this->page_options[0]))
        {
            $this->status = $this->page_options[0];
            if(!in_array($this->status, ['accept','reject']))
            {
                $msg = 'Wrong premium module parameter ';
                throw new \RuntimeException($msg);
            }

            if(isset($this->page_options[1]))
            {
                $hash = $this->page_options[1];
            } else {
                $msg = "No email hash specified! How did that happen?";
                throw new \RuntimeException($msg);
            }

        }else{
            $msg = 'Parameter not set';
            throw new \RuntimeException($msg);
        }

        $this->job_db = new \core\modules\job\models\common\db();

        //check and get data by hash
        $check = $this->job_db->getJobPremiumServiceByHash($hash);
        
        if (empty($check))
        {
            $msg = 'No data found with hash '.$hash;
            throw new \RuntimeException($msg);
        }

        //check if not already confirmed
        if ($check['verified'] == 'unknown' || $check['verified'] == 'rejected')
        {
            if ($this->status == 'accept'){
                $premium_verified = 'accepted';
            }elseif ($this->status == 'reject'){
                $premium_verified = 'rejected';
            }
            $data = array(
                'verified' => $premium_verified,
                'hash' =>$hash,
            );

            $workflow_db = new \core\modules\workflow\models\common\db();
            $personal_db = new \core\modules\personal\models\common\db();

            $address_book = $personal_db->getAddressBook($check['address_book_id']);
            $user = $personal_db->getUserBy('email', $address_book['main_email']);

            // insert workflow
            $workflow = $workflow_db->updateTrackers('workflow_premium_service_tracker', $address_book['address_book_id'], [
                'psf_verified_on' => date('Y-m-d H:i:s'),
                'psf_verified_by' => $user['user_id'],
                'level' => 1,
                'status' => 'confirm_psf',
                'notes' => 'psf has been confirmed by candidate, wating accept by administrator'
            ]);

            if ($workflow_db->getActiveWorkflow('workflow_premium_service_tracker', 'address_book_id', $address_book['address_book_id'])) {
                # code...
                if ($workflow !== 1) {
                    $msg = 'Problem in update tracker '.$workflow;
                    throw new \RuntimeException($msg);
                }
            }

            //update job premium service with inserted hash
            $affected_rows = $this->job_db->userConfirmJobPremiumService($data);
            
            if ($affected_rows != -1)
            {
                $jobapplication_common = new \core\modules\job_application\models\common\common;

                //send email thank you for taking premium service to user
                $jobapplication_common->sendPremiumServiceEmail($check['address_book_id'],false,'verified');

                //send email to LP what user choice
                $jobapplication_common->sendEmailtoLP($check['address_book_id'],$premium_verified);
            }else{
                $msg = 'Problem with update job premium service with inserted hash '.$hash;
                throw new \RuntimeException($msg);
            }

        }else{
            $this->status = 'confirmed';
        }

    }
        
    

}
?>