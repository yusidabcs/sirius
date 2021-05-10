<?php
namespace core\modules\personal\models\oktb;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 18 December 2017
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'oktb';
	
	//my variables
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
		//if Session Resume Address_book_id is not set then we should not be here
		if(!isset($_SESSION['personal']['address_book_id']))
		{
			header('Location: '.$this->baseURL);
			exit();
		} else {
			$personal_id = $_SESSION['personal']['address_book_id'];
		}
		
		
		//fix active
		$_POST['active'] = isset($_POST['active']) ? $_POST['active'] : '';
		
		//process the inputs
		$error_a = $this->_checkData();
		
		if(!empty($error_a))
		{
			//load up the errors
			foreach($error_a as $key => $value)
			{
				$this->addError($key,$value);
			}
			
			//set up oktb information
			$oktb = array(
				'oktb_number' => $_POST['oktb_number'],
				'oktb_type' => $_POST['oktb_type'],
				'date_of_issue' => $_POST['date_of_issue'],
				'valid_until' => $_POST['valid_until'],
				'filename' => $_POST['filename'],
				'active' => $_POST['active']
			);
			
			//information for the form
			$this->addInput('oktb',$oktb);

		} else { //no errors so process
		
			if ($this->system_register->getModuleIsInstalled('workflow')) {
				$workflow_db = new \core\modules\workflow\models\common\db;

				if ($workflow_db->getActiveWorkflow('workflow_oktb_tracker', 'address_book_id', $personal_id, array(['oktb_type' => $_POST['oktb_type']]))) {
					$workflow = $workflow_db->updateOktbTrackers($_POST['oktb_type'], $personal_id, [
						'filename' => $_POST['filename'],
						'notes' => 'OKTB document has been uploaded, waiting for review',
						'status' => 'review_file'
					]);
				}
			}
			
			$oktb_number = $_POST['oktb_number'];
			$oktb_type = $_POST['oktb_type'];
			$oktb_file = $_POST['filename'];
			$oktb_date = $_POST['date_of_issue_submit'];
			$oktb_expired = $_POST['valid_until_submit'];
			$active = $_POST['active'];
			
			//insert or update the oktb information
			$personal_db = new \core\modules\personal\models\common\db;
			$save = $personal_db->putOktb($personal_id, $oktb_number, $oktb_type, $oktb_date, $oktb_expired, $active, $oktb_file);
			
			if($_POST['next'] == 'home')
			{
				$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'passp';
			} else {
				$this->redirect = $this->baseURL.'/oktb/new';
			}
			
		}
		
		return;
	}
	
	private function _checkData()
	{
		$out = array();
	
		
		if(empty($_POST['oktb_number']))
		{
			$out['OKTB Number'] = 'You must enter a oktb number';
		}
		
		if(empty($_POST['active']))
		{
			$out['Active'] = 'You must say if the Visa is Active or Not';
		}
			
		if(empty($_POST['date_of_issue']))
		{
			$out['Date of Issue'] = 'You must enter date of issue';
		}

		if(empty($_POST['valid_until']))
		{
			$out['Date of Expiry'] = 'You must enter date of expiry';
		}
		
		if(empty($_POST['oktb_type']))
		{
			$out['Type'] = 'You must enter oktb type';
		}

		if( strtotime($_POST['valid_until']) < time() && $_POST['active'] == 'active')
		{
			$out['Active'] = 'An item can not be active that is out of date';
		}
		
		
		return $out;
	}
	
	
}
?>