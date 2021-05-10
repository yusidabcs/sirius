<?php
namespace core\modules\personal\models\reference;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 14 January 2018
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'reference';
	
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
		
		//make sure we have a specific the type
		if(isset($this->page_options[0]))
		{
			$acceptable_types = array('personal','work');
			
			if(in_array($this->page_options[0], $acceptable_types))
			{
				$type = $this->page_options[0];
			} else {
				$msg = "What no valid type specified! How did that happen?";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			$msg = "What no type specified! How did that happen?";
			throw new \RuntimeException($msg);

		}

		//process the inputs
		$error_a = $this->_checkData();
		
		if(!empty($error_a))
		{
			//load up the errors
			foreach($error_a as $key => $value)
			{
				$this->addError($key,$value);
			}
			
			//set up reference information
			$reference = array(
				'reference_id' => $_POST['reference_id'],
				'type' => $type,
				'entity_name' => $_POST['entity_name'],
				'family_name' => $_POST['family_name'],
				'given_names' => $_POST['given_names'],
				'relationship' => $_POST['relationship'],
				'line_1' => $_POST['line_1'],
				'line_2' => $_POST['line_2'],
				'line_3' => $_POST['line_3'],
				'countryCode_id' => $_POST['countryCode_id'],
				'number_type' => $_POST['number_type'],
				'number' => $_POST['number'],
				'email' => $_POST['email'],
				'skype' => $_POST['skype'],
				'comment' => $_POST['comment'],
				'filename' => isset($_POST['reference_current']) ? $_POST['reference_current'] : ''
			);
			
			//information for the form
			$this->addInput('reference',$reference);

		} else { //no errors so process
		
			//insert or update the image	
			if(!empty($_POST['reference_base64']))
			{
				$reference_current = empty($_POST['reference_current']) ? false : $_POST['reference_current'];
				$filename = $this->_processReferenceImage($personal_id,$reference_current,$_POST['reference_base64']);
			} else {
				$filename = empty($_POST['reference_current']) ? '' : $_POST['reference_current'];
			}
			
			$reference_id = $_POST['reference_id'];
			$address_book_id = $personal_id;
			$entity_name = $_POST['entity_name'];
			$family_name = $_POST['family_name'];
			$given_names = $_POST['given_names'];
			$relationship = $_POST['relationship'];
			$line_1 = $_POST['line_1'];
			$line_2 = $_POST['line_2'];
			$line_3 = $_POST['line_3'];
			$countryCode_id = $_POST['countryCode_id'];
			$number_type = $_POST['number_type'];
			$number = $_POST['number'];
			$email = $_POST['email'];
			$skype = $_POST['skype'];
			$comment = $_POST['comment'];

			
			//insert or update the reference information
			$personal_db = new \core\modules\personal\models\common\db;
			$personal_db->putReference($reference_id,$address_book_id,$type,$entity_name,$family_name,$given_names,$relationship,$line_1,$line_2,$line_3,$countryCode_id,$number_type,$number,$email,$skype,$comment,$filename);
			
			if($_POST['next'] == 'home')
			{
				$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'ref';
			} else {
				$this->redirect = $this->baseURL.'/reference/'.$type.'/new';
			}
			
		}
		
		return;
	}
	
	private function _checkData()
	{
		$personal_db = new \core\modules\personal\models\common\db;
		$out = array();
		
		if (!is_file(DIR_SECURE_INI.'/site_config.ini')) {
			$out['Error'] = "Cannot find site_config.ini file";

			header('Content-Type: application/json; charset=utf-8');
			return json_encode($out); 
		}

		$site_a = parse_ini_file(DIR_SECURE_INI.'/site_config.ini'); 
		
		if(empty($_POST['given_names']))
		{
			$out['Given Name(s)'] = 'Please specify a given name';
			
		} else if(empty($_POST['email']))
		{
			$out['Email'] = 'Please provide an email address';
		} else {
			if (isset($site_a['VALIDATE_EMAIL_MX'])){
				list($name,$domain) = explode('@',$_POST['email']);
				if(!checkdnsrr($domain, 'MX'))
				{
					$out['Email'] = 'The email address you entered has no valid MX.';
				}
			}
		}
		if(isset($this->page_options[1])){
			$check_reference_email=[];
			$check_reference_phone=[];
			$email = $_POST['email'];
			$number = $_POST['number'];
			$address_book_id = $_SESSION['personal']['address_book_id'];
			$check_email = true; //if need to check to database
			$check_phone = true; //if need to check to database
			if($this->page_options[1]!='new') {
				$data_reference = $personal_db->getReference($this->page_options[1]);
				if(count($data_reference)>0) {
					if($email==$data_reference['email']) {
						$check_email = false;
					}
					if($number==$data_reference['number']) {
						$check_phone = false;
					}
				}
			} 
			if($check_email) {
				$check_reference_email = $personal_db->checkUnixReference($address_book_id,'email',$email);
			}
			if($check_phone) {
				$check_reference_phone = $personal_db->checkUnixReference($address_book_id,'number',$number);
			}

			if(count($check_reference_email)>0) {
				$out['Email'] = ' : The email is already registered as your references! Please input other reference.';
			}
			if(count($check_reference_phone)>0) {
				$out['Phone Number'] = ' : The phone number is already registered as your references! Please input other reference.';
			}
		}
		
		
		return $out;
	}
	
	private function _processReferenceImage($address_book_id,$reference_current,$reference_base64)
	{
		$filename = 'none';
		
		//decode
        $data = $reference_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($reference_current)
		{
			//delete the current reference image
			$address_book_common->deleteAddressBookFile($reference_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFileRev($filename,$address_book_id,'reference',0,$reference_current);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in reference for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'reference',0);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in reference for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
	
}
?>