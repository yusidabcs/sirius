<?php
Namespace core\modules\address_book\models\common\view;

final class pots extends content
{
	//this name
	protected $contentName = 'pots';
	
	//protected variables
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array(); //array for view_variables_obj->$viewSwitch()
	
	protected $contentValue = array();
	
	public function setVariablesArray()
	{
		$out = array();
			
		//set the information
		$out['pots'] = $this->contentValue;
		
		//change country code to dial code
		//countries
		$core_db = new \core\app\classes\core_db\core_db;
		$country_code = $core_db->getAllDialCodes();
		
		foreach( $out['pots'] as $key => $value )
		{
			$out['pots'][$key]['dialInfo'] = $country_code[$value['country']];
		}
		
		$this->viewVariables = $out;
		
		return;
	}
		
}

?>