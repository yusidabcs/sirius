<?php
namespace core\modules\personal\models\idcard;

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

	protected $model_name = 'idcard';
	
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
		$_POST['idcard_safe'] = $generic->safeLinkId($_POST['idcard_orig']);
		
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
			
			
			//set up idcard information
			$idcard = array(
				'idcard_id' => $_POST['idcard_id'],
				'idcard_orig' => $_POST['idcard_orig'],
				'countryCode_id' => $_POST['countryCode_id'],
				'from_date' => $_POST['from_date'],
				'to_date' => $_POST['to_date'],
				'family_name' => $_POST['family_name'],
				'given_names' => $_POST['given_names'],
				'full_name' => $_POST['full_name'],
				'type' => $_POST['type'],
				'authority' => $_POST['authority'],
				'active' => $_POST['active'],
				'filename' => isset($_POST['idcard_current']) ? $_POST['idcard_current'] : '',
				'filename_back' => isset($_POST['idcard_back_current']) ? $_POST['idcard_back_current'] : ''
			);
			
			//information for the form
			$this->addInput('idcard',$idcard);

		} else { //no errors so process
		
			//insert or update the image	
			if(!empty($_POST['idcard_base64']))
			{
				$idcard_current = empty($_POST['idcard_current']) ? false : $_POST['idcard_current'];
				$filename = $this->_processIDCardImage($personal_id,$idcard_current,$_POST['idcard_base64'],$_POST['idcard_safe'],0);
			} else {
				$filename = empty($_POST['idcard_current']) ? '' : $_POST['idcard_current'];
			}
			
			//insert or update the image	
			if(!empty($_POST['idcard_back_base64']))
			{
				$idcard_back_current = empty($_POST['idcard_back_current']) ? false : $_POST['idcard_back_current'];
				$filename_back = $this->_processIDCardImage($personal_id,$idcard_back_current,$_POST['idcard_back_base64'],$_POST['idcard_safe'],1);
			} else {
				$filename_back = empty($_POST['idcard_back_current']) ? '' : $_POST['idcard_back_current'];
			}
			
			$idcard_id = strtoupper($_POST['idcard_id']);
			$idcard_safe = strtoupper($_POST['idcard_safe']);
			$idcard_orig = strtoupper($_POST['idcard_orig']);
			$address_book_id = $personal_id;
			$countryCode_id = $_POST['countryCode_id'];
			$from_date = date('Y-m-d',strtotime($_POST['from_date']));
			$to_date = empty($_POST['to_date']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['to_date']));
			$family_name = strtoupper(trim($_POST['family_name']));
			$given_names = strtoupper(trim($_POST['given_names']));
			$full_name = strtoupper(trim($_POST['full_name']));
			$type = strtoupper(trim($_POST['type']));
			$authority = strtoupper(trim($_POST['authority']));
			$active = $_POST['active'];
						
			//insert or update the idcard information
			$personal_db = new \core\modules\personal\models\common\db;
			$personal_db->putIDCard($idcard_id,$idcard_safe,$idcard_orig,$countryCode_id,$address_book_id,$from_date,$to_date,$family_name,$given_names,$full_name,$type,$authority,$active,$filename,$filename_back);
			
			if($_POST['next'] == 'home')
			{
				$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'ids';
			} else {
				$this->redirect = $this->baseURL.'/idcard/new';
			}
		}
		
		return;
	}
	
	private function _checkData()
	{
		$out = array();

		if(empty($_POST['countryCode_id']))
		{
			$out['Country'] = 'You must enter an issuing country for this ID Card';
		}
		
		if(empty($_POST['idcard_safe']))
		{
			$out['IDCard Number'] = 'You must enter a ID Card number';
		}

		if(empty($_POST['from_date']))
		{
			$out['Date of Issue'] = 'You must enter date of issue';
		}

		//if tick expire
		if (isset($_POST['id_expire']))
		{
			if(empty($_POST['to_date']))
			{
				$out['Date of Expiry'] = 'You must enter date of expiry';
			}
		}

		if(empty($_POST['authority']))
		{
			$out['Authority'] = 'You must enter id authority';
		}
		
		if(empty($_POST['type']))
		{
			$out['Authority'] = 'You must enter id type';
		}

		if(empty($_POST['active']))
		{
			$out['Active'] = 'You must say if the ID Card is Active or Not';
		}

		//if tick expire
		if (isset($_POST['id_expire']))
		{
			if( strtotime($_POST['to_date']) < time() && $_POST['active'] == 'active')
			{
				$out['Active'] = 'An item can not be active that is out of date';
			}
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
		
		return $out;
	}
	
	private function _processIDCardImage($address_book_id,$idcard_current,$idcard_base64,$idcard_id,$sequence)
	{
		$filename = 'none';
		
		//decode
        $data = $idcard_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($idcard_current)
		{
			//delete the current idcard image
			$address_book_common->deleteAddressBookFile($idcard_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'idcard',$sequence,$idcard_id);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in idcard for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'idcard',$sequence,$idcard_id);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in idcard for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
	
}
?>