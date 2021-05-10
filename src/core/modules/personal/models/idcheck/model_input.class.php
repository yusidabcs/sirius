<?php
namespace core\modules\personal\models\idcheck;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 20 January 2018
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'idcheck';
	
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
			
			//set up idcheck information
			$idcheck = array(
				'idcheck_id' => $_POST['idcheck_id'],
				'countryCode_id' => $_POST['countryCode_id'],
				'institution' => $_POST['institution'],
				'website' => $_POST['website'],
				'email' => $_POST['email'],
				'phone' => $_POST['phone'],
				'type' => $_POST['type'],
				'fit' => $_POST['fit'],
				'idcheck_date' => $_POST['idcheck_date'],
				'idcheck_number' => $_POST['idcheck_number'],
				'idcheck_expiry' => $_POST['idcheck_expiry'],
				'filename' => isset($_POST['idcheck_current']) ? $_POST['idcheck_current'] : '',
				'idcheck_from' => $_POST['idcheck_date'],
				'idcheck_to' => $_POST['idcheck_expiry']
			);
			
			//information for the form
			$this->addInput('idcheck',$idcheck);

		} else { //no errors so process
		
			//insert or update the image	
			if(!empty($_POST['idcheck_base64']))
			{
				$idcheck_current = empty($_POST['idcheck_current']) ? false : $_POST['idcheck_current'];
				$filename = $this->_processIdcheckImage($personal_id,$idcheck_current,$_POST['idcheck_base64']);
			} else {
				$filename = empty($_POST['idcheck_current']) ? '' : $_POST['idcheck_current'];
			}
			
			$idcheck_id = strtoupper($_POST['idcheck_id']);
			$address_book_id = $personal_id;
			$countryCode_id = $_POST['countryCode_id'];
			$institution = trim($_POST['institution']);
			$website = trim($_POST['website']);
			$email = trim($_POST['email']);
			$phone = trim($_POST['phone']);
			$type = $_POST['type'];
			$fit = $_POST['fit'];
			$idcheck_date = empty($_POST['idcheck_date']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['idcheck_date']));
			$idcheck_number = trim($_POST['idcheck_number']);
			$idcheck_expiry = empty($_POST['idcheck_expiry']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['idcheck_expiry']));

			//insert or update the idcheck information
			$personal_db = new \core\modules\personal\models\common\db;
			$personal_db->putIdcheck($idcheck_id,$address_book_id,$institution,$countryCode_id,$website,$email,$phone,$type,$idcheck_date,$idcheck_number,$idcheck_expiry,$filename);
			
			if($_POST['next'] == 'home')
			{
				$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'ids';
			} else {
				$this->redirect = $this->baseURL.'/idcheck/new';
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

		if(empty($_POST['website']))
		{
			$out['Website'] = 'You must enter an issuing website for this idcheck';
		}

		if(empty($_POST['email']))
		{
			$out['Email'] = 'You must enter an issuing email for this idcheck';
		}

		if(empty($_POST['phone']))
		{
			$out['Phone'] = 'You must enter an issuing phone for this idcheck';
		}

		if(empty($_POST['countryCode_id']))
		{
			$out['Country'] = 'You must enter an issuing country for this idcheck';
		}
		
		if(empty($_POST['type']))
		{
			$out['Type'] = 'You must say what type of idcheck you had';
		}
		
		if(empty($_POST['idcheck_date']) )
		{
			$out['Idcheck Date'] = 'You must say what the idcheck date is';
		}
		
		if(empty($_POST['idcheck_number']) )
		{
			$out['Idcheck Number'] = 'You must say what the idcheck number is';
		}
		
		return $out;
	}
	
	private function _processIdcheckImage($address_book_id,$idcheck_current,$idcheck_base64)
	{
		$filename = 'none';
		
		//decode
        $data = $idcheck_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($idcheck_current)
		{
			//delete the current idcheck image
			$address_book_common->deleteAddressBookFile($idcheck_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFileRev($filename,$address_book_id,'idcheck',0,$idcheck_current);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in idcheck for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'idcheck',0);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in idcheck for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
	
}
?>