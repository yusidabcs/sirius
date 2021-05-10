<?php
namespace core\modules\personal\models\medical;

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

	protected $model_name = 'medical';
	
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
			
			//set up medical information
			$medical = array(
				'medical_id' => $_POST['medical_id'],
				'countryCode_id' => $_POST['countryCode_id'],
				'institution' => $_POST['institution'],
				'website' => $_POST['website'],
				'email' => $_POST['email'],
				'phone' => $_POST['phone'],
				'type' => $_POST['type'],
				'fit' => $_POST['fit'],
				'certificate_date' => $_POST['certificate_date'],
				'certificate_number' => $_POST['certificate_number'],
				'doctor' => $_POST['doctor'],
				'certificate_expiry' => $_POST['certificate_expiry'],
				'filename' => isset($_POST['medical_current']) ? $_POST['medical_current'] : '',
				'certificate_from' => $_POST['certificate_date'],
				'certificate_to' => $_POST['certificate_expiry']
			);
			
			//information for the form
			$this->addInput('medical',$medical);

		} else { //no errors so process
		
			//insert or update the image	
			if(!empty($_POST['medical_base64']))
			{
				$medical_current = empty($_POST['medical_current']) ? false : $_POST['medical_current'];
				$filename = $this->_processMedicalImage($personal_id,$medical_current,$_POST['medical_base64']);
			} else {
				$filename = empty($_POST['medical_current']) ? '' : $_POST['medical_current'];
			}			
			$medical_id = $_POST['medical_id'];
			$address_book_id = $personal_id;
			$countryCode_id = $_POST['countryCode_id'];
			$institution = trim($_POST['institution']);
			$website = trim($_POST['website']);
			$email = trim($_POST['email']);
			$phone = trim($_POST['phone']);
			$type = $_POST['type'];
			$fit = $_POST['fit'];
			$certificate_date = empty($_POST['certificate_date']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['certificate_date']));
			$certificate_number = trim($_POST['certificate_number']);
			$doctor = $_POST['doctor'];
			$certificate_expiry = empty($_POST['certificate_expiry']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['certificate_expiry']));

			if ($this->system_register->getModuleIsInstalled('workflow')) {
				$workflow_db = new \core\modules\workflow\models\common\db;

				if ($workflow_db->getActiveWorkflow('workflow_medical_tracker', 'address_book_id', $address_book_id, array('medical_type' => $type))) {
					$workflow = $workflow_db->updateTrackers('workflow_medical_tracker', $address_book_id, [
						'file_uploaded_on' => date('Y-m-d'),
						'file_uploaded_by' => $_SESSION['user_id'],
						'filename' => $filename,
						'notes' => 'Medical document has been uploaded, waiting for review',
						'status' => 'review_file'
					]);

					if ($workflow !== 1) {
						throw new RuntimeException("Error updating workflow " . $workflow);
					}
				}
			}
			//insert or update the medical information
			$personal_db = new \core\modules\personal\models\common\db;
			$personal_db->putMedical($medical_id,$address_book_id,$institution,$countryCode_id,$website,$email,$phone,$type,$fit,$certificate_date,$certificate_number,$doctor,$certificate_expiry,$filename);
			
			if($_POST['next'] == 'home')
			{
				$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'med';
			} else {
				$this->redirect = $this->baseURL.'/medical/new';
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
			$out['Country'] = 'You must enter an issuing country for this medical';
		}
		
		if(empty($_POST['type']))
		{
			$out['Type'] = 'You must say what type of medical you had';
		}
		
		if(empty($_POST['fit']))
		{
			$out['Fit'] = 'You must say if your Medical says Fit or Not';
		}
		
		if(empty($_POST['doctor']))
		{
			$out['Doctor'] = 'You must enter name of the Doctor who signed the medical';
		}
		
		if(empty($_POST['certificate_date']) )
		{
			$out['Certificate Date'] = 'You must say what the certificate date is';
		}
		
		if(empty($_POST['certificate_number']) )
		{
			$out['Certificate Number'] = 'You must say what the certificate number is';
		}
		
		return $out;
	}
	
	private function _processMedicalImage($address_book_id,$medical_current,$medical_base64)
	{
		$filename = 'none';
		
		//decode
        $data = $medical_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($medical_current)
		{
			//delete the current medical image
			$address_book_common->deleteAddressBookFile($medical_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFileRev($filename,$address_book_id,'medical',0,$medical_current);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in medical for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'medical',0);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in medical for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
	
}
?>