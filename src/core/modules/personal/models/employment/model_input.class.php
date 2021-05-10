<?php
namespace core\modules\personal\models\employment;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 7 January 2018
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'employment';
	
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
		
		//fix up if the radio has not be set
		if(empty($_POST['active'])) $_POST['active'] = '';
		
		//process the inputs
		$error_a = $this->_checkData();
		
		if(!empty($error_a))
		{
			//load up the errors
			foreach($error_a as $key => $value)
			{
				$this->addError($key,$value);
			}
			
			//set up employment information
			$employment = array(
				'employment_id' => $_POST['employment_id'],
				'countryCode_id' => $_POST['countryCode_id'],
				'job_speedy_category_id' => $_POST['job_speedy_category_id'],
				'view_from' => $_POST['from_date'],
				'view_to' => $_POST['to_date'],
				'employer' => $_POST['employer'],
				'website' => $_POST['website'],
				'email' => $_POST['email'],
				'phone' => $_POST['phone'],
				'job_title' => $_POST['job_title'],
				'type' => $_POST['type'],
				'description' => $_POST['description'],
				'active' => $_POST['active'],
				'filename' => isset($_POST['employment_current']) ? $_POST['employment_current'] : ''
			);
			
			//information for the form
			$this->addInput('employment',$employment);

		} else { //no errors so process
		
			//insert or update the image	
			if(!empty($_POST['employment_base64']))
			{
				$employment_current = empty($_POST['employment_current']) ? false : $_POST['employment_current'];
				$filename = $this->_processEmploymentImage($personal_id,$employment_current,$_POST['employment_base64']);
			} else {
				$filename = empty($_POST['employment_current']) ? '' : $_POST['employment_current'];
			}
			
			$employment_id = $_POST['employment_id'];
			$address_book_id = $personal_id;
			$job_speedy_category_id = $_POST['job_speedy_category_id'];
			$countryCode_id = $_POST['countryCode_id'];
			$from_date = date('Y-m-d',strtotime($_POST['from_date']));
			$to_date = empty($_POST['to_date']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['to_date']));
			$employer = trim($_POST['employer']);
			$website = trim($_POST['website']);
			$email = trim($_POST['email']);
			$phone = trim($_POST['phone']);
			$job_title = $_POST['job_title'];
			$type = $_POST['type'];
			$description = trim($_POST['description']);
			$active = $_POST['active'];

			//insert or update the employment information
			$personal_db = new \core\modules\personal\models\common\db;
			$personal_db->putEmployment($employment_id,$address_book_id,$job_speedy_category_id,$from_date,$to_date,$employer,$countryCode_id,$website,$email,$phone,$job_title,$type,$description,$active,$filename);
			
			if($_POST['next'] == 'home')
			{
				$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'employ';
			} else {
				$this->redirect = $this->baseURL.'/employment/new';
			}
			
		}
		
		return;
	}
	
	private function _checkData()
	{
		$out = array();
		
		if(empty($_POST['countryCode_id']))
		{
			$out['Country'] = 'You must enter an issuing country for this employment';
		}
		
		if(empty($_POST['employer']))
		{
			$out['Employer Name'] = 'You must enter a employer name';
		}
		
		if(empty($_POST['active']))
		{
			$out['Active'] = 'You must say if the Employment is Current or Not';
		}
			
		if( $_POST['active'] == 'not_active' && strtotime($_POST['to_date']) < strtotime($_POST['from_date']))
		{
			$out['Dates'] = 'You can not start before you finish';
		}
				
		if(empty($_POST['from_date']))
		{
			$out['From Date'] = 'You must say when you started the job';
		}
		
		return $out;
	}
	
	private function _processEmploymentImage($address_book_id,$employment_current,$employment_base64)
	{
		$filename = 'none';
		
		//decode
        $data = $employment_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($employment_current)
		{
			//delete the current employment image
			$address_book_common->deleteAddressBookFile($employment_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFileRev($filename,$address_book_id,'employment',0,$employment_current);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in employment for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'employment',0);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in employment for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
	
}
?>