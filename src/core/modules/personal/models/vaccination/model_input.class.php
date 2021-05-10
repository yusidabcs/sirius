<?php
namespace core\modules\personal\models\vaccination;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 January 2018
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'vaccination';
	
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
		if(empty($_POST['english'])) $_POST['english'] = '';
		if(empty($_POST['fit'])) $_POST['fit'] = '';
		
		//process the inputs
		$error_a = $this->_checkData();
		
		if(!empty($error_a))
		{
			//load up the errors
			foreach($error_a as $key => $value)
			{
				$this->addError($key,$value);
			}
			
			//set up vaccination information
			$vaccination = array(
				'vaccination_id' => $_POST['vaccination_id'],
				'countryCode_id' => $_POST['countryCode_id'],
				'institution' => $_POST['institution'],
				'website' => $_POST['website'],
				'email' => $_POST['email'],
				'phone' => $_POST['phone'],
				'type' => $_POST['type'],
				'fit' => $_POST['fit'],
				'vaccination_date' => $_POST['vaccination_date'],
				'vaccination_number' => $_POST['vaccination_number'],
				'doctor' => $_POST['doctor'],
				'vaccination_expiry' => $_POST['vaccination_expiry'],
				'filename' => isset($_POST['vaccination_current']) ? $_POST['vaccination_current'] : '',
				'vaccination_from' => $_POST['vaccination_date'],
				'vaccination_to' => $_POST['vaccination_expiry']
			);
			
			//information for the form
			$this->addInput('vaccination',$vaccination);

		} else { //no errors so process
		
			//insert or update the image	
			if(!empty($_POST['vaccination_base64']))
			{
				$vaccination_current = empty($_POST['vaccination_current']) ? false : $_POST['vaccination_current'];
				$filename = $this->_processVaccinationImage($personal_id,$vaccination_current,$_POST['vaccination_base64']);
			} else {
				$filename = empty($_POST['vaccination_current']) ? '' : $_POST['vaccination_current'];
			}
			
			$vaccination_id = $_POST['vaccination_id'];
			$address_book_id = $personal_id;
			$countryCode_id = $_POST['countryCode_id'];
			$institution = trim($_POST['institution']);
			$website = trim($_POST['website']);
			$email = trim($_POST['email']);
			$phone = trim($_POST['phone']);
			$type = $_POST['type'];
			$fit = $_POST['fit'];
			$vaccination_date = empty($_POST['vaccination_date']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['vaccination_date']));
			$vaccination_number = trim($_POST['vaccination_number']);
			$doctor = $_POST['doctor'];
			$vaccination_expiry = empty($_POST['vaccination_expiry']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['vaccination_expiry']));

			if ($this->system_register->getModuleIsInstalled('workflow')) {
				$workflow_db = new \core\modules\workflow\models\common\db;

				if ($workflow_db->getActiveWorkflow('workflow_vaccination_tracker', 'address_book_id', $address_book_id, array('vaccination_type' => $type))) {
					$workflow = $workflow_db->updateTrackers('workflow_vaccination_tracker', $address_book_id, [
						'file_uploaded_on' => date('Y-m-d'),
						'file_uploaded_by' => $_SESSION['user_id'],
						'filename' => $filename,
						'notes' => 'Vaccination document has been uploaded, waiting for review',
						'status' => 'review_file'
					]);

					if ($workflow !== 1) {
						throw new RuntimeException("Error updating workflow " . $workflow);
					}
				}
			}

			//insert or update the vaccination information
			$personal_db = new \core\modules\personal\models\common\db;
			$personal_db->putVaccination($vaccination_id,$address_book_id,$institution,$countryCode_id,$website,$email,$phone,$type,$vaccination_date,$vaccination_number,$doctor,$vaccination_expiry,$filename);
			
			if($_POST['next'] == 'home')
			{
				$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'med';
			} else {
				$this->redirect = $this->baseURL.'/vaccination/new';
			}
			
		}
		
		return;
	}
	
	private function _checkData()
	{
		$out = array();
		
		if(empty($_POST['institution']))
		{
			$out['Institution Name'] = 'You must enter a institution name';
		}

		if(empty($_POST['countryCode_id']))
		{
			$out['Country'] = 'You must enter an issuing country for this vaccination';
		}
		
		if(empty($_POST['type']))
		{
			$out['Type'] = 'You must say what type of vaccination you had';
		}
		
		if(empty($_POST['doctor']))
		{
			$out['Doctor'] = 'You must enter name of the Doctor who signed the vaccination';
		}
		
		if(empty($_POST['vaccination_date']) )
		{
			$out['Vaccination Date'] = 'You must say what the vaccination date is';
		}
		
		if(empty($_POST['vaccination_number']) )
		{
			$out['Vaccination Number'] = 'You must say what the vaccination number is';
		}
		
		return $out;
	}
	
	private function _processVaccinationImage($address_book_id,$vaccination_current,$vaccination_base64)
	{
		$filename = 'none';
		
		//decode
        $data = $vaccination_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($vaccination_current)
		{
			//delete the current vaccination image
			$address_book_common->deleteAddressBookFile($vaccination_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFileRev($filename,$address_book_id,'vaccination',0,$vaccination_current);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in vaccination for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'vaccination',0);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in vaccination for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
	
}
?>