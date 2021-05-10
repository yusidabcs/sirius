<?php
namespace core\modules\personal\models\education;

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

	protected $model_name = 'education';
	
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
			
			//set up education information
			$education = array(
				'education_id' => $_POST['education_id'],
				'countryCode_id' => $_POST['countryCode_id'],
				'view_from' => $_POST['from_date'],
				'view_to' => $_POST['to_date'],
				'institution' => $_POST['institution'],
				'website' => $_POST['website'],
				'email' => $_POST['email'],
				'phone' => $_POST['phone'],
				'qualification' => $_POST['qualification'],
				'type' => $_POST['type'],
				'description' => $_POST['description'],
				'level' => $_POST['level'],
				'attended_countryCode_id' => $_POST['attended_countryCode_id'],
				'active' => $_POST['active'],
				'english' => $_POST['english'],
				'certificate_date' => $_POST['certificate_date'],
				'certificate_number' => $_POST['certificate_number'],
				'certificate_expiry' => $_POST['certificate_expiry'],
				'stcw_type' => $_POST['stcw_type'] ?? 'none',
				'filename' => isset($_POST['education_current']) ? $_POST['education_current'] : ''
			);
			
			//information for the form
			$this->addInput('education',$education);

		} else { //no errors so process
		
			//insert or update the image	
			if(!empty($_POST['education_base64']))
			{
				$education_current = empty($_POST['education_current']) ? false : $_POST['education_current'];
				$filename = $this->_processEducationImage($personal_id,$education_current,$_POST['education_base64']);
			} else {
				$filename = empty($_POST['education_current']) ? '' : $_POST['education_current'];
			}
			$education_id = $_POST['education_id'];
			$address_book_id = $personal_id;
			$countryCode_id = $_POST['countryCode_id'];
			$from_date = date('Y-m-d',strtotime($_POST['from_date']));
			$to_date = empty($_POST['to_date']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['to_date']));
			$institution = trim($_POST['institution']);
			$website = trim($_POST['website']);
			$email = trim($_POST['email']);
			$phone = trim($_POST['phone']);
			$qualification = $_POST['qualification'];
			$type = $_POST['type'];
			$description = trim($_POST['description']);
			$level = $_POST['level'];
			$attended_countryCode_id = $_POST['attended_countryCode_id'];
			$active = $_POST['active'];
			$english = $_POST['english'];
			$stcw = '';
			if($level=='stcw'){
				$stcw = (isset($_POST['stcw_type'])) ? $_POST['stcw_type'] : 'bst';
			}
			$status = (isset($_POST['stcw_type'])) ? 'pending' : 'none';
			$certificate_date = empty($_POST['certificate_date']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['certificate_date']));
			$certificate_number = trim($_POST['certificate_number']);
			$certificate_expiry = empty($_POST['certificate_expiry']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['certificate_expiry']));

			if ($level === 'stcw') {
				# code...
				$workflow_db = new \core\modules\workflow\models\common\db;
				$workflow = $workflow_db->getActiveWorkflow('workflow_stcw_tracker','address_book_id', $_SESSION['personal']['address_book_id']);
				if($workflow){
					$workflow = $workflow_db->updateStcwTrackers($stcw, $_SESSION['personal']['address_book_id'], [
						'file_uploaded_on' => date('Y-m-d H:i:s'),
						'file_uploaded_by' => $_SESSION['user_id'],
						'status' => 'review_file',
						'notes' => 'file has been uploaded from candidate, waiting for review'
					]);
	
					if ($workflow !== 1) {
						$msg = "Error updating workflow ${workflow}";
						throw new \RuntimeException($msg);
					}
	
				}
			}

			//insert or update the education information
			$personal_db = new \core\modules\personal\models\common\db;
			$personal_db->putEducation($education_id,$address_book_id,$from_date,$to_date,$institution,$countryCode_id,$website,$email,$phone,$qualification,$type,$description,$level,$attended_countryCode_id,$active,$english,$certificate_date,$certificate_number,$certificate_expiry,$filename,$stcw,$status);
			
			if($_POST['next'] == 'home')
			{
				$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'edu';
			} else {
				$this->redirect = $this->baseURL.'/education/new';
			}
			
		}
		
		return;
	}
	
	private function _checkData()
	{
		$out = array();
		
		if(empty($_POST['countryCode_id']))
		{
			$out['Country'] = 'You must enter an issuing country for this education';
		}
		
		if(empty($_POST['institution']))
		{
			$out['Institution Name'] = 'You must enter a institution name';
		}

		if(empty($_POST['qualification']))
		{
			$out['Qualification Title'] = 'You must enter a qualification title';
		}

		if(empty($_POST['type']))
		{
			$out['Course Attendance'] = 'You must choose course attendance type';
		}
		
		if(empty($_POST['level']))
		{
			$out['Level of Qualification stcw'] = 'You must choose level of qualification';
		}
		if(empty($_POST['attended_countryCode_id']))
		{
			$out['Course Country'] = 'You must choose the country from the course';
		}	
		if(empty($_POST['from_date']))
		{
			$out['Start Date'] = 'You must enter start date';
		}

		if(empty($_POST['description']))
		{
			$out['General Description'] = 'You must enter general description';
		}
		
		if(empty($_POST['active']))
		{
			$out['Active'] = 'You must say if the Education is Current or Not';
		}
			
		if( $_POST['active'] == 'not_active' && strtotime($_POST['to_date']) < strtotime($_POST['from_date']))
		{
			$out['Dates'] = 'You can not start before you finish';
		}
				
		if(empty($_POST['from_date']))
		{
			$out['From Date'] = 'You must say when you started the job';
		}

		if( $_POST['active'] == 'not_active')
		{
			if(empty($_POST['certificate_date']))
			{
				$out['Certificate Date'] = 'You must enter the certification date';
			}

			if(empty($_POST['certificate_number']))
			{
				$out['Certification Number / ID '] = 'You must enter certificate number / id';
			}
		}

		
		
		return $out;
	}
	
	private function _processEducationImage($address_book_id,$education_current,$education_base64)
	{
		$filename = 'none';
		
		//decode
        $data = $education_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($education_current)
		{
			//delete the current education image
			$address_book_common->deleteAddressBookFile($education_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFileRev($filename,$address_book_id,'education',0,$education_current);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in education for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'education',0);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in education for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
	
}
?>