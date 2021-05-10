<?php
Namespace core\modules\address_book\models\common\view;

final class avatar extends content
{
	//this name
	protected $contentName = 'avatar';
	
	//protected variables
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array(); //array for view_variables_obj->$viewSwitch()
	
	protected $contentValue = array();
	
	public function setVariablesArray()
	{
		$out = array();
			
		//set the information
		$out['avatar'] = $this->contentValue;
		
		$this->viewVariables = $out;
		
		return;
	}
		
}

?>