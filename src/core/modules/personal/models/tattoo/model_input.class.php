<?php
namespace core\modules\personal\models\tattoo;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 January 2018
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'tattoo';
	
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
		if(empty($_POST['concealable'])) $_POST['concealable'] = '';
		
		//process the inputs
		$error_a = $this->_checkData();
		
		if(!empty($error_a))
		{
			//load up the errors
			foreach($error_a as $key => $value)
			{
				$this->addError($key,$value);
			}
			
			//set up tattoo information
			$tattoo = array(
				'tattoo_id' => $_POST['tattoo_id'],
				'location' => $_POST['location'],
				'short_description' => $_POST['short_description'],
				'concealable' => $_POST['concealable'],
				'filename' => isset($_POST['tattoo_current']) ? $_POST['tattoo_current'] : ''
			);
			
			//information for the form
			$this->addInput('tattoo',$tattoo);

		} else { //no errors so process
		
			//insert or update the image	
			if(!empty($_POST['tattoo_base64']))
			{
				$tattoo_current = empty($_POST['tattoo_current']) ? false : $_POST['tattoo_current'];
				$filename = $this->_processTattooImage($personal_id,$tattoo_current,$_POST['tattoo_base64']);
			} else {
				$filename = empty($_POST['tattoo_current']) ? '' : $_POST['tattoo_current'];
			}
			
			$tattoo_id = $_POST['tattoo_id'];
			$address_book_id = $personal_id;
			$location = $_POST['location'];
			$short_description = trim($_POST['short_description']);
			$concealable = $_POST['concealable'];
			
			//insert or update the tattoo information
			$personal_db = new \core\modules\personal\models\common\db;
			$personal_db->putTattoo($tattoo_id,$address_book_id,$location,$short_description,$concealable,$filename);
			
			if($_POST['next'] == 'home')
			{
				$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'tat';
			} else {
				$this->redirect = $this->baseURL.'/tattoo/new';
			}
			
		}
		
		return;
	}
	
	private function _checkData()
	{
		$out = array();
		
		if(empty($_POST['location']))
		{
			
			$out['Location'] = 'Please specify a location';
			
		} else {
			
			if($_POST['location'] == 'other')
			{
				
				$_POST['concealable'] = 'yes';
				$_POST['tattoo_base64'] = '';
										
			} else {
				
				if(empty($_POST['short_description']))
				{
					$out['Short Description'] = 'Please give a short description of the tattoo';
				}
	
			}
			
		}
					
		if(empty($_POST['concealable']))
		{
			$out['Coverable'] = 'Please say if the tattoo can be covered or not';
		}
		
		return $out;
	}
	
	private function _processTattooImage($address_book_id,$tattoo_current,$tattoo_base64)
	{
		$filename = 'none';
		
		//decode
        $data = $tattoo_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($tattoo_current)
		{
			//delete the current tattoo image
			$address_book_common->deleteAddressBookFile($tattoo_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFileRev($filename,$address_book_id,'tattoo',0,$tattoo_current);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in tattoo for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'tattoo',0);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in tattoo for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
	
}
?>