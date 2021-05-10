<?php
namespace core\modules\personal\models\flight;

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

	protected $model_name = 'flight';
	
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
		$_POST['flight_number'] = $generic->safeLinkId($_POST['flight_number']);
		
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
			
			//set up flight information
			$flight = array(
				'flight_number' => $_POST['flight_nubmer'],
				'departure_date' => $_POST['departure_date_submit'],
				'filename' => $_POST['file_current']
			);
			
			//information for the form
			$this->addInput('flight',$flight);

		} else { //no errors so process
			//insert or update the image	
			if(!empty($_POST['flight_base64']))
			{
				$flight_current = empty($_POST['flight_current']) ? false : $_POST['flight_current'];
				$filename = $this->_processFlightImage($personal_id,$flight_current,$_POST['flight_base64'],$_POST['flight_number']);
			} else {
				$filename = empty($_POST['flight_current']) ? '' : $_POST['flight_current'];
			}
			
			$flight_number = strtoupper($_POST['flight_number']);
			$address_book_id = $personal_id;
			$departure_date = $_POST['departure_date_submit'];
			

			if ($this->system_register->getModuleIsInstalled('workflow')) {
				$workflow_db = new \core\modules\workflow\models\common\db;

				if ($workflow_db->getActiveWorkflow('workflow_flight_tracker', 'address_book_id', $address_book_id)) {
					$workflow = $workflow_db->updateTrackers('workflow_flight_tracker', $address_book_id, [
						'file_uploaded_on' => date('Y-m-d'),
						'file_uploaded_by' => $_SESSION['user_id'],
						'filename' => $filename,
						'notes' => 'Flight document has been uploaded, waiting for review',
						'status' => 'review_file'
					]);

					if ($workflow !== 1) {
						throw new \RuntimeException("Error updating workflow " . $workflow);
					}
				}
			}
			
			//insert or update the flight information
			$personal_db = new \core\modules\personal\models\common\db;
			$personal_db->putFlight($address_book_id, $flight_number,$filename,$departure_date);
			
			if($_POST['next'] == 'home')
			{
				$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'passp';
			} else {
				$this->redirect = $this->baseURL.'/flight/new';
			}
			
		}
		
		return;
	}
	
	private function _checkData()
	{
		$out = array();
		
		if(empty($_POST['departure_date']))
		{
			$out['Country'] = 'You must enter an issuing country for this flight';
		}
		
		if(empty($_POST['flight_number']))
		{
			$out['Flight Number'] = 'You must enter a flight number';
		}
		
		return $out;
	}
	
	private function _processFlightImage($address_book_id,$flight_current,$flight_base64,$flight_id)
	{
		$filename = 'none';
		
		//decode
        $data = $flight_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($flight_current)
		{
			//delete the current flight image
			$address_book_common->deleteAddressBookFile($flight_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'flight',0,$flight_id);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in flight for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'flight',0,$flight_id);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in flight for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
	
}
?>