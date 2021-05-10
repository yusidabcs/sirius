<?php
namespace core\modules\personal\models\stcw;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 3 January 2017
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'stcw';
	
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
		
		//process the inputs
		$error_a = $this->_checkData();
		
		if(!empty($error_a))
		{
			//load up the errors
			foreach($error_a as $key => $value)
			{
				$this->addError($key,$value);
			}
			
			//set up stcw information
			$stcw = array(
				'stcw_id' => $_POST['stcw_id'],
				'type' => $_POST['type'],
				'overall' => $_POST['overall'],
				'breakdown' => $_POST['breakdown'],
				'when' => $_POST['when'],
				'where' => $_POST['where'],
				'filename' => isset($_POST['stcw_current']) ? $_POST['stcw_current'] : ''
			);
			
			//information for the form
			$this->addInput('stcw',$stcw);

		} else { //no errors so process
		
			//we need an stcw id so if it is empty make a blank one (chicken-egg)
			$personal_db = new \core\modules\personal\models\common\db;

            //check if there stcw workflow
			$workflow_db = new \core\modules\workflow\models\common\db;
            $workflow = $workflow_db->getActiveWorkflow('workflow_stcw_tracker','address_book_id', $_SESSION['personal']['address_book_id']);
            if($workflow){
                $workflow = $workflow_db->updateTrackers('workflow_stcw_tracker', $_SESSION['personal']['address_book_id'], [
                    'uploaded_file_on' => date('Y-m-d H:i:s'),
                    'uploaded_file_by' => $_SESSION['user_id'],
                    'status' => 'review_file',
                    'notes' => 'file has been uploaded from candidate, waiting for review'
                ]);

                if ($workflow !== 1) {
                    $msg = "Error updating workflow ${workflow}";
                    throw new \RuntimeException($msg);
                }

            }

			$stcw_id = $_POST['stcw_id'];
			$address_book_id = $personal_id;
			if(empty($stcw_id))
			{
				$stcw_id = $personal_db->insertStcw($address_book_id);
				if($stcw_id < 1)
				{
					$msg = "There was a major issue with addInfo inserting stcw for address id {$address_book_id}. ID was {$stcw_id}";
					throw new \RuntimeException($msg);
				}
			}
			
			//insert or update the image	
			if(!empty($_POST['stcw_base64']))
			{
				$stcw_current = empty($_POST['stcw_current']) ? false : $_POST['stcw_current'];
				$filename = $this->_processStcwImage($personal_id,$stcw_current,$_POST['stcw_base64'],$stcw_id);
			} else {
				$filename = empty($_POST['stcw_current']) ? '' : $_POST['stcw_current'];
			}
			
			$type = $_POST['type'];
			$serial_no = $_POST['serial_no'];
			$place_issued = $_POST['place_issued'];
			$certificate_no = $_POST['certificate_no'];
			$held_by = $_POST['held_by'];
			$held_at = $_POST['held_at'];
			$from_date = date('Y-m-d',strtotime($_POST['from_date']));
			$to_date = date('Y-m-d',strtotime($_POST['to_date']));
			
			//update the stcw information
			$personal_db->updateStcw($type, $serial_no, $certificate_no, $place_issued, $held_by, $held_at, $from_date, $to_date,$stcw_id);
			
			if($_POST['next'] == 'home')
			{
				$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'lang';
			} else {
				$this->redirect = $this->baseURL.'/stcw/new';
			}
		}
		
		return;
	}
	
	private function _checkData()
	{
		$out = array();
		
		if(empty($_POST['type']))
		{
			$out['type'] = 'Select certificate type';
		}
		
		if(empty($_POST['serial_no']))
		{
			$out['Serial_no'] = 'Please enter the serial number';
		}
		
		if(empty($_POST['certifitace_no']))
		{
			$out['Certificate_no'] = 'Please enter certificate no';
		}
		
		if(empty($_POST['place_issued']))
		{
			$out['Place_issued'] = 'Please enter place issued certificate';
		}

		if(empty($_POST['held_by']))
		{
			$out['Held_by'] = 'Please enter certification held by';
		}

		if(empty($_POST['held_at']))
		{
			$out['Held_at'] = 'Please enter place certification held';
		}

		if(empty($_POST['from_date']))
		{
			$out['From_date'] = 'Please enter certificate date';
		}

		if(empty($_POST['to_date']))
		{
			$out['To_date'] = 'Please enter certificate expired date';
		}
		
		return $out;
	}
	
	private function _processStcwImage($address_book_id,$stcw_current,$stcw_base64,$stcw_id)
	{
		$filename = 'none';
		
		//decode
        $data = $stcw_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($stcw_current)
		{
			//delete the current stcw image
			$address_book_common->deleteAddressBookFile($stcw_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'stcw',0,$stcw_id);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in stcw for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'stcw',0,$stcw_id);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in stcw for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
	
}
?>