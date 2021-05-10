<?php
namespace core\modules\personal\models\visa;

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

	protected $model_name = 'visa';
	
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
		$_POST['visa_id'] = $generic->safeLinkId($_POST['visa_id']);
		
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
			
			//set up visa information
			$visa = array(
				'visa_id' => $_POST['visa_id'],
				'countryCode_id' => $_POST['countryCode_id'],
				'from_date' => $_POST['from_date'],
				'to_date' => $_POST['to_date'],
				'family_name' => $_POST['family_name'],
				'given_names' => $_POST['given_names'],
				'full_name' => $_POST['full_name'],
				'place_issued' => $_POST['place_issued'],
				'entry' => $_POST['entry'],
				'type' => $_POST['type'],
				'class' => $_POST['class'],
				'authority' => $_POST['authority'],
				'active' => $_POST['active'],
				'passport_id' => $_POST['passport_id'],
				'filename' => isset($_POST['visa_current']) ? $_POST['visa_current'] : ''
			);
			
			//information for the form
			$this->addInput('visa',$visa);

		} else { //no errors so process
		
			//insert or update the image	
			if(!empty($_POST['visa_base64']))
			{
				$visa_current = empty($_POST['visa_current']) ? false : $_POST['visa_current'];
				$filename = $this->_processVisaImage($personal_id,$visa_current,$_POST['visa_base64'],$_POST['visa_id']);
			} else {
				$filename = empty($_POST['visa_current']) ? '' : $_POST['visa_current'];
			}
			
			$visa_id = strtoupper($_POST['visa_id']);
			$address_book_id = $personal_id;
			$countryCode_id = $_POST['countryCode_id'];
			$from_date = date('Y-m-d',strtotime($_POST['from_date']));
			$to_date = date('Y-m-d',strtotime($_POST['to_date']));
			$family_name = strtoupper(trim($_POST['family_name']));
			$given_names = strtoupper(trim($_POST['given_names']));
			$full_name = strtoupper(trim($_POST['full_name']));
			$place_issued = strtoupper(trim($_POST['place_issued']));
			$entry = $_POST['entry'];
			$type = strtoupper(trim($_POST['type']));
			$class = strtoupper(trim($_POST['class']));
			$authority = strtoupper(trim($_POST['authority']));
			$active = $_POST['active'];
			$passport_id = $_POST['passport_id'];

			$workflow_db = new \core\modules\workflow\models\common\db;
            $workflow = $workflow_db->getActiveWorkflow('workflow_visa_tracker','address_book_id', $_SESSION['personal']['address_book_id'], ['visa_type' => $type]);
            if($workflow) {
                $workflow = $workflow_db->updateVisaTrackers($type, $_SESSION['personal']['address_book_id'], [
                    'upload_visa_on' => date('Y-m-d H:i:s'),
                    'upload_visa_by' => $_SESSION['user_id'],
					'notes' => 'file has been uploaded from candidate, waiting for review',
					'status' => 'upload_visa'
                ]);

                if ($workflow !== 1) {
                    $msg = "Error updating workflow ${workflow}";
                    throw new \RuntimeException($msg);
                }

            }
			
			//insert or update the visa information
			$personal_db = new \core\modules\personal\models\common\db;
			$personal_db->putVisa($visa_id,$address_book_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$place_issued,$entry,$type,$class,$authority,$active,$passport_id,$filename);
			
			if($_POST['next'] == 'home')
			{
				$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'passp';
			} else {
				$this->redirect = $this->baseURL.'/visa/new';
			}
			
		}
		
		return;
	}
	
	private function _checkData()
	{
		$out = array();
		
		if(empty($_POST['countryCode_id']))
		{
			$out['Country'] = 'You must enter an issuing country for this visa';
		}
		
		if(empty($_POST['visa_id']))
		{
			$out['Visa Number'] = 'You must enter a visa number';
		}
		
		if(empty($_POST['active']))
		{
			$out['Active'] = 'You must say if the Visa is Active or Not';
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
			$out['Authority'] = 'You must enter visa authority';
		}
		
		if(empty($_POST['type']))
		{
			$out['Authority'] = 'You must enter visa type';
		}

		if(empty($_POST['class']))
		{
			$out['Authority'] = 'You must enter visa class';
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
		
		if(!in_array($_POST['entry'],array('single','multiple')))
		{
			$out['Entry'] = 'You must say if it is a single or multiple entry';
		}

		
		if(empty($_POST['passport_id']))
		{
			$out['Passport'] = 'You must select a Passport to link the visa too';
		}
		
		return $out;
	}
	
	private function _processVisaImage($address_book_id,$visa_current,$visa_base64,$visa_id)
	{
		$filename = 'none';
		
		//decode
        $data = $visa_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($visa_current)
		{
			//delete the current visa image
			$address_book_common->deleteAddressBookFile($visa_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'visa',0,$visa_id);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in visa for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'visa',0,$visa_id);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in visa for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
	
}
?>