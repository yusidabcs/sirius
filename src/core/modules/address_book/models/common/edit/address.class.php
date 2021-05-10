<?php
Namespace core\modules\address_book\models\common\edit;

final class address extends content
{
	//this name
	protected $contentName = 'address';
	protected $address_book_id = ''; //the address book id for this object
	
	//protected variables
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array('useSweetAlert'); //array for view_variables_obj->$viewSwitch()
	
	protected $checkedOK = false;
	
	protected $contentValue = array();
	
	protected function setContent()
	{
		if(!empty($_POST[$this->contentName]))
		{
			//fix this if same is not ticked
			if(empty($_POST[$this->contentName]['postal']['same'])) $_POST[$this->contentName]['postal']['same'] = 0;
			
			$this->contentValue = $_POST[$this->contentName];
		} else {
			
			$currentDataArray = $this->address_book_db->getAddressBookAddressDetails($this->address_book_id);
			
			if(empty($currentDataArray))
			{
				//main address
				$this->contentValue['main']['country'] = ADDRESS_BOOK_DEFAULT_COUNTRY_CODE;
				$this->contentValue['main']['physical_pobox'] = 'physical';
				$this->contentValue['main']['care_of'] = '';
				$this->contentValue['main']['line_1'] = '';
				$this->contentValue['main']['line_2'] = '';
				$this->contentValue['main']['suburb'] = '';
				$this->contentValue['main']['state'] = '';
				$this->contentValue['main']['postcode'] = '';
				$this->contentValue['main']['latitude'] = '';
				$this->contentValue['main']['longitude'] = '';
				
				//second address (if visable or not)
				$this->contentValue['postal']['same'] = 1;
				$this->contentValue['postal']['country'] = ADDRESS_BOOK_DEFAULT_COUNTRY_CODE;
				$this->contentValue['postal']['physical_pobox'] = 'physical';
				$this->contentValue['postal']['care_of'] = '';
				$this->contentValue['postal']['line_1'] = '';
				$this->contentValue['postal']['line_2'] = '';
				$this->contentValue['postal']['suburb'] = '';
				$this->contentValue['postal']['state'] = '';
				$this->contentValue['postal']['postcode'] = '';
				$this->contentValue['postal']['latitude'] = '';
				$this->contentValue['postal']['longitude'] = '';
				
			} else if(empty($currentDataArray['postal'])) {
				
				//main address
				$this->contentValue['main'] = $currentDataArray['main'];
				
				//second address (if visable or not)
				$this->contentValue['postal']['same'] = 1;
				$this->contentValue['postal']['country'] = $currentDataArray['main']['country'];
				$this->contentValue['postal']['physical_pobox'] = 'physical';
				$this->contentValue['postal']['care_of'] = '';
				$this->contentValue['postal']['line_1'] = '';
				$this->contentValue['postal']['line_2'] = '';
				$this->contentValue['postal']['suburb'] = '';
				$this->contentValue['postal']['state'] = '';
				$this->contentValue['postal']['postcode'] = '';
				$this->contentValue['postal']['latitude'] = '';
				$this->contentValue['postal']['longitude'] = '';
				
			} else {
				
				//main address
				$this->contentValue['main'] = $currentDataArray['main'];
				
				//second address (if visable or not)
				$this->contentValue['postal'] = $currentDataArray['postal'];
				$this->contentValue['postal']['same'] = 0;
			}
			
		}
		
		return;
	}
	
	public function setVariablesArray()
	{
		$out = array();
			
		//set the information
		$out['address'] = $this->contentValue;
		
		//countries
		$core_db = new \core\app\classes\core_db\core_db;
		$countries = $core_db->getAllCountryCodes();
		$countrySubCodes_1 = $core_db->getSubCountryCodes($out['address']['main']['country']);
		
		if(isset($out['address']['postal']['country']))
		{
			$countrySubCodes_2 = $core_db->getSubCountryCodes($out['address']['postal']['country']);
		} else {
			$countrySubCodes_2 = $countrySubCodes_1;
		}
		
		$out['countries'] = $countries;
		$out['countrySubCodes_1'] = $countrySubCodes_1;
		$out['countrySubCodes_2'] = $countrySubCodes_2;
			
		$this->viewVariables = $out;
		
		return;
	}
	
	public function checkVariables()
	{
		//reset the variables the same was ticked
		if( !empty($this->contentValue['postal']['same']) )
		{
			$this->contentValue['postal']['country'] = ADDRESS_BOOK_DEFAULT_COUNTRY_CODE;
			$this->contentValue['postal']['physical_pobox'] = 'physical';
			$this->contentValue['postal']['care_of'] = '';
			$this->contentValue['postal']['line_1'] = '';
			$this->contentValue['postal']['line_2'] = '';
			$this->contentValue['postal']['suburb'] = '';
			$this->contentValue['postal']['state'] = '';
			$this->contentValue['postal']['postcode'] = '';
			$this->contentValue['postal']['latitude'] = '';
			$this->contentValue['postal']['longitude'] = '';
		}
		
		$errors = array();
		
		if(empty($errors))
		{
			$this->checkedOK = true;
		}
		
		return $errors;
	}
	
	public function updateInfo()
	{
		if( !$this->checkedOK )
		{
			$msg = 'You must check the data before you attempt to update it!';
			throw new \RuntimeException($msg);
		}
		if(!empty($this->contentValue['main']['line_1']))
		{
			//Input the first address
			$affected_rows = $this->address_book_db->updateAddressBookAddress($this->address_book_id,'main',$this->contentValue['main']['physical_pobox'],$this->contentValue['main']['care_of'],$this->contentValue['main']['line_1'],$this->contentValue['main']['line_2'],$this->contentValue['main']['suburb'],$this->contentValue['main']['state'],$this->contentValue['main']['postcode'],$this->contentValue['main']['country']);
				
			if($affected_rows == 0)
			{
				$msg = "There was a major issue with updateInfo in address main for address id {$this->address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
			if($this->contentValue['main']['physical_pobox'] == 'physical' && !empty($this->contentValue['main']['latitude']) && !empty($this->contentValue['main']['longitude']))
			{
				$affected_rows = $this->address_book_db->insertAddressBookCoordinates($this->address_book_id,'main',$this->contentValue['main']['latitude'],$this->contentValue['main']['longitude']);
					
				if($affected_rows == 0)
				{
					$msg = "There was a major issue with updateInfo Coordinates in address main for address id {$this->address_book_id}. Affected was {$affected_rows}";
					throw new \RuntimeException($msg);
				}

			} else {
				$this->address_book_db->deleteAddressBookCoordinates($this->address_book_id,'main');
			}
		}
		
		if(empty($this->contentValue['postal']['same']) && !empty($this->contentValue['postal']['line_1']))
		{
			//Input the first address
			$affected_rows = $this->address_book_db->updateAddressBookAddress($this->address_book_id,'postal',$this->contentValue['postal']['physical_pobox'],$this->contentValue['postal']['care_of'],$this->contentValue['postal']['line_1'],$this->contentValue['postal']['line_2'],$this->contentValue['postal']['suburb'],$this->contentValue['postal']['state'],$this->contentValue['postal']['postcode'],$this->contentValue['postal']['country']);
				
			if($affected_rows == 0)
			{
				$msg = "There was a major issue with updateInfo in address postal for address id {$this->address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
			if($this->contentValue['postal']['physical_pobox'] == 'physical' && !empty($this->contentValue['postal']['latitude']) && !empty($this->contentValue['postal']['longitude']))
			{
				$affected_rows = $this->address_book_db->insertAddressBookCoordinates($this->address_book_id,'postal',$this->contentValue['postal']['latitude'],$this->contentValue['postal']['longitude']);
					
				if($affected_rows == 0)
				{
					$msg = "There was a major issue with updateInfo Coordinates in address postal for address id {$this->address_book_id}. Affected was {$affected_rows}";
					throw new \RuntimeException($msg);
				}

			} else {
				$this->address_book_db->deleteAddressBookCoordinates($this->address_book_id,'postal');
			}
			
		}
		
		return;
	}
	
}

?>