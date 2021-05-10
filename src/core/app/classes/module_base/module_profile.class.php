<?php
namespace core\app\classes\module_base;
 
/**
 * Abstract module_profile class.
 * 
 * Is the base class for all module setup.
 *
 * @abstract
 * @package 	module_base
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 22 August 2019
 */

abstract class module_profile {
	
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array(); //array for view_variables_obj->$key($value) to switch to true
	protected $viewJs = array();

	public function __construct()
	{
		return;
	}
	
	protected function setViewVariables($variableName,$value)
	{
		$this->viewVariables[$variableName] = $value;
		return;
	}
	
	protected function setViewSwitches($switchName,$version)
	{
		$this->viewSwitches[$switchName] = $version;
		return;
	}

    protected function setViewJs($js)
    {
        $this->viewJs[] = $js;
        return;
    }
	
	public function getViewVariables()
	{
		return $this->viewVariables;
	}
	
	public function getViewSwitches()
	{
		return $this->viewSwitches;
	}

    public function getViewJs()
    {
        return $this->viewJs;
    }
	
}
?>