<?php
Namespace core\modules\address_book\models\common\view;

final class address extends content
{
	//this name
	protected $contentName = 'address';
	
	//protected variables
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array('useSweetAlert'); //array for view_variables_obj->$viewSwitch()
	
	protected $contentValue = array();
	
	public function setVariablesArray()
	{
		$out = array();
			
		//set the information
		$out['address'] = $this->contentValue;
				
		//countries
		$core_db = new \core\app\classes\core_db\core_db;
		$countries = $core_db->getAllCountryCodes();
		
		foreach($out['address'] as $type => $value)
		{
			$out['address'][$type]['country_full'] = $countries[$value['country']];
			
			$countrySubCodes = $core_db->getSubCountryCodes($out['address']['main']['country']);
			
			if(isset($countrySubCodes[$value['state']]))
			{
				$out['address'][$type]['state_full'] = $countrySubCodes[$value['state']];
			} else {
				$out['address'][$type]['state_full'] = '';
			}
		}

				
		$this->viewVariables = $out;
		
		return;
	}


		
}

?>