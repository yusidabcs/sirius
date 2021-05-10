<?php
namespace iow\app\classes\widget_base;

/**
 * Abstract widget_model class.
 * 
 * @abstract
 * @package 	widget
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 November 2014
 */
abstract class widget_model {

	protected $widget_name;	//set by the widget!
	protected $view_variables_obj; //set here
	
	private $_widget_dir;
	private $_widget_namespace;

	public function __construct()
	{	
		//define the models directory
		$this->_widget_dir = SITE_WIDGET_DIR.'/'.$this->widget_name.'/models';
		$this->_widget_namespace = SITE_WIDGET_NAMESPACE.'\\'.$this->widget_name.'\models';
		
		//set up to handle view Variables
		$this->_setViewVariablesObject();
		
		//check the input
		$this->_checkPost();
		
		//run the main function
		$this->main();
		
		return;
	}
	
	abstract protected function main();
	
	/**
	 * setViewVariablesObject function.
	 * 
	 * @access private
	 * @return void
	 */
	private function _setViewVariablesObject()
	{
		//we need to interact with view variables
		$this->view_variables_obj = \iow\app\classes\view\view_variables::getInstance();
		return;
	}
	
	/**
	 * _checkPost function.
	 * 
	 * It will checks if Post is not empty and runs the processPost function which is
	 * constituted in the actual widget class.
	 *
	 * @access private
	 * @return void
	 */
	private function _checkPost()
	{
		if(!empty($_POST) && is_set($_POST['widget'][$this->widget_name]))
		{
			$inputClassName = $this->widget_name . '_widget_model_input';
			$theInputClass = $this->_widget_namespace.'\\'.$inputClassName;
			if(!$this->input_obj = @new $theInputClass())
			{
				$msg = "The {$this->widget_name} widget can not load the model input object!";
				throw new \RuntimeException($msg);
			}
		}
		return;
	}
	
}
?>