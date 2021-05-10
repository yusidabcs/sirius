<?php
namespace core\modules\personal\models\police;

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

	protected $model_name = 'police';
	
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

			$police = array(
                'police_id' => $_POST['police_id'],
                'countryCode_id' => $_POST['countryCode_id'],
                'full_name' => $_POST['full_name'],
                'from_date' => $_POST['from_date'],
                'to_date' => $_POST['to_date'],
                'nationality' => $_POST['nationality'],
                'sex' => $_POST['sex'],
                'dob' => $_POST['dob'],
                'pob' => $_POST['pob'],
                'place_issued' => $_POST['place_issued'],
                'active' => $_POST['active'],
                'valid' => $_POST['valid'],
                'filename' => $_POST['filename']
			);
			
			//information for the form
			$this->addInput('police',$police);

		} else { //no errors so process

            $personal_db = new \core\modules\personal\models\common\db;
            $personal_db->commitOff();
			//insert or update the image	
			if(!empty($_POST['police_base64']))
			{
				$police_current = empty($_POST['police_current']) ? false : $_POST['police_current'];
				$filename = $this->_processPoliceImage($personal_id,$police_current,$_POST['police_base64']);
			} else {
				$filename = empty($_POST['police_current']) ? '' : $_POST['police_current'];
			}

			if ($this->system_register->getModuleIsInstalled('workflow')) {
				$workflow_db = new \core\modules\workflow\models\common\db;

				if ($workflow_db->getActiveWorkflow('workflow_police_tracker', 'address_book_id', $personal_id)) {
					$workflow = $workflow_db->updateTrackers('workflow_police_tracker', $personal_id, [
						'uploaded_file_on' => date('Y-m-d'),
						'uploaded_file_by' => $_SESSION['user_id'],
						'pc_file' => $filename,
						'notes' => 'Police document has been uploaded, waiting for review',
						'status' => 'review_file'
					]);

					if ($workflow !== 1) {
						throw new \RuntimeException("Error updating workflow " . $workflow);
					}
				}
			}

            $police = array(
                'police_id' => $_POST['police_id'],
                'address_book_id' => $personal_id,
                'countryCode_id' => $_POST['countryCode_id'],
                'full_name' => $_POST['full_name'],
                'from_date' => date('Y-m-d',strtotime($_POST['from_date'])),
                'to_date' => date('Y-m-d',strtotime($_POST['to_date'])),
                'nationality' => $_POST['nationality'],
                'sex' => $_POST['sex'],
                'dob' => date('Y-m-d',strtotime($_POST['dob'])),
                'pob' => $_POST['pob'],
                'place_issued' => $_POST['place_issued'],
                'active' => $_POST['active'],
                'filename' => $filename,
            );
			//insert or update the passport information
			$personal_db = new \core\modules\personal\models\common\db;
			$rs = $personal_db->putPoliceCheck($police);
            if($rs){
                $personal_db->commit();
            }else{
                $personal_db->rollback();
            }
			if($_POST['next'] == 'home')
			{
				$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'police';
			} else {
				$this->redirect = $this->baseURL.'/police/new';
			}
			
		}
		
		return;
	}
	
	private function _checkData()
	{
		$out = array();
		if(empty($_POST['countryCode_id']))
		{
			$out['Country'] = 'You must enter an issuing country for this police check';
		}

		if(empty($_POST['active']))
		{
			$out['Active'] = 'You must say if the police check is Active or Not';
		}
		
		if( strtotime($_POST['to_date']) < time() && $_POST['active'] == 'active')
		{
			$out['Active'] = 'An item can not be active that is out of date';
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

		
		return $out;
	}
	
	private function _processPoliceImage($address_book_id,$police_current,$police_base64)
	{
		$filename = 'none';
		
		//decode
        $data = $police_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($police_current)
		{
			//delete the current passport image
			$address_book_common->deleteAddressBookFile($police_current,$address_book_id);
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFileRev($filename,$address_book_id,'police',0,$police_current);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in police for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'police',0);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in police for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
	
}
?>