<?php
namespace core\modules\personal\models\english;

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

	protected $model_name = 'english';
	
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
		$_POST['breakdown'] = [];
		foreach($_POST['breakdown_name'] as $key => $value){
			$_POST['breakdown'][$value] = $_POST['score'][$key];
		}
		if(!empty($error_a))
		{
			//load up the errors
			foreach($error_a as $key => $value)
			{
				$this->addError($key,$value);
			}
			
			//set up english information
			$english = array(
				'english_id' => $_POST['english_id'],
				'type' => $_POST['type'],
				'overall' => $_POST['overall'],
				'breakdown' => $_POST['breakdown'],
				'when' => $_POST['when'],
				'where' => $_POST['where'],
				'filename' => isset($_POST['english_current']) ? $_POST['english_current'] : ''
			);
			//information for the form
			$this->addInput('english',$english);

		} else { //no errors so process
			//we need an english id so if it is empty make a blank one (chicken-egg)
			$personal_db = new \core\modules\personal\models\common\db;

            //check if there english workflow
			$workflow_db = new \core\modules\workflow\models\common\db;
            $workflow = $workflow_db->getActiveWorkflow('workflow_english_test_tracker','address_book_id', $_SESSION['personal']['address_book_id']);
            if($workflow){
                $workflow = $workflow_db->updateTrackers('workflow_english_test_tracker', $_SESSION['personal']['address_book_id'], [
                    'uploaded_file_on' => date('Y-m-d H:i:s'),
                    'uploaded_file_by' => $_SESSION['user_id'],
					'status' => 'review_file',
					'level' => 1,
                    'notes' => 'file has been uploaded from candidate, waiting for review'
                ]);

                if ($workflow !== 1) {
                    $msg = "Error updating workflow ${workflow}";
                    throw new \RuntimeException($msg);
                }

            }

			$english_id = $_POST['english_id'];
			$address_book_id = $personal_id;
			if(empty($english_id))
			{
				$english_id = $personal_db->insertEnglish($address_book_id);
				if($english_id < 1)
				{
					$msg = "There was a major issue with addInfo inserting english for address id {$address_book_id}. ID was {$english_id}";
					throw new \RuntimeException($msg);
				}
			}
			
			//insert or update the image	
			if(!empty($_POST['english_base64']))
			{
				$english_current = empty($_POST['english_current']) ? false : $_POST['english_current'];
				$filename = $this->_processEnglishImage($personal_id,$english_current,$_POST['english_base64'],$english_id);
			} else {
				$filename = empty($_POST['english_current']) ? '' : $_POST['english_current'];
			}
			
			$type = $_POST['type'];
			$overall = $_POST['overall'];
			$breakdown = $_POST['breakdown'];
			$when = date('Y-m-d',strtotime($_POST['when']));
			$where = $_POST['where'];
			
			//update the english information
			$personal_db->updateEnglish($type,$overall,$breakdown,$when,$where,$filename,$english_id);
			
			if($_POST['next'] == 'home')
			{
				$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'documents/english';
			} else {
				$this->redirect = $this->baseURL.'/english/new';
			}
		}
		
		return;
	}
	
	private function _checkData()
	{
		$out = array();
		
		if(empty($_POST['overall']))
		{
			$out['Overall'] = 'Please give us the overall score';
		}
		
		if(empty($_POST['breakdown_name']))
		{
			$out['Breakdown'] = 'Please enter the score breakdown';
		}
		
		if(empty($_POST['when']))
		{
			$out['When'] = 'Please specify the date you did the test';
		}
		
		if(empty($_POST['where']))
		{
			$out['Where'] = 'Please say where you did the test';
		}
		
		return $out;
	}
	
	private function _processEnglishImage($address_book_id,$english_current,$english_base64,$english_id)
	{
		$filename = 'none';
		
		//decode
        $data = $english_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($english_current)
		{
			//delete the current english image
			$address_book_common->deleteAddressBookFile($english_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'english',0,$english_id);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in english for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'english',0,$english_id);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in english for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
	
}
?>