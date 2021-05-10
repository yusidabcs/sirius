<?php
namespace core\modules\personal\models\passport;

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

	protected $model_name = 'passport';
	
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
		
		//make sure the passport_id is safe to use as a link (it should always be)
		$generic = \core\app\classes\generic\generic::getInstance();
		$_POST['passport_id'] = $generic->safeLinkId($_POST['passport_id']);
		
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
			
			//set up passport information
			$passport = array(
				'passport_id' => $_POST['passport_id'],
				'countryCode_id' => $_POST['countryCode_id'],
				'from_date' => $_POST['from_date'],
				'to_date' => $_POST['to_date'],
				'family_name' => $_POST['family_name'],
				'given_names' => $_POST['given_names'],
				'full_name' => $_POST['full_name'],
				'nationality' => $_POST['nationality'],
				'sex' => $_POST['sex'],
				'place_issued' => $_POST['place_issued'],
				'dob' => $_POST['dob'],
				'pob' => $_POST['pob'],
				'type' => $_POST['type'],
				'code' => $_POST['code'],
				'authority' => $_POST['authority'],
				'active' => $_POST['active'],
				'filename' => isset($_POST['passport_current']) ? $_POST['passport_current'] : ''
			);
			
			//information for the form
			$this->addInput('passport',$passport);

		} else { //no errors so process
		
			//insert or update the image	
			if(!empty($_POST['passport_base64']))
			{
				$passport_current = empty($_POST['passport_current']) ? false : $_POST['passport_current'];
				$filename = $this->_processPassportImage($personal_id,$passport_current,$_POST['passport_base64'],$_POST['passport_id']);
			} else {
				$filename = empty($_POST['passport_current']) ? '' : $_POST['passport_current'];
			}
			
			$passport_id = strtoupper(trim($_POST['passport_id']));
			$address_book_id = $personal_id;
			$countryCode_id = $_POST['countryCode_id'];
			$from_date = date('Y-m-d',strtotime($_POST['from_date']));
			$to_date = date('Y-m-d',strtotime($_POST['to_date']));
			$family_name = strtoupper(trim($_POST['family_name']));
			$given_names = strtoupper(trim($_POST['given_names']));
			$full_name = strtoupper(trim($_POST['full_name']));
			$nationality = strtoupper(trim($_POST['nationality']));
			$sex = $_POST['sex'];
			$place_issued = strtoupper(trim($_POST['place_issued']));
			$dob = date('Y-m-d',strtotime($_POST['dob']));
			$pob = strtoupper(trim($_POST['pob']));
			$type = strtoupper(trim($_POST['type']));
			$code = strtoupper(trim($_POST['code']));
			$authority = strtoupper(trim($_POST['authority']));
			$active = $_POST['active'];
			
			//insert or update the passport information
			$personal_db = new \core\modules\personal\models\common\db;
			$personal_db->putPassport($passport_id,$address_book_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$nationality,$sex,$place_issued,$dob,$pob,$type,$code,$authority,$active,$filename);
			
			if($_POST['next'] == 'home')
			{
				$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'passp';
			} else {
				$this->redirect = $this->baseURL.'/passport/new';
			}
			
		}
		
		return;
	}
	
	private function _checkData()
	{
		$out = array();
		if(empty($_POST['countryCode_id']))
		{
			$out['Country'] = 'You must enter an issuing country for this passport';
		}

		if(empty($_POST['type']))
		{
			$out['Passport Type'] = 'You must enter a passport type';
		}

		if(empty($_POST['code']))
		{
			$out['Passport Code'] = 'You must enter a passport code';
		}

		if(empty($_POST['passport_id']))
		{
			$out['Passport Number'] = 'You must enter a passport number';
		}
		
		if(empty($_POST['active']))
		{
			$out['Active'] = 'You must say if the passport is Active or Not';
		}
		
		if( strtotime($_POST['to_date']) < time() && $_POST['active'] == 'active')
		{
			$out['Active'] = 'An item can not be active that is out of date';
		}
		
		switch ($_POST['name_style']) 
		{
		    case 'full':
		        if(empty($_POST['full_name']))
				{
					$out['Full Name'] = 'You must enter the full name';
				}
		        break;
		    default:
		       	if(empty($_POST['family_name']) && empty($_POST['given_names']))
				{
					$out['Name'] = 'You must at least a family or given name';
				}
		}

		if(empty($_POST['nationality']))
		{
			$out['Nationality'] = 'You must enter nationality';
		}

		if(empty($_POST['sex']))
		{
			$out['sex'] = 'You must define sex';
		}

		if(empty($_POST['dob']))
		{
			$out['Date of Birth'] = 'You must enter date of birth';
		}

		if(empty($_POST['pob']))
		{
			$out['Place of Birth'] = 'You must enter place of birth';
		}
		
		if(empty($_POST['from_date']))
		{
			$out['Date of Issue'] = 'You must enter date of issue';
		}

		if(empty($_POST['to_date']))
		{
			$out['Date of Expiry'] = 'You must enter date of expiry';
		}
		
		if(empty($_POST['place_issued']))
		{
			$out['Place of Issue'] = 'You must enter place of issue';
		}

		if(empty($_POST['authority']))
		{
			$out['Authority'] = 'You must enter authority';
		}

		if (isset($this->page_options[0])) {
			# code...
			if($this->page_options[0]=='new') {
				$personal_db = new \core\modules\personal\models\common\db;
				$checkPassport = $personal_db->getPassport($_POST['passport_id']);
		
				if (!empty($checkPassport['passport_id']) && $checkPassport['passport_id'] === $_POST['passport_id']) {
					$out['Passport Number'] = ' already exists';
				}
			}
		}
		
		
		return $out;
	}
	
	private function _processPassportImage($address_book_id,$passport_current,$passport_base64,$passport_id)
	{
		$filename = 'none';
		
		//decode
        $data = $passport_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($passport_current)
		{
			//delete the current passport image
			$address_book_common->deleteAddressBookFile($passport_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'passport',0,$passport_id);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in passport for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'passport',0,$passport_id);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in passport for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
	
}
?>