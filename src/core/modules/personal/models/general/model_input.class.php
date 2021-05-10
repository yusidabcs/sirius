<?php
namespace core\modules\personal\models\general;

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

	protected $model_name = 'general';
	
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
		
			/**
			
			BMI = 730 x lbs / in2
			BMI = kg / m2
			
			so cm need to convert to m
			
			BMI Table for Adults

			This is the World Health Organization's (WHO) recommended body weight based on BMI values for adults. It is used for both men and women, age 18 or older. 	
			
			Category	BMI range - kg/m2
			Severe Thinness	< 16
			Moderate Thinness	16 - 17
			Mild Thinness	17 - 18.5
			Normal	18.5 - 25
			Overweight	25 - 30
			Obese Class I	30 - 35
			Obese Class II	35 - 40
			Obese Class III	> 40			
		**/
		
		//calculate bmi
		switch ($_POST['height_weight']) 
		{
		    case 'me':
		    
		    	if(empty($_POST['height_cm']) || empty($_POST['weight_kg']))
		    	{
			    	$height_cm = 0;
			        $weight_kg = 0;
			        $height_in = 0;
			        $weight_lb = 0;
			        $bmi = '';
		        
		    	} else {
			    	
			        $height_cm = round($_POST['height_cm'],2);
			        $weight_kg = round($_POST['weight_kg'],2);
			        $height_in = '';
			        $weight_lb = '';
			        
			        $bmi = round($weight_kg/pow(($height_cm/100),2),2);
			        
		        }
		        
		        break;
		        
		    case 'im':
		    
		    	if(empty($_POST['height_in']) || empty($_POST['weight_lb']))
		    	{
			    	$height_cm = 0;
			        $weight_kg = 0;
			        $height_in = 0;
			        $weight_lb = 0;
			        $bmi = '';
			        
		    	} else {
		    	
			        $height_cm = '';
			        $weight_kg = '';
			        $height_in = round($_POST['height_in'],2);
			        $weight_lb = round($_POST['weight_lb'],2);
			        
			        $bmi = round(703*$weight_lb/pow($height_in,2),2);
		        
		        }
		        break;
		        
		    default:
		    	$height_cm = 0;
		        $weight_kg = 0;
		        $height_in = 0;
		        $weight_lb = 0;
		        $bmi = 0;
		}

		if(!empty($error_a))
		{
			//load up the errors
			foreach($error_a as $key => $value)
			{
				$this->addError($key,$value);
			}
			
			//set up general information
			$general = array(
				'height_cm' => $height_cm,
				'weight_kg' => $weight_kg,
				'height_in' => $height_in,
				'weight_lb' => $weight_lb,
				'bmi' => $bmi,
				'tattoo' => $_POST['tattoo'],
				'relationship' => $_POST['relationship'],
				'employment' => $_POST['employment'],
				'job_hunting' => $_POST['job_hunting'],
				'seafarer' => $_POST['seafarer'],
				'migration' => $_POST['migration'],
				'country_born' => $_POST['country_born'],
				'country_residence' => $_POST['country_residence'],
				'passport' => $_POST['passport'],
				'travelled_overseas' => $_POST['travelled_overseas'],
				'nok_family_name' => $_POST['nok_family_name'],
				'nok_given_names' => $_POST['nok_given_names'],
				'nok_relationship' => $_POST['nok_relationship'],
				'nok_line_1' => $_POST['nok_line_1'],
				'nok_line_2' => $_POST['nok_line_2'],
				'nok_line_3' => $_POST['nok_line_3'],
				'nok_country' => $_POST['nok_country'],
				'nok_number_type' => $_POST['nok_number_type'],
				'nok_number' => $_POST['nok_number'],
				'nok_email' => $_POST['nok_email'],
				'nok_skype' => $_POST['nok_skype'],
				'filename' => $_POST['filename']
			);
			
			//information for the form
			$this->addInput('general',$general);

		} else { //no errors so process
		
			//insert or update the image	
			if(!empty($_POST['general_base64']))
			{
				$general_current = empty($_POST['general_current']) ? false : $_POST['general_current'];
				$filename = $this->_processGeneralImage($personal_id,$general_current,$_POST['general_base64']);
			} else {
				$filename = empty($_POST['general_current']) ? '' : $_POST['general_current'];
			}

			if(!empty($_POST['signature_base64']))
			{
				$signature_current = empty($_POST['signature_current']) ? false : $_POST['signature_current'];
				$signature_filename = $this->_processSignatureImage($personal_id,$signature_current,$_POST['signature_base64']);
			} else {
				$signature_filename = empty($_POST['signature_current']) ? '' : $_POST['signature_current'];
			}
			
			$height_cm = $height_cm;
			$weight_kg = $weight_kg;
			$height_in = $height_in;
			$weight_lb = $weight_lb;
			$bmi = $bmi;
			$tattoo = $_POST['tattoo'];
			$relationship = $_POST['relationship'];
			$children = $_POST['children'];
			$employment = $_POST['employment'];
			$job_hunting = $_POST['job_hunting'];
			$seafarer = $_POST['seafarer'];
			$migration = $_POST['migration'];
			$country_born = $_POST['country_born'];
			$country_residence = $_POST['country_residence'];
			$passport = $_POST['passport'];
			$nok_family_name = $_POST['nok_family_name'];
			$nok_given_names = $_POST['nok_given_names'];
			$nok_relationship = $_POST['nok_relationship'];
			$nok_line_1 = $_POST['nok_line_1'];
			$nok_line_2 = $_POST['nok_line_2'];
			$nok_line_3 = $_POST['nok_line_3'];
			$nok_country = $_POST['nok_country'];
			$nok_number_type = $_POST['nok_number_type'];
			$nok_number = $_POST['nok_number'];
			$nok_email = $_POST['nok_email'];
			$nok_skype = $_POST['nok_skype'];
			$travelled_overseas = $_POST['travelled_overseas'];
						
			//insert or update the general information
			$height_in = $height_in==''?0:$height_in;
			$weight_lb = $weight_lb==''?0:$weight_lb;
			$personal_db = new \core\modules\personal\models\common\db;
			$affected_rows = $personal_db->putGeneral($personal_id,$height_cm,$weight_kg,$height_in,$weight_lb,$bmi,$tattoo,$relationship,$children,$employment,$job_hunting,$seafarer,$migration,$country_born,$country_residence,$passport,$travelled_overseas,$nok_family_name,$nok_given_names,$nok_relationship,$nok_line_1,$nok_line_2,$nok_line_3,$nok_country,$nok_number_type,$nok_number,$nok_email,$nok_skype,$filename,$signature_filename);
			if($affected_rows == 0)
			{
				$msg = "There was a major issue with putGeneral in personal DB, Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			$this->redirect = $this->baseURL.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? '/home/'.$_SESSION['personal']['address_book_id'] : '');
		}
		
		return;
	}
	
	private function _checkData()
	{
		$out = array();
		
		if(empty($_POST['employment']))
		{
			$out['Employment'] = 'Please say your current employment status is';
		}
		
		if(empty($_POST['seafarer']))
		{
			$out['Seafarer'] = 'Please say if you want to work at sea or not';
		}
		
		if(empty($_POST['migration']))
		{
			$out['Migration'] = 'Please say if you are currently interested to migrate to Australia or not';
		}
		
		if(empty($_POST['passport']))
		{
			$out['Passport'] = 'Please say if you have a passport or not';
		}

		
		if(empty($_POST['relationship']))
		{
			$out['Relationship Status'] = 'Please say what your current relationship status is';
		}
		
		if(empty($_POST['children']))
		{
			$out['Children'] = 'Please say if you have children or not';
		}
		
		if(empty($_POST['tattoo']))
		{
			$out['Tattoo'] = 'Please say if you have a tattoo or not';
		}
				
		if(empty($_POST['travelled_overseas']))
		{
			$out['Travelled Overseas'] = 'Please say if you have travelled overseas or not';
		}

		if(empty($_POST['job_hunting']))
		{
			$out['Job Hunting'] = 'Please say if you are looking for a new job or not';
		}

		if(empty($_POST['country_born']))
		{
			$out['Country of Birth'] = 'Please choose country of birth';
		}

		if(empty($_POST['country_residence']))
		{
			$out['Country Residence'] = 'Please choose country of residence';
		}
		
		return $out;
	}
	
	private function _processGeneralImage($address_book_id,$general_current,$general_base64)
	{
		$filename = 'none';
		
		//decode
        $data = $general_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($general_current)
		{
			//delete the current general image
			$address_book_common->deleteAddressBookFile($general_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'general',0);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in general for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'general',0);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in general for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}

	private function _processSignatureImage($address_book_id,$signature_current,$signature_base64)
	{
		$filename = 'none';
		
		//decode
        $data = $signature_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($signature_current)
		{
			//delete the current signature image
			$address_book_common->deleteAddressBookFile($signature_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'signature',0);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in signature for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'signature',0);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in signature for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
}
?>