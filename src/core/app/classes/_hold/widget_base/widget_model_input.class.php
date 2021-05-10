<?php
namespace iow\app\classes\widget_base;

/**
 * Abstract widget_model_input class.
 * 
 * @abstract
 * @package 	widget_router
 *@author		Martin O'Dee<martin@iow.com.au>
 *@copyright	Martin O'Dee 11 October 2014
 */
abstract class widget_model_input {
	
	protected $widget_id; //set by the input class itself
	protected $input = array(); //an array of the inputed data
	protected $errors = array(); //an array of the errors
	
	public function __construct()
	{	
		//double check that Post is empty
		if(empty($_POST['']))
		{
			$msg = "The {$this->widget_id} widget input was called but POST was empty!";
			throw new \RuntimeException($msg);
		} else {
			$this->processPost();
		}
		
		return;
	}
	
	protected function addInput($name,$values)
	{
		$this->input[$name] = $values;
	}
	
	protected function addError($name,$error)
	{
		$this->input[$name] = $error;
	}
	
	public function getInput($name)
	{
		if(isset($this->input[$name]))
		{
			return $this->input[$name];
		}
		return;
	}
	
	public function hasErrors()
	{
		if(empty($this->errors))
		{
			return false;
		}
		return true;
	}
	
	public function getErrors()
	{
		return $this->errors;
	}
	
	abstract protected function processPost();
	
}
?>