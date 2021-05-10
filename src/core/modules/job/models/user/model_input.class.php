<?php
namespace core\modules\job\models\user;

/**
 * Final model_input class for job user 
 *
 * @final
 * @package 	job
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model_input extends \core\app\classes\module_base\module_model_input {
	
	protected $model_name = 'user';
	
	//my variables
	protected $redirect;
	protected $nextModel;
	
	public function __construct()
	{
		$user_common_common_ns = NS_MODULES.'\\user\\models\\common\\user_common';
		$this->user_common = new $user_common_common_ns();
		
		$user_db_common_ns = NS_MODULES.'\\user\\models\\common\\user_db';
		$this->user_db = new $user_db_common_ns();
		
		parent::__construct();
	
		return;
	}
	
	private function isOK($key){
		if (isset($_POST[$key]) && $_POST[$key] != '')
		{
			return trim($_POST[$key]);
		}else{
			$this->addError($key,$key .' is not set');
		}
	}
	protected function processPost()
	{
		
		//check data and insert to database
		/*
		id 
                i branch_id
                i cost_center
                v10 job_code
                v255 job_description
                i minimum_salary
                i mid_salary
                i max_salary
                ts created_at
                ts updated_at
		 
		
		*/
		var_dump($_POST);
		exit();
		
		/*
		if (isset($_POST['principal']) && $_POST['principal'] != '')
		{
			$partner_name = trim($_POST['partner_name']);
		}else{
			$this->addError('partner_name','partner name is not set');
		}

		*/
		//check check 
		if (isset($_POST['action']))
		{
			$action = $_POST['action'];
			if ( $action == 'insert' )
			{
				$data = array(
					'principal_id' => $this->isOK('principal_id'),
					'branch_id' => $this->isOK('branch_id'),
					'cost_id' => $this->isOK('cost_id'),
					'job_code' => $this->isOK('job_code'),
					'job_description' => $this->isOK('job_description'),
					'minimum_salary' => $this->isOK('minimum_salary'),
					'mid_salary' => $this->isOK('mid_salary'),
					'max_salary' => $this->isOK('max_salary')
				);
			}else if ( $action == 'edit' ){
				$data = array(
					'principal_id' => $this->isOK('e_principal_id'),
					'branch_id' => $this->isOK('e_branch_id'),
					'cost_id' => $this->isOK('e_cost_id'),
					'job_code' => $this->isOK('e_job_code'),
					'job_description' => $this->isOK('e_job_description'),
					'minimum_salary' => $this->isOK('e_minimum_salary'),
					'mid_salary' => $this->isOK('e_mid_salary'),
					'max_salary' => $this->isOK('e_max_salary')
				);
			}else if ( $action == 'delete' ){
				
			}else{
				//wrong action, no need further action pun intended
			}
		}else{
			//wrong action, no need further action pun intended
		}


		if ($this->hasErrors()){
			var_dump($this->getErrors());
			var_dump($_POST);
			exit();
		}
		$job_db = new \core\modules\job\models\common\db;
		$check = $job_db->insertJobMaster($data);
		if($check != 1)
		{
			$msg = 'Problem in insert Job Master with data '.$data;
			throw new \RuntimeException($msg);
		}
		
		return 1;
	}
			
	private function _checkUsername($username)
	{
		if($this->user_common->valueOk('username',$username))
		{
			if( $this->user_db->checkUserNameInUse($username) )
			{
				$this->addError('username',$this->system_register->site_term('USER_USERNAME_DUPLICATE_ERROR'));
			}
		} else {
			$this->addError('username',$this->system_register->site_term('USER_USERNAME_BAD_ERROR'));
		}
		return;
	}
	
	private function _checkEmail($email)
	{
		if(!$this->user_common->valueOk('email',$email))
		{
			$this->addError('email',$this->system_register->site_term('USER_EMAIL_BAD_ERROR'));
		} 
		return;
	}
	
	private function _checkSecurityLevelId($security_level_id)
	{
		if(!$this->user_common->valueOk('security_level_id',$security_level_id))
		{
			$this->addError('security level',$this->system_register->site_term('USER_SECURITY_BAD_ERROR'));
		}
		return;
	}
	
	private function _checkGroupId($group_id)
	{
		if(!$this->user_common->valueOk('group_id',$group_id))
		{
			$this->addError('group',$this->system_register->site_term('USER_GROUP_BAD_ERROR'));
		}
		return;
	}
	
	private function _checkPassword($password)
	{
		if(empty($password) || strlen($password) < 6)
		{
			$this->addError('password',$this->system_register->site_term('USER_PASSWORD_BAD_ERROR'));
		}
		return;
	}
	
}
?>