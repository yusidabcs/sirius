<?php
namespace iow\app\classes\module_base;
 
/**
 * Abstract module_profile class.
 * 
 * Is the base class for all module setup.
 *
 * @abstract
 * @package 	module_base
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 23 July 2017
 */

abstract class module_profile {
	
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array(); //array for view_variables_obj->$key($value) to switch to true
	
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
	
	public function getViewVariables()
	{
		return $this->viewVariables;
	}
	
	public function getViewSwitches()
	{
		return $this->viewSwitches;
	}
	
}
?>