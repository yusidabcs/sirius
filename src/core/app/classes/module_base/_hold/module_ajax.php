<?php
namespace iow\app\classes\module_base;
 
/**
 * Abstract  module_admin class.
 * 
 * Is the base class for all module menu delete options
 *
 * @abstract
 * @package 	module_setup
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 21 January 2015
 */

abstract class module_ajax {
	
	abstract public function run();
	
	protected $optionRequired = false; //we must have an option to work
	protected $page_options = array(); //the file options from the url (GET)
	protected $option = ''; //the first file option
	
	protected $unsafePostRequired = false; //we need the unsanitized post to work with
	protected $unsafe_post = '';
	
	protected $errors = array(); //an array of the errors
	
	protected $system_register; //we should have access to the regsiter
	
	protected $catchaRequired = false; //normally we don't need the catcha
	
	protected $catchaOK = false; //normally catcha is false before processing
	
	public function __construct($fileOptions,$unsafePost)
	{
		//need system register
		$this->system_register = \iow\app\classes\system_register\system_register::getInstance();
		
		//process the catcha
		$this->_processCATCHA();
		
		//load up the page_options (if any)
		$this->page_options = $fileOptions;
		
		//set the first option (most ajax only uses one option)
		$this->option = isset($fileOptions[0]) ? $fileOptions[0] : '';
		if($this->optionRequired)
		{
			$this->_checkOption();
		}
		
		if($this->unsafePostRequired)
		{
			$this->setUnsafePost($unsafePost);
		}
		
		return;
	}
	
	public function setUnsafePost($unsafePost)
	{
		$this->unsafe_post = $unsafePost;
		return;
	}
	
	private function _processCATCHA()
	{
		if($this->catchaRequired)
		{	
			if(isset($_SESSION['Md5_GenCode_CATCHA']))
			{
				$submitedCatcha = isset($_POST['catchaAnswer']) ? $_POST['catchaAnswer'] : 'It can not be blank';
			
				$catcha = md5($submitedCatcha);
				
				if( $catcha != $_SESSION['Md5_GenCode_CATCHA'] )
				{
					$this->addError($this->system_register->site_term('CATCHA_ERROR_TITLE'),$this->system_register->site_term('CATCHA_ERROR_ERROR'));
					$this->catchaOK = false;
				} else {
					$this->catchaOK = true;
				}

			} else {
				//To stop people submitting from an external point where the catcha is required
				// and also to stop idiots "reloading" forms and having them submit
				header("Location: /");
				exit();
			}
		} else {
			unset($_SESSION['Md5_GenCode_CATCHA']);
			$this->catchaOK = true;
		}
		
		return;
	}
		
	private function _checkOption()
	{
		if( empty($this->option) )
		{
			die('Hmmm .. that does not look right ... not an option in sight!');
		}
		return;
	}
	
	protected function addError($name,$error)
	{
		$this->errors[$name] = $error;
	}
		
}
?>