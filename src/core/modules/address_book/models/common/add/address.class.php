<?php
Namespace core\modules\address_book\models\common\add;

final class address extends content
{
	//this name
	protected $contentName = 'address';
	
	//protected variables
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array('useSweetAlert'); //array for view_variables_obj->$viewSwitch()
	
	protected $checkedOK = false;
	
	protected $contentValue = array();
	
	
	public function setVariablesArray()
	{
		$out = array();
		
		if(empty($this->contentValue))
		{
			//main address
			$out['address']['main']['country'] = ADDRESS_BOOK_DEFAULT_COUNTRY_CODE;
			$out['address']['main']['physical_pobox'] = 'physical';
			$out['address']['main']['care_of'] = '';
			$out['address']['main']['line_1'] = '';
			$out['address']['main']['line_2'] = '';
			$out['address']['main']['suburb'] = '';
			$out['address']['main']['state'] = '';
			$out['address']['main']['postcode'] = '';
			$out['address']['main']['latitude'] = '';
			$out['address']['main']['longitude'] = '';
			
			//second address (if visable or not)
			$out['address']['postal']['same'] = 1;
			$out['address']['postal']['country'] = ADDRESS_BOOK_DEFAULT_COUNTRY_CODE;
			$out['address']['postal']['physical_pobox'] = 'physical';
			$out['address']['postal']['care_of'] = '';
			$out['address']['postal']['line_1'] = '';
			$out['address']['postal']['line_2'] = '';
			$out['address']['postal']['suburb'] = '';
			$out['address']['postal']['state'] = '';
			$out['address']['postal']['postcode'] = '';
			$out['address']['postal']['latitude'] = '';
			$out['address']['postal']['longitude'] = '';
			
		} else {
			
			//main address
			$out['address']['main']['country'] = isset($this->contentValue['main']['country']) ? $this->contentValue['main']['country'] : ADDRESS_BOOK_DEFAULT_COUNTRY_CODE;
			$out['address']['main']['physical_pobox'] = isset($this->contentValue['main']['physical_pobox']) ? $this->contentValue['main']['physical_pobox'] : 'physical';
			$out['address']['main']['care_of'] = isset($this->contentValue['main']['care_of']) ? $this->contentValue['main']['care_of'] : '';
			$out['address']['main']['line_1'] = isset($this->contentValue['main']['line_1']) ? $this->contentValue['main']['line_1'] : '';
			$out['address']['main']['line_2'] = isset($this->contentValue['main']['line_2']) ? $this->contentValue['main']['line_2'] : '';
			$out['address']['main']['suburb'] = isset($this->contentValue['main']['suburb']) ? $this->contentValue['main']['suburb'] : '';
			$out['address']['main']['state'] = isset($this->contentValue['main']['state']) ? $this->contentValue['main']['state'] : '';
			$out['address']['main']['postcode'] = isset($this->contentValue['main']['postcode']) ? $this->contentValue['main']['postcode'] : '';
			$out['address']['main']['latitude'] = isset($this->contentValue['main']['latitude']) ? $this->contentValue['main']['latitude'] : '';
			$out['address']['main']['longitude'] = isset($this->contentValue['main']['longitude']) ? $this->contentValue['main']['longitude'] : '';
			
			//second address (if visable or not)
			$out['address']['postal']['same'] = isset($this->contentValue['postal']['same']) ? $this->contentValue['postal']['same'] : 0;
			$out['address']['postal']['country'] = isset($this->contentValue['postal']['country']) ? $this->contentValue['postal']['country'] : ADDRESS_BOOK_DEFAULT_COUNTRY_CODE;
			$out['address']['postal']['physical_pobox'] = isset($this->contentValue['postal']['physical_pobox']) ? $this->contentValue['postal']['physical_pobox'] : 'physical';
			$out['address']['postal']['care_of'] = isset($this->contentValue['postal']['care_of']) ? $this->contentValue['postal']['care_of'] : '';
			$out['address']['postal']['line_1'] = isset($this->contentValue['postal']['line_1']) ? $this->contentValue['postal']['line_1'] : '';
			$out['address']['postal']['line_2'] = isset($this->contentValue['postal']['line_2']) ? $this->contentValue['postal']['line_2'] : '';
			$out['address']['postal']['suburb'] = isset($this->contentValue['postal']['suburb']) ? $this->contentValue['postal']['suburb'] : '';
			$out['address']['postal']['state'] = isset($this->contentValue['postal']['state']) ? $this->contentValue['postal']['state'] : '';
			$out['address']['postal']['postcode'] = isset($this->contentValue['postal']['postcode']) ? $this->contentValue['postal']['postcode'] : '';
			$out['address']['postal']['latitude'] = isset($this->contentValue['postal']['latitude']) ? $this->contentValue['postal']['latitude'] : '';
			$out['address']['postal']['longitude'] = isset($this->contentValue['postal']['longitude']) ? $this->contentValue['postal']['longitude'] : '';
		}

		//countries
		$core_db = new \core\app\classes\core_db\core_db;
		$countries = $core_db->getAllCountryCodes();
		$countrySubCodes_1 = $core_db->getSubCountryCodes($out['address']['main']['country']);
		$countrySubCodes_2 = $core_db->getSubCountryCodes($out['address']['postal']['country']);
		
		$out['countries'] = $countries;
		$out['countrySubCodes_1'] = $countrySubCodes_1;
		$out['countrySubCodes_2'] = $countrySubCodes_2;
			
		$this->viewVariables = $out;
		return;
	}
	
	public function checkVariables()
	{
		if(empty($this->contentValue))
		{
			$msg = 'You can not check something that has no content values at all! ('.$this->contentName.')';
			throw new \RuntimeException($msg);
		}
		
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

	public function addInfo($address_book_id)
	{
		if( !$this->checkedOK )
		{
			$msg = 'You must check the data before you attempt to add it!';
			throw new \RuntimeException($msg);
		}
		
		if(!empty($this->contentValue['main']['line_1']))
		{
			//Input the first address
			$affected_rows = $this->address_book_db->insertAddressBookAddress($address_book_id,'main',$this->contentValue['main']['physical_pobox'],$this->contentValue['main']['care_of'],$this->contentValue['main']['line_1'],$this->contentValue['main']['line_2'],$this->contentValue['main']['suburb'],$this->contentValue['main']['state'],$this->contentValue['main']['postcode'],$this->contentValue['main']['country']);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in address main for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
			if($this->contentValue['main']['physical_pobox'] == 'physical' && !empty($this->contentValue['main']['latitude']) && !empty($this->contentValue['main']['longitude']))
			{
				$affected_rows = $this->address_book_db->insertAddressBookCoordinates($address_book_id,'main',$this->contentValue['main']['latitude'],$this->contentValue['main']['longitude']);
					
				if($affected_rows != 1)
				{
					$msg = "There was a major issue with addInfo Coordinates in address main for address id {$address_book_id}. Affected was {$affected_rows}";
					throw new \RuntimeException($msg);
				}

			}
		}
		
		if(empty($this->contentValue['postal']['same']) && !empty($this->contentValue['postal']['line_1']))
		{
			//Input the first address
			$affected_rows = $this->address_book_db->insertAddressBookAddress($address_book_id,'postal',$this->contentValue['postal']['physical_pobox'],$this->contentValue['postal']['care_of'],$this->contentValue['postal']['line_1'],$this->contentValue['postal']['line_2'],$this->contentValue['postal']['suburb'],$this->contentValue['postal']['state'],$this->contentValue['postal']['postcode'],$this->contentValue['postal']['country']);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in address postal for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
			if($this->contentValue['postal']['physical_pobox'] == 'physical' && !empty($this->contentValue['postal']['latitude']) && !empty($this->contentValue['postal']['longitude']))
			{
				$affected_rows = $this->address_book_db->insertAddressBookCoordinates($address_book_id,'postal',$this->contentValue['postal']['latitude'],$this->contentValue['postal']['longitude']);
					
				if($affected_rows != 1)
				{
					$msg = "There was a major issue with addInfo Coordinates in address postal for address id {$address_book_id}. Affected was {$affected_rows}";
					throw new \RuntimeException($msg);
				}

			}
			
		}
		
		return;
	}
	
}

?>